@extends('layouts.app')

@section('title', 'Soporte - Jediga')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8 text-center">
            <h1 class="display-4 mb-4">Centro de <span class="jg-sun">Soporte</span></h1>
            <p class="lead mb-5">¿Tienes problemas con algún juego o con tu cuenta? Estamos aquí para ayudarte.</p>
            
            <div class="row g-4 text-start">
                <div class="col-md-6">
                    <div class="bg-dark p-4 rounded-4 border border-secondary border-opacity-25 h-100">
                        <i class="bi bi-person-badge text-sun display-6 mb-3"></i>
                        <h5>Gestión de Cuenta</h5>
                        <p class="small">Problemas con el login, recuperación de contraseña o cambio de datos personales.</p>
                        <a href="{{ route('soporte.gestion-cuentas') }}" class="btn btn-outline-light btn-sm">Ver guías</a>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="bg-dark p-4 rounded-4 border border-secondary border-opacity-25 h-100">
                        <i class="bi bi-controller text-sun display-6 mb-3"></i>
                        <h5>Técnico / Juegos</h5>
                        <p class="small">Errores de instalación, fallos en partida o configuración de hardware.</p>
                        <a href="{{ route('soporte.tecnico-juegos') }}" class="btn btn-outline-light btn-sm">Ver guías</a>
                    </div>
                </div>
            </div>

            <div class="mt-5 p-4 bg-sun bg-opacity-10 border border-sun rounded-4">
                <h4 class="text-sun">¿No encuentras la solución?</h4>
                <p>Nuestro equipo responde en menos de 24 horas laborables.</p>
                <a href="{{ url('/contacto') }}" class="btn jg-btn jg-btn-sun">Abrir Ticket de Soporte</a>
            </div>
        </div>
    </div>
</div>
@endsection