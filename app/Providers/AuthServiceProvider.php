<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use App\Models\User;
use App\Models\HasilVikor;
use App\Models\criteria; // Ditambahkan
use App\Policies\UserPolicy;
use App\Policies\HasilVikorPolicy;
use App\Policies\CriteriaPolicy; // Ditambahkan
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;


class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        User::class => UserPolicy::class,
        HasilVikor::class => HasilVikorPolicy::class,
        criteria::class => CriteriaPolicy::class, 
        'App\Models\Alternatif' => 'App\Policies\AlternatifPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        Gate::define('reset-criteria', [CriteriaPolicy::class, 'reset']);
        Gate::define('manage-criteria-weights', [CriteriaPolicy::class, 'manageWeights']);
        // Gate untuk aksi spesifik lainnya
        Gate::define('access-dashboard', function ($user) {
            return $user->hasAnyRole(['admin', 'manager']) || 
                   $user->can('view-dashboard');
        });
    }
}