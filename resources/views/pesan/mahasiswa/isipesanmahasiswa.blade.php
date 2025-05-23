@extends('layouts.app')
@section('title', 'Isi Pesan Mahasiswa')

@push('styles')
    <style>
        :root {
            --primary-color: #0070dc;
            --primary-gradient: linear-gradient(to right, #004AAD, #5DE0E6);
            --primary-hover: linear-gradient(to right, #003d91, #4bcad0);
            --light-bg: #F5F7FA;
            --success-color: #27AE60;
            --danger-color: #FF5252;
            --danger-gradient: linear-gradient(135deg, #FF5252, #e63946);
            --dark-text: #333333;
            --light-text: #ffffff;
            --gray-text: #6c757d;
            --border-radius: 10px;
            --shadow: 0 3px 12px rgba(0, 0, 0, 0.08);
        }

        body {
            background-color: var(--light-bg);
            font-family: 'Nunito', sans-serif;
            font-size: 14px;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            margin: 0;
            padding: 0;
        }

        .main-content {
            flex: 1;
            padding: 30px 0; 
        }

        .custom-container {
            max-width: 1300px;
            margin: 0 auto;
            padding: 0 20px;
        }

        /* Left Panel Styling */
        .left-panel {
            background-color: white;
            border-radius: var(--border-radius);
            box-shadow: var(--shadow);
            padding: 25px;
            margin-bottom: 20px;
            width: 100%;
            position: sticky;
            top: 20px;
        }

        .back-button {
            background: var(--primary-gradient);
            color: var(--light-text);
            border: none;
            padding: 12px 20px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            margin-bottom: 25px;
            width: 100%;
            font-size: 14px;
        }

        .back-button:hover {
            background: var(--primary-hover);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 85, 179, 0.3);
            color: var(--light-text);
        }

        .back-button i {
            margin-right: 10px;
            font-size: 16px;
        }

        /* Profile Section dengan ukuran font yang diperkecil */
        .profile-section {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-bottom: 5px;
            position: relative;
        }

        .profile-section h5.info-title {
            font-size: 16px; /* Diperkecil 2px dari 18px */
            margin-bottom: 5px;
            line-height: 1.3;
            word-break: break-word;
            max-width: 100%;
            text-align: center;
        }

        .profile-section p.text-muted {
            font-size: 12px; /* Diperkecil dari 14px */
            line-height: 1.3;
            text-align: center;
        }

        .profile-image {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            object-fit: cover;
            border: 4px solid #ffffff;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            margin-bottom: 10px;
            background-color: #f0f4f8;
        }

        .profile-image-placeholder {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            background-color: #f0f4f8;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 48px;
            color: #6c757d;
            border: 4px solid #ffffff;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            margin-bottom: 10px;
        }

        .info-title {
            font-size: 16px; /* Diperkecil 2px dari 18px */
            color: var(--dark-text);
            margin-bottom: 8px;
            margin-top: 0;
            text-align: center;
            font-weight: 600;
            position: relative;
            display: block;
        }

        /* Perubahan pada info-table untuk merapikan tampilan */
        .info-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
            border: 1px solid #eaedf1;
            table-layout: fixed !important;
            font-size: 12px; /* Mengurangi ukuran font seluruh tabel */
            margin-bottom: 15px; /* Menambah margin bawah untuk menghindari kesan penuh */
        }

        .info-table tr {
            transition: background-color 0.2s ease;
        }

        .info-table tr:hover {
            background-color: #f8f9fa;
        }

        .info-table td {
            padding: 12px 15px; /* Mengurangi padding sedikit */
            vertical-align: middle;
            white-space: normal;
            word-break: break-word;
            overflow: hidden;
        }

        .info-table td:first-child {
            width: 30% !important;
            white-space: nowrap !important;
            color: var(--gray-text);
            background-color: #f0f4f8;
            font-weight: 500;
            border-bottom: 1px solid #eaedf1;
        }

        .info-table td:last-child {
            width: 70% !important;
            border-bottom: 1px solid #eaedf1;
            padding-right: 20px;
            word-break: break-all;
            word-wrap: break-word;
            white-space: normal;
            overflow-wrap: break-word;
        }

        /* Khusus untuk nama dosen (biasanya di baris kedua) */
        .info-table tr:nth-child(2) td:last-child {
            font-size: 11px; /* Ukuran font lebih kecil untuk nama yang panjang */
            letter-spacing: -0.2px; /* Spasi antar karakter sedikit rapat */
            line-height: 1.4;
        }

        /* Khusus untuk kolom NIP agar tidak overflow */
        .info-table tr:nth-child(3) td:last-child {
            font-size: 11px; /* Ukuran font lebih kecil khusus untuk NIP */
            letter-spacing: -0.3px; /* Spasi antar karakter lebih rapat */
            line-height: 1.4;
        }

        .info-table tr:last-child td {
            border-bottom: none;
        }

        .badge-priority {
            display: inline-block;
            padding: 4px 8px; /* Mengurangi padding */
            font-size: 11px; /* Ukuran font lebih kecil */
            font-weight: bold;
            color: white;
            background-color: var(--danger-color);
            border-radius: 20px;
        }

        .badge-priority.Umum, .badge-priority.bg-success {
            background-color: var(--success-color);
        }

        /* Style untuk badge kaprodi - dikecilkan */
        .badge-kaprodi, .badge.bg-warning {
            background-color: #FF9800; /* Warna oranye */
            color: white !important;
            font-size: 9px; /* Diperkecil dari 10px */
            padding: 3px 6px; /* Diperkecil dari 5px 8px */
            border-radius: 4px;
            margin-left: 5px;
            font-weight: bold;
            vertical-align: middle;
            display: inline-block;
        }

        /* Badge untuk KAPRODI di header profil */
        .profile-section .badge {
            font-size: 9px;
            padding: 3px 6px;
        }

        /* Tombol akhiri pesan - memperkecil margin top */
        .end-chat-button {
            background: var(--danger-gradient);
            color: var(--light-text);
            border: none;
            padding: 12px 20px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            width: 100%;
            font-size: 14px;
            margin-top: 10px; /* Diperkecil dari 15px */
        }

        .end-chat-button:hover {
            background: linear-gradient(135deg, #e63946, #FF5252);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(255, 82, 82, 0.3);
        }

        .end-chat-button i {
            margin-right: 10px;
            font-size: 16px;
        }

        /* Chat Container Styling */
        .message-header {
            background: var(--primary-gradient);
            color: white;
            padding: 20px 25px;
            border-radius: var(--border-radius) var(--border-radius) 0 0;
            margin-bottom: 0;
            position: relative;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .message-header h4 {
            font-weight: 600;
            margin: 0;
            display: flex;
            align-items: center;
            font-size: 16px;
        }

        .message-header h4 .status-dot {
            width: 8px;
            height: 8px;
            background-color: var(--success-color);
            border-radius: 50%;
            display: inline-block;
            margin-right: 8px;
            position: relative;
        }

        .message-header h4 .status-dot:before {
            content: '';
            position: absolute;
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background-color: rgba(39, 174, 96, 0.4);
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0% {
                transform: translate(-50%, -50%) scale(1);
                opacity: 0.7;
            }
            70% {
                transform: translate(-50%, -50%) scale(1.3);
                opacity: 0;
            }
            100% {
                transform: translate(-50%, -50%) scale(1);
                opacity: 0;
            }
        }

        .action-buttons {
            display: flex;
            gap: 15px;
        }

        .action-button {
            background: rgba(255, 255, 255, 0.15);
            border: none;
            width: 36px;
            height: 36px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 16px;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .action-button:hover {
            background: rgba(255, 255, 255, 0.25);
            transform: translateY(-2px);
        }

        .chat-container {
            background: white;
            border-radius: 0 0 var(--border-radius) var(--border-radius);
            box-shadow: var(--shadow);
            margin-bottom: 20px;
            padding: 30px;
            max-height: 500px;
            overflow-y: auto;
            scrollbar-width: thin;
            scrollbar-color: #d1d5db transparent;
            position: relative;
        }

        .chat-container::-webkit-scrollbar {
            width: 6px;
        }

        .chat-container::-webkit-scrollbar-track {
            background: transparent;
        }

        .chat-container::-webkit-scrollbar-thumb {
            background-color: #d1d5db;
            border-radius: 20px;
        }

        .chat-date-divider {
            text-align: center;
            margin: 20px 0;
            position: relative;
        }

        .chat-date-divider:before {
            content: '';
            position: absolute;
            left: 0;
            top: 50%;
            width: 100%;
            height: 1px;
            background-color: #e9ecef;
            z-index: 1;
        }

        .chat-date-divider span {
            background-color: white;
            padding: 0 15px;
            font-size: 12px;
            color: #6c757d;
            position: relative;
            z-index: 2;
        }

        .chat-message {
            margin-bottom: 25px;
            position: relative;
            max-width: 85%;
            clear: both;
        }

        .chat-message:last-child {
            margin-bottom: 10px;
        }

        .sender-info {
            margin-bottom: 5px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .sender-name {
            font-weight: 600;
            color: #f8ac30;
            font-size: 14px;
        }

        .message-bubble {
            background-color: #585f67;
            color: white;
            padding: 15px 20px;
            border-radius: 0 var(--border-radius) var(--border-radius) var(--border-radius);
            position: relative;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
            width: fit-content;
            max-width: 80%;
        }

        .message-bubble .sender-name-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 8px;
        }

        .message-bubble .sender-name {
            color: #f8ac30;
            font-weight: 500;
        }

        .message-bubble p {
            margin: 0 0 8px;
            line-height: 1.5;
        }

        .message-bubble p:last-of-type {
            margin-bottom: 0;
        }

        .message-time {
            color: rgba(255, 255, 255, 0.7);
            font-size: 12px;
            text-align: right;
            margin-top: 8px;
        }

        .message-reply {
            display: flex;
            justify-content: flex-end;
            margin-left: auto;
        }

        .message-reply .message-bubble {
            background-color: var(--primary-color);
            border-radius: var(--border-radius) 0 var(--border-radius) var(--border-radius);
            margin-left: auto;
        }

        /* Styling untuk bubble chat dengan lampiran */
        .message-bubble.has-attachment {
           background-color: #585f67;
            border-left: 4px solid #f8ac30;
        }

        .message-reply .message-bubble.has-attachment {
            background-color: var(--primary-color);
            border-left: 4px solid #ffffff;
        }

        .attachment-container {
            background: rgba(255, 255, 255, 0.15);
            border-radius: 6px;
            padding: 10px;
            margin: 8px 0;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .attachment-icon {
            color: #f8ac30;
            font-size: 18px;
            margin-right: 8px;
        }

        .message-reply .attachment-icon {
            color: #ffffff;
        }

        .attachment-link {
            color: #ffffff;
            text-decoration: none;
            display: flex;
            align-items: center;
            font-weight: 500;
            transition: all 0.2s ease;
        }

        .attachment-link:hover {
            color: #f8ac30;
            text-decoration: none;
        }

        .message-reply .attachment-link:hover {
            color: #e0e0e0;
        }

        .attachment-info {
            font-size: 11px;
            color: rgba(255, 255, 255, 0.8);
            margin-top: 4px;
        }

        /* System message for chat ended */
        .system-message {
            text-align: center;
            position: relative;
            margin: 30px 0;
        }

        .system-message:before {
            content: '';
            position: absolute;
            left: 0;
            top: 50%;
            width: 100%;
            height: 1px;
            background-color: #e74c3c;
            z-index: 1;
            opacity: 0.3;
        }

        .system-message span {
            background-color: white;
            padding: 5px 15px;
            color: #e74c3c;
            font-weight: 600;
            font-size: 14px;
            position: relative;
            z-index: 2;
            border-radius: 20px;
            display: inline-block;
            box-shadow: 0 2px 5px rgba(231, 76, 60, 0.1);
        }

        .message-input-container {
            background: white;
            border-radius: var(--border-radius);
            box-shadow: var(--shadow);
            padding: 18px 20px;
            display: flex;
            gap: 15px;
            align-items: center;
        }

        .input-actions {
            display: flex;
            gap: 10px;
        }

        .input-action-button {
            background: transparent;
            border: none;
            color: #6c757d;
            font-size: 18px;
            padding: 8px;
            border-radius: 50%;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s ease;
        }

        .input-action-button:hover {
            background-color: #f0f4f8;
            color: var(--primary-color);
        }

        .message-input {
            flex-grow: 1;
            border: 1px solid #e9ecef;
            border-radius: 8px;
            padding: 12px 20px;
            outline: none;
            transition: all 0.2s ease;
            font-size: 14px;
        }

        .message-input:focus {
            border-color: #a3c7ff;
            box-shadow: 0 0 0 3px rgba(0, 112, 220, 0.1);
        }

        .send-button {
            background: var(--primary-gradient);
            color: white;
            border: none;
            border-radius: 8px;
            padding: 12px 25px;
            font-weight: 500;
            cursor: pointer;
            display: flex;
            align-items: center;
            transition: all 0.3s ease;
        }

        .send-button i {
            margin-right: 8px;
        }

        .send-button:hover {
            background: var(--primary-hover);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 85, 179, 0.2);
        }

        /* Modal styling */
        .modal-content {
            border-radius: 12px;
            border: none;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
            overflow: hidden;
        }

        .modal-header {
            padding: 20px 25px;
            border-bottom: none;
        }

        .modal-header.danger-gradient {
            background: var(--danger-gradient);
            color: white;
        }

        .modal-title {
            font-weight: 600;
            font-size: 18px;
            display: flex;
            align-items: center;
        }

        .modal-title i {
            margin-right: 10px;
        }

        .modal-body {
            padding: 25px;
            font-size: 14px;
        }

        .modal-footer {
            padding: 15px 25px 25px;
            border-top: none;
        }

        .btn-light {
            background-color: #f1f3f5;
            color: #6c757d;
            border: none;
            padding: 10px 20px;
            border-radius: 8px;
            font-weight: 500;
            transition: all 0.2s ease;
        }

        .btn-light:hover {
            background-color: #e9ecef;
        }

        .btn-gradient-danger {
            background: var(--danger-gradient);
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 8px;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .btn-gradient-danger:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(255, 82, 82, 0.3);
        }

        /* Toast notification styling */
        .toast.custom-toast {
            border-radius: 8px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            border: none;
        }

        /* Responsive adjustments */
        @media (max-width: 992px) {
            .chat-message {
                max-width: 90%;
            }
        }

        @media (max-width: 768px) {
            .chat-message {
                max-width: 100%;
            }
            
            .main-content {
                padding: 15px 0;
            }
            
            .message-header {
                padding: 15px 20px;
            }
            
            .left-panel {
                position: static;
                margin-bottom: 20px;
            }
            
            .profile-image {
                width: 100px;
                height: 100px;
            }
            
            .end-chat-button, .back-button {
                padding: 10px 15px;
            }
        }
    </style>
@endpush

@section('content')
<div class="main-content">
    <div class="custom-container">
        <div class="row">
            <div class="col-md-4 col-lg-3">
                <!-- Panel Kiri -->
                <div class="left-panel">
                    <!-- Tombol Kembali -->
                    <a href="{{ route('mahasiswa.dashboard.pesan') }}" class="back-button">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                    
                    <!-- Bagian Profil -->
                    <div class="profile-section">
                        @if($pesan->nim_pengirim == Auth::user()->nim)
                            <!-- Jika mahasiswa adalah pengirim, tampilkan informasi dosen penerima -->
                            @php
                                $profilePhoto = $pesan->dosenPenerima && $pesan->dosenPenerima->profile_photo 
                                    ? asset('storage/profile_photos/'.$pesan->dosenPenerima->profile_photo) 
                                    : null;
                            @endphp
                            @if($profilePhoto)
                                <img src="{{ $profilePhoto }}" alt="Foto Profil" class="profile-image">
                            @else
                                <div class="profile-image-placeholder">
                                    <i class="fas fa-user"></i>
                                </div>
                            @endif
                            <h5 class="info-title">{{ $pesan->dosenPenerima ? $pesan->dosenPenerima->nama : 'Dosen' }}</h5>
                            <p class="text-muted mb-0 text-center">
                                Dosen (Penerima)
                                @if($pesan->penerima_role == 'kaprodi')
                                    <span class="badge bg-warning text-dark ms-1">KAPRODI</span>
                                @endif
                            </p>
                        @else
                            <!-- Jika dosen adalah pengirim, tampilkan informasi dosen pengirim -->
                            @php
                                $profilePhoto = $pesan->dosenPengirim && $pesan->dosenPengirim->profile_photo 
                                    ? asset('storage/profile_photos/'.$pesan->dosenPengirim->profile_photo) 
                                    : null;
                            @endphp
                            @if($profilePhoto)
                                <img src="{{ $profilePhoto }}" alt="Foto Profil" class="profile-image">
                            @else
                                <div class="profile-image-placeholder">
                                    <i class="fas fa-user"></i>
                                </div>
                            @endif
                            <h5 class="info-title">{{ $pesan->dosenPengirim ? $pesan->dosenPengirim->nama : 'Dosen' }}</h5>
                            <p class="text-muted mb-0 text-center">
                                Dosen (Pengirim)
                                @if($pesan->pengirim_role == 'kaprodi')
                                    <span class="badge bg-warning text-dark ms-1">KAPRODI</span>
                                @endif
                            </p>
                        @endif
                    </div>
                    
                    
                    <!-- Bagian Informasi Pesan -->
                    <div class="info-title mt-4">Informasi Pesan</div>
                    <table class="info-table">
                        <tr>
                            <td>Subjek</td>
                            <td>{{ $pesan->subjek }}</td>
                        </tr>
                        
                        @if($pesan->nim_pengirim == Auth::user()->nim)
                            <!-- Jika mahasiswa adalah pengirim, tampilkan informasi dosen penerima -->
                            <tr>
                                <td>Penerima</td>
                                <td>
                                    {{ $pesan->dosenPenerima ? $pesan->dosenPenerima->nama : 'Dosen' }}
                                </td>
                            </tr>
                            <tr>
                                <td>NIP</td>
                                <td>{{ $pesan->nip_penerima }}</td>
                            </tr>
                        @else
                            <!-- Jika dosen adalah pengirim, tampilkan informasi dosen pengirim -->
                            <tr>
                                <td>Pengirim</td>
                                <td>
                                    {{ $pesan->dosenPengirim ? $pesan->dosenPengirim->nama : 'Dosen' }}
                                </td>
                            </tr>
                            <tr>
                                <td>NIP</td>
                                <td>{{ $pesan->nip_pengirim }}</td>
                            </tr>
                        @endif
                        
                        <tr>
                            <td>Prioritas</td>
                            <td>
                                <span class="badge-priority {{ $pesan->prioritas == 'Umum' ? 'Umum' : '' }}">
                                    {{ $pesan->prioritas }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td>Status</td>
                            <td>
                                <span id="pesanStatus" 
                                    class="badge-priority {{ $pesan->status == 'Aktif' ? 'Umum' : '' }}">
                                    {{ $pesan->status }}
                                </span>
                            </td>
                        </tr>
                    </table>

                                            
                    
                    <!-- Tombol Akhiri Pesan - hanya tampilkan untuk pengirim -->
                    @if($pesan->status == 'Aktif')
                        @if($pesan->nim_pengirim == Auth::user()->nim)
                            <button id="endChatButton" class="end-chat-button">
                                <i class="fas fa-times-circle"></i> Akhiri Pesan
                            </button>
                        @else
                            {{-- <button class="end-chat-button" disabled style="background: #6c757d; cursor: not-allowed;">
                                <i class="fas fa-info-circle"></i> Hanya Pengirim yang Dapat Mengakhiri Pesan
                            </button> --}}
                        @endif
                    @else
                        <button class="end-chat-button" disabled style="background: #6c757d; cursor: not-allowed;">
                            <i class="fas fa-times-circle"></i> Pesan Diakhiri
                        </button>
                    @endif
                </div>
            </div>
            
            <div class="col-md-8 col-lg-9">
               <!-- Bagian Header Pesan -->
                <div class="message-header">
                    <h4>
                        <span class="status-dot"></span>
                        @if($pesan->nim_pengirim == Auth::user()->nim)
                            <!-- Jika mahasiswa adalah pengirim, tampilkan nama dosen penerima -->
                            {{ $pesan->dosenPenerima ? $pesan->dosenPenerima->nama : 'Dosen' }}
                            @if($pesan->penerima_role == 'kaprodi')
                                <span class="badge bg-warning text-dark ms-2" style="font-size: 10px;">KAPRODI</span>
                            @endif
                        @else
                            <!-- Jika dosen adalah pengirim, tampilkan nama dosen pengirim -->
                            {{ $pesan->dosenPengirim ? $pesan->dosenPengirim->nama : 'Dosen' }}
                            @if($pesan->pengirim_role == 'kaprodi')
                                <span class="badge bg-warning text-dark ms-2" style="font-size: 10px;">KAPRODI</span>
                            @endif
                        @endif
                    </h4>
                   
                </div>
                
                
                 <!-- Container Pesan -->
                <div class="chat-container" id="chatContainer">
                    @foreach($balasanByDate as $date => $messages)
                        <div class="chat-date-divider">
                            <span>{{ \Carbon\Carbon::parse($date)->format('d F Y') }}</span>
                        </div>
                        
                        @foreach($messages as $message)
                        @if($message instanceof App\Models\Pesan)
                            <!-- Pesan Pertama (original message) -->
                            @if($message->nim_pengirim == Auth::user()->nim)
                                <!-- Jika mahasiswa adalah pengirim, tampilkan di kanan (message-reply) -->
                                <div class="chat-message message-reply">
                                    <div class="message-bubble {{ $message->hasAttachment() ? 'has-attachment' : '' }}">
                                        <p>{{ $message->isi_pesan }}</p>
                                        
                                        {{-- Tampilkan lampiran jika ada --}}
                                        @if($message->hasAttachment())
                                            <div class="attachment-container">
                                                <a href="{{ $message->lampiran }}" target="_blank" class="attachment-link">
                                                    <i class="fas fa-paperclip attachment-icon"></i>
                                                    <div>
                                                        <div>{{ $message->getAttachmentName() }}</div>
                                                        <div class="attachment-info">Klik untuk membuka lampiran</div>
                                                    </div>
                                                </a>
                                            </div>
                                        @endif
                                        
                                        <div class="message-time">
                                        {{ $message->formattedCreatedAt() }}
                                        </div>
                                    </div>
                                </div>
                            @else
                                <!-- Jika mahasiswa adalah penerima (pesan dari dosen), tampilkan di kiri -->
                                <div class="chat-message">
                                    <div class="message-bubble {{ $message->hasAttachment() ? 'has-attachment' : '' }}">
                                        <p>{{ $message->isi_pesan }}</p>
                                        
                                        {{-- Tampilkan lampiran jika ada --}}
                                        @if($message->hasAttachment())
                                            <div class="attachment-container">
                                                <a href="{{ $message->lampiran }}" target="_blank" class="attachment-link">
                                                    <i class="fas fa-paperclip attachment-icon"></i>
                                                    <div>
                                                        <div>{{ $message->getAttachmentName() }}</div>
                                                        <div class="attachment-info">Klik untuk membuka lampiran</div>
                                                    </div>
                                                </a>
                                            </div>
                                        @endif
                                        
                                        <div class="message-time">
                                        {{ $message->formattedCreatedAt() }}
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @else
                            @if($message->tipe_pengirim == 'mahasiswa')
                                <!-- Balasan dari Mahasiswa -->
                                <div class="chat-message message-reply">
                                    <div class="message-bubble {{ $message->hasAttachment() ? 'has-attachment' : '' }}">
                                        <p>{{ $message->isi_balasan }}</p>
                                        
                                        {{-- PERBAIKAN: Tampilkan lampiran balasan jika ada --}}
                                        @if($message->hasAttachment())
                                            <div class="attachment-container">
                                                <a href="{{ $message->lampiran }}" target="_blank" class="attachment-link">
                                                    <i class="fas fa-paperclip attachment-icon"></i>
                                                    <div>
                                                        <div>{{ $message->getAttachmentName() }}</div>
                                                        <div class="attachment-info">Klik untuk membuka lampiran</div>
                                                    </div>
                                                </a>
                                            </div>
                                        @endif
                                        
                                        <div class="message-time">
                                        {{ $message->formattedCreatedAt() }}
                                    </div>
                                </div>
                            </div>
                        @else
                            <!-- Balasan dari Dosen -->
                            <div class="chat-message">
                                <div class="message-bubble {{ $message->hasAttachment() ? 'has-attachment' : '' }}">
                                    <p>{{ $message->isi_balasan }}</p>
                                    
                                    {{-- PERBAIKAN: Tampilkan lampiran balasan jika ada --}}
                                    @if($message->hasAttachment())
                                        <div class="attachment-container">
                                            <a href="{{ $message->lampiran }}" target="_blank" class="attachment-link">
                                                <i class="fas fa-paperclip attachment-icon"></i>
                                                <div>
                                                    <div>{{ $message->getAttachmentName() }}</div>
                                                    <div class="attachment-info">Klik untuk membuka lampiran</div>
                                                </div>
                                            </a>
                                        </div>
                                    @endif
                                    
                                    <div class="message-time">
                                        {{ $message->formattedCreatedAt() }}
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endif
                    @endforeach
                   @endforeach
                   
                   @if($pesan->status == 'Berakhir')
                       <div class="system-message">
                           <span><i class="fas fa-info-circle"></i> Pesan telah diakhiri</span>
                       </div>
                   @endif
               </div>
               
               <!-- Form Input Pesan -->
               @if($pesan->status == 'Aktif')
                   <div class="message-input-container" id="messageInputContainer">
                       <div class="input-actions">
                           <button class="input-action-button" title="Lampirkan File" id="attachmentButton">
                               <i class="fas fa-paperclip"></i>
                           </button>
                           <!-- Input hidden untuk lampiran -->
                           <input type="url" id="attachmentInput" class="form-control" style="display: none;" placeholder="Masukkan link lampiran...">
                       </div>
                       <input type="text" class="message-input" id="messageInput" placeholder="Tulis Pesan Anda disini..">
                       <button class="send-button" id="sendButton">
                           <i class="fas fa-paper-plane"></i> Kirim
                       </button>
                   </div>
               @endif
           </div>
       </div>
   </div>
</div>

<!-- Modal konfirmasi akhiri pesan -->
<div class="modal fade" id="confirmModal" tabindex="-1" aria-hidden="true">
   <div class="modal-dialog modal-dialog-centered">
       <div class="modal-content">
           <div class="modal-header danger-gradient">
               <h5 class="modal-title">
                   <i class="fas fa-exclamation-circle"></i> Konfirmasi Akhiri Pesan
               </h5>
               <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
           </div>
           <div class="modal-body">
               <p class="mb-0">Apakah Anda yakin ingin mengakhiri pesan ini? Setelah diakhiri, Anda tidak dapat mengirim pesan baru dalam percakapan ini.</p>
           </div>
           <div class="modal-footer">
               <button type="button" class="btn btn-light" data-bs-dismiss="modal" id="cancelEndChat">
                   <i class="fas fa-times"></i> Tidak
               </button>
               <button type="button" class="btn btn-gradient-danger" id="confirmEndChat">
                   <i class="fas fa-check"></i> Ya, Akhiri
               </button>
           </div>
       </div>
   </div>
</div>

<!-- Toast Notification -->
<div class="position-fixed top-0 end-0 p-3" style="z-index: 1100">
   <div id="notificationToast" class="toast custom-toast align-items-center text-white bg-success border-0" role="alert" aria-live="assertive" aria-atomic="true">
       <div class="d-flex">
           <div class="toast-body">
               <i class="fas fa-check-circle me-2"></i>
               <span id="notificationMessage">Pesan berhasil dikirim</span>
           </div>
           <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
       </div>
   </div>
</div>
@endsection

@push('scripts')
<script>
   document.addEventListener('DOMContentLoaded', function() {
       // Elemen yang diperlukan
       const endChatButton = document.getElementById('endChatButton');
       const confirmModal = new bootstrap.Modal(document.getElementById('confirmModal'));
       const cancelEndChatBtn = document.getElementById('cancelEndChat');
       const confirmEndChatBtn = document.getElementById('confirmEndChat');
       const messageInputContainer = document.getElementById('messageInputContainer');
       const pesanStatus = document.getElementById('pesanStatus');
       const notificationToast = document.getElementById('notificationToast');
       const notificationMessage = document.getElementById('notificationMessage');
       const chatContainer = document.getElementById('chatContainer');
       const messageInput = document.getElementById('messageInput');
       const sendButton = document.getElementById('sendButton');
       
       // Tambahkan variabel untuk attachment
       const attachmentButton = document.getElementById('attachmentButton');
       const attachmentInput = document.getElementById('attachmentInput');
       let isAttachmentMode = false;
       
       // Inisialisasi Toast
       const toast = new bootstrap.Toast(notificationToast, {
           delay: 3000
       });
       
       // Status percakapan (aktif atau berakhir)
       let isConversationEnded = {{ $pesan->status == 'Berakhir' ? 'true' : 'false' }};
       
       // Auto-scroll ke bawah chat container
       if (chatContainer) {
           chatContainer.scrollTop = chatContainer.scrollHeight;
       }
       
       // Toggle attachment input
       if (attachmentButton && attachmentInput) {
           attachmentButton.addEventListener('click', function() {
               isAttachmentMode = !isAttachmentMode;
               
               if (isAttachmentMode) {
                   attachmentInput.style.display = 'block';
                   attachmentInput.focus();
                   attachmentButton.innerHTML = '<i class="fas fa-times"></i>';
                   attachmentButton.title = 'Batalkan lampiran';
               } else {
                   attachmentInput.style.display = 'none';
                   attachmentInput.value = '';
                   attachmentButton.innerHTML = '<i class="fas fa-paperclip"></i>';
                   attachmentButton.title = 'Lampirkan File';
               }
           });
       }
       
       // Tampilkan modal konfirmasi
       if (endChatButton) {
           endChatButton.addEventListener('click', function() {
               // Pastikan ini adalah pengirim pesan dan percakapan belum diakhiri
               if (!isConversationEnded && {{ $pesan->nim_pengirim == Auth::user()->nim ? 'true' : 'false' }}) {
                   confirmModal.show();
               }
           });
       }
       
       // Konfirmasi akhiri pesan
       if (confirmEndChatBtn) {
           confirmEndChatBtn.addEventListener('click', function() {
               // Kirim request ke server untuk mengakhiri pesan
               fetch('{{ route("mahasiswa.pesan.end", $pesan->id) }}', {
                   method: 'POST',
                   headers: {
                       'Content-Type': 'application/json',
                       'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                   }
               })
               .then(response => response.json())
               .then(data => {
                   if (data.success) {
                       // Mengakhiri percakapan
                       isConversationEnded = true;
                       
                       // Sembunyikan modal
                       confirmModal.hide();
                       
                       // Sembunyikan form input pesan
                       if (messageInputContainer) {
                           messageInputContainer.style.display = 'none';
                       }
                       
                       // Ubah status pesan
                       pesanStatus.textContent = 'Berakhir';
                       pesanStatus.classList.remove('Umum');
                       
                       // Nonaktifkan tombol akhiri pesan
                       endChatButton.disabled = true;
                       endChatButton.style.backgroundColor = '#6c757d';
                       endChatButton.style.cursor = 'not-allowed';
                       endChatButton.style.boxShadow = 'none';
                       
                       // Tambahkan pesan sistem di chat container
                       const systemMessage = document.createElement('div');
                       systemMessage.className = 'system-message';
                       systemMessage.innerHTML = '<span><i class="fas fa-info-circle"></i> Pesan telah diakhiri</span>';
                       chatContainer.appendChild(systemMessage);
                       
                       // Scroll ke bawah untuk menampilkan pesan sistem
                       chatContainer.scrollTop = chatContainer.scrollHeight;
                       
                       // Tampilkan notifikasi
                       showNotification('Pesan berhasil diakhiri', 'success');
                   } else {
                       // Tampilkan pesan error
                       showNotification('Gagal mengakhiri pesan: ' + data.message, 'warning');
                   }
               })
               .catch(error => {
                   console.error('Error:', error);
                   showNotification('Terjadi kesalahan saat mengakhiri pesan', 'warning');
               });
           });
       }
       
       // Helper function untuk mendapatkan nama attachment
       function getAttachmentName(url) {
           if (url.includes('drive.google.com')) {
               return 'Google Drive File';
           }
           
           try {
               const path = new URL(url).pathname;
               const filename = path.split('/').pop();
               return filename || 'Lampiran';
           } catch (e) {
               return 'Lampiran';
           }
       }
       
       // Menangani pengiriman pesan saat menekan Enter di input
       if (messageInput && sendButton) {
           messageInput.addEventListener('keypress', function(e) {
               if (e.key === 'Enter') {
                   e.preventDefault();
                   sendButton.click();
               }
           });
           
           sendButton.addEventListener('click', function() {
               const message = messageInput.value.trim();
               const attachment = attachmentInput ? attachmentInput.value.trim() : '';
               
               // Debug untuk melihat apakah tombol diklik
               console.log('Send button clicked. Message:', message);
               console.log('Attachment:', attachment);
               
               if (message && !isConversationEnded) {
                   // Persiapkan data untuk dikirim
                   const requestData = { balasan: message };
                   
                   // Tambahkan attachment jika ada
                   if (attachment) {
                       requestData.lampiran = attachment;
                   }
                   
                   // Kirim pesan ke server
                   fetch('{{ route("mahasiswa.pesan.reply", $pesan->id) }}', {
                       method: 'POST',
                       headers: {
                           'Content-Type': 'application/json',
                           'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                       },
                       body: JSON.stringify(requestData)
                   })
                   .then(response => {
                       // Log response untuk debugging
                       console.log('Response status:', response.status);
                       return response.json();
                   })
                   .then(data => {
                       // Log data untuk debugging
                       console.log('Response data:', data);
                       
                       if (data.success) {
                           // Buat attachment HTML jika ada
                           let attachmentHtml = '';
                           if (attachment) {
                               const attachmentName = getAttachmentName(attachment);
                               attachmentHtml = `
                                   <div class="attachment-container">
                                       <a href="${attachment}" target="_blank" class="attachment-link">
                                           <i class="fas fa-paperclip attachment-icon"></i>
                                           <div>
                                               <div>${attachmentName}</div>
                                               <div class="attachment-info">Klik untuk membuka lampiran</div>
                                           </div>
                                       </a>
                                   </div>
                               `;
                           }
                           
                           // Tambahkan pesan baru ke chat container
                           const newMessage = document.createElement('div');
                           newMessage.className = 'chat-message message-reply';
                           
                           newMessage.innerHTML = `
                               <div class="message-bubble ${attachment ? 'has-attachment' : ''}">
                                   <p>${data.data.isi_balasan}</p>
                                   ${attachmentHtml}
                                   <div class="message-time">
                                       ${data.data.created_at}
                                   </div>
                               </div>
                           `;
                           
                           chatContainer.appendChild(newMessage);
                           
                           // Bersihkan input pesan dan attachment
                           messageInput.value = '';
                           if (attachmentInput) {
                               attachmentInput.value = '';
                               attachmentInput.style.display = 'none';
                               attachmentButton.innerHTML = '<i class="fas fa-paperclip"></i>';
                               attachmentButton.title = 'Lampirkan File';
                               isAttachmentMode = false;
                           }
                           
                           // Scroll ke bawah untuk menampilkan pesan baru
                           chatContainer.scrollTop = chatContainer.scrollHeight;
                           
                           // Tampilkan notifikasi
                           showNotification('Pesan berhasil dikirim', 'success');
                       } else {
                           // Tampilkan pesan error
                           showNotification('Gagal mengirim pesan: ' + data.message, 'warning');
                       }
                   })
                   .catch(error => {
                       console.error('Error:', error);
                       showNotification('Terjadi kesalahan saat mengirim pesan', 'warning');
                   });
               }
           });
       }
       
       // Fungsi untuk menampilkan notifikasi
       function showNotification(message, type = 'success') {
           notificationToast.classList.remove('bg-success', 'bg-warning', 'bg-danger');
           notificationToast.classList.remove('text-white', 'text-dark');
           
           if (type === 'success') {
               notificationToast.classList.add('bg-success', 'text-white');
           } else if (type === 'warning') {
               notificationToast.classList.add('bg-warning', 'text-dark');
           }
           
           notificationMessage.textContent = message;
           toast.show();
       }
   });
</script>
@endpush