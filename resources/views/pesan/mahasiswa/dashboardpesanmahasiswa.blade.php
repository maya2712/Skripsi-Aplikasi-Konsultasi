@extends('layouts.app')

@section('title', 'Dashboard Pesan Mahasiswa')

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

    .komunikasi-submenu {
        margin-left: 15px;
    }

    .komunikasi-submenu .nav-link {
        padding: 8px 15px;
        font-size: 13px;
        width: 100%;
        color: #546E7A;
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
    
    .message-card .btn,
    .message-card a {
        position: relative;
        z-index: 10;
    }
    
    .action-buttons {
        position: relative;
        z-index: 5;
    }
    
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

    @media (max-width: 768px) {
        .custom-container {
            padding: 0 10px;
        }
        
        .main-content {
            padding-top: 10px;
            padding-bottom: 15px;
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
    }

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
<!-- Mobile buttons akan diintegrasikan ke navbar yang sudah ada di layout -->

<div class="main-content">
    <div class="custom-container">
        <!-- Mobile Sidebar Overlay -->
        <div class="sidebar-overlay" id="sidebarOverlay"></div>

        <!-- Mobile Sidebar -->
        <div class="mobile-sidebar" id="mobileSidebar">
            <div class="mobile-sidebar-header">
                <h6>Menu Sidebar</h6>
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
                    <a href="{{ route('mahasiswa.dashboard.pesan') }}" class="nav-link active">
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
                        <a href="{{ route('mahasiswa.pesan.create') }}" class="btn" style="background: linear-gradient(to right, #004AAD, #5DE0E6); color: white; padding: 10px 20px; border: none; border-radius: 5px;">
                            <i class="fas fa-plus me-2"></i> Pesan Baru
                        </a>
                    </div>                                                    
                    
                    <div class="sidebar-menu">
                        <div class="nav flex-column">
                            <a href="{{ route('mahasiswa.dashboard.pesan') }}" class="nav-link active">
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

            <!-- Main Content -->
            <div class="col-md-9">
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
                                    <button class="btn btn-outline-danger rounded-pill px-4 py-2 me-2 filter-btn" data-filter="penting" style="font-size: 14px;">
                                        Penting
                                    </button>
                                    <button class="btn btn-outline-success rounded-pill px-4 py-2 me-2 filter-btn" data-filter="umum" style="font-size: 14px;">
                                        Umum
                                    </button>
                                    <button class="btn btn-primary rounded-pill px-4 py-2 filter-btn active" data-filter="semua" style="font-size: 14px;">
                                        Semua
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Message List -->
                <div class="message-list" id="messageList">
                    @if($pesan->count() > 0)
                        @foreach($pesan as $p)
                        <div class="card mb-2 message-card {{ strtolower($p->prioritas) }}" 
                             onclick="window.location.href='{{ route('mahasiswa.pesan.show', $p->id) }}'">
                            
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col-md-8 d-flex align-items-center">
                                        @if($p->nim_pengirim == Auth::user()->nim)
                                            @php
                                                $profilePhoto = $p->dosenPenerima && $p->dosenPenerima->profile_photo 
                                                    ? asset('storage/profile_photos/'.$p->dosenPenerima->profile_photo) 
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
                                            @php
                                                $profilePhoto = $p->dosenPengirim && $p->dosenPengirim->profile_photo 
                                                    ? asset('storage/profile_photos/'.$p->dosenPengirim->profile_photo) 
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
                                            <span class="badge bg-primary mb-1">{{ $p->subjek }}</span>
                                            
                                            @if($p->nim_pengirim == Auth::user()->nim)
                                                <h6 class="mb-1" style="font-size: 14px;">
                                                    <span class="badge bg-info me-1" style="font-size: 10px;">Kepada</span>
                                                    {{ $p->dosenPenerima ? $p->dosenPenerima->nama : 'Dosen' }}
                                                    
                                                    @if($p->penerima_role == 'kaprodi')
                                                        <span class="badge badge-kaprodi ms-1">KAPRODI</span>
                                                    @endif
                                                </h6>
                                                <small class="text-muted">NIP: {{ $p->nip_penerima }}</small><br>
                                                <small class="text-muted">{{ $p->dosenPenerima ? $p->dosenPenerima->jabatan : 'Dosen' }}</small>
                                            @else
                                                <h6 class="mb-1" style="font-size: 14px;">
                                                    <span class="badge bg-info me-1" style="font-size: 10px;">Dari</span>
                                                    {{ $p->dosenPengirim ? $p->dosenPengirim->nama : 'Dosen' }}
                                                    
                                                    @if($p->pengirim_role == 'kaprodi')
                                                        <span class="badge badge-kaprodi ms-1">KAPRODI</span>
                                                    @endif
                                                </h6>
                                                <small class="text-muted">NIP: {{ $p->nip_pengirim }}</small><br>
                                                <small class="text-muted">{{ $p->dosenPengirim ? $p->dosenPengirim->jabatan : 'Dosen' }}</small>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-4 text-md-end mt-3 mt-md-0">
                                        @php
                                            // Hitung jumlah balasan yang belum dibaca
                                            $unreadReplies = App\Models\BalasanPesan::where('id_pesan', $p->id)
                                                ->where('dibaca', false)
                                                ->where('tipe_pengirim', 'dosen')
                                                ->count();
                                            
                                            // Tentukan status badge
                                            $badgeClass = 'bg-success';
                                            $badgeText = 'Sudah dibaca';
                                            
                                            if (!$p->dibaca && $p->nim_penerima == Auth::user()->nim) {
                                                $badgeClass = 'bg-danger';
                                                $badgeText = 'Belum dibaca';
                                            } else if ($unreadReplies > 0) {
                                                $badgeClass = 'bg-danger';
                                                $badgeText = $unreadReplies . ' balasan baru';
                                            }
                                        @endphp
                                        
                                        <span class="badge {{ $badgeClass }} me-1">
                                            {{ $badgeText }}
                                        </span>
                                        
                                        <span class="badge {{ $p->prioritas == 'Penting' ? 'bg-danger' : 'bg-success' }}">
                                            {{ $p->prioritas }}
                                        </span>
                                        
                                       <small class="d-block text-muted my-1">
                                            {{ \Carbon\Carbon::parse($p->created_at)->diffForHumans() }}
                                        </small>
                                        
                                        <div class="action-buttons" onclick="event.stopPropagation();">
                                            <a href="{{ route('mahasiswa.pesan.show', $p->id) }}" class="btn btn-custom-primary btn-sm" style="font-size: 10px;">
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
                            <p class="text-muted">Tidak ada pesan</p>
                        </div>
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
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    console.log('Dashboard mahasiswa loaded');
    
    // ============= FILTER FUNCTIONALITY =============
    
    function applyEventListeners() {
        document.querySelectorAll('.action-buttons, .action-buttons *').forEach(element => {
            element.addEventListener('click', function(e) {
                e.stopPropagation();
            });
        });
    }
    
    // Fungsi filter pesan berdasarkan prioritas
    function filterMessages(filter) {
        console.log('Filtering messages with:', filter);
        
        const messageCards = document.querySelectorAll('.message-card');
        let visibleCount = 0;
        
        messageCards.forEach(card => {
            const isPenting = card.classList.contains('penting');
            const isUmum = card.classList.contains('umum');
            
            let shouldShow = false;
            
            if (filter === 'semua') {
                shouldShow = true;
            } else if (filter === 'penting' && isPenting) {
                shouldShow = true;
            } else if (filter === 'umum' && isUmum) {
                shouldShow = true;
            }
            
            if (shouldShow) {
                card.style.display = 'block';
                visibleCount++;
            } else {
                card.style.display = 'none';
            }
        });
        
        // Tampilkan pesan jika tidak ada yang cocok
        showNoResultsMessage(visibleCount, 'Tidak ada pesan');
        
        console.log('Filter complete. Visible cards:', visibleCount);
    }
    
    // Fungsi pencarian pesan
    function searchMessages(keyword) {
        console.log('Searching messages with keyword:', keyword);
        
        if (keyword.trim() === '') {
            // Jika pencarian kosong, kembali ke filter aktif
            const activeFilter = document.querySelector('.filter-btn.active');
            if (activeFilter) {
                filterMessages(activeFilter.dataset.filter);
            } else {
                filterMessages('semua');
            }
            return;
        }
        
        const messageCards = document.querySelectorAll('.message-card');
        const activeFilter = document.querySelector('.filter-btn.active');
        const currentFilter = activeFilter ? activeFilter.dataset.filter : 'semua';
        let visibleCount = 0;
        
        messageCards.forEach(card => {
            const messageText = card.textContent.toLowerCase();
            const isPenting = card.classList.contains('penting');
            const isUmum = card.classList.contains('umum');
            
            // Cek apakah pesan cocok dengan keyword
            const matchesSearch = messageText.includes(keyword.toLowerCase());
            
            // Cek apakah pesan cocok dengan filter aktif
            let matchesFilter = false;
            if (currentFilter === 'semua') {
                matchesFilter = true;
            } else if (currentFilter === 'penting' && isPenting) {
                matchesFilter = true;
            } else if (currentFilter === 'umum' && isUmum) {
                matchesFilter = true;
            }
            
            // Tampilkan jika cocok dengan pencarian DAN filter
            if (matchesSearch && matchesFilter) {
                card.style.display = 'block';
                visibleCount++;
            } else {
                card.style.display = 'none';
            }
        });
        
        // Tampilkan pesan jika tidak ada hasil
        showNoResultsMessage(visibleCount, `Tidak ada pesan yang ditemukan dengan kata kunci "${keyword}"`);
        
        console.log('Search complete. Visible cards:', visibleCount);
    }
    
    // Fungsi untuk menampilkan pesan "tidak ada hasil"
    function showNoResultsMessage(visibleCount, message) {
        const messageList = document.getElementById('messageList');
        let noResults = document.getElementById('no-results');
        
        if (visibleCount === 0) {
            if (!noResults) {
                noResults = document.createElement('div');
                noResults.id = 'no-results';
                noResults.className = 'text-center py-5';
                messageList.appendChild(noResults);
            }
            noResults.innerHTML = `
                <i class="fas fa-search text-muted" style="font-size: 3rem; opacity: 0.3;"></i>
                <p class="text-muted mt-3">${message}</p>
            `;
            noResults.style.display = 'block';
        } else {
            if (noResults) {
                noResults.style.display = 'none';
            }
        }
    }
    
    // Fungsi untuk set active filter
    function setActiveFilter(filterValue) {
        const filterButtons = document.querySelectorAll('.filter-btn');
        
        filterButtons.forEach(btn => {
            btn.classList.remove('active');
            
            // Reset styling
            if (btn.dataset.filter === 'penting') {
                btn.classList.remove('btn-danger');
                btn.classList.add('btn-outline-danger');
            } else if (btn.dataset.filter === 'umum') {
                btn.classList.remove('btn-success');
                btn.classList.add('btn-outline-success');
            } else if (btn.dataset.filter === 'semua') {
                btn.classList.remove('btn-primary');
                btn.classList.add('btn-outline-primary');
            }
            
            // Set active filter
            if (btn.dataset.filter === filterValue) {
                btn.classList.add('active');
                
                // Set styling untuk button aktif
                if (filterValue === 'penting') {
                    btn.classList.remove('btn-outline-danger');
                    btn.classList.add('btn-danger');
                } else if (filterValue === 'umum') {
                    btn.classList.remove('btn-outline-success');
                    btn.classList.add('btn-success');
                } else if (filterValue === 'semua') {
                    btn.classList.remove('btn-outline-primary');
                    btn.classList.add('btn-primary');
                }
            }
        });
    }
    
    // ============= MOBILE SIDEBAR FUNCTIONALITY =============
    
    // Mobile sidebar elements
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
    
    // ============= DROPDOWN FUNCTIONALITY =============
    
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
        grupDropdownToggle.addEventListener('click', function(e) {
            e.preventDefault();
            
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
    
    // ============= EVENT LISTENERS =============
    
    // Event listener untuk filter buttons
    const filterButtons = document.querySelectorAll('.filter-btn');
    filterButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            console.log('Filter button clicked:', this.dataset.filter);
            
            // Kosongkan search input ketika filter diklik
            const searchInput = document.getElementById('searchInput');
            if (searchInput && searchInput.value.trim() !== '') {
                console.log('Clearing search input because filter was clicked');
                searchInput.value = '';
            }
            
            // Set active filter
            setActiveFilter(this.dataset.filter);
            
            // Apply filter
            const filter = this.dataset.filter;
            filterMessages(filter);
        });
    });
    
    // Event listener untuk search input
    const searchInput = document.getElementById('searchInput');
    if (searchInput) {
        let searchTimeout;
        
        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            const searchTerm = this.value.trim();
            
            console.log('Search input changed:', searchTerm);
            
            searchTimeout = setTimeout(() => {
                searchMessages(searchTerm);
            }, 300);
        });
        
        // Clear search dengan Escape key
        searchInput.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                this.value = '';
                const inputEvent = new Event('input', { bubbles: true });
                this.dispatchEvent(inputEvent);
                this.blur();
            }
        });
        
        // Pastikan filter kembali normal saat search blur dan kosong
        searchInput.addEventListener('blur', function() {
            if (this.value.trim() === '') {
                const activeFilter = document.querySelector('.filter-btn.active');
                if (activeFilter) {
                    filterMessages(activeFilter.dataset.filter);
                }
            }
        });
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
    
    // ============= INITIALIZATION =============
    
    // Set default filter dan initialize
    window.addEventListener('load', function() {
        console.log('Window loaded, initializing filters...');
        
        // Set default filter ke "semua"
        setActiveFilter('semua');
        
        // Tampilkan semua pesan (tidak perlu filter karena sudah tampil)
        const messageCards = document.querySelectorAll('.message-card');
        console.log('Found', messageCards.length, 'message cards');
        
        // Apply event listeners
        applyEventListeners();
        
        // Pastikan semua pesan terlihat di awal
        messageCards.forEach(card => {
            card.style.display = 'block';
        });
        
        console.log('Initialization complete');
    });
    
    // Apply event listeners untuk tombol yang sudah ada
    applyEventListeners();
    
    // Set default active filter jika belum ada
    const activeFilter = document.querySelector('.filter-btn.active');
    if (!activeFilter) {
        setActiveFilter('semua');
    }
    
    console.log('Dashboard initialization complete');
});
</script>
@endpush