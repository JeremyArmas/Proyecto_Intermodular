@extends('layouts.app')

@section('title', 'Tu Carrito - Jediga')

@section('content')
<div class="container py-5">
    <div class="text-center mb-5">
        <h1 class="display-4 fw-bold text-white">Tu <span class="text-sun">Carrito</span> de Compra</h1>
        <p class="lead text-white opacity-75">Gestiona tus juegos seleccionados antes de finalizar el pedido.</p>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show bg-dark text-success border-success" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show bg-dark text-danger border-danger" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row g-4">
        @if($items->count() > 0)
            <!-- Listado de Productos -->
            <div class="col-lg-8">
                <div class="card jg-card border-0 shadow-sm mb-4">
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-dark table-hover align-middle mb-0 jg-table">
                                <thead class="bg-darker">
                                    <tr>
                                        <th class="ps-4 py-3">Producto</th>
                                        <th class="py-3 text-center">Cantidad</th>
                                        <th class="py-3 text-end">Precio</th>
                                        <th class="py-3 text-end pe-4">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($items as $item)
                                        <tr>
                                            <td class="ps-4">
                                                <div class="d-flex align-items-center gap-3">
                                                    <div class="rounded-3 overflow-hidden bg-secondary" style="width: 60px; height: 80px;">
                                                        @if($item->game->cover_image)
                                                            <img src="{{ asset('storage/' . $item->game->cover_image) }}" alt="{{ $item->game->title }}" class="w-100 h-100 object-fit-cover">
                                                        @else
                                                            <div class="w-100 h-100 d-flex align-items-center justify-content-center text-muted small">No IMG</div>
                                                        @endif
                                                    </div>
                                                    <div>
                                                        <h6 class="mb-0 text-white">{{ $item->game->title }}</h6>
                                                        <span class="small jg-muted">{{ $item->game->platform->name ?? 'Varios' }}</span>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="text-center">
                                                @if(auth()->user()->isCompany() || auth()->user()->isAdmin())
                                                <form action="{{ route('carrito.update', $item->id) }}" method="POST" class="d-flex align-items-center justify-content-center">
                                                    @csrf
                                                    @method('PUT')
                                                    <input type="number" name="quantity" value="{{ $item->quantity }}" min="1" max="{{ $item->game->stock }}" class="form-control form-control-sm bg-dark text-white border-secondary text-center" style="width: 70px;" onchange="this.form.submit()">
                                                    <button type="submit" class="btn btn-sm btn-outline-sun d-none" title="Actualizar">
                                                        <i class="bi bi-arrow-repeat"></i>
                                                    </button>
                                                </form>
                                                @else
                                                <span class="badge bg-dark border border-secondary text-white px-3 py-2">1</span>
                                                @endif
                                            </td>
                                            <td class="text-end text-sun fw-bold">
                                                {{ number_format($item->subtotal, 2) }}€
                                            </td>
                                            <td class="text-end pe-4">
                                                <form action="{{ route('carrito.remove', $item->id) }}" method="POST" onsubmit="return confirm('¿Eliminar este juego?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                                        <i class="bi bi-trash3"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-between align-items-center mb-4">
                    <a href="{{ url('/catalogo') }}" class="btn jg-btn-outline">
                        <i class="bi bi-arrow-left me-2"></i> Seguir Comprando
                    </a>
                    <form action="{{ route('carrito.clear') }}" method="POST" onsubmit="return confirm('¿Seguro que quieres vaciar el carrito?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-link text-danger text-decoration-none">
                            <i class="bi bi-x-circle me-1"></i> Vaciar Carrito
                        </button>
                    </form>
                </div>
            </div>

            <!-- Resumen del Pedido -->
            <div class="col-lg-4">
                <div class="card jg-card border-0 shadow-sm sticky-top" style="top: 100px;">
                    <div class="card-body p-4">
                        <h4 class="card-title text-white mb-4">Resumen del Pedido</h4>
                        
                        <div class="d-flex justify-content-between mb-3 px-1">
                            <span style="color: #ffffff !important; opacity: 1 !important; font-weight: 500;">Subtotal ({{ $items->count() }} Juegos)</span>
                            <span style="color: #ffffff !important; font-weight: 700;">{{ number_format($cart->total_price, 2) }}€</span>
                        </div>
                        
                        <div class="d-flex justify-content-between mb-3 px-1">
                            <span style="color: #ffffff !important; opacity: 1 !important; font-weight: 500;">Gastos de Envío</span>
                            <span style="color: #00ff9d !important; font-weight: 700;">Gratis</span>
                        </div>
                        
                        <hr class="opacity-10 my-4">
                        
                        <div class="d-flex justify-content-between align-items-center mb-4 px-1 pt-2">
                            <span class="h5 mb-0" style="color: #ffffff !important; font-weight: 700;">Total</span>
                            <span class="h3 mb-0" style="color: #ffcc00 !important; font-weight: 900;">{{ number_format($cart->total_price, 2) }}€</span>
                        </div>

                        <form action="{{ route('checkout.session') }}" method="POST">
                            @csrf
                            <button type="submit" class="btn jg-btn jg-btn-sun w-100 btn-lg shadow">
                                Tramitar Pedido <i class="bi bi-credit-card-2-back ms-2"></i>
                            </button>
                        </form>
                        
                        <div class="text-center mt-4 pt-2">
                            <div class="d-flex align-items-center justify-content-center gap-2 mb-2">
                                <span class="text-white opacity-50 fw-medium text-nowrap" style="font-size: 0.85rem;">Pago seguro con</span>
                                <img src="https://upload.wikimedia.org/wikipedia/commons/b/ba/Stripe_Logo%2C_revised_2016.svg" alt="Stripe" style="height: 22px !important; width: auto !important; filter: brightness(0) invert(1); opacity: 0.9;">
                            </div>
                            <div class="small text-white opacity-25">Transacción 100% encriptada</div>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <!-- Carrito Vacío -->
            <div class="col-12">
                <div class="card jg-card border-0 text-center py-5 px-4 shadow">
                    <div class="mb-4">
                        <div class="bg-dark d-inline-flex p-4 rounded-circle mb-3">
                            <i class="bi bi-cart-x text-white opacity-25 display-1"></i>
                        </div>
                        <h2 class="text-white">Tu carrito está vacío</h2>
                        <p class="text-white opacity-75">Parece que aún no has añadido nada. ¡Explora nuestro catálogo y descubre los mejores juegos!</p>
                    </div>
                    <div>
                        <a href="{{ url('/catalogo') }}" class="btn jg-btn-sun btn-lg px-5">
                            Ver el Catálogo
                        </a>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>

@endsection
