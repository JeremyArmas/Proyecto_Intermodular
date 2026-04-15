@extends('layouts.app')

@section('title', 'Catálogo de Juegos - Jediga')

@section('content')
<div class="container py-5">
    <div class="text-center mb-5">
        <h1 class="display-4 text-white">Catálogo de <span class="jg-sun">Juegos</span></h1>
        <p class="lead">Explora nuestra selección de los mejores videojuegos.</p>
    </div>

    <!-- Filtros -->
    <div class="mb-5 pb-4 border-bottom border-secondary" style="border-bottom-color: rgba(255, 255, 255, 0.1) !important;">
        <form id="filterForm" action="{{ route('catalogo') }}" method="GET">
            <div class="row g-3 align-items-end">
                
                <!-- Buscador por nombre -->
                <div class="col-12 col-md-3">
                    <label for="search" class="form-label text-white-50 small mb-1">Buscar título</label>
                    <input type="text" class="form-control form-control-sm bg-transparent text-white border-secondary" id="search" name="search" placeholder="Ej: Rain World..." value="{{ request('search') }}">
                </div>

                <!-- Plataforma -->
                <div class="col-6 col-md-2">
                    <label for="platform" class="form-label text-white-50 small mb-1">Plataforma</label>
                    <select class="form-select form-select-sm bg-transparent text-white border-secondary" id="platform" name="platform">
                        <option value="" class="text-dark">Todas</option>
                        @foreach($platforms as $plat)
                            <option value="{{ $plat->slug }}" class="text-dark" @selected(request('platform') == $plat->slug)>{{ $plat->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Categoría -->
                <div class="col-6 col-md-2">
                    <label for="category" class="form-label text-white-50 small mb-1">Categoría</label>
                    <select class="form-select form-select-sm bg-transparent text-white border-secondary" id="category" name="category">
                        <option value="" class="text-dark">Todas</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->slug }}" class="text-dark" @selected(request('category') == $cat->slug)>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Rango de precio (min y max) -->
                <div class="col-12 col-md-3">
                    <label class="form-label text-white-50 small mb-1">Precio ({{ \App\Services\CurrencyService::getSymbol() }})</label>
                    <div class="input-group input-group-sm">
                        <input type="number" class="form-control bg-transparent text-white border-secondary px-2" name="price_min" placeholder="Rango Mínimo" value="{{ request('price_min') }}" min="0" step="1">
                        <span class="input-group-text bg-transparent border-secondary text-white-50 px-2">-</span>
                        <input type="number" class="form-control bg-transparent text-white border-secondary px-2" name="price_max" placeholder="Rango Máximo" value="{{ request('price_max') }}" min="0" step="1">
                    </div>
                </div>

                <!-- Botón de limpieza -->
                <div class="col-12 col-md-2 d-flex justify-content-end">
                    <a href="{{ route('catalogo') }}" class="btn btn-sm btn-outline-secondary w-100" title="Limpiar todos los filtros">
                        <i class="bi bi-eraser text-white me-1"></i> <span class="text-white-50">Limpiar</span>
                    </a>
                </div>
            </div>
        </form>
    </div>

    @if(session('success'))
        <div class="alert alert-success bg-dark text-success border-success mb-4">
            {{ session('success') }}
        </div>
    @endif

    <div class="row g-4">
        @forelse($games as $game)
        <div class="col-md-6 col-lg-4 col-xl-3">
            <div class="card jg-card h-100 overflow-hidden shadow-sm border-0">
                
            <!-- Imagen de Portada -->
                <div class="position-relative overflow-hidden" style="height: 240px; background: linear-gradient(45deg, #121212, #2a2a2a);">
                    @if($game->cover_image)
                        <img src="{{ asset('storage/' . $game->cover_image) }}" alt="{{ $game->title }}" class="w-100 h-100 object-fit-cover jg-card-hover-img">
                    @endif
                    <div class="position-absolute top-0 end-0 p-2">
                        <span class="badge badge-sun">{{ $game->platform->name ?? 'Multi' }}</span>
                    </div>
                </div>

                <!-- Detalles del Juego -->
                <div class="card-body d-flex flex-column p-4">
                    <h5 class="card-title text-white mb-1">{{ $game->title }}</h5>
                    <p class="mb-3 text-white">{{ $game->developer ?? 'Desarrollador N/A' }}</p>
                    
                    <div class="mt-auto">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span class="h4 text-sun mb-0 fw-bold text-white">{{ \App\Services\CurrencyService::format($game->getPriceForUser(auth()->user())) }}</span>
                            @php
                                $isUpcoming = $game->release_date && $game->release_date->isFuture();
                            @endphp

                            @if($isUpcoming)
                                <span class="badge text-sun border-sun small" style="border: 1px solid var(--jg-sun);">Reserva</span>
                            @elseif($game->stock > 0)
                                <span class="badge text-mint border-mint small">Stock: {{ $game->stock }}</span>
                            @else
                                <span class="badge text-danger border-danger small">Agotado</span>
                            @endif
                        </div>

                        <div class="d-grid gap-2">
                            @auth
                                <form action="{{ route('carrito.add') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="game_id" value="{{ $game->id }}">
                                    @if($isUpcoming)
                                        <button type="submit" class="btn jg-btn jg-btn-primary w-100 rounded-pill">
                                            <i class="bi bi-calendar-check me-1"></i> Reservar
                                        </button>
                                    @else
                                        <button type="submit" class="btn jg-btn jg-btn-sun w-100 {{ $game->stock <= 0 ? 'disabled' : '' }}">
                                            <i class="bi bi-cart-plus me-1"></i> Añadir
                                        </button>
                                    @endif
                                </form>
                            @else
                                @if($isUpcoming)
                                    <button type="button" class="btn jg-btn jg-btn-primary w-100 rounded-pill" data-bs-toggle="modal" data-bs-target="#loginModal">
                                        <i class="bi bi-calendar-check me-1"></i> Reservar
                                    </button>
                                @else
                                    <button type="button" class="btn jg-btn jg-btn-sun w-100 {{ $game->stock <= 0 ? 'disabled' : '' }}" data-bs-toggle="modal" data-bs-target="#loginModal">
                                        <i class="bi bi-cart-plus me-1"></i> Añadir
                                    </button>
                                @endif
                            @endauth
                            <a href="{{ route('juego.show', $game->slug) }}" class="btn jg-btn jg-btn-outline w-100">Ver Ficha</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12 text-center py-5">
            <i class="bi bi-search display-3 mb-3"></i>
            <h3 class="text-white">No hemos encontrado juegos</h3>
            <p>Prueba con otros filtros o vuelve más tarde.</p>
        </div>
        @endforelse
    </div>

    <!-- Paginación Laravel -->
    <div class="mt-5 d-flex justify-content-center">
        @if(method_exists($games, 'links'))
            {{ $games->links() }}
        @endif
    </div>
</div>

<style>
    .jg-card-hover-img {
        transition: transform 0.4s ease;
    }
    .jg-card:hover .jg-card-hover-img {
        transform: scale(1.1);
    }
    .border-mint { border: 1px solid var(--jg-mint); }
</style>

<!-- Script para el auto-envío de filtros -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('filterForm');
        
        // Si no existe el formulario, no hacemos nada
        if (!form){
            return;
        } 

        // Auto-envío al cambiar selects e inputs numéricos (precios)
        const autoSubmitElements = form.querySelectorAll('select, input[type="number"]');
        autoSubmitElements.forEach(el => {
            el.addEventListener('change', () => form.submit());
        });

        // Auto-envío inteligente (debounce) al teclear en el buscador
        const searchInput = document.getElementById('search');
        
        // Si no existe el input de búsqueda, no hacemos nada
        if (searchInput) {
            let timeout = null;
            searchInput.addEventListener('input', function() {
                clearTimeout(timeout);
                timeout = setTimeout(() => {
                    form.submit();
                }, 800); // Espera 800ms después de que el usuario termina de teclear
            });
        }
    });
</script>
@endsection
