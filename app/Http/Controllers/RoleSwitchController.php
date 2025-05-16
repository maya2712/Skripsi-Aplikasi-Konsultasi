<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class RoleSwitchController extends Controller
{
    public function switchRole(Request $request)
    {
        $user = Auth::guard('dosen')->user();
        
        // Log informasi dosen
        Log::info('Dosen info saat switch:', [
            'nip' => $user->nip,
            'nama' => $user->nama,
            'jabatan_fungsional' => $user->jabatan_fungsional
        ]);
        
        // Toggle role tanpa pemeriksaan
        $currentRole = session('active_role', 'dosen');
        $newRole = ($currentRole === 'dosen') ? 'kaprodi' : 'dosen';
        
        // Set session secara langsung
        session(['active_role' => $newRole]);
        session()->save(); // Memastikan session disimpan
        
        Log::info('Role switched to: ' . $newRole);
        
        return redirect()->back()->with('success', 'Berhasil beralih ke mode ' . ucfirst($newRole));
    }
}