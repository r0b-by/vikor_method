<?php

namespace App\Http\Controllers;

use App\Models\alternatif;
use App\Models\criteria;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $latestAlternatif = Alternatif::orderBy('id', 'desc')->first();
        $latestCriteria = Criteria::orderBy('id', 'desc')->first();

        return view('dashboard.home', compact('latestAlternatif','latestCriteria'));
    }
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }
}
