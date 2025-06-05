@extends('layouts.app')

@section('title', 'Daftar Grup')

@push('styles')
    <style>
        :root {
            --bs-primary: #1a73e8;
            --bs-danger: #FF5252;
            --bs-success: #27AE60;
            --primary-gradient: linear-gradient(to right, #004AAD, #5DE0E6);
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
        
        .card {
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
            margin-bottom: 20px;
        }
        
        /* BOLD TITLE WITH GRADIENT - FIXED VERSION */
        .title-divider {
            border-bottom: 2px solid #dee2e6;
            padding-bottom: 15px;
            margin-bottom: 20px;
        }
        
        .title-divider h4 {
            font-weight: 700;
            font-size: 1.5rem;
            margin: 0;
            display: inline-block;
            background: var(--primary-gradient);
            background: -webkit-linear-gradient(to right, #004AAD, #5DE0E6);
            background: linear-gradient(to right, #004AAD, #5DE0E6);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            color: transparent;
        }
        
        .btn-gradient-primary {
            background: var(--primary-gradient);
            border: none;
            color: white;
            transition: all 0.3s ease;
        }

        .btn-gradient-primary:hover {
            background: linear-gradient(to right, #003c8a, #4bc4c9);
            color: white;
            box-shadow: 0 2px 5px rgba(0,0,0,0.2);
        }
        
        .grup-card {
            transition: all 0.3s ease;
            position: relative;
        }
        
        .grup-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        
        /* Style untuk badge notifikasi */
        .notification-badge {
            position: absolute;
            top: 10px;
            right: 10px;
            font-size: 12px;
            padding: 3px 8px;
            z-index: 2;
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

            .title-divider h4 {
                font-size: 1.3rem;
            }

            .grup-card:hover {
                transform: none; /* Disable hover transform on mobile */
            }

            .card-body {
                padding: 15px;
            }

            .notification-badge {
                font-size: 11px;
                padding: 2px 6px;
            }

            /* Make grid responsive */
            .col-md-4 {
                flex: 0 0 100%;
                max-width: 100%;
                margin-bottom: 15px;
            }

            /* Adjust grup card height for mobile */
            .grup-card .card-body {
                min-height: 120px;
            }

            /* Member avatars smaller on mobile */
            .member-avatar {
                width: 30px !important;
                height: 30px !important;
                font-size: 12px;
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

            .title-divider h4 {
                font-size: 1.2rem;
            }

            .btn {
                font-size: 12px;
                padding: 8px 15px;
            }

            .card-body {
                padding: 12px;
            }

            .grup-card .card-body {
                min-height: 100px;
            }

            .member-avatar {
                width: 25px !important;
                height: 25px !important;
                font-size: 10px;
            }

            .notification-badge {
                font-size: 10px;
                padding: 2px 5px;
            }

            .badge {
                font-size: 10px;
                padding: 3px 6px;
            }

            h5 {
                font-size: 14px;
            }

            .small {
                font-size: 11px !important;
            }
        }

        @media (max-width: 375px) {
            .container {
                padding: 0 6px;
            }
            
            .mobile-sidebar {
                width: 240px;
            }

            .title-divider h4 {
                font-size: 1.1rem;
            }

            .btn {
                font-size: 11px;
                padding: 6px 12px;
            }

            .card-body {
                padding: 10px;
            }

            h5 {
                font-size: 13px;
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
            <a href="{{ route('dosen.pesan.create') }}" class="btn" style="background: var(--primary-gradient); color: white; padding: 10px 20px; border: none; border-radius: 5px;">
                <i class="fas fa-plus me-2"></i> Pesan Baru
            </a>
        </div>
        <div class="sidebar-menu">
            <div class="nav flex-column">
                <a href="{{ route('dosen.dashboard.pesan') }}" class="nav-link">
                    <i class="fas fa-home me-2"></i>Daftar Pesan
                </a>
                <a href="#" class="nav-link menu-item active" id="mobileGrupDropdownToggle">
                    <div class="d-flex justify-content-between align-items-center">
                        <span><i class="fas fa-users me-2"></i>Daftar Grup</span>
                        <i class="fas fa-chevron-up" id="mobileGrupDropdownIcon"></i>
                    </div>
                </a>
                <div class="collapse show komunikasi-submenu" id="mobileKomunikasiSubmenu">
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
            <h4 class="mb-0">Daftar Grup</h4>
        </div>

        <a href="{{ route('back') }}" class="btn btn-gradient-primary mb-4">
            <i class="fas fa-arrow-left me-2"></i> Kembali
        </a>
        
        <div class="row">
            <div class="col-md-4 mb-4">
                <a href="{{ route('dosen.grup.create') }}" class="text-decoration-none">
                    <div class="card h-100 border-0 grup-card">
                        <div class="card-body d-flex flex-column align-items-center justify-content-center" style="height: 200px;">
                            <div class="bg-light rounded-circle p-3 mb-3">
                                <i class="fas fa-plus text-primary fa-2x"></i>
                            </div>
                            <h5 class="text-center">Buat Grup Baru</h5>
                        </div>
                    </div>
                </a>
            </div>
            
            @foreach($grups as $grup)
            <div class="col-md-4 mb-4">
                <a href="{{ route('dosen.grup.show', $grup->id) }}" class="text-decoration-none">
                    <div class="card h-100 border-0 grup-card">
                        <!-- Badge notifikasi -->
                        @if($grup->unreadCount > 0)
                        <span class="badge bg-danger notification-badge">{{ $grup->unreadCount }} baru</span>
                        @endif
                        
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h5 class="mb-0">{{ $grup->nama_grup }}</h5>
                                <span class="badge bg-primary rounded-pill">{{ $grup->mahasiswa->count() }} anggota</span>
                            </div>
                            <p class="text-muted small mb-3">Dibuat: {{ $grup->created_at->format('d M Y') }}</p>
                            
                            <div class="d-flex flex-wrap">
                                @foreach($grup->mahasiswa->take(5) as $anggota)
                                <div class="me-2 mb-2" title="{{ $anggota->nama }}">
                                    <div class="bg-light rounded-circle d-flex align-items-center justify-content-center member-avatar" style="width: 35px; height: 35px;">
                                        <span>{{ substr($anggota->nama, 0, 1) }}</span>
                                    </div>
                                </div>
                                @endforeach
                                
                                @if($grup->mahasiswa->count() > 5)
                                <div class="me-2 mb-2">
                                    <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center member-avatar" style="width: 35px; height: 35px;">
                                        <span>+{{ $grup->mahasiswa->count() - 5 }}</span>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            @endforeach
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
    
    console.log('Mobile Daftar Grup initialized successfully');
});
</script>
@endpush