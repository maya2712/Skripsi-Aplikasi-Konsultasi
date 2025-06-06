@extends('layouts.app')
@section('title', 'Isi Pesan Mahasiswa')

@push('styles')
    <style>
        :root {
            --bs-primary: #1a73e8;
            --bs-danger: #FF5252;
            --bs-success: #27AE60;
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
            --gradient-primary: linear-gradient(to right, #004AAD, #5DE0E6);
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

        /* ========================================
           SIDEBAR STYLES - DESKTOP & MOBILE
           ======================================== */
        
        /* Desktop Sidebar */
        .desktop-sidebar {
            background-color: white;
            border-radius: var(--border-radius);
            box-shadow: var(--shadow);
            padding: 25px;
            margin-bottom: 20px;
            width: 100%;
            position: sticky;
            top: 20px;
            display: block;
        }

        /* Mobile Sidebar Overlay */
        .sidebar-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 1040;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .sidebar-overlay.show {
            opacity: 1;
        }

        /* Mobile Info Panel Overlay - BARU */
        .info-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 1040;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .info-overlay.show {
            opacity: 1;
        }

        .mobile-sidebar {
            position: fixed;
            top: 0;
            left: -100%;
            width: 280px;
            height: 100%;
            background: white;
            z-index: 1050;
            transition: left 0.3s ease;
            overflow-y: auto;
            box-shadow: 2px 0 15px rgba(0,0,0,0.1);
        }

        .mobile-sidebar.show {
            left: 0;
        }

        /* Mobile Info Panel - BARU */
        .mobile-info-panel {
            position: fixed;
            top: 0;
            right: -100%;
            width: 300px;
            height: 100%;
            background: white;
            z-index: 1050;
            transition: right 0.3s ease;
            overflow-y: auto;
            box-shadow: -2px 0 15px rgba(0,0,0,0.1);
        }

        .mobile-info-panel.show {
            right: 0;
        }

        .mobile-sidebar-header {
            background: var(--gradient-primary);
            color: white;
            padding: 20px 15px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        /* Mobile Info Panel Header - BARU */
        .mobile-info-header {
            background: var(--gradient-primary);
            color: white;
            padding: 20px 15px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .mobile-sidebar-header h6,
        .mobile-info-header h6 {
            margin: 0;
            font-weight: 600;
        }

        .close-sidebar,
        .close-info-panel {
            background: none;
            border: none;
            color: white;
            font-size: 1.5rem;
            padding: 5px;
            cursor: pointer;
            border-radius: 3px;
            transition: all 0.2s ease;
        }

        .close-sidebar:hover,
        .close-info-panel:hover {
            background-color: rgba(255, 255, 255, 0.2);
        }

        .close-sidebar:focus,
        .close-info-panel:focus {
            outline: none;
        }

        .sidebar-buttons {
            padding: 15px;
            border-bottom: 1px solid #eee;
        }

        .sidebar-buttons .btn {
            width: 100%;
            margin-bottom: 10px;
            padding: 10px 15px;
            font-size: 14px;
        }

        .sidebar-buttons .btn:last-child {
            margin-bottom: 0;
        }

        .sidebar-menu {
            padding: 15px;
        }
        
        .sidebar-menu .nav-link {
            color: #546E7A;
            border-radius: 0.5rem;
            margin-bottom: 8px;
            padding: 10px 15px;
            transition: all 0.3s ease;
            cursor: pointer;
        }
        
        .sidebar-menu .nav-link.active {
            background: #E3F2FD;
            color: var(--bs-primary);
        }
        
        .sidebar-menu .nav-link:hover:not(.active) {
            background: #f8f9fa;
        }

        .komunikasi-submenu .nav-link.active {
            background: #E3F2FD;
            color: var(--bs-primary);
        }

        .komunikasi-submenu .nav-link:hover:not(.active) {
            background: #f8f9fa;
        }

        .komunikasi-submenu {
            margin-left: 15px;
        }

        .komunikasi-submenu .nav-link {
            padding: 8px 15px;
            font-size: 13px;
        }

        /* ========================================
           LEFT PANEL STYLES (DESKTOP SIDEBAR CONTENT)
           ======================================== */

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

        /* ========================================
           CHAT CONTAINER STYLES
           ======================================== */

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

        /* BARU: Info button untuk mobile */
        .info-button {
            background: rgba(255, 255, 255, 0.15);
            border: none;
            width: 36px;
            height: 36px;
            border-radius: 50%;
            display: none;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 16px;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .info-button:hover {
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

        /* ========================================
           MODAL STYLES
           ======================================== */

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

        /* ========================================
           RESPONSIVE STYLES
           ======================================== */

        /* Hide desktop sidebar on mobile, show mobile sidebar */
        @media (max-width: 991.98px) {
            .desktop-sidebar {
                display: none;
            }
            
            .sidebar-overlay,
            .info-overlay {
                display: block;
            }
            
            .info-button {
                display: flex;
            }
        }

        /* Show desktop sidebar on larger screens, hide mobile elements */
        @media (min-width: 992px) {
            .mobile-sidebar,
            .mobile-info-panel,
            .sidebar-overlay,
            .info-overlay {
                display: none !important;
            }
            
            .desktop-sidebar {
                display: block;
            }
            
            .info-button {
                display: none !important;
            }
        }

        /* Tablet Portrait (768px and down) */
        @media (max-width: 768px) {
            .main-content {
                padding: 15px 0;
            }
            
            .custom-container {
                padding: 0 15px;
            }
            
            /* Profile Image Smaller */
            .profile-image,
            .profile-image-placeholder {
                width: 80px;
                height: 80px;
                font-size: 32px;
                margin-bottom: 8px;
            }
            
            /* Profile Text Smaller */
            .profile-section h5.info-title {
                font-size: 14px;
                margin-bottom: 3px;
            }
            
            .profile-section p.text-muted {
                font-size: 11px;
            }
            
            /* Info Table Mobile */
            .info-table {
                font-size: 11px;
                margin-bottom: 12px;
            }
            
            .info-table td {
                padding: 8px 12px;
            }
            
            .info-table td:first-child {
                width: 35% !important;
                font-size: 10px;
            }
            
            .info-table td:last-child {
                width: 65% !important;
                font-size: 10px;
                padding-right: 12px;
            }
            
            /* Smaller buttons */
            .back-button,
            .end-chat-button {
                padding: 10px 15px;
                font-size: 13px;
                margin-bottom: 15px;
            }
            
            .back-button:hover,
            .end-chat-button:hover {
                transform: none;
            }
            
            /* Message Header Mobile */
            .message-header {
                padding: 15px 20px;
                border-radius: 8px 8px 0 0;
            }
            
            .message-header h4 {
                font-size: 14px;
            }
            
            /* Chat Container Mobile */
            .chat-container {
                padding: 20px 15px;
                max-height: 400px;
                border-radius: 0 0 8px 8px;
            }
            
            /* Chat Messages Mobile */
            .chat-message {
                max-width: 95%;
                margin-bottom: 20px;
            }
            
            .message-bubble {
                padding: 12px 15px;
                max-width: 90%;
                font-size: 13px;
            }
            
            .message-time {
                font-size: 11px;
            }
            
            /* Input Container Mobile */
            .message-input-container {
                padding: 12px 15px;
                gap: 10px;
                flex-wrap: wrap;
            }
            
            .message-input {
                padding: 10px 15px;
                font-size: 13px;
                min-height: 20px;
            }
            
            .send-button {
                padding: 10px 20px;
                font-size: 13px;
            }
            
            .send-button i {
                margin-right: 5px;
            }
            
            .input-action-button {
                padding: 6px;
                font-size: 16px;
            }
            
            /* Modal Mobile */
            .modal-title {
                font-size: 16px;
            }
            
            .modal-body {
                padding: 20px;
                font-size: 13px;
            }
            
            .modal-footer {
                padding: 15px 20px 20px;
            }
            
            /* Attachment Mobile */
            .attachment-container {
                padding: 8px;
                margin: 6px 0;
            }
            
            .attachment-link {
                font-size: 12px;
            }
            
            .attachment-info {
                font-size: 10px;
            }
            
            .attachment-icon {
                font-size: 16px;
                margin-right: 6px;
            }
        }

        /* Mobile Portrait (576px and down) */
        @media (max-width: 576px) {
            .custom-container {
                padding: 0 10px;
            }
            
            /* Even smaller profile image */
            .profile-image,
            .profile-image-placeholder {
                width: 70px;
                height: 70px;
                font-size: 28px;
            }
            
            .profile-section h5.info-title {
                font-size: 13px;
            }
            
            .profile-section p.text-muted {
                font-size: 10px;
            }
            
            /* More compact info table */
            .info-table {
                font-size: 10px;
            }
            
            .info-table td {
                padding: 6px 10px;
            }
            
            .info-table td:first-child {
                width: 38% !important;
                font-size: 9px;
            }
            
            .info-table td:last-child {
                width: 62% !important;
                font-size: 9px;
            }
            
            /* Header adjustments */
            .message-header {
                padding: 12px 15px;
            }
            
            .message-header h4 {
                font-size: 13px;
                flex-wrap: wrap;
            }
            
            /* Chat container smaller */
            .chat-container {
                padding: 15px 10px;
                max-height: 350px;
            }
            
            /* Message bubbles more compact */
            .message-bubble {
                padding: 10px 12px;
                max-width: 85%;
                font-size: 12px;
            }
            
            .chat-date-divider span {
                font-size: 11px;
                padding: 0 10px;
            }
            
            /* Input area very compact */
            .message-input-container {
                padding: 10px 12px;
                gap: 8px;
            }
            
            .message-input {
                padding: 8px 12px;
                font-size: 12px;
            }
            
            .send-button {
                padding: 8px 15px;
                font-size: 12px;
            }
            
            .input-action-button {
                padding: 5px;
                font-size: 15px;
            }
            
            /* Buttons more compact */
            .back-button,
            .end-chat-button {
                padding: 8px 12px;
                font-size: 12px;
                margin-bottom: 12px;
            }
            
            /* Badge adjustments */
            .badge-kaprodi,
            .badge.bg-warning {
                font-size: 8px;
                padding: 2px 4px;
                margin-left: 3px;
            }
            
            .badge-priority {
                font-size: 10px;
                padding: 3px 6px;
            }
        }

        /* Extra Small Mobile (480px and down) */
        @media (max-width: 480px) {
            /* Force single column layout */
            .row {
                margin: 0;
            }
            
            .col-md-4,
            .col-lg-3,
            .col-md-8,
            .col-lg-9 {
                padding: 0;
                margin-bottom: 10px;
            }
            
            /* Ultra compact profile */
            .profile-image,
            .profile-image-placeholder {
                width: 60px;
                height: 60px;
                font-size: 24px;
            }
            
            .profile-section h5.info-title {
                font-size: 12px;
                line-height: 1.2;
            }
            
            .profile-section p.text-muted {
                font-size: 9px;
            }
            
            /* Ultra compact table */
            .info-table {
                font-size: 9px;
            }
            
            .info-table td {
                padding: 4px 8px;
            }
            
            .info-table td:first-child {
                width: 40% !important;
                font-size: 8px;
            }
            
            .info-table td:last-child {
                width: 60% !important;
                font-size: 8px;
            }
            
            /* Ultra compact header */
            .message-header {
                padding: 10px 12px;
            }
            
            .message-header h4 {
                font-size: 12px;
            }
            
            /* Ultra compact chat */
            .chat-container {
                padding: 12px 8px;
                max-height: 300px;
            }
            
            .message-bubble {
                padding: 8px 10px;
                font-size: 11px;
                max-width: 80%;
            }
            
            /* Ultra compact input */
            .message-input-container {
                padding: 8px 10px;
                gap: 6px;
            }
            
            .message-input {
                padding: 6px 10px;
                font-size: 11px;
            }
            
            .send-button {
                padding: 6px 12px;
                font-size: 11px;
            }
            
            .send-button i {
                font-size: 11px;
            }
            
            /* Ultra compact buttons */
            .back-button,
            .end-chat-button {
                padding: 6px 10px;
                font-size: 11px;
                margin-bottom: 10px;
            }
            
            .back-button i,
            .end-chat-button i {
                font-size: 13px;
                margin-right: 6px;
            }
        }

        /* Landscape Mobile Specific (max-height: 500px) */
        @media (max-height: 500px) and (orientation: landscape) {
            .main-content {
                padding: 10px 0;
            }
            
            .desktop-sidebar {
                position: static;
                padding: 10px;
                margin-bottom: 10px;
            }
            
            .profile-image,
            .profile-image-placeholder {
                width: 50px;
                height: 50px;
                font-size: 20px;
                margin-bottom: 5px;
            }
            
            .profile-section h5.info-title {
                font-size: 11px;
                margin-bottom: 2px;
            }
            
            .profile-section p.text-muted {
                font-size: 8px;
            }
            
            .info-table {
                font-size: 8px;
                margin-bottom: 8px;
            }
            
            .info-table td {
                padding: 3px 6px;
            }
            
            .chat-container {
                max-height: 200px;
                padding: 10px;
            }
            
            .message-header {
                padding: 8px 12px;
            }
            
            .message-header h4 {
                font-size: 11px;
            }
            
            .message-bubble {
                padding: 6px 8px;
                font-size: 10px;
            }
            
            .message-input-container {
                padding: 6px 8px;
            }
            
            .message-input {
                padding: 4px 8px;
                font-size: 10px;
            }
            
            .send-button {
                padding: 4px 8px;
                font-size: 10px;
            }
            
            .back-button,
            .end-chat-button {
                padding: 4px 8px;
                font-size: 10px;
                margin-bottom: 8px;
            }
        }

        /* Fix for sticky positioning on mobile */
        @media (max-width: 991.98px) {
            .desktop-sidebar {
                position: static !important;
                top: auto !important;
            }
        }
        
        /* Ensure touch targets are adequate */
        @media (max-width: 576px) {
            .send-button,
            .back-button,
            .end-chat-button,
            .input-action-button {
                min-height: 44px;
                min-width: 44px;
            }
            
            .message-input {
                min-height: 44px;
            }
        }
        
        /* Handle very long text in message bubbles */
        @media (max-width: 576px) {
            .message-bubble p {
                word-break: break-word;
                overflow-wrap: break-word;
                hyphens: auto;
            }
            
            .info-table td:last-child {
                word-break: break-all;
                overflow-wrap: break-word;
                hyphens: auto;
            }
        }

        /* Dark mode support */
        @media (prefers-color-scheme: dark) {
            .sidebar-overlay,
            .info-overlay {
                background-color: rgba(0, 0, 0, 0.7);
            }
        }
    </style>
@endpush

@section('content')
<!-- Mobile Sidebar Overlay -->
<div class="sidebar-overlay" id="sidebarOverlay"></div>

<!-- Mobile Info Panel Overlay -->
<div class="info-overlay" id="infoOverlay"></div>

<!-- Mobile Sidebar -->
<div class="mobile-sidebar" id="mobileSidebar">
    <div class="mobile-sidebar-header">
        <h6>Menu Navigasi</h6>
        <button class="close-sidebar" id="closeSidebar">
            <i class="fas fa-times"></i>
        </button>
    </div>
    
    <div class="sidebar-buttons">
        <a href="{{ route('mahasiswa.pesan.create') }}" class="btn" style="background: var(--gradient-primary); color: white; padding: 10px 20px; border: none; border-radius: 5px;">
            <i class="fas fa-plus me-2"></i> Pesan Baru
        </a>
    </div>
    <div class="sidebar-menu">
        <div class="nav flex-column">
            <a href="{{ route('mahasiswa.dashboard.pesan') }}" class="nav-link">
                <i class="fas fa-home me-2"></i>Daftar Pesan
            </a>
            <a href="#" class="nav-link menu-item" id="mobileGrupDropdownToggle">
                <div class="d-flex justify-content-between align-items-center">
                    <span><i class="fas fa-users me-2"></i>Daftar Grup</span>
                    <i class="fas fa-chevron-down" id="mobileGrupDropdownIcon"></i>
                </div>
            </a>
            <div class="collapse komunikasi-submenu" id="mobileKomunikasiSubmenu">
                @php
                    $userGrups = Auth::user()->grups ?? collect();
                @endphp
                
                @if($userGrups && $userGrups->count() > 0)
                    @foreach($userGrups as $grupItem)
                    <a href="{{ route('mahasiswa.grup.show', $grupItem->id) }}" class="nav-link menu-item d-flex justify-content-between align-items-center">
                        {{ $grupItem->nama_grup }}
                        @if($unreadCount = $grupItem->unreadMessages ?? 0)
                        <span class="badge bg-danger rounded-pill">{{ $unreadCount }}</span>
                        @endif
                    </a>
                    @endforeach
                @else
                    <div class="nav-link menu-item text-muted">
                        <small>Belum ada grup</small>
                    </div>
                @endif
            </div>
            <a href="{{ route('mahasiswa.pesan.history') }}" class="nav-link menu-item">
                <i class="fas fa-history me-2"></i>Riwayat Pesan
            </a>
            <a href="{{ url('/faqmahasiswa') }}" class="nav-link menu-item">
                <i class="fas fa-thumbtack me-2"></i>Pesan Tersematkan
            </a>
        </div>
    </div>
</div>

<!-- Mobile Info Panel -->
<div class="mobile-info-panel" id="mobileInfoPanel">
    <div class="mobile-info-header">
        <h6>Informasi</h6>
        <button class="close-info-panel" id="closeInfoPanel">
            <i class="fas fa-times"></i>
        </button>
    </div>
    
    <!-- Mobile: Informasi Pengirim -->
    <div style="padding: 15px; border-bottom: 1px solid #eee;">
        <div class="profile-section" style="margin-bottom: 15px;">
            @if($pesan->nim_pengirim == Auth::user()->nim)
                <!-- Jika mahasiswa adalah pengirim, tampilkan informasi dosen penerima -->
                @php
                    $profilePhoto = $pesan->dosenPenerima && $pesan->dosenPenerima->profile_photo 
                        ? asset('storage/profile_photos/'.$pesan->dosenPenerima->profile_photo) 
                        : null;
                @endphp
                @if($profilePhoto)
                    <img src="{{ $profilePhoto }}" alt="Foto Profil" class="profile-image" style="width: 60px; height: 60px; font-size: 24px;">
                @else
                    <div class="profile-image-placeholder" style="width: 60px; height: 60px; font-size: 24px;">
                        <i class="fas fa-user"></i>
                    </div>
                @endif
                <h5 class="info-title" style="font-size: 12px; margin-bottom: 3px;">{{ $pesan->dosenPenerima ? $pesan->dosenPenerima->nama : 'Dosen' }}</h5>
                <p class="text-muted mb-0 text-center" style="font-size: 10px;">
                    Dosen (Penerima)
                    @if($pesan->penerima_role == 'kaprodi')
                        <span class="badge bg-warning text-dark ms-1" style="font-size: 8px; padding: 2px 4px;">KAPRODI</span>
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
                    <img src="{{ $profilePhoto }}" alt="Foto Profil" class="profile-image" style="width: 60px; height: 60px; font-size: 24px;">
                @else
                    <div class="profile-image-placeholder" style="width: 60px; height: 60px; font-size: 24px;">
                        <i class="fas fa-user"></i>
                    </div>
                @endif
                <h5 class="info-title" style="font-size: 12px; margin-bottom: 3px;">{{ $pesan->dosenPengirim ? $pesan->dosenPengirim->nama : 'Dosen' }}</h5>
                <p class="text-muted mb-0 text-center" style="font-size: 10px;">
                    Dosen (Pengirim)
                    @if($pesan->pengirim_role == 'kaprodi')
                        <span class="badge bg-warning text-dark ms-1" style="font-size: 8px; padding: 2px 4px;">KAPRODI</span>
                    @endif
                </p>
            @endif
        </div>
        
        <!-- Mobile: Tabel Informasi -->
        <div class="info-title" style="font-size: 12px; margin-bottom: 8px; text-align: center;">Informasi Pesan</div>
        <table class="info-table" style="font-size: 9px; margin-bottom: 10px;">
            <tr>
                <td style="padding: 4px 8px; font-size: 8px;">Subjek</td>
                <td style="padding: 4px 8px; font-size: 8px;">{{ $pesan->subjek }}</td>
            </tr>
            
            @if($pesan->nim_pengirim == Auth::user()->nim)
                <tr>
                    <td style="padding: 4px 8px; font-size: 8px;">Penerima</td>
                    <td style="padding: 4px 8px; font-size: 8px;">
                        {{ $pesan->dosenPenerima ? $pesan->dosenPenerima->nama : 'Dosen' }}
                    </td>
                </tr>
                <tr>
                    <td style="padding: 4px 8px; font-size: 8px;">NIP</td>
                    <td style="padding: 4px 8px; font-size: 8px;">{{ $pesan->nip_penerima }}</td>
                </tr>
            @else
                <tr>
                    <td style="padding: 4px 8px; font-size: 8px;">Pengirim</td>
                    <td style="padding: 4px 8px; font-size: 8px;">
                        {{ $pesan->dosenPengirim ? $pesan->dosenPengirim->nama : 'Dosen' }}
                    </td>
                </tr>
                <tr>
                    <td style="padding: 4px 8px; font-size: 8px;">NIP</td>
                    <td style="padding: 4px 8px; font-size: 8px;">{{ $pesan->nip_pengirim }}</td>
                </tr>
            @endif
            
            <tr>
                <td style="padding: 4px 8px; font-size: 8px;">Prioritas</td>
                <td style="padding: 4px 8px; font-size: 8px;">
                    <span class="badge-priority {{ $pesan->prioritas == 'Umum' ? 'Umum' : '' }}" style="font-size: 8px; padding: 2px 5px;">
                        {{ $pesan->prioritas }}
                    </span>
                </td>
            </tr>
            <tr>
                <td style="padding: 4px 8px; font-size: 8px;">Status</td>
                <td style="padding: 4px 8px; font-size: 8px;">
                    <span id="pesanStatusMobile" 
                        class="badge-priority {{ $pesan->status == 'Aktif' ? 'Umum' : '' }}" style="font-size: 8px; padding: 2px 5px;">
                        {{ $pesan->status }}
                    </span>
                </td>
            </tr>
        </table>
        
        <!-- Mobile: Tombol Akhiri Pesan -->
        @if($pesan->status == 'Aktif')
            @if($pesan->nim_pengirim == Auth::user()->nim)
                <button id="endChatButtonMobile" class="end-chat-button" style="padding: 8px 12px; font-size: 11px; margin-top: 8px;">
                    <i class="fas fa-times-circle"></i> Akhiri Pesan
                </button>
            @endif
        @else
            <button class="end-chat-button" disabled style="background: #6c757d; cursor: not-allowed; padding: 8px 12px; font-size: 11px; margin-top: 8px;">
                <i class="fas fa-times-circle"></i> Pesan Diakhiri
            </button>
        @endif
    </div>
</div>

<div class="main-content">
    <div class="custom-container">
        <div class="row">
            <div class="col-md-4 col-lg-3">
                <!-- Desktop Sidebar -->
                <div class="desktop-sidebar">
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
                    <div class="action-buttons">
                        <!-- Info button untuk mobile -->
                        <button class="info-button" id="infoButton" title="Informasi">
                            <i class="fas fa-ellipsis-v"></i>
                        </button>
                    </div>
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
    // ========================================
    // MOBILE SIDEBAR FUNCTIONALITY
    // ========================================
    
    // Mobile sidebar functionality
    const mobileMenuToggle = document.getElementById('mobileMenuToggle');
    const mobileSidebar = document.getElementById('mobileSidebar');
    const sidebarOverlay = document.getElementById('sidebarOverlay');
    const closeSidebar = document.getElementById('closeSidebar');
    
    // Mobile info panel functionality
    const infoButton = document.getElementById('infoButton');
    const mobileInfoPanel = document.getElementById('mobileInfoPanel');
    const infoOverlay = document.getElementById('infoOverlay');
    const closeInfoPanel = document.getElementById('closeInfoPanel');
    
    // Fungsi untuk membuka mobile sidebar
    function openMobileSidebar() {
        if (mobileSidebar && sidebarOverlay) {
            mobileSidebar.classList.add('show');
            sidebarOverlay.style.display = 'block';
            setTimeout(() => {
                sidebarOverlay.classList.add('show');
            }, 10);
            document.body.style.overflow = 'hidden';
            
            if (mobileMenuToggle) {
                mobileMenuToggle.setAttribute('aria-expanded', 'true');
            }
        }
    }
    
    // Fungsi untuk menutup mobile sidebar
    function closeMobileSidebar() {
        if (mobileSidebar && sidebarOverlay) {
            mobileSidebar.classList.remove('show');
            sidebarOverlay.classList.remove('show');
            setTimeout(() => {
                sidebarOverlay.style.display = 'none';
            }, 300);
            document.body.style.overflow = '';
            
            if (mobileMenuToggle) {
                mobileMenuToggle.setAttribute('aria-expanded', 'false');
            }
        }
    }
    
    // Fungsi untuk membuka mobile info panel
    function openMobileInfoPanel() {
        if (mobileInfoPanel && infoOverlay) {
            mobileInfoPanel.classList.add('show');
            infoOverlay.style.display = 'block';
            setTimeout(() => {
                infoOverlay.classList.add('show');
            }, 10);
            document.body.style.overflow = 'hidden';
        }
    }
    
    // Fungsi untuk menutup mobile info panel
    function closeMobileInfoPanel() {
        if (mobileInfoPanel && infoOverlay) {
            mobileInfoPanel.classList.remove('show');
            infoOverlay.classList.remove('show');
            setTimeout(() => {
                infoOverlay.style.display = 'none';
            }, 300);
            document.body.style.overflow = '';
        }
    }
    
    // Event listener untuk mobile menu toggle
    if (mobileMenuToggle) {
        mobileMenuToggle.addEventListener('click', function(e) {
            e.stopPropagation();
            openMobileSidebar();
        });
    }
    
    // Event listener untuk info button
    if (infoButton) {
        infoButton.addEventListener('click', function(e) {
            e.stopPropagation();
            openMobileInfoPanel();
        });
    }
    
    // Event listener untuk menutup sidebar
    if (closeSidebar) {
        closeSidebar.addEventListener('click', function(e) {
            e.stopPropagation();
            closeMobileSidebar();
        });
    }
    
    // Event listener untuk menutup info panel
    if (closeInfoPanel) {
        closeInfoPanel.addEventListener('click', function(e) {
            e.stopPropagation();
            closeMobileInfoPanel();
        });
    }
    
    if (sidebarOverlay) {
        sidebarOverlay.addEventListener('click', function(e) {
            e.stopPropagation();
            closeMobileSidebar();
        });
    }
    
    if (infoOverlay) {
        infoOverlay.addEventListener('click', function(e) {
            e.stopPropagation();
            closeMobileInfoPanel();
        });
    }
    
    // Close sidebar when clicking on a menu item (mobile)
    const mobileMenuItems = document.querySelectorAll('#mobileSidebar .nav-link[href]');
    mobileMenuItems.forEach(item => {
        if (!item.id.includes('Dropdown') && item.getAttribute('href') !== '#') {
            item.addEventListener('click', function() {
                setTimeout(closeMobileSidebar, 100);
            });
        }
    });
    
    // Mobile dropdown functionality
    const mobileGrupDropdownToggle = document.getElementById('mobileGrupDropdownToggle');
    const mobileKomunikasiSubmenu = document.getElementById('mobileKomunikasiSubmenu');
    const mobileGrupDropdownIcon = document.getElementById('mobileGrupDropdownIcon');
    
    if (mobileGrupDropdownToggle) {
        mobileGrupDropdownToggle.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            const isCollapsed = !mobileKomunikasiSubmenu.classList.contains('show');
            
            if (isCollapsed) {
                mobileKomunikasiSubmenu.classList.add('show');
                mobileGrupDropdownIcon.classList.remove('fa-chevron-down');
                mobileGrupDropdownIcon.classList.add('fa-chevron-up');
            } else {
                mobileKomunikasiSubmenu.classList.remove('show');
                mobileGrupDropdownIcon.classList.remove('fa-chevron-up');
                mobileGrupDropdownIcon.classList.add('fa-chevron-down');
            }
        });
        
        mobileGrupDropdownIcon.addEventListener('click', function(e) {
            e.stopPropagation();
        });
    }
    
    // Enhanced keyboard shortcuts
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            // Close mobile sidebar if open
            if (mobileSidebar && mobileSidebar.classList.contains('show')) {
                closeMobileSidebar();
            }
            // Close mobile info panel if open
            if (mobileInfoPanel && mobileInfoPanel.classList.contains('show')) {
                closeMobileInfoPanel();
            }
        }
    });
    
    // Handle window resize to close mobile panels on desktop
    window.addEventListener('resize', function() {
        if (window.innerWidth > 991) {
            if (mobileSidebar && mobileSidebar.classList.contains('show')) {
                closeMobileSidebar();
            }
            if (mobileInfoPanel && mobileInfoPanel.classList.contains('show')) {
                closeMobileInfoPanel();
            }
        }
    });
    
    // Swipe gesture for mobile sidebar and info panel
    let touchStartX = 0;
    let touchEndX = 0;
    
    document.addEventListener('touchstart', function(e) {
        touchStartX = e.changedTouches[0].screenX;
    });
    
    document.addEventListener('touchend', function(e) {
        touchEndX = e.changedTouches[0].screenX;
        handleSwipeGesture();
    });
    
    function handleSwipeGesture() {
        const swipeThreshold = 100;
        const swipeDistance = touchEndX - touchStartX;
        
        // Swipe right to open sidebar (only if not already open)
        if (swipeDistance > swipeThreshold && touchStartX < 50 && mobileSidebar && !mobileSidebar.classList.contains('show')) {
            if (window.innerWidth <= 768) {
                if (mobileMenuToggle) {
                    mobileMenuToggle.click();
                }
            }
        }
        
        // Swipe left to close sidebar or info panel
        if (swipeDistance < -swipeThreshold) {
            if (mobileSidebar && mobileSidebar.classList.contains('show')) {
                closeMobileSidebar();
            }
            if (mobileInfoPanel && mobileInfoPanel.classList.contains('show')) {
                closeMobileInfoPanel();
            }
        }
        
        // Swipe left to open info panel (from right edge)
        if (swipeDistance < -swipeThreshold && touchStartX > window.innerWidth - 50 && mobileInfoPanel && !mobileInfoPanel.classList.contains('show')) {
            if (window.innerWidth <= 768) {
                openMobileInfoPanel();
            }
        }
    }
    
    // Add haptic feedback for mobile interactions (if supported)
    function addHapticFeedback() {
        if ('vibrate' in navigator) {
            navigator.vibrate(50);
        }
    }
    
    // Add haptic feedback to button clicks on mobile
    const interactiveElements = [mobileMenuToggle, closeSidebar, infoButton, closeInfoPanel, ...mobileMenuItems];
    interactiveElements.forEach(element => {
        if (element) {
            element.addEventListener('touchstart', function() {
                if (window.innerWidth <= 768) {
                    addHapticFeedback();
                }
            });
        }
    });

    // ========================================
    // CHAT FUNCTIONALITY
    // ========================================
    
    // Elemen yang diperlukan
    const endChatButton = document.getElementById('endChatButton');
    const endChatButtonMobile = document.getElementById('endChatButtonMobile');
    const confirmModal = new bootstrap.Modal(document.getElementById('confirmModal'));
    const cancelEndChatBtn = document.getElementById('cancelEndChat');
    const confirmEndChatBtn = document.getElementById('confirmEndChat');
    const messageInputContainer = document.getElementById('messageInputContainer');
    const pesanStatus = document.getElementById('pesanStatus');
    const pesanStatusMobile = document.getElementById('pesanStatusMobile');
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
            if (!isConversationEnded && {{ $pesan->nim_pengirim == Auth::user()->nim ? 'true' : 'false' }}) {
                confirmModal.show();
            }
        });
    }
    
    // Tampilkan modal konfirmasi (mobile version)
    if (endChatButtonMobile) {
        endChatButtonMobile.addEventListener('click', function() {
            if (!isConversationEnded && {{ $pesan->nim_pengirim == Auth::user()->nim ? 'true' : 'false' }}) {
                confirmModal.show();
                closeMobileInfoPanel(); // Tutup info panel mobile saat modal muncul
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
                    if (pesanStatus) {
                        pesanStatus.textContent = 'Berakhir';
                        pesanStatus.classList.remove('Umum');
                    }
                    
                    // Ubah status pesan mobile juga
                    if (pesanStatusMobile) {
                        pesanStatusMobile.textContent = 'Berakhir';
                        pesanStatusMobile.classList.remove('Umum');
                    }
                    
                    // Nonaktifkan tombol akhiri pesan (desktop)
                    if (endChatButton) {
                        endChatButton.disabled = true;
                        endChatButton.style.backgroundColor = '#6c757d';
                        endChatButton.style.cursor = 'not-allowed';
                        endChatButton.style.boxShadow = 'none';
                        endChatButton.innerHTML = '<i class="fas fa-times-circle"></i> Pesan Diakhiri';
                    }
                    
                    // Nonaktifkan tombol akhiri pesan (mobile)
                    if (endChatButtonMobile) {
                        endChatButtonMobile.disabled = true;
                        endChatButtonMobile.style.backgroundColor = '#6c757d';
                        endChatButtonMobile.style.cursor = 'not-allowed';
                        endChatButtonMobile.style.boxShadow = 'none';
                        endChatButtonMobile.innerHTML = '<i class="fas fa-times-circle"></i> Pesan Diakhiri';
                    }
                    
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
            
            console.log('Send button clicked. Message:', message);
            console.log('Attachment:', attachment);
            
            // Validasi - boleh kirim pesan saja, lampiran saja, atau keduanya
            if ((message || attachment) && !isConversationEnded) {
                const requestData = {};
                
                // Tambahkan balasan jika ada pesan text
                if (message) {
                    requestData.balasan = message;
                } else {
                    // Jika hanya lampiran, berikan pesan default
                    requestData.balasan = '[Lampiran]';
                }
                
                // Tambahkan lampiran jika ada
                if (attachment) {
                    requestData.lampiran = attachment;
                }
                
                fetch('{{ route("mahasiswa.pesan.reply", $pesan->id) }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify(requestData)
                })
                .then(response => {
                    console.log('Response status:', response.status);
                    return response.json();
                })
                .then(data => {
                    console.log('Response data:', data);
                    
                    if (data.success) {
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
                        
                        const newMessage = document.createElement('div');
                        newMessage.className = 'chat-message message-reply';
                        
                        // Tampilkan pesan yang sesuai
                        const displayMessage = message || '';
                        
                        newMessage.innerHTML = `
                            <div class="message-bubble ${attachment ? 'has-attachment' : ''}">
                                ${displayMessage ? `<p>${displayMessage}</p>` : ''}
                                ${attachmentHtml}
                                <div class="message-time">
                                    ${data.data.created_at}
                                </div>
                            </div>
                        `;
                        
                        chatContainer.appendChild(newMessage);
                        
                        messageInput.value = '';
                        if (attachmentInput) {
                            attachmentInput.value = '';
                            attachmentInput.style.display = 'none';
                            attachmentButton.innerHTML = '<i class="fas fa-paperclip"></i>';
                            attachmentButton.title = 'Lampirkan File';
                            isAttachmentMode = false;
                        }
                        
                        chatContainer.scrollTop = chatContainer.scrollHeight;
                        showNotification('Pesan berhasil dikirim', 'success');
                    } else {
                        showNotification('Gagal mengirim pesan: ' + data.message, 'warning');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showNotification('Terjadi kesalahan saat mengirim pesan', 'warning');
                });
            } else if (!message && !attachment) {
                showNotification('Mohon masukkan pesan atau lampiran', 'warning');
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
    
    console.log('Mobile Isi Pesan Mahasiswa dengan Info Panel terpisah initialized successfully');
});
</script>
@endpush