@extends('layouts.app')

@section('title', 'Detail Grup - ' . $grup->nama_grup)

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

    .komunikasi-submenu {
        margin-left: 15px;
    }

    .komunikasi-submenu .nav-link {
        padding: 8px 15px;
        font-size: 13px;
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
    
    .btn-gradient-primary {
        background: linear-gradient(to right, #004AAD, #5DE0E6);
        border: none;
        color: white;
        transition: all 0.3s ease;
    }

    .btn-gradient-primary:hover {
        background: linear-gradient(to right, #003c8a, #4bc4c9);
        color: white;
        box-shadow: 0 2px 5px rgba(0,0,0,0.2);
    }
    
    .group-header {
        background: linear-gradient(to right, #004AAD, #5DE0E6);
        color: white;
        border-radius: 10px;
        padding: 20px;
        margin-bottom: 20px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }
    
    .message-container {
        max-height: calc(100vh - 350px);
        overflow-y: auto;
        margin-bottom: 20px;
        background-color: white;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        padding: 15px;
    }
    
    .message-input {
        position: sticky;
        bottom: 0;
        background: white;
        padding: 15px;
        border-radius: 10px;
        box-shadow: 0 -2px 10px rgba(0,0,0,0.05);
        margin-top: 15px;
    }

    .header-icon {
        color: white;
        font-size: 1.2rem;
        margin-left: 15px;
        cursor: pointer;
        opacity: 0.9;
        transition: all 0.3s ease;
        padding: 8px;
        border-radius: 50%;
    }

    .header-icon:hover {
        opacity: 1;
        background-color: rgba(255, 255, 255, 0.2);
        transform: scale(1.1);
    }

    .header-icon.danger:hover {
        color: #FF5252;
        background-color: rgba(255, 82, 82, 0.2);
    }
    
    /* Container untuk memberi jarak dari tepi - menggunakan custom-container dari dashboard pesan */
    .custom-container {
        max-width: 1400px;
        margin: 0 auto;
        padding: 0 15px;
    }
    
    .badge-notification {
        background: var(--bs-danger);
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
</style>
@endpush

@section('content')
<div class="main-content">
    <div class="custom-container">
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
                                @php
                                    $grups = Auth::user()->grups;
                                @endphp
                                
                                @if($grups && $grups->count() > 0)
                                    @foreach($grups as $grupItem)
                                    <a href="{{ route('mahasiswa.grup.show', $grupItem->id) }}" class="nav-link menu-item d-flex justify-content-between align-items-center {{ $grupItem->id == $grup->id ? 'active' : '' }}">
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
                <!-- Group Header -->
                <div class="group-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="mb-0 fw-semibold">{{ $grup->nama_grup }}</h5>
                            <small>{{ $grup->mahasiswa->count() }} anggota</small>
                        </div>
                        <div class="d-flex">
                            <i class="fas fa-users header-icon" data-bs-toggle="modal" data-bs-target="#anggotaGrupModal"></i>
                            <i class="fas fa-info-circle header-icon" data-bs-toggle="modal" data-bs-target="#infoGrupModal"></i>
                        </div>
                    </div>
                </div>
                
                <!-- Chat Content -->
                <div class="message-container">
                    <div class="text-center py-5 text-muted">
                        <i class="fas fa-comments fa-3x mb-3"></i>
                        <p>Belum ada pesan di grup ini.</p>
                        <p>Mulai percakapan dengan mengirim pesan!</p>
                    </div>
                </div>
                
                <!-- Message Input -->
                <div class="message-input">
                    <form>
                        <div class="input-group">
                            <button type="button" class="btn btn-light">
                                <i class="fas fa-paperclip"></i>
                            </button>
                            <input type="text" class="form-control" placeholder="Tulis Pesan Anda disini..">
                            <button type="submit" class="btn btn-gradient-primary">
                                <i class="fas fa-paper-plane"></i>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Info Grup Modal -->
<div class="modal fade" id="infoGrupModal" tabindex="-1" aria-labelledby="infoGrupModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="infoGrupModalLabel">Informasi Grup</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="fw-bold">Nama Grup:</label>
                    <p>{{ $grup->nama_grup }}</p>
                </div>
                <div class="mb-3">
                    <label class="fw-bold">Dibuat pada:</label>
                    <p>{{ $grup->created_at->format('d M Y, H:i') }}</p>
                </div>
                <div class="mb-3">
                    <label class="fw-bold">Jumlah Anggota:</label>
                    <p>{{ $grup->mahasiswa->count() }} mahasiswa</p>
                </div>
                <div class="mb-3">
                    <label class="fw-bold">Dibuat oleh:</label>
                    <p>{{ \App\Models\Dosen::find($grup->dosen_id)->nama }}</p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<!-- Anggota Grup Modal -->
<div class="modal fade" id="anggotaGrupModal" tabindex="-1" aria-labelledby="anggotaGrupModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="anggotaGrupModalLabel">Anggota Grup</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th width="5%">#</th>
                                <th width="15%">Foto</th>
                                <th width="40%">Nama</th>
                                <th width="25%">NIM</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($grup->mahasiswa as $index => $anggota)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>
                                    <div class="profile-image-placeholder">
                                        <i class="fas fa-user"></i>
                                    </div>
                                </td>
                                <td>{{ $anggota->nama }}</td>
                                <td>{{ $anggota->nim }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
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