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

    // Si el cliente intenta enviar otro mensaje antes de que pasen 30 segundos desde el último, se le muestra un error.
    if (session('bloqueo_spam') > now()) { 
        return back()->withInput()->with('error_spam', 'Has enviado demasiados mensajes, espera 30 segundos');
    }

    // Validación de los datos enviados por el cliente
    $datosValidados = $request->validate([
        'name' => 'required|string|max:100',
        'email' => 'required|email|max:100',
        'subject' => 'required|string|max:100',
        'message' => 'required|string|min:10|max:500',
    ]);

    // Se guarda el mensaje en la base de datos
    ContactMessage::create($datosValidados);

    // Se establece un bloqueo de 30 segundos para evitar que el cliente envíe mensajes de spam.
    session()->put('bloqueo_spam', now()->addSeconds(30));

    return back()->with('success', true);
}
}

