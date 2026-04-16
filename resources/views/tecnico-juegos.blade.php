@extends('layouts.app')

@section('title', 'Técnico/Juegos - Jediga')

@section('content')
<div class="container py-5">
    <div class="text-center mb-5">
        <h1 class="display-4 jg-sun">Técnico/Juegos</h1>
        <p class="text-white">Despeja tus dudas sobre errores de intslación , fallos o problemas de hardware.</p>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="accordion accordion-dark" id="accordionFAQ">
                @php
                    $faqs = [
                        ['q' => '¿Por que no se me instala el juego?', 'a' => 'Asegurate de tener espacio suficiente en tu disco duro y de tener una conexión a internet estable.'],
                        ['q' => '¿Por que me da fallos el juego?', 'a' => 'Asegurate de tener los drivers de tu tarjeta grafica actualizados y de tener una conexión a internet estable.'],
                        ['q' => 'No se me guardan las partidas', 'a' => 'Comprueba que la carpeta de guardado no este en modo solo lectura.'],
                        ['q' => 'El juego me va muy lento', 'a' => 'Comprueba que tu tarjeta grafica sea compatible con el juego y que tengas los drivers actualizados.'],
                    ];
                @endphp

                @foreach($faqs as $i => $item)
                <div class="accordion-item bg-dark border-secondary border-opacity-25 mb-3 rounded-4 overflow-hidden">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed bg-dark text-white shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#collapse{{$i}}">
                            {{ $item['q'] }}
                        </button>
                    </h2>
                    <div id="collapse{{$i}}" class="accordion-collapse collapse" data-bs-parent="#accordionFAQ">
                        <div class="accordion-body text-white opacity-75 small">
                            {{ $item['a'] }}
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

@endsection