@extends('layouts.app')

@section('title', 'Pago Completado - Jediga')

@section('content')
<div class="container py-5 text-center" style="min-height: 60vh; display: flex; align-items: center; justify-content: center;">
    <div class="card jg-card border-0 shadow-lg p-5" style="max-width: 600px; width: 100%;">
        <div class="mb-4">
            <i class="bi bi-check-circle-fill text-success" style="font-size: 5rem;"></i>
        </div>
        <h1 class="display-5 fw-bold text-white mb-3">¡Pago Completado!</h1>
        <p class="lead text-white opacity-75 mb-4">
            Gracias por tu compra. Tu pedido <strong class="text-sun">#{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}</strong> se ha procesado correctamente.
        </p>

        <div class="bg-dark p-3 rounded mb-4 text-start">
            <div class="d-flex justify-content-between mb-2">
                <span class="text-white opacity-75">Total Pagado:</span>
                <strong class="text-white">{{ \App\Services\CurrencyService::format($order->total_amount) }}</strong>
            </div>
            <div class="d-flex justify-content-between">
                <span class="text-white opacity-75">Estado:</span>
                <strong class="text-success"><i class="bi bi-check-circle"></i> Confirmado</strong>
            </div>
        </div>

        <p class="text-white opacity-50 small mb-4">Te hemos enviado un correo electrónico con los detalles de tu pedido.</p>

        <a href="{{ route('catalogo') }}" class="btn jg-btn jg-btn-sun btn-lg w-100">
            Seguir explorando el catálogo
        </a>
    </div>
</div>
@endsection
