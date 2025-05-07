@extends('layouts.app')
@section('title', 'Riwayat Pesan Mahasiswa')
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

        .sidebar-buttons .btn:last-child {
            margin-bottom: 0;
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
        }
        
        .sidebar-menu .nav-link.active {
            background: #E3F2FD;
            color: var(--bs-primary);
        }
        
        .sidebar-menu .nav-link:hover {
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
            margin-bottom: 20px;
        }

        .messages-wrapper {
            position: relative;
            margin-top: 20px;
            background-color: #F5F7FA;  
            z-index: 1;
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
        
        /* Animasi fade untuk pesan */
        .message-card {
            transition: opacity 0.3s ease, transform 0.2s ease;
            cursor: pointer;
        }
        
        .message-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
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
                            <a href="#" class="nav-link menu-item" id="grupDropdownToggle">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span><i class="fas fa-users me-2"></i>Daftar Grup</span>
                                    <i class="fas fa-chevron-down" id="grupDropdownIcon"></i>
                                </div>
                            </a>
                            <div class="collapse komunikasi-submenu" id="komunikasiSubmenu">
                                @php
                                    $userGrups = Auth::user()->grups;
                                @endphp
                                
                                @if($userGrups && $userGrups->count() > 0)
                                    @foreach($userGrups as $grupItem)
                                    <a href="{{ url('/detailgrup/' . $grupItem->id) }}" class="nav-link menu-item d-flex justify-content-between align-items-center">
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
                            <a href="{{ url('/riwayatpesanmahasiswa') }}" class="nav-link active">
                                <i class="fas fa-history me-2"></i>Riwayat Pesan
                            </a>
                            <a href="{{ url('/faqmahasiswa') }}" class="nav-link menu-item">
                                <i class="fas fa-question-circle me-2"></i>FAQ
                            </a>
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
                             data-pengirim="{{ $pesan->nim_pengirim == Auth::user()->nim ? ($pesan->penerima->nama ?? 'Dosen') : ($pesan->pengirim->nama ?? 'Pengirim') }}" 
                             data-judul="{{ $pesan->subjek }}" 
                             onclick="window.location.href='{{ url('/isipesanmahasiswa/' . $pesan->id) }}'">
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col-md-8 d-flex align-items-center">
                                        <div class="profile-image-placeholder me-3">
                                            <i class="fas fa-user"></i>
                                        </div>
                                        <div>
                                            <span class="badge bg-primary mb-1">{{ $pesan->subjek }}</span>
                                            @if($pesan->nim_pengirim == Auth::user()->nim)
                                                <!-- Jika mahasiswa adalah pengirim, tampilkan informasi dosen penerima -->
                                                <h6 class="mb-1" style="font-size: 14px;">
                                                    @php
                                                        $dosenPenerima = App\Models\Dosen::where('nip', $pesan->nip_penerima)->first();
                                                        $namaPenerima = $dosenPenerima ? $dosenPenerima->nama : 'Dosen';
                                                        $jabatanPenerima = $dosenPenerima ? $dosenPenerima->jabatan ?? 'Dosen' : 'Dosen';
                                                    @endphp
                                                    {{ $namaPenerima }}
                                                </h6>
                                                <small class="text-muted">{{ $jabatanPenerima }}</small>
                                            @else
                                                <!-- Jika mahasiswa adalah penerima, tampilkan informasi dosen pengirim -->
                                                <h6 class="mb-1" style="font-size: 14px;">
                                                    @php
                                                        $dosenPengirim = App\Models\Dosen::where('nip', $pesan->nip_pengirim)->first();
                                                        $namaPengirim = $dosenPengirim ? $dosenPengirim->nama : 'Dosen';
                                                        $jabatanPengirim = $dosenPengirim ? $dosenPengirim->jabatan ?? 'Dosen' : 'Dosen';
                                                    @endphp
                                                    {{ $namaPengirim }}
                                                </h6>
                                                <small class="text-muted">{{ $jabatanPengirim }}</small>
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
                                        <button class="btn btn-custom-primary btn-sm" style="font-size: 10px;" onclick="event.stopPropagation(); window.location.href='{{ url('/isipesanmahasiswa/' . $pesan->id) }}'">
                                            <i class="fas fa-eye me-1"></i>Lihat
                                        </button>
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
        // Toggle dropdown untuk menu grup
        const grupDropdownToggle = document.getElementById('grupDropdownToggle');
        const komunikasiSubmenu = document.getElementById('komunikasiSubmenu');
        const grupDropdownIcon = document.getElementById('grupDropdownIcon');
        
        // Membuat instance Bootstrap collapse
        const bsCollapse = new bootstrap.Collapse(komunikasiSubmenu, {
            toggle: false
        });
        
        if (grupDropdownToggle) {
            grupDropdownToggle.addEventListener('click', function(e) {
                e.preventDefault();
                // Menggunakan metode toggle dari Bootstrap daripada manipulasi kelas manual
                bsCollapse.toggle();
            });
            
            // Mengubah ikon saat submenu dibuka
            komunikasiSubmenu.addEventListener('shown.bs.collapse', function() {
                grupDropdownIcon.classList.remove('fa-chevron-down');
                grupDropdownIcon.classList.add('fa-chevron-up');
            });
            
            // Mengubah ikon saat submenu ditutup
            komunikasiSubmenu.addEventListener('hidden.bs.collapse', function() {
                grupDropdownIcon.classList.remove('fa-chevron-up');
                grupDropdownIcon.classList.add('fa-chevron-down');
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
    });
</script>
@endpush