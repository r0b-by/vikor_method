<?php

namespace App\Http\Controllers;

use App\Models\HasilVikor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class HasilController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $selectedTahunAjaran = $request->input('tahun_ajaran');
        $selectedSemester = $request->input('semester');

        $query = HasilVikor::with(['alternatif.user']);

        if ($selectedTahunAjaran) {
            $query->where('tahun_ajaran', $selectedTahunAjaran);
        }

        if ($selectedSemester) {
            $query->where('semester', $selectedSemester);
        }

        $hasil = $query->orderBy('ranking')->get();

        $tahunAjarans = HasilVikor::select('tahun_ajaran')
            ->whereNotNull('tahun_ajaran')
            ->distinct()
            ->orderByDesc('tahun_ajaran')
            ->pluck('tahun_ajaran');

        $semesters = HasilVikor::select('semester')
            ->whereNotNull('semester')
            ->distinct()
            ->pluck('semester');

        return view('hasil.index', compact('hasil', 'tahunAjarans', 'semesters', 'selectedTahunAjaran', 'selectedSemester'));
    }

    public function showSiswa(Request $request)
    {
        $user = Auth::user();
        $selectedTahunAjaran = $request->input('tahun_ajaran');
        $selectedSemester = $request->input('semester');

        $query = HasilVikor::whereHas('alternatif', function ($q) use ($user) {
            $q->where('user_id', $user->id);
        });

        if ($selectedTahunAjaran) {
            $query->where('tahun_ajaran', $selectedTahunAjaran);
        }

        if ($selectedSemester) {
            $query->where('semester', $selectedSemester);
        }

        $hasil = $query->first();

        $tahunAjarans = HasilVikor::whereHas('alternatif', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            })
            ->whereNotNull('tahun_ajaran')
            ->select('tahun_ajaran')
            ->distinct()
            ->orderByDesc('tahun_ajaran')
            ->pluck('tahun_ajaran');

        $semesters = HasilVikor::whereHas('alternatif', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            })
            ->whereNotNull('semester')
            ->select('semester')
            ->distinct()
            ->pluck('semester');

        if (!$hasil) {
            return view('siswa.hasil.show', compact(
                'hasil',
                'tahunAjarans',
                'semesters',
                'selectedTahunAjaran',
                'selectedSemester'
            ))->with('error', 'Hasil belum tersedia untuk filter yang dipilih.');
        }

        return view('siswa.hasil.show', compact(
            'hasil',
            'tahunAjarans',
            'semesters',
            'selectedTahunAjaran',
            'selectedSemester'
        ));
    }

    public function cetak(Request $request)
    {
        $selectedTahunAjaran = $request->input('tahun_ajaran');
        $selectedSemester = $request->input('semester');

        $query = HasilVikor::with(['alternatif.user']);

        if (Auth::user()->hasRole('admin') || Auth::user()->hasRole('guru')) {
            if ($selectedTahunAjaran) {
                $query->where('tahun_ajaran', $selectedTahunAjaran);
            }

            if ($selectedSemester) {
                $query->where('semester', $selectedSemester);
            }
        } else {
            $query->whereHas('alternatif', function ($q) {
                $q->where('user_id', Auth::id());
            });

            if ($selectedTahunAjaran) {
                $query->where('tahun_ajaran', $selectedTahunAjaran);
            }

            if ($selectedSemester) {
                $query->where('semester', $selectedSemester);
            }
        }

        $hasil = $query->orderBy('ranking')->get();

        if ($hasil->isEmpty()) {
            abort(404, 'Data hasil tidak ditemukan untuk laporan ini.');
        }

        // Sanitize filename
        $safeTahun = preg_replace('/[\/\\\\]/', '-', $selectedTahunAjaran ?? 'Semua');
        $safeSemester = preg_replace('/[\/\\\\]/', '-', $selectedSemester ?? 'Semua');

        $pdf = Pdf::loadView('hasil.cetak', compact('hasil', 'selectedTahunAjaran', 'selectedSemester'))
            ->setPaper('A4', 'portrait');

        return $pdf->download("laporan-hasil-vikor_{$safeTahun}_{$safeSemester}.pdf");
    }

    public function cetakSiswa(Request $request)
    {
        $user = Auth::user();
        $selectedTahunAjaran = $request->input('tahun_ajaran');
        $selectedSemester = $request->input('semester');

        $query = HasilVikor::with(['alternatif.user'])
            ->whereHas('alternatif', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            });

        if ($selectedTahunAjaran) {
            $query->where('tahun_ajaran', $selectedTahunAjaran);
        }
        if ($selectedSemester) {
            $query->where('semester', $selectedSemester);
        }

        $hasil = $query->first();

        if (!$hasil) {
            abort(404, 'Data hasil tidak ditemukan untuk filter ini.');
        }

        // Sanitize filename
        $safeTahun = preg_replace('/[\/\\\\]/', '-', $selectedTahunAjaran ?? 'Semua');
        $safeSemester = preg_replace('/[\/\\\\]/', '-', $selectedSemester ?? 'Semua');

        $pdf = Pdf::loadView('siswa.cetak-siswa', compact('hasil', 'selectedTahunAjaran', 'selectedSemester'))
            ->setPaper('A4', 'portrait');

        return $pdf->download("laporan-hasil-saya_{$safeTahun}_{$safeSemester}.pdf");
    }
}
