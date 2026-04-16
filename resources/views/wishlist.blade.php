@extends('layouts.app')

@section('title', 'Wishlist - Jediga')

@section('content')
<div class="jg-hero py-5">
    <div class="container mt-4">
        <div class="row align-items-center">
            <div class="col-lg-8 text-center text-lg-start">
                <nav aria-label="breadcrumb">
                </nav>
                <h1 class="display-5 fw-bold text-white mb-2 jg-page-title">
                    Mi <span class="jg-sun jg-bg-sun-alpha px-2 py-1 rounded">Lista de Deseos</span>
                </h1>
                <p class="text-white opacity-75 lead jg-lead-light">Guarda los juegos que más te gustan para comprarlos más tarde.</p>
            </div>
            <div class="col-lg-4 text-center text-lg-end mt-4 mt-lg-0">
                <div class="jg-pill scale-in d-inline-flex">
                    <div class="jg-dot"></div>
                    <span class="small fw-bold text-uppercase">{{ $wishlistGames->total() }} JUEGOS GUARDADOS</span>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container pb-5 jg-mt-n4-overlap">
    <div class="row g-4">
        {{-- Listado de juegos --}}
        <div class="col-lg-9">
            <div class="jg-panel p-0 overflow-visible">
                @if($wishlistGames->isEmpty())
                    <div class="text-center py-5 my-5">
                        <div class="mb-4">
                            <i class="bi bi-heart text-sun opacity-25 jg-icon-xl"></i>
                        </div>
                        <h3 class="mb-3 jg-font-title tracking-wider">TU LISTA ESTÁ VACÍA</h3>
                        <p class="text-white mb-4 px-4">¡Explora nuestro catálogo y añade tus juegos favoritos!</p>
                        <a href="{{ route('catalogo') }}" class="jg-btn jg-btn-sun px-5 py-3 text-decoration-none d-inline-block">
                            <i class="bi bi-controller me-2"></i> IR AL CATÁLOGO
                        </a>
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-dark table-hover mb-0 jg-table border-0 text-white">
                            <thead class="bg-darker">
                                <tr>
                                    <th class="ps-4 py-4 border-0 text-sun small tracking-wider">PRODUCTO</th>
                                    <th class="py-4 border-0 text-sun small tracking-wider text-center d-none d-md-table-cell">ESTADO</th>
                                    <th class="py-4 border-0 text-sun small tracking-wider text-end">PRECIO</th>
                                    <th class="pe-4 py-4 border-0 text-sun small tracking-wider text-end">ACCIONES</th>
                                </tr>
                            </thead>
                            <tbody class="align-middle">
                                @foreach($wishlistGames as $item)
                                    @php $game = $item->game; @endphp
                                    <tr class="border-bottom border-white-10 transition-all hover-bg-sun-5">
                                        <td class="ps-4 py-4">
                                            <div class="d-flex align-items-center gap-3">
                                                {{-- Botón Eliminar (X) --}}
                                                <form action="{{ route('wishlist.destroy', $item->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-link p-0 text-white-50 hover-text-danger transition-colors" title="Eliminar de favoritos">
                                                        <i class="bi bi-x-circle-fill jg-fs-5"></i>
                                                    </button>
                                                </form>

                                                {{-- Portada del Juego --}}
                                                <div class="position-relative">
                                                    <img src="{{ asset('storage/' . $game->cover_image) }}" 
                                                         alt="{{ $game->title }}" 
                                                         class="rounded-3 shadow-lg jg-wishlist-cover">
                                                    
                                                    @if($game->platform)
                                                    <span class="position-absolute top-0 end-0 translate-middle-y translate-middle-x badge bg-dark border border-white-10 p-1 rounded-circle shadow jg-badge-mini">
                                                        @if(str_contains(strtolower($game->platform->slug), 'ps5'))
                                                            <i class="bi bi-playstation text-info"></i>
                                                        @elseif(str_contains(strtolower($game->platform->slug), 'xbox'))
                                                            <i class="bi bi-xbox text-success"></i>
                                                        @elseif(str_contains(strtolower($game->platform->slug), 'switch'))
                                                            <i class="bi bi-nintendo-switch text-danger"></i>
                                                        @else
                                                            <i class="bi bi-pc-display text-white-50"></i>
                                                        @endif
                                                    </span>
                                                    @endif
                                                </div>

                                                {{-- Info del Juego --}}
                                                <div>
                                                    <h5 class="mb-1 fw-bold fs-5 text-white">{{ $game->title }}</h5>
                                                    <div class="d-flex flex-wrap gap-2 mt-1">
                                                        @foreach($game->categories->take(2) as $cat)
                                                            <span class="small opacity-50">#{{ $cat->name }}</span>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        
                                        <td class="text-center py-4 d-none d-md-table-cell">
                                            @if($game->stock > 0)
                                                <span class="badge badge-mint px-3">
                                                    <i class="bi bi-check2-circle me-1"></i> STOCK
                                                </span>
                                            @else
                                                <span class="badge badge-soft px-3">
                                                    <i class="bi bi-hourglass-split me-1"></i> AGOTADO
                                                </span>
                                            @endif
                                        </td>

                                        <td class="text-end py-4">
                                            <div class="fs-5 fw-bold text-sun">
                                                {{ \App\Services\CurrencyService::format($game->getPriceForUser(Auth::user())) }}
                                            </div>
                                            @if(Auth::user() && Auth::user()->isCompany())
                                                <div class="small text-white-50 opacity-75 jg-text-tiny">Tarifa B2B</div>
                                            @endif
                                        </td>

                                        <td class="pe-4 py-4 text-end">
                                            <div class="d-flex flex-column flex-md-row gap-2 justify-content-end align-items-center">
                                                {{-- Mover al Carrito --}}
                                                <form action="{{ route('wishlist.moveToCart', $item->id) }}" method="POST">
                                                    @csrf
                                                    <button type="submit" class="jg-btn jg-btn-sun btn-sm px-4 py-2 border-0" 
                                                            {{ $game->stock <= 0 ? 'disabled' : '' }}>
                                                        <i class="bi bi-cart-plus me-1"></i>
                                                        AÑADIR AL CARRITO
                                                    </button>
                                                </form>

                                                {{-- Ver Detalles --}}
                                                <a href="{{ route('juego.show', $game->slug) }}" class="jg-btn jg-btn-outline btn-sm px-4 py-2 text-decoration-none d-inline-block">
                                                    VER DETALLES
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    {{-- Paginación Custom --}}
                    @if($wishlistGames->hasPages())
                        <div class="p-4 bg-darker border-top border-white-10 text-center">
                            {{ $wishlistGames->links() }}
                        </div>
                    @endif
                @endif
            </div>
        </div>

        {{-- Sidebar --}}
        <div class="col-lg-3">
            <div class="jg-panel p-4 mb-4">
                <h4 class="text-sun h5 mb-4 tracking-wider jg-font-title">MI CARRITO</h4>
                @php 
                    $userCart = \App\Models\Cart::where('user_id', Auth::id())->first();
                    $cartItems = $userCart ? $userCart->items()->with('game')->get() : collect();
                @endphp
                @if($cartItems->count() > 0)
                    <div class="mb-4">
                        @foreach($cartItems->take(3) as $item)
                            <div class="d-flex align-items-center gap-3 mb-3 pb-3 border-bottom border-white-10">
                                <img src="{{ asset('storage/' . $item->game->cover_image) }}" class="rounded-2 shadow-sm jg-cart-thumb-mini">
                                <div class="overflow-hidden">
                                    <div class="text-white small fw-bold text-truncate">{{ $item->game->title }}</div>
                                    <div class="text-sun small">{{ \App\Services\CurrencyService::format($item->game->getPriceForUser(Auth::user())) }}</div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <a href="{{ route('carrito.index') }}" class="jg-btn jg-btn-outline w-100 text-center py-2 text-decoration-none small d-block">
                        VER CARRITO COMPLETO
                    </a>
                @else
                    <div class="text-center py-4 opacity-50">
                        <i class="bi bi-cart-x fs-2 mb-2 d-block"></i>
                        <span class="small">El carrito está vacío</span>
                    </div>
                    <a href="{{ route('catalogo') }}" class="jg-btn jg-btn-outline w-100 text-center py-2 text-decoration-none small mt-3 d-block">
                        COMPRAR AHORA
                    </a>
                @endif
            </div>

            <div class="jg-panel p-4 mb-4">
                <h4 class="text-sun h5 mb-3 tracking-wider jg-font-title">TUS GÉNEROS FAVORITOS</h4>
                <div class="d-flex flex-wrap gap-2">
                    @forelse($userCategories as $category)
                        <a href="{{ route('catalogo', ['category' => $category->slug]) }}" 
                           class="badge bg-dark border-sun text-sun text-decoration-none px-3 py-2 jg-badge-hover transition-all">
                            <i class="bi bi-tag-fill me-1 opacity-50"></i> {{ strtoupper($category->name) }}
                        </a>
                    @empty
                        <div class="text-white-50 small opacity-80 py-2">
                            Añade juegos para ver tus preferencias aquí.
                        </div>
                    @endforelse
                </div>
            </div>

        </div>
    </div>
</div>


@endsection