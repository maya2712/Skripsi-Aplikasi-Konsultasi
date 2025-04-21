@extends('layouts.app')

@section('title', 'Dashboard Pesan Mahasiswa')

@push('styles')
<style>
    :root {
        --bs-primary: #1a73e8;
        --bs-danger: #FF5252;
        --bs-success: #27AE60;
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
</style>
@endpush

@section('content')
<div class="main-content">
    <div class="custom-container">
        <div class="row g-4">
            <div class="col-md-3">
                <div class="sidebar">
                    <div class="sidebar-buttons">
                        <a href="{{ url('/buatpesanmahasiswa') }}" class="btn" style="background: linear-gradient(to right, #004AAD, #5DE0E6); color: white; padding: 10px 20px; border: none; border-radius: 5px;">
                            <i class="fas fa-plus me-2"></i> Pesan Baru
                        </a>
                    </div>                                                    
                    
                    <div class="sidebar-menu">
                        <div class="nav flex-column">
                            <a href="#" class="nav-link active">
                                <i class="fas fa-home me-2"></i>Daftar Pesan
                            </a>
                            <a href="#" class="nav-link menu-item" id="grupDropdownToggle">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span><i class="fas fa-users me-2"></i>Daftar Grup</span>
                                    <i class="fas fa-chevron-down" id="grupDropdownIcon"></i>
                                </div>
                            </a>
                            <div class="collapse komunikasi-submenu" id="komunikasiSubmenu">
                                <!-- Mahasiswa hanya bisa melihat daftar grup, tidak bisa membuat grup baru -->
                                <a href="#" class="nav-link menu-item d-flex justify-content-between align-items-center">
                                    Bimbingan Skripsi
                                    <span class="badge bg-danger rounded-pill">3</span>
                                </a>
                                <a href="#" class="nav-link menu-item d-flex justify-content-between align-items-center">
                                    Kerja Praktek
                                    <span class="badge bg-danger rounded-pill">1</span>
                                </a>
                                <a href="#" class="nav-link menu-item d-flex justify-content-between align-items-center">
                                    Pembimbing Akademik
                                    <span class="badge bg-danger rounded-pill">4</span>
                                </a>
                            </div>
                            <a href="{{ url('/riwayatpesanmahasiswa') }}" class="nav-link menu-item">
                                <i class="fas fa-history me-2"></i>Riwayat Pesan
                            </a>
                            <a href="{{ url('/faqmahasiswa') }}" class="nav-link menu-item">
                                <i class="fas fa-question-circle me-2"></i>FAQ
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Content -->
            <div class="col-md-9">
                <!-- Stats Cards -->
                <div class="stats-cards row g-3 mb-4">
                    <div class="col-md-4">
                        <div class="card h-100">
                            <div class="card-body d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="text-muted mb-1">Belum dibaca</h6>
                                    <h3 class="mb-0 fs-4">2</h3>
                                </div>
                                <div class="bg-danger bg-opacity-10 p-3 rounded">
                                    <i class="fas fa-envelope text-danger"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card h-100">
                            <div class="card-body d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="text-muted mb-1">Pesan Aktif</h6>
                                    <h3 class="mb-0 fs-4">4</h3>
                                </div>
                                <div class="bg-success bg-opacity-10 p-3 rounded">
                                    <i class="fas fa-comments text-success"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="card h-100">
                            <div class="card-body d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="text-muted mb-1">Total Pesan</h6>
                                    <h3 class="mb-0 fs-4">4</h3>
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
                                <input type="text" class="form-control" placeholder="Cari Pesan..." style="font-size: 14px;">
                            </div>
                            <div class="col-md-auto">
                                <div class="btn-group">
                                    <button class="btn btn-outline-danger rounded-pill px-4 py-2 me-2" style="font-size: 14px;">Penting</button>
                                    <button class="btn btn-outline-success rounded-pill px-4 py-2 me-2" style="font-size: 14px;">Umum</button>
                                    <button class="btn btn-primary rounded-pill px-4 py-2" style="font-size: 14px;">Semua</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Message List -->
                <div class="message-list">
                    <!-- Message 1 -->
                    <div class="card mb-2 message-card penting" onclick="window.location.href='{{ url('/isipesanmahasiswa') }}'">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-md-8 d-flex align-items-center">
                                    <div class="profile-image-placeholder me-3">
                                        <i class="fas fa-user"></i>
                                    </div>
                                    <div>
                                        <span class="badge bg-primary mb-1">Bimbingan Skripsi</span>
                                        <h6 class="mb-1" style="font-size: 14px;">Dr. Ahmad Sulaiman, M.Kom</h6>
                                        <small class="text-muted">Dosen Pembimbing</small>
                                    </div>
                                </div>
                                <div class="col-md-4 text-md-end mt-3 mt-md-0">
                                    <span class="badge bg-danger me-1">Belum dibaca</span>
                                    <span class="badge bg-danger">Penting</span>
                                    <small class="d-block text-muted my-1">2 jam yang lalu</small>
                                    <button class="btn btn-custom-primary btn-sm" style="font-size: 10px;">
                                        <i class="fas fa-eye me-1"></i>Lihat
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Message 2 -->
                    <div class="card mb-2 message-card umum" onclick="window.location.href='{{ url('/isipesanmahasiswa') }}'">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-md-8 d-flex align-items-center">
                                    <div class="profile-image-placeholder me-3">
                                        <i class="fas fa-user"></i>
                                    </div>
                                    <div>
                                        <span class="badge bg-primary mb-1">Bimbingan KRS</span>
                                        <h6 class="mb-1" style="font-size: 14px;">Dr. Siti Nurhaliza, M.Si</h6>
                                        <small class="text-muted">Dosen Pembimbing Akademik</small>
                                    </div>
                                </div>
                                <div class="col-md-4 text-md-end mt-3 mt-md-0">
                                    <span class="badge bg-success me-1">Sudah dibaca</span>
                                    <span class="badge bg-success">Umum</span>
                                    <small class="d-block text-muted my-1">14 februari 2025</small>
                                    <button class="btn btn-custom-primary btn-sm" style="font-size: 10px;">
                                        <i class="fas fa-eye me-1"></i>Lihat
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Message 3 -->
                    <div class="card mb-2 message-card penting" onclick="window.location.href='{{ url('/isipesanmahasiswa') }}'">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-md-8 d-flex align-items-center">
                                    <div class="profile-image-placeholder me-3">
                                        <i class="fas fa-user"></i>
                                    </div>
                                    <div>
                                        <span class="badge bg-primary mb-1">Bimbingan MBKM</span>
                                        <h6 class="mb-1" style="font-size: 14px;">Dr. Budi Santoso, M.T</h6>
                                        <small class="text-muted">Koordinator MBKM</small>
                                    </div>
                                </div>
                                <div class="col-md-4 text-md-end mt-3 mt-md-0">
                                    <span class="badge bg-danger me-1">Belum dibaca</span>
                                    <span class="badge bg-danger">Penting</span>
                                    <small class="d-block text-muted my-1">2 jam yang lalu</small>
                                    <button class="btn btn-custom-primary btn-sm" style="font-size: 10px;">
                                        <i class="fas fa-eye me-1"></i>Lihat
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

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
    // Initialize the dropdown manually
    const grupDropdownToggle = document.getElementById('grupDropdownToggle');
    const komunikasiSubmenu = document.getElementById('komunikasiSubmenu');
    const grupDropdownIcon = document.getElementById('grupDropdownIcon');
    
    grupDropdownToggle.addEventListener('click', function() {
        // Toggle the collapse
        const bsCollapse = new bootstrap.Collapse(komunikasiSubmenu, {
            toggle: true
        });
        
        // Toggle the icon
        grupDropdownIcon.classList.toggle('fa-chevron-up');
        grupDropdownIcon.classList.toggle('fa-chevron-down');
    });

    // Tombol filter
    const filterButtons = document.querySelectorAll('.btn-group .btn');
    filterButtons.forEach(button => {
        button.addEventListener('click', function() {
            // Hapus class active dari semua tombol
            filterButtons.forEach(btn => btn.classList.remove('active'));
            
            // Tambahkan class active ke tombol yang diklik
            this.classList.add('active');
            
            // Filter pesan berdasarkan tombol yang diklik
            const filter = this.textContent.trim().toLowerCase();
            filterMessages(filter);
        });
    });

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
        document.getElementById('no-results').style.display = visibleCount === 0 ? 'block' : 'none';
    }

    // Pencarian pesan
    const searchInput = document.querySelector('.form-control');
    searchInput.addEventListener('input', function() {
        const searchTerm = this.value.toLowerCase();
        searchMessages(searchTerm);
    });

    // Fungsi pencarian pesan
    function searchMessages(searchTerm) {
        const messageCards = document.querySelectorAll('.message-card');
        let visibleCount = 0;
        
        messageCards.forEach(card => {
            const messageText = card.textContent.toLowerCase();
            
            if (messageText.includes(searchTerm)) {
                card.style.display = 'block';
                visibleCount++;
            } else {
                card.style.display = 'none';
            }
        });
        
        // Tampilkan pesan "tidak tersedia" jika tidak ada pesan yang sesuai pencarian
        document.getElementById('no-results').style.display = visibleCount === 0 ? 'block' : 'none';
    }
});
</script>
@endpush