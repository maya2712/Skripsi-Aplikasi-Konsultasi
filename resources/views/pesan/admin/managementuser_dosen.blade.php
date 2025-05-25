@extends('layouts.app')

@section('title', 'Manajemen User - Admin')

@push('styles')
<style>
    :root {
        --bs-primary: #1a73e8;
        --bs-danger: #FF5252;
        --bs-success: #27AE60;
        --bs-warning: #FFC107;
        --bs-info: #00BCD4;
        --gradient-primary: linear-gradient(to right, #004AAD, #5DE0E6);
    }
    
    body {
        background-color: #F5F7FA;
        font-size: 13px;
    }

    .main-content {
        padding-top: 20px; 
        padding-bottom: 200px; /* Tambah padding bawah lebih besar untuk ruang dropdown */
        min-height: 100vh; /* Pastikan main content cukup tinggi */
    }
    
    .profile-image-placeholder {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background-color: #e9ecef;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #6c757d;
        font-size: 18px;
    }
    
    /* Tabel style baru */
    .table-header {
        background-color: #222;
        color: white;
    }
    
    .table-header th {
        font-weight: normal;
        vertical-align: middle;
        padding: 10px 15px;
        border: 1px solid #444 !important;
        text-align: center;
    }
    
    .table-striped > tbody > tr:nth-of-type(odd) {
        background-color: #f9f9f9;
    }
    
    .table-striped > tbody > tr > td {
        padding: 12px 15px;
        vertical-align: middle;
        border: 1px solid #dee2e6 !important;
        text-align: center;
    }
    
    .table {
        border-collapse: collapse;
        width: 100%;
    }
    
    /* FIX DROPDOWN VISIBILITY - HANYA UNTUK DROPDOWN TABEL */
    .table-responsive {
        overflow-x: auto;
        overflow-y: visible !important;
        padding-bottom: 150px; /* Tambah ruang di bawah untuk dropdown */
        margin-bottom: -150px; /* Kompensasi padding */
    }
    
    /* Dropdown positioning tepat di bawah tombol - HANYA UNTUK DROPDOWN AKSI TABEL */
    .table .dropdown {
        position: relative;
    }
    
    .table .dropdown-menu {
        position: absolute !important;
        z-index: 9999 !important;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15) !important;
        border: 1px solid rgba(0, 0, 0, 0.05) !important;
        border-radius: 6px !important;
        min-width: 160px !important;
        right: 0 !important; 
        left: auto !important;
        top: calc(100% + 2px) !important; /* Tepat di bawah tombol dengan jarak 2px */
        margin: 0 !important; /* Hapus margin default */
        transform: none !important; /* Hapus transform */
        min-height: 120px !important; /* Pastikan tinggi minimum untuk 3 menu */
    }
    
    /* Tambahan: Pastikan dropdown item terlihat semua - HANYA UNTUK TABEL */
    .table .dropdown-item {
        padding: 8px 16px !important;
        font-size: 14px !important;
        line-height: 1.4 !important;
    }
    
    .edit-btn {
        background-color: #ffc107;
        border-radius: 50%;
        width: 30px;
        height: 30px;
        display: flex;
        justify-content: center;
        align-items: center;
        color: #000;
        text-decoration: none;
        margin: 0 auto;
        font-size: 12px;
        position: relative;
    }
    
    .add-btn {
        background-color: #28a745;
        color: white;
        border: none;
        padding: 5px 10px;
        border-radius: 3px;
        font-size: 14px;
    }
    
    .form-select {
        background-color: white;
        border: 1px solid #ced4da;
    }
    
    .card {
        border-radius: 10px;
        box-shadow: 0 0 5px rgba(0,0,0,0.1);
        border: none;
        overflow: visible !important;
        min-height: 250px; /* Tambah tinggi minimum card */
    }
    
    .card-body {
        overflow: visible !important;
        position: relative;
        min-height: 200px; /* Tambah tinggi minimum card body */
    }
    
    .status-active {
        color: #28a745;
    }
    
    .status-inactive {
        color: #dc3545;
    }
    
    .status-pending {
        color: #ffc107;
    }
    
    /* CSS untuk memusatkan checkbox */
    .form-check {
        display: flex;
        justify-content: center;
        align-items: center;
        margin: 0;
        height: 100%;
    }
    
    .form-check-input {
        margin: 0;
        float: none;
    }
    
    /* Sembunyikan notifikasi global yang ada di atas */
    .alert.alert-success.global-alert,
    .alert.alert-danger.global-alert,
    .alert-success[style*="background-color: rgba(220, 242, 231, 0.2)"],
    .alert-success:not(.mb-4) {
        display: none !important;
    }
    
    .custom-container {
        max-width: 1400px;
        margin: 0 auto;
        padding: 0 15px;
        overflow: visible !important;
        position: relative;
        min-height: 100vh; /* Pastikan container cukup tinggi */
    }
    
    /* Baris tabel dengan dropdown - perbaiki positioning */
    tbody tr {
        position: relative;
    }
    
    tbody tr td:last-child {
        position: relative; /* Kolom aksi dengan posisi relative */
    }
    
    /* Gaya untuk modal reset password - DIPERBARUI dengan gradasi */
    .modal-reset-password .modal-header {
        background: linear-gradient(to right, #004AAD, #5DE0E6);
        color: white;
        border-bottom: none;
        border-top-left-radius: 10px;
        border-top-right-radius: 10px;
    }
    
    .modal-reset-password .modal-content {
        border: none;
        border-radius: 10px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }
    
    .modal-reset-password .modal-footer {
        border-top: none;
        padding: 1rem;
    }
    
    .modal-reset-password .btn-reset {
        background: linear-gradient(to right, #004AAD, #5DE0E6);
        color: white;
        border: none;
        transition: all 0.3s;
    }
    
    .modal-reset-password .btn-reset:hover {
        opacity: 0.9;
        box-shadow: 0 2px 8px rgba(0, 74, 173, 0.3);
    }
    
    .modal-reset-password .modal-body {
        padding: 1.5rem;
    }
    
    /* Animasi loading */
    .loading-spinner {
        display: inline-block;
        width: 2rem;
        height: 2rem;
        border: 3px solid rgba(0, 0, 0, 0.1);
        border-radius: 50%;
        border-top-color: #5DE0E6;
        border-left-color: #004AAD;
        animation: spin 1s ease-in-out infinite;
    }
    
    @keyframes spin {
        to { transform: rotate(360deg); }
    }
    
    .loading-container {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 1.5rem;
    }
    
    .loading-text {
        margin-top: 15px;
        font-size: 14px;
        font-weight: 500;
        background: linear-gradient(to right, #004AAD, #5DE0E6);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }
</style>
@endpush

@section('content')
<div class="main-content">
    <div class="custom-container">
        <div class="row">
            <!-- Main Content - Full width tanpa sidebar -->
            <div class="col-12">
                <!-- Users Table -->
                <div class="card">
                    <div class="card-body p-4">
                        <!-- Notifikasi sukses dengan desain modern -->
                        @if(session('success'))
                        <div class="alert mb-4" role="alert" style="background-color: rgba(39, 174, 96, 0.1); border-left: 4px solid #27AE60; color: #2E7D32; border-radius: 4px; box-shadow: 0 2px 5px rgba(0,0,0,0.05); padding: 12px; position: relative;">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-check-circle me-2" style="font-size: 18px; color: #27AE60;"></i>
                                <div><strong>Berhasil!</strong> {{ session('success') }}</div>
                                <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close" style="font-size: 10px; padding: 8px;"></button>
                            </div>
                        </div>
                        @endif
                        
                        <!-- Notifikasi error dengan desain modern -->
                        @if(session('error'))
                        <div class="alert mb-4" role="alert" style="background-color: rgba(255, 82, 82, 0.1); border-left: 4px solid #FF5252; color: #D32F2F; border-radius: 4px; box-shadow: 0 2px 5px rgba(0,0,0,0.05); padding: 12px; position: relative;">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-exclamation-circle me-2" style="font-size: 18px; color: #FF5252;"></i>
                                <div><strong>Error!</strong> {{ session('error') }}</div>
                                <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close" style="font-size: 10px; padding: 8px;"></button>
                            </div>
                        </div>
                        @endif
                        
                        <div class="row mb-4">
                            <div class="col-auto mb-2 ms-3">
                                <div class="d-flex align-items-center">
                                    <label class="me-2 mb-0">Tampilkan</label>
                                    <select class="form-select form-select-sm" style="width: 70px; height: 30px; padding: 2px 8px;" id="displayLimit">
                                        <option value="50">50</option>
                                        <option value="100">100</option>
                                        <option value="200">200</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-auto mb-2 ms-4">
                                <div class="d-flex align-items-center">
                                    <label class="me-2 mb-0">Jurusan</label>
                                    <select class="form-select form-select-sm" style="width: 180px; height: 30px; padding: 2px 8px;" id="prodiFilter">
                                        <option>Semua</option>
                                        <option>Teknik Elektro</option>
                                        <option>Teknik Informatika</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col ms-auto mb-2 d-flex">
                                <div class="input-group">
                                    <input type="text" class="form-control" placeholder="Cari" style="height: 30px; font-size: 14px;" id="searchInput">
                                </div>
                                <a href="{{ route('admin.tambahdosen') }}" class="btn ms-2" style="background: linear-gradient(to right, #00ad51, #00ad51); color: white; height: 30px; font-size: 14px; display: flex; align-items: center; white-space: nowrap; width: auto; padding: 0 10px;">
                                    <i class="fas fa-plus me-1"></i> Dosen baru
                                </a>
                            </div>
                        </div>
                        
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered border-secondary">
                                <thead class="table-header">
                                    <tr>
                                        <th>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="checkAll">
                                            </div>
                                        </th>
                                        <th>No <i class="fas fa-sort"></i></th>
                                        <th>NIP <i class="fas fa-sort"></i></th>
                                        <th>Nama <i class="fas fa-sort"></i></th>
                                        <th>Email <i class="fas fa-sort"></i></th>
                                        <th>Keterangan <i class="fas fa-sort"></i></th>
                                        <th>Prodi <i class="fas fa-sort"></i></th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if(isset($dosens) && count($dosens) > 0)
                                        @foreach($dosens as $index => $dosen)
                                        <tr>
                                            <td>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" value="{{ $dosen->nip }}">
                                                </div>
                                            </td>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $dosen->nip }}</td>
                                            <td>{{ $dosen->nama }}</td>
                                            <td>{{ $dosen->email }}</td>
                                            <td>{{ $dosen->jabatan_fungsional ?? 'N/A' }}</td>
                                            <td>
                                                @if(isset($prodiMap[$dosen->prodi_id]))
                                                    {{ $prodiMap[$dosen->prodi_id] }}
                                                @else
                                                    N/A
                                                @endif
                                            </td>
                                            <td>
                                                <div class="dropdown">
                                                    <a href="#" class="edit-btn" data-bs-toggle="dropdown" aria-expanded="false">
                                                        <i class="fas fa-pen"></i>
                                                    </a>
                                                    <ul class="dropdown-menu">
                                                        <li><a class="dropdown-item" href="{{ route('admin.edit-dosen', $dosen->nip) }}"><i class="fas fa-edit me-2 text-primary"></i>Edit</a></li>
                                                        <li><a class="dropdown-item reset-password-btn" href="javascript:void(0)" data-nip="{{ $dosen->nip }}" data-nama="{{ $dosen->nama }}"><i class="fas fa-key me-2 text-warning"></i>Reset Password</a></li>
                                                        <li><hr class="dropdown-divider"></li>
                                                        <li>
                                                            <form action="{{ route('admin.delete-dosen', $dosen->nip) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus dosen ini?');">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="dropdown-item">
                                                                    <i class="fas fa-trash me-2 text-danger"></i>Hapus
                                                                </button>
                                                            </form>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </td>
                                        </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="8" class="text-center">Belum ada data dosen</td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="d-flex justify-content-start align-items-center mt-3">
                            <div>
                                <button class="btn btn-sm btn-outline-danger me-2" id="deleteSelected">
                                    <i class="fas fa-trash me-1"></i> Hapus yang dipilih
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Reset Password - DIPERBARUI dengan desain yang lebih modern -->
<div class="modal fade modal-reset-password" id="resetPasswordModal" tabindex="-1" aria-labelledby="resetPasswordModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="resetPasswordModalLabel"><i class="fas fa-key me-2"></i>Reset Password</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="resetPasswordModalBody">
                <div class="text-center mb-3">
                    <i class="fas fa-user-shield fa-3x mb-3" style="background: linear-gradient(to right, #004AAD, #5DE0E6); -webkit-background-clip: text; -webkit-text-fill-color: transparent;"></i>
                </div>
                <p class="text-center">Anda akan mereset password untuk dosen</p>
                <h5 class="text-center mb-3" id="dosenNama" style="font-weight: 600;"></h5>
                <p class="text-center text-muted">NIP: <span id="dosenNIP"></span></p>
                <div class="alert alert-info mt-3">
                    <i class="fas fa-info-circle me-2"></i>
                    Password akan direset ke nilai default. Dosen akan perlu mengubah passwordnya setelah login.
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-reset" id="confirmResetBtn">
                    <i class="fas fa-key me-2"></i>Reset Password
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // PERBAIKAN: Auto close dropdown lain ketika membuka dropdown baru
    
    // Tambahkan event listener untuk setiap dropdown toggle
    document.querySelectorAll('[data-bs-toggle="dropdown"]').forEach(function(dropdownToggle) {
        dropdownToggle.addEventListener('click', function(e) {
            // Tutup semua dropdown yang sedang terbuka kecuali yang diklik
            document.querySelectorAll('.dropdown-menu.show').forEach(function(openDropdown) {
                const currentDropdown = this.nextElementSibling;
                if (openDropdown !== currentDropdown) {
                    // Tutup dropdown lain
                    openDropdown.classList.remove('show');
                    // Hapus atribut aria-expanded pada toggle button
                    const otherToggle = openDropdown.previousElementSibling;
                    if (otherToggle) {
                        otherToggle.setAttribute('aria-expanded', 'false');
                    }
                }
            }.bind(this));
        });
    });
    
    // Tutup dropdown ketika klik di luar area dropdown
    document.addEventListener('click', function(e) {
        if (!e.target.closest('.dropdown')) {
            document.querySelectorAll('.dropdown-menu.show').forEach(function(openDropdown) {
                openDropdown.classList.remove('show');
                const toggle = openDropdown.previousElementSibling;
                if (toggle) {
                    toggle.setAttribute('aria-expanded', 'false');
                }
            });
        }
    });
    
    // Menyembunyikan semua flash message global dengan JavaScript
    document.querySelectorAll('.alert.alert-success:not(.mb-4), .alert.alert-danger:not(.mb-4)').forEach(function(alert) {
        alert.style.display = 'none';
    });
    
    // Menyembunyikan notifikasi hijau di atas
    const globalAlerts = document.querySelectorAll('.alert-success[style*="background-color"]');
    globalAlerts.forEach(function(alert) {
        if (!alert.classList.contains('mb-4')) {
            alert.style.display = 'none';
        }
    });
    
    // Check all checkboxes
    const checkAll = document.getElementById('checkAll');
    const checkboxes = document.querySelectorAll('tbody .form-check-input');
    
    if (checkAll) {
        checkAll.addEventListener('change', function() {
            checkboxes.forEach(checkbox => {
                checkbox.checked = checkAll.checked;
            });
        });
    }
    
    // Handle delete selected - KODE YANG DIPERBARUI
    document.getElementById('deleteSelected').addEventListener('click', function() {
        const selectedNips = [];
        document.querySelectorAll('tbody .form-check-input:checked').forEach(checkbox => {
            if (checkbox.value) {
                selectedNips.push(checkbox.value);
            }
        });
        
        if (selectedNips.length === 0) {
            alert('Pilih minimal satu dosen untuk dihapus');
            return;
        }
        
        if (confirm('Apakah Anda yakin ingin menghapus ' + selectedNips.length + ' dosen yang dipilih?')) {
            // Gunakan URL yang ada di aplikasi
            // Kita bisa gunakan route yang sudah ada untuk hapus satu dosen, lalu buat array
            selectedNips.forEach(nip => {
                // Buat form untuk setiap NIP
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = '/admin/delete-dosen/' + nip; // Sesuaikan dengan URL yang ada di aplikasi
                form.style.display = 'none';
                
                // Tambahkan CSRF token
                const csrfToken = document.createElement('input');
                csrfToken.type = 'hidden';
                csrfToken.name = '_token';
                csrfToken.value = document.querySelector('meta[name="csrf-token"]')?.content || '';
                form.appendChild(csrfToken);
                
                // Tambahkan method spoofing untuk DELETE
                const methodField = document.createElement('input');
                methodField.type = 'hidden';
                methodField.name = '_method';
                methodField.value = 'DELETE';
                form.appendChild(methodField);
                
                // Tambahkan ke document
                document.body.appendChild(form);
                
                // Kirim form secara asynchronous
                fetch(form.action, {
                    method: 'POST',
                    body: new FormData(form),
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                }).then(response => {
                    // Hapus form dari DOM
                    document.body.removeChild(form);
                }).catch(error => {
                    console.error('Error:', error);
                });
            });
            
            // Tunggu sebentar kemudian reload halaman
            setTimeout(function() {
                location.reload();
            }, 1000);
        }
    });

    // Fungsi untuk filter tabel berdasarkan jumlah yang ditampilkan
    const displayLimitSelect = document.getElementById('displayLimit');
    if (displayLimitSelect) {
        displayLimitSelect.addEventListener('change', function() {
            filterTable();
        });
    }

    // Fungsi untuk filter berdasarkan jurusan/prodi
    const prodiSelect = document.getElementById('prodiFilter');
    if (prodiSelect) {
        prodiSelect.addEventListener('change', function() {
            filterTable();
        });
    }

    // Fungsi untuk pencarian
    const searchInput = document.getElementById('searchInput');
    if (searchInput) {
        searchInput.addEventListener('keyup', function() {
            filterTable();
        });
    }

    // Fungsi untuk melakukan filter pada tabel
    function filterTable() {
        const displayLimit = parseInt(displayLimitSelect.value);
        const prodiFilter = prodiSelect.value;
        const searchQuery = searchInput.value.toLowerCase();
        
        const tableRows = document.querySelectorAll('tbody tr');
        let visibleCount = 0;
        
        tableRows.forEach(function(row) {
            // Skip row jika tidak berisi data dosen (baris pesan 'Belum ada data dosen')
            if (row.querySelectorAll('td').length <= 1) {
                return;
            }
            
            let showRow = true;
            
            // Filter berdasarkan prodi jika tidak 'Semua'
            if (prodiFilter !== 'Semua') {
                const prodiCell = row.querySelector('td:nth-child(7)').textContent.trim();
                if (prodiCell !== prodiFilter) {
                    showRow = false;
                }
            }
            
            // Filter berdasarkan pencarian
            if (searchQuery) {
                const nip = row.querySelector('td:nth-child(3)').textContent.toLowerCase();
                const nama = row.querySelector('td:nth-child(4)').textContent.toLowerCase();
                const email = row.querySelector('td:nth-child(5)').textContent.toLowerCase();
                const jabatan = row.querySelector('td:nth-child(6)').textContent.toLowerCase();
                
                if (!nip.includes(searchQuery) && 
                    !nama.includes(searchQuery) && 
                    !email.includes(searchQuery) && 
                    !jabatan.includes(searchQuery)) {
                    showRow = false;
                }
            }
            
            // Filter berdasarkan batas tampilan
            if (showRow && visibleCount < displayLimit) {
                row.style.display = '';
                visibleCount++;
            } else {
                row.style.display = 'none';
            }
        });
        
        // Update nomor urut berdasarkan yang terlihat
        updateRowNumbers();
    }

    // Fungsi untuk update nomor urut
    function updateRowNumbers() {
        const visibleRows = Array.from(document.querySelectorAll('tbody tr')).filter(row => row.style.display !== 'none');
        visibleRows.forEach((row, index) => {
            const numberCell = row.querySelector('td:nth-child(2)');
            if (numberCell) {
                numberCell.textContent = index + 1;
            }
        });
    }

    // Inisialisasi filter saat halaman dimuat
    filterTable();
    
    // Script untuk reset password dengan modal dan loading - DIPERBARUI
    const resetPasswordModal = document.getElementById('resetPasswordModal');
    const resetPasswordModalBody = document.getElementById('resetPasswordModalBody');
    const dosenNama = document.getElementById('dosenNama');
    const dosenNIP = document.getElementById('dosenNIP');
    const confirmResetBtn = document.getElementById('confirmResetBtn');
    
    // Inisialisasi modal Bootstrap
    const resetModal = new bootstrap.Modal(resetPasswordModal);
    
    // Handler untuk tombol reset password
    document.querySelectorAll('.reset-password-btn').forEach(function(btn) {
        btn.addEventListener('click', function() {
            const nip = this.getAttribute('data-nip');
            const nama = this.getAttribute('data-nama');
            
            // Set nilai di modal
            dosenNama.textContent = nama;
            dosenNIP.textContent = nip;
            
            // Tampilkan modal
            resetModal.show();
        });
    });
    
    // Handler untuk tombol konfirmasi di dalam modal
    confirmResetBtn.addEventListener('click', function() {
        const nip = dosenNIP.textContent;
        
        // Tampilkan loading
        resetPasswordModalBody.innerHTML = `
            <div class="loading-container">
                <div class="loading-spinner"></div>
                <div class="loading-text">Sedang mereset password...</div>
            </div>
        `;
        confirmResetBtn.style.display = 'none';
        
        // Redirect ke halaman reset password dengan timeout untuk efek loading
        setTimeout(function() {
            window.location.href = `/admin/reset-password-dosen/${nip}`;
        }, 1500); // Tunggu 1.5 detik untuk efek loading
    });
});
</script>
@endpush