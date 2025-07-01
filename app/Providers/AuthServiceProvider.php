<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use App\Policies\HasilVikorPolicy;
use App\Models\HasilVikor;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        HasilVikor::class => HasilVikorPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        //
    }
}
