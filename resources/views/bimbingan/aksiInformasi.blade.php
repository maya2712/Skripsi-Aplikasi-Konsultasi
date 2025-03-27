@extends('layouts.app')
@section('title', 'Detail Mahasiswa')

@push('styles')
<style>
    .status-badge {
        display: inline-block;
        margin-top: 10px;
        padding: 5px 15px;
        color: white;
        border-radius: 5px;
        font-size: 0.9em;
    }
    
    .text-bold {
        font-weight: 600;
    }
</style>
@endpush

@section('content')
<div class="container mt-5">
    <h1 class="mb-2 gradient-text fw-bold">Detail Mahasiswa</h1>
    <hr>
    <button class="btn btn-gradient mb-4 mt-2 d-flex align-items-center justify-content-center">
        <a href="{{ url('/usulanbimbingan') }}">
            <i class="fas fa-arrow-left me-2"></i> Kembali
        </a>
    </button>

    <div class="container">
        <div class="row">
            <!-- Mahasiswa Info Card -->
            <div class="col-lg-6 col-md-12 bg-white rounded-start px-4 py-3 mb-2 shadow-sm">
                <h5 class="text-bold">Mahasiswa</h5>
                <hr>
                <p class="card-title text-muted text-sm">Nama</p>
                <p class="card-text text-start">{{ $usulan->mahasiswa_nama }}</p>
                <p class="card-title text-muted text-sm">NIM</p>
                <p class="card-text text-start">{{ $usulan->nim }}</p>
                <p class="card-title text-muted text-sm">Program Studi</p>
                <p class="card-text text-start">{{ $usulan->nama_prodi }}</p>
                <p class="card-title text-muted text-sm">Konsentrasi</p>
                <p class="card-text text-start">{{ $usulan->nama_konsentrasi }}</p>
            </div>

            <!-- Dosen Pembimbing Card -->
            <div class="col-lg-6 col-md-12 bg-white rounded-end px-4 py-3 mb-2 shadow-sm">
                <h5 class="text-bold">Dosen Pembimbing</h5>
                <hr>
                <p class="card-title text-secondary text-sm">Nama Pembimbing</p>
                <p class="card-text text-start">{{ $usulan->dosen_nama }}</p>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="row">
            <!-- Usulan Jadwal Card -->
            <div class="col-lg-6 col-md-12 px-4 py-3 mb-2 bg-white rounded-start shadow-sm">
                <h5 class="text-bold">Data Usulan Jadwal Bimbingan</h5>
                <hr>
                <p class="card-title text-muted text-sm">Jenis Bimbingan</p>
                <p class="card-text text-start">{{ ucfirst($usulan->jenis_bimbingan) }}</p>
                <p class="card-title text-muted text-sm">Tanggal</p>
                <p class="card-text text-start">{{ $tanggal }}</p>
                <p class="card-title text-muted text-sm">Waktu</p>
                <p class="card-text text-start">{{ $waktuMulai }} - {{ $waktuSelesai }}</p>
                <p class="card-title text-muted text-sm">Lokasi</p>
                <p class="card-text text-start">{{ $usulan->lokasi ?? '-' }}</p>
                <p class="card-title text-muted text-sm">Deskripsi</p>
                <p class="card-text text-start">{{ $usulan->deskripsi ?? '-' }}</p>
            </div>

            <!-- Keterangan Usulan Card -->
            <div class="col-lg-6 col-md-12 px-4 py-3 mb-2 bg-white rounded-end shadow-sm">
                <h5 class="text-bold">Keterangan Usulan</h5>
                <hr>
                <p class="card-title text-secondary text-sm">Status Usulan</p>
                <p class="card-text text-start">
                    <span class="status-badge {{ $statusBadgeClass }}">{{ strtoupper($usulan->status) }}</span>
                </p>
                <p class="card-title text-secondary text-sm">Keterangan</p>
                <p class="card-text text-start">{{ $usulan->keterangan ?? 'Belum ada keterangan' }}</p>
                @if($usulan->created_at)
                <p class="card-title text-secondary text-sm">Diajukan pada</p>
                <p class="card-text text-start">{{ \Carbon\Carbon::parse($usulan->created_at)->format('d/m/Y H:i') }}</p>
                @endif
                @if($usulan->updated_at && $usulan->status !== 'USULAN')
                <p class="card-title text-secondary text-sm">Terakhir diupdate</p>
                <p class="card-text text-start">{{ \Carbon\Carbon::parse($usulan->updated_at)->format('d/m/Y H:i') }}</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection