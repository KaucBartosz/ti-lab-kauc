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

    if ($akcja === 'usun') {
        $z = $pdo->prepare('DELETE FROM uslugi WHERE id = :id');
        $z->execute(['id' => (int) ($_POST['id'] ?? 0)]);
        header('Location: admin.php?ok=usunieto');
        exit;
    }
}

// 3. lista usług do tabeli
$uslugi = $pdo->query('SELECT id, nazwa, cena FROM uslugi ORDER BY nazwa')->fetchAll();
?>
<!doctype html>
<html lang="pl">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Panel Szprychy</title>
  <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH"
        crossorigin="anonymous">
</head>
<body>
  <nav class="navbar navbar-dark bg-dark">
    <div class="container">
      <a class="navbar-brand" href="index.php">Szprycha · panel</a>
      <span class="navbar-text text-white">
        Witaj, <?= htmlspecialchars($_SESSION['uzytkownik']) ?>
        <a class="btn btn-sm btn-outline-light ms-2" href="wyloguj.php">Wyloguj</a>
      </span>
    </div>
  </nav>

  <main class="container py-4">
    <?php if (($_GET['ok'] ?? '') === 'dodano'): ?>
      <div class="alert alert-success">Usługa dodana.</div>
    <?php endif; ?>
    <?php if (($_GET['ok'] ?? '') === 'usunieto'): ?>
      <div class="alert alert-success">Usługa usunięta.</div>
    <?php endif; ?>
    <?php if (isset($blad)): ?>
      <div class="alert alert-danger"><?= htmlspecialchars($blad) ?></div>
    <?php endif; ?>

    <div class="row">
      <div class="col-lg-8">
        <h2 class="h4">Usługi</h2>
        <table class="table table-striped align-middle">
          <thead>
            <tr><th>Nazwa</th><th>Cena</th><th></th></tr>
          </thead>
          <tbody>
            <?php foreach ($uslugi as $usluga): ?>
              <tr>
                <td><?= htmlspecialchars($usluga['nazwa'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?></td>
                <td><?= (int) $usluga['cena'] ?> zł</td>
                <td>
                  <form method="post" class="d-inline">
                    <input type="hidden" name="akcja" value="usun">
                    <input type="hidden" name="id" value="<?= (int) $usluga['id'] ?>">
                    <button class="btn btn-sm btn-outline-danger">Usuń</button>
                  </form>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>

      <div class="col-lg-4">
        <h2 class="h4">Dodaj usługę</h2>
        <form method="post">
          <input type="hidden" name="akcja" value="dodaj">
          <div class="mb-3">
            <label for="nazwa" class="form-label">Nazwa</label>
            <input id="nazwa" name="nazwa" class="form-control" required maxlength="100">
          </div>
          <div class="mb-3">
            <label for="cena" class="form-label">Cena (zł)</label>
            <input id="cena" name="cena" type="number" min="1" class="form-control" required>
          </div>
          <button class="btn btn-primary">Dodaj</button>
        </form>
      </div>
    </div>
  </main>
</body>
</html>
