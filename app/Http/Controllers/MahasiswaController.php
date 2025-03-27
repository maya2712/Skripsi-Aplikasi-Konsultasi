<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class MahasiswaController extends Controller
{   

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
            };
            
            \Log::info('Data usulan ditemukan', ['usulan' => $usulan]);

            // Kirim data ke view
            return view('bimbingan.aksiInformasi', compact(
                'usulan',
                'tanggal',
                'waktuMulai',
                'waktuSelesai',
                'statusBadgeClass'
            ));

        } catch (\Exception $e) {
            \Log::error('Error di getDetailBimbingan: ' . $e->getMessage());
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

    public function getDetailDaftar($nip, Request $request)
    {
        try {
            $perPage = $request->input('per_page', 10);
            
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
                ->paginate($perPage);

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
            return back()->with('error', 'Gagal memuat riwayat bimbingan');
        }
    }
}