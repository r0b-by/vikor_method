<?php

namespace App\Http\Controllers;

use App\Models\HasilVikor;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class HasilController extends Controller
{
    public function index()
    {
        $hasil = HasilVikor::with('alternatif')->orderBy('ranking')->get();
        return view('hasil.index', compact('hasil'));
    }

    public function cetak()
    {
        $hasil = HasilVikor::with('alternatif')->orderBy('ranking')->get();

        $pdf = Pdf::loadView('hasil.cetak', compact('hasil'))
                  ->setPaper('A4', 'portrait');

        return $pdf->download('laporan-hasil-vikor.pdf');
    }
}
