@extends('layouts.app')

@section('title', 'Leer Ticket • Administración')

@section('content')
    <div class="jg-admin jg-admin-wrap">
        <div class="container">
            <div class="jg-admin-header p-4 mb-4">
                <div class="d-flex flex-wrap justify-content-between align-items-start gap-3">
                    <div>
                        <div class="jg-pill mb-2">
                            <span class="jg-dot"></span>
                            <span>Atención al cliente</span>
                        </div>
                        <h1 class="jg-section-title h3 mb-2" style="display:block;">Asunto: {{ $ticket->subject }}</h1>
                        <div class="jg-muted">Ticket escrito por <strong>{{ $ticket->name }}</strong> ({{ $ticket->email }})
                        </div>
                    </div>
                    <div class="d-flex gap-2 align-items-center">
                        <a href="{{ route('admin.tickets.index') }}" class="btn jg-btn jg-btn-outline">
                            <i class="bi bi-arrow-left me-1"></i> Volver a la bandeja
                        </a>
                    </div>
                </div>
            </div>

            <!-- Muestra el mensaje del cliente -->
            <div class="jg-card p-4 mb-4">
                <h4 class="mb-3">Mensaje del cliente:</h4>
                <div class="p-4"
                    style="background: rgba(255,255,255,0.03); border-radius: 8px; border: 1px solid rgba(255,255,255,0.1);">
                    <!-- nl2br respeta los saltos de línea que hizo el usuario -->
                    {!! nl2br(e($ticket->message)) !!}
                </div>
                <div class="mt-2 text-end">
                    <small class="jg-muted">Recibido el: {{ $ticket->created_at->format('d/m/Y H:i') }}</small>
                </div>
            </div>

            <!-- Formulario para responder por Email Mailable -->
            <div class="jg-card p-4">
                <h4 class="mb-3">Responder por Correo Oficial</h4>

                @if($ticket->status === 'pendiente')
                    @php
                        $canRespond = auth('admin')->user()->hasPermission('tickets.update');
                    @endphp
                    
                    @if($canRespond)
                        <!-- FÍJATE AQUÍ: Usamos update, que es la ruta correcta -->
                        <form action="{{ route('admin.tickets.update', $ticket->id) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="mb-3">
                                <label class="form-label jg-muted">Tu respuesta, la cual se enviará desde
                                    <b>jedigasupport@gmail.com</b> directamente al cliente:</label>
                                <textarea name="respuesta_email" class="form-control" rows="6"
                                    placeholder="Hola, {{ $ticket->name }}. Hemos revisado tu consulta y..." required
                                    style="background: rgba(255,255,255,0.06); color: white; border: 1px solid rgba(255,255,255,0.1);"></textarea>
                            </div>

                            <button type="submit" class="btn jg-btn jg-btn-primary w-100">
                                <i class="bi bi-send me-2"></i> Enviar respuesta oficial por correo
                            </button>
                        </form>
                    @else
                        <div class="alert alert-info" style="background: rgba(255, 255, 255, 0.03); border-color: rgba(255, 255, 255, 0.1); color: #fff;">
                            <i class="bi bi-info-circle me-2"></i> No tienes permisos para responder tickets de soporte. Contacta con un super-administrador si crees que es un error.
                        </div>
                    @endif
                @else
                    <div class="text-center py-4">
                        <i class="bi bi-check-circle text-success" style="font-size: 3rem;"></i>
                        <h5 class="mt-3">Este ticket ya fue respondido</h5>
                        <p class="jg-muted">No puedes volver a responder a un caso cerrado.</p>
                    </div>

                    <div class="mt-4">
                        <h5>Respuesta enviada al cliente:</h5>
                        <div class="p-4"
                            style="background: rgba(255,255,255,0.03); border-radius: 8px; border: 1px solid rgba(255,255,255,0.1);">
                            <!-- Aquí mostramos la respuesta que se envió al cliente -->
                            {!! nl2br(e($ticket->respuesta_email)) !!}
                        </div>
                        <div class="mt-2 text-end">
                            <small class="jg-muted">Respondido el: {{ $ticket->updated_at->format('d/m/Y H:i') }}</small>
                        </div>

                @endif
            </div>

        </div>
    </div>
@endsection
