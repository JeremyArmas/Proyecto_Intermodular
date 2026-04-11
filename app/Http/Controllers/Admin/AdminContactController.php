<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

/*Controlador para que los admins puedan ver y responder los mensajes de contacto (Tickets) enviados por los clientes desde la web*/

class AdminContactController extends Controller
{
    public function index()
    {
        $tickets = \App\Models\ContactMessage::orderBy("created_at","desc")->paginate(10); // Se obtienen los tickets de contacto ordenados por fecha de creación (los más recientes primero) y se paginan de 10 en 10 para no saturar la vista.

        return view('admin.tickets.index', compact('tickets'));
    }

    public function show($id)
    {
        // Se obtiene el ticket por su ID, si el ticket está bloqueado por otro admin y no es el mismo admin el que intenta acceder,
        // se le muestra un mensaje de error indicando que el ticket lo está atendiendo otro compañero y que espere 5 minutos o busque
        // otro ticket para atender. Si el ticket no está bloqueado o está bloqueado pero es el mismo admin el que intenta acceder, se
        // bloquea el ticket para ese admin (se asigna su ID al campo admin_id y se establece la fecha/hora actual en locked_at) y se
        // muestra la vista con los detalles del ticket para que el admin pueda responderlo.

        $ticket = \App\Models\ContactMessage::findOrFail($id);
        if ($ticket->locked_at && $ticket->admin_id !==auth()->id()) {

            $minutesBloqueado = $ticket->locked_at->diffInMinutes(now());

            if ($minutesBloqueado < 5) {
                return redirect()->route('admin.tickets.index')->with('error', 'Este ticket lo está haciendo otro compañero , ve a buscar otro o espera 5 minutos.');
            }
        }

        $ticket->admin_id = auth()->id(); // Se asigna el ID del admin que atiende el ticket para bloquearlo
        $ticket->locked_at = now();
        $ticket->save();

        return view('admin.tickets.show', compact('ticket'));
    }

    public function update(Request $request, $id)
    {
        // Se valida que se haya ingresado una respuesta, se obtiene el ticket por su ID, si el ticket no está en estado "pendiente"
        // (lo que significa que ya ha sido respondido por otro admin), se redirige al panel de administración con un mensaje de error
        // indicando que el ticket ya ha sido respondido por otro compañero. Si el ticket está en estado "pendiente", se envía un email
        // al cliente con la respuesta del admin utilizando la clase RespondTicketMail, se actualiza el estado del ticket a "finalizado",
        // se asigna el ID del admin que respondió el ticket, se guarda la respuesta en el campo respuesta_email del ticket, se desbloquea
        // el ticket (se establece locked_at en null) y se redirige al panel de administración con un mensaje de éxito indicando que el email
        // se envió con éxito al cliente y que el ticket se cerró.

        $request->validate(['respuesta_email' => 'required']);
        $ticket = \App\Models\ContactMessage::findOrFail($id);
        if ($ticket->status !== 'pendiente') {
            return redirect()->route('admin.panel')->with('error', 'Este ticket ya ha sido respondido por otro compañero.');
        }
        \Illuminate\Support\Facades\Mail::to($ticket->email)->send(new \App\Mail\RespondTicketMail($request->respuesta_email));
        $ticket->status = 'finalizado';
        $ticket->admin_id = auth()->id();
        $ticket->respuesta_email = $request->respuesta_email;
        $ticket->locked_at = null;
        $ticket->save();
        return redirect()->route('admin.panel')->with('success', '¡Email enviado con éxito al cliente y ticket cerrado!');
    }

}
