@extends('layouts.app')

@section('title', 'Jediga')

@section('content')
  <!-- HERO -->
  <header class="jg-hero">
    <div class="container">
      <div class="hero-video">
        <video autoplay muted loop playsinline preload="metadata">
          <source src="{{ asset('videos/hero.mp4') }}" type="video/mp4">
        </video>

        <div class="hero-video-content">
          <div class="jg-pill mb-3">
            <span class="jg-dot"></span>
            <span>Bienvenido a Jediga</span>
          </div>

          <h1 class="jg-hero-title display-6 mb-2">
            Juegos, comunidad y presencia profesional — con un toque cercano
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

          <div class="mt-4 small jg-muted">
          </div>
        </div>
      </div>
    </div>
  </header>

  @php
    $upcoming = $upcoming ?? [
      ['title'=>'Astra Runner','desc'=>'Acción / Roguelite','tag'=>'PS','slug'=>'astra-runner'],
      ['title'=>'Neon Rift: Season 1','desc'=>'Competitivo 4v4','tag'=>'PC','slug'=>'neon-rift-season-1'],
      ['title'=>'Skyforge Lite','desc'=>'Aventura ligera','tag'=>'Switch','slug'=>'skyforge-lite'],
    ];

    $popular = $popular ?? [
      ['title'=>'Neon Rift','desc'=>'Shooter táctico','tag'=>'PC','slug'=>'neon-rift'],
      ['title'=>'Pulse Arena','desc'=>'Arena rápido','tag'=>'PC','slug'=>'pulse-arena'],
      ['title'=>'Echoes DLC','desc'=>'Contenido extra','tag'=>'PC','slug'=>'echoes-dlc'],
    ];

    $free = $free ?? [
      ['title'=>'Zero Byte','desc'=>'Indie corto','tag'=>'Xbox','slug'=>'zero-byte'],
      ['title'=>'Starter Pack','desc'=>'Pack de bienvenida','tag'=>'PC','slug'=>'starter-pack'],
      ['title'=>'Trial Access','desc'=>'Acceso limitado','tag'=>'PS','slug'=>'trial-access'],
    ];
  @endphp

  <!-- PRÓXIMAMENTE -->
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

  <!-- POPULARES -->
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

  <!-- GRATIS -->
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
@endsection
