<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class CheckRole
{
    public function handle(Request $request, Closure $next, $role)
    {
        Log::info('CheckRole middleware called with role: ' . $role);
        Log::info('Session role: ' . session('role'));
        
        // PERBAIKAN: Gunakan URL sebagai string, bukan objek Route
        Log::info('Current URL: ' . $request->url());
        Log::info('Request method: ' . $request->method());
        
        // Admin check
        if ($role === 'admin') {
            Log::info('Admin guard check: ' . (Auth::guard('admin')->check() ? 'true' : 'false'));
            if (Auth::guard('admin')->check()) {
                Log::info('Admin authenticated via guard check');
                return $next($request);
            }
        }
        
        // Dosen check
        if ($role === 'dosen') {
            Log::info('Dosen guard check: ' . (Auth::guard('dosen')->check() ? 'true' : 'false'));
            if (Auth::guard('dosen')->check()) {
                Log::info('Dosen authenticated via guard check');
                // Jangan gunakan json_encode langsung pada objek User
                // Gunakan hanya atribut tertentu yang Anda butuhkan
                $dosen = Auth::guard('dosen')->user();
                Log::info('Dosen data: NIP=' . $dosen->nip . ', Nama=' . $dosen->nama);
                return $next($request);
            }
        }
        
        // Mahasiswa check
        if ($role === 'mahasiswa') {
            Log::info('Mahasiswa guard check: ' . (Auth::guard('mahasiswa')->check() ? 'true' : 'false'));
            if (Auth::guard('mahasiswa')->check()) {
                Log::info('Mahasiswa authenticated via guard check');
                return $next($request);
            }
        }
        
        // Session role check (fallback)
        if (session('role') === $role) {
            Log::info('Authentication via session role: ' . session('role'));
            return $next($request);
        }
        
        Log::warning('Authentication failed for role: ' . $role);
        return redirect()->route('login');
    }
}