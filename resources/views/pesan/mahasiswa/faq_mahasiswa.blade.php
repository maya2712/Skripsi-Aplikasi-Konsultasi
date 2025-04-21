@extends('layouts.app')
@section('title', 'FAQ Mahasiswa')
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
                        <a href="{{ url('/buatpesanmahasiswa') }}" class="btn" style="background: linear-gradient(to right, #004AAD, #5DE0E6); color: white; padding: 10px 20px; border: none; border-radius: 5px;">
                            <i class="fas fa-plus me-2"></i> Pesan Baru
                        </a>
                    </div>
                    
                    <div class="sidebar-menu">
                        <div class="nav flex-column">
                            <a href="{{ url('/dashboardpesanmahasiswa') }}" class="nav-link">
                                <i class="fas fa-home me-2"></i>Daftar Pesan
                            </a>
                            <a href="#" class="nav-link menu-item" id="grupDropdownToggle" data-bs-toggle="collapse" data-bs-target="#komunikasiSubmenu">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span><i class="fas fa-users me-2"></i>Daftar Grup</span>
                                    <i class="fas fa-chevron-down" id="grupDropdownIcon"></i>
                                </div>
                            </a>
                            <div class="collapse komunikasi-submenu" id="komunikasiSubmenu">
                                <!-- Menghilangkan opsi "Grup Baru" karena mahasiswa tidak bisa membuat grup -->
                                <a href="#" class="nav-link menu-item d-flex justify-content-between align-items-center">
                                    Bimbingan Skripsi
                                    <span class="badge bg-danger rounded-pill">3</span>
                                </a>
                                <a href="#" class="nav-link menu-item d-flex justify-content-between align-items-center">
                                    Kerja Praktek
                                    <span class="badge bg-danger rounded-pill">1</span>
                                </a>
                                <a href="#" class="nav-link menu-item d-flex justify-content-between align-items-center">
                                    Pembimbing Akademik
                                    <span class="badge bg-danger rounded-pill">4</span>
                                </a>
                            </div>
                            <a href="{{ url('/riwayatpesanmahasiswa') }}" class="nav-link menu-item">
                                <i class="fas fa-history me-2"></i>Riwayat Pesan
                            </a>
                            <a href="{{ url('/faqmahasiswa') }}" class="nav-link menu-item active">
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
                                <li><a class="dropdown-item" href="#" data-dosen="Irsan Taufik Ali">Dr. Irsan Taufik Ali, S.T., M.T.</a></li>
                                <li><a class="dropdown-item" href="#" data-dosen="Dian Ramadhani">Dian Ramadhani, S.T., M.T.</a></li>
                                <li><a class="dropdown-item" href="#" data-dosen="Feri Candra">Dr. Feri Candra, S.T., M.T</a></li>
                                <li><a class="dropdown-item" href="#" data-dosen="Edi Susilo">Edi Susilo, Spd., M.Kom.,M.Eng</a></li>
                            </ul>
                        </div>
                    </div>

                    <!-- FAQ Items -->
                    <div class="faq-list mt-4">
                        <!-- FAQ Item 1 -->
                        <div class="faq-item" data-category="krs" data-dosen="Irsan Taufik Ali" style="background-color: #F5F7FA;">
                            <div class="faq-header" data-bs-toggle="collapse" data-bs-target="#faqItem1" aria-expanded="false">
                                <i class="fas fa-thumbtack pin-icon"></i>
                                <div class="flex-grow-1">
                                    <h5 class="faq-title mb-2">Bimbingan KRS dilakukan mulai tanggal 25-30 Januari 2025</h5>
                                    <div class="d-flex align-items-center">
                                        <span class="faq-badge me-3">Bimbingan KRS</span>
                                        <div class="faq-meta">
                                            Di-Pin oleh: Dr. Irsan Taufik Ali, S.T., M.T. - 09.30, 20 Januari 2025
                                        </div>
                                    </div>
                                </div>
                                <i class="fas fa-chevron-down ms-3 chevron-icon"></i>
                            </div>
                            <div class="collapse" id="faqItem1">
                                <div class="p-3">
                                    <p>Untuk semua mahasiswa bimbingan saya, mohon bisa melakukan pembimbingan KRS di tanggal yang telah ditentukan yaitu 25-30 Januari 2025. Saya akan berada di ruang dosen sepanjang jam kantor (08.00-16.00). Harap membawa KHS semester sebelumnya dan rencana matakuliah yang akan diambil.</p>
                                </div>
                            </div>
                        </div>

                        <!-- FAQ Item 2 -->
                        <div class="faq-item" data-category="kp" data-dosen="Dian Ramadhani" style="background-color: #F5F7FA;">
                            <div class="faq-header" data-bs-toggle="collapse" data-bs-target="#faqItem2" aria-expanded="false">
                                <i class="fas fa-thumbtack pin-icon"></i>
                                <div class="flex-grow-1">
                                    <h5 class="faq-title mb-2">Silahkan datang ke Prodi</h5>
                                    <div class="d-flex align-items-center">
                                        <span class="faq-badge me-3">Bimbingan KP</span>
                                        <div class="faq-meta">
                                            Di-Pin oleh: Dian Ramadhani, S.T., M.T. - 09.30, 18 Januari 2025
                                        </div>
                                    </div>
                                </div>
                                <i class="fas fa-chevron-down ms-3 chevron-icon"></i>
                            </div>
                            <div class="collapse" id="faqItem2">
                                <div class="p-3">
                                    <p>Untuk mahasiswa yang ingin berkonsultasi mengenai Kerja Praktek, silakan datang ke ruang prodi pada hari Senin-Jumat jam 10.00-15.00. Mohon untuk membawa berkas-berkas yang diperlukan dan pastikan sudah memenuhi persyaratan minimal untuk mengambil KP.</p>
                                </div>
                            </div>
                        </div>

                        <!-- FAQ Item 3 -->
                        <div class="faq-item" data-category="skripsi" data-dosen="Edi Susilo" style="background-color: #F5F7FA;">
                            <div class="faq-header" data-bs-toggle="collapse" data-bs-target="#faqItem3" aria-expanded="false">
                                <i class="fas fa-thumbtack pin-icon"></i>
                                <div class="flex-grow-1">
                                    <h5 class="faq-title mb-2">Saya Ada di kampus sekitar jam 9 pagi</h5>
                                    <div class="d-flex align-items-center">
                                        <span class="faq-badge me-3">Bimbingan Skripsi</span>
                                        <div class="faq-meta">
                                            Di-Pin oleh: Edi Susilo, Spd., M.Kom.,M.Eng - 09.30, 15 Januari 2025
                                        </div>
                                    </div>
                                </div>
                                <i class="fas fa-chevron-down ms-3 chevron-icon"></i>
                            </div>
                            <div class="collapse" id="faqItem3">
                                <div class="p-3">
                                    <p>Untuk mahasiswa bimbingan skripsi, saya akan ada di kampus setiap hari Selasa dan Kamis mulai jam 9 pagi. Silakan untuk membuat janji terlebih dahulu melalui sistem pesan di SEPTI agar jadwal tidak bertabrakan dengan mahasiswa lain. Harap membawa progres terbaru dari skripsi Anda.</p>
                                </div>
                            </div>
                        </div>

                        <!-- FAQ Item 4 -->
                        <div class="faq-item" data-category="mbkm" data-dosen="Feri Candra" style="background-color: #F5F7FA;">
                            <div class="faq-header" data-bs-toggle="collapse" data-bs-target="#faqItem4" aria-expanded="false">
                                <i class="fas fa-thumbtack pin-icon"></i>
                                <div class="flex-grow-1">
                                    <h5 class="faq-title mb-2">Saya Ada di kampus sekitar jam 9 pagi</h5>
                                    <div class="d-flex align-items-center">
                                        <span class="faq-badge me-3">Bimbingan MBKM</span>
                                        <div class="faq-meta">
                                            Di-Pin oleh: Dr. Feri Candra, S.T., M.T - 09.30, 15 Januari 2025
                                        </div>
                                    </div>
                                </div>
                                <i class="fas fa-chevron-down ms-3 chevron-icon"></i>
                            </div>
                            <div class="collapse" id="faqItem4">
                                <div class="p-3">
                                    <p>Informasi untuk mahasiswa yang mengikuti program MBKM, saya akan ada di kampus sekitar jam 9 pagi setiap Rabu dan Jumat. Untuk konsultasi mengenai konversi SKS dan laporan kegiatan MBKM, harap membawa dokumen pendukung yang lengkap dan laporan progres terbaru.</p>
                                </div>
                            </div>
                        </div>

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
    });
</script>
@endpush