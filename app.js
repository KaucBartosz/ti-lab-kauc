const przycisk = document.querySelector('.menu-toggle');
const menu     = document.querySelector('.menu');

przycisk?.addEventListener('click', function () {
  const otwarte = menu.classList.toggle('menu--open');
  przycisk.setAttribute('aria-expanded', otwarte);
});

const statusInfo = document.getElementById('status');
const tabelaBody = document.querySelector('.pricing__table tbody');
const poleMax = document.getElementById('max');
const wykres = document.getElementById('wykres');
const SVG_NS = 'http://www.w3.org/2000/svg';

async function wczytajUslugi(max = '') {
  try {
    const adres = max === '' ? 'api.php' : 'api.php?max=' + encodeURIComponent(max);
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
    tdNazwa.textContent = usluga.nazwa;
    const tdCzas = document.createElement('td');
    tdCzas.textContent = usluga.czas ?? '';
    const tdCena = document.createElement('td');
    tdCena.className = 'num';
    tdCena.textContent = usluga.cena + ' zł';
    tr.append(tdNazwa, tdCzas, tdCena);
    tabelaBody.append(tr);
  });
}

function rysujWykres(uslugi) {
  if (!wykres) return;
  while (wykres.firstChild) wykres.removeChild(wykres.firstChild);
  const title = document.createElementNS(SVG_NS, 'title');
  title.textContent = 'Porównanie cen usług';
  wykres.append(title);

  const wartosci = uslugi.map(u => u.popularnosc || u.cena);
  const najwieksza = Math.max(1, ...wartosci);

  uslugi.forEach(function (usluga, i) {
    const y = 15 + i * 50;
    const wartosc = usluga.popularnosc || usluga.cena;
    const dlugosc = wartosc / najwieksza * 260;

    const pasek = document.createElementNS(SVG_NS, 'rect');
    pasek.setAttribute('x', 180);
    pasek.setAttribute('y', y);
    pasek.setAttribute('width', Math.max(4, dlugosc));
    pasek.setAttribute('height', 28);
    pasek.setAttribute('rx', 4);
    pasek.setAttribute('class', wartosc === najwieksza ? 'chart__bar chart__bar--max' : 'chart__bar');
    wykres.append(pasek);

    const etykieta = document.createElementNS(SVG_NS, 'text');
    etykieta.setAttribute('x', 8);
    etykieta.setAttribute('y', y + 19);
    etykieta.setAttribute('class', 'chart__label');
    etykieta.textContent = usluga.nazwa;
    wykres.append(etykieta);

    const wart = document.createElementNS(SVG_NS, 'text');
    wart.setAttribute('x', 180 + Math.max(4, dlugosc) + 8);
    wart.setAttribute('y', y + 19);
    wart.setAttribute('class', 'chart__value');
    wart.textContent = wartosc;
    wykres.append(wart);
  });
}

let opoznienie;
if (poleMax) {
  poleMax.addEventListener('input', function () {
    clearTimeout(opoznienie);
    opoznienie = setTimeout(function () {
      wczytajUslugi(poleMax.value.trim());
    }, 300);
  });
}

wczytajUslugi();
