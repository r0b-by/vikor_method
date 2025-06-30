<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\Request;

use App\Http\Controllers\HomeController;
use App\Http\Controllers\HasilController; // Make sure HasilController is imported
use App\Http\Controllers\criteria_controller;
use App\Http\Controllers\AlternatifController;
use App\Http\Controllers\PenilaianController;
use App\Http\Controllers\SettingController;
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


// ============================================================\
// Rute Setelah Autentikasi (dilindungi oleh middleware 'auth')
// ============================================================\
Route::middleware(['auth', 'check.user.status'])->group(function () {
    // Dashboard, diakses setelah login
    Route::get('/dashboard', [HomeController::class, 'index'])->name('dashboard');
    Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::patch('/users/{user}', [UserController::class, 'update'])->name('user.update');

    // Manajemen Pengguna (hanya Admin)
    Route::prefix('users')->middleware('role:admin')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('user.management');
        Route::get('/users', [UserController::class, 'index'])->name('user.management');
        Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
        // routes/web.php
        Route::patch('/users/{user}', [UserController::class, 'update'])->name('user.update');
        Route::get('/pending-registrations', [UserController::class, 'pendingRegistrations'])->name('admin.pending-registrations');
        Route::post('/approve-registration/{user}', [UserController::class, 'approveRegistration'])->name('admin.approve-registration');
        Route::post('/reject-registration/{user}', [UserController::class, 'rejectRegistration'])->name('admin.reject-registration');
        Route::get('/pending-profile-updates', [UserController::class, 'pendingProfileUpdates'])->name('admin.pending-profile-updates');
        Route::post('/approve-profile-update/{pendingUpdate}', [UserController::class, 'approveProfileUpdate'])->name('admin.approve-profile-update');
        Route::post('/reject-profile-update/{pendingUpdate}', [UserController::class, 'rejectProfileUpdate'])->name('admin.reject-profile-update');
    });

    // Edit dan Update Profil (User yang sedang login)
    Route::get('/profile/edit', [UserController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [UserController::class, 'updateProfile'])->name('profile.update');
    Route::get('/setting', [SettingController::class, 'index'])->name('setting');
    Route::put('/settings', [SettingController::class, 'update'])->name('setting.update');


    // ============================================================
    // Rute Khusus Admin & Guru (Dilindungi oleh middleware 'role:admin|guru')
    // ============================================================
    Route::middleware('role:admin|guru')->group(function () {
        // Manajemen Kriteria
        Route::resource('criteria', criteria_controller::class);
        Route::post('/criteria/update/{criteria}', [criteria_controller::class, 'update'])->name('criteria.update');
        Route::get('/criteria', [criteria_controller::class, 'index'])->name('criteria.index');

        // Manajemen Alternatif
        Route::resource('alternatif', AlternatifController::class);
        Route::post('/alternatif/update/{alternatif}', [AlternatifController::class, 'update'])->name('alternatif.update');
        Route::get('/alternatif', [AlternatifController::class, 'index'])->name('alternatif.index');

        // Manajemen Penilaian
        Route::get('/penilaian', [PenilaianController::class, 'index'])->name('penilaian.index');
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

    // Rute untuk Hasil VIKOR (dapat diakses oleh semua peran terautentikasi)
    Route::get('/hasil', [HasilController::class, 'index'])->name('hasil.index');

    // ** Tambahkan rute ini untuk cetak PDF **
    // Pastikan hanya admin yang bisa mengaksesnya, sesuai dengan middleware di controller
    Route::get('/hasil/cetak-pdf', [HasilController::class, 'cetak'])->name('hasil.cetak');
    Route::get('/some-restricted-page', [UserController::class, 'index'])->middleware('check.user.status');

});
