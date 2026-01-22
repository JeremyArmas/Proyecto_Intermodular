<nav class="navbar navbar-expand-lg navbar-dark fixed-top jg-nav">
    <div class="container py-2">
      <a class="navbar-brand d-flex align-items-center gap-3" href="{{ url('/') }}">
        <span class="jg-brand">JG</span>
        <div class="lh-sm">
          <div class="fw-bold">Jediga</div>
          <div class="small jg-muted">Videojuegos • Canarias</div>
        </div>
      </a>

      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navJediga" aria-controls="navJediga" aria-expanded="false" aria-label="Abrir menú">
        <span class="navbar-toggler-icon"></span>
      </button>

  <div class="navbar-collapse" id="navJediga">
        <ul class="navbar-nav ms-auto align-items-lg-center gap-lg-2">
          <!-- Juegos (Mega dropdown) -->
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
              Juegos
            </a>

            <div class="dropdown-menu megamenu p-0">
              <div class="p-3">
                <div class="row g-3">
                  <div class="col-md-6">
                    <div class="mm-title">Plataformas</div>
                    <a class="mm-item" href="{{ url('/catalogo?platform=pc') }}">
                      <span><i class="bi bi-pc-display me-2"></i> PC</span>
                      <span class="badge badge-sun">Ver</span>
                    </a>
                    <a class="mm-item" href="{{ url('/catalogo?platform=playstation') }}">
                      <span><i class="bi bi-playstation me-2"></i> PlayStation</span>
                      <span class="badge badge-sun">Ver</span>
                    </a>
                    <a class="mm-item" href="{{ url('/catalogo?platform=xbox') }}">
                      <span><i class="bi bi-xbox me-2"></i> Xbox</span>
                      <span class="badge badge-sun">Ver</span>
                    </a>
                    <a class="mm-item" href="{{ url('/catalogo?platform=switch') }}">
                      <span><i class="bi bi-nintendo-switch me-2"></i> Switch</span>
                      <span class="badge badge-sun">Ver</span>
                    </a>
                  </div>

                  <div class="col-md-6">
                    <div class="mm-title">Explorar</div>
                    <a class="mm-item" href="{{ url('/catalogo?sort=latest') }}">
                      <span><i class="bi bi-stars me-2"></i> Últimos lanzamientos</span>
                      <span class="badge badge-soft">Nuevo</span>
                    </a>
                    <a class="mm-item" href="{{ url('/catalogo?sort=popular') }}">
                      <span><i class="bi bi-fire me-2"></i> Más populares</span>
                      <span class="badge badge-sun">Top</span>
                    </a>
                    <a class="mm-item" href="{{ url('/catalogo?price=free') }}">
                      <span><i class="bi bi-gift me-2"></i> Juegos gratuitos</span>
                      <span class="badge badge-mint">Gratis</span>
                    </a>
                    <a class="mm-item" href="{{ url('/catalogo?status=upcoming') }}">
                      <span><i class="bi bi-clock-history me-2"></i> Próximamente</span>
                      <span class="badge badge-soft">Soon</span>
                    </a>

                    <div class="mt-3 p-3 rounded-4" style="border:1px solid var(--jg-border); background: rgba(255,255,255,.03);">
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
            <ul class="dropdown-menu dropdown-menu-end" style="background: rgba(14,14,26,.95); border:1px solid var(--jg-border);">
              <li><a class="dropdown-item text-light" href="{{ url('/lang/es') }}">Español</a></li>
              <li><a class="dropdown-item text-light" href="{{ url('/lang/en') }}">English</a></li>
            </ul>
          </div>

          <a class="btn jg-btn jg-btn-outline" href="{{ url('/login') }}">Iniciar sesión</a>
          <a class="btn jg-btn jg-btn-primary" href="{{ url('/register') }}">Crear cuenta</a>
        </div>
      </div>
    </div>
  </nav>