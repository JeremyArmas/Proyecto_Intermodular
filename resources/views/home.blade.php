<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Jediga</title>

  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Bootstrap Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

  <style>
    :root{
      /* Base */
      --jg-bg:#131224;          /* dark violeta suave (no negro puro) */
      --jg-bg2:#0b0b14;
      --jg-text:#f4f5ff;
      --jg-muted:#c8c9ea;

      /* Marca */
      --jg-purple:#7c3aed;
      --jg-purple2:#a855f7;


 


      /* “Sol” (3er color) -> da calidez, rompe monotonía */
      --jg-sun:#f6c053;         /* ámbar suave */
      --jg-sun2:#fdd765;

      /* Auxiliar (solo puntualmente, no neón) */
      --jg-mint:#34d399;        /* para “Gratis” (pequeños acentos) */

      --jg-border:rgba(255,255,255,.14);
      --jg-card:rgba(255,255,255,.06);
      --jg-card2:rgba(255,255,255,.09);
      --jg-radius:18px;
      --jg-shadow: 0 20px 70px rgba(0,0,0,.40);
    }

    body{
      color: var(--jg-text);
      background:
        radial-gradient(900px 550px at 12% 6%, rgba(168,85,247,.26), transparent 60%),
        radial-gradient(900px 560px at 88% 14%, rgba(246,196,83,.18), transparent 58%),
        radial-gradient(900px 520px at 55% 110%, rgba(124,58,237,.12), transparent 55%),
        linear-gradient(180deg, var(--jg-bg), var(--jg-bg2));
      overflow-x:hidden;
    }

    /* “Alma” sin discoteca: aurora cálida + grano suave */
    body::before{
      content:"";
      position: fixed;
      inset:-25%;
      pointer-events:none;
      z-index:-1;
      background:
        radial-gradient(520px 260px at 20% 20%, rgba(246,196,83,.18), transparent 62%),
        radial-gradient(560px 280px at 78% 18%, rgba(124,58,237,.18), transparent 62%),
        radial-gradient(520px 280px at 55% 75%, rgba(168,85,247,.12), transparent 62%);
      filter: blur(10px);
      opacity: .95;
    }

    body::after{
      content:"";
      position: fixed;
      inset:0;
      pointer-events:none;
      z-index:-1;
      opacity:.07;
      background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='180' height='180'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='.8' numOctaves='3' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='180' height='180' filter='url(%23n)' opacity='.55'/%3E%3C/svg%3E");
    }

    .jg-nav{
      backdrop-filter: blur(14px);
      background: rgba(14,14,26,.62) !important;
      border-bottom: 1px solid var(--jg-border);
    }

    .jg-brand{
      width: 42px; height: 42px;
      border-radius: 14px;
      display:grid; place-items:center;
      font-weight: 900;
      background: linear-gradient(135deg, rgba(124,58,237,.95), rgba(246,196,83,.55));
      box-shadow: 0 16px 44px rgba(124,58,237,.18);
      letter-spacing:.5px;
    }

    .jg-muted{ color: var(--jg-muted); }

    .jg-panel{
      border-radius: calc(var(--jg-radius) + 6px);
      border: 1px solid var(--jg-border);
      background:
        radial-gradient(760px 240px at 18% 0%, rgba(124,58,237,.16), transparent 62%),
        radial-gradient(720px 240px at 92% 18%, rgba(246,196,83,.12), transparent 62%),
        linear-gradient(180deg, rgba(255,255,255,.07), rgba(255,255,255,.03));
      box-shadow: var(--jg-shadow);
      overflow:hidden;
    }

    .jg-pill{
      border: 1px solid var(--jg-border);
      background: rgba(255,255,255,.03);
      border-radius: 999px;
      padding: 8px 12px;
      display:inline-flex;
      gap: 10px;
      align-items:center;
      color: var(--jg-muted);
    }
    .jg-dot{
      width:10px;height:10px;border-radius:999px;
      background: var(--jg-sun);
      box-shadow: 0 0 0 6px rgba(246,196,83,.14), 0 0 18px rgba(246,196,83,.35);
    }

    .jg-btn{
      border-radius: 14px;
      font-weight: 800;
    }
    .jg-btn-primary{
      background: var(--jg-purple);
      border-color: rgba(255,255,255,.12);
      color:#0b0b12;
      box-shadow: 0 18px 44px rgba(124,58,237,.16);
    }
    .jg-btn-primary:hover{ background: #8b5cf6; color:#0b0b12; }

    .jg-btn-sun{
      background: var(--jg-sun);
      border-color: rgba(255,255,255,.12);
      color:#0b0b12;
      box-shadow: 0 18px 44px rgba(246,196,83,.14);
    }
    .jg-btn-sun:hover{ background: var(--jg-sun2); color:#0b0b12; }

    .jg-btn-outline{
      border: 1px solid rgba(255,255,255,.18);
      color: var(--jg-text);
      background: transparent;
      font-weight: 700;
    }
    .jg-btn-outline:hover{
      background: rgba(246,196,83,.10);
      border-color: rgba(246,196,83,.35);
      color: var(--jg-text);
    }

    .jg-section{
      padding: 40px 0;
    }

    .jg-section-title{
      font-weight: 900;
      letter-spacing: .2px;
      margin: 0;
      position: relative;
      display: inline-block;
    }
    .jg-section-title::after{
  background: linear-gradient(90deg,
    rgba(124,58,237,.9),
    rgba(255,210,74,.9),
    rgba(43,108,255,.75)
  );
}


    .jg-card{
      border: 1px solid var(--jg-border);
      background: linear-gradient(180deg, var(--jg-card2), var(--jg-card));
      border-radius: var(--jg-radius);
      transition: transform .18s ease, border-color .18s ease, box-shadow .18s ease;
      overflow:hidden;
    }
    .jg-card:hover{
      transform: translateY(-3px);
      border-color: rgba(246,196,83,.35);
      box-shadow: 0 18px 55px rgba(124,58,237,.12);
    }

    .jg-cover{
      height: 150px;
      background:
        radial-gradient(360px 200px at 20% 25%, rgba(124,58,237,.18), transparent 62%),
        radial-gradient(420px 220px at 85% 45%, rgba(246,196,83,.16), transparent 62%),
        linear-gradient(180deg, rgba(255,255,255,.10), rgba(255,255,255,0));
      border-bottom: 1px solid rgba(255,255,255,.10);
      position: relative;
    }
    .jg-cover::after{
      content:"";
      position:absolute;
      inset:0;
      background: radial-gradient(circle at 30% 20%, rgba(255,255,255,.10), transparent 55%);
      opacity:.5;
      pointer-events:none;
    }

    .badge-soft{
      background: rgba(124,58,237,.14);
      border: 1px solid rgba(124,58,237,.28);
      color: var(--jg-text);
      font-weight: 800;
    }
    .badge-sun{
      background: rgba(246,196,83,.14);
      border: 1px solid rgba(246,196,83,.30);
      color: var(--jg-text);
      font-weight: 800;
    }
    .badge-mint{
      background: rgba(52,211,153,.14);
      border: 1px solid rgba(52,211,153,.30);
      color: var(--jg-text);
      font-weight: 800;
    }

    /* Mega dropdown estilo EA (limpio) */
    .dropdown-menu.megamenu{
      width: min(780px, 92vw);
      border-radius: 18px;
      border: 1px solid var(--jg-border);
      background: rgba(14,14,26,.94);
      backdrop-filter: blur(16px);
      box-shadow: 0 22px 70px rgba(0,0,0,.55);
      padding: 14px;
    }
    .mm-title{
      font-weight: 900;
      color: rgba(255,255,255,.70);
      font-size: .82rem;
      text-transform: uppercase;
      letter-spacing: .7px;
      margin-bottom: 8px;
    }
    .mm-item{
      padding: 9px 10px;
      border-radius: 12px;
      display:flex;
      justify-content: space-between;
      align-items:center;
      gap: 10px;
      border: 1px solid transparent;
      color: var(--jg-text);
      text-decoration: none;
    }
    .mm-item:hover{
      background: rgba(246,196,83,.10);
      border-color: rgba(246,196,83,.20);
      color: var(--jg-text);
    }

    /* Footer */
    footer{
      margin-top: 46px;
      border-top: 1px solid var(--jg-border);
      background: rgba(14,14,26,.62);
      backdrop-filter: blur(14px);
    }
    .ft-title{
      font-weight: 900;
      letter-spacing: .2px;
      margin-bottom: 10px;
    }
    .jg-link-muted{ color: rgba(255,255,255,.75); text-decoration:none; }
    .jg-link-muted:hover{ color: var(--jg-text); }

    .form-select, .form-control{
      background: rgba(255,255,255,.05);
      border: 1px solid rgba(255,255,255,.14);
      color: var(--jg-text);
      border-radius: 14px;
    }
    .form-select:focus, .form-control:focus{
      border-color: rgba(246,196,83,.45);
      box-shadow: 0 0 0 .25rem rgba(246,196,83,.14);
    }

    .social-btn{
      width: 42px; height: 42px;
      display:inline-grid;
      place-items:center;
      border-radius: 14px;
      border: 1px solid rgba(255,255,255,.18);
      background: rgba(255,255,255,.04);
      color: var(--jg-text);
      transition: transform .18s ease, border-color .18s ease, background .18s ease;
    }
    .social-btn:hover{
      transform: translateY(-2px);
      background: rgba(246,196,83,.10);
      border-color: rgba(246,196,83,.35);
      color: var(--jg-text);
    }

    /* HERO VIDEO RECTANGLE */
.hero-video{
  position: relative;
  border-radius: calc(var(--jg-radius) + 10px);
  border: 1px solid rgba(255,255,255,.16);
  overflow: hidden;
  box-shadow: var(--jg-shadow);
  min-height: 380px; /* ajusta si quieres más/menos alto */
  background: rgba(255,255,255,.04);
}

/* El vídeo ocupa toda la caja */
.hero-video video{
  position: absolute;
  inset: 0;
  width: 100%;
  height: 100%;
  object-fit: cover;
  transform: scale(1.03); /* evita bordes raros */
  filter: saturate(1.05) contrast(1.05);
}

/* Overlay para legibilidad + tono marca */
.hero-video::before{
  content:"";
  position:absolute;
  inset:0;
  background:
    radial-gradient(900px 500px at 15% 20%, rgba(124,58,237,.28), transparent 60%),
    radial-gradient(900px 520px at 85% 30%, rgba(255,210,74,.18), transparent 62%),
    linear-gradient(180deg, rgba(8,8,14,.40), rgba(8,8,14,.78));
  z-index: 1;
}

/* Un toque de “calcomanía” súper sutil dentro del hero */
.hero-video::after{
  content:"";
  position:absolute;
  inset:-20%;
  background:
    radial-gradient(400px 220px at 30% 40%, rgba(255,255,255,.08), transparent 60%),
    repeating-linear-gradient(90deg, rgba(255,255,255,.035) 0, rgba(255,255,255,.035) 1px, transparent 1px, transparent 120px);
  opacity: .35;
  filter: blur(6px);
  z-index: 1;
  pointer-events:none;
}

/* Contenido por encima del vídeo */
.hero-video-content{
  position: relative;
  z-index: 2;
  padding: 34px;
}

@media (max-width: 992px){
  .hero-video{ min-height: 420px; }
  .hero-video-content{ padding: 26px; }
}

/* Accesibilidad: si el usuario prefiere menos movimiento, ocultamos vídeo */
@media (prefers-reduced-motion: reduce){
  .hero-video video{ display:none; }
}


  </style>
</head>

<body>
  <div class="jg-site-bg" aria-hidden="true"></div>

  <!-- NAVBAR -->
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

      <div class="collapse navbar-collapse" id="navJediga">
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

  <!-- HERO (corto, con alma y calidez) -->
  <header style="padding:110px 0 26px;">
  <div class="container">

    <div class="hero-video">
      <!-- VIDEO: pon tu archivo en /public/videos/hero.mp4 -->
      <video autoplay muted loop playsinline preload="metadata">
        <source src="{{ asset('videos/hero.mp4') }}" type="video/mp4">
        <!-- opcional: mejor compatibilidad/performance -->
        <!-- <source src="{{ asset('videos/hero.webm') }}" type="video/webm"> -->
      </video>

      <div class="hero-video-content">
        <div class="jg-pill mb-3">
          <span class="jg-dot"></span>
          <span>Bienvenido a Jediga</span>
        </div>

        <h1 class="fw-bold display-6 mb-2">
          Juegos, comunidad y presencia profesional — con un toque cercano.
        </h1>

        <p class="jg-muted mb-4" style="max-width: 58ch;">
          Explora por secciones: próximos lanzamientos, lo más popular y juegos gratuitos.
        </p>

        <div class="d-flex flex-wrap gap-2">
          <a class="btn jg-btn jg-btn-primary" href="{{ url('/catalogo') }}">Ver catálogo</a>
          <a class="btn jg-btn jg-btn-sun" href="{{ url('/catalogo?price=free') }}">
            <i class="bi bi-gift me-1"></i> Gratis
          </a>
          <a class="btn jg-btn jg-btn-outline" href="{{ url('/noticias') }}">Noticias</a>
        </div>

        <!-- Mini info/CTA abajo, opcional y sutil -->
        <div class="mt-4 small jg-muted">
          Consejo: usa un clip corto (10–15s) y sin texto en el vídeo para que no “pelee” con el contenido.
        </div>
      </div>
    </div>

  </div>
</header>


  <!-- DATA DEMO (si tu controller aún no pasa datos) -->
  @php
    $upcoming = $upcoming ?? [
      ['title'=>'Astra Runner','desc'=>'Acción / Roguelite','tag'=>'PS','badge'=>'Próximamente','slug'=>'astra-runner'],
      ['title'=>'Neon Rift: Season 1','desc'=>'Competitivo 4v4','tag'=>'PC','badge'=>'Próximamente','slug'=>'neon-rift-season-1'],
      ['title'=>'Skyforge Lite','desc'=>'Aventura ligera','tag'=>'Switch','badge'=>'Próximamente','slug'=>'skyforge-lite'],
    ];

    $popular = $popular ?? [
      ['title'=>'Neon Rift','desc'=>'Shooter táctico','tag'=>'PC','badge'=>'Popular','slug'=>'neon-rift'],
      ['title'=>'Pulse Arena','desc'=>'Arena rápido','tag'=>'PC','badge'=>'Popular','slug'=>'pulse-arena'],
      ['title'=>'Echoes DLC','desc'=>'Contenido extra','tag'=>'PC','badge'=>'Popular','slug'=>'echoes-dlc'],
    ];

    $free = $free ?? [
      ['title'=>'Zero Byte','desc'=>'Indie corto','tag'=>'Xbox','badge'=>'Gratis','slug'=>'zero-byte'],
      ['title'=>'Starter Pack','desc'=>'Pack de bienvenida','tag'=>'PC','badge'=>'Gratis','slug'=>'starter-pack'],
      ['title'=>'Trial Access','desc'=>'Acceso limitado','tag'=>'PS','badge'=>'Gratis','slug'=>'trial-access'],
    ];
  @endphp

  <!-- SECCIÓN: PRÓXIMAMENTE -->
  <section class="jg-section" id="proximamente">
    <div class="container">
      <div class="d-flex justify-content-between align-items-end flex-wrap gap-2 mb-3">
        <div>
          <h2 class="h4 jg-section-title">Próximamente</h2>
          <div class="jg-muted mt-2">Lo que viene en camino.</div>
        </div>
        <a class="jg-link-muted" href="{{ url('/catalogo?status=upcoming') }}">Ver todos <i class="bi bi-arrow-right ms-1"></i></a>
      </div>

      <div class="row g-3">
        @foreach($upcoming as $g)
          <div class="col-12 col-md-6 col-lg-4">
            <div class="jg-card h-100">
              <div class="jg-cover"></div>
              <div class="p-3">
                <div class="d-flex justify-content-between align-items-center mb-2">
                  <span class="badge badge-soft">{{ $g['tag'] }}</span>
                  <span class="badge badge-soft">Soon</span>
                </div>
                <div class="fw-bold">{{ $g['title'] }}</div>
                <div class="small jg-muted mb-3">{{ $g['desc'] }}</div>
                <a class="btn jg-btn jg-btn-outline btn-sm" href="{{ url('/juego/'.$g['slug']) }}">Ver ficha</a>
              </div>
            </div>
          </div>
        @endforeach
      </div>
    </div>
  </section>

  <!-- SECCIÓN: MÁS POPULARES -->
  <section class="jg-section" id="populares">
    <div class="container">
      <div class="d-flex justify-content-between align-items-end flex-wrap gap-2 mb-3">
        <div>
          <h2 class="h4 jg-section-title">Más populares</h2>
          <div class="jg-muted mt-2">Lo que más está jugando la gente.</div>
        </div>
        <a class="jg-link-muted" href="{{ url('/catalogo?sort=popular') }}">Ver todos <i class="bi bi-arrow-right ms-1"></i></a>
      </div>

      <div class="row g-3">
        @foreach($popular as $g)
          <div class="col-12 col-md-6 col-lg-4">
            <div class="jg-card h-100">
              <div class="jg-cover"></div>
              <div class="p-3">
                <div class="d-flex justify-content-between align-items-center mb-2">
                  <span class="badge badge-soft">{{ $g['tag'] }}</span>
                  <span class="badge badge-sun"><i class="bi bi-fire me-1"></i>Top</span>
                </div>
                <div class="fw-bold">{{ $g['title'] }}</div>
                <div class="small jg-muted mb-3">{{ $g['desc'] }}</div>
                <a class="btn jg-btn jg-btn-primary btn-sm" href="{{ url('/juego/'.$g['slug']) }}">Ver ficha</a>
              </div>
            </div>
          </div>
        @endforeach
      </div>
    </div>
  </section>

  <!-- SECCIÓN: GRATIS -->
  <section class="jg-section" id="gratis">
    <div class="container">
      <div class="d-flex justify-content-between align-items-end flex-wrap gap-2 mb-3">
        <div>
          <h2 class="h4 jg-section-title">Gratis</h2>
          <div class="jg-muted mt-2">Para entrar sin pensarlo demasiado.</div>
        </div>
        <a class="jg-link-muted" href="{{ url('/catalogo?price=free') }}">Ver todos <i class="bi bi-arrow-right ms-1"></i></a>
      </div>

      <div class="row g-3">
        @foreach($free as $g)
          <div class="col-12 col-md-6 col-lg-4">
            <div class="jg-card h-100">
              <div class="jg-cover"></div>
              <div class="p-3">
                <div class="d-flex justify-content-between align-items-center mb-2">
                  <span class="badge badge-soft">{{ $g['tag'] }}</span>
                  <span class="badge badge-mint"><i class="bi bi-gift me-1"></i>Gratis</span>
                </div>
                <div class="fw-bold">{{ $g['title'] }}</div>
                <div class="small jg-muted mb-3">{{ $g['desc'] }}</div>
                <a class="btn jg-btn jg-btn-sun btn-sm" href="{{ url('/juego/'.$g['slug']) }}">Ver ficha</a>
              </div>
            </div>
          </div>
        @endforeach
      </div>
    </div>
  </section>

  <!-- FOOTER (con títulos y mejor uso del espacio) -->
  <footer class="py-5">
    <div class="container">
      <div class="row g-4">
        <!-- Marca -->
        <div class="col-md-4">
          <div class="d-flex align-items-center gap-3 mb-2">
            <span class="jg-brand">JG</span>
            <div class="lh-sm">
              <div class="fw-bold">Jediga</div>
              <div class="small jg-muted">© 2026 • Canarias</div>
            </div>
          </div>
          <div class="small jg-muted">
            Un espacio para juegos, noticias y soporte, con una presencia profesional y cercana.
          </div>
        </div>

        <!-- Región / precios -->
        <div class="col-md-4">
          <div class="ft-title">Precios regionales</div>
          <div class="small jg-muted mb-2">Selecciona tu región para mostrar precios locales.</div>
          <select class="form-select" aria-label="Precios regionales">
            <option selected>España (EUR)</option>
            <option>Reino Unido (GBP)</option>
            <option>EE. UU. (USD)</option>
            <option>LatAm (USD)</option>
          </select>

          <div class="mt-3 d-flex flex-wrap gap-3 small">
            <a class="jg-link-muted" href="{{ url('/aviso-legal') }}">Aviso legal</a>
            <a class="jg-link-muted" href="{{ url('/privacidad') }}">Privacidad</a>
            <a class="jg-link-muted" href="{{ url('/terminos') }}">Términos</a>
            <a class="jg-link-muted" href="{{ url('/cookies') }}">Cookies</a>
          </div>
        </div>

        <!-- Redes sociales -->
        <div class="col-md-4">
          <div class="ft-title">Redes sociales</div>
          <div class="small jg-muted mb-3">Síguenos para lanzamientos, novedades y comunidad.</div>
          <div class="d-flex gap-2">
            <a class="social-btn" href="#" aria-label="Instagram"><i class="bi bi-instagram"></i></a>
            <a class="social-btn" href="#" aria-label="YouTube"><i class="bi bi-youtube"></i></a>
            <a class="social-btn" href="#" aria-label="Twitch"><i class="bi bi-twitch"></i></a>
            <a class="social-btn" href="#" aria-label="Discord"><i class="bi bi-discord"></i></a>
          </div>

          <div class="mt-3 small jg-muted">
            ¿Necesitas ayuda? Visita <a class="jg-link-muted" href="{{ url('/soporte') }}">Soporte</a> o <a class="jg-link-muted" href="{{ url('/faq') }}">FAQ</a>.
          </div>
        </div>
      </div>
    </div>
  </footer>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
