@extends('layouts.app')
@section('title', 'Tambah Dosen')
@section('content')
<div class="container">
    <h3 class="mt-3 mb-1" style="font-weight: 600; background: linear-gradient(to right, #004AAD, #5DE0E6); -webkit-background-clip: text; -webkit-text-fill-color: transparent; display: inline-block;">Tambah Dosen</h3>
    <hr class="mb-4">
    
    <a href="{{ url('/admin/managementuser_dosen') }}" class="btn btn-sm mb-4" style="background: linear-gradient(to right, #004AAD, #5DE0E6); color: white;">
        <i class="fas fa-arrow-left"></i> Kembali
    </a>
    
    <form action="{{ url('/admin/store-dosen') }}" method="POST">
        @csrf
        
        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="nip" class="form-label">NIP</label>
                <input type="text" class="form-control @error('nip') is-invalid @enderror" id="nip" name="nip" value="{{ old('nip') }}">
                @error('nip')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="col-md-6 mb-3">
                <label for="nama" class="form-label">Nama Lengkap</label>
                <input type="text" class="form-control @error('nama') is-invalid @enderror" id="nama" name="nama" value="{{ old('nama') }}">
                @error('nama')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>
        
        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}">
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="col-md-6 mb-3">
                <label for="jabatan" class="form-label">Jabatan</label>
                <select class="form-select @error('jabatan') is-invalid @enderror" id="jabatan" name="jabatan">
                    <option selected>Dosen Tetap</option>
                    <option>Dosen Tidak Tetap</option>
                    <option>Profesor</option>
                    <option>Asisten Ahli</option>
                    <option>Lektor</option>
                    <option>Lektor Kepala</option>
                </select>
                @error('jabatan')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>
        
        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="jurusan" class="form-label">Jurusan</label>
                <select class="form-select @error('jurusan') is-invalid @enderror" id="jurusan" name="jurusan">
                    <option selected>Teknik Informatika</option>
                    <option>Sistem Informasi</option>
                    <option>Teknik Elektro</option>
                </select>
                @error('jurusan')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="col-md-6 mb-3">
                <label for="bidang_keahlian" class="form-label">Bidang Keahlian</label>
                <select class="form-select @error('bidang_keahlian') is-invalid @enderror" id="bidang_keahlian" name="bidang_keahlian">
                    <option selected>-Belum Dipilih-</option>
                    <option>Pemrograman Web</option>
                    <option>Kecerdasan Buatan</option>
                    <option>Jaringan Komputer</option>
                    <option>Keamanan Sistem</option>
                    <option>Komputasi Awan</option>
                    <option>Data Science</option>
                </select>
                @error('bidang_keahlian')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>
        
        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password">
                @error('password')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="col-md-6 d-flex align-items-end justify-content-end mb-3">
                <button type="submit" class="btn" style="background: linear-gradient(to right, #004AAD, #5DE0E6); color: white;">Tambah</button>
            </div>
        </div>
    </form>
</div>
@endsection