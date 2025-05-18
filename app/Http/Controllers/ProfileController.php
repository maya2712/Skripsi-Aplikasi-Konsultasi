<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class ProfileController extends Controller
{
    /**
     * Menampilkan halaman profil pengguna yang login
     */
    public function index()
    {
        // Definisikan array untuk mapping prodi dan konsentrasi
        $prodiMap = [
            1 => 'Teknik Elektro',
            2 => 'Teknik Informatika'
        ];
        
        $konsentrasiMap = [
            1 => 'Web Development',
            2 => 'Mobile Development',
            3 => 'Data Science'
        ];
        
        // Tambahkan logging untuk session role
        Log::info('Session role saat ini: ' . session('role'));
        
        // Gunakan session role sebagai prioritas untuk menentukan guard yang aktif
        $guard = session('role') ?: null;
        $user = null;
        
        // Tentukan guard berdasarkan session role
        if ($guard === 'mahasiswa' && Auth::guard('mahasiswa')->check()) {
            $user = Auth::guard('mahasiswa')->user();
            Log::info('Menggunakan guard mahasiswa dari session: ' . $user->nim);
        } 
        elseif ($guard === 'dosen' && Auth::guard('dosen')->check()) {
            $user = Auth::guard('dosen')->user();
            Log::info('Menggunakan guard dosen dari session: ' . $user->nip);
        } 
        elseif ($guard === 'admin' && Auth::guard('admin')->check()) {
            $user = Auth::guard('admin')->user();
            Log::info('Menggunakan guard admin dari session: ' . $user->id);
        }
        // Fallback ke pengecekan guard secara manual jika session tidak tersedia
        else {
            Log::warning('Session role tidak cocok dengan guard yang terautentikasi, melakukan pengecekan manual');
            
            // Pengecekan manual guard
            if (Auth::guard('mahasiswa')->check()) {
                $guard = 'mahasiswa';
                $user = Auth::guard('mahasiswa')->user();
                Log::info('Guard mahasiswa aktif secara manual: ' . $user->nim);
                
                // Perbarui session role
                session(['role' => 'mahasiswa']);
            } 
            elseif (Auth::guard('dosen')->check()) {
                $guard = 'dosen';
                $user = Auth::guard('dosen')->user();
                Log::info('Guard dosen aktif secara manual: ' . $user->nip);
                
                // Perbarui session role
                session(['role' => 'dosen']);
            } 
            elseif (Auth::guard('admin')->check()) {
                $guard = 'admin';
                $user = Auth::guard('admin')->user();
                Log::info('Guard admin aktif secara manual: ' . $user->id);
                
                // Perbarui session role
                session(['role' => 'admin']);
            } 
            else {
                Log::warning('Tidak ada guard yang aktif di ProfileController');
                return redirect()->route('login');
            }
        }
        
        // Log untuk debugging
        Log::info('Profil diakses oleh ' . $guard . ': ' . $user->email);
        
        // Return view dengan data yang dibutuhkan
        return view('components.profil', compact('guard', 'user', 'prodiMap', 'konsentrasiMap'));
    }
}