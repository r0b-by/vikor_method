<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\Request;

use App\Http\Controllers\HomeController;
use App\Http\Controllers\HasilController;
use App\Http\Controllers\criteria_controller;
use App\Http\Controllers\AlternatifController;
use App\Http\Controllers\PenilaianController;
use App\Http\Controllers\HitungController;
use App\Http\Controllers\UserController;

// ============================================================
// Autentikasi Laravel (termasuk verifikasi email)
// ============================================================
Auth::routes(['verify' => true]);

// ============================================================
// Rute Umum
// ============================================================

// Akses root diarahkan ke halaman welcome
Route::get('/', function () {
    return view('welcome');
});

// /home dialihkan ke dashboard setelah login
Route::get('/home', function () {
    return redirect()->route('dashboard');
});

// Logout manual (dengan invalidasi session dan regenerasi token)
Route::post('/logout', function (Request $request) {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect('/');
})->name('logout');

// Halaman khusus untuk pendaftaran yang sedang menunggu konfirmasi
Route::get('/registration-pending', function () {
    return view('auth.registration-pending');
})->name('registration.pending');


// ============================================================
// Rute Terproteksi (Hanya untuk user yang login & verified & aktif)
// ============================================================
// Middleware 'check.status' ditambahkan di sini untuk memeriksa apakah user aktif atau pending
Route::middleware(['auth', 'verified', 'check.status'])->group(function () {

    // Dashboard (Akses untuk semua peran terautentikasi dan terverifikasi & aktif)
    Route::get('/dashboard', [HomeController::class, 'index'])->name('dashboard');

    // Halaman Pengaturan (Akses untuk semua peran terautentikasi dan terverifikasi & aktif)
    Route::view('/setting', 'dashboard.layouts.setting')->name('setting');

    // Hasil VIKOR (Dapat diakses oleh semua peran terautentikasi & aktif)
    Route::get('/hasil', [HasilController::class, 'index'])->name('hasil.index');

    // Rute Edit Profil (diakses oleh user sendiri atau admin)
    // Rute ini harus berada di luar middleware 'role:admin' atau 'role:guru|siswa'
    // karena perlu diakses oleh admin DAN pengguna biasa (guru/siswa) untuk mengedit profil mereka sendiri.
    Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
    // Rute untuk guru/siswa mengajukan perubahan profil (memanggil updateProfile di UserController)
    Route::put('/users/{user}/update-profile', [UserController::class, 'updateProfile'])->name('users.updateProfile');


    // ============================================================
    // Rute Khusus Admin
    // ============================================================
    Route::middleware(['role:admin'])->group(function () {
        // Master Data Kriteria (CRUD hanya Admin)
        Route::resource('criteria', criteria_controller::class);
        
        // Cetak PDF Hasil VIKOR (hanya Admin)
        Route::get('/hasil/cetak', [HasilController::class, 'cetak'])->name('hasil.cetak');

        // Manajemen Pengguna (CRUD Users) - Admin memiliki kontrol penuh
        // Kecuali 'edit' dan 'update' yang ditangani terpisah oleh alur konfirmasi profil atau rute umum
        Route::resource('users', UserController::class)->except(['create', 'store', 'edit', 'update']);
        Route::get('/user-management', [UserController::class, 'index'])->name('user.management');

        // Rute untuk Pendaftaran Menunggu Konfirmasi (hanya Admin)
        Route::get('/admin/pending-registrations', [UserController::class, 'pendingRegistrations'])->name('admin.pending-registrations');
        Route::post('/admin/approve-registration/{user}', [UserController::class, 'approveRegistration'])->name('admin.approve-registration');
        Route::post('/admin/reject-registration/{user}', [UserController::class, 'rejectRegistration'])->name('admin.reject-registration');

        // Rute untuk Perubahan Profil Menunggu Konfirmasi (hanya Admin)
        Route::get('/admin/pending-profile-updates', [UserController::class, 'pendingProfileUpdates'])->name('admin.pending-profile-updates');
        Route::post('/admin/approve-profile-update/{pendingUpdate}', [UserController::class, 'approveProfileUpdate'])->name('admin.approve-profile-update');
        Route::post('/admin/reject-profile-update/{pendingUpdate}', [UserController::class, 'rejectProfileUpdate'])->name('admin.reject-profile-update');
    });

    // ============================================================
    // Rute Khusus Guru (dan Admin)
    // ============================================================
    Route::middleware(['role:admin|guru'])->group(function () {
        // Data Alternatif (CRUD oleh Admin & Guru)
        Route::resource('alternatif', AlternatifController::class);
        
        // Matriks Penilaian (CRUD oleh Admin & Guru)
        Route::resource('penilaian', PenilaianController::class)->except(['create', 'show', 'edit', 'destroy']);
        Route::post('/penilaian/store-or-update', [PenilaianController::class, 'storeOrUpdate'])->name('penilaian.storeOrUpdate');
        
        // Proses VIKOR (oleh Admin & Guru)
        Route::prefix('hitung')->group(function () {
            Route::get('/', [HitungController::class, 'index'])->name('hitung.index');
            Route::post('/simpan', [HitungController::class, 'simpan'])->name('hitung.simpan');
            Route::get('/normalisasi', [HitungController::class, 'tampilNormalisasi'])->name('hitung.normalisasi');
            Route::get('/normalisasiterbobot', [HitungController::class, 'tampilNormalisasiTerbobot'])->name('hitung.normalisasiterbobot');
            Route::get('/selisihideal', [HitungController::class, 'tampilSelisihIdeal'])->name('hitung.selisihideal');
            Route::get('/matriks', [HitungController::class, 'tampilMatriks'])->name('hitung.matriks');
            Route::get('/utility', [HitungController::class, 'tampilUtility'])->name('hitung.utility');
            Route::get('/kompromi', [HitungController::class, 'tampilKompromi'])->name('hitung.kompromi');
            Route::get('/ranking', [HitungController::class, 'tampilRanking'])->name('hitung.ranking');
        });
    });

    // ============================================================
    // Rute Khusus Siswa
    // ============================================================
    // Saat ini, siswa hanya mengakses Hasil VIKOR melalui rute umum di atas
    // dan rute edit/update profile mereka sendiri.
});

