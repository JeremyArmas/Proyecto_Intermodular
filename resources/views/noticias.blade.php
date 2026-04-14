@extends('layouts.app')

@section('title', 'Noticias - Jediga')

@section('content')
<div class="container py-5">
    <h1 class="display-4 mb-4">Últimas <span class="jg-sun">Noticias</span></h1>
      
    <div class="row g-4">
        
        <!-- Recorremos de verdad las noticias que nos ha mandado tu web.php -->
        @forelse($noticias as $n)
        
        <div class="col-md-4">
            <div class="bg-dark rounded-4 overflow-hidden border border-secondary border-opacity-25 h-100">
                <!-- Mostramos tu foto subida en lugar de la foto gris -->
                <div style="height: 200px; background: url('{{ asset('storage/' . $n->image) }}') center/cover;"></div>
                
                <div class="p-4">
                    <!-- Ponemos la fecha en la placa amarilla -->
                    <span class="badge badge-sun mb-2">{{ $n->created_at->locale('es')->isoFormat('D [de] MMMM [de] YYYY') }}</span>
                    
                    <!-- Ponemos TU título -->
                    <h4>{{ $n->title }}</h4>
                    
                    <!-- Ponemos TU texto, pero si superas los 120 caracteres, pone "..." -->
                    <p class="text-white opacity-75 small">{{ Str::limit($n->content, 120) }}</p>
                    
                    <!-- Botón de leer más -->
                    <a href="{{ route('noticias.show.public', $n->id) }}" class="btn btn-link link-sun p-0 text-decoration-none">Leer más <i class="bi bi-arrow-right"></i></a>
                </div>
            </div>
        </div>
        
        @empty
            <div class="col-12 text-center py-5">
                <p class="text-muted">Aún no hay ninguna noticia redactada.</p>
            </div>
        @endforelse

    </div>

    <!-- Y este es el botoncito mágico para pasar a la página 2 si escribes más de 10 noticias -->
    <div class="d-flex justify-content-center mt-5">
        {{ $noticias->links() }}
    </div>
</div>
@endsection