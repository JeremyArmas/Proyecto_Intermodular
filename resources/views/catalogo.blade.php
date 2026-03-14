@extends('layouts.app')

@section('title', 'Catálogo de Juegos - Jediga')

@section('content')
<div class="container py-5 mt-5">
    <div class="text-center mb-5">
        <h1 class="display-4 fw-bold text-white">Catálogo de <span class="text-sun">Juegos</span></h1>
        <p class="lead text-muted">Explora nuestra selección de los mejores videojuegos.</p>
    </div>

    <div class="row g-4">
        @php
            $games = [
                ['title' => 'Astra Runner', 'desc' => 'Acción / Roguelite', 'slug' => 'astra-runner'],
                ['title' => 'Neon Rift', 'desc' => 'Shooter táctico', 'slug' => 'neon-rift'],
                ['title' => 'Skyforge Lite', 'desc' => 'Aventura ligera', 'slug' => 'skyforge-lite'],
                ['title' => 'Pulse Arena', 'desc' => 'Arena rápido', 'slug' => 'pulse-arena'],
                ['title' => 'Echoes DLC', 'desc' => 'Contenido extra', 'slug' => 'echoes-dlc'],
                ['title' => 'Zero Byte', 'desc' => 'Indie corto', 'slug' => 'zero-byte'],
            ];
        @endphp

        @foreach($games as $game)
        <div class="col-md-4">
            <div class="card jg-card h-100">
                <div class="card-img-top jg-card-img" style="height: 200px; background: linear-gradient(45deg, #121212, #2a2a2a);"></div>
                <div class="card-body">
                    <h5 class="card-title text-white">{{ $game['title'] }}</h5>
                    <p class="card-text text-muted">{{ $game['desc'] }}</p>
                    <a href="{{ url('/juego/'.$game['slug']) }}" class="btn jg-btn jg-btn-sun w-100">Ver Ficha</a>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection
