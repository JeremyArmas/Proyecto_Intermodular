
//Recargar de la página
(() => {
  const preloader = document.getElementById('preloader');
  if (!preloader) return;

  document.documentElement.style.overflow = 'hidden';

  const start = () => {
    setTimeout(() => {
      preloader.classList.add('is-leaving');

      const onAnimEnd = (e) => {
        // 1) Ignora animationend de hijos (spans)
        if (e.target !== preloader) return;

        // 2) Asegura que es la animación del loader, no otra
        if (e.animationName !== 'jgLoaderLeave') return;

        preloader.removeEventListener('animationend', onAnimEnd);
        preloader.remove();
        document.documentElement.style.overflow = '';
      };

      preloader.addEventListener('animationend', onAnimEnd);
    }, 700);
  };

  if (document.readyState === 'complete') start();
  else window.addEventListener('load', start);
})();

//Botón de mostrar contraseña
document.addEventListener('DOMContentLoaded', () => {
  const input = document.getElementById('loginPassword');
  const btn = document.getElementById('togglePassword');
  const icon = document.getElementById('togglePasswordIcon');

  if (!input || !btn || !icon) return;

  const render = () => {
    const isHidden = input.type === 'password'; // oculto = password
    icon.classList.toggle('bi-eye', isHidden);
    icon.classList.toggle('bi-eye-slash', !isHidden);
    btn.setAttribute('aria-label', isHidden ? 'Mostrar contraseña' : 'Ocultar contraseña');
  };

  render();

  btn.addEventListener('click', () => {
    input.type = (input.type === 'password') ? 'text' : 'password';
    render();
  });
});
