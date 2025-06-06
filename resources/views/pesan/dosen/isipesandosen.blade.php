@extends('layouts.app')
@section('title', 'Isi Pesan')
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

        /* Mobile Info Panel Overlay */
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

        /* Mobile Info Panel */
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

        /* Mobile Info Panel Header */
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
        
        /* Left Panel Styling (Desktop Sidebar Content) */
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
        
        .profile-section {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-bottom: 5px;
            position: relative;
        }

        .profile-section h5.info-title {
            font-size: 16px;
            margin-bottom: 5px;
            line-height: 1.3;
            word-break: break-word;
            max-width: 100%;
            text-align: center;
        }

        .profile-section p.text-muted {
            font-size: 12px;
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
            font-size: 16px;
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
            font-size: 12px;
            margin-bottom: 15px;
        }
        
        .info-table tr {
            transition: background-color 0.2s ease;
        }
        
        .info-table tr:hover {
            background-color: #f8f9fa;
        }
        
        .info-table td {
            padding: 12px 15px;
            vertical-align: middle;
            white-space: normal;
            word-break: break-word;
            overflow: hidden;
        }
        
        .info-table td:first-child {
            width: 35% !important;
            white-space: nowrap !important;
            color: var(--gray-text);
            background-color: #f0f4f8;
            font-weight: 500;
            border-bottom: 1px solid #eaedf1;
        }
        
        .info-table td:last-child {
            width: 65% !important;
            border-bottom: 1px solid #eaedf1;
            padding-right: 20px;
            word-break: break-all;
            word-wrap: break-word;
            white-space: normal;
            overflow-wrap: break-word;
        }
        
        .info-table tr:last-child td {
            border-bottom: none;
        }
        
        .badge-priority {
            display: inline-block;
            padding: 4px 8px;
            font-size: 11px;
            font-weight: bold;
            color: white;
            background-color: var(--danger-color);
            border-radius: 20px;
        }
        
        .badge-priority.Umum, .badge-priority.bg-success {
            background-color: var(--success-color);
        }

        /* End Chat Button */
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
            margin-top: 10px;
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
        
        /* Tampilan untuk tombol yang dinonaktifkan */
        .end-chat-button:disabled {
            background: #6c757d;
            cursor: not-allowed;
            opacity: 0.7;
            box-shadow: none;
        }

        .end-chat-button:disabled:hover {
            transform: none;
            box-shadow: none;
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
        
        /* Menghilangkan tombol arsip */
        .action-button[title="Arsipkan"] {
            display: none;
        }
        
        .action-button:hover {
            background: rgba(255, 255, 255, 0.25);
            transform: translateY(-2px);
        }

        /* Info button untuk mobile */
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
        
        /* Styling untuk pesan yang disematkan */
        .chat-message.pinned .bookmark-icon {
            display: inline-block !important;
        }
        
        .chat-message.pinned .message-bubble {
            border-left: 3px solid #f8ac30;
        }
        
        .chat-message.pinned .bookmark-checkbox {
            display: none !important;
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
            
            /* Left Panel Mobile Adjustments */
            .left-panel {
                position: static;
                margin-bottom: 15px;
                padding: 20px;
                border-radius: 8px;
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
            
            /* Info Title */
            .info-title {
                font-size: 14px;
                margin-bottom: 6px;
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
            
            /* Action buttons mobile */
            .action-buttons {
                gap: 10px;
            }
            
            .action-button {
                width: 32px;
                height: 32px;
                font-size: 14px;
            }
            
            .simpan-button {
                padding: 6px 12px;
                font-size: 12px;
                margin-left: 10px;
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
            
            /* Compact left panel */
            .left-panel {
                padding: 15px;
            }
            
            .profile-section h5.info-title {
                font-size: 13px;
            }
            
            .profile-section p.text-muted {
                font-size: 10px;
            }
            
            .info-title {
                font-size: 13px;
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
            
            .action-buttons {
                gap: 8px;
            }
            
            .action-button {
                width: 28px;
                height: 28px;
                font-size: 12px;
            }
            
            .simpan-button {
                padding: 5px 10px;
                font-size: 11px;
                margin-left: 8px;
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
            
            .left-panel {
                padding: 12px;
            }
            
            .profile-section h5.info-title {
                font-size: 12px;
                line-height: 1.2;
            }
            
            .profile-section p.text-muted {
                font-size: 9px;
            }
            
            .info-title {
                font-size: 12px;
                line-height: 1.2;
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
            
            .action-buttons {
                gap: 6px;
            }
            
            .action-button {
                width: 26px;
                height: 26px;
                font-size: 11px;
            }
            
            .simpan-button {
                padding: 4px 8px;
                font-size: 10px;
                margin-left: 6px;
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
            
            .left-panel {
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
            
            .info-title {
                font-size: 11px;
                margin-bottom: 4px;
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
            
            .action-button {
                width: 24px;
                height: 24px;
                font-size: 10px;
            }
            
            .simpan-button {
                padding: 3px 6px;
                font-size: 9px;
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
        @media (max-width: 768px) {
            .left-panel {
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
        <a href="{{ route('dosen.pesan.create') }}" class="btn" style="background: var(--gradient-primary); color: white; padding: 10px 20px; border: none; border-radius: 5px;">
            <i class="fas fa-plus me-2"></i> Pesan Baru
        </a>
    </div>
    <div class="sidebar-menu">
        <div class="nav flex-column">
            <a href="{{ route('dosen.dashboard.pesan') }}" class="nav-link">
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
                    <a href="{{ route('dosen.grup.show', $grupItem->id) }}" class="nav-link menu-item d-flex justify-content-between align-items-center">
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
            <a href="{{ route('dosen.pesan.history') }}" class="nav-link menu-item">
                <i class="fas fa-history me-2"></i>Riwayat Pesan
            </a>
            <a href="{{ url('/faqdosen') }}" class="nav-link menu-item">
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
            @if($pesan->nip_pengirim == Auth::user()->nip)
                <!-- Jika dosen adalah pengirim, tampilkan informasi mahasiswa penerima -->
                @php
                    $profilePhoto = $pesan->mahasiswaPenerima && $pesan->mahasiswaPenerima->profile_photo 
                        ? asset('storage/profile_photos/'.$pesan->mahasiswaPenerima->profile_photo) 
                        : null;
                @endphp
                @if($profilePhoto)
                    <img src="{{ $profilePhoto }}" alt="Foto Profil" class="profile-image" style="width: 60px; height: 60px; font-size: 24px;">
                @else
                    <div class="profile-image-placeholder" style="width: 60px; height: 60px; font-size: 24px;">
                        <i class="fas fa-user"></i>
                    </div>
                @endif
                <h5 class="info-title" style="font-size: 12px; margin-bottom: 3px;">{{ $pesan->mahasiswaPenerima ? $pesan->mahasiswaPenerima->nama : 'Mahasiswa' }}</h5>
                <p class="text-muted mb-0 text-center" style="font-size: 10px;">
                    Mahasiswa (Penerima)
                </p>
            @else
                <!-- Jika mahasiswa adalah pengirim, tampilkan informasi mahasiswa pengirim -->
                @php
                    $profilePhoto = $pesan->mahasiswaPengirim && $pesan->mahasiswaPengirim->profile_photo 
                        ? asset('storage/profile_photos/'.$pesan->mahasiswaPengirim->profile_photo) 
                        : null;
                @endphp
                @if($profilePhoto)
                    <img src="{{ $profilePhoto }}" alt="Foto Profil" class="profile-image" style="width: 60px; height: 60px; font-size: 24px;">
                @else
                    <div class="profile-image-placeholder" style="width: 60px; height: 60px; font-size: 24px;">
                        <i class="fas fa-user"></i>
                    </div>
                @endif
                <h5 class="info-title" style="font-size: 12px; margin-bottom: 3px;">{{ $pesan->mahasiswaPengirim ? $pesan->mahasiswaPengirim->nama : 'Mahasiswa' }}</h5>
                <p class="text-muted mb-0 text-center" style="font-size: 10px;">
                    Mahasiswa (Pengirim)
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
            
            @if($pesan->nip_pengirim == Auth::user()->nip)
                <tr>
                    <td style="padding: 4px 8px; font-size: 8px;">Dikirim ke</td>
                    <td style="padding: 4px 8px; font-size: 8px;">
                        {{ $pesan->mahasiswaPenerima ? $pesan->mahasiswaPenerima->nama : 'Mahasiswa' }}
                    </td>
                </tr>
                <tr>
                    <td style="padding: 4px 8px; font-size: 8px;">NIM</td>
                    <td style="padding: 4px 8px; font-size: 8px;">{{ $pesan->nim_penerima }}</td>
                </tr>
            @else
                <tr>
                    <td style="padding: 4px 8px; font-size: 8px;">Pengirim</td>
                    <td style="padding: 4px 8px; font-size: 8px;">
                        {{ $pesan->mahasiswaPengirim ? $pesan->mahasiswaPengirim->nama : 'Mahasiswa' }}
                    </td>
                </tr>
                <tr>
                    <td style="padding: 4px 8px; font-size: 8px;">NIM</td>
                    <td style="padding: 4px 8px; font-size: 8px;">{{ $pesan->nim_pengirim }}</td>
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
            @if($pesan->nip_pengirim == Auth::user()->nip)
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
                    <a href="{{ route('dosen.dashboard.pesan') }}" class="back-button">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                    
                    <!-- Bagian Profil -->
                    <div class="profile-section">
                        @if($pesan->nip_pengirim == Auth::user()->nip)
                            <!-- Menampilkan foto mahasiswa penerima -->
                            @php
                                $profilePhoto = $pesan->mahasiswaPenerima && $pesan->mahasiswaPenerima->profile_photo 
                                    ? asset('storage/profile_photos/'.$pesan->mahasiswaPenerima->profile_photo) 
                                    : null;
                            @endphp
                            @if($profilePhoto)
                                <img src="{{ $profilePhoto }}" alt="Foto Profil" class="profile-image">
                            @else
                                <div class="profile-image-placeholder">
                                    <i class="fas fa-user"></i>
                                </div>
                            @endif
                            <h5 class="info-title">{{ $pesan->mahasiswaPenerima ? $pesan->mahasiswaPenerima->nama : 'Mahasiswa' }}</h5>
                            <p class="text-muted mb-0 text-center">Mahasiswa (Penerima)</p>
                        @else
                            <!-- Menampilkan foto mahasiswa pengirim -->
                            @php
                                $profilePhoto = $pesan->mahasiswaPengirim && $pesan->mahasiswaPengirim->profile_photo 
                                    ? asset('storage/profile_photos/'.$pesan->mahasiswaPengirim->profile_photo) 
                                    : null;
                            @endphp
                            @if($profilePhoto)
                                <img src="{{ $profilePhoto }}" alt="Foto Profil" class="profile-image">
                            @else
                                <div class="profile-image-placeholder">
                                    <i class="fas fa-user"></i>
                                </div>
                            @endif
                            <h5 class="info-title">{{ $pesan->mahasiswaPengirim ? $pesan->mahasiswaPengirim->nama : 'Mahasiswa' }}</h5>
                            <p class="text-muted mb-0 text-center">Mahasiswa (Pengirim)</p>
                        @endif
                    </div>
                    
                   <!-- Bagian Informasi Pesan -->
                    <div class="info-title mt-4">Informasi Pesan</div>
                    <table class="info-table">
                        <tr>
                            <td>Subjek</td>
                            <td>{{ $pesan->subjek }}</td>
                        </tr>
                        @if($pesan->nip_pengirim == Auth::user()->nip)
                            <tr>
                                <td>Dikirim ke</td>
                                <td>{{ $pesan->mahasiswaPenerima ? $pesan->mahasiswaPenerima->nama : 'Mahasiswa' }}</td>
                            </tr>
                            <tr>
                                <td>NIM</td>
                                <td>{{ $pesan->nim_penerima }}</td>
                            </tr>
                        @else
                            <tr>
                                <td>Pengirim</td>
                                <td>{{ $pesan->mahasiswaPengirim ? $pesan->mahasiswaPengirim->nama : 'Pengirim' }}</td>
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
                        @if($pesan->nip_pengirim == Auth::user()->nip)
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
                        @if($pesan->nip_pengirim == Auth::user()->nip)
                            {{ $pesan->mahasiswaPenerima ? $pesan->mahasiswaPenerima->nama : 'Mahasiswa' }} - {{ $pesan->nim_penerima }}
                        @else
                            {{ $pesan->mahasiswaPengirim ? $pesan->mahasiswaPengirim->nama : 'Pengirim' }} - {{ $pesan->nim_pengirim }}
                        @endif
                    </h4>
                    <div class="action-buttons">
                        <button class="action-button" title="Sematkan" id="bookmarkButton">
                            <i class="far fa-bookmark"></i>
                        </button>
                        <button class="simpan-button" id="simpanButton">
                            <i class="fas fa-check"></i> Simpan
                        </button>
                        
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
                            <span>{{ Carbon\Carbon::parse($date)->locale('id')->isoFormat('dddd, D MMMM YYYY') }}</span>
                        </div>
                        
                        @foreach($messages as $message)
                            @if($message instanceof App\Models\Pesan)
                                <!-- Pesan Awal -->
                                @if($message->nip_pengirim == Auth::user()->nip)
                                    <!-- Pesan yang dikirim dosen (posisi kanan) -->
                                    <div class="chat-message message-reply {{ $message->is_pinned ? 'pinned' : ($message->bookmarked ? 'bookmarked' : '') }}" data-id="message-{{ $message->id }}">
                                        <div class="message-bubble {{ $message->hasAttachment() ? 'has-attachment' : '' }}">
                                            <!-- PERBAIKAN: Hanya tampilkan checkbox jika belum di-pin -->
                                            @if(!$message->is_pinned)
                                                <div class="bookmark-checkbox">
                                                    <input class="form-check-input" type="checkbox" value="" id="bookmark-{{ $message->id }}">
                                                </div>
                                            @endif
                                            
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
                                                
                                                {{-- PERBAIKAN: Tampilkan icon berdasarkan status dari database --}}
                                                @if($message->is_pinned)
                                                    <span class="bookmark-icon" style="display: inline-block;">
                                                        <i class="fas fa-thumbtack text-warning"></i>
                                                    </span>
                                                @else
                                                    <span class="bookmark-icon">
                                                        <i class="fas fa-bookmark"></i>
                                                    </span>
                                                    <span class="bookmark-cancel" title="Batalkan sematan"><i class="fas fa-times"></i></span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <!-- Pesan dari mahasiswa (posisi kiri) -->
                                    <div class="chat-message {{ $message->is_pinned ? 'pinned' : ($message->bookmarked ? 'bookmarked' : '') }}" data-id="message-{{ $message->id }}">
                                        <div class="message-bubble {{ $message->hasAttachment() ? 'has-attachment' : '' }}">
                                            <!-- PERBAIKAN: Hanya tampilkan checkbox jika belum di-pin -->
                                            @if(!$message->is_pinned)
                                                <div class="bookmark-checkbox">
                                                    <input class="form-check-input" type="checkbox" value="" id="bookmark-{{ $message->id }}">
                                                </div>
                                            @endif
                                            
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
                                                
                                                {{-- PERBAIKAN: Tampilkan icon berdasarkan status dari database --}}
                                                @if($message->is_pinned)
                                                    <span class="bookmark-icon" style="display: inline-block;">
                                                        <i class="fas fa-thumbtack text-warning"></i>
                                                    </span>
                                                @else
                                                    <span class="bookmark-icon">
                                                        <i class="fas fa-bookmark"></i>
                                                    </span>
                                                    <span class="bookmark-cancel" title="Batalkan sematan"><i class="fas fa-times"></i></span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            @else
                                {{-- BALASAN PESAN --}}
                                @if($message->tipe_pengirim == 'dosen')
                                    <!-- Balasan dari Dosen (posisi kanan) -->
                                    <div class="chat-message message-reply {{ $message->is_pinned ? 'pinned' : ($message->bookmarked ? 'bookmarked' : '') }}" data-id="reply-{{ $message->id }}">
                                        <div class="message-bubble {{ $message->hasAttachment() ? 'has-attachment' : '' }}">
                                            <!-- PERBAIKAN: Hanya tampilkan checkbox jika belum di-pin -->
                                            @if(!$message->is_pinned)
                                                <div class="bookmark-checkbox">
                                                    <input class="form-check-input" type="checkbox" value="" id="bookmark-reply-{{ $message->id }}">
                                                </div>
                                            @endif
                                            
                                            <p>{{ $message->isi_balasan }}</p>
                                            
                                            {{-- Tampilkan lampiran balasan jika ada --}}
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
                                                
                                                {{-- PERBAIKAN: Tampilkan icon berdasarkan status dari database --}}
                                                @if($message->is_pinned)
                                                    <span class="bookmark-icon" style="display: inline-block;">
                                                        <i class="fas fa-thumbtack text-warning"></i>
                                                    </span>
                                                @else
                                                    <span class="bookmark-icon">
                                                        <i class="fas fa-bookmark"></i>
                                                    </span>
                                                    <span class="bookmark-cancel" title="Batalkan sematan"><i class="fas fa-times"></i></span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <!-- Balasan dari Mahasiswa (posisi kiri) -->
                                    <div class="chat-message {{ $message->is_pinned ? 'pinned' : ($message->bookmarked ? 'bookmarked' : '') }}" data-id="reply-{{ $message->id }}">
                                        <div class="message-bubble {{ $message->hasAttachment() ? 'has-attachment' : '' }}">
                                            <!-- PERBAIKAN: Hanya tampilkan checkbox jika belum di-pin -->
                                            @if(!$message->is_pinned)
                                                <div class="bookmark-checkbox">
                                                    <input class="form-check-input" type="checkbox" value="" id="bookmark-reply-{{ $message->id }}">
                                                </div>
                                            @endif
                                            
                                            <p>{{ $message->isi_balasan }}</p>
                                            
                                            {{-- Tampilkan lampiran balasan jika ada --}}
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
                                                
                                                {{-- PERBAIKAN: Tampilkan icon berdasarkan status dari database --}}
                                                @if($message->is_pinned)
                                                    <span class="bookmark-icon" style="display: inline-block;">
                                                        <i class="fas fa-thumbtack text-warning"></i>
                                                    </span>
                                                @else
                                                    <span class="bookmark-icon">
                                                        <i class="fas fa-bookmark"></i>
                                                    </span>
                                                    <span class="bookmark-cancel" title="Batalkan sematan"><i class="fas fa-times"></i></span>
                                                @endif
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
                            @if($pesan->nip_pengirim == Auth::user()->nip)
                                <span><i class="fas fa-info-circle me-1"></i> Pesan telah diakhiri oleh Anda</span>
                            @else
                                <span><i class="fas fa-info-circle me-1"></i> Pesan telah diakhiri oleh pengirim</span>
                            @endif
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
                @else
                <div class="message-input-container" style="display: none;">
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

<!-- Modal untuk pengaturan sematan -->
<div class="modal fade" id="sematkanModal">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Sematkan Pesan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Info otomatis -->
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>
                    <strong>Informasi:</strong><br>
                    • Judul Sematan akan otomatis diambil dari pertanyaan mahasiswa<br>
                    • Kategori akan otomatis ditentukan berdasarkan subjek pesan<br>
                    • Pastikan memilih pertanyaan mahasiswa dan jawaban dosen
                </div>
                
                <!-- Hanya durasi yang bisa dipilih -->
                <div class="mb-3">
                    <label class="form-label">Berapa lama sematan berlangsung</label>
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
                </div>
                
                <!-- Preview informasi yang akan disematkan -->
                <div class="card bg-light">
                    <div class="card-body">
                        <h6 class="card-title">Preview Sematan:</h6>
                        <div id="previewSematan">
                            <p><strong>Subjek:</strong> <span id="previewSubjek">{{ $pesan->subjek }}</span></p>
                            <p><strong>Kategori:</strong> <span id="previewKategori">Akan ditentukan otomatis</span></p>
                            <p><strong>Judul Sematan:</strong> <span id="previewJudul">Akan diambil dari pertanyaan mahasiswa</span></p>
                        </div>
                    </div>
                </div>
                
                <p class="text-muted small mt-3">
                    <i class="fas fa-lightbulb me-1"></i>
                    Tip: Pilih pertanyaan mahasiswa terlebih dahulu, kemudian pilih jawaban dosen yang relevan.
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" id="batalSematkan">Batal</button>
                <button type="button" class="btn btn-primary" id="simpanSematkan">Sematkan</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal konfirmasi akhiri pesan -->
<div class="modal fade" id="confirmModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header" style="background: var(--danger-gradient); color: white;">
                <h5 class="modal-title">
                    <i class="fas fa-exclamation-circle"></i> Konfirmasi Akhiri Pesan
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p class="mb-0">Apakah Anda yakin ingin mengakhiri pesan ini? Setelah diakhiri, tidak ada yang dapat mengirim pesan baru dalam percakapan ini.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal" id="cancelEndChat">
                    <i class="fas fa-times"></i> Tidak
                </button>
                <button type="button" class="btn btn-danger" id="confirmEndChat">
                    <i class="fas fa-check"></i> Ya, Akhiri
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Toast Notification -->
<div class="position-fixed top-0 end-0 p-3" style="z-index: 1100">
    <div id="notificationToast" class="toast align-items-center text-white bg-success border-0" role="alert" aria-live="assertive" aria-atomic="true">
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
    // CHAT FUNCTIONALITY (EXISTING)
    // ========================================
    
    // Elemen yang diperlukan
    const bookmarkButton = document.querySelector('#bookmarkButton');
    const simpanButton = document.querySelector('#simpanButton');
    const bookmarkCheckboxes = document.querySelectorAll('.bookmark-checkbox');
    const sematkanModal = document.querySelector('#sematkanModal');
    const batalSematkanBtn = document.querySelector('#batalSematkan');
    const simpanSematkanBtn = document.querySelector('#simpanSematkan');
    const chatContainer = document.getElementById('chatContainer');
    const messageInput = document.querySelector('.message-input');
    const sendButton = document.querySelector('.send-button');
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
    
    // Tambahkan variabel untuk attachment
    const attachmentButton = document.getElementById('attachmentButton');
    const attachmentInput = document.getElementById('attachmentInput');
    let isAttachmentMode = false;
    
    let isBookmarkMode = false;
    
    // Status percakapan (aktif atau berakhir)
    let isConversationEnded = {{ $pesan->status == 'Berakhir' ? 'true' : 'false' }};
    
    // Inisialisasi Toast
    const toast = new bootstrap.Toast(notificationToast, {
        delay: 3000
    });
    
    // PERBAIKAN: Inisialisasi status pinned saat halaman load berdasarkan data dari server
    function initializePinnedStatus() {
        console.log('Initializing pinned status...');
        
        // Cek semua pesan yang sudah di-pin berdasarkan data dari server
        document.querySelectorAll('.chat-message').forEach(messageElem => {
            const messageId = messageElem.getAttribute('data-id');
            let isPinned = false;
            
            // Cek apakah elemen memiliki class 'pinned' ATAU cek dari database
            if (messageElem.classList.contains('pinned')) {
                isPinned = true;
            } else {
                // PERBAIKAN: Cek dari atribut data yang dikirim dari server
                if (messageId) {
                    if (messageId.startsWith('message-')) {
                        // Pesan utama - cek dari data server
                        isPinned = {{ $pesan->is_pinned ? 'true' : 'false' }};
                    } else if (messageId.startsWith('reply-')) {
                        // Balasan - cek dari data server untuk balasan ini
                        const replyId = messageId.replace('reply-', '');
                        @foreach($pesan->balasan as $balasan)
                            if (replyId == '{{ $balasan->id }}' && {{ $balasan->is_pinned ? 'true' : 'false' }}) {
                                isPinned = true;
                            }
                        @endforeach
                    }
                }
            }
            
            if (isPinned) {
                console.log('Setting pinned status for message:', messageId);
                
                // Tambahkan class pinned jika belum ada
                messageElem.classList.add('pinned');
                
                // Sembunyikan checkbox untuk pesan yang sudah di-pin
                const checkboxContainer = messageElem.querySelector('.bookmark-checkbox');
                if (checkboxContainer) {
                    checkboxContainer.style.display = 'none';
                }
                
                // Disable checkbox jika ada
                const checkbox = messageElem.querySelector('.bookmark-checkbox input');
                if (checkbox) {
                    checkbox.disabled = true;
                    checkbox.checked = false;
                }
                
                // Pastikan icon pin tampil
                const bookmarkIcon = messageElem.querySelector('.bookmark-icon');
                if (bookmarkIcon) {
                    bookmarkIcon.style.display = 'inline-block';
                    const iconElement = bookmarkIcon.querySelector('i');
                    if (iconElement) {
                        iconElement.className = 'fas fa-thumbtack text-warning';
                    }
                }
                
                // Sembunyikan tombol cancel untuk pesan yang sudah di-pin
                const cancelIcon = messageElem.querySelector('.bookmark-cancel');
                if (cancelIcon) {
                    cancelIcon.style.display = 'none';
                }
            } else {
                // Update state untuk pesan yang belum di-pin
                messageElem.classList.remove('pinned');
                
                const checkboxContainer = messageElem.querySelector('.bookmark-checkbox');
                const checkbox = messageElem.querySelector('.bookmark-checkbox input');
                const bookmarkIcon = messageElem.querySelector('.bookmark-icon');
                const cancelIcon = messageElem.querySelector('.bookmark-cancel');
                
                if (checkboxContainer) {
                    checkboxContainer.style.display = 'none'; // Sembunyikan by default
                }
                
                if (checkbox) {
                    checkbox.disabled = false;
                    checkbox.checked = false;
                }
                
                if (bookmarkIcon) {
                    bookmarkIcon.style.display = 'none';
                }
                
                if (cancelIcon) {
                    cancelIcon.style.display = 'none';
                }
            }
        });
    }
    
    // Panggil fungsi inisialisasi saat halaman load
    initializePinnedStatus();
    
    // Sembunyikan tombol simpan secara default
    if (simpanButton) {
        simpanButton.style.display = 'none';
    }
    
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
    
    // PERBAIKAN: Update logika bookmark button untuk mengecek pesan yang sudah di-pin
    if (bookmarkButton) {
        bookmarkButton.addEventListener('click', function() {
            const icon = this.querySelector('i');
            isBookmarkMode = !isBookmarkMode;
            
            if (isBookmarkMode) {
                // Aktifkan mode bookmark
                icon.classList.remove('far');
                icon.classList.add('fas');
                showNotification('Pilih pesan untuk ditandai');
                
                // Tampilkan checkbox hanya untuk pesan yang belum di-pin
                document.querySelectorAll('.chat-message:not(.pinned) .bookmark-checkbox').forEach(checkbox => {
                    checkbox.style.display = 'block';
                });
            } else {
                // Nonaktifkan mode bookmark
                icon.classList.remove('fas');
                icon.classList.add('far');
                
                // Sembunyikan semua checkbox
                if (bookmarkCheckboxes) {
                    bookmarkCheckboxes.forEach(checkbox => {
                        checkbox.style.display = 'none';
                    });
                }
                
                // Sembunyikan tombol simpan
                if (simpanButton) {
                    simpanButton.style.display = 'none';
                }
            }
        });
    }
    
    // PERBAIKAN: Setup event listener checkbox untuk mengecualikan yang sudah di-pin
    function setupCheckboxListeners() {
        const availableCheckboxes = document.querySelectorAll('.chat-message:not(.pinned) .bookmark-checkbox input');
        
        availableCheckboxes.forEach(checkbox => {
            // Remove existing listeners to prevent duplicates
            checkbox.removeEventListener('change', handleCheckboxChange);
            // Add new listener
            checkbox.addEventListener('change', handleCheckboxChange);
        });
    }
    
    // Function untuk handle checkbox change
    function handleCheckboxChange(event) {
        const availableCheckboxes = document.querySelectorAll('.chat-message:not(.pinned) .bookmark-checkbox input');
        const anyChecked = Array.from(availableCheckboxes).some(input => input.checked);
        
        if (anyChecked && simpanButton) {
            simpanButton.style.display = 'inline-flex';
        } else if (simpanButton) {
            simpanButton.style.display = 'none';
        }
        
        updatePreviewSematan();
    }
    
    // Setup initial checkbox listeners
    setupCheckboxListeners();
    
    // Tombol simpan untuk menampilkan modal
    if (simpanButton) {
        simpanButton.addEventListener('click', function() {
            if (sematkanModal) {
                const bsModal = new bootstrap.Modal(sematkanModal);
                bsModal.show();
            }
            
            isBookmarkMode = false;
            if (bookmarkButton) {
                const bookmarkIcon = bookmarkButton.querySelector('i');
                if (bookmarkIcon) {
                    bookmarkIcon.classList.remove('fas');
                    bookmarkIcon.classList.add('far');
                }
            }
            
            if (bookmarkCheckboxes) {
                bookmarkCheckboxes.forEach(checkbox => {
                    checkbox.style.display = 'none';
                });
            }
        });
    }
    
    // Batal sematkan
    if (batalSematkanBtn) {
        batalSematkanBtn.addEventListener('click', function() {
            const availableCheckboxes = document.querySelectorAll('.chat-message:not(.pinned) .bookmark-checkbox input');
            availableCheckboxes.forEach(checkbox => {
                checkbox.checked = false;
                const messageElem = checkbox.closest('.chat-message');
                messageElem.classList.remove('bookmarked');
            });
            
            if (simpanButton) {
                simpanButton.style.display = 'none';
            }
        });
    }
    
    // PERBAIKAN: Update fungsi updatePreviewSematan
    function updatePreviewSematan() {
        const previewKategori = document.getElementById('previewKategori');
        const previewJudul = document.getElementById('previewJudul');
        
        if (!previewKategori || !previewJudul) return;
        
        let selectedMahasiswaMessage = null;
        let selectedDosenMessage = null;
        let selectedCount = 0;
        
        // Hanya cek checkbox yang belum di-pin
        const availableCheckboxes = document.querySelectorAll('.chat-message:not(.pinned) .bookmark-checkbox input');
        availableCheckboxes.forEach(checkbox => {
            if (checkbox.checked) {
                selectedCount++;
                const messageElem = checkbox.closest('.chat-message');
                const messageId = messageElem.getAttribute('data-id');
                const messageBubble = messageElem.querySelector('.message-bubble p');
                const messageText = messageBubble ? messageBubble.textContent : '';
                
                // PERBAIKAN: Cek apakah pesan dari mahasiswa atau dosen
                if (messageId.startsWith('message-')) {
                    // Pesan utama - cek apakah dari mahasiswa
                    @if($pesan->nim_pengirim)
                        selectedMahasiswaMessage = messageText;
                    @else
                        selectedDosenMessage = messageText;
                    @endif
                } else if (messageId.startsWith('reply-')) {
                    // Balasan - cek berdasarkan posisi (message-reply = dosen, lainnya = mahasiswa)
                    if (messageElem.classList.contains('message-reply')) {
                        selectedDosenMessage = messageText;
                    } else {
                        selectedMahasiswaMessage = messageText;
                    }
                }
            }
        });
        
        if (selectedMahasiswaMessage) {
            // Update preview judul berdasarkan pesan mahasiswa
            let previewJudulText = selectedMahasiswaMessage;
            if (previewJudulText.length > 50) {
                previewJudulText = previewJudulText.substring(0, 50) + '...';
            }
            previewJudul.textContent = previewJudulText;
        } else {
            previewJudul.textContent = 'Pilih pesan dari mahasiswa untuk judul';
        }
        
        // Update kategori berdasarkan subjek
        const subjek = '{{ $pesan->subjek }}';
        let kategoriText = 'KRS'; // default
        
        if (subjek.toLowerCase().includes('kp')) {
            kategoriText = 'KP';
        } else if (subjek.toLowerCase().includes('skripsi')) {
            kategoriText = 'Skripsi';
        } else if (subjek.toLowerCase().includes('mbkm')) {
            kategoriText = 'MBKM';
        }
        
        previewKategori.textContent = 'Bimbingan ' + kategoriText;
    }
    
    // PERBAIKAN: Update simpan sematkan dengan validasi yang lebih baik
    if (simpanSematkanBtn) {
        simpanSematkanBtn.addEventListener('click', function() {
            // Dapatkan pesan yang dibookmark (hanya yang belum di-pin)
            const bookmarkedMessages = [];
            let hasMahasiswaMessage = false;
            let hasDosenMessage = false;
            
            const availableCheckboxes = document.querySelectorAll('.chat-message:not(.pinned) .bookmark-checkbox input');
            availableCheckboxes.forEach(checkbox => {
                if (checkbox.checked) {
                    const messageElem = checkbox.closest('.chat-message');
                    const messageId = messageElem.getAttribute('data-id');
                    
                    bookmarkedMessages.push(messageId);
                    
                    // PERBAIKAN: Cek apakah ada pesan dari mahasiswa dan dosen
                    if (messageId.startsWith('message-')) {
                        // Pesan utama
                        @if($pesan->nim_pengirim)
                            hasMahasiswaMessage = true;
                        @else
                            hasDosenMessage = true;
                        @endif
                    } else if (messageId.startsWith('reply-')) {
                        // Balasan - cek berdasarkan posisi
                        if (messageElem.classList.contains('message-reply')) {
                            hasDosenMessage = true;
                        } else {
                            hasMahasiswaMessage = true;
                        }
                    }
                    
                    // Tandai pesan sebagai bookmarked untuk UI
                    messageElem.classList.add('bookmarked');
                }
            });
            
            // PERBAIKAN: Validasi harus ada pesan dari mahasiswa dan dosen
            if (!hasMahasiswaMessage) {
                showNotification('Pilih minimal satu pesan dari mahasiswa untuk dijadikan judul FAQ', 'warning');
                return;
            }
            
            if (!hasDosenMessage) {
                showNotification('Pilih minimal satu pesan dari dosen untuk dijadikan isi sematan', 'warning');
                return;
            }
            
            // Dapatkan durasi yang dipilih
            const durasiRadio = document.querySelector('input[name="sematkanDurasi"]:checked');
            if (!durasiRadio) {
                showNotification('Pilih durasi sematan', 'warning');
                return;
            }
            const durasiValue = durasiRadio.value;
            
            // Kirim data sematan ke server
            fetch('{{ route("dosen.pesan.sematkan", $pesan->id) }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    message_ids: bookmarkedMessages,
                    durasi: parseInt(durasiValue, 10)
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Sembunyikan modal
                    if (sematkanModal) {
                        const bsModal = bootstrap.Modal.getInstance(sematkanModal);
                        if (bsModal) {
                            bsModal.hide();
                        }
                    }
                    
                    // PERBAIKAN: Tandai pesan yang dipilih sebagai disematkan di UI
                    if (data.data && data.data.pinned_messages) {
                        data.data.pinned_messages.forEach(messageId => {
                            const messageElem = document.querySelector(`[data-id="${messageId}"]`);
                            if (messageElem) {
                                // Tambahkan kelas untuk styling
                                messageElem.classList.add('pinned');
                                messageElem.classList.remove('bookmarked');
                                
                                // Tampilkan ikon pin
                                const bookmarkIcon = messageElem.querySelector('.bookmark-icon');
                                if (bookmarkIcon) {
                                    bookmarkIcon.innerHTML = '<i class="fas fa-thumbtack text-warning"></i>';
                                    bookmarkIcon.style.display = 'inline-block';
                                }
                                
                                // Sembunyikan checkbox
                                const checkboxContainer = messageElem.querySelector('.bookmark-checkbox');
                                if (checkboxContainer) {
                                    checkboxContainer.style.display = 'none';
                                }
                                
                                // Disable checkbox
                                const checkbox = messageElem.querySelector('.bookmark-checkbox input');
                                if (checkbox) {
                                    checkbox.disabled = true;
                                    checkbox.checked = false;
                                }
                                
                                // Sembunyikan tombol cancel
                                const cancelIcon = messageElem.querySelector('.bookmark-cancel');
                                if (cancelIcon) {
                                    cancelIcon.style.display = 'none';
                                }
                            }
                        });
                    } else {
                        // Fallback jika tidak ada pinned_messages di response
                        bookmarkedMessages.forEach(messageId => {
                            const messageElem = document.querySelector(`[data-id="${messageId}"]`);
                            if (messageElem) {
                                messageElem.classList.add('pinned');
                                messageElem.classList.remove('bookmarked');
                                
                                const bookmarkIcon = messageElem.querySelector('.bookmark-icon');
                                if (bookmarkIcon) {
                                    bookmarkIcon.innerHTML = '<i class="fas fa-thumbtack text-warning"></i>';
                                    bookmarkIcon.style.display = 'inline-block';
                                }
                                
                                const checkboxContainer = messageElem.querySelector('.bookmark-checkbox');
                                if (checkboxContainer) {
                                    checkboxContainer.style.display = 'none';
                                }
                                
                                const checkbox = messageElem.querySelector('.bookmark-checkbox input');
                                if (checkbox) {
                                    checkbox.disabled = true;
                                    checkbox.checked = false;
                                }
                                
                                const cancelIcon = messageElem.querySelector('.bookmark-cancel');
                                if (cancelIcon) {
                                    cancelIcon.style.display = 'none';
                                }
                            }
                        });
                    }
                    
                    // Setup ulang checkbox listeners setelah update
                    setupCheckboxListeners();
                    
                    // Sembunyikan tombol simpan
                    if (simpanButton) {
                        simpanButton.style.display = 'none';
                    }
                    
                    showNotification(data.message, 'success');
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
            
            if (confirm('Apakah Anda yakin ingin membatalkan sematan?')) {
                messageElem.classList.remove('bookmarked');
                messageElem.classList.remove('pinned');
                
                const sematanId = messageElem.getAttribute('data-sematan-id');
                
                if (sematanId) {
                    fetch('{{ url("/dosen/batalkan-sematan") }}/' + sematanId, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            const bookmarkIcon = messageElem.querySelector('.bookmark-icon');
                            if (bookmarkIcon) {
                                bookmarkIcon.innerHTML = '<i class="fas fa-bookmark"></i>';
                                bookmarkIcon.style.display = 'none';
                            }
                            
                            const checkboxContainer = messageElem.querySelector('.bookmark-checkbox');
                            if (checkboxContainer) {
                                checkboxContainer.style.display = 'none';
                            }
                            
                            const checkbox = messageElem.querySelector('.bookmark-checkbox input');
                            if (checkbox) {
                                checkbox.disabled = false;
                            }
                            
                            // Setup ulang listeners setelah perubahan
                            setupCheckboxListeners();
                            
                            showNotification('Sematan berhasil dibatalkan', 'success');
                        } else {
                            showNotification(data.message, 'warning');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        showNotification('Terjadi kesalahan saat membatalkan sematan', 'warning');
                    });
                } else {
                    const bookmarkIcon = messageElem.querySelector('.bookmark-icon');
                    if (bookmarkIcon) {
                        bookmarkIcon.innerHTML = '<i class="fas fa-bookmark"></i>';
                        bookmarkIcon.style.display = 'none';
                    }
                    
                    const checkboxContainer = messageElem.querySelector('.bookmark-checkbox');
                    if (checkboxContainer) {
                        checkboxContainer.style.display = 'none';
                    }
                    
                    const checkbox = messageElem.querySelector('.bookmark-checkbox input');
                    if (checkbox) {
                        checkbox.disabled = false;
                    }
                    
                    setupCheckboxListeners();
                    showNotification('Sematan berhasil dibatalkan');
                }
            }
        }
    });
    
    // Tampilkan modal konfirmasi untuk akhiri pesan (desktop)
    if (endChatButton) {
        endChatButton.addEventListener('click', function() {
            if (!isConversationEnded && {{ $pesan->nip_pengirim == Auth::user()->nip ? 'true' : 'false' }}) {
                confirmModal.show();
            }
        });
    }
    
    // Tampilkan modal konfirmasi untuk akhiri pesan (mobile)
    if (endChatButtonMobile) {
        endChatButtonMobile.addEventListener('click', function() {
            if (!isConversationEnded && {{ $pesan->nip_pengirim == Auth::user()->nip ? 'true' : 'false' }}) {
                confirmModal.show();
                closeMobileInfoPanel(); // Tutup info panel mobile saat modal muncul
            }
        });
    }
    
    // Konfirmasi akhiri pesan
    if (confirmEndChatBtn) {
        confirmEndChatBtn.addEventListener('click', function() {
            fetch('{{ route("dosen.pesan.end", $pesan->id) }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    isConversationEnded = true;
                    confirmModal.hide();
                    
                    if (messageInputContainer) {
                        messageInputContainer.style.display = 'none';
                    }
                    
                    // Update status untuk desktop
                    if (pesanStatus) {
                        pesanStatus.textContent = 'Berakhir';
                        pesanStatus.classList.remove('Umum');
                    }
                    
                    // Update status untuk mobile
                    if (pesanStatusMobile) {
                        pesanStatusMobile.textContent = 'Berakhir';
                        pesanStatusMobile.classList.remove('Umum');
                    }
                    
                    // Disable tombol desktop
                    if (endChatButton) {
                        endChatButton.disabled = true;
                        endChatButton.style.backgroundColor = '#6c757d';
                        endChatButton.style.cursor = 'not-allowed';
                        endChatButton.style.boxShadow = 'none';
                    }
                    
                    // Disable tombol mobile
                    if (endChatButtonMobile) {
                        endChatButtonMobile.disabled = true;
                        endChatButtonMobile.style.backgroundColor = '#6c757d';
                        endChatButtonMobile.style.cursor = 'not-allowed';
                        endChatButtonMobile.style.boxShadow = 'none';
                    }
                    
                    if (chatContainer) {
                        const systemMessage = document.createElement('div');
                        systemMessage.className = 'system-message';
                        systemMessage.innerHTML = '<span><i class="fas fa-info-circle me-1"></i> Pesan telah diakhiri oleh Anda</span>';
                        chatContainer.appendChild(systemMessage);
                        chatContainer.scrollTop = chatContainer.scrollHeight;
                    }
                    
                    showNotification('Pesan berhasil diakhiri', 'success');
                } else {
                    showNotification('Gagal mengakhiri pesan: ' + data.message, 'warning');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('Terjadi kesalahan saat mengakhiri pesan', 'warning');
            });
        });
    }
    
    // Fungsi untuk format waktu
    function formatTimeToJakarta(dateString) {
        if (!dateString) return '';
        
        if (/^\d{1,2}:\d{2}$/.test(dateString)) {
            return dateString;
        }
        
        const date = new Date(dateString);
        
        if (isNaN(date.getTime())) return dateString;
        
        const options = {
            hour: '2-digit',
            minute: '2-digit',
            timeZone: 'Asia/Jakarta'
        };
        
        return date.toLocaleTimeString('id-ID', options);
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
    
    // Menangani pengiriman pesan
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
        
        // PERBAIKAN: Validasi - boleh kirim pesan saja, lampiran saja, atau keduanya
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
            
            fetch('{{ route("dosen.pesan.reply", $pesan->id) }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify(requestData)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const formattedTime = formatTimeToJakarta(data.data.created_at);
                    
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
                    newMessage.setAttribute('data-id', 'reply-' + data.data.id);
                    
                    // PERBAIKAN: Tampilkan pesan yang sesuai
                    const displayMessage = message || '';
                    
                    newMessage.innerHTML = `
                        <div class="message-bubble ${attachment ? 'has-attachment' : ''}">
                            <div class="bookmark-checkbox">
                                <input class="form-check-input" type="checkbox" value="" id="bookmark-${data.data.id}">
                            </div>
                            ${displayMessage ? `<p>${displayMessage}</p>` : ''}
                            ${attachmentHtml}
                            <div class="message-time">
                                ${formattedTime}
                                <span class="bookmark-icon"><i class="fas fa-bookmark"></i></span>
                                <span class="bookmark-cancel" title="Batalkan sematan"><i class="fas fa-times"></i></span>
                            </div>
                        </div>
                    `;
                    
                    if (chatContainer) {
                        chatContainer.appendChild(newMessage);
                        chatContainer.scrollTop = chatContainer.scrollHeight;
                    }
                    
                    // Setup checkbox listener untuk pesan baru
                    setupCheckboxListeners();
                    
                    messageInput.value = '';
                    if (attachmentInput) {
                        attachmentInput.value = '';
                        attachmentInput.style.display = 'none';
                    }
                    if (attachmentButton) {
                        attachmentButton.innerHTML = '<i class="fas fa-paperclip"></i>';
                        attachmentButton.title = 'Lampirkan File';
                        isAttachmentMode = false;
                    }
                    
                    showNotification('Pesan berhasil dikirim', 'success');
                } else {
                    showNotification(data.message, 'warning');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('Terjadi kesalahan saat mengirim pesan', 'warning');
            });
        } else if (!message && !attachment) {
            // Validasi jika tidak ada pesan maupun lampiran
            showNotification('Mohon masukkan pesan atau lampiran', 'warning');
        }
    });
    }
    
    // Fungsi untuk menampilkan notifikasi
    function showNotification(message, type = 'success') {
        if (!notificationToast || !notificationMessage) return;
        
        // Reset classes
        notificationToast.classList.remove('bg-success', 'bg-warning', 'bg-danger', 'text-white', 'text-dark');
        
        if (type === 'success') {
            notificationToast.classList.add('bg-success', 'text-white');
        } else if (type === 'warning') {
            notificationToast.classList.add('bg-warning', 'text-dark');
        } else if (type === 'danger') {
            notificationToast.classList.add('bg-danger', 'text-white');
        }
        
        notificationMessage.textContent = message;
        
        const toast = new bootstrap.Toast(notificationToast, {
            delay: 3000
        });
        toast.show();
    }
    
    // Observer untuk mengamati perubahan DOM dan re-initialize listeners
    const observer = new MutationObserver(function(mutations) {
        mutations.forEach(function(mutation) {
            if (mutation.type === 'childList') {
                // Re-setup checkbox listeners setelah ada perubahan DOM
                setupCheckboxListeners();
            }
        });
    });
    
    // Start observing jika chatContainer ada
    if (chatContainer) {
        observer.observe(chatContainer, {
            childList: true,
            subtree: true
        });
    }
    
    // Fungsi untuk handle visibility change (ketika user switch tab)
    document.addEventListener('visibilitychange', function() {
        if (!document.hidden) {
            // Re-initialize status ketika user kembali ke tab
            initializePinnedStatus();
            setupCheckboxListeners();
        }
    });
    
    // Fungsi untuk handle window focus (ketika user kembali ke window)
    window.addEventListener('focus', function() {
        // Re-initialize status ketika window mendapat focus
        initializePinnedStatus();
        setupCheckboxListeners();
    });
    
    // PERBAIKAN: Fungsi untuk handle refresh data pinned status dari server
    function refreshPinnedStatusFromServer() {
        // Optional: Implementasi untuk sync dengan server jika diperlukan
        fetch('{{ route("dosen.pesan.show", $pesan->id) }}', {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(response => {
            if (response.ok) {
                // Re-initialize setelah data terbaru
                setTimeout(() => {
                    initializePinnedStatus();
                    setupCheckboxListeners();
                }, 100);
            }
        })
        .catch(error => {
            console.log('Unable to refresh pinned status:', error);
        });
    }
    
    // Periodic check untuk memastikan status tetap sinkron (optional)
    setInterval(function() {
        // Re-setup listeners setiap 30 detik untuk memastikan consistency
        setupCheckboxListeners();
    }, 30000);
    
    // PERBAIKAN: Handle error jika ada elemen yang tidak ditemukan
    function safeQuerySelector(selector) {
        try {
            return document.querySelector(selector);
        } catch (e) {
            console.warn('Element not found:', selector);
            return null;
        }
    }
    
    function safeQuerySelectorAll(selector) {
        try {
            return document.querySelectorAll(selector);
        } catch (e) {
            console.warn('Elements not found:', selector);
            return [];
        }
    }
    
    // PERBAIKAN: Validasi form sebelum submit
    function validateSematkanForm() {
        const availableCheckboxes = document.querySelectorAll('.chat-message:not(.pinned) .bookmark-checkbox input');
        const checkedBoxes = Array.from(availableCheckboxes).filter(cb => cb.checked);
        
        if (checkedBoxes.length === 0) {
            showNotification('Pilih minimal satu pesan untuk disematkan', 'warning');
            return false;
        }
        
        const durasiRadio = document.querySelector('input[name="sematkanDurasi"]:checked');
        if (!durasiRadio) {
            showNotification('Pilih durasi sematan', 'warning');
            return false;
        }
        
        return true;
    }
    
    // PERBAIKAN: Clean up event listeners untuk mencegah memory leak
    function cleanupEventListeners() {
        const availableCheckboxes = document.querySelectorAll('.chat-message:not(.pinned) .bookmark-checkbox input');
        availableCheckboxes.forEach(checkbox => {
            checkbox.removeEventListener('change', handleCheckboxChange);
        });
    }
    
    // Clean up ketika page akan di-unload
    window.addEventListener('beforeunload', function() {
        cleanupEventListeners();
        if (observer) {
            observer.disconnect();
        }
    });
    
    // Log untuk debugging
    console.log('Enhanced dosen isi pesan dengan mobile sidebar dan info panel initialized successfully');
    console.log('Available pinned messages on load:', document.querySelectorAll('.chat-message.pinned').length);
    console.log('Available unpinned messages on load:', document.querySelectorAll('.chat-message:not(.pinned)').length);
    
    // Final initialization call
    setTimeout(() => {
        initializePinnedStatus();
        setupCheckboxListeners();
    }, 100);
});
</script>
@endpush