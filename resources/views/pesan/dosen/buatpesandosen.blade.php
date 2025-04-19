@extends('layouts.app')

@section('title', 'Buat Pesan Dosen')
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
        
        .tab-penerima {
            border-radius: 5px;
            padding: 8px 15px;
            background: transparent;
            border: 1px solid #ddd;
            transition: all 0.3s ease;
            cursor: pointer;
            font-weight: 500;
            text-align: center;
        }
        
        .tab-penerima.active {
            background: linear-gradient(to right, #9fa4a9, #838f87);
            color: white;
            border: none;
        }
        
        .form-header {
            padding-bottom: 15px;
            margin-bottom: 25px;
            border-bottom: 1px solid #dee2e6;
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
        
        #daftarAngkatan, #daftarMahasiswa {
            display: none;
            margin-top: 15px;
            position: relative;
        }
        
        .dropdown-priority {
            max-height: 200px;
            overflow-y: auto;
        }
        
        .penerima-tag {
            display: inline-block;
            background-color: #e9f2ff;
            border: 1px solid #c2dcff;
            padding: 4px 10px;
            border-radius: 20px;
            margin-right: 5px;
            margin-bottom: 5px;
            font-size: 12px;
        }
        
        .penerima-tag .close {
            margin-left: 5px;
            cursor: pointer;
            opacity: 0.7;
        }
        
        .penerima-tag .close:hover {
            opacity: 1;
        }
        
        #selectedPenerima {
            margin-top: 15px;
            padding: 10px;
            border: 1px solid #dee2e6;
            border-radius: 5px;
            min-height: 50px;
            background-color: #f8f9fa;
        }
        
        .checkbox-angkatan {
            margin-bottom: 5px;
        }
        
        .checkbox-angkatan .form-check-input:checked {
            background-color: #1a73e8;
            border-color: #1a73e8;
        }

        #hasilPencarianMahasiswa {
            display: none;
            position: absolute;
            width: 100%;
            max-height: 200px;
            overflow-y: auto;
            z-index: 1000;
            background: white;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
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

        .font-size-10 {
            font-size: 10px !important;
        }
    </style>
@endpush

@section('content')
<div class="main-content">
    <div class="container">
        <h4 class="form-header">Buat Pesan Baru</h4>
        
        <a href="{{ route('back') }}" class="btn-kembali">
            <i class="fas fa-arrow-left me-2"></i> Kembali
        </a>
        
        <form id="formPesan">
            <div class="mb-4">
                <label for="subjek" class="form-label required-field fs-6">Subjek</label>
                <div class="custom-select">
                    <select class="form-select text-muted small" id="subjek" required>
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
                <label class="form-label required-field fs-6">Penerima</label>
                <div class="d-flex gap-3">
                    <div class="tab-penerima active" id="tabIndividual" onclick="switchTab('individual')">
                        Mahasiswa Individual
                    </div>
                    <div class="tab-penerima" id="tabAngkatan" onclick="switchTab('angkatan')">
                        Berdasarkan Angkatan
                    </div>
                </div>
                
                <!-- Form Mahasiswa Individual -->
                <div id="daftarMahasiswa" class="mt-3">
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" id="searchMahasiswa" placeholder="Cari mahasiswa..." oninput="searchMahasiswa(this.value)">
                        <button class="btn btn-outline-secondary" type="button" id="buttonSearchMahasiswa">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                    
                    <div class="list-group" id="hasilPencarianMahasiswa">
                        <!-- Hasil pencarian akan ditampilkan di sini -->
                    </div>
                </div>
                
                <!-- Form Angkatan -->
                <div id="daftarAngkatan" class="mt-3">
                    <div class="mb-3">
                        <div class="form-check checkbox-angkatan">
                            <input class="form-check-input" type="checkbox" id="angkatanAll" onclick="pilihSemuaAngkatan()">
                            <label class="form-check-label" for="angkatanAll">
                                <strong>Semua Angkatan</strong>
                            </label>
                        </div>
                        <hr>
                        <div class="form-check checkbox-angkatan">
                            <input class="form-check-input angkatan-checkbox" type="checkbox" id="angkatan2019" onclick="updateAngkatanSelection('2019', 'Angkatan 2019')">
                            <label class="form-check-label" for="angkatan2019">
                                Angkatan 2019
                            </label>
                        </div>
                        <div class="form-check checkbox-angkatan">
                            <input class="form-check-input angkatan-checkbox" type="checkbox" id="angkatan2020" onclick="updateAngkatanSelection('2020', 'Angkatan 2020')">
                            <label class="form-check-label" for="angkatan2020">
                                Angkatan 2020
                            </label>
                        </div>
                        <div class="form-check checkbox-angkatan">
                            <input class="form-check-input angkatan-checkbox" type="checkbox" id="angkatan2021" onclick="updateAngkatanSelection('2021', 'Angkatan 2021')">
                            <label class="form-check-label" for="angkatan2021">
                                Angkatan 2021
                            </label>
                        </div>
                        <div class="form-check checkbox-angkatan">
                            <input class="form-check-input angkatan-checkbox" type="checkbox" id="angkatan2022" onclick="updateAngkatanSelection('2022', 'Angkatan 2022')">
                            <label class="form-check-label" for="angkatan2022">
                                Angkatan 2022
                            </label>
                        </div>
                        <div class="form-check checkbox-angkatan">
                            <input class="form-check-input angkatan-checkbox" type="checkbox" id="angkatan2023" onclick="updateAngkatanSelection('2023', 'Angkatan 2023')">
                            <label class="form-check-label" for="angkatan2023">
                                Angkatan 2023
                            </label>
                        </div>
                    </div>
                </div>
                
                <!-- Tampilkan penerima yang sudah dipilih -->
                <div id="selectedPenerima" class="mt-3">
                    <!-- Penerima yang dipilih akan ditampilkan di sini -->
                </div>
            </div>
            
            <div class="mb-4">
                <label for="prioritas" class="form-label required-field fs-6">Prioritas</label>
                <div class="custom-select">
                    <select class="form-select text-muted small" id="prioritas" required>
                        <option value="" disabled selected>Masukkan prioritas</option>
                        <option value="Penting">Penting</option>
                        <option value="Umum">Umum</option>
                    </select>
                </div>
            </div>
            
            <div class="mb-4">
                <label for="lampiran" class="form-label fs-6">Lampiran (Link Google Drive)</label>
                <input type="url" class="form-control" id="lampiran" placeholder="Masukkan link Google Drive">
            </div>
            
            <div class="mb-4">
                <label for="pesanText" class="form-label required-field fs-6">Pesan</label>
                <textarea class="form-control" id="pesanText" rows="8" placeholder="Tulis pesan Anda di sini..." required></textarea>
            </div>
            
            <div class="d-flex justify-content-end">
                <button type="submit" class="btn btn-gradient px-4 py-2">
                    Kirim
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Menyimpan daftar penerima
    let daftarPenerima = {};

    // Data mahasiswa untuk simulasi pencarian
    const dataMahasiswa = [
        {id: 'M001', nama: 'Desi Maya Sari', nim: '2055301140'},
        {id: 'M002', nama: 'Ahmad Fauzi', nim: '2055301141'},
        {id: 'M003', nama: 'Rina Wijaya', nim: '2055301142'},
        {id: 'M004', nama: 'Budi Santoso', nim: '2055301143'},
        {id: 'M005', nama: 'Putri Amelia', nim: '2055301144'},
        {id: 'M006', nama: 'Sarah Annisa', nim: '2055301145'},
        {id: 'M007', nama: 'Muhammad Rizki', nim: '2055301146'},
        {id: 'M008', nama: 'Indah Permata', nim: '2055301147'},
        {id: 'M009', nama: 'Farhan Malik', nim: '2055301148'},
        {id: 'M010', nama: 'Nova Diana', nim: '2055301149'}
    ];

    // Fungsi untuk beralih antar tab
    function switchTab(tabType) {
        // Reset tampilan tab
        document.getElementById('tabIndividual').classList.remove('active');
        document.getElementById('tabAngkatan').classList.remove('active');
        document.getElementById('daftarMahasiswa').style.display = 'none';
        document.getElementById('daftarAngkatan').style.display = 'none';
        
        // Hapus semua penerima yang sudah dipilih sebelumnya
        daftarPenerima = {};
        renderPenerima();
        
        // Reset checkbox angkatan
        if (tabType === 'individual') {
            document.getElementById('tabIndividual').classList.add('active');
            document.getElementById('daftarMahasiswa').style.display = 'block';
            // Reset input pencarian
            document.getElementById('searchMahasiswa').value = '';
        } else if (tabType === 'angkatan') {
            document.getElementById('tabAngkatan').classList.add('active');
            document.getElementById('daftarAngkatan').style.display = 'block';
            // Reset semua checkbox angkatan
            document.getElementById('angkatanAll').checked = false;
            document.querySelectorAll('.angkatan-checkbox').forEach(cb => cb.checked = false);
        }
    }

    // Fungsi untuk memilih semua angkatan
    function pilihSemuaAngkatan() {
        const isChecked = document.getElementById('angkatanAll').checked;
        const angkatanCheckboxes = document.querySelectorAll('.angkatan-checkbox');
        
        angkatanCheckboxes.forEach(checkbox => {
            checkbox.checked = isChecked;
            
            if (isChecked) {
                const id = checkbox.id.replace('angkatan', '');
                const nama = checkbox.nextElementSibling.textContent.trim();
                updateAngkatanSelection(id, nama);
            } else {
                const id = checkbox.id.replace('angkatan', '');
                hapusPenerima('A' + id);
            }
        });
    }

    // Fungsi untuk memperbarui pilihan angkatan
    function updateAngkatanSelection(angkatanId, angkatanNama) {
        const checkbox = document.getElementById('angkatan' + angkatanId);
        
        if (checkbox.checked) {
            tambahPenerima('A' + angkatanId, angkatanNama, 'angkatan');
        } else {
            hapusPenerima('A' + angkatanId);
            document.getElementById('angkatanAll').checked = false;
        }
    }

    // Fungsi untuk menambah penerima
    function tambahPenerima(id, nama, tipe = 'mahasiswa') {
        if (!daftarPenerima[id]) {
            daftarPenerima[id] = {id, nama, tipe};
            renderPenerima();
        }
    }

    // Fungsi untuk menghapus penerima
    function hapusPenerima(id) {
        if (daftarPenerima[id]) {
            delete daftarPenerima[id];
            renderPenerima();
            
            if (id.startsWith('A')) {
                const angkatanId = id.substring(1);
                document.getElementById('angkatan' + angkatanId).checked = false;
            }
        }
    }

    // Fungsi untuk merender daftar penerima
    function renderPenerima() {
        const container = document.getElementById('selectedPenerima');
        container.innerHTML = '';
        
        if (Object.keys(daftarPenerima).length === 0) {
            container.innerHTML = '<p class="text-muted mb-0">Belum ada penerima yang dipilih</p>';
            return;
        }
        
        for (const id in daftarPenerima) {
            const penerima = daftarPenerima[id];
            const tag = document.createElement('div');
            tag.className = 'penerima-tag';
            tag.innerHTML = `${penerima.nama} <span class="close" onclick="hapusPenerima('${id}')">&times;</span>`;
            container.appendChild(tag);
        }
    }

    // Fungsi untuk pencarian mahasiswa
    function searchMahasiswa(keyword) {
        const hasilPencarianContainer = document.getElementById('hasilPencarianMahasiswa');
        
        if (keyword.length < 1) {
            hasilPencarianContainer.style.display = 'none';
            return;
        }

        keyword = keyword.toLowerCase();
        const hasilPencarian = dataMahasiswa.filter(mhs => {
            return mhs.nama.toLowerCase().includes(keyword) || 
                mhs.nim.includes(keyword);
        });
        
        hasilPencarianContainer.style.display = 'block';
        hasilPencarianContainer.innerHTML = '';
        
        if (hasilPencarian.length === 0) {
            hasilPencarianContainer.innerHTML = '<div class="list-group-item">Tidak ada hasil</div>';
        } else {
            hasilPencarian.forEach(mhs => {
                const item = document.createElement('a');
                item.href = '#';
                item.className = 'list-group-item list-group-item-action';
                item.textContent = `${mhs.nama} - ${mhs.nim}`;
                item.onclick = function(e) {
                    e.preventDefault();
                    tambahPenerima(mhs.id, mhs.nama);
                    document.getElementById('searchMahasiswa').value = '';
                    hasilPencarianContainer.style.display = 'none';
                };
                hasilPencarianContainer.appendChild(item);
            });
        }
    }

    // Event listener untuk tombol search
    document.getElementById('buttonSearchMahasiswa').addEventListener('click', function() {
        const keyword = document.getElementById('searchMahasiswa').value;
        if (keyword) {
            searchMahasiswa(keyword);
        }
    });

    // Event listener untuk form submission
    document.getElementById('formPesan').addEventListener('submit', function(e) {
        e.preventDefault();
        
        if (Object.keys(daftarPenerima).length === 0) {
            alert('Pilih minimal satu penerima');
            return false;
        }
        
        // Tampilkan data untuk demo
        console.log('Data pesan:', {
            subjek: document.getElementById('subjek').value,
            penerima: Object.values(daftarPenerima),
            prioritas: document.getElementById('prioritas').value,
            lampiran: document.getElementById('lampiran').value,
            pesan: document.getElementById('pesanText').value
        });
        
        alert('Pesan berhasil dikirim!');
        
        // Reset form
        this.reset();
        daftarPenerima = {};
        renderPenerima();
        document.querySelectorAll('.angkatan-checkbox').forEach(cb => cb.checked = false);
        document.getElementById('angkatanAll').checked = false;
        document.getElementById('hasilPencarianMahasiswa').style.display = 'none';
    });

    // Close hasil pencarian when clicking outside
    document.addEventListener('click', function(e) {
        const searchContainer = document.getElementById('daftarMahasiswa');
        const hasilPencarian = document.getElementById('hasilPencarianMahasiswa');
        
        if (!searchContainer.contains(e.target)) {
            hasilPencarian.style.display = 'none';
        }
    });

    // Inisialisasi tampilan
    document.addEventListener('DOMContentLoaded', function() {
        switchTab('individual');
        renderPenerima();
    });
</script>
@endpush