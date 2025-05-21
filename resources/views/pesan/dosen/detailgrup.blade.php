@extends('layouts.app')

@section('title', 'Detail Grup - ' . $grup->nama_grup)

@push('styles')
<style>
    :root {
        --bs-primary: #1a73e8;
        --bs-danger: #FF5252;
        --bs-success: #27AE60;
        --primary-gradient: linear-gradient(to right, #004AAD, #5DE0E6);
        --primary-hover: linear-gradient(to right, #003c8a, #4bc4c9);
        --text-color: #546E7A; /* Warna teks menu utama */
    }
    
    body {
        background-color: #F5F7FA;
        font-size: 13px;
    }

    .main-content {
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
        width: 100%;
        text-align: left;
    }
    
    .sidebar-menu .nav-link.active {
        background: #E3F2FD;
        color: var(--bs-primary);
    }
    
    .sidebar-menu .nav-link:hover:not(.active) {
        background: #f8f9fa;
    }

    .komunikasi-submenu .nav-link.active,
    .pengaturan-submenu .nav-link.active {
        background: #E3F2FD;
        color: var(--bs-primary);
    }

    .komunikasi-submenu .nav-link:hover:not(.active),
    .pengaturan-submenu .nav-link:hover:not(.active) {
        background: #f8f9fa;
    }

    .komunikasi-submenu,
    .pengaturan-submenu {
        margin-left: 15px;
        width: 100%;
    }

    .komunikasi-submenu .nav-link,
    .pengaturan-submenu .nav-link {
        padding: 8px 15px;
        font-size: 13px;
        width: 100%;
        color: #546E7A; /* Warna teks menu yang konsisten */
    }
    
    .profile-image-placeholder {
        width: 45px;
        height: 45px;
        border-radius: 50%;
        background-color: #e9ecef;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #6c757d;
        font-size: 20px;
        border: 2px solid #f8f9fa;
    }
    
    .btn-gradient-primary {
        background: var(--primary-gradient);
        border: none;
        color: white;
        transition: all 0.3s ease;
    }

    .btn-gradient-primary:hover {
        background: var(--primary-hover);
        color: white;
        box-shadow: 0 2px 5px rgba(0,0,0,0.2);
    }
    
    .group-header {
        background: var(--primary-gradient);
        color: white;
        border-radius: 10px;
        padding: 20px;
        margin-bottom: 20px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }
    
    .message-container {
        max-height: calc(100vh - 350px);
        overflow-y: auto;
        margin-bottom: 20px;
        background-color: white;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        padding: 15px 20px;
    }
    
    .message-input {
        position: sticky;
        bottom: 0;
        background: white;
        padding: 15px;
        border-radius: 10px;
        box-shadow: 0 -2px 10px rgba(0,0,0,0.05);
        margin-top: 15px;
    }

    .header-icon {
        color: white;
        font-size: 1.2rem;
        margin-left: 15px;
        cursor: pointer;
        opacity: 0.9;
        transition: all 0.3s ease;
        padding: 8px;
        border-radius: 50%;
    }

    .header-icon:hover {
        opacity: 1;
        background-color: rgba(255, 255, 255, 0.2);
        transform: scale(1.1);
    }

    .header-icon.danger:hover {
        color: #FF5252;
        background-color: rgba(255, 82, 82, 0.2);
    }
    
    /* Container untuk memberi jarak dari tepi - menggunakan custom-container dari dashboard pesan */
    .custom-container {
        max-width: 1400px;
        margin: 0 auto;
        padding: 0 15px;
    }
    
    .badge-notification {
        background: var(--bs-danger);
    }
    
    .message-container::-webkit-scrollbar {
        width: 6px;
    }

    .message-container::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 10px;
    }

    .message-container::-webkit-scrollbar-thumb {
        background: #888;
        border-radius: 10px;
    }

    .message-container::-webkit-scrollbar-thumb:hover {
        background: #555;
    }
    
    /* Style baru untuk chat bubble yang disesuaikan dengan halaman isi pesan */
    .message-time {
        color: rgba(255, 255, 255, 0.7);
        font-size: 12px;
        text-align: right;
        margin-top: 8px;
    }
    
    .chat-message {
        margin-bottom: 25px;
        position: relative;
        max-width: 85%;
        clear: both;
    }
    
    .chat-message:last-child {
        margin-bottom: 10px;
    }
    
    .message-bubble {
        padding: 15px 20px;
        border-radius: 15px;
        position: relative;
        word-wrap: break-word;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
        width: fit-content;
        max-width: 85%;
    }
    
    .chat-message.dosen {
        margin-left: auto; /* Pesan dosen di kanan */
    }
    
    .chat-message.dosen .message-bubble {
        background-color: var(--bs-primary);
        color: white;
        border-radius: 15px 15px 3px 15px;
        margin-left: auto;
    }
    
    .chat-message.mahasiswa .message-bubble {
        background-color: #585f67;
        color: white;
        border-radius: 15px 15px 15px 3px;
    }
    
    .sender-name {
        color: #f8ac30;
        font-size: 14px;
        font-weight: 600;
        margin-bottom: 4px;
    }
    
    .chat-date-divider {
        text-align: center;
        margin: 20px 0;
        position: relative;
    }
    
    .chat-date-divider:before {
        content: '';
        position: absolute;
        left: 0;
        top: 50%;
        width: 100%;
        height: 1px;
        background-color: #e9ecef;
        z-index: 1;
    }
    
    .chat-date-divider span {
        background-color: white;
        padding: 0 15px;
        font-size: 12px;
        color: #6c757d;
        position: relative;
        z-index: 2;
    }
    
    /* Style untuk submenu mode peran */
    .pengaturan-submenu .btn-link {
        padding: 8px 15px;
        display: flex;
        align-items: center;
        text-decoration: none;
        border: none;
        background: none;
        font-size: 13px;
        width: 100%;
        text-align: left;
        margin: 0;
        border-radius: 0.5rem;
        color: #546E7A; /* Warna teks abu-abu yang konsisten */
    }
    
    .pengaturan-submenu .btn-link:hover {
        background-color: #f8f9fa;
        color: #546E7A; /* Warna teks saat hover - tetap konsisten */
    }
</style>
@endpush

@section('content')
<div class="main-content">
    <div class="custom-container">
        <div class="row g-4">
            <!-- Sidebar - Kolom Kiri -->
            <div class="col-md-3">
                <div class="sidebar">
                    <div class="sidebar-buttons">
                        <a href="{{ route('dosen.pesan.create') }}" class="btn" style="background: var(--primary-gradient); color: white; padding: 10px 20px; border: none; border-radius: 5px;">
                            <i class="fas fa-plus me-2"></i> Pesan Baru
                        </a>
                    </div>                                                    
                    
                    <!-- Sidebar Menu dengan Notifikasi Pesan Belum Dibaca -->
                    <div class="sidebar-menu">
                        <div class="nav flex-column">
                            <a href="{{ route('dosen.dashboard.pesan') }}" class="nav-link">
                                <i class="fas fa-home me-2"></i>Daftar Pesan
                            </a>
                            <a href="#" class="nav-link menu-item active" id="grupDropdownToggle">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span><i class="fas fa-users me-2"></i>Daftar Grup</span>
                                    <i class="fas fa-chevron-down" id="grupDropdownIcon"></i>
                                </div>
                            </a>
                            <div class="collapse show komunikasi-submenu" id="komunikasiSubmenu">
                                <a href="{{ route('dosen.grup.create') }}" class="nav-link menu-item d-flex align-items-center" style="color: #546E7A;">
                                    <i class="fas fa-plus me-2"></i>Grup Baru
                                </a>
                                
                                @php
                                    $activeRole = session('active_role', 'dosen');
                                    $grups = App\Models\Grup::where('dosen_id', Auth::user()->nip)
                                                            ->where('dosen_role', $activeRole)
                                                            ->get();
                                @endphp
                                
                                @if($grups && $grups->count() > 0)
                                    @foreach($grups as $grupItem)
                                    <a href="{{ route('dosen.grup.show', $grupItem->id) }}" class="nav-link menu-item d-flex justify-content-between align-items-center {{ $grupItem->id == $grup->id ? 'active' : '' }}">
                                        {{ $grupItem->nama_grup }}
                                        @if(isset($grupItem->unreadCount) && $grupItem->unreadCount > 0)
                                        <span class="badge bg-danger rounded-pill">{{ $grupItem->unreadCount }}</span>
                                        @endif
                                    </a>
                                    @endforeach
                                @else
                                    <div class="nav-link menu-item text-muted">
                                        <small>Belum ada grup</small>
                                    </div>
                                @endif
                            </div>
                            
                            <a href="{{ route('dosen.pesan.history') }}" class="nav-link menu-item">
                                <i class="fas fa-history me-2"></i>Riwayat Pesan
                            </a>
                            <a href="{{ url('/faqdosen') }}" class="nav-link menu-item">
                                <i class="fas fa-question-circle me-2"></i>FAQ
                            </a>
                            
                            <!-- Menu Pengaturan dengan Dropdown -->
                            @if(!empty(Auth::guard('dosen')->user()->jabatan_fungsional) && 
                                (stripos(Auth::guard('dosen')->user()->jabatan_fungsional, 'kaprodi') !== false || 
                                 stripos(Auth::guard('dosen')->user()->jabatan_fungsional, 'ketua') !== false))
                                <a href="#" class="nav-link menu-item" id="pengaturanDropdownToggle">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span><i class="fas fa-cog me-2"></i>Pengelola</span>
                                        <i class="fas fa-chevron-down" id="pengaturanDropdownIcon"></i>
                                    </div>
                                </a>
                                <div class="collapse pengaturan-submenu" id="pengaturanSubmenu">
                                    <form action="{{ route('dosen.switch-role') }}" method="POST" id="switchRoleForm" style="width: 100%;">
                                        @csrf
                                        <button type="submit" class="btn-link nav-link">
                                            <i class="fas {{ session('active_role') === 'kaprodi' ? 'fa-chalkboard-teacher' : 'fa-user-tie' }} me-2"></i>
                                            Mode {{ session('active_role') === 'kaprodi' ? 'Dosen' : 'Kaprodi' }}
                                        </button>
                                    </form>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Content Area - Kolom Kanan -->
            <div class="col-md-9">
                <!-- Group Header -->
                <div class="group-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="mb-0 fw-semibold">{{ $grup->nama_grup }}</h5>
                            <small>{{ $grup->mahasiswa->count() }} anggota</small>
                        </div>
                        <div class="d-flex">
                            <i class="fas fa-users header-icon" data-bs-toggle="modal" data-bs-target="#tambahAnggotaModal"></i>
                            <i class="fas fa-info-circle header-icon" data-bs-toggle="modal" data-bs-target="#infoGrupModal"></i>
                            <form action="{{ route('dosen.grup.destroy', $grup->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus grup ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="bg-transparent border-0 p-0">
                                    <i class="fas fa-trash header-icon danger"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                
                <!-- Chat Content -->
                <div class="message-container" id="messageContainer">
                    @if(isset($grupPesanByDate) && count($grupPesanByDate) > 0)
                        @foreach($grupPesanByDate as $date => $pesanList)
                            <div class="chat-date-divider">
                                <span data-date="{{ $date }}">{{ \Carbon\Carbon::parse($date)->locale('id')->isoFormat('dddd, D MMMM YYYY') }}</span>
                            </div>
                            
                           @foreach($pesanList as $pesan)
                                <div class="chat-message {{ $pesan->tipe_pengirim }}">
                                    <div class="message-bubble">
                                        @if($pesan->tipe_pengirim == 'mahasiswa')
                                            <div class="sender-name">{{ $pesan->pengirim->nama ?? 'Mahasiswa' }}</div>
                                        @endif
                                        <p>{{ $pesan->isi_pesan }}</p>
                                        <div class="message-time">
                                            {{ $pesan->created_at->format('H:i') }}
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @endforeach
                    @else
                        <div class="text-center py-5 text-muted">
                            <i class="fas fa-comments fa-3x mb-3"></i>
                            <p>Belum ada pesan di grup ini.</p>
                            <p>Mulai percakapan dengan mengirim pesan!</p>
                        </div>
                    @endif
                </div>
                
                <!-- Message Input -->
                <div class="message-input">
                    <form id="sendMessageForm" enctype="multipart/form-data">
                        @csrf
                        <input type="file" id="lampiran" name="lampiran" style="display: none;">
                        <div class="input-group">
                            <button type="button" class="btn btn-light" id="attachmentBtn">
                                <i class="fas fa-paperclip"></i>
                            </button>
                            <input type="text" class="form-control" id="messageInput" name="isi_pesan" placeholder="Tulis Pesan Anda disini..">
                            <button type="submit" class="btn btn-gradient-primary" id="sendBtn">
                                <i class="fas fa-paper-plane"></i>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Info Grup Modal -->
<div class="modal fade" id="infoGrupModal" tabindex="-1" aria-labelledby="infoGrupModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="infoGrupModalLabel">Informasi Grup</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="fw-bold">Nama Grup:</label>
                    <p>{{ $grup->nama_grup }}</p>
                </div>
                <div class="mb-3">
                    <label class="fw-bold">Dibuat pada:</label>
                    <p>{{ $grup->created_at->format('d M Y, H:i') }}</p>
                </div>
                <div class="mb-3">
                    <label class="fw-bold">Jumlah Anggota:</label>
                    <p>{{ $grup->mahasiswa->count() }} mahasiswa</p>
                </div>
                <div class="mb-3">
                    <label class="fw-bold">Dibuat oleh:</label>
                    <p>{{ Auth::user()->nama }}</p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<!-- Tambah Anggota Modal -->
<div class="modal fade" id="tambahAnggotaModal" tabindex="-1" aria-labelledby="tambahAnggotaModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="tambahAnggotaModalLabel">Anggota Grup</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <ul class="nav nav-tabs mb-3" id="anggotaTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="daftar-tab" data-bs-toggle="tab" data-bs-target="#daftar-tab-pane" type="button" role="tab" aria-controls="daftar-tab-pane" aria-selected="true">Daftar Anggota</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="tambah-tab" data-bs-toggle="tab" data-bs-target="#tambah-tab-pane" type="button" role="tab" aria-controls="tambah-tab-pane" aria-selected="false">Tambah Anggota</button>
                    </li>
                </ul>
                
                <div class="tab-content" id="anggotaTabContent">
                    <!-- Tab Daftar Anggota -->
                    <div class="tab-pane fade show active" id="daftar-tab-pane" role="tabpanel" aria-labelledby="daftar-tab" tabindex="0">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th width="5%">#</th>
                                        <th width="15%">Foto</th>
                                        <th width="40%">Nama</th>
                                        <th width="25%">NIM</th>
                                        <th width="15%">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($grup->mahasiswa as $index => $anggota)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>
                                            <div class="profile-image-placeholder">
                                                <i class="fas fa-user"></i>
                                            </div>
                                        </td>
                                        <td>{{ $anggota->nama }}</td>
                                        <td>{{ $anggota->nim }}</td>
                                        <td>
                                            <form action="{{ url('/grupanggota/hapus/'.$grup->id.'/'.$anggota->nim) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus anggota ini dari grup?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm">
                                                    <i class="fas fa-trash me-1"></i> Hapus
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    
                    <!-- Tab Tambah Anggota -->
                    <div class="tab-pane fade" id="tambah-tab-pane" role="tabpanel" aria-labelledby="tambah-tab" tabindex="0">
                        <form action="{{ route('dosen.grup.addMember', $grup->id) }}" method="POST" id="tambahAnggotaForm">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label">Cari Mahasiswa</label>
                                <input type="text" class="form-control" id="searchMahasiswa" placeholder="Cari berdasarkan nama atau NIM...">
                            </div>
                            <div class="mb-3">
                                <div class="card">
                                    <div class="card-body" style="max-height: 300px; overflow-y: auto;">
                                        @php
                                            $allMahasiswa = App\Models\Mahasiswa::whereNotIn('nim', $grup->mahasiswa->pluck('nim')->toArray())->get();
                                        @endphp
                                        
                                        @if($allMahasiswa->count() > 0)
                                            <div class="row">
                                                @foreach($allMahasiswa as $mhs)
                                                <div class="col-md-6 mb-2 searchable-item">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" name="new_members[]" value="{{ $mhs->nim }}" id="mhs{{ $mhs->nim }}">
                                                        <label class="form-check-label" for="mhs{{ $mhs->nim }}">
                                                            {{ $mhs->nama }} - {{ $mhs->nim }}
                                                        </label>
                                                    </div>
                                                </div>
                                                @endforeach
                                            </div>
                                        @else
                                            <div class="alert alert-info">
                                                Semua mahasiswa sudah ada di dalam grup ini.
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary">Tambahkan Anggota</button>
                            </div>
                        </form>
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
    // Kode dropdown grup yang sudah ada
    const grupDropdownToggle = document.getElementById('grupDropdownToggle');
    const komunikasiSubmenu = document.getElementById('komunikasiSubmenu');
    const grupDropdownIcon = document.getElementById('grupDropdownIcon');
    
    grupDropdownToggle.addEventListener('click', function() {
        // Toggle the collapse
        const bsCollapse = new bootstrap.Collapse(komunikasiSubmenu, {
            toggle: true
        });
        
        // Toggle the icon
        grupDropdownIcon.classList.toggle('fa-chevron-up');
        grupDropdownIcon.classList.toggle('fa-chevron-down');
    });
    
    // Initialize the pengaturan dropdown manually
    const pengaturanDropdownToggle = document.getElementById('pengaturanDropdownToggle');
    const pengaturanSubmenu = document.getElementById('pengaturanSubmenu');
    const pengaturanDropdownIcon = document.getElementById('pengaturanDropdownIcon');
    
    if (pengaturanDropdownToggle && pengaturanSubmenu && pengaturanDropdownIcon) {
        pengaturanDropdownToggle.addEventListener('click', function() {
            // Toggle the collapse
            const bsCollapse = new bootstrap.Collapse(pengaturanSubmenu, {
                toggle: true
            });
            
            // Toggle the icon
            pengaturanDropdownIcon.classList.toggle('fa-chevron-up');
            pengaturanDropdownIcon.classList.toggle('fa-chevron-down');
        });
    }
    
    // Kode pencarian anggota yang sudah ada
    const searchInput = document.getElementById('searchMahasiswa');
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            const items = document.querySelectorAll('.searchable-item');
            
            items.forEach(item => {
                const text = item.textContent.toLowerCase();
                if (text.includes(searchTerm)) {
                    item.style.display = 'block';
                } else {
                    item.style.display = 'none';
                }
            });
        });
    }
    
    // Kode baru - Auto scroll ke chat terbaru
    const messageContainer = document.getElementById('messageContainer');
    if (messageContainer) {
        messageContainer.scrollTop = messageContainer.scrollHeight;
    }
    
    // Kode baru - Kirim pesan
    const sendMessageForm = document.getElementById('sendMessageForm');
    const messageInput = document.getElementById('messageInput');
    
    if (sendMessageForm) {
        sendMessageForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const message = messageInput.value.trim();
            if (!message) return;
            
            // Kirim pesan ke server
            fetch('{{ route("dosen.grup.sendMessage", $grup->id) }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ isi_pesan: message })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Buat pesan baru
                    const today = new Date();
                    const dateStr = today.toISOString().split('T')[0];
                    
                    // Cek apakah sudah ada divider untuk hari ini
                    let dateDivider = document.querySelector(`.chat-date-divider span[data-date="${dateStr}"]`);
                    if (!dateDivider) {
                        // Buat date divider baru jika belum ada
                        const dividerDiv = document.createElement('div');
                        dividerDiv.className = 'chat-date-divider';
                        
                        const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
                        const dateDisplay = today.toLocaleDateString('id-ID', options);
                        
                        dividerDiv.innerHTML = `<span data-date="${dateStr}">${dateDisplay}</span>`;
                        messageContainer.appendChild(dividerDiv);
                    }
                    
                    // Tambah pesan baru
                    const msgDiv = document.createElement('div');
                    msgDiv.className = 'chat-message dosen';
                    msgDiv.innerHTML = `
                        <div class="message-bubble">
                            <p>${data.data.isi_pesan}</p>
                            <div class="message-time">
                                ${data.data.created_at}
                            </div>
                        </div>
                    `;
                    
                    messageContainer.appendChild(msgDiv);
                    
                    // Clear input
                    messageInput.value = '';
                    
                    // Scroll ke pesan terbaru
                    messageContainer.scrollTop = messageContainer.scrollHeight;
                    
                    // Jika container pesan kosong, hapus pesan "belum ada pesan"
                    const emptyMessage = messageContainer.querySelector('.text-center.py-5');
                    if (emptyMessage) {
                        emptyMessage.remove();
                    }
                } else {
                    alert('Gagal mengirim pesan: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Terjadi kesalahan saat mengirim pesan');
            });
        });
    }
    
    // Kode untuk file upload
    const attachmentBtn = document.getElementById('attachmentBtn');
    const lampiran = document.getElementById('lampiran');
    
    if (attachmentBtn && lampiran) {
        attachmentBtn.addEventListener('click', function() {
            lampiran.click();
        });
    }
    
    // Tambahkan pengendali peristiwa ke form perpindahan peran
    const switchRoleForm = document.getElementById('switchRoleForm');
    if (switchRoleForm) {
        switchRoleForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Submit form langsung
            this.submit();
        });
    }
});
</script>