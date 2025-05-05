@extends('layouts.app')

@section('title', 'Dashboard Pesan Admin')

@push('styles')
<style>
    :root {
        --bs-primary: #1a73e8;
        --bs-danger: #FF5252;
        --bs-success: #27AE60;
        --bs-warning: #FFC107;
        --bs-info: #00BCD4;
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

    .badge-notification {
        background: var(--bs-danger);
    }

    /* Tabel style */
    .table-header {
        background-color: #222;
        color: white;
    }
    
    .table-header th {
        font-weight: normal;
        vertical-align: middle;
        padding: 10px 15px;
        border: 1px solid #444 !important;
        text-align: center;
    }
    
    .table-striped > tbody > tr:nth-of-type(odd) {
        background-color: #f9f9f9;
    }
    
    .table-striped > tbody > tr > td {
        padding: 12px 15px;
        vertical-align: middle;
        border: 1px solid #dee2e6 !important;
        text-align: center;
    }
    
    .table {
        border-collapse: collapse;
        width: 100%;
    }
    
    .edit-btn {
        background-color: #ffc107;
        border-radius: 50%;
        width: 30px;
        height: 30px;
        display: flex;
        justify-content: center;
        align-items: center;
        color: #000;
        text-decoration: none;
        margin: 0 auto;
        font-size: 12px;
    }

    .search-filter-card {
        position: sticky;
        top: 76px;
        z-index: 100;
        background: white;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    .message-container {
        max-height: calc(100vh - 320px);
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
    
    .message-container::-webkit-scrollbar-thumb:hover {
        background: #555;
    }
    
    .custom-container {
        max-width: 1400px;
        margin: 0 auto;
        padding: 0 15px;
    }
    
    .card {
        margin-bottom: 20px;
        border-radius: 10px;
        box-shadow: 0 0 5px rgba(0,0,0,0.1);
        border: none;
    }
    
    .user-list .card {
        margin-bottom: 15px;
    }
    
    .user-card.dosen {
        border-left: 4px solid var(--bs-info);
    }
    
    .user-card.mahasiswa {
        border-left: 4px solid var(--bs-success);
    }
    
    .user-card.admin {
        border-left: 4px solid var(--bs-warning);
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
    
    .stats-card {
        transition: all 0.3s ease;
    }
    
    .stats-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1);
    }
    
    .action-buttons .btn {
        margin-right: 5px;
    }
    
    /* Style untuk card pesan seperti di dashboard mahasiswa */
    .message-card {
        cursor: pointer;
        transition: all 0.2s ease;
        border-radius: 10px;
    }
    
    .message-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }
    
    .message-card.penting {
        border-left: 4px solid var(--bs-danger);
    }
    
    .message-card.umum {
        border-left: 4px solid var(--bs-success);
    }
    
    .message-card.akademik {
        border-left: 4px solid var(--bs-primary);
    }
    
    .message-card.pengumuman {
        border-left: 4px solid var(--bs-warning);
    }
    
    .stats-cards .card {
        height: 100%;
        margin-bottom: 0;
    }
</style>
@endpush

@section('content')
<div class="main-content">
    <div class="custom-container">
        <div class="row g-4">
            <!-- Sidebar - Dipertahankan persis seperti aslinya -->
            <div class="col-md-3">
                <div class="sidebar">
                    <div class="sidebar-buttons">
                        <a href="{{ url('/addmessage_admin') }}" class="btn w-100" style="background: linear-gradient(to right, #004AAD, #5DE0E6); color: white; padding: 10px 20px; border: none; border-radius: 5px;">
                            <i class="fas fa-plus me-2"></i> Pesan Baru
                        </a>                        
                    </div>                                                    
                    
                    <div class="sidebar-menu">
                        <div class="nav flex-column">
                            <a href="{{ route('admin.dashboard') }}" class="nav-link active">
                                <i class="fas fa-tachometer-alt me-2"></i>Dashboard Admin
                            </a>
                            <a href="#" class="nav-link" id="userManagementToggle">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span><i class="fas fa-users-cog me-2"></i>Manajemen User</span>
                                    <i class="fas fa-chevron-down" id="userManagementIcon"></i>
                                </div>
                            </a>
                            <div class="collapse" id="userManagementSubmenu">
                                <div class="ps-3">
                                    <a href="{{ url('/admin/managementuser_dosen') }}" class="nav-link">
                                        <i class="fas fa-chalkboard-teacher me-2"></i>Dosen
                                    </a>                                    
                                    <a href="{{ url('/admin/managementuser_mahasiswa') }}" class="nav-link">
                                        <i class="fas fa-user-graduate me-2"></i>Mahasiswa
                                    </a>
                                </div>
                            </div>
                            <a href="#" class="nav-link" id="grupDropdownToggle">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span><i class="fas fa-user-tag me-2"></i>Daftar Grup</span>
                                    <i class="fas fa-chevron-down" id="grupDropdownIcon"></i>
                                </div>
                            </a>
                            <div class="collapse" id="komunikasiSubmenu">
                                <div class="ps-3">
                                    <a href="{{ url('/creategroup_admin') }}" class="nav-link">
                                        <i class="fas fa-plus me-2"></i>Buat Grup Baru
                                    </a>
                                    <a href="{{ url('/groupmanagement_admin') }}" class="nav-link">
                                        <i class="fas fa-list me-2"></i>Lihat Semua Grup
                                    </a>
                                </div>
                            </div>
                           
                            <a href="{{ url('/logs_admin') }}" class="nav-link">
                                <i class="fas fa-history me-2"></i>Riwayat
                            </a>
                           
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Content - Redesigned to match mahasiswa dashboard style -->
            <div class="col-md-9">
                <!-- Notifikasi sukses dengan desain modern -->
                @if(session('success'))
                <div class="alert mb-4 auto-dismiss-alert" role="alert" style="background-color: rgba(39, 174, 96, 0.1); border-left: 4px solid #27AE60; color: #2E7D32; border-radius: 4px; box-shadow: 0 2px 5px rgba(0,0,0,0.05); padding: 12px; position: relative;">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-check-circle me-2" style="font-size: 18px; color: #27AE60;"></i>
                        <div><strong>Berhasil!</strong> {{ session('success') }}</div>
                        <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close" style="font-size: 10px; padding: 8px;"></button>
                    </div>
                </div>
                @endif
                
                <!-- Stats Cards - Mirip dengan dashboard mahasiswa -->
                <div class="stats-cards row g-3 mb-4">
                    <div class="col-md-3">
                        <div class="card h-100 stats-card">
                            <div class="card-body d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="text-muted mb-1">Pesan Hari Ini</h6>
                                    <h3 class="mb-0 fs-4">56</h3>
                                </div>
                                <div class="bg-primary bg-opacity-10 p-3 rounded">
                                    <i class="fas fa-envelope text-primary"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card h-100 stats-card">
                            <div class="card-body d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="text-muted mb-1">Belum Dibaca</h6>
                                    <h3 class="mb-0 fs-4">23</h3>
                                </div>
                                <div class="bg-danger bg-opacity-10 p-3 rounded">
                                    <i class="fas fa-envelope-open text-danger"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card h-100 stats-card">
                            <div class="card-body d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="text-muted mb-1">Total Pesan</h6>
                                    <h3 class="mb-0 fs-4">215</h3>
                                </div>
                                <div class="bg-success bg-opacity-10 p-3 rounded">
                                    <i class="fas fa-inbox text-success"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card h-100 stats-card">
                            <div class="card-body d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="text-muted mb-1">Total Grup</h6>
                                    <h3 class="mb-0 fs-4">24</h3>
                                </div>
                                <div class="bg-info bg-opacity-10 p-3 rounded">
                                    <i class="fas fa-users text-info"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Search and Filters - Mirip dengan dashboard mahasiswa -->
                <div class="card mb-4 search-filter-card">
                    <div class="card-body">
                        <div class="row g-3 align-items-center">
                            <div class="col-md">
                                <input type="text" class="form-control" placeholder="Cari Pesan..." style="font-size: 14px;" id="searchInput">
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

                <!-- Message List - Style mirip dengan dashboard mahasiswa -->
                <div class="message-list">
                    <!-- Message 1 - Penting - Belum dibaca -->
                    <div class="card mb-2 message-card penting" onclick="window.location.href='{{ url('/viewmessage_admin') }}'">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-md-8 d-flex align-items-center">
                                    <div class="profile-image-placeholder me-3">
                                        <i class="fas fa-user"></i>
                                    </div>
                                    <div>
                                        <span class="badge bg-primary mb-1">Bimbingan Skripsi</span>
                                        <h6 class="mb-1" style="font-size: 14px;">Dr. Ahmad Fauzi, S.Kom., M.Kom.</h6>
                                        <small class="text-muted">Dosen Tetap</small>
                                    </div>
                                </div>
                                <div class="col-md-4 text-md-end mt-3 mt-md-0">
                                    <span class="badge bg-danger me-1">Belum dibaca</span>
                                    <span class="badge bg-danger">Penting</span>
                                    <small class="d-block text-muted my-1">20 Apr 2025, 08:12</small>
                                    <button class="btn btn-custom-primary btn-sm" style="font-size: 10px;">
                                        <i class="fas fa-eye me-1"></i>Lihat
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Message 2 - Umum - Sudah dibaca -->
                    <div class="card mb-2 message-card umum" onclick="window.location.href='{{ url('/viewmessage_admin') }}'">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-md-8 d-flex align-items-center">
                                    <div class="profile-image-placeholder me-3">
                                        <i class="fas fa-user-graduate"></i>
                                    </div>
                                    <div>
                                        <span class="badge bg-warning mb-1">Konsultasi</span>
                                        <h6 class="mb-1" style="font-size: 14px;">Dinda Pratiwi</h6>
                                        <small class="text-muted">Mahasiswa</small>
                                    </div>
                                </div>
                                <div class="col-md-4 text-md-end mt-3 mt-md-0">
                                    <span class="badge bg-success me-1">Sudah dibaca</span>
                                    <span class="badge bg-success">Umum</span>
                                    <small class="d-block text-muted my-1">19 Apr 2025, 15:43</small>
                                    <button class="btn btn-custom-primary btn-sm" style="font-size: 10px;">
                                        <i class="fas fa-eye me-1"></i>Lihat
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Message 3 - Penting - Belum dibaca -->
                    <div class="card mb-2 message-card penting" onclick="window.location.href='{{ url('/viewmessage_admin') }}'">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-md-8 d-flex align-items-center">
                                    <div class="profile-image-placeholder me-3">
                                        <i class="fas fa-user-shield"></i>
                                    </div>
                                    <div>
                                        <span class="badge bg-danger mb-1">Sistem</span>
                                        <h6 class="mb-1" style="font-size: 14px;">Admin Sistem</h6>
                                        <small class="text-muted">Pengumuman: Pemeliharaan Sistem</small>
                                    </div>
                                </div>
                                <div class="col-md-4 text-md-end mt-3 mt-md-0">
                                    <span class="badge bg-danger me-1">Belum dibaca</span>
                                    <span class="badge bg-danger">Penting</span>
                                    <small class="d-block text-muted my-1">19 Apr 2025, 09:15</small>
                                    <button class="btn btn-custom-primary btn-sm" style="font-size: 10px;">
                                        <i class="fas fa-eye me-1"></i>Lihat
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Message 4 - Akademik -->
                    <div class="card mb-2 message-card akademik" onclick="window.location.href='{{ url('/viewmessage_admin') }}'">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-md-8 d-flex align-items-center">
                                    <div class="profile-image-placeholder me-3">
                                        <i class="fas fa-user"></i>
                                    </div>
                                    <div>
                                        <span class="badge bg-info mb-1">Akademik</span>
                                        <h6 class="mb-1" style="font-size: 14px;">Dr. Siti Rahayu, M.T.</h6>
                                        <small class="text-muted">Dosen Tetap</small>
                                    </div>
                                </div>
                                <div class="col-md-4 text-md-end mt-3 mt-md-0">
                                    <span class="badge bg-success me-1">Sudah dibaca</span>
                                    <span class="badge bg-info">Akademik</span>
                                    <small class="d-block text-muted my-1">18 Apr 2025, 10:30</small>
                                    <button class="btn btn-custom-primary btn-sm" style="font-size: 10px;">
                                        <i class="fas fa-eye me-1"></i>Lihat
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Message 5 - Pengumuman -->
                    <div class="card mb-2 message-card pengumuman" onclick="window.location.href='{{ url('/viewmessage_admin') }}'">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-md-8 d-flex align-items-center">
                                    <div class="profile-image-placeholder me-3">
                                        <i class="fas fa-bullhorn"></i>
                                    </div>
                                    <div>
                                        <span class="badge bg-warning mb-1">Pengumuman</span>
                                        <h6 class="mb-1" style="font-size: 14px;">Jadwal UAS Semester Genap</h6>
                                        <small class="text-muted">BAAK Universitas</small>
                                    </div>
                                </div>
                                <div class="col-md-4 text-md-end mt-3 mt-md-0">
                                    <span class="badge bg-success me-1">Sudah dibaca</span>
                                    <span class="badge bg-warning">Pengumuman</span>
                                    <small class="d-block text-muted my-1">17 Apr 2025, 14:15</small>
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
    // Auto dismiss alerts after 5 seconds
    const autoDismissAlerts = document.querySelectorAll('.auto-dismiss-alert');
    if (autoDismissAlerts.length > 0) {
        autoDismissAlerts.forEach(function(alert) {
            setTimeout(function() {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            }, 5000); // Menghilang setelah 5 detik
        });
    }
    
    // Toggle dropdown for grup
    const grupDropdownToggle = document.getElementById('grupDropdownToggle');
    const komunikasiSubmenu = document.getElementById('komunikasiSubmenu');
    const grupDropdownIcon = document.getElementById('grupDropdownIcon');
    
    if (grupDropdownToggle && komunikasiSubmenu && grupDropdownIcon) {
        grupDropdownToggle.addEventListener('click', function() {
            // Toggle the collapse
            const bsCollapse = new bootstrap.Collapse(komunikasiSubmenu, {
                toggle: true
            });
            
            // Toggle the icon
            grupDropdownIcon.classList.toggle('fa-chevron-up');
            grupDropdownIcon.classList.toggle('fa-chevron-down');
        });
    }
    
    // Toggle dropdown for user management
    const userManagementToggle = document.getElementById('userManagementToggle');
    const userManagementSubmenu = document.getElementById('userManagementSubmenu');
    const userManagementIcon = document.getElementById('userManagementIcon');
    
    if (userManagementToggle && userManagementSubmenu && userManagementIcon) {
        userManagementToggle.addEventListener('click', function() {
            // Toggle the collapse
            const bsCollapse = new bootstrap.Collapse(userManagementSubmenu, {
                toggle: true
            });
            
            // Toggle the icon
            userManagementIcon.classList.toggle('fa-chevron-up');
            userManagementIcon.classList.toggle('fa-chevron-down');
        });
    }
    
    // Tombol filter
    const filterButtons = document.querySelectorAll('.filter-btn');
    filterButtons.forEach(button => {
        button.addEventListener('click', function() {
            // Hapus class active dari semua tombol
            filterButtons.forEach(btn => {
                btn.classList.remove('active');
                if (btn.classList.contains('btn-primary')) {
                    btn.classList.remove('btn-primary');
                    btn.classList.add('btn-outline-primary');
                }
            });
            
            // Tambahkan class active ke tombol yang diklik
            this.classList.add('active');
            if (this.classList.contains('btn-outline-primary') || 
                this.classList.contains('btn-outline-danger') || 
                this.classList.contains('btn-outline-success')) {
                this.classList.remove('btn-outline-primary');
                this.classList.remove('btn-outline-danger');
                this.classList.remove('btn-outline-success');
                this.classList.add('btn-primary');
            }
            
            // Filter pesan berdasarkan tombol yang diklik
            const filter = this.getAttribute('data-filter');
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
    const searchInput = document.getElementById('searchInput');
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