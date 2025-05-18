<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cookie;
use App\Models\Mahasiswa;
use App\Models\Dosen;
use App\Models\User;
use App\Models\Admin;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        // Validasi input
        $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);
        
        $identifier = $request->username;
        $password = $request->password;
        
        Log::info('Mencoba login dengan: ' . $identifier);
    
        // Cek admin dengan tabel admins yang baru
        $admin = Admin::where('email', $identifier)->first();
        if ($admin) {
            Log::info('Admin ditemukan: ' . $admin->email . ' dengan ID: ' . $admin->id);
            
            if (Hash::check($password, $admin->password)) {
                Log::info('Password benar untuk admin: ' . $admin->email);
                
                // Login dengan Auth::guard
                Auth::guard('admin')->login($admin);
                
                // Set session role
                $request->session()->put('role', 'admin');
                $request->session()->regenerate(); // Regenerasi ID session untuk keamanan
                $request->session()->save(); // Pastikan session disimpan
                
                // Log status auth setelah login
                Log::info('Login sebagai admin berhasil, Auth check: ' . (Auth::guard('admin')->check() ? 'true' : 'false'));
                Log::info('Session role: ' . session('role'));
                
                // Redirect ke dashboard admin
                return redirect('/admin/dashboard');
            } else {
                Log::warning('Password salah untuk admin: ' . $admin->email);
            }
        } else {
            Log::info('Admin tidak ditemukan dengan email: ' . $identifier);
        }

        // Cek mahasiswa
        $mahasiswa = Mahasiswa::where('nim', $identifier)->first();
        if ($mahasiswa && Hash::check($password, $mahasiswa->password)) {
            Auth::guard('mahasiswa')->login($mahasiswa);
            $request->session()->put('role', 'mahasiswa');
            $request->session()->regenerate();
            $request->session()->save();
            Log::info('Login berhasil untuk mahasiswa: ' . $mahasiswa->nim);
            return redirect('/dashboardpesanmahasiswa');
        }

        // Cek dosen
        $dosen = Dosen::where('nip', $identifier)->first();
        if ($dosen && Hash::check($password, $dosen->password)) {
            Auth::guard('dosen')->login($dosen);
            $request->session()->put('role', 'dosen');
            
            // Cek secara eksplisit kolom jabatan_fungsional
            $isKaprodi = false;
            if (!empty($dosen->jabatan_fungsional)) {
                $jabatan = strtolower($dosen->jabatan_fungsional);
                if (strpos($jabatan, 'kaprodi') !== false || 
                    strpos($jabatan, 'ketua') !== false || 
                    strpos($jabatan, 'kepala program') !== false) {
                    $isKaprodi = true;
                }
            }
            
            // Simpan ke session
            $request->session()->put('is_kaprodi', $isKaprodi);
            $request->session()->put('active_role', 'dosen'); // Default role
            $request->session()->regenerate();
            $request->session()->save();
            
            Log::info('Login berhasil untuk dosen, status kaprodi: ' . ($isKaprodi ? 'Ya' : 'Tidak'), [
                'nip' => $dosen->nip,
                'nama' => $dosen->nama,
                'jabatan_fungsional' => $dosen->jabatan_fungsional
            ]);
            
            return redirect('/dashboardpesandosen');
        }

        // Jika login gagal
        Log::warning('Login gagal untuk: ' . $identifier);
        return back()
            ->withInput($request->only('username'))
            ->withErrors([
                'login' => 'Email/NIP/NIM atau password salah.'
            ]);
    }

    public function logout(Request $request)
    {
        // Log informasi sebelum logout
        $guard = session('role');
        Log::info('Logout dimulai untuk guard: ' . $guard);
        
        // Logout dari semua guard secara eksplisit
        Auth::guard('mahasiswa')->logout();
        Auth::guard('dosen')->logout();
        Auth::guard('admin')->logout();
        
        // Hapus session secara menyeluruh
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        // Hapus semua cookie autentikasi
        $cookies = $request->cookie();
        foreach ($cookies as $name => $value) {
            if (strpos($name, 'laravel_session') !== false || strpos($name, 'remember_') !== false) {
                Cookie::queue(Cookie::forget($name));
            }
        }
        
        Log::info('Logout berhasil');
        
        // Redirect dengan header khusus untuk mencegah caching
        return redirect()->route('login')
            ->with('success', 'Anda telah berhasil keluar dari sistem.')
            ->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0')
            ->header('Pragma', 'no-cache')
            ->header('Expires', 'Sat, 01 Jan 2000 00:00:00 GMT');
    }
}