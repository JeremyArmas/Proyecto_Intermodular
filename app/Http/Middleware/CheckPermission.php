<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class CheckPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $permission): Response
    {
        // El guard 'admin' debe estar autenticado
        $admin = Auth::guard('admin')->user();

        if (!$admin || !$admin->hasPermission($permission)) {
            if ($request->expectsJson()) {
                return response()->json(['error' => 'No tienes permisos para realizar esta acción.'], 403);
            }
            return redirect()->route('admin.panel')->with('error', 'No tienes permisos para realizar esta acción.');
        }

        return $next($request);
    }
}
