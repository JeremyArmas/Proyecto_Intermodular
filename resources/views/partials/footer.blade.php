<footer class="py-5">
    <div class="container">
      <div class="row g-4">
        <!-- Marca -->
        <div class="col-md-4">
          <div class="d-flex align-items-center gap-3 mb-2">
            <img class="jg-brand" src="{{ asset('images/logo_jediga_provisional.png') }}" alt="Logo Jediga">
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