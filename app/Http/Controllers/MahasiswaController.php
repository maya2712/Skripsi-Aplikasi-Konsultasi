<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use App\Models\Grup;
use App\Models\GrupPesan;
use App\Models\GrupPesanRead;

class MahasiswaController extends Controller
{   
    // Tambahkan method untuk mendapatkan jumlah pesan belum dibaca
    public function getUnreadCount($grupId)
    {
        $mahasiswa = Auth::user();
        
        // Hitung jumlah pesan grup yang belum dibaca oleh mahasiswa
        $unreadCount = GrupPesan::where('grup_id', $grupId)
            ->whereNotExists(function ($query) use ($mahasiswa) {
                $query->select(DB::raw(1))
                    ->from('grup_pesan_reads')
                    ->whereColumn('grup_pesan_reads.grup_pesan_id', 'grup_pesans.id')
                    ->where('user_id', $mahasiswa->nim)
                    ->where('user_type', 'mahasiswa')
                    ->where('read', true);
            })
            ->where('pengirim_id', '!=', $mahasiswa->nim) // Jangan hitung pesan yang dikirim oleh mahasiswa sendiri
            ->count();
        
        return $unreadCount;
    }

    public function index()
    {
        return view('bimbingan.mahasiswa.usulanbimbingan', [
            'activeTab' => 'usulan'
        ]);
    }
    
    public function getUsulanBimbingan(Request $request)
    {
        try {
            if (!Auth::check()) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }

            $perPage = $request->input('per_page', 10);
            $nim = Auth::user()->nim;

            $usulan = DB::table('usulan_bimbingans as ub')
                ->join('mahasiswas as m', 'ub.nim', '=', 'm.nim')
                ->where('ub.nim', $nim)
                ->select('ub.*', 'm.nama as mahasiswa_nama')
                ->orderBy('ub.created_at', 'desc')
                ->paginate($perPage);

            $html = view('components.tabs.usulan', [
                'usulan' => $usulan
            ])->render();

            return response()->json([
                'html' => $html,
                'pagination' => [
                    'total' => $usulan->total(),
                    'per_page' => $usulan->perPage(),
                    'current_page' => $usulan->currentPage(),
                    'last_page' => $usulan->lastPage(),
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Error getting usulan bimbingan: ' . $e->getMessage());
            return response()->json(['error' => 'Gagal memuat data usulan'], 500);
        }
    }

    public function getDetailBimbingan($id)
    {
        try {
            $usulan = DB::table('usulan_bimbingans as ub')
                ->join('mahasiswas as m', 'ub.nim', '=', 'm.nim')
                ->join('prodi as p', 'm.prodi_id', '=', 'p.id')
                ->join('konsentrasi as k', 'm.konsentrasi_id', '=', 'k.id')
                ->select(
                    'ub.*',
                    'p.nama_prodi',
                    'k.nama_konsentrasi'
                )
                ->where('ub.id', $id)
                ->firstOrFail();

            // Format tanggal ke format Indonesia
            $tanggal = Carbon::parse($usulan->tanggal)->locale('id')->isoFormat('dddd, D MMMM Y');
            
            // Format waktu
            $waktuMulai = Carbon::parse($usulan->waktu_mulai)->format('H.i');
            $waktuSelesai = Carbon::parse($usulan->waktu_selesai)->format('H.i');
            
            // Set warna badge status
            $statusBadgeClass = match($usulan->status) {
                'DISETUJUI' => 'bg-success',
                'DITOLAK' => 'bg-danger',
                'USULAN' => 'bg-info',
                default => 'bg-secondary'
            };
            
            Log::info('Data usulan ditemukan', ['usulan' => $usulan]);

            // Kirim data ke view
            return view('bimbingan.aksiInformasi', compact(
                'usulan',
                'tanggal',
                'waktuMulai',
                'waktuSelesai',
                'statusBadgeClass'
            ));

        } catch (\Exception $e) {
            Log::error('Error di getDetailBimbingan: ' . $e->getMessage());
            return redirect()
                ->back()
                ->with('error', 'Terjadi kesalahan saat mengambil data usulan bimbingan');
        }
    }

    public function getDaftarDosen(Request $request)
    {
        try {
            if (!Auth::check()) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }
            
            $perPage = $request->input('per_page', 10);
            
            $daftarDosen = DB::table('dosens as d')
                ->leftJoin('usulan_bimbingans as ub', function($join) {
                    $join->on('d.nip', '=', 'ub.nip')
                        ->where('ub.status', 'DISETUJUI');
                })
                ->select(
                    'd.nip',
                    'd.nama_singkat',
                    'd.nama',
                    DB::raw('COUNT(ub.id) as total_bimbingan')
                )
                ->groupBy('d.nip', 'nama_singkat', 'd.nama')
                ->orderBy('d.nama')
                ->paginate($perPage);

            $html = view('components.tabs.jadwal', [
                'daftarDosen' => $daftarDosen
            ])->render();

            return response()->json([
                'html' => $html,
                'pagination' => [
                    'total' => $daftarDosen->total(),
                    'per_page' => $daftarDosen->perPage(),
                    'current_page' => $daftarDosen->currentPage(),
                    'last_page' => $daftarDosen->lastPage(),
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Error getting daftar dosen: ' . $e->getMessage());
            return response()->json(['error' => 'Gagal memuat data daftar dosen'], 500);
        }
    }

    public function getDetailDaftar($nip)
    {
        try {
            $dosen = DB::table('dosens')
                ->where('nip', $nip)
                ->firstOrFail();

            // Ambil detail bimbingan yang disetujui untuk dosen ini
            $bimbingan = DB::table('usulan_bimbingans as ub')
                ->join('mahasiswas as m', 'ub.nim', '=', 'm.nim')
                ->where('ub.nip', $nip)
                ->where('ub.status', 'DISETUJUI')
                ->select(
                    'ub.*',
                    'm.nama as mahasiswa_nama'
                )
                ->orderBy('ub.tanggal', 'desc')
                ->orderBy('ub.waktu_mulai', 'asc')
                ->paginate(10);

            return view('bimbingan.mahasiswa.detaildaftar', compact('dosen', 'bimbingan'));

        } catch (\Exception $e) {
            Log::error('Error getting detail dosen: ' . $e->getMessage());
            return back()->with('error', 'Gagal memuat detail dosen');
        }
    }

    public function getRiwayatBimbingan(Request $request)
    {
        try {
            if (!Auth::check()) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }

            $perPage = $request->input('per_page', 10);
            $nim = Auth::user()->nim;

            $riwayat = DB::table('usulan_bimbingans as ub')
                ->join('mahasiswas as m', 'ub.nim', '=', 'm.nim')
                ->join('dosens as d', 'ub.nip', '=', 'd.nip')
                ->where('ub.nim', $nim)
                ->where('ub.status', 'SELESAI')
                ->select('ub.*', 'm.nama as mahasiswa_nama', 'd.nama as dosen_nama')
                ->orderBy('ub.tanggal', 'desc')
                ->paginate($perPage);

            $html = view('components.tabs.riwayat', [
                'riwayat' => $riwayat
            ])->render();

            return response()->json([
                'html' => $html,
                'pagination' => [
                    'total' => $riwayat->total(),
                    'per_page' => $riwayat->perPage(),
                    'current_page' => $riwayat->currentPage(),
                    'last_page' => $riwayat->lastPage(),
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Error getting riwayat: ' . $e->getMessage());
            return response()->json(['error' => 'Gagal memuat riwayat bimbingan'], 500);
        }
    }
    
    /**
     * Fungsi untuk mendapatkan grup-grup mahasiswa
     */
   // Di MahasiswaController.php
    public function getGrupMahasiswa()
    {
        try {
            $mahasiswa = Auth::user();
            $grups = $mahasiswa->grups;
            
            // Tambahkan unread count untuk setiap grup
            foreach ($grups as $grup) {
                // Hitung menggunakan accessor yang telah diperbaiki
                $grup->unreadCount = $grup->unreadMessages;
                
                // Log nilai untuk debugging
                \Illuminate\Support\Facades\Log::info('Grup ' . $grup->nama_grup . ': unreadMessages = ' . $grup->unreadMessages);
            }
            
            return view('pesan.mahasiswa.daftargrupmahasiswa', compact('grups'));
        } catch (\Exception $e) {
            Log::error('Error mendapatkan daftar grup: ' . $e->getMessage());
            return back()->with('error', 'Gagal memuat daftar grup');
        }
    }
    
    /**
     * Fungsi untuk mendapatkan detail grup
     */
    public function getDetailGrup($id)
{
    try {
        $mahasiswa = Auth::user();
        $grup = Grup::with('mahasiswa')->findOrFail($id);
        
        // Cek apakah mahasiswa ini anggota grup
        $isMember = $grup->mahasiswa->contains('nim', $mahasiswa->nim);
        
        if (!$isMember) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses ke grup ini');
        }
        
        // Ambil semua pesan grup
        // PERBAIKAN: Gunakan with untuk preload relasi pengirimDosen dan pengirimMahasiswa
        $pesans = GrupPesan::with(['pengirimDosen', 'pengirimMahasiswa'])
            ->where('grup_id', $id)
            ->orderBy('created_at', 'asc')
            ->get();
        
        // Kelompokkan pesan berdasarkan tanggal
        $grupPesanByDate = [];
        foreach ($pesans as $pesan) {
            $date = $pesan->created_at->format('Y-m-d');
            if (!isset($grupPesanByDate[$date])) {
                $grupPesanByDate[$date] = [];
            }
            $grupPesanByDate[$date][] = $pesan;
            
            // Tandai pesan sebagai dibaca oleh mahasiswa
            GrupPesanRead::updateOrCreate(
                [
                    'grup_pesan_id' => $pesan->id,
                    'user_id' => $mahasiswa->nim,
                    'user_type' => 'mahasiswa'
                ],
                ['read' => true]
            );
        }
        
        // Urutkan tanggal (dari paling lama ke paling baru)
        ksort($grupPesanByDate);
        
        return view('pesan.mahasiswa.detailgrupmahasiswa', compact('grup', 'grupPesanByDate'));
    } catch (\Exception $e) {
        Log::error('Error mendapatkan detail grup: ' . $e->getMessage());
        return back()->with('error', 'Gagal memuat detail grup');
    }
}

    /**
     * Fungsi untuk mengirim pesan dalam grup dari sisi mahasiswa
     */
    public function sendMessageGrup(Request $request, $id)
    {
        $request->validate([
            'isi_pesan' => 'required'
        ]);
        
        $grup = Grup::findOrFail($id);
        $mahasiswa = Auth::user();
        
        // Cek apakah mahasiswa adalah anggota grup
        $isMember = $grup->mahasiswa->contains('nim', $mahasiswa->nim);
        
        if (!$isMember) {
            return response()->json([
                'success' => false,
                'message' => 'Anda bukan anggota grup ini'
            ], 403);
        }
        
        try {
            DB::beginTransaction();
            
            // Buat pesan baru
            $pesan = new GrupPesan();
            $pesan->grup_id = $id;
            $pesan->pengirim_id = $mahasiswa->nim;
            $pesan->tipe_pengirim = 'mahasiswa';
            $pesan->isi_pesan = $request->isi_pesan;
            
            // Jika ada lampiran
            if ($request->hasFile('lampiran')) {
                // Proses upload file lampiran
                $file = $request->file('lampiran');
                $fileName = time() . '_' . $file->getClientOriginalName();
                $file->storeAs('public/lampiran', $fileName);
                $pesan->lampiran = 'storage/lampiran/' . $fileName;
            }
            
            $pesan->save();
            
            Log::info('Pesan grup baru dibuat', ['id' => $pesan->id, 'pengirim' => $mahasiswa->nim]);
            
            // Buat record di grup_pesan_reads
            // Tandai sebagai belum dibaca untuk semua anggota grup kecuali pengirim
            
            // Untuk semua mahasiswa di grup
            foreach ($grup->mahasiswa as $mahasiswaItem) {
                // Jangan buat record untuk pengirim
                if ($mahasiswaItem->nim == $mahasiswa->nim) {
                    continue;
                }
                
                GrupPesanRead::create([
                    'grup_pesan_id' => $pesan->id,
                    'user_id' => $mahasiswaItem->nim,
                    'user_type' => 'mahasiswa',
                    'read' => false
                ]);
                
                Log::info('Record belum dibaca dibuat untuk mahasiswa', [
                    'pesan_id' => $pesan->id,
                    'mahasiswa_nim' => $mahasiswaItem->nim
                ]);
            }
            
            // Tandai belum dibaca untuk dosen
            GrupPesanRead::create([
                'grup_pesan_id' => $pesan->id,
                'user_id' => $grup->dosen_id,
                'user_type' => 'dosen',
                'read' => false
            ]);
            
            Log::info('Record belum dibaca dibuat untuk dosen', [
                'pesan_id' => $pesan->id,
                'dosen_nip' => $grup->dosen_id
            ]);
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Pesan berhasil dikirim',
                'data' => [
                    'id' => $pesan->id,
                    'pengirim' => $mahasiswa->nama,
                    'isi_pesan' => $pesan->isi_pesan,
                    'created_at' => $pesan->created_at->format('H:i')
                ]
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error saat mengirim pesan grup: ' . $e->getMessage() . ' - ' . $e->getTraceAsString());
            
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengirim pesan: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Fungsi khusus untuk debugging jumlah pesan yang belum dibaca di grup
     * 
     * @param int $grupId ID grup yang ingin di-debug
     * @return \Illuminate\Http\JsonResponse
     */
    public function debugGrupUnreadMessages($grupId)
    {
        try {
            $grup = Grup::findOrFail($grupId);
            $mahasiswa = Auth::user();
            
            if (!$mahasiswa) {
                return response()->json(['error' => 'User tidak terautentikasi'], 401);
            }
            
            // Hitung pesan belum dibaca dengan query langsung
            $unreadCount = GrupPesan::where('grup_id', $grupId)
                ->where('pengirim_id', '!=', $mahasiswa->nim)
                ->whereNotExists(function ($query) use ($mahasiswa) {
                    $query->select(DB::raw(1))
                        ->from('grup_pesan_reads')
                        ->whereColumn('grup_pesan_reads.grup_pesan_id', 'grup_pesan.id')
                        ->where('user_id', $mahasiswa->nim)
                        ->where('user_type', 'mahasiswa')
                        ->where('read', true);
                })
                ->count();
                
            // Dapatkan semua pesan yang belum dibaca untuk debugging
            $unreadMessages = GrupPesan::where('grup_id', $grupId)
                ->where('pengirim_id', '!=', $mahasiswa->nim)
                ->whereNotExists(function ($query) use ($mahasiswa) {
                    $query->select(DB::raw(1))
                        ->from('grup_pesan_reads')
                        ->whereColumn('grup_pesan_reads.grup_pesan_id', 'grup_pesans.id')
                        ->where('user_id', $mahasiswa->nim)
                        ->where('user_type', 'mahasiswa')
                        ->where('read', true);
                })
                ->get();
            
            // Dapatkan semua pesan di grup dan status dibaca
            $allMessages = GrupPesan::where('grup_id', $grupId)->get();
            $messageReadStatus = [];
            
            foreach ($allMessages as $msg) {
                $readRecord = GrupPesanRead::where('grup_pesan_id', $msg->id)
                    ->where('user_id', $mahasiswa->nim)
                    ->where('user_type', 'mahasiswa')
                    ->first();
                    
                $messageReadStatus[] = [
                    'message_id' => $msg->id,
                    'pengirim_id' => $msg->pengirim_id,
                    'tipe_pengirim' => $msg->tipe_pengirim,
                    'isi_singkat' => substr($msg->isi_pesan, 0, 30) . '...',
                    'created_at' => $msg->created_at->format('Y-m-d H:i:s'),
                    'read_status' => $readRecord ? ($readRecord->read ? 'Dibaca' : 'Belum Dibaca') : 'Tidak Ada Record',
                    'read_record_id' => $readRecord ? $readRecord->id : null
                ];
            }
            
            // Hitung pesan dengan accessor
            $grupWithAccessor = Grup::find($grupId);
            $accessorUnreadCount = $grupWithAccessor->unreadMessages;
                
            return response()->json([
                'grup_nama' => $grup->nama_grup,
                'unread_count_query' => $unreadCount,
                'unread_count_accessor' => $accessorUnreadCount,
                'match' => ($unreadCount == $accessorUnreadCount) ? 'Ya' : 'Tidak',
                'user_nim' => $mahasiswa->nim,
                'user_nama' => $mahasiswa->nama,
                'total_pesan' => $allMessages->count(),
                'message_read_status' => $messageReadStatus,
                'unread_messages' => $unreadMessages->map(function($msg) {
                    return [
                        'id' => $msg->id,
                        'pengirim_id' => $msg->pengirim_id,
                        'tipe_pengirim' => $msg->tipe_pengirim,
                        'isi_pesan' => $msg->isi_pesan,
                        'created_at' => $msg->created_at->format('Y-m-d H:i:s')
                    ];
                })
            ]);
        } catch (\Exception $e) {
            Log::error('Error saat debug grup unread messages: ' . $e->getMessage() . ' - ' . $e->getTraceAsString());
            return response()->json([
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ], 500);
        }
    }
}