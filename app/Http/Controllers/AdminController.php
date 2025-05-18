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
        // Tambahkan kode debug ini di awal method
        Log::info('Admin dashboard dipanggil');
        Log::info('Session role: ' . session('role'));
        Log::info('Auth admin check: ' . (Auth::guard('admin')->check() ? 'true' : 'false'));
        
        if (Auth::guard('admin')->check()) {
            Log::info('Admin user: ' . Auth::guard('admin')->user()->email);
        } else {
            Log::info('Admin tidak terautentikasi');
            // Auto-redirect ke login jika admin tidak terautentikasi
            return redirect()->route('login');
        }
        
        // Kode asli dashboard Anda
        try {
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
                
            // Data untuk grafik distribusi mahasiswa per angkatan
            $mahasiswaPerAngkatan = DB::table('mahasiswas')
                ->select('angkatan', DB::raw('count(*) as total'))
                ->groupBy('angkatan')
                ->orderBy('angkatan')
                ->get();
            
            // Format data untuk Chart.js
            $angkatanLabels = [];
            $angkatanData = [];
            $angkatanColors = [
                'rgba(26, 115, 232, 0.8)',
                'rgba(66, 133, 244, 0.8)',
                'rgba(94, 168, 244, 0.8)',
                'rgba(138, 201, 254, 0.8)',
                'rgba(179, 229, 252, 0.8)',
                'rgba(225, 245, 254, 0.8)'
            ];
            
            $colorIndex = 0;
            $angkatanBackgroundColors = [];
            
            foreach ($mahasiswaPerAngkatan as $data) {
                $angkatanLabels[] = $data->angkatan;
                $angkatanData[] = $data->total;
                
                // Rotating colors for the chart
                $angkatanBackgroundColors[] = $angkatanColors[$colorIndex % count($angkatanColors)];
                $colorIndex++;
            }
            
            // Data untuk latar belakang grafik bar (warna hijau)
            $barBackgroundColors = [
                'rgba(39, 174, 96, 0.8)',
                'rgba(46, 204, 113, 0.8)',
                'rgba(88, 214, 141, 0.8)',
                'rgba(171, 235, 198, 0.8)',
                'rgba(200, 247, 220, 0.8)',
                'rgba(220, 252, 231, 0.8)'
            ];

            Log::info('Admin data loaded, redirecting to view');
            
            return view('pesan.admin.dashboardpesan_admin', compact(
                'totalMahasiswa',
                'totalDosen',
                'totalUsulanBimbingan',
                'totalUsulanDiproses',
                'totalUsulanPending',
                'angkatanLabels',
                'angkatanData',
                'angkatanBackgroundColors',
                'barBackgroundColors'
            ));
        } catch (\Exception $e) {
            Log::error('Error di dashboard admin: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat memuat dashboard: ' . $e->getMessage());
        }
    }
}