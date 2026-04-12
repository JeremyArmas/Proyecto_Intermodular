@extends('layouts.app')

@section('title', 'Pago Cancelado - Jediga')

@section('content')
<div class="container py-5 text-center" style="min-height: 60vh; display: flex; align-items: center; justify-content: center;">
    <div class="card jg-card border-0 shadow-lg p-5" style="max-width: 600px; width: 100%;">
        <div class="mb-4">
            <i class="bi bi-x-circle text-danger" style="font-size: 5rem;"></i>
        </div>
        <h1 class="display-5 fw-bold text-white mb-3">Pago Cancelado</h1>
        <p class="lead text-white opacity-75 mb-4">
            No te preocupes, el proceso de pago ha sido cancelado y <strong class="text-danger">no se ha realizado ningún cargo</strong> en tu cuenta.
        </p>
        <p class="text-white opacity-50 mb-5">
            Tus artículos siguen guardados en el carrito por si decides continuar la compra más adelante.
        </p>

        <div class="d-flex gap-3 justify-content-center">
            <a href="{{ route('carrito.index') }}" class="btn btn-outline-light border-secondary btn-lg px-4">
                Volver al carrito
            </a>
            <a href="{{ route('catalogo') }}" class="btn jg-btn jg-btn-sun btn-lg px-4">
                Ir al catálogo
            </a>
        </div>
    </div>
</div>
@endsection
