@extends('layouts.app')
@section('title', 'FAQ Dosen')
@push('styles')
    <style>
        :root {
            --bs-primary: #1a73e8;
            --bs-danger: #FF5252;
            --bs-success: #27AE60;
            --primary-gradient: linear-gradient(to right, #004AAD, #5DE0E6);
            --primary-hover: linear-gradient(to right, #003c8a, #4bc4c9);
            --text-color: #546E7A;
        }
        
        body {
            background-color: #F5F7FA;
            font-size: 13px;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            margin: 0;
            padding: 0;
        }

        .main-content {
            flex: 1;
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
        }

        .komunikasi-submenu .nav-link,
        .pengaturan-submenu .nav-link {
            padding: 8px 15px;
            font-size: 13px;
        }

        .custom-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 15px;
        }

        .search-container {
            width: 100%;
            margin: 0 auto 30px;
            position: relative;
        }

        .search-box {
            width: 100%;
            padding: 12px 20px;
            border: 1px solid #ddd;
            border-radius: 25px;
            font-size: 14px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
        }

        .category-pills {
            margin: 20px 0;
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }

        .category-pills .btn {
            border-radius: 20px;
            padding: 8px 20px;
            background-color: #e9ecef;
            border: none;
            color: #495057;
            white-space: nowrap;
        }

        .category-pills .btn.active {
            background-color: #0d6efd;
            color: white;
        }

        .faq-content {
            text-align: center;
            margin-bottom: 30px;
            background: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
        }

        .faq-item {
            background: white;
            border-radius: 10px;
            margin-bottom: 15px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
            overflow: hidden;
        }

        .faq-header {
            padding: 15px 20px;
            border-bottom: 1px solid #eee;
            display: flex;
            align-items: center;
            cursor: pointer;
        }

        .pin-icon {
            color: red;
            margin-right: 10px;
        }

        .faq-badge {
            background-color: #0d6efd;
            color: white;
            padding: 4px 12px;
            border-radius: 15px;
            font-size: 12px;
            margin-right: 10px;
        }

        .faq-title {
            margin: 0;
            font-size: 14px;
            font-weight: 500;
        }

        .faq-meta {
            color: #6c757d;
            font-size: 12px;
            margin-top: 5px;
        }

        /* Dropdown styles */
        .dosen-dropdown {
            position: relative;
            display: inline-block;
        }

        .dosen-dropdown .btn {
            border-radius: 20px;
            padding: 8px 20px;
            background-color: #e9ecef;
            border: none;
            color: #495057;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .dosen-dropdown .dropdown-menu {
            min-width: 200px;
            padding: 8px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .dosen-dropdown .dropdown-item {
            padding: 8px 15px;
            border-radius: 5px;
            font-size: 13px;
        }

        .dosen-dropdown .dropdown-item:hover {
            background-color: #f8f9fa;
        }
        
        /* Style tambahan untuk dropdown dosen aktif */
        .dosen-dropdown .btn.active-dosen {
            background-color: #E3F2FD;
            color: #1a73e8;
            border: 1px solid #1a73e8;
            font-weight: 500;
        }
        
        /* Fix untuk memastikan hover tidak mengganggu style aktif */
        .dosen-dropdown .btn.active-dosen:hover {
            background-color: #E3F2FD;
            color: #1a73e8;
        }
        
        /* Style untuk mode switcher */
        .mode-switcher-container {
            background: linear-gradient(to right, #0858b2, #53d1e0);
            color: white;
            padding: 15px 20px;
            border-radius: 10px;
            margin-bottom: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .mode-switcher-container.kaprodi {
            background: linear-gradient(to right, #53d1e0, #0858b2);
        }
        
        .mode-title {
            font-size: 16px;
            font-weight: 600;
            margin: 0;
        }
        
        .switch {
            position: relative;
            display: inline-block;
            width: 56px;
            height: 28px;
        }
        
        .switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }
        
        .slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #f0f2f0;
            transition: .4s;
            border-radius: 34px;
        }
        
        /* Style untuk mode Dosen (default) - bulat di kiri */
        .slider:before {
            position: absolute;
            content: "";
            height: 20px;
            width: 20px;
            left: 4px;
            bottom: 4px;
            background-color: #0858b2;
            transition: .4s;
            border-radius: 50%;
        }
        
        /* Style untuk mode Kaprodi - bulat di kanan (dengan !important) */
        .mode-switcher-container.kaprodi .slider:before {
            left: auto !important;
            right: 4px !important;
            background-color: #0cc0df !important;
        }
        
        /* Animasi loading spinner */
        .role-loading-spinner {
            width: 40px;
            height: 40px;
            margin: 0 auto 15px;
            border: 4px solid rgba(0, 0, 0, 0.1);
            border-left-color: var(--bs-primary);
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }
        
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        
        /* Modal Role Switcher */
        .role-modal-backdrop {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 1050;
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
        }
        
        .role-modal-backdrop.show {
            opacity: 1;
            visibility: visible;
        }
        
        .role-modal {
            background: white;
            border-radius: 10px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
            width: 90%;
            max-width: 400px;
            transform: scale(0.8);
            transition: transform 0.3s ease;
            overflow: hidden;
            text-align: center;
        }
        
        .role-modal-backdrop.show .role-modal {
            transform: scale(1);
        }

        /* LAMPIRAN SEDERHANA - DESAIN YANG LEBIH MENONJOL */
        .simple-attachment-link {
            display: inline-flex;
            align-items: center;
            color: #1a73e8 !important;
            text-decoration: none;
            padding: 12px 16px;
            background: white;
            border: 2px solid #e8f0fe;
            border-radius: 10px;
            font-size: 14px;
            font-weight: 500;
            transition: all 0.3s ease;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
            margin-top: 10px;
            min-width: 180px;
        }

        .simple-attachment-link:hover {
            background: #e8f0fe;
            color: #1557b0 !important;
            border-color: #1a73e8;
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(26, 115, 232, 0.2);
        }

        .simple-attachment-link i {
            color: #34a853;
            font-size: 18px;
            margin-right: 10px;
        }

        /* MODAL LOADING DAN KONFIRMASI SEPERTI DI BUAT PESAN MAHASISWA */
        /* Modal Loading */
        .modal-loading .modal-content {
            border: none;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }
        
        .modal-loading .loading-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 2rem;
        }
        
        .modal-loading .loading-spinner {
            display: inline-block;
            width: 3rem;
            height: 3rem;
            border: 3px solid rgba(0, 0, 0, 0.1);
            border-radius: 50%;
            border-top-color: #5DE0E6;
            border-left-color: #004AAD;
            animation: spin 1s ease-in-out infinite;
        }
        
        .modal-loading .loading-text {
            margin-top: 15px;
            font-size: 16px;
            font-weight: 500;
            background: var(--primary-gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        
        /* Modal Konfirmasi */
        .modal-confirm .modal-content {
            border: none;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        
        .modal-confirm .modal-header {
            background: var(--primary-gradient);
            color: white;
            border-bottom: none;
            border-top-left-radius: 10px;
            border-top-right-radius: 10px;
            padding: 1.5rem 1.5rem 0.5rem;
        }
        
        .modal-confirm .modal-header .modal-title {
            font-weight: 600;
        }
        
        .modal-confirm .modal-body {
            padding: 1.5rem;
            text-align: center;
        }
        
        .modal-confirm .confirm-icon {
            font-size: 3rem;
            margin-bottom: 1rem;
            background: var(--primary-gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        
        .modal-confirm .confirm-title {
            font-size: 1.3rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
            color: #333;
        }
        
        .modal-confirm .confirm-message {
            font-size: 1rem;
            color: #6c757d;
            margin-bottom: 1.5rem;
        }
        
        .modal-confirm .modal-footer {
            border-top: none;
            padding: 0 1.5rem 1.5rem;
            justify-content: center;
            gap: 15px;
        }
        
        .modal-confirm .btn-confirm {
            background: var(--primary-gradient);
            border: none;
            color: white;
            padding: 0.7rem 2rem;
            border-radius: 5px;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        
        .modal-confirm .btn-confirm:hover {
            opacity: 0.9;
            box-shadow: 0 2px 8px rgba(0, 74, 173, 0.3);
            color: white;
        }
        
        .modal-confirm .btn-cancel {
            background: #e9ecef;
            border: none;
            color: #495057;
            padding: 0.7rem 2rem;
            border-radius: 5px;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        
        .modal-confirm .btn-cancel:hover {
            background: #dee2e6;
            color: #495057;
        }

        /* Mobile Responsive Styles - ENHANCED SEPERTI MAHASISWA */
        @media (max-width: 991.98px) {
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
            
            .main-content {
                padding-top: 15px; /* Normal padding since navbar is sticky */
            }

            .mode-switcher-container {
                margin: 0 10px 15px;
                padding: 12px 15px;
                border-radius: 8px;
            }

            .mode-title {
                font-size: 14px;
            }

            .switch {
                width: 48px;
                height: 24px;
            }

            .slider:before {
                height: 16px;
                width: 16px;
                left: 3px;
                bottom: 4px;
            }

            .mode-switcher-container.kaprodi .slider:before {
                right: 3px !important;
            }
        }

        /* Tablet Responsive Styles */
        @media (max-width: 768px) {
            .custom-container {
                padding: 0 10px;
            }
            
            .main-content {
                padding-top: 10px; /* Normal padding */
                padding-bottom: 15px;
            }
            
            .bg-white {
                padding: 20px !important;
            }
            
            .category-pills {
                gap: 8px;
                margin: 15px 0;
            }
            
            .category-pills .btn {
                padding: 6px 15px;
                font-size: 13px;
            }
            
            .faq-header {
                padding: 12px 15px;
            }
            
            .faq-title {
                font-size: 13px;
            }
            
            .faq-meta {
                font-size: 11px;
            }
            
            .faq-badge {
                font-size: 11px;
                padding: 3px 10px;
            }
            
            .search-box {
                padding: 10px 15px;
                font-size: 13px;
            }
            
            .dosen-dropdown .btn {
                padding: 6px 15px;
                font-size: 13px;
            }
            
            .dosen-dropdown .dropdown-menu {
                min-width: 180px;
            }
            
            .simple-attachment-link {
                padding: 10px 14px;
                font-size: 13px;
                min-width: 160px;
            }
            
            .simple-attachment-link i {
                font-size: 16px;
                margin-right: 8px;
            }

            .mode-switcher-container {
                margin: 0 5px 15px;
                padding: 10px 15px;
            }
        }

        /* Mobile Phone Responsive Styles */
        @media (max-width: 576px) {
            body {
                font-size: 12px;
            }
            
            .custom-container {
                padding: 0 8px;
            }
            
            .main-content {
                padding-top: 8px; /* Normal top padding */
                padding-bottom: 10px;
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

            .mode-switcher-container {
                margin: 0 5px 12px;
                padding: 8px 12px;
                border-radius: 8px;
            }

            .mode-title {
                font-size: 13px;
            }

            .switch {
                width: 44px;
                height: 22px;
            }

            .slider:before {
                height: 14px;
                width: 14px;
                left: 3px;
                bottom: 4px;
            }

            .mode-switcher-container.kaprodi .slider:before {
                right: 3px !important;
            }
            
            .bg-white {
                padding: 15px !important;
                border-radius: 8px !important;
            }
            
            .bg-white h2 {
                font-size: 1.3rem;
                margin-bottom: 8px;
            }
            
            .bg-white p {
                font-size: 12px;
                margin-bottom: 15px;
            }
            
            .search-box {
                padding: 8px 35px 8px 12px;
                font-size: 12px;
                border-radius: 20px;
            }
            
            .position-relative .fas.fa-search {
                right: 12px !important;
                font-size: 12px;
            }
            
            .category-pills {
                gap: 6px;
                margin: 12px 0;
                justify-content: center;
            }
            
            .category-pills .btn {
                padding: 5px 12px;
                font-size: 11px;
                flex: 1;
                min-width: 0;
                text-align: center;
            }
            
            .dosen-dropdown {
                width: 100%;
                margin-top: 8px;
            }
            
            .dosen-dropdown .btn {
                width: 100%;
                padding: 5px 12px;
                font-size: 11px;
                justify-content: center;
            }
            
            .dosen-dropdown .dropdown-menu {
                width: 100%;
                min-width: auto;
                font-size: 11px;
            }
            
            /* FAQ Items - mempertahankan layout asli tapi dengan sizing yang responsive */
            .faq-item {
                margin-bottom: 12px;
                border-radius: 8px;
                box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            }
            
            /* FAQ Header - tetap menggunakan flex horizontal seperti aslinya */
            .faq-header {
                padding: 12px;
                display: flex;
                align-items: center;
                cursor: pointer;
                border-bottom: 1px solid #eee;
            }
            
            /* Pin Icon - tetap di posisi asli */
            .pin-icon {
                color: red;
                margin-right: 8px;
                font-size: 12px;
                flex-shrink: 0;
            }
            
            /* Flex-grow-1 - tetap mempertahankan struktur asli */
            .flex-grow-1 {
                flex: 1;
                min-width: 0;
            }
            
            /* FAQ Title - hanya adjust font size */
            .faq-title {
                margin: 0;
                font-size: 12px;
                font-weight: 500;
                margin-bottom: 5px;
                line-height: 1.3;
                word-wrap: break-word;
            }
            
            /* D-flex container - tetap horizontal tapi dengan wrap */
            .d-flex.align-items-center {
                display: flex;
                align-items: center;
                flex-wrap: wrap;
                gap: 8px;
            }
            
            /* FAQ Badge - ukuran yang lebih kecil */
            .faq-badge {
                background-color: #0d6efd;
                color: white;
                padding: 2px 8px;
                border-radius: 12px;
                font-size: 9px;
                margin-right: 8px;
                white-space: nowrap;
                flex-shrink: 0;
            }
            
            /* FAQ Meta - font size yang lebih kecil */
            .faq-meta {
                color: #6c757d;
                font-size: 10px;
                margin-top: 0;
                line-height: 1.2;
                word-wrap: break-word;
            }
            
            /* Chevron Icon - tetap di posisi asli (ms-3) */
            .chevron-icon {
                margin-left: 8px;
                font-size: 12px;
                color: #6c757d;
                transition: transform 0.3s ease;
                flex-shrink: 0;
            }
            
            /* Collapse Content */
            .collapse .p-3 {
                padding: 12px !important;
            }
            
            .collapse p {
                font-size: 12px;
                line-height: 1.4;
                margin-bottom: 8px;
                word-wrap: break-word;
            }
            
            /* Attachment Link - tetap inline-flex seperti asli */
            .simple-attachment-link {
                display: inline-flex;
                align-items: center;
                color: #1a73e8 !important;
                text-decoration: none;
                padding: 8px 12px;
                background: white;
                border: 2px solid #e8f0fe;
                border-radius: 8px;
                font-size: 11px;
                font-weight: 500;
                transition: all 0.3s ease;
                box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
                margin-top: 8px;
                min-width: 140px;
                word-wrap: break-word;
            }
            
            .simple-attachment-link i {
                color: #34a853;
                font-size: 14px;
                margin-right: 6px;
                flex-shrink: 0;
            }

            /* Batalkan Sematan Button */
            .batalkan-sematan {
                font-size: 10px !important;
                padding: 4px 8px !important;
                margin-top: 8px !important;
            }

            .batalkan-sematan i {
                font-size: 10px !important;
                margin-right: 4px !important;
            }
            
            /* No FAQ Found */
            #noFaqFound {
                padding: 15px 10px !important;
                border-radius: 8px;
            }
            
            #noFaqFound .fa-3x {
                font-size: 2rem !important;
                margin-bottom: 10px !important;
            }
            
            #noFaqFound h5 {
                font-size: 1rem;
                margin-bottom: 8px;
            }
            
            #noFaqFound p {
                font-size: 11px;
            }
        }

        /* Extra Small Mobile (iPhone SE, etc) */
        @media (max-width: 375px) {
            .custom-container {
                padding: 0 6px;
            }
            
            .mobile-sidebar {
                width: 240px;
            }

            .mode-switcher-container {
                margin: 0 3px 10px;
                padding: 6px 10px;
            }

            .mode-title {
                font-size: 12px;
            }

            .switch {
                width: 40px;
                height: 20px;
            }

            .slider:before {
                height: 12px;
                width: 12px;
                left: 3px;
                bottom: 4px;
            }

            .mode-switcher-container.kaprodi .slider:before {
                right: 3px !important;
            }
            
            .bg-white {
                padding: 12px !important;
            }
            
            .bg-white h2 {
                font-size: 1.2rem;
            }
            
            .category-pills .btn {
                padding: 4px 8px;
                font-size: 10px;
            }
            
            .search-box {
                padding: 6px 10px;
                font-size: 11px;
            }
            
            .faq-header {
                padding: 8px 10px;
            }
            
            .faq-title {
                font-size: 11px;
            }
            
            .simple-attachment-link {
                padding: 6px 10px;
                font-size: 10px;
                min-width: 120px;
            }

            .batalkan-sematan {
                font-size: 9px !important;
                padding: 3px 6px !important;
            }
        }

        /* High DPI / Retina Display Adjustments */
        @media (-webkit-min-device-pixel-ratio: 2), (min-resolution: 192dpi) {
            .faq-item {
                box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08);
            }
            
            .sidebar {
                box-shadow: 0 1px 5px rgba(0, 0, 0, 0.08);
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
<!-- Mobile buttons akan diintegrasikan ke navbar yang sudah ada di layout -->

<div class="main-content">
    <div class="custom-container">
        <!-- Mobile Sidebar Overlay -->
        <div class="sidebar-overlay" id="sidebarOverlay"></div>

        <!-- Mobile Sidebar -->
        <div class="mobile-sidebar" id="mobileSidebar">
            <div class="mobile-sidebar-header">
                <h6>Menu Navigasi</h6>
                <button class="close-sidebar" id="closeSidebar">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="sidebar-buttons">
                <a href="{{ route('dosen.pesan.create') }}" class="btn" style="background: var(--primary-gradient); color: white; padding: 10px 20px; border: none; border-radius: 5px;">
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
                        <a href="{{ route('dosen.grup.create') }}" class="nav-link menu-item d-flex align-items-center">
                            <i class="fas fa-plus me-2"></i>Grup Baru
                        </a>
                        
                        @php
                            $activeRole = session('active_role', 'dosen');
                            $grups = App\Models\Grup::where('dosen_id', Auth::user()->nip)
                                                    ->where('dosen_role', $activeRole)
                                                    ->get();
                        @endphp
                        
                        @if($grups && $grups->count() > 0)
                            @foreach($grups as $grup)
                            <a href="{{ route('dosen.grup.show', $grup->id) }}" class="nav-link menu-item d-flex justify-content-between align-items-center">
                                {{ $grup->nama_grup }}
                                @if($unreadCount = $grup->unreadMessages ?? 0)
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
                    <a href="{{ url('/faqdosen') }}" class="nav-link menu-item active">
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
                        <a href="{{ route('dosen.pesan.create') }}" class="btn" style="background: linear-gradient(to right, #004AAD, #5DE0E6); color: white; padding: 10px 20px; border: none; border-radius: 5px;">
                            <i class="fas fa-plus me-2"></i> Pesan Baru
                        </a>
                    </div>
                    
                    <div class="sidebar-menu">
                        <div class="nav flex-column">
                            <a href="{{ route('dosen.dashboard.pesan') }}" class="nav-link">
                                <i class="fas fa-home me-2"></i>Daftar Pesan
                            </a>

                            <a href="#" class="nav-link menu-item" id="grupDropdownToggle">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span><i class="fas fa-users me-2"></i>Daftar Grup</span>
                                    <i class="fas fa-chevron-down" id="grupDropdownIcon"></i>
                                </div>
                            </a>
                            <div class="collapse komunikasi-submenu" id="komunikasiSubmenu">
                                <a href="{{ route('dosen.grup.create') }}" class="nav-link menu-item d-flex align-items-center">
                                    <i class="fas fa-plus me-2"></i>Grup Baru
                                </a>
                                
                                @php
                                    $activeRole = session('active_role', 'dosen');
                                    $grups = App\Models\Grup::where('dosen_id', Auth::user()->nip)
                                                            ->where('dosen_role', $activeRole)
                                                            ->get();
                                @endphp
                                
                                @if($grups && $grups->count() > 0)
                                    @foreach($grups as $grup)
                                    <a href="{{ route('dosen.grup.show', $grup->id) }}" class="nav-link menu-item d-flex justify-content-between align-items-center">
                                        {{ $grup->nama_grup }}
                                        @if($unreadCount = $grup->unreadMessages ?? 0)
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
                            
                            <a href="{{ url('/faqdosen') }}" class="nav-link menu-item active">
                                <i class="fas fa-thumbtack me-2"></i>Pesan Tersematkan
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Content Area -->
            <div class="col-md-9">
                <!-- Mode Switcher - Ditambahkan di halaman FAQ -->
                @if(!empty(Auth::guard('dosen')->user()->jabatan_fungsional) && 
                    (stripos(Auth::guard('dosen')->user()->jabatan_fungsional, 'kaprodi') !== false || 
                     stripos(Auth::guard('dosen')->user()->jabatan_fungsional, 'ketua') !== false))
                <div class="mode-switcher-container {{ session('active_role') === 'kaprodi' ? 'kaprodi' : '' }}">
                    <h5 class="mode-title">
                        Mode {{ session('active_role') === 'kaprodi' ? 'Kaprodi' : 'Dosen' }}
                    </h5>
                    <form action="{{ route('dosen.switch-role') }}" method="POST" id="switchRoleForm">
                        @csrf
                        <label class="switch">
                            <input type="checkbox" id="roleSwitcher">
                            <span class="slider"></span>
                        </label>
                    </form>
                </div>
                @endif
                
                <div class="bg-white p-4 rounded-3 shadow-sm">
                    <h2 class="mb-1 text-center" style="font-weight: bold;">PESAN TERSEMATKAN</h2>
                    <p class="text-muted mb-4 text-center">Kumpulan pesan yang telah disematkan oleh Dosen</p>

                    <!-- Search Box -->
                    <div class="position-relative mb-4">
                        <input type="text" class="search-box" id="searchFaq" placeholder="Cari Pesan yang disematkan...">
                        <i class="fas fa-search position-absolute" style="right: 20px; top: 50%; transform: translateY(-50%);"></i>
                    </div>

                    <!-- Category Pills with Dosen Dropdown -->
                    <div class="category-pills">
                        <button class="btn active" data-category="all">Semua</button>
                        <button class="btn" data-category="krs">KRS</button>
                        <button class="btn" data-category="kp">KP</button>
                        <button class="btn" data-category="skripsi">Skripsi</button>
                        <button class="btn" data-category="mbkm">MBKM</button>
                        <div class="dosen-dropdown">
                            <button class="btn dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-user-tie"></i>
                                Dosen
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="#" data-dosen="Semua Dosen">Semua Dosen</a></li>
                                
                                @foreach($dosenList as $dosen)
                                    <li><a class="dropdown-item" href="#" data-dosen="{{ $dosen->nama }}">{{ $dosen->nama }}</a></li>
                                @endforeach
                            </ul>
                        </div>
                    </div>

                    <!-- FAQ Items untuk Dosen - UPDATE VERSION (Tanpa CSS Tambahan) -->
                    <div class="faq-list mt-4">
                        @forelse($sematan as $item)
                            <div class="faq-item" data-category="{{ $item->kategori }}" data-dosen="{{ $item->dosen->nama ?? 'Dosen' }}" style="background-color: #F5F7FA;">
                                <div class="faq-header" data-bs-toggle="collapse" data-bs-target="#faqItem{{ $item->id }}" aria-expanded="false">
                                    <i class="fas fa-thumbtack pin-icon"></i>
                                    <div class="flex-grow-1">
                                        <!-- JUDUL DIAMBIL DARI PERTANYAAN MAHASISWA -->
                                        <h5 class="faq-title mb-2">{{ $item->judul }}</h5>
                                        <div class="d-flex align-items-center flex-wrap">
                                            <span class="faq-badge me-3">
                                                @if($item->kategori == 'krs')
                                                    Bimbingan KRS
                                                @elseif($item->kategori == 'kp')
                                                    Bimbingan KP
                                                @elseif($item->kategori == 'skripsi')
                                                    Bimbingan Skripsi
                                                @elseif($item->kategori == 'mbkm')
                                                    Bimbingan MBKM
                                                @endif
                                            </span>
                                            <div class="faq-meta">
                                                Di-Pin oleh: {{ $item->dosen->nama ?? 'Dosen' }} - {{ $item->created_at->format('H:i, d F Y') }}
                                            </div>
                                        </div>
                                    </div>
                                    <i class="fas fa-chevron-down ms-3 chevron-icon"></i>
                                </div>
                                <div class="collapse" id="faqItem{{ $item->id }}">
                                    <div class="p-3">
                                        <!-- ISI SEMATAN (JAWABAN DOSEN SAJA) -->
                                        <p>{!! nl2br(e($item->isi_sematan)) !!}</p>
                                        
                                        {{-- LAMPIRAN JIKA ADA --}}
                                        @if($item->hasAttachment())
                                            <div class="mt-3">
                                                <a href="{{ $item->lampiran }}" target="_blank" class="simple-attachment-link">
                                                    <i class="fab fa-google-drive me-2"></i>
                                                    {{ $item->getAttachmentName() }}
                                                </a>
                                            </div>
                                        @endif
                                        
                                        <!-- TOMBOL BATALKAN SEMATAN UNTUK DOSEN (jika ada hak akses) -->
                                        @if(isset($item->can_cancel) && $item->can_cancel)
                                            <div class="mt-3 text-end">
                                                <button class="btn btn-sm btn-danger batalkan-sematan" data-id="{{ $item->id }}">
                                                    <i class="fas fa-times"></i> Batalkan Sematan
                                                </button>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center p-4">
                                <i class="fas fa-info-circle fa-2x mb-3 text-muted"></i>
                                <h5 class="text-muted">Belum Ada Pesan Tersematkan</h5>
                                <p class="text-muted">Pesan yang disematkan akan muncul di sini</p>
                            </div>
                        @endforelse

                        <!-- PESAN KETIKA TIDAK ADA HASIL PENCARIAN -->
                        <div id="noFaqFound" class="text-center p-4 mt-3" style="display: none; background-color: #F8F9FA; border-radius: 10px;">
                            <i class="fas fa-search fa-3x mb-3 text-muted"></i>
                            <h5>Tidak ada Pesan Sematan yang ditemukan</h5>
                            <p class="text-muted">Silakan coba kata kunci lain atau pilih kategori yang berbeda</p>
                        </div>
                    </div>
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
                <div class="loading-text">Memproses...</div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Konfirmasi -->
<div class="modal fade modal-confirm" id="confirmModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-exclamation-triangle me-2"></i>Konfirmasi
                </h5>
            </div>
            <div class="modal-body">
                <div class="confirm-icon">
                    <i class="fas fa-question-circle"></i>
                </div>
                <div class="confirm-title">Batalkan Sematan?</div>
                <div class="confirm-message">
                    Apakah Anda yakin ingin membatalkan sematan pesan ini? Pesan akan dihapus dari daftar Pesan Sematan.
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-cancel" id="cancelConfirm">
                    <i class="fas fa-times me-2"></i>Batal
                </button>
                <button type="button" class="btn btn-confirm" id="confirmDelete">
                    <i class="fas fa-check me-2"></i>Ya, Batalkan
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Role Switcher - Hanya menampilkan loading spinner -->
<div class="role-modal-backdrop" id="roleModalBackdrop">
    <div class="role-modal role-modal-success">
        <div class="role-modal-body py-4">
            <div class="role-loading-spinner" id="roleLoadingSpinner"></div>
            <p class="role-modal-message mt-3" id="roleModalMessage">Memuat...</p>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Inisialisasi modal
    const loadingModal = new bootstrap.Modal(document.getElementById('loadingModal'));
    const confirmModal = new bootstrap.Modal(document.getElementById('confirmModal'));
    
    // Mobile sidebar functionality
    const mobileMenuToggle = document.getElementById('mobileMenuToggle');
    const mobileSidebar = document.getElementById('mobileSidebar');
    const sidebarOverlay = document.getElementById('sidebarOverlay');
    const closeSidebar = document.getElementById('closeSidebar');
    const menuDots = document.getElementById('menuDots');
    
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
    
    // Event listener untuk mobile menu toggle
    if (mobileMenuToggle) {
        mobileMenuToggle.addEventListener('click', function(e) {
            e.stopPropagation();
            openMobileSidebar();
        });
    }
    
    // Event listener untuk menutup sidebar
    if (closeSidebar) {
        closeSidebar.addEventListener('click', function(e) {
            e.stopPropagation();
            closeMobileSidebar();
        });
    }
    
    if (sidebarOverlay) {
        sidebarOverlay.addEventListener('click', function(e) {
            e.stopPropagation();
            closeMobileSidebar();
        });
    }
    
    // Menu dots functionality (for account menu)
    if (menuDots) {
        menuDots.addEventListener('click', function() {
            console.log('Account menu clicked');
            // Add account menu functionality here
        });
    }
    
    // Close sidebar when clicking on a menu item (mobile) - PERBAIKAN
    const mobileMenuItems = document.querySelectorAll('#mobileSidebar .nav-link[href]');
    mobileMenuItems.forEach(item => {
        // Hanya tutup sidebar untuk menu yang benar-benar punya href dan bukan dropdown toggle
        if (!item.id.includes('Dropdown') && item.getAttribute('href') !== '#') {
            item.addEventListener('click', function() {
                // Add small delay to allow navigation
                setTimeout(closeMobileSidebar, 100);
            });
        }
    });
    
    // Mobile dropdown functionality - PERBAIKAN
    const mobileGrupDropdownToggle = document.getElementById('mobileGrupDropdownToggle');
    const mobileKomunikasiSubmenu = document.getElementById('mobileKomunikasiSubmenu');
    const mobileGrupDropdownIcon = document.getElementById('mobileGrupDropdownIcon');
    
    if (mobileGrupDropdownToggle) {
        mobileGrupDropdownToggle.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation(); // Mencegah event bubbling yang bisa menutup sidebar
            
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
        
        // Mencegah klik pada icon dropdown menutup sidebar
        mobileGrupDropdownIcon.addEventListener('click', function(e) {
            e.stopPropagation();
        });
    }
    
    // Desktop dropdown functionality
    const grupDropdownToggle = document.getElementById('grupDropdownToggle');
    const komunikasiSubmenu = document.getElementById('komunikasiSubmenu');
    const grupDropdownIcon = document.getElementById('grupDropdownIcon');
    
    // Membuat instance Bootstrap collapse untuk menu grup
    const bsCollapse = new bootstrap.Collapse(komunikasiSubmenu, {
        toggle: false
    });
    
    if (grupDropdownToggle) {
        grupDropdownToggle.addEventListener('click', function(e) {
            e.preventDefault();
            // Menggunakan metode toggle dari Bootstrap daripada manipulasi kelas manual
            bsCollapse.toggle();
        });
        
        // Mengubah ikon saat submenu dibuka
        komunikasiSubmenu.addEventListener('shown.bs.collapse', function() {
            grupDropdownIcon.classList.remove('fa-chevron-down');
            grupDropdownIcon.classList.add('fa-chevron-up');
        });
        
        // Mengubah ikon saat submenu ditutup
        komunikasiSubmenu.addEventListener('hidden.bs.collapse', function() {
            grupDropdownIcon.classList.remove('fa-chevron-up');
            grupDropdownIcon.classList.add('fa-chevron-down');
        });
    }
    
    // Setup event listeners untuk FAQ item collapse
    const faqCollapses = document.querySelectorAll('.collapse');
    faqCollapses.forEach(collapse => {
        // Menambahkan event listener untuk saat collapse dibuka
        collapse.addEventListener('show.bs.collapse', function() {
            // Mengambil ikon chevron dari header yang terkait
            const header = this.previousElementSibling;
            const chevronIcon = header.querySelector('.chevron-icon');
            if (chevronIcon) {
                chevronIcon.classList.remove('fa-chevron-down');
                chevronIcon.classList.add('fa-chevron-up');
            }
        });
        
        // Menambahkan event listener untuk saat collapse ditutup
        collapse.addEventListener('hide.bs.collapse', function() {
            // Mengambil ikon chevron dari header yang terkait
            const header = this.previousElementSibling;
            const chevronIcon = header.querySelector('.chevron-icon');
            if (chevronIcon) {
                chevronIcon.classList.remove('fa-chevron-up');
                chevronIcon.classList.add('fa-chevron-down');
            }
        });
    });
    
    // Filter FAQ berdasarkan kategori dan dosen
    const faqItems = document.querySelectorAll('.faq-item');
    const categoryButtons = document.querySelectorAll('.category-pills .btn:not(.dropdown-toggle)');
    const dosenLinks = document.querySelectorAll('.dosen-dropdown .dropdown-item');
    const searchInput = document.getElementById('searchFaq');
    const noFaqFound = document.getElementById('noFaqFound');
    
    let activeCategory = 'all';
    let activeDosen = null;
    
    // Fungsi untuk menerapkan filter
    function applyFilters() {
        let visibleCount = 0;
        
        faqItems.forEach(item => {
            const itemCategory = item.getAttribute('data-category');
            const itemDosen = item.getAttribute('data-dosen');
            const itemTitle = item.querySelector('.faq-title').textContent.toLowerCase();
            const searchTerm = searchInput.value.toLowerCase();
            
            // Check if matches all active filters
            const matchesCategory = activeCategory === 'all' || itemCategory === activeCategory;
            const matchesDosen = !activeDosen || itemDosen === activeDosen;
            const matchesSearch = !searchTerm || itemTitle.includes(searchTerm);
            
            if (matchesCategory && matchesDosen && matchesSearch) {
                item.style.display = 'block';
                visibleCount++;
            } else {
                item.style.display = 'none';
            }
        });
        
        // Show or hide "No FAQ Found" message
        if (visibleCount === 0) {
            if (noFaqFound) noFaqFound.style.display = 'block';
        } else {
            if (noFaqFound) noFaqFound.style.display = 'none';
        }
    }
    
    // Event listener untuk tombol kategori
    categoryButtons.forEach(button => {
        button.addEventListener('click', function() {
            // Remove active class from all category buttons
            categoryButtons.forEach(btn => btn.classList.remove('active'));
            
            // Add active class to this button
            this.classList.add('active');
            
            // Update active category
            activeCategory = this.getAttribute('data-category') || 'all';
            
            // Apply filters
            applyFilters();
        });
    });
    
    // Event listener untuk dropdown dosen
    dosenLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            
            // Jika yang dipilih adalah "Semua Dosen"
            if (this.getAttribute('data-dosen') === "Semua Dosen") {
                // Reset active dosen filter
                activeDosen = null;
                
                // Reset dropdown button text ke default
                const dropdownButton = document.querySelector('.dosen-dropdown .btn');
                dropdownButton.innerHTML = '<i class="fas fa-user-tie"></i> Dosen';
                dropdownButton.classList.remove('active-dosen');
                
                // Aktifkan tombol "Semua" kategori
                categoryButtons.forEach(btn => {
                    if (btn.getAttribute('data-category') === 'all') {
                        btn.classList.add('active');
                    } else {
                        btn.classList.remove('active');
                    }
                });
                
                // Set active category to 'all'
                activeCategory = 'all';
            } else {
                // Update active dosen untuk dosen lain
                activeDosen = this.getAttribute('data-dosen');
                
                // Update dropdown button text and make it active
                const dropdownButton = document.querySelector('.dosen-dropdown .btn');
                dropdownButton.innerHTML = '<i class="fas fa-user-tie"></i> ' + this.textContent;
                dropdownButton.classList.add('active-dosen');
                
                // Reset active category to 'all' but don't highlight any button
                activeCategory = 'all';
                categoryButtons.forEach(btn => btn.classList.remove('active'));
                categoryButtons[0].classList.add('active');
            }
            
            // Apply filters
            applyFilters();
        });
    });
    
    // Event listener untuk pencarian
    if (searchInput) {
        searchInput.addEventListener('input', applyFilters);
    }
    
    // Variabel untuk menyimpan ID sematan yang akan dihapus
    let sematanToDelete = null;
    
    // Event listener untuk tombol batalkan sematan dengan modal konfirmasi
    document.querySelectorAll('.batalkan-sematan').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            
            sematanToDelete = this.getAttribute('data-id');
            
            // Tampilkan modal konfirmasi
            confirmModal.show();
        });
    });
    
    // Event listener untuk tombol batal di modal konfirmasi
    document.getElementById('cancelConfirm').addEventListener('click', function() {
        confirmModal.hide();
        sematanToDelete = null;
    });
    
    // Event listener untuk tombol konfirmasi hapus
    document.getElementById('confirmDelete').addEventListener('click', function() {
        if (sematanToDelete) {
            // Sembunyikan modal konfirmasi
            confirmModal.hide();
            
            // Tampilkan modal loading
            document.querySelector('#loadingModal .loading-text').textContent = 'Membatalkan sematan...';
            loadingModal.show();
            
            // Kirim request untuk membatalkan sematan
            fetch(`/batalkan-sematan/${sematanToDelete}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                // Sembunyikan modal loading
                loadingModal.hide();
                
                if (data.success) {
                    // Hapus elemen FAQ dari DOM dengan animasi
                    const faqItem = document.querySelector(`[data-id="${sematanToDelete}"]`).closest('.faq-item');
                    faqItem.style.transition = 'opacity 0.3s ease';
                    faqItem.style.opacity = '0';
                    
                    setTimeout(() => {
                        faqItem.remove();
                        
                        // Periksa apakah masih ada FAQ tersisa
                        const remainingFaq = document.querySelectorAll('.faq-item');
                        if (remainingFaq.length === 0) {
                            // Tampilkan pesan "belum ada pesan tersematkan"
                            const faqList = document.querySelector('.faq-list');
                            faqList.innerHTML = `
                                <div class="text-center p-4">
                                    <i class="fas fa-info-circle fa-2x mb-3 text-muted"></i>
                                    <h5 class="text-muted">Belum Ada Pesan Tersematkan</h5>
                                    <p class="text-muted">Pesan yang disematkan akan muncul di sini</p>
                                </div>
                            `;
                        }
                        
                        applyFilters(); // Reapply filters
                    }, 300);
                    
                    // Tampilkan notifikasi sukses (bisa diganti dengan toast notification)
                    showNotification('Sematan berhasil dibatalkan', 'success');
                } else {
                    showNotification('Gagal membatalkan sematan: ' + data.message, 'error');
                }
                
                // Reset variabel
                sematanToDelete = null;
            })
            .catch(error => {
                console.error('Error:', error);
                loadingModal.hide();
                showNotification('Terjadi kesalahan saat membatalkan sematan', 'error');
                sematanToDelete = null;
            });
        }
    });
    
    // Fungsi untuk menampilkan notifikasi (sederhana)
    function showNotification(message, type = 'info') {
        // Buat elemen notifikasi
        const notification = document.createElement('div');
        notification.className = `alert alert-${type === 'success' ? 'success' : 'danger'} alert-dismissible fade show position-fixed`;
        notification.style.cssText = `
            top: 20px;
            right: 20px;
            z-index: 9999;
            min-width: 300px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
        `;
        
        notification.innerHTML = `
            <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'} me-2"></i>
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
        
        document.body.appendChild(notification);
        
        // Auto remove setelah 5 detik
        setTimeout(() => {
            if (notification.parentNode) {
                notification.remove();
            }
        }, 5000);
    }
    
    // Role switcher toggle untuk perpindahan peran
    const roleSwitcher = document.getElementById('roleSwitcher');
    if (roleSwitcher) {
        roleSwitcher.addEventListener('change', function() {
            // Tampilkan loading spinner
            showRoleModal('Memuat...');
            
            // Submit form langsung setelah delay singkat
            setTimeout(() => {
                document.getElementById('switchRoleForm').submit();
            }, 500);
        });
    }
    
    // Fungsi untuk menampilkan modal loading sederhana
    function showRoleModal(message) {
        const modal = document.getElementById('roleModalBackdrop');
        if (modal) {
            // Perbarui pesan jika ada
            if (message) {
                const messageElement = document.getElementById('roleModalMessage');
                if (messageElement) {
                    messageElement.textContent = message;
                }
            }
            
            // Tampilkan modal
            modal.classList.add('show');
        }
    }
    
    // Enhanced keyboard shortcuts
    document.addEventListener('keydown', function(e) {
        // Esc untuk tutup sidebar mobile atau modal
        if (e.key === 'Escape') {
            // Close mobile sidebar if open
            if (mobileSidebar && mobileSidebar.classList.contains('show')) {
                closeMobileSidebar();
            }
            // Close confirm modal if open
            else if (confirmModal._isShown) {
                confirmModal.hide();
                sematanToDelete = null;
            }
        }
    });
    
    // Handle window resize to close mobile sidebar on desktop
    window.addEventListener('resize', function() {
        if (window.innerWidth > 991 && mobileSidebar && mobileSidebar.classList.contains('show')) {
            closeMobileSidebar();
        }
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
        if (swipeDistance > swipeThreshold && touchStartX < 50 && mobileSidebar && !mobileSidebar.classList.contains('show')) {
            if (window.innerWidth <= 768) {
                if (mobileMenuToggle) {
                    mobileMenuToggle.click();
                }
            }
        }
        
        // Swipe left to close sidebar (only if open)
        if (swipeDistance < -swipeThreshold && mobileSidebar && mobileSidebar.classList.contains('show')) {
            closeMobileSidebar();
        }
    }
    
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
        
        if (searchInput) {
            searchInput.setAttribute('aria-label', 'Cari pesan tersematkan');
        }
        
        // Update ARIA states when sidebar opens/closes
        if (mobileMenuToggle) {
            mobileMenuToggle.addEventListener('click', function() {
                this.setAttribute('aria-expanded', 'true');
            });
        }
        
        // Reset ARIA state when closing
        const originalCloseMobileSidebar = closeMobileSidebar;
        closeMobileSidebar = function() {
            originalCloseMobileSidebar();
            if (mobileMenuToggle) {
                mobileMenuToggle.setAttribute('aria-expanded', 'false');
            }
        };
    }
    
    enhanceAccessibility();
    
    console.log('Enhanced FAQ dosen with loading and confirmation modals initialized successfully');
});
</script>
@endpush