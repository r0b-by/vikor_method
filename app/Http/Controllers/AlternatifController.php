<?php

namespace App\Http\Controllers;

use App\Models\alternatif;
use Illuminate\Http\Request;
use App\Http\Requests\AlternatifRequest;

class AlternatifController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $alternatif = alternatif::orderByRaw('LENGTH(alternatif_code), alternatif_code')->get();
        return view('dashboard.alternatif', compact('alternatif'));
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
    public function store(AlternatifRequest $request)
    {
        $data = $request->validated();
        alternatif::create($data);
        return redirect()-> back();
    }

    /**
     * Display the specified resource.
     */
    public function show(alternatif $alternatif)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(alternatif $alternatif)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(AlternatifRequest $request, alternatif $alternatif)
    {
        $alternatif = alternatif::findOrFail($request->id);
        $data = $request->validated();
        $alternatif -> update($data);
        return redirect()-> back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $alternatif)
    {
        alternatif::find($alternatif)->delete();
        return redirect()-> back();
    }
}
