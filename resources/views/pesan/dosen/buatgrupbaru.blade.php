@extends('layouts.app')

@section('title', 'Buat Grup Baru')

@push('styles')
    <style>
        :root {
            --bs-primary: #1a73e8;
            --bs-danger: #FF5252;
            --bs-success: #27AE60;
            --gradient-primary: linear-gradient(to right, #004AAD, #5DE0E6);
        }

        body {
            background-color: #F5F7FA;
            font-size: 14px;
        }

        .main-content {
            padding-top: 20px;
            padding-bottom: 20px;
        }

        .form-control, .form-select {
            font-size: 14px;
            padding: 0.6rem 0.85rem;
        }

        .btn {
            font-size: 14px;
            padding: 0.6rem 1.2rem;
        }

        .badge {
            font-weight: normal;
            font-size: 13px;
        }

        .card {
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
            border-radius: 8px;
        }

        .title-divider {
            border-bottom: 2px solid #dee2e6;
            padding-bottom: 15px;
            margin-bottom: 20px;
        }

        .btn-gradient-primary {
            background: var(--gradient-primary);
            border: none;
            color: white;
            transition: all 0.3s ease;
        }

        .btn-gradient-primary:hover {
            background: linear-gradient(to right, #003c8a, #4bc4c9);
            color: white;
            box-shadow: 0 2px 5px rgba(0,0,0,0.2);
        }
        
        .form-check-label {
            font-size: 14px;
        }
        
        h4 {
            font-size: 22px;
            font-weight: 600;
        }
        
        h6 {
            font-size: 16px;
            font-weight: 500;
        }
        
        .form-label {
            font-size: 14px;
        }
        
        .search-result {
            max-height: 400px;
            overflow-y: auto;
        }
        
        .search-result::-webkit-scrollbar {
            width: 6px;
        }
        
        .search-result::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }
        
        .search-result::-webkit-scrollbar-thumb {
            background: #888;
            border-radius: 10px;
        }
        
        .search-result::-webkit-scrollbar-thumb:hover {
            background: #555;
        }
        
        .no-results {
            padding: 15px;
            text-align: center;
            color: #6c757d;
        }
        
        #search-info {
            font-size: 13px;
            color: #6c757d;
            margin-top: 5px;
        }
        
        /* Style untuk role badge */
        .role-badge {
            display: inline-block;
            padding: 5px 10px;
            border-radius: 30px;
            font-size: 12px;
            margin-bottom: 10px;
        }
        
        .required-field:after {
            content: "*";
            color: red;
            margin-left: 3px;
        }
        
        /* Style untuk modal loading */
        .modal-loading .modal-content {
            border: none;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }
        
        .modal-loading .loading-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 2rem;
        }
        
        .modal-loading .loading-spinner {
            display: inline-block;
            width: 3rem;
            height: 3rem;
            border: 3px solid rgba(0, 0, 0, 0.1);
            border-radius: 50%;
            border-top-color: #5DE0E6;
            border-left-color: #004AAD;
            animation: spin 1s ease-in-out infinite;
        }
        
        @keyframes spin {
            to { transform: rotate(360deg); }
        }
        
        .modal-loading .loading-text {
            margin-top: 15px;
            font-size: 16px;
            font-weight: 500;
            background: var(--gradient-primary);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        
        /* Style untuk modal sukses/error */
        .modal-status .modal-header {
            border-bottom: none;
            border-top-left-radius: 10px;
            border-top-right-radius: 10px;
            padding: 1.5rem 1.5rem 0.5rem;
        }
        
        .modal-status .modal-header.success {
            background: var(--gradient-primary);
            color: white;
        }
        
        .modal-status .modal-header.error {
            background: linear-gradient(to right, #D32F2F, #FF5252);
            color: white;
        }
        
        .modal-status .modal-content {
            border: none;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        
        .modal-status .status-icon {
            font-size: 3rem;
            margin-bottom: 1rem;
        }
        
        .modal-status .status-icon.text-success i {
            background: var(--gradient-primary);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        
        .modal-status .status-title {
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
        }
        
        .modal-status .status-message {
            font-size: 1rem;
            color: #6c757d;
        }
        
        .modal-status .modal-body {
            padding: 1.5rem;
            text-align: center;
        }
        
        .modal-status .modal-footer {
            border-top: none;
            justify-content: center;
            padding-bottom: 1.5rem;
        }
        
        .modal-status .btn-status {
            padding: 0.5rem 2rem;
            border-radius: 5px;
            font-weight: 500;
        }
        
        .modal-status .btn-success {
            background: var(--gradient-primary);
            border: none;
            color: white;
            transition: all 0.3s ease;
        }
        
        .modal-status .btn-success:hover {
            opacity: 0.9;
            box-shadow: 0 2px 8px rgba(0, 74, 173, 0.3);
        }
        
        .modal-status .btn-error {
            background: linear-gradient(to right, #D32F2F, #FF5252);
            border: none;
            color: white;
        }
    </style>
@endpush

@section('content')
<div class="container py-4">
    <div class="title-divider">
        <h4 class="mb-0">Buat Grup Baru</h4>
    </div>

    <a href="{{ route('back') }}" class="btn btn-gradient-primary mb-4">
        <i class="fas fa-arrow-left me-2"></i> Kembali
    </a>

    @if ($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    @if(session()->has('active_role'))
    <div class="alert alert-info">
        <i class="fas fa-info-circle me-2"></i>
        Grup ini akan dibuat sebagai grup {{ session('active_role') === 'kaprodi' ? 'Kaprodi' : 'Dosen' }}. Anda hanya dapat melihat dan mengelola grup ini saat berada dalam peran {{ session('active_role') === 'kaprodi' ? 'Kaprodi' : 'Dosen' }}.
    </div>
    @endif

    <form id="formGrup" action="{{ route('dosen.grup.store') }}" method="POST">
        @csrf
        <div class="card">
            <div class="card-body p-4">
                <div class="mb-4">
                    <label class="form-label fw-semibold required-field">Nama Grup</label>
                    <input type="text" name="nama_grup" id="nama_grup" class="form-control rounded-2" placeholder="Masukkan nama grup" required>
                </div>            

                <div class="mb-4">
                    <label class="form-label fw-semibold required-field">Anggota Grup</label>
                    <div class="input-group mb-2">
                        <input type="text" id="search_mahasiswa" class="form-control rounded-start" placeholder="Cari mahasiswa (minimal 3 karakter)...">
                        <button class="btn btn-outline-secondary" type="button" id="clearSearch">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                    <div id="search-info">Ketik minimal 3 karakter untuk mencari mahasiswa</div>

                    <div class="mt-3">
                        <div class="card border-0 shadow-sm">
                            <div class="card-body search-result" id="search_results">
                                <!-- Hasil pencarian akan ditampilkan di sini -->
                                <div class="no-results">
                                    Ketik nama atau NIM mahasiswa pada form pencarian
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-3">
                        <div class="card border-0 shadow-sm">
                            <div class="card-body">
                                <h6 class="mb-3">Anggota Terpilih:</h6>
                                <div id="selected_members">
                                    <p class="text-muted" id="no_selected">Belum ada anggota yang dipilih</p>
                                    <!-- Anggota yang dipilih akan ditampilkan di sini -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="text-end">
                    <button type="submit" class="btn btn-gradient-primary px-4" id="buatGrupBtn">
                        <i class="fas fa-users me-2"></i>Buat Grup
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>

<!-- Modal Loading -->
<div class="modal fade modal-loading" id="loadingModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content">
            <div class="loading-container">
                <div class="loading-spinner"></div>
                <div class="loading-text">Membuat grup...</div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Status (Success/Error) -->
<div class="modal fade modal-status" id="statusModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header" id="statusModalHeader">
                <!-- Header akan diisi dinamis -->
            </div>
            <div class="modal-body" id="statusModalBody">
                <!-- Body akan diisi dinamis -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-status" id="statusModalBtn"></button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('search_mahasiswa');
    const searchResults = document.getElementById('search_results');
    const selectedMembersContainer = document.getElementById('selected_members');
    const noSelectedMessage = document.getElementById('no_selected');
    const clearSearchBtn = document.getElementById('clearSearch');
    const searchInfo = document.getElementById('search-info');
    const formGrup = document.getElementById('formGrup');
    const buatGrupBtn = document.getElementById('buatGrupBtn');
    const namaGrupInput = document.getElementById('nama_grup');
    
    // Inisialisasi modal
    const loadingModal = new bootstrap.Modal(document.getElementById('loadingModal'));
    const statusModal = new bootstrap.Modal(document.getElementById('statusModal'));
    
    // Data mahasiswa dari database
    const mahasiswaData = [
        @foreach($mahasiswa as $mhs)
        {
            nim: '{{ $mhs->nim }}',
            nama: '{{ $mhs->nama }}'
        },
        @endforeach
    ];
    
    // Simpan mahasiswa yang dipilih
    const selectedMahasiswa = new Set();
    
    // Fungsi untuk memperbarui tampilan anggota yang dipilih
    function updateSelectedMembers() {
        if (selectedMahasiswa.size === 0) {
            noSelectedMessage.style.display = 'block';
        } else {
            noSelectedMessage.style.display = 'none';
        }
        
        // Hapus badge yang sudah tidak dipilih
        document.querySelectorAll('#selected_members .badge').forEach(badge => {
            const nim = badge.getAttribute('data-nim');
            if (!selectedMahasiswa.has(nim)) {
                badge.remove();
            }
        });
        
        // Tambahkan badge untuk mahasiswa yang baru dipilih
        selectedMahasiswa.forEach(nim => {
            if (!document.querySelector(`#selected_members .badge[data-nim="${nim}"]`)) {
                const mahasiswa = mahasiswaData.find(m => m.nim === nim);
                if (mahasiswa) {
                    const badge = document.createElement('span');
                    badge.className = 'badge bg-light text-dark border p-2 me-2 mb-2';
                    badge.setAttribute('data-nim', nim);
                    badge.innerHTML = `
                        ${mahasiswa.nama} - ${mahasiswa.nim}
                        <button type="button" class="btn-close ms-2" style="font-size: 8px;" data-nim="${nim}"></button>
                        <input type="hidden" name="anggota[]" value="${nim}">
                    `;
                    selectedMembersContainer.appendChild(badge);
                }
            }
        });
        
        // Tambahkan event listener untuk tombol close di badges
        document.querySelectorAll('#selected_members .btn-close').forEach(btn => {
            btn.addEventListener('click', function() {
                const nim = this.getAttribute('data-nim');
                selectedMahasiswa.delete(nim);
                updateSelectedMembers();
                
                // Update checkbox jika sedang ditampilkan
                const checkbox = document.querySelector(`input[type="checkbox"][value="${nim}"]`);
                if (checkbox) {
                    checkbox.checked = false;
                }
            });
        });
    }
    
    // Fungsi untuk menampilkan hasil pencarian
    function showSearchResults(searchTerm) {
        if (searchTerm.length < 3) {
            searchResults.innerHTML = `
                <div class="no-results">
                    Ketik minimal 3 karakter untuk mencari mahasiswa
                </div>
            `;
            return;
        }
        
        searchTerm = searchTerm.toLowerCase();
        
        // Filter mahasiswa berdasarkan kata kunci
        const filteredMahasiswa = mahasiswaData.filter(mhs => 
            mhs.nama.toLowerCase().includes(searchTerm) || 
            mhs.nim.toLowerCase().includes(searchTerm)
        );
        
        if (filteredMahasiswa.length === 0) {
            searchResults.innerHTML = `
                <div class="no-results">
                    Tidak ada mahasiswa yang sesuai dengan kata kunci "${searchTerm}"
                </div>
            `;
            return;
        }
        
        // Tampilkan hasil pencarian
        let html = '<div class="list-group">';
        
        filteredMahasiswa.forEach(mhs => {
            const isSelected = selectedMahasiswa.has(mhs.nim);
            html += `
                <div class="list-group-item">
                    <div class="form-check">
                        <input class="form-check-input mahasiswa-checkbox" type="checkbox" value="${mhs.nim}" id="mhs${mhs.nim}" ${isSelected ? 'checked' : ''}>
                        <label class="form-check-label" for="mhs${mhs.nim}">
                            ${mhs.nama} - ${mhs.nim}
                        </label>
                    </div>
                </div>
            `;
        });
        
        html += '</div>';
        searchResults.innerHTML = html;
        
        // Event listener untuk checkbox hasil pencarian
        document.querySelectorAll('.mahasiswa-checkbox').forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                const nim = this.value;
                
                if (this.checked) {
                    selectedMahasiswa.add(nim);
                } else {
                    selectedMahasiswa.delete(nim);
                }
                
                updateSelectedMembers();
            });
        });
    }
    
    // Event listener untuk input pencarian
    searchInput.addEventListener('input', function() {
        const searchTerm = this.value.trim();
        
        if (searchTerm.length >= 3) {
            searchInfo.textContent = `Menampilkan hasil pencarian untuk: "${searchTerm}"`;
        } else {
            searchInfo.textContent = 'Ketik minimal 3 karakter untuk mencari mahasiswa';
        }
        
        showSearchResults(searchTerm);
    });
    
    // Event listener untuk tombol clear search
    clearSearchBtn.addEventListener('click', function() {
        searchInput.value = '';
        searchInfo.textContent = 'Ketik minimal 3 karakter untuk mencari mahasiswa';
        searchResults.innerHTML = `
            <div class="no-results">
                Ketik nama atau NIM mahasiswa pada form pencarian
            </div>
        `;
    });
    
    // Fungsi untuk menampilkan modal status
    function showStatusModal(success, message) {
        const statusModalHeader = document.getElementById('statusModalHeader');
        const statusModalBody = document.getElementById('statusModalBody');
        const statusModalBtn = document.getElementById('statusModalBtn');
        
        if (success) {
            statusModalHeader.className = 'modal-header success';
            statusModalHeader.innerHTML = '<h5 class="modal-title text-white"><i class="fas fa-check-circle me-2"></i>Berhasil</h5>';
            statusModalBody.innerHTML = `
                <div class="status-icon text-success">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="status-title">Grup Berhasil Dibuat!</div>
                <div class="status-message">${message}</div>
            `;
            statusModalBtn.className = 'btn btn-success btn-status';
            statusModalBtn.innerHTML = '<i class="fas fa-users me-2"></i>Lihat Grup';
            
            // Redirect saat tombol diklik
            statusModalBtn.onclick = function() {
                window.location.href = '{{ route("dosen.grup.index") }}';
            };
        } else {
            statusModalHeader.className = 'modal-header error';
            statusModalHeader.innerHTML = '<h5 class="modal-title text-white"><i class="fas fa-exclamation-circle me-2"></i>Gagal</h5>';
            statusModalBody.innerHTML = `
                <div class="status-icon text-danger">
                    <i class="fas fa-exclamation-circle"></i>
                </div>
                <div class="status-title">Pembuatan Grup Gagal</div>
                <div class="status-message">${message}</div>
            `;
            statusModalBtn.className = 'btn btn-error btn-status';
            statusModalBtn.innerHTML = '<i class="fas fa-redo me-2"></i>Coba Lagi';
            
            // Tutup modal saat tombol diklik
            statusModalBtn.onclick = function() {
                statusModal.hide();
            };
        }
        
        loadingModal.hide();
        statusModal.show();
    }
    
    // Form Submit Handler
    formGrup.addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Validasi form
        const namaGrup = namaGrupInput.value.trim();
        
        if (!namaGrup) {
            showStatusModal(false, 'Nama grup harus diisi');
            return;
        }
        
        if (selectedMahasiswa.size === 0) {
            showStatusModal(false, 'Pilih minimal satu anggota untuk grup');
            return;
        }
        
        // Disable tombol submit untuk mencegah multiple submit
        buatGrupBtn.disabled = true;
        
        // Tampilkan modal loading
        loadingModal.show();
        
        // Simulasi delay untuk efek loading yang lebih smooth
        setTimeout(() => {
            // Submit form secara normal
            this.submit();
        }, 1200);
    });
    
    // Inisialisasi tampilan anggota terpilih
    updateSelectedMembers();
});
</script>
@endpush