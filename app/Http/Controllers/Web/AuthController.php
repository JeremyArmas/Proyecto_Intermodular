<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
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

        // Intenta autenticar primero como usuario normal (web)
        $credentials = ['email' => $validated['email'], 'password' => $validated['password']];
        
        if (Auth::guard('web')->attempt($credentials, $remember)) {
            $user = Auth::guard('web')->user();
            $redirect = url('/'); // Default para usuarios
        } 
        // Si falla, intenta autenticar como administrador (admin)
        else if (Auth::guard('admin')->attempt($credentials, $remember)) {
            $user = Auth::guard('admin')->user();
            $redirect = route('admin.panel'); // Default para admins
        } 
        else {
            // Incrementa intentos fallidos en sesión si ambos fallan
            $intentos++;
            $request->session()->put('login_intentos', $intentos);

            $restantes = $this->maxIntentos - $intentos;

            // Bloqueo si se excede el máximo
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

            return response()->json([
                'success' => false,
                'message' => "Credenciales incorrectas. Te quedan {$restantes} intento(s).",
                'blocked' => false,
                'attempts_remaining' => $restantes,
                'show_captcha' => ($restantes === 1),
            ], 401);
        }

        // Si la autenticación fue exitosa (ya sea user o admin)
        $request->session()->forget(['login_intentos', 'login_bloqueado_hasta']);
        $request->session()->regenerate();

        return response()->json(['success' => true, 'redirect' => $redirect]);
    }

    // Función del logout: cierra sesión, limpia remember_token y regenera token CSRF
    // Función del logout
    public function logout(Request $request)
    {
        if (Auth::guard('admin')->check()) {
            Auth::guard('admin')->logout();
        } else {
            Auth::guard('web')->logout();
        }

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Si la petición espera JSON, devolver JSON; si no, redirigir a home
        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'redirect' => url('/')]);
        }

        return redirect('/');
    }
}
