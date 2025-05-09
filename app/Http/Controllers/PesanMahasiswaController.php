<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\Pesan;
use App\Models\BalasanPesan;
use App\Models\Dosen;
use App\Models\Mahasiswa; // Import model Mahasiswa
use Carbon\Carbon;

class PesanMahasiswaController extends Controller
{
    // Menampilkan dashboard pesan mahasiswa
    public function index()
    {
        $mahasiswa = Auth::user();
        
        // Mengambil semua pesan yang diterima ATAU dikirim oleh mahasiswa
        // dengan status 'Aktif' (pesan yang berakhir masuk ke riwayat)
        $pesan = Pesan::where(function($query) use ($mahasiswa) {
            $query->where('nim_penerima', $mahasiswa->nim)
                ->orWhere('nim_pengirim', $mahasiswa->nim);
        })
        ->where('status', 'Aktif') // Hanya tampilkan pesan aktif
        ->orderBy('created_at', 'desc')
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
    public function create()
    {
        // Dapatkan daftar dosen untuk dropdown
        $dosen = Dosen::orderBy('nama')->get();
        
        return view('pesan.mahasiswa.buatpesanmahasiswa', compact('dosen'));
    }
    
    // Menyimpan pesan baru
    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'subjek' => 'required',
            'dosenId' => 'required',
            'prioritas' => 'required',
            'pesanText' => 'required'
        ]);
        
        try {
            // Buat pesan baru
            $pesan = new Pesan();
            $pesan->subjek = $request->subjek;
            $pesan->nim_pengirim = Auth::user()->nim;
            $pesan->nip_penerima = $request->dosenId;
            $pesan->isi_pesan = $request->pesanText;
            $pesan->prioritas = $request->prioritas;
            $pesan->lampiran = $request->lampiran; // Opsional
            $pesan->status = 'Aktif';
            $pesan->dibaca = false;
            $pesan->bookmarked = false; // Pastikan nilai default untuk bookmark
            $pesan->save();
            
            // Log informasi untuk debugging
            Log::info('Pesan mahasiswa berhasil dibuat', [
                'id' => $pesan->id,
                'pengirim' => $pesan->nim_pengirim,
                'penerima' => $pesan->nip_penerima
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Pesan berhasil dikirim'
            ]);
        } catch (\Exception $e) {
            Log::error('Error saat mengirim pesan: ' . $e->getMessage());
            
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
            // Buat balasan baru
            $balasan = new BalasanPesan();
            $balasan->id_pesan = $id;
            $balasan->pengirim_id = $mahasiswa->nim;
            $balasan->tipe_pengirim = 'mahasiswa';
            $balasan->isi_balasan = $request->balasan;
            $balasan->dibaca = false;
            $balasan->save();
            
            // Log hasil untuk debugging
            Log::info('Balasan mahasiswa berhasil dibuat', [
                'id' => $balasan->id,
                'pengirim_id' => $balasan->pengirim_id,
                'tipe_pengirim' => $balasan->tipe_pengirim,
                'isi_balasan' => $balasan->isi_balasan
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Balasan berhasil dikirim',
                'data' => [
                    'id' => $balasan->id,
                    'isi_balasan' => $balasan->isi_balasan,
                    'created_at' => Carbon::parse($balasan->created_at)->format('H:i'),
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
        
        // Mengambil semua pesan dengan status 'Berakhir' (dikirim ATAU diterima)
        $riwayatPesan = Pesan::where(function($query) use ($mahasiswa) {
                            $query->where('nim_pengirim', $mahasiswa->nim)
                                  ->orWhere('nim_penerima', $mahasiswa->nim);
                         })
                         ->where('status', 'Berakhir')
                         ->orderBy('updated_at', 'desc')
                         ->get();
                           
        return view('pesan.mahasiswa.riwayatpesanmahasiswa', compact('riwayatPesan'));
    }
    
    // Filter pesan berdasarkan prioritas
    public function filter(Request $request)
    {
        $mahasiswa = Auth::user();
        $filter = $request->filter;
        
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
        
        $pesan = $query->orderBy('created_at', 'desc')->get();
        
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
        
        $pesan = $query->orderBy('created_at', 'desc')->get();
        
        return response()->json([
            'success' => true,
            'html' => view('pesan.mahasiswa.partials.pesan_list', compact('pesan'))->render()
        ]);
    }
    
    // Halaman FAQ
    public function faq()
    {
        return view('pesan.mahasiswa.faq_mahasiswa');
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