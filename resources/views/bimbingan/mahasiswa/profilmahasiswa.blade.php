@extends('layouts.app')

@section('title', 'Profil Mahasiswa')

@push('styles')
    <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #059669, #2563eb);
            --secondary-gradient: linear-gradient(to right, #4ade80, #3b82f6);
            --text-dark: #2c3e50;
            --text-light: #34495e;
        }

        .student-profile-container {
            max-width: 800px;
            margin: 50px auto;
            perspective: 1000px;
        }

        .student-profile-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            transition: all 0.4s ease-in-out;
            transform-style: preserve-3d;
            yy
        }

        .student-profile-card:hover {
            transform: rotateX(0) rotateY(0) scale(1);
            box-shadow: 0 30px 50px rgba(0, 0, 0, 0.15);
        }

        .profile-header {
            background: var(--primary-gradient);
            color: white;
            padding: 30px;
            text-align: center;
            position: relative;
        }

        .student-avatar {
            width: 180px;
            height: 180px;
            border-radius: 50%;
            object-fit: cover;
            border: 6px solid white;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
            transition: all 0.3s ease;
        }

        .student-avatar:hover {
            transform: scale(1.1) rotate(5deg);
        }

        .profile-name {
            font-size: 28px;
            font-weight: 700;
            margin-top: 15px;
            letter-spacing: -0.5px;
        }

        .profile-nim {
            font-size: 16px;
            color: rgba(255, 255, 255, 0.8);
            letter-spacing: 1px;
        }

        .profile-details {
            padding: 30px;
            background: linear-gradient(to right, #f8f9fa, #f1f3f5);
        }

        .detail-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 0;
            border-bottom: 1px solid #e9ecef;
        }

        .detail-item:last-child {
            border-bottom: none;
        }

        .detail-label {
            font-weight: 600;
            color: var(--text-dark);
        }

        .detail-value {
            color: var(--text-light);
            text-align: right;
        }

        .profile-actions {
            display: flex;
            justify-content: space-between;
            padding: 20px;
            background: #f8f9fa;
        }

        .btn-modern {
            flex-grow: 1;
            margin: 0 10px;
            padding: 12px 20px;
            border: none;
            border-radius: 10px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: all 0.3s ease;
        }

        .btn-modern a {
            color: white;
            text-decoration: none;
        }

        .btn-update {
            background: var(--primary-gradient);
            color: white;
        }

        .btn-password {
            background: var(--secondary-gradient);
            color: white;
        }

        .btn-modern:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }
    </style>
@endpush

@section('content')
    <div class="container my-5">
        <h1 class="mb-3 gradient-text fw-bold">Profil Mahasiswa</h1>
        <hr>
        <button class="btn btn-gradient mb-4 mt-2 d-flex align-items-center justify-content-center">
            <a href="/usulanbimbingan">
                <i class="fas fa-arrow-left me-2"></i>Kembali
            </a>
        </button>
        <div class="student-profile-container">
            <div class="student-profile-card">
                <div class="profile-header">
                    <img src="{{ asset('images/fotosasa.jpg') }}" alt="Foto Mahasiswa" class="student-avatar mx-auto d-block">
                    <h2 class="profile-name">SYAHIRAH TRI MEILINA</h2>
                    <p class="profile-nim">NIM. 2107110255</p>
                </div>
                <div class="profile-details">
                    <div class="detail-item">
                        <span class="detail-label">Program Studi</span>
                        <span class="detail-value">S1 - Teknik Informatika</span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Pembimbing Akademik</span>
                        <span class="detail-value">Rahmat Rizal Andhi</span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Angkatan</span>
                        <span class="detail-value">2021</span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Email</span>
                        <span class="detail-value">syahirah.tri0255@student.unri.ac.id</span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Status</span>
                        <span class="detail-value text-success fw-bold">Aktif</span>
                    </div>
                </div>
                <div class="profile-actions">
                    <button class="btn btn-modern btn-password">
                        <a href="{{ url('/gantipassword') }}">
                            <i></i> Ganti Password
                        </a>
                    </button>
                    {{-- <button class="btn btn-modern btn-update">Perbarui Data</button> --}}
                </div>
            </div>
        </div>
    </div>
@endsection
