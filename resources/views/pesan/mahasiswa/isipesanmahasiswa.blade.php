@extends('layouts.app')
@section('title', 'Isi Pesan Mahasiswa')

@push('styles')
    <style>
        /* CSS Variables yang hanya berlaku dalam konten pesan */
        #pesan-container {
            --primary-color: #0070dc;
            --primary-gradient: linear-gradient(135deg, #0070dc, #00a5e3);
            --primary-hover: linear-gradient(135deg, #006ccc, #0099d6);
            --secondary-color: #6c757d;
            --success-color: #27AE60;
            --danger-color: #FF5252;
            --danger-gradient: linear-gradient(135deg, #FF5252, #e63946);
            --light-bg: #f8f9fa;
            --dark-text: #333333;
            --light-text: #ffffff;
            --gray-text: #6c757d;
            --border-radius: 0.5rem;
            --card-radius: 0.5rem;
            --shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
        }
        
        /* Gaya untuk konten pesan, bukan untuk navbar/footer */
        #pesan-container .content-wrapper {
            background-color: var(--light-bg);
            font-family: 'Nunito', sans-serif;
        }
        
        /* Header dengan gradasi */
        #pesan-container .header-gradient {
            background: linear-gradient(to right, #004AAD, #5DE0E6);
            color: var(--light-text);
            border: none;
        }
        
        /* Tombol kembali dengan gradasi */
        #pesan-container .btn-gradient-primary {
            background: linear-gradient(to right, #004AAD, #5DE0E6);
            color: var(--light-text);
            border: none;
            box-shadow: 0 3px 8px rgba(0, 74, 173, 0.2);
            transition: all 0.3s ease;
        }
        
        #pesan-container .btn-gradient-primary:hover {
            background: linear-gradient(to right, #003d91, #4bcad0);
            color: var(--light-text);
            transform: translateY(-2px);
            box-shadow: 0 5px 12px rgba(0, 74, 173, 0.3);
        }
        
        /* Tombol akhiri dengan gradasi */
        #pesan-container .btn-gradient-danger {
            background: var(--danger-gradient);
            color: var(--light-text);
            border: none;
            box-shadow: 0 3px 8px rgba(255, 82, 82, 0.2);
        }
        
        #pesan-container .btn-gradient-danger:hover {
            background: linear-gradient(135deg, #e63946, #FF5252);
            color: var(--light-text);
        }
        
        /* Card styling */
        #pesan-container .custom-card {
            border-radius: var(--card-radius);
            box-shadow: var(--shadow);
            border: none;
        }
        
        /* Profile image */
        #pesan-container .profile-image {
            border: 4px solid white;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.1);
        }
        
        /* Chat container */
        #pesan-container .chat-container {
            max-height: 500px;
            overflow-y: auto;
            padding: 1.25rem !important;
        }
        
        /* Message bubble */
        #pesan-container .message-bubble {
            max-width: 85%;
            border-radius: 1rem;
            position: relative;
        }
        
        #pesan-container .user-message .message-bubble {
            background-color: #0f57c4;
            border-radius: 1rem;
            margin-left: auto;
        }
        
        #pesan-container .received-message .message-bubble {
            background-color: #3a3a3a;
            border-radius: 1rem;
            color: #ffffff;
        }
        
        /* Memastikan teks dalam bubble chat berwarna putih */
        #pesan-container .message-bubble p {
            color: #ffffff !important;
        }
        
        #pesan-container .received-message .message-bubble p {
            color: #ffffff !important;
        }
        
        /* Nama pengirim dalam pesan dosen */
        #pesan-container .received-message .fw-bold.text-primary {
            color: #ffffff !important;
        }
        
        /* Badge */
        #pesan-container .badge-priority {
            padding: 4px 8px;
            border-radius: 50px;
            font-weight: 600;
            font-size: 0.7rem;
        }
        
        /* Input standard */
        #pesan-container .custom-input {
            border-radius: 0.5rem;
            padding: 10px 15px;
            border: 1px solid #e9ecef;
        }
        
        #pesan-container .custom-input:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(0, 112, 220, 0.1);
        }
        
        /* Date divider */
        #pesan-container .chat-date-divider {
            text-align: center;
            position: relative;
            margin: 20px 0;
        }
        
        #pesan-container .chat-date-divider:before {
            content: '';
            position: absolute;
            left: 0;
            top: 50%;
            width: 100%;
            height: 1px;
            background-color: #e9ecef;
            z-index: 1;
        }
        
        #pesan-container .chat-date-divider span {
            background-color: white;
            padding: 0 15px;
            font-size: 12px;
            color: #6c757d;
            position: relative;
            z-index: 2;
            border-radius: 20px;
        }
        
        /* System message */
        #pesan-container .system-message {
            text-align: center;
            position: relative;
            margin: 30px 0;
        }
        
        #pesan-container .system-message:before {
            content: '';
            position: absolute;
            left: 0;
            top: 50%;
            width: 100%;
            height: 1px;
            background-color: #e74c3c;
            z-index: 1;
            opacity: 0.3;
        }
        
        #pesan-container .system-message span {
            background-color: white;
            padding: 5px 15px;
            color: #e74c3c;
            font-weight: 600;
            font-size: 14px;
            position: relative;
            z-index: 2;
            border-radius: 20px;
            display: inline-block;
            box-shadow: 0 2px 5px rgba(231, 76, 60, 0.1);
        }
        
        /* Status dot dengan animasi standard */
        #pesan-container .status-dot {
            display: inline-block;
            width: 8px;
            height: 8px;
            background-color: var(--success-color);
            border-radius: 50%;
            margin-right: 8px;
            position: relative;
        }
        
        #pesan-container .status-dot:before {
            content: '';
            position: absolute;
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background-color: rgba(39, 174, 96, 0.4);
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            animation: pulse 2s infinite;
        }
        
        @keyframes pulse {
            0% {
                transform: translate(-50%, -50%) scale(1);
                opacity: 0.7;
            }
            70% {
                transform: translate(-50%, -50%) scale(1.3);
                opacity: 0;
            }
            100% {
                transform: translate(-50%, -50%) scale(1);
                opacity: 0;
            }
        }
        
        /* Modal standard */
        .modal-content {
            border-radius: 0.5rem;
            overflow: hidden;
            border: none;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }
        
        #pesan-container .modal-header.danger-gradient {
            background: var(--danger-gradient);
        }
        
        /* Toast standard */
        #pesan-container .custom-toast {
            border-radius: 0.5rem;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }
        
        /* Tabel dengan ujung melengkung */
        #pesan-container .info-table {
            width: 100%;
            margin: 0;
            border-collapse: separate;
            border-spacing: 0;
            font-size: 0.85rem;
            border-radius: 0.8rem;
            overflow: hidden;
            border: 1px solid #dee2e6;
        }
        
        #pesan-container .info-table th, 
        #pesan-container .info-table td {
            padding: 6px 8px;
            vertical-align: middle;
            border-bottom: 1px solid #dee2e6;
            border-right: 1px solid #dee2e6;
            word-break: break-word;
        }
        
        #pesan-container .info-table tr:last-child td {
            border-bottom: none;
        }
        
        #pesan-container .info-table td:last-child {
            border-right: none;
        }
        
        #pesan-container .info-table tr td:first-child {
            width: 80px;
            background-color: rgba(0, 0, 0, 0.03);
            font-weight: 500;
            font-size: 0.8rem;
        }
        
        /* Penyesuaian untuk tampilan badge dalam tabel */
        #pesan-container .info-table .badge {
            font-size: 0.7rem;
            padding: 4px 8px;
            border-radius: 50px;
        }
        
        /* Message time */
        #pesan-container .message-time {
            font-size: 12px;
            text-align: right;
            margin-top: 5px;
        }
        
        /* Layout */
        #pesan-container .layout-wrapper {
            display: flex;
            flex-direction: column;
        }
        
        #pesan-container .content-wrapper {
            flex: 1;
            padding: 20px 0;
        }
        
        /* Smaller content for sidebar */
        #pesan-container .sidebar-text {
            font-size: 0.85rem;
        }
        
        #pesan-container .sidebar-name {
            font-size: 1.1rem;
            margin-bottom: 0;
        }
        
        #pesan-container .sidebar-role {
            font-size: 0.75rem;
        }
        
        /* Smaller status badge */
        #pesan-container .status-badge {
            font-size: 0.7rem;
            padding: 3px 8px;
        }
        
        /* Column spacing */
        #pesan-container .g-3 {
            --bs-gutter-x: 1rem;
        }
    </style>
@endpush

@section('content')
<div id="pesan-container">
    <div class="content-wrapper bg-light">
        <div class="container">
            <div class="row g-3">
                <!-- Panel Kiri (Sidebar) -->
                <div class="col-lg-4 col-xl-3">
                    <div class="custom-card card">
                        <div class="card-body p-3">
                            <!-- Tombol Kembali -->
                            <a href="{{ route('mahasiswa.dashboard.pesan') }}" class="btn btn-gradient-primary d-block w-100 mb-3">
                                <i class="fas fa-arrow-left me-2"></i> Kembali
                            </a>
                            
                            <!-- Bagian Profil -->
                            <div class="text-center mb-3">
                                <img src="https://randomuser.me/api/portraits/men/41.jpg" alt="Profil Dosen" 
                                    class="rounded-circle profile-image mb-2" style="width: 100px; height: 100px; object-fit: cover;">
                                <h5 class="sidebar-name fw-bold text-primary">{{ $pesan->penerima->nama }}</h5>
                                <p class="sidebar-role text-muted mb-0">Dosen</p>
                            </div>
                            
                            <!-- Bagian Informasi Pesan -->
                            <h6 class="fw-bold text-center mb-2 text-primary sidebar-text">
                                <i class="fas fa-info-circle me-1"></i> Informasi Pesan
                            </h6>
                            
                            <table class="info-table mb-3">
                                <tbody>
                                    <tr>
                                        <td class="bg-light">Subjek</td>
                                        <td>{{ $pesan->subjek }}</td>
                                    </tr>
                                    <tr>
                                        <td class="bg-light">Penerima</td>
                                        <td>{{ $pesan->penerima->nama }}</td>
                                    </tr>
                                    <tr>
                                        <td class="bg-light">NIDN</td>
                                        <td>{{ $pesan->penerima->nip }}</td>
                                    </tr>
                                    <tr>
                                        <td class="bg-light">Prioritas</td>
                                        <td>
                                            <span class="badge badge-priority {{ $pesan->prioritas == 'Umum' ? 'bg-success' : 'bg-danger' }}">
                                                {{ $pesan->prioritas }}
                                            </span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="bg-light">Status</td>
                                        <td>
                                            <span id="pesanStatus" 
                                                class="badge badge-priority {{ $pesan->status == 'Aktif' ? 'bg-success' : 'bg-secondary' }}">
                                                {{ $pesan->status }}
                                            </span>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            
                            <!-- Tombol Akhiri Pesan -->
                            @if($pesan->status == 'Aktif')
                                <button id="endChatButton" class="btn btn-gradient-danger d-block w-100">
                                    <i class="fas fa-times-circle me-2"></i> Akhiri Pesan
                                </button>
                            @else
                                <button class="btn btn-secondary d-block w-100" disabled>
                                    <i class="fas fa-times-circle me-2"></i> Pesan Diakhiri
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
                
                <!-- Panel Kanan - Chat -->
                <div class="col-lg-8 col-xl-9">
                    <!-- Header Pesan -->
                    <div class="custom-card card mb-3">
                        <div class="card-header header-gradient p-3 d-flex justify-content-between align-items-center">
                            <div class="d-flex align-items-center">
                                <span class="status-dot"></span>
                                <h5 class="mb-0 fw-bold">{{ $pesan->penerima->nama }}</h5>
                            </div>
                            <div>
                                <button class="btn btn-sm bg-white bg-opacity-10 rounded-circle" title="Opsi Lainnya">
                                    <i class="fas fa-ellipsis-v text-white"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Container Pesan -->
                    <div class="custom-card card mb-3">
                        <div class="card-body chat-container" id="chatContainer">
                            @foreach($balasanByDate as $date => $messages)
                                <div class="chat-date-divider">
                                    <span>{{ \Carbon\Carbon::parse($date)->format('d F Y') }}</span>
                                </div>
                                
                                @foreach($messages as $message)
                                    @if($message instanceof App\Models\Pesan)
                                        <!-- Pesan Pertama (original message) -->
                                        <div class="d-flex justify-content-end mb-4 user-message">
                                            <div class="message-bubble p-3">
                                                <p class="mb-1">{{ $message->isi_pesan }}</p>
                                                <div class="message-time text-white-50">
                                                    {{ \Carbon\Carbon::parse($message->created_at)->format('H:i') }}
                                                </div>
                                            </div>
                                        </div>
                                    @else
                                        @if($message->tipe_pengirim == 'mahasiswa')
                                            <!-- Balasan dari Mahasiswa -->
                                            <div class="d-flex justify-content-end mb-4 user-message">
                                                <div class="message-bubble p-3">
                                                    <p class="mb-1">{{ $message->isi_balasan }}</p>
                                                    <div class="message-time text-white-50">
                                                        {{ \Carbon\Carbon::parse($message->created_at)->format('H:i') }}
                                                    </div>
                                                </div>
                                            </div>
                                        @else
                                            <!-- Balasan dari Dosen -->
                                            <div class="d-flex mb-4 received-message">
                                                <div class="message-bubble p-3">
                                                    <div class="mb-1">
                                                        <span class="fw-bold text-primary">{{ $pesan->penerima->nama }}</span>
                                                    </div>
                                                    <p class="mb-1">{{ $message->isi_balasan }}</p>
                                                    <div class="message-time text-muted">
                                                        {{ \Carbon\Carbon::parse($message->created_at)->format('H:i') }}
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    @endif
                                @endforeach
                            @endforeach
                            
                            @if($pesan->status == 'Berakhir')
                                <div class="system-message">
                                    <span><i class="fas fa-info-circle me-1"></i> Pesan telah diakhiri</span>
                                </div>
                            @endif
                        </div>
                    </div>
                    
                    <!-- Form Input Pesan -->
                    @if($pesan->status == 'Aktif')
                        <div class="custom-card card" id="messageInputContainer">
                            <div class="card-body p-3">
                                <div class="input-group">
                                    <button class="btn btn-outline-secondary" title="Lampirkan File">
                                        <i class="fas fa-paperclip"></i>
                                    </button>
                                    <input type="text" class="form-control custom-input" id="messageInput" 
                                        placeholder="Tulis Pesan Anda disini..">
                                    <button class="btn btn-gradient-primary" id="sendButton">
                                        <i class="fas fa-paper-plane me-1"></i> Kirim
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal konfirmasi akhiri pesan -->
<div class="modal fade" id="confirmModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header danger-gradient text-white">
                <h5 class="modal-title">
                    <i class="fas fa-exclamation-circle me-2"></i> Konfirmasi Akhiri Pesan
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <p class="mb-0">Apakah Anda yakin ingin mengakhiri pesan ini? Setelah diakhiri, Anda tidak dapat mengirim pesan baru dalam percakapan ini.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal" id="cancelEndChat">
                    <i class="fas fa-times me-1"></i> Tidak
                </button>
                <button type="button" class="btn btn-gradient-danger" id="confirmEndChat">
                    <i class="fas fa-check me-1"></i> Ya, Akhiri
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Toast Notification -->
<div class="position-fixed top-0 end-0 p-3" style="z-index: 1100">
    <div id="notificationToast" class="toast custom-toast align-items-center text-white bg-success border-0" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="d-flex">
            <div class="toast-body">
                <i class="fas fa-check-circle me-2"></i>
                <span id="notificationMessage">Pesan berhasil dikirim</span>
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Elemen yang diperlukan
        const endChatButton = document.getElementById('endChatButton');
        const confirmModal = new bootstrap.Modal(document.getElementById('confirmModal'));
        const cancelEndChatBtn = document.getElementById('cancelEndChat');
        const confirmEndChatBtn = document.getElementById('confirmEndChat');
        const messageInputContainer = document.getElementById('messageInputContainer');
        const pesanStatus = document.getElementById('pesanStatus');
        const notificationToast = document.getElementById('notificationToast');
        const notificationMessage = document.getElementById('notificationMessage');
        const chatContainer = document.getElementById('chatContainer');
        const messageInput = document.getElementById('messageInput');
        const sendButton = document.getElementById('sendButton');
        
        // Inisialisasi Toast
        const toast = new bootstrap.Toast(notificationToast, {
            delay: 3000
        });
        
        // Status percakapan (aktif atau berakhir)
        let isConversationEnded = {{ $pesan->status == 'Berakhir' ? 'true' : 'false' }};
        
        // Auto-scroll ke bawah chat container
        if (chatContainer) {
            chatContainer.scrollTop = chatContainer.scrollHeight;
        }
        
        // Tampilkan modal konfirmasi
        if (endChatButton) {
            endChatButton.addEventListener('click', function() {
                if (!isConversationEnded) {
                    confirmModal.show();
                }
            });
        }
        
        // Konfirmasi akhiri pesan
        if (confirmEndChatBtn) {
            confirmEndChatBtn.addEventListener('click', function() {
                // Kirim request ke server untuk mengakhiri pesan
                fetch('{{ route("mahasiswa.pesan.end", $pesan->id) }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Mengakhiri percakapan
                        isConversationEnded = true;
                        
                        // Sembunyikan modal
                        confirmModal.hide();
                        
                        // Sembunyikan form input pesan
                        if (messageInputContainer) {
                            messageInputContainer.style.display = 'none';
                        }
                        
                        // Ubah status pesan
                        pesanStatus.textContent = 'Berakhir';
                        pesanStatus.classList.remove('bg-success');
                        pesanStatus.classList.add('bg-secondary');
                        
                        // Nonaktifkan tombol akhiri pesan
                        endChatButton.classList.add('disabled');
                        endChatButton.disabled = true;
                        endChatButton.classList.remove('btn-gradient-danger');
                        endChatButton.classList.add('btn-secondary');
                        
                        // Tambahkan pesan sistem di chat container
                        const systemMessage = document.createElement('div');
                        systemMessage.className = 'system-message';
                        systemMessage.innerHTML = '<span><i class="fas fa-info-circle me-1"></i> Pesan telah diakhiri</span>';
                        chatContainer.appendChild(systemMessage);
                        
                        // Scroll ke bawah untuk menampilkan pesan sistem
                        chatContainer.scrollTop = chatContainer.scrollHeight;
                        
                        // Tampilkan notifikasi
                        showNotification('Pesan berhasil diakhiri', 'success');
                    } else {
                        // Tampilkan pesan error
                        showNotification('Gagal mengakhiri pesan: ' + data.message, 'warning');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showNotification('Terjadi kesalahan saat mengakhiri pesan', 'warning');
                });
            });
        }
        
        // Menangani pengiriman pesan saat menekan Enter di input
        if (messageInput && sendButton) {
            messageInput.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    sendButton.click();
                }
            });
            
            sendButton.addEventListener('click', function() {
                const message = messageInput.value.trim();
                if (message && !isConversationEnded) {
                    // Kirim pesan ke server
                    fetch('{{ route("mahasiswa.pesan.reply", $pesan->id) }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({ balasan: message })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Tambahkan pesan baru ke chat container
                            const newMessage = document.createElement('div');
                            newMessage.className = 'd-flex justify-content-end mb-4 user-message';
                            
                            newMessage.innerHTML = `
                                <div class="message-bubble p-3">
                                    <p class="mb-1">${data.data.isi_balasan}</p>
                                    <div class="message-time text-white-50">
                                        ${data.data.created_at}
                                    </div>
                                </div>
                            `;
                            
                            chatContainer.appendChild(newMessage);
                            
                            // Bersihkan input pesan
                            messageInput.value = '';
                            
                            // Scroll ke bawah untuk menampilkan pesan baru
                            chatContainer.scrollTop = chatContainer.scrollHeight;
                            
                            // Tampilkan notifikasi
                            showNotification('Pesan berhasil dikirim', 'success');
                        } else {
                            // Tampilkan pesan error
                            showNotification('Gagal mengirim pesan: ' + data.message, 'warning');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        showNotification('Terjadi kesalahan saat mengirim pesan', 'warning');
                    });
                }
            });
        }
        
        // Fungsi untuk menampilkan notifikasi
        function showNotification(message, type = 'success') {
            notificationToast.classList.remove('bg-success', 'bg-warning', 'bg-danger');
            notificationToast.classList.remove('text-white', 'text-dark');
            
            if (type === 'success') {
                notificationToast.classList.add('bg-success', 'text-white');
            } else if (type === 'warning') {
                notificationToast.classList.add('bg-warning', 'text-dark');
            }
            
            notificationMessage.textContent = message;
            toast.show();
        }
    });
</script>
@endpush