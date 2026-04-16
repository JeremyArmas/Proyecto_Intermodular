//LOGICAS FRONTEND / BACKEND

/* Inicialización al cargar el DOM:
   - inicia el preloader
   - habilita el icono para mostrar/ocultar contraseña */
document.addEventListener('DOMContentLoaded', () => {
  iniciarPreloader();
  mostrarIconoContraseña();
  configurarUI();
});

/* PRELOADER
   - muestra una pantalla de carga y la anima fuera cuando la página carga
   - bloquea el scroll mientras está visible */
function iniciarPreloader() {
  const preloader = document.getElementById('preloader');
  if (!preloader) return;

  // Bloquea el scroll mientras se muestra el loader
  document.documentElement.style.overflow = 'hidden';

  const lanzarSalida = () => {
    setTimeout(() => {
      preloader.classList.add('is-leaving');
    }, 200);
  };

  // Si la página ya cargó, lanzamos la salida directamente
  if (document.readyState === 'complete') {
    lanzarSalida();
  } else {
    window.addEventListener('load', lanzarSalida);
  }

  // Eliminación del preloader: usamos varios eventos y un timeout de seguridad
  const removerLoader = () => {
    if (preloader.parentNode) {
      preloader.remove();
      document.documentElement.style.overflow = '';
    }
  };

  // Escucha ambos eventos de fin para mayor compatibilidad
  preloader.addEventListener('animationend', (e) => {
    if (e.target === preloader && e.animationName === 'jgLoaderLeave') removerLoader();
  });
  preloader.addEventListener('transitionend', (e) => {
    if (e.target === preloader) removerLoader();
  });

  // Timeout de seguridad extra (por si fallan los eventos de CSS)
  setTimeout(removerLoader, 2500);
}

/* Mostrar/ocultar contraseña en el input del login:
   - botón que alterna el tipo y actualiza el icono y aria-label */
function mostrarIconoContraseña() {
  const input = document.getElementById('loginPassword');
  const btn = document.getElementById('togglePassword');
  const icon = document.getElementById('togglePasswordIcon');

  if (!input || !btn || !icon){
    return;
  } 

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


// LÓGICAS BACKEND 

// Login: realiza la petición de login vía fetch, maneja errores, muestra mensajes y controla la visibilidad del captcha según la respuesta del backend
async function login(form, contenedorErrores) {
  
  // CSRF token desde meta
  const token = document.head.querySelector('meta[name="csrf-token"]')?.content;

  try {
    const res = await fetch(form.action, {
      method: 'POST',
      headers: { 'X-CSRF-TOKEN': token, 'Accept': 'application/json' },
      credentials: 'include',
      body: new FormData(form),
    });

    // Si token CSRF expiró -> recarga la página
    if (res.status === 419) {
      window.location.reload();
      return;
    }

    // Intenta parsear JSON (fallback a objeto vacío si falla)
    const data = await res.json().catch(() => ({}));

    // Elemento del bloque de captcha para mostrar/ocultar según la respuesta
    const captchaBlock = document.getElementById('captchaBlock');

    // Muestra el bloque de captcha
    const mostrarCaptcha = () => {
      
      // Si no existe el bloque de captcha, no hace nada
      if (!captchaBlock){
        return; 
      } 
      
      // Oculta el bloque de captcha para evitar mostrarlo innecesariamente
      captchaBlock.classList.remove('d-none');
      document.getElementById('reloadCaptcha')?.click(); // recarga la imagen
    };

    // Oculta el bloque de captcha
    const ocultarCaptcha = () => {
      
      // Si no existe el bloque de captcha, no hace nada
      if (!captchaBlock){
        return;
      } 
      
      // Oculta el bloque de captcha para evitar mostrarlo innecesariamente 
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

// Logout: realiza la petición de logout vía fetch, maneja errores y redirige según respuesta
async function logout() {
  
  // CSRF token desde meta
  const token = document.head.querySelector('meta[name="csrf-token"]')?.content;

  try {
    const res = await fetch('/logout', {
      method: 'POST',
      headers: { 'X-CSRF-TOKEN': token, 'Accept': 'application/json' },
    });

    // Intenta parsear JSON 
    const data = await res.json().catch(() => ({}));

    // Si el logout fue exitoso, redirige a la URL recibida; si no, muestra un error
    if (!res.ok || !data.success) {
      throw new Error(data.message || 'No se pudo cerrar sesión');
    }

    // Redirige tras logout exitoso
    window.location.href = data.redirect || '/';
  } catch (err) {
    alert(err.message || 'Error de red. Inténtalo de nuevo.');
  }
}

/* Añade listener al formulario de login para usar la función login() */
document.addEventListener('DOMContentLoaded', () => {
  const form = document.querySelector('#loginModal form');
  const contenedorErrores = document.getElementById('contenedorErrores');
  
  if (!form){
    return;
  }

  form.addEventListener('submit', (e) => {
    e.preventDefault();
    login(form, contenedorErrores);
  });
});


/* NAV COLLAPSE (móvil/tablet)
   - controla la apertura/ cierre del panel de navegación en dispositivos pequeños
   - cierra al hacer click fuera, al pulsar ESC o al cambiar a escritorio */
document.addEventListener('DOMContentLoaded', () => {
  const toggles = document.querySelectorAll('[data-jg-nav-toggle], #jgNavToggle');
  const esTabletMovil = () => window.innerWidth < 992;

  // Para cada botón que controla un panel de navegación, asigna los eventos necesarios para abrir/cerrar el panel y gestionar la accesibilidad
  toggles.forEach((btn) => {
    
    // Obtiene el ID del panel asociado al botón a través del atributo aria-controls, o usa un ID por defecto si no se especifica
    const id = btn.getAttribute('aria-controls') || 'navJediga';
    
    // Busca el panel en el DOM usando el ID obtenido
    const panel = document.getElementById(id);
    
    // Si no se encuentra el panel asociado al botón, no hacer nada (evita errores en consola)
    if (!panel){
      return;
    } 

    // Funciones para abrir y cerrar el panel, que también actualizan el atributo aria-expanded para mejorar la accesibilidad
    const open = () => {
      panel.classList.add('is-open');
      btn.setAttribute('aria-expanded', 'true');
    };

    const close = () => {
      panel.classList.remove('is-open');
      btn.setAttribute('aria-expanded', 'false');
    };

    // Al hacer click en el botón, alterna el panel solo si estamos en móvil/tablet; en escritorio no interferir con el comportamiento normal (por ejemplo, si es un dropdown)
    btn.addEventListener('click', (e) => {

      // En escritorio no interfiere con comportamiento normal
      if (!esTabletMovil()){
        return;
      } 

      e.preventDefault();
      
      // Alterna el panel: si ya está abierto, lo cierra; si está cerrado, lo abre
      panel.classList.contains('is-open') ? close() : open();
    });

    // Si se pulsa un enlace dentro del panel, lo cierra (salvo toggles de dropdown)
    panel.querySelectorAll('a').forEach(a => {
      
      // Al hacer click en un enlace dentro del panel, si estamos en móvil/tablet y el enlace no es un toggle de dropdown, cierra el panel para mejorar la navegación
      a.addEventListener('click', () => {
        
        // En escritorio no interfiere con comportamiento normal de los enlaces
        if (!esTabletMovil()){
          return; 
        } 

        // Si el enlace es un toggle de dropdown (tiene clase o atributo específico), no cierra el panel para permitir la interacción con el dropdown 
        const isDropdownToggle = a.classList.contains('dropdown-toggle') || a.getAttribute('data-bs-toggle') === 'dropdown';

        // Si no es un toggle de dropdown, cierra el panel para mejorar la navegación en móvil/tablet, evitando que el menú quede abierto
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
   - inicializa el slider si existe y sincroniza la reproducción de trailers de YouTube
   - autoplay con pausa en interacción del usuario */
document.addEventListener('DOMContentLoaded', () => {
  const swiperJG = document.querySelector('.jg-hero-swiper');
  
  if (!swiperJG || typeof window.Swiper === 'undefined'){
    return;
  } 

  const contenedor = swiperJG.closest('.jg-hero-container') || swiperJG.parentElement;

  const swiper = new Swiper(swiperJG, {
    loop: true,
    speed: 1300, 
    effect: 'slide',
    spaceBetween: 40, 
    autoplay: {
      delay: 19500, 
      disableOnInteraction: false,
      pauseOnMouseEnter: true,
    },
    pagination: {
      el: swiperJG.querySelector('.swiper-pagination'),
      clickable: true,
    },
    navigation: {
      nextEl: contenedor.querySelector('.swiper-button-next'),
      prevEl: contenedor.querySelector('.swiper-button-prev'),
    },
  });

  // Control de iframes de YouTube:
  // - Al salir de un slide: vaciar su iframe para detener el video
  // - Al entrar en un slide: recargar el iframe (desde data-src) para reiniciar el video
  function getSlideIframe(slideEl) {
    return slideEl ? slideEl.querySelector('iframe.jg-hero-vid') : null;
  }

  swiper.on('slideChangeTransitionStart', function () {
    const prevSlide = swiper.slides[swiper.previousIndex];
    const prevIframe = getSlideIframe(prevSlide);
    if (prevIframe && prevIframe.dataset.src) {
      prevIframe.src = '';
    }
  });

  swiper.on('slideChangeTransitionEnd', function () {
    const activeSlide = swiper.slides[swiper.activeIndex];
    const activeIframe = getSlideIframe(activeSlide);
    if (activeIframe && activeIframe.dataset.src) {
      activeIframe.src = activeIframe.dataset.src;
    }
  });

  // Carga inicial para el primer slide
  const initialIframe = getSlideIframe(swiper.slides[swiper.activeIndex]);
  if (initialIframe && initialIframe.dataset.src) {
    initialIframe.src = initialIframe.dataset.src;
  }
});


/* LÓGICAS NAVEGACIÓN Y UI */
function configurarUI() {
  // Manejo de Logout: asegura que el formulario se envíe por POST
  const btnLogout = document.querySelector('form[action$="/logout"] button[type="submit"]');
  if (btnLogout) {
    btnLogout.addEventListener('click', (e) => {
      e.preventDefault();
      console.log('Iniciando cierre de sesión...');
      // alert('Cerrando sesión...'); // Uncomment if needed for manual verification
      btnLogout.closest('form').submit();
    });
  }
}

/* RELOAD CAPTCHA
   - solicita al backend un nuevo captcha y lo inserta en el DOM
   - maneja estado disabled */
document.addEventListener('DOMContentLoaded', () => {
  const btn = document.getElementById('reloadCaptcha');
  const frame = document.querySelector('.jg-captcha-frame');
  const input = document.querySelector('input[name="captcha"]');

  // Si no se encuentran el btn o el frame, devuelve
  if (!btn || !frame){
    return;
  } 

  // Función para recargar el captcha: hace una petición al backend y actualiza el contenido del frame
  const recargarCaptcha = async () => {
    
    // Deshabilita el botón mientras se carga para evitar múltiples clicks
    btn.disabled = true;
    
    // Agrega un timestamp a la URL para evitar caché
    try {
      const res = await fetch(`/reload-captcha?_=${Date.now()}`, {
        headers: { 'Accept': 'application/json' },
      });

      const data = await res.json();
      
      // Si el backend devuelve un nuevo captcha, actualiza el contenido del frame y limpia el input
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

  // Agrega el listener al botón de recarga
  btn.addEventListener('click', recargarCaptcha);

  // Recarga al abrir el modal
  document.getElementById('loginModal')?.addEventListener('shown.bs.modal', recargarCaptcha);
});

async function register(form, contenedorErrores) {
  const token = document.head.querySelector('meta[name="csrf-token"]')?.content;
  try {
    const res = await fetch(form.action, {
      method: 'POST',
      headers: { 'X-CSRF-TOKEN': token, 'Accept': 'application/json' },
      body: new FormData(form),
    });
    if (res.status === 419) { window.location.reload(); return; }
    const data = await res.json().catch(() => ({}));
    if (res.ok && data.success) { window.location.href = data.redirect || '/'; return; }
    let msg = 'Error al crear la cuenta';
    if (data && data.message) msg = data.message;
    else if (data && data.errors) msg = Object.values(data.errors)[0][0];
    if (contenedorErrores) {
      contenedorErrores.textContent = msg;
      contenedorErrores.classList.remove('d-none');
    } else alert(msg);
    document.getElementById('reloadCaptchaRegistro')?.click();
  } catch {
    const msg = 'Error de red. Inténtalo de nuevo.';
    if (contenedorErrores) {
      contenedorErrores.textContent = msg;
      contenedorErrores.classList.remove('d-none');
    } else alert(msg);
  }
}

document.addEventListener('DOMContentLoaded', () => {
  const form = document.querySelector('#registerModal form');
  const contenedorErrores = document.getElementById('contenedorErroresRegistro');
  if (!form) return;
  form.addEventListener('submit', (e) => {
    e.preventDefault();
    register(form, contenedorErrores);
  });
});

document.addEventListener('DOMContentLoaded', () => {
  const btn = document.getElementById('reloadCaptchaRegistro');
  const frame = document.querySelector('#captchaBlockRegistro .jg-captcha-frame');
  const input = document.querySelector('#registerModal input[name="captcha"]');
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
    } catch (e) { console.error('No se pudo recargar captcha registro', e); }
    finally { btn.disabled = false; }
  };
  btn.addEventListener('click', recargarCaptcha);
  document.getElementById('registerModal')?.addEventListener('shown.bs.modal', recargarCaptcha);
});