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
        --success-gradient: linear-gradient(135deg, #27AE60, #2ECC71);
        --success-hover: linear-gradient(135deg, #229954, #28b965);
        --text-color: #546E7A;
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
    }
    
    .sidebar-menu .nav-link.active {
        background: #E3F2FD;
        color: var(--bs-primary);
    }
    
    .sidebar-menu .nav-link:hover:not(.active) {
        background: #f8f9fa;
    }

    .komunikasi-submenu .nav-link.active {
        background: #E3F2FD;
        color: var(--bs-primary);
    }

    .komunikasi-submenu .nav-link:hover:not(.active) {
        background: #f8f9fa;
    }

    .komunikasi-submenu {
        margin-left: 15px;
    }

    .komunikasi-submenu .nav-link {
        padding: 8px 15px;
        font-size: 13px;
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
        margin: 0 auto;
    }

    .profile-image-real {
        width: 45px;
        height: 45px;
        border-radius: 50%;
        object-fit: cover;
        border: 2px solid #f8f9fa;
        margin: 0 auto;
        display: block;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
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

    .btn-gradient-success {
        background: var(--success-gradient);
        border: none;
        color: white;
        transition: all 0.3s ease;
        font-weight: 500;
    }

    .btn-gradient-success:hover {
        background: var(--success-hover);
        color: white;
        box-shadow: 0 4px 15px rgba(39, 174, 96, 0.4);
        transform: translateY(-1px);
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
        display: flex;
        flex-direction: column;
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
    
    .message-time {
        color: rgba(255, 255, 255, 0.7);
        font-size: 12px;
        text-align: right;
        margin-top: 4px;
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
    
    /* Pesan dari mahasiswa yang sedang login (diri sendiri) */
    .chat-message.mahasiswa {
        margin-left: auto;
    }
    
    .chat-message.mahasiswa .message-bubble {
        background-color: #1a73e8;
        color: white;
        border-radius: 15px 15px 3px 15px;
        margin-left: auto;
        min-width: 120px;
        display: block;
    }
    
    .chat-message.mahasiswa p {
        margin-bottom: 8px;
        word-break: break-word;
    }
    
    /* Pesan dari mahasiswa lain */
    .chat-message.mahasiswa-lain .message-bubble {
        background-color: #585f67;
        color: white;
        border-radius: 15px 15px 15px 3px;
        min-width: 80px;
        display: block;
    }
    
    .chat-message.mahasiswa-lain p {
        margin-bottom: 8px;
        word-break: break-word;
    }
    
    /* Pesan dari dosen */
    .chat-message.dosen .message-bubble {
        background-color: #585f67;
        color: white;
        border-radius: 15px 15px 15px 3px;
        min-width: 80px;
        display: block;
    }
    
    .chat-message.dosen p {
        margin-bottom: 8px;
        word-break: break-word;
    }
    
    .sender-name {
        font-size: 14px;
        font-weight: 600;
        margin-bottom: 4px;
    }
    
    .chat-message.dosen .sender-name {
        color: #5DE0E6;
    }
    
    .chat-message.mahasiswa-lain .sender-name {
        color: #f8ac30;
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

    /* ENHANCED MODAL STYLES */
    .modal-content {
        border: none;
        border-radius: 15px;
        box-shadow: 0 10px 40px rgba(0,0,0,0.15);
        overflow: hidden;
    }

    .modal-header {
        background: var(--primary-gradient);
        color: white;
        border-bottom: none;
        padding: 20px 25px;
        position: relative;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .modal-header::before {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        height: 3px;
        background: linear-gradient(90deg, rgba(255,255,255,0.3), rgba(255,255,255,0.1), rgba(255,255,255,0.3));
    }

    .modal-title {
        font-weight: 600;
        font-size: 1.2rem;
        margin: 0;
        flex: 1;
    }

    .btn-close {
        opacity: 1;
        font-size: 1.5rem;
        font-weight: normal;
        transition: all 0.2s ease;
        background: none !important;
        border: none !important;
        color: white !important;
        padding: 0 !important;
        width: 30px !important;
        height: 30px !important;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        box-shadow: none !important;
        outline: none !important;
        margin-left: auto !important;
        flex-shrink: 0 !important;
    }

    .btn-close:hover {
        opacity: 0.8;
        transform: scale(1.1);
        background: none !important;
        box-shadow: none !important;
    }

    .btn-close:focus {
        box-shadow: none !important;
        outline: none !important;
    }

    .btn-close::before {
        content: "×";
        font-size: 1.8rem;
        font-weight: bold;
        line-height: 1;
    }

    .modal-body {
        padding: 25px;
        background: #fafbfc;
    }

    .modal-footer {
        background: white;
        border-top: 1px solid rgba(0,0,0,0.05);
        padding: 20px 25px;
    }

    /* Info Modal Styles */
    .info-item {
        background: white;
        border-radius: 10px;
        padding: 15px;
        margin-bottom: 15px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        border-left: 4px solid transparent;
        border-image: var(--primary-gradient) 1;
        transition: all 0.3s ease;
    }

    .info-item:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 20px rgba(0,0,0,0.1);
    }

    .info-item label {
        font-weight: 600;
        color: #2c3e50;
        display: flex;
        align-items: center;
        margin-bottom: 8px;
    }

    .info-item label i {
        margin-right: 8px;
        color: #004AAD;
    }

    .info-item p {
        margin: 0;
        color: #546E7A;
        font-size: 0.95rem;
    }

    /* Table Styles for Member List */
    .table-responsive {
        border-radius: 8px;
        overflow: hidden;
        border: 1px solid #e9ecef;
    }

    .table {
        margin-bottom: 0;
        background: white;
        border-collapse: collapse;
    }

    .table thead th {
        background: #f8f9fa;
        color: #495057;
        border: 1px solid #e9ecef;
        font-weight: 600;
        text-align: center;
        padding: 12px 10px;
        font-size: 0.9rem;
    }

    .table tbody td {
        text-align: center;
        vertical-align: middle;
        padding: 12px 10px;
        border: 1px solid #e9ecef;
        font-size: 0.9rem;
    }

    .table tbody tr:hover {
        background-color: #f8f9fa;
    }

    .table tbody tr:nth-child(even) {
        background-color: #fafbfc;
    }

    .table tbody tr:nth-child(even):hover {
        background-color: #f8f9fa;
    }

    /* Form Control Styles */
    .form-control {
        border: 2px solid #e9ecef;
        border-radius: 10px;
        padding: 12px 15px;
        transition: all 0.3s ease;
    }

    .form-control:focus {
        border-color: #5DE0E6;
        box-shadow: 0 0 0 0.2rem rgba(93, 224, 230, 0.25);
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .custom-container {
            padding: 0 10px;
        }
        
        .main-content {
            padding-top: 15px;
            padding-bottom: 15px;
        }
        
        .group-header {
            padding: 15px;
            margin-bottom: 15px;
        }
        
        .group-header h5 {
            font-size: 1.1rem;
        }
        
        .sidebar {
            position: relative;
            top: 0;
            max-height: none;
            margin-bottom: 20px;
        }
        
        .message-container {
            max-height: 400px;
            padding: 10px 15px;
        }
        
        .message-input {
            padding: 10px;
        }
        
        .chat-message {
            max-width: 90%;
        }
        
        .message-bubble {
            padding: 12px 15px;
            max-width: 95%;
        }
        
        .modal-dialog {
            margin: 10px;
            max-width: calc(100% - 20px);
        }
        
        .modal-body {
            padding: 20px 15px;
        }
        
        .modal-header {
            padding: 15px 20px;
        }
        
        .modal-footer {
            padding: 15px 20px;
        }
        
        .table-responsive {
            font-size: 0.85rem;
        }
        
        .table thead th,
        .table tbody td {
            padding: 8px 5px;
        }
        
        .profile-image-placeholder {
            width: 35px;
            height: 35px;
            font-size: 16px;
        }
        
        .btn {
            padding: 8px 12px;
            font-size: 0.85rem;
        }
        
        .info-item {
            padding: 12px;
            margin-bottom: 12px;
        }
        
        .info-item label {
            font-size: 0.9rem;
        }
        
        .info-item p {
            font-size: 0.85rem;
        }
    }

    @media (max-width: 576px) {
        .header-icon {
            margin-left: 8px;
            padding: 6px;
            font-size: 1rem;
        }
        
        .group-header .d-flex {
            flex-direction: column;
            align-items: flex-start;
        }
        
        .group-header .d-flex > div:last-child {
            margin-top: 10px;
            align-self: flex-end;
        }
        
        .modal-dialog {
            margin: 5px;
            max-width: calc(100% - 10px);
        }
        
        .input-group {
            flex-wrap: nowrap;
        }
        
        .input-group .form-control {
            min-width: 0;
            flex: 1;
        }
        
        .message-input .input-group {
            border-radius: 25px;
            overflow: hidden;
        }
        
        .message-input .form-control {
            border-radius: 25px 0 0 25px;
        }
        
        .message-input .btn {
            border-radius: 0 25px 25px 0;
        }
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
                        <a href="{{ url('/buatpesanmahasiswa') }}" class="btn" style="background: var(--primary-gradient); color: white; padding: 10px 20px; border: none; border-radius: 5px;">
                            <i class="fas fa-plus me-2"></i> Pesan Baru
                        </a>
                    </div>                                                    
                    
                    <div class="sidebar-menu">
                        <div class="nav flex-column">
                            <a href="{{ url('/dashboardpesanmahasiswa') }}" class="nav-link">
                                <i class="fas fa-home me-2"></i>Daftar Pesan
                            </a>
                            <a href="#" class="nav-link menu-item active" id="grupDropdownToggle">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span><i class="fas fa-users me-2"></i>Daftar Grup</span>
                                    <i class="fas fa-chevron-down" id="grupDropdownIcon"></i>
                                </div>
                            </a>
                            <div class="collapse show komunikasi-submenu" id="komunikasiSubmenu">
                                @php
                                    $grups = Auth::user()->grups;
                                @endphp
                                
                                @if($grups && $grups->count() > 0)
                                    @foreach($grups as $grupItem)
                                    <a href="{{ route('mahasiswa.grup.show', $grupItem->id) }}" class="nav-link menu-item d-flex justify-content-between align-items-center {{ $grupItem->id == $grup->id ? 'active' : '' }}">
                                        {{ $grupItem->nama_grup }}
                                        @if(isset($grupItem->unreadMessages) && $grupItem->unreadMessages > 0)
                                        <span class="badge bg-danger rounded-pill">{{ $grupItem->unreadMessages }}</span>
                                        @endif
                                    </a>
                                    @endforeach
                                @else
                                    <div class="nav-link menu-item text-muted">
                                        <small>Belum ada grup</small>
                                    </div>
                                @endif
                            </div>
                            
                            <a href="{{ url('/riwayatpesanmahasiswa') }}" class="nav-link menu-item">
                                <i class="fas fa-history me-2"></i>Riwayat Pesan
                            </a>
                            <a href="{{ url('/faqmahasiswa') }}" class="nav-link menu-item">
                                <i class="fas fa-thumbtack me-2"></i>Pesan Tersematkan
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Content Area -->
            <div class="col-md-9">
                <!-- Group Header -->
                <div class="group-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="mb-0 fw-semibold">{{ $grup->nama_grup }}</h5>
                            <small>{{ $grup->mahasiswa->count() }} anggota</small>
                        </div>
                        <div class="d-flex">
                            <i class="fas fa-users header-icon" data-bs-toggle="modal" data-bs-target="#anggotaGrupModal"></i>
                            <i class="fas fa-info-circle header-icon" data-bs-toggle="modal" data-bs-target="#infoGrupModal"></i>
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
                                @if($pesan->tipe_pengirim == 'mahasiswa')
                                    @if($pesan->pengirim_id == Auth::user()->nim)
                                        <!-- Pesan dari mahasiswa yang sedang login (diri sendiri) -->
                                        <div class="chat-message mahasiswa">
                                            <div class="message-bubble">
                                                <p>{{ $pesan->isi_pesan }}</p>
                                                <div class="message-time">
                                                    {{ $pesan->created_at->format('H:i') }}
                                                </div>
                                            </div>
                                        </div>
                                    @else
                                        <!-- Pesan dari mahasiswa lain -->
                                        <div class="chat-message mahasiswa-lain">
                                            <div class="message-bubble">
                                                <div class="sender-name">{{ $pesan->pengirim->nama ?? 'Mahasiswa' }}</div>
                                                <p>{{ $pesan->isi_pesan }}</p>
                                                <div class="message-time">
                                                    {{ $pesan->created_at->format('H:i') }}
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                @else
                                    <!-- Pesan dari dosen -->
                                    <div class="chat-message dosen">
                                        <div class="message-bubble">
                                            <div class="sender-name">{{ $pesan->pengirim->nama ?? 'Dosen' }}</div>
                                            <p>{{ $pesan->isi_pesan }}</p>
                                            <div class="message-time">
                                                {{ $pesan->created_at->format('H:i') }}
                                            </div>
                                        </div>
                                    </div>
                                @endif
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
                        <div class="input-group">
                            <input type="text" class="form-control" id="messageInput" name="isi_pesan" placeholder="Tulis Pesan Anda disini.." autocomplete="off" style="border-radius: 25px 0 0 25px; padding: 12px 20px;">
                            <button type="submit" class="btn btn-gradient-primary" id="sendBtn" style="border-radius: 0 25px 25px 0; padding: 12px 20px;">
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
                <h5 class="modal-title" id="infoGrupModalLabel">
                    <i class="fas fa-info-circle me-2"></i>Informasi Grup
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="info-item">
                    <label><i class="fas fa-tag"></i>Nama Grup:</label>
                    <p>{{ $grup->nama_grup }}</p>
                </div>
                <div class="info-item">
                    <label><i class="fas fa-calendar-alt"></i>Dibuat pada:</label>
                    <p>{{ $grup->created_at->format('d M Y, H:i') }}</p>
                </div>
                <div class="info-item">
                    <label><i class="fas fa-users"></i>Jumlah Anggota:</label>
                    <p>{{ $grup->mahasiswa->count() }} mahasiswa</p>
                </div>
                <div class="info-item">
                    <label><i class="fas fa-user-tie"></i>Dibuat oleh:</label>
                    <p>{{ \App\Models\Dosen::find($grup->dosen_id)->nama }}</p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-2"></i>Tutup
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Anggota Grup Modal -->
<div class="modal fade" id="anggotaGrupModal" tabindex="-1" aria-labelledby="anggotaGrupModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="anggotaGrupModalLabel">
                    <i class="fas fa-users me-2"></i>Anggota Grup
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th width="8%">No.</th>
                                <th width="15%">Foto</th>
                                <th width="50%">Nama</th>
                                <th width="27%">NIM</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($grup->mahasiswa as $index => $anggota)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>
                                    @if($anggota->profile_photo && file_exists(public_path('storage/profile_photos/' . $anggota->profile_photo)))
                                        <img src="{{ asset('storage/profile_photos/' . $anggota->profile_photo) }}" 
                                             alt="Foto {{ $anggota->nama }}" 
                                             class="profile-image-real">
                                    @else
                                        <div class="profile-image-placeholder">
                                            <i class="fas fa-user"></i>
                                        </div>
                                    @endif
                                </td>
                                <td>{{ $anggota->nama }}</td>
                                <td>{{ $anggota->nim }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-2"></i>Tutup
                </button>
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
    
    // Auto scroll ke chat terbaru
    const messageContainer = document.getElementById('messageContainer');
    if (messageContainer) {
        messageContainer.scrollTop = messageContainer.scrollHeight;
    }
    
    // Kirim pesan
    const sendMessageForm = document.getElementById('sendMessageForm');
    const messageInput = document.getElementById('messageInput');
    
    if (sendMessageForm) {
        sendMessageForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const message = messageInput.value.trim();
            if (!message) return;
            
            // Tampilkan loading sementara
            const sendBtn = document.getElementById('sendBtn');
            const originalText = sendBtn.innerHTML;
            sendBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
            sendBtn.disabled = true;
            
            // Kirim pesan ke server
            fetch('{{ route("mahasiswa.grup.sendMessage", $grup->id) }}', {
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
                    
                    // Tambah pesan baru - selalu sebagai pesan dari diri sendiri (mahasiswa)
                    const msgDiv = document.createElement('div');
                    msgDiv.className = 'chat-message mahasiswa'; // Selalu mahasiswa untuk pengirim sendiri
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
            })
            .finally(() => {
                // Kembalikan tombol ke state normal
                sendBtn.innerHTML = originalText;
                sendBtn.disabled = false;
            });
        });
    }
    
    // Prevent text styling di input message
    if (messageInput) {
        // Disable browser autocomplete yang mungkin menyebabkan styling aneh
        messageInput.setAttribute('autocomplete', 'off');
        messageInput.setAttribute('autocorrect', 'off');
        messageInput.setAttribute('autocapitalize', 'off');
        messageInput.setAttribute('spellcheck', 'false');
        
        // Reset style saat focus
        messageInput.addEventListener('focus', function() {
            this.style.fontStyle = 'normal';
            this.style.textTransform = 'none';
        });
        
        // Prevent paste formatting
        messageInput.addEventListener('paste', function(e) {
            e.preventDefault();
            const text = (e.clipboardData || window.clipboardData).getData('text');
            const selection = window.getSelection();
            if (!selection.rangeCount) return;
            selection.deleteFromDocument();
            selection.getRangeAt(0).insertNode(document.createTextNode(text));
        });
    }
    
    // Enhancement: Keyboard shortcuts
    document.addEventListener('keydown', function(e) {
        // Esc untuk tutup modal yang terbuka
        if (e.key === 'Escape') {
            const openModals = document.querySelectorAll('.modal.show');
            openModals.forEach(modal => {
                bootstrap.Modal.getInstance(modal)?.hide();
            });
        }
        
        // Ctrl/Cmd + Enter untuk kirim pesan
        if ((e.ctrlKey || e.metaKey) && e.key === 'Enter' && messageInput === document.activeElement) {
            sendMessageForm.dispatchEvent(new Event('submit'));
        }
    });
    
    // Enhancement: Auto focus pada message input saat halaman dimuat
    if (messageInput) {
        setTimeout(() => {
            messageInput.focus();
        }, 500);
    }
    
    // Enhancement: Handle enter key untuk submit pesan
    if (messageInput) {
        messageInput.addEventListener('keydown', function(e) {
            if (e.key === 'Enter' && !e.shiftKey) {
                e.preventDefault();
                sendMessageForm.dispatchEvent(new Event('submit'));
            }
        });
    }
    
    // Enhancement: Real-time typing indicator (bisa dikembangkan nanti)
    let typingTimer;
    const typingInterval = 1000; // 1 detik
    
    if (messageInput) {
        messageInput.addEventListener('input', function() {
            clearTimeout(typingTimer);
            
            // Set timer untuk berhenti mengetik
            typingTimer = setTimeout(() => {
                // Logika untuk menghentikan indikator typing
                // Bisa ditambahkan nanti jika diperlukan
            }, typingInterval);
        });
    }
    
    // Enhancement: Smooth scroll untuk pesan baru
    function smoothScrollToBottom() {
        messageContainer.scrollTo({
            top: messageContainer.scrollHeight,
            behavior: 'smooth'
        });
    }
    
    // Enhancement: Deteksi jika user scroll ke atas
    let isUserScrolling = false;
    let scrollTimeout;
    
    if (messageContainer) {
        messageContainer.addEventListener('scroll', function() {
            isUserScrolling = true;
            clearTimeout(scrollTimeout);
            
            scrollTimeout = setTimeout(() => {
                isUserScrolling = false;
            }, 1000);
        });
    }
    
    // Enhancement: Show/hide scroll to bottom button
    const scrollToBottomBtn = document.createElement('button');
    scrollToBottomBtn.innerHTML = '<i class="fas fa-chevron-down"></i>';
    scrollToBottomBtn.className = 'btn btn-primary btn-sm position-fixed';
    scrollToBottomBtn.style.cssText = `
        bottom: 120px;
        right: 30px;
        border-radius: 50%;
        width: 40px;
        height: 40px;
        display: none;
        z-index: 1000;
        box-shadow: 0 2px 10px rgba(0,0,0,0.2);
    `;
    
    document.body.appendChild(scrollToBottomBtn);
    
    scrollToBottomBtn.addEventListener('click', function() {
        smoothScrollToBottom();
        this.style.display = 'none';
    });
    
    if (messageContainer) {
        messageContainer.addEventListener('scroll', function() {
            const isAtBottom = this.scrollTop + this.clientHeight >= this.scrollHeight - 100;
            scrollToBottomBtn.style.display = isAtBottom ? 'none' : 'block';
        });
    }
});
</script>
@endpush