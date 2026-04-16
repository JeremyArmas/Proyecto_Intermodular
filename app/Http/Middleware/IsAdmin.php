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
        // Verifica si hay un administrador autenticado usando el guard 'admin'
        if (Auth::guard('admin')->check()) {
            return $next($request);
        }

        // Si no está autenticado como admin, redirige al inicio con un error
        return redirect('/')->with('error', 'Acceso denegado. Se requieren permisos de administrador.');
    }
}
