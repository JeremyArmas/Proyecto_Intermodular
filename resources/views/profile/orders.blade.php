@extends('layouts.app')

@section('title', 'Mis Pedidos - Jediga')

@section('content')
<div class="container py-5">
    <div class="row mb-4">
        <div class="col-12 text-center text-md-start">
            <h1 class="display-5 fw-bold text-white mb-2 jg-page-title">
                Mis <span class="jg-sun jg-bg-sun-alpha px-2 py-1 rounded">Pedidos</span>
            </h1>
            <p class="text-white opacity-75 lead jg-lead-light">Visualiza el historial completo de tus compras.</p>
        </div>
    </div>

    @if($orders->count() > 0)
        <div class="card jg-card border-0 shadow-sm mb-4">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-dark table-hover align-middle mb-0 jg-table">
                        <thead class="bg-darker">
                        <tr>
                            <th scope="col" class="text-white opacity-75 text-uppercase small py-3 jg-tracking-1"># Pedido</th>
                            <th scope="col" class="text-white opacity-75 text-uppercase small py-3 jg-tracking-1">Fecha Generada</th>
                            <th scope="col" class="text-white opacity-75 text-uppercase small py-3 jg-tracking-1">Valor Total</th>
                            <th scope="col" class="text-white opacity-75 text-uppercase small py-3 text-center jg-tracking-1">Estado</th>
                            <th scope="col" class="text-white opacity-75 text-uppercase small py-3 text-center pe-4 jg-tracking-1">Ver Detalle</th>
                            <th scope="col" class="text-white opacity-75 text-uppercase small py-3 text-center pe-4 jg-tracking-1">Descargar</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($orders as $order)
                            <!-- Fila Principal del Pedido -->
                            <tr style="border-bottom: 1px solid rgba(255,255,255,0.05); transition: background-color 0.2s;" onmouseover="this.style.backgroundColor='rgba(255,255,255,0.03)'" onmouseout="this.style.backgroundColor='transparent'">
                                <td class="ps-4 py-4">
                                    <span class="text-white fw-bold opacity-75 fs-6">{{ str_pad($order->id, 8, '0', STR_PAD_LEFT) }}</span>
                                </td>
                                <td class="py-4">
                                    <span class="text-white">{{ $order->created_at->format('d/m/Y') }}</span>
                                </td>
                                <td class="py-4">
                                    <span class="fw-bold" style="color: #00ff9d;">{{ \App\Services\CurrencyService::format($order->total_amount) }}</span>
                                </td>
                                <td class="py-4 text-center">
                                    @if($order->status === 'paid')
                                        <span class="badge jg-badge-status jg-badge-paid w-100 py-2 shadow-sm">Completado</span>
                                    @elseif($order->status === 'pending')
                                        <span class="badge jg-badge-status jg-badge-pending w-100 py-2 shadow-sm">Pendiente</span>
                                    @elseif($order->status === 'shipped')
                                        <span class="badge jg-badge-status jg-badge-shipped w-100 py-2 shadow-sm">Enviado</span>
                                    @elseif($order->status === 'cancelled')
                                        <span class="badge jg-badge-status jg-badge-cancelled w-100 py-2 shadow-sm">Cancelado</span>
                                    @endif
                                </td>
                                <td class="py-4 text-center pe-4">
                                    <button class="btn btn-sm jg-btn jg-btn-outline" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOrder{{ $order->id }}" aria-expanded="false" aria-controls="collapseOrder{{ $order->id }}" title="Ver detalles">
                                        <i class="bi bi-box-arrow-up-right"></i>
                                    </button>
                                </td>
                                <td class="py-4 text-center pe-4"> <!-- Boton para descargar la factura -->
                                    <a href="{{ route('profile.orders.download', $order->id) }}" class="btn btn-sm jg-btn jg-btn-outline" target="_blank" title="Descargar"> <!--Creamos un enlace para que usa el ID del pedido para descargar la factura-->
                                        <i class="bi bi-download"></i>
                                    </a>
                                </td>
                            </tr>
                            
                            <!-- Fila Desplegable con los Detalles -->
                            <tr class="collapse" id="collapseOrder{{ $order->id }}">
                                <td colspan="6" class="p-0 border-0 bg-darker">
                                    <div class="p-4" style="background: rgba(0,0,0,0.15); box-shadow: inset 0 5px 15px rgba(0,0,0,0.2);">
                                        
                                        <!-- Lista vertical de juegos -->
                                        <div class="d-flex flex-column gap-2 mb-3">
                                            @foreach($order->items as $item)
                                                <div class="d-flex align-items-center p-3 rounded" style="background: rgba(255,255,255,0.03); border-left: 3px solid var(--jg-sun); transition: background 0.2s;" onmouseover="this.style.background='rgba(255,255,255,0.06)'" onmouseout="this.style.background='rgba(255,255,255,0.03)'">
                                                    <div class="shrink-0 shadow-sm rounded overflow-hidden" style="width: 55px; height: 75px;">
                                                        @if($item->game->cover_image)
                                                            <img src="{{ asset('storage/' . $item->game->cover_image) }}" alt="{{ $item->game->title }}" class="w-100 h-100 object-fit-cover">
                                                        @else
                                                            <div class="w-100 h-100 d-flex align-items-center justify-content-center text-muted bg-dark"><i class="bi bi-controller"></i></div>
                                                        @endif
                                                    </div>
                                                    <div class="grow ms-4">
                                                        <h6 class="text-white fw-bold mb-1 fs-5" style="letter-spacing: 0.5px;">{{ $item->game->title }}</h6>
                                                        <div class="text-white opacity-50 small">Precio unitario: {{ \App\Services\CurrencyService::format($item->price_at_purchase) }} &nbsp;&bull;&nbsp; Cantidad x{{ $item->quantity }}</div>
                                                    </div>
                                                    <div class="text-white fw-bold fs-5 px-3">
                                                        {{ \App\Services\CurrencyService::format($item->price_at_purchase * $item->quantity) }}
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>

                                        <!-- Dirección -->
                                        @if($order->shipping_address)
                                            <div class="border-top border-secondary border-opacity-25 pt-3 d-flex align-items-center">
                                                <div class="bg-dark p-2 rounded-circle me-3 border border-secondary border-opacity-25 shadow-sm d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                                                    <i class="bi bi-geo-alt-fill text-sun" style="font-size: 0.9rem;"></i>
                                                </div>
                                                <div>
                                                    <span class="text-white opacity-50 small text-uppercase" style="letter-spacing: 1px;">Enviado a:</span>
                                                    <span class="text-white small ms-2 fw-bold opacity-75">{{ $order->shipping_address }}</span>
                                                </div>
                                            </div>
                                        @endif
                                        <!--Apartado para el rastreo de los pedidos (Solo para las empresas)-->
                                        @if($order->order_type == 'b2b')
                                            <div class="border-top border-secondary border-opacity-25 pt-3 d-flex flex-column gap-3"> <!--Apartado para el rastreo de los pedidos (Solo para las empresas)-->
                                                <div class="d-flex align-items-center gap-2">
                                                    <i class="bi bi-truck text-sun fs-5"></i>
                                                    <span class="text-white fw-bold small text-uppercase" style="letter-spacing: 1px;">Rastreo del pedido</span>
                                                </div>

                                                <div class="table-responsive bg-dark rounded border border-secondary mb-3"> 
                                                    <table class="table table-dark table-borderless mb-0"><!--Tabla para mostrar el rastreo del pedido-->
                                                        <tbody>
                                                            <tr>
                                                                <td class="{{ $order->tracking_status == 'in_warehouse' ? 'text-sun fw-bold' : 'text-white opacity-50' }}">En el almacén</td> <!--Estado del pedido , en el almacén-->
                                                            </tr>
                                                            <tr>
                                                                <td class="{{ $order->tracking_status == 'shipped_out' ? 'text-sun fw-bold' : 'text-white opacity-50' }}">Salió del almacén</td> <!--Estado del pedido , salió del almacén-->
                                                            </tr>
                                                            <tr>
                                                                <td class="{{ $order->tracking_status == 'with_courier' ? 'text-sun fw-bold' : 'text-white opacity-50' }}">Lo acaba de recibir la empresa de reparto local</td> <!--Estado del peido , lo acaba de recibir la empresa de reparto local-->
                                                            </tr>
                                                            <tr>
                                                                <td class="{{ $order->tracking_status == 'on_the_way' ? 'text-sun fw-bold' : 'text-white opacity-50' }}">En camino hacia el cliente</td> <!--Estado del pedido , en camino hacia el cliente-->
                                                            </tr>
                                                            <tr>
                                                                <td class="{{ $order->tracking_status == 'delivered' ? 'text-sun fw-bold' : 'text-white opacity-50' }}">Entregado</td> <!--Estado del pedido , entregado-->
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>

                                                <div class="border-top border-secondary border-opacity-25 pt-3 d-flex align-items-center"> <!--Apartado para confirmar la entrega-->
                                                    <div>
                                                        @if($order->delivered_confirmed_at) <!--Si el pedido ha sido entregado-->
                                                            <span class="text-white opacity-50 small text-uppercase" style="letter-spacing: 1px;">Entregado el:</span> 
                                                            <span class="text-white small ms-2 fw-bold opacity-75">{{ $order->delivered_confirmed_at->format('d/m/Y H:i:s') }}</span> <!--Fecha en la que el usuario confirma la entrega-->
                                                        @else
                                                        <form action="{{ route('profile.orders.confirm', $order->id) }}" method="POST">
                                                            @csrf
                                                            <button type="submit" class="btn btn-primary jg-btn jg-btn-sun" {{ $order->tracking_status != 'delivered' ? 'disabled' : '' }}>Confirmar entrega</button> <!--Botón para confirmar la entrega-->
                                                        </form>
                                                        @endif
                                                    </div>

                                                    <div class="small text-white opacity-50 ms-2 fw-bold">
                                                        ¿Tienes problemas con tu pedido? <a href="{{ route('contacto') }}" class='text-sun'>Contacta con soporte.</a> <!--Apartado para contactar con soporte-->
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @else
        <!-- Sin pedidos -->
        <div class="row mt-5">
            <div class="col-12">
                <div class="card jg-card border-0 text-center py-5 shadow-sm">
                    <div class="py-4">
                        <div class="d-inline-flex p-4 rounded-circle mb-4 bg-dark">
                            <i class="bi bi-bag-x text-muted" style="font-size: 3rem;"></i>
                        </div>
                        <h2 class="text-white fw-bold mb-3 jg-page-title">Aún no tienes ningún pedido</h2>
                        <p class="text-white opacity-75 mb-5 mx-auto" style="max-width: 500px;">Aún no tienes facturas ni pedidos generados en tu historial.</p>
                        <a href="{{ url('/catalogo') }}" class="btn jg-btn jg-btn-sun btn-lg px-5 border-0 shadow" style="letter-spacing: 1px;">
                            Explorar Catálogo <i class="bi bi-arrow-right ms-2"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>


@endsection
