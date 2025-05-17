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
    
    /* Custom toast/alert styling */
    .custom-toast {
        position: fixed;
        top: 20px;
        right: 20px;
        max-width: 350px;
        z-index: 1060;
        background: white;
        border-radius: 8px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        overflow: hidden;
        opacity: 0;
        transform: translateY(-15px);
        transition: all 0.3s ease;
    }
    
    .custom-toast.show {
        opacity: 1;
        transform: translateY(0);
    }
    
    .custom-toast-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 10px 15px;
        border-bottom: 1px solid #e9ecef;
        background-color: #f8f9fa;
    }
    
    .custom-toast-body {
        padding: 15px;
    }
    
    /* Success toast styling */
    .custom-toast-success {
        border-left: 4px solid var(--bs-success);
    }
    
    .custom-toast-success .custom-toast-header {
        color: var(--bs-success);
    }
    
    /* Error toast styling */
    .custom-toast-error {
        border-left: 4px solid var(--bs-danger);
    }
    
    .custom-toast-error .custom-toast-header {
        color: var(--bs-danger);
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
</style>
@endpush

@section('content')
<div class="main-content">
    <div class="custom-container">
        <div class="row g-4">
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
                                <i class="fas fa-question-circle me-2"></i>FAQ
                            </a>
                            
                            <!-- Menu Pengaturan dengan Dropdown -->
                            @if(!empty(Auth::guard('dosen')->user()->jabatan_fungsional) && 
                                (stripos(Auth::guard('dosen')->user()->jabatan_fungsional, 'kaprodi') !== false || 
                                 stripos(Auth::guard('dosen')->user()->jabatan_fungsional, 'ketua') !== false))
                                <a href="#" class="nav-link menu-item" id="pengaturanDropdownToggle">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span><i class="fas fa-cog me-2"></i>Pengelola</span>
                                        <i class="fas fa-chevron-down" id="pengaturanDropdownIcon"></i>
                                    </div>
                                </a>
                                <div class="collapse pengaturan-submenu" id="pengaturanSubmenu">
                                    <form action="{{ route('dosen.switch-role') }}" method="POST" id="switchRoleForm" style="width: 100%;">
                                        @csrf
                                        <button type="submit" class="btn-link nav-link">
                                            <i class="fas {{ session('active_role') === 'kaprodi' ? 'fa-chalkboard-teacher' : 'fa-user-tie' }} me-2"></i>
                                            Mode {{ session('active_role') === 'kaprodi' ? 'Dosen' : 'Kaprodi' }}
                                        </button>
                                    </form>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Content -->
            <div class="col-md-9">
                <!-- Indikator peran aktif -->
                @if(session('is_kaprodi'))
                <div class="mb-3">
                    <span class="role-badge {{ session('active_role') === 'kaprodi' ? 'bg-primary' : 'bg-secondary' }} text-white">
                        <i class="{{ session('active_role') === 'kaprodi' ? 'fas fa-user-tie' : 'fas fa-chalkboard-teacher' }} me-1"></i>
                        Mode: {{ session('active_role') === 'kaprodi' ? 'Kaprodi' : 'Dosen' }}
                    </span>
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
                    <div id="no-results" class="text-center py-4 d-none">
                        <p class="text-muted">Tidak ada pesan yang sesuai dengan filter atau pencarian</p>
                    </div>
                    
                    @if($pesan->count() > 0)
                        @foreach($pesan as $item)
                        <div class="card mb-2 message-card {{ strtolower($item->prioritas) }}" onclick="window.location.href='{{ route('dosen.pesan.show', $item->id) }}';" style="cursor: pointer;">
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col-md-8 d-flex align-items-center">
                                        <div class="profile-image-placeholder me-3">
                                            <i class="fas fa-user"></i>
                                        </div>
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
                                            {{ \Carbon\Carbon::parse($item->created_at)->timezone('Asia/Jakarta')->diffForHumans() }}
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
                            <p class="text-muted">Belum ada pesan</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Custom Toast untuk notifikasi pindah peran -->
<div class="custom-toast custom-toast-success" id="roleToast">
    <div class="custom-toast-header">
        <strong><i class="fas fa-check-circle me-2"></i> Berhasil</strong>
        <button type="button" class="btn-close" onclick="closeToast()"></button>
    </div>
    <div class="custom-toast-body">
        Mode berhasil diubah menjadi <strong id="roleText">Dosen</strong>
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
    
    // Initialize the pengaturan dropdown manually
    const pengaturanDropdownToggle = document.getElementById('pengaturanDropdownToggle');
    const pengaturanSubmenu = document.getElementById('pengaturanSubmenu');
    const pengaturanDropdownIcon = document.getElementById('pengaturanDropdownIcon');
    
    if (pengaturanDropdownToggle && pengaturanSubmenu && pengaturanDropdownIcon) {
        pengaturanDropdownToggle.addEventListener('click', function(e) {
            e.preventDefault();
            
            // Toggle the collapse
            if (pengaturanSubmenu.classList.contains('show')) {
                pengaturanSubmenu.classList.remove('show');
                pengaturanDropdownIcon.classList.remove('fa-chevron-up');
                pengaturanDropdownIcon.classList.add('fa-chevron-down');
            } else {
                pengaturanSubmenu.classList.add('show');
                pengaturanDropdownIcon.classList.remove('fa-chevron-down');
                pengaturanDropdownIcon.classList.add('fa-chevron-up');
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
    
    // Tambahkan pengendali peristiwa ke form perpindahan peran untuk menampilkan toast
    const switchRoleForm = document.getElementById('switchRoleForm');
    if (switchRoleForm) {
        switchRoleForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Ambil peran yang akan diaktifkan (kebalikan dari peran aktif saat ini)
            const currentRole = "{{ session('active_role') }}";
            const newRole = currentRole === 'kaprodi' ? 'Dosen' : 'Kaprodi';
            
            // Perbarui teks peran di toast
            document.getElementById('roleText').textContent = newRole;
            
            // Tampilkan toast
            showToast();
            
            // Kirim form setelah sedikit penundaan agar toast terlihat
            setTimeout(() => {
                this.submit();
            }, 1000);
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

// Fungsi untuk menampilkan toast
function showToast() {
    const toast = document.getElementById('roleToast');
    toast.classList.add('show');
    
    // Sembunyikan toast secara otomatis setelah 5 detik
    setTimeout(() => {
        closeToast();
    }, 5000);
}

// Fungsi untuk menutup toast
function closeToast() {
    const toast = document.getElementById('roleToast');
    toast.classList.remove('show');
}
</script>
@endpush