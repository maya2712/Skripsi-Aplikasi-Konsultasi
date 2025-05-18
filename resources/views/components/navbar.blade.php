<!-- Navbar yang diperbaiki -->
<nav class="navbar navbar-expand-lg navbar-light bg-white sticky-top" style="box-shadow: 0px 0px 10px 1px #afafaf">
    <div class="container">
        <a class="navbar-brand me-4" href="#" style="font-family: 'Viga', sans-serif; font-weight: 600; font-size: 25px;">
            <img src="https://upload.wikimedia.org/wikipedia/commons/2/2c/LOGO-UNRI.png" alt="SITEI Logo" width="30" height="30" class="d-inline-block align-text-top me-2">
            SEPTI
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link" href="#" style="font-weight: bold; border-bottom: 3px solid transparent; padding-bottom: 8px; margin-right: 20px;" onmouseover="this.style.borderBottomColor='#1a73e8'" onmouseout="this.style.borderBottomColor='transparent'">
                        BIMBINGAN
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" href="#" style="font-weight: bold; border-bottom: 3px solid #1a73e8; padding-bottom: 8px;">
                        KONSULTASI
                    </a>
                </li>
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