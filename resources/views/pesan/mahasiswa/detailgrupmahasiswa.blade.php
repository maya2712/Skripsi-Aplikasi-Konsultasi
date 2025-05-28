@extends('layouts.app')

@section('title', 'Detail Grup - ' . $grup->nama_grup)

@push('styles')
<style>
    :root {
        --bs-primary: #1a73e8;
        --bs-danger: #FF5252;
        --bs-success: #27AE60;
        --primary-gradient: linear-gradient(to right, #004AAD, #5DE0E6);
        --primary-hover: linear-gradient(to right, #003c8a, #4bc4c9);
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

    /* Mobile Navigation Bar */
    .mobile-navbar {
        display: none;
        background: var(--primary-gradient);
        color: white;
        padding: 12px 15px;
        position: sticky;
        top: 0;
        left: 0;
        right: 0;
        z-index: 1025;
        box-shadow: 0 2px 15px rgba(0,0,0,0.15);
        border-radius: 0;
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        margin-top: 0;
    }

    /* Enhanced mobile navbar with scroll effect */
    .mobile-navbar.scrolled {
        background: rgba(0, 74, 173, 0.95);
        backdrop-filter: blur(15px);
        -webkit-backdrop-filter: blur(15px);
        box-shadow: 0 4px 20px rgba(0,0,0,0.25);
        position: fixed;
        top: 0;
        z-index: 1030;
    }

    /* Add padding compensation when navbar becomes fixed */
    .mobile-navbar-compensation {
        height: 70px;
        display: none;
    }

    .mobile-navbar-compensation.active {
        display: block;
    }

    .mobile-navbar .navbar-content {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .mobile-navbar .group-info h6 {
        margin: 0;
        font-size: 1rem;
        font-weight: 600;
    }

    .mobile-navbar .group-info small {
        font-size: 0.75rem;
        opacity: 0.9;
    }

    .mobile-navbar .navbar-actions {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .burger-menu {
        background: none;
        border: none;
        color: white;
        font-size: 1.2rem;
        padding: 8px;
        border-radius: 5px;
        transition: all 0.3s ease;
        cursor: pointer;
    }

    .burger-menu:hover {
        background-color: rgba(255, 255, 255, 0.2);
        color: white;
    }

    .burger-menu:focus {
        outline: none;
        box-shadow: none;
    }

    .header-icon {
        color: white;
        font-size: 1.2rem;
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

    .mobile-sidebar-header {
        background: var(--primary-gradient);
        color: white;
        padding: 20px 15px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .mobile-sidebar-header h6 {
        margin: 0;
        font-weight: 600;
    }

    .close-sidebar {
        background: none;
        border: none;
        color: white;
        font-size: 1.5rem;
        padding: 5px;
        cursor: pointer;
        border-radius: 3px;
        transition: all 0.2s ease;
    }

    .close-sidebar:hover {
        background-color: rgba(255, 255, 255, 0.2);
    }

    .close-sidebar:focus {
        outline: none;
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
        display: flex;
        flex-direction: column;
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
        margin-top: 4px;
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
    
    /* Pesan dari mahasiswa yang sedang login (diri sendiri) */
    .chat-message.mahasiswa {
        margin-left: auto;
    }
    
    .chat-message.mahasiswa .message-bubble {
        background-color: #1a73e8;
        color: white;
        border-radius: 15px 15px 3px 15px;
        margin-left: auto;
        min-width: 120px;
        display: block;
    }
    
    .chat-message.mahasiswa p {
        margin-bottom: 8px;
        word-break: break-word;
    }
    
    /* Pesan dari mahasiswa lain */
    .chat-message.mahasiswa-lain .message-bubble {
        background-color: #585f67;
        color: white;
        border-radius: 15px 15px 15px 3px;
        min-width: 80px;
        display: block;
    }
    
    .chat-message.mahasiswa-lain p {
        margin-bottom: 8px;
        word-break: break-word;
    }
    
    /* Pesan dari dosen */
    .chat-message.dosen .message-bubble {
        background-color: #585f67;
        color: white;
        border-radius: 15px 15px 15px 3px;
        min-width: 80px;
        display: block;
    }
    
    .chat-message.dosen p {
        margin-bottom: 8px;
        word-break: break-word;
    }
    
    .sender-name {
        font-size: 14px;
        font-weight: 600;
        margin-bottom: 4px;
    }
    
    .chat-message.dosen .sender-name {
        color: #5DE0E6;
    }
    
    .chat-message.mahasiswa-lain .sender-name {
        color: #f8ac30;
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

    .modal-body {
        padding: 25px;
        background: #fafbfc;
    }

    .modal-footer {
        background: white;
        border-top: 1px solid rgba(0,0,0,0.05);
        padding: 20px 25px;
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

    /* Form Control Styles */
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

    /* Mobile Responsive Styles - ENHANCED */
    @media (max-width: 991.98px) {
        body {
            padding-top: 0; /* Remove fixed padding since navbar is now sticky */
        }
        
        .row.g-4 {
            --bs-gutter-x: 0;
        }
        
        .col-md-3 {
            display: none; /* Hide desktop sidebar on mobile */
        }
        
        .col-md-9 {
            padding-left: 0;
            padding-right: 0;
            flex: 0 0 100%;
            max-width: 100%;
        }
        
        .mobile-navbar {
            display: block; /* Show mobile navbar */
        }
        
        .group-header {
            display: none; /* Hide desktop group header */
        }
        
        .main-content {
            padding-top: 15px; /* Normal padding since navbar is sticky */
        }
        
        .message-container {
            border-radius: 8px;
            margin-bottom: 15px;
            max-height: calc(100vh - 200px);
        }
        
        .message-input {
            border-radius: 8px;
            margin-top: 10px;
        }
    }

    /* Tablet Responsive Styles */
    @media (max-width: 768px) {
        body {
            padding-top: 0; /* Remove fixed padding */
        }
        
        .custom-container {
            padding: 0 10px;
        }
        
        .main-content {
            padding-top: 10px; /* Normal padding */
            padding-bottom: 15px;
        }
        
        .mobile-navbar {
            padding: 10px 15px; /* Slightly smaller padding */
        }
        
        .mobile-navbar .navbar-content {
            flex-direction: row;
            align-items: center;
        }
        
        .mobile-navbar .group-info h6 {
            font-size: 0.95rem;
        }
        
        .mobile-navbar .group-info small {
            font-size: 0.7rem;
        }
        
        .message-container {
            max-height: calc(100vh - 180px);
            min-height: 300px;
            padding: 12px 15px;
        }
        
        .message-input {
            padding: 12px;
        }
        
        .chat-message {
            max-width: 90%;
            margin-bottom: 20px;
        }
        
        .message-bubble {
            padding: 12px 16px;
            max-width: 95%;
        }
        
        .profile-image-placeholder,
        .profile-image-real {
            width: 35px;
            height: 35px;
            font-size: 16px;
        }
    }

    /* Mobile Phone Responsive Styles */
    @media (max-width: 576px) {
        body {
            font-size: 12px;
            padding-top: 0; /* Remove fixed padding */
        }
        
        .custom-container {
            padding: 0 8px;
        }
        
        .main-content {
            padding-top: 8px; /* Normal top padding */
            padding-bottom: 10px;
        }
        
        .mobile-navbar {
            padding: 8px 12px; /* More compact */
        }
        
        .mobile-navbar .group-info h6 {
            font-size: 0.9rem;
            margin-bottom: 2px;
            line-height: 1.2;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
            max-width: 200px;
        }
        
        .mobile-navbar .group-info small {
            font-size: 0.65rem;
            opacity: 0.9;
        }
        
        .mobile-navbar .navbar-actions {
            gap: 5px;
        }
        
        .burger-menu {
            font-size: 1rem;
            padding: 6px;
        }
        
        .header-icon {
            margin-left: 3px;
            margin-right: 3px;
            padding: 4px;
            font-size: 0.9rem;
            width: 28px;
            height: 28px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .mobile-sidebar {
            width: 260px;
        }
        
        .mobile-sidebar-header {
            padding: 15px 12px;
        }
        
        .mobile-sidebar-header h6 {
            font-size: 0.95rem;
        }
        
        .message-container {
            max-height: calc(100vh - 160px);
            min-height: 250px;
            padding: 10px 12px;
            border-radius: 6px;
            margin-bottom: 12px;
        }
        
        .message-input {
            padding: 10px;
            border-radius: 6px;
            margin-top: 8px;
        }
        
        .chat-message {
            max-width: 95%;
            margin-bottom: 15px;
        }
        
        .message-bubble {
            padding: 10px 14px;
            border-radius: 12px;
            max-width: 100%;
            min-width: 60px;
        }
        
        .chat-message.mahasiswa .message-bubble {
            border-radius: 12px 12px 3px 12px;
            min-width: 80px;
        }
        
        .chat-message.mahasiswa-lain .message-bubble,
        .chat-message.dosen .message-bubble {
            border-radius: 12px 12px 12px 3px;
            min-width: 60px;
        }
        
        .sender-name {
            font-size: 12px;
            margin-bottom: 3px;
        }
        
        .message-bubble p {
            margin-bottom: 6px;
            font-size: 12px;
            line-height: 1.4;
        }
        
        .message-time {
            font-size: 10px;
            margin-top: 3px;
        }
        
        .chat-date-divider {
            margin: 15px 0;
        }
        
        .chat-date-divider span {
            padding: 0 10px;
            font-size: 10px;
        }
        
        .form-control {
            border-radius: 20px;
            padding: 10px 15px;
            font-size: 12px;
        }
        
        .input-group .form-control {
            border-radius: 20px 0 0 20px;
        }
        
        .input-group .btn {
            border-radius: 0 20px 20px 0;
            padding: 10px 15px;
            font-size: 12px;
        }
        
        .btn {
            padding: 8px 12px;
            font-size: 12px;
        }
        
        /* Modal Responsive untuk Mobile */
        .modal-dialog {
            margin: 8px;
            max-width: calc(100% - 16px);
        }
        
        .modal-content {
            border-radius: 8px;
        }
        
        .modal-header {
            padding: 15px 16px;
        }
        
        .modal-title {
            font-size: 1rem;
        }
        
        .modal-body {
            padding: 16px;
        }
        
        .modal-footer {
            padding: 12px 16px;
        }
        
        .info-item {
            padding: 12px;
            margin-bottom: 10px;
            border-radius: 6px;
        }
        
        .info-item label {
            font-size: 12px;
            margin-bottom: 6px;
        }
        
        .info-item p {
            font-size: 11px;
        }
        
        .table-responsive {
            font-size: 11px;
        }
        
        .table thead th,
        .table tbody td {
            padding: 8px 4px;
            font-size: 10px;
        }
        
        .profile-image-placeholder,
        .profile-image-real {
            width: 30px;
            height: 30px;
            font-size: 14px;
        }
        
        /* Scroll to bottom button untuk mobile */
        .scroll-to-bottom-mobile {
            bottom: 100px !important;
            right: 15px !important;
            width: 35px !important;
            height: 35px !important;
        }
        
        /* Empty message state untuk mobile */
        .text-center.py-5 {
            padding: 2rem 1rem !important;
        }
        
        .text-center.py-5 .fa-3x {
            font-size: 2rem !important;
            margin-bottom: 1rem !important;
        }
        
        .text-center.py-5 p {
            font-size: 12px;
            margin-bottom: 8px;
        }
    }

    /* Extra Small Mobile (iPhone SE, etc) */
    @media (max-width: 375px) {
        body {
            padding-top: 0; /* Remove fixed padding */
        }
        
        .custom-container {
            padding: 0 6px;
        }
        
        .message-container {
            max-height: calc(100vh - 140px);
            min-height: 220px;
        }
        
        .chat-message {
            max-width: 98%;
        }
        
        .mobile-navbar .group-info h6 {
            font-size: 0.85rem !important;
            max-width: 150px;
        }
        
        .mobile-navbar .group-info small {
            font-size: 0.6rem !important;
        }
        
        .mobile-sidebar {
            width: 240px;
        }
        
        .modal-dialog {
            margin: 5px;
            max-width: calc(100% - 10px);
        }
        
        .input-group {
            flex-wrap: nowrap;
        }
        
        .form-control {
            min-width: 0;
            flex: 1;
        }
    }

    /* Landscape orientation untuk mobile */
    @media (max-width: 768px) and (orientation: landscape) {
        body {
            padding-top: 0; /* Remove fixed padding for landscape */
        }
        
        .mobile-navbar {
            padding: 6px 12px; /* More compact in landscape */
        }
        
        .main-content {
            padding-top: 6px;
            padding-bottom: 8px;
        }
        
        .message-container {
            max-height: calc(100vh - 120px);
            min-height: 200px;
        }
        
        .message-input {
            padding: 8px;
        }
    }

    /* High DPI / Retina Display Adjustments */
    @media (-webkit-min-device-pixel-ratio: 2), (min-resolution: 192dpi) {
        .message-bubble {
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08);
        }
        
        .sidebar {
            box-shadow: 0 1px 5px rgba(0, 0, 0, 0.08);
        }
        
        .mobile-navbar {
            box-shadow: 0 1px 8px rgba(0, 0, 0, 0.12);
        }
    }

    /* Dark mode support (optional) */
    @media (prefers-color-scheme: dark) {
        .sidebar-overlay {
            background-color: rgba(0, 0, 0, 0.7);
        }
    }
</style>
@endpush

@section('content')
<!-- Mobile Navigation Bar - Fixed at top -->
<div class="mobile-navbar" id="mobileNavbar">
    <div class="navbar-content">
        <div class="group-info">
            <h6>{{ $grup->nama_grup }}</h6>
            <small>{{ $grup->mahasiswa->count() }} anggota</small>
        </div>
        <div class="navbar-actions">
            <i class="fas fa-users header-icon" data-bs-toggle="modal" data-bs-target="#anggotaGrupModal"></i>
            <i class="fas fa-info-circle header-icon" data-bs-toggle="modal" data-bs-target="#infoGrupModal"></i>
            <button class="burger-menu" id="mobileMenuToggle">
                <i class="fas fa-bars"></i>
            </button>
        </div>
    </div>
</div>

<!-- Mobile navbar compensation when it becomes fixed -->
<div class="mobile-navbar-compensation" id="mobileNavbarCompensation"></div>

<div class="main-content">
    <div class="custom-container">
        <!-- Mobile Sidebar Overlay -->
        <div class="sidebar-overlay" id="sidebarOverlay"></div>

        <!-- Mobile Sidebar -->
        <div class="mobile-sidebar" id="mobileSidebar">
            <div class="mobile-sidebar-header">
                <h6>Menu</h6>
                <button class="close-sidebar" id="closeSidebar">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="sidebar-buttons">
                <a href="{{ route('mahasiswa.pesan.create') }}" class="btn" style="background: var(--primary-gradient); color: white; padding: 10px 20px; border: none; border-radius: 5px;">
                    <i class="fas fa-plus me-2"></i> Pesan Baru
                </a>
            </div>
            <div class="sidebar-menu">
                <div class="nav flex-column">
                    <a href="{{ route('mahasiswa.dashboard.pesan') }}" class="nav-link">
                        <i class="fas fa-home me-2"></i>Daftar Pesan
                    </a>
                    <a href="#" class="nav-link menu-item active" id="mobileGrupDropdownToggle">
                        <div class="d-flex justify-content-between align-items-center">
                            <span><i class="fas fa-users me-2"></i>Daftar Grup</span>
                            <i class="fas fa-chevron-down" id="mobileGrupDropdownIcon"></i>
                        </div>
                    </a>
                    <div class="collapse show komunikasi-submenu" id="mobileKomunikasiSubmenu">
                        @php
                            $grups = Auth::user()->grups;
                        @endphp
                        
                        @if($grups && $grups->count() > 0)
                            @foreach($grups as $grupItem)
                            <a href="{{ route('mahasiswa.grup.show', $grupItem->id) }}" class="nav-link menu-item d-flex justify-content-between align-items-center {{ $grupItem->id == $grup->id ? 'active' : '' }}">
                                {{ $grupItem->nama_grup }}
                                @if(isset($grupItem->unreadMessages) && $grupItem->unreadMessages > 0)
                                <span class="badge bg-danger rounded-pill">{{ $grupItem->unreadMessages }}</span>
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

        <div class="row g-4">
            <!-- Desktop Sidebar -->
            <div class="col-md-3">
                <div class="sidebar">
                    <div class="sidebar-buttons">
                        <a href="{{ route('mahasiswa.pesan.create') }}" class="btn" style="background: var(--primary-gradient); color: white; padding: 10px 20px; border: none; border-radius: 5px;">
                            <i class="fas fa-plus me-2"></i> Pesan Baru
                        </a>
                    </div>                                                    
                    
                    <div class="sidebar-menu">
                        <div class="nav flex-column">
                            <a href="{{ route('mahasiswa.dashboard.pesan') }}" class="nav-link">
                                <i class="fas fa-home me-2"></i>Daftar Pesan
                            </a>
                            <a href="#" class="nav-link menu-item active" id="grupDropdownToggle">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span><i class="fas fa-users me-2"></i>Daftar Grup</span>
                                    <i class="fas fa-chevron-down" id="grupDropdownIcon"></i>
                                </div>
                            </a>
                            <div class="collapse show komunikasi-submenu" id="komunikasiSubmenu">
                                @php
                                    $grups = Auth::user()->grups;
                                @endphp
                                
                                @if($grups && $grups->count() > 0)
                                    @foreach($grups as $grupItem)
                                    <a href="{{ route('mahasiswa.grup.show', $grupItem->id) }}" class="nav-link menu-item d-flex justify-content-between align-items-center {{ $grupItem->id == $grup->id ? 'active' : '' }}">
                                        {{ $grupItem->nama_grup }}
                                        @if(isset($grupItem->unreadMessages) && $grupItem->unreadMessages > 0)
                                        <span class="badge bg-danger rounded-pill">{{ $grupItem->unreadMessages }}</span>
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
            </div>

            <!-- Main Content Area -->
            <div class="col-md-9">
                <!-- Desktop Group Header -->
                <div class="group-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="mb-0 fw-semibold">{{ $grup->nama_grup }}</h5>
                            <small>{{ $grup->mahasiswa->count() }} anggota</small>
                        </div>
                        <div class="d-flex">
                            <i class="fas fa-users header-icon" data-bs-toggle="modal" data-bs-target="#anggotaGrupModal"></i>
                            <i class="fas fa-info-circle header-icon" data-bs-toggle="modal" data-bs-target="#infoGrupModal"></i>
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
                                @if($pesan->tipe_pengirim == 'mahasiswa')
                                    @if($pesan->pengirim_id == Auth::user()->nim)
                                        <!-- Pesan dari mahasiswa yang sedang login (diri sendiri) -->
                                        <div class="chat-message mahasiswa">
                                            <div class="message-bubble">
                                                <p>{{ $pesan->isi_pesan }}</p>
                                                <div class="message-time">
                                                    {{ $pesan->created_at->format('H:i') }}
                                                </div>
                                            </div>
                                        </div>
                                    @else
                                        <!-- Pesan dari mahasiswa lain -->
                                        <div class="chat-message mahasiswa-lain">
                                            <div class="message-bubble">
                                                <div class="sender-name">{{ $pesan->pengirim->nama ?? 'Mahasiswa' }}</div>
                                                <p>{{ $pesan->isi_pesan }}</p>
                                                <div class="message-time">
                                                    {{ $pesan->created_at->format('H:i') }}
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                @else
                                    <!-- Pesan dari dosen -->
                                    <div class="chat-message dosen">
                                        <div class="message-bubble">
                                            <div class="sender-name">{{ $pesan->pengirim->nama ?? 'Dosen' }}</div>
                                            <p>{{ $pesan->isi_pesan }}</p>
                                            <div class="message-time">
                                                {{ $pesan->created_at->format('H:i') }}
                                            </div>
                                        </div>
                                    </div>
                                @endif
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
                    <p>{{ \App\Models\Dosen::find($grup->dosen_id)->nama }}</p>
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

<!-- Anggota Grup Modal -->
<div class="modal fade" id="anggotaGrupModal" tabindex="-1" aria-labelledby="anggotaGrupModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="anggotaGrupModalLabel">
                    <i class="fas fa-users me-2"></i>Anggota Grup
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th width="8%">No.</th>
                                <th width="15%">Foto</th>
                                <th width="50%">Nama</th>
                                <th width="27%">NIM</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($grup->mahasiswa as $index => $anggota)
                            <tr>
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
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
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
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Mobile sidebar functionality
    const mobileMenuToggle = document.getElementById('mobileMenuToggle');
    const mobileSidebar = document.getElementById('mobileSidebar');
    const sidebarOverlay = document.getElementById('sidebarOverlay');
    const closeSidebar = document.getElementById('closeSidebar');
    const mobileNavbar = document.getElementById('mobileNavbar');
    const mobileNavbarCompensation = document.getElementById('mobileNavbarCompensation');
    
    // Enhanced mobile navbar with scroll effect - same as dashboard
    function handleMobileNavbarScroll() {
        if (!mobileNavbar || window.innerWidth > 991) return;
        
        const scrolled = window.scrollY > 50;
        
        if (scrolled) {
            mobileNavbar.classList.add('scrolled');
            mobileNavbarCompensation.classList.add('active');
        } else {
            mobileNavbar.classList.remove('scrolled');
            mobileNavbarCompensation.classList.remove('active');
        }
    }
    
    // Add scroll listener for mobile navbar effect
    window.addEventListener('scroll', handleMobileNavbarScroll);
    
    // Performance optimization: Throttle scroll events
    let ticking = false;
    
    function updateScrollEffects() {
        handleMobileNavbarScroll();
        ticking = false;
    }
    
    window.addEventListener('scroll', function() {
        if (!ticking) {
            requestAnimationFrame(updateScrollEffects);
            ticking = true;
        }
    });
    
    // Initialize scroll effects on load
    handleMobileNavbarScroll();
    
    // Open mobile sidebar
    if (mobileMenuToggle) {
        mobileMenuToggle.addEventListener('click', function() {
            mobileSidebar.classList.add('show');
            sidebarOverlay.style.display = 'block';
            setTimeout(() => {
                sidebarOverlay.classList.add('show');
            }, 10);
            
            // Prevent body scroll when sidebar is open
            document.body.style.overflow = 'hidden';
        });
    }
    
    // Close mobile sidebar
    function closeMobileSidebar() {
        mobileSidebar.classList.remove('show');
        sidebarOverlay.classList.remove('show');
        setTimeout(() => {
            sidebarOverlay.style.display = 'none';
        }, 300);
        
        // Restore body scroll
        document.body.style.overflow = '';
    }
    
    if (closeSidebar) {
        closeSidebar.addEventListener('click', closeMobileSidebar);
    }
    
    if (sidebarOverlay) {
        sidebarOverlay.addEventListener('click', closeMobileSidebar);
    }
    
    // Close sidebar when clicking on a menu item (mobile)
    const mobileMenuItems = document.querySelectorAll('#mobileSidebar .nav-link[href]');
    mobileMenuItems.forEach(item => {
        item.addEventListener('click', function() {
            // Add small delay to allow navigation
            setTimeout(closeMobileSidebar, 100);
        });
    });
    
    // Mobile dropdown functionality
    const mobileGrupDropdownToggle = document.getElementById('mobileGrupDropdownToggle');
    const mobileKomunikasiSubmenu = document.getElementById('mobileKomunikasiSubmenu');
    const mobileGrupDropdownIcon = document.getElementById('mobileGrupDropdownIcon');
    
    if (mobileGrupDropdownToggle) {
        mobileGrupDropdownToggle.addEventListener('click', function(e) {
            e.preventDefault();
            
            // Toggle the collapse
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
    }
    
    // Desktop dropdown functionality
    const grupDropdownToggle = document.getElementById('grupDropdownToggle');
    const komunikasiSubmenu = document.getElementById('komunikasiSubmenu');
    const grupDropdownIcon = document.getElementById('grupDropdownIcon');
    
    if (grupDropdownToggle) {
        grupDropdownToggle.addEventListener('click', function() {
            // Toggle the collapse using Bootstrap
            const bsCollapse = new bootstrap.Collapse(komunikasiSubmenu, {
                toggle: true
            });
            
            // Toggle the icon
            grupDropdownIcon.classList.toggle('fa-chevron-up');
            grupDropdownIcon.classList.toggle('fa-chevron-down');
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
            fetch('{{ route("mahasiswa.grup.sendMessage", $grup->id) }}', {
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
                    
                    // Tambah pesan baru - selalu sebagai pesan dari diri sendiri (mahasiswa)
                    const msgDiv = document.createElement('div');
                    msgDiv.className = 'chat-message mahasiswa'; // Selalu mahasiswa untuk pengirim sendiri
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
                } else {
                    alert('Gagal mengirim pesan: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Terjadi kesalahan saat mengirim pesan');
            })
            .finally(() => {
                // Kembalikan tombol ke state normal
                sendBtn.innerHTML = originalText;
                sendBtn.disabled = false;
            });
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
    
    // Enhancement: Keyboard shortcuts
    document.addEventListener('keydown', function(e) {
        // Esc untuk tutup modal yang terbuka atau sidebar mobile
        if (e.key === 'Escape') {
            const openModals = document.querySelectorAll('.modal.show');
            openModals.forEach(modal => {
                bootstrap.Modal.getInstance(modal)?.hide();
            });
            
            // Close mobile sidebar if open
            if (mobileSidebar.classList.contains('show')) {
                closeMobileSidebar();
            }
        }
        
        // Ctrl/Cmd + Enter untuk kirim pesan
        if ((e.ctrlKey || e.metaKey) && e.key === 'Enter' && messageInput === document.activeElement) {
            sendMessageForm.dispatchEvent(new Event('submit'));
        }
    });
    
    // Enhancement: Auto focus pada message input saat halaman dimuat
    if (messageInput) {
        setTimeout(() => {
            messageInput.focus();
        }, 500);
    }
    
    // Enhancement: Handle enter key untuk submit pesan
    if (messageInput) {
        messageInput.addEventListener('keydown', function(e) {
            if (e.key === 'Enter' && !e.shiftKey) {
                e.preventDefault();
                sendMessageForm.dispatchEvent(new Event('submit'));
            }
        });
    }
    
    // Enhancement: Real-time typing indicator (bisa dikembangkan nanti)
    let typingTimer;
    const typingInterval = 1000; // 1 detik
    
    if (messageInput) {
        messageInput.addEventListener('input', function() {
            clearTimeout(typingTimer);
            
            // Set timer untuk berhenti mengetik
            typingTimer = setTimeout(() => {
                // Logika untuk menghentikan indikator typing
                // Bisa ditambahkan nanti jika diperlukan
            }, typingInterval);
        });
    }
    
    // Enhancement: Smooth scroll untuk pesan baru
    function smoothScrollToBottom() {
        messageContainer.scrollTo({
            top: messageContainer.scrollHeight,
            behavior: 'smooth'
        });
    }
    
    // Enhancement: Deteksi jika user scroll ke atas
    let isUserScrolling = false;
    let scrollTimeout;
    
    if (messageContainer) {
        messageContainer.addEventListener('scroll', function() {
            isUserScrolling = true;
            clearTimeout(scrollTimeout);
            
            scrollTimeout = setTimeout(() => {
                isUserScrolling = false;
            }, 1000);
        });
    }
    
    // Enhancement: Show/hide scroll to bottom button
    const scrollToBottomBtn = document.createElement('button');
    scrollToBottomBtn.innerHTML = '<i class="fas fa-chevron-down"></i>';
    scrollToBottomBtn.className = 'btn btn-primary btn-sm position-fixed';
    scrollToBottomBtn.style.cssText = `
        bottom: 120px;
        right: 30px;
        border-radius: 50%;
        width: 40px;
        height: 40px;
        display: none;
        z-index: 1000;
        box-shadow: 0 2px 10px rgba(0,0,0,0.2);
    `;
    
    document.body.appendChild(scrollToBottomBtn);
    
    scrollToBottomBtn.addEventListener('click', function() {
        smoothScrollToBottom();
        this.style.display = 'none';
    });
    
    if (messageContainer) {
        messageContainer.addEventListener('scroll', function() {
            const isAtBottom = this.scrollTop + this.clientHeight >= this.scrollHeight - 100;
            scrollToBottomBtn.style.display = isAtBottom ? 'none' : 'block';
        });
    }
    
    // Mobile responsiveness enhancements
    function handleMobileOptimizations() {
        const isMobile = window.innerWidth <= 768;
        
        if (isMobile) {
            // Add mobile-specific classes
            scrollToBottomBtn.classList.add('scroll-to-bottom-mobile');
            
            // Adjust message container height on mobile
            if (messageContainer) {
                const viewportHeight = window.innerHeight;
                const mobileNavbarElement = document.querySelector('.mobile-navbar');
                const inputHeight = document.querySelector('.message-input').offsetHeight;
                const mobileNavbarHeight = mobileNavbarElement ? mobileNavbarElement.offsetHeight : 0;
                
                // Calculate optimal height for mobile
                const availableHeight = viewportHeight - mobileNavbarHeight - inputHeight - 80; // 80px for margins
                messageContainer.style.maxHeight = Math.max(250, availableHeight) + 'px';
            }
            
            // Optimize touch interactions
            if (messageInput) {
                messageInput.addEventListener('touchstart', function() {
                    // Prevent zoom on iOS
                    const viewport = document.querySelector('meta[name="viewport"]');
                    if (viewport) {
                        viewport.setAttribute('content', 
                            'width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no');
                    }
                });
                
                messageInput.addEventListener('blur', function() {
                    // Re-enable zoom after input loses focus
                    const viewport = document.querySelector('meta[name="viewport"]');
                    if (viewport) {
                        viewport.setAttribute('content', 'width=device-width, initial-scale=1.0');
                    }
                });
            }
        }
    }
    
    // Run mobile optimizations on load and resize
    handleMobileOptimizations();
    window.addEventListener('resize', handleMobileOptimizations);
    
    // Handle orientation changes on mobile
    window.addEventListener('orientationchange', function() {
        setTimeout(handleMobileOptimizations, 500);
    });
    
    // Swipe gesture for mobile sidebar
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
        if (swipeDistance > swipeThreshold && touchStartX < 50 && !mobileSidebar.classList.contains('show')) {
            if (window.innerWidth <= 768) {
                mobileMenuToggle.click();
            }
        }
        
        // Swipe left to close sidebar (only if open)
        if (swipeDistance < -swipeThreshold && mobileSidebar.classList.contains('show')) {
            closeMobileSidebar();
        }
    }
    
    // Prevent sidebar from opening when swiping on message input
    if (messageInput) {
        messageInput.addEventListener('touchstart', function(e) {
            e.stopPropagation();
        });
        
        messageInput.addEventListener('touchend', function(e) {
            e.stopPropagation();
        });
    }
    
    // Prevent accidental sidebar opening when scrolling messages
    if (messageContainer) {
        messageContainer.addEventListener('touchstart', function(e) {
            // Only prevent if touch starts more than 20px from left edge
            if (e.touches[0].clientX > 20) {
                e.stopPropagation();
            }
        });
    }
    
    // Handle window resize to close mobile sidebar on desktop
    window.addEventListener('resize', function() {
        if (window.innerWidth > 768 && mobileSidebar.classList.contains('show')) {
            closeMobileSidebar();
        }
    });
    
    // Add haptic feedback for mobile interactions (if supported)
    function addHapticFeedback() {
        if ('vibrate' in navigator) {
            navigator.vibrate(50); // Short vibration
        }
    }
    
    // Add haptic feedback to button clicks on mobile
    const interactiveElements = [mobileMenuToggle, closeSidebar, ...mobileMenuItems];
    interactiveElements.forEach(element => {
        if (element) {
            element.addEventListener('touchstart', function() {
                if (window.innerWidth <= 768) {
                    addHapticFeedback();
                }
            });
        }
    });
    
    // Smooth animations for better mobile experience
    mobileSidebar.style.transition = 'left 0.3s cubic-bezier(0.4, 0.0, 0.2, 1)';
    sidebarOverlay.style.transition = 'opacity 0.3s ease';
    
    // Optimize scroll performance on mobile
    let tickingScroll = false;
    
    function updateScrollPosition() {
        // Update scroll-to-bottom button visibility
        if (messageContainer) {
            const isAtBottom = messageContainer.scrollTop + messageContainer.clientHeight >= messageContainer.scrollHeight - 100;
            scrollToBottomBtn.style.display = isAtBottom ? 'none' : 'block';
        }
        tickingScroll = false;
    }
    
    if (messageContainer) {
        messageContainer.addEventListener('scroll', function() {
            if (!tickingScroll) {
                requestAnimationFrame(updateScrollPosition);
                tickingScroll = true;
            }
        });
    }
    
    // Enhanced mobile keyboard handling
    function handleMobileKeyboard() {
        if (window.innerWidth <= 768) {
            const initialViewportHeight = window.innerHeight;
            
            window.addEventListener('resize', function() {
                const currentViewportHeight = window.innerHeight;
                const keyboardHeight = initialViewportHeight - currentViewportHeight;
                
                // Keyboard is likely open if viewport height decreased significantly
                if (keyboardHeight > 150) {
                    // Adjust message container height when keyboard is open
                    if (messageContainer) {
                        const mobileNavbarElement = document.querySelector('.mobile-navbar');
                        const inputHeight = document.querySelector('.message-input').offsetHeight;
                        const mobileNavbarHeight = mobileNavbarElement ? mobileNavbarElement.offsetHeight : 0;
                        
                        const availableHeight = currentViewportHeight - mobileNavbarHeight - inputHeight - 60;
                        messageContainer.style.maxHeight = Math.max(200, availableHeight) + 'px';
                        
                        // Scroll to bottom when keyboard opens
                        setTimeout(() => {
                            messageContainer.scrollTop = messageContainer.scrollHeight;
                        }, 100);
                    }
                } else {
                    // Keyboard closed, restore normal height
                    handleMobileOptimizations();
                }
            });
        }
    }
    
    handleMobileKeyboard();
    
    // Prevent double-tap zoom on mobile buttons
    const preventDoubleTapZoom = function(e) {
        e.preventDefault();
        this.click();
    };
    
    [mobileMenuToggle, closeSidebar].forEach(button => {
        if (button) {
            button.addEventListener('touchend', preventDoubleTapZoom);
        }
    });
    
    // Add loading states for better UX
    function showLoading(element, originalContent) {
        element.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
        element.disabled = true;
        return originalContent;
    }
    
    function hideLoading(element, originalContent) {
        element.innerHTML = originalContent;
        element.disabled = false;
    }
    
    // Enhanced error handling
    function showError(message, duration = 3000) {
        const errorDiv = document.createElement('div');
        errorDiv.className = 'alert alert-danger position-fixed';
        errorDiv.style.cssText = `
            top: 20px;
            right: 20px;
            z-index: 9999;
            max-width: 300px;
            animation: slideInRight 0.3s ease;
        `;
        errorDiv.innerHTML = `
            <i class="fas fa-exclamation-triangle me-2"></i>
            ${message}
            <button type="button" class="btn-close ms-2" onclick="this.parentElement.remove()"></button>
        `;
        
        document.body.appendChild(errorDiv);
        
        setTimeout(() => {
            if (errorDiv.parentElement) {
                errorDiv.style.animation = 'slideOutRight 0.3s ease';
                setTimeout(() => errorDiv.remove(), 300);
            }
        }, duration);
    }
    
    // Add CSS animations for notifications
    const style = document.createElement('style');
    style.textContent = `
        @keyframes slideInRight {
            from { transform: translateX(100%); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }
        @keyframes slideOutRight {
            from { transform: translateX(0); opacity: 1; }
            to { transform: translateX(100%); opacity: 0; }
        }
    `;
    document.head.appendChild(style);
    
    // Connection status indicator for better UX
    function updateConnectionStatus() {
        const isOnline = navigator.onLine;
        const statusIndicator = document.getElementById('connectionStatus') || createConnectionStatusIndicator();
        
        if (isOnline) {
            statusIndicator.style.display = 'none';
        } else {
            statusIndicator.style.display = 'block';
            statusIndicator.innerHTML = '<i class="fas fa-wifi"></i> Tidak ada koneksi internet';
        }
    }
    
    function createConnectionStatusIndicator() {
        const indicator = document.createElement('div');
        indicator.id = 'connectionStatus';
        indicator.className = 'alert alert-warning position-fixed';
        indicator.style.cssText = `
            top: 10px;
            left: 50%;
            transform: translateX(-50%);
            z-index: 9998;
            display: none;
            padding: 8px 16px;
            font-size: 12px;
            border-radius: 20px;
        `;
        document.body.appendChild(indicator);
        return indicator;
    }
    
    window.addEventListener('online', updateConnectionStatus);
    window.addEventListener('offline', updateConnectionStatus);
    
    // Initialize connection status
    updateConnectionStatus();
    
    // Performance optimization: Debounce resize events
    function debounce(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    }
    
    const debouncedHandleMobileOptimizations = debounce(handleMobileOptimizations, 250);
    window.addEventListener('resize', debouncedHandleMobileOptimizations);
    
    // Accessibility improvements
    function enhanceAccessibility() {
        // Add ARIA labels for better screen reader support
        if (mobileMenuToggle) {
            mobileMenuToggle.setAttribute('aria-label', 'Buka menu navigasi');
            mobileMenuToggle.setAttribute('aria-expanded', 'false');
        }
        
        if (closeSidebar) {
            closeSidebar.setAttribute('aria-label', 'Tutup menu navigasi');
        }
        
        if (mobileSidebar) {
            mobileSidebar.setAttribute('role', 'navigation');
            mobileSidebar.setAttribute('aria-label', 'Menu navigasi utama');
        }
        
        if (messageInput) {
            messageInput.setAttribute('aria-label', 'Ketik pesan Anda');
        }
        
        // Update ARIA states when sidebar opens/closes
        const originalCloseMobileSidebar = closeMobileSidebar;
        closeMobileSidebar = function() {
            originalCloseMobileSidebar();
            if (mobileMenuToggle) {
                mobileMenuToggle.setAttribute('aria-expanded', 'false');
            }
        };
        
        if (mobileMenuToggle) {
            mobileMenuToggle.addEventListener('click', function() {
                this.setAttribute('aria-expanded', 'true');
            });
        }
    }
    
    enhanceAccessibility();
    
    // Focus management for better keyboard navigation
    function manageFocus() {
        // When sidebar opens, focus on first menu item
        if (mobileMenuToggle) {
            mobileMenuToggle.addEventListener('click', function() {
                setTimeout(() => {
                    const firstMenuItem = mobileSidebar.querySelector('.nav-link');
                    if (firstMenuItem) {
                        firstMenuItem.focus();
                    }
                }, 350); // Wait for animation to complete
            });
        }
        
        // Trap focus within sidebar when open
        if (mobileSidebar) {
            mobileSidebar.addEventListener('keydown', function(e) {
                if (e.key === 'Tab') {
                    const focusableElements = mobileSidebar.querySelectorAll(
                        'button, [href], input, select, textarea, [tabindex]:not([tabindex="-1"])'
                    );
                    const firstElement = focusableElements[0];
                    const lastElement = focusableElements[focusableElements.length - 1];
                    
                    if (e.shiftKey && document.activeElement === firstElement) {
                        e.preventDefault();
                        lastElement.focus();
                    } else if (!e.shiftKey && document.activeElement === lastElement) {
                        e.preventDefault();
                        firstElement.focus();
                    }
                }
            });
        }
    }
    
    manageFocus();
    
    console.log('Mobile group chat initialized successfully');
});
</script>
@endpush