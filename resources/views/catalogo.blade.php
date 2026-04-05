@extends('layouts.app')

@section('title', 'Catálogo de Juegos - Jediga')

@section('content')
<div class="container py-5">
    <div class="text-center mb-5">
        <h1 class="display-4 text-white">Catálogo de <span class="jg-sun">Juegos</span></h1>
        <p class="lead">Explora nuestra selección de los mejores videojuegos.</p>
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
                            <span class="h4 text-sun mb-0 fw-bold text-white">{{ number_format($game->getPriceForUser(auth()->user()), 2) }}€</span>
                            @if($game->stock > 0)
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
                                    <button type="submit" class="btn jg-btn jg-btn-sun w-100 {{ $game->stock <= 0 ? 'disabled' : '' }}">
                                        <i class="bi bi-cart-plus me-1"></i> Añadir
                                    </button>
                                </form>
                            @else
                                <button type="button" class="btn jg-btn jg-btn-sun w-100 {{ $game->stock <= 0 ? 'disabled' : '' }}" data-bs-toggle="modal" data-bs-target="#loginModal">
                                    <i class="bi bi-cart-plus me-1"></i> Añadir
                                </button>
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
@endsection
