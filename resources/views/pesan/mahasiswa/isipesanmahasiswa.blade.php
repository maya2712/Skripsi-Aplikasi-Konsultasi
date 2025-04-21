@extends('layouts.app')
@section('title', 'Isi Pesan Mahasiswa')
@push('styles')
    <style>
        :root {
            --primary-color: #0070dc;
            --primary-gradient: linear-gradient(to right, #0056b3, #00a5e3);
            --primary-hover: linear-gradient(to right, #004494, #0090d0);
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
        
        .end-chat-button {
            background: linear-gradient(to right, #e74c3c, #c0392b);
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
            margin-top: 20px;
            width: 100%;
            font-size: 14px;
        }
        
        .end-chat-button:hover {
            background: linear-gradient(to right, #c0392b, #a63326);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(231, 76, 60, 0.3);
        }
        
        .end-chat-button i {
            margin-right: 10px;
            font-size: 16px;
        }
        
        /* Disable style for ended conversation */
        .end-chat-button.disabled {
            background: #6c757d;
            cursor: not-allowed;
            pointer-events: none;
            opacity: 0.6;
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
            table-layout: auto;
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
            width: 90px;
            color: var(--gray-text);
            background-color: #f0f4f8;
            font-weight: 500;
            border-bottom: 1px solid #eaedf1;
        }
        
        .info-table td:last-child {
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
        
        .badge-status {
            display: inline-block;
            padding: 5px 10px;
            font-size: 12px;
            font-weight: bold;
            color: white;
            border-radius: 20px;
        }
        
        .badge-status.active {
            background-color: var(--success-color);
        }
        
        .badge-status.ended {
            background-color: #6c757d;
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
            display: flex;
            align-items: center;
            justify-content: flex-end;
        }
        
        /* Pesan dari user (mahasiswa) */
        .chat-message.user-message {
            display: flex;
            justify-content: flex-end;
            margin-left: auto;
        }
        
        .chat-message.user-message .message-bubble {
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
            transition: all 0.3s ease;
        }
        
        .message-input-container.hidden {
            display: none;
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
        
        /* Modal untuk konfirmasi akhiri pesan */
        .modal-backdrop {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.6);
            z-index: 1000;
            backdrop-filter: blur(3px);
        }
        
        .modal-backdrop.show {
            display: block;
        }
        
        .confirm-modal {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 400px;
            max-width: 90%;
            background-color: white;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
            z-index: 1001;
            padding: 0;
            overflow: hidden;
            animation: modalFadeIn 0.3s ease;
        }
        
        @keyframes modalFadeIn {
            from {
                opacity: 0;
                transform: translate(-50%, -60%);
            }
            to {
                opacity: 1;
                transform: translate(-50%, -50%);
            }
        }
        
        .confirm-modal.show {
            display: block;
        }
        
        .confirm-modal-header {
            background: linear-gradient(135deg, #e74c3c, #c0392b);
            color: white;
            padding: 20px 25px;
            border-bottom: 1px solid rgba(0,0,0,0.1);
        }
        
        .confirm-modal-header h5 {
            font-size: 20px;
            font-weight: 600;
            margin: 0;
            display: flex;
            align-items: center;
        }
        
        .confirm-modal-header h5 i {
            margin-right: 10px;
            font-size: 22px;
        }
        
        .confirm-modal-body {
            padding: 25px;
            background-color: #f8f9fa;
        }
        
        .confirm-modal-body p {
            color: #495057;
            font-size: 15px;
            line-height: 1.6;
            margin: 0;
        }
        
        .confirm-modal-footer {
            display: flex;
            justify-content: flex-end;
            gap: 15px;
            padding: 20px 25px;
            background-color: white;
            border-top: 1px solid rgba(0,0,0,0.05);
        }
        
        .btn-cancel {
            background-color: #f8f9fa;
            color: #6c757d;
            border: none;
            padding: 10px 20px;
            border-radius: 8px;
            font-size: 15px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s ease;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }
        
        .btn-cancel:hover {
            background-color: #e9ecef;
            transform: translateY(-2px);
        }
        
        .btn-confirm {
            background: linear-gradient(to right, #e74c3c, #c0392b);
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 8px;
            font-size: 15px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s ease;
            box-shadow: 0 2px 5px rgba(231, 76, 60, 0.3);
        }
        
        .btn-confirm:hover {
            background: linear-gradient(to right, #c0392b, #a63326);
            transform: translateY(-2px);
            box-shadow: 0 4px 10px rgba(231, 76, 60, 0.4);
        }
        
        /* Pesan sistem - Pesan telah diakhiri */
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
            box-shadow: 0 2px 6px rgba(231, 76, 60, 0.2);
            display: inline-block;
        }
        
        /* Notification styling */
        .notification {
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 15px 20px;
            background-color: #fff;
            color: #333;
            border-left: 4px solid;
            box-shadow: 0 3px 10px rgba(0,0,0,0.1);
            border-radius: 4px;
            z-index: 1005;
            font-size: 14px;
            display: flex;
            align-items: center;
            transform: translateX(120%);
            transition: transform 0.3s ease;
        }
        
        .notification.show {
            transform: translateX(0);
        }
        
        .notification.success {
            border-left-color: var(--success-color);
        }
        
        .notification.success i {
            color: var(--success-color);
        }
        
        .notification.warning {
            border-left-color: #f39c12;
        }
        
        .notification.warning i {
            color: #f39c12;
        }
        
        .notification i {
            margin-right: 10px;
            font-size: 18px;
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
            
            .confirm-modal {
                width: 90%;
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
                    <a href="{{ url('/dashboardpesanmahasiswa') }}" class="back-button">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                    
                    <!-- Bagian Profil -->
                    <div class="profile-section">
                        <img src="https://randomuser.me/api/portraits/men/41.jpg" alt="Profil Dosen" class="profile-image">
                    </div>
                    
                    <!-- Bagian Informasi Pesan -->
                    <div class="info-title">Informasi Pesan</div>
                    <table class="info-table">
                        <tr>
                            <td>Subjek</td>
                            <td>Bimbingan KRS</td>
                        </tr>
                        <tr>
                            <td>Penerima</td>
                            <td>Dr. Ahmad Fauzi, M.Kom</td>
                        </tr>
                        <tr>
                            <td>NIDN</td>
                            <td>0015087203</td>
                        </tr>
                        <tr>
                            <td>Prioritas</td>
                            <td><span class="badge-priority">Penting</span></td>
                        </tr>
                        <tr>
                            <td>Status</td>
                            <td><span class="badge-status active" id="pesanStatus">Aktif</span></td>
                        </tr>
                    </table>
                    
                    <!-- Tombol Akhiri Pesan -->
                    <button class="end-chat-button" id="endChatButton">
                        <i class="fas fa-times-circle"></i> Akhiri Pesan
                    </button>
                </div>
            </div>
            
            <div class="col-md-8 col-lg-9">
                <!-- Bagian Header Pesan -->
                <div class="message-header">
                    <h4><span class="status-dot"></span> Dr. Ahmad Fauzi, M.Kom</h4>
                    <div class="action-buttons">
                        <button class="action-button" title="Opsi Lainnya">
                            <i class="fas fa-ellipsis-v"></i>
                        </button>
                    </div>
                </div>
                
                <!-- Container Pesan -->
                <div class="chat-container" id="chatContainer">
                    <div class="chat-date-divider">
                        <span>Sabtu, 20 Januari 2024</span>
                    </div>
                    
                    <!-- Pesan Mahasiswa 1 -->
                    <div class="chat-message user-message">
                        <div class="message-bubble">
                            <p>Assalamuaikum pak</p>
                            <p>izin bertanya, untuk bimbingan KRS dilakukan mulai kapan ya pak?</p>
                            <div class="message-time">
                                09:30
                            </div>
                        </div>
                    </div>
                    
                    <!-- Balasan Dosen -->
                    <div class="chat-message">
                        <div class="message-bubble">
                            <div class="sender-name-container">
                                <span class="sender-name">Dr. Ahmad Fauzi, M.Kom</span>
                            </div>
                            <p>Wa'alaikumsalam Warohmatulloh Wabarokatuh</p>
                            <p>Bimbingan KRS dilakukan mulai tanggal 25-30 Januari 2025</p>
                            <div class="message-time">
                                09:32
                            </div>
                        </div>
                    </div>
                    
                    <!-- Pesan Mahasiswa 2 -->
                    <div class="chat-message user-message">
                        <div class="message-bubble">
                            <p>Baik pak</p>
                            <p>Terimakasih Informasinya.</p>
                            <div class="message-time">
                                09:35
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Form Input Pesan -->
                <div class="message-input-container" id="messageInputContainer">
                    <div class="input-actions">
                        <button class="input-action-button" title="Lampirkan File">
                            <i class="fas fa-paperclip"></i>
                        </button>
                    </div>
                    <input type="text" class="message-input" placeholder="Tulis Pesan Anda disini.." id="messageInput">
                    <button class="send-button" id="sendButton">
                        <i class="fas fa-paper-plane"></i> Kirim
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal konfirmasi akhiri pesan -->
<div class="modal-backdrop" id="modalBackdrop"></div>
<div class="confirm-modal" id="confirmModal">
    <div class="confirm-modal-header">
        <h5><i class="fas fa-exclamation-circle"></i> Konfirmasi Akhiri Pesan</h5>
    </div>
    <div class="confirm-modal-body">
        <p>Apakah Anda yakin ingin mengakhiri pesan ini? Setelah diakhiri, Anda tidak dapat mengirim pesan baru dalam percakapan ini.</p>
    </div>
    <div class="confirm-modal-footer">
        <button type="button" class="btn-cancel" id="cancelEndChat">Tidak</button>
        <button type="button" class="btn-confirm" id="confirmEndChat">Ya, Akhiri</button>
    </div>
</div>

<!-- Notification element -->
<div class="notification" id="notification">
    <i class="fas fa-check-circle"></i>
    <span id="notificationMessage">Pesan berhasil diakhiri</span>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Elemen yang diperlukan
        const endChatButton = document.getElementById('endChatButton');
        const modalBackdrop = document.getElementById('modalBackdrop');
        const confirmModal = document.getElementById('confirmModal');
        const cancelEndChatBtn = document.getElementById('cancelEndChat');
        const confirmEndChatBtn = document.getElementById('confirmEndChat');
        const messageInputContainer = document.getElementById('messageInputContainer');
        const pesanStatus = document.getElementById('pesanStatus');
        const notification = document.getElementById('notification');
        const notificationMessage = document.getElementById('notificationMessage');
        const chatContainer = document.getElementById('chatContainer');
        const messageInput = document.getElementById('messageInput');
        const sendButton = document.getElementById('sendButton');
        
        // Status percakapan (aktif atau berakhir)
        let isConversationEnded = false;
        
        // Auto-scroll ke bawah chat container
        if (chatContainer) {
            chatContainer.scrollTop = chatContainer.scrollHeight;
        }
        
        // Tampilkan modal konfirmasi
        if (endChatButton) {
            endChatButton.addEventListener('click', function() {
                if (!isConversationEnded) {
                    modalBackdrop.classList.add('show');
                    confirmModal.classList.add('show');
                }
            });
        }
        
        // Tutup modal konfirmasi
        if (cancelEndChatBtn) {
            cancelEndChatBtn.addEventListener('click', function() {
                modalBackdrop.classList.remove('show');
                confirmModal.classList.remove('show');
            });
        }
        
        // Konfirmasi akhiri pesan
        if (confirmEndChatBtn) {
            confirmEndChatBtn.addEventListener('click', function() {
                // Mengakhiri percakapan
                isConversationEnded = true;
                
                // Sembunyikan modal
                modalBackdrop.classList.remove('show');
                confirmModal.classList.remove('show');
                
                // Sembunyikan form input pesan
                messageInputContainer.classList.add('hidden');
                
                // Ubah status pesan
                pesanStatus.textContent = 'Berakhir';
                pesanStatus.classList.remove('active');
                pesanStatus.classList.add('ended');
                
                // Nonaktifkan tombol akhiri pesan
                endChatButton.classList.add('disabled');
                
                // Tambahkan pesan sistem di chat container
                const systemMessage = document.createElement('div');
                systemMessage.className = 'system-message';
                systemMessage.innerHTML = '<span>Pesan telah diakhiri</span>';
                chatContainer.appendChild(systemMessage);
                
                // Scroll ke bawah untuk menampilkan pesan sistem
                chatContainer.scrollTop = chatContainer.scrollHeight;
                
                // Tampilkan notifikasi
                showNotification('Pesan berhasil diakhiri', 'success');
                
                // Simpan status ke "backend" (simulasi)
                console.log('Pesan diakhiri oleh mahasiswa');
            });
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
                if (message && !isConversationEnded) {
                    // Tambahkan pesan baru ke chat container
                    const newMessage = document.createElement('div');
                    newMessage.className = 'chat-message user-message';
                    
                    const currentTime = new Date();
                    const hours = currentTime.getHours().toString().padStart(2, '0');
                    const minutes = currentTime.getMinutes().toString().padStart(2, '0');
                    const timeString = `${hours}:${minutes}`;
                    
                    newMessage.innerHTML = `
                        <div class="message-bubble">
                            <p>${message}</p>
                            <div class="message-time">
                                ${timeString}
                            </div>
                        </div>
                    `;
                    
                    chatContainer.appendChild(newMessage);
                    
                    // Bersihkan input pesan
                    messageInput.value = '';
                    
                    // Scroll ke bawah untuk menampilkan pesan baru
                    chatContainer.scrollTop = chatContainer.scrollHeight;
                }
            });
        }
        
        // Fungsi untuk menampilkan notifikasi
        function showNotification(message, type = 'success') {
            notification.className = `notification ${type}`;
            notificationMessage.textContent = message;
            
            notification.classList.add('show');
            
            setTimeout(() => {
                notification.classList.remove('show');
            }, 3000);
        }
    });
</script>
@endpush