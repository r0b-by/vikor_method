<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use App\Models\User; // Pastikan ini di-import
use App\Policies\UserPolicy; // Pastikan ini di-import
use App\Models\HasilVikor;
use App\Policies\HasilVikorPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        User::class => UserPolicy::class, // <-- BARIS PENTING INI DITAMBAHKAN
        HasilVikor::class => HasilVikorPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        // PENTING: Jika Anda sudah memiliki metode 'approveRegistrations'
        // dan 'rejectRegistrations' di UserPolicy.php (seperti yang saya sarankan sebelumnya),
        // maka Anda bisa MENGHAPUS definisi Gate ini.
        // Jika tidak, biarkan ini di sini atau pindahkan ke UserPolicy.
        // Rekomendasi saya adalah pindahkan ke UserPolicy untuk konsistensi.

        // Contoh cara memindahkan ke UserPolicy:
        // Di UserPolicy.php:
        // public function approveRegistrations(User $user): bool { return $user->hasRole('admin'); }
        // public function rejectRegistrations(User $user): bool { return $user->hasRole('admin'); }

        // Jika Anda pindahkan, hapus baris Gate::define di bawah ini:
        // Gate::define('approve registrations', function ($user) {
        //     return $user->hasRole('admin');
        // });

        // Gate::define('reject registrations', function ($user) {
        //     return $user->hasRole('admin');
        // });
    }
}