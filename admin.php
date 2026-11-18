<?php
require 'auth.php'; // 1. strażnik
require 'baza.php';

// 2. obsługa formularzy (tylko POST zmienia dane)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $akcja = $_POST['akcja'] ?? '';

    if ($akcja === 'dodaj') {
        $nazwa = is_string($_POST['nazwa'] ?? null) ? trim($_POST['nazwa']) : '';
        $cena = (int) ($_POST['cena'] ?? 0);

        if ($nazwa !== '' && $cena > 0) {
            $z = $pdo->prepare('INSERT INTO uslugi (nazwa, czas, cena) VALUES (:nazwa, :czas, :cena)');
            $z->execute(['nazwa' => $nazwa, 'czas' => '—', 'cena' => $cena]);
            header('Location: admin.php?ok=dodano');
            exit;
        }
        $blad = 'Uzupełnij nazwę i cenę.';
    }
}

// 3. lista usług do tabeli
$uslugi = $pdo->query('SELECT id, nazwa, cena FROM uslugi ORDER BY nazwa')->fetchAll();
?>
<!doctype html>
<html lang="pl">
<head>
  <meta charset="utf-8">
  <title>Panel Szprychy</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <main>
    <h1>Witaj, <?= htmlspecialchars($_SESSION['uzytkownik']) ?></h1>
    <p><a href="wyloguj.php">Wyloguj</a> | <a href="index.php">Cennik</a></p>

    <?php if (($_GET['ok'] ?? '') === 'dodano'): ?>
      <p>Usługa dodana.</p>
    <?php endif; ?>
    <?php if (isset($blad)): ?>
      <p><?= htmlspecialchars($blad) ?></p>
    <?php endif; ?>

    <h2>Usługi</h2>
    <table>
      <thead>
        <tr><th>Nazwa</th><th>Cena</th></tr>
      </thead>
      <tbody>
        <?php foreach ($uslugi as $usluga): ?>
          <tr>
            <td><?= htmlspecialchars($usluga['nazwa'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?></td>
            <td><?= (int) $usluga['cena'] ?> zł</td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>

    <h2>Dodaj usługę</h2>
    <form method="post">
      <input type="hidden" name="akcja" value="dodaj">
      <label for="nazwa">Nazwa</label>
      <input id="nazwa" name="nazwa" required maxlength="100">
      <label for="cena">Cena (zł)</label>
      <input id="cena" name="cena" type="number" min="1" required>
      <button type="submit">Dodaj</button>
    </form>
  </main>
</body>
</html>
