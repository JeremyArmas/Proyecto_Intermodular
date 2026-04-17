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

        // Compartir variables de autenticación con todas las vistas
        view()->composer('*', function ($view) {
            $isAdmin = auth()->guard('admin')->check();
            $isWebActive = auth()->guard('web')->check();
            $user = $isAdmin ? auth()->guard('admin')->user() : ($isWebActive ? auth()->guard('web')->user() : null);
            
            $view->with([
                'isAdmin' => $isAdmin,
                'isWebActive' => $isWebActive,
                'anyAuth' => $isAdmin || $isWebActive,
                'user' => $user
            ]);
        });

        // Puerta para gestionar administradores (Solo Super Admin)
        Gate::define('manage-administrators', function ($user) {
            // Verificamos si es un administrador y si es super admin
            return $user instanceof Administrator && $user->is_super_admin;
        });
    }
}
