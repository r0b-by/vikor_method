<?php

namespace App\Http\Controllers;

use App\Models\HasilVikor;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf; // Pastikan ini diimpor jika menggunakan PDF

class HasilController extends Controller
{
    /**
     * Konstruktor untuk menerapkan middleware otorisasi.
     * Semua peran terautentikasi dapat melihat index hasil.
     * Hanya 'admin' yang dapat mengakses metode 'cetak'.
     */
    public function __construct()
    {
        $this->middleware('auth'); // Semua user terautentikasi bisa melihat hasil
        $this->middleware('role:admin')->only('cetak'); // Hanya admin yang bisa mencetak
    }

    /**
     * Display a listing of the resource (Hasil VIKOR).
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $hasil = HasilVikor::with('alternatif')->orderBy('ranking')->get();
        return view('hasil.index', compact('hasil'));
    }

    /**
     * Generate and download the PDF report.
     *
     * @return \Illuminate\Http\Response
     */
    public function cetak()
    {
        // Logika otorisasi sudah di handle oleh middleware di konstruktor
        $hasil = HasilVikor::with('alternatif')->orderBy('ranking')->get();

        // Pastikan view 'hasil.cetak' ada dan disiapkan untuk data ini
        $pdf = Pdf::loadView('hasil.cetak', compact('hasil'))
                  ->setPaper('A4', 'portrait');

        return $pdf->download('laporan-hasil-vikor.pdf');
    }
}
