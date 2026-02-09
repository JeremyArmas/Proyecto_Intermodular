<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(Request $request){
        
        //Validaciones
        $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        $remember = $request->boolean('remember');

        if(Auth::attempt($request->only('email', 'password'), $remember)) {
            
            $request->session()->regenerate();

            //Redirecciona según el rol
            $user = Auth::user();

            if ($user->es_admin) {
                return redirect()->route('adminPanel');
            }

            return redirect()->route('home');
        }

        //Si falla vuelve con error
        return back()
            ->withErrors(['email' => 'Credenciales incorrectas.'])
            ->onlyInput('email');
    }

    public function logout(Request $request){
        Auth::logout();

        //Limpia las sesiones y el token por seguridad
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }


}
