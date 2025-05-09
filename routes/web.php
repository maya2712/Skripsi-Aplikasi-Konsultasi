<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DosenController;
use App\Http\Controllers\MahasiswaController;
use App\Http\Controllers\GoogleCalendarController;
use App\Http\Controllers\PilihJadwalController;
use App\Http\Controllers\MasukkanJadwalController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Auth;

// Route untuk guest (belum login)
Route::middleware(['guest'])->group(function () {
    Route::get('/', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
});

// API untuk daftar dosen
Route::get('/api/dosen', [App\Http\Controllers\Api\DosenApiController::class, 'getDosen'])
    ->name('api.dosen.list');

// Route dashboard yang memerlukan autentikasi dan mencegah kembali setelah logout
Route::middleware(['auth:mahasiswa,dosen,admin', \App\Http\Middleware\PreventBackHistory::class])->group(function () {
    
    Route::get('/dashboardpesan', function () {
        return view('pesan.dashboardpesan');
    });
    
    // Route profil - sudah diperbarui untuk menggunakan controller
    Route::get('/profil', [ProfileController::class, 'index'])->name('profil');
    
    // Route back
    Route::get('/back', function () {
        $routeStack = session()->get('routeStack', []);
        
        // Debug: Lihat isi stack
        // dd($routeStack);
        
        if (count($routeStack) >= 2) {
            // Hapus URL terakhir (URL saat ini) dari stack
            array_pop($routeStack);
            
            // Ambil URL untuk redirect (URL sebelumnya)
            $previousUrl = end($routeStack);
            
            // Hapus URL saat ini dari stack (koreksi)
            session()->put('routeStack', $routeStack);
            
            // Pastikan URL valid
            if (!empty($previousUrl)) {
                return redirect($previousUrl);
            }
        }
        
        // Fallback jika tidak ada history
        if (auth()->guard('dosen')->check()) {
            return redirect('/dashboardpesandosen');
        } else {
            return redirect('/dashboardpesanmahasiswa');
        }
    })->name('back');

});

// Route lainnya
Route::get('/profilmahasiswa', function () {
    return view('bimbingan.mahasiswa.profilmahasiswa');
});

Route::get('/gantipassword', function () {
    return view('bimbingan.mahasiswa.gantipassword');
});

Route::get('/contohdashboard', function () {
    return view('pesan.contohdashboard');
});

Route::get('/datausulanbimbingan', function () {
    return view('bimbingan.admin.datausulanbimbingan');
});

// Route untuk mahasiswa
Route::middleware(['auth:mahasiswa', 'checkRole:mahasiswa'])->group(function () {
    Route::get('/riwayatmahasiswa', function () {
        return view('bimbingan.riwayatmahasiswa');
    });

    // Routes untuk fitur pesan mahasiswa
    Route::controller(App\Http\Controllers\PesanMahasiswaController::class)->group(function () {
        // Dashboard pesan
        Route::get('/dashboardpesanmahasiswa', 'index')
            ->name('mahasiswa.dashboard.pesan');
        
        // Buat pesan baru
        Route::get('/buatpesanmahasiswa', 'create')
            ->name('mahasiswa.pesan.create');
        Route::post('/kirimpesan', 'store')
            ->name('mahasiswa.pesan.store');
        
        // Lihat detail pesan
        Route::get('/isipesanmahasiswa/{id}', 'show')
            ->name('mahasiswa.pesan.show');
        
        // Kirim balasan pesan
        Route::post('/balaspesan/{id}', 'reply')
            ->name('mahasiswa.pesan.reply');
        
        // Akhiri pesan
        Route::post('/akhiripesan/{id}', 'endChat')
            ->name('mahasiswa.pesan.end');
        
        // Riwayat pesan
        Route::get('/riwayatpesanmahasiswa', 'history')
            ->name('mahasiswa.pesan.history');
        
        // Filter pesan
        Route::get('/filterpesan', 'filter')
            ->name('mahasiswa.pesan.filter');
        
        // Pencarian pesan
        Route::get('/caripesan', 'search')
            ->name('mahasiswa.pesan.search');
        
        // FAQ Mahasiswa
        Route::get('/faqmahasiswa', 'faq')
            ->name('mahasiswa.faq');
        
        // Route Debug Pesan - BARU
        Route::get('/debug-pesan', 'debugPesan')
            ->name('mahasiswa.debug.pesan');
    });

    // Route untuk Grup Mahasiswa - BARU
    Route::get('/daftargrupmahasiswa', [MahasiswaController::class, 'getGrupMahasiswa'])->name('mahasiswa.grup.index');
    Route::get('/detailgrupmahasiswa/{id}', [MahasiswaController::class, 'getDetailGrup'])->name('mahasiswa.grup.show');

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
        Route::get('/google/connect', 'connect')->name('mahasiswa.google.connect');
        Route::get('/google/callback', 'callback')->name('mahasiswa.google.callback');
    });
});

// Route untuk dosen
Route::middleware(['auth:dosen', 'checkRole:dosen'])->group(function () {
    Route::get('/persetujuan', function () {
        return view('bimbingan.dosen.persetujuan');
    });

    Route::get('/riwayatdosen', function () {
        return view('bimbingan.riwayatdosen');
    });

    Route::get('/faqdosen', function () {
        return view('pesan.dosen.faq_dosen');
    });

    Route::get('/terimausulanbimbingan', function () {
        return view('bimbingan.dosen.terimausulanbimbingan');
    });

    Route::get('/editusulan', function () {
        return view('bimbingan.dosen.editusulan');
    });

    // Routes untuk fitur pesan dosen
    Route::controller(App\Http\Controllers\PesanDosenController::class)->group(function () {
        // Dashboard pesan
        Route::get('/dashboardpesandosen', 'index')
            ->name('dosen.dashboard.pesan');
        
        // Buat pesan baru
        Route::get('/buatpesandosen', 'create')
            ->name('dosen.pesan.create');
        Route::post('/kirimpesandosen', 'store')
            ->name('dosen.pesan.store');
        
        // Lihat detail pesan
        Route::get('/isipesandosen/{id}', 'show')
            ->name('dosen.pesan.show');
        
        // Kirim balasan pesan
        Route::post('/balaspesandosen/{id}', 'reply')
            ->name('dosen.pesan.reply');
        
        // Bookmark pesan
        Route::post('/bookmarkpesan/{id}', 'bookmark')
            ->name('dosen.pesan.bookmark');
        
        // Riwayat pesan
        Route::get('/riwayatpesandosen', 'history')
            ->name('dosen.pesan.history');
        
        // Filter pesan
        Route::get('/filterpesandosen', 'filter')
            ->name('dosen.pesan.filter');
        
        // Pencarian pesan
        Route::get('/caripesandosen', 'search')
            ->name('dosen.pesan.search');
    });

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
    
    // Route untuk fitur grup
    Route::get('/daftargrup', [App\Http\Controllers\GrupController::class, 'index'])->name('dosen.grup.index');
    Route::get('/buatgrupbaru', [App\Http\Controllers\GrupController::class, 'create'])->name('dosen.grup.create');
    Route::post('/simpangrup', [App\Http\Controllers\GrupController::class, 'store'])->name('dosen.grup.store');
    Route::get('/detailgrup/{id}', [App\Http\Controllers\GrupController::class, 'show'])->name('dosen.grup.show');
    Route::delete('/hapusgrup/{id}', [App\Http\Controllers\GrupController::class, 'destroy'])->name('dosen.grup.destroy');
    Route::post('/tambah-anggota-grup/{id}', [App\Http\Controllers\GrupController::class, 'addMember'])->name('dosen.grup.addMember');
    Route::delete('/grupanggota/hapus/{id}/{mahasiswa_id}', [App\Http\Controllers\GrupController::class, 'hapusAnggota']);
});

// Pastikan routes sudah ada di web.php dalam grup admin
Route::middleware(['auth:admin', \App\Http\Middleware\PreventBackHistory::class])->prefix('admin')->group(function () {
    // Route yang sudah ada
    Route::get('/dashboard', [App\Http\Controllers\AdminController::class, 'dashboard'])->name('admin.dashboard');
    
    // Route untuk manajemen user
    Route::get('/managementuser_dosen', [App\Http\Controllers\AdminUserController::class, 'managementDosen'])->name('admin.managementuser_dosen');
    Route::get('/managementuser_mahasiswa', [App\Http\Controllers\AdminUserController::class, 'managementMahasiswa'])->name('admin.managementuser_mahasiswa');
    
    Route::get('/tambahdosen', [App\Http\Controllers\AdminUserController::class, 'tambahDosen'])->name('admin.tambahdosen');
    Route::post('/store-dosen', [App\Http\Controllers\AdminUserController::class, 'storeDosen'])->name('admin.store-dosen');
    
    Route::get('/tambahmahasiswa', [App\Http\Controllers\AdminUserController::class, 'tambahMahasiswa'])->name('admin.tambahmahasiswa');
    Route::post('/store-mahasiswa', [App\Http\Controllers\AdminUserController::class, 'storeMahasiswa'])->name('admin.store-mahasiswa');
    
    Route::delete('/delete-dosen/{nip}', [App\Http\Controllers\AdminUserController::class, 'deleteDosen'])->name('admin.delete-dosen');
    Route::delete('/delete-mahasiswa/{nim}', [App\Http\Controllers\AdminUserController::class, 'deleteMahasiswa'])->name('admin.delete-mahasiswa');
    Route::delete('/delete-multiple-mahasiswa', [App\Http\Controllers\AdminUserController::class, 'deleteMultipleMahasiswa'])->name('admin.delete-multiple-mahasiswa');

    Route::get('/edit-dosen/{nip}', [App\Http\Controllers\AdminUserController::class, 'editDosen'])->name('admin.edit-dosen');
    Route::put('/update-dosen/{nip}', [App\Http\Controllers\AdminUserController::class, 'updateDosen'])->name('admin.update-dosen');

    Route::get('/edit-mahasiswa/{nim}', [App\Http\Controllers\AdminUserController::class, 'editMahasiswa'])->name('admin.edit-mahasiswa');
    Route::put('/update-mahasiswa/{nim}', [App\Http\Controllers\AdminUserController::class, 'updateMahasiswa'])->name('admin.update-mahasiswa');

    Route::get('/reset-password-dosen/{nip}', [App\Http\Controllers\AdminUserController::class, 'showResetPassword'])->name('admin.reset-password-dosen');
    Route::post('/reset-password-dosen/{nip}', [App\Http\Controllers\AdminUserController::class, 'resetPassword'])->name('admin.reset-password-dosen.post');
});

// Logout route
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');