@extends('layouts.app')

@section('title', $news->title . ' - Jediga')

@section('content')
<div class="container py-5" style="margin-top: 100px; min-height: 50vh;">
    <div class="row bg-dark text-white rounded p-5 shadow">
        <div class="col-12">
            
            <!-- Si tiene imagen, le ponemos una caja arriba bonita tipo Portada -->
            @if($news->image)
                <img src="{{ asset('storage/' . $news->image) }}" alt="Portada" class="img-fluid rounded mb-4" style="max-height: 400px; width: 100%; object-fit: cover;">
            @endif
            
            <!-- Fecha Amarillita -->
            <span class="badge badge-sun mb-3 fs-6">{{ $news->created_at->locale('es')->isoFormat('D [de] MMMM [de] YYYY') }}</span>
            
            <!-- Título -->
            <h1 class="fw-bold text-warning mb-4">{{ $news->title }}</h1>
            
            <!-- Texto conservando los saltos de línea (pre-line) -->
            <div class="opacity-75" style="line-height: 1.8; font-size: 1.1rem; white-space: pre-line;">
                {{ $news->content }}
            </div>
            
            <!-- Botón Volver -->
            <div class="mt-5">
                <a href="{{ url()->previous() }}" class="btn btn-outline-warning px-4 py-2">
                    <i class="bi bi-arrow-left"></i> Volver atrás
                </a>
            </div>

        </div>
    </div>
</div>
@endsection
