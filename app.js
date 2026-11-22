const przycisk = document.querySelector('.menu-toggle');
const menu     = document.querySelector('.menu');

przycisk.addEventListener('click', function () {
  const otwarte = menu.classList.toggle('menu--open');
  przycisk.setAttribute('aria-expanded', otwarte);
});

const statusInfo = document.getElementById('status');
const tabelaBody = document.querySelector('.pricing__table tbody');

async function wczytajUslugi(max = '') {
  try {
    const adres = max === ''
      ? 'api.php'
      : 'api.php?max=' + encodeURIComponent(max);
    const odpowiedz = await fetch(adres);
    if (!odpowiedz.ok) throw new Error('HTTP ' + odpowiedz.status);
    const uslugi = await odpowiedz.json();
    pokazTabele(uslugi);
    rysujWykres(uslugi);
  } catch (blad) {
    if (statusInfo) statusInfo.textContent = 'Nie udało się wczytać cennika.';
  }
}

function pokazTabele(uslugi) {
  if (!tabelaBody) return;
  tabelaBody.innerHTML = '';
  uslugi.forEach(function (usluga) {
    const tr = document.createElement('tr');
    const tdNazwa = document.createElement('td');
    tdNazwa.textContent = usluga.nazwa; // textContent = ochrona przed XSS
    const tdCzas = document.createElement('td');
    tdCzas.textContent = usluga.czas ?? '';
    const tdCena = document.createElement('td');
    tdCena.className = 'num';
    tdCena.textContent = usluga.cena + ' zł';
    tr.append(tdNazwa, tdCzas, tdCena);
    tabelaBody.append(tr);
  });
}

wczytajUslugi();
