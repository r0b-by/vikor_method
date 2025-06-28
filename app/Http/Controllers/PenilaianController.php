<?php

namespace App\Http\Controllers;

use App\Models\penilaian;
use App\Http\Requests\PenilaianRequest; // Asumsi PenilaianRequest ada
use App\Models\alternatif;
use App\Models\criteria;
use Illuminate\Support\Facades\DB; // Pastikan ini diimpor

class PenilaianController extends Controller
{
    /**
     * Konstruktor untuk menerapkan middleware otorisasi.
     * Hanya 'admin' dan 'guru' yang dapat mengakses metode-metode di controller ini.
     */
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin|guru']); // Memastikan hanya admin atau guru
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $alternatif = alternatif::all();
        $criteria = criteria::all();
        $penilaian = Penilaian::with(['criteria', 'alternatif'])->get();
        return view('dashboard.penilaian ', compact(['criteria', 'alternatif', 'penilaian']));
    }

    /**
     * Store or update penilaian data.
     * Metode ini akan menggantikan metode `store` dan `update` Resource Controller yang biasa,
     * karena Anda melakukan updateOrCreate berdasarkan alternatif dan kriteria.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeOrUpdate(Request $request)
    {
        $request->validate([
            'id_alternatif' => 'required|exists:alternatifs,id',
            'nilai' => 'required|array',
            'nilai.*' => 'required|numeric|min:0', // Validasi setiap nilai dalam array
        ]);

        $idAlternatif = $request->input('id_alternatif');
        $nilaiData = $request->input('nilai');

        foreach ($nilaiData as $criteriaId => $nilai) {
            Penilaian::updateOrCreate(
                ['id_alternatif' => $idAlternatif, 'id_criteria' => $criteriaId],
                ['nilai' => $nilai]
            );
        }

        return redirect()->back()->with('success', 'Penilaian berhasil disimpan/diperbarui!');
    }

    // Metode 'create', 'show', 'edit', 'update', 'destroy' standar Resource Controller
    // tidak perlu diimplementasikan secara terpisah jika semua CRUD dilakukan melalui modal di halaman index
    // atau jika Anda tidak menggunakannya untuk fungsionalitas ini.
    // Jika ada kebutuhan spesifik, Anda bisa menambahkan kembali dan menerapkan otorisasi di sini.

    public function create() { /* ... */ }
    public function show(penilaian $penilaian) { /* ... */ }
    public function edit(penilaian $penilaian) { /* ... */ }
    public function update(Request $request, penilaian $penilaian) { /* ... */ } // Sesuaikan parameter jika tidak melalui route model binding
    public function destroy(penilaian $penilaian) { /* ... */ }
}
