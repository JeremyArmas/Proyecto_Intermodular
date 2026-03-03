//LOGICAS FRONTEND / BACKEND

/* Inicialización al cargar el DOM:
   - inicia el preloader
   - habilita el icono para mostrar/ocultar contraseña */
document.addEventListener('DOMContentLoaded', () => {
  iniciarPreloader();
  mostrarIconoContraseña();
});

/* PRELOADER
   - muestra una pantalla de carga y la anima fuera cuando la página carga
   - bloquea el scroll mientras está visible */
function iniciarPreloader() {
  const preloader = document.getElementById('preloader');
  if (!preloader) return;

  // Bloquea el scroll mientras se muestra el loader
  document.documentElement.style.overflow = 'hidden';

  // Cuando la página termina de cargar, lanza la animación de salida
  window.addEventListener('load', () => {
    setTimeout(() => {
      preloader.classList.add('is-leaving');
    }, 100);
  });

  // Cuando termina la animación del propio preloader, lo elimina del DOM
  preloader.addEventListener('animationend', (e) => {
    // Ignora animaciones de elementos hijos
    if (e.target !== preloader) return;
    // Asegura que la animación sea la esperada
    if (e.animationName !== 'jgLoaderLeave') return;

    preloader.remove();
    // Restaura el scroll
    document.documentElement.style.overflow = '';
  });
}

/* Mostrar/ocultar contraseña en el input del login:
   - botón que alterna el tipo y actualiza el icono y aria-label */
function mostrarIconoContraseña() {
  const input = document.getElementById('loginPassword');
  const btn = document.getElementById('togglePassword');
  const icon = document.getElementById('togglePasswordIcon');

  if (!input || !btn || !icon) return;

  // Actualiza el icono inicial según el tipo
  actualizarIcono();

  // Alterna el tipo al hacer click
  btn.addEventListener('click', () => {
    input.type = (input.type === 'password') ? 'text' : 'password';
    actualizarIcono();
  });

  // Actualiza clases del icono y aria-label accesible
  function actualizarIcono() {
    const oculto = input.type === 'password';
    icon.classList.toggle('bi-eye-slash', oculto);
    icon.classList.toggle('bi-eye', !oculto);
    btn.setAttribute('aria-label', oculto ? 'Mostrar contraseña' : 'Ocultar contraseña');
  }
}


/* LÓGICAS BACKEND: LOGIN
   - realiza la petición de login vía fetch
   - gestiona errores, recarga de captcha y mensajes al usuario */
async function login(form, contenedorErrores) {
  // CSRF token desde meta
  const token = document.head.querySelector('meta[name="csrf-token"]')?.content;

  try {
    const res = await fetch(form.action, {
      method: 'POST',
      headers: { 'X-CSRF-TOKEN': token, 'Accept': 'application/json' },
      body: new FormData(form),
    });

    // Si token CSRF expiró -> recarga la página
    if (res.status === 419) {
      window.location.reload();
      return;
    }

    // Intenta parsear JSON (fallback a objeto vacío si falla)
    const data = await res.json().catch(() => ({}));

    const captchaBlock = document.getElementById('captchaBlock');

    const mostrarCaptcha = () => {
      if (!captchaBlock) return;
      captchaBlock.classList.remove('d-none');
      document.getElementById('reloadCaptcha')?.click(); // recarga la imagen
    };

    const ocultarCaptcha = () => {
      if (!captchaBlock) return;
      captchaBlock.classList.add('d-none');
    };

    // Si el login fue correcto redirige a la URL recibida
    if (res.ok && data.success) {
      window.location.href = data.redirect || '/';
      return;
    }

    // Mensaje por defecto en caso de error
    let msg = 'Error al iniciar sesión';

    // Prioriza mensajes concretos devueltos por el backend
    if (data && data.message) {
      msg = data.message;
    } else if (data && data.errors && data.errors.captcha && data.errors.captcha?.length) {
      msg = data.errors.captcha[0];
    } else if (data && data.errors && data.errors.email && data.errors.email.length) {
      msg = data.errors.email[0];
    } else if (data && data.errors && data.errors.password && data.errors.password.length) {
      msg = data.errors.password[0];
    }

    // Decide mostrar o esconder captcha según la respuesta
    if (data?.show_captcha || data?.errors?.captcha?.length || data?.attempts_remaining === 1) {
      mostrarCaptcha();
    } else {
      ocultarCaptcha();
    }

    // Si se proporcionó un contenedor para errores, mostrar ahí
    if (contenedorErrores) {
      contenedorErrores.textContent = msg;
      contenedorErrores.classList.remove('d-none');

      // Si queda 1 intento, usar estilo de advertencia más visible
      if (data?.attempts_remaining === 1) {
        contenedorErrores.classList.remove('alert-warning');
        contenedorErrores.classList.add('alert-danger');
      } else {
        contenedorErrores.classList.remove('alert-danger');
        contenedorErrores.classList.add('alert-warning');
      }
    } else {
      // Fallback: alert simple
      alert(msg);
    }

    // Tras mostrar el error, recarga el captcha para evitar repetición
    document.getElementById('reloadCaptcha')?.click();

    // Limpia el input del captcha si existe
    const inputCaptcha = form.querySelector('input[name="captcha"]');
    if (inputCaptcha) inputCaptcha.value = '';

  } catch {
    // Error de red genérico
    const msg = 'Error de red. Inténtalo de nuevo.';
    if (contenedorErrores) {
      contenedorErrores.textContent = msg;
      contenedorErrores.classList.remove('d-none');
    } else {
      alert(msg);
    }
  }
}

/* Añade listener al formulario de login para usar la función login() */
document.addEventListener('DOMContentLoaded', () => {
  const form = document.querySelector('#loginModal form');
  const contenedorErrores = document.getElementById('contenedorErrores');
  if (!form) return;

  form.addEventListener('submit', (e) => {
    e.preventDefault();
    login(form, contenedorErrores);
  });
});


/* LOGOUT
   - petición POST a /logout y redirección al recibir success */
async function logout() {
  const token = document.head.querySelector('meta[name="csrf-token"]')?.content;

  try {
    const res = await fetch('/logout', {
      method: 'POST',
      headers: { 'X-CSRF-TOKEN': token, 'Accept': 'application/json' },
    });

    const data = await res.json().catch(() => ({}));

    if (!res.ok || !data.success) {
      throw new Error(data.message || 'No se pudo cerrar sesión');
    }

    window.location.href = data.redirect || '/';
  } catch (err) {
    alert(err.message || 'Error de red. Inténtalo de nuevo.');
  }
}

/* NAV COLLAPSE (móvil/tablet)
   - controla la apertura/ cierre del panel de navegación en dispositivos pequeños
   - cierra al hacer click fuera, al pulsar ESC o al cambiar a escritorio */
document.addEventListener('DOMContentLoaded', () => {
  const toggles = document.querySelectorAll('[data-jg-nav-toggle], #jgNavToggle');
  const esTabletMovil = () => window.innerWidth < 992;

  toggles.forEach((btn) => {
    const id = btn.getAttribute('aria-controls') || 'navJediga';
    const panel = document.getElementById(id);
    if (!panel) return;

    const open = () => {
      panel.classList.add('is-open');
      btn.setAttribute('aria-expanded', 'true');
    };
    const close = () => {
      panel.classList.remove('is-open');
      btn.setAttribute('aria-expanded', 'false');
    };

    btn.addEventListener('click', (e) => {
      // En escritorio no interferir con comportamiento normal
      if (!esTabletMovil()) return;

      e.preventDefault();
      panel.classList.contains('is-open') ? close() : open();
    });

    // Si se pulsa un enlace dentro del panel, cerrarlo (salvo toggles de dropdown)
    panel.querySelectorAll('a').forEach(a => {
      a.addEventListener('click', () => {
        if (!esTabletMovil()) return;

        const isDropdownToggle =
          a.classList.contains('dropdown-toggle') ||
          a.getAttribute('data-bs-toggle') === 'dropdown';

        if (isDropdownToggle) return;
        close();
      });
    });

    // Click fuera del panel cierra (solo en móvil/tablet)
    document.addEventListener('click', (e) => {
      if (!esTabletMovil()) return;
      if (!panel.contains(e.target) && !btn.contains(e.target)) close();
    });

    // ESC para cerrar
    document.addEventListener('keydown', (e) => {
      if (!esTabletMovil()) return;
      if (e.key === 'Escape') close();
    });

    // Si se redimensiona a escritorio, forzar cierre
    window.addEventListener('resize', () => {
      if (!esTabletMovil()) close();
    });
  });
});


/* HERO SLIDER (Swiper)
   - inicializa el slider si existe y sincroniza la reproducción de vídeos
   - autoplay con pausa en interacción del usuario */
document.addEventListener('DOMContentLoaded', () => {
  const el = document.querySelector('.jg-hero-swiper');
  if (!el || typeof window.Swiper === 'undefined') return;

  const contenedor = el.closest('.jg-hero-container') || el.parentElement;

  const swiper = new Swiper(el, {
    loop: true,
    speed: 600,
    effect: 'fade',
    fadeEffect: { crossFade: true },
    autoplay: {
      delay: 5400,
      disableOnInteraction: false,
      pauseOnMouseEnter: true,
    },
    pagination: {
      el: el.querySelector('.swiper-pagination'),
      clickable: true,
    },
    navigation: {
      nextEl: contenedor.querySelector('.swiper-button-next'),
      prevEl: contenedor.querySelector('.swiper-button-prev'),
    },
  });

  // Sincroniza vídeos: pausa todos y reproduce solo el del slide activo
  const syncVideos = () => {
    el.querySelectorAll('video').forEach(v => {
      v.pause();
      v.currentTime = 0;
    });
    const activeVideo = el.querySelector('.swiper-slide-active video');
    if (activeVideo) {
      const p = activeVideo.play();
      if (p && typeof p.catch === 'function') p.catch(() => {});
    }
  };

  syncVideos();
  swiper.on('slideChangeTransitionStart', syncVideos);
});


/* RELOAD CAPTCHA
   - solicita al backend un nuevo captcha y lo inserta en el DOM
   - maneja estado disabled mientras carga */
document.addEventListener('DOMContentLoaded', () => {
  const btn = document.getElementById('reloadCaptcha');
  const frame = document.querySelector('.jg-captcha-frame');
  const input = document.querySelector('input[name="captcha"]');

  if (!btn || !frame) return;

  const recargarCaptcha = async () => {
    btn.disabled = true;
    try {
      const res = await fetch(`/reload-captcha?_=${Date.now()}`, {
        headers: { 'Accept': 'application/json' },
      });
      const data = await res.json();
      if (data?.captcha) {
        frame.innerHTML = data.captcha;
        if (input) input.value = '';
      }
    } catch (e) {
      console.error('No se pudo recargar captcha', e);
    } finally {
      btn.disabled = false;
    }
  };

  btn.addEventListener('click', recargarCaptcha);

  // Si se desea recargar al abrir el modal, se puede descomentar:
  // document.getElementById('loginModal')?.addEventListener('shown.bs.modal', recargarCaptcha);
});