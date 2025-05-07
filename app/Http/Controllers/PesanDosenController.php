<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\Pesan;
use App\Models\BalasanPesan;
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
        
        // Mengambil semua pesan yang diterima ATAU dikirim oleh dosen
        // hanya yang statusnya masih 'Aktif'
        $pesan = Pesan::where(function($query) use ($dosen) {
                $query->where('nip_penerima', $dosen->nip)
                    ->orWhere('nip_pengirim', $dosen->nip);
            })
            ->where('status', 'Aktif') // Hanya tampilkan pesan aktif di dashboard
            ->orderBy('created_at', 'desc')
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
            }
            
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
            // Buat balasan baru
            $balasan = new BalasanPesan();
            $balasan->id_pesan = $id;
            $balasan->pengirim_id = $dosen->nip;
            $balasan->tipe_pengirim = 'dosen';
            $balasan->isi_balasan = $request->balasan;
            $balasan->dibaca = false;
            $balasan->save();
            
            // Log informasi
            Log::info('Balasan dosen berhasil dibuat', [
                'id' => $balasan->id,
                'pengirim_id' => $balasan->pengirim_id,
                'tipe_pengirim' => $balasan->tipe_pengirim
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Balasan berhasil dikirim',
                'data' => [
                    'id' => $balasan->id,
                    'isi_balasan' => $balasan->isi_balasan,
                    'created_at' => Carbon::parse($balasan->created_at)->format('H:i')
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
        
        // Mengambil semua pesan dengan status 'Berakhir' yang diterima ATAU dikirim oleh dosen
        $riwayatPesan = Pesan::where(function($query) use ($dosen) {
                           $query->where('nip_penerima', $dosen->nip)
                                 ->orWhere('nip_pengirim', $dosen->nip);
                       })
                       ->where('status', 'Berakhir')
                       ->orderBy('updated_at', 'desc')
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
        
        $pesan = $query->orderBy('created_at', 'desc')->get();
        
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
        
        $pesan = $query->orderBy('created_at', 'desc')->get();
        
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
        return view('pesan.dosen.faq_dosen');
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