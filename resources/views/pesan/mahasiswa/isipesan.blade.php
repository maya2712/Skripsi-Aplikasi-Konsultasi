@extends('layouts.app')

@section('title', 'Isi Pesan Mahasiswa')

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
        .btn-gradient:hover a{
            color: black;
        }
        .green-text {
            color: #28a745;
        }
        .container {
            flex: 1; 
        }
        .message-card {
            background-color: white;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0,0,0,.1);
            margin-bottom: 20px;
            transition: all 0.3s ease;
        }
        .message-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 12px rgba(0,0,0,.15);
        }
        .message-card.student {
            border-left: 5px solid #28a745;
        }
        .message-card.teacher {
            border-left: 5px solid #007bff;
        }
        .message-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 1px solid #e0e0e0;
        }
        .message-header .name {
            font-weight: bold;
            font-size: 18px;
        }
        .message-header .name.student {
            color: #28a745;
        }
        .message-header .name.teacher {
            color: #007bff;
        }
        .message-body {
            font-size: 16px;
            color: #333;
        }

        .student-card {
            background-color: #fff;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0,0,0,.1);
            padding: 20px;
            margin-bottom: 20px;
            position: sticky;
            top: 76px;
        }
        .student-photo {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            object-fit: cover;
            margin-bottom: 15px;
            border: 4px solid #007bff;
        }
        .student-info {
            text-align: center;
            margin-bottom: 20px;
        }
        .student-name {
            font-weight: bold;
            font-size: 20px;
            margin-bottom: 5px;
            color: #007bff;
        }
        .student-id {
            color: #6c757d;
            margin-bottom: 10px;
            font-size: 16px;
        }
        .info-table {
            width: 100%;
            margin: 20px 0;
            border-collapse: separate;
            border-spacing: 0;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            overflow: hidden;
        }
        .info-table th, .info-table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #dee2e6;
        }
        .info-table th {
            background-color: #f8f9fa;
            font-weight: 600;
            color: #495057;
        }
        .info-table tr:last-child td {
            border-bottom: none;
        }
        .btn-action {
            width: 100%;
            margin-top: 10px;
            margin-bottom: 10px;
            border-radius: 20px;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        .btn-action:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 6px rgba(0,0,0,.1);
        }
        .chat-wrapper {
            display: flex;
            flex-direction: column;
        }
        .chat-container {
            padding-right: 10px;
            transition: all 0.3s ease;
        }
        .chat-container::-webkit-scrollbar {
            width: 5px;
        }
        .chat-container::-webkit-scrollbar-thumb {
            background-color: #28a745;
            border-radius: 10px;
        }
        .reply-form {
            margin-top: 20px;
            background-color: white;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0,0,0,.1);
            transition: all 0.3s ease;
        }
        .reply-form h4 {
            color: #28a745;
            margin-bottom: 15px;
        }
        .form-control {
            border-radius: 20px;
            border: 1px solid #ced4da;
            padding: 10px 15px;
        }
        .form-control:focus {
            border-color: #28a745;
            box-shadow: 0 0 0 0.2rem rgba(40, 167, 69, 0.25);
        }
        .btn-primary {
            background-color: #28a745;
            border: none;
            border-radius: 20px;
            padding: 10px 20px;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        .btn-primary:hover {
            background-color: #218838;
            transform: translateY(-2px);
            box-shadow: 0 4px 6px rgba(0,0,0,.1);
        }
        .priority {
            display: none;
        }
        .priority.high {
            background-color: #dc3545;
            color: white;
        }
        .priority.medium {
            background-color: #ffc107;
            color: black;
        }
        .priority.low {
            background-color: #28a745;
            color: white;
        }
        .attachment {
            margin-top: 15px;
            padding: 10px;
            background-color: #f8f9fa;
            border-radius: 8px;
        }
        .attachment a {
            color: #007bff;
            text-decoration: none;
        }
        .attachment a:hover {
            text-decoration: underline;
        }
        .subject-info {
            margin: 20px 0; /* Menambahkan margin atas dan bawah */
            padding: 15px;
            background-color: #f8f9fa;
            border-radius: 8px;
            border: 1px solid #e9ecef;
        }
        .subject-title {
            font-weight: bold;
            color: #28a745;
            margin-bottom: 10px;
        }
        .priority-badge {
            display: inline-block;
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
            margin-top: 5px;
        }
        .priority-high {
            background-color: #dc3545;
            color: white;
        }
        .priority-medium {
            background-color: #ffc107;
            color: black;
        }
        .priority-low {
            background-color: #28a745;
            color: white;
        }
        .btn-action {
            width: 100%;
            margin-top: 10px; /* Menambahkan margin di atas tombol */
            margin-bottom: 10px;
            border-radius: 20px;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        .btn-action:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 6px rgba(0,0,0,.1);
        }
        .modal-content {
            border-radius: 15px;
        }
        .modal-header {
            background-color: #28a745;
            color: white;
            border-top-left-radius: 15px;
            border-top-right-radius: 15px;
        }
        .modal-title {
            font-weight: bold;
        }
        .modal-footer {
            border-top: none;
        }
        .status-ended {
            color: #dc3545;
            font-weight: bold;
        }
    </style>
    @endpush

    @section('content')
    <div class="container mt-5">
        <h1 class="mb-2 gradient-text fw-bold">Isi Konsultasi</h1>
        <hr></hr>
        <button class="btn btn-gradient mb-4 mt-2 d-flex align-items-center justify-content-center">
            <a href="/dashboardpesan">
                <i class="fas fa-arrow-left me-2"></i> Kembali
            </a>
        </button>
        <div class="row">
            <div class="col-md-4">
                <div class="student-card">
                    <img src="{{ asset('images/fotopakedi.png') }}" alt="Foto Mahasiswa" class="student-photo mx-auto d-block">
                    <div class="student-info">
                        <h3 class="student-name">Edi Susilo, Spd., M,Kom.,M.Eng </h3>
                        <p class="student-id">NIP. 1991 1029 201903 010 </p>
                        <p><i class="fas fa-chalkboard-teacher"></i> Dosen Teknik Informatika</p>
                    </div>
                    <table class="info-table">
                        <tr>
                            <th>Subjek</th>
                            <td>Bimbingan Skripsi</td>
                        </tr>
                        <tr>
                            <th>Penerima</th>
                            <td>Edi Susilo, S.Pd., M.Kom., M.Eng.</td>
                        </tr>
                        <tr>
                            <th>Prioritas</th>
                            <td><span class="priority-badge priority-high">Mendesak</span></td>
                        </tr>
                        <tr>
                            <th>Dikirim</th>
                            <td>15:30, 26 September 2024</td>
                        </tr>
                        <tr id="statusRow" style="display: none;">
                            <th>Status</th>
                            <td class="status-ended">Pesan telah berakhir</td>
                        </tr>
                    </table>
                    <button class="btn btn-danger btn-action" id="endChatBtn"><i class="fas fa-times-circle"></i> Akhiri Pesan</button>
                </div>
            </div>
                
            <div class="col-md-8">
                <div class="chat-wrapper">
                    <div class="chat-container">
                        <div class="message-card student">
                            <div class="message-header">
                                <span class="name student"><i class="fas fa-user-circle"></i> Desi Maya Sari</span>
                                <div>
                                    <small class="text-muted"><i class="far fa-clock"></i> 15:30, 26 September 2024</small>
                                </div>
                            </div>
                            <div class="message-body">
                                <p>Assalamualaikum Pak,</p>
                                <p>Selamat sore.</p>
                                <p>Saya Desi Maya Sari dari Prodi Teknik Informatika ingin melakukan bimbingan Skripsi. Karena itu, apakah Bapak ada di kampus?</p>
                                <p>Terima kasih, Pak.</p>
                                <p>Wassalamualaikum.</p>
                            </div>
                            <div class="attachment">
                                <p><i class="fas fa-paperclip"></i> Lampiran:</p>
                                <a href="#" target="_blank"><i class="fas fa-file-pdf"></i> Skripsi_Desi_Maya_Sari.pdf</a>
                            </div>
                        </div>
                        <div class="message-card teacher">
                            <div class="message-header">
                                <span class="name teacher"><i class="fas fa-user-tie"></i> Edi Susilo, S.Pd., M.Kom., M.Eng.</span>
                                <div>
                                    <small class="text-muted"><i class="far fa-clock"></i> 16:45, 26 September 2024</small>
                                </div>
                            </div>
                            <div class="message-body">
                                <p>Waalaikumsalam</p>
                                <p>Saya ada di kampus besok dari pukul 10.00 sampai 15.00. Silakan datang ke ruangan saya untuk bimbingan Skripsi.</p>
                            </div>
                        </div>
                        <div class="message-card student">
                            <div class="message-header">
                                <span class="name student"><i class="fas fa-user-circle"></i> Desi Maya Sari</span>
                                <div>
                                    <small class="text-muted"><i class="far fa-clock"></i> 17:20, 26 September 2024</small>
                                </div>
                            </div>
                            <div class="message-body">
                                <p>Baik Pak, Terima kasih atas informasinya.</p>
                            </div>
                        </div>
                    </div>
                    <div class="reply-form" id="replyForm">
                        <h4><i class="fas fa-reply"></i> Balas Pesan</h4>
                        <form>
                            <div class="mb-3">
                                <textarea class="form-control" rows="4" placeholder="Tulis pesan Anda di sini..."></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary"><i class="fas fa-paper-plane"></i> Kirim Pesan</button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="confirmModal" tabindex="-1" aria-labelledby="confirmModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="confirmModalLabel">Konfirmasi Akhiri Pesan</h5>
                            <!-- <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button> -->
                        </div>
                        <div class="modal-body">
                            Apakah Anda yakin ingin mengakhiri pesan ini?
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                            <button type="button" class="btn btn-danger" id="confirmEndChat">Ya, Akhiri Pesan</button>
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
            const endChatBtn = document.getElementById('endChatBtn');
            const confirmEndChatBtn = document.getElementById('confirmEndChat');
            const replyForm = document.getElementById('replyForm');
            const statusRow = document.getElementById('statusRow');
            const modal = new bootstrap.Modal(document.getElementById('confirmModal'));
            
            endChatBtn.addEventListener('click', function() {
                modal.show();
            });

            confirmEndChatBtn.addEventListener('click', function() {
                // Sembunyikan form balas pesan
                replyForm.style.display = 'none';
                
                // Ubah teks tombol "Akhiri Pesan" menjadi "Pesan Diakhiri"
                endChatBtn.innerHTML = '<i class="fas fa-check-circle"></i> Pesan Diakhiri';
                endChatBtn.classList.remove('btn-danger');
                endChatBtn.classList.add('btn-secondary');
                endChatBtn.disabled = true;

                // Tampilkan status pesan diakhiri dalam tabel
                statusRow.style.display = 'table-row';

                // Tutup modal
                modal.hide();
            });
        });
    </script>
    @endpush