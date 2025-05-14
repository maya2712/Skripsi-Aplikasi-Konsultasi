@extends('layouts.app')
@section('title', 'FAQ Dosen')
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
            cursor: pointer;
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

        .custom-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 15px;
        }

        .search-container {
            width: 100%;
            margin: 0 auto 30px;
            position: relative;
        }

        .search-box {
            width: 100%;
            padding: 12px 20px;
            border: 1px solid #ddd;
            border-radius: 25px;
            font-size: 14px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
        }

        .category-pills {
            margin: 20px 0;
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }

        .category-pills .btn {
            border-radius: 20px;
            padding: 8px 20px;
            background-color: #e9ecef;
            border: none;
            color: #495057;
            white-space: nowrap;
        }

        .category-pills .btn.active {
            background-color: #0d6efd;
            color: white;
        }

        .faq-content {
            text-align: center;
            margin-bottom: 30px;
            background: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
        }

        .faq-item {
            background: white;
            border-radius: 10px;
            margin-bottom: 15px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
            overflow: hidden;
        }

        .faq-header {
            padding: 15px 20px;
            border-bottom: 1px solid #eee;
            display: flex;
            align-items: center;
            cursor: pointer;
        }

        .pin-icon {
            color: red;
            margin-right: 10px;
        }

        .faq-badge {
            background-color: #0d6efd;
            color: white;
            padding: 4px 12px;
            border-radius: 15px;
            font-size: 12px;
            margin-right: 10px;
        }

        .faq-title {
            margin: 0;
            font-size: 14px;
            font-weight: 500;
        }

        .faq-meta {
            color: #6c757d;
            font-size: 12px;
            margin-top: 5px;
        }

        /* Dropdown styles */
        .dosen-dropdown {
            position: relative;
            display: inline-block;
        }

        .dosen-dropdown .btn {
            border-radius: 20px;
            padding: 8px 20px;
            background-color: #e9ecef;
            border: none;
            color: #495057;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .dosen-dropdown .dropdown-menu {
            min-width: 200px;
            padding: 8px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .dosen-dropdown .dropdown-item {
            padding: 8px 15px;
            border-radius: 5px;
            font-size: 13px;
        }

        .dosen-dropdown .dropdown-item:hover {
            background-color: #f8f9fa;
        }
        
        /* Style tambahan untuk dropdown dosen aktif */
        .dosen-dropdown .btn.active-dosen {
            background-color: #E3F2FD;
            color: #1a73e8;
            border: 1px solid #1a73e8;
            font-weight: 500;
        }
        
        /* Fix untuk memastikan hover tidak mengganggu style aktif */
        .dosen-dropdown .btn.active-dosen:hover {
            background-color: #E3F2FD;
            color: #1a73e8;
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
                        <a href="{{ url('/buatpesandosen') }}" class="btn" style="background: linear-gradient(to right, #004AAD, #5DE0E6); color: white; padding: 10px 20px; border: none; border-radius: 5px;">
                            <i class="fas fa-plus me-2"></i> Pesan Baru
                        </a>
                    </div>
                    
                    <div class="sidebar-menu">
                        <div class="nav flex-column">
                            <a href="{{ url('/dashboardpesandosen') }}" class="nav-link">
                                <i class="fas fa-home me-2"></i>Daftar Pesan
                            </a>
                            <a href="#" class="nav-link menu-item" id="grupDropdownToggle" data-bs-toggle="collapse" data-bs-target="#komunikasiSubmenu">
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
                            <a href="{{ url('/riwayatpesandosen') }}" class="nav-link menu-item">
                                <i class="fas fa-history me-2"></i>Riwayat Pesan
                            </a>
                            <a href="{{ url('/faq') }}" class="nav-link menu-item active">
                                <i class="fas fa-question-circle me-2"></i>FAQ
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Content Area -->
            <div class="col-md-9">
                <div class="bg-white p-4 rounded-3 shadow-sm">
                    <h2 class="mb-1 text-center" style="font-weight: bold;">FAQ KONSULTASI AKADEMIK</h2>
                    <p class="text-muted mb-4 text-center">Kumpulan pertanyaan yang telah di Arsipkan oleh Dosen</p>

                    <!-- Search Box -->
                    <div class="position-relative mb-4">
                        <input type="text" class="search-box" id="searchFaq" placeholder="Cari Pertanyaan atau Topik...">
                        <i class="fas fa-search position-absolute" style="right: 20px; top: 50%; transform: translateY(-50%);"></i>
                    </div>

                    <!-- Category Pills with Dosen Dropdown -->
                    <div class="category-pills">
                        <button class="btn active" data-category="all">Semua</button>
                        <button class="btn" data-category="krs">KRS</button>
                        <button class="btn" data-category="kp">KP</button>
                        <button class="btn" data-category="skripsi">Skripsi</button>
                        <button class="btn" data-category="mbkm">MBKM</button>
                        <div class="dosen-dropdown">
                            <button class="btn dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-user-tie"></i>
                                Dosen
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="#" data-dosen="Semua Dosen">Semua Dosen</a></li>
                                
                                @foreach($dosenList as $dosen)
                                    <li><a class="dropdown-item" href="#" data-dosen="{{ $dosen->nama }}">{{ $dosen->nama }}</a></li>
                                @endforeach
                            </ul>
                        </div>
                    </div>

                    <!-- FAQ Items -->
                    <div class="faq-list mt-4">
                        @forelse($sematan as $item)
                            <div class="faq-item" data-category="{{ $item->kategori }}" data-dosen="{{ $item->dosen->nama ?? 'Dosen' }}" style="background-color: #F5F7FA;">
                                <div class="faq-header" data-bs-toggle="collapse" data-bs-target="#faqItem{{ $item->id }}" aria-expanded="false">
                                    <i class="fas fa-thumbtack pin-icon"></i>
                                    <div class="flex-grow-1">
                                        <h5 class="faq-title mb-2">{{ $item->judul }}</h5>
                                        <div class="d-flex align-items-center">
                                            <span class="faq-badge me-3">
                                                @if($item->kategori == 'krs')
                                                    Bimbingan KRS
                                                @elseif($item->kategori == 'kp')
                                                    Bimbingan KP
                                                @elseif($item->kategori == 'skripsi')
                                                    Bimbingan Skripsi
                                                @elseif($item->kategori == 'mbkm')
                                                    Bimbingan MBKM
                                                @endif
                                            </span>
                                            <div class="faq-meta">
                                                Di-Pin oleh: {{ $item->dosen->nama ?? 'Dosen' }} - {{ $item->created_at->format('H:i, d F Y') }}
                                            </div>
                                        </div>
                                    </div>
                                    <i class="fas fa-chevron-down ms-3 chevron-icon"></i>
                                </div>
                                <div class="collapse" id="faqItem{{ $item->id }}">
                                    <div class="p-3">
                                        <p>{{ $item->isi_sematan }}</p>
                                        
                                        @if($item->nip_dosen == Auth::user()->nip)
                                            <div class="mt-3 text-end">
                                                <button class="btn btn-sm btn-danger batalkan-sematan" data-id="{{ $item->id }}">
                                                    <i class="fas fa-times"></i> Batalkan Sematan
                                                </button>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center p-4">
                                <p class="text-muted">Belum ada FAQ yang disematkan</p>
                            </div>
                        @endforelse

                        <!-- No FAQ Found Message -->
                        <div id="noFaqFound" class="text-center p-4 mt-3" style="display: none; background-color: #F8F9FA; border-radius: 10px;">
                            <i class="fas fa-search fa-3x mb-3 text-muted"></i>
                            <h5>Tidak ada FAQ yang ditemukan</h5>
                            <p class="text-muted">Silakan coba kata kunci lain atau pilih kategori yang berbeda</p>
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
        
        // Membuat instance Bootstrap collapse untuk menu grup
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
        
        // Setup event listeners untuk FAQ item collapse
        const faqCollapses = document.querySelectorAll('.collapse');
        faqCollapses.forEach(collapse => {
            // Menambahkan event listener untuk saat collapse dibuka
            collapse.addEventListener('show.bs.collapse', function() {
                // Mengambil ikon chevron dari header yang terkait
                const header = this.previousElementSibling;
                const chevronIcon = header.querySelector('.chevron-icon');
                chevronIcon.classList.remove('fa-chevron-down');
                chevronIcon.classList.add('fa-chevron-up');
            });
            
            // Menambahkan event listener untuk saat collapse ditutup
            collapse.addEventListener('hide.bs.collapse', function() {
                // Mengambil ikon chevron dari header yang terkait
                const header = this.previousElementSibling;
                const chevronIcon = header.querySelector('.chevron-icon');
                chevronIcon.classList.remove('fa-chevron-up');
                chevronIcon.classList.add('fa-chevron-down');
            });
        });
        
        // Filter FAQ berdasarkan kategori dan dosen
        const faqItems = document.querySelectorAll('.faq-item');
        const categoryButtons = document.querySelectorAll('.category-pills .btn:not(.dropdown-toggle)');
        const dosenLinks = document.querySelectorAll('.dosen-dropdown .dropdown-item');
        const searchInput = document.getElementById('searchFaq');
        const noFaqFound = document.getElementById('noFaqFound');
        
        let activeCategory = 'all';
        let activeDosen = null;
        
        // Fungsi untuk menerapkan filter
        function applyFilters() {
            let visibleCount = 0;
            
            faqItems.forEach(item => {
                const itemCategory = item.getAttribute('data-category');
                const itemDosen = item.getAttribute('data-dosen');
                const itemTitle = item.querySelector('.faq-title').textContent.toLowerCase();
                const searchTerm = searchInput.value.toLowerCase();
                
                // Check if matches all active filters
                const matchesCategory = activeCategory === 'all' || itemCategory === activeCategory;
                const matchesDosen = !activeDosen || itemDosen === activeDosen;
                const matchesSearch = !searchTerm || itemTitle.includes(searchTerm);
                
                if (matchesCategory && matchesDosen && matchesSearch) {
                    item.style.display = 'block';
                    visibleCount++;
                } else {
                    item.style.display = 'none';
                }
            });
            
            // Show or hide "No FAQ Found" message
            if (visibleCount === 0) {
                noFaqFound.style.display = 'block';
            } else {
                noFaqFound.style.display = 'none';
            }
        }
        
        // Event listener untuk tombol kategori
        categoryButtons.forEach(button => {
            button.addEventListener('click', function() {
                // Remove active class from all category buttons
                categoryButtons.forEach(btn => btn.classList.remove('active'));
                
                // Add active class to this button
                this.classList.add('active');
                
                // Update active category
                activeCategory = this.getAttribute('data-category') || 'all';
                
                // Apply filters
                applyFilters();
            });
        });
        
        // Event listener untuk dropdown dosen
        dosenLinks.forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                
                // Jika yang dipilih adalah "Semua Dosen"
                if (this.getAttribute('data-dosen') === "Semua Dosen") {
                    // Reset active dosen filter
                    activeDosen = null;
                    
                    // Reset dropdown button text ke default
                    const dropdownButton = document.querySelector('.dosen-dropdown .btn');
                    dropdownButton.innerHTML = '<i class="fas fa-user-tie"></i> Dosen';
                    dropdownButton.classList.remove('active-dosen');
                    
                    // Aktifkan tombol "Semua" kategori
                    categoryButtons.forEach(btn => {
                        if (btn.getAttribute('data-category') === 'all') {
                            btn.classList.add('active');
                        } else {
                            btn.classList.remove('active');
                        }
                    });
                    
                    // Set active category to 'all'
                    activeCategory = 'all';
                } else {
                    // Update active dosen untuk dosen lain
                    activeDosen = this.getAttribute('data-dosen');
                    
                    // Update dropdown button text and make it active
                    const dropdownButton = document.querySelector('.dosen-dropdown .btn');
                    dropdownButton.innerHTML = '<i class="fas fa-user-tie"></i> ' + this.textContent;
                    dropdownButton.classList.add('active-dosen');
                    
                    // Reset active category to 'all' but don't highlight any button
                    activeCategory = 'all';
                    categoryButtons.forEach(btn => btn.classList.remove('active'));
                    categoryButtons[0].classList.add('active');
                }
                
                // Apply filters
                applyFilters();
            });
        });
        
        // Event listener untuk pencarian
        searchInput.addEventListener('input', applyFilters);
        
        // Event listener untuk tombol batalkan sematan
        document.querySelectorAll('.batalkan-sematan').forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                
                const sematanId = this.getAttribute('data-id');
                
                if (confirm('Apakah Anda yakin ingin membatalkan sematan ini?')) {
                    // Kirim request untuk membatalkan sematan
                    fetch(`/batalkan-sematan/${sematanId}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Content-Type': 'application/json'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Hapus elemen FAQ dari DOM
                            const faqItem = this.closest('.faq-item');
                            faqItem.remove();
                            
                            // Tampilkan notifikasi
                            alert('Sematan berhasil dibatalkan');
                            
                            // Reload halaman jika semua FAQ sudah dihapus
                            if (document.querySelectorAll('.faq-item').length === 0) {
                                window.location.reload();
                            }
                        } else {
                            alert('Gagal membatalkan sematan: ' + data.message);
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Terjadi kesalahan saat membatalkan sematan');
                    });
                }
            });
        });
    });
</script>
@endpush