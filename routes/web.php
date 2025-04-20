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

// ============================================================
// 🔐 Autentikasi Laravel (termasuk verifikasi email)
// ============================================================
Auth::routes(['verify' => true]);

// ============================================================
// 🌐 Route Umum
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

// ============================================================
// 🛡️ Route Proteksi (Hanya untuk user yang login & verified)
// ============================================================
Route::middleware(['auth', 'verified'])->group(function () {

    // 🏠 Dashboard
    Route::get('/dashboard', [HomeController::class, 'index'])->name('dashboard');

    // 📊 Master Data
    Route::resource('criteria', criteria_controller::class);
    Route::resource('alternatif', AlternatifController::class);
    Route::resource('penilaian', PenilaianController::class);

    // ⚙️ Proses VIKOR
    Route::get('/hitung', [HitungController::class, 'index'])->name('hitung.index');
    Route::post('/hitung/simpan', [HitungController::class, 'simpan'])->name('hitung.simpan');

    // 📈 Hasil VIKOR
    Route::get('/hasil', [HasilController::class, 'index'])->name('hasil.index');
    Route::get('/hasil/cetak', [HasilController::class, 'cetak'])->name('hasil.cetak');

    // 🧑 Halaman Tambahan
    Route::view('/user', 'dashboard.user')->name('user');
    Route::view('/setting', 'dashboard.setting')->name('setting');
});
