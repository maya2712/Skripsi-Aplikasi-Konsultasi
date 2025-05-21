@extends('layouts.app')

@section('title', 'Dashboard Pesan Dosen')

@push('styles')
<style>
    :root {
        --bs-primary: #1a73e8;
        --bs-danger: #FF5252;
        --bs-success: #27AE60;
        --text-color: #546E7A; /* Warna teks menu utama */
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
        width: 100%;
        text-align: left;
    }
    
    .sidebar-menu .nav-link.active {
        background: #E3F2FD;
        color: var(--bs-primary);
    }
    
    .sidebar-menu .nav-link:hover:not(.active) {
        background: #f8f9fa;
    }

    .komunikasi-submenu .nav-link.active,
    .pengaturan-submenu .nav-link.active {
        background: #E3F2FD;
        color: var(--bs-primary);
    }

    .komunikasi-submenu .nav-link:hover:not(.active),
    .pengaturan-submenu .nav-link:hover:not(.active) {
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

    .komunikasi-submenu,
    .pengaturan-submenu {
        margin-left: 15px;
        width: 100%;
    }

    .komunikasi-submenu .nav-link,
    .pengaturan-submenu .nav-link {
        padding: 8px 15px;
        font-size: 13px;
        width: 100%;
        color: #546E7A; /* Warna teks menu yang konsisten */
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
    
    .bookmark-icon {
        color: #ffc107;
        cursor: pointer;
    }
    
    .bookmark-icon.active {
        color: #ffc107;
    }
    
    .bookmark-icon:not(.active) {
        color: #dee2e6;
    }
    
    /* Style baru untuk message card clickable */
    .message-card {
        cursor: pointer;
        transition: all 0.2s ease;
    }
    
    .message-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }
    
    /* Memastikan tombol-tombol tetap berfungsi dan tidak mengganggu card clickable */
    .message-card .btn, 
    .message-card .bookmark-icon {
        position: relative;
        z-index: 10;
    }
    
    .action-buttons {
        position: relative;
        z-index: 10;
    }
    
    /* Style untuk role badge */
    .role-badge {
        display: inline-block;
        padding: 5px 10px;
        border-radius: 30px;
        font-size: 12px;
        margin-bottom: 10px;
    }
    
    /* Custom modal/pop-up styling */
    .role-modal-backdrop {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        z-index: 1050;
        display: flex;
        align-items: center;
        justify-content: center;
        opacity: 0;
        visibility: hidden;
        transition: all 0.3s ease;
    }
    
    .role-modal-backdrop.show {
        opacity: 1;
        visibility: visible;
    }
    
    .role-modal {
        background: white;
        border-radius: 10px;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
        width: 90%;
        max-width: 400px;
        transform: scale(0.8);
        transition: transform 0.3s ease;
        overflow: hidden;
        text-align: center;
    }
    
    .role-modal-backdrop.show .role-modal {
        transform: scale(1);
    }
    
    .role-modal-header {
        padding: 15px;
        border-bottom: 1px solid #e9ecef;
        position: relative;
    }
    
    .role-modal-body {
        padding: 20px;
    }
    
    .role-modal-icon {
        width: 70px;
        height: 70px;
        margin: 0 auto 15px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 30px;
    }
    
    .role-modal-success .role-modal-header {
        background-color: #E3F2FD;
        color: var(--bs-primary);
    }
    
    .role-modal-success .role-modal-icon {
        background-color: #E3F2FD;
        color: var(--bs-primary);
    }
    
    .role-modal-error .role-modal-header {
        background-color: #FFEBEE;
        color: var(--bs-danger);
    }
    
    .role-modal-error .role-modal-icon {
        background-color: #FFEBEE;
        color: var(--bs-danger);
    }
    
    .role-modal-title {
        font-size: 18px;
        font-weight: 600;
        margin: 0;
    }
    
    .role-modal-message {
        font-size: 16px;
        margin-bottom: 0;
    }
    
    .role-modal-footer {
        padding: 10px 20px 20px;
    }
    
    .btn-role-modal {
        padding: 8px 20px;
        border-radius: 30px;
        font-weight: 500;
        border: none;
        transition: all 0.3s ease;
    }
    
    .btn-role-modal-primary {
        background: linear-gradient(to right, #1a73e8, #3f9cff);
        color: white;
    }
    
    .btn-role-modal-primary:hover {
        background: linear-gradient(to right, #1557b0, #1a73e8);
        box-shadow: 0 4px 10px rgba(26, 115, 232, 0.3);
        color: white;
    }
    
    /* Animasi loading spinner */
    .role-loading-spinner {
        width: 40px;
        height: 40px;
        margin: 0 auto 15px;
        border: 4px solid rgba(0, 0, 0, 0.1);
        border-left-color: var(--bs-primary);
        border-radius: 50%;
        animation: spin 1s linear infinite;
    }
    
    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
    
    /* Style untuk submenu mode peran */
    .pengaturan-submenu .btn-link {
        padding: 8px 15px;
        display: flex;
        align-items: center;
        text-decoration: none;
        border: none;
        background: none;
        font-size: 13px;
        width: 100%;
        text-align: left;
        margin: 0;
        border-radius: 0.5rem;
        color: #546E7A; /* Warna teks abu-abu yang konsisten */
    }
    
    .pengaturan-submenu .btn-link:hover {
        background-color: #f8f9fa;
        color: #546E7A; /* Warna teks saat hover - tetap konsisten */
    }
    
    /* Style untuk mode switcher */
    .mode-switcher-container {
        background: linear-gradient(to right, #0858b2, #53d1e0);
        color: white;
        padding: 15px 20px;
        border-radius: 10px;
        margin-bottom: 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    
    .mode-switcher-container.kaprodi {
        background: linear-gradient(to right, #53d1e0, #0858b2);
    }
    
    .mode-title {
        font-size: 16px;
        font-weight: 600;
        margin: 0;
    }
    
    .switch {
        position: relative;
        display: inline-block;
        width: 56px;
        height: 28px;
    }
    
    .switch input {
        opacity: 0;
        width: 0;
        height: 0;
    }
    
    .slider {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: #f0f2f0;
        transition: .4s;
        border-radius: 34px;
    }
    
    /* Style untuk mode Dosen (default) - bulat di kiri */
    .slider:before {
        position: absolute;
        content: "";
        height: 20px;
        width: 20px;
        left: 4px;
        bottom: 4px;
        background-color: #0858b2;
        transition: .4s;
        border-radius: 50%;
    }
    
    /* Style untuk mode Kaprodi - bulat di kanan (dengan !important) */
    .mode-switcher-container.kaprodi .slider:before {
        left: auto !important;
        right: 4px !important;
        background-color: #0cc0df !important;
    }
    
    .switch-labels span {
        z-index: 1;
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
                        <a href="{{ route('dosen.pesan.create') }}" class="btn" style="background: linear-gradient(to right, #004AAD, #5DE0E6); color: white; padding: 10px 20px; border: none; border-radius: 5px;">
                            <i class="fas fa-plus me-2"></i> Pesan Baru
                        </a>
                    </div>
                    
                    <div class="sidebar-menu">
                        <div class="nav flex-column">
                            <a href="{{ route('dosen.dashboard.pesan') }}" class="nav-link active">
                                <i class="fas fa-home me-2"></i>Daftar Pesan
                            </a>

                            <a href="#" class="nav-link menu-item" id="grupDropdownToggle">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span><i class="fas fa-users me-2"></i>Daftar Grup</span>
                                    <i class="fas fa-chevron-down" id="grupDropdownIcon"></i>
                                </div>
                            </a>
                            <div class="collapse komunikasi-submenu" id="komunikasiSubmenu">
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
                                    @foreach($grups as $grup)
                                    <a href="{{ route('dosen.grup.show', $grup->id) }}" class="nav-link menu-item d-flex justify-content-between align-items-center">
                                        {{ $grup->nama_grup }}
                                        @if($unreadCount = $grup->unreadMessages ?? 0)
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
            </div>

            <!-- Main Content -->
            <div class="col-md-9">
                <!-- Mode Switcher - Perubahan sesuai desain baru -->
                @if(!empty(Auth::guard('dosen')->user()->jabatan_fungsional) && 
                    (stripos(Auth::guard('dosen')->user()->jabatan_fungsional, 'kaprodi') !== false || 
                     stripos(Auth::guard('dosen')->user()->jabatan_fungsional, 'ketua') !== false))
                <div class="mode-switcher-container {{ session('active_role') === 'kaprodi' ? 'kaprodi' : '' }}">
                    <h5 class="mode-title">
                        Mode {{ session('active_role') === 'kaprodi' ? 'Kaprodi' : 'Dosen' }}
                    </h5>
                    <form action="{{ route('dosen.switch-role') }}" method="POST" id="switchRoleForm">
                        @csrf
                        <label class="switch">
                            <input type="checkbox" id="roleSwitcher">
                            <span class="slider"></span>
                        </label>
                    </form>
                </div>
                @endif
                
                <!-- Stats Cards -->
                <div class="stats-cards row g-3 mb-4">
                    <div class="col-md-4">
                        <div class="card h-100">
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
                    <div class="col-md-4">
                        <div class="card h-100">
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
                    
                    <div class="col-md-4">
                        <div class="card h-100">
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
                                    <button class="btn btn-outline-danger rounded-pill px-4 py-2 me-2 filter-btn" data-filter="penting" style="font-size: 14px;">Penting</button>
                                    <button class="btn btn-outline-success rounded-pill px-4 py-2 me-2 filter-btn" data-filter="umum" style="font-size: 14px;">Umum</button>
                                    <button class="btn btn-outline-primary rounded-pill px-4 py-2 filter-btn" data-filter="semua" style="font-size: 14px;">Semua</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Message List -->
                <div class="message-list" id="messageList">
                    <!-- Tambahkan elemen untuk menampilkan pesan "tidak ada hasil" -->
                    
                    
                    @if($pesan->count() > 0)
                        @foreach($pesan as $item)
                        <div class="card mb-2 message-card {{ strtolower($item->prioritas) }}" onclick="window.location.href='{{ route('dosen.pesan.show', $item->id) }}';" style="cursor: pointer;">
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col-md-8 d-flex align-items-center">
                                        @if($item->nip_pengirim == Auth::user()->nip)
                                            <!-- Menampilkan foto mahasiswa penerima -->
                                            @php
                                                $mahasiswa = App\Models\Mahasiswa::where('nim', $item->nim_penerima)->first();
                                                $profilePhoto = $mahasiswa && $mahasiswa->profile_photo ? asset('storage/profile_photos/'.$mahasiswa->profile_photo) : null;
                                            @endphp
                                            @if($profilePhoto)
                                                <img src="{{ $profilePhoto }}" alt="Foto Profil" class="profile-image me-3">
                                            @else
                                                <div class="profile-image-placeholder me-3">
                                                    <i class="fas fa-user"></i>
                                                </div>
                                            @endif
                                        @else
                                            <!-- Menampilkan foto mahasiswa pengirim -->
                                            @php
                                                $mahasiswa = App\Models\Mahasiswa::where('nim', $item->nim_pengirim)->first();
                                                $profilePhoto = $mahasiswa && $mahasiswa->profile_photo ? asset('storage/profile_photos/'.$mahasiswa->profile_photo) : null;
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
                                            
                                            <span class="badge bg-primary mb-1">{{ $item->subjek }}</span>
                                            
                                            @if($item->nip_pengirim == Auth::user()->nip)
                                                <!-- Jika dosen adalah pengirim, tampilkan nama mahasiswa penerima -->
                                                <h6 class="mb-1" style="font-size: 14px;">
                                                    <span class="badge bg-info me-1" style="font-size: 10px;">Kepada</span>
                                                    @php
                                                        // Ambil langsung data mahasiswa penerima
                                                        $mahasiswa = App\Models\Mahasiswa::where('nim', $item->nim_penerima)->first();
                                                        $nama_penerima = $mahasiswa ? $mahasiswa->nama : 'Mahasiswa';
                                                    @endphp
                                                    {{ $nama_penerima }}
                                                </h6>
                                                <small class="text-muted">{{ $item->nim_penerima }}</small>
                                            @else
                                                <!-- Jika dosen adalah penerima, tampilkan nama mahasiswa pengirim -->
                                                <h6 class="mb-1" style="font-size: 14px;">
                                                    <span class="badge bg-info me-1" style="font-size: 10px;">Dari</span>
                                                    @php
                                                        // Ambil langsung data mahasiswa pengirim
                                                        $mahasiswa = App\Models\Mahasiswa::where('nim', $item->nim_pengirim)->first();
                                                        $nama_pengirim = $mahasiswa ? $mahasiswa->nama : 'Mahasiswa';
                                                    @endphp
                                                    {{ $nama_pengirim }}
                                                </h6>
                                                <small class="text-muted">{{ $item->nim_pengirim }}</small>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-4 text-md-end mt-3 mt-md-0">
                                        @php
                                            // Hitung jumlah balasan yang belum dibaca dengan Query Builder
                                            $unreadReplies = App\Models\BalasanPesan::where('id_pesan', $item->id)
                                                ->where('dibaca', false)
                                                ->where('tipe_pengirim', 'mahasiswa') // Hanya balasan dari mahasiswa
                                                ->count();
                                            
                                            // Tentukan status badge - Gunakan pengecekan yang lebih ketat
                                            $badgeClass = 'bg-success';
                                            $badgeText = 'Sudah dibaca';
                                            
                                            if ($item->nip_penerima == Auth::user()->nip && $item->dibaca == false) {
                                                // Pesan utama belum dibaca oleh dosen (sebagai penerima)
                                                $badgeClass = 'bg-danger';
                                                $badgeText = 'Belum dibaca';
                                            } 
                                            elseif ($unreadReplies > 0) {
                                                // Ada balasan baru dari mahasiswa yang belum dibaca
                                                $badgeClass = 'bg-danger';
                                                $badgeText = $unreadReplies . ' balasan baru';
                                            }
                                        @endphp
                                        
                                        <!-- Status dibaca/balasan baru dalam satu badge -->
                                        <span class="badge {{ $badgeClass }} me-1">
                                            {{ $badgeText }}
                                        </span>
                                        
                                        <span class="badge {{ $item->prioritas == 'Penting' ? 'bg-danger' : 'bg-success' }}">
                                            {{ $item->prioritas }}
                                        </span>
                                        
                                        <small class="d-block text-muted my-1">
                                            {{ \Carbon\Carbon::parse($item->created_at)->diffForHumans() }}
                                        </small>
                                        
                                        <div class="action-buttons" onclick="event.stopPropagation();">
                                            @if(isset($item->bookmarked))
                                            <form action="{{ route('dosen.pesan.bookmark', $item->id) }}" method="POST" class="d-inline me-2">
                                                @csrf
                                                <button type="submit" class="btn btn-link p-0 bookmark-btn" title="{{ $item->bookmarked ? 'Hapus Bookmark' : 'Bookmark Pesan' }}">
                                                    <i class="fas fa-bookmark bookmark-icon {{ $item->bookmarked ? 'active' : '' }}"></i>
                                                </button>
                                            </form>
                                            @endif
                                            
                                            <a href="{{ route('dosen.pesan.show', $item->id) }}" class="btn btn-custom-primary btn-sm view-btn" style="font-size: 10px;">
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
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Role Switcher - Hanya menampilkan loading spinner -->
<div class="role-modal-backdrop" id="roleModalBackdrop">
    <div class="role-modal role-modal-success">
        <div class="role-modal-body py-4">
            <div class="role-loading-spinner" id="roleLoadingSpinner"></div>
            <p class="role-modal-message mt-3" id="roleModalMessage">Memuat...</p>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Inisialisasi dropdown Bootstrap secara manual dan benar
    var dropdownElementList = [].slice.call(document.querySelectorAll('.dropdown-toggle'));
    dropdownElementList.forEach(function(dropdownToggleEl) {
        new bootstrap.Dropdown(dropdownToggleEl);
    });
    
    // Initialize the grup dropdown manually
    const grupDropdownToggle = document.getElementById('grupDropdownToggle');
    const komunikasiSubmenu = document.getElementById('komunikasiSubmenu');
    const grupDropdownIcon = document.getElementById('grupDropdownIcon');
    
    if (grupDropdownToggle && komunikasiSubmenu && grupDropdownIcon) {
        grupDropdownToggle.addEventListener('click', function(e) {
            e.preventDefault();
            
            // Toggle the collapse
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
        const noResults = document.getElementById('no-results');
        if (noResults) {
            noResults.style.display = visibleCount === 0 ? 'block' : 'none';
            if (visibleCount === 0) {
                noResults.classList.remove('d-none');
            } else {
                noResults.classList.add('d-none');
            }
        }
    }
    
    // Fungsi pencarian pesan
    function searchMessages(searchTerm) {
        // Dapatkan filter aktif saat ini
        const activeFilter = document.querySelector('.filter-btn.active')?.dataset.filter || 'semua';
        
        const messageCards = document.querySelectorAll('.message-card');
        let visibleCount = 0;
        
        messageCards.forEach(card => {
            const messageText = card.textContent.toLowerCase();
            const isPenting = card.classList.contains('penting');
            const isUmum = card.classList.contains('umum');
            
            // Kombinasikan filter pencarian dengan filter prioritas
            const matchesSearch = messageText.includes(searchTerm);
            const matchesFilter = activeFilter === 'semua' || 
                                 (activeFilter === 'penting' && isPenting) || 
                                 (activeFilter === 'umum' && isUmum);
            
            if (matchesSearch && matchesFilter) {
                card.style.display = 'block';
                visibleCount++;
            } else {
                card.style.display = 'none';
            }
        });
        
        // Tampilkan pesan "tidak tersedia" jika tidak ada pesan yang sesuai pencarian
        const noResults = document.getElementById('no-results');
        if (noResults) {
            noResults.style.display = visibleCount === 0 ? 'block' : 'none';
            if (visibleCount === 0) {
                noResults.classList.remove('d-none');
            } else {
                noResults.classList.add('d-none');
            }
        }
    }
    
    // Pencarian pesan
    const searchInput = document.getElementById('searchInput');
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            searchMessages(searchTerm);
        });
    }
    
    // Menghentikan propagasi klik pada tombol-tombol di dalam card
    document.querySelectorAll('.action-buttons').forEach(button => {
        button.addEventListener('click', function(e) {
            e.stopPropagation();
        });
    });
    
    // Tambahkan event listener pada tombol filter
    const filterButtons = document.querySelectorAll('.filter-btn');
    filterButtons.forEach(button => {
        button.addEventListener('click', function() {
            // Hapus class active dari semua tombol
            filterButtons.forEach(btn => btn.classList.remove('active'));
            
            // Tambahkan class active ke tombol yang diklik
            this.classList.add('active');
            
            // Filter pesan berdasarkan tombol yang diklik
            const filter = this.dataset.filter;
            filterMessages(filter);
        });
    });
    
    // Set default filter ke "semua" saat halaman dimuat
    window.addEventListener('load', function() {
        // Hapus kelas active dari semua tombol filter
        filterButtons.forEach(btn => btn.classList.remove('active'));
        
        // Tambahkan kelas active ke tombol filter "semua"
        const semuaFilterBtn = document.querySelector('.filter-btn[data-filter="semua"]');
        if (semuaFilterBtn) {
            semuaFilterBtn.classList.add('active');
            // Aktifkan filter semua
            filterMessages('semua');
        }
    });
    
    // Role switcher toggle
    const roleSwitcher = document.getElementById('roleSwitcher');
    if (roleSwitcher) {
        roleSwitcher.addEventListener('change', function() {
            // Tampilkan loading spinner
            showRoleModal('Memuat...');
            
            // Submit form langsung setelah delay singkat
            setTimeout(() => {
                document.getElementById('switchRoleForm').submit();
            }, 500);
        });
    }
    
    // Periksa apakah ada pesan sukses dari backend (dari session)
    // dan jika ada, hilangkan setelah beberapa detik
    const successAlert = document.querySelector('.alert-success');
    if (successAlert) {
        setTimeout(() => {
            successAlert.remove();
        }, 5000);
    }
});

// Fungsi untuk menampilkan modal loading sederhana
function showRoleModal(message) {
    const modal = document.getElementById('roleModalBackdrop');
    if (modal) {
        // Perbarui pesan jika ada
        if (message) {
            document.getElementById('roleModalMessage').textContent = message;
        }
        
        // Tampilkan modal
        modal.classList.add('show');
    }
}
</script>
@endpush