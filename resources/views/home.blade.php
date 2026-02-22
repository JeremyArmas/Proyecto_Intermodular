@extends('layouts.app')

@section('title', 'Jediga')

@section('content')

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

    $heroSlides = [
      [
        'pill' => 'Próximamente',
        'desc2' => 'Descubre lo que viene en camino y guarda tus favoritos.',
        'badgeClass' => 'badge-soft',
        'badgeText' => 'Soon',
        'game' => $upcoming[0],
        'mediaType' => 'video',
        'mediaSrc' => 'videos/hero.mp4',
        'primary' => ['text'=>'Ver ficha', 'href'=> url('/juego/'.$upcoming[0]['slug']), 'class'=>'jg-btn-sun'],
        'secondary' => ['text'=>'Ver todos', 'href'=> url('/catalogo?status=upcoming'), 'class'=>'jg-btn-primary'],
        'tertiary' => ['text'=>'Catálogo', 'href'=> url('/catalogo'), 'class'=>'jg-btn-outline'],
      ],
      [
        'pill' => 'Más populares',
        'desc2' => 'Lo más jugado ahora mismo. Entra a la ficha o mira el top completo.',
        'badgeClass' => 'badge-sun',
        'badgeText' => 'Top',
        'game' => $popular[0],
        'mediaType' => 'video',
        'mediaSrc' => 'videos/hero3.mp4',
        'primary' => ['text'=>'Ver ficha', 'href'=> url('/juego/'.$popular[0]['slug']), 'class'=>'jg-btn-sun'],
        'secondary' => ['text'=>'Ver todos', 'href'=> url('/catalogo?sort=popular'), 'class'=>'jg-btn-primary'],
        'tertiary' => ['text'=>'Noticias', 'href'=> url('/noticias'), 'class'=>'jg-btn-outline'],
      ],
      [
        'pill' => 'Gratis',
        'desc2' => 'Entra sin pagar: juegos y packs para empezar rápido.',
        'badgeClass' => 'badge-mint',
        'badgeText' => 'Free',
        'game' => $free[0],
        'mediaType' => 'video',
        'mediaSrc' => 'videos/hero2.mp4',
        'primary' => ['text'=>'Ver ficha', 'href'=> url('/juego/'.$free[0]['slug']), 'class'=>'jg-btn-sun'],
        'secondary' => ['text'=>'Ver todos', 'href'=> url('/catalogo?price=free'), 'class'=>'jg-btn-primary'],
        'tertiary' => ['text'=>'Catálogo', 'href'=> url('/catalogo'), 'class'=>'jg-btn-outline'],
      ],
    ];
  @endphp

  <!-- HERO -->
  <header class="jg-hero">
    <div class="container">

      <div class="jg-hero-container">
        <div class="swiper jg-hero-swiper">
        <div class="swiper-wrapper">

          @foreach($heroSlides as $s)
            <div class="hero-video swiper-slide">

              @if($s['mediaType'] === 'video')
                <video class="jg-hero-media" muted loop playsinline preload="metadata">
                  <source src="{{ asset($s['mediaSrc']) }}" type="video/mp4">
                </video>
              @else
                <img class="jg-hero-media" src="{{ asset($s['mediaSrc']) }}" alt="">
              @endif

              <div class="hero-video-content">
                <div class="jg-pill mb-3">
                  <span class="jg-dot"></span>
                  <span>{{ $s['pill'] }}</span>
                </div>

                <div class="d-flex flex-wrap gap-2 mb-3">
                  <span class="badge badge-soft">{{ $s['game']['tag'] }}</span>
                  <span class="badge {{ $s['badgeClass'] }}">{{ $s['badgeText'] }}</span>
                </div>

                <h1 class="jg-hero-title display-6 mb-2">
                  {{ $s['game']['title'] }}
                </h1>

                <p class="jg-hero-desc mb-4" style="max-width: 62ch;">
                  {{ $s['game']['desc'] }}
                  <span class="jg-hero-desc2 d-block mt-1">{{ $s['desc2'] }}</span>
                </p>

                <div class="d-flex flex-wrap gap-2">
                  <a class="btn jg-btn {{ $s['primary']['class'] }}" href="{{ $s['primary']['href'] }}">
                    {{ $s['primary']['text'] }}
                  </a>
                  <a class="btn jg-btn {{ $s['secondary']['class'] }}" href="{{ $s['secondary']['href'] }}">
                    {{ $s['secondary']['text'] }}
                  </a>
                  <a class="btn jg-btn {{ $s['tertiary']['class'] }}" href="{{ $s['tertiary']['href'] }}">
                    {{ $s['tertiary']['text'] }}
                  </a>
                </div>
              </div>

            </div>
          @endforeach

        </div>

        <!-- Botones de paginación -->
        <div class="swiper-pagination"></div>
        
      </div>
      
      <!-- Flechas de paginación -->
        <div class="swiper-button-prev" aria-label="Anterior"></div>
        <div class="swiper-button-next" aria-label="Siguiente"></div>
      </div>
      
    </div>
  </header>

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
