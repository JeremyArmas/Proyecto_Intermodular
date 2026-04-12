@extends('layouts.app')

@section('title', 'Recuperar contraseña – Jediga')

@section('content')

<!--
  VISTA: forgot-password
  El usuario llega aquí desde el enlace "¿Olvidaste la contraseña?" del modal de login.
  Reutilizamos las clases de auth.css (login-modal__left / right) para mantener
  el mismo aspecto que los modales de login y registro, pero en página completa.
-->

<div class="d-flex align-items-center justify-content-center" style="min-height: calc(100vh - 72px); padding: 2rem 1rem;">
  <div class="login-modal show d-block position-relative w-100" style="max-width: 860px;">
    <div class="modal-dialog modal-dialog-centered m-0 w-100" style="max-width:100%;">
      <div class="modal-content overflow-hidden border-0">
        <div class="row g-0">

          <!-- Panel izquierdo: branding (idéntico al modal de login) -->
          <div class="col-lg-4 col-md-4 d-none d-md-block login-modal__left">
            <div class="login-modal__left-inner">
              <a href="{{ url('/') }}" class="login-modal__mark" aria-label="Ir a inicio">
                <img class="login-modal__mark-img"
                     src="{{ asset('images/logo_jediga_provisional.png') }}"
                     alt="Logo Jediga">
              </a>
            </div>
          </div>

          <!-- Panel derecho: formulario -->
          <div class="col-lg-8 col-md-8 login-modal__right">

            <div class="modal-head mb-3">
              <div>
                <h1 class="h2 mb-1">¿Olvidaste tu contraseña?</h1>
                <div class="opacity-75 small">Introduce tu email y te enviamos un enlace para restablecerla.</div>
              </div>
            </div>

            <!--
              Mensaje de éxito: aparece cuando el controlador hace back()->with('status', '...')
              tras enviar correctamente el enlace al email.
            -->
            @if (session('status'))
              <div class="alert alert-success d-flex align-items-center gap-2 mb-4">
                <i class="bi bi-check-circle-fill"></i>
                <span>{{ session('status') }}</span>
              </div>
            @endif

            <form method="POST" action="{{ route('password.email') }}" novalidate>
              @csrf

              <div class="mb-4">
                <label for="email" class="form-label">Correo electrónico</label>
                <input
                  type="email"
                  id="email"
                  name="email"
                  class="form-control form-control-lg @error('email') is-invalid @enderror"
                  value="{{ old('email') }}"
                  placeholder="tuemail@ejemplo.com"
                  required
                  autofocus
                >
                @error('email')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>

              <button type="submit" class="btn btn-jediga w-100 btn-lg">
                <i class="bi bi-envelope-fill me-2"></i>Enviar enlace de recuperación
              </button>

              <div class="mt-4 text-center small opacity-75">
                ¿Ya recuerdas tu contraseña?
                <a href="{{ url('/') }}" class="link-sun fw-bold" data-bs-toggle="modal" data-bs-target="#loginModal">
                  Iniciar sesión
                </a>
              </div>

            </form>
          </div>

        </div>
      </div>
    </div>
  </div>
</div>

@endsection
