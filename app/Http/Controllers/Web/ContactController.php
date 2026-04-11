<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ContactMessage;

/*Controlador para manejar los mensajes de contacto enviados por los clientes desde la web*/

class ContactController extends Controller
{

    public function index()
    {
        return view('contacto');
    }

    public function store(Request $request)
{

    if (session('bloqueo_spam') > now()) { // Si el cliente intenta enviar otro mensaje antes de que pasen 30 segundos desde el último, se le muestra un error.
        return back()->withInput()->with('error_spam', 'Has enviado demasiados mensajes, espera 30 segundos');
    }

    $datosValidados = $request->validate([ // Validación de los datos enviados por el cliente
        'name' => 'required|string|max:100',
        'email' => 'required|email:rfc,dns|max:100',
        'subject' => 'required|string|max:100',
        'message' => 'required|string|min:10|max:500',
    ]);

    ContactMessage::create($datosValidados); // Se guarda el mensaje en la base de datos

    session()->put('bloqueo_spam', now()->addSeconds(30)); // Se establece un bloqueo de 30 segundos para evitar que el cliente envíe mensajes de spam.

    return back()->with('success', true);
}
}

