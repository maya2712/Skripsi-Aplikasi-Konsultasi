@extends('layouts.app')

@section('title', 'Buat Pesan Mahasiswa')
@push('styles')
    <style>
        :root {
            --bs-primary: #1a73e8;
            --bs-danger: #FF5252;
            --bs-success: #27AE60;
            --form-font-size: 13px; /* Ukuran font konsisten untuk semua elemen form */
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
        
        .form-header {
            padding-bottom: 15px;
            margin-bottom: 25px;
            border-bottom: 1px solid #dee2e6;
            font-weight: 700; /* Membuat tulisan lebih bold */
            background: linear-gradient(to right, #004AAD, #5DE0E6); /* Gradasi seperti button */
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            display: inline-block; /* Penting untuk gradient text */
        }
        
        .btn-gradient {
            background: linear-gradient(to right, #004AAD, #5DE0E6);
            color: white;
            border: none;
        }
        
        .btn-gradient:hover {
            background: linear-gradient(to right, #1558b7, #1558b7);
            color: white;
        }
        
        .btn-kembali {
            background: linear-gradient(to right, #004AAD, #5DE0E6);
            color: white;
            border: none;
            display: flex;
            align-items: center;
            padding: 8px 15px;
            border-radius: 5px;
            text-decoration: none;
            font-weight: 500;
            margin-bottom: 20px;
            width: fit-content;
        }
        
        .btn-kembali:hover {
            background: linear-gradient(to right, #1558b7, #1558b7);
            color: white;
        }
        
        .form-label {
            font-weight: 500;
        }
        
        .required-field:after {
            content: "*";
            color: red;
        }
        
        /* Menyamakan semua input form */
        .form-control, 
        .form-select, 
        .custom-select select, 
        .dosen-dropdown-toggle,
        .dosen-dropdown-search input,
        .dosen-dropdown-item,
        .dosen-dropdown-placeholder,
        .selected-dosen,
        .no-results {
            font-size: var(--form-font-size) !important;
        }
        
        /* Pastikan textarea juga memiliki font yang sama */
        textarea.form-control {
            font-size: var(--form-font-size) !important;
            font-family: inherit;
        }
        
        .custom-select {
            position: relative;
            width: 100%;
        }

        .custom-select select {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            appearance: none;
            -webkit-appearance: none;
            -moz-appearance: none;
            background: white;
            cursor: pointer;
        }

        .custom-select::after {
            content: '\f107';
            font-family: 'Font Awesome 5 Free';
            font-weight: 900;
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            pointer-events: none;
        }

        .dosen-dropdown {
            position: relative;
            width: 100%;
        }
        
        .dosen-dropdown-toggle {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            background: white;
            cursor: pointer;
            text-align: left;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .dosen-dropdown-menu {
            position: absolute;
            left: 0;
            top: 100%;
            width: 100%;
            max-height: 250px;
            overflow-y: auto;
            border: 1px solid #ddd;
            border-radius: 4px;
            background: white;
            z-index: 1000;
            display: none;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        
        .dosen-dropdown-item {
            padding: 8px 12px;
            cursor: pointer;
            border-bottom: 1px solid #f5f5f5;
        }
        
        .dosen-dropdown-item:hover {
            background-color: #f8f9fa;
        }
        
        .dosen-dropdown-search {
            padding: 8px;
            border-bottom: 1px solid #ddd;
            position: sticky;
            top: 0;
            background: white;
            z-index: 1001;
        }
        
        .dosen-dropdown-search input {
            width: 100%;
            padding: 6px 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        
        .selected-dosen {
            display: flex;
            align-items: center;
        }
        
        .selected-dosen-avatar {
            width: 20px;
            height: 20px;
            border-radius: 50%;
            background-color: #e9ecef;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 8px;
            font-size: 10px;
        }
        
        .dosen-dropdown-placeholder {
            color: #6c757d;
        }
        
        .no-results {
            padding: 12px;
            text-align: center;
            color: #6c757d;
            font-style: italic;
            display: none;
        }
        
        .dosen-jabatan {
            color: #6c757d;
            font-size: var(--form-font-size) !important;
        }
        
        /* Khusus untuk Bootstrap form-control */
        input.form-control, 
        textarea.form-control {
            font-size: var(--form-font-size) !important;
        }
        
        /* Override untuk Bootstrap placeholder */
        .form-control::placeholder,
        .form-select::placeholder,
        input::placeholder {
            font-size: var(--form-font-size) !important;
        }
        
        /* Heading dengan gradasi */
        .gradient-heading {
            font-weight: 700;
            background: linear-gradient(to right, #004AAD, #5DE0E6);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            display: inline-block;
            margin-bottom: 5px;
        }
        
        /* Tambahkan styling untuk dropdown header */
        .dropdown-header {
            padding: 8px 12px;
            background-color: #f3f4f6;
            font-weight: 600;
            color: #495057;
            font-size: var(--form-font-size) !important;
        }
        
        /* Badge untuk menampilkan role penerima */
        .role-badge {
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 10px;
            font-weight: 500;
            margin-left: 8px;
        }
        
        .role-badge-kaprodi {
            background-color: #17a2b8;
            color: white;
        }
        
        .role-badge-dosen {
            background-color: #6c757d;
            color: white;
        }
    </style>
@endpush

@section('content')
<div class="main-content">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <h4 class="gradient-heading">BUAT PESAN BARU</h4>
                <hr class="mb-4">
                
                <a href="{{ route('mahasiswa.dashboard.pesan') }}" class="btn-kembali">
                    <i class="fas fa-arrow-left me-2"></i> Kembali
                </a>
                
                <form id="formPesan">
                    @csrf
                    <div class="mb-4">
                        <label for="subjek" class="form-label required-field fs-6">Subjek</label>
                        <div class="custom-select">
                            <select class="form-select text-muted" id="subjek" name="subjek" required>
                                <option value="" disabled selected>Masukkan subjek</option>
                                <option value="Bimbingan KRS">Bimbingan KRS</option>
                                <option value="Bimbingan KP">Bimbingan KP</option>
                                <option value="Bimbingan Skripsi">Bimbingan Skripsi</option>
                                <option value="Bimbingan MBKM">Bimbingan MBKM</option>
                                <option value="Lainnya">Lainnya</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <label for="penerima" class="form-label required-field fs-6">Penerima</label>
                        <div class="dosen-dropdown">
                            <div class="dosen-dropdown-toggle" id="dosenDropdownToggle">
                                <span class="dosen-dropdown-placeholder">Pilih dosen</span>
                                <i class="fas fa-chevron-down"></i>
                            </div>
                            <div class="dosen-dropdown-menu" id="dosenDropdownMenu">
                                <div class="dosen-dropdown-search">
                                    <input type="text" id="dosenSearchInput" placeholder="Cari dosen..." autocomplete="off">
                                </div>
                                <div id="dosenDropdownItems">
                                    <!-- Daftar dosen akan muncul di sini berdasarkan pencarian -->
                                </div>
                                <div class="no-results" id="noResults">
                                    Tidak ada dosen ditemukan
                                </div>
                            </div>
                        </div>
                        
                        <!-- Hidden fields -->
                        <input type="hidden" id="selectedDosenId" name="dosenId">
                        <input type="hidden" id="selectedDosenNama" name="dosenNama">
                        <input type="hidden" id="selectedDosenJabatan" name="dosenJabatan">
                        <input type="hidden" id="selectedDosenRole" name="penerima_role">
                    </div>
                    
                    <div class="mb-4">
                        <label for="prioritas" class="form-label required-field fs-6">Prioritas</label>
                        <div class="custom-select">
                            <select class="form-select text-muted" id="prioritas" name="prioritas" required>
                                <option value="" disabled selected>Masukkan prioritas</option>
                                <option value="Penting">Penting</option>
                                <option value="Umum">Umum</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <label for="lampiran" class="form-label fs-6">Lampiran (Link Google Drive)</label>
                        <input type="url" class="form-control" id="lampiran" name="lampiran" placeholder="Masukkan link Google Drive">
                    </div>
                    
                    <div class="mb-4">
                        <label for="pesanText" class="form-label required-field fs-6">Pesan</label>
                        <textarea class="form-control" id="pesanText" name="pesanText" rows="8" placeholder="Tulis pesan Anda di sini..." required></textarea>
                    </div>
                    
                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-gradient px-4 py-2">
                            Kirim
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Perbaikan JavaScript untuk menampilkan daftar dosen
    document.addEventListener('DOMContentLoaded', function() {
        // Mengambil data dosen dari database
        let dataDosen = [];
        
        // Menggunakan Blade untuk mengisi data dosen dengan peran yang benar
        @foreach($dosen as $d)
            dataDosen.push({ 
                id: '{{ $d['nip'] }}', 
                nama: '{{ $d['nama'] }}',
                role: '{{ $d['role'] }}',
                displayName: '{{ $d['nama'] }}{{ $d['role'] == "kaprodi" ? " (Sebagai Kaprodi)" : "" }}',
                jabatan: '{{ $d['jabatan_fungsional'] ?? "Dosen" }}'
            });
        @endforeach
        
        // Log untuk debugging
        console.log('Data dosen:', dataDosen);
        
        // Variabel untuk menyimpan data dosen yang dipilih
        let selectedDosen = null;
        
        // Get DOM elements
        const dosenDropdownToggle = document.getElementById('dosenDropdownToggle');
        const dosenDropdownMenu = document.getElementById('dosenDropdownMenu');
        const dosenSearchInput = document.getElementById('dosenSearchInput');
        const dosenDropdownItems = document.getElementById('dosenDropdownItems');
        const noResults = document.getElementById('noResults');
        
        // Toggle dropdown
        dosenDropdownToggle.addEventListener('click', function() {
            if (dosenDropdownMenu.style.display === 'block') {
                dosenDropdownMenu.style.display = 'none';
            } else {
                dosenDropdownMenu.style.display = 'block';
                dosenSearchInput.focus();
                
                // Bersihkan dropdown items dulu
                dosenDropdownItems.innerHTML = '';
                
                // Tampilkan kolom search, tapi tidak langsung tampilkan dosen
                dosenSearchInput.value = '';
                noResults.style.display = 'none';
            }
        });
        
        // Render dosen list dengan info jabatan yang ditambahkan
        function renderDosenList(dosenList) {
            dosenDropdownItems.innerHTML = '';
            
            if (dosenList.length > 0) {
                noResults.style.display = 'none';
                
                // Group dosen vs kaprodi
                const dosenRegular = dosenList.filter(d => d.role === 'dosen');
                const dosenKaprodi = dosenList.filter(d => d.role === 'kaprodi');
                
                // Tambahkan header untuk dosen
                if (dosenRegular.length > 0) {
                    const header = document.createElement('div');
                    header.className = 'dropdown-header';
                    header.textContent = 'Dosen';
                    dosenDropdownItems.appendChild(header);
                    
                    dosenRegular.forEach(dosen => {
                        appendDosenItem(dosen);
                    });
                }
                
                // Tambahkan header untuk kaprodi
                if (dosenKaprodi.length > 0) {
                    const header = document.createElement('div');
                    header.className = 'dropdown-header';
                    header.textContent = 'Kaprodi';
                    dosenDropdownItems.appendChild(header);
                    
                    dosenKaprodi.forEach(dosen => {
                        appendDosenItem(dosen);
                    });
                }
            } else {
                noResults.style.display = 'block';
            }
        }
        
        function appendDosenItem(dosen) {
            const item = document.createElement('div');
            item.className = 'dosen-dropdown-item';
            item.innerHTML = `
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <div>${dosen.nama}</div>
                    </div>
                    <span class="role-badge ${dosen.role === 'kaprodi' ? 'role-badge-kaprodi' : 'role-badge-dosen'}">
                        ${dosen.role === 'kaprodi' ? 'Kaprodi' : 'Dosen'}
                    </span>
                </div>
            `;
            
            item.addEventListener('click', function() {
                pilihDosen(dosen);
            });
            
            dosenDropdownItems.appendChild(item);
        }
        
        // Fungsi untuk memilih dosen
        function pilihDosen(dosen) {
            selectedDosen = dosen;
            
            // Set nilai ke hidden fields
            document.getElementById('selectedDosenId').value = dosen.id;
            document.getElementById('selectedDosenNama').value = dosen.nama;
            document.getElementById('selectedDosenJabatan').value = dosen.jabatan;
            document.getElementById('selectedDosenRole').value = dosen.role;
            
            // Update dropdown toggle text
            dosenDropdownToggle.innerHTML = `
                <div class="selected-dosen">
                    <div class="selected-dosen-avatar">
                        <i class="fas fa-user" style="font-size: 8px;"></i>
                    </div>
                    <span>${dosen.nama}${dosen.role === 'kaprodi' ? ' (Sebagai Kaprodi)' : ''}</span>
                    <span class="role-badge ${dosen.role === 'kaprodi' ? 'role-badge-kaprodi' : 'role-badge-dosen'}">
                        ${dosen.role === 'kaprodi' ? 'Kaprodi' : 'Dosen'}
                    </span>
                </div>
                <i class="fas fa-chevron-down"></i>
            `;
            
            // Hide dropdown
            dosenDropdownMenu.style.display = 'none';
        }
        
        // Search functionality
        dosenSearchInput.addEventListener('input', function() {
            const keyword = this.value.toLowerCase().trim();
            
            if (keyword === '') {
                // Jika search dikosongkan, hilangkan semua hasil
                dosenDropdownItems.innerHTML = '';
                noResults.style.display = 'none';
                return;
            }
            
            // Filter dosen berdasarkan keyword
            const filteredDosen = dataDosen.filter(dosen => {
                return dosen.nama.toLowerCase().includes(keyword) || 
                    dosen.jabatan.toLowerCase().includes(keyword);
            });
            
            renderDosenList(filteredDosen);
        });
        
        // Close dropdown when clicking outside
        document.addEventListener('click', function(e) {
            if (!dosenDropdownToggle.contains(e.target) && !dosenDropdownMenu.contains(e.target)) {
                dosenDropdownMenu.style.display = 'none';
            }
        });
        
        // Event listener untuk form submission
        document.getElementById('formPesan').addEventListener('submit', function(e) {
            e.preventDefault();
            
            if (!selectedDosen) {
                alert('Pilih penerima pesan terlebih dahulu');
                return false;
            }
            
            // Kumpulkan data dari form
            const formData = new FormData(this);
            
            // Kirim data via AJAX
            fetch('{{ route("mahasiswa.pesan.store") }}', {
                method: 'POST',
                body: formData,
                headers: {
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
                    alert('Pesan berhasil dikirim!');
                    
                    // Reset form
                    this.reset();
                    selectedDosen = null;
                    document.getElementById('selectedDosenId').value = '';
                    document.getElementById('selectedDosenNama').value = '';
                    document.getElementById('selectedDosenJabatan').value = '';
                    document.getElementById('selectedDosenRole').value = '';
                    dosenDropdownToggle.innerHTML = `
                        <span class="dosen-dropdown-placeholder">Pilih dosen</span>
                        <i class="fas fa-chevron-down"></i>
                    `;
                    
                    // Redirect ke dashboard pesan
                    window.location.href = '{{ route("mahasiswa.dashboard.pesan") }}';
                } else {
                    alert('Gagal mengirim pesan: ' + (data.message || 'Terjadi kesalahan'));
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Terjadi kesalahan saat mengirim pesan. Silahkan coba lagi.');
            });
        });
    });
</script>
@endpush