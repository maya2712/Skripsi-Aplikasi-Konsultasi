@extends('layouts.app')

@section('title', 'Persetujuan Bimbingan')

@push('styles')
<style>
    .action-icons {
        display: flex;
        justify-content: center;
        gap: 5px;
    }

    .action-icon {
        padding: 5px;
        border-radius: 4px;
        cursor: pointer;
        width: 28px;
        height: 28px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: opacity 0.2s;
        text-decoration: none;
    }

    .action-icon:hover {
        opacity: 0.8;
    }

    .info-icon {
        background-color: #17a2b8;
        color: white !important;
    }

    .approve-icon {
        background-color: #28a745;
        color: white !important;
    }

    .reject-icon {
        background-color: #dc3545;
        color: white !important;
    }

    .action-icon.edit-icon {
        background-color: #F3B806;
        color: #FFFFFF !important;
        padding: 6px;
        border-radius: 4px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }

    .action-icon.edit-icon:hover {
        background-color: #d69e05;
    }

    .modal-header {
        background: linear-gradient(to right, #4ade80, #3b82f6);
        color: white;
    }

    .modal-title {
        font-weight: bold;
    }
</style>
@endpush

@section('content')
<div class="container mt-5">
    <h1 class="mb-2 gradient-text fw-bold">Persetujuan Bimbingan</h1>
    <hr>
    <button class="btn btn-gradient mb-4 mt-2 d-flex align-items-center justify-content-center">
        <a href="{{ url('/masukkanjadwal') }}">
            <i class="bi bi-plus-lg me-2"></i> Masukkan Jadwal Bimbingan
        </a>
    </button>

    <div class="card shadow-lg border-0 rounded-4 overflow-hidden">
        <div class="card-header bg-white p-0">
            <ul class="nav nav-tabs" id="bimbinganTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active px-4 py-3" id="usulan-tab" data-bs-toggle="tab" data-bs-target="#usulan" type="button" role="tab" aria-controls="usulan" aria-selected="true">
                        Usulan Bimbingan
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link px-4 py-3" id="jadwal-tab" data-bs-toggle="tab" data-bs-target="#jadwal" type="button" role="tab" aria-controls="jadwal" aria-selected="false">
                        Daftar Jadwal
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link px-4 py-3" id="riwayat-tab" data-bs-toggle="tab" data-bs-target="#riwayat" type="button" role="tab" aria-controls="riwayat" aria-selected="false">
                        Riwayat
                    </button>
                </li>
            </ul>
        </div>

        <div class="card-body p-4">
            <div class="tab-content" id="bimbinganTabContent">
                <!-- Tab Usulan -->
                <div class="tab-pane fade show active" id="usulan" role="tabpanel" aria-labelledby="usulan-tab">
                    @include('components.dosen.usulan')
                </div>

                <!-- Tab Jadwal -->
                <div class="tab-pane fade" id="jadwal" role="tabpanel" aria-labelledby="jadwal-tab">
                    @include('components.dosen.jadwal')
                </div>

                <!-- Tab Riwayat -->
                <div class="tab-pane fade" id="riwayat" role="tabpanel" aria-labelledby="riwayat-tab">
                    @include('components.dosen.riwayat')
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Terima -->
@include('components.dosen.modalterima')

<!-- Modal Tolak -->
@include('components.dosen.modaltolak')
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    let currentRow = null;

    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function(tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    function getRowData(row) {
        return {
            nim: row.querySelector('td:nth-child(2)').textContent,
            nama: row.querySelector('td:nth-child(3)').textContent,
            jenisBimbingan: row.querySelector('td:nth-child(4)').textContent
        };
    }

    function updateModal(modal, rowData) {
        modal.querySelector('.nim-display').textContent = rowData.nim;
        modal.querySelector('.nama-display').textContent = rowData.nama;
        modal.querySelector('.jenis-display').textContent = rowData.jenisBimbingan;
    }

    function cleanupModal() {
        const modals = document.querySelectorAll('.modal');
        modals.forEach(modal => {
            const modalInstance = bootstrap.Modal.getInstance(modal);
            if (modalInstance) {
                modalInstance.dispose();
            }
        });

        const backdrops = document.querySelectorAll('.modal-backdrop');
        backdrops.forEach(backdrop => backdrop.remove());

        document.body.classList.remove('modal-open');
        document.body.style.removeProperty('padding-right');
        document.body.style.overflow = '';
    }

    document.querySelectorAll('.approve-icon, .reject-icon').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            currentRow = this.closest('tr');
            const rowData = getRowData(currentRow);
            const modalId = this.classList.contains('approve-icon') ? 'modalTerima' : 'modalTolak';
            const modal = document.getElementById(modalId);
            updateModal(modal, rowData);

            const bsModal = new bootstrap.Modal(modal);
            bsModal.show();
        });
    });

    function updateRowStatus(row, status) {
        const statusCell = row.querySelector('td:nth-last-child(2)');
        const actionIcons = row.querySelector('.action-icons');

        statusCell.textContent = status;

        if (status === 'DISETUJUI' || status === 'DITOLAK') {
            const approveIcon = actionIcons.querySelector('.approve-icon');
            const rejectIcon = actionIcons.querySelector('.reject-icon');
            if (approveIcon) approveIcon.remove();
            if (rejectIcon) rejectIcon.remove();

            if (status === 'DISETUJUI' && !actionIcons.querySelector('.edit-icon')) {
                const editIcon = document.createElement('a');
                editIcon.href = '/editusulan';
                editIcon.className = 'action-icon edit-icon';
                editIcon.setAttribute('data-bs-toggle', 'tooltip');
                editIcon.setAttribute('title', 'Edit Usulan');
                editIcon.innerHTML = '<i class="fas fa-pencil-alt"></i>';
                actionIcons.appendChild(editIcon);

                new bootstrap.Tooltip(editIcon);
            }
        }
    }

    function showNotification(message, type) {
        const notification = document.createElement('div');
        notification.className = `alert alert-${type} alert-dismissible fade show position-fixed top-0 end-0 m-3`;
        notification.style.zIndex = '1050';
        notification.innerHTML = `${message} <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>`;
        document.body.appendChild(notification);

        setTimeout(() => {
            notification.remove();
        }, 3000);
    }

    document.getElementById('confirmTerima').addEventListener('click', function() {
        const lokasiInput = document.getElementById('lokasiBimbingan');

        if (!lokasiInput.value.trim()) {
            lokasiInput.classList.add('is-invalid');
            return;
        }

        if (currentRow) {
            updateRowStatus(currentRow, 'DISETUJUI');
            const lokasiCell = currentRow.querySelector('td:nth-child(7)');
            lokasiCell.textContent = lokasiInput.value.trim();

            const modal = document.getElementById('modalTerima');
            const bsModal = bootstrap.Modal.getInstance(modal);
            if (bsModal) {
                bsModal.hide();
                setTimeout(cleanupModal, 300);
            }

            showNotification('Usulan bimbingan telah disetujui', 'success');
        }

        lokasiInput.value = '';
        lokasiInput.classList.remove('is-invalid');
    });

    document.getElementById('modalTerima').addEventListener('hidden.bs.modal', function() {
        const lokasiInput = document.getElementById('lokasiBimbingan');
        lokasiInput.value = '';
        lokasiInput.classList.remove('is-invalid');
    });

    document.getElementById('confirmTolak').addEventListener('click', function() {
        const alasanInput = document.getElementById('alasanPenolakan');

        if (!alasanInput.value.trim()) {
            alasanInput.classList.add('is-invalid');
            return;
        }

        if (currentRow) {
            updateRowStatus(currentRow, 'DITOLAK');
            const modal = document.getElementById('modalTolak');
            const bsModal = bootstrap.Modal.getInstance(modal);
            if (bsModal) {
                bsModal.hide();
                setTimeout(cleanupModal, 300);
            }
        }

        alasanInput.value = '';
        alasanInput.classList.remove('is-invalid');
    });

    ['modalTerima', 'modalTolak'].forEach(modalId => {
        const modal = document.getElementById(modalId);
        modal.addEventListener('hidden.bs.modal', function() {
            setTimeout(cleanupModal, 300);
            if (modalId === 'modalTolak') {
                const alasanInput = document.getElementById('alasanPenolakan');
                alasanInput.value = '';
                alasanInput.classList.remove('is-invalid');
            }
        });
    });
});
</script>
@endpush