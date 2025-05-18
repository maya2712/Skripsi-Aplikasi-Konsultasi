@extends('layouts.app')

@if(config('app.debug'))
<div class="container mt-2 mb-2" style="background-color: #f8f9fa; padding: 10px; border-radius: 5px; color: #333; display: none;">
    <small>
        <strong>Debug Info:</strong> 
        Guard: {{ $guard }} | 
        @if($guard === 'mahasiswa')
            NIM: {{ $user->nim }}
        @elseif($guard === 'dosen')
            NIP: {{ $user->nip }}
        @elseif($guard === 'admin')
            ID: {{ $user->id }}
        @endif
        | Email: {{ $user->email }}
    </small>
</div>
@endif

@section('title', 'Profil Pengguna')

@push('styles')
<style>
    body {
        background-color: #F5F7FA;
    }
    
    .profile-wrapper {
        background-color: #fff;
        border-radius: 10px;
        box-shadow: 0 0 15px rgba(0, 0, 0, 0.05);
        overflow: hidden;
        margin-top: 30px;
        position: relative;
    }
    
    .back-button {
        position: absolute;
        top: 15px;
        left: 15px;
        background: rgba(255, 255, 255, 0.2);
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        color: white;
        transition: all 0.2s;
        z-index: 5;
        text-decoration: none;
    }
    
    .back-button:hover {
        background: rgba(255, 255, 255, 0.4);
        color: white;
    }
    
    .profile-header {
        background: linear-gradient(to right, #004AAD, #5DE0E6);
        padding: 30px 0;
        position: relative;
        text-align: center;
    }
    
    .profile-header h3 {
        color: white;
        font-weight: 700;
        margin-bottom: 5px;
    }
    
    .profile-header p {
        color: rgba(255, 255, 255, 0.8);
        font-size: 14px;
    }
    
    .profile-img-container {
        position: relative;
        width: 130px;
        height: 130px;
        margin: 0 auto 10px;
    }
    
    .profile-img {
        width: 130px;
        height: 130px;
        border-radius: 50%;
        border: 4px solid rgba(255, 255, 255, 0.3);
        object-fit: cover;
        background-color: #f1f1f1;
    }
    
    .default-profile-img {
        width: 130px;
        height: 130px;
        border-radius: 50%;
        border: 4px solid rgba(255, 255, 255, 0.3);
        background-color: #E3F2FD;
        color: #004AAD;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 60px;
        position: absolute;
        top: 0;
        left: 0;
    }
    
    .edit-photo-icon {
        position: absolute;
        bottom: 0;
        right: 0;
        background: #fff;
        width: 35px;
        height: 35px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #004AAD;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
        cursor: pointer;
        transition: all 0.2s;
    }
    
    .edit-photo-icon:hover {
        background: #f8f9fa;
    }
    
    .profile-content {
        padding: 30px;
    }
    
    .profile-content .section-title {
        font-weight: 600;
        font-size: 18px;
        color: #333;
        margin-bottom: 20px;
        border-bottom: 1px solid #eee;
        padding-bottom: 10px;
    }
    
    .info-group {
        margin-bottom: 20px;
    }
    
    .info-label {
        font-size: 14px;
        color: #777;
        margin-bottom: 5px;
    }
    
    .info-value {
        font-size: 16px;
        color: #333;
        font-weight: 500;
    }
    
    .btn-password {
        color: #004AAD;
        text-decoration: none;
        font-weight: 500;
        font-size: 14px;
        padding: 10px 18px;
        border-radius: 5px;
        background-color: #E3F2FD;
        transition: all 0.2s;
        display: inline-flex;
        align-items: center;
        margin-top: 10px;
    }
    
    .btn-password:hover {
        background-color: #BBDEFB;
        color: #003380;
    }
    
    .btn-password i {
        margin-right: 8px;
    }
    
    /* Modal styling */
    .modal-header {
        background: linear-gradient(to right, #004AAD, #5DE0E6);
        color: white;
    }
    
    .modal-header .btn-close {
        color: white;
    }
    
    .modal-footer .btn-primary {
        background: linear-gradient(to right, #004AAD, #5DE0E6);
        border: none;
    }
    
    .modal-footer .btn-primary:hover {
        background: linear-gradient(to right, #1558b7, #1558b7);
    }
    
    .upload-area {
        border: 2px dashed #ccc;
        padding: 20px;
        text-align: center;
        border-radius: 5px;
        margin-bottom: 20px;
        cursor: pointer;
        transition: all 0.3s;
    }
    
    .upload-area:hover {
        border-color: #004AAD;
    }
    
    .upload-icon {
        font-size: 40px;
        color: #ccc;
        margin-bottom: 10px;
    }
    
    /* Responsive adjustments */
    @media (max-width: 767px) {
        .profile-content {
            padding: 20px;
        }
        
        .profile-img, .default-profile-img {
            width: 100px;
            height: 100px;
        }
        
        .profile-img-container {
            width: 100px;
            height: 100px;
        }
        
        .default-profile-img {
            font-size: 45px;
        }
    }
    
    /* Loading spinner styles */
    @keyframes spin {
        to { transform: rotate(360deg); }
    }
    
    .loading-spinner {
        display: inline-block;
        width: 3rem;
        height: 3rem;
        border: 3px solid rgba(0, 0, 0, 0.1);
        border-radius: 50%;
        border-top-color: #5DE0E6;
        border-left-color: #004AAD;
        animation: spin 1s ease-in-out infinite;
    }
    
    .loading-container {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 1.5rem;
    }
    
    .loading-text {
        margin-top: 15px;
        font-size: 16px;
        font-weight: 500;
        background: linear-gradient(to right, #004AAD, #5DE0E6);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }
</style>
@endpush

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="profile-wrapper">
                
                <!-- Tombol Kembali sesuai dengan role user -->
                @if($guard === 'mahasiswa')
                <a href="{{ route('mahasiswa.dashboard.pesan') }}" class="back-button">
                    <i class="fas fa-arrow-left"></i>
                </a>
                @elseif($guard === 'dosen')
                <a href="{{ route('dosen.dashboard.pesan') }}" class="back-button">
                    <i class="fas fa-arrow-left"></i>
                </a>
                @elseif($guard === 'admin')
                <a href="{{ route('admin.dashboard') }}" class="back-button">
                    <i class="fas fa-arrow-left"></i>
                </a>
                @endif
                
                <!-- Profile Header dengan debugging ID -->
                <div class="profile-header">
                    <div class="profile-img-container">
                        @if(isset($profilePhotoUrl))
                            <!-- Foto profil yang telah diupload -->
                            <img src="{{ $profilePhotoUrl }}" alt="Foto Profil" class="profile-img" id="profileImage">
                            <div class="default-profile-img" id="defaultProfileImg" style="display: none;">
                                <i class="fas fa-user"></i>
                            </div>
                        @else
                            <!-- Ikon default untuk foto profil yang belum diset -->
                            <div class="default-profile-img" id="defaultProfileImg">
                                <i class="fas fa-user"></i>
                            </div>
                            <img src="" alt="Foto Profil" class="profile-img" id="profileImage" style="display: none;">
                        @endif
                        <div class="edit-photo-icon" data-bs-toggle="modal" data-bs-target="#editPhotoModal">
                            <i class="fas fa-camera"></i>
                        </div>
                    </div>
                    
                    <!-- Nama dan role dinamis dengan ID pengguna -->
                    <h3>{{ $user->nama }}</h3>
                    <p>{{ ucfirst($guard) }} 
                        @if($guard === 'mahasiswa')
                            ({{ $user->nim }})
                        @elseif($guard === 'dosen')
                            ({{ $user->nip }})
                        @elseif($guard === 'admin')
                            ({{ $user->id }})
                        @endif
                    </p>
                </div>
                
                <!-- Profile Content -->
                <div class="profile-content">
                    <h4 class="section-title">Informasi Pribadi</h4>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="info-group">
                                <div class="info-label">Nama Lengkap</div>
                                <div class="info-value">{{ $user->nama }}</div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="info-group">
                                <!-- Tampilkan NIM/NIP sesuai role -->
                                <div class="info-label">{{ $guard === 'mahasiswa' ? 'NIM' : ($guard === 'dosen' ? 'NIP' : 'ID Admin') }}</div>
                                <div class="info-value">{{ $guard === 'mahasiswa' ? $user->nim : ($guard === 'dosen' ? $user->nip : $user->id) }}</div>
                            </div>
                        </div>
                        
                        <!-- Angkatan untuk mahasiswa, atau kolom kosong untuk dosen/admin -->
                        <div class="col-md-6">
                            <div class="info-group">
                                @if($guard === 'mahasiswa')
                                <div class="info-label">Angkatan</div>
                                <div class="info-value">{{ $user->angkatan }}</div>
                                @else
                                <div class="info-label">Jabatan</div>
                                <div class="info-value">{{ $guard === 'dosen' ? ($user->jabatan_fungsional ?? 'N/A') : 'Administrator' }}</div>
                                @endif
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="info-group">
                                <div class="info-label">Email</div>
                                <div class="info-value">{{ $user->email }}</div>
                            </div>
                        </div>
                    </div>
                    
                    <h4 class="section-title mt-4">Pengaturan Akun</h4>
                    
                    <div>
                        <a href="#" class="btn-password" data-bs-toggle="modal" data-bs-target="#changePasswordModal">
                            <i class="fas fa-key"></i> Ubah Kata Sandi
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Edit Foto -->
<div class="modal fade" id="editPhotoModal" tabindex="-1" aria-labelledby="editPhotoModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editPhotoModalLabel">Ubah Foto Profil</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="uploadPhotoForm" enctype="multipart/form-data">
                    @csrf
                    <div class="upload-area" id="uploadArea">
                        <div class="upload-icon">
                            <i class="fas fa-cloud-upload-alt"></i>
                        </div>
                        <p>Klik atau seret foto ke sini</p>
                        <small class="text-muted">Format yang didukung: JPG, PNG</small>
                        <input type="file" id="photoInput" name="photo" class="d-none" accept="image/jpeg, image/png">
                    </div>
                    
                    <div id="previewContainer" class="text-center mb-3" style="display: none;">
                        <img src="" id="photoPreview" class="img-fluid rounded-circle" style="max-height: 200px; width: 200px; height: 200px; object-fit: cover;">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary" id="savePhotoBtn">Simpan</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Ubah Kata Sandi -->
<div class="modal fade" id="changePasswordModal" tabindex="-1" aria-labelledby="changePasswordModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="changePasswordModalLabel">Ubah Kata Sandi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="changePasswordForm">
                    @csrf
                    <div class="mb-3">
                        <label for="current_password" class="form-label">Kata Sandi Saat Ini</label>
                        <div class="input-group">
                            <input type="password" class="form-control" id="current_password" name="current_password" required>
                            <button class="btn btn-outline-secondary toggle-password" type="button" data-target="current_password">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                        <div class="invalid-feedback" id="current_password_error"></div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="new_password" class="form-label">Kata Sandi Baru</label>
                        <div class="input-group">
                            <input type="password" class="form-control" id="new_password" name="new_password" required>
                            <button class="btn btn-outline-secondary toggle-password" type="button" data-target="new_password">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                        <div class="form-text">
                            Kata sandi minimal 6 karakter
                        </div>
                        <div class="invalid-feedback" id="new_password_error"></div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="new_password_confirmation" class="form-label">Konfirmasi Kata Sandi Baru</label>
                        <div class="input-group">
                            <input type="password" class="form-control" id="new_password_confirmation" name="new_password_confirmation" required>
                            <button class="btn btn-outline-secondary toggle-password" type="button" data-target="new_password_confirmation">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                        <div class="invalid-feedback" id="new_password_confirmation_error"></div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary" id="savePasswordBtn">Simpan</button>
            </div>
        </div>
    </div>
</div>
@endsection


@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Upload foto profile
        const uploadArea = document.getElementById('uploadArea');
        const photoInput = document.getElementById('photoInput');
        const photoPreview = document.getElementById('photoPreview');
        const previewContainer = document.getElementById('previewContainer');
        const savePhotoBtn = document.getElementById('savePhotoBtn');
        const profileImage = document.getElementById('profileImage');
        const defaultProfileImg = document.getElementById('defaultProfileImg');
        
        // Click on upload area to trigger file input
        if (uploadArea) {
            uploadArea.addEventListener('click', function() {
                photoInput.click();
            });
        }
        
        // Handle file selection
        if (photoInput) {
            photoInput.addEventListener('change', function() {
                if (this.files && this.files[0]) {
                    const reader = new FileReader();
                    
                    reader.onload = function(e) {
                        photoPreview.src = e.target.result;
                        previewContainer.style.display = 'block';
                        uploadArea.style.display = 'none';
                    };
                    
                    reader.readAsDataURL(this.files[0]);
                }
            });
        }
        
        // Drag and drop functionality
        if (uploadArea) {
            ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
                uploadArea.addEventListener(eventName, preventDefaults, false);
            });
            
            function preventDefaults(e) {
                e.preventDefault();
                e.stopPropagation();
            }
            
            ['dragenter', 'dragover'].forEach(eventName => {
                uploadArea.addEventListener(eventName, highlight, false);
            });
            
            ['dragleave', 'drop'].forEach(eventName => {
                uploadArea.addEventListener(eventName, unhighlight, false);
            });
            
            function highlight() {
                uploadArea.classList.add('border-primary');
            }
            
            function unhighlight() {
                uploadArea.classList.remove('border-primary');
            }
            
            uploadArea.addEventListener('drop', handleDrop, false);
            
            function handleDrop(e) {
                const dt = e.dataTransfer;
                const files = dt.files;
                
                if (files && files[0]) {
                    photoInput.files = files;
                    
                    const reader = new FileReader();
                    
                    reader.onload = function(e) {
                        photoPreview.src = e.target.result;
                        previewContainer.style.display = 'block';
                        uploadArea.style.display = 'none';
                    };
                    
                    reader.readAsDataURL(files[0]);
                }
            }
        }
        
        // Save photo functionality
        if (savePhotoBtn) {
            savePhotoBtn.addEventListener('click', function() {
                if (photoInput.files && photoInput.files[0]) {
                    // Buat form data untuk upload
                    const formData = new FormData();
                    formData.append('photo', photoInput.files[0]);
                    formData.append('_token', '{{ csrf_token() }}');
                    
                    // Tampilkan loading
                    savePhotoBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Uploading...';
                    savePhotoBtn.disabled = true;
                    
                    // Upload ke server dengan fetch API
                    fetch('{{ route("profil.upload-photo") }}', {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Update tampilan profil
                            profileImage.src = data.photo_url;
                            profileImage.style.display = 'block';
                            defaultProfileImg.style.display = 'none';
                            
                            // Tampilkan pesan sukses
                            alert('Foto profil berhasil diupload!');
                            
                            // Reset modal
                            const modal = bootstrap.Modal.getInstance(document.getElementById('editPhotoModal'));
                            modal.hide();
                            
                            // Opsional: Reload halaman untuk melihat perubahan
                            setTimeout(() => {
                                location.reload();
                            }, 500);
                        } else {
                            // Tampilkan pesan error
                            alert('Error: ' + (data.error || 'Gagal mengupload foto profil'));
                        }
                        
                        // Reset tombol
                        savePhotoBtn.innerHTML = 'Simpan';
                        savePhotoBtn.disabled = false;
                    })
                    .catch(error => {
                        console.error('Error uploading photo:', error);
                        alert('Terjadi kesalahan saat mengupload foto');
                        
                        // Reset tombol
                        savePhotoBtn.innerHTML = 'Simpan';
                        savePhotoBtn.disabled = false;
                    });
                } else {
                    alert('Pilih foto terlebih dahulu!');
                }
            });
        }
        
        // Toggle password visibility
        const togglePasswordButtons = document.querySelectorAll('.toggle-password');
        
        if (togglePasswordButtons.length > 0) {
            togglePasswordButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const targetId = this.getAttribute('data-target');
                    const passwordInput = document.getElementById(targetId);
                    
                    if (passwordInput.type === 'password') {
                        passwordInput.type = 'text';
                        this.innerHTML = '<i class="fas fa-eye-slash"></i>';
                    } else {
                        passwordInput.type = 'password';
                        this.innerHTML = '<i class="fas fa-eye"></i>';
                    }
                });
            });
        }
        
        // Password change form validation and submission - KODE YANG DIPERBARUI
        const savePasswordBtn = document.getElementById('savePasswordBtn');
        const changePasswordForm = document.getElementById('changePasswordForm');
        const modalBody = document.querySelector('#changePasswordModal .modal-body');
        const modalFooter = document.querySelector('#changePasswordModal .modal-footer');

        if (savePasswordBtn && changePasswordForm) {
            savePasswordBtn.addEventListener('click', function() {
                // Reset semua error sebelumnya
                document.querySelectorAll('.invalid-feedback').forEach(el => {
                    el.textContent = '';
                });
                document.querySelectorAll('.form-control').forEach(el => {
                    el.classList.remove('is-invalid');
                });
                
                // Ambil data form
                const currentPassword = document.getElementById('current_password').value;
                const newPassword = document.getElementById('new_password').value;
                const confirmPassword = document.getElementById('new_password_confirmation').value;
                
                // Validasi client-side sederhana
                let isValid = true;
                
                if (!currentPassword) {
                    document.getElementById('current_password').classList.add('is-invalid');
                    document.getElementById('current_password_error').textContent = 'Password saat ini harus diisi';
                    isValid = false;
                }
                
                if (!newPassword) {
                    document.getElementById('new_password').classList.add('is-invalid');
                    document.getElementById('new_password_error').textContent = 'Password baru harus diisi';
                    isValid = false;
                } else if (newPassword.length < 6) {
                    document.getElementById('new_password').classList.add('is-invalid');
                    document.getElementById('new_password_error').textContent = 'Password baru minimal 6 karakter';
                    isValid = false;
                }
                
                if (!confirmPassword) {
                    document.getElementById('new_password_confirmation').classList.add('is-invalid');
                    document.getElementById('new_password_confirmation_error').textContent = 'Konfirmasi password harus diisi';
                    isValid = false;
                } else if (newPassword !== confirmPassword) {
                    document.getElementById('new_password_confirmation').classList.add('is-invalid');
                    document.getElementById('new_password_confirmation_error').textContent = 'Konfirmasi password tidak cocok';
                    isValid = false;
                }
                
                if (!isValid) {
                    return;
                }
                
                // Simpan konten form untuk dikembalikan jika terjadi error
                const originalFormHTML = modalBody.innerHTML;
                const originalFooterHTML = modalFooter.innerHTML;
                
                // Tampilkan loading di tengah modal
                modalBody.innerHTML = `
                    <div class="loading-container text-center py-4">
                        <div class="loading-spinner"></div>
                        <div class="loading-text mt-3">Sedang mengubah password...</div>
                    </div>
                `;
                
                // Sembunyikan tombol-tombol di footer
                modalFooter.style.display = 'none';
                
                // Kirim data dengan fetch API
                const formData = new FormData(changePasswordForm);
                
                fetch('{{ route("profil.change-password") }}', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    // Tunggu sebentar untuk efek loading (1,5 detik)
                    setTimeout(() => {
                        // Kembalikan tombol footer
                        modalFooter.style.display = '';
                        
                        if (data.success) {
                            // Tampilkan pesan sukses di modal
                            modalBody.innerHTML = `
                                <div class="text-center py-4">
                                    <div class="mb-3">
                                        <i class="fas fa-check-circle fa-4x" style="color: #27AE60;"></i>
                                    </div>
                                    <h5 class="mb-3" style="font-weight: 600;">Password Berhasil Diubah!</h5>
                                    <p class="text-muted">Password Anda telah berhasil diperbarui.</p>
                                </div>
                            `;
                            
                            // Ganti tombol footer dengan "Tutup" saja
                            modalFooter.innerHTML = `
                                <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Tutup</button>
                            `;
                            
                            // Reset form
                            changePasswordForm.reset();
                            
                            // Tutup modal setelah beberapa detik
                            setTimeout(() => {
                                const modal = bootstrap.Modal.getInstance(document.getElementById('changePasswordModal'));
                                modal.hide();
                                
                                // Opsional: Reload halaman untuk memastikan semua perubahan tampil
                                // location.reload();
                            }, 2000);
                        } else {
                            if (data.message === 'Password saat ini salah') {
                                // Kembalikan form asli
                                modalBody.innerHTML = originalFormHTML;
                                modalFooter.innerHTML = originalFooterHTML;
                                
                                // Set ulang event listener untuk toggle password
                                initTogglePassword();
                                
                                // Tambahkan pesan error pada field password saat ini
                                document.getElementById('current_password').classList.add('is-invalid');
                                document.getElementById('current_password_error').textContent = data.message;
                            } else {
                                // Tampilkan pesan error
                                modalBody.innerHTML = `
                                    <div class="text-center py-4">
                                        <div class="mb-3">
                                            <i class="fas fa-times-circle fa-4x" style="color: #FF5252;"></i>
                                        </div>
                                        <h5 class="mb-3" style="font-weight: 600;">Gagal Mengubah Password</h5>
                                        <p class="text-muted">${data.message}</p>
                                    </div>
                                `;
                                
                                // Ganti tombol footer dengan "Coba Lagi" dan "Tutup"
                                modalFooter.innerHTML = `
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                    <button type="button" class="btn btn-primary" id="tryAgainBtn">Coba Lagi</button>
                                `;
                                
                               // Tambahkan event listener untuk "Coba Lagi"
                                document.getElementById('tryAgainBtn').addEventListener('click', function() {
                                    // Kembalikan form asli
                                    modalBody.innerHTML = originalFormHTML;
                                    modalFooter.innerHTML = originalFooterHTML;
                                    
                                    // Inisialisasi kembali toggle password
                                    initTogglePassword();
                                    
                                    // Re-attach event handler untuk tombol simpan
                                    document.getElementById('savePasswordBtn').addEventListener('click', savePasswordBtnHandler);
                                });
                            }
                        }
                    }, 1500); // Tunggu 1.5 detik untuk efek loading
                })
                .catch(error => {
                    console.error('Error:', error);
                    
                    // Tunggu sebentar, lalu tampilkan pesan error
                    setTimeout(() => {
                        modalBody.innerHTML = `
                            <div class="text-center py-4">
                                <div class="mb-3">
                                    <i class="fas fa-exclamation-triangle fa-4x" style="color: #FFC107;"></i>
                                </div>
                                <h5 class="mb-3" style="font-weight: 600;">Terjadi Kesalahan</h5>
                                <p class="text-muted">Terjadi kesalahan saat mengganti password. Silakan coba lagi.</p>
                            </div>
                        `;
                        
                        // Kembalikan tombol footer
                        modalFooter.style.display = '';
                        modalFooter.innerHTML = `
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                            <button type="button" class="btn btn-primary" id="tryAgainBtn">Coba Lagi</button>
                        `;
                        
                        // Tambahkan event listener untuk "Coba Lagi"
                        document.getElementById('tryAgainBtn').addEventListener('click', function() {
                            // Kembalikan form asli
                            modalBody.innerHTML = originalFormHTML;
                            modalFooter.innerHTML = originalFooterHTML;
                            
                            // Inisialisasi kembali toggle password
                            initTogglePassword();
                            
                            // Re-attach event handler untuk tombol simpan
                            document.getElementById('savePasswordBtn').addEventListener('click', savePasswordBtnHandler);
                        });
                    }, 1500);
                });
            });
            
            // Simpan fungsi handler untuk digunakan kembali
            const savePasswordBtnHandler = savePasswordBtn.onclick;
        }
        
        // Fungsi untuk menginisialisasi kembali toggle password setelah mengembalikan form asli
        function initTogglePassword() {
            const toggleButtons = document.querySelectorAll('.toggle-password');
            
            if (toggleButtons.length > 0) {
                toggleButtons.forEach(button => {
                    button.addEventListener('click', function() {
                        const targetId = this.getAttribute('data-target');
                        const passwordInput = document.getElementById(targetId);
                        
                        if (passwordInput.type === 'password') {
                            passwordInput.type = 'text';
                            this.innerHTML = '<i class="fas fa-eye-slash"></i>';
                        } else {
                            passwordInput.type = 'password';
                            this.innerHTML = '<i class="fas fa-eye"></i>';
                        }
                    });
                });
            }
        }
    });
</script>
@endpush