<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class IsAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Verifica si el usuario está autenticado y tiene rol de administrador
        if (Auth::check() && Auth::user()->isAdmin()) {
            return $next($request);
        }

        // Si no es admin, redirige al inicio con un error
        return redirect('/')->with('error', 'Acceso denegado. Se requieren permisos de administrador.');
    }
}
