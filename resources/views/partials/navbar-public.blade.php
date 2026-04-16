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
                  <a class="mm-item" href="{{ url('/catalogo?price_max=0') }}">
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

      <!-- Idioma + Carrito + Auth -->
      <div class="d-flex align-items-center gap-2 ms-lg-3 mt-3 mt-lg-0">
        
        <!-- Carrito -->
        @if($isWebActive)
          <a href="{{ route('carrito.index') }}" class="btn jg-btn jg-btn-outline position-relative" title="Ver Carrito">
        @else
          <a href="#" class="btn jg-btn jg-btn-outline position-relative" data-bs-toggle="modal" data-bs-target="#loginModal" title="Inicia sesión para ver el carrito">
        @endif
          <i class="bi bi-cart3"></i>
        @php
            $cartCount = 0;
            if ($isWebActive && $user) {
                $cart = $user->cart;
                if ($cart) {
                    $cartCount = $cart->items()->sum('quantity');
                }
            }
        @endphp
        @if($cartCount > 0)
          <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill shadow-sm" style="background-color: #ffcc00 !important; color: #000 !important; font-size: 0.75rem; font-weight: 900; border: 1px solid rgba(255,255,255,0.2);">
            {{ $cartCount }}
          </span>
        @endif
      </a>

        <!-- Selector de Moneda -->
        <div class="dropdown">
          <button class="btn jg-btn jg-btn-outline d-flex align-items-center gap-1" data-bs-toggle="dropdown" aria-expanded="false" title="Cambiar Moneda">
            <span>{{ \App\Services\CurrencyService::getSymbol() }}</span>
            <span class="small opacity-50">{{ \App\Services\CurrencyService::getCurrent() }}</span>
          </button>
          <ul class="dropdown-menu dropdown-menu-end jg-dd">
            <li><a class="dropdown-item" href="{{ route('currency.switch', 'EUR') }}">Euro (€)</a></li>
            <li><a class="dropdown-item" href="{{ route('currency.switch', 'USD') }}">Dólar ($)</a></li>
            <li><a class="dropdown-item" href="{{ route('currency.switch', 'GBP') }}">Libra (£)</a></li>
          </ul>
        </div>

        <div class="dropdown">
          <button class="btn jg-btn jg-btn-outline" data-bs-toggle="dropdown" aria-expanded="false" title="Idioma">
            <i class="bi bi-globe2"></i>
          </button>
          <ul class="dropdown-menu dropdown-menu-end jg-dd"> <!-- Opciones de idioma -->
            <li><a class="dropdown-item" href="#" onclick="cambiarIdioma('es')">Español</a></li>
            <li><a class="dropdown-item" href="#" onclick="cambiarIdioma('en')">English</a></li>
            <li><a class="dropdown-item" href="#" onclick="cambiarIdioma('de')">Alemán</a></li>
            <li><a class="dropdown-item" href="#" onclick="cambiarIdioma('it')">Italiano</a></li>
            <li><a class="dropdown-item" href="#" onclick="cambiarIdioma('pt')">Portugués</a></li>
          </ul>
        </div>

        <!-- Verificar roles (variables ya definidas en app.blade.php) -->
        @php
          $isCompany = $isWebActive && $user && $user->isCompany();
          $isUser = $isWebActive && $user && $user->isClient();
        @endphp

        <!-- NO logueado -->
        @if(!$anyAuth)
          <a class="btn jg-btn jg-btn-sun" data-bs-toggle="modal" data-bs-target="#loginModal" href="#">
            Iniciar sesión
          </a>
          <a class="btn jg-btn jg-btn-primary" data-bs-toggle="modal" data-bs-target="#registerModal" href="#">
            Crear cuenta
          </a>
        @endif

        <!-- Logueado -->
        @if($anyAuth && $user)
          <div class="dropdown">
            <button class="btn jg-btn jg-btn-outline d-flex align-items-center gap-2 p-1 pe-3 rounded-pill" 
                    type="button" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">

              @if($user->avatar)
              <img src="{{ asset('avatars/' . $user->avatar) }}" class="rounded-circle"
                        style="width:32px; height:32px; object-fit:cover; border: 1px solid #ffcc00;">
              @else
              <div class="rounded-circle d-flex align-items-center justify-content-center shadow-sm" 
                   style="width: 32px; height: 32px; background-color: #ffcc00 !important; color: #000 !important; font-weight: 900; font-size: 1rem; font-family: var(--jg-font-title);">
                {{ strtoupper(substr($user->name, 0, 1)) }}
              </div>
              @endif

              <span class="d-none d-sm-inline small text-white fw-bold">{{ explode(' ', $user->name)[0] }}</span>
              <i class="bi bi-chevron-down small opacity-50"></i>
            </button>
            <ul class="dropdown-menu dropdown-menu-end shadow-lg border-secondary mt-2 p-2 jg-panel" aria-labelledby="userDropdown" style="min-width: 200px;">
              <li class="px-3 py-2 border-bottom border-secondary mb-2">
                <div class="small text-white">Sesión de:</div>
                <div class="text-white text-truncate">{{ $user->name }}</div>
              </li>
              @if($isAdmin)
                <li>
                  <a class="dropdown-item py-2 rounded-3" href="{{ url('/admin') }}"> <!-- Ruta a admin -->
                    <i class="bi bi-speedometer2 me-2 text-sun"></i> Panel Admin
                  </a>
                </li>
              @endif
              <li>
                <a class="dropdown-item py-2 rounded-3" href="{{ route('profile.show') }}"> <!-- Ruta a perfil -->
                  <i class="bi bi-person me-2 text-sun"></i> Mi Perfil
                </a>
              </li>
              <li>
                <a class="dropdown-item py-2 rounded-3" href="{{ url('/biblioteca') }}"> <!-- Ruta a biblioteca -->
                  <i class="bi bi-collection-play me-2 text-sun"></i> Mi Biblioteca
                </a>
              </li>
              <li>
                <a class="dropdown-item py-2 rounded-3" href="{{ route('profile.orders') }}">
                  <i class="bi bi-bag-check me-2 text-sun"></i> Mis Pedidos
                </a>
              </li>
              <li><hr class="dropdown-divider border-secondary opacity-25"></li>
              <li>
                <form method="POST" action="{{ route('logout') }}" class="m-0">
                  @csrf
                  <button type="submit" class="dropdown-item py-2 rounded-3 text-danger">
                    <i class="bi bi-box-arrow-right me-2"></i> Cerrar sesión
                  </button>
                </form>
              </li>
            </ul>
          </div>
        @endif

      </div>
    </div>
  </div>
</nav>