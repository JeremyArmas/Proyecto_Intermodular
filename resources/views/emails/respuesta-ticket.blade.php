<x-mail::message>
# Respuesta a tu ticket de soporte

Hola, nuestro equipo ha revisado y atendido tu consulta:

<x-mail::panel>
{{ $respuesta }}
</x-mail::panel>

Gracias por confiar en nosotros,<br>
**{{ config('app.name') }}**
</x-mail::message>
