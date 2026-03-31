@extends('layouts.app')

@section('title', 'FAQ - Jediga')

@section('content')
<div class="container py-5">
    <div class="text-center mb-5">
        <h1 class="display-4 fw-bold">Preguntas <span class="jg-sun">Frecuentes</span></h1>
        <p class="text-white">Despeja tus dudas sobre compras, devoluciones y soporte.</p>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="accordion accordion-dark" id="accordionFAQ">
                @php
                    $faqs = [
                        ['q' => '¿Cómo puedo comprar un juego?', 'a' => 'Solo necesitas registrarte, añadir el juego a tu carrito y procesar el pago desde tu perfil.'],
                        ['q' => '¿Qué métodos de pago aceptáis?', 'a' => 'Aceptamos tarjetas de crédito/débito, PayPal y métodos locales para usuarios en Canarias.'],
                        ['q' => '¿Tengo soporte técnico garantizado?', 'a' => 'Sí, todos los juegos adquiridos en Jediga cuentan con soporte directo del equipo o del desarrollador.'],
                        ['q' => '¿Puedo solicitar un reembolso?', 'a' => 'Sí, siempre que no hayas jugado más de 2 horas y la compra sea reciente (menos de 14 días).'],
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
                        <div class="accordion-body text-muted small">
                            {{ $item['a'] }}
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

<style>
.accordion-button::after { filter: invert(1); }
.accordion-item:first-of-type, .accordion-item:last-of-type { border-radius: 1rem !important; }
</style>
@endsection