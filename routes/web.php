<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\HasilController;
use App\Http\Controllers\criteria_controller; // Perbaiki nama controller
use App\Http\Controllers\AlternatifController;
use App\Http\Controllers\PenilaianController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\HitungController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AcademicPeriodController; // Import AcademicPeriodController


// ============================================================
// Halaman Utama / Welcome Page (Tidak memerlukan autentikasi)
// ============================================================
Route::get('/', function () {
    return view('welcome');
})->name('welcome');

// ============================================================
// Halaman Informasi Pendaftaran 'Pending' (tanpa login)
// ============================================================
Route::get('/registration-pending', function () {
    return view('admin.registration-pending');
})->name('registration.pending');

// ============================================================
// Autentikasi Laravel (termasuk verifikasi email)
// ============================================================
Auth::routes(['verify' => true]);

// ============================================================
// Rute Setelah Autentikasi (Dilindungi oleh middleware 'auth' dan 'check.user.status')
// Middleware 'check.user.status' akan memastikan user dengan status 'pending' tidak bisa mengakses rute ini
// ============================================================
Route::middleware(['auth', 'check.user.status'])->group(function () {

    // Dashboard utama, diakses setelah login
    Route::get('/dashboard', [HomeController::class, 'index'])->name('dashboard');

    // Rute-rute ini akan digunakan oleh SEMUA user yang terautentikasi (admin, guru, siswa)
    Route::get('/profile', [UserController::class, 'showProfile'])->name('profile.show');
    Route::get('/profile/edit', [UserController::class, 'editProfile'])->name('profile.edit');
    Route::put('/profile/update', [UserController::class, 'updateProfile'])->name('profile.update');

    // Rute Setting
    Route::get('/setting', [SettingController::class, 'index'])->name('setting');
    Route::put('/settings', [SettingController::class, 'update'])->name('setting.update');

    // Rute untuk Hasil VIKOR (dapat diakses oleh semua peran terautentikasi)
    Route::get('/hasil', [HasilController::class, 'index'])->name('hasil.index');
    Route::get('/hasil/cetak-pdf', [HasilController::class, 'cetak'])->name('hasil.cetak');
    // Rute untuk detail hasil VIKOR (jika Anda membuat halaman detail per perhitungan)
    Route::get('/hasil/detail', [HasilController::class, 'detail'])->name('hasil.detail'); // Tambahkan ini jika Anda punya halaman detail

    // ============================================================
    // Rute Khusus Admin (Dilindungi oleh middleware 'role:admin')
    // ============================================================
    Route::middleware('role:admin')->prefix('admin')->group(function () {
        // Manajemen Pengguna (Admin dapat mengelola semua pengguna)
        Route::get('/users', [UserController::class, 'index'])->name('user.management');
        Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
        // Admin mengedit user lain (perhatikan route model binding)
        Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('admin.users.edit');
        Route::get('/users/pending-registrations', [UserController::class, 'pendingRegistrations'])->name('admin.users.pending-registrations');
        Route::put('/users/{user}', [UserController::class, 'update'])->name('admin.users.update');
        // Persetujuan Pendaftaran Pengguna
        Route::post('/users/{userId}/approve-registration', [UserController::class, 'approveRegistration'])->name('admin.users.approve-registration');
        Route::post('/users/{userId}/reject-registration', [UserController::class, 'rejectRegistration'])->name('admin.users.reject-registration');

        // Persetujuan Pembaruan Profil
        Route::get('/users/pending-profile-updates', [UserController::class, 'pendingProfileUpdates'])->name('admin.users.pending-profile-updates');
        Route::post('/users/{pendingUpdate}/approve-profile-update', [UserController::class, 'approveProfileUpdate'])->name('admin.users.approve-profile-update');
        Route::post('/users/{pendingUpdate}/reject-profile-update', [UserController::class, 'rejectProfileUpdate'])->name('admin.users.reject-profile-update');

        // Manajemen Periode Akademik (Baru ditambahkan)
        Route::prefix('academic-periods')->group(function () {
            Route::get('/admin/academic-periods', [AcademicPeriodController::class, 'index'])->name('admin.academic_periods.index');
            Route::get('/admin/academic-periods/create', [AcademicPeriodController::class, 'create'])->name('admin.academic_periods.create');
            Route::post('/admin/academic-periods/store', [AcademicPeriodController::class, 'store'])->name('admin.academic_periods.store');
            Route::get('/{academicPeriod}/edit', [AcademicPeriodController::class, 'edit'])->name('admin.academic_periods.edit');
            Route::put('/{academicPeriod}', [AcademicPeriodController::class, 'update'])->name('admin.academic_periods.update');
            Route::delete('/{academicPeriod}', [AcademicPeriodController::class, 'destroy'])->name('admin.academic_periods.destroy');
        });
    });

    // ============================================================
    // Rute Khusus Siswa (Dilindungi oleh middleware 'role:siswa')
    // ============================================================
    Route::middleware('role:siswa')->group(function () {

        // Dashboard siswa untuk menampilkan hasil Vikor
        Route::get('/siswa/dashboard', [HomeController::class, 'showSiswaDashboard'])->name('siswa.dashboard');
        Route::get('/siswa/hasil', [HasilController::class, 'showSiswa'])->name('siswa.hasil');
        Route::get('/siswa/cetak-hasil', [HasilController::class, 'cetakSiswa'])->name('siswa.cetak-hasil');
        Route::get('/siswa/profile/show', [UserController::class, 'showProfile'])->name('siswa.profile.show');
        Route::get('/siswa/profile/edit', [UserController::class, 'edit'])->name('siswa.profile.edit-siswa');
        // Form penilaian mandiri siswa
        Route::get('/siswa/penilaian', [PenilaianController::class, 'indexForStudent'])->name('siswa.penilaian.index');
        Route::post('/siswa/penilaian', [PenilaianController::class, 'storeOrUpdateForStudent'])->name('siswa.penilaian.store'); // Method POST untuk menyimpan

    });

    // ============================================================
    // Rute Khusus Admin & Guru (Dilindungi oleh middleware 'role:admin|guru')
    // ============================================================
    Route::middleware('role:admin|guru')->group(function () {
        // Manajemen Kriteria
        Route::resource('criteria', criteria_controller::class)->except(['update']); // Perbaiki nama controller
        Route::post('/criteria/update/{criteria}', [criteria_controller::class, 'update'])->name('criteria.update'); // Perbaiki nama controller

        // Manajemen Alternatif
        Route::resource('alternatif', AlternatifController::class)->except(['update']);
        Route::post('/alternatif/update/{alternatif}', [AlternatifController::class, 'update'])->name('alternatif.update');

        // Manajemen Penilaian
        Route::get('/penilaian', [PenilaianController::class, 'index'])->name('penilaian.index');
        Route::post('/penilaian/store-or-update', [PenilaianController::class, 'storeOrUpdate'])->name('penilaian.storeOrUpdate');
        Route::delete('/penilaian/{penilaian}', [PenilaianController::class, 'destroy'])->name('penilaian.destroy');

        // Proses VIKOR
        Route::prefix('hitung')->group(function () {
            Route::get('/', [HitungController::class, 'index'])->name('hitung.index');
            // Rute untuk memicu perhitungan (menggunakan POST untuk mengirim filter)
           Route::post('/dahsboard/perform', [HitungController::class, 'performCalculation'])->name('hitung.perform');
            Route::post('/dashboard/simpan', [HitungController::class, 'simpan'])->name('hitung.simpan');

            // Rute untuk menampilkan tahapan perhitungan (jika masih ingin terpisah)
            // Pastikan metode ini di HitungController juga difilter berdasarkan tahun_ajaran/semester
            Route::get('/matriks', [HitungController::class, 'tampilMatriksKeputusan'])->name('hitung.matriks'); // Perbaiki nama method
            Route::get('/normalisasi', [HitungController::class, 'tampilNormalisasi'])->name('hitung.normalisasi');
            Route::get('/normalisasi-terbobot', [HitungController::class, 'tampilNormalisasiTerbobot'])->name('hitung.normalisasi-terbobot');
            Route::get('/selisih-ideal', [HitungController::class, 'tampilSelisihIdeal'])->name('hitung.selisih-ideal');
            Route::get('/utility', [HitungController::class, 'tampilUtility'])->name('hitung.utility');
            Route::get('/kompromi', [HitungController::class, 'tampilKompromi'])->name('hitung.kompromi');
            // Route::get('/ranking', [HitungController::class, 'tampilRanking'])->name('hitung.ranking'); // Ini biasanya bagian dari tampilKompromi
        });

        // Rute untuk menghapus hasil VIKOR dari riwayat (di HasilController)
        Route::delete('/hasil/{hasilVikor}', [HasilController::class, 'destroy'])->name('hasil.destroy');
    });
});