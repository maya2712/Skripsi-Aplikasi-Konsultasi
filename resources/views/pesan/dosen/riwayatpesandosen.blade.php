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
        
        /* Modal Role Switcher */
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
                                <i class="fas fa-thumbtack me-2"></i>Pesan Tersematkan
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Content -->
            <div class="col-md-9">
                <!-- Mode Switcher - Ditambahkan di halaman riwayat pesan -->
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
                                        @if($pesan->nip_pengirim == Auth::user()->nip)
                                            <!-- Menampilkan foto mahasiswa penerima -->
                                            @php
                                                $profilePhoto = $pesan->mahasiswaPenerima && $pesan->mahasiswaPenerima->profile_photo 
                                                    ? asset('storage/profile_photos/'.$pesan->mahasiswaPenerima->profile_photo) 
                                                    : null;
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
                                                $profilePhoto = $pesan->mahasiswaPengirim && $pesan->mahasiswaPengirim->profile_photo 
                                                    ? asset('storage/profile_photos/'.$pesan->mahasiswaPengirim->profile_photo) 
                                                    : null;
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
                                            <span class="badge bg-primary mb-1">{{ $pesan->subjek }}</span>
                                            
                                            @if($pesan->nip_pengirim == Auth::user()->nip)
                                                <!-- Jika dosen adalah pengirim, tampilkan nama mahasiswa penerima -->
                                                <h6 class="mb-1" style="font-size: 14px;">
                                                    <span class="badge bg-info me-1" style="font-size: 10px;">Kepada</span>
                                                    {{ $pesan->mahasiswaPenerima ? $pesan->mahasiswaPenerima->nama : 'Mahasiswa' }}
                                                </h6>
                                                <small class="text-muted">{{ $pesan->nim_penerima }}</small>
                                            @else
                                                <!-- Jika dosen adalah penerima, tampilkan nama mahasiswa pengirim -->
                                                <h6 class="mb-1" style="font-size: 14px;">
                                                    <span class="badge bg-info me-1" style="font-size: 10px;">Dari</span>
                                                    {{ $pesan->mahasiswaPengirim ? $pesan->mahasiswaPengirim->nama : 'Mahasiswa' }}
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