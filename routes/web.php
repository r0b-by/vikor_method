<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\HasilController;
use App\Http\Controllers\CriteriaController; // Perbaiki nama controller
use App\Http\Controllers\AlternatifController;
use App\Http\Controllers\PenilaianController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\HitungController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AcademicPeriodController; // Import AcademicPeriodController


// ============================================================
// Halaman Utama / Welcome Page (Tidak memerlukan autentikasi)
// ============================================================
// Halaman Publik
Route::get('/', function () {
    return view('welcome'); // Pastikan file welcome.blade.php ada
})->name('welcome');

Route::get('/about', function() {
    if (!View::exists('about')) {
        abort(500, 'View [about] tidak ditemukan');
    }
    return view('about');
})->name('about');

Route::get('/features', function () {
    return view('features'); // Pastikan file features.blade.php ada
})->name('features');

Route::get('/contact', function () {
    return view('contact'); // Pastikan file contact.blade.php ada
})->name('contact');

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
        Route::get('/users', [UserController::class, 'index'])->name('admin.user.management');
        Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('admin.users.destroy');
        // Admin mengedit user lain
        Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('admin.users.edit');
        // ... (other admin routes)
        Route::put('/users/{user}', [UserController::class, 'update'])->name('admin.users.update');
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

    Route::middleware(['auth', 'role:siswa'])->group(function () { // Added 'auth' middleware explicitly here
    // Dashboard siswa untuk menampilkan hasil Vikor
    Route::get('/siswa/dashboard', [HomeController::class, 'showSiswaDashboard'])->name('siswa.dashboard');
    Route::get('/siswa/hasil', [HasilController::class, 'showSiswa'])->name('siswa.hasil');
    Route::get('/siswa/cetak-hasil', [HasilController::class, 'cetakSiswa'])->name('siswa.cetak-hasil');
    Route::get('/siswa/profile/show', [UserController::class, 'showProfile'])->name('siswa.profile.show');
    Route::get('/siswa/profile/edit', [UserController::class, 'edit'])->name('siswa.profile.edit-siswa');
    // Form penilaian mandiri siswa
    Route::get('/siswa/penilaian', [PenilaianController::class, 'indexForStudent'])->name('siswa.penilaian.index');
    // Updated to call storeOrUpdateForStudent
    Route::post('/siswa/penilaian', [PenilaianController::class, 'storeOrUpdateForStudent'])->name('siswa.penilaian.store'); // Changed name for clarity
});
    // ============================================================
    // Rute Khusus Admin & Guru (Dilindungi oleh middleware 'role:admin|guru')
    // ============================================================
    Route::middleware('role:admin|guru')->group(function () {
        // Manajemen Kriteria
        Route::resource('criteria', CriteriaController::class);

        // Manajemen Alternatif
        Route::resource('alternatif', AlternatifController::class);

        // Manajemen Penilaian
        Route::get('/penilaian', [PenilaianController::class, 'index'])->name('penilaian.index');
        Route::post('/penilaian/store-or-update', [PenilaianController::class, 'storeOrUpdate'])->name('penilaian.storeOrUpdate');
        Route::delete('/penilaian/{penilaian}', [PenilaianController::class, 'destroy'])->name('penilaian.destroy');

        // Proses VIKOR
        Route::prefix('hitung')->group(function () {
            // Route untuk menampilkan halaman utama perhitungan VIKOR
            // Ini adalah halaman di mana pengguna mungkin memilih periode akademik sebelum melakukan perhitungan.
            Route::get('/', [HitungController::class, 'index'])->name('dashboard.hitung');

            // Route untuk memicu perhitungan VIKOR
            // Menggunakan POST karena ini adalah tindakan yang mengubah atau memproses data (memulai perhitungan).
            // Typo 'dahsboard' diperbaiki menjadi 'dashboard'.
            Route::match(['get', 'post'], '/dashboard/perform', [HitungController::class, 'performCalculation'])->name('hitung.perform');
            
            // Route untuk menyimpan hasil perhitungan VIKOR
            // Menggunakan POST karena ini adalah tindakan penyimpanan data.
            Route::post('/dashboard/simpan', [HitungController::class, 'simpan'])->name('hitung.simpan');

            // --- Rute untuk Menampilkan Tahapan Perhitungan (Read-only views) ---
            // Semua rute ini menggunakan GET karena mereka hanya menampilkan data, bukan mengubahnya.
            // Pastikan metode-metode ini di HitungController juga difilter berdasarkan tahun_ajaran/semester
            // (misalnya, melalui parameter di URL atau data sesi jika sudah dipilih di index).

            // Route untuk menampilkan Matriks Keputusan
            Route::get('/matriks-keputusan', [HitungController::class, 'tampilMatriksKeputusan'])->name('hitung.matriks-keputusan');
            
            // Route untuk menampilkan Matriks Normalisasi
            Route::get('/normalisasi', [HitungController::class, 'tampilNormalisasi'])->name('hitung.normalisasi');
            
            // Route untuk menampilkan Matriks Normalisasi Terbobot
            Route::get('/normalisasi-terbobot', [HitungController::class, 'tampilNormalisasiTerbobot'])->name('hitung.normalisasi-terbobot');
            
            // Route untuk menampilkan Nilai Selisih Ideal (S dan R)
            Route::get('/selisih-ideal', [HitungController::class, 'tampilSelisihIdeal'])->name('hitung.selisih-ideal');
            
            // Route untuk menampilkan Nilai Utility (Q)
            Route::get('/utility', [HitungController::class, 'tampilUtility'])->name('hitung.utility');
            
            // Route untuk menampilkan Indeks Kompromi dan Ranking Akhir
            // Biasanya, tahap kompromi sudah mencakup ranking akhir, jadi mungkin tidak perlu route terpisah untuk ranking.
            Route::get('/kompromi-ranking', [HitungController::class, 'tampilKompromi'])->name('hitung.kompromi-ranking');
        });

        // Rute untuk menghapus hasil VIKOR dari riwayat (di HasilController)
        Route::delete('/hasil/{hasilVikor}', [HasilController::class, 'destroy'])->name('hasil.destroy');
    });
});