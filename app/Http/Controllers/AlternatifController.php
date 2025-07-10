<?php

namespace App\Http\Controllers;

use App\Models\Alternatif;
use App\Models\AcademicPeriod;
use Illuminate\Http\Request;
use App\Http\Requests\AlternatifRequest;
use Illuminate\Contracts\View\View;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

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
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index(Request $request): Factory|View
    {
        $perPage = request()->query('perPage', 10);
        $alternatif = Alternatif::paginate($perPage);

        $academicPeriods = AcademicPeriod::where('is_active', true)->get();

        return view('dashboard.alternatif', compact('alternatif', 'academicPeriods'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return void
     */
    public function create(): void
    {
        // Tidak perlu implementasi karena menggunakan modal
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\AlternatifRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(AlternatifRequest $request): RedirectResponse
    {
        $data = $request->validated();

        // Mengambil objek AcademicPeriod berdasarkan ID yang divalidasi
        $academicPeriod = AcademicPeriod::find($data['academic_period_id']);

        Alternatif::create([
            'alternatif_code' => $data['alternatif_code'],
            'alternatif_name' => $data['alternatif_name'],
            'tahun_ajaran' => $academicPeriod->tahun_ajaran,
            'semester' => $academicPeriod->semester,
            'user_id' => Auth::id(),
            'status_perhitungan' => 'pending',
        ]);

        return redirect()->back()->with('success', 'Alternatif berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Alternatif  $alternatif
     * @return void
     */
    public function show(Alternatif $alternatif): void
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Alternatif  $alternatif
     * @return void
     */
    public function edit(Alternatif $alternatif): void
    {
        // Tidak perlu implementasi karena menggunakan modal
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\AlternatifRequest  $request
     * @param  \App\Models\Alternatif  $alternatif
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(AlternatifRequest $request, Alternatif $alternatif): RedirectResponse
    {
        $data = $request->validated();

        // Mengambil objek AcademicPeriod berdasarkan ID yang divalidasi
        $academicPeriod = AcademicPeriod::find($data['academic_period_id']);

        $alternatif->update([
            'alternatif_code' => $data['alternatif_code'],
            'alternatif_name' => $data['alternatif_name'],
            'tahun_ajaran' => $academicPeriod->tahun_ajaran,
            'semester' => $academicPeriod->semester,
        ]);

        return redirect()->back()->with('success', 'Alternatif berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Alternatif  $alternatif
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Alternatif $alternatif): RedirectResponse
    {
        $alternatif->delete();
        return redirect()->back()->with('success', 'Alternatif berhasil dihapus!');
    }
}