<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Models\Pesan;
use App\Models\BalasanPesan;
use App\Models\PesanSematan;
use App\Models\Mahasiswa;
use App\Models\Dosen;
use Carbon\Carbon;

class PesanDosenController extends Controller
{
    /**
     * Menampilkan dashboard pesan dosen
     */
    public function index()
    {
        $dosen = Auth::user();
        
        // Membuat subquery untuk mendapatkan waktu balasan terakhir
        $latestReplies = BalasanPesan::selectRaw('id_pesan, MAX(created_at) as latest_reply_at')
            ->groupBy('id_pesan');
        
        // Mengambil semua pesan yang diterima ATAU dikirim oleh dosen
        // hanya yang statusnya masih 'Aktif'
        $pesan = Pesan::where(function($query) use ($dosen) {
                $query->where('nip_penerima', $dosen->nip)
                    ->orWhere('nip_pengirim', $dosen->nip);
            })
            ->where('status', 'Aktif') // Hanya tampilkan pesan aktif di dashboard
            ->leftJoinSub($latestReplies, 'latest_replies', function ($join) {
                $join->on('pesan.id', '=', 'latest_replies.id_pesan');
            })
            ->select('pesan.*', DB::raw('IFNULL(latest_replies.latest_reply_at, pesan.created_at) as last_activity'))
            ->orderBy('last_activity', 'desc') // Urutkan berdasarkan aktivitas terakhir
            ->get();
                      
        // Menghitung jumlah pesan belum dibaca (hanya yang diterima)
        $belumDibaca = Pesan::where('nip_penerima', $dosen->nip)
                        ->where('dibaca', false)
                        ->count();
        
        // Menghitung jumlah pesan aktif (baik yang diterima maupun dikirim)
        $pesanAktif = Pesan::where(function($query) use ($dosen) {
                         $query->where('nip_penerima', $dosen->nip)
                               ->orWhere('nip_pengirim', $dosen->nip);
                       })
                       ->where('status', 'Aktif')
                       ->count();
        
        // Menghitung total pesan (termasuk aktif dan berakhir)
        $totalPesan = Pesan::where(function($query) use ($dosen) {
                         $query->where('nip_penerima', $dosen->nip)
                               ->orWhere('nip_pengirim', $dosen->nip);
                     })
                     ->count();
        
        return view('pesan.dosen.dashboardpesandosen', compact('pesan', 'belumDibaca', 'pesanAktif', 'totalPesan'));
    }
    
    /**
     * Menampilkan form buat pesan baru (dosen ke mahasiswa)
     */
    public function create()
    {
        // Ambil daftar mahasiswa untuk dipilih sebagai penerima
        $mahasiswa = Mahasiswa::all();
        
        return view('pesan.dosen.buatpesandosen', compact('mahasiswa'));
    }
    
    /**
     * Menyimpan pesan baru dari dosen ke mahasiswa (bisa lebih dari satu)
     */
    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'subjek' => 'required',
            'anggota' => 'required|array',
            'anggota.*' => 'exists:mahasiswas,nim',
            'prioritas' => 'required|in:Penting,Umum',
            'pesanText' => 'required'
        ]);
        
        try {
            // Ambil dosen yang sedang login
            $dosen = Auth::user();
            
            // Tambahkan log untuk debugging
            Log::info('Mengirim pesan dari dosen', [
                'nip_dosen' => $dosen->nip,
                'nama_dosen' => $dosen->nama,
                'jumlah_penerima' => count($request->anggota)
            ]);
            
            // Array untuk menyimpan ID pesan yang dibuat
            $pesanIds = [];
            
            // Kirim pesan ke setiap mahasiswa yang dipilih
            foreach($request->anggota as $nim) {
                $pesan = new Pesan();
                $pesan->subjek = $request->subjek;
                $pesan->nip_pengirim = $dosen->nip;
                $pesan->nim_penerima = $nim;
                $pesan->isi_pesan = $request->pesanText;
                $pesan->prioritas = $request->prioritas;
                $pesan->lampiran = $request->has('lampiran') ? $request->lampiran : null;
                $pesan->status = 'Aktif';
                $pesan->dibaca = false;
                $pesan->bookmarked = false; // Pastikan nilai default untuk bookmark
                
                // Force timezone untuk timestamp
                $pesan->created_at = Carbon::now('Asia/Jakarta');
                $pesan->updated_at = Carbon::now('Asia/Jakarta');
                
                $pesan->save();
                
                // Log pesan yang berhasil disimpan
                Log::info('Pesan berhasil dibuat', [
                    'id' => $pesan->id,
                    'nip_pengirim' => $pesan->nip_pengirim,
                    'nim_penerima' => $pesan->nim_penerima
                ]);
                
                $pesanIds[] = $pesan->id;
            }
            
            return response()->json([
                'success' => true,
                'message' => 'Pesan berhasil dikirim ke ' . count($request->anggota) . ' mahasiswa',
                'pesan_ids' => $pesanIds
            ]);
        } catch (\Exception $e) {
            Log::error('Error saat mengirim pesan: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengirim pesan: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Menampilkan detail pesan
     */
    public function show($id)
    {
        try {
            $pesan = Pesan::findOrFail($id);
            $dosen = Auth::user();
            
            // Pastikan dosen yang melihat adalah penerima ATAU pengirim pesan
            if ($pesan->nip_penerima != $dosen->nip && $pesan->nip_pengirim != $dosen->nip) {
                return redirect()->route('dosen.dashboard.pesan')
                    ->with('error', 'Anda tidak memiliki akses ke pesan ini');
            }
            
            // Load relasi balasan secara manual
            $balasan = BalasanPesan::where('id_pesan', $pesan->id)->get();
            $pesan->setRelation('balasan', $balasan);
            
            // Log jumlah balasan untuk debug
            Log::info('Balasan count for message ' . $id . ': ' . $balasan->count());
            
            // Cek balasan yang belum dibaca
            $unreadBalasan = $balasan->filter(function($item) {
                return !$item->dibaca && $item->tipe_pengirim == 'mahasiswa';
            })->count();
            
            Log::info('Unread balasan count: ' . $unreadBalasan);
            
            // Load semua pengirim balasan (dosen dan mahasiswa) secara manual
            foreach ($pesan->balasan as $balasan) {
                if ($balasan->tipe_pengirim == 'dosen') {
                    $dosenPengirim = Dosen::find($balasan->pengirim_id);
                    $balasan->pengirimData = $dosenPengirim;
                } else {
                    $mahasiswaPengirim = Mahasiswa::find($balasan->pengirim_id);
                    $balasan->pengirimData = $mahasiswaPengirim;
                }
            }
            
            // Kelompokkan balasan berdasarkan tanggal
            $balasanByDate = [];
            
            // Tambahkan pesan awal ke grup balasan berdasarkan tanggal
            $dateAwal = Carbon::parse($pesan->created_at)->format('Y-m-d');
            $balasanByDate[$dateAwal][] = $pesan;
            
            // Tambahkan semua balasan ke grup berdasarkan tanggal
            foreach ($pesan->balasan as $balasan) {
                $date = Carbon::parse($balasan->created_at)->format('Y-m-d');
                if (!isset($balasanByDate[$date])) {
                    $balasanByDate[$date] = [];
                }
                $balasanByDate[$date][] = $balasan;
            }
            
            // Urutkan tanggal (dari paling lama)
            ksort($balasanByDate);
            
            // Update status menjadi dibaca jika belum dibaca dan dosen adalah penerima
            if (!$pesan->dibaca && $pesan->nip_penerima == $dosen->nip) {
                $pesan->dibaca = true;
                $pesan->save();
                
                Log::info('Marked message as read: ' . $pesan->id);
            }
            
            // PERBAIKAN: Update status balasan yang belum dibaca (hanya balasan dari mahasiswa)
            // Gunakan direct query untuk update balasan yang belum dibaca
            $updatedCount = BalasanPesan::where('id_pesan', $pesan->id)
                ->where('tipe_pengirim', 'mahasiswa')
                ->where('dibaca', false)
                ->update(['dibaca' => true]);
                
            Log::info('Updated ' . $updatedCount . ' unread replies to read');
            
            return view('pesan.dosen.isipesandosen', compact('pesan', 'balasanByDate'));
        } catch (\Exception $e) {
            Log::error('Error menampilkan detail pesan: ' . $e->getMessage() . ' | ' . $e->getTraceAsString());
            
            return redirect()->route('dosen.dashboard.pesan')
                ->with('error', 'Terjadi kesalahan saat menampilkan detail pesan');
        }
    }
    
    /**
     * Mengirim balasan pesan
     */
    public function reply(Request $request, $id)
    {
        $request->validate([
            'balasan' => 'required'
        ]);
        
        $pesan = Pesan::findOrFail($id);
        $dosen = Auth::user();
        
        // Pastikan dosen yang membalas adalah penerima ATAU pengirim pesan
        if ($pesan->nip_penerima != $dosen->nip && $pesan->nip_pengirim != $dosen->nip) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak memiliki akses ke pesan ini'
            ], 403);
        }
        
        // Pastikan pesan masih aktif
        if ($pesan->status == 'Berakhir') {
            return response()->json([
                'success' => false,
                'message' => 'Pesan telah diakhiri'
            ], 400);
        }
        
        try {
            // Buat balasan baru dengan nilai dibaca yang jelas
            $balasan = new BalasanPesan();
            $balasan->id_pesan = $id;
            $balasan->pengirim_id = $dosen->nip;
            $balasan->tipe_pengirim = 'dosen';
            $balasan->isi_balasan = $request->balasan;
            $balasan->dibaca = false; // PERBAIKAN: Gunakan false (boolean) untuk konsistensi
            
            // Force timezone untuk timestamp
            $balasan->created_at = Carbon::now('Asia/Jakarta');
            $balasan->updated_at = Carbon::now('Asia/Jakarta');
            
            $balasan->save();
            
            // Log informasi
            Log::info('Balasan dosen berhasil dibuat', [
                'id' => $balasan->id,
                'pengirim_id' => $balasan->pengirim_id,
                'tipe_pengirim' => $balasan->tipe_pengirim,
                'dibaca' => $balasan->dibaca
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Balasan berhasil dikirim',
                'data' => [
                    'id' => $balasan->id,
                    'isi_balasan' => $balasan->isi_balasan,
                    'created_at' => Carbon::parse($balasan->created_at)->timezone('Asia/Jakarta')->format('H:i')
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Error saat mengirim balasan: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengirim balasan: ' . $e->getMessage()
            ], 500);
        }
    }
        
    /**
     * Mengakhiri pesan (dosen juga dapat mengakhiri pesan)
     */
    public function endChat($id)
    {
        try {
            $pesan = Pesan::findOrFail($id);
            $dosen = Auth::user();
            
            // Pastikan dosen yang mengakhiri adalah pengirim ATAU penerima pesan
            if ($pesan->nip_penerima != $dosen->nip && $pesan->nip_pengirim != $dosen->nip) {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda tidak memiliki akses ke pesan ini'
                ], 403);
            }
            
            // Pastikan pesan masih aktif
            if ($pesan->status == 'Berakhir') {
                return response()->json([
                    'success' => false,
                    'message' => 'Pesan sudah diakhiri sebelumnya'
                ], 400);
            }
            
            // Update status pesan
            $pesan->status = 'Berakhir';
            $pesan->save();
            
            // Log informasi
            Log::info('Pesan diakhiri oleh dosen', [
                'id_pesan' => $pesan->id,
                'nip_dosen' => $dosen->nip
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Pesan berhasil diakhiri'
            ]);
        } catch (\Exception $e) {
            Log::error('Error saat mengakhiri pesan: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengakhiri pesan: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Fungsi untuk membookmark pesan
     */
    public function bookmark(Request $request, $id)
    {
        try {
            $pesan = Pesan::findOrFail($id);
            $dosen = Auth::user();
            
            // Pastikan dosen yang membookmark adalah penerima ATAU pengirim pesan
            if ($pesan->nip_penerima != $dosen->nip && $pesan->nip_pengirim != $dosen->nip) {
                return redirect()->back()->with('error', 'Anda tidak memiliki akses ke pesan ini');
            }
            
            // Toggle status bookmark
            $pesan->bookmarked = !$pesan->bookmarked;
            $pesan->save();
            
            return redirect()->back()->with('success', 
                $pesan->bookmarked ? 'Pesan berhasil ditandai' : 'Tanda pada pesan berhasil dihapus');
            
        } catch (\Exception $e) {
            Log::error('Error saat bookmark pesan: ' . $e->getMessage());
            
            return redirect()->back()->with('error', 'Gagal menandai pesan');
        }
    }
    
    /**
     * Menampilkan riwayat pesan
     */
    public function history()
    {
        $dosen = Auth::user();
        
        // Membuat subquery untuk mendapatkan waktu balasan terakhir
        $latestReplies = BalasanPesan::selectRaw('id_pesan, MAX(created_at) as latest_reply_at')
            ->groupBy('id_pesan');
        
        // Mengambil semua pesan dengan status 'Berakhir' yang diterima ATAU dikirim oleh dosen
        $riwayatPesan = Pesan::where(function($query) use ($dosen) {
                           $query->where('nip_penerima', $dosen->nip)
                                 ->orWhere('nip_pengirim', $dosen->nip);
                       })
                       ->where('status', 'Berakhir')
                       ->leftJoinSub($latestReplies, 'latest_replies', function ($join) {
                            $join->on('pesan.id', '=', 'latest_replies.id_pesan');
                       })
                       ->select('pesan.*', DB::raw('IFNULL(latest_replies.latest_reply_at, pesan.updated_at) as last_activity'))
                       ->orderBy('last_activity', 'desc') // Urutkan berdasarkan aktivitas terakhir
                       ->get();
                         
        return view('pesan.dosen.riwayatpesandosen', compact('riwayatPesan'));
    }
    
    /**
     * Filter pesan berdasarkan prioritas
     */
    public function filter(Request $request)
    {
        $dosen = Auth::user();
        $filter = $request->filter;
        
        // Membuat subquery untuk mendapatkan waktu balasan terakhir
        $latestReplies = BalasanPesan::selectRaw('id_pesan, MAX(created_at) as latest_reply_at')
            ->groupBy('id_pesan');
        
        $query = Pesan::where(function($query) use ($dosen) {
                    $query->where('nip_penerima', $dosen->nip)
                          ->orWhere('nip_pengirim', $dosen->nip);
                });
        
        // Filter berdasarkan prioritas
        if ($filter == 'penting') {
            $query->where('prioritas', 'Penting');
        } elseif ($filter == 'umum') {
            $query->where('prioritas', 'Umum');
        }
        
        // Filter hanya pesan aktif untuk dashboard utama
        if (!$request->has('include_ended') || !$request->include_ended) {
            $query->where('status', 'Aktif');
        }
        
        // Gabungkan dengan subquery balasan terakhir
        $pesan = $query->leftJoinSub($latestReplies, 'latest_replies', function ($join) {
                    $join->on('pesan.id', '=', 'latest_replies.id_pesan');
                })
                ->select('pesan.*', DB::raw('IFNULL(latest_replies.latest_reply_at, pesan.created_at) as last_activity'))
                ->orderBy('last_activity', 'desc') // Urutkan berdasarkan aktivitas terakhir
                ->get();
        
        return response()->json([
            'success' => true,
            'html' => view('pesan.dosen.partials.pesan_list', compact('pesan'))->render()
        ]);
    }
    
    /**
     * Pencarian pesan
     */
    public function search(Request $request)
    {
        $dosen = Auth::user();
        $keyword = $request->keyword;
        
        // Membuat subquery untuk mendapatkan waktu balasan terakhir
        $latestReplies = BalasanPesan::selectRaw('id_pesan, MAX(created_at) as latest_reply_at')
            ->groupBy('id_pesan');
        
        $query = Pesan::where(function($query) use ($dosen) {
                    $query->where('nip_penerima', $dosen->nip)
                          ->orWhere('nip_pengirim', $dosen->nip);
                })
                ->where(function($query) use ($keyword) {
                    $query->where('subjek', 'like', "%{$keyword}%")
                          ->orWhere('isi_pesan', 'like', "%{$keyword}%")
                          ->orWhereHas('pengirim', function($q) use ($keyword) {
                              $q->where('nama', 'like', "%{$keyword}%");
                          })
                          ->orWhereHas('penerima', function($q) use ($keyword) {
                              $q->where('nama', 'like', "%{$keyword}%");
                          });
                });
                
        // Filter berdasarkan status jika parameter diberikan
        if ($request->has('status')) {
            $query->where('status', $request->status);
        } else {
            // Default hanya tampilkan pesan aktif
            $query->where('status', 'Aktif');
        }
        
        // Gabungkan dengan subquery balasan terakhir
        $pesan = $query->leftJoinSub($latestReplies, 'latest_replies', function ($join) {
                    $join->on('pesan.id', '=', 'latest_replies.id_pesan');
                })
                ->select('pesan.*', DB::raw('IFNULL(latest_replies.latest_reply_at, pesan.created_at) as last_activity'))
                ->orderBy('last_activity', 'desc') // Urutkan berdasarkan aktivitas terakhir
                ->get();
        
        return response()->json([
            'success' => true,
            'html' => view('pesan.dosen.partials.pesan_list', compact('pesan'))->render()
        ]);
    }
    
    /**
     * Halaman FAQ untuk dosen
     */
    public function faq()
    {
        $dosen = Auth::user();
        
        // Ambil sematan yang masih aktif
        $sematan = PesanSematan::where('aktif', true)
                            ->where('durasi_sematan', '>', now())
                            ->orderBy('created_at', 'desc')
                            ->get();
        
        // Kelompokkan sematan berdasarkan kategori
        $sematanByKategori = [
            'krs' => [],
            'kp' => [],
            'skripsi' => [],
            'mbkm' => []
        ];
        
        foreach ($sematan as $item) {
            $sematanByKategori[$item->kategori][] = $item;
        }
        
        return view('pesan.dosen.faq_dosen', [
            'sematan' => $sematan,
            'sematanByKategori' => $sematanByKategori
        ]);
    }
    
    /**
     * Menyematkan pesan atau balasan ke FAQ
     */
    public function sematkan(Request $request, $id)
    {
        try {
            $dosen = Auth::user();
            $pesan = Pesan::findOrFail($id);
            
            // Validasi data request
            $request->validate([
                'message_ids' => 'required|array',
                'message_ids.*' => 'required|string',
                'kategori' => 'required|in:krs,kp,skripsi,mbkm',
                'judul' => 'required|string|max:255',
                'durasi' => 'required|integer|min:1|max:1000' // dalam jam
            ]);
            
           // Pastikan konversi ke integer sebelum digunakan
            $durasi = (int) $request->durasi;
            $durasiSematan = Carbon::now()->addHours($request->durasi);
            
            // Array untuk menyimpan ID sematan yang dibuat
            $sematanIds = [];
            
            // Proses sematan untuk setiap pesan
            foreach ($request->message_ids as $messageId) {
                // Format message_id: 'message-123' atau 'reply-456'
                $parts = explode('-', $messageId);
                $type = $parts[0]; // 'message' atau 'reply'
                $id = $parts[1]; // ID pesan atau balasan
                
                if ($type === 'message') {
                    // Sematan untuk pesan utama
                    $pesanToPin = Pesan::findOrFail($id);
                    
                    // Pastikan pesan ini terkait dengan dosen
                    if ($pesanToPin->nip_pengirim != $dosen->nip && $pesanToPin->nip_penerima != $dosen->nip) {
                        continue; // Skip jika bukan pesan dosen
                    }
                    
                    // Buat sematan
                    $sematan = PesanSematan::create([
                        'nip_dosen' => $dosen->nip,
                        'jenis_pesan' => 'pesan',
                        'pesan_id' => $id,
                        'balasan_id' => null,
                        'isi_sematan' => $pesanToPin->isi_pesan,
                        'kategori' => $request->kategori,
                        'judul' => $request->judul,
                        'aktif' => true,
                        'durasi_sematan' => $durasiSematan
                    ]);
                    
                    $sematanIds[] = $sematan->id;
                } else if ($type === 'reply') {
                    // Sematan untuk balasan
                    $balasanToPin = BalasanPesan::findOrFail($id);
                    
                    // Pastikan balasan ini terkait dengan dosen
                    if ($balasanToPin->tipe_pengirim === 'dosen' && $balasanToPin->pengirim_id != $dosen->nip) {
                        continue; // Skip jika bukan balasan dari dosen ini
                    }
                    
                    // Buat sematan
                    $sematan = PesanSematan::create([
                        'nip_dosen' => $dosen->nip,
                        'jenis_pesan' => 'balasan',
                        'pesan_id' => null,
                        'balasan_id' => $id,
                        'isi_sematan' => $balasanToPin->isi_balasan,
                        'kategori' => $request->kategori,
                        'judul' => $request->judul,
                        'aktif' => true,
                        'durasi_sematan' => $durasiSematan
                    ]);
                    
                    $sematanIds[] = $sematan->id;
                }
            }
            
            return response()->json([
                'success' => true,
                'message' => count($sematanIds) . ' pesan berhasil disematkan',
                'sematan_ids' => $sematanIds
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error saat menyematkan pesan: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Gagal menyematkan pesan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Batalkan sematan pesan
     */
    public function batalkanSematan($id)
    {
        try {
            $dosen = Auth::user();
            $sematan = PesanSematan::where('id', $id)
                                   ->where('nip_dosen', $dosen->nip)
                                   ->firstOrFail();
            
            // Nonaktifkan sematan
            $sematan->aktif = false;
            $sematan->save();
            
            return response()->json([
                'success' => true,
                'message' => 'Sematan berhasil dibatalkan'
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error saat membatalkan sematan: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Gagal membatalkan sematan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Mendapatkan daftar sematan untuk FAQ
     */
    public function getSematan(Request $request)
    {
        try {
            $dosen = Auth::user();
            
            // Filter berdasarkan kategori jika ada
            $kategori = $request->input('kategori');
            
            $query = PesanSematan::where('nip_dosen', $dosen->nip)
                                ->where('aktif', true)
                                ->where('durasi_sematan', '>', now());
            
            if ($kategori && $kategori !== 'all') {
                $query->where('kategori', $kategori);
            }
            
            $sematan = $query->orderBy('created_at', 'desc')
                             ->get();
            
            return response()->json([
                'success' => true,
                'data' => $sematan
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error saat mengambil sematan: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil sematan: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Method untuk debugging
     * Gunakan method ini jika masih terjadi error pada pesan
     */
    public function debug($id)
    {
        $pesan = Pesan::findOrFail($id);
        $balasan = BalasanPesan::where('id_pesan', $id)->get();
        
        dd([
            'Pesan' => $pesan->toArray(),
            'Balasan' => $balasan->toArray(),
            'Penerima Exists' => $pesan->penerima ? true : false,
            'Balasan Count' => $balasan->count()
        ]);
    }
}