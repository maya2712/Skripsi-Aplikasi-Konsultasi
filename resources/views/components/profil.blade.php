@extends('layouts.app')

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
        background-color: #f1f1f1; /* Background untuk foto default */
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
    
    .btn-forgot-password {
        color: #004AAD;
        text-decoration: none;
        font-weight: 500;
        font-size: 14px;
        display: inline-block;
        margin-top: 10px;
    }
    
    .btn-forgot-password:hover {
        text-decoration: underline;
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
</style>
@endpush

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="profile-wrapper">
                <!-- Tombol Kembali di dalam container -->
                <a href="{{ route('mahasiswa.dashboard.pesan') }}" class="back-button">
                    <i class="fas fa-arrow-left"></i>
                </a>
                
                <!-- Profile Header -->
                <div class="profile-header">
                    <div class="profile-img-container">
                        <!-- Ikon default untuk foto profil yang belum diset -->
                        <div class="default-profile-img" id="defaultProfileImg">
                            <i class="fas fa-user"></i>
                        </div>
                        <img src="" alt="Foto Profil" class="profile-img" id="profileImage" style="display: none;">
                        <div class="edit-photo-icon" data-bs-toggle="modal" data-bs-target="#editPhotoModal">
                            <i class="fas fa-camera"></i>
                        </div>
                    </div>
                    <h3>Desi Maya Sari</h3>
                    <p>Mahasiswa</p>
                </div>
                
                <!-- Profile Content -->
                <div class="profile-content">
                    <h4 class="section-title">Informasi Pribadi</h4>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="info-group">
                                <div class="info-label">Nama Lengkap</div>
                                <div class="info-value">Desi Maya Sari</div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="info-group">
                                <div class="info-label">NIM</div>
                                <div class="info-value">2107110665</div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="info-group">
                                <div class="info-label">Program Studi</div>
                                <div class="info-value">Teknik Informatika</div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="info-group">
                                <div class="info-label">Fakultas</div>
                                <div class="info-value">Teknik</div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="info-group">
                                <div class="info-label">Angkatan</div>
                                <div class="info-value">2021</div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="info-group">
                                <div class="info-label">Email</div>
                                <div class="info-value">desi.mayasari@students.unri.ac.id</div>
                            </div>
                        </div>
                    </div>
                    
                    <h4 class="section-title mt-4">Pengaturan Akun</h4>
                    
                    <div class="row">
                        <div class="col-md-12">
                            <a href="#" class="btn-forgot-password" data-bs-toggle="modal" data-bs-target="#forgotPasswordModal">
                                <i class="fas fa-lock me-1"></i> Lupa Kata Sandi?
                            </a>
                        </div>
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
                <form id="uploadPhotoForm">
                    <div class="upload-area" id="uploadArea">
                        <div class="upload-icon">
                            <i class="fas fa-cloud-upload-alt"></i>
                        </div>
                        <p>Klik atau seret foto ke sini</p>
                        <small class="text-muted">Format yang didukung: JPG, PNG</small>
                        <input type="file" id="photoInput" class="d-none" accept="image/jpeg, image/png">
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

<!-- Modal Lupa Kata Sandi -->
<div class="modal fade" id="forgotPasswordModal" tabindex="-1" aria-labelledby="forgotPasswordModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="forgotPasswordModalLabel">Lupa Kata Sandi?</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="text-center mb-4">
                    <i class="fas fa-exclamation-circle text-warning" style="font-size: 48px;"></i>
                    <h5 class="mt-3">Anda tidak dapat mengubah kata sandi secara mandiri</h5>
                    <p class="text-muted">
                        Untuk mengubah kata sandi, silakan hubungi administrator sistem.
                    </p>
                </div>
                
                <div class="alert alert-info">
                    <h6><i class="fas fa-info-circle me-2"></i> Informasi Administrator:</h6>
                    <p class="mb-1">Email: admin@unri.ac.id</p>
                    <p class="mb-1">Telepon: (0761) 123456</p>
                    <p class="mb-0">Lokasi: Gedung Rektorat Lt. 2, Ruang TIK</p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Foto profil upload handling
        const uploadArea = document.getElementById('uploadArea');
        const photoInput = document.getElementById('photoInput');
        const photoPreview = document.getElementById('photoPreview');
        const previewContainer = document.getElementById('previewContainer');
        const savePhotoBtn = document.getElementById('savePhotoBtn');
        const profileImage = document.getElementById('profileImage');
        const defaultProfileImg = document.getElementById('defaultProfileImg');
        
        // Click on upload area to trigger file input
        uploadArea.addEventListener('click', function() {
            photoInput.click();
        });
        
        // Handle file selection
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
        
        // Drag and drop functionality
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
        
        // Save photo functionality
        savePhotoBtn.addEventListener('click', function() {
            if (photoInput.files && photoInput.files[0]) {
                // In a real application, here you would upload the file to the server
                // For this prototype, we'll just update the profile image with the preview
                profileImage.src = photoPreview.src;
                profileImage.style.display = 'block';
                defaultProfileImg.style.display = 'none';
                
                // Close the modal
                const modal = bootstrap.Modal.getInstance(document.getElementById('editPhotoModal'));
                modal.hide();
                
                // Reset the modal for next time
                setTimeout(() => {
                    previewContainer.style.display = 'none';
                    uploadArea.style.display = 'block';
                    photoInput.value = ''; // Clear the file input
                }, 300);
            } else {
                alert('Pilih foto terlebih dahulu!');
            }
        });
    });
</script>
@endpush