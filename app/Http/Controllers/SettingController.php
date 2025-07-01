<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('dashboard.layouts.setting'); 
    }

    public function update(Request $request)
    {
        $request->validate([
            'app_name' => 'nullable|string|max:255',
            'timezone' => 'nullable|string|max:255',
        ]);

        return redirect()->back()->with('success', 'Pengaturan berhasil diperbarui!');
    }
}