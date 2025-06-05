@extends('layouts.app')

@section('title', 'Buat Pesan Baru')

@push('styles')
    <style>
        :root {
            --bs-primary: #1a73e8;
            --bs-danger: #FF5252;
            --bs-success: #27AE60;
            --gradient-primary: linear-gradient(to right, #004AAD, #5DE0E6);
        }

        body {
            background-color: #F5F7FA;
            font-size: 14px;
        }

        .main-content {
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

        .form-control, .form-select {
            font-size: 14px;
            padding: 0.6rem 0.85rem;
        }

        .btn {
            font-size: 14px;
            padding: 0.6rem 1.2rem;
        }

        .badge {
            font-weight: normal;
            font-size: 13px;
        }

        .card {
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
            border-radius: 8px;
        }

        .title-divider {
            border-bottom: 2px solid #dee2e6;
            padding-bottom: 15px;
            margin-bottom: 20px;
        }

        .btn-gradient-primary {
            background: var(--gradient-primary);
            border: none;
            color: white;
            transition: all 0.3s ease;
        }

        .btn-gradient-primary:hover {
            background: linear-gradient(to right, #003c8a, #4bc4c9);
            color: white;
            box-shadow: 0 2px 5px rgba(0,0,0,0.2);
        }
        
        .form-check-label {
            font-size: 14px;
        }
        
        h4 {
            font-size: 22px;
            font-weight: 600;
        }
        
        h6 {
            font-size: 16px;
            font-weight: 500;
        }
        
        .form-label {
            font-size: 14px;
        }
        
        .search-result {
            max-height: 400px;
            overflow-y: auto;
        }
        
        .search-result::-webkit-scrollbar {
            width: 6px;
        }
        
        .search-result::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }
        
        .search-result::-webkit-scrollbar-thumb {
            background: #888;
            border-radius: 10px;
        }
        
        .search-result::-webkit-scrollbar-thumb:hover {
            background: #555;
        }
        
        .no-results {
            padding: 15px;
            text-align: center;
            color: #6c757d;
        }
        
        #search-info {
            font-size: 13px;
            color: #6c757d;
            margin-top: 5px;
        }
        
        .required-field:after {
            content: "*";
            color: red;
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
            
            .card {
                border-radius: 8px;
            }

            .card-body {
                padding: 20px !important;
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

            .search-result {
                max-height: 300px;
            }

            /* Mobile layout adjustments */
            .row {
                flex-direction: column;
            }

            .col-lg-8, .col-lg-4 {
                flex: none;
                width: 100%;
                max-width: 100%;
            }

            .col-lg-4 {
                margin-top: 15px;
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

            .card-body {
                padding: 15px !important;
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

            .badge {
                font-size: 11px;
            }

            .search-result {
                max-height: 250px;
            }

            .form-check-label {
                font-size: 12px;
            }

            #search-info {
                font-size: 11px;
            }

            textarea {
                min-height: 120px !important;
            }
        }

        @media (max-width: 375px) {
            .container {
                padding: 0 6px;
            }
            
            .mobile-sidebar {
                width: 240px;
            }

            .card-body {
                padding: 12px !important;
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
            <a href="{{ route('dosen.pesan.create') }}" class="btn active" style="background: var(--gradient-primary); color: white; padding: 10px 20px; border: none; border-radius: 5px;">
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
                        @foreach($grups as $grupItem)
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

    <div class="container py-4">
        <div class="title-divider">
            <h4 class="mb-0">Buat Pesan Baru</h4>
        </div>

        <a href="{{ route('dosen.dashboard.pesan') }}" class="btn btn-gradient-primary mb-4">
            <i class="fas fa-arrow-left me-2"></i> Kembali
        </a>

        @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form id="formPesan">
            @csrf
            <div class="row">
                <div class="col-lg-8">
                    <div class="card mb-4">
                        <div class="card-body p-4">
                            <div class="mb-4">
                                <label class="form-label required-field">Subjek</label>
                                <select class="form-select" id="subjek" name="subjek" required>
                                    <option value="" disabled selected>Pilih subjek pesan</option>
                                    <option value="Bimbingan KRS">Bimbingan KRS</option>
                                    <option value="Bimbingan KP">Bimbingan KP</option>
                                    <option value="Bimbingan Skripsi">Bimbingan Skripsi</option>
                                    <option value="Bimbingan MBKM">Bimbingan MBKM</option>
                                    <option value="Lainnya">Lainnya</option>
                                </select>
                            </div>
                            
                            <div class="mb-4">
                                <label class="form-label required-field">Prioritas</label>
                                <select class="form-select" id="prioritas" name="prioritas" required>
                                    <option value="" disabled selected>Pilih prioritas</option>
                                    <option value="Penting">Penting</option>
                                    <option value="Umum">Umum</option>
                                </select>
                            </div>
                            
                            <div class="mb-4">
                                <label class="form-label">Lampiran (Link Google Drive)</label>
                                <input type="url" class="form-control" id="lampiran" name="lampiran" placeholder="Masukkan link Google Drive (opsional)">
                            </div>
                            
                            <div class="mb-4">
                                <label class="form-label required-field">Pesan</label>
                                <textarea class="form-control" id="pesanText" name="pesanText" rows="8" placeholder="Tulis pesan Anda di sini..." required></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-4">
                    <div class="card mb-4">
                        <div class="card-body p-4">
                            <div class="mb-4">
                                <label class="form-label required-field">Penerima</label>
                                <div class="input-group mb-2">
                                    <input type="text" id="search_mahasiswa" class="form-control" placeholder="Cari mahasiswa (minimal 3 karakter)...">
                                    <button class="btn btn-outline-secondary" type="button" id="clearSearch">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                                <div id="search-info" class="small text-muted">Ketik minimal 3 karakter untuk mencari</div>

                                <div class="mt-3">
                                    <div class="card border-0">
                                        <div class="card-body p-0 search-result" id="search_results">
                                            <!-- Hasil pencarian akan ditampilkan di sini -->
                                            <div class="no-results">
                                                Ketik nama atau NIM mahasiswa pada form pencarian
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="mt-3">
                                <h6 class="mb-3">Penerima Terpilih:</h6>
                                <div id="selected_members">
                                    <p class="text-muted" id="no_selected">Belum ada penerima yang dipilih</p>
                                    <!-- Daftar penerima yang dipilih akan tampil di sini -->
                                </div>
                            </div>
                            
                            <hr class="my-4">
                            
                            <div class="d-grid">
                                <button type="submit" class="btn btn-gradient-primary px-4" id="kirimPesanBtn">
                                    <i class="fas fa-paper-plane me-2"></i> Kirim Pesan
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
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

    // Original form functionality
    const searchInput = document.getElementById('search_mahasiswa');
    const searchResults = document.getElementById('search_results');
    const selectedMembersContainer = document.getElementById('selected_members');
    const noSelectedMessage = document.getElementById('no_selected');
    const clearSearchBtn = document.getElementById('clearSearch');
    const searchInfo = document.getElementById('search-info');
    const formPesan = document.getElementById('formPesan');
    const kirimPesanBtn = document.getElementById('kirimPesanBtn');
    
    // Inisialisasi modal
    const loadingModal = new bootstrap.Modal(document.getElementById('loadingModal'));
    const statusModal = new bootstrap.Modal(document.getElementById('statusModal'));
    
    // Data mahasiswa dari database
    const mahasiswaData = [
        @foreach($mahasiswa as $mhs)
        {
            nim: '{{ $mhs->nim }}',
            nama: '{{ $mhs->nama }}'
        },
        @endforeach
    ];
    
    // Simpan mahasiswa yang dipilih
    const selectedMahasiswa = new Set();
    
    // Fungsi untuk memperbarui tampilan penerima yang dipilih
    function updateSelectedMembers() {
        if (selectedMahasiswa.size === 0) {
            noSelectedMessage.style.display = 'block';
        } else {
            noSelectedMessage.style.display = 'none';
        }
        
        // Hapus badge yang sudah tidak dipilih
        document.querySelectorAll('#selected_members .badge').forEach(badge => {
            const nim = badge.getAttribute('data-nim');
            if (!selectedMahasiswa.has(nim)) {
                badge.remove();
            }
        });
        
        // Tambahkan badge untuk mahasiswa yang baru dipilih
        selectedMahasiswa.forEach(nim => {
            if (!document.querySelector(`#selected_members .badge[data-nim="${nim}"]`)) {
                const mahasiswa = mahasiswaData.find(m => m.nim === nim);
                if (mahasiswa) {
                    const badge = document.createElement('span');
                    badge.className = 'badge bg-light text-dark border p-2 me-2 mb-2';
                    badge.setAttribute('data-nim', nim);
                    badge.innerHTML = `
                        ${mahasiswa.nama} - ${mahasiswa.nim}
                        <button type="button" class="btn-close ms-2" style="font-size: 8px;" data-nim="${nim}"></button>
                        <input type="hidden" name="anggota[]" value="${nim}">
                    `;
                    selectedMembersContainer.appendChild(badge);
                }
            }
        });
        
        // Tambahkan event listener untuk tombol close di badges
        document.querySelectorAll('#selected_members .btn-close').forEach(btn => {
            btn.addEventListener('click', function() {
                const nim = this.getAttribute('data-nim');
                selectedMahasiswa.delete(nim);
                updateSelectedMembers();
                
                // Refresh hasil pencarian agar mahasiswa yang dihapus muncul kembali
                const currentSearchTerm = searchInput.value.trim();
                if (currentSearchTerm.length >= 3) {
                    showSearchResults(currentSearchTerm);
                }
            });
        });
    }
    
    // Fungsi untuk menampilkan hasil pencarian
    function showSearchResults(searchTerm) {
        if (searchTerm.length < 3) {
            searchResults.innerHTML = `
                <div class="no-results">
                    Ketik minimal 3 karakter untuk mencari mahasiswa
                </div>
            `;
            return;
        }
        
        searchTerm = searchTerm.toLowerCase();
        
        // Filter mahasiswa berdasarkan kata kunci DAN yang belum dipilih
        const filteredMahasiswa = mahasiswaData.filter(mhs => 
            (mhs.nama.toLowerCase().includes(searchTerm) || 
             mhs.nim.toLowerCase().includes(searchTerm)) &&
            !selectedMahasiswa.has(mhs.nim) // Hanya tampilkan yang belum dipilih
        );
        
        if (filteredMahasiswa.length === 0) {
            // Cek apakah tidak ada hasil karena sudah dipilih semua atau memang tidak ada
            const allFilteredMahasiswa = mahasiswaData.filter(mhs => 
                mhs.nama.toLowerCase().includes(searchTerm) || 
                mhs.nim.toLowerCase().includes(searchTerm)
            );
            
            if (allFilteredMahasiswa.length > 0) {
                searchResults.innerHTML = `
                    <div class="no-results">
                        Semua mahasiswa dengan kata kunci "${searchTerm}" sudah dipilih
                    </div>
                `;
            } else {
                searchResults.innerHTML = `
                    <div class="no-results">
                        Tidak ada mahasiswa yang sesuai dengan kata kunci "${searchTerm}"
                    </div>
                `;
            }
            return;
        }
        
        // Tampilkan hasil pencarian (hanya yang belum dipilih)
        let html = '<div class="list-group">';
        
        filteredMahasiswa.forEach(mhs => {
            html += `
                <div class="list-group-item" id="search-item-${mhs.nim}">
                    <div class="form-check">
                        <input class="form-check-input mahasiswa-checkbox" type="checkbox" value="${mhs.nim}" id="mhs${mhs.nim}">
                        <label class="form-check-label" for="mhs${mhs.nim}">
                            ${mhs.nama} - ${mhs.nim}
                        </label>
                    </div>
                </div>
            `;
        });
        
        html += '</div>';
        searchResults.innerHTML = html;
        
        // Event listener untuk checkbox hasil pencarian
        document.querySelectorAll('.mahasiswa-checkbox').forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                const nim = this.value;
                
                if (this.checked) {
                    // Tambahkan ke selected
                    selectedMahasiswa.add(nim);
                    
                    // Hilangkan dari hasil pencarian dengan animasi
                    const searchItem = document.getElementById(`search-item-${nim}`);
                    if (searchItem) {
                        searchItem.style.transition = 'opacity 0.3s ease, height 0.3s ease';
                        searchItem.style.opacity = '0';
                        searchItem.style.height = '0';
                        searchItem.style.padding = '0';
                        searchItem.style.margin = '0';
                        
                        setTimeout(() => {
                            searchItem.remove();
                            
                            // Cek apakah masih ada hasil pencarian
                            const remainingItems = document.querySelectorAll('.list-group-item');
                            if (remainingItems.length === 0) {
                                // Refresh hasil pencarian untuk menampilkan pesan yang tepat
                                showSearchResults(searchInput.value.trim());
                            }
                        }, 300);
                    }
                    
                    // Update tampilan penerima terpilih
                    updateSelectedMembers();
                }
            });
        });
    }
    
    // Event listener untuk input pencarian
    searchInput.addEventListener('input', function() {
        const searchTerm = this.value.trim();
        
        if (searchTerm.length >= 3) {
            searchInfo.textContent = `Menampilkan hasil pencarian untuk: "${searchTerm}"`;
        } else {
            searchInfo.textContent = 'Ketik minimal 3 karakter untuk mencari mahasiswa';
        }
        
        showSearchResults(searchTerm);
    });
    
    // Event listener untuk tombol clear search
    clearSearchBtn.addEventListener('click', function() {
        searchInput.value = '';
        searchInfo.textContent = 'Ketik minimal 3 karakter untuk mencari mahasiswa';
        searchResults.innerHTML = `
            <div class="no-results">
                Ketik nama atau NIM mahasiswa pada form pencarian
            </div>
        `;
    });
    
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
                window.location.href = '{{ route("dosen.dashboard.pesan") }}';
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
    
    // Form Submit Handler
    formPesan.addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Validasi form
        if (selectedMahasiswa.size === 0) {
            showStatusModal(false, 'Pilih minimal satu penerima');
            return;
        }
        
        // Disable tombol kirim untuk mencegah multiple submit
        kirimPesanBtn.disabled = true;
        
        // Tampilkan modal loading
        loadingModal.show();
        
        // Kumpulkan data dari form
        const formData = new FormData(this);
        
        // Delay simulasi untuk efek loading (dapat dihapus di produksi)
        setTimeout(() => {
            // Kirim ke server
            fetch('{{ route("dosen.pesan.store") }}', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showStatusModal(true, data.message || 'Pesan berhasil dikirim kepada penerima yang dipilih.');
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
    
    // Inisialisasi tampilan penerima terpilih
    updateSelectedMembers();
    
    console.log('Mobile Buat Pesan Baru initialized successfully');
});
</script>
@endpush