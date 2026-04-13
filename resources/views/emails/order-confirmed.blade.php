<x-mail::message>
# ¡Pago Completado con Éxito!

Hola **{{ $order->user->name }}**,

¡Gracias por tu compra en **Jediga**! Hemos procesado tu pago correctamente y tu pedido ya está en marcha.
Aquí tienes los detalles de tu adquisición:

<x-mail::panel>
**ID Pedido:** #{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}<br>
**Fecha:** {{ $order->created_at->format('d/m/Y H:i') }}<br>
**Tipo de Pedido:** {{ strtoupper($order->order_type) }}<br>
**Dirección de Envío:** {{ $order->shipping_address ?? 'Digital / No especificada' }}
</x-mail::panel>

### Resumen de la compra

<x-mail::table>
| Artículo       | Cantidad | Precio Unitario | Subtotal |
| :---           | :---:    | :---:           | :---:    |
@foreach($order->items as $item)
| **{{ $item->game->title }}**<br><span style="color: #6c757d; font-size: 0.85em;">{{ $item->game->platform->name ?? 'Varias' }}</span> | {{ $item->quantity }}x | {{ number_format($item->price_at_purchase, 2) }}€ | {{ number_format($item->price_at_purchase * $item->quantity, 2) }}€ |
@endforeach
| &nbsp; | &nbsp; | **TOTAL** | **{{ number_format($order->total_amount, 2) }}€** |
</x-mail::table>

Si deseas revisar el estado actual de este y otros pedidos, visita tu historial de compras desde tu perfil en la plataforma:

<x-mail::button :url="route('profile.orders')" color="success">
Ver Mis Pedidos
</x-mail::button>

Gracias por formar parte de la comunidad Jediga.<br>
**{{ config('app.name') }}**
</x-mail::message>
