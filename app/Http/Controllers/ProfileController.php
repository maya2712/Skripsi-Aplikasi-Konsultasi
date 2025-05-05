<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

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
        
        // Tentukan guard yang aktif (mahasiswa, dosen, atau admin)
        $guard = null;
        $user = null;
        
        if (Auth::guard('mahasiswa')->check()) {
            $guard = 'mahasiswa';
            $user = Auth::guard('mahasiswa')->user();
        } elseif (Auth::guard('dosen')->check()) {
            $guard = 'dosen';
            $user = Auth::guard('dosen')->user();
        } elseif (Auth::guard('admin')->check()) {
            $guard = 'admin';
            $user = Auth::guard('admin')->user();
        } else {
            // Jika tidak ada yang login, redirect ke halaman login
            return redirect()->route('login');
        }
        
        // Log untuk debugging
        Log::info('Profil diakses oleh ' . $guard . ': ' . $user->email);
        
        // Return view dengan data yang dibutuhkan
        return view('components.profil', compact('guard', 'user', 'prodiMap', 'konsentrasiMap'));
    }
}