<?php

namespace App\Http\Controllers;

use App\Models\Alternatif; // Perbaiki 'alternatif' menjadi 'Alternatif' (huruf besar A)
use App\Models\AcademicPeriod; // Import model AcademicPeriod
use Illuminate\Http\Request;
use App\Http\Requests\AlternatifRequest; // Asumsi Anda memiliki AlternatifRequest
use Illuminate\Contracts\View\View;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator; // Import Validator facade

class AlternatifController extends Controller
{
    /**
     * Konstruktor untuk menerapkan middleware otorisasi.
     * Hanya 'admin' dan 'guru' yang dapat mengakses metode-metode di controller ini.
     */
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin|guru']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(): Factory|View
    {
        // Ambil semua alternatif, diurutkan berdasarkan kode
        $alternatif = Alternatif::orderByRaw('LENGTH(alternatif_code), alternatif_code')->get();

        // Ambil semua periode akademik yang aktif untuk dropdown
        $academicPeriods = AcademicPeriod::where('is_active', true)->get();

        // Teruskan kedua data ke view
        return view('dashboard.alternatif', compact('alternatif', 'academicPeriods'));
    }

    /**
     * Show the form for creating a new resource.
     * (Biasanya tidak perlu implementasi jika menggunakan modal seperti di Blade)
     *
     * @return \Illuminate\Http\Response
     */
    public function create(): void
    {
        // Tidak perlu implementasi karena menggunakan modal
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request // Ubah tipe parameter menjadi Request jika validasi dilakukan di sini
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request): RedirectResponse
    {
        // Validasi input
        $validator = Validator::make($request->all(), [
            'alternatif_code' => ['required', 'string', 'max:255', 'unique:alternatifs'],
            'alternatif_name' => ['required', 'string', 'max:255'],
            'academic_period_combined' => [
                'required',
                'string',
                function ($attribute, $value, $fail) {
                    list($tahunAjaran, $semester) = explode('|', $value);
                    $exists = AcademicPeriod::where('tahun_ajaran', $tahunAjaran)
                                            ->where('semester', $semester)
                                            ->where('is_active', true)
                                            ->exists();
                    if (!$exists) {
                        $fail('Periode akademik yang dipilih tidak valid atau tidak aktif.');
                    }
                },
            ],
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $data = $validator->validated();

        // Memecah nilai tahun_ajaran dan semester dari input gabungan
        list($tahunAjaran, $semester) = explode('|', $data['academic_period_combined']);

        // Buat alternatif baru
        Alternatif::create([
            'alternatif_code' => $data['alternatif_code'],
            'alternatif_name' => $data['alternatif_name'],
            'tahun_ajaran' => $tahunAjaran,
            'semester' => $semester,
            'user_id' => null, // Alternatif yang dibuat admin mungkin tidak langsung terkait user_id
                               // Sesuaikan jika ada logika untuk mengaitkan dengan user tertentu
            'status_perhitungan' => 'pending', // Set default status
        ]);

        return redirect()->back()->with('success', 'Alternatif berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     * (Biasanya tidak digunakan untuk CRUD sederhana, kecuali ada halaman detail)
     *
     * @param  \App\Models\Alternatif  $alternatif
     * @return \Illuminate\Http\Response
     */
    public function show(Alternatif $alternatif): void
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     * (Biasanya tidak perlu implementasi jika menggunakan modal seperti di Blade)
     *
     * @param  \App\Models\Alternatif  $alternatif
     * @return \Illuminate\Http\Response
     */
    public function edit(Alternatif $alternatif): Factory|View
    {
        // Tidak perlu implementasi karena menggunakan modal
        // Jika Anda memiliki view edit terpisah, Anda akan mengembalikan view di sini
        // return view('dashboard.alternatif', compact('alternatif'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request // Ubah tipe parameter menjadi Request jika validasi dilakukan di sini
     * @param  \App\Models\Alternatif  $alternatif
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Alternatif $alternatif): RedirectResponse
    {
        // Validasi input
        $validator = Validator::make($request->all(), [
            'alternatif_code' => ['required', 'string', 'max:255', 'unique:alternatifs,alternatif_code,' . $alternatif->id],
            'alternatif_name' => ['required', 'string', 'max:255'],
            'academic_period_combined' => [
                'required',
                'string',
                function ($attribute, $value, $fail) {
                    list($tahunAjaran, $semester) = explode('|', $value);
                    $exists = AcademicPeriod::where('tahun_ajaran', $tahunAjaran)
                                            ->where('semester', $semester)
                                            ->where('is_active', true)
                                            ->exists();
                    if (!$exists) {
                        $fail('Periode akademik yang dipilih tidak valid atau tidak aktif.');
                    }
                },
            ],
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $data = $validator->validated();

        // Memecah nilai tahun_ajaran dan semester dari input gabungan
        list($tahunAjaran, $semester) = explode('|', $data['academic_period_combined']);

        // Perbarui alternatif
        $alternatif->update([
            'alternatif_code' => $data['alternatif_code'],
            'alternatif_name' => $data['alternatif_name'],
            'tahun_ajaran' => $tahunAjaran,
            'semester' => $semester,
            // user_id dan status_perhitungan biasanya tidak diubah di sini
        ]);

        return redirect()->back()->with('success', 'Alternatif berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  string  $alternatif
     * @return \Illuminate\Http\Response
     */
    public function destroy(string $alternatif): RedirectResponse
    {
        // Menggunakan findOrFail untuk memastikan alternatif ada sebelum dihapus
        Alternatif::findOrFail($alternatif)->delete(); // Perbaiki 'alternatif' menjadi 'Alternatif'
        return redirect()->back()->with('success', 'Alternatif berhasil dihapus!');
    }
}
