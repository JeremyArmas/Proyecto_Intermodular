<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    private int $maxIntentos = 3;
    private int $minutosBloqueo = 3;

    //Función del login
    public function login(Request $request)
    {
        //Valida cada campo del lógin
        $validated = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required', 'string'],
        ], [
            'email.required' => 'El email es obligatorio',
            'email.email' => 'El email debe ser válido',
            'password.required' => 'La contraseña es obligatoria',
        ]);

        //Revisa en la session si el usuario está bloqueado y cuanto lleva de bloqueo
        $bloqueadoHasta = $request->session()->get('login_bloqueado_hasta'); 
        if ($bloqueadoHasta && now()->lt($bloqueadoHasta)) {
            $mins = (int) ceil(now()->diffInSeconds($bloqueadoHasta) / 60);

            return response()->json([
                'success' => false,
                'message' => "Demasiados intentos. Inténtalo de nuevo en {$mins} min.",
                'blocked' => true,
                'minutes_remaining' => $mins,
            ], 423);
        }

        //Recoge de la session el contador de fallos que se tenga
        $intentos = (int) $request->session()->get('login_intentos', 0);

        //Intenta verificar las credenciales con las de base de datos, si no lo consigue acumula intentos
        $remember = $request->boolean('remember');
        if (!Auth::attempt(['email' => $validated['email'], 'password' => $validated['password']], $remember)) {
            $intentos++;
            $request->session()->put('login_intentos', $intentos);

            //Si llegó al maximo de intentos establece los 5 minutos de espera y reinicia los intentos
            if ($intentos >= $this->maxIntentos) {
                $request->session()->put('login_bloqueado_hasta', now()->addMinutes($this->minutosBloqueo));
                $request->session()->forget('login_intentos');

                return response()->json([
                    'success' => false,
                    'message' => "Demasiados intentos. Bloqueado {$this->minutosBloqueo} min.",
                    'blocked' => true,
                    'minutes_remaining' => $this->minutosBloqueo,
                ], 423);
            }

            //Si no llegó al limite canta el mensaje de correción con los intentos restantes
            return response()->json([
                'success' => false,
                'message' => "Credenciales incorrectas. Te quedan " . ($this->maxIntentos - $intentos) . " intento(s).",
                'blocked' => false,
                'attempts_remaining' => ($this->maxIntentos - $intentos),
            ], 401);
        }

        //Limpia los intentos y el tiempo de espera si consiguió loguearse
        $request->session()->forget(['login_intentos', 'login_bloqueado_hasta']); // <-- misma clave
        $request->session()->regenerate();

        //Redirige al usuario según el rol que tenga
        $user = Auth::user();
        $redirect = match ($user->role) {
            'admin'   => route('admin.panel'),
            'company' => url('/'),
            'client'  => url('/'),
            default   => url('/'),
        };
        
        return response()->json([
            'success' => true,
            'redirect' => $redirect,
        ]);
    }

    //Función del logout
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'redirect' => url('/')]);
        }

        return redirect('/');
    }
}
