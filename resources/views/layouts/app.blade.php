<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>@yield('title', 'Jediga')</title>

  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Bootstrap Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>


<link href="https://fonts.googleapis.com/css2?family=Coral+Pixels&family=Jersey+10&family=Jersey+25&family=Tiny5&display=swap" rel="stylesheet">


  @vite(['resources/css/app.css', 'resources/js/app.js'])


  @stack('styles')
</head>

<body>

  {{-- Animación de refrescar --}}
  <div class="loader-wrap" id="preloader" aria-hidden="true">
    <div class="loader-wrap-heading">
      <div class="load-load-text">
        <span style="--i:0">J</span>
        <span style="--i:1">E</span>
        <span style="--i:2">D</span>
        <span style="--i:3">I</span>
        <span style="--i:4">G</span>
        <span style="--i:5">A</span>
      </div>
    </div>
  </div>

  <!-- Modal del login -->
<div class="modal fade login-modal" id="loginModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-xl">
    <div class="modal-content overflow-hidden border-0">
      <div class="row g-0">

        {{-- IZQUIERDA: panel branding --}}
        <div class="col-md-5 d-none d-md-block login-modal__left">
          <div class="login-modal__left-inner">
            <div class="d-flex align-items-center gap-2 mb-4">
              <div class="login-modal__logo">JG</div>
              <div>
                <div class="fw-semibold">Jediga</div>
                <div class="opacity-75 small">Videojuegos • Canarias</div>
              </div>
            </div>

            <h3 class="fw-bold mb-2">Bienvenido</h3>
            <p class="opacity-75 mb-4">Inicia sesión para continuar</p>

            <div class="login-modal__badge">
              Acceso al panel de administración y gestión de productos
            </div>
          </div>
        </div>

        {{-- DERECHA: formulario --}}
        <div class="col-md-7 login-modal__right">
          <div class="d-flex justify-content-between align-items-start mb-3">
            <div>
              <h4 class="fw-bold mb-1">Iniciar sesión</h4>
              <div class="opacity-75 small">Accede con tu cuenta</div>
            </div>

            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
          </div>

          <form method="POST" action="{{ route('login') }}" class="mt-3">
            @csrf

            <div class="mb-3">
              <label class="form-label">Email</label>
              <input
                type="email"
                name="email"
                class="form-control form-control-lg"
                value="{{ old('email') }}"
                required
                autofocus
                placeholder="tuemail@ejemplo.com"
              >
              @error('email')
                <div class="text-danger small mt-1">{{ $message }}</div>
              @enderror
            </div>

            <div class="mb-3">
              <label class="form-label">Contraseña</label>
              <input
                type="password"
                name="password"
                class="form-control form-control-lg"
                required
                placeholder="••••••••"
              >
              @error('password')
                <div class="text-danger small mt-1">{{ $message }}</div>
              @enderror
            </div>

            <div class="d-flex justify-content-between align-items-center mb-4">
              <div class="form-check">
                <input class="form-check-input" type="checkbox" name="remember" id="remember">
                <label class="form-check-label" for="remember">Recordarme</label>
              </div>
              {{-- si luego haces reset password, aquí pones link --}}
              {{-- <a href="#" class="link-light small">¿Olvidaste la contraseña?</a> --}}
            </div>

            <button type="submit" class="btn btn-jediga w-100 btn-lg">
              Entrar
            </button>
          </form>
        </div>

      </div>
    </div>
  </div>
</div>

  {{-- Capa de fondo --}}
  <div class="jg-site-bg" aria-hidden="true"></div>

  {{-- Navbar --}}
  @include('partials.navbar')

  <main>
    @yield('content')
  </main>

  {{-- Footer --}}
  @include('partials.footer')

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  @stack('scripts')
</body>
</html>
