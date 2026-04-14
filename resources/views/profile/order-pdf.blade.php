<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Recibo del pedido #{{ str_pad($order->id, 8, '0', STR_PAD_LEFT) }}</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 14px; }
        h1 { color: #333; margin-bottom: 5px; }
        .header-info { margin-bottom: 30px; color: #555; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 12px; text-align: left; vertical-align: top; }
        th { background: #f8f9fa; font-weight: bold; }
        .game-title { margin: 0 0 5px 0; font-size: 16px; color: #111; }
        .game-desc { font-size: 11px; color: #666; line-height: 1.4; margin: 0; }
        .total-box { margin-top: 30px; text-align: right; font-size: 18px; }
    </style>
</head>
<body>
    <h1>Comprobante de Pedido #{{ str_pad($order->id, 8, '0', STR_PAD_LEFT) }}</h1>
    <div class="header-info">
        <p style="margin: 0;"><strong>Fecha:</strong> {{ $order->created_at->format('d/m/Y - H:i') }}</p>
        <p style="margin: 5px 0 0 0;"><strong>Usuario:</strong> {{ auth()->user()->name }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th width="15%" style="text-align: center;">Portada</th>
                <th width="50%">Juego y Descripción</th>
                <th width="15%" style="text-align: center;">Cantidad</th>
                <th width="20%" style="text-align: right;">Precio</th>
            </tr>
        </thead>
        <tbody>
            @foreach($order->items as $item)
            <tr>
                <td style="text-align: center;">
                    @if($item->game->cover_image)
                        {{-- Convertimos la imagen a Base64 para que el PDF la incruste sin fallos --}}
                        @php
                            $path = public_path('storage/' . $item->game->cover_image);
                            $base64 = '';
                            if (file_exists($path)) {
                                $type = pathinfo($path, PATHINFO_EXTENSION);
                                $data = file_get_contents($path);
                                $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
                            }
                        @endphp
                        
                        @if($base64)
                            <img src="{{ $base64 }}" width="60" style="border-radius: 4px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                        @else
                            <span style="font-size: 10px; color: #999;">Sin portada</span>
                        @endif
                    @else
                        <span style="font-size: 10px; color: #999;">Sin portada</span>
                    @endif
                </td>
                <td>
                    <h3 class="game-title">{{ $item->game->title }}</h3>
                    <p class="game-desc">
                        {{ \Illuminate\Support\Str::limit($item->game->description, 150) }}
                    </p>
                </td>
                <td style="text-align: center;">
                    x{{ $item->quantity }}
                </td>
                <td style="text-align: right; font-weight: bold;">
                    {{ number_format($item->price_at_purchase, 2) }} €
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="total-box">
        <strong>Total pagado: <span style="color: #28a745;">{{ number_format($order->total_amount, 2) }} €</span></strong>
    </div>

    <div style="margin-top: 50px; text-align: center; font-size: 10px; color: #000000ff;">
        <img src="{{ public_path('images/logo_jediga_provisional.png') }}" alt="Logo Jediga" width="100">
        <p>Gracias por tu compra en <strong>Jediga</strong>. Tus compras siempre estarán disponibles en tu perfil :D</p>
    </div>

</body>
</html>
