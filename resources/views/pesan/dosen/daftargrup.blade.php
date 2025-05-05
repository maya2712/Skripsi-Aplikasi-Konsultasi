@extends('layouts.app')

@section('title', 'Daftar Grup')

@push('styles')
    <style>
        :root {
            --bs-primary: #1a73e8;
            --bs-danger: #FF5252;
            --bs-success: #27AE60;
        }
        
        body {
            background-color: #F5F7FA;
            font-size: 13px;
        }

        .main-content {
            padding-top: 20px; 
            padding-bottom: 20px; 
        }
        
        .card {
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
            margin-bottom: 20px;
        }
        
        .title-divider {
            border-bottom: 2px solid #dee2e6;
            padding-bottom: 15px;
            margin-bottom: 20px;
        }
        
        .btn-gradient-primary {
            background: linear-gradient(to right, #004AAD, #5DE0E6);
            border: none;
            color: white;
            transition: all 0.3s ease;
        }

        .btn-gradient-primary:hover {
            background: linear-gradient(to right, #003c8a, #4bc4c9);
            color: white;
            box-shadow: 0 2px 5px rgba(0,0,0,0.2);
        }
        
        .grup-card {
            transition: all 0.3s ease;
        }
        
        .grup-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
    </style>
@endpush

@section('content')
<div class="container py-4">
    <div class="title-divider">
        <h4 class="mb-0">Daftar Grup</h4>
    </div>

    <a href="{{ route('back') }}" class="btn btn-gradient-primary mb-4">
        <i class="fas fa-arrow-left me-2"></i> Kembali
    </a>
    
    @if (session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif
    
    @if (session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif
    
    <div class="row">
        <div class="col-md-4 mb-4">
            <a href="{{ route('dosen.grup.create') }}" class="text-decoration-none">
                <div class="card h-100 border-0 grup-card">
                    <div class="card-body d-flex flex-column align-items-center justify-content-center" style="height: 200px;">
                        <div class="bg-light rounded-circle p-3 mb-3">
                            <i class="fas fa-plus text-primary fa-2x"></i>
                        </div>
                        <h5 class="text-center">Buat Grup Baru</h5>
                    </div>
                </div>
            </a>
        </div>
        
        @foreach($grups as $grup)
        <div class="col-md-4 mb-4">
            <a href="{{ route('dosen.grup.show', $grup->id) }}" class="text-decoration-none">
                <div class="card h-100 border-0 grup-card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="mb-0">{{ $grup->nama_grup }}</h5>
                            <span class="badge bg-primary rounded-pill">{{ $grup->mahasiswa->count() }} anggota</span>
                        </div>
                        <p class="text-muted small mb-3">Dibuat: {{ $grup->created_at->format('d M Y') }}</p>
                        
                        <div class="d-flex flex-wrap">
                            @foreach($grup->mahasiswa->take(5) as $anggota)
                            <div class="me-2 mb-2" title="{{ $anggota->nama }}">
                                <div class="bg-light rounded-circle d-flex align-items-center justify-content-center" style="width: 35px; height: 35px;">
                                    <span>{{ substr($anggota->nama, 0, 1) }}</span>
                                </div>
                            </div>
                            @endforeach
                            
                            @if($grup->mahasiswa->count() > 5)
                            <div class="me-2 mb-2">
                                <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 35px; height: 35px;">
                                    <span>+{{ $grup->mahasiswa->count() - 5 }}</span>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </a>
        </div>
        @endforeach
    </div>
</div>
@endsection