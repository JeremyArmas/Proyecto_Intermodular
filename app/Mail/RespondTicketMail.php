<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

/*Clase para enviar un email al cliente con la respuesta del admin cuando se responde un ticket de contacto desde el panel de administración*/

class RespondTicketMail extends Mailable
{
    use Queueable, SerializesModels;

    /*El constructor recibe la respuesta del admin como parámetro y la asigna a una propiedad pública para que esté disponible en la vista del email.
    El método envelope() define el asunto y el remitente del email, el método content() especifica la vista que se utilizará para el contenido del email
    y le pasa la respuesta del admin para que se muestre en el email, y el método attachments() devuelve un array vacío ya que no se adjunta ningún archivo en este caso.*/

    public function __construct(public string $respuesta)
    {

    }


    public function envelope(): Envelope // Se define el asunto y el remitente del email
    {
        return new Envelope(
            subject: 'Respuesta a tu mensaje de soporte - Por Jediga',
            from: 'jedigasupport@gmail.com',
        );
    }

    public function content(): Content // Se especifica la vista que se utilizará para el contenido del email y se le pasa la respuesta del admin para que se muestre en el email.
    {
        return new Content(
            markdown: 'emails.respuesta-ticket',
            with: [ 'respuesta' => $this->respuesta ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
