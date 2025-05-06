<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\Pesan;
use App\Models\BalasanPesan;
use App\Models\Dosen;
use Carbon\Carbon;

class PesanMahasiswaController extends Controller
{
    // Menampilkan dashboard pesan mahasiswa
    public function index()
    {
        $mahasiswa = Auth::user();
        
        // Mengambil semua pesan yang dikirim oleh mahasiswa
        $pesan = Pesan::where('nim_pengirim', $mahasiswa->nim)
                      ->orderBy('created_at', 'desc')
                      ->get();
                      
        // Menghitung jumlah pesan belum dibaca
        $belumDibaca = Pesan::where('nim_pengirim', $mahasiswa->nim)
                          ->where('dibaca', false)
                          ->count();
        
        // Menghitung jumlah pesan aktif
        $pesanAktif = Pesan::where('nim_pengirim', $mahasiswa->nim)
                         ->where('status', 'Aktif')
                         ->count();
        
        // Menghitung total pesan
        $totalPesan = $pesan->count();
        
        return view('pesan.mahasiswa.dashboardpesanmahasiswa', compact('pesan', 'belumDibaca', 'pesanAktif', 'totalPesan'));
    }
    
    // Menampilkan form untuk membuat pesan baru
    public function create()
    {
        // Dapatkan daftar dosen untuk dropdown
        $dosen = Dosen::all();
        
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
            $pesan->save();
            
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
        $pesan = Pesan::with(['pengirim', 'penerima', 'balasan'])->findOrFail($id);
        
        // Pastikan mahasiswa yang melihat adalah pengirim pesan
        if ($pesan->nim_pengirim != Auth::user()->nim) {
            return redirect()->route('mahasiswa.dashboard.pesan')
                        ->with('error', 'Anda tidak memiliki akses ke pesan ini');
        }
        
        // Kelompokkan balasan berdasarkan tanggal untuk tampilan yang lebih baik
        $balasanByDate = [];
        
        // Tambahkan pesan awal ke balasan untuk ditampilkan dalam chat
        $allMessages = collect([$pesan]);
        
        // Gabungkan dengan balasan
        if ($pesan->balasan) {
            $allMessages = $allMessages->concat($pesan->balasan);
        }
        
        // Kelompokkan berdasarkan tanggal
        foreach ($allMessages as $message) {
            $date = Carbon::parse($message->created_at)->format('Y-m-d');
            if (!isset($balasanByDate[$date])) {
                $balasanByDate[$date] = [];
            }
            $balasanByDate[$date][] = $message;
        }
        
        // Update status menjadi dibaca jika belum dibaca
        if (!$pesan->dibaca) {
            $pesan->dibaca = true;
            $pesan->save();
        }
        
        return view('pesan.mahasiswa.isipesanmahasiswa', compact('pesan', 'balasanByDate'));
    }
    
    // Mengirim balasan pesan
    public function reply(Request $request, $id)
    {
        $request->validate([
            'balasan' => 'required'
        ]);
        
        $pesan = Pesan::findOrFail($id);
        
        // Pastikan mahasiswa yang membalas adalah pengirim pesan
        if ($pesan->nim_pengirim != Auth::user()->nim) {
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
            $balasan->pengirim_id = Auth::user()->nim;
            $balasan->tipe_pengirim = 'mahasiswa';
            $balasan->isi_balasan = $request->balasan;
            $balasan->dibaca = false;
            $balasan->save();
            
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
        $pesan = Pesan::findOrFail($id);
        
        // Pastikan mahasiswa yang mengakhiri adalah pengirim pesan
        if ($pesan->nim_pengirim != Auth::user()->nim) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak memiliki akses ke pesan ini'
            ], 403);
        }
        
        try {
            $pesan->status = 'Berakhir';
            $pesan->save();
            
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
    
    // Menampilkan riwayat pesan
    public function history()
    {
        $mahasiswa = Auth::user();
        
        // Mengambil semua pesan dengan status 'Berakhir'
        $riwayatPesan = Pesan::where('nim_pengirim', $mahasiswa->nim)
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
        
        $query = Pesan::where('nim_pengirim', $mahasiswa->nim);
        
        if ($filter == 'penting') {
            $query->where('prioritas', 'Penting');
        } elseif ($filter == 'umum') {
            $query->where('prioritas', 'Umum');
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
        
        $pesan = Pesan::where('nim_pengirim', $mahasiswa->nim)
                    ->where(function($query) use ($keyword) {
                        $query->where('subjek', 'like', "%{$keyword}%")
                              ->orWhere('isi_pesan', 'like', "%{$keyword}%")
                              ->orWhereHas('penerima', function($q) use ($keyword) {
                                  $q->where('nama', 'like', "%{$keyword}%");
                              });
                    })
                    ->orderBy('created_at', 'desc')
                    ->get();
        
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
}