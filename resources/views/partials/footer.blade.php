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

        <!-- Enlaces legales -->
        <div class="col-md-4">
        <div class="ft-title">Apartado legal</div>
        <div class="mt-3 d-flex flex-wrap gap-3 small">
          <a class="jg-link-muted" href="{{ url('/aviso-legal') }}">Aviso legal</a>
          <a class="jg-link-muted" href="{{ url('/terminos') }}">Términos y condiciones</a>
          <a class="jg-link-muted" href="{{ url('/cookies') }}">Política de cookies</a>
        </div>
      </div>

      <!-- Redes sociales -->
      <div class="col-md-4">
        <div class="ft-title">Redes sociales</div>
        <div class="small jg-muted mb-3">Síguenos para lanzamientos, novedades y comunidad.</div>
        <div class="d-flex gap-2">
          <a class="social-btn" href="https://www.instagram.com/jedigasa/" target="_blank"
            title="Siguenos en Instagram :D" rel="noreferrer noopener" aria-label="Instagram"><i
              class="bi bi-instagram"></i></a>
          <a class="social-btn" href="https://www.youtube.com/@JedigaSA" target="_blank" title="Siguenos en Youtube :D"
            rel="noreferrer noopener" aria-label="YouTube"><i class="bi bi-youtube"></i></a>
          <a class="social-btn" href="https://x.com/JedigaSA" target="_blank" title="Siguenos en Twitter (X) :D"
            rel="noreferrer noopener" aria-label="x (Twitter)"><i class="bi bi-twitter-x"></i></a>
          <a class="social-btn" href="https://discord.gg/dEMNmmPQ5T" target="_blank" title="Siguenos en Discord :D"
            rel="noreferrer noopener" aria-label="Discord"><i class="bi bi-discord"></i></a>
        </div>

        <!-- Aunque con los símbolos en las redes sociales ya deja claro de que trata , por si acaso voy a dejar un título por temas de accesibilidad. 
         También el rel="noreferrer noopener" es por temas de seguridad (para que no puedan acceder a nuestra web desde las redes sociales). Para evitar el tabnabbing.-->

        <!-- Enlaces de soporte -->
        <div class="mt-3 small jg-muted">
          ¿Necesitas ayuda? Visita <a class="jg-link-muted" href="{{ url('/soporte') }}">Soporte</a> o <a
            class="jg-link-muted" href="{{ url('/faq') }}">FAQ</a>.
        </div>
      </div>
    </div>
  </div>
</footer>