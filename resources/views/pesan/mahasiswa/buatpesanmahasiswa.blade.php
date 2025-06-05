@extends('layouts.app')

@section('title', 'Buat Pesan Mahasiswa')
@push('styles')
    <style>
        :root {
            --bs-primary: #1a73e8;
            --bs-danger: #FF5252;
            --bs-success: #27AE60;
            --form-font-size: 13px; /* Ukuran font konsisten untuk semua elemen form */
            --gradient-primary: linear-gradient(to right, #004AAD, #5DE0E6);
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
            background: var(--gradient-primary);
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
        
        .form-header {
            padding-bottom: 15px;
            margin-bottom: 25px;
            border-bottom: 1px solid #dee2e6;
            font-weight: 700; /* Membuat tulisan lebih bold */
            background: var(--gradient-primary); /* Gradasi seperti button */
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            display: inline-block; /* Penting untuk gradient text */
        }
        
        .btn-gradient {
            background: var(--gradient-primary);
            color: white;
            border: none;
        }
        
        .btn-gradient:hover {
            background: linear-gradient(to right, #1558b7, #1558b7);
            color: white;
        }
        
        .btn-kembali {
            background: var(--gradient-primary);
            color: white;
            border: none;
            display: flex;
            align-items: center;
            padding: 8px 15px;
            border-radius: 5px;
            text-decoration: none;
            font-weight: 500;
            margin-bottom: 20px;
            width: fit-content;
        }
        
        .btn-kembali:hover {
            background: linear-gradient(to right, #1558b7, #1558b7);
            color: white;
        }
        
        .form-label {
            font-weight: 500;
        }
        
        .required-field:after {
            content: "*";
            color: red;
        }
        
        /* Menyamakan semua input form */
        .form-control, 
        .form-select, 
        .custom-select select, 
        .dosen-dropdown-toggle,
        .dosen-dropdown-search input,
        .dosen-dropdown-item,
        .dosen-dropdown-placeholder,
        .selected-dosen,
        .no-results {
            font-size: var(--form-font-size) !important;
        }
        
        /* Pastikan textarea juga memiliki font yang sama */
        textarea.form-control {
            font-size: var(--form-font-size) !important;
            font-family: inherit;
        }
        
        .custom-select {
            position: relative;
            width: 100%;
        }

        .custom-select select {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            appearance: none;
            -webkit-appearance: none;
            -moz-appearance: none;
            background: white;
            cursor: pointer;
        }

        .custom-select::after {
            content: '\f107';
            font-family: 'Font Awesome 5 Free';
            font-weight: 900;
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            pointer-events: none;
        }

        .dosen-dropdown {
            position: relative;
            width: 100%;
        }
        
        .dosen-dropdown-toggle {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            background: white;
            cursor: pointer;
            text-align: left;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .dosen-dropdown-menu {
            position: absolute;
            left: 0;
            top: 100%;
            width: 100%;
            max-height: 250px;
            overflow-y: auto;
            border: 1px solid #ddd;
            border-radius: 4px;
            background: white;
            z-index: 1000;
            display: none;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        
        .dosen-dropdown-item {
            padding: 8px 12px;
            cursor: pointer;
            border-bottom: 1px solid #f5f5f5;
        }
        
        .dosen-dropdown-item:hover {
            background-color: #f8f9fa;
        }
        
        .dosen-dropdown-search {
            padding: 8px;
            border-bottom: 1px solid #ddd;
            position: sticky;
            top: 0;
            background: white;
            z-index: 1001;
        }
        
        .dosen-dropdown-search input {
            width: 100%;
            padding: 6px 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        
        .selected-dosen {
            display: flex;
            align-items: center;
        }
        
        .selected-dosen-avatar {
            width: 20px;
            height: 20px;
            border-radius: 50%;
            background-color: #e9ecef;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 8px;
            font-size: 10px;
        }
        
        .dosen-dropdown-placeholder {
            color: #6c757d;
        }
        
        .no-results {
            padding: 12px;
            text-align: center;
            color: #6c757d;
            font-style: italic;
            display: none;
        }
        
        .dosen-jabatan {
            color: #6c757d;
            font-size: var(--form-font-size) !important;
        }
        
        /* Khusus untuk Bootstrap form-control */
        input.form-control, 
        textarea.form-control {
            font-size: var(--form-font-size) !important;
        }
        
        /* Override untuk Bootstrap placeholder */
        .form-control::placeholder,
        .form-select::placeholder,
        input::placeholder {
            font-size: var(--form-font-size) !important;
        }
        
        /* Heading dengan gradasi */
        .gradient-heading {
            font-weight: 700;
            background: var(--gradient-primary);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            display: inline-block;
            margin-bottom: 5px;
        }
        
        /* Tambahkan styling untuk dropdown header */
        .dropdown-header {
            padding: 8px 12px;
            background-color: #f3f4f6;
            font-weight: 600;
            color: #495057;
            font-size: var(--form-font-size) !important;
        }
        
        /* Badge untuk menampilkan role penerima */
        .role-badge {
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 10px;
            font-weight: 500;
            margin-left: 8px;
        }
        
        .role-badge-kaprodi {
            background-color: #17a2b8;
            color: white;
        }
        
        .role-badge-dosen {
            background-color: #6c757d;
            color: white;
        }
        
        /* Style untuk modal loading */
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
        
        @keyframes spin {
            to { transform: rotate(360deg); }
        }
        
        .modal-loading .loading-text {
            margin-top: 15px;
            font-size: 16px;
            font-weight: 500;
            background: var(--gradient-primary);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        
        /* Style untuk modal sukses/error */
        .modal-status .modal-header {
            border-bottom: none;
            border-top-left-radius: 10px;
            border-top-right-radius: 10px;
            padding: 1.5rem 1.5rem 0.5rem;
        }
        
        .modal-status .modal-header.success {
            background: var(--gradient-primary);
            color: white;
        }
        
        .modal-status .modal-header.error {
            background: linear-gradient(to right, #D32F2F, #FF5252);
            color: white;
        }
        
        .modal-status .modal-content {
            border: none;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        
        .modal-status .status-icon {
            font-size: 3rem;
            margin-bottom: 1rem;
        }
        
        .modal-status .status-icon.text-success i {
            background: var(--gradient-primary);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        
        .modal-status .status-title {
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
        }
        
        .modal-status .status-message {
            font-size: 1rem;
            color: #6c757d;
        }
        
        .modal-status .modal-body {
            padding: 1.5rem;
            text-align: center;
        }
        
        .modal-status .modal-footer {
            border-top: none;
            justify-content: center;
            padding-bottom: 1.5rem;
        }
        
        .modal-status .btn-status {
            padding: 0.5rem 2rem;
            border-radius: 5px;
            font-weight: 500;
        }
        
        .modal-status .btn-success {
            background: var(--gradient-primary);
            border: none;
            color: white;
            transition: all 0.3s ease;
        }
        
        .modal-status .btn-success:hover {
            opacity: 0.9;
            box-shadow: 0 2px 8px rgba(0, 74, 173, 0.3);
        }
        
        .modal-status .btn-error {
            background: linear-gradient(to right, #D32F2F, #FF5252);
            border: none;
            color: white;
        }

        /* Mobile Responsive Styles */
        @media (max-width: 991.98px) {
            .main-content {
                padding-top: 15px;
            }
        }

        @media (max-width: 768px) {
            .container {
                padding: 0 10px;
            }
            
            .main-content {
                padding-top: 10px;
                padding-bottom: 15px;
            }

            h4 {
                font-size: 20px;
            }

            .form-control, .form-select {
                font-size: 13px;
                padding: 0.5rem 0.75rem;
            }

            .btn {
                font-size: 13px;
                padding: 0.5rem 1rem;
            }

            .dosen-dropdown-menu {
                max-height: 200px;
            }
        }

        @media (max-width: 576px) {
            body {
                font-size: 12px;
            }
            
            .container {
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

            h4 {
                font-size: 18px;
            }

            .form-control, .form-select {
                font-size: 12px;
                padding: 0.4rem 0.6rem;
            }

            .btn {
                font-size: 12px;
                padding: 0.4rem 0.8rem;
            }

            .form-label {
                font-size: 13px;
            }

            textarea {
                min-height: 120px !important;
            }

            .dosen-dropdown-menu {
                max-height: 180px;
            }

            .dosen-dropdown-item {
                padding: 6px 10px;
            }

            .role-badge {
                font-size: 9px;
                padding: 2px 6px;
            }
        }

        @media (max-width: 375px) {
            .container {
                padding: 0 6px;
            }
            
            .mobile-sidebar {
                width: 240px;
            }

            h4 {
                font-size: 16px;
            }

            .form-control, .form-select {
                font-size: 11px;
                padding: 0.3rem 0.5rem;
            }

            .btn {
                font-size: 11px;
                padding: 0.3rem 0.6rem;
            }

            textarea {
                min-height: 100px !important;
            }
        }

        /* Dark mode support */
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
            <a href="{{ route('mahasiswa.pesan.create') }}" class="btn active" style="background: var(--gradient-primary); color: white; padding: 10px 20px; border: none; border-radius: 5px;">
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

    <div class="container">
        <div class="row">
            <div class="col-12">
                <h4 class="gradient-heading">BUAT PESAN BARU</h4>
                <hr class="mb-4">
                
                <a href="{{ route('mahasiswa.dashboard.pesan') }}" class="btn-kembali">
                    <i class="fas fa-arrow-left me-2"></i> Kembali
                </a>
                
                <form id="formPesan">
                    @csrf
                    <div class="mb-4">
                        <label for="subjek" class="form-label required-field fs-6">Subjek</label>
                        <div class="custom-select">
                            <select class="form-select text-muted" id="subjek" name="subjek" required>
                                <option value="" disabled selected>Masukkan subjek</option>
                                <option value="Bimbingan KRS">Bimbingan KRS</option>
                                <option value="Bimbingan KP">Bimbingan KP</option>
                                <option value="Bimbingan Skripsi">Bimbingan Skripsi</option>
                                <option value="Bimbingan MBKM">Bimbingan MBKM</option>
                                <option value="Lainnya">Lainnya</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <label for="penerima" class="form-label required-field fs-6">Penerima</label>
                        <div class="dosen-dropdown">
                            <div class="dosen-dropdown-toggle" id="dosenDropdownToggle">
                                <span class="dosen-dropdown-placeholder">Pilih dosen</span>
                                <i class="fas fa-chevron-down"></i>
                            </div>
                            <div class="dosen-dropdown-menu" id="dosenDropdownMenu">
                                <div class="dosen-dropdown-search">
                                    <input type="text" id="dosenSearchInput" placeholder="Cari dosen..." autocomplete="off">
                                </div>
                                <div id="dosenDropdownItems">
                                    <!-- Daftar dosen akan muncul di sini berdasarkan pencarian -->
                                </div>
                                <div class="no-results" id="noResults">
                                    Tidak ada dosen ditemukan
                                </div>
                            </div>
                        </div>
                        
                        <!-- Hidden fields -->
                        <input type="hidden" id="selectedDosenId" name="dosenId">
                        <input type="hidden" id="selectedDosenNama" name="dosenNama">
                        <input type="hidden" id="selectedDosenJabatan" name="dosenJabatan">
                        <input type="hidden" id="selectedDosenRole" name="penerima_role">
                    </div>
                    
                    <div class="mb-4">
                        <label for="prioritas" class="form-label required-field fs-6">Prioritas</label>
                        <div class="custom-select">
                            <select class="form-select text-muted" id="prioritas" name="prioritas" required>
                                <option value="" disabled selected>Masukkan prioritas</option>
                                <option value="Penting">Penting</option>
                                <option value="Umum">Umum</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <label for="lampiran" class="form-label fs-6">Lampiran (Link Google Drive)</label>
                        <input type="url" class="form-control" id="lampiran" name="lampiran" placeholder="Masukkan link Google Drive">
                    </div>
                    
                    <div class="mb-4">
                        <label for="pesanText" class="form-label required-field fs-6">Pesan</label>
                        <textarea class="form-control" id="pesanText" name="pesanText" rows="8" placeholder="Tulis pesan Anda di sini..." required></textarea>
                    </div>
                    
                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-gradient px-4 py-2" id="kirimPesanBtn">
                            <i class="fas fa-paper-plane me-2"></i> Kirim
                        </button>
                    </div>
                </form>
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
                <div class="loading-text">Mengirim pesan...</div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Status (Success/Error) -->
<div class="modal fade modal-status" id="statusModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header" id="statusModalHeader">
                <!-- Header akan diisi dinamis -->
            </div>
            <div class="modal-body" id="statusModalBody">
                <!-- Body akan diisi dinamis -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-status" id="statusModalBtn"></button>
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
    
    // Enhanced keyboard shortcuts
    document.addEventListener('keydown', function(e) {
        // Esc untuk tutup sidebar mobile
        if (e.key === 'Escape') {
            // Close mobile sidebar if open
            if (mobileSidebar && mobileSidebar.classList.contains('show')) {
                closeMobileSidebar();
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

    // Original form functionality - Perbaikan JavaScript untuk menampilkan daftar dosen
    // Mengambil data dosen dari database
    let dataDosen = [];
    
    // Inisialisasi modal
    const loadingModal = new bootstrap.Modal(document.getElementById('loadingModal'));
    const statusModal = new bootstrap.Modal(document.getElementById('statusModal'));
    
    // Fungsi untuk menampilkan modal status
    function showStatusModal(success, message) {
        const statusModalHeader = document.getElementById('statusModalHeader');
        const statusModalBody = document.getElementById('statusModalBody');
        const statusModalBtn = document.getElementById('statusModalBtn');
        
        if (success) {
            statusModalHeader.className = 'modal-header success';
            statusModalHeader.innerHTML = '<h5 class="modal-title text-white"><i class="fas fa-check-circle me-2"></i>Berhasil</h5>';
            statusModalBody.innerHTML = `
                <div class="status-icon text-success">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="status-title">Pesan Terkirim!</div>
                <div class="status-message">${message}</div>
            `;
            statusModalBtn.className = 'btn btn-success btn-status';
            statusModalBtn.innerHTML = '<i class="fas fa-check me-2"></i>Lihat Pesan';
            
            // Redirect saat tombol diklik
            statusModalBtn.onclick = function() {
                window.location.href = '{{ route("mahasiswa.dashboard.pesan") }}';
            };
        } else {
            statusModalHeader.className = 'modal-header error';
            statusModalHeader.innerHTML = '<h5 class="modal-title text-white"><i class="fas fa-exclamation-circle me-2"></i>Gagal</h5>';
            statusModalBody.innerHTML = `
                <div class="status-icon text-danger">
                    <i class="fas fa-exclamation-circle"></i>
                </div>
                <div class="status-title">Pengiriman Gagal</div>
                <div class="status-message">${message}</div>
            `;
            statusModalBtn.className = 'btn btn-error btn-status';
            statusModalBtn.innerHTML = '<i class="fas fa-redo me-2"></i>Coba Lagi';
            
            // Tutup modal saat tombol diklik
            statusModalBtn.onclick = function() {
                statusModal.hide();
            };
        }
        
        loadingModal.hide();
        statusModal.show();
    }
    
    // Menggunakan Blade untuk mengisi data dosen dengan peran yang benar
    @foreach($dosen as $d)
        dataDosen.push({ 
            id: '{{ $d['nip'] }}', 
            nama: '{{ $d['nama'] }}',
            role: '{{ $d['role'] }}',
            displayName: '{{ $d['nama'] }}{{ $d['role'] == "kaprodi" ? " (Sebagai Kaprodi)" : "" }}',
            jabatan: '{{ $d['jabatan_fungsional'] ?? "Dosen" }}'
        });
    @endforeach
    
    // Log untuk debugging
    console.log('Data dosen:', dataDosen);
    
    // Variabel untuk menyimpan data dosen yang dipilih
    let selectedDosen = null;
    
    // Get DOM elements
    const dosenDropdownToggle = document.getElementById('dosenDropdownToggle');
    const dosenDropdownMenu = document.getElementById('dosenDropdownMenu');
    const dosenSearchInput = document.getElementById('dosenSearchInput');
    const dosenDropdownItems = document.getElementById('dosenDropdownItems');
    const noResults = document.getElementById('noResults');
    const kirimPesanBtn = document.getElementById('kirimPesanBtn');
    
    // Toggle dropdown
    dosenDropdownToggle.addEventListener('click', function() {
        if (dosenDropdownMenu.style.display === 'block') {
            dosenDropdownMenu.style.display = 'none';
        } else {
            dosenDropdownMenu.style.display = 'block';
            dosenSearchInput.focus();
            
            // Bersihkan dropdown items dulu
            dosenDropdownItems.innerHTML = '';
            
            // Tampilkan kolom search, tapi tidak langsung tampilkan dosen
            dosenSearchInput.value = '';
            noResults.style.display = 'none';
        }
    });
    
    // Render dosen list dengan info jabatan yang ditambahkan
    function renderDosenList(dosenList) {
        dosenDropdownItems.innerHTML = '';
        
        if (dosenList.length > 0) {
            noResults.style.display = 'none';
            
            // Group dosen vs kaprodi
            const dosenRegular = dosenList.filter(d => d.role === 'dosen');
            const dosenKaprodi = dosenList.filter(d => d.role === 'kaprodi');
            
            // Tambahkan header untuk dosen
            if (dosenRegular.length > 0) {
                const header = document.createElement('div');
                header.className = 'dropdown-header';
                header.textContent = 'Dosen';
                dosenDropdownItems.appendChild(header);
                
                dosenRegular.forEach(dosen => {
                    appendDosenItem(dosen);
                });
            }
            
            // Tambahkan header untuk kaprodi
            if (dosenKaprodi.length > 0) {
                const header = document.createElement('div');
                header.className = 'dropdown-header';
                header.textContent = 'Kaprodi';
                dosenDropdownItems.appendChild(header);
                
                dosenKaprodi.forEach(dosen => {
                    appendDosenItem(dosen);
                });
            }
        } else {
            noResults.style.display = 'block';
        }
    }
    
    function appendDosenItem(dosen) {
        const item = document.createElement('div');
        item.className = 'dosen-dropdown-item';
        item.innerHTML = `
            <div class="d-flex align-items-center">
                <div class="flex-grow-1">
                    <div>${dosen.nama}</div>
                </div>
                <span class="role-badge ${dosen.role === 'kaprodi' ? 'role-badge-kaprodi' : 'role-badge-dosen'}">
                    ${dosen.role === 'kaprodi' ? 'Kaprodi' : 'Dosen'}
                </span>
            </div>
        `;
        
        item.addEventListener('click', function() {
            pilihDosen(dosen);
        });
        
        dosenDropdownItems.appendChild(item);
    }
    
    // Fungsi untuk memilih dosen
    function pilihDosen(dosen) {
        selectedDosen = dosen;
        
        // Set nilai ke hidden fields
        document.getElementById('selectedDosenId').value = dosen.id;
        document.getElementById('selectedDosenNama').value = dosen.nama;
        document.getElementById('selectedDosenJabatan').value = dosen.jabatan;
        document.getElementById('selectedDosenRole').value = dosen.role;
        
        // Update dropdown toggle text
        dosenDropdownToggle.innerHTML = `
            <div class="selected-dosen">
                <div class="selected-dosen-avatar">
                    <i class="fas fa-user" style="font-size: 8px;"></i>
                </div>
                <span>${dosen.nama}${dosen.role === 'kaprodi' ? ' (Sebagai Kaprodi)' : ''}</span>
                <span class="role-badge ${dosen.role === 'kaprodi' ? 'role-badge-kaprodi' : 'role-badge-dosen'}">
                    ${dosen.role === 'kaprodi' ? 'Kaprodi' : 'Dosen'}
                </span>
            </div>
            <i class="fas fa-chevron-down"></i>
        `;
        
        // Hide dropdown
        dosenDropdownMenu.style.display = 'none';
    }
    
    // Search functionality
    dosenSearchInput.addEventListener('input', function() {
        const keyword = this.value.toLowerCase().trim();
        
        if (keyword === '') {
            // Jika search dikosongkan, hilangkan semua hasil
            dosenDropdownItems.innerHTML = '';
            noResults.style.display = 'none';
            return;
        }
        
        // Filter dosen berdasarkan keyword
        const filteredDosen = dataDosen.filter(dosen => {
            return dosen.nama.toLowerCase().includes(keyword) || 
                dosen.jabatan.toLowerCase().includes(keyword);
        });
        
        renderDosenList(filteredDosen);
    });
    
    // Close dropdown when clicking outside
    document.addEventListener('click', function(e) {
        if (!dosenDropdownToggle.contains(e.target) && !dosenDropdownMenu.contains(e.target)) {
            dosenDropdownMenu.style.display = 'none';
        }
    });
    
    // Event listener untuk form submission
    document.getElementById('formPesan').addEventListener('submit', function(e) {
        e.preventDefault();
        
        if (!selectedDosen) {
            showStatusModal(false, 'Pilih penerima pesan terlebih dahulu');
            return false;
        }
        
        // Disable tombol kirim untuk mencegah multiple submit
        kirimPesanBtn.disabled = true;
        
        // Tampilkan modal loading
        loadingModal.show();
        
        // Kumpulkan data dari form
        const formData = new FormData(this);
        
        // Delay simulasi untuk efek loading (dapat dihapus di produksi)
        setTimeout(() => {
            // Kirim data via AJAX
            fetch('{{ route("mahasiswa.pesan.store") }}', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    showStatusModal(true, 'Pesan berhasil dikirim kepada ' + selectedDosen.nama);
                    
                    // Reset form akan dilakukan saat user mengklik tombol di modal
                } else {
                    showStatusModal(false, data.message || 'Gagal mengirim pesan. Silakan coba lagi.');
                    kirimPesanBtn.disabled = false;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showStatusModal(false, 'Terjadi kesalahan saat mengirim pesan. Silakan coba lagi.');
                kirimPesanBtn.disabled = false;
            });
        }, 1200); // Simulasi delay 1.2 detik untuk efek loading (dapat disesuaikan)
    });
    
    console.log('Mobile Buat Pesan Mahasiswa initialized successfully');
});
</script>
@endpush