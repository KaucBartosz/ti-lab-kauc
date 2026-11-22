<?php
$firma = 'Szprycha';
$rok = date('Y');

require 'baza.php';
$cennik = $pdo->query('SELECT nazwa, czas, cena FROM uslugi ORDER BY cena')->fetchAll();


// Powitanie z parametru ?imie= — surowej wartości nigdy nie wypisujemy.
$imieParam = $_GET['imie'] ?? 'Kliencie';
$imie = is_string($imieParam) ? trim($imieParam) : '';
if ($imie === '') {
  $imie = 'Kliencie';
}
if (mb_strlen($imie, 'UTF-8') > 40) {
  $imie = mb_substr($imie, 0, 40, 'UTF-8');
}

// Najwyższa cena — skala wykresu SVG.
$maxCena = 0;
foreach ($cennik as $usluga) {
  $wartosc = (int) preg_replace('/\D+/', '', $usluga['cena']);
  if ($wartosc > $maxCena) {
    $maxCena = $wartosc;
  }
}
if ($maxCena === 0) {
  $maxCena = 1;
}
?>

<!DOCTYPE html>
<html lang="pl">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Szprycha – serwis rowerowy</title>
  <link rel="stylesheet" href="style.css">
  <script src="app.js" defer></script>
</head>
<body>

  <header class="site-header">
    <div class="site-header__brand">
      <h1>
        <a class="logo" href="#">
          <svg viewBox="0 0 120 64" width="72" height="38" role="img" aria-labelledby="logo-tytul">
            <title id="logo-tytul">Szprycha</title>
            <g fill="none" stroke-width="5" stroke-linecap="round" stroke-linejoin="round">
              <circle cx="28" cy="42" r="18" stroke="#ffbf3f"/>
              <circle cx="92" cy="42" r="18" stroke="#ffbf3f"/>
              <polyline points="28,42 48,14 80,14 92,42" stroke="#ffffff"/>
              <polyline points="48,14 60,42 80,14" stroke="#ffffff"/>
              <circle cx="60" cy="42" r="4" fill="#ffbf3f" stroke="none"/>
            </g>
          </svg>
          <span>Szprycha</span>
        </a>
      </h1>
      <p>Mała pracownia rowerowa</p>
      <p class="greeting">Cześć, <?= htmlspecialchars($imie, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?>!</p>
    </div>

    <button class="menu-toggle" aria-expanded="false">Menu</button>

    <nav class="menu">
      <ul class="menu__list">
        <li><a href="#uslugi">Oferta</a></li>
        <li><a href="#cennik">Cennik</a></li>
        <li><a href="#kontakt">Kontakt</a></li>
      </ul>
    </nav>
  </header>

  <main>
    <section id="uslugi">
      <h2>Nasza oferta</h2>

      <div class="cards">
        <article class="card">
          <img src="img/rower.svg" alt="Ikona roweru" class="card__icon" width="64" height="64">
          <h3>Regulacja</h3>
          <p>Dostrojenie biegów i hamulców, tak żeby wszystko chodziło płynnie.</p>
          <p class="card__price">od 60 zł</p>
        </article>

        <article class="card">
          <img src="img/kolo.svg" alt="Ikona koła rowerowego" class="card__icon" width="64" height="64">
          <h3>Wymiana części</h3>
          <p>Dętki, opony, łańcuchy i klocki hamulcowe – nowe części na szybki czas.</p>
          <p class="card__price">od 20 zł</p>
        </article>

        <article class="card card--highlight">
          <img src="img/narzedzia.svg" alt="Ikona narzędzi serwisowych" class="card__icon" width="64" height="64">
          <h3>Serwis kompletny <span class="card__badge">Najczęściej wybierane</span></h3>
          <p>Pełny przegląd: smarowanie łańcucha, kontrola śrubek i przygotowanie do sezonu.</p>
          <p class="card__price">od 180 zł</p>
        </article>
      </div>
    </section>

    <section id="cennik">
      <h2>Cennik</h2>
      <p id="status" role="status"></p>
      <label>Max cena (zł): <input id="max" type="number" min="0"></label>

      <table class="pricing__table">
        <thead>
          <tr>
            <th scope="col">Usługa</th>
            <th scope="col">Czas</th>
            <th scope="col" class="num">Cena</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($cennik as $usluga): ?>
            <tr>
              <td><?= htmlspecialchars($usluga['nazwa']) ?></td>
              <td><?= htmlspecialchars($usluga['czas']) ?></td>
              <td class="num"><?= $usluga['cena'] ?> zł</td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </section>

    <section id="ceny-w-liczbach">
      <h2>Ceny w liczbach</h2>

      <svg id="wykres" class="chart" viewBox="0 0 480 220" role="img" aria-labelledby="wykres-tytul">
        <title id="wykres-tytul">Porównanie cen usług w złotych</title>
      </svg>

    </section>
  </main>

  <footer id="kontakt">
    <h2>Kontakt</h2>
    <p>&copy; <?= $rok ?> <?= $firma ?></p>
    <address>
      <p>ul. Rowerowa 12, 00-001 Warszawa</p>
      <p><a href="tel:+48600100200">+48 600 100 200</a></p>
      <p><a href="mailto:kontakt@szprycha.pl">kontakt@szprycha.pl</a></p>
      <p>Otwarte: pon–pt 9:00–18:00, sob 9:00–14:00</p>
    </address>
  </footer>

</body>
</html>
