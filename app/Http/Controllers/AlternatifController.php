<?php

namespace App\Http\Controllers;

use App\Models\alternatif;
use Illuminate\Http\Request;
use App\Http\Requests\AlternatifRequest;

class AlternatifController extends Controller
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
        $alternatif = alternatif::orderByRaw('LENGTH(alternatif_code), alternatif_code')->get();
        return view('dashboard.alternatif', compact('alternatif'));
    }

    /**
     * Show the form for creating a new resource.
     * (Biasanya tidak perlu implementasi jika menggunakan modal seperti di Blade)
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
     * @param  \App\Http\Requests\AlternatifRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AlternatifRequest $request)
    {
        $data = $request->validated();
        alternatif::create($data);
        return redirect()->back()->with('success', 'Alternatif berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     * (Biasanya tidak digunakan untuk CRUD sederhana, kecuali ada halaman detail)
     *
     * @param  \App\Models\alternatif  $alternatif
     * @return \Illuminate\Http\Response
     */
    public function show(alternatif $alternatif)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     * (Biasanya tidak perlu implementasi jika menggunakan modal seperti di Blade)
     *
     * @param  \App\Models\alternatif  $alternatif
     * @return \Illuminate\Http\Response
     */
    public function edit(alternatif $alternatif)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\AlternatifRequest  $request
     * @param  \App\Models\alternatif  $alternatif
     * @return \Illuminate\Http\Response
     */
    public function update(AlternatifRequest $request, alternatif $alternatif)
    {
        $alternatif = alternatif::findOrFail($request->id); // Pastikan mengambil instance yang benar
        $data = $request->validated();
        $alternatif->update($data);
        return redirect()->back()->with('success', 'Alternatif berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  string  $alternatif
     * @return \Illuminate\Http\Response
     */
    public function destroy(string $alternatif)
    {
        // Menggunakan findOrFail untuk memastikan alternatif ada sebelum dihapus
        alternatif::findOrFail($alternatif)->delete();
        return redirect()->back()->with('success', 'Alternatif berhasil dihapus!');
    }
}
