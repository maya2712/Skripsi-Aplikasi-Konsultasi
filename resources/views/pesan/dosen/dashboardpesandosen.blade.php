@extends('layouts.app')

@section('title', 'Dashboard Pesan Dosen')

@push('styles')
<style>
    :root {
        --bs-primary: #1a73e8;
        --bs-danger: #FF5252;
        --bs-success: #27AE60;
        --bs-warning: #ff9800;
        --primary-gradient: linear-gradient(to right, #004AAD, #5DE0E6);
        --primary-hover: linear-gradient(to right, #003c8a, #4bc4c9);
        --text-color: #546E7A; /* Warna teks menu utama */
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

    .mobile-navbar.scrolled {
        background: rgba(0, 74, 173, 0.95);
        backdrop-filter: blur(15px);
        -webkit-backdrop-filter: blur(15px);
        box-shadow: 0 4px 20px rgba(0,0,0,0.25);
        position: fixed;
        top: 0;
        z-index: 1030;
    }

    .mobile-navbar .navbar-content {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .mobile-navbar .page-info h6 {
        margin: 0;
        font-size: 1rem;
        font-weight: 600;
    }

    .mobile-navbar .page-info small {
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

    /* Mobile Sidebar */
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

    /* PERBAIKAN: Pastikan navbar dropdown memiliki z-index yang lebih tinggi */
    .navbar .dropdown-menu {
        z-index: 1060 !important;
    }
    
    .navbar .dropdown-toggle {
        z-index: 1055 !important;
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

    .badge-notification {
        background: var(--bs-danger);
    }

    .search-filter-card {
        position: sticky;
        top: 76px;
        z-index: 100;
        background: white;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    .message-container {
        max-height: calc(100vh - 200px);
        overflow-y: auto;
        padding-right: 10px; 
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
    
    .message-card.penting {
        border-left: 4px solid var(--bs-danger);
    }

    .message-container::-webkit-scrollbar-thumb:hover {
        background: #555;
    }
    
    .message-card.umum {
        border-left: 4px solid var(--bs-success);
    }
    
    .content-cards {
        margin-top: 0;
    }
    
    .stats-cards .card {
        height: 100%;
        margin-bottom: 0;
    }

    .stats-cards .col-md-4 {
        display: flex;
        align-items: stretch;
    }

    .stats-cards .card {
        width: 100%;
    }
    
    .custom-container {
        max-width: 1400px;
        margin: 0 auto;
        padding: 0 15px;
    }
    
    .card {
        margin-bottom: 20px;
    }
    
    .message-list .card {
        margin-bottom: 15px;
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
        color: #546E7A; /* Warna teks menu yang konsisten */
    }

    .profile-image {
        width: 45px;
        height: 45px;
        border-radius: 50%;
        object-fit: cover;
        border: 2px solid #f8f9fa;
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
    }
    
    .bookmark-icon {
        color: #ffc107;
        cursor: pointer;
    }
    
    .bookmark-icon.active {
        color: #ffc107;
    }
    
    .bookmark-icon:not(.active) {
        color: #dee2e6;
    }
    
    /* Style baru untuk message card clickable */
    .message-card {
        cursor: pointer;
        transition: all 0.2s ease;
        position: relative;
        background-color: #ffffff !important;
    }
    
    .message-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }
    
    /* Memastikan tombol-tombol tetap berfungsi dan tidak mengganggu card clickable */
    .message-card .btn, 
    .message-card .bookmark-icon {
        position: relative;
        z-index: 10;
    }
    
    .action-buttons {
        position: relative;
        z-index: 10;
    }
    
    /* Style untuk role badge */
    .role-badge {
        display: inline-block;
        padding: 5px 10px;
        border-radius: 30px;
        font-size: 12px;
        margin-bottom: 10px;
    }
    
    /* Custom modal/pop-up styling */
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
    
    .role-modal-header {
        padding: 15px;
        border-bottom: 1px solid #e9ecef;
        position: relative;
    }
    
    .role-modal-body {
        padding: 20px;
    }
    
    .role-modal-icon {
        width: 70px;
        height: 70px;
        margin: 0 auto 15px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 30px;
    }
    
    .role-modal-success .role-modal-header {
        background-color: #E3F2FD;
        color: var(--bs-primary);
    }
    
    .role-modal-success .role-modal-icon {
        background-color: #E3F2FD;
        color: var(--bs-primary);
    }
    
    .role-modal-error .role-modal-header {
        background-color: #FFEBEE;
        color: var(--bs-danger);
    }
    
    .role-modal-error .role-modal-icon {
        background-color: #FFEBEE;
        color: var(--bs-danger);
    }
    
    .role-modal-title {
        font-size: 18px;
        font-weight: 600;
        margin: 0;
    }
    
    .role-modal-message {
        font-size: 16px;
        margin-bottom: 0;
    }
    
    .role-modal-footer {
        padding: 10px 20px 20px;
    }
    
    .btn-role-modal {
        padding: 8px 20px;
        border-radius: 30px;
        font-weight: 500;
        border: none;
        transition: all 0.3s ease;
    }
    
    .btn-role-modal-primary {
        background: linear-gradient(to right, #1a73e8, #3f9cff);
        color: white;
    }
    
    .btn-role-modal-primary:hover {
        background: linear-gradient(to right, #1557b0, #1a73e8);
        box-shadow: 0 4px 10px rgba(26, 115, 232, 0.3);
        color: white;
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
    
    /* Style untuk submenu mode peran */
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
        color: #546E7A; /* Warna teks abu-abu yang konsisten */
    }
    
    .pengaturan-submenu .btn-link:hover {
        background-color: #f8f9fa;
        color: #546E7A; /* Warna teks saat hover - tetap konsisten */
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
    
    .switch-labels span {
        z-index: 1;
    }

    .filter-btn.active {
        font-weight: 600;
        box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
    }

    .page-title {
        color: #37474F;
        font-weight: 600;
        margin-bottom: 20px;
    }

    /* Mobile Responsive Styles */
    @media (max-width: 991.98px) {
        body {
            padding-top: 0;
        }
        
        .row.g-4 {
            --bs-gutter-x: 0;
        }
        
        .col-md-3 {
            display: none;
        }
        
        .col-md-9 {
            padding-left: 0;
            padding-right: 0;
            flex: 0 0 100%;
            max-width: 100%;
        }
        
        .mobile-navbar {
            display: block;
        }
        
        .main-content {
            padding-top: 15px;
        }
        
        .search-filter-card {
            position: static;
            top: auto;
            margin-bottom: 20px;
        }
        
        .message-container {
            max-height: none;
            overflow: visible;
            padding-right: 0;
        }

        .mode-switcher-container {
            padding: 12px 15px;
        }

        .mode-title {
            font-size: 14px;
        }
    }

    @media (max-width: 768px) {
        body {
            padding-top: 0;
        }
        
        .custom-container {
            padding: 0 10px;
        }
        
        .main-content {
            padding-top: 10px;
            padding-bottom: 15px;
        }
        
        .mobile-navbar {
            padding: 10px 15px;
        }
        
        .mobile-navbar .navbar-content {
            flex-direction: row;
            align-items: center;
        }
        
        .mobile-navbar .page-info h6 {
            font-size: 0.95rem;
        }
        
        .mobile-navbar .page-info small {
            font-size: 0.7rem;
        }
        
        .stats-cards {
            margin-bottom: 20px;
        }
        
        .stats-cards .col-md-4 {
            margin-bottom: 15px;
            display: flex;
            align-items: stretch;
        }

        .stats-cards .card {
            width: 100%;
        }

        /* Mobile: Ubah ke layout horizontal 3 kolom yang lebih compact */
        .stats-cards .row {
            --bs-gutter-x: 0.4rem;
        }

        .stats-cards .col-md-4 {
            flex: 0 0 33.333333%;
            max-width: 33.333333%;
            margin-bottom: 12px;
        }

        .stats-cards .card {
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            border: 1px solid rgba(0,0,0,0.05);
        }

        .stats-cards .card-body {
            padding: 10px 8px;
            text-align: center;
        }

        .stats-cards .card-body h6 {
            font-size: 0.65rem;
            margin-bottom: 4px;
            font-weight: 500;
            color: #6c757d;
            line-height: 1.2;
        }

        .stats-cards .card-body h3 {
            font-size: 1.4rem;
            font-weight: 700;
            margin-bottom: 4px;
            color: #2c3e50;
        }

        .stats-cards .bg-opacity-10 {
            padding: 6px;
            border-radius: 8px;
            margin: 0 auto;
            width: fit-content;
        }

        .stats-cards .bg-opacity-10 i {
            font-size: 0.9rem;
        }
        
        .card-body {
            padding: 15px;
        }
        
        .message-card .row {
            --bs-gutter-x: 0.75rem;
        }
        
        .message-card .col-md-8,
        .message-card .col-md-4 {
            padding: 0 8px;
        }
        
        .profile-image,
        .profile-image-placeholder {
            width: 35px;
            height: 35px;
            font-size: 16px;
        }
        
        .btn-group {
            display: flex;
            flex-wrap: wrap;
            gap: 5px;
        }
        
        .filter-btn {
            padding: 8px 12px;
            font-size: 12px;
        }

        .mode-switcher-container {
            padding: 10px 12px;
            margin-bottom: 15px;
        }

        .mode-title {
            font-size: 13px;
        }

        .switch {
            width: 48px;
            height: 24px;
        }

        .slider:before {
            height: 16px;
            width: 16px;
            left: 4px;
            bottom: 4px;
        }

        .mode-switcher-container.kaprodi .slider:before {
            right: 4px !important;
        }
    }

    @media (max-width: 576px) {
        body {
            font-size: 12px;
            padding-top: 0;
        }
        
        .custom-container {
            padding: 0 8px;
        }
        
        .main-content {
            padding-top: 8px;
            padding-bottom: 10px;
        }
        
        .mobile-navbar {
            padding: 8px 12px;
        }
        
        .mobile-navbar .page-info h6 {
            font-size: 0.9rem;
            margin-bottom: 2px;
            line-height: 1.2;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
            max-width: 200px;
        }
        
        .mobile-navbar .page-info small {
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
        
        .mobile-sidebar {
            width: 260px;
        }
        
        .mobile-sidebar-header {
            padding: 15px 12px;
        }
        
        .mobile-sidebar-header h6 {
            font-size: 0.95rem;
        }
        
        .stats-cards {
            margin-bottom: 15px;
        }
        
        .stats-cards .col-md-4 {
            margin-bottom: 8px;
            display: flex;
            align-items: stretch;
        }

        .stats-cards .card {
            width: 100%;
        }

        /* Mobile Small: Layout horizontal 3 kolom yang sangat compact */
        .stats-cards .row {
            --bs-gutter-x: 0.25rem;
        }

        .stats-cards .col-md-4 {
            flex: 0 0 33.333333%;
            max-width: 33.333333%;
            margin-bottom: 8px;
        }

        .stats-cards .card {
            border-radius: 8px;
            box-shadow: 0 1px 6px rgba(0,0,0,0.06);
            border: 1px solid rgba(0,0,0,0.04);
        }

        .stats-cards .card-body {
            padding: 8px 6px;
            text-align: center;
        }

        .stats-cards .card-body h6 {
            font-size: 0.6rem;
            margin-bottom: 3px;
            font-weight: 500;
            color: #6c757d;
            line-height: 1.1;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .stats-cards .card-body h3 {
            font-size: 1.2rem;
            font-weight: 700;
            margin-bottom: 3px;
            color: #2c3e50;
        }

        .stats-cards .bg-opacity-10 {
            padding: 4px;
            border-radius: 6px;
            margin: 0 auto;
            width: fit-content;
        }

        .stats-cards .bg-opacity-10 i {
            font-size: 0.75rem;
        }
        
        .card {
            border-radius: 8px;
            margin-bottom: 12px;
        }
        
        .card-body {
            padding: 12px;
        }
        
        .search-filter-card .card-body {
            padding: 12px;
        }
        
        .search-filter-card .row {
            --bs-gutter-x: 0.5rem;
        }
        
        .form-control {
            font-size: 12px;
            padding: 10px 12px;
            border-radius: 8px;
        }
        
        .btn-group {
            width: 100%;
            justify-content: space-between;
        }
        
        .filter-btn {
            flex: 1;
            padding: 6px 8px;
            font-size: 11px;
            border-radius: 15px;
            margin: 0 2px;
        }
        
        .message-card {
            border-radius: 8px;
        }
        
        .message-card .card-body {
            padding: 12px;
        }
        
        .message-card .row {
            --bs-gutter-x: 0.5rem;
        }
        
        .message-card .col-md-8 {
            margin-bottom: 10px;
        }
        
        .profile-image,
        .profile-image-placeholder {
            width: 30px;
            height: 30px;
            font-size: 14px;
        }
        
        .message-card h6 {
            font-size: 12px;
        }
        
        .message-card small {
            font-size: 10px;
        }
        
        .badge {
            font-size: 9px;
            padding: 3px 6px;
        }
        
        .action-buttons .btn {
            font-size: 9px;
            padding: 4px 8px;
        }
        
        .text-center.py-5 {
            padding: 2rem 1rem !important;
        }
        
        .text-center.py-5 p {
            font-size: 12px;
        }

        .mode-switcher-container {
            padding: 8px 10px;
            margin-bottom: 10px;
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
            left: 4px;
            bottom: 4px;
        }

        .mode-switcher-container.kaprodi .slider:before {
            right: 4px !important;
        }
    }

    /* Perbaikan khusus untuk tampilan yang lebih mirip dengan gambar */
    @media (max-width: 768px) {
        .stats-cards .card-body {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            min-height: 75px;
            position: relative;
        }

        .stats-cards .card-body > div:first-child {
            order: 2;
            text-align: center;
        }

        .stats-cards .bg-opacity-10 {
            order: 1;
            margin-bottom: 6px;
            padding: 8px;
            border-radius: 8px;
        }

        .stats-cards .bg-opacity-10 i {
            font-size: 1rem;
        }

        /* Warna khusus untuk setiap card statistik */
        .stats-cards .col-md-4:nth-child(1) .bg-danger {
            background-color: rgba(255, 82, 82, 0.1) !important;
        }

        .stats-cards .col-md-4:nth-child(1) .text-danger {
            color: #FF5252 !important;
        }

        .stats-cards .col-md-4:nth-child(2) .bg-success {
            background-color: rgba(39, 174, 96, 0.1) !important;
        }

        .stats-cards .col-md-4:nth-child(2) .text-success {
            color: #27AE60 !important;
        }

        .stats-cards .col-md-4:nth-child(3) .bg-primary {
            background-color: rgba(26, 115, 232, 0.1) !important;
        }

        .stats-cards .col-md-4:nth-child(3) .text-primary {
            color: #1a73e8 !important;
        }
    }

    @media (max-width: 576px) {
        .stats-cards .card-body {
            min-height: 70px;
            padding: 6px 4px;
        }

        .stats-cards .bg-opacity-10 {
            padding: 6px;
            margin-bottom: 4px;
        }

        .stats-cards .bg-opacity-10 i {
            font-size: 0.85rem;
        }
    }

    /* Tambahan untuk memastikan responsive yang baik */
    @media (max-width: 480px) {
        .stats-cards .row {
            --bs-gutter-x: 0.2rem;
        }

        .stats-cards .card-body h6 {
            font-size: 0.55rem;
        }

        .stats-cards .card-body h3 {
            font-size: 1.1rem;
        }
    }
</style>
@endpush

@section('content')
<!-- Mobile Navigation Bar -->
<div class="mobile-navbar">
    <div class="navbar-content">
        <div class="page-info">
            <h6>Dashboard Pesan Dosen</h6>
            <small>{{ $totalPesan }} total pesan</small>
        </div>
        <div class="navbar-actions">
            <button class="burger-menu" id="mobileMenuToggle">
                <i class="fas fa-bars"></i>
            </button>
        </div>
    </div>
</div>

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
                <a href="{{ route('dosen.pesan.create') }}" class="btn" style="background: var(--primary-gradient); color: white; padding: 10px 20px; border: none; border-radius: 5px;">
                    <i class="fas fa-plus me-2"></i> Pesan Baru
                </a>
            </div>
            <div class="sidebar-menu">
                <div class="nav flex-column">
                    <a href="{{ route('dosen.dashboard.pesan') }}" class="nav-link active">
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
                    <a href="{{ url('/faqdosen') }}" class="nav-link menu-item">
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
                            <a href="{{ route('dosen.dashboard.pesan') }}" class="nav-link active">
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
                            
                            <a href="{{ url('/faqdosen') }}" class="nav-link menu-item">
                                <i class="fas fa-thumbtack me-2"></i>Pesan Tersematkan
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Content -->
            <div class="col-md-9">
                <!-- Mode Switcher - Perubahan sesuai desain baru -->
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
                
                <!-- Stats Cards -->
                <div class="stats-cards row g-3 mb-4">
                    <div class="col-md-4 d-flex">
                        <div class="card h-100 w-100">
                            <div class="card-body d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="text-muted mb-1">Belum dibaca</h6>
                                    <h3 class="mb-0 fs-4">{{ $belumDibaca }}</h3>
                                </div>
                                <div class="bg-danger bg-opacity-10 p-3 rounded">
                                    <i class="fas fa-envelope text-danger"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 d-flex">
                        <div class="card h-100 w-100">
                            <div class="card-body d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="text-muted mb-1">Pesan Aktif</h6>
                                    <h3 class="mb-0 fs-4">{{ $pesanAktif }}</h3>
                                </div>
                                <div class="bg-success bg-opacity-10 p-3 rounded">
                                    <i class="fas fa-comments text-success"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-4 d-flex">
                        <div class="card h-100 w-100">
                            <div class="card-body d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="text-muted mb-1">Total Pesan</h6>
                                    <h3 class="mb-0 fs-4">{{ $totalPesan }}</h3>
                                </div>
                                <div class="bg-primary bg-opacity-10 p-3 rounded">
                                    <i class="fas fa-inbox text-primary"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Search and Filters -->
                <div class="card mb-4 search-filter-card">
                    <div class="card-body">
                        <div class="row g-3 align-items-center">
                            <div class="col-md">
                                <input type="text" class="form-control" id="searchInput" placeholder="Cari Pesan..." style="font-size: 14px;">
                            </div>
                            <div class="col-md-auto">
                                <div class="btn-group">
                                    <button class="btn btn-outline-danger rounded-pill px-4 py-2 me-2 filter-btn" data-filter="penting" style="font-size: 14px;">Penting</button>
                                    <button class="btn btn-outline-success rounded-pill px-4 py-2 me-2 filter-btn" data-filter="umum" style="font-size: 14px;">Umum</button>
                                    <button class="btn btn-primary rounded-pill px-4 py-2 filter-btn" data-filter="semua" style="font-size: 14px;">Semua</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Message List -->
                <div class="message-list" id="messageList">
                    @if($pesan->count() > 0)
                        @foreach($pesan as $item)
                        <div class="card mb-2 message-card {{ strtolower($item->prioritas) }}" onclick="window.location.href='{{ route('dosen.pesan.show', $item->id) }}';" style="cursor: pointer;">
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col-md-8 d-flex align-items-center">
                                        @if($item->nip_pengirim == Auth::user()->nip)
                                            <!-- Menampilkan foto mahasiswa penerima -->
                                            @php
                                                $mahasiswa = App\Models\Mahasiswa::where('nim', $item->nim_penerima)->first();
                                                $profilePhoto = $mahasiswa && $mahasiswa->profile_photo ? asset('storage/profile_photos/'.$mahasiswa->profile_photo) : null;
                                            @endphp
                                            @if($profilePhoto)
                                                <img src="{{ $profilePhoto }}" alt="Foto Profil" class="profile-image me-3">
                                            @else
                                                <div class="profile-image-placeholder me-3">
                                                    <i class="fas fa-user"></i>
                                                </div>
                                            @endif
                                        @else
                                            <!-- Menampilkan foto mahasiswa pengirim -->
                                            @php
                                                $mahasiswa = App\Models\Mahasiswa::where('nim', $item->nim_pengirim)->first();
                                                $profilePhoto = $mahasiswa && $mahasiswa->profile_photo ? asset('storage/profile_photos/'.$mahasiswa->profile_photo) : null;
                                            @endphp
                                            @if($profilePhoto)
                                                <img src="{{ $profilePhoto }}" alt="Foto Profil" class="profile-image me-3">
                                            @else
                                                <div class="profile-image-placeholder me-3">
                                                    <i class="fas fa-user"></i>
                                                </div>
                                            @endif
                                        @endif
                                        <div>
                                            
                                            <span class="badge bg-primary mb-1">{{ $item->subjek }}</span>
                                            
                                            @if($item->nip_pengirim == Auth::user()->nip)
                                                <!-- Jika dosen adalah pengirim, tampilkan nama mahasiswa penerima -->
                                                <h6 class="mb-1" style="font-size: 14px;">
                                                    <span class="badge bg-info me-1" style="font-size: 10px;">Kepada</span>
                                                    @php
                                                        // Ambil langsung data mahasiswa penerima
                                                        $mahasiswa = App\Models\Mahasiswa::where('nim', $item->nim_penerima)->first();
                                                        $nama_penerima = $mahasiswa ? $mahasiswa->nama : 'Mahasiswa';
                                                    @endphp
                                                    {{ $nama_penerima }}
                                                </h6>
                                                <small class="text-muted">{{ $item->nim_penerima }}</small>
                                            @else
                                                <!-- Jika dosen adalah penerima, tampilkan nama mahasiswa pengirim -->
                                                <h6 class="mb-1" style="font-size: 14px;">
                                                    <span class="badge bg-info me-1" style="font-size: 10px;">Dari</span>
                                                    @php
                                                        // Ambil langsung data mahasiswa pengirim
                                                        $mahasiswa = App\Models\Mahasiswa::where('nim', $item->nim_pengirim)->first();
                                                        $nama_pengirim = $mahasiswa ? $mahasiswa->nama : 'Mahasiswa';
                                                    @endphp
                                                    {{ $nama_pengirim }}
                                                </h6>
                                                <small class="text-muted">{{ $item->nim_pengirim }}</small>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-4 text-md-end mt-3 mt-md-0">
                                        @php
                                            // Hitung jumlah balasan yang belum dibaca dengan Query Builder
                                            $unreadReplies = App\Models\BalasanPesan::where('id_pesan', $item->id)
                                                ->where('dibaca', false)
                                                ->where('tipe_pengirim', 'mahasiswa') // Hanya balasan dari mahasiswa
                                                ->count();
                                            
                                            // Tentukan status badge - Gunakan pengecekan yang lebih ketat
                                            $badgeClass = 'bg-success';
                                            $badgeText = 'Sudah dibaca';
                                            
                                            if ($item->nip_penerima == Auth::user()->nip && $item->dibaca == false) {
                                                // Pesan utama belum dibaca oleh dosen (sebagai penerima)
                                                $badgeClass = 'bg-danger';
                                                $badgeText = 'Belum dibaca';
                                            } 
                                            elseif ($unreadReplies > 0) {
                                                // Ada balasan baru dari mahasiswa yang belum dibaca
                                                $badgeClass = 'bg-danger';
                                                $badgeText = $unreadReplies . ' balasan baru';
                                            }
                                        @endphp
                                        
                                        <!-- Status dibaca/balasan baru dalam satu badge -->
                                        <span class="badge {{ $badgeClass }} me-1">
                                            {{ $badgeText }}
                                        </span>
                                        
                                        <span class="badge {{ $item->prioritas == 'Penting' ? 'bg-danger' : 'bg-success' }}">
                                            {{ $item->prioritas }}
                                        </span>
                                        
                                        <small class="d-block text-muted my-1">
                                            {{ \Carbon\Carbon::parse($item->created_at)->diffForHumans() }}
                                        </small>
                                        
                                        <div class="action-buttons" onclick="event.stopPropagation();">
                                            @if(isset($item->bookmarked))
                                            <form action="{{ route('dosen.pesan.bookmark', $item->id) }}" method="POST" class="d-inline me-2">
                                                @csrf
                                            
                                            </form>
                                            @endif
                                            
                                            <a href="{{ route('dosen.pesan.show', $item->id) }}" class="btn btn-custom-primary btn-sm view-btn" style="font-size: 10px;">
                                                <i class="fas fa-eye me-1"></i>Lihat
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    @else
                     
                    @endif

                    <!-- Pesan pencarian tidak tersedia -->
                    <div id="no-results" class="text-center py-4" style="display: none;">
                        <p class="text-muted">Pesan tidak tersedia</p>
                    </div>
                </div>
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
    // ============= PERBAIKAN KHUSUS UNTUK DROPDOWN AKUN =============
    
    // Event listener khusus untuk dropdown AKUN di navbar
    const userDropdownBtn = document.getElementById('userDropdown');
    if (userDropdownBtn) {
        userDropdownBtn.addEventListener('click', function(e) {
            // PENTING: Jangan preventDefault atau stopPropagation
            // Biarkan Bootstrap menangani dropdown secara normal
            
            console.log('Dropdown AKUN diklik - tidak akan menutup sidebar');
            
            // Tidak ada aksi tambahan - biarkan Bootstrap bekerja
        });
    }
    
    // Event listener untuk dropdown menu AKUN
    const userDropdownMenu = document.querySelector('#userDropdown + .dropdown-menu');
    if (userDropdownMenu) {
        userDropdownMenu.addEventListener('click', function(e) {
            console.log('Klik di dalam dropdown menu AKUN');
            // Biarkan link profil dan logout berfungsi normal
        });
    }
    
    // Pastikan semua dropdown di navbar tidak menutup sidebar
    document.querySelectorAll('.navbar .dropdown').forEach(dropdown => {
        dropdown.addEventListener('click', function(e) {
            console.log('Klik di navbar dropdown - sidebar tidak akan ditutup');
        });
    });
    
    // ============= MOBILE SIDEBAR FUNCTIONALITY =============
    
    const mobileMenuToggle = document.getElementById('mobileMenuToggle');
    const mobileSidebar = document.getElementById('mobileSidebar');
    const sidebarOverlay = document.getElementById('sidebarOverlay');
    const closeSidebar = document.getElementById('closeSidebar');
    
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
    
    // Tutup sidebar saat klik menu item mobile
    const mobileMenuItems = document.querySelectorAll('#mobileSidebar .nav-link[href]');
    mobileMenuItems.forEach(item => {
        item.addEventListener('click', function() {
            setTimeout(closeMobileSidebar, 100);
        });
    });
    
    // Event listener global dengan logika yang SANGAT SPESIFIK
    document.addEventListener('click', function(e) {
        // Jika sidebar tidak terbuka, tidak perlu melakukan apa-apa
        if (!mobileSidebar || !mobileSidebar.classList.contains('show')) {
            return;
        }
        
        // Identifikasi area klik yang SANGAT spesifik
        const clickedElement = e.target;
        
        // JANGAN tutup sidebar jika klik di:
        const isClickInsideSidebar = clickedElement.closest('.mobile-sidebar');
        const isClickOnHamburgerMenu = clickedElement.closest('#mobileMenuToggle');
        const isClickOnNavbarAkun = clickedElement.closest('#userDropdown') || clickedElement.closest('.dropdown-menu');
        const isClickOnNavbarToggler = clickedElement.closest('.navbar-toggler');
        const isClickAnywhereInNavbar = clickedElement.closest('.navbar');
        
        // Debug log
        if (isClickAnywhereInNavbar) {
            console.log('Klik di navbar terdeteksi - sidebar TIDAK akan ditutup');
        }
        
        if (isClickOnNavbarAkun) {
            console.log('Klik di dropdown AKUN terdeteksi - sidebar TIDAK akan ditutup');
        }
        
        // Hanya tutup sidebar jika:
        // 1. Klik TIDAK di dalam sidebar
        // 2. Klik TIDAK di hamburger menu dashboard  
        // 3. Klik TIDAK di dropdown AKUN atau menu dropdown
        // 4. Klik TIDAK di navbar toggler
        // 5. Klik TIDAK di manapun dalam navbar
        if (!isClickInsideSidebar && 
            !isClickOnHamburgerMenu && 
            !isClickOnNavbarAkun && 
            !isClickOnNavbarToggler && 
            !isClickAnywhereInNavbar) {
            
            console.log('Klik di luar area yang diizinkan - menutup sidebar');
            closeMobileSidebar();
        }
    });
    
    // ============= DROPDOWN FUNCTIONALITY =============
    
    // Mobile dropdown untuk grup
    const mobileGrupDropdownToggle = document.getElementById('mobileGrupDropdownToggle');
    const mobileKomunikasiSubmenu = document.getElementById('mobileKomunikasiSubmenu');
    const mobileGrupDropdownIcon = document.getElementById('mobileGrupDropdownIcon');
    
    if (mobileGrupDropdownToggle) {
        mobileGrupDropdownToggle.addEventListener('click', function(e) {
            e.preventDefault();
            
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
        grupDropdownToggle.addEventListener('click', function(e) {
            e.preventDefault();
            
            // Toggle the collapse
            if (komunikasiSubmenu.classList.contains('show')) {
                komunikasiSubmenu.classList.remove('show');
                grupDropdownIcon.classList.remove('fa-chevron-up');
                grupDropdownIcon.classList.add('fa-chevron-down');
            } else {
                komunikasiSubmenu.classList.add('show');
                grupDropdownIcon.classList.remove('fa-chevron-down');
                grupDropdownIcon.classList.add('fa-chevron-up');
            }
        });
    }
    
    // ============= FILTER & SEARCH FUNCTIONALITY =============
    
    // Fungsi filter pesan
    function filterMessages(filter) {
        const messageCards = document.querySelectorAll('.message-card');
        let visibleCount = 0;
        
        messageCards.forEach(card => {
            const isPenting = card.classList.contains('penting');
            const isUmum = card.classList.contains('umum');
            
            if (filter === 'semua' || 
                (filter === 'penting' && isPenting) || 
                (filter === 'umum' && isUmum)) {
                card.style.display = 'block';
                visibleCount++;
            } else {
                card.style.display = 'none';
            }
        });
        
        // Tampilkan pesan "tidak tersedia" jika tidak ada pesan yang sesuai filter
        const noResults = document.getElementById('no-results');
        if (noResults) {
            noResults.style.display = visibleCount === 0 ? 'block' : 'none';
            if (visibleCount === 0) {
                noResults.classList.remove('d-none');
            } else {
                noResults.classList.add('d-none');
            }
        }
    }
    
    // Fungsi pencarian pesan
    function searchMessages(searchTerm) {
        // Dapatkan filter aktif saat ini
        const activeFilter = document.querySelector('.filter-btn.active')?.dataset.filter || 'semua';
        
        const messageCards = document.querySelectorAll('.message-card');
        let visibleCount = 0;
        
        messageCards.forEach(card => {
            const messageText = card.textContent.toLowerCase();
            const isPenting = card.classList.contains('penting');
            const isUmum = card.classList.contains('umum');
            
            // Kombinasikan filter pencarian dengan filter prioritas
            const matchesSearch = messageText.includes(searchTerm);
            const matchesFilter = activeFilter === 'semua' || 
                                 (activeFilter === 'penting' && isPenting) || 
                                 (activeFilter === 'umum' && isUmum);
            
            if (matchesSearch && matchesFilter) {
                card.style.display = 'block';
                visibleCount++;
            } else {
                card.style.display = 'none';
            }
        });
        
        // Tampilkan pesan "tidak tersedia" jika tidak ada pesan yang sesuai pencarian
        const noResults = document.getElementById('no-results');
        if (noResults) {
            noResults.style.display = visibleCount === 0 ? 'block' : 'none';
            if (visibleCount === 0) {
                noResults.classList.remove('d-none');
            } else {
                noResults.classList.add('d-none');
            }
        }
    }
    
    // Pencarian pesan
    const searchInput = document.getElementById('searchInput');
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            searchMessages(searchTerm);
        });
    }
    
    // Menghentikan propagasi klik pada tombol-tombol di dalam card
    document.querySelectorAll('.action-buttons').forEach(button => {
        button.addEventListener('click', function(e) {
            e.stopPropagation();
        });
    });
    
    // Tambahkan event listener pada tombol filter
    const filterButtons = document.querySelectorAll('.filter-btn');
    filterButtons.forEach(button => {
        button.addEventListener('click', function() {
            // Hapus class active dari semua tombol
            filterButtons.forEach(btn => {
                btn.classList.remove('active');
                
                if (btn.dataset.filter === 'semua') {
                    btn.classList.remove('btn-primary');
                    btn.classList.add('btn-outline-primary');
                }
            });
            
            // Tambahkan class active ke tombol yang diklik
            this.classList.add('active');
            
            if (this.dataset.filter === 'semua') {
                this.classList.remove('btn-outline-primary');
                this.classList.add('btn-primary');
            }
            
            // Filter pesan berdasarkan tombol yang diklik
            const filter = this.dataset.filter;
            filterMessages(filter);
        });
    });
    
    // Set default filter ke "semua" saat halaman dimuat
    window.addEventListener('load', function() {
        // Hapus kelas active dari semua tombol filter
        filterButtons.forEach(btn => btn.classList.remove('active'));
        
        // Tambahkan kelas active ke tombol filter "semua"
        const semuaFilterBtn = document.querySelector('.filter-btn[data-filter="semua"]');
        if (semuaFilterBtn) {
            semuaFilterBtn.classList.add('active');
            semuaFilterBtn.classList.remove('btn-outline-primary');
            semuaFilterBtn.classList.add('btn-primary');
            // Aktifkan filter semua
            filterMessages('semua');
        }
    });
    
    // ============= ROLE SWITCHER FUNCTIONALITY =============
    
    // Role switcher toggle
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
    
    // Periksa apakah ada pesan sukses dari backend (dari session)
    // dan jika ada, hilangkan setelah beberapa detik
    const successAlert = document.querySelector('.alert-success');
    if (successAlert) {
        setTimeout(() => {
            successAlert.remove();
        }, 5000);
    }
    
    // ============= OTHER FUNCTIONALITY =============
    
    // Keyboard shortcuts
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            if (mobileSidebar && mobileSidebar.classList.contains('show')) {
                closeMobileSidebar();
            }
        }
    });
    
    // Handle window resize
    window.addEventListener('resize', function() {
        if (window.innerWidth > 991) {
            if (mobileSidebar && mobileSidebar.classList.contains('show')) {
                closeMobileSidebar();
            }
        }
    });
    
    // Swipe gestures
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
        
        if (swipeDistance > swipeThreshold && touchStartX < 50 && !mobileSidebar.classList.contains('show')) {
            if (window.innerWidth <= 768) {
                openMobileSidebar();
            }
        }
        
        if (swipeDistance < -swipeThreshold && mobileSidebar.classList.contains('show')) {
            closeMobileSidebar();
        }
    }
    
    // Mobile navbar scroll effect
    function handleMobileNavbarScroll() {
        const mobileNavbar = document.querySelector('.mobile-navbar');
        if (!mobileNavbar || window.innerWidth > 991) return;
        
        const scrolled = window.scrollY > 50;
        
        if (scrolled) {
            mobileNavbar.classList.add('scrolled');
        } else {
            mobileNavbar.classList.remove('scrolled');
        }
    }
    
    window.addEventListener('scroll', handleMobileNavbarScroll);
    
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
    
    handleMobileNavbarScroll();
    
    console.log('Dashboard dosen initialized - navbar dropdown should work normally');
});

// Fungsi untuk menampilkan modal loading sederhana
function showRoleModal(message) {
    const modal = document.getElementById('roleModalBackdrop');
    if (modal) {
        // Perbarui pesan jika ada
        if (message) {
            document.getElementById('roleModalMessage').textContent = message;
        }
        
        // Tampilkan modal
        modal.classList.add('show');
    }
}
</script>
@endpush