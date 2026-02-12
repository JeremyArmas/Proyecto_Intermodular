
//LOGICAS FRONTEND

document.addEventListener('DOMContentLoaded', () => {
  iniciarPreloader();
  mostrarIconoContraseña();
});

//Función que recarga el preloader con la animación
function iniciarPreloader() {

  //Recoge el elemento preloader
  const preloader = document.getElementById('preloader');
  if (!preloader) return;

  //Bloquea el scroll mientras se ve el loader
  document.documentElement.style.overflow = 'hidden';

  //Cuando la página termine de cargar, arrancamos la salida
  window.addEventListener('load', () => {
    setTimeout(() => {
      preloader.classList.add('is-leaving');
    }, 700);
  });

  //Cuando termine la animación del loader, se quita
  preloader.addEventListener('animationend', (e) => {
    
    //Ignora animaciones de hijos (spans)
    if (e.target !== preloader){
      return;
    } 

    //Asegura que es la animación del loader
    if (e.animationName !== 'jgLoaderLeave'){
      return;
    } 

    //Borra el preloader una vez terminado
    preloader.remove();
    
    //Vuelve a permitir el scroll
    document.documentElement.style.overflow = '';
  });
}

//Botón de mostrar contraseña
function mostrarIconoContraseña() {
  const input = document.getElementById('loginPassword');
  const btn = document.getElementById('togglePassword');
  const icon = document.getElementById('togglePasswordIcon');

  //Muestra inicialmente el icono del ojo tachado
  actualizarIcono();

  //Acción del botón al hacer click
  btn.addEventListener('click', () => {
    input.type = (input.type === 'password') ? 'text' : 'password';
    actualizarIcono();
  });

  //Función que actualiza el icono 
  function actualizarIcono() {
    const oculto = input.type === 'password';

    icon.classList.toggle('bi-eye-slash', oculto);
    icon.classList.toggle('bi-eye', !oculto);

    btn.setAttribute('aria-label', oculto ? 'Mostrar contraseña' : 'Ocultar contraseña');
  }
}


// LOGICAS BACKEND

// Función para realizar el inicio de sesión
async function login(form, contenedorErrores) {

  //Recoge el token en el head
  const token = document.head.querySelector('meta[name="csrf-token"]')?.content;

  try {
    
    //Hace una petición POST
    const res = await fetch(form.action, {
      method: 'POST',
      headers: { 'X-CSRF-TOKEN': token, 'Accept': 'application/json' },
      body: new FormData(form),
    });

    //Si hay un error 419 recarga automaticamente
    if (res.status === 419) {
      window.location.reload();
      return;
    }

    //Intenta capturar la respuesta como JSON
    const data = await res.json().catch(() => ({}));

    //Si el login está OK redirige a la página
    if (res.ok && data.success) {
      window.location.href = data.redirect || '/';
      return;
    }

    //Mensaje en caso de error
    let msg = 'Error al iniciar sesión';

    //Si hay errores concretos, sobreescribe el mensaje de error inicial
    if (data && data.message) {
      msg = data.message;
    } else if (data && data.errors && data.errors.email && data.errors.email.length) {
      msg = data.errors.email[0];
    } else if (data && data.errors && data.errors.password && data.errors.password.length) {
      msg = data.errors.password[0];
    }

    //Recoge el "contenedorErrores" de app.blade para usarlo como lugar donde mostrar los errores
    if (contenedorErrores) {
      contenedorErrores.textContent = msg;
      contenedorErrores.classList.remove('d-none');
    } else {
      alert(msg);
    }

  } catch {

    //Muestra el error en caso de fallar la red
    const msg = 'Error de red. Inténtalo de nuevo.';
    if (contenedorErrores) {
      contenedorErrores.textContent = msg;
      contenedorErrores.classList.remove('d-none');
    } else {
      alert(msg);
    }
  }
}

//Espera al DOM y llama a la función de login cuando se accede a él
document.addEventListener('DOMContentLoaded', () => {
  const form = document.querySelector('#loginModal form');
  const contenedorErrores = document.getElementById('contenedorErrores');
  if (!form) return;

  form.addEventListener('submit', (e) => {
    e.preventDefault();
    login(form, contenedorErrores);
  });
});


// Función para cerrar sesión
async function logout() {
  
  //Recoge el token del head
  const token = document.head.querySelector('meta[name="csrf-token"]')?.content;

  try {

    //Hace una peticion POST al logout
    const res = await fetch('/logout', {
      method: 'POST',
      headers: { 'X-CSRF-TOKEN': token, 'Accept': 'application/json' },
    });

    //Lee la información que le ha llegado en JSON
    const data = await res.json().catch(() => ({}));

    //Si hay errores los canta en un mensaje
    if (!res.ok || !data.success) {
      throw new Error(data.message || 'No se pudo cerrar sesión');
    }

    //Si va todo OK redirige al usuario
    window.location.href = data.redirect || '/';
  } catch (err) {

    //En caso de haber errores de red lo muestra
    alert(err.message || 'Error de red. Inténtalo de nuevo.');
  }
}
