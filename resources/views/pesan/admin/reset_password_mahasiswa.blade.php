@extends('layouts.app')
@section('title', 'Reset Password Mahasiswa')
@section('content')
<div class="container">
    <h3 class="mt-3 mb-1" style="font-weight: 600; background: linear-gradient(to right, #004AAD, #5DE0E6); -webkit-background-clip: text; -webkit-text-fill-color: transparent; display: inline-block;">Reset Password Mahasiswa</h3>
    <hr class="mb-4">
    
    <a href="{{ route('admin.managementuser_mahasiswa') }}" class="btn btn-sm mb-4" style="background: linear-gradient(to right, #004AAD, #5DE0E6); color: white;">
        <i class="fas fa-arrow-left"></i> Kembali
    </a>
    
    <!-- Notifikasi error validasi -->
    @if ($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif
    
    <!-- Notifikasi error lainnya -->
    @if(session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
    @endif
    
    <div class="card">
        <div class="card-body">
            <div class="alert alert-warning">
                <i class="fas fa-exclamation-triangle me-2"></i>
                <strong>Perhatian!</strong> Anda akan mereset password untuk mahasiswa berikut:
            </div>
            
            <div class="row mb-4">
                <div class="col-md-3">
                    <p class="mb-0 text-muted">NIM:</p>
                </div>
                <div class="col-md-9">
                    <p class="mb-0 fw-bold">{{ $mahasiswa->nim }}</p>
                </div>
            </div>
            
            <div class="row mb-4">
                <div class="col-md-3">
                    <p class="mb-0 text-muted">Nama:</p>
                </div>
                <div class="col-md-9">
                    <p class="mb-0 fw-bold">{{ $mahasiswa->nama }}</p>
                </div>
            </div>
            
            <div class="row mb-4">
                <div class="col-md-3">
                    <p class="mb-0 text-muted">Email:</p>
                </div>
                <div class="col-md-9">
                    <p class="mb-0">{{ $mahasiswa->email }}</p>
                </div>
            </div>
            
            <form action="{{ route('admin.reset-password-mahasiswa.post', $mahasiswa->nim) }}" method="POST">
                @csrf
                
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="new_password" class="form-label">Password Baru *</label>
                        <div class="input-group">
                            <input type="password" class="form-control @error('new_password') is-invalid @enderror" id="new_password" name="new_password" required>
                            <button class="btn btn-outline-secondary toggle-password" type="button" data-target="new_password">
                                <i class="fas fa-eye-slash"></i>
                            </button>
                            @error('new_password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <small class="form-text text-muted">Minimal 6 karakter</small>
                    </div>
                    
                    <div class="col-md-6">
                        <label for="new_password_confirmation" class="form-label">Konfirmasi Password Baru *</label>
                        <div class="input-group">
                            <input type="password" class="form-control" id="new_password_confirmation" name="new_password_confirmation" required>
                            <button class="btn btn-outline-secondary toggle-password" type="button" data-target="new_password_confirmation">
                                <i class="fas fa-eye-slash"></i>
                            </button>
                        </div>
                    </div>
                </div>
                
                <div class="d-flex justify-content-end mt-4">
                    <button type="button" class="btn btn-secondary me-2" onclick="window.location='{{ route('admin.managementuser_mahasiswa') }}'">Batal</button>
                    <button type="submit" class="btn btn-danger">Save Password</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Ambil semua tombol toggle password
        document.querySelectorAll('.toggle-password').forEach(function(button) {
            button.addEventListener('click', function() {
                // Dapatkan target input password
                const targetId = this.getAttribute('data-target');
                const passwordInput = document.getElementById(targetId);
                
                // Toggle tipe input antara "password" dan "text"
                if (passwordInput.type === 'password') {
                    passwordInput.type = 'text';
                    this.querySelector('i').classList.remove('fa-eye-slash');
                    this.querySelector('i').classList.add('fa-eye');
                } else {
                    passwordInput.type = 'password';
                    this.querySelector('i').classList.remove('fa-eye');
                    this.querySelector('i').classList.add('fa-eye-slash');
                }
            });
        });
    });
</script>
@endpush