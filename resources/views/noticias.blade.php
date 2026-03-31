@extends('layouts.app')

@section('title', 'Noticias - Jediga')

@section('content')
<div class="container py-5">
    <h1 class="display-4 mb-4">Últimas <span class="jg-sun">Noticias</span></h1>
    
    <div class="row g-4">
        @for($i=1; $i<=3; $i++)
        <div class="col-md-4">
            <div class="bg-dark rounded-4 overflow-hidden border border-secondary border-opacity-25 h-100">
                <div style="height: 200px; background: #222;"></div>
                <div class="p-4">
                    <span class="badge badge-sun mb-2">Gamer News</span>
                    <h4>Lanzamiento oficial de Astra Runner</h4>
                    <p class="text-muted small">El esperado roguelite desarrollado íntegramente en Las Palmas llega a la fase Beta abierta este mes...</p>
                    <a href="#" class="btn btn-link link-sun p-0 text-decoration-none">Leer más <i class="bi bi-arrow-right"></i></a>
                </div>
            </div>
        </div>
        @endfor
    </div>
</div>
@endsection