@extends('layouts.app')

@section('title', 'Manajemen Mahasiswa - Admin')

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
    
    /* Tombol aksi positioning */
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
    
    /* Pastikan dropdown tidak terpotong di container */
    .card-body {
        overflow: visible !important;
        position: relative;
        min-height: 200px; /* Tambah tinggi minimum card body */
    }
    
    .card {
        border-radius: 10px;
        box-shadow: 0 0 5px rgba(0,0,0,0.1);
        border: none;
        overflow: visible !important;
        min-height: 250px; /* Tambah tinggi minimum card */
    }
    
    /* Container utama */
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
    
    /* Tambahan: Pastikan main content juga cukup tinggi */
    .main-content {
        padding-top: 20px; 
        padding-bottom: 200px; /* Tambah padding bawah lebih besar */
        min-height: 100vh; /* Pastikan main content tinggi */
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
        z-index: 2;
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
        overflow: visible !important; /* Tambahan untuk dropdown */
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
    
    /* RESPONSIVE FIXES untuk dropdown */
    @media (max-width: 768px) {
        .dropdown-menu {
            position: absolute !important;
            right: 0 !important;
            left: auto !important;
            transform: translateX(-50%) !important;
        }
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
                                    <label class="me-2 mb-0">Program Studi</label>
                                    <select class="form-select form-select-sm" style="width: 150px; height: 30px; padding: 2px 8px;" id="prodiFilter">
                                        <option>Semua</option>
                                        <option>Teknik Informatika</option>
                                        <option>Teknik Elektro</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-auto mb-2 ms-4">
                                <div class="d-flex align-items-center">
                                    <label class="me-2 mb-0">Angkatan</label>
                                    <select class="form-select form-select-sm" style="width: 100px; height: 30px; padding: 2px 8px;" id="angkatanFilter">
                                        <option>Semua</option>
                                        <option>2024</option>
                                        <option>2023</option>
                                        <option>2022</option>
                                        <option>2021</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col ms-auto mb-2 d-flex">
                                <div class="input-group">
                                    <input type="text" class="form-control" placeholder="Cari" style="height: 30px; font-size: 14px;" id="searchInput">
                                </div>
                                <a href="{{ route('admin.tambahmahasiswa') }}" class="btn ms-2" style="background: linear-gradient(to right, #00ad51, #00ad51); color: white; height: 30px; font-size: 14px; display: flex; align-items: center; white-space: nowrap; width: auto; padding: 0 10px;">
                                    <i class="fas fa-plus me-1"></i> Mahasiswa baru
                                </a>                                
                            </div>
                        </div>
                        
                        <!-- PERBAIKAN: Tabel dengan overflow yang tepat -->
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered border-secondary">
                                <thead class="table-header">
                                    <tr>
                                        <th>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="checkAll">
                                            </div>
                                        </th>
                                        <th>NIM <i class="fas fa-sort"></i></th>
                                        <th>Nama <i class="fas fa-sort"></i></th>
                                        <th>Email <i class="fas fa-sort"></i></th>
                                        <th>Angkatan <i class="fas fa-sort"></i></th>
                                        <th>Prodi <i class="fas fa-sort"></i></th>
                                        <th>Konsentrasi <i class="fas fa-sort"></i></th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if(isset($mahasiswas) && count($mahasiswas) > 0)
                                        @foreach($mahasiswas as $mahasiswa)
                                        <tr>
                                            <td>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" value="{{ $mahasiswa->nim }}">
                                                </div>
                                            </td>
                                            <td>{{ $mahasiswa->nim }}</td>
                                            <td>{{ $mahasiswa->nama }}</td>
                                            <td>{{ $mahasiswa->email }}</td>
                                            <td>{{ $mahasiswa->angkatan }}</td>
                                            <td>
                                                @if(isset($prodiMap[$mahasiswa->prodi_id]))
                                                    {{ $prodiMap[$mahasiswa->prodi_id] }}
                                                @else
                                                    N/A
                                                @endif
                                            </td>
                                            <td>
                                                @if($mahasiswa->konsentrasi_id && isset($konsentrasiMap[$mahasiswa->konsentrasi_id]))
                                                    {{ $konsentrasiMap[$mahasiswa->konsentrasi_id] }}
                                                @else
                                                    <span class="text-muted fst-italic">Belum dipilih</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="dropdown">
                                                    <a href="#" class="edit-btn" data-bs-toggle="dropdown" aria-expanded="false">
                                                        <i class="fas fa-pen"></i>
                                                    </a>
                                                    <ul class="dropdown-menu">
                                                        <li><a class="dropdown-item" href="{{ route('admin.edit-mahasiswa', $mahasiswa->nim) }}"><i class="fas fa-edit me-2 text-primary"></i>Edit</a></li>
                                                        <li><a class="dropdown-item reset-password-btn" href="javascript:void(0)" data-nim="{{ $mahasiswa->nim }}" data-nama="{{ $mahasiswa->nama }}"><i class="fas fa-key me-2 text-warning"></i>Reset Password</a></li>
                                                        <li><hr class="dropdown-divider"></li>
                                                        <li>
                                                            <form action="{{ route('admin.delete-mahasiswa', $mahasiswa->nim) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus mahasiswa ini?');">
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
                                            <td colspan="8" class="text-center">Belum ada data mahasiswa</td>
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
                        
                        <!-- Pesan jika tidak ada data yang ditemukan -->
                        <div id="noDataMessage" class="text-center py-4" style="display: none;">
                            <i class="fas fa-search fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">Data tidak tersedia</h5>
                            <p class="text-muted">Tidak ada data mahasiswa yang sesuai dengan kriteria pencarian Anda.</p>
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
                    <i class="fas fa-user-graduate fa-3x mb-3" style="background: linear-gradient(to right, #004AAD, #5DE0E6); -webkit-background-clip: text; -webkit-text-fill-color: transparent;"></i>
                </div>
                <p class="text-center">Anda akan mereset password untuk mahasiswa</p>
                <h5 class="text-center mb-3" id="mahasiswaNama" style="font-weight: 600;"></h5>
                <p class="text-center text-muted">NIM: <span id="mahasiswaNIM"></span></p>
                <div class="alert alert-info mt-3">
                    <i class="fas fa-info-circle me-2"></i>
                    Password akan direset ke nilai default. Mahasiswa akan perlu mengubah passwordnya setelah login.
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
        const selectedNims = [];
        document.querySelectorAll('tbody .form-check-input:checked').forEach(checkbox => {
            if (checkbox.value) {
                selectedNims.push(checkbox.value);
            }
        });
        
        if (selectedNims.length === 0) {
            alert('Pilih minimal satu mahasiswa untuk dihapus');
            return;
        }
        
        if (confirm('Apakah Anda yakin ingin menghapus ' + selectedNims.length + ' mahasiswa yang dipilih?')) {
            // Implementasi penghapusan mahasiswa yang dipilih
            // Gunakan URL yang ada di aplikasi untuk menghapus satu per satu
            let successCount = 0;
            let failCount = 0;
            let processedCount = 0;
            
            // Dapatkan token CSRF dari meta tag
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '';
            
            // Buat promises untuk setiap penghapusan
            const deletePromises = selectedNims.map(nim => {
                return new Promise((resolve, reject) => {
                    // Buat form untuk submission
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = '/admin/delete-mahasiswa/' + nim; // Sesuaikan dengan URL yang benar
                    form.style.display = 'none';
                    
                    // Tambahkan CSRF token
                    const tokenInput = document.createElement('input');
                    tokenInput.type = 'hidden';
                    tokenInput.name = '_token';
                    tokenInput.value = csrfToken;
                    form.appendChild(tokenInput);
                    
                    // Tambahkan method spoofing untuk DELETE
                    const methodInput = document.createElement('input');
                    methodInput.type = 'hidden';
                    methodInput.name = '_method';
                    methodInput.value = 'DELETE';
                    form.appendChild(methodInput);
                    
                    // Tambahkan form ke document
                    document.body.appendChild(form);
                    
                    // Kirim form dengan fetch
                    fetch(form.action, {
                        method: 'POST',
                        body: new FormData(form),
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(response => {
                        document.body.removeChild(form);
                        if (response.ok) {
                            successCount++;
                            resolve();
                        } else {
                            failCount++;
                            reject();
                        }
                    })
                    .catch(error => {
                        document.body.removeChild(form);
                        failCount++;
                        reject(error);
                    })
                    .finally(() => {
                        processedCount++;
                        // Jika semua telah diproses, reload halaman
                        if (processedCount === selectedNims.length) {
                            if (successCount > 0) {
                                alert('Berhasil menghapus ' + successCount + ' mahasiswa.' + 
                                      (failCount > 0 ? ' Gagal menghapus ' + failCount + ' mahasiswa.' : ''));
                                location.reload();
                            } else {
                                alert('Gagal menghapus mahasiswa. Silakan coba lagi nanti.');
                            }
                        }
                    });
                });
            });
            
            // Jalankan semua promises
            Promise.allSettled(deletePromises);
        }
    });
    
    // Fungsi untuk filter tabel berdasarkan input
    const displayLimitSelect = document.getElementById('displayLimit');
    const prodiFilter = document.getElementById('prodiFilter');
    const angkatanFilter = document.getElementById('angkatanFilter');
    const searchInput = document.getElementById('searchInput');
    const noDataMessage = document.getElementById('noDataMessage');
    const tableElement = document.querySelector('.table-responsive');
    
    function filterTable() {
        const displayLimit = parseInt(displayLimitSelect.value);
        const prodiValue = prodiFilter.value;
        const angkatanValue = angkatanFilter.value;
        const searchValue = searchInput.value.toLowerCase();
        
        const tableRows = document.querySelectorAll('tbody tr');
        let visibleCount = 0;
        let hasData = false;
        
        // Cek apakah ada data mahasiswa
        tableRows.forEach(row => {
            if (row.cells.length > 1) { // Bukan row "Belum ada data"
                hasData = true;
            }
        });
        
        tableRows.forEach(row => {
            if (row.cells.length <= 1) return; // Skip "Belum ada data" row
            
            let showRow = true;
            
            // Filter berdasarkan prodi
            if (prodiValue !== 'Semua') {
                const prodiCell = row.cells[5].textContent.trim();
                if (prodiCell !== prodiValue) {
                    showRow = false;
                }
            }
            
            // Filter berdasarkan angkatan
            if (angkatanValue !== 'Semua') {
                const angkatanCell = row.cells[4].textContent.trim();
                if (angkatanCell !== angkatanValue) {
                    showRow = false;
                }
            }
            
            // Filter berdasarkan pencarian
            if (searchValue) {
                const nim = row.cells[1].textContent.toLowerCase();
                const nama = row.cells[2].textContent.toLowerCase();
                const email = row.cells[3].textContent.toLowerCase();
                
                if (!nim.includes(searchValue) && !nama.includes(searchValue) && !email.includes(searchValue)) {
                    showRow = false;
                }
            }
            
            // Filter berdasarkan batas tampilan
            if (showRow && visibleCount < displayLimit) {
                row.style.display = '';
                visibleCount++;
            } else if (showRow) {
                row.style.display = 'none';
            } else {
                row.style.display = 'none';
            }
        });
        
        // Tampilkan pesan "Data tidak tersedia" jika tidak ada data yang cocok atau tidak ada data sama sekali
        if (visibleCount === 0) {
            noDataMessage.style.display = 'block';
            tableElement.style.display = 'none';
        } else {
            noDataMessage.style.display = 'none';
            tableElement.style.display = 'block';
        }
        
        // Re-initialize normal dropdown setelah filter (tidak perlu positioning khusus)
        // Bootstrap dropdown akan bekerja normal dengan CSS overflow fix
    }
    
    // Event listeners untuk filter
    if (displayLimitSelect) displayLimitSelect.addEventListener('change', filterTable);
    if (prodiFilter) prodiFilter.addEventListener('change', filterTable);
    if (angkatanFilter) angkatanFilter.addEventListener('change', filterTable);
    if (searchInput) searchInput.addEventListener('keyup', filterTable);
    
    // Inisialisasi filter saat halaman dimuat
    filterTable();
    
    // Script untuk reset password dengan modal dan loading - DIPERBARUI
    const resetPasswordModal = document.getElementById('resetPasswordModal');
    const resetPasswordModalBody = document.getElementById('resetPasswordModalBody');
    const mahasiswaNama = document.getElementById('mahasiswaNama');
    const mahasiswaNIM = document.getElementById('mahasiswaNIM');
    const confirmResetBtn = document.getElementById('confirmResetBtn');
    
    // Inisialisasi modal Bootstrap
    const resetModal = new bootstrap.Modal(resetPasswordModal);
    
    // Handler untuk tombol reset password
    document.querySelectorAll('.reset-password-btn').forEach(function(btn) {
        btn.addEventListener('click', function() {
            const nim = this.getAttribute('data-nim');
            const nama = this.getAttribute('data-nama');
            
            // Set nilai di modal
            mahasiswaNama.textContent = nama;
            mahasiswaNIM.textContent = nim;
            
            // Tampilkan modal
            resetModal.show();
        });
    });
    
    // Handler untuk tombol konfirmasi di dalam modal
    confirmResetBtn.addEventListener('click', function() {
        const nim = mahasiswaNIM.textContent;
        
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
            window.location.href = `/admin/reset-password-mahasiswa/${nim}`;
        }, 1500); // Tunggu 1.5 detik untuk efek loading
    });
});
</script>
@endpush