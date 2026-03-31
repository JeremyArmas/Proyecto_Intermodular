@extends('layouts.app')

@section('title', 'Contacto - Jediga')

@section('content')
<div class="container py-2 mt-5">
    <div class="row g-5">
        <div class="col-lg-5">
            <h1 class="display-4 mb-4">Hablemos <br><span class="jg-sun">con Jediga</span></h1>
            <p class="mb-5">Si tienes una propuesta, un problema o simplemente quieres saludarnos, escríbenos.</p>
            
            <div class="d-flex align-items-center gap-3 mb-4">
                <div class="bg-sun bg-opacity-10 p-3 rounded-circle text-sun">
                    <i class="bi bi-geo-alt display-6"></i>
                </div>
                <div>
                    <h6 class="mb-0">Ubicación</h6>
                    <span class="small jg-muted">Las Palmas de G.C, España</span>
                </div>
            </div>

            <div class="d-flex align-items-center gap-3 mb-4">
                <div class="bg-sun bg-opacity-10 p-3 rounded-circle text-sun">
                    <i class="bi bi-envelope-at display-6"></i>
                </div>
                <div>
                    <h6 class="mb-0">Email</h6>
                    <span class="small jg-muted">soporte@jediga.test</span>
                </div>
            </div>
        </div>

        <div class="col-lg-7">
            <div class="bg-dark p-5 rounded-4 shadow border border-secondary border-opacity-25">
                <form action="#" method="POST">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label text-white small">Nombre</label>
                            <input type="text" class="form-control jg-input" placeholder="Tu nombre">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-white small">Email</label>
                            <input type="email" class="form-control jg-input" placeholder="email@ejemplo.com">
                        </div>
                        <div class="col-12">
                            <label class="form-label text-white small">Asunto</label>
                            <input type="text" class="form-control jg-input" placeholder="¿En qué podemos ayudarte?">
                        </div>
                        <div class="col-12">
                            <label class="form-label text-white small">Mensaje</label>
                            <textarea class="form-control jg-input" rows="4" placeholder="Escribe tu mensaje..."></textarea>
                        </div>
                        <div class="col-12 mt-4">
                            <button type="button" class="btn jg-btn jg-btn-sun w-100 btn-lg">Enviar Mensaje</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection