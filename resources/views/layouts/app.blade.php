<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>@yield('title', 'Jediga')</title>

  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Bootstrap Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
  <!-- Slider Swiper -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@12/swiper-bundle.min.css"/>

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

<!-- Link de las tipografías de google fonts -->
<link href="https://fonts.googleapis.com/css2?family=Coral+Pixels&family=Jersey+10&family=Jersey+25&family=Tiny5&display=swap" rel="stylesheet">


  @vite(['resources/css/app.css', 'resources/js/app.js'])


  @stack('styles')
</head>

<body>

  <!-- Animación del preloader -->
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

  <script>
    // Fail-safe para el preloader: si en 3 segundos no se ha ido solo, lo forzamos
    setTimeout(() => {
      const loader = document.getElementById('preloader');
      if (loader) {
        loader.classList.add('is-leaving');
        setTimeout(() => { if(loader.parentNode) loader.remove(); document.documentElement.style.overflow = ''; }, 1000);
      }
    }, 3000);
  </script>


  <!-- Modal (ventana emergente) del login -->
<div class="modal fade login-modal" id="loginModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-xl">
    <div class="modal-content overflow-hidden border-0">
      <div class="row g-0">

        <!-- IZQUIERDA: panel branding -->
      <div class="col-lg-4 col-md-4 d-none d-md-block login-modal__left">
        
        <!-- Contenedor del logo -->
        <div class="login-modal__left-inner">
          <a href="{{ url('/') }}" class="login-modal__mark" aria-label="Ir a inicio">
            <img class="login-modal__mark-img"
                src="{{ asset('images/logo_jediga_provisional.png') }}"
                alt="Logo Jediga">
          </a>
        </div>
      </div>

        <!-- DERECHA: formulario -->
        <div class="col-lg-8 col-md-8 login-modal__right">
          <div class="modal-head mb-3">
            
            <!-- Título del modal -->
            <div>
              <h2>Iniciar sesión</h2>
              <div class="opacity-75 small">Accede con tu cuenta</div>
            </div>

            <!-- Botón para cerrar el modal -->
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
          </div>

          <!-- Contenedor para mostrar errores generales -->
          <div id="contenedorErrores" class="alert alert-danger d-none mb-3"></div>

          <!-- Formulario de login -->
          <form method="POST" action="{{ route('login.submit') }}" class="mt-3">
            @csrf

            <!-- Campo de email -->
            <div class="mb-3">
              <label class="form-label">Email</label>
              <input type="email" name="email" class="form-control form-control-lg" value="{{ old('email') }}" required autofocus placeholder="tuemail@ejemplo.com">
              @error('email')
                <div class="text-danger small mt-1">{{ $message }}</div>
              @enderror
            </div>

            <!-- Campo de contraseña con botón para mostrar/ocultar -->
            <div class="mb-3">
              <label class="form-label">Contraseña</label>
                
              <div class="input-group input-group-lg">
                  <input id="loginPassword" type="password" name="password" class="form-control" required placeholder="••••••••" autocomplete="current-password">
                  <button class="btn" type="button" id="togglePassword" aria-label="Mostrar contraseña">
                    <i class="bi bi-eye-slash" id="togglePasswordIcon"></i>
                  </button>
              </div>
              
              @error('password')
                <div class="text-danger small mt-1">{{ $message }}</div>
              @enderror
            </div>

            <!-- Bloque con checkbox de "Recordarme" y enlace de "¿Olvidaste la contraseña?" -->
            <div class="d-flex justify-content-between align-items-center mb-4">
              <div class="form-check">
                <input class="form-check-input" type="checkbox" name="remember" id="remember">
                <label class="form-check-label" for="remember">Recordarme</label>
              </div>
         
              <a href="#" class="link-light small">¿Olvidaste la contraseña?</a>
            </div>


            <!-- Bloque de captcha, inicialmente oculto, se mostrará después de varios intentos fallidos -->
            <div class="mb-3 d-none" id="captchaBlock">
              <label class="form-label">Verificación</label>

              <div class="d-flex align-items-stretch gap-2 mb-2">
                <div class="jg-captcha-frame flex-grow-1">
                  {!! captcha_img('flat') !!}
                </div>

                <button type="button" class="btn jg-captcha-reload" id="reloadCaptcha" aria-label="Recargar captcha">
                  <i class="bi bi-arrow-clockwise"></i>
                </button>
              </div>

              <input type="text" name="captcha" class="form-control form-control-lg"
                placeholder="Escribe el texto de la imagen" autocomplete="off">
            </div>

            <button type="submit" class="btn btn-jediga w-100 btn-lg">
              Entrar
            </button>

            <div class="mt-4 text-center small opacity-75">
              ¿No tienes cuenta? 
              <a href="#" class="link-sun fw-bold" data-bs-toggle="modal" data-bs-target="#registerModal">Regístrate</a>
            </div>
          </form>
        </div>

      </div>
    </div>
  </div>
</div>

<!-- Modal del registro -->
<div class="modal fade login-modal" id="registerModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-xl">
    <div class="modal-content overflow-hidden border-0">
      <div class="row g-0">

        {{-- IZQUIERDA: panel branding --}}
      <div class="col-lg-4 col-md-4 d-none d-md-block login-modal__left">
        <div class="login-modal__left-inner">
          <a href="{{ url('/') }}" class="login-modal__mark" aria-label="Ir a inicio">
            <img class="login-modal__mark-img"
                src="{{ asset('images/logo_jediga_provisional.png') }}"
                alt="Logo Jediga">
          </a>
        </div>
      </div>

        {{-- DERECHA: formulario --}}
        <div class="col-lg-8 col-md-8 login-modal__right">
          <div class="modal-head mb-3">
            <div>
              <h2>Crear cuenta</h2>
              <div class="opacity-75 small">Únete a la comunidad de Jediga</div>
            </div>

            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
          </div>

          <div id="contenedorErroresRegistro" class="alert alert-danger d-none mb-3"></div>

          <form method="POST" action="{{ route('register') }}" class="mt-3">
            @csrf

            <div class="mb-3">
              <label class="form-label">Nombre completo</label>
              <input type="text" name="name" class="form-control form-control-lg" required placeholder="Tu nombre">
            </div>

            <div class="mb-3">
              <label class="form-label">Email</label>
              <input type="email" name="email" class="form-control form-control-lg" required placeholder="tuemail@ejemplo.com">
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Contraseña</label>
                    <input type="password" name="password" class="form-control form-control-lg" required placeholder="••••••••">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Confirmar contraseña</label>
                    <input type="password" name="password_confirmation" class="form-control form-control-lg" required placeholder="••••••••">
                </div>
            </div>

            {{-- BLOQUE DEL CAPTCHA (siempre visible en registro para evitar spam) --}}
            <div class="mb-4" id="captchaBlockRegistro">
              <label class="form-label">Verificación</label>

              <div class="d-flex align-items-stretch gap-2 mb-2">
                <div class="jg-captcha-frame flex-grow-1">
                  {!! captcha_img('flat') !!}
                </div>

                <button type="button" class="btn jg-captcha-reload" id="reloadCaptchaRegistro" aria-label="Recargar captcha">
                  <i class="bi bi-arrow-clockwise"></i>
                </button>
              </div>

              <input type="text" name="captcha" class="form-control form-control-lg"
                placeholder="Escribe el texto de la imagen" autocomplete="off" required>
            </div>

            <button type="submit" class="btn btn-jediga w-100 btn-lg">
              Crear cuenta
            </button>

            <div class="mt-4 text-center small opacity-75">
              ¿Ya tienes cuenta? 
              <a href="#" class="link-sun fw-bold" data-bs-toggle="modal" data-bs-target="#loginModal">Inicia sesión</a>
            </div>
          </form>
        </div>

      </div>
    </div>
  </div>
</div>

<!-- Fondo decorativo del sitio -->
<div class="jg-site-bg" aria-hidden="true"></div>

<!-- Navbar (Barra de navegación) -->
@php
  // Verifica si la ruta actual es una ruta de administrador y si el usuario lo es
  $isAdminRoute = request()->is('admin*');
  $isAdmin = auth()->check() && auth()->user()->isAdmin();
@endphp

<!-- Incluye la barra de navegación correspondiente según el tipo de usuario -->
@if($isAdminRoute && $isAdmin)
  @include('partials.navbar-admin')
@else
  @include('partials.navbar-public')
@endif

<!-- Contenedor principal donde se renderizará el contenido específico de cada página -->
<main>
  @yield('content')
</main>

<!-- Footer -->
@include('partials.footer')

<!-- Link Bootstrap js -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<!-- Link Swiper js -->
<script src="https://cdn.jsdelivr.net/npm/swiper@12/swiper-bundle.min.js"></script>
@stack('scripts')
</body>
</html>
