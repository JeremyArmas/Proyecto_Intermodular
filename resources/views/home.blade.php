@extends('layouts.app')

@section('title', 'Jediga')

@section('content')

  <!-- Toast de éxito al resetear contraseña: aparece cuando el controlador redirige aquí con este flash -->
  @if(session('password_reset_success'))
    <div id="toastResetOk" class="toast-container position-fixed top-0 start-50 translate-middle-x p-3" style="z-index:9999; margin-top:80px;">
      <div class="toast show align-items-center text-white border-0" style="background: #198754; border-radius:.75rem; box-shadow:0 8px 30px rgba(0,0,0,.4);">
        <div class="d-flex">
          <div class="toast-body d-flex align-items-center gap-2">
            <i class="bi bi-check-circle-fill fs-5"></i>
            {{ session('password_reset_success') }}
          </div>
          <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Cerrar"></button>
        </div>
      </div>
    </div>
    <script>
      // Auto-ocultar después de 5 segundos
      setTimeout(() => {
        const el = document.getElementById('toastResetOk');
        if (el) el.style.display = 'none';
      }, 5000);
    </script>
  @endif

  <!-- HERO PRINCIPAL --> 
  <header class="jg-hero">
    <div class="container">

      <!-- Slider principal del hero, con videos o imágenes de fondo y contenido encima. Usamos Swiper.js para esto. -->
      <div class="jg-hero-container">
        <div class="swiper jg-hero-swiper">
        <div class="swiper-wrapper">

          <!-- Slide 1 -->  
          @foreach($heroSlides as $s)
            <div class="hero-video swiper-slide">

              <!-- Contenedor para el efecto Fundido (Fade) de Imagen a Vídeo -->
              <div class="hero-media-wrapper">
                <!-- El vídeo (reproduciéndose por detrás) -->
                <video class="jg-hero-vid" muted loop playsinline preload="metadata">
                  <source src="{{ asset($s['mediaSrc']) }}" type="video/mp4">
                </video>
                
                <!-- La imagen de cubierta (encima, que luego desaparece) -->
                @if(!empty($s['game']['cover_image']))
                  <img class="jg-hero-img" src="{{ asset('storage/' . $s['game']['cover_image']) }}" alt="{{ $s['game']['title'] }}">
                @else
                  <div class="jg-hero-img" style="background: var(--jg-bg2);"></div>
                @endif
              </div>


              <!-- Contenido encima del video/imagen -->
              <div class="hero-video-content">
                <div class="jg-pill mb-3">
                  <span class="jg-dot"></span>
                  <span>{{ $s['pill'] }}</span>
                </div>

                <!-- Badges (etiquetas) del juego, como el género o el estado (nuevo, exclusivo, etc.) -->
                <div class="d-flex flex-wrap gap-2 mb-3">
                  <span class="badge badge-soft">{{ $s['game']['tag'] }}</span>
                  <span class="badge {{ $s['badgeClass'] }}">{{ $s['badgeText'] }}</span>
                </div>

                <!-- Título del juego destacado -->
                <h1 class="jg-hero-title display-6 mb-2">
                  {{ $s['game']['title'] }}
                </h1>

                <!-- Descripción del juego destacado, con un texto secundario más pequeño debajo -->
                <p class="jg-hero-desc mb-4" style="max-width: 62ch;">
                  {{ $s['game']['desc'] }}
                  <span class="jg-hero-desc2 d-block mt-1">{{ $s['desc2'] }}</span>
                </p>

                <!-- Botones de acción para el juego destacado, como "Ver ficha", "Comprar ahora", etc. -->
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

        <!-- Paginación del slider, con puntos o números para indicar en qué slide estamos -->
        <div class="swiper-pagination"></div>
        
      </div>
      
      <!-- Botones de navegación (anterior/siguiente) -->
        <div class="swiper-button-prev" aria-label="Anterior"></div>
        <div class="swiper-button-next" aria-label="Siguiente"></div>
      </div>
      
    </div>
  </header>

  <!-- SECCIONES DESTACADAS DEL HOME -->

  <!-- PRÓXIMAMENTE -->
  <section class="jg-section" id="proximamente">
    <div class="container">
      <div class="d-flex justify-content-between align-items-end flex-wrap gap-2 mb-3">
        <div>

          <!-- Título de la sección, con un texto secundario debajo para explicar de qué va esta sección. -->
          <h2 class="h4 jg-section-title">Próximamente</h2>
          <div class="jg-muted mt-2">Lo que viene en camino.</div>
        </div>

        <!-- Enlace a ver todos los juegos de esta sección, con un icono de flecha a la derecha. -->
        <a class="jg-link-muted" href="{{ url('/catalogo?status=upcoming') }}">Ver todos <i class="bi bi-arrow-right ms-1"></i></a>
      </div>

      <!-- Lista de juegos destacados en esta sección -->
      <div class="row g-3">
        @foreach($upcoming as $g)
          <div class="col-12 col-md-6 col-lg-4">
            <div class="jg-card h-100">
              <div class="jg-cover">
                @if(!empty($g['cover_image']))
                  <img src="{{ asset('storage/' . $g['cover_image']) }}" alt="{{ $g['title'] }}" style="width:100%; height:100%; object-fit:cover;">
                @endif
              </div>
              <div class="p-3">

                <!-- Etiquetas del juego, como el género o el estado (nuevo, exclusivo, etc.) -->
                <div class="d-flex justify-content-between align-items-center mb-2">
                  <span class="badge badge-soft">{{ $g['tag'] }}</span>
                  <span class="badge badge-soft">Soon</span>
                </div>

                <!-- Título y descripción -->
                <div class="fw-bold">{{ $g['title'] }}</div>
                <div class="small jg-muted mb-3">{{ $g['desc'] }}</div>
                
                <!-- Botón para ver la ficha -->
                <a class="btn jg-btn jg-btn-sun btn-sm" href="{{ url('/juego/'.$g['slug']) }}">Ver ficha</a>
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

          <!-- Título de la sección, con un texto secundario debajo para explicar de qué va esta sección. -->
          <h2 class="h4 jg-section-title">Más populares</h2>
          <div class="jg-muted mt-2">Lo que más está jugando la gente.</div>
        </div>

        <!-- Enlace a ver todos los juegos de esta sección, con un icono de flecha a la derecha. -->
        <a class="jg-link-muted" href="{{ url('/catalogo?sort=popular') }}">Ver todos <i class="bi bi-arrow-right ms-1"></i></a>
      </div>

      <!-- Lista de juegos destacados en esta sección -->
      <div class="row g-3">
        @foreach($popular as $g)
          <div class="col-12 col-md-6 col-lg-4">
            <div class="jg-card h-100">
              <div class="jg-cover">
                @if(!empty($g['cover_image']))
                  <img src="{{ asset('storage/' . $g['cover_image']) }}" alt="{{ $g['title'] }}" style="width:100%; height:100%; object-fit:cover;">
                @endif
              </div>
              <div class="p-3">
                
                <!-- Etiquetas del juego, como el género o el estado (nuevo, exclusivo, etc.) -->
                <div class="d-flex justify-content-between align-items-center mb-2">
                  <span class="badge badge-soft">{{ $g['tag'] }}</span>
                  <span class="badge badge-sun"><i class="bi bi-fire me-1"></i>Top</span>
                </div>

                <!-- Título y descripción -->
                <div class="fw-bold">{{ $g['title'] }}</div>
                <div class="small jg-muted mb-3">{{ $g['desc'] }}</div>
                
                <!-- Botón para ver la ficha -->
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
        
          <!-- Título de la sección, con un texto secundario debajo para explicar de qué va esta sección. -->
          <h2 class="h4 jg-section-title">Gratis</h2>
          <div class="jg-muted mt-2">Para entrar sin pensarlo demasiado.</div>
        </div>

        <!-- Enlace a ver todos los juegos de esta sección, con un icono de flecha a la derecha. -->
        <a class="jg-link-muted" href="{{ url('/catalogo?price=free') }}">Ver todos <i class="bi bi-arrow-right ms-1"></i></a>
      </div>

      <!-- Lista de juegos destacados en esta sección -->
      <div class="row g-3">
        @foreach($free as $g)
          <div class="col-12 col-md-6 col-lg-4">
            <div class="jg-card h-100">
              <div class="jg-cover">
                @if(!empty($g['cover_image']))
                  <img src="{{ asset('storage/' . $g['cover_image']) }}" alt="{{ $g['title'] }}" style="width:100%; height:100%; object-fit:cover;">
                @endif
              </div>
              <div class="p-3">
                
                <!-- Etiquetas del juego, como el género o el estado (nuevo, exclusivo, etc.) -->
                <div class="d-flex justify-content-between align-items-center mb-2">
                  <span class="badge badge-soft">{{ $g['tag'] }}</span>
                  <span class="badge badge-mint"><i class="bi bi-gift me-1"></i>Gratis</span>
                </div>

                <!-- Título y descripción -->
                <div class="fw-bold">{{ $g['title'] }}</div>
                <div class="small jg-muted mb-3">{{ $g['desc'] }}</div>
                
                <!-- Botón para ver la ficha -->
                <a class="btn jg-btn jg-btn-sun btn-sm" href="{{ url('/juego/'.$g['slug']) }}">Ver ficha</a>
              </div>
            </div>
          </div>
        @endforeach
      </div>
    </div>
  </section>
@endsection