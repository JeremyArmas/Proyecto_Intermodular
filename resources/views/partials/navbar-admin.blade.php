<nav class="navbar navbar-expand-lg navbar-dark fixed-top jg-nav">
  <div class="container py-1">

    {{-- Brand -> Admin --}}
    <a class="navbar-brand d-flex align-items-center gap-3" href="{{ url('/admin') }}">
      <img src="{{ asset('images/logo_jediga_provisional.png') }}" alt="Logo">
      <div class="lh-sm">
        <div class="logoNombre">Jediga</div>
        <div class="small jg-muted">Administración</div>
      </div>
    </a>

    <button class="navbar-toggler" type="button"
      aria-controls="navJedigaAdmin"
      aria-expanded="false"
      aria-label="Abrir menú"
      data-jg-nav-toggle>
      <span class="navbar-toggler-icon"></span>
    </button>


    <div class="jg-nav-collapse" id="navJedigaAdmin" data-jg-nav-panel>
      <ul class="navbar-nav ms-auto align-items-lg-center gap-lg-2">

        {{-- Links públicos (si quieres que el admin navegue el sitio) --}}
        <li class="nav-item"><a class="nav-link" href="{{ url('/catalogo') }}">Catálogo</a></li>
        <li class="nav-item"><a class="nav-link" href="{{ url('/noticias') }}">Noticias</a></li>
        <li class="nav-item"><a class="nav-link" href="{{ url('/soporte') }}">Soporte</a></li>

        {{-- Admin quick links (a tu misma vista con hash) --}}
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            Panel
          </a>

          <ul class="dropdown-menu dropdown-menu-end"
              style="background: rgba(14,14,26,.95); border:1px solid var(--jg-border);">
            <li><a class="dropdown-item text-light" href="{{ url('/admin#productos') }}">
              <i class="bi bi-box-seam me-2"></i> Productos
            </a></li>
            <li><a class="dropdown-item text-light" href="{{ url('/admin#categorias') }}">
              <i class="bi bi-tags me-2"></i> Categorías
            </a></li>
            <li><a class="dropdown-item text-light" href="{{ url('/admin#usuarios') }}">
              <i class="bi bi-people me-2"></i> Usuarios
            </a></li>
            <li><a class="dropdown-item text-light" href="{{ url('/admin#pedidos') }}">
              <i class="bi bi-receipt me-2"></i> Pedidos
            </a></li>

            <li><hr class="dropdown-divider" style="border-color: rgba(255,255,255,.10)"></li>

            <li><a class="dropdown-item text-light" href="{{ url('/admin') }}">
              <i class="bi bi-speedometer2 me-2"></i> Dashboard
            </a></li>
          </ul>
        </li>
      </ul>

      {{-- Idioma + Admin actions --}}
      <div class="d-flex align-items-center gap-2 ms-lg-3 mt-3 mt-lg-0">

        {{-- Idioma (mismo que public, pero arreglado “global” con CSS luego) --}}
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

        {{-- Volver al sitio (opcional) --}}
        <a class="btn jg-btn jg-btn-outline" href="{{ url('/') }}">
          <i class="bi bi-arrow-left me-1"></i> Volver al home
        </a>

        {{-- Cerrar sesión (POST real) --}}
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
