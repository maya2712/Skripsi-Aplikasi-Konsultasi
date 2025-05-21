@extends('layouts.app')
@section('title', 'Edit Dosen')
@section('content')
<div class="container">
    <h3 class="mt-3 mb-1" style="font-weight: 600; background: linear-gradient(to right, #004AAD, #5DE0E6); -webkit-background-clip: text; -webkit-text-fill-color: transparent; display: inline-block;">Edit Dosen</h3>
    <hr class="mb-4">
    
    <a href="{{ route('admin.managementuser_dosen') }}" class="btn btn-sm mb-4" style="background: linear-gradient(to right, #004AAD, #5DE0E6); color: white;">
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
    
    <form action="{{ route('admin.update-dosen', $dosen->nip) }}" method="POST">
        @csrf
        @method('PUT')
        
        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="nip" class="form-label">NIP *</label>
                <input type="text" class="form-control" id="nip" value="{{ $dosen->nip }}" readonly>
                <small class="form-text text-muted">NIP tidak dapat diubah</small>
            </div>
            
            <div class="col-md-6 mb-3">
                <label for="nama" class="form-label">Nama Lengkap *</label>
                <input type="text" class="form-control @error('nama') is-invalid @enderror" id="nama" name="nama" value="{{ old('nama', $dosen->nama) }}" required>
                @error('nama')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>
        
        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="email" class="form-label">Email *</label>
                <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', $dosen->email) }}" required>
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="col-md-6 mb-3">
                <label for="nama_singkat" class="form-label">Nama Singkat</label>
                <input type="text" class="form-control @error('nama_singkat') is-invalid @enderror" id="nama_singkat" name="nama_singkat" value="{{ old('nama_singkat', $dosen->nama_singkat) }}">
                @error('nama_singkat')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>
        
        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="jabatan_fungsional" class="form-label">Keterangan</label>
                <input type="text" class="form-control @error('jabatan_fungsional') is-invalid @enderror" id="jabatan_fungsional" name="jabatan_fungsional" value="{{ old('jabatan_fungsional', $dosen->jabatan_fungsional) }}">
                @error('jabatan_fungsional')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="col-md-6 mb-3">
                <label for="prodi_id" class="form-label">Program Studi *</label>
                <select class="form-select @error('prodi_id') is-invalid @enderror" id="prodi_id" name="prodi_id" required>
                    <option value="">Pilih Program Studi</option>
                    @foreach($prodis as $prodi)
                        <option value="{{ $prodi->id }}" {{ old('prodi_id', $dosen->prodi_id) == $prodi->id ? 'selected' : '' }}>
                            {{ $prodi->nama_prodi }}
                        </option>
                    @endforeach
                </select>
                @error('prodi_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>
        
        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="password" class="form-label">Password (Opsional)</label>
                <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password">
                <small class="form-text text-muted">Biarkan kosong jika tidak ingin mengubah password</small>
                @error('password')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="col-md-6 d-flex align-items-end justify-content-end mb-3">
                <button type="submit" class="btn" style="background: linear-gradient(to right, #004AAD, #5DE0E6); color: white;">Simpan Perubahan</button>
            </div>
        </div>
    </form>
</div>
@endsection