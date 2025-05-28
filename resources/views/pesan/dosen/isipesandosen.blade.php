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
            font-size: 14px;
            margin-bottom: 20px;
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
            margin-top: 15px;
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

        /* ===============================
           MOBILE RESPONSIVE STYLES
           =============================== */

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
                width: 100px;
                height: 100px;
                font-size: 40px;
                margin-bottom: 8px;
            }
            
            /* Profile Text Smaller */
            .info-title {
                font-size: 16px;
                margin-bottom: 6px;
            }
            
            /* Info Table Mobile - MINIMAL CHANGES */
            .info-table {
                font-size: 13px;
                margin-bottom: 15px;
            }
            
            .info-table td {
                padding: 12px 15px;
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
                width: 80px;
                height: 80px;
                font-size: 32px;
            }
            
            /* Compact left panel */
            .left-panel {
                padding: 15px;
            }
            
            .info-title {
                font-size: 15px;
            }
            
            /* More compact info table - MINIMAL CHANGES */
            .info-table {
                font-size: 12px;
            }
            
            .info-table td {
                padding: 10px 12px;
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
                width: 70px;
                height: 70px;
                font-size: 28px;
            }
            
            .left-panel {
                padding: 12px;
            }
            
            .info-title {
                font-size: 14px;
                line-height: 1.2;
            }
            
            /* Ultra compact table - MINIMAL CHANGES */
            .info-table {
                font-size: 11px;
            }
            
            .info-table td {
                padding: 8px 10px;
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
                width: 60px;
                height: 60px;
                font-size: 24px;
                margin-bottom: 5px;
            }
            
            .info-title {
                font-size: 13px;
                margin-bottom: 4px;
            }
            
            .info-table {
                font-size: 10px;
                margin-bottom: 8px;
            }
            
            .info-table td {
                padding: 6px 8px;
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
                        @endif
                    </div>
                    
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
                        
                    </div>
                </div>
                
                <!-- Container Pesan -->
                <div class="chat-container" id="chatContainer">
                    @foreach($balasanByDate as $date => $messages)
                        <div class="chat-date-divider">
                            <span>{{ Carbon\Carbon::parse($date)->locale('id')->isoFormat('dddd, D MMMM YYYY') }}</span>
                        </div>
                        
                        {{-- Bagian yang perlu diperbaiki di isipesandosen.blade.php --}}
                        {{-- Ganti bagian balasan dengan kode di bawah ini --}}

                        @foreach($messages as $message)
                            @if($message instanceof App\Models\Pesan)
                                <!-- Pesan Awal -->
                                @if($message->nip_pengirim == Auth::user()->nip)
                                    <!-- Pesan yang dikirim dosen (posisi kanan) -->
                                    <div class="chat-message message-reply {{ $message->is_pinned ? 'pinned' : ($message->bookmarked ? 'bookmarked' : '') }}" data-id="message-{{ $message->id }}">
                                        <div class="message-bubble {{ $message->hasAttachment() ? 'has-attachment' : '' }}">
                                            <div class="bookmark-checkbox" style="{{ $message->is_pinned ? 'display: none;' : '' }}">
                                                <input class="form-check-input" type="checkbox" value="" id="bookmark-{{ $message->id }}" {{ $message->is_pinned ? 'disabled' : '' }}>
                                            </div>
                                            
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
                                                @if($message->is_pinned)
                                                    <span class="bookmark-icon" style="display: inline-block;">
                                                        <i class="fas fa-thumbtack text-warning"></i>
                                                    </span>
                                                @else
                                                    <span class="bookmark-icon">
                                                        <i class="fas fa-bookmark"></i>
                                                    </span>
                                                @endif
                                                <span class="bookmark-cancel" title="Batalkan sematan"><i class="fas fa-times"></i></span>
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <!-- Pesan dari mahasiswa (posisi kiri) -->
                                    <div class="chat-message {{ $message->is_pinned ? 'pinned' : ($message->bookmarked ? 'bookmarked' : '') }}" data-id="message-{{ $message->id }}">
                                        <div class="message-bubble {{ $message->hasAttachment() ? 'has-attachment' : '' }}">
                                            <div class="bookmark-checkbox" style="{{ $message->is_pinned ? 'display: none;' : '' }}">
                                                <input class="form-check-input" type="checkbox" value="" id="bookmark-{{ $message->id }}" {{ $message->is_pinned ? 'disabled' : '' }}>
                                            </div>
                                            
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
                                                @if($message->is_pinned)
                                                    <span class="bookmark-icon" style="display: inline-block;">
                                                        <i class="fas fa-thumbtack text-warning"></i>
                                                    </span>
                                                @else
                                                    <span class="bookmark-icon">
                                                        <i class="fas fa-bookmark"></i>
                                                    </span>
                                                @endif
                                                <span class="bookmark-cancel" title="Batalkan sematan"><i class="fas fa-times"></i></span>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            @else
                                @if($message->tipe_pengirim == 'dosen')
                                    <!-- Balasan dari Dosen (posisi kanan) -->
                                    <div class="chat-message message-reply {{ $message->is_pinned ? 'pinned' : ($message->bookmarked ? 'bookmarked' : '') }}" data-id="reply-{{ $message->id }}">
                                        <div class="message-bubble {{ $message->hasAttachment() ? 'has-attachment' : '' }}">
                                            <div class="bookmark-checkbox" style="{{ $message->is_pinned ? 'display: none;' : '' }}">
                                                <input class="form-check-input" type="checkbox" value="" id="bookmark-{{ $message->id }}" {{ $message->is_pinned ? 'disabled' : '' }}>
                                            </div>
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
                                            @if($message->is_pinned)
                                                    <span class="bookmark-icon" style="display: inline-block;">
                                                        <i class="fas fa-thumbtack text-warning"></i>
                                                    </span>
                                                @else
                                                    <span class="bookmark-icon">
                                                        <i class="fas fa-bookmark"></i>
                                                    </span>
                                                @endif
                                                <span class="bookmark-cancel" title="Batalkan sematan"><i class="fas fa-times"></i></span>
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <!-- Balasan dari Mahasiswa (posisi kiri) -->
                                    <div class="chat-message {{ $message->is_pinned ? 'pinned' : ($message->bookmarked ? 'bookmarked' : '') }}" data-id="reply-{{ $message->id }}">
                                        <div class="message-bubble {{ $message->hasAttachment() ? 'has-attachment' : '' }}">
                                            <div class="bookmark-checkbox" style="{{ $message->is_pinned ? 'display: none;' : '' }}">
                                                <input class="form-check-input" type="checkbox" value="" id="bookmark-{{ $message->id }}" {{ $message->is_pinned ? 'disabled' : '' }}>
                                            </div>
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
                                                @if($message->is_pinned)
                                                    <span class="bookmark-icon" style="display: inline-block;">
                                                        <i class="fas fa-thumbtack text-warning"></i>
                                                    </span>
                                                @else
                                                    <span class="bookmark-icon">
                                                        <i class="fas fa-bookmark"></i>
                                                    </span>
                                                @endif
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
                <div class="mb-3">
                    <label for="sematkanJudul" class="form-label">Judul untuk Pesan yang disematkan</label>
                    <input type="text" class="form-control" id="sematkanJudul" placeholder="Masukkan judul">
                </div>
                <div class="mb-3">
                    <label for="sematkanKategori" class="form-label">Kategori</label>
                    <select class="form-select" id="sematkanKategori">
                        <option value="krs">Bimbingan KRS</option>
                        <option value="kp">Bimbingan KP</option>
                        <option value="skripsi">Bimbingan Skripsi</option>
                        <option value="mbkm">Bimbingan MBKM</option>
                    </select>
                </div>
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
                <p class="text-muted small">Pesan yang disematkan akan ditampilkan di halaman pesan tersematkan selama durasi yang dipilih.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" id="batalSematkan">Batal</button>
                <button type="button" class="btn btn-primary" id="simpanSematkan">Simpan</button>
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
    // Elemen yang diperlukan
    const bookmarkButton = document.querySelector('#bookmarkButton');
    const simpanButton = document.querySelector('#simpanButton');
    const bookmarkCheckboxes = document.querySelectorAll('.bookmark-checkbox');
    const checkboxInputs = document.querySelectorAll('.bookmark-checkbox input');
    const sematkanModal = document.querySelector('#sematkanModal');
    const batalSematkanBtn = document.querySelector('#batalSematkan');
    const simpanSematkanBtn = document.querySelector('#simpanSematkan');
    const sematkanJudul = document.getElementById('sematkanJudul');
    const sematkanKategori = document.getElementById('sematkanKategori');
    const chatContainer = document.getElementById('chatContainer');
    const messageInput = document.querySelector('.message-input');
    const sendButton = document.querySelector('.send-button');
    const endChatButton = document.getElementById('endChatButton');
    const confirmModal = new bootstrap.Modal(document.getElementById('confirmModal'));
    const cancelEndChatBtn = document.getElementById('cancelEndChat');
    const confirmEndChatBtn = document.getElementById('confirmEndChat');
    const messageInputContainer = document.getElementById('messageInputContainer');
    const pesanStatus = document.getElementById('pesanStatus');
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
    
    // Sembunyikan tombol simpan secara default
    simpanButton.style.display = 'none';
    
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
    
    // Tampilkan modal konfirmasi untuk akhiri pesan
    if (endChatButton) {
        endChatButton.addEventListener('click', function() {
            // Pastikan ini adalah pengirim pesan dan percakapan belum diakhiri
            if (!isConversationEnded && {{ $pesan->nip_pengirim == Auth::user()->nip ? 'true' : 'false' }}) {
                confirmModal.show();
            }
        });
    }
    
    // Konfirmasi akhiri pesan
    if (confirmEndChatBtn) {
        confirmEndChatBtn.addEventListener('click', function() {
            // Kirim request ke server untuk mengakhiri pesan
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
                    systemMessage.innerHTML = '<span><i class="fas fa-info-circle me-1"></i> Pesan telah diakhiri oleh Anda</span>';
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
                
                // Tampilkan semua checkbox bookmark KECUALI pesan yang sudah disematkan
                document.querySelectorAll('.chat-message:not(.pinned) .bookmark-checkbox').forEach(checkbox => {
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
            // Tampilkan modal sematkan menggunakan Bootstrap modal
            const bsModal = new bootstrap.Modal(sematkanModal);
            bsModal.show();
            
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
            
            // Reset field judul
            sematkanJudul.value = '';
            
            // Sembunyikan tombol simpan
            simpanButton.style.display = 'none';
        });
    }
    
    // Simpan sematkan
    if (simpanSematkanBtn) {
        simpanSematkanBtn.addEventListener('click', function() {
            // Validasi judul
            if (!sematkanJudul.value.trim()) {
                showNotification('Judul harus diisi', 'warning');
                return;
            }
            
            // Dapatkan pesan yang dibookmark
            const bookmarkedMessages = [];
            checkboxInputs.forEach(checkbox => {
                if (checkbox.checked) {
                    const messageElem = checkbox.closest('.chat-message');
                    const messageId = messageElem.getAttribute('data-id');
                    
                    bookmarkedMessages.push(messageId);
                    
                    // Tandai pesan sebagai bookmarked untuk UI
                    messageElem.classList.add('bookmarked');
                }
            });
            
            // Dapatkan kategori yang dipilih
            const kategori = sematkanKategori.value;
            
            // Dapatkan durasi yang dipilih
            const durasiValue = document.querySelector('input[name="sematkanDurasi"]:checked').value;
            
            // Kirim data sematan ke server
            fetch('{{ route("dosen.pesan.sematkan", $pesan->id) }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    message_ids: bookmarkedMessages,
                    kategori: kategori,
                    judul: sematkanJudul.value,
                    durasi: parseInt(durasiValue, 10)
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Sembunyikan modal
                    const bsModal = bootstrap.Modal.getInstance(sematkanModal);
                    bsModal.hide();
                    
                    // Tandai pesan yang dipilih sebagai disematkan di UI
                    checkboxInputs.forEach(checkbox => {
                        if (checkbox.checked) {
                            const messageElem = checkbox.closest('.chat-message');
                            
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
                            checkbox.disabled = true;
                            checkbox.checked = false;
                        }
                    });
                    
                    // Sembunyikan tombol simpan
                    simpanButton.style.display = 'none';
                    
                    // Reset fields
                    sematkanJudul.value = '';
                    
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
            
            // Konfirmasi pembatalan sematan
            if (confirm('Apakah Anda yakin ingin membatalkan sematan?')) {
                // Hapus kelas bookmarked dari pesan
                messageElem.classList.remove('bookmarked');
                messageElem.classList.remove('pinned');
                
                // Dapatkan ID sematan untuk dibatalkan (dari atribut data-sematan-id jika ada)
                const sematanId = messageElem.getAttribute('data-sematan-id');
                
                if (sematanId) {
                    // Kirim permintaan untuk membatalkan sematan ke server
                    fetch('{{ url("/batalkan-sematan") }}/' + sematanId, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Reset tampilan pesan
                            const bookmarkIcon = messageElem.querySelector('.bookmark-icon');
                            if (bookmarkIcon) {
                                bookmarkIcon.innerHTML = '<i class="fas fa-bookmark"></i>';
                                bookmarkIcon.style.display = 'none';
                            }
                            
                            // Tampilkan checkbox kembali
                            const checkboxContainer = messageElem.querySelector('.bookmark-checkbox');
                            if (checkboxContainer) {
                                checkboxContainer.style.display = 'none'; // Tetap disembunyikan sampai mode bookmark aktif
                            }
                            
                            // Enable checkbox
                            const checkbox = messageElem.querySelector('.bookmark-checkbox input');
                            if (checkbox) {
                                checkbox.disabled = false;
                            }
                            
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
                    // Jika tidak ada ID sematan (hanya UI lokal)
                    // Reset tampilan pesan
                    const bookmarkIcon = messageElem.querySelector('.bookmark-icon');
                    if (bookmarkIcon) {
                        bookmarkIcon.innerHTML = '<i class="fas fa-bookmark"></i>';
                        bookmarkIcon.style.display = 'none';
                    }
                    
                    showNotification('Sematan berhasil dibatalkan');
                }
            }
        }
    });
    
    // Fungsi untuk format waktu
    function formatTimeToJakarta(dateString) {
        // Pastikan kita memiliki tanggal yang valid
        if (!dateString) return '';
        
        // Jika sudah dalam format jam:menit (H:i), langsung kembalikan
        if (/^\d{1,2}:\d{2}$/.test(dateString)) {
            return dateString;
        }
        
        // Buat objek tanggal
        const date = new Date(dateString);
        
        // Pastikan tanggal valid
        if (isNaN(date.getTime())) return dateString;
        
        // Opsi untuk format waktu Indonesia
        const options = {
            hour: '2-digit',
            minute: '2-digit',
            timeZone: 'Asia/Jakarta'
        };
        
        // Format waktu sesuai dengan locale Indonesia dan timezone Jakarta
        return date.toLocaleTimeString('id-ID', options);
    }
    
    // Helper function untuk mendapatkan nama attachment
    function getAttachmentName(url) {
        if (url.includes('drive.google.com')) {
            return 'Google Drive File';
        }
        
        const path = new URL(url).pathname;
        const filename = path.split('/').pop();
        
        return filename || 'Lampiran';
    }
    
    // Menangani pengiriman pesan dengan format waktu yang benar
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
            
            if (message && !isConversationEnded) {
                // Persiapkan data untuk dikirim
                const requestData = { balasan: message };
                
                // Tambahkan attachment jika ada
                if (attachment) {
                    requestData.lampiran = attachment;
                }
                
                // Kirim pesan ke server
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
                        // Format waktu pesan dengan benar
                        const formattedTime = formatTimeToJakarta(data.data.created_at);
                        
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
                        newMessage.setAttribute('data-id', 'reply-' + data.data.id);
                        
                        newMessage.innerHTML = `
                            <div class="message-bubble ${attachment ? 'has-attachment' : ''}">
                                <div class="bookmark-checkbox">
                                    <input class="form-check-input" type="checkbox" value="" id="bookmark-${data.data.id}">
                                </div>
                                <p>${data.data.isi_balasan}</p>
                                ${attachmentHtml}
                                <div class="message-time">
                                    ${formattedTime}
                                    <span class="bookmark-icon"><i class="fas fa-bookmark"></i></span>
                                    <span class="bookmark-cancel" title="Batalkan sematan"><i class="fas fa-times"></i></span>
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
        const notificationToast = document.getElementById('notificationToast');
        notificationToast.classList.remove('bg-success', 'bg-warning', 'bg-danger');
        
        if (type === 'success') {
            notificationToast.classList.add('bg-success');
        } else if (type === 'warning') {
            notificationToast.classList.add('bg-warning', 'text-dark');
            notificationToast.classList.remove('text-white');
        } else if (type === 'danger') {
            notificationToast.classList.add('bg-danger');
        }
        
        const notificationMessage = document.getElementById('notificationMessage');
        notificationMessage.textContent = message;
        
        const toast = new bootstrap.Toast(notificationToast);
        toast.show();
    }
});
</script>
@endpush