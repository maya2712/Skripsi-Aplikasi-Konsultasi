@extends('layouts.app')

@section('title', 'Riwayat Pesan Mahasiswa')

@push('styles')
<style>
    :root {
        --bs-primary: #1a73e8;
        --bs-danger: #FF5252;
        --bs-success: #27AE60;
        --bs-warning: #ff9800;
        --primary-gradient: linear-gradient(to right, #004AAD, #5DE0E6);
        --primary-hover: linear-gradient(to right, #003c8a, #4bc4c9);
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

    .komunikasi-submenu {
        margin-left: 15px;
    }

    .komunikasi-submenu .nav-link {
        padding: 8px 15px;
        font-size: 13px;
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
    
    /* Tambahan CSS untuk card yang bisa diklik */
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
    
    /* Pastikan tombol tetap bisa diklik dan tidak memicu klik pada card */
    .message-card .btn,
    .message-card a {
        position: relative;
        z-index: 10;
    }
    
    /* Memastikan event dari tombol tidak menyebar ke card */
    .action-buttons {
        position: relative;
        z-index: 5;
    }
    
    /* Style untuk badge kaprodi */
    .badge-kaprodi {
        background-color: #FF9800;
        color: white;
        font-size: 10px;
        padding: 5px 8px;
        border-radius: 4px;
        margin-left: 5px;
        font-weight: bold;
        vertical-align: middle;
    }
    
    /* Style untuk filter button */
    .filter-btn.active {
        font-weight: 600;
        box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
    }

    /* Filter Penting */
    .btn-outline-danger.btn-filter {
        color: #FF5252;
        border-color: #FF5252;
        transition: all 0.2s ease;
    }
    
    .btn-outline-danger.btn-filter:hover,
    .btn-outline-danger.btn-filter.active-filter {
        background-color: #FF5252;
        color: white;
        border-color: #FF5252;
    }
    
    /* Filter Umum */
    .btn-outline-success.btn-filter {
        color: #27AE60;
        border-color: #27AE60;
        transition: all 0.2s ease;
    }
    
    .btn-outline-success.btn-filter:hover,
    .btn-outline-success.btn-filter.active-filter {
        background-color: #27AE60;
        color: white;
        border-color: #27AE60;
    }
    
    /* Filter Semua */
    .btn-outline-primary.btn-filter {
        color: #1a73e8;
        border-color: #1a73e8;
        transition: all 0.2s ease;
    }
    
    .btn-outline-primary.btn-filter:hover,
    .btn-outline-primary.btn-filter.active-filter {
        background-color: #1a73e8;
        color: white;
        border-color: #1a73e8;
    }

    /* Styling untuk judul halaman */
    .page-title {
        color: #37474F;
        font-weight: 600;
        margin-bottom: 20px;
    }

    /* Hide pesan yang tidak sesuai dengan filter */
    .message-card.hidden {
        display: none;
    }
    
    /* Pesan not found */
    .no-messages-found {
        text-align: center;
        padding: 30px;
        background: white;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        margin-top: 20px;
        display: none;
    }

    /* Mobile Navigation Buttons - akan diintegrasikan ke navbar existing */
    .mobile-nav-buttons {
        display: none;
    }

    .burger-menu, .menu-dots {
        background: none;
        border: none;
        color: white;
        font-size: 20px;
        padding: 8px;
        border-radius: 8px;
        transition: all 0.3s ease;
        cursor: pointer;
        margin-left: 8px;
    }

    .burger-menu:hover, .menu-dots:hover {
        background-color: rgba(255, 255, 255, 0.2);
    }

    .burger-menu:focus, .menu-dots:focus {
        outline: none;
        box-shadow: none;
    }

    /* Mobile Responsive Styles - ENHANCED */
    @media (max-width: 991.98px) {
        body {
            padding-top: 0;
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
        
        .mobile-nav-buttons {
            display: flex; /* Show mobile buttons */
            align-items: center;
            gap: 5px;
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
    }

    /* Tablet Responsive Styles */
    @media (max-width: 768px) {
        .custom-container {
            padding: 0 10px;
        }
        
        .main-content {
            padding-top: 10px;
            padding-bottom: 15px;
        }
        
        .mobile-nav-buttons {
            display: flex;
            align-items: center;
            gap: 5px;
        }
        
        .mobile-top-header {
            padding: 12px 15px;
        }
        
        .mobile-top-header .app-logo {
            width: 32px;
            height: 32px;
            font-size: 12px;
        }
        
        .mobile-top-header .app-name {
            font-size: 15px;
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
            padding-top: 8px;
            padding-bottom: 10px;
        }
        
        .mobile-nav-buttons {
            display: flex;
            align-items: center;
            gap: 5px;
        }
        
        .burger-menu,
        .menu-dots {
            font-size: 18px;
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
        
        .badge-kaprodi {
            font-size: 8px;
            padding: 3px 5px;
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
        
        .no-messages-found {
            padding: 20px;
            margin-top: 15px;
        }
        
        .no-messages-found .fa-3x {
            font-size: 2rem !important;
        }
        
        .no-messages-found h5 {
            font-size: 1rem;
            margin-bottom: 8px;
        }
        
        .no-messages-found p {
            font-size: 12px;
        }
    }

    /* Extra Small Mobile (iPhone SE, etc) */
    @media (max-width: 375px) {
        .mobile-top-header {
            padding: 8px 10px;
        }
        
        .mobile-nav-buttons {
            display: flex;
            align-items: center;
            gap: 3px;
        }
        
        .mobile-sidebar {
            width: 240px;
        }
        
        .filter-btn {
            font-size: 10px;
            padding: 5px 6px;
        }
        
        .message-card .col-md-8,
        .message-card .col-md-4 {
            padding: 0 4px;
        }
    }

    /* Landscape orientation untuk mobile */
    @media (max-width: 768px) and (orientation: landscape) {
        .mobile-top-header {
            padding: 8px 12px;
        }
        
        .main-content {
            padding-top: 6px;
            padding-bottom: 8px;
        }
        
        .message-card .card-body {
            padding: 10px;
        }
    }

    /* High DPI / Retina Display Adjustments */
    @media (-webkit-min-device-pixel-ratio: 2), (min-resolution: 192dpi) {
        .card {
            box-shadow: 0 1px 5px rgba(0, 0, 0, 0.08);
        }
        
        .sidebar {
            box-shadow: 0 1px 5px rgba(0, 0, 0, 0.08);
        }
        
        .mobile-top-header {
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
                <a href="{{ route('mahasiswa.pesan.create') }}" class="btn" style="background: var(--primary-gradient); color: white; padding: 10px 20px; border: none; border-radius: 5px;">
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
                            $userGrups = Auth::user()->grups;
                        @endphp
                        
                        @if($userGrups && $userGrups->count() > 0)
                           @foreach($userGrups as $grupItem)
                            <a href="{{ route('mahasiswa.grup.show', $grupItem->id) }}" class="nav-link menu-item d-flex justify-content-between align-items-center">
                                {{ $grupItem->nama_grup }}
                                @if(($unreadCount = $grupItem->unreadMessages) && $unreadCount > 0)
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
                    <a href="{{ route('mahasiswa.pesan.history') }}" class="nav-link menu-item active">
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
                        <a href="{{ route('mahasiswa.pesan.create') }}" class="btn" style="background: linear-gradient(to right, #004AAD, #5DE0E6); color: white; padding: 10px 20px; border: none; border-radius: 5px;">
                            <i class="fas fa-plus me-2"></i> Pesan Baru
                        </a>
                    </div>                                                    
                    
                    <div class="sidebar-menu">
                        <div class="nav flex-column">
                            <a href="{{ route('mahasiswa.dashboard.pesan') }}" class="nav-link">
                                <i class="fas fa-home me-2"></i>Daftar Pesan
                            </a>
                            <a href="#" class="nav-link menu-item" id="grupDropdownToggle">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span><i class="fas fa-users me-2"></i>Daftar Grup</span>
                                    <i class="fas fa-chevron-down" id="grupDropdownIcon"></i>
                                </div>
                            </a>
                            <div class="collapse komunikasi-submenu" id="komunikasiSubmenu">
                                @php
                                    $userGrups = Auth::user()->grups;
                                @endphp
                                
                                @if($userGrups && $userGrups->count() > 0)
                                   @foreach($userGrups as $grupItem)
                                    <a href="{{ route('mahasiswa.grup.show', $grupItem->id) }}" class="nav-link menu-item d-flex justify-content-between align-items-center">
                                        {{ $grupItem->nama_grup }}
                                        @if(($unreadCount = $grupItem->unreadMessages) && $unreadCount > 0)
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
                            <a href="{{ route('mahasiswa.pesan.history') }}" class="nav-link menu-item active">
                                <i class="fas fa-history me-2"></i>Riwayat Pesan
                            </a>
                            <a href="{{ url('/faqmahasiswa') }}" class="nav-link menu-item">
                                <i class="fas fa-thumbtack me-2"></i>Pesan Tersematkan
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Content -->
            <div class="col-md-9">
                <!-- Search and Filters -->
                <div class="card mb-4 search-filter-card">
                    <div class="card-body">
                        <div class="row g-3 align-items-center">
                            <div class="col-md">
                                <input type="text" class="form-control" id="searchInput" placeholder="Cari Pesan..." style="font-size: 14px;">
                            </div>
                            <div class="col-md-auto">
                                <div class="btn-group">
                                    <button class="btn btn-outline-danger rounded-pill px-4 py-2 me-2 btn-filter" id="filterPenting" data-filter="penting" style="font-size: 14px;">Penting</button>
                                    <button class="btn btn-outline-success rounded-pill px-4 py-2 me-2 btn-filter" id="filterUmum" data-filter="umum" style="font-size: 14px;">Umum</button>
                                    <button class="btn btn-outline-primary rounded-pill px-4 py-2 btn-filter active-filter" id="filterSemua" data-filter="semua" style="font-size: 14px;">Semua</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Message List -->
                <div class="message-list" id="messageList">
                    @if($riwayatPesan->count() > 0)
                        @foreach($riwayatPesan as $pesan)
                        <div class="card mb-2 message-card {{ strtolower($pesan->prioritas) }}" 
                             data-kategori="{{ strtolower($pesan->prioritas) }}" 
                             data-pengirim="{{ $pesan->nim_pengirim == Auth::user()->nim ? ($pesan->dosenPenerima->nama ?? 'Dosen') : ($pesan->dosenPengirim->nama ?? 'Pengirim') }}" 
                             data-judul="{{ $pesan->subjek }}" 
                             onclick="window.location.href='{{ route('mahasiswa.pesan.show', $pesan->id) }}'">
                            
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col-md-8 d-flex align-items-center">
                                        @if($pesan->nim_pengirim == Auth::user()->nim)
                                            <!-- Menampilkan foto dosen penerima -->
                                            @php
                                                $profilePhoto = $pesan->dosenPenerima && $pesan->dosenPenerima->profile_photo 
                                                    ? asset('storage/profile_photos/'.$pesan->dosenPenerima->profile_photo) 
                                                    : null;
                                            @endphp
                                            @if($profilePhoto)
                                                <img src="{{ $profilePhoto }}" alt="Foto Profil" class="profile-image me-3">
                                            @else
                                                <div class="profile-image-placeholder me-3">
                                                    <i class="fas fa-user"></i>
                                                </div>
                                            @endif
                                        @else
                                            <!-- Menampilkan foto dosen pengirim -->
                                            @php
                                                $profilePhoto = $pesan->dosenPengirim && $pesan->dosenPengirim->profile_photo 
                                                    ? asset('storage/profile_photos/'.$pesan->dosenPengirim->profile_photo) 
                                                    : null;
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
                                            <span class="badge bg-primary mb-1">{{ $pesan->subjek }}</span>
                                            
                                            @if($pesan->nim_pengirim == Auth::user()->nim)
                                                <!-- Jika mahasiswa adalah pengirim, tampilkan nama dosen penerima -->
                                                <h6 class="mb-1" style="font-size: 14px;">
                                                    <span class="badge bg-info me-1" style="font-size: 10px;">Kepada</span>
                                                    {{ $pesan->dosenPenerima ? $pesan->dosenPenerima->nama : 'Dosen' }}
                                                    
                                                    @if($pesan->penerima_role == 'kaprodi')
                                                        <span class="badge badge-kaprodi ms-1">KAPRODI</span>
                                                    @endif
                                                </h6>
                                                <small class="text-muted">NIP: {{ $pesan->nip_penerima }}</small><br>
                                                <small class="text-muted">{{ $pesan->dosenPenerima ? $pesan->dosenPenerima->jabatan : 'Dosen' }}</small>
                                            @else
                                                <!-- Jika mahasiswa adalah penerima, tampilkan nama dosen pengirim -->
                                                <h6 class="mb-1" style="font-size: 14px;">
                                                    <span class="badge bg-info me-1" style="font-size: 10px;">Dari</span>
                                                    {{ $pesan->dosenPengirim ? $pesan->dosenPengirim->nama : 'Dosen' }}
                                                    
                                                    @if($pesan->pengirim_role == 'kaprodi')
                                                        <span class="badge badge-kaprodi ms-1">KAPRODI</span>
                                                    @endif
                                                </h6>
                                                <small class="text-muted">NIP: {{ $pesan->nip_pengirim }}</small><br>
                                                <small class="text-muted">{{ $pesan->dosenPengirim ? $pesan->dosenPengirim->jabatan : 'Dosen' }}</small>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-4 text-md-end mt-3 mt-md-0">
                                        <span class="badge bg-secondary me-1">Diakhiri</span>
                                        <span class="badge {{ $pesan->prioritas == 'Penting' ? 'bg-danger' : 'bg-success' }}">
                                            {{ $pesan->prioritas }}
                                        </span>
                                        
                                       <small class="d-block text-muted my-1">
                                            {{ \Carbon\Carbon::parse($pesan->updated_at)->diffForHumans() }}
                                        </small>
                                        
                                        <div class="action-buttons" onclick="event.stopPropagation();">
                                            <a href="{{ route('mahasiswa.pesan.show', $pesan->id) }}" class="btn btn-custom-primary btn-sm" style="font-size: 10px;">
                                                <i class="fas fa-eye me-1"></i>Lihat
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    @else
                        <div class="text-center py-5">
                            <div class="p-4 text-center">
                                <i class="fas fa-history fa-3x mb-3 text-muted"></i>
                                <h5>Belum ada riwayat pesan</h5>
                                <p class="text-muted">Riwayat pesan yang telah diakhiri akan muncul di sini</p>
                            </div>
                        </div>
                    @endif

                    <!-- Pesan pencarian tidak tersedia -->
                    <div id="no-results" class="text-center py-4" style="display: none;">
                        <p class="text-muted">Pesan tidak tersedia</p>
                    </div>
                    
                    <!-- Pesan tidak ditemukan -->
                    <div class="no-messages-found" id="noMessagesFound">
                        <div class="p-4 text-center">
                            <i class="fas fa-search fa-3x mb-3 text-muted"></i>
                            <h5>Tidak ada pesan yang ditemukan</h5>
                            <p class="text-muted">Coba ubah kata kunci pencarian atau filter</p>
                        </div>
                    </div>
                </div>
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
    const menuDots = document.getElementById('menuDots');
    
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
    
    // Menu dots functionality (for account menu - can be implemented later)
    if (menuDots) {
        menuDots.addEventListener('click', function() {
            // Add account menu functionality here
            console.log('Account menu clicked');
            // You can add dropdown menu for account settings, logout, etc.
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

    // Fungsi baru yang hanya menerapkan event listeners
    function applyEventListeners() {
        // Pastikan event listeners dari action buttons berfungsi
        document.querySelectorAll('.action-buttons, .action-buttons *').forEach(element => {
            element.addEventListener('click', function(e) {
                e.stopPropagation();
            });
        });
    }
    
    // Fungsi pencarian dan filter pesan
    const searchInput = document.getElementById('searchInput');
    const filterButtons = document.querySelectorAll('.btn-filter');
    const messageCards = document.querySelectorAll('.message-card');
    const noMessagesFound = document.getElementById('noMessagesFound');
    
    // Simpan filter aktif saat ini
    let currentFilter = 'semua';
    
    // Fungsi untuk menerapkan filter dan pencarian
    function applyFilterAndSearch() {
        const searchTerm = searchInput.value.toLowerCase();
        let visibleCount = 0;
        
        messageCards.forEach(card => {
            const kategori = card.getAttribute('data-kategori');
            const pengirim = card.getAttribute('data-pengirim').toLowerCase();
            const judul = card.getAttribute('data-judul').toLowerCase();
            const cardContent = card.textContent.toLowerCase();
            
            // Check filter category
            const matchesFilter = currentFilter === 'semua' || kategori === currentFilter;
            
            // Check search term
            const matchesSearch = searchTerm === '' || 
                                pengirim.includes(searchTerm) || 
                                judul.includes(searchTerm) || 
                                cardContent.includes(searchTerm);
            
            // Show/hide card based on both conditions
            if (matchesFilter && matchesSearch) {
                card.classList.remove('hidden');
                visibleCount++;
            } else {
                card.classList.add('hidden');
            }
        });
        
        // Show/hide no messages found message
        if (visibleCount === 0 && messageCards.length > 0) {
            noMessagesFound.style.display = 'block';
        } else {
            noMessagesFound.style.display = 'none';
        }
    }
    
    // Event untuk input pencarian
    if (searchInput) {
        searchInput.addEventListener('input', applyFilterAndSearch);
    }
    
    // Event untuk tombol filter
    filterButtons.forEach(button => {
        button.addEventListener('click', function() {
            // Remove active-filter class from all buttons
            filterButtons.forEach(btn => {
                btn.classList.remove('active-filter');
                
                // Reset semua tombol ke outline style sesuai dengan jenisnya
                if (btn.id === 'filterPenting') {
                    btn.className = 'btn btn-outline-danger rounded-pill px-4 py-2 me-2 btn-filter';
                } else if (btn.id === 'filterUmum') {
                    btn.className = 'btn btn-outline-success rounded-pill px-4 py-2 me-2 btn-filter';
                } else if (btn.id === 'filterSemua') {
                    btn.className = 'btn btn-outline-primary rounded-pill px-4 py-2 btn-filter';
                }
            });
            
            // Add active-filter class to clicked button
            this.classList.add('active-filter');
            
            // Update current filter
            currentFilter = this.getAttribute('data-filter');
            
            // Apply filter and search
            applyFilterAndSearch();
        });
    });
    
    // Enhanced keyboard shortcuts
    document.addEventListener('keydown', function(e) {
        // Esc untuk tutup sidebar mobile
        if (e.key === 'Escape') {
            // Close mobile sidebar if open
            if (mobileSidebar.classList.contains('show')) {
                closeMobileSidebar();
            }
        }
    });
    
    // Handle window resize to close mobile sidebar on desktop
    window.addEventListener('resize', function() {
        if (window.innerWidth > 768 && mobileSidebar.classList.contains('show')) {
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
    
    // Add haptic feedback for mobile interactions (if supported)
    function addHapticFeedback() {
        if ('vibrate' in navigator) {
            navigator.vibrate(50); // Short vibration
        }
    }
    
    // Add haptic feedback to button clicks on mobile - PERBAIKAN
    const interactiveElements = [mobileMenuToggle, closeSidebar, menuDots];
    // Tidak termasuk mobileMenuItems karena sudah dihandle secara terpisah
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
        
        if (menuDots) {
            menuDots.setAttribute('aria-label', 'Menu akun');
        }
        
        if (closeSidebar) {
            closeSidebar.setAttribute('aria-label', 'Tutup menu navigasi');
        }
        
        if (mobileSidebar) {
            mobileSidebar.setAttribute('role', 'navigation');
            mobileSidebar.setAttribute('aria-label', 'Menu navigasi utama');
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
    
    // Set default filter ke "semua" saat halaman dimuat
    window.addEventListener('load', function() {
        console.log('Page loaded, setting default filter');
        
        // Hapus kelas active dari semua tombol filter kecuali 'semua'
        filterButtons.forEach(btn => {
            // Jika tombol filter adalah 'semua', berikan class active
            if (btn.dataset.filter === 'semua') {
                btn.classList.add('active-filter');
            } else {
                btn.classList.remove('active-filter');
            }
        });
        
        // Menghentikan propagasi klik pada tombol-tombol di dalam card
        document.querySelectorAll('.action-buttons, .action-buttons *').forEach(element => {
            element.addEventListener('click', function(e) {
                e.stopPropagation();
            });
        });
        
        // Terapkan event listeners saat halaman dimuat
        applyEventListeners();
    });
    
    console.log('Mobile riwayat pesan initialized successfully');
});
</script>
@endpush