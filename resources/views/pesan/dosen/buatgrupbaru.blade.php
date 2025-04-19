@extends('layouts.app')

@section('title', 'Buat Grup Baru')

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

        .form-control, .form-select {
            font-size: 13px;
            padding: 0.5rem 0.75rem;
        }

        .btn {
            font-size: 13px;
            padding: 0.5rem 1rem;
        }

        .badge {
            font-weight: normal;
        }

        .card {
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
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
    </style>
@endpush

@section('content')
<div class="container py-4">
    <div class="title-divider">
        <h4 class="mb-0">Buat Grup Baru</h4>
    </div>

    <a href="{{ route('back') }}" class="btn btn-gradient-primary mb-4">
        <i class="fas fa-arrow-left me-2"></i> Kembali
    </a>

    <form>
        <div class="mb-4">
            <label class="form-label fw-semibold">Nama Grup<span class="text-danger">*</span></label>
            <input type="text" class="form-control rounded-2" placeholder="Buat Nama Grup" required>
        </div>            

        <div class="mb-4">
            <label class="form-label fw-semibold">Anggota Grup<span class="text-danger">*</span></label>
            <input type="text" class="form-control rounded-2" placeholder="Cari Atau pilih Anggota Grup...">

            <div class="mt-3">
                <div class="card border-0">
                    <div class="card-body">
                        <span class="badge bg-light text-dark border p-2 me-2 mb-2">
                            Desi Maya Sari - 2107110665
                            <button type="button" class="btn-close ms-2" style="font-size: 8px;"></button>
                        </span>
                        <span class="badge bg-light text-dark border p-2 mb-2">
                            Syahira Tri - 2107110665
                            <button type="button" class="btn-close ms-2" style="font-size: 8px;"></button>
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <div class="text-end">
            <button type="submit" class="btn btn-gradient-primary px-4">Buat Grup</button>
        </div>
    </form>
</div>
@endsection
