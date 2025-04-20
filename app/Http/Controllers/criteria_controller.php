<?php

namespace App\Http\Controllers;

use App\Models\criteria;
use Illuminate\Http\Request;
use App\Http\Requests\CriteriaRequest;

class criteria_controller extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $criteria = criteria::orderByRaw('LENGTH(criteria_code), criteria_code')->get();
        return view('dashboard.criteria ', compact('criteria'));
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
    public function store(CriteriaRequest $request)
    {
        $data = $request->validated();
        criteria::create($data);
        return redirect()-> back();
    }

    /**
     * Display the specified resource.
     */
    public function show(criteria $criteria)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(criteria $criteria)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CriteriaRequest $request, criteria $criteria)
    {
        $criteria = criteria::findOrFail($request->id);
        $data = $request->validated();
        $criteria -> update($data);
        return redirect()-> back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $criteria)
    {
        criteria::find($criteria)->delete();
        // $criteria->delete();
        return redirect()-> back();
    }
}
