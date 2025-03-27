<!-- resources/views/bimbingan/mahasiswa/usulanbimbingan.blade.php -->
@extends('layouts.app')

@section('title', 'Dashboard Bimbingan')

@push('styles')
<style>
    .gradient-text {
        background: linear-gradient(to right, #059669, #2563eb);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }
    
    .btn-gradient {
        background: linear-gradient(to right, #4ade80, #3b82f6);
        border: none;
        color: white;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        position: relative; 
        z-index: 1; 
        cursor: pointer; 
    }
    
    .btn-gradient a {
        color: white;
        text-decoration: none;
    }
    
    .btn-gradient:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }
    
    .btn-gradient:hover a {
        color: black;
    }
</style>
@endpush

@section('content')
<div class="container mt-5">
    <h1 class="mb-2 gradient-text fw-bold">Usulan Bimbingan</h1>
    <hr>
    <button class="btn btn-gradient mb-4 mt-2 d-flex align-items-center justify-content-center">
        <a href="/pilihjadwal">
            <i class="bi bi-plus-lg me-2"></i> Pilih Jadwal Bimbingan
        </a>
    </button>

    <div class="card shadow-lg border-0 rounded-4 overflow-hidden">
        <div class="card-header bg-white p-0">
            <ul class="nav nav-tabs" id="bimbinganTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active px-4 py-3" id="usulan-tab" data-bs-toggle="tab" data-bs-target="#usulan" type="button" role="tab">
                        Usulan Bimbingan
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link px-4 py-3" id="jadwal-tab" data-bs-toggle="tab" data-bs-target="#jadwal" type="button" role="tab">
                        Daftar Jadwal
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link px-4 py-3" id="riwayat-tab" data-bs-toggle="tab" data-bs-target="#riwayat" type="button" role="tab">
                        Riwayat
                    </button>
                </li>
            </ul>
        </div>

        <div class="card-body p-4">
            <div class="row mb-3">
                <div class="col-md-6">
                    <div class="d-flex align-items-center">
                        <label class="me-2">Tampilkan</label>
                        <select class="form-select form-select-sm w-auto" id="show-entries">
                            <option value="10">10</option>
                            <option value="50">50</option>
                            <option value="100">100</option>
                            <option value="150">150</option>
                        </select>
                        <label class="ms-2">entries</label>
                    </div>
                </div>
            </div>

            <div class="tab-content" id="bimbinganTabContent">
                <div class="tab-pane fade show active" id="usulan" role="tabpanel">
                    <div id="usulan-content"></div>
                </div>
                <div class="tab-pane fade" id="jadwal" role="tabpanel">
                    <div id="jadwal-content"></div>
                </div>
                <div class="tab-pane fade" id="riwayat" role="tabpanel">
                    <div id="riwayat-content"></div>
                </div>
            </div>

            <div class="d-flex flex-column flex-sm-row justify-content-between align-items-center mt-3">
                <p class="mb-2" id="entries-info"></p>
                <nav aria-label="Page navigation" id="pagination-container"></nav>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    let currentTab = 'usulan';
    let currentPage = 1;
    let entriesPerPage = 10;
    
    function activateTabFromHash() {
        const hash = window.location.hash.replace('#', '');
        if (['usulan', 'jadwal', 'riwayat'].includes(hash)) {
            currentTab = hash;
            const tabButton = document.querySelector(`[data-bs-target="#${hash}"]`);
            if (tabButton) {
                document.querySelectorAll('#bimbinganTab button').forEach(tab => {
                    tab.classList.remove('active');
                    tab.setAttribute('aria-selected', 'false');
                });
                
                tabButton.classList.add('active');
                tabButton.setAttribute('aria-selected', 'true');
                
                document.querySelectorAll('.tab-pane').forEach(pane => {
                    pane.classList.remove('show', 'active');
                });
                
                const pane = document.querySelector(`#${hash}`);
                if (pane) {
                    pane.classList.add('show', 'active');
                }
                
                loadTabContent(hash, currentPage, entriesPerPage);
            }
        }
    }

    document.getElementById('show-entries').addEventListener('change', function() {
        entriesPerPage = parseInt(this.value);
        currentPage = 1;
        loadTabContent(currentTab, currentPage, entriesPerPage);
    });

    const triggerTabList = document.querySelectorAll('#bimbinganTab button');
    triggerTabList.forEach(triggerEl => {
        triggerEl.addEventListener('click', function(event) {
            event.preventDefault();
            currentTab = this.getAttribute('data-bs-target').replace('#', '');
            const currentScrollPos = window.scrollY;
            window.location.hash = currentTab;
            window.scrollTo(0, currentScrollPos);
            
            currentPage = 1;
            loadTabContent(currentTab, currentPage, entriesPerPage);
        });
    });

    function loadTabContent(tabName, page, perPage) {
        const contentDiv = document.getElementById(`${tabName}-content`);
        
        if (contentDiv) {
            let url = '';
            switch(tabName) {
                case 'usulan':
                    url = `/load-usulan?page=${page}&per_page=${perPage}`;
                    break;
                case 'jadwal':
                    url = `/load-jadwal?page=${page}&per_page=${perPage}`;
                    break;
                case 'riwayat':
                    url = `/load-riwayat?page=${page}&per_page=${perPage}`;
                    break;
            }
            
            fetch(url)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.error) {
                        throw new Error(data.error);
                    }
                    contentDiv.innerHTML = data.html;
                    updatePagination(data.pagination);
                    updateEntriesInfo(data.pagination);
                })
                .catch(error => {
                    contentDiv.innerHTML = `
                        <div class="alert alert-danger">
                            Gagal memuat data. ${error.message}
                        </div>`;
                    console.error('Error:', error);
                });
        }
    }

    function updatePagination(pagination) {
        const container = document.getElementById('pagination-container');
        if (!container) return;

        let html = '<ul class="pagination mb-0">';
        
        html += `
            <li class="page-item ${pagination.current_page === 1 ? 'disabled' : ''}">
                <a class="page-link" href="#" data-page="${pagination.current_page - 1}">Sebelumnya</a>
            </li>`;

        for (let i = 1; i <= pagination.last_page; i++) {
            if (
                i === 1 ||
                i === pagination.last_page ||
                (i >= pagination.current_page - 2 && i <= pagination.current_page + 2)
            ) {
                html += `
                    <li class="page-item ${pagination.current_page === i ? 'active' : ''}">
                        <a class="page-link" href="#" data-page="${i}">${i}</a>
                    </li>`;
            } else if (
                i === pagination.current_page - 3 ||
                i === pagination.current_page + 3
            ) {
                html += '<li class="page-item disabled"><a class="page-link">...</a></li>';
            }
        }

        html += `
            <li class="page-item ${pagination.current_page === pagination.last_page ? 'disabled' : ''}">
                <a class="page-link" href="#" data-page="${pagination.current_page + 1}">Selanjutnya</a>
            </li>`;

        html += '</ul>';
        container.innerHTML = html;

        container.querySelectorAll('.page-link').forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                const page = parseInt(this.getAttribute('data-page'));
                if (page && page !== currentPage) {
                    currentPage = page;
                    loadTabContent(currentTab, currentPage, entriesPerPage);
                }
            });
        });
    }

    function updateEntriesInfo(pagination) {
        const info = document.getElementById('entries-info');
        if (info) {
            const start = (pagination.current_page - 1) * pagination.per_page + 1;
            const end = Math.min(start + pagination.per_page - 1, pagination.total);
            info.textContent = `Menampilkan ${start} sampai ${end} dari ${pagination.total} entri`;
        }
    }

    if (window.location.hash) {
        activateTabFromHash();
    } else {
        loadTabContent('usulan', currentPage, entriesPerPage);
    }

    window.addEventListener('hashchange', activateTabFromHash);
});
</script>
@endpush