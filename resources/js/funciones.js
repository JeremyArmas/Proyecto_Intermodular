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
