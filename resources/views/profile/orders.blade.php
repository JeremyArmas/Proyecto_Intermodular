@extends('layouts.app')

@section('title', 'Mis Pedidos - Jediga')

@section('content')
<div class="container py-5">
    <div class="row mb-4">
        <div class="col-12 text-center text-md-start">
            <h1 class="display-5 fw-bold text-white mb-2" style="font-family: var(--jg-font-title); letter-spacing: 1px;">
                Mis <span class="jg-sun px-2 py-1 rounded" style="background: rgba(255, 204, 0, 0.1);">Pedidos</span>
            </h1>
            <p class="text-white opacity-75 lead" style="font-weight: 300;">Visualiza el historial completo de tus compras.</p>
        </div>
    </div>

    @if($orders->count() > 0)
        <div class="card jg-card border-0 shadow-sm mb-4">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-dark table-hover align-middle mb-0 jg-table">
                        <thead class="bg-darker">
                        <tr>
                            <th scope="col" class="text-white opacity-75 text-uppercase small py-3 ps-4" style="letter-spacing: 1px;"># Pedido</th>
                            <th scope="col" class="text-white opacity-75 text-uppercase small py-3" style="letter-spacing: 1px;">Fecha Generada</th>
                            <th scope="col" class="text-white opacity-75 text-uppercase small py-3" style="letter-spacing: 1px;">Valor Total</th>
                            <th scope="col" class="text-white opacity-75 text-uppercase small py-3 text-center" style="letter-spacing: 1px;">Estado</th>
                            <th scope="col" class="text-white opacity-75 text-uppercase small py-3 text-center pe-4" style="letter-spacing: 1px;">Ver Detalle</th>
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
                                        <span class="badge w-100 py-2 rounded-2 shadow-sm" style="background: rgba(0, 255, 157, 0.15); color: #00ff9d; border: 1px solid rgba(0, 255, 157, 0.3); font-size: 0.85rem; letter-spacing: 0.5px;">Completado</span>
                                    @elseif($order->status === 'pending')
                                        <span class="badge w-100 py-2 rounded-2 shadow-sm" style="background: rgba(255, 204, 0, 0.15); color: #ffcc00; border: 1px solid rgba(255, 204, 0, 0.3); font-size: 0.85rem; letter-spacing: 0.5px;">Pendiente</span>
                                    @elseif($order->status === 'shipped')
                                        <span class="badge w-100 py-2 rounded-2 shadow-sm" style="background: rgba(0, 195, 255, 0.15); color: #00c3ff; border: 1px solid rgba(0, 195, 255, 0.3); font-size: 0.85rem; letter-spacing: 0.5px;">Enviado</span>
                                    @elseif($order->status === 'cancelled')
                                        <span class="badge w-100 py-2 rounded-2 shadow-sm" style="background: rgba(255, 71, 87, 0.15); color: #ff4757; border: 1px solid rgba(255, 71, 87, 0.3); font-size: 0.85rem; letter-spacing: 0.5px;">Cancelado</span>
                                    @endif
                                </td>
                                <td class="py-4 text-center pe-4">
                                    <button class="btn btn-sm btn-outline-light border-secondary shadow-sm" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOrder{{ $order->id }}" aria-expanded="false" aria-controls="collapseOrder{{ $order->id }}" title="Ver detalles">
                                        <i class="bi bi-box-arrow-up-right"></i>
                                    </button>
                                </td>
                            </tr>
                            
                            <!-- Fila Desplegable con los Detalles -->
                            <tr class="collapse" id="collapseOrder{{ $order->id }}">
                                <td colspan="5" class="p-0 border-0 bg-darker">
                                    <div class="p-4" style="background: rgba(0,0,0,0.15); box-shadow: inset 0 5px 15px rgba(0,0,0,0.2);">
                                        
                                        <!-- Lista vertical de juegos -->
                                        <div class="d-flex flex-column gap-2 mb-3">
                                            @foreach($order->items as $item)
                                                <div class="d-flex align-items-center p-3 rounded" style="background: rgba(255,255,255,0.03); border-left: 3px solid var(--jg-sun); transition: background 0.2s;" onmouseover="this.style.background='rgba(255,255,255,0.06)'" onmouseout="this.style.background='rgba(255,255,255,0.03)'">
                                                    <div class="flex-shrink-0 shadow-sm rounded overflow-hidden" style="width: 55px; height: 75px;">
                                                        @if($item->game->cover_image)
                                                            <img src="{{ asset('storage/' . $item->game->cover_image) }}" alt="{{ $item->game->title }}" class="w-100 h-100 object-fit-cover">
                                                        @else
                                                            <div class="w-100 h-100 d-flex align-items-center justify-content-center text-muted bg-dark"><i class="bi bi-controller"></i></div>
                                                        @endif
                                                    </div>
                                                    <div class="flex-grow-1 ms-4">
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
                        <h2 class="text-white fw-bold mb-3" style="font-family: var(--jg-font-title); letter-spacing: 1px;">Aún no tienes ningún pedido</h2>
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
