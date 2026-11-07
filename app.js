const przycisk = document.querySelector('.menu-toggle');
const menu     = document.querySelector('.menu');

przycisk.addEventListener('click', function () {
  const otwarte = menu.classList.toggle('menu--open');
  przycisk.setAttribute('aria-expanded', otwarte);
});
