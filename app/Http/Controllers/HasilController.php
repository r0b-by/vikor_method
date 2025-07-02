<?php

namespace App\Http\Controllers;

use App\Models\HasilVikor;
use App\Models\Penilaian; // Import model Penilaian
use App\Models\Alternatif; // Import model Alternatif jika belum ada
use Illuminate\Http\Request;
use App\Policies\HasilVikorPolicy; // Mungkin tidak diperlukan jika hanya untuk otorisasi dasar
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class HasilController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        // Anda mungkin ingin menambahkan middleware role di sini jika hanya admin/guru yang bisa melihat semua hasil
        // $this->middleware('role:admin|guru')->only('index', 'cetak');
    }

    public function index(Request $request) // Tambahkan parameter Request
    {
        $selectedTahunAjaran = $request->input('tahun_ajaran');
        $selectedSemester = $request->input('semester');

        // Query dasar untuk HasilVikor
        $query = HasilVikor::with(['alternatif.user']);

        // Jika ada filter tahun ajaran atau semester, terapkan
        if ($selectedTahunAjaran || $selectedSemester) {
            $query->whereHas('alternatif.penilaians', function ($q) use ($selectedTahunAjaran, $selectedSemester) {
                if ($selectedTahunAjaran) {
                    $q->where('tahun_ajaran', $selectedTahunAjaran);
                }
                if ($selectedSemester) {
                    $q->where('semester', $selectedSemester);
                }
            });
        }

        $hasil = $query->orderBy('ranking')->get();

        // Ambil semua tahun ajaran unik dan semester dari tabel penilaians untuk dropdown
        // Ini memastikan dropdown hanya menampilkan tahun ajaran/semester yang benar-benar ada di data penilaian
        $tahunAjarans = Penilaian::select('tahun_ajaran')
                                  ->distinct()
                                  ->whereNotNull('tahun_ajaran') // Pastikan hanya mengambil yang tidak null
                                  ->pluck('tahun_ajaran');

        $semesters = Penilaian::select('semester')
                               ->distinct()
                               ->whereNotNull('semester') // Pastikan hanya mengambil yang tidak null
                               ->pluck('semester');

        return view('hasil.index', compact('hasil', 'tahunAjarans', 'semesters', 'selectedTahunAjaran', 'selectedSemester'));
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
        // Ambil filter yang mungkin ada dari request (untuk laporan yang difilter)
        $request = request(); // Ambil instance request di sini
        $selectedTahunAjaran = $request->input('tahun_ajaran');
        $selectedSemester = $request->input('semester');

        $query = HasilVikor::with(['alternatif.user']);

        if (Auth::user()->hasRole('admin')) {
            // Jika admin, terapkan filter jika ada
            if ($selectedTahunAjaran || $selectedSemester) {
                $query->whereHas('alternatif.penilaians', function ($q) use ($selectedTahunAjaran, $selectedSemester) {
                    if ($selectedTahunAjaran) {
                        $q->where('tahun_ajaran', $selectedTahunAjaran);
                    }
                    if ($selectedSemester) {
                        $q->where('semester', $selectedSemester);
                    }
                });
            }
        } else {
            // Jika siswa, hanya cetak hasil mereka sendiri dan terapkan filter jika ada
            $query->whereHas('alternatif', function($q) {
                $q->where('user_id', Auth::id());
            });
            if ($selectedTahunAjaran || $selectedSemester) {
                $query->whereHas('alternatif.penilaians', function ($q) use ($selectedTahunAjaran, $selectedSemester) {
                    if ($selectedTahunAjaran) {
                        $q->where('tahun_ajaran', $selectedTahunAjaran);
                    }
                    if ($selectedSemester) {
                        $q->where('semester', $selectedSemester);
                    }
                });
            }
        }

        $hasil = $query->orderBy('ranking')->get();

        $pdf = Pdf::loadView('hasil.cetak', compact('hasil', 'selectedTahunAjaran', 'selectedSemester'))
            ->setPaper('A4', 'portrait');

        return $pdf->download('laporan-hasil-vikor.pdf');
    }

    public function cetakSiswa()
    {
        $user = auth()->user();

        // Ambil filter yang mungkin ada dari request
        $request = request();
        $selectedTahunAjaran = $request->input('tahun_ajaran');
        $selectedSemester = $request->input('semester');

        $query = HasilVikor::with(['alternatif.user'])
            ->whereHas('alternatif', function($q) use ($user) {
                $q->where('user_id', $user->id);
            });

        if ($selectedTahunAjaran || $selectedSemester) {
            $query->whereHas('alternatif.penilaians', function ($q) use ($selectedTahunAjaran, $selectedSemester) {
                if ($selectedTahunAjaran) {
                    $q->where('tahun_ajaran', $selectedTahunAjaran);
                }
                if ($selectedSemester) {
                    $q->where('semester', $selectedSemester);
                }
            });
        }

        $hasil = $query->first(); // Mengambil satu hasil untuk siswa

        if (!$hasil) {
            abort(404, 'Data hasil tidak ditemukan untuk filter ini.'); // Pesan error lebih spesifik
        }
        $pdf = Pdf::loadView('siswa.cetak-siswa', compact('hasil', 'selectedTahunAjaran', 'selectedSemester'))
            ->setPaper('A4', 'portrait');

        return $pdf->download('laporan-hasil-saya.pdf');
    }
}