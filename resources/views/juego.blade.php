@extends('layouts.app')

@section('title', $game->title . ' - Jediga')

@section('content')
<div class="container py-5">
    @php
        $isUpcoming = $game->release_date && $game->release_date->isFuture();
    @endphp
    
    <div class="row g-4">
        <!-- Izquierda: Media + Descripción -->
        <div class="col-lg-8">
            <!-- Hero: Carrusel Portada / Trailer -->
            <div id="gameMediaCarousel" class="carousel slide mb-4 rounded-4 overflow-hidden position-relative"
                 data-bs-ride="false" data-bs-wrap="true" style="height: 450px; background: #0f0f0f;">
                <div class="carousel-inner h-100">

                    <!-- Slide 1: Portada del juego -->
                    <div class="carousel-item active h-100 position-relative">
                        @if($game->cover_image)
                            <img src="{{ asset('storage/' . $game->cover_image) }}"
                                 alt="{{ $game->title }}"
                                 class="w-100 h-100 object-fit-cover opacity-75">
                        @endif
                        <div class="position-absolute bottom-0 start-0 p-5 w-100"
                             style="background: linear-gradient(to top, rgba(0,0,0,0.9), transparent); z-index: 2;">
                            <h1 class="display-3 fw-bold text-white mb-0 shadow-lg">{{ $game->title }}</h1>
                            <div class="d-flex gap-2 mt-3 flex-wrap">
                                @foreach($game->categories as $category)
                                    <span class="badge bg-dark border-sun text-sun px-3 py-2">{{ $category->name }}</span>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <!-- Slide 2: Trailer de YouTube -->
                    @if($game->youtube_id)
                        <div class="carousel-item h-100 bg-black" id="trailerSlide">
                            <iframe id="gameTrailerIframe"
                                    class="w-100 h-100 border-0"
                                    data-src="https://www.youtube.com/embed/{{ $game->youtube_id }}?rel=0&color=white&modestbranding=1&enablejsapi=1"
                                    src=""
                                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                    allowfullscreen
                                    title="Trailer de {{ $game->title }}">
                            </iframe>
                        </div>
                    @endif
                </div>

                @if($game->youtube_id)
                    <!-- Flechas llamativas sin franjas -->
                    <button id="btnCarouselPrev" class="jg-carousel-btn jg-carousel-btn--prev" type="button"
                            data-bs-target="#gameMediaCarousel" data-bs-slide="prev">
                        <i class="bi bi-chevron-left"></i>
                    </button>
                    <button id="btnCarouselNext" class="jg-carousel-btn jg-carousel-btn--next" type="button"
                            data-bs-target="#gameMediaCarousel" data-bs-slide="next">
                        <i class="bi bi-chevron-right"></i>
                    </button>
                @endif
            </div>
            
            <div class="card jg-card p-4 rounded-4 text-white mb-4 border-0">
                <h3 class="mb-4">Resumen y Detalles</h3>
                <p class="text-white opacity-75 fs-5 line-height-lg">{{ $game->description ?? 'No hay descripción disponible para este título.' }}</p>
                
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
                        <span class="d-block small jg-muted mb-1 text-uppercase tracking-wider">Estado</span>
                        @if($isUpcoming)
                            <strong class="text-sun h5"><i class="bi bi-calendar-check me-2"></i>Próximamente</strong>
                        @elseif($game->stock > 0)
                            <strong class="text-mint h5"><i class="bi bi-check-circle me-2"></i>Disponible ({{ $game->stock }})</strong>
                        @else
                            <strong class="text-danger h5"><i class="bi bi-x-circle me-2"></i>Agotado</strong>
                        @endif
                    </div>
                    <div class="col-md-3">
                        <span class="d-block small jg-muted mb-1 text-uppercase tracking-wider">Precio Habitual</span>
                        <strong class="text-white opacity-50 h5 text-decoration-line-through">{{ number_format($game->price * 1.2, 2) }}€</strong>
                    </div>
                </div>
            </div>
        </div>

        <!-- Derecha: Compra y Acciones -->
        <div class="col-lg-4">
            <div class="card jg-card p-4 rounded-4 text-white border-0 sticky-top" style="top: 100px;">
                <div class="mb-4">
                    <h4 class="jg-muted small text-uppercase mb-2">{{ $isUpcoming ? 'Reservar Hoy' : 'Comprar Hoy' }}</h4>
                    <div class="display-4 fw-bold text-sun mb-1">{{ number_format($game->getPriceForUser(auth()->user()), 2) }}€</div>
                    <div class="text-mint small">
                        @if($isUpcoming)
                            <i class="bi bi-calendar-date me-1"></i> Lanzamiento: {{ $game->release_date->format('d/m/Y') }}
                        @else
                            <i class="bi bi-lightning-charge-fill me-1"></i>Entrega Digital Inmediata
                        @endif
                    </div>
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
                            <label class="form-label small text-white opacity-75">Seleccionar Cantidad</label>
                            <select name="quantity" class="form-select bg-dark text-white border-secondary mb-3" {{ $game->stock <= 0 ? 'disabled' : '' }}>
                                @for($i = 1; $i <= min(10, $game->stock); $i++)
                                    <option value="{{ $i }}">{{ $i }} {{ $i > 1 ? 'unidades' : 'unidad' }}</option>
                                @endfor
                            </select>
                        </div>

                        @if($isUpcoming)
                            <button type="submit" class="btn jg-btn jg-btn-primary w-100 btn-lg mb-3 shadow rounded-pill">
                                <i class="bi bi-calendar-check me-2"></i> Reservar Juego
                            </button>
                        @else
                            <button type="submit" class="btn jg-btn jg-btn-sun w-100 btn-lg mb-3 shadow {{ $game->stock <= 0 ? 'disabled' : '' }}">
                                <i class="bi bi-cart-plus-fill me-2"></i> Añadir al Carrito
                            </button>
                        @endif
                    </form>
                @else
                    @if($isUpcoming)
                        <button type="button" class="btn jg-btn jg-btn-primary w-100 btn-lg mb-3 shadow rounded-pill" data-bs-toggle="modal" data-bs-target="#loginModal">
                            <i class="bi bi-calendar-check me-2"></i> Reservar Juego
                        </button>
                    @else
                        <button type="button" class="btn jg-btn jg-btn-sun w-100 btn-lg mb-3 shadow {{ $game->stock <= 0 ? 'disabled' : '' }}" data-bs-toggle="modal" data-bs-target="#loginModal">
                            <i class="bi bi-cart-plus-fill me-2"></i> Añadir al Carrito
                        </button>
                    @endif
                @endif
                
                <button type="button" class="btn jg-btn jg-btn-outline w-100 py-2">
                    <i class="bi bi-heart me-2"></i> Añadir a Lista de Deseos
                </button>

                <div class="mt-4 p-3 rounded-3" style="background: rgba(255,255,255, 0.03);">
                    <div class="d-flex align-items-center gap-3">
                        <i class="bi bi-shield-check text-sun h3 mb-0"></i>
                        <div>
                            <div class="small fw-bold">Garantía Jediga</div>
                            <div class="x-small text-white opacity-60">Protección total en cada transacción digital.</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const carouselEl = document.getElementById('gameMediaCarousel');
    if (!carouselEl) return;

    const iframe = document.getElementById('gameTrailerIframe');
    const dataSrc = iframe ? iframe.dataset.src : null;

    carouselEl.addEventListener('slide.bs.carousel', function (e) {
        // Al ir al slide del trailer → cargar el iframe inmediatamente para que empiece a prepararse
        if (e.to === 1 && iframe && dataSrc) {
            iframe.src = dataSrc;
        }
    });

    carouselEl.addEventListener('slid.bs.carousel', function (e) {
        // Solo DESPUÉS de que acabe la animación de vuelta a portada → limpiar el iframe
        // Así no hay parpadeo blanco durante la transición
        if (e.to === 0 && iframe) {
            iframe.src = '';
        }
    });
});
</script>
@endsection
