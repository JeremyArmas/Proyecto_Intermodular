<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>@yield('title', 'Jediga')</title>

  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Bootstrap Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

  @vite(['resources/css/app.css', 'resources/js/app.js'])


  @stack('styles')
</head>

<body>
  {{-- Capa de fondo --}}
  <div class="jg-site-bg" aria-hidden="true"></div>

  {{-- Navbar --}}
  @include('partials.navbar')

  <main>
    @yield('content')
  </main>

  {{-- Footer --}}
  @include('partials.footer')

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  @stack('scripts')
</body>
</html>
