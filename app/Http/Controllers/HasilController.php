<?php

namespace App\Http\Controllers;

use App\Models\HasilVikor;
use Illuminate\Http\Request;
use App\Policies\HasilVikorPolicy;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class HasilController extends Controller
{
    public function __construct()
    {
       $this->middleware('auth');
    }

    public function index()
    {
        $hasil = HasilVikor::with(['alternatif.user'])
              ->orderBy('ranking')
              ->get();
              
        return view('hasil.index', compact('hasil'));
    }

    public function showSiswa()
    {
        $user = Auth::user();
        $hasil = HasilVikor::whereHas('alternatif', function($query) use ($user) {
            $query->where('user_id', $user->id);
        })->first();

        if (!$hasil) {
            return redirect()->route('siswa.dashboard')
                   ->with('error', 'Hasil belum tersedia');
        }

        return view('siswa.hasil.show', compact('hasil'));
    }
     public function cetak()
    {
        // Hanya admin yang bisa cetak semua hasil
        if (Auth::user()->hasRole('admin')) {
            $hasil = HasilVikor::with(['alternatif.user'])
                  ->orderBy('ranking')
                  ->get();
        } else {
            // Siswa hanya bisa cetak hasil mereka sendiri
            $hasil = HasilVikor::with(['alternatif.user'])
                  ->whereHas('alternatif', function($query) {
                      $query->where('user_id', Auth::id());
                  })
                  ->orderBy('ranking')
                  ->get();
        }

        $pdf = Pdf::loadView('hasil.cetak', compact('hasil'))
                  ->setPaper('A4', 'portrait');

        return $pdf->download('laporan-hasil-vikor.pdf');
    }
    public function cetakSiswa()
    {
        $user = auth()->user();
        
        $hasil = HasilVikor::with(['alternatif.user'])
            ->whereHas('alternatif', function($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->first();

        if (!$hasil) {
            abort(404, 'Data hasil tidak ditemukan');
        }

        $pdf = Pdf::loadView('siswa.cetak-siswa', compact('hasil'))
                ->setPaper('A4', 'portrait');

        return $pdf->download('laporan-hasil-saya.pdf');
    }
}