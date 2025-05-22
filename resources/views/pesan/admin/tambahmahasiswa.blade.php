@extends('layouts.app')
@section('title', 'Tambah Mahasiswa')
@section('content')
<div class="container">
    <h3 class="mt-3 mb-1" style="font-weight: 600; background: linear-gradient(to right, #004AAD, #5DE0E6); -webkit-background-clip: text; -webkit-text-fill-color: transparent; display: inline-block;">Tambah Mahasiswa</h3>
    <hr class="mb-4">
    
    <a href="{{ route('admin.managementuser_mahasiswa') }}" class="btn btn-sm mb-4" style="background: linear-gradient(to right, #004AAD, #5DE0E6); color: white;">
        <i class="fas fa-arrow-left"></i> Kembali
    </a>
    
    <!-- Notifikasi error validasi -->
    @if ($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif
    
    <!-- Notifikasi error lainnya -->
    @if(session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
    @endif
    
    <form action="{{ route('admin.store-mahasiswa') }}" method="POST">
        @csrf
        
        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="nim" class="form-label">NIM *</label>
                <input type="text" class="form-control @error('nim') is-invalid @enderror" id="nim" name="nim" value="{{ old('nim') }}" required>
                @error('nim')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="col-md-6 mb-3">
                <label for="nama" class="form-label">Nama *</label>
                <input type="text" class="form-control @error('nama') is-invalid @enderror" id="nama" name="nama" value="{{ old('nama') }}" required>
                @error('nama')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>
        
        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="email" class="form-label">Email *</label>
                <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" required>
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="col-md-6 mb-3">
                <label for="angkatan" class="form-label">Angkatan *</label>
                <input type="text" class="form-control @error('angkatan') is-invalid @enderror" id="angkatan" name="angkatan" value="{{ old('angkatan') }}" required>
                @error('angkatan')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>
        
        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="prodi_id" class="form-label">Program Studi *</label>
                <select class="form-select @error('prodi_id') is-invalid @enderror" id="prodi_id" name="prodi_id" required>
                    <option value="">Pilih Program Studi</option>
                    @foreach($prodis as $prodi)
                        <option value="{{ $prodi->id }}" {{ old('prodi_id') == $prodi->id ? 'selected' : '' }}>
                            {{ $prodi->nama_prodi }}
                        </option>
                    @endforeach
                </select>
                @error('prodi_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="col-md-6 mb-3">
                <label for="konsentrasi_id" class="form-label">
                    Konsentrasi 
                    <small class="text-muted">(Opsional)</small>
                </label>
                <select class="form-select @error('konsentrasi_id') is-invalid @enderror" id="konsentrasi_id" name="konsentrasi_id">
                    <option value="">-- Pilih Konsentrasi (Opsional) --</option>
                    @foreach($konsentrasis as $konsentrasi)
                        <option value="{{ $konsentrasi->id }}" {{ old('konsentrasi_id') == $konsentrasi->id ? 'selected' : '' }}>
                            {{ $konsentrasi->nama_konsentrasi }}
                        </option>
                    @endforeach
                </select>
                <small class="form-text text-muted">
                    Konsentrasi dapat dipilih nanti jika mahasiswa belum menentukan pilihan
                </small>
                @error('konsentrasi_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>
        
        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="password" class="form-label">Password *</label>
                <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" required>
                <small class="form-text text-muted">Minimal 6 karakter</small>
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