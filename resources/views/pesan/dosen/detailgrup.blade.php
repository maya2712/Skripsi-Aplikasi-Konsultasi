@extends('layouts.app')

@section('title', 'Detail Grup - ' . $grup->nama_grup)

@push('styles')
<style>
    /* HIDE DEFAULT SEPTI NOTIFICATIONS */
    .alert-success,
    .alert-info,
    .alert-warning,
    .alert-danger,
    .alert-primary,
    .alert-secondary,
    .alert-light,
    .alert-dark {
        display: none !important;
    }
    
    /* Jika ada notifikasi Bootstrap lainnya yang muncul otomatis */
    .alert {
        display: none !important;
    }
    
    /* Jika notifikasi menggunakan class khusus SEPTI */
    .notification,
    .septi-notification,
    .default-notification,
    .system-notification {
        display: none !important;
    }
    
    /* Sembunyikan semua div dengan class yang mengandung 'alert' atau 'notification' */
    div[class*="alert"],
    div[class*="notification"] {
        display: none !important;
    }

    :root {
        --bs-primary: #1a73e8;
        --bs-danger: #FF5252;
        --bs-success: #27AE60;
        --primary-gradient: linear-gradient(to right, #004AAD, #5DE0E6);
        --primary-hover: linear-gradient(to right, #003c8a, #4bc4c9);
        --danger-gradient: linear-gradient(135deg, #FF5252, #FF1744);
        --danger-hover: linear-gradient(135deg, #e04848, #e6153e);
        --success-gradient: linear-gradient(135deg, #27AE60, #2ECC71);
        --success-hover: linear-gradient(135deg, #229954, #28b965);
        --text-color: #546E7A;
    }
    
    body {
        background-color: #F5F7FA;
        font-size: 13px;
    }

    .main-content {
        padding-top: 20px; 
        padding-bottom: 20px; 
    }
    
    .btn-custom-primary {
        background: var(--bs-primary);
        color: white;
    }
    
    .btn-custom-primary:hover {
        background: #1557b0;
        color: white;
    }

    .sidebar {
        background: #ffffff;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        position: sticky;
        top: 20px; 
        max-height: calc(100vh - 100px);
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
        position: relative;
        width: 100%;
        text-align: left;
    }
    
    .sidebar-menu .nav-link.active {
        background: #E3F2FD;
        color: var(--bs-primary);
    }
    
    .sidebar-menu .nav-link:hover:not(.active) {
        background: #f8f9fa;
    }

    .komunikasi-submenu .nav-link.active,
    .pengaturan-submenu .nav-link.active {
        background: #E3F2FD;
        color: var(--bs-primary);
    }

    .komunikasi-submenu .nav-link:hover:not(.active),
    .pengaturan-submenu .nav-link:hover:not(.active) {
        background: #f8f9fa;
    }

    .komunikasi-submenu,
    .pengaturan-submenu {
        margin-left: 15px;
        width: 100%;
    }

    .komunikasi-submenu .nav-link,
    .pengaturan-submenu .nav-link {
        padding: 8px 15px;
        font-size: 13px;
        width: 100%;
        color: #546E7A;
    }
    
    .profile-image-placeholder {
        width: 45px;
        height: 45px;
        border-radius: 50%;
        background-color: #e9ecef;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #6c757d;
        font-size: 20px;
        border: 2px solid #f8f9fa;
        margin: 0 auto;
    }

    .profile-image-real {
        width: 45px;
        height: 45px;
        border-radius: 50%;
        object-fit: cover;
        border: 2px solid #f8f9fa;
        margin: 0 auto;
        display: block;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    }
    
    .btn-gradient-primary {
        background: var(--primary-gradient);
        border: none;
        color: white;
        transition: all 0.3s ease;
    }

    .btn-gradient-primary:hover {
        background: var(--primary-hover);
        color: white;
        box-shadow: 0 2px 5px rgba(0,0,0,0.2);
    }

    .btn-gradient-danger {
        background: var(--danger-gradient);
        border: none;
        color: white;
        transition: all 0.3s ease;
        font-weight: 500;
    }

    .btn-gradient-danger:hover {
        background: var(--danger-hover);
        color: white;
        box-shadow: 0 4px 15px rgba(255, 82, 82, 0.4);
        transform: translateY(-1px);
    }

    .btn-gradient-success {
        background: var(--success-gradient);
        border: none;
        color: white;
        transition: all 0.3s ease;
        font-weight: 500;
    }

    .btn-gradient-success:hover {
        background: var(--success-hover);
        color: white;
        box-shadow: 0 4px 15px rgba(39, 174, 96, 0.4);
        transform: translateY(-1px);
    }
    
    .group-header {
        background: var(--primary-gradient);
        color: white;
        border-radius: 10px;
        padding: 20px;
        margin-bottom: 20px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }
    
    .message-container {
        max-height: calc(100vh - 350px);
        overflow-y: auto;
        margin-bottom: 20px;
        background-color: white;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        padding: 15px 20px;
    }
    
    .message-input {
        position: sticky;
        bottom: 0;
        background: white;
        padding: 15px;
        border-radius: 10px;
        box-shadow: 0 -2px 10px rgba(0,0,0,0.05);
        margin-top: 15px;
    }

    .header-icon {
        color: white;
        font-size: 1.2rem;
        margin-left: 15px;
        cursor: pointer;
        opacity: 0.9;
        transition: all 0.3s ease;
        padding: 8px;
        border-radius: 50%;
    }

    .header-icon:hover {
        opacity: 1;
        background-color: rgba(255, 255, 255, 0.2);
        transform: scale(1.1);
    }

    .header-icon.danger:hover {
        color: #FF5252;
        background-color: rgba(255, 82, 82, 0.2);
    }
    
    .custom-container {
        max-width: 1400px;
        margin: 0 auto;
        padding: 0 15px;
    }
    
    .badge-notification {
        background: var(--bs-danger);
    }
    
    .message-container::-webkit-scrollbar {
        width: 6px;
    }

    .message-container::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 10px;
    }

    .message-container::-webkit-scrollbar-thumb {
        background: #888;
        border-radius: 10px;
    }

    .message-container::-webkit-scrollbar-thumb:hover {
        background: #555;
    }
    
    .message-time {
        color: rgba(255, 255, 255, 0.7);
        font-size: 12px;
        text-align: right;
        margin-top: 8px;
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
    
    .message-bubble {
        padding: 15px 20px;
        border-radius: 15px;
        position: relative;
        word-wrap: break-word;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
        width: fit-content;
        max-width: 85%;
    }
    
    .chat-message.dosen {
        margin-left: auto;
    }
    
    .chat-message.dosen .message-bubble {
        background-color: var(--bs-primary);
        color: white;
        border-radius: 15px 15px 3px 15px;
        margin-left: auto;
    }
    
    .chat-message.mahasiswa .message-bubble {
        background-color: #585f67;
        color: white;
        border-radius: 15px 15px 15px 3px;
    }
    
    .sender-name {
        color: #f8ac30;
        font-size: 14px;
        font-weight: 600;
        margin-bottom: 4px;
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
    
    .pengaturan-submenu .btn-link {
        padding: 8px 15px;
        display: flex;
        align-items: center;
        text-decoration: none;
        border: none;
        background: none;
        font-size: 13px;
        width: 100%;
        text-align: left;
        margin: 0;
        border-radius: 0.5rem;
        color: #546E7A;
    }
    
    .pengaturan-submenu .btn-link:hover {
        background-color: #f8f9fa;
        color: #546E7A;
    }
    
    /* Style untuk input message yang normal (tidak tersalin/miring) */
    #messageInput {
        font-style: normal !important;
        font-family: inherit !important;
        text-transform: none !important;
    }
    
    /* ENHANCED MODAL STYLES */
    .modal-content {
        border: none;
        border-radius: 15px;
        box-shadow: 0 10px 40px rgba(0,0,0,0.15);
        overflow: hidden;
    }

    .modal-header {
        background: var(--primary-gradient);
        color: white;
        border-bottom: none;
        padding: 20px 25px;
        position: relative;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .modal-header::before {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        height: 3px;
        background: linear-gradient(90deg, rgba(255,255,255,0.3), rgba(255,255,255,0.1), rgba(255,255,255,0.3));
    }

    .modal-title {
        font-weight: 600;
        font-size: 1.2rem;
        margin: 0;
        flex: 1;
    }

    .btn-close {
        opacity: 1;
        font-size: 1.5rem;
        font-weight: normal;
        transition: all 0.2s ease;
        background: none !important;
        border: none !important;
        color: white !important;
        padding: 0 !important;
        width: 30px !important;
        height: 30px !important;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        box-shadow: none !important;
        outline: none !important;
        margin-left: auto !important;
        flex-shrink: 0 !important;
    }

    .btn-close:hover {
        opacity: 0.8;
        transform: scale(1.1);
        background: none !important;
        box-shadow: none !important;
    }

    .btn-close:focus {
        box-shadow: none !important;
        outline: none !important;
    }

    .btn-close::before {
        content: "×";
        font-size: 1.8rem;
        font-weight: bold;
        line-height: 1;
    }

    .modal-header {
        background: var(--primary-gradient);
        color: white;
        border-bottom: none;
        padding: 20px 25px;
        position: relative;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .modal-body {
        padding: 25px;
        background: #fafbfc;
    }

    .modal-footer {
        background: white;
        border-top: 1px solid rgba(0,0,0,0.05);
        padding: 20px 25px;
    }
    
    /* Style untuk modal loading */
    .modal-loading .modal-content {
        border: none;
        border-radius: 15px;
        box-shadow: 0 5px 25px rgba(0,0,0,0.2);
        background: white;
    }
    
    .modal-loading .loading-container {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 2.5rem;
    }
    
    .modal-loading .loading-spinner {
        display: inline-block;
        width: 3.5rem;
        height: 3.5rem;
        border: 4px solid rgba(0, 0, 0, 0.1);
        border-radius: 50%;
        border-top-color: #5DE0E6;
        border-left-color: #004AAD;
        animation: spin 1s ease-in-out infinite;
        margin-bottom: 1rem;
    }
    
    @keyframes spin {
        to { transform: rotate(360deg); }
    }
    
    .modal-loading .loading-text {
        margin-top: 15px;
        font-size: 16px;
        font-weight: 500;
        background: var(--primary-gradient);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        text-align: center;
    }
    
    /* Style untuk modal konfirmasi hapus */
    .modal-confirm .modal-header {
        background: var(--primary-gradient);
        color: white;
    }

    .modal-confirm .modal-body {
        padding: 2rem 1.5rem;
        text-align: center;
        background: white;
    }
    
    .modal-confirm .confirm-icon {
        font-size: 4rem;
        color: #6c757d;
        margin-bottom: 1.5rem;
        animation: pulse 2s infinite;
    }

    @keyframes pulse {
        0% { transform: scale(1); }
        50% { transform: scale(1.05); }
        100% { transform: scale(1); }
    }
    
    .modal-confirm .confirm-title {
        font-size: 1.4rem;
        font-weight: 700;
        margin-bottom: 0.8rem;
        color: #2c3e50;
    }
    
    .modal-confirm .confirm-message {
        font-size: 1rem;
        color: #6c757d;
        line-height: 1.6;
        margin-bottom: 1.5rem;
    }

    /* Info Modal Styles */
    .info-item {
        background: white;
        border-radius: 10px;
        padding: 15px;
        margin-bottom: 15px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        border-left: 4px solid transparent;
        border-image: var(--primary-gradient) 1;
        transition: all 0.3s ease;
    }

    .info-item:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 20px rgba(0,0,0,0.1);
    }

    .info-item label {
        font-weight: 600;
        color: #2c3e50;
        display: flex;
        align-items: center;
        margin-bottom: 8px;
    }

    .info-item label i {
        margin-right: 8px;
        color: #004AAD;
    }

    .info-item p {
        margin: 0;
        color: #546E7A;
        font-size: 0.95rem;
    }

    /* Table Styles for Member List */
    .table-responsive {
        border-radius: 8px;
        overflow: hidden;
        border: 1px solid #e9ecef;
    }

    .table {
        margin-bottom: 0;
        background: white;
        border-collapse: collapse;
    }

    .table thead th {
        background: #f8f9fa;
        color: #495057;
        border: 1px solid #e9ecef;
        font-weight: 600;
        text-align: center;
        padding: 12px 10px;
        font-size: 0.9rem;
    }

    .table tbody td {
        text-align: center;
        vertical-align: middle;
        padding: 12px 10px;
        border: 1px solid #e9ecef;
        font-size: 0.9rem;
    }

    .table tbody tr:hover {
        background-color: #f8f9fa;
    }

    .table tbody tr:nth-child(even) {
        background-color: #fafbfc;
    }

    .table tbody tr:nth-child(even):hover {
        background-color: #f8f9fa;
    }

    /* Hover effect untuk tombol hapus anggota */
    .delete-member-btn:hover {
        background-color: #dc3545 !important;
        border-color: #dc3545 !important;
        color: white !important;
        transform: translateY(-1px);
        box-shadow: 0 2px 5px rgba(220, 53, 69, 0.3);
    }

    /* Search Box Styles */
    .form-control {
        border: 2px solid #e9ecef;
        border-radius: 10px;
        padding: 12px 15px;
        transition: all 0.3s ease;
    }

    .form-control:focus {
        border-color: #5DE0E6;
        box-shadow: 0 0 0 0.2rem rgba(93, 224, 230, 0.25);
    }

    /* Checkbox Styles */
    .form-check {
        background: white;
        border-radius: 8px;
        padding: 10px 15px;
        margin-bottom: 8px;
        border: 1px solid #e9ecef;
        transition: all 0.3s ease;
    }

    .form-check:hover {
        background: rgba(0, 74, 173, 0.05);
        border-color: #5DE0E6;
    }

    .form-check-input:checked {
        background-color: #004AAD;
        border-color: #004AAD;
    }

    .form-check-label {
        color: #2c3e50;
        font-weight: 500;
        cursor: pointer;
    }

    /* Tab Styles */
    .nav-tabs {
        border-bottom: 2px solid #e9ecef;
        margin-bottom: 20px;
    }

    .nav-tabs .nav-link {
        border: none;
        border-bottom: 3px solid transparent;
        color: #6c757d;
        font-weight: 500;
        padding: 12px 20px;
        transition: all 0.3s ease;
    }

    .nav-tabs .nav-link.active {
        color: #004AAD;
        border-bottom-color: #5DE0E6;
        background: none;
    }

    .nav-tabs .nav-link:hover:not(.active) {
        border-bottom-color: rgba(93, 224, 230, 0.5);
        color: #004AAD;
    }

    /* Alert Styles - HANYA UNTUK CUSTOM ALERT, BUKAN BAWAAN SEPTI */
    .custom-alert-info {
        background: linear-gradient(135deg, rgba(0, 74, 173, 0.1), rgba(93, 224, 230, 0.1));
        border: 1px solid rgba(93, 224, 230, 0.3);
        color: #004AAD;
        border-radius: 10px;
        font-weight: 500;
    }

    /* Custom Toast Close Button Styles */
    .toast .btn-close {
        background: transparent !important;
        border: none !important;
        opacity: 0.9 !important;
        font-size: 18px !important;
        width: 30px !important;
        height: 30px !important;
        padding: 0 !important;
        margin: 8px 12px 8px 8px !important;
        color: white !important;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        position: relative !important;
        top: 0 !important;
        right: 0 !important;
        transform: none !important;
        border-radius: 50% !important;
        transition: all 0.2s ease !important;
        flex-shrink: 0 !important;
    }

    .toast .btn-close:hover {
        opacity: 1 !important;
        color: white !important;
        background: rgba(255, 255, 255, 0.1) !important;
        transform: scale(1.1) !important;
    }

    .toast .btn-close:focus {
        box-shadow: none !important;
        outline: none !important;
    }

    .toast .btn-close::before {
        content: "×" !important;
        font-weight: bold !important;
        font-size: 22px !important;
        color: white !important;
        line-height: 1 !important;
        display: block !important;
        text-align: center !important;
    }

    /* Force white color for all toast close buttons */
    .toast .btn-close,
    .toast .btn-close:hover,
    .toast .btn-close:focus {
        filter: brightness(0) invert(1) !important;
    }

    /* Toast body alignment fix */
    .toast .d-flex {
        align-items: center !important;
        padding: 0 !important;
    }

    .toast .toast-body {
        display: flex !important;
        align-items: center !important;
        padding: 12px 16px !important;
        flex-grow: 1 !important;
    }

    /* Toast container styling */
    .toast {
        min-width: 300px !important;
        border-radius: 10px !important;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15) !important;
    }

    /* SUCCESS MODAL STYLES */
    .modal-success .modal-content {
        border: none;
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 15px 50px rgba(39, 174, 96, 0.2);
    }

    .modal-success .modal-header {
        background: var(--success-gradient);
        color: white;
        border-bottom: none;
        padding: 25px;
        text-align: center;
    }

    .modal-success .modal-body {
        padding: 30px;
        text-align: center;
        background: white;
    }

    .modal-success .success-icon {
        width: 80px;
        height: 80px;
        margin: 0 auto 20px;
        background: var(--success-gradient);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 2.5rem;
        animation: successPulse 1.5s ease-in-out;
    }

    @keyframes successPulse {
        0% { transform: scale(0.8); opacity: 0.5; }
        50% { transform: scale(1.1); opacity: 1; }
        100% { transform: scale(1); opacity: 1; }
    }

    .modal-success .success-title {
        font-size: 1.5rem;
        font-weight: 700;
        color: #27AE60;
        margin-bottom: 15px;
    }

    .modal-success .success-message {
        font-size: 1rem;
        color: #6c757d;
        line-height: 1.6;
        margin-bottom: 20px;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .custom-container {
            padding: 0 10px;
        }
        
        .main-content {
            padding-top: 15px;
            padding-bottom: 15px;
        }
        
        .group-header {
            padding: 15px;
            margin-bottom: 15px;
        }
        
        .group-header h5 {
            font-size: 1.1rem;
        }
        
        .sidebar {
            position: relative;
            top: 0;
            max-height: none;
            margin-bottom: 20px;
        }
        
        .message-container {
            max-height: 400px;
            padding: 10px 15px;
        }
        
        .message-input {
            padding: 10px;
        }
        
        .chat-message {
            max-width: 90%;
        }
        
        .message-bubble {
            padding: 12px 15px;
            max-width: 95%;
        }
        
        .modal-dialog {
            margin: 10px;
            max-width: calc(100% - 20px);
        }
        
        .modal-body {
            padding: 20px 15px;
        }
        
        .modal-header {
            padding: 15px 20px;
        }
        
        .modal-footer {
            padding: 15px 20px;
        }
        
        .table-responsive {
            font-size: 0.85rem;
        }
        
        .table thead th,
        .table tbody td {
            padding: 8px 5px;
        }
        
        .profile-image-placeholder {
            width: 35px;
            height: 35px;
            font-size: 16px;
        }
        
        .btn {
            padding: 8px 12px;
            font-size: 0.85rem;
        }
        
        .form-check {
            padding: 8px 12px;
        }
        
        .nav-tabs .nav-link {
            padding: 10px 15px;
            font-size: 0.9rem;
        }
        
        .confirm-icon {
            font-size: 3rem !important;
        }
        
        .confirm-title {
            font-size: 1.2rem !important;
        }
        
        .confirm-message {
            font-size: 0.9rem !important;
        }
        
        .loading-spinner {
            width: 3rem !important;
            height: 3rem !important;
        }
        
        .loading-text {
            font-size: 14px !important;
        }
    }

    @media (max-width: 576px) {
        .header-icon {
            margin-left: 8px;
            padding: 6px;
            font-size: 1rem;
        }
        
        .group-header .d-flex {
            flex-direction: column;
            align-items: flex-start;
        }
        
        .group-header .d-flex > div:last-child {
            margin-top: 10px;
            align-self: flex-end;
        }
        
        .modal-dialog {
            margin: 5px;
            max-width: calc(100% - 10px);
        }
        
        .btn-group-mobile {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }
        
        .btn-group-mobile .btn {
            width: 100%;
        }
    }
</style>
@endpush

@section('content')
<div class="main-content">
    <div class="custom-container">
        <div class="row g-4">
            <!-- Sidebar - Kolom Kiri -->
            <div class="col-md-3">
                <div class="sidebar">
                    <div class="sidebar-buttons">
                        <a href="{{ route('dosen.pesan.create') }}" class="btn" style="background: var(--primary-gradient); color: white; padding: 10px 20px; border: none; border-radius: 5px;">
                            <i class="fas fa-plus me-2"></i> Pesan Baru
                        </a>
                    </div>                                                    
                    
                    <!-- Sidebar Menu dengan Notifikasi Pesan Belum Dibaca -->
                    <div class="sidebar-menu">
                        <div class="nav flex-column">
                            <a href="{{ route('dosen.dashboard.pesan') }}" class="nav-link">
                                <i class="fas fa-home me-2"></i>Daftar Pesan
                            </a>
                            <a href="#" class="nav-link menu-item active" id="grupDropdownToggle">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span><i class="fas fa-users me-2"></i>Daftar Grup</span>
                                    <i class="fas fa-chevron-down" id="grupDropdownIcon"></i>
                                </div>
                            </a>
                            <div class="collapse show komunikasi-submenu" id="komunikasiSubmenu">
                                <a href="{{ route('dosen.grup.create') }}" class="nav-link menu-item d-flex align-items-center" style="color: #546E7A;">
                                    <i class="fas fa-plus me-2"></i>Grup Baru
                                </a>
                                
                                @php
                                    $activeRole = session('active_role', 'dosen');
                                    $grups = App\Models\Grup::where('dosen_id', Auth::user()->nip)
                                                            ->where('dosen_role', $activeRole)
                                                            ->get();
                                @endphp
                                
                                @if($grups && $grups->count() > 0)
                                    @foreach($grups as $grupItem)
                                    <a href="{{ route('dosen.grup.show', $grupItem->id) }}" class="nav-link menu-item d-flex justify-content-between align-items-center {{ $grupItem->id == $grup->id ? 'active' : '' }}">
                                        {{ $grupItem->nama_grup }}
                                        @if(isset($grupItem->unreadCount) && $grupItem->unreadCount > 0)
                                        <span class="badge bg-danger rounded-pill">{{ $grupItem->unreadCount }}</span>
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
            </div>

            <!-- Main Content Area - Kolom Kanan -->
            <div class="col-md-9">
                <!-- Group Header -->
                <div class="group-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="mb-0 fw-semibold">{{ $grup->nama_grup }}</h5>
                            <small>{{ $grup->mahasiswa->count() }} anggota</small>
                        </div>
                        <div class="d-flex">
                            <i class="fas fa-users header-icon" data-bs-toggle="modal" data-bs-target="#tambahAnggotaModal"></i>
                            <i class="fas fa-info-circle header-icon" data-bs-toggle="modal" data-bs-target="#infoGrupModal"></i>
                            <button type="button" class="bg-transparent border-0 p-0" id="deleteGrupBtn">
                                <i class="fas fa-trash header-icon danger"></i>
                            </button>
                        </div>
                    </div>
                </div>
                
                <!-- Chat Content -->
                <div class="message-container" id="messageContainer">
                    @if(isset($grupPesanByDate) && count($grupPesanByDate) > 0)
                        @foreach($grupPesanByDate as $date => $pesanList)
                            <div class="chat-date-divider">
                                <span data-date="{{ $date }}">{{ \Carbon\Carbon::parse($date)->locale('id')->isoFormat('dddd, D MMMM YYYY') }}</span>
                            </div>
                            
                           @foreach($pesanList as $pesan)
                                <div class="chat-message {{ $pesan->tipe_pengirim }}">
                                    <div class="message-bubble">
                                        @if($pesan->tipe_pengirim == 'mahasiswa')
                                            <div class="sender-name">{{ $pesan->pengirim->nama ?? 'Mahasiswa' }}</div>
                                        @endif
                                        <p>{{ $pesan->isi_pesan }}</p>
                                        <div class="message-time">
                                            {{ $pesan->created_at->format('H:i') }}
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @endforeach
                    @else
                        <div class="text-center py-5 text-muted">
                            <i class="fas fa-comments fa-3x mb-3"></i>
                            <p>Belum ada pesan di grup ini.</p>
                            <p>Mulai percakapan dengan mengirim pesan!</p>
                        </div>
                    @endif
                </div>
                
                <!-- Message Input -->
                <div class="message-input">
                    <form id="sendMessageForm" enctype="multipart/form-data">
                        @csrf
                        <div class="input-group">
                            <input type="text" class="form-control" id="messageInput" name="isi_pesan" placeholder="Tulis Pesan Anda disini.." autocomplete="off" style="border-radius: 25px 0 0 25px; padding: 12px 20px;">
                            <button type="submit" class="btn btn-gradient-primary" id="sendBtn" style="border-radius: 0 25px 25px 0; padding: 12px 20px;">
                                <i class="fas fa-paper-plane"></i>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Success Toast Notification -->
<div class="position-fixed top-0 end-0 p-3" style="z-index: 1100">
    <div id="successToast" class="toast border-0" role="alert" aria-live="assertive" aria-atomic="true" style="background: linear-gradient(135deg, #27AE60, #2ECC71); color: white;">
        <div class="d-flex align-items-center">
            <div class="toast-body flex-grow-1">
                <i class="fas fa-check-circle me-2"></i>
                <span id="toastMessage">Operasi berhasil dilakukan.</span>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
    </div>
</div>

<!-- Error Toast Notification -->
<div class="position-fixed top-0 end-0 p-3" style="z-index: 1100">
    <div id="errorToast" class="toast border-0" role="alert" aria-live="assertive" aria-atomic="true" style="background: linear-gradient(135deg, #FF5252, #FF1744); color: white;">
        <div class="d-flex align-items-center">
            <div class="toast-body flex-grow-1">
                <i class="fas fa-exclamation-circle me-2"></i>
                <span id="errorToastMessage">Terjadi kesalahan saat memproses permintaan.</span>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
    </div>
</div>

<!-- Warning Toast Notification -->
<div class="position-fixed top-0 end-0 p-3" style="z-index: 1100">
    <div id="warningToast" class="toast border-0" role="alert" aria-live="assertive" aria-atomic="true" style="background: linear-gradient(135deg, #ffc107, #ffca2c); color: white;">
        <div class="d-flex align-items-center">
            <div class="toast-body flex-grow-1">
                <i class="fas fa-exclamation-triangle me-2"></i>
                <span id="warningToastMessage">Peringatan.</span>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
    </div>
</div>

<!-- Success Modal -->
<div class="modal fade modal-success" id="successModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-check-circle me-2"></i>Operasi Berhasil
                </h5>
            </div>
            <div class="modal-body">
                <div class="success-icon">
                    <i class="fas fa-check"></i>
                </div>
                <div class="success-title" id="successTitle">Berhasil!</div>
                <div class="success-message" id="successMessage">
                    Operasi telah berhasil dilakukan.
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-gradient-success" data-bs-dismiss="modal" id="successOkBtn">
                    <i class="fas fa-check me-2"></i>OK
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Info Grup Modal -->
<div class="modal fade" id="infoGrupModal" tabindex="-1" aria-labelledby="infoGrupModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="infoGrupModalLabel">
                    <i class="fas fa-info-circle me-2"></i>Informasi Grup
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="info-item">
                    <label><i class="fas fa-tag"></i>Nama Grup:</label>
                    <p>{{ $grup->nama_grup }}</p>
                </div>
                <div class="info-item">
                    <label><i class="fas fa-calendar-alt"></i>Dibuat pada:</label>
                    <p>{{ $grup->created_at->format('d M Y, H:i') }}</p>
                </div>
                <div class="info-item">
                    <label><i class="fas fa-users"></i>Jumlah Anggota:</label>
                    <p>{{ $grup->mahasiswa->count() }} mahasiswa</p>
                </div>
                <div class="info-item">
                    <label><i class="fas fa-user-tie"></i>Dibuat oleh:</label>
                    <p>{{ Auth::user()->nama }}</p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-2"></i>Tutup
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Tambah Anggota Modal -->
<div class="modal fade" id="tambahAnggotaModal" tabindex="-1" aria-labelledby="tambahAnggotaModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="tambahAnggotaModalLabel">
                    <i class="fas fa-users me-2"></i>Anggota Grup
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <ul class="nav nav-tabs mb-3" id="anggotaTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="daftar-tab" data-bs-toggle="tab" data-bs-target="#daftar-tab-pane" type="button" role="tab" aria-controls="daftar-tab-pane" aria-selected="true">
                            <i class="fas fa-list me-2"></i>Daftar Anggota
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="tambah-tab" data-bs-toggle="tab" data-bs-target="#tambah-tab-pane" type="button" role="tab" aria-controls="tambah-tab-pane" aria-selected="false">
                            <i class="fas fa-user-plus me-2"></i>Tambah Anggota
                        </button>
                    </li>
                </ul>
                
                <div class="tab-content" id="anggotaTabContent">
                    <!-- Tab Daftar Anggota -->
                    <div class="tab-pane fade show active" id="daftar-tab-pane" role="tabpanel" aria-labelledby="daftar-tab" tabindex="0">
                        <div class="table-responsive">
                            <table class="table table-hover" id="memberTable">
                                <thead>
                                    <tr>
                                        <th width="5%">No.</th>
                                        <th width="15%">Foto</th>
                                        <th width="40%">Nama</th>
                                        <th width="25%">NIM</th>
                                        <th width="15%">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($grup->mahasiswa as $index => $anggota)
                                    <tr data-member-nim="{{ $anggota->nim }}">
                                        <td>{{ $index + 1 }}</td>
                                        <td>
                                            @if($anggota->profile_photo && file_exists(public_path('storage/profile_photos/' . $anggota->profile_photo)))
                                                <img src="{{ asset('storage/profile_photos/' . $anggota->profile_photo) }}" 
                                                     alt="Foto {{ $anggota->nama }}" 
                                                     class="profile-image-real">
                                            @else
                                                <div class="profile-image-placeholder">
                                                    <i class="fas fa-user"></i>
                                                </div>
                                            @endif
                                        </td>
                                        <td>{{ $anggota->nama }}</td>
                                        <td>{{ $anggota->nim }}</td>
                                        <td>
                                            <button type="button" class="btn btn-outline-secondary btn-sm delete-member-btn" 
                                                    data-grup-id="{{ $grup->id }}" 
                                                    data-member-nim="{{ $anggota->nim }}" 
                                                    data-member-name="{{ $anggota->nama }}"
                                                    style="transition: all 0.3s ease;">
                                                <i class="fas fa-trash me-1"></i> Hapus
                                            </button>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    
                    <!-- Tab Tambah Anggota -->
                    <div class="tab-pane fade" id="tambah-tab-pane" role="tabpanel" aria-labelledby="tambah-tab" tabindex="0">
                        <form action="{{ route('dosen.grup.addMember', $grup->id) }}" method="POST" id="tambahAnggotaForm">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label">
                                    <i class="fas fa-search me-2"></i>Cari Mahasiswa
                                </label>
                                <input type="text" class="form-control" id="searchMahasiswa" placeholder="Cari berdasarkan nama atau NIM...">
                            </div>
                            <div class="mb-3">
                                <div class="card">
                                    <div class="card-body" style="max-height: 300px; overflow-y: auto;">
                                        @php
                                            $allMahasiswa = App\Models\Mahasiswa::whereNotIn('nim', $grup->mahasiswa->pluck('nim')->toArray())->get();
                                        @endphp
                                        
                                        @if($allMahasiswa->count() > 0)
                                            <div class="row">
                                                @foreach($allMahasiswa as $mhs)
                                                <div class="col-md-6 mb-2 searchable-item">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" name="new_members[]" value="{{ $mhs->nim }}" id="mhs{{ $mhs->nim }}">
                                                        <label class="form-check-label" for="mhs{{ $mhs->nim }}">
                                                            <i class="fas fa-user me-2"></i>{{ $mhs->nama }} - {{ $mhs->nim }}
                                                        </label>
                                                    </div>
                                                </div>
                                                @endforeach
                                            </div>
                                        @else
                                            <div class="custom-alert-info alert">
                                                <i class="fas fa-info-circle me-2"></i>
                                                Semua mahasiswa sudah ada di dalam grup ini.
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="d-grid">
                                <button type="submit" class="btn btn-gradient-success" id="btnTambahAnggota">
                                    <i class="fas fa-user-plus me-2"></i>Tambahkan Anggota
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Konfirmasi Hapus Grup -->
<div class="modal fade modal-confirm" id="confirmDeleteModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-exclamation-triangle me-2"></i>Konfirmasi Hapus Grup
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="confirm-icon">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
                <div class="confirm-title">Apakah Anda yakin?</div>
                <div class="confirm-message">
                    Grup "<strong>{{ $grup->nama_grup }}</strong>" akan dihapus secara permanen beserta seluruh pesan di dalamnya. Tindakan ini tidak dapat dibatalkan.
                </div>
            </div>
            <div class="modal-footer">
                <div class="btn-group-mobile">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-2"></i>Batal
                    </button>
                    <button type="button" class="btn btn-gradient-primary" id="confirmDeleteBtn">
                        <i class="fas fa-trash me-2"></i>Hapus Grup
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Konfirmasi Hapus Anggota -->
<div class="modal fade modal-confirm" id="confirmDeleteMemberModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-user-minus me-2"></i>Konfirmasi Hapus Anggota
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="confirm-icon">
                    <i class="fas fa-user-times"></i>
                </div>
                <div class="confirm-title">Hapus Anggota Grup?</div>
                <div class="confirm-message">
                    Anggota "<strong id="memberNameToDelete"></strong>" akan dihapus dari grup ini. Tindakan ini tidak dapat dibatalkan.
                </div>
            </div>
            <div class="modal-footer">
                <div class="btn-group-mobile">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-2"></i>Batal
                    </button>
                    <button type="button" class="btn btn-gradient-primary" id="confirmDeleteMemberBtn">
                        <i class="fas fa-user-times me-2"></i>Hapus Anggota
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Loading -->
<div class="modal fade modal-loading" id="loadingModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content">
            <div class="loading-container">
                <div class="loading-spinner"></div>
                <div class="loading-text" id="loadingText">Memproses...</div>
            </div>
        </div>
    </div>
</div>

<!-- Form tersembunyi untuk hapus grup -->
<form id="deleteGrupForm" action="{{ route('dosen.grup.destroy', $grup->id) }}" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>

<!-- Form tersembunyi untuk hapus anggota -->
<form id="deleteMemberForm" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Inisialisasi modal dan toast
    const confirmDeleteModal = new bootstrap.Modal(document.getElementById('confirmDeleteModal'));
    const confirmDeleteMemberModal = new bootstrap.Modal(document.getElementById('confirmDeleteMemberModal'));
    const loadingModal = new bootstrap.Modal(document.getElementById('loadingModal'));
    const tambahAnggotaModal = new bootstrap.Modal(document.getElementById('tambahAnggotaModal'));
    const successModal = new bootstrap.Modal(document.getElementById('successModal'));
    
    // Toast elements - Updated untuk menggunakan Bootstrap toast yang sama seperti isi pesan
    const successToast = document.getElementById('successToast');
    const errorToast = document.getElementById('errorToast');
    const warningToast = document.getElementById('warningToast');
    const toastMessage = document.getElementById('toastMessage');
    const errorToastMessage = document.getElementById('errorToastMessage');
    const warningToastMessage = document.getElementById('warningToastMessage');
    
    // Success modal elements
    const successTitle = document.getElementById('successTitle');
    const successMessage = document.getElementById('successMessage');
    
    // Variables untuk hapus anggota
    let currentGrupId = null;
    let currentMemberNim = null;
    let currentMemberName = null;
    
    // Function untuk menampilkan toast notification (sama seperti di isi pesan)
    function showNotification(message, type = 'success') {
        let toastElement, messageElement;
        
        // Tentukan toast yang akan digunakan berdasarkan type
        if (type === 'success') {
            toastElement = successToast;
            messageElement = toastMessage;
        } else if (type === 'warning') {
            toastElement = warningToast;
            messageElement = warningToastMessage;
        } else if (type === 'danger' || type === 'error') {
            toastElement = errorToast;
            messageElement = errorToastMessage;
        } else {
            toastElement = successToast;
            messageElement = toastMessage;
        }
        
        // Set pesan
        messageElement.textContent = message;
        
        // Tampilkan toast menggunakan Bootstrap
        const toast = new bootstrap.Toast(toastElement, {
            delay: 3000
        });
        toast.show();
    }
    
    // Function untuk menampilkan success modal (tetap digunakan untuk operasi penting)
    function showSuccessModal(title, message, callback = null) {
        successTitle.textContent = title;
        successMessage.textContent = message;
        
        successModal.show();
        
        // Handle callback ketika modal ditutup
        if (callback) {
            const successOkBtn = document.getElementById('successOkBtn');
            const handleCallback = () => {
                callback();
                successOkBtn.removeEventListener('click', handleCallback);
                successModal._element.removeEventListener('hidden.bs.modal', handleCallback);
            };
            
            successOkBtn.addEventListener('click', handleCallback);
            successModal._element.addEventListener('hidden.bs.modal', handleCallback);
        }
    }
    
    // Cek session flash messages saat halaman dimuat
    @if(session('success'))
        @if(session('operation_type') === 'add_member')
            showSuccessModal('Anggota Berhasil Ditambahkan!', '{{ session('success') }}');
        @elseif(session('operation_type') === 'delete_member')
            showSuccessModal('Anggota Berhasil Dihapus!', '{{ session('success') }}');
        @elseif(session('operation_type') === 'delete_group')
            showNotification('{{ session('success') }}', 'success');
        @else
            showNotification('{{ session('success') }}', 'success');
        @endif
    @endif
    
    @if(session('error'))
        showNotification('{{ session('error') }}', 'danger');
    @endif
    
    // Kode dropdown grup
    const grupDropdownToggle = document.getElementById('grupDropdownToggle');
    const komunikasiSubmenu = document.getElementById('komunikasiSubmenu');
    const grupDropdownIcon = document.getElementById('grupDropdownIcon');
    
    grupDropdownToggle.addEventListener('click', function() {
        const bsCollapse = new bootstrap.Collapse(komunikasiSubmenu, {
            toggle: true
        });
        
        grupDropdownIcon.classList.toggle('fa-chevron-up');
        grupDropdownIcon.classList.toggle('fa-chevron-down');
    });
    
    // Kode pencarian anggota
    const searchInput = document.getElementById('searchMahasiswa');
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            const items = document.querySelectorAll('.searchable-item');
            
            items.forEach(item => {
                const text = item.textContent.toLowerCase();
                if (text.includes(searchTerm)) {
                    item.style.display = 'block';
                } else {
                    item.style.display = 'none';
                }
            });
        });
    }
    
    // Auto scroll ke chat terbaru
    const messageContainer = document.getElementById('messageContainer');
    if (messageContainer) {
        messageContainer.scrollTop = messageContainer.scrollHeight;
    }
    
    // Kirim pesan
    const sendMessageForm = document.getElementById('sendMessageForm');
    const messageInput = document.getElementById('messageInput');
    
    if (sendMessageForm) {
        sendMessageForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const message = messageInput.value.trim();
            if (!message) return;
            
            // Tampilkan loading sementara
            const sendBtn = document.getElementById('sendBtn');
            const originalText = sendBtn.innerHTML;
            sendBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
            sendBtn.disabled = true;
            
            // Kirim pesan ke server
            fetch('{{ route("dosen.grup.sendMessage", $grup->id) }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ isi_pesan: message })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Buat pesan baru
                    const today = new Date();
                    const dateStr = today.toISOString().split('T')[0];
                    
                    // Cek apakah sudah ada divider untuk hari ini
                    let dateDivider = document.querySelector(`.chat-date-divider span[data-date="${dateStr}"]`);
                    if (!dateDivider) {
                        // Buat date divider baru jika belum ada
                        const dividerDiv = document.createElement('div');
                        dividerDiv.className = 'chat-date-divider';
                        
                        const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
                        const dateDisplay = today.toLocaleDateString('id-ID', options);
                        
                        dividerDiv.innerHTML = `<span data-date="${dateStr}">${dateDisplay}</span>`;
                        messageContainer.appendChild(dividerDiv);
                    }
                    
                    // Tambah pesan baru
                    const msgDiv = document.createElement('div');
                    msgDiv.className = 'chat-message dosen';
                    msgDiv.innerHTML = `
                        <div class="message-bubble">
                            <p>${data.data.isi_pesan}</p>
                            <div class="message-time">
                                ${data.data.created_at}
                            </div>
                        </div>
                    `;
                    
                    messageContainer.appendChild(msgDiv);
                    
                    // Clear input
                    messageInput.value = '';
                    
                    // Scroll ke pesan terbaru
                    messageContainer.scrollTop = messageContainer.scrollHeight;
                    
                    // Jika container pesan kosong, hapus pesan "belum ada pesan"
                    const emptyMessage = messageContainer.querySelector('.text-center.py-5');
                    if (emptyMessage) {
                        emptyMessage.remove();
                    }
                    
                    // Tampilkan notifikasi sukses
                    showNotification('Pesan berhasil dikirim!', 'success');
                } else {
                    showNotification('Gagal mengirim pesan: ' + data.message, 'danger');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('Terjadi kesalahan saat mengirim pesan', 'danger');
            })
            .finally(() => {
                // Kembalikan tombol ke state normal
                sendBtn.innerHTML = originalText;
                sendBtn.disabled = false;
            });
        });
    }
    
    // Handler untuk hapus grup dengan konfirmasi dan loading
    const deleteGrupBtn = document.getElementById('deleteGrupBtn');
    const confirmDeleteBtn = document.getElementById('confirmDeleteBtn');
    const deleteGrupForm = document.getElementById('deleteGrupForm');
    const loadingText = document.getElementById('loadingText');
    
    if (deleteGrupBtn) {
        deleteGrupBtn.addEventListener('click', function() {
            confirmDeleteModal.show();
        });
    }
    
    if (confirmDeleteBtn) {
        confirmDeleteBtn.addEventListener('click', function() {
            // Tutup modal konfirmasi
            confirmDeleteModal.hide();
            
            // Tampilkan loading modal
            loadingText.textContent = 'Menghapus grup...';
            loadingModal.show();
            
            // Submit form setelah delay untuk efek loading
            setTimeout(() => {
                deleteGrupForm.submit();
            }, 1500);
        });
    }
    
    // Handler untuk hapus anggota dengan konfirmasi dan loading
    const deleteMemberBtns = document.querySelectorAll('.delete-member-btn');
    const confirmDeleteMemberBtn = document.getElementById('confirmDeleteMemberBtn');
    const deleteMemberForm = document.getElementById('deleteMemberForm');
    const memberNameToDelete = document.getElementById('memberNameToDelete');
    
    deleteMemberBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            currentGrupId = this.getAttribute('data-grup-id');
            currentMemberNim = this.getAttribute('data-member-nim');
            currentMemberName = this.getAttribute('data-member-name');
            
            // Set nama anggota di modal
            memberNameToDelete.textContent = currentMemberName;
            
            // Tampilkan modal konfirmasi
            confirmDeleteMemberModal.show();
        });
    });
    
    if (confirmDeleteMemberBtn) {
        confirmDeleteMemberBtn.addEventListener('click', function() {
            // Tutup modal konfirmasi
            confirmDeleteMemberModal.hide();
            
            // Set action untuk form hapus anggota
            deleteMemberForm.action = `/grupanggota/hapus/${currentGrupId}/${currentMemberNim}`;
            
            // Tampilkan loading modal
            loadingText.textContent = 'Menghapus anggota...';
            loadingModal.show();
            
            // Submit form setelah delay untuk efek loading
            setTimeout(() => {
                deleteMemberForm.submit();
            }, 1500);
        });
    }
    
    // Handler untuk tambah anggota dengan loading
    const tambahAnggotaForm = document.getElementById('tambahAnggotaForm');
    const btnTambahAnggota = document.getElementById('btnTambahAnggota');
    
    if (tambahAnggotaForm) {
        tambahAnggotaForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Cek apakah ada anggota yang dipilih
            const checkedBoxes = this.querySelectorAll('input[type="checkbox"]:checked');
            if (checkedBoxes.length === 0) {
                showNotification('Pilih minimal satu mahasiswa untuk ditambahkan ke grup', 'warning');
                return;
            }
            
            // Disable tombol untuk mencegah double submit
            btnTambahAnggota.disabled = true;
            btnTambahAnggota.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Menambahkan...';
            
            // Tutup modal tambah anggota
            tambahAnggotaModal.hide();
            
            // Tampilkan loading modal
            loadingText.textContent = 'Menambahkan anggota...';
            loadingModal.show();
            
            // Submit form setelah delay untuk efek loading
            setTimeout(() => {
                this.submit();
            }, 1500);
        });
    }
    
    // Prevent text styling di input message
    if (messageInput) {
        // Disable browser autocomplete yang mungkin menyebabkan styling aneh
        messageInput.setAttribute('autocomplete', 'off');
        messageInput.setAttribute('autocorrect', 'off');
        messageInput.setAttribute('autocapitalize', 'off');
        messageInput.setAttribute('spellcheck', 'false');
        
        // Reset style saat focus
        messageInput.addEventListener('focus', function() {
            this.style.fontStyle = 'normal';
            this.style.textTransform = 'none';
        });
        
        // Prevent paste formatting
        messageInput.addEventListener('paste', function(e) {
            e.preventDefault();
            const text = (e.clipboardData || window.clipboardData).getData('text');
            const selection = window.getSelection();
            if (!selection.rangeCount) return;
            selection.deleteFromDocument();
            selection.getRangeAt(0).insertNode(document.createTextNode(text));
        });
    }
    
    // Enhancement: Smooth animations untuk loading modal
    document.getElementById('loadingModal').addEventListener('shown.bs.modal', function() {
        const spinner = this.querySelector('.loading-spinner');
        spinner.style.animation = 'spin 1s ease-in-out infinite';
    });
    
    // Enhancement: Auto focus pada search input ketika tab tambah anggota dibuka
    document.getElementById('tambah-tab').addEventListener('click', function() {
        setTimeout(() => {
            const searchInput = document.getElementById('searchMahasiswa');
            if (searchInput) {
                searchInput.focus();
            }
        }, 200);
    });
    
    // Enhancement: Keyboard shortcuts
    document.addEventListener('keydown', function(e) {
        // Esc untuk tutup modal yang terbuka
        if (e.key === 'Escape') {
            const openModals = document.querySelectorAll('.modal.show');
            openModals.forEach(modal => {
                if (!modal.classList.contains('modal-loading')) {
                    bootstrap.Modal.getInstance(modal)?.hide();
                }
            });
        }
        
        // Ctrl/Cmd + Enter untuk kirim pesan
        if ((e.ctrlKey || e.metaKey) && e.key === 'Enter' && messageInput === document.activeElement) {
            sendMessageForm.dispatchEvent(new Event('submit'));
        }
    });
    
    // Enhancement: Handle page visibility change untuk auto-refresh
    document.addEventListener('visibilitychange', function() {
        if (!document.hidden) {
            // Refresh grup member count ketika user kembali ke halaman
            // (berguna jika ada perubahan dari tab/window lain)
            // Optional: implementasikan polling untuk update real-time
        }
    });
});
</script>
@endpush