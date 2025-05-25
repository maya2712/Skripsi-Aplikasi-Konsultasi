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
        font-size: 12px;
    }

    .main-content {
        padding-top: 10px; 
        padding-bottom: 10px; 
    }
    
    .btn-custom-primary {
        background: var(--bs-primary);
        color: white;
    }
    
    .btn-custom-primary:hover {
        background: #1557b0;
        color: white;
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
        margin-bottom: 12px;
        border-radius: 6px;
        box-shadow: 0 0 3px rgba(0,0,0,0.08);
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
        border-radius: 6px;
        padding: 15px;
        margin-bottom: 15px;
        box-shadow: 0 3px 10px rgba(0,0,0,0.1);
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
        margin-bottom: 5px;
        font-size: 22px; /* Dikurangi dari 24px menjadi 22px */
    }
    
    .welcome-banner p {
        font-size: 14px; /* Dikurangi dari 16px menjadi 14px */
    }
    
    .chart-container {
        position: relative;
        height: 200px;
        margin-top: 8px;
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
        padding: 10px 12px;
    }
    
    .card-header h5 {
        margin-bottom: 0;
        font-size: 12px;
        font-weight: 600;
    }
    
    /* Memperbesar font untuk stats cards */
    .stats-card .card-body h6 {
        font-size: 12px !important; /* Dikurangi dari 14px menjadi 12px */
        font-weight: 500;
    }
    
    .stats-card .card-body h3 {
        font-size: 26px !important; /* Dikurangi dari 28px menjadi 26px */
        font-weight: 700;
    }
    
    .legends {
        display: flex;
        flex-wrap: wrap;
        gap: 6px;
        margin-top: 8px;
        font-size: 10px;
    }
    
    .legend-item {
        display: flex;
        align-items: center;
        margin-right: 15px;
    }
    
    .legend-color {
        width: 10px;
        height: 10px;
        border-radius: 2px;
        margin-right: 4px;
    }
</style>
@endpush

@section('content')
<div class="main-content">
    <div class="custom-container">
        <div class="row">
            <!-- Main Content - Full width tanpa sidebar -->
            <div class="col-12">
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
                <div class="stats-cards row g-2 mb-2">
                    <div class="col-md-6">
                        <div class="card h-100 stats-card">
                            <div class="card-body d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="text-muted mb-1">Total Dosen</h6>
                                    <h3 class="mb-0">{{ $totalDosen }}</h3>
                                </div>
                                <div class="bg-primary bg-opacity-10 p-2 rounded">
                                    <i class="fas fa-chalkboard-teacher text-primary" style="font-size: 16px;"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card h-100 stats-card">
                            <div class="card-body d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="text-muted mb-1">Total Mahasiswa</h6>
                                    <h3 class="mb-0">{{ $totalMahasiswa }}</h3>
                                </div>
                                <div class="bg-success bg-opacity-10 p-2 rounded">
                                    <i class="fas fa-user-graduate text-success" style="font-size: 16px;"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Grafik Section - Hanya 2 grafik berdasarkan angkatan -->
                <div class="row g-2">
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