@extends('layouts.app')

@section('title', 'Mi Biblioteca - Jediga')

@section('content')
<div class="container py-5 mt-4">
    <div class="row mb-5 align-items-center">
        <div class="col-md-8">
            <h1 class="display-4 fw-bold text-white mb-2" style="font-family: var(--jg-font-title);">Mi <span class="jg-sun">Biblioteca</span></h1>
            <p class="lead text-white opacity-75">Tus juegos digitales listos para descargar y disfrutar.</p>
        </div>
    </div>

    @if($items->count() > 0)
        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
            @foreach($items as $item)
                @php $game = $item->game; @endphp
                <div class="col">
                    <div class="card bg-darker text-white h-100 jg-card-hover border-secondary border-opacity-25" style="border-radius: 1rem; overflow: hidden; box-shadow: 0 4px 15px rgba(0,0,0,0.5);">
                        <!-- Portada (con fallback) -->
                        <div class="position-relative" style="height: 250px;">
                            @if($game->cover_image)
                                <img src="{{ asset('storage/' . $game->cover_image) }}" class="w-100 h-100 object-fit-cover opacity-75" alt="{{ $game->title }}">
                            @else
                                <div class="w-100 h-100 bg-secondary d-flex align-items-center justify-content-center opacity-50">
                                    <i class="bi bi-controller fs-1"></i>
                                </div>
                            @endif
                            <!-- Etiqueta superior -->
                            <div class="position-absolute top-0 start-0 m-3">
                                <span class="badge jg-bg-sun text-dark px-3 py-2 fs-6 fw-bold shadow">
                                    <i class="bi bi-controller me-1"></i> {{ $game->platform->name ?? 'Digital' }}
                                </span>
                            </div>
                        </div>

                        <!-- Detalles del juego -->
                        <div class="card-body d-flex flex-column p-4">
                            <h4 class="card-title fw-bold mb-3">{{ $game->title }}</h4>
                            <div class="small text-white opacity-50 mb-4 flex-grow-1">
                                <i class="bi bi-calendar3 me-2"></i> Adquirido el: {{ $item->created_at->format('d/m/Y') }}<br>
                                <i class="bi bi-bag-check me-2"></i> Pedido #{{ str_pad($item->order_id, 6, '0', STR_PAD_LEFT) }}
                            </div>

                            <!-- Botón de Descarga "Fake" (Abre un modal en lugar de descargar) -->
                            <button type="button" class="btn jg-btn-primary w-100 py-3 fw-bold fw-bold rounded-3" 
                                    data-bs-toggle="modal" data-bs-target="#descargaModal{{ $item->id }}"
                                    style="letter-spacing: 1px; text-transform: uppercase;">
                                <i class="bi bi-cloud-arrow-down-fill me-2 fs-5"></i> Descargar Juego
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Modal de "Descarga" (Fake) específico para cada juego -->
                <div class="modal fade" id="descargaModal{{ $item->id }}" tabindex="-1" aria-hidden="true" data-bs-theme="dark">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content bg-dark border-secondary">
                            <div class="modal-header border-bottom-0 pb-0">
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body text-center pb-5 px-4">
                                <div class="mb-4 text-primary">
                                    <i class="bi bi-cloud-arrow-down" style="font-size: 5rem;"></i>
                                </div>
                                <h3 class="fw-bold mb-3 text-white">Iniciando descarga...</h3>
                                <p class="text-white opacity-75 mb-4">
                                    Preparando los archivos para <strong>{{ $game->title }}</strong>.<br>
                                    <span class="small">(Esta es una simulación visual. No se está descargando ningún archivo real).</span>
                                </p>
                                
                                <!-- Barra de progreso animada -->
                                <div class="progress mb-3" style="height: 15px; border-radius: 10px; background-color: rgba(255,255,255,0.1);">
                                    <div class="progress-bar progress-bar-striped progress-bar-animated bg-primary" role="progressbar" style="width: 100%" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                                <span class="small text-muted">Conectando al servidor seguro...</span>

                                <div class="mt-4">
                                    <button type="button" class="btn btn-outline-secondary px-4" data-bs-dismiss="modal">Cerrar</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <!-- Estado Vacío -->
        <div class="text-center py-5 bg-darker rounded-4 border-secondary border-opacity-25" style="box-shadow: inset 0 0 20px rgba(0,0,0,0.5);">
            <i class="bi bi-journal-x text-muted mb-3" style="font-size: 4rem;"></i>
            <h3 class="fw-bold text-white mb-3">Tu biblioteca está vacía</h3>
            <p class="text-white opacity-75 mb-4">Aún no has adquirido ningún juego digital. ¡Explora nuestro catálogo!</p>
            <a href="{{ route('catalogo') }}" class="btn jg-btn-sun btn-lg px-5">
                Ir al Catálogo <i class="bi bi-arrow-right ms-2"></i>
            </a>
        </div>
    @endif
</div>
@endsection