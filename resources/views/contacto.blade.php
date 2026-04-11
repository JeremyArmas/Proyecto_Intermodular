@extends('layouts.app')

@section('title', 'Contacto - Jediga')

@section('content')
<div class="container py-5">
    <div class="row g-5">
        <div class="col-lg-5">
            <h1 class="display-4 mb-4">Hablemos <br><span class="jg-sun">con Jediga</span></h1>
            <p class="mb-5">Si tienes una propuesta, un problema o simplemente quieres saludarnos, escríbenos :D.</p>

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
                    <span class="small jg-muted">jedigasupport@gmail.com</span>
                </div>
            </div>
        </div>

        <div class="col-lg-7">
            <div class="bg-dark p-5 rounded-4 shadow border border-secondary border-opacity-25"> <!-- Formulario de contacto -->
                <form action="{{ route('contacto.store') }}" method="POST">
                    @csrf

                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <strong>Tu mensaje se ha enviado correctamente.</strong>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if(session('error_spam'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <strong>{{ session('error_spam') }}</strong>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif


                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label text-white small">Nombre</label>
                            <input type="text" name="name" id="name" value="{{ old('name') }}" class="form-control jg-input" placeholder="Tu nombre" required>

                            @error('name')
                                <span class="text-danger small">{{ $message }}</span>
                            @enderror

                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-white small">Email</label>
                            <input type="email" name="email" id="email" value="{{ old('email') }}" class="form-control jg-input" placeholder="email@ejemplo.com" required>

                            @error('email')
                                <span class="text-danger small">{{ $message }}</span>
                            @enderror

                        </div>
                        <div class="col-12">
                            <label class="form-label text-white small">Asunto</label>
                            <input type="text" name="subject" id="subject" value="{{ old('subject') }}" class="form-control jg-input" placeholder="¿En qué podemos ayudarte?" required>

                            @error('subject')
                                <span class="text-danger small">{{ $message }}</span>
                            @enderror

                        </div>
                        <div class="col-12">
                            <label class="form-label text-white small">Mensaje</label>
                            <textarea class="form-control jg-input" name="message" id="message"  rows="4" placeholder="Escribe tu mensaje..." required minlength="10">{{ old('message') }}</textarea>

                            @error('message')
                                <span class="text-danger small">{{ $message }}</span>
                            @enderror

                        </div>
                        <div class="col-12 mt-4">
                            <button type="submit" class="btn jg-btn jg-btn-sun w-100 btn-lg">Enviar Mensaje</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection