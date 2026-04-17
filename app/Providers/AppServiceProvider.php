<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;

use Illuminate\Support\Facades\Gate;
use App\Models\Administrator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::useBootstrap();

        // Puerta para gestionar administradores (Solo Super Admin)
        Gate::define('manage-administrators', function ($user) {
            // Verificamos si es un administrador y si es super admin
            return $user instanceof Administrator && $user->is_super_admin;
        });
    }
}
