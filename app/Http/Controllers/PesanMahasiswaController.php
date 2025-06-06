<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB; // Dipastikan import DB sudah ada
use App\Models\Pesan;
use App\Models\BalasanPesan;
use App\Models\PesanSematan;
use App\Models\Dosen;
use App\Models\Mahasiswa;
use Carbon\Carbon;

class PesanMahasiswaController extends Controller
{
    // Menampilkan dashboard pesan mahasiswa
    public function index()
    {
        $mahasiswa = Auth::user();
        
        // Membuat subquery untuk mendapatkan waktu balasan terakhir
        $latestReplies = BalasanPesan::selectRaw('id_pesan, MAX(created_at) as latest_reply_at')
            ->groupBy('id_pesan');
        
        // Mengambil pesan dengan eager loading untuk dosen
        $pesan = Pesan::with(['dosenPengirim', 'dosenPenerima'])
            ->where(function($query) use ($mahasiswa) {
                $query->where('nim_penerima', $mahasiswa->nim)
                    ->orWhere('nim_pengirim', $mahasiswa->nim);
            })
            ->where('status', 'Aktif')
            ->leftJoinSub($latestReplies, 'latest_replies', function ($join) {
                $join->on('pesan.id', '=', 'latest_replies.id_pesan');
            })
            ->select('pesan.*', DB::raw('IFNULL(latest_replies.latest_reply_at, pesan.created_at) as last_activity'))
            ->orderBy('last_activity', 'desc')
            ->get();
        
        // Hitung pesan utama yang belum dibaca
        $belumDibacaUtama = Pesan::where('nim_penerima', $mahasiswa->nim)
                        ->where('dibaca', false)
                        ->count();
        
        // Hitung balasan dari dosen yang belum dibaca
        $belumDibacaBalasan = BalasanPesan::whereIn('id_pesan', function($query) use ($mahasiswa) {
                            $query->select('id')
                                  ->from('pesan')
                                  ->where(function($q) use ($mahasiswa) {
                                      $q->where('nim_penerima', $mahasiswa->nim)
                                        ->orWhere('nim_pengirim', $mahasiswa->nim);
                                  })
                                  ->where('status', 'Aktif');
                        })
                        ->where('tipe_pengirim', 'dosen')
                        ->where('dibaca', false)
                        ->count();
        
        // Total pesan belum dibaca (pesan utama + balasan)
        $belumDibaca = $belumDibacaUtama + $belumDibacaBalasan;
        
        // Code untuk pesanAktif dan totalPesan tetap sama
        $pesanAktif = Pesan::where(function($query) use ($mahasiswa) {
                        $query->where('nim_penerima', $mahasiswa->nim)
                            ->orWhere('nim_pengirim', $mahasiswa->nim);
                    })
                    ->where('status', 'Aktif')
                    ->count();
                    
        $totalPesan = Pesan::where(function($query) use ($mahasiswa) {
                        $query->where('nim_penerima', $mahasiswa->nim)
                            ->orWhere('nim_pengirim', $mahasiswa->nim);
                    })
                    ->count();
        
        return view('pesan.mahasiswa.dashboardpesanmahasiswa', compact('pesan', 'belumDibaca', 'pesanAktif', 'totalPesan'));
    }
    
    // Menampilkan form untuk membuat pesan baru
    public function create()
    {
        // Dapatkan daftar dosen dengan informasi jabatan
        $dosen = Dosen::select('nip', 'nama', 'jabatan_fungsional')
            ->orderBy('nama')
            ->get();
        
        // Duplikasi dosen yang memiliki peran kaprodi
        $dosenWithRoles = [];
        
        foreach ($dosen as $d) {
            // Tambahkan sebagai dosen reguler terlebih dahulu
            $dosenWithRoles[] = [
                'nip' => $d->nip,
                'nama' => $d->nama,
                'jabatan_fungsional' => $d->jabatan_fungsional,
                'role' => 'dosen'
            ];
            
            // Jika dosen adalah kaprodi, tambahkan sekali lagi dengan peran kaprodi
            if (stripos($d->jabatan_fungsional, 'kaprodi') !== false || 
                stripos($d->jabatan_fungsional, 'ketua program') !== false || 
                stripos($d->jabatan_fungsional, 'kepala program') !== false) {
                
                $dosenWithRoles[] = [
                    'nip' => $d->nip,
                    'nama' => $d->nama,
                    'jabatan_fungsional' => $d->jabatan_fungsional,
                    'role' => 'kaprodi'
                ];
            }
        }
        
        // Log jumlah dosen yang diambil untuk debugging
        Log::info('Mengambil daftar dosen untuk form pesan baru: ' . count($dosenWithRoles) . ' dosen ditemukan (termasuk duplikasi untuk kaprodi)');
        
        return view('pesan.mahasiswa.buatpesanmahasiswa', ['dosen' => $dosenWithRoles]);
    }
    
    // Menyimpan pesan baru
    public function store(Request $request)
    {
        // Log semua request data untuk debugging
        Log::info('Request data untuk pesan baru:', $request->all());
        
        // Validasi input
        $validatedData = $request->validate([
            'subjek' => 'required',
            'dosenId' => 'required',
            'prioritas' => 'required',
            'pesanText' => 'required',
            'penerima_role' => 'required|in:dosen,kaprodi'  // Memastikan role divalidasi
        ]);
        
        Log::info('Validasi berhasil dengan data:', $validatedData);
        
        try {
            // Get mahasiswa aktif
            $mahasiswa = Auth::user();
            Log::info('Mahasiswa pengirim:', [
                'nim' => $mahasiswa->nim,
                'nama' => $mahasiswa->nama
            ]);
            
            // Cek dosen yang akan menerima pesan
            $dosen = Dosen::find($request->dosenId);
            if (!$dosen) {
                Log::warning('Dosen dengan NIP ' . $request->dosenId . ' tidak ditemukan');
                return response()->json([
                    'success' => false,
                    'message' => 'Dosen penerima tidak ditemukan'
                ], 404);
            }
            
            Log::info('Dosen penerima ditemukan:', [
                'nip' => $dosen->nip,
                'nama' => $dosen->nama,
                'jabatan' => $dosen->jabatan_fungsional
            ]);
            
            // Buat pesan baru
            $pesan = new Pesan();
            $pesan->subjek = $request->subjek;
            $pesan->nim_pengirim = $mahasiswa->nim;
            $pesan->nip_penerima = $request->dosenId;
            
            // Tentukan penerima_role dari input form
            $pesan->penerima_role = $request->penerima_role;
            
            // Log role yang digunakan
            Log::info('Pesan dibuat dengan penerima role: ' . $pesan->penerima_role);
            
            $pesan->isi_pesan = $request->pesanText;
            $pesan->prioritas = $request->prioritas;
            $pesan->lampiran = $request->lampiran ?? null; // Opsional, gunakan null jika tidak ada
            $pesan->status = 'Aktif';
            $pesan->dibaca = false;
            $pesan->bookmarked = false; // Pastikan nilai default untuk bookmark
            
            // PERBAIKAN: Log sebelum save - Jangan akses created_at yang masih null
            Log::info('Data pesan sebelum disimpan:', [
                'subjek' => $pesan->subjek,
                'nim_pengirim' => $pesan->nim_pengirim,
                'nip_penerima' => $pesan->nip_penerima,
                'penerima_role' => $pesan->penerima_role ?? 'null',
                'isi_pesan' => substr($pesan->isi_pesan, 0, 100), // Hanya tampilkan 100 karakter pertama
                'prioritas' => $pesan->prioritas,
                'lampiran' => $pesan->lampiran
            ]);
            
            // Cek database connection sebelum save
            try {
                DB::connection()->getPdo();
                Log::info('Database connection OK');
            } catch (\Exception $e) {
                Log::error('Database connection error: ' . $e->getMessage());
                throw new \Exception('Tidak dapat terhubung ke database: ' . $e->getMessage());
            }
            
            // Simpan pesan
            $pesan->save();
            
            // Log sukses SETELAH pesan disimpan (created_at sudah ada nilainya)
            Log::info('Pesan berhasil disimpan dengan ID: ' . $pesan->id . ', pada waktu: ' . 
                ($pesan->created_at ? $pesan->created_at->format('Y-m-d H:i:s') : 'N/A'));
            
            return response()->json([
                'success' => true,
                'message' => 'Pesan berhasil dikirim',
                'pesan_id' => $pesan->id
            ]);
        } catch (\Exception $e) {
            // Log error dengan detail
            Log::error('Error saat mengirim pesan:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->all()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengirim pesan: ' . $e->getMessage()
            ], 500);
        }
    }
    
    // Menampilkan detail pesan
    public function show($id)
    {
        try {
            $pesan = Pesan::findOrFail($id);
            $mahasiswa = Auth::user();
            
            // Pastikan mahasiswa yang melihat adalah pengirim ATAU penerima pesan
            if ($pesan->nim_pengirim != $mahasiswa->nim && $pesan->nim_penerima != $mahasiswa->nim) {
                return redirect()->route('mahasiswa.dashboard.pesan')
                    ->with('error', 'Anda tidak memiliki akses ke pesan ini');
            }
            
            // Load relasi balasan secara manual
            $balasan = BalasanPesan::where('id_pesan', $pesan->id)->get();
            $pesan->setRelation('balasan', $balasan);
            
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
            
            // Update status menjadi dibaca jika belum dibaca dan mahasiswa adalah penerima
            if (!$pesan->dibaca && $pesan->nim_penerima == $mahasiswa->nim) {
                $pesan->dibaca = true;
                $pesan->save();
            }
            
            // Update status balasan yang belum dibaca (hanya balasan dari dosen)
            foreach ($pesan->balasan as $balasan) {
                if (!$balasan->dibaca && $balasan->tipe_pengirim == 'dosen') {
                    // Ambil objek balasan dari database (tanpa properti tambahan)
                    $baUpdate = BalasanPesan::find($balasan->id);
                    if ($baUpdate) {
                        $baUpdate->dibaca = true;
                        $baUpdate->save();
                    }
                }
            }
            
            return view('pesan.mahasiswa.isipesanmahasiswa', compact('pesan', 'balasanByDate'));
        } catch (\Exception $e) {
            // Log error dengan lebih detail
            Log::error('Error saat menampilkan detail pesan: ' . $e->getMessage() . ' | ' . $e->getTraceAsString());
            
            return redirect()->route('mahasiswa.dashboard.pesan')
                ->with('error', 'Terjadi kesalahan saat menampilkan detail pesan: ' . $e->getMessage());
        }
    }
      
    /**
     * Mengirim balasan pesan
     */
    public function reply(Request $request, $id)
    {
        // PERBAIKAN: Validasi - balasan tidak wajib jika ada lampiran
        $request->validate([
            'balasan' => 'required_without:lampiran|string', // Wajib jika tidak ada lampiran
            'lampiran' => 'nullable|url' // Lampiran opsional tapi harus berupa URL yang valid
        ]);
        
        $pesan = Pesan::findOrFail($id);
        $mahasiswa = Auth::user();
        
        // Pastikan mahasiswa yang membalas adalah pengirim ATAU penerima pesan
        if ($pesan->nim_pengirim != $mahasiswa->nim && $pesan->nim_penerima != $mahasiswa->nim) {
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
            // Buat balasan baru dengan lampiran
            $balasan = new BalasanPesan();
            $balasan->id_pesan = $id;
            $balasan->pengirim_id = $mahasiswa->nim;
            $balasan->tipe_pengirim = 'mahasiswa';
            
            // PERBAIKAN: Set balasan default jika kosong tapi ada lampiran
            $balasan->isi_balasan = $request->balasan ?: '[Lampiran]';
            $balasan->lampiran = $request->lampiran; // Tambahkan lampiran
            $balasan->dibaca = false;
            
            $balasan->save();
            
            // Log hasil untuk debugging
            Log::info('Balasan mahasiswa berhasil dibuat', [
                'id' => $balasan->id,
                'pengirim_id' => $balasan->pengirim_id,
                'tipe_pengirim' => $balasan->tipe_pengirim,
                'isi_balasan' => $balasan->isi_balasan,
                'dibaca' => $balasan->dibaca,
                'has_attachment' => !empty($balasan->lampiran)
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Balasan berhasil dikirim',
                'data' => [
                    'id' => $balasan->id,
                    'isi_balasan' => $balasan->isi_balasan,
                    'lampiran' => $balasan->lampiran,
                    'created_at' => $balasan->formattedCreatedAt()
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
    
    // Mengakhiri pesan
    public function endChat($id)
    {
        try {
            $pesan = Pesan::findOrFail($id);
            $mahasiswa = Auth::user();
            
            // PERUBAHAN: Pastikan mahasiswa yang mengakhiri adalah PENGIRIM pesan
            if ($pesan->nim_pengirim != $mahasiswa->nim) {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda tidak memiliki akses untuk mengakhiri pesan ini. Hanya pengirim yang dapat mengakhiri pesan.'
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
            Log::info('Pesan diakhiri oleh mahasiswa', [
                'id_pesan' => $pesan->id,
                'nim_mahasiswa' => $mahasiswa->nim
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
    
    // Fungsi untuk bookmark pesan
    public function bookmark(Request $request, $id)
    {
        try {
            $pesan = Pesan::findOrFail($id);
            $mahasiswa = Auth::user();
            
            // Pastikan mahasiswa yang membookmark adalah penerima ATAU pengirim pesan
            if ($pesan->nim_penerima != $mahasiswa->nim && $pesan->nim_pengirim != $mahasiswa->nim) {
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
    
    // Menampilkan riwayat pesan
    public function history()
    {
        $mahasiswa = Auth::user();
        
        // Membuat subquery untuk mendapatkan waktu balasan terakhir
        $latestReplies = BalasanPesan::selectRaw('id_pesan, MAX(created_at) as latest_reply_at')
            ->groupBy('id_pesan');
        
        // Mengambil semua pesan dengan status 'Berakhir' (dikirim ATAU diterima)
        // Tambahkan eager loading untuk dosenPengirim dan dosenPenerima
        $riwayatPesan = Pesan::with(['dosenPengirim', 'dosenPenerima'])
                        ->where(function($query) use ($mahasiswa) {
                            $query->where('nim_pengirim', $mahasiswa->nim)
                                ->orWhere('nim_penerima', $mahasiswa->nim);
                        })
                        ->where('status', 'Berakhir')
                        ->leftJoinSub($latestReplies, 'latest_replies', function ($join) {
                            $join->on('pesan.id', '=', 'latest_replies.id_pesan');
                        })
                        ->select('pesan.*', DB::raw('IFNULL(latest_replies.latest_reply_at, pesan.updated_at) as last_activity'))
                        ->orderBy('last_activity', 'desc') // Urutkan berdasarkan aktivitas terakhir
                        ->get();
                        
        return view('pesan.mahasiswa.riwayatpesanmahasiswa', compact('riwayatPesan'));
    }
    
    // Filter pesan berdasarkan prioritas
    public function filter(Request $request)
    {
        $mahasiswa = Auth::user();
        $filter = $request->filter;
        
        Log::info('Filter request received:', ['filter' => $filter, 'mahasiswa_nim' => $mahasiswa->nim]);
        
        try {
            // Membuat subquery untuk mendapatkan waktu balasan terakhir
            $latestReplies = BalasanPesan::selectRaw('id_pesan, MAX(created_at) as latest_reply_at')
                ->groupBy('id_pesan');
            
            $query = Pesan::with(['dosenPengirim', 'dosenPenerima'])
                ->where(function($query) use ($mahasiswa) {
                    $query->where('nim_pengirim', $mahasiswa->nim)
                        ->orWhere('nim_penerima', $mahasiswa->nim);
                })
                ->where('status', 'Aktif'); // Hanya pesan aktif
            
            // Filter berdasarkan prioritas
            if ($filter == 'penting') {
                $query->where('prioritas', 'Penting');
            } elseif ($filter == 'umum') {
                $query->where('prioritas', 'Umum');
            }
            // Jika filter == 'semua', tidak perlu filter tambahan
            
            // Gabungkan dengan subquery balasan terakhir
            $pesan = $query->leftJoinSub($latestReplies, 'latest_replies', function ($join) {
                        $join->on('pesan.id', '=', 'latest_replies.id_pesan');
                    })
                    ->select('pesan.*', DB::raw('IFNULL(latest_replies.latest_reply_at, pesan.created_at) as last_activity'))
                    ->orderBy('last_activity', 'desc')
                    ->get();
            
            Log::info('Filter results:', [
                'filter' => $filter,
                'total_found' => $pesan->count(),
                'pesan_ids' => $pesan->pluck('id')->toArray()
            ]);
            
            // Generate HTML menggunakan partial
            $html = view('pesan.mahasiswa.partials.pesan_list', compact('pesan'))->render();
            
            return response()->json([
                'success' => true,
                'html' => $html,
                'count' => $pesan->count()
            ]);
            
        } catch (\Exception $e) {
            Log::error('Filter error:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memfilter pesan: ' . $e->getMessage()
            ], 500);
        }
    }

    // Pencarian pesan - PERBAIKAN
    // Pencarian pesan - PERBAIKAN dengan validation keyword
public function search(Request $request)
{
    $mahasiswa = Auth::user();
    $keyword = trim($request->get('keyword', ''));
    
    Log::info('Search request received:', [
        'keyword' => $keyword, 
        'mahasiswa_nim' => $mahasiswa->nim,
        'keyword_length' => strlen($keyword),
        'request_method' => $request->method(),
        'is_ajax' => $request->ajax()
    ]);
    
    try {
        // Jika keyword kosong, redirect ke filter semua
        if (empty($keyword)) {
            Log::info('Empty keyword, redirecting to filter all');
            return $this->filter($request->merge(['filter' => 'semua']));
        }
        
        // Validasi panjang keyword minimal
        if (strlen($keyword) < 2) {
            return response()->json([
                'success' => false,
                'message' => 'Kata kunci pencarian minimal 2 karakter',
                'count' => 0
            ]);
        }
        
        // Membuat subquery untuk mendapatkan waktu balasan terakhir
        $latestReplies = BalasanPesan::selectRaw('id_pesan, MAX(created_at) as latest_reply_at')
            ->groupBy('id_pesan');
        
        $query = Pesan::with(['dosenPengirim', 'dosenPenerima'])
                    ->where(function($query) use ($mahasiswa) {
                        $query->where('nim_pengirim', $mahasiswa->nim)
                              ->orWhere('nim_penerima', $mahasiswa->nim);
                     })
                     ->where('status', 'Aktif'); // Hanya pesan aktif
        
        // Filter pencarian dengan multiple field dan case insensitive
        $query->where(function($searchQuery) use ($keyword) {
            $searchQuery->where('subjek', 'like', "%{$keyword}%")
                        ->orWhere('isi_pesan', 'like', "%{$keyword}%")
                        ->orWhere('prioritas', 'like', "%{$keyword}%")
                        ->orWhereHas('dosenPengirim', function($q) use ($keyword) {
                            $q->where('nama', 'like', "%{$keyword}%")
                              ->orWhere('jabatan_fungsional', 'like', "%{$keyword}%");
                        })
                        ->orWhereHas('dosenPenerima', function($q) use ($keyword) {
                            $q->where('nama', 'like', "%{$keyword}%")
                              ->orWhere('jabatan_fungsional', 'like', "%{$keyword}%");
                        });
        });
        
        // Gabungkan dengan subquery balasan terakhir dan urutkan
        $pesan = $query->leftJoinSub($latestReplies, 'latest_replies', function ($join) {
                    $join->on('pesan.id', '=', 'latest_replies.id_pesan');
                })
                ->select('pesan.*', DB::raw('IFNULL(latest_replies.latest_reply_at, pesan.created_at) as last_activity'))
                ->orderBy('last_activity', 'desc')
                ->limit(50) // Batasi hasil untuk performa
                ->get();
        
        Log::info('Search results:', [
            'keyword' => $keyword,
            'total_found' => $pesan->count(),
            'pesan_ids' => $pesan->pluck('id')->toArray(),
            'execution_time' => microtime(true) - LARAVEL_START
        ]);
        
        // Jika tidak ada hasil
        if ($pesan->isEmpty()) {
            return response()->json([
                'success' => true,
                'html' => '<div class="text-center py-5">
                            <i class="fas fa-search text-muted" style="font-size: 3rem; opacity: 0.3;"></i>
                            <p class="text-muted mt-3">Tidak ada pesan yang ditemukan dengan kata kunci "<strong>' . htmlspecialchars($keyword) . '</strong>"</p>
                            <small class="text-muted">Coba gunakan kata kunci yang berbeda atau lebih umum</small>
                          </div>',
                'count' => 0,
                'keyword' => $keyword
            ]);
        }
        
        // Generate HTML menggunakan partial
        $html = view('pesan.mahasiswa.partials.pesan_list', compact('pesan'))->render();
        
        return response()->json([
            'success' => true,
            'html' => $html,
            'count' => $pesan->count(),
            'keyword' => $keyword,
            'message' => $pesan->count() . ' pesan ditemukan untuk "' . $keyword . '"'
        ]);
        
    } catch (\Exception $e) {
        Log::error('Search error:', [
            'message' => $e->getMessage(),
            'trace' => $e->getTraceAsString(),
            'keyword' => $keyword,
            'mahasiswa_nim' => $mahasiswa->nim,
            'line' => $e->getLine(),
            'file' => $e->getFile()
        ]);
        
        return response()->json([
            'success' => false,
            'message' => 'Terjadi kesalahan saat mencari pesan. Silakan coba lagi.',
            'error_detail' => config('app.debug') ? $e->getMessage() : null
        ], 500);
    }
}
    
   
    
    // Halaman FAQ
    public function faq()
    {
        // Ambil sematan yang masih aktif dari semua dosen
        $sematan = PesanSematan::with('dosen')
                            ->where('aktif', true)
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
        
        // Jika tidak ada sematan, ambil daftar dosen langsung dari tabel dosen
        if ($sematan->isEmpty()) {
            $dosenList = Dosen::orderBy('nama')->get(['nip', 'nama']);
        } else {
            // Ekstrak daftar dosen dari sematan
            $dosenList = $sematan->map(function($item) {
                return $item->dosen;
            })->unique('nip')->sortBy('nama')->values();
        }
        
        return view('pesan.mahasiswa.faq_mahasiswa', [
            'sematan' => $sematan,
            'sematanByKategori' => $sematanByKategori,
            'dosenList' => $dosenList
        ]);
    }
    
    /**
     * Method untuk debugging penghitungan pesan yang belum dibaca
     * Berguna untuk memecahkan masalah penghitungan notifikasi
     */
    public function debugUnreadCount()
    {
        $mahasiswa = Auth::user();
        
        // Hitung pesan utama yang belum dibaca
        $belumDibacaUtama = Pesan::where('nim_penerima', $mahasiswa->nim)
                        ->where('dibaca', false)
                        ->count();
        
        // Ambil list pesan utama yang belum dibaca
        $pesanUtamaBelumDibaca = Pesan::where('nim_penerima', $mahasiswa->nim)
                        ->where('dibaca', false)
                        ->get(['id', 'subjek', 'nim_pengirim', 'nip_pengirim', 'created_at']);
        
        // Hitung balasan dari dosen yang belum dibaca
        $belumDibacaBalasan = BalasanPesan::whereIn('id_pesan', function($query) use ($mahasiswa) {
                            $query->select('id')
                                ->from('pesan')
                                ->where(function($q) use ($mahasiswa) {
                                    $q->where('nim_penerima', $mahasiswa->nim)
                                        ->orWhere('nim_pengirim', $mahasiswa->nim);
                                })
                                ->where('status', 'Aktif');
                        })
                        ->where('tipe_pengirim', 'dosen')
                        ->where('dibaca', false)
                        ->count();
        
        // Ambil list balasan yang belum dibaca
        $balasanBelumDibaca = BalasanPesan::whereIn('id_pesan', function($query) use ($mahasiswa) {
                            $query->select('id')
                                ->from('pesan')
                                ->where(function($q) use ($mahasiswa) {
                                    $q->where('nim_penerima', $mahasiswa->nim)
                                        ->orWhere('nim_pengirim', $mahasiswa->nim);
                                })
                                ->where('status', 'Aktif');
                        })
                        ->where('tipe_pengirim', 'dosen')
                        ->where('dibaca', false)
                        ->get(['id', 'id_pesan', 'pengirim_id', 'created_at']);
        
        // Total pesan belum dibaca (pesan utama + balasan)
        $belumDibaca = $belumDibacaUtama + $belumDibacaBalasan;
        
        return response()->json([
            'total_belum_dibaca' => $belumDibaca,
            'pesan_utama_belum_dibaca' => $belumDibacaUtama,
            'list_pesan_utama' => $pesanUtamaBelumDibaca,
            'balasan_belum_dibaca' => $belumDibacaBalasan,
            'list_balasan' => $balasanBelumDibaca
        ]);
    }

    /**
     * Method untuk debugging detail pesan
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

    /**
     * Method untuk debugging seluruh pesan mahasiswa
     * Gunakan method ini untuk memeriksa data pesan mahasiswa
     */
    public function debugPesan()
    {
        $mahasiswa = Auth::user();
        
        // Periksa pesan yang terkait dengan mahasiswa
        $pesanPengirim = Pesan::where('nim_pengirim', $mahasiswa->nim)->get();
        $pesanPenerima = Pesan::where('nim_penerima', $mahasiswa->nim)->get();
        
        dd([
            'NIM Mahasiswa' => $mahasiswa->nim,
            'Jumlah Pesan (Sebagai Pengirim)' => $pesanPengirim->count(),
            'Contoh Pesan Pengirim' => $pesanPengirim->take(3)->toArray(),
            'Jumlah Pesan (Sebagai Penerima)' => $pesanPenerima->count(),
            'Contoh Pesan Penerima' => $pesanPenerima->take(3)->toArray(),
        ]);
    }
}