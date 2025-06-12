<?php

namespace App\Http\Controllers;

use App\Models\Grup;
use App\Models\Mahasiswa;
use App\Models\GrupPesan;
use App\Models\GrupPesanRead;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class GrupController extends Controller
{
    // Tambahkan method untuk mendapatkan jumlah pesan belum dibaca
    public function getUnreadCount($grupId)
    {
        $dosen = Auth::user();
        
        // Hitung jumlah pesan grup yang belum dibaca oleh dosen
        $unreadCount = GrupPesan::where('grup_id', $grupId)
            ->whereNotExists(function ($query) use ($dosen) {
                $query->select(DB::raw(1))
                      ->from('grup_pesan_reads')
                      ->whereRaw('grup_pesan_reads.grup_pesan_id = grup_pesan.id')
                      ->where('user_id', $dosen->nip)
                      ->where('user_type', 'dosen')
                      ->where('read', true);
            })
            ->where('pengirim_id', '!=', $dosen->nip) // Jangan hitung pesan yang dikirim oleh dosen sendiri
            ->count();
        
        return $unreadCount;
    }
    
    // Tampilkan daftar grup dosen
    public function index()
    {
        $dosen = Auth::user();
        $activeRole = session('active_role', 'dosen'); // Ambil peran aktif dari session
        
        // Ambil grup sesuai dengan peran aktif dosen
        $grups = Grup::where('dosen_id', $dosen->nip)
                    ->where('dosen_role', $activeRole)
                    ->get();
        
        // Tambahkan unread count untuk setiap grup
        foreach ($grups as $grup) {
            // Hitung menggunakan accessor yang telah diperbaiki
            $grup->unreadCount = $grup->unreadMessages;
        }
        
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
        
        $dosen = Auth::user();
        $activeRole = session('active_role', 'dosen'); // Ambil peran aktif dari session
        
        // Buat grup baru dengan NIP dosen yang login dan peran aktif
        $grup = Grup::create([
            'nama_grup' => $request->nama_grup,
            'dosen_id' => $dosen->nip,
            'dosen_role' => $activeRole // Simpan peran dosen saat membuat grup
        ]);
        
        // Tambahkan anggota ke grup
        $grup->mahasiswa()->attach($request->anggota);
        
        return redirect()->route('dosen.grup.index')
                         ->with('success', 'Grup berhasil dibuat');
    }
    
    // Lihat detail grup
    public function show($id)
    {
        $grup = Grup::with('mahasiswa')->findOrFail($id);
        $dosen = Auth::user();
        $activeRole = session('active_role', 'dosen'); // Ambil peran aktif dari session
        
        // Pastikan dosen yang melihat adalah pemilik grup dan dengan peran yang sesuai
        if ($grup->dosen_id != $dosen->nip || $grup->dosen_role != $activeRole) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses ke grup ini dengan peran saat ini');
        }
        
        // Ambil semua pesan grup
        $pesans = GrupPesan::where('grup_id', $id)->orderBy('created_at', 'asc')->get();
        
        // Kelompokkan pesan berdasarkan tanggal
        $grupPesanByDate = [];
        foreach ($pesans as $pesan) {
            $date = $pesan->created_at->format('Y-m-d');
            if (!isset($grupPesanByDate[$date])) {
                $grupPesanByDate[$date] = [];
            }
            $grupPesanByDate[$date][] = $pesan;
            
            // Tandai pesan sebagai dibaca oleh dosen
            GrupPesanRead::updateOrCreate(
                [
                    'grup_pesan_id' => $pesan->id,
                    'user_id' => $dosen->nip,
                    'user_type' => 'dosen'
                ],
                ['read' => true]
            );
        }
        
        // Urutkan tanggal (dari paling lama ke paling baru)
        ksort($grupPesanByDate);
        
        return view('pesan.dosen.detailgrup', compact('grup', 'grupPesanByDate'));
    }
    
    // Hapus grup
    public function destroy($id)
    {
        $grup = Grup::findOrFail($id);
        $dosen = Auth::user();
        $activeRole = session('active_role', 'dosen'); // Ambil peran aktif dari session
        
        // Pastikan dosen yang menghapus adalah pemilik grup dan dengan peran yang sesuai
        if ($grup->dosen_id != $dosen->nip || $grup->dosen_role != $activeRole) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses untuk menghapus grup ini dengan peran saat ini');
        }
        
        $grup->delete();
        
        return redirect()->route('dosen.grup.index')
                         ->with('success', 'Grup berhasil dihapus');
    }

    // Tambah anggota grup
    public function addMember(Request $request, $id)
    {
         // TAMBAHKAN INI DI AWAL
    Log::info('=== DEBUG TAMBAH ANGGOTA ===', [
        'grup_id' => $id,
        'data_yang_diterima' => $request->all(),
        'new_members' => $request->input('new_members', []),
        'user_yang_login' => Auth::user()->nip
    ]);
        
        $request->validate([
            'new_members' => 'required|array',
            'new_members.*' => 'exists:mahasiswas,nim'
        ]);
        
        $grup = Grup::findOrFail($id);
        $dosen = Auth::user();
        $activeRole = session('active_role', 'dosen'); // Ambil peran aktif dari session
        
        // Pastikan dosen yang menambahkan adalah pemilik grup dan dengan peran yang sesuai
        if ($grup->dosen_id != $dosen->nip || $grup->dosen_role != $activeRole) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses untuk mengedit grup ini dengan peran saat ini');
        }
        
        // Tambahkan anggota baru ke grup
        $grup->mahasiswa()->attach($request->new_members);
        
        return redirect()->route('dosen.grup.show', $grup->id)
                        ->with('success', 'Anggota berhasil ditambahkan ke grup');
    }

    // Hapus anggota grup
    public function hapusAnggota($id, $mahasiswa_id)
    {
        $grup = Grup::findOrFail($id);
        $dosen = Auth::user();
        $activeRole = session('active_role', 'dosen'); // Ambil peran aktif dari session
        
        // Pastikan dosen yang menghapus anggota adalah pemilik grup dan dengan peran yang sesuai
        if ($grup->dosen_id != $dosen->nip || $grup->dosen_role != $activeRole) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses untuk menghapus anggota dari grup ini dengan peran saat ini');
        }
        
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
        $dosen = Auth::user();
        $activeRole = session('active_role', 'dosen'); // Ambil peran aktif dari session
        
        // Cek apakah pengirim adalah dosen pemilik grup dan dengan peran yang sesuai
        if ($dosen->nip != $grup->dosen_id || $grup->dosen_role != $activeRole) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak memiliki akses untuk mengirim pesan di grup ini dengan peran saat ini'
            ], 403);
        }
        
        // Buat pesan baru
        $pesan = new GrupPesan();
        $pesan->grup_id = $id;
        $pesan->pengirim_id = $dosen->nip;
        $pesan->tipe_pengirim = 'dosen';
        $pesan->sender_role = $activeRole; // Tambahkan peran pengirim
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
        
        // Buat record di grup_pesan_reads
        // Tandai sebagai belum dibaca untuk semua anggota grup kecuali pengirim
        
        // Untuk semua mahasiswa di grup
        foreach ($grup->mahasiswa as $mahasiswa) {
            // Jangan buat record untuk pengirim (jika pengirim adalah mahasiswa)
            if ($pesan->tipe_pengirim == 'mahasiswa' && $pesan->pengirim_id == $mahasiswa->nim) {
                continue;
            }
            
            GrupPesanRead::create([
                'grup_pesan_id' => $pesan->id,
                'user_id' => $mahasiswa->nim,
                'user_type' => 'mahasiswa',
                'read' => false
            ]);
        }
        
        // Jika pengirim bukan dosen, tandai belum dibaca untuk dosen juga
        if ($pesan->tipe_pengirim != 'dosen') {
            GrupPesanRead::create([
                'grup_pesan_id' => $pesan->id,
                'user_id' => $grup->dosen_id,
                'user_type' => 'dosen',
                'read' => false
            ]);
        }
        
        return response()->json([
            'success' => true,
            'message' => 'Pesan berhasil dikirim',
            'data' => [
                'id' => $pesan->id,
                'pengirim' => $dosen->nama,
                'isi_pesan' => $pesan->isi_pesan,
                'created_at' => $pesan->created_at->format('H:i'),
                'sender_role' => $pesan->sender_role // Tambahkan informasi peran pengirim
            ]
        ]);
    }
}