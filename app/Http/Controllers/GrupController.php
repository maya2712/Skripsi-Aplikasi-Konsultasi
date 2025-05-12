<?php

namespace App\Http\Controllers;

use App\Models\Grup;
use App\Models\Mahasiswa;
use App\Models\GrupPesan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GrupController extends Controller
{
    // Tampilkan daftar grup dosen
    public function index()
    {
        $dosen = Auth::user();
        $grups = Grup::where('dosen_id', $dosen->nip)->get();
        
        return view('pesan.dosen.daftargrup', compact('grups'));
    }
    
    // Tampilkan form buat grup baru
    public function create()
    {
        // Ambil semua mahasiswa dari database
        $mahasiswa = Mahasiswa::all();
        
        return view('pesan.dosen.buatgrupbaru', compact('mahasiswa'));
    }
    
    // Simpan grup baru
    public function store(Request $request)
    {
        $request->validate([
            'nama_grup' => 'required|string|max:255',
            'anggota' => 'required|array',
            'anggota.*' => 'exists:mahasiswas,nim'
        ]);
        
        // Buat grup baru dengan NIP dosen yang login
        $grup = Grup::create([
            'nama_grup' => $request->nama_grup,
            'dosen_id' => Auth::user()->nip  // Gunakan NIP sebagai foreign key
        ]);
        
        // Tambahkan anggota ke grup
        $grup->mahasiswa()->attach($request->anggota);
        
        return redirect()->route('dosen.grup.index')
                         ->with('success', 'Grup berhasil dibuat');
    }
    
    // Lihat detail grup
    public function show($id)
    {
        $grup = Grup::with(['mahasiswa', 'pesan'])->findOrFail($id);
        
        // Pastikan dosen yang melihat adalah pemilik grup
        if ($grup->dosen_id != Auth::user()->nip) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses ke grup ini');
        }
        
        // Kelompokkan pesan berdasarkan tanggal
        $grupPesanByDate = [];
        
        foreach ($grup->pesan as $pesan) {
            $date = $pesan->created_at->format('Y-m-d');
            if (!isset($grupPesanByDate[$date])) {
                $grupPesanByDate[$date] = [];
            }
            $grupPesanByDate[$date][] = $pesan;
        }
        
        // Urutkan tanggal (dari paling lama ke paling baru)
        ksort($grupPesanByDate);
        
        return view('pesan.dosen.detailgrup', compact('grup', 'grupPesanByDate'));
    }
    
    // Hapus grup
    public function destroy($id)
    {
        $grup = Grup::findOrFail($id);
        
        // Pastikan dosen yang menghapus adalah pemilik grup
        if ($grup->dosen_id != Auth::user()->nip) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses untuk menghapus grup ini');
        }
        
        $grup->delete();
        
        return redirect()->route('dosen.grup.index')
                         ->with('success', 'Grup berhasil dihapus');
    }

    // Tambahkan method di GrupController.php
    public function addMember(Request $request, $id)
    {
        $request->validate([
            'new_members' => 'required|array',
            'new_members.*' => 'exists:mahasiswas,nim'
        ]);
        
        $grup = Grup::findOrFail($id);
        
        // Pastikan dosen yang menambahkan adalah pemilik grup
        if ($grup->dosen_id != Auth::user()->nip) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses untuk mengedit grup ini');
        }
        
        // Tambahkan anggota baru ke grup
        $grup->mahasiswa()->attach($request->new_members);
        
        return redirect()->route('dosen.grup.show', $grup->id)
                        ->with('success', 'Anggota berhasil ditambahkan ke grup');
    }

    // hapusAnggota
    public function hapusAnggota($id, $mahasiswa_id)
    {
        $grup = Grup::findOrFail($id);
        $grup->mahasiswa()->detach($mahasiswa_id);
        
        return redirect()->back()->with('success', 'Anggota berhasil dihapus dari grup');
    }

    // Mengirim pesan dalam grup
    public function sendMessage(Request $request, $id)
    {
        $request->validate([
            'isi_pesan' => 'required'
        ]);
        
        $grup = Grup::findOrFail($id);
        $user = Auth::user();
        
        // Cek apakah pengirim adalah dosen pemilik grup
        if ($user->nip != $grup->dosen_id) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak memiliki akses untuk mengirim pesan di grup ini'
            ], 403);
        }
        
        // Buat pesan baru
        $pesan = new GrupPesan();
        $pesan->grup_id = $id;
        $pesan->pengirim_id = $user->nip;
        $pesan->tipe_pengirim = 'dosen';
        $pesan->isi_pesan = $request->isi_pesan;
        
        // Jika ada lampiran
        if ($request->hasFile('lampiran')) {
            // Proses upload file lampiran (tambahkan logika sesuai kebutuhan)
            $file = $request->file('lampiran');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $file->storeAs('public/lampiran', $fileName);
            $pesan->lampiran = 'storage/lampiran/' . $fileName;
        }
        
        $pesan->save();
        
        return response()->json([
            'success' => true,
            'message' => 'Pesan berhasil dikirim',
            'data' => [
                'id' => $pesan->id,
                'pengirim' => $user->nama,
                'isi_pesan' => $pesan->isi_pesan,
                'created_at' => $pesan->created_at->format('H:i')
            ]
        ]);
    }
}