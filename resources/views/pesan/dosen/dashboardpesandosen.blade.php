@extends('layouts.app')

@section('title', 'Dashboard Pesan Dosen')

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
        position: relative; /* Keep this for positioning */
    }
    
    /* Removed the :before styling that created the line effect */
    
    .sidebar-menu .nav-link.active {
        background: #E3F2FD;
        color: var(--bs-primary);
    }
    
    .sidebar-menu .nav-link:hover:not(.active) {
        background: #f8f9fa;
    }
    
    /* Removed the :hover:before that made the line appear */

    .komunikasi-submenu .nav-link.active {
        background: #E3F2FD;
        color: var(--bs-primary);
    }

    .komunikasi-submenu .nav-link:hover:not(.active) {
        background: #f8f9fa;
    }
    
    /* Removed the :before styling for submenu */
    
    /* Removed the :hover:before for submenu */

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
                                <a href="{{ route('dosen.grup.create') }}" class="nav-link menu-item d-flex align-items-center" style="color: #546E7A;">
                                    <i class="fas fa-plus me-2"></i>Grup Baru
                                </a>
                                
                                @php
                                    $grups = App\Models\Grup::where('dosen_id', Auth::user()->nip)->get();
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

                <!-- Message List - PERBAIKAN -->
                <div class="message-list" id="messageList">
                    @if($pesan->count() > 0)
                        @foreach($pesan as $item)
                        <div class="card mb-2 message-card {{ $item->prioritas == 'Penting' ? 'penting' : 'umum' }}" onclick="window.location.href='{{ route('dosen.pesan.show', $item->id) }}'">
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
                                        @if(!$item->dibaca)
                                            <span class="badge bg-danger me-1">Belum dibaca</span>
                                        @else
                                            <span class="badge bg-success me-1">Sudah dibaca</span>
                                        @endif
                                        
                                        <span class="badge {{ $item->prioritas == 'Penting' ? 'bg-danger' : 'bg-success' }}">
                                            {{ $item->prioritas }}
                                        </span>
                                        
                                        <small class="d-block text-muted my-1">
                                            {{ \Carbon\Carbon::parse($item->created_at)->diffForHumans() }}
                                        </small>
                                        
                                        <div class="d-flex justify-content-end align-items-center action-buttons" onclick="event.stopPropagation();">
                                            <form action="{{ route('dosen.pesan.bookmark', $item->id) }}" method="POST" class="d-inline me-2">
                                                @csrf
                                                <button type="submit" class="btn btn-link p-0" title="{{ $item->bookmarked ? 'Hapus Bookmark' : 'Bookmark Pesan' }}">
                                                    <i class="fas fa-bookmark bookmark-icon {{ $item->bookmarked ? 'active' : '' }}"></i>
                                                </button>
                                            </form>
                                            
                                            <a href="{{ route('dosen.pesan.show', $item->id) }}" class="btn btn-custom-primary btn-sm" style="font-size: 10px;">
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
    
    // Fungsi pencarian pesan
    function searchMessages(searchTerm) {
        // Dapatkan filter aktif saat ini
        const activeFilter = document.querySelector('.filter-btn.active').dataset.filter;
        
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
        document.getElementById('no-results').style.display = visibleCount === 0 ? 'block' : 'none';
    }
    
    // Pencarian pesan
    const searchInput = document.getElementById('searchInput');
    searchInput.addEventListener('input', function() {
        const searchTerm = this.value.toLowerCase();
        searchMessages(searchTerm);
    });
    
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
});
</script>
@endpush