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

    .user-list {
        max-height: calc(100vh - 320px);
        overflow-y: auto;
        padding-right: 10px; 
    }

    .user-list::-webkit-scrollbar {
        width: 6px;
    }

    .user-list::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 10px;
    }

    .user-list::-webkit-scrollbar-thumb {
        background: #888;
        border-radius: 10px;
    }
    
    .user-list::-webkit-scrollbar-thumb:hover {
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
</style>
@endpush

@section('content')
<div class="main-content">
    <div class="custom-container">
        <div class="row g-4">
            <div class="col-md-3">
                <div class="sidebar">
                    <div class="sidebar-buttons">
                        <a href="{{ url('/addmessage_admin') }}" class="btn w-100" style="background: linear-gradient(to right, #004AAD, #5DE0E6); color: white; padding: 10px 20px; border: none; border-radius: 5px;">
                            <i class="fas fa-plus me-2"></i> Pesan Baru
                        </a>                        
                    </div>                                                    
                    
                    <div class="sidebar-menu">
                        <div class="nav flex-column">
                            <a href="{{ url('/dashboardpesan_admin') }}" class="nav-link active">
                                <i class="fas fa-tachometer-alt me-2"></i>Dashboard
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
                            <a href="{{ url('/resetpassword_admin') }}" class="nav-link">
                                <i class="fas fa-key me-2"></i>Reset Password
                            </a>
                            <a href="{{ url('/logs_admin') }}" class="nav-link">
                                <i class="fas fa-history me-2"></i>Log Aktivitas
                            </a>
                            <a href="{{ url('/settings_admin') }}" class="nav-link">
                                <i class="fas fa-cog me-2"></i>Pengaturan
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Content -->
            <div class="col-md-9">
                <!-- Heading -->
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h4>Dashboard Pesan Admin</h4>
                    <span class="badge bg-primary p-2">
                        <i class="fas fa-calendar-alt me-1"></i> {{ date('d M Y') }}
                    </span>
                </div>
                
                <!-- Stats Cards -->
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
                                    <h6 class="text-muted mb-1">Pesan Belum Dibaca</h6>
                                    <h3 class="mb-0 fs-4">23</h3>
                                </div>
                                <div class="bg-warning bg-opacity-10 p-3 rounded">
                                    <i class="fas fa-envelope-open text-warning"></i>
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

                <!-- Notification Cards -->
                <div class="row mb-4">
                    <div class="col-md-6">
                        <div class="card h-100">
                            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                                <h6 class="mb-0">Pesan Penting</h6>
                                <span class="badge bg-danger">4 baru</span>
                            </div>
                            <div class="card-body">
                                <div class="notification-item d-flex align-items-start mb-3">
                                    <div class="bg-danger bg-opacity-10 p-2 rounded me-3">
                                        <i class="fas fa-exclamation-circle text-danger"></i>
                                    </div>
                                    <div>
                                        <h6 class="mb-1 fs-6">Pengumuman Penting</h6>
                                        <p class="text-muted mb-1 small">Jadwal UAS telah diperbarui, mohon dicek</p>
                                        <small class="text-muted">10 menit yang lalu</small>
                                    </div>
                                </div>
                                <div class="notification-item d-flex align-items-start mb-3">
                                    <div class="bg-danger bg-opacity-10 p-2 rounded me-3">
                                        <i class="fas fa-exclamation-circle text-danger"></i>
                                    </div>
                                    <div>
                                        <h6 class="mb-1 fs-6">Laporan Error</h6>
                                        <p class="text-muted mb-1 small">Beberapa mahasiswa melaporkan masalah login</p>
                                        <small class="text-muted">2 jam yang lalu</small>
                                    </div>
                                </div>
                                <a href="#" class="btn btn-sm btn-outline-danger w-100">Lihat Semua Pesan Penting</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card h-100">
                            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                                <h6 class="mb-0">Pesan Terbaru</h6>
                                <span class="badge bg-primary">Hari ini</span>
                            </div>
                            <div class="card-body">
                                <div class="notification-item d-flex align-items-start mb-3">
                                    <div class="bg-primary bg-opacity-10 p-2 rounded me-3">
                                        <i class="fas fa-envelope text-primary"></i>
                                    </div>
                                    <div>
                                        <h6 class="mb-1 fs-6">Jadwal Bimbingan</h6>
                                        <p class="text-muted mb-1 small">Dr. Ahmad Fauzi mengirim jadwal bimbingan</p>
                                        <small class="text-muted">1 jam yang lalu</small>
                                    </div>
                                </div>
                                <div class="notification-item d-flex align-items-start mb-3">
                                    <div class="bg-primary bg-opacity-10 p-2 rounded me-3">
                                        <i class="fas fa-envelope text-primary"></i>
                                    </div>
                                    <div>
                                        <h6 class="mb-1 fs-6">Grup Baru</h6>
                                        <p class="text-muted mb-1 small">Grup "Seminar Proposal" telah dibuat</p>
                                        <small class="text-muted">3 jam yang lalu</small>
                                    </div>
                                </div>
                                <a href="#" class="btn btn-sm btn-outline-primary w-100">Lihat Semua Pesan</a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quick Access -->
                <div class="card mb-4">
                    <div class="card-header bg-white">
                        <h6 class="mb-0">Akses Cepat</h6>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-3">
                                <a href="{{ url('/addmessage_admin') }}" class="card text-center h-100 text-decoration-none">
                                    <div class="card-body">
                                        <div class="mb-3 bg-primary bg-opacity-10 p-3 rounded d-inline-block">
                                            <i class="fas fa-plus text-primary fa-2x"></i>
                                        </div>
                                        <h6 class="mb-0">Pesan Baru</h6>
                                    </div>
                                </a>
                            </div>
                            <div class="col-md-3">
                                <a href="{{ url('/inbox_admin') }}" class="card text-center h-100 text-decoration-none">
                                    <div class="card-body">
                                        <div class="mb-3 bg-warning bg-opacity-10 p-3 rounded d-inline-block">
                                            <i class="fas fa-inbox text-warning fa-2x"></i>
                                        </div>
                                        <h6 class="mb-0">Kotak Masuk</h6>
                                    </div>
                                </a>
                            </div>
                            <div class="col-md-3">
                                <a href="{{ url('/creategroup_admin') }}" class="card text-center h-100 text-decoration-none">
                                    <div class="card-body">
                                        <div class="mb-3 bg-success bg-opacity-10 p-3 rounded d-inline-block">
                                            <i class="fas fa-users text-success fa-2x"></i>
                                        </div>
                                        <h6 class="mb-0">Buat Grup</h6>
                                    </div>
                                </a>
                            </div>
                            <div class="col-md-3">
                                <a href="{{ url('/archivedmessages_admin') }}" class="card text-center h-100 text-decoration-none">
                                    <div class="card-body">
                                        <div class="mb-3 bg-info bg-opacity-10 p-3 rounded d-inline-block">
                                            <i class="fas fa-archive text-info fa-2x"></i>
                                        </div>
                                        <h6 class="mb-0">Arsip Pesan</h6>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recent Messages -->
                <div class="card mb-4">
                    <div class="card-header bg-white d-flex justify-content-between align-items-center">
                        <h6 class="mb-0">Pesan Terbaru</h6>
                        <a href="{{ url('/inbox_admin') }}" class="btn btn-sm btn-outline-primary">Lihat Semua</a>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered border-secondary">
                                <thead class="table-header">
                                    <tr>
                                        <th>Pengirim <i class="fas fa-sort"></i></th>
                                        <th>Subjek <i class="fas fa-sort"></i></th>
                                        <th>Kategori <i class="fas fa-sort"></i></th>
                                        <th>Status <i class="fas fa-sort"></i></th>
                                        <th>Tanggal <i class="fas fa-sort"></i></th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center justify-content-center">
                                                <div class="profile-image-placeholder me-2">
                                                    <i class="fas fa-user"></i>
                                                </div>
                                                <div class="text-start">
                                                    <h6 class="mb-0">Dr. Ahmad Fauzi</h6>
                                                    <small class="text-muted">Dosen</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>Jadwal Bimbingan Minggu Ini</td>
                                        <td><span class="badge bg-info">Akademik</span></td>
                                        <td><span class="badge bg-success">Dibaca</span></td>
                                        <td>20 Apr 2025, 08:12</td>
                                        <td>
                                            <div class="dropdown">
                                                <a href="#" class="edit-btn" data-bs-toggle="dropdown">
                                                    <i class="fas fa-pen"></i>
                                                </a>
                                                <ul class="dropdown-menu dropdown-menu-end">
                                                    <li><a class="dropdown-item" href="#"><i class="fas fa-eye me-2 text-primary"></i>Lihat</a></li>
                                                    <li><a class="dropdown-item" href="#"><i class="fas fa-reply me-2 text-success"></i>Balas</a></li>
                                                    <li><a class="dropdown-item" href="#"><i class="fas fa-forward me-2 text-info"></i>Teruskan</a></li>
                                                    <li><hr class="dropdown-divider"></li>
                                                    <li><a class="dropdown-item" href="#"><i class="fas fa-trash me-2 text-danger"></i>Hapus</a></li>
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center justify-content-center">
                                                <div class="profile-image-placeholder me-2">
                                                    <i class="fas fa-user"></i>
                                                </div>
                                                <div class="text-start">
                                                    <h6 class="mb-0">Dinda Pratiwi</h6>
                                                    <small class="text-muted">Mahasiswa</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>Pertanyaan tentang Tugas Akhir</td>
                                        <td><span class="badge bg-warning">Konsultasi</span></td>
                                        <td><span class="badge bg-danger">Belum Dibaca</span></td>
                                        <td>19 Apr 2025, 15:43</td>
                                        <td>
                                            <div class="dropdown">
                                                <a href="#" class="edit-btn" data-bs-toggle="dropdown">
                                                    <i class="fas fa-pen"></i>
                                                </a>
                                                <ul class="dropdown-menu dropdown-menu-end">
                                                    <li><a class="dropdown-item" href="#"><i class="fas fa-eye me-2 text-primary"></i>Lihat</a></li>
                                                    <li><a class="dropdown-item" href="#"><i class="fas fa-reply me-2 text-success"></i>Balas</a></li>
                                                    <li><a class="dropdown-item" href="#"><i class="fas fa-forward me-2 text-info"></i>Teruskan</a></li>
                                                    <li><hr class="dropdown-divider"></li>
                                                    <li><a class="dropdown-item" href="#"><i class="fas fa-trash me-2 text-danger"></i>Hapus</a></li>
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center justify-content-center">
                                                <div class="profile-image-placeholder me-2">
                                                    <i class="fas fa-user-shield"></i>
                                                </div>
                                                <div class="text-start">
                                                    <h6 class="mb-0">Admin Sistem</h6>
                                                    <small class="text-muted">Admin</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>Pengumuman: Pemeliharaan Sistem</td>
                                        <td><span class="badge bg-danger">Penting</span></td>
                                        <td><span class="badge bg-danger">Belum Dibaca</span></td>
                                        <td>19 Apr 2025, 09:15</td>
                                        <td>
                                            <div class="dropdown">
                                                <a href="#" class="edit-btn" data-bs-toggle="dropdown">
                                                    <i class="fas fa-pen"></i>
                                                </a>
                                                <ul class="dropdown-menu dropdown-menu-end">
                                                    <li><a class="dropdown-item" href="#"><i class="fas fa-eye me-2 text-primary"></i>Lihat</a></li>
                                                    <li><a class="dropdown-item" href="#"><i class="fas fa-reply me-2 text-success"></i>Balas</a></li>
                                                    <li><a class="dropdown-item" href="#"><i class="fas fa-forward me-2 text-info"></i>Teruskan</a></li>
                                                    <li><hr class="dropdown-divider"></li>
                                                    <li><a class="dropdown-item" href="#"><i class="fas fa-trash me-2 text-danger"></i>Hapus</a></li>
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                
                <!-- Message Status -->
                <div class="card">
                    <div class="card-header bg-white">
                        <h6 class="mb-0">Status Pesan</h6>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <div class="d-flex align-items-center mb-2">
                                    <div class="me-2 text-success"><i class="fas fa-check-circle"></i></div>
                                    <div>Pesan Terkirim</div>
                                    <div class="ms-auto text-success">56 hari ini</div>
                                </div>
                                <div class="progress" style="height: 6px;">
                                    <div class="progress-bar bg-success" style="width: 92%"></div>
                                </div>
                                <div class="d-flex justify-content-between mt-1">
                                    <small class="text-muted">Total: 985</small>
                                    <small class="text-muted">Sukses: 98.5%</small>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="d-flex align-items-center mb-2">
                                    <div class="me-2 text-info"><i class="fas fa-envelope-open"></i></div>
                                    <div>Tingkat Respon</div>
                                    <div class="ms-auto text-info">Baik</div>
                                </div>
                                <div class="progress" style="height: 6px;">
                                    <div class="progress-bar bg-info" style="width: 78%"></div>
                                </div>
                                <div class="d-flex justify-content-between mt-1">
                                    <small class="text-muted">Waktu: 2.4 jam</small>
                                    <small class="text-muted">Rate: 78%</small>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="d-flex align-items-center mb-2">
                                    <div class="me-2 text-warning"><i class="fas fa-exclamation-circle"></i></div>
                                    <div>Pesan Belum Dibaca</div>
                                    <div class="ms-auto text-warning">23 pesan</div>
                                </div>
                                <div class="progress" style="height: 6px;">
                                    <div class="progress-bar bg-warning" style="width: 12%"></div>
                                </div>
                                <div class="d-flex justify-content-between mt-1">
                                    <small class="text-muted">Dari: 215 total</small>
                                    <small class="text-warning">12% belum dibaca</small>
                                </div>
                            </div>
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