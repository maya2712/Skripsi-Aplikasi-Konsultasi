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
            background: linear-gradient(135deg, #00923F 0%, #006428 100%);
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
            background: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1440 320'%3E%3Cpath fill='%2300923F' fill-opacity='0.05' d='M0,96L48,122.7C96,149,192,203,288,208C384,213,480,171,576,138.7C672,107,768,85,864,96C960,107,1056,149,1152,154.7C1248,160,1344,128,1392,112L1440,96L1440,0L1392,0C1344,0,1248,0,1152,0C1056,0,960,0,864,0C768,0,672,0,576,0C480,0,384,0,288,0C192,0,96,0,48,0L0,0Z'%3E%3C/path%3E%3C/svg%3E") no-repeat top;
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
            background: linear-gradient(90deg, transparent, #00923F, transparent);
        }

        .form-floating > .form-control {
            border: 2px solid #e0e0e0;
            border-radius: 12px;
            transition: all 0.3s ease;
        }

        .form-floating input::-ms-reveal,
        .form-floating input::-ms-clear {
            display: none;
        }

        .form-floating input::-webkit-contacts-auto-fill-button,
        .form-floating input::-webkit-credentials-auto-fill-button {
            visibility: hidden;
            display: none !important;
            pointer-events: none;
            height: 0;
            width: 0;
            margin: 0;
        }

        .btn-success {
            background: linear-gradient(135deg, #00923F 0%, #007F36 100%);
            border: none;
            border-radius: 12px;
            padding: 0.8rem;
            font-weight: 600;
            letter-spacing: 0.5px;
            transition: all 0.3s ease;
        }

        .btn-success:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 16px rgba(0, 146, 63, 0.2);
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
            border-left: 4px solid #00923F;
        }

        .developer-link {
            color: #00923F;
            transition: all 0.3s ease;
        }

        .developer-link:hover {
            color: #007F36;
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
            background: linear-gradient(135deg, #00923F 0%, #006428 100%);
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
                                                <img src="{{ asset('images/1.png') }}" class="d-block w-100 h-100" alt="Slide 1" style="object-fit: contain;">
                                            </div>
                                            <div class="carousel-item h-100">
                                                <img src="{{ asset('images/2.png') }}" class="d-block w-100 h-100" alt="Slide 2" style="object-fit: contain;">
                                            </div>
                                            <div class="carousel-item h-100">
                                                <img src="{{ asset('images/3.png') }}" class="d-block w-100 h-100" alt="Slide 3" style="object-fit: contain;">
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
                                        <h4 class="fw-bold mb-0">Sistem Informasi</h4>
                                        <p class="mb-0">Teknik Elektro & Informatika</p>
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
                                        <div class="form-floating mb-3">
                                            <input type="text" class="form-control @error('username') is-invalid @enderror" name="username" id="username" placeholder="NIP/NIM" value="{{ old('username') }}" required autocomplete="username" autofocus>
                                            <label for="username">NIP/NIM <span class="text-danger">*</span></label>
                                            @error('username')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="form-floating mb-4 position-relative">
                                            <input type="password" 
                                                class="form-control @error('password') is-invalid @enderror" 
                                                name="password" 
                                                id="password" 
                                                placeholder="Password" 
                                                required>
                                            <label for="password">Password <span class="text-danger">*</span></label>
                                            <button type="button" 
                                                    class="password-toggle-btn" 
                                                    id="togglePassword" 
                                                    style="background: none; border: none; position: absolute; right: 10px; top: 50%; transform: translateY(-50%); color: #6c757d; cursor: pointer; z-index: 100;">
                                                <i class="fas fa-eye-slash"></i>
                                            </button>
                                            @error('password')
                                                <div class="invalid-feedback">{{ $message }}</div>
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
                                                DESI, MURNI, SYAHIRAH
                                            </a>
                                            <div class="mt-2">
                                                <small class="text-muted">2024 © SITEI JTE UNRI</small>
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
        // Password Toggle
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

        // Form Validation
        (function() {
            'use strict';
            const forms = document.querySelectorAll('.needs-validation');
            Array.from(forms).forEach(form => {
                form.addEventListener('submit', event => {
                    if (!form.checkValidity()) {
                        event.preventDefault();
                        event.stopPropagation();
                    }
                    form.classList.add('was-validated');
                }, false);
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