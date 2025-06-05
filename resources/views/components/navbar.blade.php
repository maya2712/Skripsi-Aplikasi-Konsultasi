<!-- Navbar yang sudah dimodifikasi dengan mobile buttons -->
<nav class="navbar navbar-expand-lg navbar-light bg-white sticky-top" style="box-shadow: 0px 0px 10px 1px #afafaf">
    <div class="container">
        <a class="navbar-brand me-4" href="#" style="font-family: 'Viga', sans-serif; font-weight: 600; font-size: 25px;">
            <img src="https://upload.wikimedia.org/wikipedia/commons/2/2c/LOGO-UNRI.png" alt="SITEI Logo" width="30" height="30" class="d-inline-block align-text-top me-2">
            SEPTI  
        </a>
        
        <!-- Desktop navbar toggler (existing) -->
        <button class="navbar-toggler d-lg-none" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Mobile navigation buttons - TAMBAHAN BARU -->
        <div class="mobile-nav-buttons d-lg-none">
            <button class="burger-menu" id="mobileMenuToggle" type="button" aria-label="Buka menu navigasi">
                <i class="fas fa-bars"></i>
            </button>
            <button class="menu-dots" id="menuDots" type="button" aria-label="Menu akun">
                <i class="fas fa-ellipsis-v"></i>
            </button>
        </div>
        
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                @if(Auth::guard('admin')->check())
                    <!-- Menu khusus untuk Admin -->
                    <li class="nav-item">
                        <a class="nav-link {{ Route::currentRouteName() == 'admin.dashboard' ? 'active' : '' }}" 
                           href="{{ route('admin.dashboard') }}" 
                           style="font-weight: bold; border-bottom: 3px solid {{ Route::currentRouteName() == 'admin.dashboard' ? '#1a73e8' : 'transparent' }}; padding-bottom: 8px; margin-right: 20px;" 
                           onmouseover="if(!this.classList.contains('active')) this.style.borderBottomColor='#1a73e8'" 
                           onmouseout="if(!this.classList.contains('active')) this.style.borderBottomColor='transparent'">
                            DASHBOARD
                        </a>
                    </li>
                    
                    <!-- Management User Dropdown Menu tanpa arrow -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle-no-arrow {{ (Route::currentRouteName() == 'admin.managementuser_dosen' || Route::currentRouteName() == 'admin.managementuser_mahasiswa') ? 'active' : '' }}" 
                           href="#" 
                           id="managementDropdown" 
                           role="button" 
                           data-bs-toggle="dropdown" 
                           aria-expanded="false"
                           style="font-weight: bold; padding-bottom: 8px; position: relative; color: {{ (Route::currentRouteName() == 'admin.managementuser_dosen' || Route::currentRouteName() == 'admin.managementuser_mahasiswa') ? '#1a73e8' : '#4b5563' }};"
                           onmouseover="if(!this.classList.contains('active')) { this.querySelector('.custom-border').style.width = '100%'; this.style.color = '#1a73e8'; }" 
                           onmouseout="if(!this.classList.contains('active')) { this.querySelector('.custom-border').style.width = '0'; this.style.color = '#4b5563'; }">
                            MANAJEMEN USER
                            <span class="custom-border" style="position: absolute; bottom: 0; left: 0; height: 3px; background-color: #1a73e8; width: {{ (Route::currentRouteName() == 'admin.managementuser_dosen' || Route::currentRouteName() == 'admin.managementuser_mahasiswa') ? '100%' : '0' }}; transition: width 0.3s ease;"></span>
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="managementDropdown">
                            <li>
                                <a class="dropdown-item {{ Route::currentRouteName() == 'admin.managementuser_dosen' ? 'active' : '' }}" 
                                   href="{{ route('admin.managementuser_dosen') }}">
                                    <i class="bi bi-person-badge me-2"></i>Dosen
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item {{ Route::currentRouteName() == 'admin.managementuser_mahasiswa' ? 'active' : '' }}" 
                                   href="{{ route('admin.managementuser_mahasiswa') }}">
                                    <i class="bi bi-people me-2"></i>Mahasiswa
                                </a>
                            </li>
                        </ul>
                    </li>
                @else
                    <!-- Menu untuk Mahasiswa dan Dosen (tetap seperti semula) -->
                    <li class="nav-item">
                        {{-- <a class="nav-link" href="#" style="font-weight: bold; border-bottom: 3px solid transparent; padding-bottom: 8px; margin-right: 20px;" onmouseover="this.style.borderBottomColor='#1a73e8'" onmouseout="this.style.borderBottomColor='transparent'">
                            BIMBINGAN
                        </a> --}}
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="#" style="font-weight: bold; border-bottom: 3px solid #1a73e8; padding-bottom: 8px;">
                            KONSULTASI
                        </a>
                    </li>
                @endif
            </ul>
            <div class="d-flex align-items-center">
                <div class="dropdown">
                    <button class="btn btn-transparent dropdown-toggle" type="button" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                        AKUN
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="userDropdown">
                        <li>
                            @if(Auth::guard('mahasiswa')->check())
                                <a class="dropdown-item" href="{{ route('profil.mahasiswa') }}"><i class="bi bi-person-circle me-2"></i>Profil</a>
                            @elseif(Auth::guard('dosen')->check())
                                <a class="dropdown-item" href="{{ route('profil.dosen') }}"><i class="bi bi-person-circle me-2"></i>Profil</a>
                            @elseif(Auth::guard('admin')->check())
                                <a class="dropdown-item" href="{{ route('profil.admin') }}"><i class="bi bi-person-circle me-2"></i>Profil</a>
                            @else
                                <a class="dropdown-item" href="{{ route('profil') }}"><i class="bi bi-person-circle me-2"></i>Profil</a>
                            @endif
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <a class="dropdown-item text-danger" href="{{ route('logout') }}" 
                               onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                <i class="bi bi-box-arrow-right me-2"></i>Keluar
                            </a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                @csrf
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</nav>

<!-- CSS yang perlu ditambahkan ke file CSS utama aplikasi -->
<style>
/* Mobile Navigation Buttons */
.mobile-nav-buttons {
    display: none;
    align-items: center;
    gap: 5px; /* Jarak antar button lebih kecil */
    margin-left: auto;
}

.burger-menu, .menu-dots {
    background: none;
    border: none;
    color: #4b5563;
    font-size: 20px;
    padding: 4px; /* Padding lebih kecil */
    border-radius: 4px;
    transition: color 0.2s ease; /* Hanya transition warna */
    cursor: pointer;
    width: auto; /* Width auto agar tidak terlalu lebar */
    height: auto;
    display: flex;
    align-items: center;
    justify-content: center;
    min-width: 28px; /* Minimum width yang kecil */
    min-height: 28px;
}

.burger-menu:hover, .menu-dots:hover {
    color: #1a73e8; /* Hanya ubah warna, tidak ada background */
}

.burger-menu:focus, .menu-dots:focus {
    outline: none;
    box-shadow: none; /* Hilangkan focus box shadow */
}

/* Hide default navbar toggler on mobile when custom buttons are shown */
@media (max-width: 991.98px) {
    .navbar-toggler {
        display: none !important;
    }
    
    .mobile-nav-buttons {
        display: flex;
    }
    
    /* Mengurangi jarak dengan menghilangkan margin pada navbar-brand */
    .navbar-brand {
        margin-right: 0 !important;
        flex: 1; /* Biarkan brand mengambil space yang tersisa */
    }
    
    /* Memastikan container menggunakan space secara efisien */
    .navbar .container {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 8px; /* Gap kecil antara brand dan buttons */
    }
}

@media (max-width: 576px) {
    .mobile-nav-buttons {
        gap: 3px; /* Gap lebih kecil untuk mobile */
    }
    
    .burger-menu, .menu-dots {
        font-size: 18px;
        padding: 3px;
        min-width: 24px;
        min-height: 24px;
    }
    
    .navbar-brand {
        font-size: 20px !important;
        margin-right: 0 !important;
    }
    
    .navbar-brand img {
        width: 25px !important;
        height: 25px !important;
    }
    
    /* Mengurangi padding container untuk mobile */
    .navbar .container {
        padding-left: 10px;
        padding-right: 10px;
        gap: 5px;
    }
}

@media (max-width: 375px) {
    .mobile-nav-buttons {
        gap: 2px;
    }
    
    .burger-menu, .menu-dots {
        font-size: 16px;
        padding: 2px;
        min-width: 20px;
        min-height: 20px;
    }
    
    .navbar-brand {
        font-size: 18px !important;
    }
    
    .navbar .container {
        padding-left: 8px;
        padding-right: 8px;
        gap: 3px;
    }
}

/* Account dropdown menu for mobile dots button */
.mobile-account-menu {
    position: fixed;
    top: 70px;
    right: 15px;
    background: white;
    border-radius: 10px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
    padding: 10px 0;
    min-width: 200px;
    z-index: 1060;
    display: none;
    border: 1px solid #e5e7eb;
}

.mobile-account-menu.show {
    display: block;
    animation: slideDown 0.3s ease;
}

@keyframes slideDown {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.mobile-account-menu .menu-item {
    display: block;
    padding: 12px 20px;
    color: #374151;
    text-decoration: none;
    transition: background-color 0.2s ease;
    border: none;
    background: none;
    width: 100%;
    text-align: left;
    font-size: 14px;
}

.mobile-account-menu .menu-item:hover {
    background-color: #f9fafb;
    color: #1a73e8;
}

.mobile-account-menu .menu-item.text-danger:hover {
    background-color: #fef2f2;
    color: #dc2626;
}

.mobile-account-menu .divider {
    height: 1px;
    background-color: #e5e7eb;
    margin: 8px 0;
}
</style>

<!-- JavaScript untuk mobile menu -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const menuDots = document.getElementById('menuDots');
    
    // Handle menu dots click for account menu
    if (menuDots) {
        menuDots.addEventListener('click', function(e) {
            e.stopPropagation();
            
            // Check if mobile account menu already exists
            let mobileAccountMenu = document.getElementById('mobileAccountMenu');
            
            if (!mobileAccountMenu) {
                // Create mobile account menu
                mobileAccountMenu = document.createElement('div');
                mobileAccountMenu.id = 'mobileAccountMenu';
                mobileAccountMenu.className = 'mobile-account-menu';
                
                mobileAccountMenu.innerHTML = `
                    @if(Auth::guard('mahasiswa')->check())
                        <a href="{{ route('profil.mahasiswa') }}" class="menu-item">
                            <i class="bi bi-person-circle me-2"></i>Profil
                        </a>
                    @elseif(Auth::guard('dosen')->check())
                        <a href="{{ route('profil.dosen') }}" class="menu-item">
                            <i class="bi bi-person-circle me-2"></i>Profil
                        </a>
                    @elseif(Auth::guard('admin')->check())
                        <a href="{{ route('profil.admin') }}" class="menu-item">
                            <i class="bi bi-person-circle me-2"></i>Profil
                        </a>
                    @else
                        <a href="{{ route('profil') }}" class="menu-item">
                            <i class="bi bi-person-circle me-2"></i>Profil
                        </a>
                    @endif
                    <div class="divider"></div>
                    <button class="menu-item text-danger" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="bi bi-box-arrow-right me-2"></i>Keluar
                    </button>
                `;
                
                document.body.appendChild(mobileAccountMenu);
            }
            
            // Toggle menu visibility
            mobileAccountMenu.classList.toggle('show');
        });
        
        // Close menu when clicking outside
        document.addEventListener('click', function(e) {
            const mobileAccountMenu = document.getElementById('mobileAccountMenu');
            if (mobileAccountMenu && !menuDots.contains(e.target)) {
                mobileAccountMenu.classList.remove('show');
            }
        });
    }
});
</script>