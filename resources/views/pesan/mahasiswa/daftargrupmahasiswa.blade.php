@extends('layouts.app')

@section('title', 'Daftar Grup Mahasiswa')

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
        margin-right: 15px;
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

    .komunikasi-submenu {
        margin-left: 15px;
    }

    .komunikasi-submenu .nav-link {
        padding: 8px 15px;
        font-size: 13px;
    }
    
    .main-content-area {
        padding-left: 15px;
    }
    
    .grup-card {
        transition: all 0.3s ease;
        border-radius: 10px;
        overflow: hidden;
        margin-bottom: 20px;
        border: none;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        position: relative;
    }
    
    .grup-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
    }
    
    .grup-card .card-header {
        background: linear-gradient(to right, #004AAD, #5DE0E6);
        color: white;
        border: none;
        padding: 15px;
    }
    
    .grup-card .card-body {
        padding: 20px;
    }
    
    .grup-info {
        display: flex;
        justify-content: space-between;
        margin-bottom: 15px;
    }
    
    .badge-custom {
        font-weight: normal;
        padding: 5px 10px;
        border-radius: 15px;
    }
    
    .custom-container {
        max-width: 1400px;
        margin: 0 auto;
        padding: 0 15px;
    }
</style>
@endpush

@section('content')
<div class="main-content">
    <div class="container-fluid">
        <div class="row g-4">
            <!-- Sidebar -->
            <div class="col-md-3">
                <div class="sidebar">
                    <div class="sidebar-buttons">
                        <a href="{{ url('/buatpesanmahasiswa') }}" class="btn" style="background: linear-gradient(to right, #004AAD, #5DE0E6); color: white; padding: 10px 20px; border: none; border-radius: 5px;">
                            <i class="fas fa-plus me-2"></i> Pesan Baru
                        </a>
                    </div>                                                    
                    
                    <div class="sidebar-menu">
                        <div class="nav flex-column">
                            <a href="{{ url('/dashboardpesanmahasiswa') }}" class="nav-link">
                                <i class="fas fa-home me-2"></i>Daftar Pesan
                            </a>
                            <a href="#" class="nav-link menu-item active" id="grupDropdownToggle">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span><i class="fas fa-users me-2"></i>Daftar Grup</span>
                                    <i class="fas fa-chevron-down" id="grupDropdownIcon"></i>
                                </div>
                            </a>
                            <div class="collapse show komunikasi-submenu" id="komunikasiSubmenu">
                                @if($grups && $grups->count() > 0)
                                    @foreach($grups as $grupItem)
                                    <a href="{{ route('mahasiswa.grup.show', $grupItem->id) }}" class="nav-link menu-item d-flex justify-content-between align-items-center">
                                        {{ $grupItem->nama_grup }}
                                        @if(isset($grupItem->unreadCount) && $grupItem->unreadCount > 0)
                                        <span class="badge bg-danger rounded-pill">{{ $grupItem->unreadCount }}</span>
                                        @endif
                                    </a>
                                    @endforeach
                                @else
                                    <div class="nav-link menu-item text-muted">
                                        <small>Belum ada grup</small>
                                    </div>
                                @endif
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

            <!-- Main Content Area -->
            <div class="col-md-9">
                <div class="main-content-area">
                    <h4 class="mb-4">Daftar Grup Anda</h4>
                    
                    <div class="row">
                        @if($grups && $grups->count() > 0)
                            @foreach($grups as $grup)
                            <div class="col-md-6 mb-4">
                                <div class="card grup-card">
                                    <div class="card-header">
                                        <h5 class="mb-0 fw-semibold">{{ $grup->nama_grup }}</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="grup-info">
                                            <span><i class="fas fa-users me-2"></i>{{ $grup->mahasiswa->count() }} anggota</span>
                                            <span class="badge badge-custom bg-primary">Dibuat oleh: {{ \App\Models\Dosen::find($grup->dosen_id)->nama_singkat }}</span>
                                        </div>
                                        <div class="grup-info">
                                            <span><i class="fas fa-calendar-alt me-2"></i>{{ $grup->created_at->format('d M Y') }}</span>
                                            @if($grup->unreadCount > 0)
                                            <span class="badge badge-custom bg-danger">{{ $grup->unreadCount }} pesan baru</span>
                                            @else
                                            <span class="badge badge-custom bg-success">Sudah dibaca</span>
                                            @endif
                                        </div>
                                        <a href="{{ route('mahasiswa.grup.show', $grup->id) }}" class="btn btn-primary w-100 mt-3">
                                            <i class="fas fa-eye me-2"></i>Lihat Grup
                                        </a>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        @else
                            <div class="col-12">
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle me-2"></i>Anda belum menjadi anggota grup apapun.
                                </div>
                            </div>
                        @endif
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
});
</script>
@endpush