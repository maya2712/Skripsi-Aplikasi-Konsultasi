<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AdminController extends Controller
{
    /**
     * Menampilkan dashboard admin
     */
    public function dashboard()
    {
        try {
            Log::info('Admin dashboard dipanggil');
            
            // Hitung jumlah data untuk statistik
            $totalMahasiswa = DB::table('mahasiswas')->count();
            $totalDosen = DB::table('dosens')->count();
            $totalUsulanBimbingan = DB::table('usulan_bimbingans')->count();
            
            // Data tambahan jika diperlukan
            $totalUsulanDiproses = DB::table('usulan_bimbingans')
                ->whereIn('status', ['DISETUJUI', 'DITOLAK'])
                ->count();
                
            $totalUsulanPending = DB::table('usulan_bimbingans')
                ->where('status', 'USULAN')
                ->count();

            Log::info('Admin data loaded, redirecting to view');
            
            return view('pesan.admin.dashboardpesan_admin', compact(
                'totalMahasiswa',
                'totalDosen',
                'totalUsulanBimbingan',
                'totalUsulanDiproses',
                'totalUsulanPending'
            ));
        } catch (\Exception $e) {
            Log::error('Error di dashboard admin: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat memuat dashboard: ' . $e->getMessage());
        }
    }
}