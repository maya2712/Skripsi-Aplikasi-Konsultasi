@extends('layouts.app')

@section('title', 'Buat Pesan Mahasiwa')

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
        form .form-label {
            font-weight: bold;
        }
        
        /* Style untuk opsi dalam select */
        select.form-select option {
            color: black;
            font-weight: bold;
        }
        /* Warna abu-abu untuk opsi yang disabled */
        select.form-select option:disabled {
            color: #6c757d;
        }  
    </style>
    @endpush

    @section('content')
    <div class="container mt-5">
        <h1 class="mb-2 gradient-text fw-bold">Buat Pesan Baru</h1>
        <hr></hr>
        <button class="btn btn-gradient mb-4 mt-2 d-flex align-items-center justify-content-center">
            <a href="/dashboardpesan">
                <i class="fas fa-arrow-left me-2"></i> Kembali
            </a>
        </button>

        <form>
            <div class="mb-3">
                <label for="subject" class="form-label">Subjek<span style="color: red;">*</span></label>
                <!-- Tambahkan placeholder "Isi subjek" -->
                <input type="text" class="form-control" id="subject" placeholder="Isi subjek" required>
            </div>
            
            <div class="mb-3">
                <label for="recipient" class="form-label">Penerima<span style="color: red;">*</span></label>
                <input type="text" class="form-control" id="recipient" placeholder="Isi penerima" required>
            </div>
            
            <div class="mb-3">
                <label for="priority" class="form-label">Prioritas<span style="color: red;">*</span></label>
                <select class="form-select" id="priority" required>
                    <option value="" selected disabled>Pilih Prioritas</option>
                    <option value="high">Medesak</option>
                    <option value="medium">Umum</option>
                </select>
            </div>
            
            <div class="mb-3">
                <label for="attachment" class="form-label">Lampiran (Opsional)</label>
                <!-- Tambahkan placeholder "Isi lampiran berupa link Google Drive" -->
                <input type="text" class="form-control" id="attachment" placeholder="Isi lampiran berupa link Google Drive">
            </div>
            
            <div class="mb-3">
                <label for="message" class="form-label">Pesan<span style="color: red;">*</span></label>
                <!-- Tambahkan placeholder "Isi pesan" -->
                <textarea class="form-control" id="message" rows="5" placeholder="Isi pesan" required></textarea>
            </div>
            
            <div class="text-end">
                <button type="submit" class="btn btn-gradient">
                    <i class="fas fa-paper-plane"></i> Kirim
                </button>
            </div>
        </form>            
    </div>
    @endsection
        

    @push('scripts')
        <script>
            // Mengubah warna teks "Pilih Prioritas" saat ini menggunakan JavaScript
            const prioritySelect = document.getElementById('priority');
            prioritySelect.addEventListener('change', function() {
                if (this.value === "") {
                    this.style.color = "#6c757d";  // Warna abu-abu
                } else {
                    this.style.color = "black";  // Warna normal
                }
            });
        
            // Mengatur warna awal ketika halaman dimuat
            if (prioritySelect.value === "") {
                prioritySelect.style.color = "#6c757d";  // Warna abu-abu
            }
        
            // Menginisialisasi autocomplete untuk input penerima
            $(document).ready(function() {
                var dosenList = [
                    "Dr. Feri Candra, S.T., M.T.",
                    "Dr. Dahliyusmanto S.Kom., M.Sc.",
                    "Dr. Irsan Taufik Ali, S.T., M.T.",
                    "Noveri Lysbetti Marpaung, S.T., M.Sc.",
                    "Rahyul Amri, S.T., M.T.",
                    "Linna Oktaviana Sari, S.T., M.T.",
                    "Salhazan Nasution, S.Kom., MIT.",
                    "T. Yudi Hadiwandra, S.Kom., M.Kom.",
                    "Rahmat Rizal Andhi, S.T., M.T.",
                    "Edi Susilo, Spd., M,Kom.,M.Eng",
                    "Dian Ramadhani, S.T., M.T.",
                ];
        
                $("#recipient").autocomplete({
                    source: dosenList,
                    minLength: 1 // Menampilkan saran setelah 1 karakter diketik
                });
            });
        </script>
        @endpush