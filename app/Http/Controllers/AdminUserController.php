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
            return view('pesan.admin.managementuser_dosen', compact('dosens'));
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
            return view('pesan.admin.managementuser_mahasiswa', compact('mahasiswas'));
        } catch (\Exception $e) {
            Log::error('Error di managementMahasiswa: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat memuat data mahasiswa: ' . $e->getMessage());
        }
    }
    
    // Method untuk menampilkan form tambah dosen
    public function tambahDosen()
    {
        try {
            // Buat array prodi statis karena tabel prodis tidak ada
            $prodis = [
                ['id' => 1, 'nama_prodi' => 'Teknik Informatika'],
                ['id' => 2, 'nama_prodi' => 'Sistem Informasi'],
                ['id' => 3, 'nama_prodi' => 'Teknik Elektro']
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
                ['id' => 2, 'nama_prodi' => 'Sistem Informasi'],
                ['id' => 3, 'nama_prodi' => 'Teknik Elektro']
            ];
            
            $konsentrasis = [
                ['id' => 1, 'nama_konsentrasi' => 'Web Development'],
                ['id' => 2, 'nama_konsentrasi' => 'Mobile Development'],
                ['id' => 3, 'nama_konsentrasi' => 'Data Science']
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
}