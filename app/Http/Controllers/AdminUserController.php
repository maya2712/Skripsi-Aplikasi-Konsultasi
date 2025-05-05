<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Dosen;
use App\Models\Mahasiswa;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class AdminUserController extends Controller
{
    // Method untuk menampilkan halaman manajemen dosen
    public function managementDosen()
    {
        try {
            // Ambil semua data dosen
            $dosens = Dosen::all();
            
            // Buat array prodi sesuai dengan isi database
            $prodiMap = [
                1 => 'Teknik Elektro',
                2 => 'Teknik Informatika'
            ];
            
            return view('pesan.admin.managementuser_dosen', compact('dosens', 'prodiMap'));
        } catch (\Exception $e) {
            Log::error('Error di managementDosen: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat memuat data dosen: ' . $e->getMessage());
        }
    }
    
    // Method untuk menampilkan halaman manajemen mahasiswa
    public function managementMahasiswa()
    {
        try {
            // Ambil semua data mahasiswa
            $mahasiswas = Mahasiswa::all();
            
            // Buat array prodi dan konsentrasi sesuai database
            $prodiMap = [
                1 => 'Teknik Elektro',
                2 => 'Teknik Informatika'
            ];
            
            $konsentrasiMap = [
                1 => 'Web Development',
                2 => 'Mobile Development',
                3 => 'Data Science'
            ];
            
            return view('pesan.admin.managementuser_mahasiswa', compact('mahasiswas', 'prodiMap', 'konsentrasiMap'));
        } catch (\Exception $e) {
            Log::error('Error di managementMahasiswa: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat memuat data mahasiswa: ' . $e->getMessage());
        }
    }
    
    // Method untuk menampilkan form tambah dosen
    public function tambahDosen()
    {
        try {
            // Sesuaikan dengan data yang sebenarnya ada di database
            $prodis = [
                ['id' => 1, 'nama_prodi' => 'Teknik Elektro'],
                ['id' => 2, 'nama_prodi' => 'Teknik Informatika']
            ];
            
            return view('pesan.admin.tambahdosen', compact('prodis'));
        } catch (\Exception $e) {
            Log::error('Error di tambahDosen: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
    
    // Method untuk menampilkan form tambah mahasiswa
    public function tambahMahasiswa()
    {
        try {
            // Data statis untuk prodi dan konsentrasi
            $prodis = [
                ['id' => 1, 'nama_prodi' => 'Teknik Informatika'],
                ['id' => 2, 'nama_prodi' => 'Teknik Elektro'],
            ];
            
            $konsentrasis = [
                ['id' => 1, 'nama_konsentrasi' => 'Rekayasa Perangkat Lunak'],
                ['id' => 2, 'nama_konsentrasi' => 'Komputasi Cerdas dan Visi'],
                ['id' => 3, 'nama_konsentrasi' => 'Komputasi Berbasis Jaringan']
            ];
            
            return view('pesan.admin.tambahmahasiswa', compact('prodis', 'konsentrasis'));
        } catch (\Exception $e) {
            Log::error('Error di tambahMahasiswa: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
    
    // Method untuk menyimpan data dosen baru
    public function storeDosen(Request $request)
    {
        try {
            Log::info('Attempting to store dosen with data: ' . json_encode($request->except('password')));
            
            // Validasi input
            $validator = Validator::make($request->all(), [
                'nip' => 'required|unique:dosens,nip',
                'nama' => 'required',
                'email' => 'required|email|unique:dosens,email',
                'password' => 'required|min:6',
                'prodi_id' => 'required',
            ]);
            
            if ($validator->fails()) {
                Log::warning('Validation failed: ' . json_encode($validator->errors()->toArray()));
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }
            
            // Buat dosen baru
            $dosen = new Dosen();
            $dosen->nip = $request->nip;
            $dosen->nama = $request->nama;
            $dosen->email = $request->email;
            $dosen->password = Hash::make($request->password);
            
            // Field opsional
            if ($request->has('nama_singkat')) {
                $dosen->nama_singkat = $request->nama_singkat;
            }
            
            // Kolom jabatan_fungsional jika ada
            if ($request->has('jabatan_fungsional')) {
                $dosen->jabatan_fungsional = $request->jabatan_fungsional;
            }
            
            // Kolom prodi_id
            $dosen->prodi_id = $request->prodi_id;
            
            // Kolom role_id dengan nilai default (2 untuk dosen)
            $dosen->role_id = 2; // Role untuk dosen
            
            // Simpan data dosen
            $dosen->save();
            
            Log::info('Dosen berhasil disimpan dengan NIP: ' . $dosen->nip);
            
            return redirect()->route('admin.managementuser_dosen')
                ->with('success', 'Dosen berhasil ditambahkan!');
        } catch (\Exception $e) {
            Log::error('Error di storeDosen: ' . $e->getMessage());
            Log::error('Error detail: ' . $e->getTraceAsString());
            return back()->with('error', 'Terjadi kesalahan saat menyimpan data dosen: ' . $e->getMessage())
                ->withInput();
        }
    }
    
    // Method untuk menyimpan data mahasiswa baru
    public function storeMahasiswa(Request $request)
    {
        try {
            Log::info('Attempting to store mahasiswa with data: ' . json_encode($request->except('password')));
            
            // Validasi input
            $validator = Validator::make($request->all(), [
                'nim' => 'required|unique:mahasiswas,nim',
                'nama' => 'required',
                'email' => 'required|email|unique:mahasiswas,email',
                'password' => 'required|min:6',
                'angkatan' => 'required',
                'prodi_id' => 'required',
            ]);
            
            if ($validator->fails()) {
                Log::warning('Validation failed: ' . json_encode($validator->errors()->toArray()));
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }
            
            // Buat mahasiswa baru
            $mahasiswa = new Mahasiswa();
            $mahasiswa->nim = $request->nim;
            $mahasiswa->nama = $request->nama;
            $mahasiswa->email = $request->email;
            $mahasiswa->password = Hash::make($request->password);
            $mahasiswa->angkatan = $request->angkatan;
            $mahasiswa->prodi_id = $request->prodi_id;
            
            // Opsional fields
            if ($request->has('konsentrasi_id') && !empty($request->konsentrasi_id)) {
                $mahasiswa->konsentrasi_id = $request->konsentrasi_id;
            }
            
            // Kolom role_id dengan nilai default (3 untuk mahasiswa)
            $mahasiswa->role_id = 3; // Role untuk mahasiswa
            
            $mahasiswa->save();
            
            Log::info('Mahasiswa berhasil disimpan dengan NIM: ' . $mahasiswa->nim);
            
            return redirect()->route('admin.managementuser_mahasiswa')
                ->with('success', 'Mahasiswa berhasil ditambahkan!');
        } catch (\Exception $e) {
            Log::error('Error di storeMahasiswa: ' . $e->getMessage());
            Log::error('Error detail: ' . $e->getTraceAsString());
            return back()->with('error', 'Terjadi kesalahan saat menyimpan data mahasiswa: ' . $e->getMessage())
                ->withInput();
        }
    }
    
    // Method untuk menghapus dosen
    public function deleteDosen($nip)
    {
        try {
            $dosen = Dosen::where('nip', $nip)->firstOrFail();
            $dosen->delete();
            
            return redirect()->route('admin.managementuser_dosen')
                ->with('success', 'Dosen berhasil dihapus!');
        } catch (\Exception $e) {
            Log::error('Error di deleteDosen: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat menghapus dosen: ' . $e->getMessage());
        }
    }
    
    // Method untuk menghapus mahasiswa
    public function deleteMahasiswa($nim)
    {
        try {
            $mahasiswa = Mahasiswa::where('nim', $nim)->firstOrFail();
            $mahasiswa->delete();
            
            return redirect()->route('admin.managementuser_mahasiswa')
                ->with('success', 'Mahasiswa berhasil dihapus!');
        } catch (\Exception $e) {
            Log::error('Error di deleteMahasiswa: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat menghapus mahasiswa: ' . $e->getMessage());
        }
    }
    
    // Method untuk menampilkan form edit dosen
    public function editDosen($nip)
    {
        try {
            // Cari dosen berdasarkan NIP
            $dosen = Dosen::where('nip', $nip)->firstOrFail();
            
            // Sesuaikan dengan data yang sebenarnya ada di database
            $prodis = [
                ['id' => 1, 'nama_prodi' => 'Teknik Elektro'],
                ['id' => 2, 'nama_prodi' => 'Teknik Informatika']
            ];
            
            return view('pesan.admin.editdosen', compact('dosen', 'prodis'));
        } catch (\Exception $e) {
            Log::error('Error di editDosen: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat memuat data dosen: ' . $e->getMessage());
        }
    }
    
    // Method untuk update data dosen
    public function updateDosen(Request $request, $nip)
    {
        try {
            Log::info('Attempting to update dosen with NIP: ' . $nip);
            
            // Validasi input
            $validator = Validator::make($request->all(), [
                'nama' => 'required',
                'email' => 'required|email|unique:dosens,email,'.$nip.',nip',
                'prodi_id' => 'required|exists:prodi,id', // Validate prodi_id exists in prodi table
                'password' => 'nullable|min:6',
            ]);
            
            if ($validator->fails()) {
                Log::warning('Validation failed: ' . json_encode($validator->errors()->toArray()));
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }
            
            // Cari dosen berdasarkan NIP
            $dosen = Dosen::where('nip', $nip)->firstOrFail();
            
            // Update data dosen
            $dosen->nama = $request->nama;
            $dosen->email = $request->email;
            
            // Field opsional
            if ($request->has('nama_singkat')) {
                $dosen->nama_singkat = $request->nama_singkat;
            }
            
            // Kolom jabatan_fungsional jika ada
            if ($request->has('jabatan_fungsional')) {
                $dosen->jabatan_fungsional = $request->jabatan_fungsional;
            }
            
            // Kolom prodi_id - pastikan nilai valid
            $dosen->prodi_id = $request->prodi_id;
            
            // Update password jika diisi
            if ($request->filled('password')) {
                $dosen->password = Hash::make($request->password);
            }
            
            // Simpan perubahan
            $dosen->save();
            
            Log::info('Dosen berhasil diperbarui dengan NIP: ' . $dosen->nip);
            
            return redirect()->route('admin.managementuser_dosen')
                ->with('success', 'Data dosen berhasil diperbarui!');
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation error: ' . $e->getMessage());
            return back()->withErrors($e->validator)->withInput();
        } catch (\Illuminate\Database\QueryException $e) {
            Log::error('Database error: ' . $e->getMessage());
            // Pesan error yang lebih spesifik untuk foreign key constraint
            if (str_contains($e->getMessage(), 'foreign key constraint')) {
                return back()->with('error', 'ID Program Studi tidak valid. Silahkan pilih Program Studi yang tersedia.')->withInput();
            }
            return back()->with('error', 'Terjadi kesalahan database: ' . $e->getMessage())->withInput();
        } catch (\Exception $e) {
            Log::error('Error di updateDosen: ' . $e->getMessage());
            Log::error('Error detail: ' . $e->getTraceAsString());
            return back()->with('error', 'Terjadi kesalahan saat memperbarui data dosen: ' . $e->getMessage())
                ->withInput();
        }
    }
    
    // Method untuk menampilkan form edit mahasiswa
    public function editMahasiswa($nim)
    {
        try {
            // Cari mahasiswa berdasarkan NIM
            $mahasiswa = Mahasiswa::where('nim', $nim)->firstOrFail();
            
            // Data statis untuk prodi dan konsentrasi
            $prodis = [
                ['id' => 1, 'nama_prodi' => 'Teknik Elektro'],
                ['id' => 2, 'nama_prodi' => 'Teknik Informatika']
            ];
            
            $konsentrasis = [
                ['id' => 1, 'nama_konsentrasi' => 'Web Development'],
                ['id' => 2, 'nama_konsentrasi' => 'Mobile Development'],
                ['id' => 3, 'nama_konsentrasi' => 'Data Science']
            ];
            
            return view('pesan.admin.editmahasiswa', compact('mahasiswa', 'prodis', 'konsentrasis'));
        } catch (\Exception $e) {
            Log::error('Error di editMahasiswa: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat memuat data mahasiswa: ' . $e->getMessage());
        }
    }
    
    // Method untuk update data mahasiswa
    public function updateMahasiswa(Request $request, $nim)
    {
        try {
            Log::info('Attempting to update mahasiswa with NIM: ' . $nim);
            
            // Validasi input
            $validator = Validator::make($request->all(), [
                'nama' => 'required',
                'email' => 'required|email|unique:mahasiswas,email,'.$nim.',nim',
                'angkatan' => 'required',
                'prodi_id' => 'required|exists:prodi,id',
                'password' => 'nullable|min:6',
            ]);
            
            if ($validator->fails()) {
                Log::warning('Validation failed: ' . json_encode($validator->errors()->toArray()));
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }
            
            // Cari mahasiswa berdasarkan NIM
            $mahasiswa = Mahasiswa::where('nim', $nim)->firstOrFail();
            
            // Update data mahasiswa
            $mahasiswa->nama = $request->nama;
            $mahasiswa->email = $request->email;
            $mahasiswa->angkatan = $request->angkatan;
            $mahasiswa->prodi_id = $request->prodi_id;
            
            // Konsentrasi (opsional)
            if ($request->has('konsentrasi_id') && !empty($request->konsentrasi_id)) {
                $mahasiswa->konsentrasi_id = $request->konsentrasi_id;
            } else {
                $mahasiswa->konsentrasi_id = null;
            }
            
            // Update password jika diisi
            if ($request->filled('password')) {
                $mahasiswa->password = Hash::make($request->password);
            }
            
            // Simpan perubahan
            $mahasiswa->save();
            
            Log::info('Mahasiswa berhasil diperbarui dengan NIM: ' . $mahasiswa->nim);
            
            return redirect()->route('admin.managementuser_mahasiswa')
                ->with('success', 'Data mahasiswa berhasil diperbarui!');
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation error: ' . $e->getMessage());
            return back()->withErrors($e->validator)->withInput();
        } catch (\Illuminate\Database\QueryException $e) {
            Log::error('Database error: ' . $e->getMessage());
            // Pesan error yang lebih spesifik untuk foreign key constraint
            if (str_contains($e->getMessage(), 'foreign key constraint')) {
                return back()->with('error', 'ID Program Studi atau Konsentrasi tidak valid. Silahkan pilih yang tersedia.')->withInput();
            }
            return back()->with('error', 'Terjadi kesalahan database: ' . $e->getMessage())->withInput();
        } catch (\Exception $e) {
            Log::error('Error di updateMahasiswa: ' . $e->getMessage());
            Log::error('Error detail: ' . $e->getTraceAsString());
            return back()->with('error', 'Terjadi kesalahan saat memperbarui data mahasiswa: ' . $e->getMessage())
                ->withInput();
        }
    }
    
    /**
     * Menghapus multiple mahasiswa berdasarkan NIM yang dipilih
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function deleteMultipleMahasiswa(Request $request)
    {
        try {
            // Validasi request
            $validator = Validator::make($request->all(), [
                'nims' => 'required|array',
                'nims.*' => 'exists:mahasiswas,nim'
            ]);
            
            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validasi gagal: ' . $validator->errors()->first()
                ]);
            }
            
            $nims = $request->nims;
            $count = count($nims);
            
            // Hapus semua mahasiswa yang dipilih
            Mahasiswa::whereIn('nim', $nims)->delete();
            
            // Log aktivitas
            Log::info('Admin menghapus ' . $count . ' mahasiswa dengan NIM: ' . implode(', ', $nims));
            
            return response()->json([
                'success' => true,
                'message' => $count . ' mahasiswa berhasil dihapus',
                'count' => $count
            ]);
        } catch (\Exception $e) {
            Log::error('Error di deleteMultipleMahasiswa: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ]);
        }
    }
    
    /**
     * Menampilkan form konfirmasi reset password dosen
     */
    public function showResetPassword($nip)
    {
        try {
            // Cari dosen berdasarkan NIP
            $dosen = Dosen::where('nip', $nip)->firstOrFail();
            return view('pesan.admin.reset_password_dosen', compact('dosen'));
        } catch (\Exception $e) {
            Log::error('Error di showResetPassword: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat memuat data dosen: ' . $e->getMessage());
        }
    }
    
    /**
     * Melakukan reset password dosen
     */
    public function resetPassword(Request $request, $nip)
    {
        try {
            // Validasi input
            $validator = Validator::make($request->all(), [
                'new_password' => 'required|min:6|confirmed',
            ]);
            
            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }
            
            // Cari dosen berdasarkan NIP
            $dosen = Dosen::where('nip', $nip)->firstOrFail();
            
            // Update password
            $dosen->password = Hash::make($request->new_password);
            $dosen->save();
            
            Log::info('Password untuk dosen ' . $dosen->nama . ' (NIP: ' . $dosen->nip . ') berhasil direset');
            
            // Redirect kembali ke halaman manajemen dosen dengan pesan sukses
            return redirect()->route('admin.managementuser_dosen')
                ->with('success', 'Password untuk ' . $dosen->nama . ' berhasil direset!');
        } catch (\Exception $e) {
            Log::error('Error di resetPassword: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat mereset password: ' . $e->getMessage());
        }
    }
}