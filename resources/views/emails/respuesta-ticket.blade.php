<x-mail::message>
# Hemos respondido a tu mensaje

Hola,

Gracias por ponerte en contacto con el equipo de **Jediga**. Hemos revisado tu mensaje y aquí tienes nuestra respuesta:

<x-mail::panel>
{{ $respuesta }}
</x-mail::panel>

Si tienes alguna duda adicional o necesitas más información, no dudes en volver a escribirnos a través de nuestro formulario de contacto.

<x-mail::button :url="route('contacto')" color="success">
Volver a Contacto
</x-mail::button>

Un saludo y gracias por ser parte de la comunidad Jediga.<br>
**{{ config('app.name') }} — Soporte**
</x-mail::message>
