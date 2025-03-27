@extends('layouts.app')

@section('title', 'Terima Usulan')

@push('styles')
<style>
    .status {
        background-color: #FFBE01;
        color: white;
        border-radius: 5px;
        font-size: 0.9em;
        display: inline-block;
        margin-top: 10px;
        padding: 5px 15px;
    }

    /* Modal styles */
    .modal {
        display: none;
        position: fixed;
        z-index: 1000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        overflow: auto;
        background-color: rgba(0, 0, 0, 0.4);
    }

    .modal-content {
        background-color: #fefefe;
        margin: 15% auto;
        padding: 20px;
        border: 1px solid #888;
        width: 80%;
        max-width: 400px;
        border-radius: 10px;
        text-align: center;
    }

    .modal-content h2 {
        margin-top: 0;
        font-size: 1.5rem;
    }

    .question-mark {
        width: 60px;
        height: 60px;
        background-color: #f0f0f0;
        border-radius: 50%;
        display: flex;
        justify-content: center;
        align-items: center;
        margin: 0 auto 20px;
    }

    .question-mark span {
        color: #17a2b8;
        font-size: 40px;
        font-weight: bold;
    }

    .modal-buttons {
        display: flex;
        justify-content: center;
        gap: 10px;
        margin-top: 20px;
    }

    .modal-buttons button {
        padding: 10px 20px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
    }

    .btn-terima {
        background-color: #28a745;
        color: white;
    }

    .btn-tolak {
        background-color: #dc3545;
        color: white;
    }

    .btn-batal {
        background-color: #6c757d;
        color: white;
    }

    #alasanPenolakan {
        width: 100%;
        margin-top: 10px;
        padding: 10px;
        border: 1px solid #ced4da;
        border-radius: 5px;
    }
</style>
@endpush

@section('content')
<div class="container mt-5">
    <h1 class="mb-2 gradient-text fw-bold">Detail Mahasiswa</h1>
    <hr>
    <button class="btn btn-gradient mb-4 mt-2 d-flex align-items-center justify-content-center">
        <a href="{{ url('/persetujuan') }}">
            <i class="fas fa-arrow-left me-2"></i> Kembali
        </a>
    </button>

    <div class="container">
        <div class="row">
            <!-- Mahasiswa Info Card -->
            <div class="col-lg-6 col-md-12 bg-white rounded-start px-4 py-3 mb-2 shadow-sm">
                <h5 class="text-bold">Mahasiswa</h5>
                <hr>
                <p class="card-title text-muted text-sm">Nama</p>
                <p class="card-text text-start">Syahirah Tri Meilina</p>
                <p class="card-title text-muted text-sm">NIM</p>
                <p class="card-text text-start">2107110255</p>
                <p class="card-title text-muted text-sm">Program Studi</p>
                <p class="card-text text-start">Teknik Informatika S1</p>
                <p class="card-title text-muted text-sm">Konsentrasi</p>
                <p class="card-text text-start">Rekayasa Perangkat Lunak</p>
            </div>

            <!-- Dosen Pembimbing Card -->
            <div class="col-lg-6 col-md-12 bg-white rounded-end px-4 py-3 mb-2 shadow-sm">
                <h5 class="text-bold">Dosen Pembimbing</h5>
                <hr>
                <p class="card-title text-secondary text-sm">Nama Pembimbing</p>
                <p class="card-text text-start">Edi Susilo, S.Pd., M.Kom., M.Eng.</p>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="row">
            <!-- Usulan Jadwal Card -->
            <div class="col-lg-6 col-md-12 px-4 py-3 mb-2 bg-white rounded-start shadow-sm">
                <h5 class="text-bold">Data Usulan Jadwal Bimbingan</h5>
                <hr>
                <p class="card-title text-muted text-sm">Jenis Bimbingan</p>
                <p class="card-text text-start">Bimbingan Skripsi</p>
                <p class="card-title text-muted text-sm">Tanggal</p>
                <p class="card-text text-start">Senin, 30 September 2024</p>
                <p class="card-title text-muted text-sm">Waktu</p>
                <p class="card-text text-start">13.30 - 16.00</p>
                <p class="card-title text-muted text-sm">Deskripsi</p>
                <p class="card-text text-start">Izin bapak, saya ingin melakukan bimbingan bab 1 skripsi saya pak</p>
            </div>

            <!-- Keterangan Usulan Card -->
            <div class="col-lg-6 col-md-12 px-4 py-3 mb-2 bg-white rounded-end shadow-sm">
                <h5 class="text-bold">Keterangan Usulan</h5>
                <hr>
                <p class="card-title text-secondary text-sm">Status Usulan</p>
                <p class="card-text text-start">
                    <span id="status" class="status">BELUM DIPROSES</span>
                </p>
                <p class="card-title text-secondary text-sm">Keterangan</p>
                <p id="keteranganText" class="card-text text-start">-</p>
            </div>
        </div>
    </div>

    <div class="container mt-4">
        <div class="row">
            <div class="col-md-12 mb-2">
                <button id="btnTerima" class="btn btn-success w-100">Terima</button>
            </div>
            <div class="col-md-12 mb-2">
                <button id="btnTolak" class="btn btn-danger w-100">Tolak</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Terima -->
@include('components.dosen.modalterima')

<!-- Modal Tolak -->
@include('components.dosen.modaltolak')

<!-- Modal Alasan Penolakan -->
@include('components.dosen.modalalasan')
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Get modal elements
        var modalTerima = document.getElementById("modalTerima");
        var modalTolak = document.getElementById("modalTolak");
        var modalAlasanPenolakan = document.getElementById("modalAlasanPenolakan");

        // Get buttons
        var btnTerima = document.getElementById("btnTerima");
        var btnTolak = document.getElementById("btnTolak");
        var confirmTerima = document.getElementById("confirmTerima");
        var confirmTolak = document.getElementById("confirmTolak");
        var submitPenolakan = document.getElementById("submitPenolakan");

        // Get close buttons
        var closeButtons = document.getElementsByClassName("close-modal");

        // Get status and keterangan elements
        var statusBadge = document.getElementById("status");
        var keteranganText = document.getElementById("keteranganText");

        // Open Terima modal
        btnTerima.onclick = function() {
            modalTerima.style.display = "block";
        }

        // Open Tolak modal
        btnTolak.onclick = function() {
            modalTolak.style.display = "block";
        }

        // Confirm Terima
        confirmTerima.onclick = function() {
            statusBadge.innerHTML = "USULAN DISETUJUI";
            statusBadge.style.backgroundColor = "#28a745";
            keteranganText.innerHTML = "Usulan Jadwal Bimbingan Disetujui";
            modalTerima.style.display = "none";
            disableButtons();
        }

        // Confirm Tolak
        confirmTolak.onclick = function() {
            modalTolak.style.display = "none";
            modalAlasanPenolakan.style.display = "block";
        }

        // Submit Penolakan
        submitPenolakan.onclick = function() {
            var alasan = document.getElementById("alasanPenolakan").value;
            if (alasan.trim() !== "") {
                statusBadge.innerHTML = "USULAN DITOLAK";
                statusBadge.style.backgroundColor = "#dc3545";
                keteranganText.innerHTML = alasan;
                modalAlasanPenolakan.style.display = "none";
                disableButtons();
            } else {
                alert("Harap isi alasan penolakan.");
            }
        }

        // Close modals
        for (var i = 0; i < closeButtons.length; i++) {
            closeButtons[i].onclick = function() {
                modalTerima.style.display = "none";
                modalTolak.style.display = "none";
                modalAlasanPenolakan.style.display = "none";
            }
        }

        // Close modal when clicking outside
        window.onclick = function(event) {
            if (event.target == modalTerima || event.target == modalTolak || event.target == modalAlasanPenolakan) {
                modalTerima.style.display = "none";
                modalTolak.style.display = "none";
                modalAlasanPenolakan.style.display = "none";
            }
        }

        // Disable buttons after action
        function disableButtons() {
            btnTerima.disabled = true;
            btnTolak.disabled = true;
        }
    });
</script>
@endpush