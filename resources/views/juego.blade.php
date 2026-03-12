@extends('layouts.app')

@section('title', $slug . ' - Jediga')

@section('content')
<div class="container py-5 mt-5">
    <div class="row">
        <div class="col-lg-8">
            <div class="jg-game-hero mb-4 rounded-4" style="height: 400px; background: linear-gradient(45deg, #0f0f0f, #333);">
                <div class="d-flex align-items-end h-100 p-4">
                    <h1 class="display-3 fw-bold text-white">{{ str_replace('-', ' ', ucwords($slug, '-')) }}</h1>
                </div>
            </div>
            
            <div class="bg-dark p-4 rounded-4 text-white mb-4">
                <h3>Resumen</h3>
                <p class="text-muted">Esta es la ficha técnica y detalles de <strong>{{ $slug }}</strong>. Actualmente en desarrollo.</p>
                <hr class="opacity-25">
                <div class="d-flex gap-4">
                    <div>
                        <span class="d-block small jg-muted">Plataforma</span>
                        <strong>PC / PS5 / Xbox</strong>
                    </div>
                    <div>
                        <span class="d-block small jg-muted">Lanzamiento</span>
                        <strong>2026</strong>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="bg-dark p-4 rounded-4 text-white">
                <h4>Compra hoy</h4>
                <div class="display-5 fw-bold text-sun mb-3">59.99€</div>
                <button class="btn jg-btn jg-btn-sun w-100 btn-lg mb-2">Comprar ya</button>
                <button class="btn jg-btn jg-btn-outline w-100">Añadir a deseos</button>
            </div>
        </div>
    </div>
</div>
@endsection
