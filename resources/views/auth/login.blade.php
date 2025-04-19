<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Sistem Informasi Bimbingan dan Perpesanan Teknik Elektro UNRI">
    <meta name="author" content="SITEI JTE UNRI">
    <title>APLIKASI BIMBINGAN DAN PERPESANAN</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.3/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            min-height: 100vh;
            background: linear-gradient(135deg, #0a3b5c 0%, #4b9ed0 100%);
            overflow-x: hidden;
        }

        .login-wrapper {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
        }

        .login-container {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            position: relative;
        }

        .login-container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1440 320'%3E%3Cpath fill='%230a3b5c' fill-opacity='0.05' d='M0,96L48,122.7C96,149,192,203,288,208C384,213,480,171,576,138.7C672,107,768,85,864,96C960,107,1056,149,1152,154.7C1248,160,1344,128,1392,112L1440,96L1440,0L1392,0C1344,0,1248,0,1152,0C1056,0,960,0,864,0C768,0,672,0,576,0C480,0,384,0,288,0C192,0,96,0,48,0L0,0Z'%3E%3C/path%3E%3C/svg%3E") no-repeat top;
            background-size: cover;
            z-index: 0;
        }

        .carousel-section {
            background: rgba(248, 249, 250, 0.9);
            border-radius: 15px;
            margin: 1rem;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.05);
        }

        .carousel-item img {
            border-radius: 15px;
            padding: 1rem;
        }

        .form-section {
            position: relative;
            z-index: 1;
            padding: 2rem;
        }

        .logo-section {
            position: relative;
            padding-bottom: 2rem;
        }

        .logo-section::after {
            content: '';
            position: absolute;
            bottom: 1rem;
            left: 50%;
            transform: translateX(-50%);
            width: 50%;
            height: 3px;
            background: linear-gradient(90deg, transparent, #0a3b5c, transparent);
        }

        .form-floating > .form-control {
            border: 2px solid #e0e0e0;
            border-radius: 12px;
            transition: all 0.3s ease;
            background-image: none !important; /* Menghilangkan ikon validasi bawaan Bootstrap */
        }

        /* Menyembunyikan semua ikon bawaan browser pada input */
        .form-floating input::-ms-reveal,
        .form-floating input::-ms-clear,
        .form-floating input::-webkit-contacts-auto-fill-button,
        .form-floating input::-webkit-credentials-auto-fill-button {
            visibility: hidden;
            display: none !important;
            pointer-events: none;
            height: 0;
            width: 0;
            margin: 0;
        }
        
        /* Mematikan styling validasi bawaan Bootstrap */
        .form-control.is-valid,
        .was-validated .form-control:valid,
        .form-control.is-invalid,
        .was-validated .form-control:invalid {
            background-image: none !important;
            border-color: #e0e0e0 !important;
            padding-right: calc(1.5em + 0.75rem) !important;
        }
        
        /* Kustom styling untuk tombol toggle password */
        .password-toggle-btn {
            background: none !important;
            border: none !important;
            position: absolute !important;
            right: 10px !important;
            top: 50% !important;
            transform: translateY(-50%) !important;
            color: #6c757d !important;
            cursor: pointer !important;
            z-index: 5 !important;
            width: 36px !important;
            height: 36px !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
        }
        
        /* Focus state untuk input */
        .form-control:focus {
            border-color: #0a3b5c !important;
            box-shadow: 0 0 0 0.25rem rgba(10, 59, 92, 0.15) !important;
        }
        
        /* Styling untuk feedback validasi */
        .custom-feedback {
            display: none;
            width: 100%;
            margin-top: 0.25rem;
            font-size: 0.875em;
        }
        
        .custom-invalid-feedback {
            color: #dc3545;
        }
        
        .custom-valid-feedback {
            color: #0a3b5c;
        }
        
        .input-with-icon {
            position: relative;
        }

        .btn-success {
            background: linear-gradient(135deg, #0a3b5c 0%, #3c8dbc 100%);
            border: none;
            border-radius: 12px;
            padding: 0.8rem;
            font-weight: 600;
            letter-spacing: 0.5px;
            transition: all 0.3s ease;
        }

        .btn-success:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 16px rgba(10, 59, 92, 0.2);
        }

        .alert {
            border-radius: 12px;
            border: none;
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
        }

        .alert-danger {
            border-left: 4px solid #dc3545;
        }

        .alert-success {
            border-left: 4px solid #0a3b5c;
        }

        .developer-link {
            color: #0a3b5c;
            transition: all 0.3s ease;
        }

        .developer-link:hover {
            color: #3c8dbc;
            text-decoration: none !important;
        }

        @media (max-width: 992px) {
            .carousel-section {
                margin-bottom: 2rem;
            }
        }

        /* Animated background */
        .animated-bg {
            position: fixed;
            width: 100vw;
            height: 100vh;
            top: 0;
            left: 0;
            z-index: -1;
            background: linear-gradient(135deg, #0a3b5c 0%, #3c8dbc 100%);
        }

        .animated-bg::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1440 320'%3E%3Cpath fill='%23ffffff' fill-opacity='0.05' d='M0,96L48,122.7C96,149,192,203,288,208C384,213,480,171,576,138.7C672,107,768,85,864,96C960,107,1056,149,1152,154.7C1248,160,1344,128,1392,112L1440,96L1440,0L1392,0C1344,0,1248,0,1152,0C1056,0,960,0,864,0C768,0,672,0,576,0C480,0,384,0,288,0C192,0,96,0,48,0L0,0Z'%3E%3C/path%3E%3C/svg%3E") repeat-y;
            animation: wave 15s linear infinite;
            opacity: 0.1;
        }

        @keyframes wave {
            0% { background-position: 0 0; }
            100% { background-position: 1440px 0; }
        }
    </style>
</head>

<body>
    <div class="animated-bg"></div>
    <div class="login-wrapper">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-11">
                    <div class="login-container">
                        <div class="row g-0">
                            <div class="col-lg-8">
                                <div class="carousel-section h-100">
                                    <div id="carouselExampleControls" class="carousel slide h-100" data-bs-ride="carousel">
                                        <div class="carousel-inner h-100">
                                            <div class="carousel-item active h-100">
                                                <img src="{{ asset('images/bg1.jpg') }}" class="d-block w-100 h-100" alt="Slide 1" style="object-fit: contain;">
                                            </div>
                                            <div class="carousel-item h-100">
                                                <img src="{{ asset('images/bg2.jpg') }}" class="d-block w-100 h-100" alt="Slide 2" style="object-fit: contain;">
                                            </div>
                                            <div class="carousel-item h-100">
                                                <img src="{{ asset('images/bg3.jpg') }}" class="d-block w-100 h-100" alt="Slide 3" style="object-fit: contain;">
                                            </div>
                                        </div>
                                        <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleControls" data-bs-slide="prev">
                                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                            <span class="visually-hidden">Previous</span>
                                        </button>
                                        <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleControls" data-bs-slide="next">
                                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                            <span class="visually-hidden">Next</span>
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-4">
                                <div class="form-section">
                                    <div class="logo-section text-center">
                                        <img src="{{ asset('images/logounri.png') }}" alt="logo_unri" width="80" height="80" class="img-fluid mb-3">
                                        <h4 class="fw-bold mb-0">Sistem Elektronik</h4>
                                        <p class="mb-0">Program Studi Teknik Infomatika</p>
                                    </div>

                                    @if ($errors->any())
                                        <div class="alert alert-danger py-2 mb-3">
                                            <small><i class="fas fa-exclamation-circle me-2"></i>{{ $errors->first() }}</small>
                                        </div>
                                    @endif

                                    @if (session('status'))
                                        <div class="alert alert-success py-2 mb-3">
                                            <small><i class="fas fa-check-circle me-2"></i>{{ session('status') }}</small>
                                        </div>
                                    @endif

                                    <form class="needs-validation" action="/login" method="POST" novalidate>
                                        @csrf
                                        <div class="form-floating mb-3 input-with-icon">
                                            <input type="text" 
                                                class="form-control" 
                                                name="username" 
                                                id="username" 
                                                placeholder="NIP/NIM" 
                                                value="{{ old('username') }}" 
                                                required 
                                                autocomplete="username" 
                                                autofocus>
                                            <label for="username">NIP/NIM <span class="text-danger">*</span></label>
                                            @error('username')
                                                <div class="custom-feedback custom-invalid-feedback d-block">
                                                    <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                                </div>
                                            @enderror
                                        </div>

                                        <div class="form-floating mb-4 input-with-icon">
                                            <input type="password" 
                                                class="form-control" 
                                                name="password" 
                                                id="password" 
                                                placeholder="Password" 
                                                required>
                                            <label for="password">Password <span class="text-danger">*</span></label>
                                            <button type="button" 
                                                   class="password-toggle-btn" 
                                                   id="togglePassword">
                                                <i class="fas fa-eye-slash"></i>
                                            </button>
                                            @error('password')
                                                <div class="custom-feedback custom-invalid-feedback d-block">
                                                    <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                                </div>
                                            @enderror
                                        </div>

                                        <button type="submit" class="btn btn-success w-100 mb-3">
                                            <i class="fas fa-sign-in-alt me-2"></i>Masuk
                                        </button>

                                        <div class="text-center mb-4">
                                            <small class="text-muted">
                                                Belum Punya Akun atau Lupa Password?<br>
                                                <span class="fw-semibold">(Hubungi Admin Prodi)</span>
                                            </small>
                                        </div>

                                        <div class="text-center pt-3 border-top">
                                            <small class="text-muted d-block mb-1">Dikembangkan oleh:</small>
                                            <a href="/developer" class="developer-link text-decoration-none fw-semibold">
                                                DESI MAYA SARI
                                            </a>
                                            <div class="mt-2">
                                                <small class="text-muted">2025 © SEPTI JTE UNRI</small>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Password Toggle yang lebih baik
        document.getElementById("togglePassword").addEventListener("click", function(e) {
            e.preventDefault();
            const passwordInput = document.getElementById("password");
            const icon = this.querySelector('i');
            
            if (passwordInput.type === "password") {
                passwordInput.type = "text";
                icon.classList.remove("fa-eye-slash");
                icon.classList.add("fa-eye");
            } else {
                passwordInput.type = "password";
                icon.classList.remove("fa-eye");
                icon.classList.add("fa-eye-slash");
            }
        });

        // Form Validation kustom yang tidak menambahkan ikon validasi
        (function() {
            'use strict';
            const form = document.querySelector('form.needs-validation');
            const inputs = form.querySelectorAll('input');
            
            // Tambahkan event listener untuk validasi kustom
            form.addEventListener('submit', function(event) {
                let isValid = true;
                
                // Cek validitas setiap input
                inputs.forEach(input => {
                    if (!input.validity.valid) {
                        isValid = false;
                    }
                });
                
                // Jika form tidak valid, hindari pengiriman
                if (!isValid) {
                    event.preventDefault();
                    event.stopPropagation();
                }
                
                // Hindari menambahkan class was-validated
                // form.classList.add('was-validated');
            });
            
            // Hapus visual feedback pada saat input
            inputs.forEach(input => {
                input.addEventListener('input', function() {
                    // Jangan tambahkan class apapun untuk validasi
                    input.classList.remove('is-valid');
                    input.classList.remove('is-invalid');
                });
            });
        })();

        // Auto-hide alerts
        setTimeout(function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(function(alert) {
                alert.style.transition = 'opacity 0.5s ease-out';
                alert.style.opacity = '0';
                setTimeout(function() {
                    alert.style.display = 'none';
                }, 500);
            });
        }, 5000);
    </script>
</body>
</html>