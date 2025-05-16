<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB; // Ditambahkan import DB
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
        
        // Mengambil semua pesan yang diterima ATAU dikirim oleh mahasiswa
        // dengan status 'Aktif' (pesan yang berakhir masuk ke riwayat)
        $pesan = Pesan::where(function($query) use ($mahasiswa) {
                $query->where('nim_penerima', $mahasiswa->nim)
                    ->orWhere('nim_pengirim', $mahasiswa->nim);
            })
            ->where('status', 'Aktif') // Hanya tampilkan pesan aktif
            ->leftJoinSub($latestReplies, 'latest_replies', function ($join) {
                $join->on('pesan.id', '=', 'latest_replies.id_pesan');
            })
            ->select('pesan.*', DB::raw('IFNULL(latest_replies.latest_reply_at, pesan.created_at) as last_activity'))
            ->orderBy('last_activity', 'desc') // Urutkan berdasarkan aktivitas terakhir
            ->get();
                    
        // Menghitung jumlah pesan belum dibaca (hanya yang diterima)
        $belumDibaca = Pesan::where('nim_penerima', $mahasiswa->nim)
                        ->where('dibaca', false)
                        ->count();
        
        // Menghitung jumlah pesan aktif (baik yang diterima maupun dikirim)
        $pesanAktif = Pesan::where(function($query) use ($mahasiswa) {
                        $query->where('nim_penerima', $mahasiswa->nim)
                            ->orWhere('nim_pengirim', $mahasiswa->nim);
                    })
                    ->where('status', 'Aktif')
                    ->count();
        
        // Menghitung total pesan (termasuk yang aktif dan berakhir)
        $totalPesan = Pesan::where(function($query) use ($mahasiswa) {
                        $query->where('nim_penerima', $mahasiswa->nim)
                            ->orWhere('nim_pengirim', $mahasiswa->nim);
                    })
                    ->count();
        
        return view('pesan.mahasiswa.dashboardpesanmahasiswa', compact('pesan', 'belumDibaca', 'pesanAktif', 'totalPesan'));
    }
    
    // Menampilkan form untuk membuat pesan baru
    // Di method create pada PesanMahasiswaController
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
            'penerima_role' => 'required|in:dosen,kaprodi'
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
            
            Log::info('Penerima role yang diset: ' . $pesan->penerima_role);
            
            $pesan->isi_pesan = $request->pesanText;
            $pesan->prioritas = $request->prioritas;
            $pesan->lampiran = $request->lampiran; // Opsional
            $pesan->status = 'Aktif';
            $pesan->dibaca = false;
            $pesan->bookmarked = false; // Pastikan nilai default untuk bookmark
            
            // Force timezone untuk timestamp
            $pesan->created_at = Carbon::now('Asia/Jakarta');
            $pesan->updated_at = Carbon::now('Asia/Jakarta');
            
            // Log sebelum save
            Log::info('Data pesan sebelum disimpan:', [
                'subjek' => $pesan->subjek,
                'nim_pengirim' => $pesan->nim_pengirim,
                'nip_penerima' => $pesan->nip_penerima,
                'penerima_role' => $pesan->penerima_role ?? 'null',
                'isi_pesan' => substr($pesan->isi_pesan, 0, 100), // Hanya tampilkan 100 karakter pertama
                'prioritas' => $pesan->prioritas,
                'lampiran' => $pesan->lampiran,
                'created_at' => $pesan->created_at->format('Y-m-d H:i:s')
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
            
            // Log sukses
            Log::info('Pesan berhasil disimpan dengan ID: ' . $pesan->id);
            
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
      
    // Mengirim balasan pesan
    public function reply(Request $request, $id)
    {
        $request->validate([
            'balasan' => 'required'
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
            // Buat balasan baru dengan nilai dibaca yang jelas
            $balasan = new BalasanPesan();
            $balasan->id_pesan = $id;
            $balasan->pengirim_id = $mahasiswa->nim;
            $balasan->tipe_pengirim = 'mahasiswa';
            $balasan->isi_balasan = $request->balasan;
            $balasan->dibaca = false; // Gunakan false untuk konsistensi dengan accessor/mutator
            
            // Force timezone untuk timestamp
            $balasan->created_at = Carbon::now('Asia/Jakarta');
            $balasan->updated_at = Carbon::now('Asia/Jakarta');
            
            $balasan->save();
            
            // Log hasil untuk debugging
            Log::info('Balasan mahasiswa berhasil dibuat', [
                'id' => $balasan->id,
                'pengirim_id' => $balasan->pengirim_id,
                'tipe_pengirim' => $balasan->tipe_pengirim,
                'isi_balasan' => $balasan->isi_balasan,
                'dibaca' => $balasan->dibaca
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Balasan berhasil dikirim',
                'data' => [
                    'id' => $balasan->id,
                    'isi_balasan' => $balasan->isi_balasan,
                    'created_at' => Carbon::parse($balasan->created_at)->timezone('Asia/Jakarta')->format('H:i'),
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
            
            // Pastikan mahasiswa yang mengakhiri adalah pengirim ATAU penerima pesan
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
        $riwayatPesan = Pesan::where(function($query) use ($mahasiswa) {
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
        
        // Membuat subquery untuk mendapatkan waktu balasan terakhir
        $latestReplies = BalasanPesan::selectRaw('id_pesan, MAX(created_at) as latest_reply_at')
            ->groupBy('id_pesan');
        
        $query = Pesan::where(function($query) use ($mahasiswa) {
            $query->where('nim_pengirim', $mahasiswa->nim)
                  ->orWhere('nim_penerima', $mahasiswa->nim);
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
            'html' => view('pesan.mahasiswa.partials.pesan_list', compact('pesan'))->render()
        ]);
    }
    
    // Pencarian pesan
    public function search(Request $request)
    {
        $mahasiswa = Auth::user();
        $keyword = $request->keyword;
        
        // Membuat subquery untuk mendapatkan waktu balasan terakhir
        $latestReplies = BalasanPesan::selectRaw('id_pesan, MAX(created_at) as latest_reply_at')
            ->groupBy('id_pesan');
        
        $query = Pesan::where(function($query) use ($mahasiswa) {
                    $query->where('nim_pengirim', $mahasiswa->nim)
                          ->orWhere('nim_penerima', $mahasiswa->nim);
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
            'html' => view('pesan.mahasiswa.partials.pesan_list', compact('pesan'))->render()
        ]);
    }
    
    // Halaman FAQ
    /**
 * Halaman FAQ
 */
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