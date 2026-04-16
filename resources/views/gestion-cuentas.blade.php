@extends('layouts.app')

@section('title', 'FAQ - Jediga')

@section('content')
<div class="container py-5">
    <div class="text-center mb-5">
        <h1 class="display-4 jg-sun">Gestion de Cuentas</h1>
        <p class="text-white">Despeja tus dudas sobre como loguearte en la web y todo lo relacionado con tu cuenta.</p>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="accordion accordion-dark" id="accordionFAQ">
                @php
                    $faqs = [
                        ['q' => '¿No puedo comprar juegos sin cuenta?', 'a' => 'No, no puedes comprar juegos sin cuenta. Debes registrarte para poder comprar juegos.'],
                        ['q' => '¿He olvidado mi contraseña?', 'a' => 'Al intentar iniciar sesion, pulsa en "¿Has olvidado tu contraseña?" y sigue los pasos para restablecerla.'],
                        ['q' => '¿Puedo personalizar mi perfil?', 'a' => 'Sí, puedes personalizar tu perfil desde la sección "Mi Perfil" en tu cuenta.'],
                        ['q' => '¿Puedo comprar juegos en físicos?', 'a' => 'No, no puedes comprar juegos en físicos. Todos los juegos son digitales ( A menos que seas una empresa , en ese caso , contacta con nosotros).'],
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