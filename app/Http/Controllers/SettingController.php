<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function __construct()
    {
        // Middleware autentikasi untuk semua metode di controller ini
        $this->middleware('auth');
        // Jika hanya peran tertentu yang bisa mengakses setting, tambahkan middleware role
        // $this->middleware(['auth', 'role:admin|guru']);
    }

    public function index()
    {
        // Logika untuk menampilkan halaman pengaturan
        return view('dashboard.layouts.setting'); // Asumsikan Anda punya view di resources/views/dashboard/setting.blade.php
    }

    // Opsional: Jika Anda punya fitur untuk update pengaturan
    public function update(Request $request)
    {
        // Logika untuk memproses update pengaturan
        // Contoh validasi:
        $request->validate([
            'app_name' => 'nullable|string|max:255',
            'timezone' => 'nullable|string|max:255',
            // tambahkan validasi untuk pengaturan lain
        ]);

        // Lakukan update ke database atau file config
        // ...

        return redirect()->back()->with('success', 'Pengaturan berhasil diperbarui!');
    }
}