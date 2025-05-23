<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}"> 
    <title>SEPTI - @yield('title', 'Sistem Informasi Teknik Informatika')</title>

    <!-- Anti-back button -->
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css">
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600&family=Viga&display=swap" rel="stylesheet">
    
    <!-- Custom Global CSS -->
    <link href="{{ asset('css/custom.css') }}" rel="stylesheet">

    {{-- custom logo website --}}
    <link rel="icon" href="{{ asset('images/logounri.png') }}" type="image/png">

    <!-- Page Specific Styles -->
    @stack('styles')
    <style>
        body {
            font-family: "Open Sans", sans-serif;
            margin: 0;
            padding: 0;
            overflow-x: hidden;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            position: relative;
        }
        
        .blob {
            position: absolute;
            border-radius: 50%;
            filter: blur(70px);
            mix-blend-mode: multiply;
            animation: blob 7s infinite;
            pointer-events: none;
        }
        
        .blob-1 { 
            top: 0; 
            left: 0; 
            width: 300px; 
            height: 300px; 
            background-color: rgba(74, 222, 128, 0.1); 
        }
        
        .blob-2 { 
            top: 50%; 
            right: 0; 
            width: 350px; 
            height: 350px; 
            background-color: rgba(251, 191, 36, 0.1); 
            animation-delay: 2s;
        }
        
        .blob-3 { 
            bottom: 0; 
            left: 50%; 
            width: 350px; 
            height: 350px; 
            background-color: rgba(239, 68, 68, 0.1); 
            animation-delay: 4s;
        }
        
        @keyframes blob {
            0%, 100% { transform: translate(0, 0) scale(1); }
            25% { transform: translate(20px, -50px) scale(1.1); }
            50% { transform: translate(-20px, 20px) scale(0.9); }
            75% { transform: translate(50px, 50px) scale(1.05); }
        }
        
        .nav-link {
            position: relative;
            color: #4b5563;
            transition: color 0.3s ease;
            font-weight: bold;
        }
        
        .nav-link:hover, .nav-link.active {
            color: #1a73e8; /* Warna biru saat hover (diubah dari hijau menjadi biru) */
        }
        
        .nav-link::after {
            content: '';
            position: absolute;
            width: 0;
            height: 2px;
            bottom: 0;
            left: 0;
            background-color: #1a73e8; /* Warna garis biru (diubah dari hijau menjadi biru) */
            transition: width 0.3s ease;
        }
        
        .nav-link:hover::after, .nav-link.active::after {
            width: 100%;
        }
        
        /* Tambahan style untuk navbar baru */
        .main-content {
            flex: 1;
            padding-top: 20px; 
            padding-bottom: 20px; 
        }
        
        /* Style untuk footer baru */
        .footer {
            background-color: #343a40; 
            color: #fff; 
            padding: 12px 0; 
            margin-top: auto; 
            width: 100%;
        }
        
        /* Custom style untuk dropdown */
        .dropdown-menu {
            border-radius: 8px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            border: none;
            padding: 8px 0;
        }
        
        .dropdown-item {
            padding: 8px 24px;
            transition: background-color 0.2s;
        }
        
        .dropdown-item:hover {
            background-color: #f0f7ff;
        }
        
        .dropdown-item.text-danger:hover {
            background-color: #fff0f0;
        }
        
        /* Style untuk tombol AKUN */
        .btn-transparent {
            background-color: transparent;
            border: none;
            box-shadow: none;
            color: #4b5563;
            font-weight: bold;
        }
        
        .btn-transparent:hover, .btn-transparent:focus, .btn-transparent:active {
            background-color: transparent !important;
            box-shadow: none !important;
            color: #1a73e8 !important;
        }
    </style>
    @stack('styles')
</head>
<body class="bg-light d-flex flex-column min-vh-100">
    @include('components.blobbackground')
    
    @include('components.navbar')
    
    <main class="flex-grow-1">
        {{-- Pindahkan alert ke dalam main content dengan container --}}
        @if(session('success') || session('error'))
        <div class="container mt-3">
            @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            @endif
            
            @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            @endif
        </div>
        @endif
        
        @yield('content')
    </main>
    
    @include('components.footer')
    
    <!-- Sederhanakan script yang dimuat untuk menghindari konflik -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    
    <!-- Script pencegahan navigasi balik setelah logout -->
<script>
    (function() {
        window.history.pushState(null, null, window.location.href);
        window.onpopstate = function() {
            window.history.pushState(null, null, window.location.href);
        };
    })();
</script>

<!-- Cek autentikasi pada halaman dashboard -->
@if(Route::current() && (
    str_contains(Route::current()->getName() ?? '', 'dashboard') || 
    str_contains(Route::current()->uri ?? '', 'dashboard')
))
    @php
        $isLoggedIn = Auth::guard('mahasiswa')->check() || Auth::guard('dosen')->check() || Auth::guard('admin')->check();
    @endphp

    @if(!$isLoggedIn)
        <script>
            window.location.href = "{{ route('login') }}";
        </script>
    @endif
@endif

<!-- Script untuk memastikan dropdown Bootstrap berfungsi -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Inisialisasi semua dropdown
        var dropdownElementList = [].slice.call(document.querySelectorAll('.dropdown-toggle'));
        var dropdownList = dropdownElementList.map(function(dropdownToggleEl) {
            return new bootstrap.Dropdown(dropdownToggleEl);
        });

        // Memastikan dropdown tetap terbuka sampai diklik di luar dropdown
        document.querySelectorAll('.dropdown').forEach(function(dropdown) {
            dropdown.addEventListener('click', function(event) {
                event.stopPropagation();
            });
        });
    });
</script>

    
    @stack('scripts')
</body>
</html>