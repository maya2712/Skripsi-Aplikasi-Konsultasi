<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PreventBackHistory
{
    public function handle(Request $request, Closure $next)
    {
        // Cek apakah user masih terautentikasi
        $isAuthenticated = Auth::guard('admin')->check() || 
                          Auth::guard('dosen')->check() || 
                          Auth::guard('mahasiswa')->check();
        
        // Jika tidak terautentikasi, redirect ke login
        if (!$isAuthenticated) {
            return redirect()->route('login')
                ->with('error', 'Sesi Anda telah berakhir. Silakan login kembali.')
                ->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0')
                ->header('Pragma', 'no-cache')
                ->header('Expires', 'Sat, 01 Jan 2000 00:00:00 GMT');
        }
        
        $response = $next($request);
        
        // Set header untuk mencegah browser cache halaman yang dilindungi
        return $response->header('Cache-Control', 'no-store, no-cache, max-age=0, must-revalidate')
            ->header('Pragma', 'no-cache')
            ->header('Expires', 'Sun, 02 Jan 1990 00:00:00 GMT')
            ->header('Clear-Site-Data', '"cache"'); // Tambahan untuk clear cache
    }
}