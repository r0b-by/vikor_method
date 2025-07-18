<?php

namespace App\Http\Controllers;

use App\Models\alternatif;
use App\Models\criteria;
use App\Models\User;
use App\Models\PendingProfileUpdate; 
use App\Models\penilaian; 
use App\Models\HasilVikor; 
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');

        View::composer('dashboard.layouts.dashboardmain', function ($view) {
            if (auth()->check() && auth()->user()->hasRole('admin')) {
                $pendingRegistrationsCount = User::where('status', 'pending')->count();
                $pendingProfileUpdatesCount = PendingProfileUpdate::where('status', 'pending')->count();

                $view->with('pendingRegistrationsCount', $pendingRegistrationsCount);
                $view->with('pendingProfileUpdatesCount', $pendingProfileUpdatesCount);
            } else {
                $view->with('pendingRegistrationsCount', 0);
                $view->with('pendingProfileUpdatesCount', 0);
            }
        });
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        // Fetch counts for all relevant data
        $alternatifCount = Alternatif::count();
        $criteriaCount = Criteria::count();
        $penilaianCount = Penilaian::count();
        $hasilVikorCount = HasilVikor::count();
        $userCount = User::count();

        // The existing latestAlternatif and latestCriteria are for displaying specific details,
        // but for total counts, use the count() method as above.
        // If you still need 'latest' instances for other purposes on the dashboard:
        $latestAlternatif = Alternatif::orderBy('id', 'desc')->first();
        $latestCriteria = Criteria::orderBy('id', 'desc')->first();

        return view('dashboard.home', compact(
            'alternatifCount',
            'criteriaCount',
            'penilaianCount',
            'hasilVikorCount',
            'userCount',
            'latestAlternatif', // Keep if still needed for other displays
            'latestCriteria'    // Keep if still needed for other displays
        ));
    }
    public function showSiswaDashboard()
    {
        $user = Auth::user();
        $alternatif = $user->alternatif; // Pastikan relasi ini ada di model User

        $hasilVikor = null;
        if ($alternatif) {
            $hasilVikor = HasilVikor::where('id_alternatif', $alternatif->id)->first();
        }

        return view('siswa.dashboard', compact('alternatif', 'hasilVikor'));
    }
}