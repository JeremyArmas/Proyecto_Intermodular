<nav class="navbar navbar-expand-lg navbar-dark fixed-top jg-nav">
  <div class="container py-1">

    <!-- Logo + nombre -->
    <a class="navbar-brand d-flex align-items-center gap-3" href="{{ url('/admin') }}">
      <img src="{{ asset('images/logo_jediga_provisional.png') }}" alt="Logo">
      <div class="lh-sm">
        <div class="logoNombre">Jediga</div>
        <div class="small jg-muted">Administración</div>
      </div>
    </a>

    <!-- Toggle (desplegable) -->
    <button class="navbar-toggler" type="button"
      aria-controls="navJedigaAdmin"
      aria-expanded="false"
      aria-label="Abrir menú"
      data-jg-nav-toggle>
      <span class="navbar-toggler-icon"></span>
    </button>

    <!-- Menú -->
    <div class="jg-nav-collapse" id="navJedigaAdmin" data-jg-nav-panel>
      <ul class="navbar-nav ms-auto align-items-lg-center gap-lg-2">

        <!-- Links públicos -->
        <li class="nav-item"><a class="nav-link" href="{{ url('/catalogo') }}">Catálogo</a></li>
        <li class="nav-item"><a class="nav-link" href="{{ url('/noticias') }}">Noticias</a></li>
        <li class="nav-item"><a class="nav-link" href="{{ url('/soporte') }}">Soporte</a></li>
        <li class="nav-item"><a class="nav-link" href="{{ url('/faq') }}">FAQ</a></li>

      </ul>

      <!-- Idioma + Admin actions -->
      <div class="d-flex align-items-center gap-2 ms-lg-3 mt-3 mt-lg-0">
        <div class="dropdown">
          <button class="btn jg-btn jg-btn-outline" data-bs-toggle="dropdown" aria-expanded="false" title="Idioma">
            <i class="bi bi-globe2"></i>
          </button>
          <ul class="dropdown-menu dropdown-menu-end"
              style="background: rgba(14,14,26,.95); border:1px solid var(--jg-border);">
            <li><a class="dropdown-item text-light" href="{{ url('/lang/es') }}">Español</a></li>
            <li><a class="dropdown-item text-light" href="{{ url('/lang/en') }}">English</a></li>
          </ul>
        </div>

        <!-- Volver al home -->
        <a class="btn jg-btn jg-btn-outline" href="{{ url('/') }}">
          <i class="bi bi-arrow-left me-1"></i> Volver al home
        </a>

        <!-- Cerrar sesión -->
        <form method="POST" action="{{ route('logout') }}" class="m-0">
          @csrf
          <button type="submit" class="btn jg-btn jg-btn-sun">
            <i class="bi bi-box-arrow-right me-1"></i> Cerrar sesión
          </button>
        </form>

      </div>
    </div>
  </div>
</nav>
