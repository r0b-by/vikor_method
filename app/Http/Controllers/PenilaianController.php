<?php

namespace App\Http\Controllers;

use App\Models\penilaian;
use App\Http\Requests\PenilaianRequest;
use App\Models\alternatif;
use App\Models\criteria;
use \Illuminate\Support\Facades\DB;

class PenilaianController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $alternatif = alternatif::all();
        $criteria = criteria::all();
        $penilaian = Penilaian::with(['criteria', 'alternatif'])->get();
        return view('dashboard.penilaian ', compact(['criteria', 'alternatif', 'penilaian']));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PenilaianRequest $request)
    {
        $request->validate([
            'id_alternatif' => 'required|exists:alternatifs,id',
            'nilai.*' => 'required|numeric|min:0', // HAPUS batas between 0–99.99
        ]);

        $idAlternatif = $request->input('id_alternatif');
        $nilaiData = $request->input('nilai');

        foreach ($nilaiData as $criteriaId => $nilai) {
            Penilaian::updateOrCreate(
                ['id_alternatif' => $idAlternatif, 'id_criteria' => $criteriaId],
                ['nilai' => $nilai]
            );
        }

        return redirect()->back()->with('success', 'Penilaian added successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(penilaian $penilaian)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(penilaian $penilaian)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update($request, penilaian $penilaian)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(penilaian $penilaian)
    {
        //
    }
}
