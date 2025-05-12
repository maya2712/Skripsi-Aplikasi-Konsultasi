@extends('layouts.app')

@section('title', 'Dashboard Admin')

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
    
    .stats-card {
        transition: all 0.3s ease;
    }
    
    .stats-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1);
    }
    
    .stats-cards .card {
        height: 100%;
        margin-bottom: 0;
    }
    
    .welcome-banner {
        background: linear-gradient(135deg, #1a73e8, #6c49e3);
        color: white;
        border-radius: 10px;
        padding: 30px;
        margin-bottom: 25px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        position: relative;
        overflow: hidden;
    }
    
    .welcome-banner::before {
        content: '';
        position: absolute;
        top: 0;
        right: 0;
        width: 200px;
        height: 100%;
        background: url('https://via.placeholder.com/200') no-repeat;
        background-size: cover;
        opacity: 0.1;
    }
    
    .welcome-banner h4 {
        font-weight: 600;
        margin-bottom: 15px;
        font-size: 24px;
    }
    
    .notification-card {
        border-left: 4px solid var(--bs-warning);
        transition: all 0.2s ease;
        margin-bottom: 12px;
    }
    
    .notification-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.05);
    }
    
    .notification-card .card-body {
        padding: 15px;
    }
    
    .notification-card.dosen {
        border-left-color: var(--bs-info);
        background-color: rgba(0, 188, 212, 0.05);
    }
    
    .notification-card.mahasiswa {
        border-left-color: var(--bs-success);
        background-color: rgba(39, 174, 96, 0.05);
    }
    
    .notif-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
        background-color: #f8f9fa;
        padding: 15px;
        border-radius: 10px;
        border-left: 4px solid #1a73e8;
    }
    
    .notif-header h5 {
        margin-bottom: 0;
        font-size: 16px;
        font-weight: 600;
    }
    
    .notifications-container {
        max-height: 500px;
        overflow-y: auto;
    }
    
    .notifications-container::-webkit-scrollbar {
        width: 6px;
    }
    
    .notifications-container::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 10px;
    }
    
    .notifications-container::-webkit-scrollbar-thumb {
        background: #ccc;
        border-radius: 10px;
    }
    
    .notifications-container::-webkit-scrollbar-thumb:hover {
        background: #aaa;
    }
    
    .empty-notif {
        padding: 40px 20px;
        text-align: center;
        background: #f8f9fa;
        border-radius: 10px;
    }
    
    .empty-notif i {
        font-size: 40px;
        color: #ccc;
        margin-bottom: 15px;
    }
    
    .empty-notif p {
        color: #777;
        margin-bottom: 0;
    }
    
    .profile-icon {
        width: 45px;
        height: 45px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 20px;
        margin-right: 15px;
    }
    
    .profile-icon.dosen {
        background-color: var(--bs-info);
    }
    
    .profile-icon.mahasiswa {
        background-color: var(--bs-success);
    }
</style>
@endpush

@section('content')
<div class="main-content">
    <div class="custom-container">
        <div class="row g-4">
            <!-- Sidebar - Tanpa tombol -->
            <div class="col-md-3">
                <div class="sidebar">
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

            <!-- Main Content - Redesigned sesuai fungsi admin -->
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
                
                <!-- Welcome Banner yang lebih besar dan menarik (tanpa tombol) -->
                <div class="welcome-banner">
                    <h4>Selamat Datang, Admin!</h4>
                </div>
                
                <!-- Stats Cards - Fokus pada jumlah user (hanya Total Dosen, Total Mahasiswa, Reset Password) -->
                <div class="stats-cards row g-3 mb-4">
                    <div class="col-md-4">
                        <div class="card h-100 stats-card">
                            <div class="card-body d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="text-muted mb-1">Total Dosen</h6>
                                    <h3 class="mb-0 fs-4">56</h3>
                                </div>
                                <div class="bg-primary bg-opacity-10 p-3 rounded">
                                    <i class="fas fa-chalkboard-teacher text-primary"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card h-100 stats-card">
                            <div class="card-body d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="text-muted mb-1">Total Mahasiswa</h6>
                                    <h3 class="mb-0 fs-4">238</h3>
                                </div>
                                <div class="bg-success bg-opacity-10 p-3 rounded">
                                    <i class="fas fa-user-graduate text-success"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card h-100 stats-card">
                            <div class="card-body d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="text-muted mb-1">Reset Password</h6>
                                    <h3 class="mb-0 fs-4">12</h3>
                                </div>
                                <div class="bg-warning bg-opacity-10 p-3 rounded">
                                    <i class="fas fa-key text-warning"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Notifications Section (Disederhanakan tanpa "Lihat Semua") -->
                <div class="card">
                    <div class="card-body">
                        <div class="notif-header">
                            <h5><i class="fas fa-bell me-2"></i> Permintaan Reset Password</h5>
                        </div>
                        
                        <div class="notifications-container">
                            <!-- Permintaan Reset dari Dosen -->
                            <div class="notification-card dosen">
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <div class="profile-icon dosen">
                                            <i class="fas fa-user"></i>
                                        </div>
                                        <div class="flex-grow-1">
                                            <h6 class="mb-1">Dr. Ahmad Fauzi</h6>
                                            <div class="d-flex align-items-center mb-2">
                                                <span class="badge bg-info me-2">Dosen</span>
                                                <small class="text-muted">NIP: D003</small>
                                            </div>
                                        </div>
                                        <div class="ms-3 d-flex flex-column align-items-end">
                                            <span class="badge bg-danger mb-2">15 menit lalu</span>
                                            <button class="btn btn-primary btn-sm">
                                                <i class="fas fa-check me-1"></i>Proses
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Permintaan Reset dari Mahasiswa -->
                            <div class="notification-card mahasiswa">
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <div class="profile-icon mahasiswa">
                                            <i class="fas fa-user-graduate"></i>
                                        </div>
                                        <div class="flex-grow-1">
                                            <h6 class="mb-1">Ahmad Rizaldi</h6>
                                            <div class="d-flex align-items-center mb-2">
                                                <span class="badge bg-success me-2">Mahasiswa</span>
                                                <small class="text-muted">NIM: 2023005</small>
                                            </div>
                                        </div>
                                        <div class="ms-3 d-flex flex-column align-items-end">
                                            <span class="badge bg-danger mb-2">45 menit lalu</span>
                                            <button class="btn btn-primary btn-sm">
                                                <i class="fas fa-check me-1"></i>Proses
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Permintaan Reset dari Dosen -->
                            <div class="notification-card dosen">
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <div class="profile-icon dosen">
                                            <i class="fas fa-user"></i>
                                        </div>
                                        <div class="flex-grow-1">
                                            <h6 class="mb-1">Dr. Siti Rahayu</h6>
                                            <div class="d-flex align-items-center mb-2">
                                                <span class="badge bg-info me-2">Dosen</span>
                                                <small class="text-muted">NIP: D005</small>
                                            </div>
                                        </div>
                                        <div class="ms-3 d-flex flex-column align-items-end">
                                            <span class="badge bg-secondary mb-2">3 jam lalu</span>
                                            <button class="btn btn-primary btn-sm">
                                                <i class="fas fa-check me-1"></i>Proses
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Permintaan Reset dari Mahasiswa -->
                            <div class="notification-card mahasiswa">
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <div class="profile-icon mahasiswa">
                                            <i class="fas fa-user-graduate"></i>
                                        </div>
                                        <div class="flex-grow-1">
                                            <h6 class="mb-1">Dinda Pratiwi</h6>
                                            <div class="d-flex align-items-center mb-2">
                                                <span class="badge bg-success me-2">Mahasiswa</span>
                                                <small class="text-muted">NIM: 2022007</small>
                                            </div>
                                        </div>
                                        <div class="ms-3 d-flex flex-column align-items-end">
                                            <span class="badge bg-secondary mb-2">5 jam lalu</span>
                                            <button class="btn btn-primary btn-sm">
                                                <i class="fas fa-check me-1"></i>Proses
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Jika Tidak Ada Notifikasi -->
                            <!--
                            <div class="empty-notif">
                                <i class="fas fa-bell-slash d-block"></i>
                                <p>Tidak ada permintaan reset password saat ini</p>
                            </div>
                            -->
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
});
</script>
@endpush