<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    // Máximo de intentos permitidos antes de bloqueo
    private int $maxIntentos = 3;

    // Minutos que dura el bloqueo cuando se exceden los intentos
    private int $minutosBloqueo = 3;

    // Función del login: valida credenciales, gestiona intentos, captcha y bloqueo temporal
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
        // Comprueba si la sesión tiene un bloqueo activo y si sigue vigente
        $bloqueadoHasta = $request->session()->get('login_bloqueado_hasta');
        if ($bloqueadoHasta && now()->lt($bloqueadoHasta)) {
            // Calcula minutos restantes (redondeando hacia arriba)
            $mins = (int) ceil(now()->diffInSeconds($bloqueadoHasta) / 60);

            // Respuesta JSON indicando bloqueo (HTTP 423 Locked)
            return response()->json([
                'success' => false,
                'message' => "Demasiados intentos. Inténtalo de nuevo en {$mins} min.",
                'blocked' => true,
                'minutes_remaining' => $mins,
                'show_captcha' => false,
            ], 423);
        }

        // Obtiene el número de intentos fallidos almacenados en sesión (antes del intento actual)
        $intentos = (int) $request->session()->get('login_intentos', 0);

        // Requiere captcha si ya se han fallado (maxIntentos - 1) intentos (es decir, queda 1 intento)
        $captchaRequerido = $intentos >= ($this->maxIntentos - 1);

        // Reglas base de validación
        $rules = [
            'email'    => ['required', 'email'],
            'password' => ['required', 'string'],
        ];

        // Añade regla de captcha si procede
        if ($captchaRequerido) {
            $rules['captcha'] = ['required', 'captcha'];
        }

        // Valida la request y define mensajes de error en español
        $validated = $request->validate($rules, [
            'email.required' => 'El email es obligatorio',
            'email.email' => 'El email debe ser válido',
            'password.required' => 'La contraseña es obligatoria',
            'captcha.required' => 'El captcha es obligatorio',
            'captcha.captcha' => 'El captcha no es correcto',
        ]);

        // Si el usuario pidió "recordarme"
        $remember = $request->boolean('remember');

        // Intenta autenticar con las credenciales validadas
        if (!Auth::attempt(['email' => $validated['email'], 'password' => $validated['password']], $remember)) {
            // Incrementa intentos fallidos en sesión
            $intentos++;
            $request->session()->put('login_intentos', $intentos);

            $restantes = $this->maxIntentos - $intentos;

            // Si se excede el máximo, establece bloqueo temporal y borra el contador de intentos
            if ($intentos >= $this->maxIntentos) {
                $request->session()->put('login_bloqueado_hasta', now()->addMinutes($this->minutosBloqueo));
                $request->session()->forget('login_intentos');

                return response()->json([
                    'success' => false,
                    'message' => "Demasiados intentos. Bloqueado {$this->minutosBloqueo} min.",
                    'blocked' => true,
                    'minutes_remaining' => $this->minutosBloqueo,
                    'show_captcha' => false,
                ], 423);
            }

            // Si no está bloqueado pero queda 1 intento, indicar al front que muestre captcha
            return response()->json([
                'success' => false,
                'message' => "Credenciales incorrectas. Te quedan {$restantes} intento(s).",
                'blocked' => false,
                'attempts_remaining' => $restantes,
                'show_captcha' => ($restantes === 1),
            ], 401);
        }

        // Si la autenticación es correcta: limpiar datos de sesión relacionados con login y regenerar
        $request->session()->forget(['login_intentos', 'login_bloqueado_hasta']);
        $request->session()->regenerate();

        // Redirección según rol del usuario autenticado
        $user = Auth::user();
        $redirect = match ($user->role) {
            'admin'   => route('admin.panel'),
            'admin'   => route('admin.panel'),
            'company' => url('/'),
            'client'  => url('/'),
            default   => url('/'),
        };

        // Respuesta JSON indicando éxito y URL de redirección
        return response()->json(['success' => true, 'redirect' => $redirect]);
    }

    // Función del logout: cierra sesión y regenera token CSRF
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();      // Invalida la sesión actual
        $request->session()->regenerateToken(); // Regenera token CSRF

        // Si la petición espera JSON, devolver JSON; si no, redirigir a /
        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'redirect' => url('/')]);
        }

        return redirect('/');
    }
}
