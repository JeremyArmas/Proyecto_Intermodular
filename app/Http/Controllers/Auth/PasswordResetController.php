<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

class PasswordResetController extends Controller
{
    /**
     * Recibe el formulario con el email del usuario, genera un token
     * y le envía el correo con el enlace de recuperación.
     *
     * Password::sendResetLink() se encarga de: buscar al usuario por email,
     * crear el token en la tabla `password_reset_tokens` y enviar la notificación.
     */
    public function sendResetLinkEmail(Request $request)
    {
        $request->validate(
            ['email' => 'required|email'],
            ['email.required' => 'El email es obligatorio.', 'email.email' => 'Introduce un email válido.']
        );

        $status = Password::sendResetLink(
            $request->only('email')
        );

        // Password::RESET_LINK_SENT  → el email se envió correctamente
        // Password::INVALID_USER     → no existe ningún usuario con ese email
        // En ambos casos devolvemos el mismo mensaje al usuario para no
        // revelar si el email está registrado o no (seguridad).
        return $status === Password::RESET_LINK_SENT
            ? back()->with('status', '¡Enlace enviado! Revisa tu bandeja de entrada.')
            : back()->withErrors(['email' => '¡Enlace enviado! Revisa tu bandeja de entrada.']);
    }

    /**
     * Recibe el token + email + nueva contraseña, los valida contra la BD
     * y actualiza la contraseña del usuario.
     *
     * Password::reset() verifica que: el token sea válido, no haya expirado
     * (config/auth.php → passwords.users.expire) y que el email coincida.
     * Si todo va bien, ejecuta el closure donde actualizamos la contraseña.
     */
    public function reset(Request $request)
    {
        $request->validate([
            'token'    => 'required',
            'email'    => 'required|email',
            'password' => 'required|min:8|confirmed',
        ], [
            'token.required'         => 'El token de recuperación no es válido.',
            'email.required'         => 'El email es obligatorio.',
            'email.email'            => 'Introduce un email válido.',
            'password.required'      => 'La nueva contraseña es obligatoria.',
            'password.min'           => 'La contraseña debe tener al menos 8 caracteres.',
            'password.confirmed'     => 'Las contraseñas no coinciden.',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            
            function (User $user, string $password) {

                // forceFill ignora $fillable, necesario porque 'password' usa cast 'hashed'
                // y aquí lo hacemos manualmente con Hash::make para ser explícitos.
                $user->forceFill([
                    'password'       => Hash::make($password),
                    'remember_token' => Str::random(60), // Invalida todas las sesiones "recuérdame"
                ])->save();

                // Dispara el evento PasswordReset por si hay listeners (logs, notificaciones, etc.)
                event(new PasswordReset($user));
            }
        );

        // Si fue bien, redirigimos al home (donde está el modal de login) con un mensaje de éxito.
        return $status === Password::PASSWORD_RESET
            ? redirect()->route('home')->with('password_reset_success', '¡Contraseña actualizada! Ya puedes iniciar sesión.')
            : back()->withErrors(['email' => __($status)]);
    }
}
