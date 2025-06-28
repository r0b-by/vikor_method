<?php

namespace App\Http\Controllers;

use App\Models\alternatif;
use App\Models\criteria;
use App\Models\User; // Import the User model
use App\Models\PendingProfileUpdate; // Import the PendingProfileUpdate model
use Illuminate\Http\Request; // Pastikan ini diimpor dengan benar
use Illuminate\Support\Facades\View; // Import View facade

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

        // Menggunakan View Composer untuk meneruskan data ke semua view
        // yang menggunakan layout dashboardmain
        View::composer('dashboard.layouts.dashboardmain', function ($view) {
            // Memastikan pengguna terautentikasi dan memiliki peran 'admin'
            // Pastikan model User menggunakan trait HasRoles dari Spatie
            if (auth()->check() && auth()->user()->hasRole('admin')) {
                // Menghitung jumlah pengguna dengan status 'pending'
                $pendingRegistrationsCount = User::where('status', 'pending')->count();
                // Menghitung jumlah perubahan profil dengan status 'pending'
                $pendingProfileUpdatesCount = PendingProfileUpdate::where('status', 'pending')->count();

                // Meneruskan jumlah ke view
                $view->with('pendingRegistrationsCount', $pendingRegistrationsCount);
                $view->with('pendingProfileUpdatesCount', $pendingProfileUpdatesCount);
            } else {
                // Default ke 0 untuk pengguna non-admin
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
        $latestAlternatif = Alternatif::orderBy('id', 'desc')->first();
        $latestCriteria = Criteria::orderBy('id', 'desc')->first();

        return view('dashboard.home', compact('latestAlternatif','latestCriteria'));
    }
}
