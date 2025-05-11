@extends('layouts.app')

@section('title', 'Dashboard Pesan Mahasiswa')

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
    
    /* Tambahan CSS untuk card yang bisa diklik */
    .message-card {
        cursor: pointer;
        transition: all 0.2s ease;
        position: relative;
    }
    
    .message-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }
    
    /* Pastikan tombol tetap bisa diklik dan tidak memicu klik pada card */
    .message-card .btn,
    .message-card a {
        position: relative;
        z-index: 10;
    }
    
    /* Memastikan event dari tombol tidak menyebar ke card */
    .action-buttons {
        position: relative;
        z-index: 5;
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
                        <a href="{{ route('mahasiswa.pesan.create') }}" class="btn" style="background: linear-gradient(to right, #004AAD, #5DE0E6); color: white; padding: 10px 20px; border: none; border-radius: 5px;">
                            <i class="fas fa-plus me-2"></i> Pesan Baru
                        </a>
                    </div>                                                    
                    
                    <div class="sidebar-menu">
                        <div class="nav flex-column">
                            <a href="{{ route('mahasiswa.dashboard.pesan') }}" class="nav-link active">
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
                                    <a href="{{ route('mahasiswa.grup.show', $grupItem->id) }}" class="nav-link menu-item d-flex justify-content-between align-items-center">
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
                            <a href="{{ route('mahasiswa.pesan.history') }}" class="nav-link menu-item">
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
                                    <button class="btn btn-primary rounded-pill px-4 py-2 filter-btn" data-filter="semua" style="font-size: 14px;">Semua</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Message List - PERBAIKAN -->
                <div class="message-list" id="messageList">
                    @if($pesan->count() > 0)
                        @foreach($pesan as $p)
                        <div class="card mb-2 message-card {{ strtolower($p->prioritas) }}" onclick="window.location.href='{{ route('mahasiswa.pesan.show', $p->id) }}'">
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col-md-8 d-flex align-items-center">
                                        <div class="profile-image-placeholder me-3">
                                            <i class="fas fa-user"></i>
                                        </div>
                                        <div>
                                            <span class="badge bg-primary mb-1">{{ $p->subjek }}</span>
                                            
                                            @if($p->nim_pengirim == Auth::user()->nim)
                                                <!-- Jika mahasiswa adalah pengirim, tampilkan nama dosen penerima -->
                                                <h6 class="mb-1" style="font-size: 14px;">
                                                    <span class="badge bg-info me-1" style="font-size: 10px;">Kepada</span>
                                                    @php
                                                        // Ambil langsung data dosen penerima
                                                        $dosen = App\Models\Dosen::where('nip', $p->nip_penerima)->first();
                                                        $nama_penerima = $dosen ? $dosen->nama : 'Dosen';
                                                    @endphp
                                                    {{ $nama_penerima }}
                                                </h6>
                                                <small class="text-muted">{{ $p->nip_penerima }}</small>
                                            @else
                                                <!-- Jika mahasiswa adalah penerima, tampilkan nama dosen pengirim -->
                                                <h6 class="mb-1" style="font-size: 14px;">
                                                    <span class="badge bg-info me-1" style="font-size: 10px;">Dari</span>
                                                    @php
                                                        // Ambil langsung data dosen pengirim
                                                        $dosen = App\Models\Dosen::where('nip', $p->nip_pengirim)->first();
                                                        $nama_pengirim = $dosen ? $dosen->nama : 'Dosen';
                                                    @endphp
                                                    {{ $nama_pengirim }}
                                                </h6>
                                                <small class="text-muted">{{ $p->nip_pengirim }}</small>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-4 text-md-end mt-3 mt-md-0">
                                        <span class="badge {{ $p->dibaca ? 'bg-success' : 'bg-danger' }} me-1">
                                            {{ $p->dibaca ? 'Sudah dibaca' : 'Belum dibaca' }}
                                        </span>
                                        <span class="badge {{ $p->prioritas == 'Penting' ? 'bg-danger' : 'bg-success' }}">
                                            {{ $p->prioritas }}
                                        </span>
                                        <small class="d-block text-muted my-1">
                                           {{ \Carbon\Carbon::parse($p->created_at)->timezone('Asia/Jakarta')->diffForHumans() }}
                                        </small>

                                        <div class="action-buttons" onclick="event.stopPropagation();">
                                            <a href="{{ route('mahasiswa.pesan.show', $p->id) }}" class="btn btn-custom-primary btn-sm" style="font-size: 10px;">
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

    // Tombol filter - PERBAIKAN
    const filterButtons = document.querySelectorAll('.filter-btn');
    
    // Fungsi filter pesan
    function filterMessages(filter) {
        // Menampilkan indikator loading jika diperlukan
        // document.getElementById('loadingIndicator').style.display = 'block';
        
        fetch(`{{ route('mahasiswa.pesan.filter') }}?filter=${filter}`, {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                // Periksa apakah HTML respons hanya berisi pesan kosong
                if (data.html.includes('Belum ada pesan') || data.html.trim() === '') {
                    // Tidak ada hasil, tampilkan pesan "Pesan tidak tersedia"
                    document.getElementById('messageList').innerHTML = 
                        '<div class="text-center py-5"><p class="text-muted">Pesan tidak tersedia</p></div>';
                } else {
                    // Ada hasil, perbarui konten
                    document.getElementById('messageList').innerHTML = data.html;
                    
                    // Tambahkan event listener pada tombol action
                    document.querySelectorAll('.action-buttons, .action-buttons *').forEach(element => {
                        element.addEventListener('click', function(e) {
                            e.stopPropagation();
                        });
                    });
                }
                
                // Tampilkan/sembunyikan pesan "tidak tersedia" berdasarkan hasil
                const messageCards = document.querySelectorAll('.message-card');
                document.getElementById('no-results').style.display = 
                    messageCards.length === 0 ? 'block' : 'none';
            } else {
                console.error('Filter response indicates failure:', data);
                fallbackFilterMessages(filter);
            }
            // document.getElementById('loadingIndicator').style.display = 'none';
        })
        .catch(error => {
            console.error('Error during filter operation:', error);
            // Fallback ke filter lokal jika AJAX gagal
            fallbackFilterMessages(filter);
            // document.getElementById('loadingIndicator').style.display = 'none';
        });
    }

    // Fungsi filter fallback (client-side)
    function fallbackFilterMessages(filter) {
        console.log('Using fallback filter with:', filter);
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
        
        // Tampilkan pesan "tidak tersedia" jika tidak ada pesan yang terlihat
        if (visibleCount === 0) {
            document.getElementById('messageList').innerHTML = 
                '<div class="text-center py-5"><p class="text-muted">Pesan tidak tersedia</p></div>';
        }
        
        // Sembunyikan elemen no-results karena kita sudah menangani pesan kosong
        document.getElementById('no-results').style.display = 'none';
    }

    // Tambahkan event listener untuk setiap tombol filter
    filterButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault(); // Prevent any default behavior
            
            // Hapus class active dari semua tombol
            filterButtons.forEach(btn => {
                btn.classList.remove('active');
                
                // Reset juga styling untuk tombol semua
                if (btn.dataset.filter === 'semua') {
                    btn.classList.remove('btn-primary');
                    btn.classList.add('btn-outline-primary');
                }
            });
            
            // Tambahkan class active ke tombol yang diklik
            this.classList.add('active');
            
            // Tambahkan styling khusus jika tombol semua yang diklik
            if (this.dataset.filter === 'semua') {
                this.classList.remove('btn-outline-primary');
                this.classList.add('btn-primary');
            }
            
            // Filter pesan berdasarkan tombol yang diklik
            const filter = this.dataset.filter;
            console.log('Filter clicked:', filter);
            filterMessages(filter);
        });
    });

    // Pencarian pesan
    const searchInput = document.getElementById('searchInput');
    let searchTimeout;
    
    searchInput.addEventListener('input', function() {
        clearTimeout(searchTimeout);
        const searchTerm = this.value.trim();
        
        // Tambahkan delay untuk mencegah terlalu banyak request
        searchTimeout = setTimeout(() => {
            if (searchTerm.length > 0) {
                searchMessages(searchTerm);
            } else {
                // Jika input kosong, reset ke tampilan awal
                // Dapatkan filter aktif saat ini
                const activeFilter = document.querySelector('.filter-btn.active');
                if (activeFilter) {
                    filterMessages(activeFilter.dataset.filter);
                } else {
                    filterMessages('semua');
                }
            }
        }, 500);
    });

    // Fungsi pencarian pesan melalui AJAX
    function searchMessages(keyword) {
        fetch(`{{ route('mahasiswa.pesan.search') }}?keyword=${encodeURIComponent(keyword)}`, {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                // Periksa apakah HTML respons kosong atau hanya berisi pesan kosong
                if (data.html.includes('Belum ada pesan') || data.html.trim() === '') {
                    // Tidak ada hasil, tampilkan pesan "Pesan tidak tersedia"
                    document.getElementById('messageList').innerHTML = 
                        '<div class="text-center py-5"><p class="text-muted">Pesan tidak tersedia</p></div>';
                } else {
                    // Ada hasil, perbarui konten
                    document.getElementById('messageList').innerHTML = data.html;
                    
                    // Tambahkan event listener pada tombol action
                    document.querySelectorAll('.action-buttons, .action-buttons *').forEach(element => {
                        element.addEventListener('click', function(e) {
                            e.stopPropagation();
                        });
                    });
                }
                
                // Tampilkan/sembunyikan pesan "tidak tersedia" berdasarkan hasil
                const messageCards = document.querySelectorAll('.message-card');
                document.getElementById('no-results').style.display = 
                    messageCards.length === 0 ? 'block' : 'none';
            } else {
                console.error('Search response indicates failure:', data);
                fallbackSearchMessages(keyword);
            }
        })
        .catch(error => {
            console.error('Error during search operation:', error);
            // Fallback ke pencarian lokal jika AJAX gagal
            fallbackSearchMessages(keyword);
        });
    }

    // Fungsi pencarian fallback (client-side)
    function fallbackSearchMessages(searchTerm) {
        console.log('Using fallback search with:', searchTerm);
        
        // Dapatkan filter aktif saat ini untuk digabungkan dengan pencarian
        const activeFilter = document.querySelector('.filter-btn.active')?.dataset.filter || 'semua';
        
        const messageCards = document.querySelectorAll('.message-card');
        let visibleCount = 0;
        
        messageCards.forEach(card => {
            const messageText = card.textContent.toLowerCase();
            const isPenting = card.classList.contains('penting');
            const isUmum = card.classList.contains('umum');
            
            // Kombinasikan filter pencarian dengan filter prioritas
            const matchesSearch = messageText.includes(searchTerm.toLowerCase());
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
        
        // Tampilkan pesan "tidak tersedia" jika tidak ada pesan yang terlihat
        if (visibleCount === 0) {
            document.getElementById('messageList').innerHTML = 
                '<div class="text-center py-5"><p class="text-muted">Pesan tidak tersedia</p></div>';
        }
        
        // Sembunyikan elemen no-results karena kita sudah menangani pesan kosong
        document.getElementById('no-results').style.display = 'none';
    }
    
    // Set default filter ke "semua" saat halaman dimuat
    window.addEventListener('load', function() {
        console.log('Page loaded, setting default filter');
        
        // Hapus kelas active dari semua tombol filter kecuali 'semua'
        filterButtons.forEach(btn => {
            // Jika tombol filter adalah 'semua', berikan class active
            if (btn.dataset.filter === 'semua') {
                btn.classList.add('active');
                // Pastikan pakai style yang benar (btn-primary)
                btn.classList.remove('btn-outline-primary');
                btn.classList.add('btn-primary');
            } else {
                btn.classList.remove('active');
            }
        });
        
        // Aktifkan filter 'semua' secara default
        filterMessages('semua');
        
        // Menghentikan propagasi klik pada tombol-tombol di dalam card
        document.querySelectorAll('.action-buttons, .action-buttons *').forEach(element => {
            element.addEventListener('click', function(e) {
                e.stopPropagation();
            });
        });
    });
});
</script>
@endpush