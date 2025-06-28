<?php

namespace App\Http\Controllers;

use App\Models\criteria;
use Illuminate\Http\Request;
use App\Http\Requests\CriteriaRequest;

class criteria_controller extends Controller
{
    /**
     * Konstruktor untuk menerapkan middleware otorisasi.
     * Hanya 'admin' yang dapat mengakses metode-metode di controller ini.
     */
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin']); // Memastikan hanya admin
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $criteria = criteria::orderByRaw('LENGTH(criteria_code), criteria_code')->get();
        return view('dashboard.criteria ', compact('criteria'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\CriteriaRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CriteriaRequest $request)
    {
        $data = $request->validated();
        criteria::create($data);
        return redirect()->back()->with('success', 'Kriteria berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\criteria  $criteria
     * @return \Illuminate\Http\Response
     */
    public function show(criteria $criteria)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\criteria  $criteria
     * @return \Illuminate\Http\Response
     */
    public function edit(criteria $criteria)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\CriteriaRequest  $request
     * @param  \App\Models\criteria  $criteria
     * @return \Illuminate\Http\Response
     */
    public function update(CriteriaRequest $request, criteria $criteria)
    {
        $criteria = criteria::findOrFail($request->id); // Pastikan mengambil instance yang benar
        $data = $request->validated();
        $criteria->update($data);
        return redirect()->back()->with('success', 'Kriteria berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  string  $criteria
     * @return \Illuminate\Http\Response
     */
    public function destroy(string $criteria)
    {
        criteria::findOrFail($criteria)->delete();
        return redirect()->back()->with('success', 'Kriteria berhasil dihapus!');
    }
}
