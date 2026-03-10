<nav class="navbar navbar-expand-lg navbar-dark fixed-top jg-nav">
  <div class="container-lg py-1 px-3">

    <!-- Logo + nombre -->
    <a class="navbar-brand d-flex align-items-center gap-3" href="{{ url('/') }}">
      <img src="{{ asset('images/logo_jediga_provisional.png') }}" alt="Logo">
      <div class="lh-sm">
        <div class="logoNombre">Jediga</div>
        <div class="small jg-muted">Videojuegos • Canarias</div>
      </div>
    </a>

    <!-- Toggle (desplegable) -->
    <button class="navbar-toggler ms-auto" type="button"
      id="jgNavToggle"
      aria-controls="navJediga"
      aria-expanded="false"
      aria-label="Abrir menú"
      data-jg-nav-toggle>
      <span class="navbar-toggler-icon"></span>
    </button>

    <!-- Menú -->
    <div class="jg-nav-collapse" id="navJediga" data-jg-nav-panel>
      <ul class="navbar-nav ms-auto align-items-lg-center gap-lg-2">
        
        <!-- Juegos (Mega dropdown) -->
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            Juegos
          </a>

          <!-- Mega dropdown personalizado -->
          <div class="dropdown-menu megamenu p-0">
            <div class="p-3">
              <div class="row g-3">
                <div class="col-md-6">

                  <!-- Plataformas -->
                  <div class="mm-title">Plataformas</div>
                  <a class="mm-item" href="{{ url('/catalogo?platform=pc') }}">
                    <span><i class="bi bi-pc-display me-2"></i> PC</span>
                    <span class="badge badge-sun">Ver</span>
                  </a>

                  <!-- PlayStation -->
                  <a class="mm-item" href="{{ url('/catalogo?platform=playstation') }}">
                    <span><i class="bi bi-playstation me-2"></i> PlayStation</span>
                    <span class="badge badge-sun">Ver</span>
                  </a>
                  
                  <!-- Xbox -->
                  <a class="mm-item" href="{{ url('/catalogo?platform=xbox') }}">
                    <span><i class="bi bi-xbox me-2"></i> Xbox</span>
                    <span class="badge badge-sun">Ver</span>
                  </a>
                  
                  <!-- Nintendo Switch -->
                  <a class="mm-item" href="{{ url('/catalogo?platform=switch') }}">
                    <span><i class="bi bi-nintendo-switch me-2"></i> Switch</span>
                    <span class="badge badge-sun">Ver</span>
                  </a>
                </div>

                <!-- Explorar -->
                <div class="col-md-6">
                  <div class="mm-title">Explorar</div>
                  
                  <!-- Últimos lanzamientos -->
                  <a class="mm-item" href="{{ url('/catalogo?sort=latest') }}">
                    <span><i class="bi bi-stars me-2"></i> Últimos lanzamientos</span>
                    <span class="badge badge-soft">Nuevo</span>
                  </a>
                  
                  <!-- Más populares -->
                  <a class="mm-item" href="{{ url('/catalogo?sort=popular') }}">
                    <span><i class="bi bi-fire me-2"></i> Más populares</span>
                    <span class="badge badge-sun">Top</span>
                  </a>
                  
                  <!-- Juegos gratuitos -->
                  <a class="mm-item" href="{{ url('/catalogo?price=free') }}">
                    <span><i class="bi bi-gift me-2"></i> Juegos gratuitos</span>
                    <span class="badge badge-mint">Gratis</span>
                  </a>
                  
                  <!-- Próximamente -->
                  <a class="mm-item" href="{{ url('/catalogo?status=upcoming') }}">
                    <span><i class="bi bi-clock-history me-2"></i> Próximamente</span>
                    <span class="badge badge-soft">Soon</span>
                  </a>

                  <!-- Separador -->
                  <div class="mt-3 p-3 rounded-4" style="border:1px solid var(--jg-border); background: rgba(255,255,255,.03);">
                  
                    <!-- Catálogo completo -->
                    <div class="fw-bold">Catálogo completo</div>
                    <div class="small jg-muted mb-2">Ver todos los juegos en la vista de catálogo.</div>
                    <a class="btn jg-btn jg-btn-primary w-100" href="{{ url('/catalogo') }}">
                      Ver todos <i class="bi bi-arrow-right ms-1"></i>
                    </a>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </li>

        <!-- Resto de enlaces -->
        <li class="nav-item"><a class="nav-link" href="{{ url('/sobre-nosotros') }}">Sobre nosotros</a></li>
        <li class="nav-item"><a class="nav-link" href="{{ url('/noticias') }}">Noticias</a></li>
        <li class="nav-item"><a class="nav-link" href="{{ url('/soporte') }}">Soporte</a></li>
        <li class="nav-item"><a class="nav-link" href="{{ url('/faq') }}">FAQ</a></li>
        <li class="nav-item"><a class="nav-link" href="{{ url('/contacto') }}">Contacto</a></li>
      </ul>

      <!-- Idioma + Auth -->
      <div class="d-flex align-items-center gap-2 ms-lg-3 mt-3 mt-lg-0">
        <div class="dropdown">
          <button class="btn jg-btn jg-btn-outline" data-bs-toggle="dropdown" aria-expanded="false" title="Idioma">
            <i class="bi bi-globe2"></i>
          </button>
          <ul class="dropdown-menu dropdown-menu-end jg-dd">
            <li><a class="dropdown-item" href="{{ url('/lang/es') }}">Español</a></li>
            <li><a class="dropdown-item" href="{{ url('/lang/en') }}">English</a></li>
          </ul>
        </div>

        <!-- Verificar roles para mostrar opciones específicas -->
        @php
          $isAdmin = auth()->check() && auth()->user()->isAdmin();
          $isCompany = auth()->check() && auth()->user()->isCompany();
          $isUser = auth()->check() && auth()->user()->isClient();
        @endphp

        <!-- NO logueado -->
        @guest
          <a class="btn jg-btn jg-btn-sun" data-bs-toggle="modal" data-bs-target="#loginModal" href="{{ url('/login') }}">
            Iniciar sesión
          </a>
          <a class="btn jg-btn jg-btn-primary" href="{{ url('/register') }}">
            Crear cuenta
          </a>
        @endguest

        <!-- Logueado -->
        @auth
          @if($isAdmin)
            <a class="btn jg-btn jg-btn-outline" href="{{ url('/admin') }}">
              <i class="bi bi-speedometer2 me-1"></i> Volver al panel
            </a>
          @endif

          <!-- Cerrar sesión -->
          <form method="POST" action="{{ route('logout') }}" class="m-0">
            @csrf
            <button type="submit" class="btn jg-btn jg-btn-sun">
              <i class="bi bi-box-arrow-right me-1"></i> Cerrar sesión
            </button>
          </form>
        @endauth

      </div>
    </div>
  </div>
</nav>