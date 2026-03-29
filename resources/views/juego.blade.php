@extends('layouts.app')

@section('title', $game->title . ' - Jediga')

@section('content')
<div class="container py-5 mt-5">
    <div class="row g-4">
        <!-- Izquierda: Media + Descripción -->
        <div class="col-lg-8">
            <div class="jg-game-hero mb-4 rounded-4 overflow-hidden position-relative" style="height: 450px; background: linear-gradient(45deg, #0f0f0f, #333);">
                @if($game->cover_image)
                    <img src="{{ asset('storage/' . $game->cover_image) }}" alt="{{ $game->title }}" class="w-100 h-100 object-fit-cover opacity-75">
                @endif
                <div class="position-absolute bottom-0 start-0 p-5 w-100" style="background: linear-gradient(to top, rgba(0,0,0,0.9), transparent);">
                    <h1 class="display-3 fw-bold text-white mb-0 shadow-lg">{{ $game->title }}</h1>
                    <div class="d-flex gap-2 mt-3">
                        @foreach($game->categories as $category)
                            <span class="badge bg-dark border-sun text-sun px-3 py-2">{{ $category->name }}</span>
                        @endforeach
                    </div>
                </div>
            </div>
            
            <div class="card jg-card p-4 rounded-4 text-white mb-4 border-0">
                <h3 class="mb-4">Resumen y Detalles</h3>
                <p class="text-muted fs-5 line-height-lg">{{ $game->description ?? 'No hay descripción disponible para este título.' }}</p>
                
                <hr class="opacity-10 my-4">
                
                <div class="row g-4">
                    <div class="col-md-3">
                        <span class="d-block small jg-muted mb-1 text-uppercase tracking-wider">Plataforma</span>
                        <strong class="text-white h5"><i class="bi bi-display me-2"></i>{{ $game->platform->name ?? 'Varios' }}</strong>
                    </div>
                    <div class="col-md-3">
                        <span class="d-block small jg-muted mb-1 text-uppercase tracking-wider">Desarrollador</span>
                        <strong class="text-white h5"><i class="bi bi-code-square me-2"></i>{{ $game->developer ?? 'N/A' }}</strong>
                    </div>
                    <div class="col-md-3">
                        <span class="d-block small jg-muted mb-1 text-uppercase tracking-wider">Estado de Stock</span>
                        @if($game->stock > 0)
                            <strong class="text-mint h5"><i class="bi bi-check-circle me-2"></i>Disponible ({{ $game->stock }})</strong>
                        @else
                            <strong class="text-danger h5"><i class="bi bi-x-circle me-2"></i>Agotado</strong>
                        @endif
                    </div>
                    <div class="col-md-3">
                        <span class="d-block small jg-muted mb-1 text-uppercase tracking-wider">Precio Habitual</span>
                        <strong class="text-muted h5 text-decoration-line-through">{{ number_format($game->price * 1.2, 2) }}€</strong>
                    </div>
                </div>
            </div>
        </div>

        <!-- Derecha: Compra y Acciones -->
        <div class="col-lg-4">
            <div class="card jg-card p-4 rounded-4 text-white border-0 sticky-top" style="top: 100px;">
                <div class="mb-4">
                    <h4 class="text-muted small text-uppercase mb-2">Comprar Hoy</h4>
                    <div class="display-4 fw-bold text-sun mb-1">{{ number_format($game->getPriceForUser(auth()->user()), 2) }}€</div>
                    <div class="text-mint small"><i class="bi bi-lightning-charge-fill me-1"></i>Entrega Digital Inmediata</div>
                </div>

                @if(session('error'))
                    <div class="alert alert-danger bg-dark text-danger border-danger small p-2 mb-3">
                        {{ session('error') }}
                    </div>
                @endif

                @if(auth()->check())
                    <form action="{{ route('carrito.add') }}" method="POST">
                        @csrf
                        <input type="hidden" name="game_id" value="{{ $game->id }}">
                        
                        <div class="mb-3">
                            <label class="form-label small text-muted">Seleccionar Cantidad</label>
                            <select name="quantity" class="form-select bg-dark text-white border-secondary mb-3" {{ $game->stock <= 0 ? 'disabled' : '' }}>
                                @for($i = 1; $i <= min(10, $game->stock); $i++)
                                    <option value="{{ $i }}">{{ $i }} {{ $i > 1 ? 'unidades' : 'unidad' }}</option>
                                @endfor
                            </select>
                        </div>

                        <button type="submit" class="btn jg-btn jg-btn-sun w-100 btn-lg mb-3 shadow {{ $game->stock <= 0 ? 'disabled' : '' }}">
                            <i class="bi bi-cart-plus-fill me-2"></i> Añadir al Carrito
                        </button>
                    </form>
                @else
                    <button type="button" class="btn jg-btn jg-btn-sun w-100 btn-lg mb-3 shadow {{ $game->stock <= 0 ? 'disabled' : '' }}" data-bs-toggle="modal" data-bs-target="#loginModal">
                        <i class="bi bi-cart-plus-fill me-2"></i> Añadir al Carrito
                    </button>
                @endif
                
                <button type="button" class="btn jg-btn jg-btn-outline w-100 py-2">
                    <i class="bi bi-heart me-2"></i> Añadir a Lista de Deseos
                </button>

                <div class="mt-4 p-3 rounded-3" style="background: rgba(255,255,255, 0.03);">
                    <div class="d-flex align-items-center gap-3">
                        <i class="bi bi-shield-check text-sun h3 mb-0"></i>
                        <div>
                            <div class="small fw-bold">Garantía Jediga</div>
                            <div class="x-small text-muted">Protección total en cada transacción digital.</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .border-sun { border: 1px solid var(--jg-sun); }
    .tracking-wider { letter-spacing: 0.05em; }
    .line-height-lg { line-height: 1.8; }
    .x-small { font-size: 0.75rem; }
</style>
@endsection
