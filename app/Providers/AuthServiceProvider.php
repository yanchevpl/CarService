<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        // Admin Level Access
        Gate::define('admin', function ($user) {
            if (Auth::user()->hasAnyRole(['admin'])) {
                return true;
            }
            return false;
        });

        // Employee Level Access
        Gate::define('employee', function ($user) {
            if (Auth::user()->hasAnyRole(['employee'])) {
                return true;
            }
            return false;
        });

        // Customer Level Access
        Gate::define('customer', function ($user) {
            if (Auth::user()->hasAnyRole(['customer'])) {
                return true;
            }
            return false;
        });

        $this->registerPolicies();
    }
}
