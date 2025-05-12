@extends('layouts.app')
@section('title', 'Isi Pesan')
@push('styles')
    <style>
        :root {
            --primary-color: #0070dc;
            --primary-gradient: linear-gradient(to right, #004AAD, #5DE0E6);
            --primary-hover: linear-gradient(to right, #003d91, #4bcad0);
            --light-bg: #F5F7FA;
            --success-color: #27AE60;
            --danger-color: #FF5252;
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
        }
        
        .back-button i {
            margin-right: 10px;
            font-size: 16px;
        }
        
        .profile-section {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-bottom: 5px;
            position: relative;
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
            font-size: 18px;
            color: var(--dark-text);
            margin-bottom: 8px;
            margin-top: 0;
            text-align: center;
            font-weight: 600;
            position: relative;
            display: block;
        }
        
        .info-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
            border: 1px solid #eaedf1;
            table-layout: fixed !important;
        }
        
        .info-table tr {
            transition: background-color 0.2s ease;
        }
        
        .info-table tr:hover {
            background-color: #f8f9fa;
        }
        
        .info-table td {
            padding: 14px 15px;
            vertical-align: middle;
            white-space: normal;
            word-break: break-word;
        }
        
        .info-table td:first-child {
            width: 100px !important;
            white-space: nowrap !important;
            color: var(--gray-text);
            background-color: #f0f4f8;
            font-weight: 500;
            border-bottom: 1px solid #eaedf1;
        }
        
        .info-table td:last-child {
            width: calc(100% - 100px) !important;
            border-bottom: 1px solid #eaedf1;
            padding-right: 20px;
            word-break: normal;
            white-space: normal;
        }
        
        .info-table tr:last-child td {
            border-bottom: none;
        }
        
        .badge-priority {
            display: inline-block;
            padding: 5px 10px;
            font-size: 12px;
            font-weight: bold;
            color: white;
            background-color: var(--danger-color);
            border-radius: 20px;
        }
        
        .badge-priority.Umum {
            background-color: var(--success-color);
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
        
        /* Menghilangkan tombol arsip */
        .action-button[title="Arsipkan"] {
            display: none;
        }
        
        .action-button:hover {
            background: rgba(255, 255, 255, 0.25);
            transform: translateY(-2px);
        }
        
        /* Tombol Simpan bookmark */
        .simpan-button {
            display: none; /* Disembunyikan secara default */
            background: var(--primary-gradient);
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 6px;
            font-weight: 500;
            cursor: pointer;
            margin-left: 15px;
            transition: all 0.3s ease;
        }
        
        .simpan-button:hover {
            background: var(--primary-hover);
            transform: translateY(-2px);
        }
        
        .simpan-button.show {
            display: inline-flex;
            align-items: center;
        }
        
        .simpan-button i {
            margin-right: 5px;
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
        
        /* Styling untuk checkbox bookmark - DIUBAH */
        .bookmark-checkbox {
            display: none; /* Hidden by default */
            float: right;
            margin-left: 10px;
            margin-top: 3px;
        }
        
        .bookmark-checkbox .form-check-input {
            width: 18px;
            height: 18px;
            cursor: pointer;
            border: 2px solid rgba(255, 255, 255, 0.9);
            background-color: transparent;
            border-radius: 3px;
        }
        
        .bookmark-checkbox .form-check-input:checked {
            background-color: #f8ac30;
            border-color: #f8ac30;
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
            display: flex;
            align-items: center;
            justify-content: flex-end;
        }
        
        /* Ikon bookmark untuk pesan yang sudah dibookmark */
        .bookmark-icon {
            margin-left: 10px;
            color: #f8ac30;
            display: none;
        }
        
        .chat-message.bookmarked .bookmark-icon {
            display: inline-block;
        }
        
        /* Ikon batal bookmark untuk pesan yang sudah dibookmark */
        .bookmark-cancel {
            margin-left: 5px;
            color: rgba(255, 255, 255, 0.7);
            cursor: pointer;
            display: none;
        }
        
        .bookmark-cancel:hover {
            color: #FF5252;
        }
        
        .chat-message.bookmarked .bookmark-cancel {
            display: inline-block;
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
        
        .message-status {
            position: absolute;
            bottom: -18px;
            right: 0;
            color: #6c757d;
            font-size: 12px;
            display: flex;
            align-items: center;
        }
        
        .message-status i {
            color: #27AE60;
            margin-left: 4px;
        }
        
        .typing-indicator {
            display: flex;
            padding: 10px;
            width: fit-content;
            border-radius: 20px;
            background-color: #f1f3f5;
            margin-bottom: 20px;
        }
        
        .typing-indicator span {
            width: 8px;
            height: 8px;
            background-color: #adb5bd;
            border-radius: 50%;
            display: inline-block;
            margin: 0 1px;
            animation: typing 1.5s infinite ease-in-out;
        }
        
        .typing-indicator span:nth-child(1) {
            animation-delay: 0s;
        }
        
        .typing-indicator span:nth-child(2) {
            animation-delay: 0.2s;
        }
        
        .typing-indicator span:nth-child(3) {
            animation-delay: 0.4s;
        }
        
        @keyframes typing {
            0%, 100% {
                transform: translateY(0);
            }
            50% {
                transform: translateY(-5px);
            }
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
        
        /* Menghilangkan tombol emoji */
        .input-action-button[title="Emoji"] {
            display: none;
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
        
        /* Modal untuk pengaturan waktu sematkan */
        .sematkan-modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 1000;
            background-color: rgba(0, 0, 0, 0.5);
        }
        
        .sematkan-modal.show {
            display: block;
        }
        
        .sematkan-modal-content {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.15);
            width: 350px;
            max-width: 90%;
            padding: 20px;
        }
        
        .sematkan-modal-content h5 {
            font-size: 16px;
            margin-top: 0;
            margin-bottom: 5px;
            color: #333;
            font-weight: 600;
        }
        
        .sematkan-modal-content p {
            font-size: 13px;
            color: #6c757d;
            margin-bottom: 15px;
        }
        
        .sematkan-modal-content .form-check {
            margin-bottom: 10px;
        }
        
        .sematkan-modal-content .btn-group {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            margin-top: 20px;
        }
        
        .sematkan-modal-content .btn {
            padding: 8px 16px;
            border-radius: 6px;
            font-size: 14px;
            font-weight: 500;
        }
        
        .sematkan-modal-content .btn-secondary {
            background-color: #f1f3f5;
            color: #6c757d;
            border: none;
        }
        
        .sematkan-modal-content .btn-primary {
            background: var(--primary-gradient);
            color: white;
            border: none;
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
                    <a href="{{ route('dosen.dashboard.pesan') }}" class="back-button">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                    
                    <!-- Bagian Profil -->
                    <div class="profile-section">
                        <div class="profile-image-placeholder">
                            <i class="fas fa-user"></i>
                        </div>
                    </div>
                    
                   <!-- Bagian Informasi Pesan -->
<!-- Bagian Informasi Pesan -->
<div class="info-title">Informasi Pesan</div>
<table class="info-table">
    <tr>
        <td>Subjek</td>
        <td>{{ $pesan->subjek }}</td>
    </tr>
    @if($pesan->nip_pengirim == Auth::user()->nip)
        <tr>
            <td>Dikirim ke</td>
            <td>{{ optional($pesan->penerima)->nama ?? 'Mahasiswa' }}</td>
        </tr>
        <tr>
            <td>NIM</td>
            <td>{{ $pesan->nim_penerima }}</td>
        </tr>
    @else
        <tr>
            <td>Pengirim</td>
            <td>{{ optional($pesan->pengirim)->nama ?? 'Pengirim' }}</td>
        </tr>
        <tr>
            <td>NIM</td>
            <td>{{ $pesan->nim_pengirim }}</td>
        </tr>
    @endif
    <tr>
        <td>Prioritas</td>
        <td>
            <span class="badge-priority {{ $pesan->prioritas }}">{{ $pesan->prioritas }}</span>
        </td>
    </tr>
    @if($pesan->lampiran)
    <tr>
        <td>Lampiran</td>
        <td>
            <a href="{{ $pesan->lampiran }}" target="_blank" class="text-primary">
                <i class="fas fa-external-link-alt me-1"></i> Lihat Lampiran
            </a>
        </td>
    </tr>
    @endif
</table>
                </div>
            </div>
            
            <div class="col-md-8 col-lg-9">
                <!-- Bagian Header Pesan -->
                <div class="message-header">
                    <h4>
                        <span class="status-dot"></span>
                        @if($pesan->nip_pengirim == Auth::user()->nip)
                            {{ optional($pesan->penerima)->nama ?? 'Mahasiswa' }} - {{ $pesan->nim_penerima }}
                        @else
                            {{ optional($pesan->pengirim)->nama ?? 'Pengirim' }} - {{ $pesan->nim_pengirim }}
                        @endif
                    </h4>
                    <div class="action-buttons">
                        <button class="action-button" title="Bookmark" id="bookmarkButton">
                            <i class="far fa-bookmark"></i>
                        </button>
                        <button class="simpan-button" id="simpanButton">
                            <i class="fas fa-check"></i> Simpan
                        </button>
                        <button class="action-button" title="Opsi Lainnya">
                            <i class="fas fa-ellipsis-v"></i>
                        </button>
                    </div>
                </div>
                
                <!-- Container Pesan -->
<div class="chat-container" id="chatContainer">
    @foreach($balasanByDate as $date => $messages)
        <div class="chat-date-divider">
            <span>{{ Carbon\Carbon::parse($date)->locale('id')->isoFormat('dddd, D MMMM YYYY') }}</span>
        </div>
        
        @foreach($messages as $message)
            @if($message instanceof App\Models\Pesan)
                <!-- Pesan Awal -->
                @if($message->nip_pengirim == Auth::user()->nip)
                    <!-- Pesan yang dikirim dosen (posisi kanan) -->
                    <div class="chat-message message-reply {{ $message->bookmarked ? 'bookmarked' : '' }}" data-id="message-{{ $message->id }}">
                        <div class="message-bubble">
                            <div class="bookmark-checkbox">
                                <input class="form-check-input" type="checkbox" value="" id="bookmark-{{ $message->id }}">
                            </div>
                            <p>{{ $message->isi_pesan }}</p>
                            <div class="message-time">
                                {{ Carbon\Carbon::parse($message->created_at)->timezone('Asia/Jakarta')->format('H:i') }}
                                <span class="bookmark-icon"><i class="fas fa-bookmark"></i></span>
                                <span class="bookmark-cancel" title="Batalkan sematan"><i class="fas fa-times"></i></span>
                            </div>
                        </div>
                    </div>
                @else
                    <!-- Pesan dari mahasiswa (posisi kiri) -->
                    <div class="chat-message {{ $message->bookmarked ? 'bookmarked' : '' }}" data-id="message-{{ $message->id }}">
                        <div class="message-bubble">
                            <div class="bookmark-checkbox">
                                <input class="form-check-input" type="checkbox" value="" id="bookmark-{{ $message->id }}">
                            </div>
                            <p>{{ $message->isi_pesan }}</p>
                            <div class="message-time">
                                {{ Carbon\Carbon::parse($message->created_at)->timezone('Asia/Jakarta')->format('H:i') }}
                                <span class="bookmark-icon"><i class="fas fa-bookmark"></i></span>
                                <span class="bookmark-cancel" title="Batalkan sematan"><i class="fas fa-times"></i></span>
                            </div>
                        </div>
                    </div>
                @endif
            @else
                @if($message->tipe_pengirim == 'dosen')
                    <!-- Balasan dari Dosen (posisi kanan) -->
                    <div class="chat-message message-reply {{ $message->bookmarked ? 'bookmarked' : '' }}" data-id="reply-{{ $message->id }}">
                        <div class="message-bubble">
                            <div class="bookmark-checkbox">
                                <input class="form-check-input" type="checkbox" value="" id="bookmark-{{ $message->id }}">
                            </div>
                            <p>{{ $message->isi_balasan }}</p>
                            <div class="message-time">
                               {{ Carbon\Carbon::parse($message->created_at)->timezone('Asia/Jakarta')->format('H:i') }}
                                <span class="bookmark-icon"><i class="fas fa-bookmark"></i></span>
                                <span class="bookmark-cancel" title="Batalkan sematan"><i class="fas fa-times"></i></span>
                            </div>
                        </div>
                    </div>
                @else
                    <!-- Balasan dari Mahasiswa (posisi kiri) -->
                    <div class="chat-message {{ $message->bookmarked ? 'bookmarked' : '' }}" data-id="reply-{{ $message->id }}">
                        <div class="message-bubble">
                            <div class="bookmark-checkbox">
                                <input class="form-check-input" type="checkbox" value="" id="bookmark-{{ $message->id }}">
                            </div>
                            <p>{{ $message->isi_balasan }}</p>
                            <div class="message-time">
                                {{ Carbon\Carbon::parse($message->created_at)->timezone('Asia/Jakarta')->format('H:i') }}
                                <span class="bookmark-icon"><i class="fas fa-bookmark"></i></span>
                                <span class="bookmark-cancel" title="Batalkan sematan"><i class="fas fa-times"></i></span>
                            </div>
                        </div>
                    </div>
                @endif
            @endif
        @endforeach
    @endforeach
    
    @if($pesan->status == 'Berakhir')
        <!-- Pesan sistem - Pesan telah diakhiri -->
        <div class="system-message">
            <span><i class="fas fa-info-circle me-1"></i> Pesan telah diakhiri oleh mahasiswa</span>
        </div>
    @endif
</div>
                
                <!-- Form Input Pesan -->
                @if($pesan->status == 'Aktif')
                <div class="message-input-container">
                    <div class="input-actions">
                        <button class="input-action-button" title="Lampirkan File">
                            <i class="fas fa-paperclip"></i>
                        </button>
                    </div>
                    <input type="text" class="message-input" id="messageInput" placeholder="Tulis Pesan Anda disini..">
                    <button class="send-button" id="sendButton">
                        <i class="fas fa-paper-plane"></i> Kirim
                    </button>
                </div>
                @else
                <div class="message-input-container" style="display: none;">
                    <div class="input-actions">
                        <button class="input-action-button" title="Lampirkan File">
                            <i class="fas fa-paperclip"></i>
                        </button>
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

<!-- Modal untuk pengaturan waktu sematkan -->
<div class="sematkan-modal" id="sematkanModal">
<div class="sematkan-modal-content">
        <h5>Pilih berapa lama Sematan Berlangsung</h5>
        <p>Anda bisa melepas sematan kapan saja</p>
        
        <div class="form-check">
            <input class="form-check-input" type="radio" name="sematkanDurasi" id="durasi24jam" value="24" checked>
            <label class="form-check-label" for="durasi24jam">
                24 Jam
            </label>
        </div>
        <div class="form-check">
            <input class="form-check-input" type="radio" name="sematkanDurasi" id="durasi7hari" value="168">
            <label class="form-check-label" for="durasi7hari">
                7 Hari
            </label>
        </div>
        <div class="form-check">
            <input class="form-check-input" type="radio" name="sematkanDurasi" id="durasi30hari" value="720">
            <label class="form-check-label" for="durasi30hari">
                30 Hari
            </label>
        </div>
        
        <div class="btn-group">
            <button type="button" class="btn btn-secondary" id="batalSematkan">Batal</button>
            <button type="button" class="btn btn-primary" id="simpanSematkan">Simpan</button>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Elemen yang diperlukan
        const bookmarkButton = document.querySelector('#bookmarkButton');
        const simpanButton = document.querySelector('#simpanButton');
        const bookmarkCheckboxes = document.querySelectorAll('.bookmark-checkbox');
        const checkboxInputs = document.querySelectorAll('.bookmark-checkbox input');
        const sematkanModal = document.querySelector('#sematkanModal');
        const batalSematkanBtn = document.querySelector('#batalSematkan');
        const simpanSematkanBtn = document.querySelector('#simpanSematkan');
        const chatContainer = document.getElementById('chatContainer');
        const messageInput = document.querySelector('.message-input');
        const sendButton = document.querySelector('.send-button');
        
        let isBookmarkMode = false;
        
        // Sembunyikan tombol simpan secara default
        simpanButton.style.display = 'none';
        
        // Auto-scroll ke bawah chat container
        if (chatContainer) {
            chatContainer.scrollTop = chatContainer.scrollHeight;
        }
        
        // Toggle mode bookmark
        if (bookmarkButton) {
            bookmarkButton.addEventListener('click', function() {
                const icon = this.querySelector('i');
                isBookmarkMode = !isBookmarkMode;
                
                if (isBookmarkMode) {
                    // Aktifkan mode bookmark
                    icon.classList.remove('far');
                    icon.classList.add('fas');
                    showNotification('Pilih pesan untuk ditandai');
                    
                    // Tampilkan semua checkbox bookmark
                    bookmarkCheckboxes.forEach(checkbox => {
                        checkbox.style.display = 'block';
                    });
                } else {
                    // Nonaktifkan mode bookmark
                    icon.classList.remove('fas');
                    icon.classList.add('far');
                    
                    // Sembunyikan semua checkbox bookmark
                    bookmarkCheckboxes.forEach(checkbox => {
                        checkbox.style.display = 'none';
                    });
                    
                    // Sembunyikan tombol simpan
                    simpanButton.style.display = 'none';
                }
            });
        }
        
        // Event untuk checkbox bookmark (untuk menampilkan tombol simpan)
        checkboxInputs.forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                // Cek apakah ada checkbox yang dicentang
                const anyChecked = Array.from(checkboxInputs).some(input => input.checked);
                
                // Tampilkan atau sembunyikan tombol simpan berdasarkan status checkbox
                if (anyChecked) {
                    simpanButton.style.display = 'inline-flex';
                } else {
                    simpanButton.style.display = 'none';
                }
            });
        });
        
        // Tombol simpan untuk menampilkan modal
        if (simpanButton) {
            simpanButton.addEventListener('click', function() {
                // Tampilkan modal sematkan
                sematkanModal.classList.add('show');
                
                // Nonaktifkan mode bookmark
                isBookmarkMode = false;
                const bookmarkIcon = bookmarkButton.querySelector('i');
                bookmarkIcon.classList.remove('fas');
                bookmarkIcon.classList.add('far');
                
                // Sembunyikan checkbox
                bookmarkCheckboxes.forEach(checkbox => {
                    checkbox.style.display = 'none';
                });
            });
        }
        
        // Batal sematkan
        if (batalSematkanBtn) {
            batalSematkanBtn.addEventListener('click', function() {
                // Hapus semua centang
                checkboxInputs.forEach(checkbox => {
                    checkbox.checked = false;
                    const messageElem = checkbox.closest('.chat-message');
                    messageElem.classList.remove('bookmarked');
                });
                
                // Sembunyikan modal
                sematkanModal.classList.remove('show');
                
                // Sembunyikan tombol simpan
                simpanButton.style.display = 'none';
            });
        }
        
        // Simpan sematkan
        if (simpanSematkanBtn) {
            simpanSematkanBtn.addEventListener('click', function() {
                // Simpan pesan yang dibookmark
                const bookmarkedMessages = [];
                checkboxInputs.forEach(checkbox => {
                    if (checkbox.checked) {
                        const messageElem = checkbox.closest('.chat-message');
                        const messageId = messageElem.getAttribute('data-id');
                        
                        bookmarkedMessages.push(messageId);
                        
                        // Tandai pesan sebagai bookmarked untuk menampilkan ikon
                        messageElem.classList.add('bookmarked');
                    }
                });
                
                // Dapatkan durasi yang dipilih
                const durasiValue = document.querySelector('input[name="sematkanDurasi"]:checked').value;
                
                // Kirim data bookmark ke server
                fetch('{{ route("dosen.pesan.bookmark", $pesan->id) }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        message_ids: bookmarkedMessages,
                        durasi: durasiValue
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Sembunyikan modal
                        sematkanModal.classList.remove('show');
                        
                        // Hapus centang pada checkbox
                        checkboxInputs.forEach(checkbox => {
                            checkbox.checked = false;
                        });
                        
                        // Sembunyikan tombol simpan
                        simpanButton.style.display = 'none';
                        
                        showNotification('Pesan berhasil disematkan', 'success');
                    } else {
                        showNotification(data.message, 'warning');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showNotification('Terjadi kesalahan saat menyematkan pesan', 'warning');
                });
            });
        }
        
        // Membatalkan sematan pada pesan yang sudah dibookmark
        document.addEventListener('click', function(e) {
            if (e.target.closest('.bookmark-cancel')) {
                const cancelBtn = e.target.closest('.bookmark-cancel');
                const messageElem = cancelBtn.closest('.chat-message');
                
                // Hapus kelas bookmarked dari pesan
                messageElem.classList.remove('bookmarked');
                
                // Simpan perubahan ke "backend" (simulasi)
                const messageId = messageElem.getAttribute('data-id');
                console.log('Pembatalan sematan pesan:', messageId);
                
                showNotification('Sematan berhasil dibatalkan');
            }
        });
        
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
                if (message) {
                    // Kirim pesan ke server
                    fetch('{{ route("dosen.pesan.reply", $pesan->id) }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({
                            balasan: message
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Tambahkan pesan baru ke chat container
                            const newMessage = document.createElement('div');
                            newMessage.className = 'chat-message message-reply';
                            newMessage.setAttribute('data-id', 'reply-' + data.data.id);
                            
                            newMessage.innerHTML = `
                                <div class="message-bubble">
                                    <div class="bookmark-checkbox">
                                        <input class="form-check-input" type="checkbox" value="" id="bookmark-${data.data.id}">
                                    </div>
                                    <p>${data.data.isi_balasan}</p>
                                    <div class="message-time">
                                        ${data.data.created_at}
                                        <span class="bookmark-icon"><i class="fas fa-bookmark"></i></span>
                                        <span class="bookmark-cancel" title="Batalkan sematan"><i class="fas fa-times"></i></span>
                                    </div>
                                </div>
                            `;
                            
                            chatContainer.appendChild(newMessage);
                            
                            // Bersihkan input pesan
                            messageInput.value = '';
                            
                            // Scroll ke bawah untuk menampilkan pesan baru
                            chatContainer.scrollTop = chatContainer.scrollHeight;
                            
                            showNotification('Pesan berhasil dikirim', 'success');
                        } else {
                            showNotification(data.message, 'warning');
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
            const notification = document.createElement('div');
            notification.className = `alert alert-${type} alert-dismissible fade show`;
            notification.style.position = 'fixed';
            notification.style.top = '20px';
            notification.style.right = '20px';
            notification.style.zIndex = '9999';
            notification.style.borderRadius = '8px';
            notification.style.boxShadow = '0 3px 10px rgba(0,0,0,0.1)';
            notification.innerHTML = `
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            `;
            document.body.appendChild(notification);
            
            setTimeout(() => {
                notification.remove();
            }, 3000);
        }
    });
</script>
@endpush