@extends('layouts.app')

@section('title', 'Sobre Nosotros - Jediga')

@section('content')
<div class="container py-5">
    <div class="row align-items-center g-3 mt-4">
        <div class="col-lg-6">
            <h1 class="display-4 mb-4">La pasión por los <span class="jg-sun">Videojuegos</span> desde Canarias</h1>
            <p class="lead mb-4">En Jediga, creemos que el talento no tiene fronteras. Nacimos en el corazón de las Islas Canarias con una misión clara: <span class="jg-sun">conectar a los desarrolladores locales con el mundo.</span></p>
            <p>Nuestra plataforma ofrece una selección curada de títulos indie y grandes producciones, con un enfoque especial en la comunidad Canaria y el soporte personalizado.</p>
            <div class="d-flex gap-3 ">
                <div class="text-center">
                    <h2 class="fw-bold jg-sun mb-0">10k+</h2>
                    <span class="small jg-muted">Usuarios</span>
                </div>
                <div class="vr mx-3 opacity-25"></div>
                <div class="text-center">
                    <h2 class="fw-bold jg-sun mb-0">50+</h2>
                    <span class="small jg-muted">Juegos</span>
                </div>
                <div class="vr mx-3 opacity-25"></div>
                <div class="text-center">
                    <h2 class="fw-bold jg-sun mb-0">24/7</h2>
                    <span class="small jg-muted">Soporte</span>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="rounded-4 overflow-hidden shadow-lg" style="height: 400px; background: linear-gradient(135deg, #1e1e2d, #0f0f1a); display: grid; place-items: center;">
                <img src="{{ asset('images/logo_jediga_provisional.png') }}" style="width: 180px; opacity: 1;">
            </div>
        </div>
    </div>
</div>
@endsection