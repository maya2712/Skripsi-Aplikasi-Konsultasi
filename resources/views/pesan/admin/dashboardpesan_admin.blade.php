@extends('layouts.app')

@section('title', 'Dashboard Admin')

@push('styles')
<style>
    :root {
        --bs-primary: #1a73e8;
        --bs-danger: #FF5252;
        --bs-success: #27AE60;
        --bs-warning: #FFC107;
        --bs-info: #00BCD4;
    }
    
    body {
        background-color: #F5F7FA;
        font-size: 13px;
    }

    .main-content {
        padding-top: 20px; 
        padding-bottom: 20px; 
    }
    
    .btn-custom-primary {
        background: var(--bs-primary);
        color: white;
    }
    
    .btn-custom-primary:hover {
        background: #1557b0;
        color: white;
    }

    .sidebar {
        background: #ffffff;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        position: sticky;
        top: 20px; 
        max-height: calc(100vh - 100px);
    }

    .sidebar-menu {
        padding: 15px;
    }
    
    .sidebar-menu .nav-link {
        color: #546E7A;
        border-radius: 0.5rem;
        margin-bottom: 8px;
        padding: 10px 15px;
        transition: all 0.3s ease;
        cursor: pointer;
        position: relative;
    }
    
    .sidebar-menu .nav-link.active {
        background: #E3F2FD;
        color: var(--bs-primary);
    }
    
    .sidebar-menu .nav-link:hover:not(.active) {
        background: #f8f9fa;
    }

    .badge-notification {
        background: var(--bs-danger);
    }
    
    .custom-container {
        max-width: 1400px;
        margin: 0 auto;
        padding: 0 15px;
    }
    
    .card {
        margin-bottom: 20px;
        border-radius: 10px;
        box-shadow: 0 0 5px rgba(0,0,0,0.1);
        border: none;
    }
    
    .stats-card {
        transition: all 0.3s ease;
    }
    
    .stats-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1);
    }
    
    .stats-cards .card {
        height: 100%;
        margin-bottom: 0;
    }
    
    .welcome-banner {
        background: linear-gradient(135deg, #1a73e8, #6c49e3);
        color: white;
        border-radius: 10px;
        padding: 30px;
        margin-bottom: 25px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        position: relative;
        overflow: hidden;
    }
    
    .welcome-banner::before {
        content: '';
        position: absolute;
        top: 0;
        right: 0;
        width: 200px;
        height: 100%;
        background: url('https://via.placeholder.com/200') no-repeat;
        background-size: cover;
        opacity: 0.1;
    }
    
    .welcome-banner h4 {
        font-weight: 600;
        margin-bottom: 15px;
        font-size: 24px;
    }
    
    .chart-container {
        position: relative;
        height: 280px;
        margin-top: 15px;
    }
    
    .chart-title {
        font-size: 16px;
        font-weight: 600;
        margin-bottom: 15px;
        color: #333;
    }
    
    .card-header {
        background-color: transparent;
        border-bottom: 1px solid rgba(0,0,0,0.05);
        padding: 15px 20px;
    }
    
    .card-header h5 {
        margin-bottom: 0;
        font-size: 16px;
        font-weight: 600;
    }
    
    .legends {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        margin-top: 15px;
    }
    
    .legend-item {
        display: flex;
        align-items: center;
        margin-right: 15px;
    }
    
    .legend-color {
        width: 12px;
        height: 12px;
        border-radius: 2px;
        margin-right: 5px;
    }
</style>
@endpush

@section('content')
<div class="main-content">
    <div class="custom-container">
        <div class="row g-4">
            <!-- Sidebar - Tanpa tombol -->
            <div class="col-md-3">
                <div class="sidebar">
                    <div class="sidebar-menu">
                        <div class="nav flex-column">
                            <a href="{{ route('admin.dashboard') }}" class="nav-link active">
                                <i class="fas fa-tachometer-alt me-2"></i>Dashboard Admin
                            </a>
                            <a href="#" class="nav-link" id="userManagementToggle">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span><i class="fas fa-users-cog me-2"></i>Manajemen User</span>
                                    <i class="fas fa-chevron-down" id="userManagementIcon"></i>
                                </div>
                            </a>
                            <div class="collapse" id="userManagementSubmenu">
                                <div class="ps-3">
                                    <a href="{{ url('/admin/managementuser_dosen') }}" class="nav-link">
                                        <i class="fas fa-chalkboard-teacher me-2"></i>Dosen
                                    </a>                                    
                                    <a href="{{ url('/admin/managementuser_mahasiswa') }}" class="nav-link">
                                        <i class="fas fa-user-graduate me-2"></i>Mahasiswa
                                    </a>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Content - Redesigned dengan grafik -->
            <div class="col-md-9">
                <!-- Notifikasi sukses dengan desain modern -->
                @if(session('success'))
                <div class="alert mb-4 auto-dismiss-alert" role="alert" style="background-color: rgba(39, 174, 96, 0.1); border-left: 4px solid #27AE60; color: #2E7D32; border-radius: 4px; box-shadow: 0 2px 5px rgba(0,0,0,0.05); padding: 12px; position: relative;">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-check-circle me-2" style="font-size: 18px; color: #27AE60;"></i>
                        <div><strong>Berhasil!</strong> {{ session('success') }}</div>
                        <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close" style="font-size: 10px; padding: 8px;"></button>
                    </div>
                </div>
                @endif
                
                <!-- Welcome Banner yang lebih besar dan menarik (tanpa tombol) -->
                <div class="welcome-banner">
                    <h4>Selamat Datang, Admin!</h4>
                    <p class="mb-0">Berikut statistik pengguna di sistem Anda saat ini</p>
                </div>
                
                <!-- Stats Cards - Total Dosen, Total Mahasiswa -->
                <div class="stats-cards row g-3 mb-4">
                    <div class="col-md-6">
                        <div class="card h-100 stats-card">
                            <div class="card-body d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="text-muted mb-1">Total Dosen</h6>
                                    <h3 class="mb-0 fs-4">{{ $totalDosen }}</h3>
                                </div>
                                <div class="bg-primary bg-opacity-10 p-3 rounded">
                                    <i class="fas fa-chalkboard-teacher text-primary"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card h-100 stats-card">
                            <div class="card-body d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="text-muted mb-1">Total Mahasiswa</h6>
                                    <h3 class="mb-0 fs-4">{{ $totalMahasiswa }}</h3>
                                </div>
                                <div class="bg-success bg-opacity-10 p-3 rounded">
                                    <i class="fas fa-user-graduate text-success"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Grafik Section - Hanya 2 grafik berdasarkan angkatan -->
                <div class="row g-3">
                    <!-- Grafik Distribusi Mahasiswa per Angkatan (PIE CHART) -->
                    <div class="col-md-6">
                        <div class="card h-100">
                            <div class="card-header">
                                <h5><i class="fas fa-chart-pie me-2 text-primary"></i> Distribusi Mahasiswa per Angkatan</h5>
                            </div>
                            <div class="card-body">
                                <div class="chart-container">
                                    <canvas id="mahasiswaDistribusiChart"></canvas>
                                </div>
                                <div class="legends" id="pieChartLegends">
                                    <!-- Legends will be generated dynamically by JavaScript -->
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Grafik Distribusi Mahasiswa per Angkatan -->
                    <div class="col-md-6">
                        <div class="card h-100">
                            <div class="card-header">
                                <h5><i class="fas fa-chart-bar me-2 text-success"></i> Mahasiswa per Angkatan</h5>
                            </div>
                            <div class="card-body">
                                <div class="chart-container">
                                    <canvas id="mahasiswaAngkatanChart"></canvas>
                                </div>
                                <div class="legends" id="barChartLegends">
                                    <!-- Legends will be generated dynamically by JavaScript -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto dismiss alerts after 5 seconds
    const autoDismissAlerts = document.querySelectorAll('.auto-dismiss-alert');
    if (autoDismissAlerts.length > 0) {
        autoDismissAlerts.forEach(function(alert) {
            setTimeout(function() {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            }, 5000); // Menghilang setelah 5 detik
        });
    }
    
    // Toggle dropdown for user management
    const userManagementToggle = document.getElementById('userManagementToggle');
    const userManagementSubmenu = document.getElementById('userManagementSubmenu');
    const userManagementIcon = document.getElementById('userManagementIcon');
    
    if (userManagementToggle && userManagementSubmenu && userManagementIcon) {
        userManagementToggle.addEventListener('click', function() {
            // Toggle the collapse
            const bsCollapse = new bootstrap.Collapse(userManagementSubmenu, {
                toggle: true
            });
            
            // Toggle the icon
            userManagementIcon.classList.toggle('fa-chevron-up');
            userManagementIcon.classList.toggle('fa-chevron-down');
        });
    }
    
    // Inisialisasi Chart.js menggunakan data dari controller
    const angkatanLabels = @json($angkatanLabels);
    const angkatanData = @json($angkatanData);
    const angkatanBackgroundColors = @json($angkatanBackgroundColors);
    const barBackgroundColors = @json($barBackgroundColors);
    
    // Pie Chart untuk Distribusi Mahasiswa per Angkatan
    const mahasiswaDistribusiCtx = document.getElementById('mahasiswaDistribusiChart');
    if (mahasiswaDistribusiCtx) {
        const pieChart = new Chart(mahasiswaDistribusiCtx, {
            type: 'pie',
            data: {
                labels: angkatanLabels,
                datasets: [{
                    label: 'Jumlah Mahasiswa',
                    data: angkatanData,
                    backgroundColor: angkatanBackgroundColors,
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const label = context.label || '';
                                const value = context.formattedValue;
                                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                const percentage = Math.round((context.raw / total) * 100);
                                return `${label}: ${value} (${percentage}%)`;
                            }
                        }
                    }
                }
            }
        });
        
        // Generate legends for pie chart
        const pieChartLegends = document.getElementById('pieChartLegends');
        if (pieChartLegends) {
            angkatanLabels.forEach((label, index) => {
                const legendItem = document.createElement('div');
                legendItem.className = 'legend-item';
                
                const legendColor = document.createElement('div');
                legendColor.className = 'legend-color';
                legendColor.style.backgroundColor = angkatanBackgroundColors[index];
                
                const legendText = document.createElement('span');
                legendText.textContent = label;
                
                legendItem.appendChild(legendColor);
                legendItem.appendChild(legendText);
                pieChartLegends.appendChild(legendItem);
            });
        }
    }
    
    // Bar Chart untuk Mahasiswa per Angkatan
    const mahasiswaAngkatanCtx = document.getElementById('mahasiswaAngkatanChart');
    if (mahasiswaAngkatanCtx) {
        const barChart = new Chart(mahasiswaAngkatanCtx, {
            type: 'bar',
            data: {
                labels: angkatanLabels,
                datasets: [{
                    label: 'Jumlah Mahasiswa',
                    data: angkatanData,
                    backgroundColor: barBackgroundColors.slice(0, angkatanLabels.length),
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            display: true,
                            drawBorder: false
                        },
                        ticks: {
                            precision: 0
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });
        
        // Generate legends for bar chart
        const barChartLegends = document.getElementById('barChartLegends');
        if (barChartLegends) {
            angkatanLabels.forEach((label, index) => {
                const legendItem = document.createElement('div');
                legendItem.className = 'legend-item';
                
                const legendColor = document.createElement('div');
                legendColor.className = 'legend-color';
                legendColor.style.backgroundColor = barBackgroundColors[index];
                
                const legendText = document.createElement('span');
                legendText.textContent = label;
                
                legendItem.appendChild(legendColor);
                legendItem.appendChild(legendText);
                barChartLegends.appendChild(legendItem);
            });
        }
    }
});
</script>
@endpush