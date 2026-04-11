@extends('layouts.app')

@section('title', 'Nueva contraseña – Jediga')

@section('content')

<!--
  VISTA: reset-password
  El usuario llega aquí desde el enlace del correo de recuperación.
  La URL tiene forma: /reset-password/{token}?email=xxx@yyy.com
  Mismas clases de auth.css que forgot-password y los modales de login/registro.
-->

<div class="d-flex align-items-center justify-content-center" style="min-height: calc(100vh - 72px); padding: 2rem 1rem;">
  <div class="login-modal show d-block position-relative w-100" style="max-width: 860px;">
    <div class="modal-dialog modal-dialog-centered m-0 w-100" style="max-width:100%;">
      <div class="modal-content overflow-hidden border-0">
        <div class="row g-0">

          <!-- Panel izquierdo: branding -->
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
                <h1 class="h2 mb-1">Nueva contraseña</h1>
                <div class="opacity-75 small">Elige una contraseña segura de al menos 8 caracteres.</div>
              </div>
            </div>

            <!-- Error general: token inválido o expirado -->
            @if ($errors->has('email') && !$errors->has('password'))
              <div class="alert alert-danger d-flex align-items-center gap-2 mb-4">
                <i class="bi bi-exclamation-triangle-fill"></i>
                <span>{{ $errors->first('email') }}</span>
              </div>
            @endif

            <form method="POST" action="{{ route('password.update') }}" novalidate>
              @csrf

              <!--
                Token oculto: string generado por Laravel y guardado (hasheado) en password_reset_tokens.
                Viene de la URL /reset-password/{token} y se pasa desde web.php como $token.
              -->
              <input type="hidden" name="token" value="{{ $token }}">

              <!--
                Email: pre-rellenado desde la query string del enlace del correo (?email=...).
                Es readonly porque el broker lo necesita para verificar que el token
                pertenece exactamente a este usuario.
              -->
              <div class="mb-3">
                <label for="email" class="form-label">Correo electrónico</label>
                <input
                  type="email"
                  id="email"
                  name="email"
                  class="form-control form-control-lg @error('email') is-invalid @enderror"
                  value="{{ old('email', request()->get('email')) }}"
                  required
                  readonly
                >
                @error('email')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>

              <!-- Nueva contraseña con toggle mostrar/ocultar (mismo estilo que el modal de login) -->
              <div class="mb-3">
                <label for="password" class="form-label">Nueva contraseña</label>
                <div class="input-group input-group-lg">
                  <input
                    type="password"
                    id="password"
                    name="password"
                    class="form-control @error('password') is-invalid @enderror"
                    placeholder="Mínimo 8 caracteres"
                    required
                    autocomplete="new-password"
                  >
                  <button class="btn" type="button" id="togglePassword" aria-label="Mostrar contraseña">
                    <i class="bi bi-eye-slash text-white" id="togglePasswordIcon"></i>
                  </button>
                </div>
                @error('password')
                  <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror
              </div>

              <!--
                Confirmación: DEBE llamarse password_confirmation para que la regla
                confirmed de Laravel la valide automáticamente contra password.
              -->
              <div class="mb-4">
                <label for="password_confirmation" class="form-label">Confirmar contraseña</label>
                <div class="input-group input-group-lg">
                  <input
                    type="password"
                    id="password_confirmation"
                    name="password_confirmation"
                    class="form-control"
                    placeholder="Repite la contraseña"
                    required
                    autocomplete="new-password"
                  >
                  <button class="btn" type="button" id="togglePasswordConfirm" aria-label="Mostrar confirmación">
                    <i class="bi bi-eye-slash text-white" id="togglePasswordConfirmIcon"></i>
                  </button>
                </div>
              </div>

              <button type="submit" class="btn btn-jediga w-100 btn-lg">
                <i class="bi bi-shield-lock-fill me-2"></i>Guardar nueva contraseña
              </button>

            </form>
          </div>

        </div>
      </div>
    </div>
  </div>
</div>

@endsection

@push('scripts')
<script>
  // Toggle mostrar/ocultar contraseña, mismo patrón que el modal de login
  function setupToggle(btnId, iconId, inputId) {
    const btn  = document.getElementById(btnId);
    const icon = document.getElementById(iconId);
    const inp  = document.getElementById(inputId);
    
    // Si no existe el botón, no hacemos nada
    if (!btn){
      return;
    }

    // Event listener para mostrar/ocultar contraseña
    btn.addEventListener('click', () => {
      const show = inp.type === 'password';
      inp.type   = show ? 'text' : 'password';
      icon.classList.toggle('bi-eye',       show);
      icon.classList.toggle('bi-eye-slash', !show);
    });
  }

  // Configuración de los toggles de contraseña 
  setupToggle('togglePassword', 'togglePasswordIcon', 'password');
  setupToggle('togglePasswordConfirm', 'togglePasswordConfirmIcon', 'password_confirmation');
</script>
@endpush
