@extends('layouts.app')
@section('title', 'Riwayat Pesan Dosen')
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
        
        .custom-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 15px;
        }
        
        .card {
            margin-bottom: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
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
        
        /* Hide pesan yang tidak sesuai dengan filter */
        .message-card.hidden {
            display: none;
        }
        
        /* Pesan not found */
        .no-messages-found {
            text-align: center;
            padding: 30px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            margin-top: 20px;
            display: none;
        }
        
        /* Style untuk tombol filter */
        /* Filter Penting */
        .btn-outline-danger.btn-filter {
            color: #FF5252;
            border-color: #FF5252;
            transition: all 0.2s ease;
        }
        
        .btn-outline-danger.btn-filter:hover,
        .btn-outline-danger.btn-filter.active-filter {
            background-color: #FF5252;
            color: white;
            border-color: #FF5252;
        }
        
        /* Filter Umum */
        .btn-outline-success.btn-filter {
            color: #27AE60;
            border-color: #27AE60;
            transition: all 0.2s ease;
        }
        
        .btn-outline-success.btn-filter:hover,
        .btn-outline-success.btn-filter.active-filter {
            background-color: #27AE60;
            color: white;
            border-color: #27AE60;
        }
        
        /* Filter Semua */
        .btn-outline-primary.btn-filter {
            color: #1a73e8;
            border-color: #1a73e8;
            transition: all 0.2s ease;
        }
        
        .btn-outline-primary.btn-filter:hover,
        .btn-outline-primary.btn-filter.active-filter {
            background-color: #1a73e8;
            color: white;
            border-color: #1a73e8;
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
                            <a href="{{ route('dosen.dashboard.pesan') }}" class="nav-link">
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
                            
                            <a href="{{ route('dosen.pesan.history') }}" class="nav-link active">
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
                <h4 class="mb-4">Riwayat Pesan</h4>
                
                <!-- Search and Filters -->
                <div class="card mb-4 search-filter-card">
                    <div class="card-body">
                        <div class="row g-3 align-items-center">
                            <div class="col-md">
                                <input type="text" class="form-control" id="searchInput" placeholder="Cari Pesan..." style="font-size: 14px;">
                            </div>
                            <div class="col-md-auto">
                                <div class="btn-group">
                                    <button class="btn btn-outline-danger rounded-pill px-4 py-2 me-2 btn-filter" id="filterPenting" data-filter="penting" style="font-size: 14px;">Penting</button>
                                    <button class="btn btn-outline-success rounded-pill px-4 py-2 me-2 btn-filter" id="filterUmum" data-filter="umum" style="font-size: 14px;">Umum</button>
                                    <button class="btn btn-outline-primary rounded-pill px-4 py-2 btn-filter active-filter" id="filterSemua" data-filter="semua" style="font-size: 14px;">Semua</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Riwayat Pesan List -->
                <div class="messages-wrapper" id="messagesContainer">
                    @if($riwayatPesan->count() > 0)
                        @foreach($riwayatPesan as $pesan)
                        <div class="card mb-2 message-card {{ strtolower($pesan->prioritas) }}" data-kategori="{{ strtolower($pesan->prioritas) }}" 
                             data-pengirim="{{ $pesan->nip_pengirim == Auth::user()->nip ? ($pesan->mahasiswaPenerima->nama ?? 'Mahasiswa') : ($pesan->mahasiswaPengirim->nama ?? 'Pengirim') }}" 
                             data-judul="{{ $pesan->subjek }}" 
                             onclick="window.location.href='{{ route('dosen.pesan.show', $pesan->id) }}'">
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col-md-8 d-flex align-items-center">
                                        <div class="profile-image-placeholder me-3">
                                            <i class="fas fa-user"></i>
                                        </div>
                                        <div>
                                            <span class="badge bg-primary mb-1">{{ $pesan->subjek }}</span>
                                            
                                            @if($pesan->nip_pengirim == Auth::user()->nip)
                                                <!-- Jika dosen adalah pengirim, tampilkan nama mahasiswa penerima -->
                                                <h6 class="mb-1" style="font-size: 14px;">
                                                    <span class="badge bg-info me-1" style="font-size: 10px;">Kepada</span>
                                                    @php
                                                        // Ambil langsung data mahasiswa penerima
                                                        $mahasiswa = App\Models\Mahasiswa::where('nim', $pesan->nim_penerima)->first();
                                                        $nama_penerima = $mahasiswa ? $mahasiswa->nama : 'Mahasiswa';
                                                    @endphp
                                                    {{ $nama_penerima }}
                                                </h6>
                                                <small class="text-muted">{{ $pesan->nim_penerima }}</small>
                                            @else
                                                <!-- Jika dosen adalah penerima, tampilkan nama mahasiswa pengirim -->
                                                <h6 class="mb-1" style="font-size: 14px;">
                                                    <span class="badge bg-info me-1" style="font-size: 10px;">Dari</span>
                                                    @php
                                                        // Ambil langsung data mahasiswa pengirim
                                                        $mahasiswa = App\Models\Mahasiswa::where('nim', $pesan->nim_pengirim)->first();
                                                        $nama_pengirim = $mahasiswa ? $mahasiswa->nama : 'Mahasiswa';
                                                    @endphp
                                                    {{ $nama_pengirim }}
                                                </h6>
                                                <small class="text-muted">{{ $pesan->nim_pengirim }}</small>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-4 text-md-end mt-3 mt-md-0">
                                        <span class="badge bg-secondary me-1">Diakhiri</span>
                                        <span class="badge {{ $pesan->prioritas == 'Penting' ? 'bg-danger' : 'bg-success' }}">
                                            {{ $pesan->prioritas }}
                                        </span>
                                        
                                        <small class="d-block text-muted my-1">
                                            {{ \Carbon\Carbon::parse($pesan->updated_at)->format('d M Y') }}
                                        </small>
                                        
                                        <div class="d-flex justify-content-end align-items-center action-buttons" onclick="event.stopPropagation();">
                                            <form action="{{ route('dosen.pesan.bookmark', $pesan->id) }}" method="POST" class="d-inline me-2">
                                                @csrf
                                                <button type="submit" class="btn btn-link p-0" title="{{ $pesan->bookmarked ? 'Hapus Bookmark' : 'Bookmark Pesan' }}">
                                                    <i class="fas fa-bookmark bookmark-icon {{ $pesan->bookmarked ? 'active' : '' }}"></i>
                                                </button>
                                            </form>
                                            
                                            <a href="{{ route('dosen.pesan.show', $pesan->id) }}" class="btn btn-custom-primary btn-sm" style="font-size: 10px;">
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
                            <div class="p-4 text-center">
                                <i class="fas fa-history fa-3x mb-3 text-muted"></i>
                                <h5>Belum ada riwayat pesan</h5>
                                <p class="text-muted">Riwayat pesan yang telah diakhiri akan muncul di sini</p>
                            </div>
                        </div>
                    @endif
                    
                    <!-- Pesan tidak ditemukan -->
                    <div class="no-messages-found" id="noMessagesFound">
                        <div class="p-4 text-center">
                            <i class="fas fa-search fa-3x mb-3 text-muted"></i>
                            <h5>Tidak ada pesan yang ditemukan</h5>
                            <p class="text-muted">Coba ubah kata kunci pencarian atau filter</p>
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
        // Initialize the grup dropdown manually
        const grupDropdownToggle = document.getElementById('grupDropdownToggle');
        const komunikasiSubmenu = document.getElementById('komunikasiSubmenu');
        const grupDropdownIcon = document.getElementById('grupDropdownIcon');
        
        // Buat instance collapse
        const bsCollapse = new bootstrap.Collapse(komunikasiSubmenu, {
            toggle: false
        });
        
        if (grupDropdownToggle) {
            grupDropdownToggle.addEventListener('click', function(e) {
                e.preventDefault();
                // Toggle the collapse
                bsCollapse.toggle();
                
                // Toggle the icon
                grupDropdownIcon.classList.toggle('fa-chevron-up');
                grupDropdownIcon.classList.toggle('fa-chevron-down');
            });
        }
        
        // Initialize the pengaturan dropdown manually
        const pengaturanDropdownToggle = document.getElementById('pengaturanDropdownToggle');
        const pengaturanSubmenu = document.getElementById('pengaturanSubmenu');
        const pengaturanDropdownIcon = document.getElementById('pengaturanDropdownIcon');
        
        if (pengaturanDropdownToggle && pengaturanSubmenu && pengaturanDropdownIcon) {
            pengaturanDropdownToggle.addEventListener('click', function() {
                // Toggle the collapse
                const bsCollapse = new bootstrap.Collapse(pengaturanSubmenu, {
                    toggle: true
                });
                
                // Toggle the icon
                pengaturanDropdownIcon.classList.toggle('fa-chevron-up');
                pengaturanDropdownIcon.classList.toggle('fa-chevron-down');
            });
        }
        
        // Fungsi pencarian dan filter pesan
        const searchInput = document.getElementById('searchInput');
        const filterButtons = document.querySelectorAll('.btn-filter');
        const messageCards = document.querySelectorAll('.message-card');
        const noMessagesFound = document.getElementById('noMessagesFound');
        
        // Simpan filter aktif saat ini
        let currentFilter = 'semua';
        
        // Fungsi untuk menerapkan filter dan pencarian
        function applyFilterAndSearch() {
            const searchTerm = searchInput.value.toLowerCase();
            let visibleCount = 0;
            
            messageCards.forEach(card => {
                const kategori = card.getAttribute('data-kategori');
                const pengirim = card.getAttribute('data-pengirim').toLowerCase();
                const judul = card.getAttribute('data-judul').toLowerCase();
                const cardContent = card.textContent.toLowerCase();
                
                // Check filter category
                const matchesFilter = currentFilter === 'semua' || kategori === currentFilter;
                
                // Check search term
                const matchesSearch = searchTerm === '' || 
                                    pengirim.includes(searchTerm) || 
                                    judul.includes(searchTerm) || 
                                    cardContent.includes(searchTerm);
                
                // Show/hide card based on both conditions
                if (matchesFilter && matchesSearch) {
                    card.classList.remove('hidden');
                    visibleCount++;
                } else {
                    card.classList.add('hidden');
                }
            });
            
            // Show/hide no messages found message
            if (visibleCount === 0) {
                noMessagesFound.style.display = 'block';
            } else {
                noMessagesFound.style.display = 'none';
            }
        }
        
        // Event untuk input pencarian
        searchInput.addEventListener('input', applyFilterAndSearch);
        
        // Event untuk tombol filter
        filterButtons.forEach(button => {
            button.addEventListener('click', function() {
                // Remove active-filter class from all buttons
                filterButtons.forEach(btn => {
                    btn.classList.remove('active-filter');
                    
                    // Reset semua tombol ke outline style sesuai dengan jenisnya
                    if (btn.id === 'filterPenting') {
                        btn.className = 'btn btn-outline-danger rounded-pill px-4 py-2 me-2 btn-filter';
                    } else if (btn.id === 'filterUmum') {
                        btn.className = 'btn btn-outline-success rounded-pill px-4 py-2 me-2 btn-filter';
                    } else if (btn.id === 'filterSemua') {
                        btn.className = 'btn btn-outline-primary rounded-pill px-4 py-2 btn-filter';
                    }
                });
                
                // Add active-filter class to clicked button
                this.classList.add('active-filter');
                
                // Update current filter
                currentFilter = this.getAttribute('data-filter');
                
                // Apply filter and search
                applyFilterAndSearch();
            });
        });
        
        // Tambahkan pengendali peristiwa ke form perpindahan peran untuk menampilkan toast
        const switchRoleForm = document.getElementById('switchRoleForm');
        if (switchRoleForm) {
            switchRoleForm.addEventListener('submit', function(e) {
                e.preventDefault();
                
                // Kirim form langsung tanpa animasi
                this.submit();
            });
        }
    });
</script>
@endpush