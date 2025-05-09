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
    }
    
    body {
        background-color: #F5F7FA;
        font-size: 13px;
    }

    .main-content {
        padding-top: 20px; 
        padding-bottom: 20px; 
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
    
    /* Modifikasi disini - hanya submenu yang memiliki hover effect */
    .sidebar-menu .submenu-active .nav-link:hover {
        background: #f8f9fa;
    }
    
    /* Pada menu parent tidak ada hover effect */
    .sidebar-menu .nav-link.parent-menu:hover {
        background: transparent;
    }
    
    .sidebar-menu .nav-link.parent-active {
        background: #EDF8FF;
        color: #1a73e8;
    }
    
    .sidebar-menu .nav-link.parent-active:hover {
        background: #EDF8FF;
    }
    
    .sidebar-menu .submenu-active {
        display: block !important;
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
                        <a href="{{ url('/addmessage_admin') }}" class="btn w-100" style="background: linear-gradient(to right, #004AAD, #5DE0E6); color: white; padding: 10px 20px; border: none; border-radius: 5px;">
                            <i class="fas fa-plus me-2"></i> Pesan Baru
                        </a>
                    </div>                                                    
                    
                    <div class="sidebar-menu">
                        <div class="nav flex-column">
                            <a href="{{ route('admin.dashboard') }}" class="nav-link">
                                <i class="fas fa-tachometer-alt me-2"></i>Dashboard Admin
                            </a>
                            <a href="#" class="nav-link parent-active parent-menu" id="userManagementToggle">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span><i class="fas fa-users-cog me-2"></i>Manajemen User</span>
                                    <i class="fas fa-chevron-up" id="userManagementIcon"></i>
                                </div>
                            </a>
                            <div class="show submenu-active" id="userManagementSubmenu">
                                <div class="ps-3">
                                    <a href="{{ url('/dosen_admin') }}" class="nav-link active">
                                        <i class="fas fa-chalkboard-teacher me-2"></i>Dosen
                                    </a>
                                    <a href="{{ url('/admin/managementuser_mahasiswa') }}" class="nav-link">
                                        <i class="fas fa-user-graduate me-2"></i>Mahasiswa
                                    </a>
                                </div>
                            </div>
                            <a href="#" class="nav-link parent-menu" id="grupDropdownToggle">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span><i class="fas fa-user-tag me-2"></i>Daftar Grup</span>
                                    <i class="fas fa-chevron-down" id="grupDropdownIcon"></i>
                                </div>
                            </a>
                            <div class="collapse" id="komunikasiSubmenu">
                                <div class="ps-3">
                                    <a href="{{ url('/creategroup_admin') }}" class="nav-link">
                                        <i class="fas fa-plus me-2"></i>Buat Grup Baru
                                    </a>
                                    <a href="{{ url('/groupmanagement_admin') }}" class="nav-link">
                                        <i class="fas fa-list me-2"></i>Lihat Semua Grup
                                    </a>
                                </div>
                            </div>
                            <a href="{{ url('/logs_admin') }}" class="nav-link">
                                <i class="fas fa-history me-2"></i>Riwayat
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Content -->
            <div class="col-md-9">
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
                                        <th>Jabatan <i class="fas fa-sort"></i></th>
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
                                                    <a href="#" class="edit-btn" data-bs-toggle="dropdown">
                                                        <i class="fas fa-pen"></i>
                                                    </a>
                                                    <ul class="dropdown-menu dropdown-menu-end">
                                                        <li><a class="dropdown-item" href="{{ route('admin.edit-dosen', $dosen->nip) }}"><i class="fas fa-edit me-2 text-primary"></i>Edit</a></li>
                                                        <li><a class="dropdown-item" href="#"><i class="fas fa-key me-2 text-warning"></i>Reset Password</a></li>
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
                        
                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <div>
                                <button class="btn btn-sm btn-outline-danger me-2" id="deleteSelected">
                                    <i class="fas fa-trash me-1"></i> Hapus yang dipilih
                                </button>
                            </div>
                            
                            <!-- Pagination -->
                            <nav aria-label="Page navigation">
                                <ul class="pagination pagination-sm mb-0">
                                    <li class="page-item disabled">
                                        <a class="page-link" href="#" aria-label="Previous">
                                            <span aria-hidden="true">&laquo;</span>
                                        </a>
                                    </li>
                                    <li class="page-item active"><a class="page-link" href="#">1</a></li>
                                    <li class="page-item"><a class="page-link" href="#">2</a></li>
                                    <li class="page-item"><a class="page-link" href="#">3</a></li>
                                    <li class="page-item">
                                        <a class="page-link" href="#" aria-label="Next">
                                            <span aria-hidden="true">&raquo;</span>
                                        </a>
                                    </li>
                                </ul>
                            </nav>
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

    // Toggle dropdown for grup
    const grupDropdownToggle = document.getElementById('grupDropdownToggle');
    const komunikasiSubmenu = document.getElementById('komunikasiSubmenu');
    const grupDropdownIcon = document.getElementById('grupDropdownIcon');
    
    if (grupDropdownToggle && komunikasiSubmenu && grupDropdownIcon) {
        grupDropdownToggle.addEventListener('click', function() {
            // Toggle the collapse
            const bsCollapse = new bootstrap.Collapse(komunikasiSubmenu, {
                toggle: true
            });
            
            // Toggle the icon
            grupDropdownIcon.classList.toggle('fa-chevron-up');
            grupDropdownIcon.classList.toggle('fa-chevron-down');
        });
    }
    
    // Toggle dropdown for user management (but prevent closing if in submenu page)
    const userManagementToggle = document.getElementById('userManagementToggle');
    const userManagementSubmenu = document.getElementById('userManagementSubmenu');
    const userManagementIcon = document.getElementById('userManagementIcon');
    
    if (userManagementToggle && userManagementSubmenu && userManagementIcon) {
        // If we're already in a submenu page, make sure the submenu stays open
        if (userManagementSubmenu.classList.contains('submenu-active')) {
            userManagementSubmenu.classList.add('show');
            userManagementIcon.classList.add('fa-chevron-up');
            userManagementIcon.classList.remove('fa-chevron-down');
        }
        
        userManagementToggle.addEventListener('click', function(e) {
            // If we're in a submenu page, prevent toggle action
            if (userManagementSubmenu.classList.contains('submenu-active')) {
                e.preventDefault();
                e.stopPropagation();
                return false;
            }
            
            // Otherwise, toggle as normal
            const bsCollapse = new bootstrap.Collapse(userManagementSubmenu, {
                toggle: true
            });
            
            // Toggle the icon
            userManagementIcon.classList.toggle('fa-chevron-up');
            userManagementIcon.classList.toggle('fa-chevron-down');
        });
    }
    
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
});
</script>
@endpush