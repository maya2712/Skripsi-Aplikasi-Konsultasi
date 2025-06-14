<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

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
        
        // Cek dan siapkan URL foto profil atau SVG default
        $profilePhotoUrl = null;
        $hasCustomPhoto = false;
        
        if ($user && !empty($user->profile_photo)) {
            // Cek apakah file foto benar-benar ada
            if (Storage::disk('public')->exists('profile_photos/' . $user->profile_photo)) {
                $profilePhotoUrl = asset('storage/profile_photos/' . $user->profile_photo);
                $hasCustomPhoto = true;
            }
        }
        
        // Jika tidak ada foto custom, akan menggunakan SVG default di view
        
        // Log untuk debugging
        Log::info('Profil diakses oleh ' . $guard . ': ' . $user->email);
        
        // Return view dengan data yang dibutuhkan
        return view('components.profil', compact('guard', 'user', 'prodiMap', 'konsentrasiMap', 'profilePhotoUrl', 'hasCustomPhoto'));
    }

    /**
     * Upload foto profil dengan penyimpanan ke database
     */
    public function uploadPhoto(Request $request)
    {
        // Validasi file
        $request->validate([
            'photo' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // Tentukan guard yang aktif
        $guard = session('role');
        $user = null;

        if ($guard === 'mahasiswa' && Auth::guard('mahasiswa')->check()) {
            $user = Auth::guard('mahasiswa')->user();
            $id_field = 'nim';
            $id_value = $user->nim;
            $model = 'App\\Models\\Mahasiswa';
        } elseif ($guard === 'dosen' && Auth::guard('dosen')->check()) {
            $user = Auth::guard('dosen')->user();
            $id_field = 'nip';
            $id_value = $user->nip;
            $model = 'App\\Models\\Dosen';
        } elseif ($guard === 'admin' && Auth::guard('admin')->check()) {
            $user = Auth::guard('admin')->user();
            $id_field = 'id';
            $id_value = $user->id;
            $model = 'App\\Models\\Admin';
        } else {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        try {
            // Hapus foto lama jika ada
            if ($user->profile_photo && Storage::disk('public')->exists('profile_photos/' . $user->profile_photo)) {
                Storage::disk('public')->delete('profile_photos/' . $user->profile_photo);
                Log::info('Foto lama dihapus: ' . $user->profile_photo);
            }

            // Generate nama file unik
            $fileName = $guard . '_' . $id_value . '_' . time() . '.' . $request->photo->extension();
            
            // Simpan file ke storage/app/public/profile_photos
            $path = $request->photo->storeAs('profile_photos', $fileName, 'public');
            
            if (!$path) {
                throw new \Exception('Gagal menyimpan file ke storage');
            }

            // Update database dengan nama file
            $modelClass = $model;
            $updated = $modelClass::where($id_field, $id_value)->update([
                'profile_photo' => $fileName,
                'updated_at' => now()
            ]);

            if (!$updated) {
                // Jika gagal update database, hapus file yang sudah diupload
                Storage::disk('public')->delete('profile_photos/' . $fileName);
                throw new \Exception('Gagal menyimpan data ke database');
            }

            Log::info('Foto profil berhasil diupload dan disimpan ke database', [
                'user_type' => $guard,
                'user_id' => $id_value,
                'filename' => $fileName,
                'file_path' => $path
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Foto profil berhasil diupload dan disimpan',
                'photo_url' => asset('storage/profile_photos/' . $fileName),
                'filename' => $fileName
            ]);

        } catch (\Exception $e) {
            Log::error('Error uploading profile photo: ' . $e->getMessage(), [
                'user_type' => $guard,
                'user_id' => $id_value,
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'error' => 'Gagal mengupload foto: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Hapus foto profil
     */
    public function deletePhoto()
    {
        // Tentukan guard yang aktif
        $guard = session('role');
        $user = null;

        if ($guard === 'mahasiswa' && Auth::guard('mahasiswa')->check()) {
            $user = Auth::guard('mahasiswa')->user();
            $id_field = 'nim';
            $id_value = $user->nim;
            $model = 'App\\Models\\Mahasiswa';
        } elseif ($guard === 'dosen' && Auth::guard('dosen')->check()) {
            $user = Auth::guard('dosen')->user();
            $id_field = 'nip';
            $id_value = $user->nip;
            $model = 'App\\Models\\Dosen';
        } elseif ($guard === 'admin' && Auth::guard('admin')->check()) {
            $user = Auth::guard('admin')->user();
            $id_field = 'id';
            $id_value = $user->id;
            $model = 'App\\Models\\Admin';
        } else {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        try {
            // Hapus file dari storage jika ada
            if ($user->profile_photo && Storage::disk('public')->exists('profile_photos/' . $user->profile_photo)) {
                Storage::disk('public')->delete('profile_photos/' . $user->profile_photo);
                Log::info('File foto dihapus: ' . $user->profile_photo);
            }

            // Update database - set profile_photo ke null
            $modelClass = $model;
            $modelClass::where($id_field, $id_value)->update([
                'profile_photo' => null,
                'updated_at' => now()
            ]);

            Log::info('Foto profil berhasil dihapus', [
                'user_type' => $guard,
                'user_id' => $id_value
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Foto profil berhasil dihapus'
            ]);

        } catch (\Exception $e) {
            Log::error('Error deleting profile photo: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => 'Gagal menghapus foto: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Proses perubahan password
     */
    public function changePassword(Request $request)
    {
        // Validasi form
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:6|confirmed',
            'new_password_confirmation' => 'required'
        ]);
        
        // Tentukan guard yang aktif
        $guard = session('role');
        $user = null;
        
        if ($guard === 'mahasiswa' && Auth::guard('mahasiswa')->check()) {
            $user = Auth::guard('mahasiswa')->user();
            $model = 'App\\Models\\Mahasiswa';
            $id_field = 'nim';
            $id_value = $user->nim;
        } elseif ($guard === 'dosen' && Auth::guard('dosen')->check()) {
            $user = Auth::guard('dosen')->user();
            $model = 'App\\Models\\Dosen';
            $id_field = 'nip';
            $id_value = $user->nip;
        } elseif ($guard === 'admin' && Auth::guard('admin')->check()) {
            $user = Auth::guard('admin')->user();
            $model = 'App\\Models\\Admin';
            $id_field = 'id';
            $id_value = $user->id;
        } else {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        
        // Verifikasi password lama
        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Password saat ini salah'
            ]);
        }
        
        try {
            // Update password
            $modelClass = $model;
            $modelClass::where($id_field, $id_value)->update([
                'password' => Hash::make($request->new_password)
            ]);
            
            // Log perubahan password
            Log::info('Password changed for user: ' . $user->email);
            
            return response()->json([
                'success' => true,
                'message' => 'Password berhasil diubah'
            ]);
        } catch (\Exception $e) {
            Log::error('Error changing password: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengubah password: ' . $e->getMessage()
            ]);
        }
    }
}