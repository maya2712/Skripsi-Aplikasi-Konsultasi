<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DosenController;
use App\Http\Controllers\MahasiswaController;
use App\Http\Controllers\GoogleCalendarController;
use App\Http\Controllers\PilihJadwalController;
use App\Http\Controllers\MasukkanJadwalController;

// Route untuk guest (belum login)
Route::middleware(['guest'])->group(function () {
    Route::get('/', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
});

// Route untuk pesanan
Route::get('/dashboardpesanmahasiswa', function () {
    return view('pesan.mahasiswa.dashboardpesanmahasiswa');
});

Route::get('/buatpesan', function () {
    return view('pesan.mahasiswa.buatpesan');
});

Route::get('/isipesan', function () {
    return view('pesan.mahasiswa.isipesan');
});

Route::get('/dashboardpesan', function () {
    return view('pesan.dashboardpesan');
});

Route::get('/dashboardpesandosen', function () {
    return view('pesan.dosen.dashboardpesandosen');
});

Route::get('/buatpesandosen', function () {
    return view('pesan.dosen.buatpesandosen');
});

Route::get('/isipesandosen', function () {
    return view('pesan.dosen.isipesandosen');
});

Route::get('/profilmahasiswa', function(){
    return view('bimbingan.mahasiswa.profilmahasiswa');
  });

  Route::get('/gantipassword', function(){
    return view('bimbingan.mahasiswa.gantipassword');
  });

Route::get('/contohdashboard', function(){
    return view('pesan.contohdashboard');
});

Route::get('/datausulanbimbingan', function(){
    return view('bimbingan.admin.datausulanbimbingan');
});

// Route untuk mahasiswa
Route::middleware(['auth:mahasiswa', 'checkRole:mahasiswa'])->group(function () {
    Route::get('/riwayatmahasiswa', function(){ return view('bimbingan.riwayatmahasiswa'); });

    Route::controller(MahasiswaController::class)->group(function () {
        Route::get('/usulanbimbingan', 'index')->name('mahasiswa.usulanbimbingan');
        Route::get('/aksiInformasi/{id}', 'getDetailBimbingan')->name('mahasiswa.aksiInformasi');
        Route::get('/detaildaftar/{nip}', 'getDetailDaftar')->name('mahasiswa.detaildaftar');
        Route::get('/load-usulan', 'getUsulanBimbingan')->name('mahasiswa.load.usulan');
        Route::get('/load-jadwal', 'getDaftarDosen')->name('mahasiswa.load.jadwal');
        Route::get('/load-riwayat', 'getRiwayatBimbingan')->name('mahasiswa.load.riwayat');
    });

    // Bimbingan routes
    Route::controller(PilihJadwalController::class)->prefix('pilihjadwal')->group(function () {
        Route::get('/', 'index')->name('pilihjadwal.index');
        Route::post('/store', 'store')->name('pilihjadwal.store');
        Route::get('/available', 'getAvailableJadwal')->name('pilihjadwal.available');
        Route::get('/check', 'checkAvailability')->name('pilihjadwal.check');
        Route::post('/create-event/{usulanId}', 'createGoogleCalendarEvent')->name('pilihjadwal.create-event');
    });
    
    Route::controller(GoogleCalendarController::class)->prefix('mahasiswa')->group(function () {
        Route::get('/google/connect','connect')->name('mahasiswa.google.connect');
        Route::get('/google/callback','callback')->name('mahasiswa.google.callback');
    });
});

// Route untuk dosen
Route::middleware(['auth:dosen', 'checkRole:dosen'])->group(function () {
    // Route view biasa
    Route::get('/persetujuan', function() { return view('bimbingan.dosen.persetujuan'); })->name('dosen.persetujuan');
    Route::get('/riwayatdosen', function(){ return view('bimbingan.riwayatdosen'); });
    Route::get('/terimausulanbimbingan', function(){ return view('bimbingan.dosen.terimausulanbimbingan'); });
    Route::get('/editusulan', function(){ return view('bimbingan.dosen.editusulan'); });

    // Jadwal routes
    Route::controller(MasukkanJadwalController::class)->prefix('masukkanjadwal')->group(function () {
        Route::get('/', 'index')->name('dosen.jadwal.index');
        Route::post('/store', 'store')->name('dosen.jadwal.store');
        Route::delete('/{eventId}', 'destroy')->name('dosen.jadwal.destroy');
    });

    Route::controller(GoogleCalendarController::class)->prefix('dosen')->group(function () {
        Route::get('/google/connect', 'connect')->name('dosen.google.connect');
        Route::get('/google/events', 'getEvents')->name('dosen.google.events');
        Route::get('/google/callback', 'callback')->name('dosen.google.callback');
    });
});

// Logout route
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');