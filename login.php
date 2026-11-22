<<<<<<< HEAD
<?php
session_start();
require 'baza.php';

if (!empty($_SESSION['uzytkownik'])) {
    header('Location: admin.php');
    exit;
}

$blad = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $login = is_string($_POST['login'] ?? null) ? trim($_POST['login']) : '';
    $haslo = is_string($_POST['haslo'] ?? null) ? $_POST['haslo'] : '';

    $z = $pdo->prepare('SELECT login, haslo_hash FROM uzytkownicy WHERE login = :login');
    $z->execute(['login' => $login]);
    $u = $z->fetch();

    if ($u && password_verify($haslo, $u['haslo_hash'])) {
        session_regenerate_id(true);
        $_SESSION['uzytkownik'] = $u['login'];
        header('Location: admin.php');
        exit;
    }
    $blad = 'Błędny login lub hasło.';
}
?>
<!doctype html>
<html lang="pl">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Szprycha — logowanie</title>
  <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH"
        crossorigin="anonymous">
</head>
=======
>>>>>>> 9ef2aad82992c54a58d286a3ec65212bf7d518ff
<body class="bg-light">
  <main class="container" style="max-width: 420px">
    <div class="card mt-5 shadow-sm">
      <div class="card-body">
        <h1 class="h4 mb-4">Panel Szprychy</h1>
        <?php if ($blad !== ''): ?>
          <div class="alert alert-danger"><?= htmlspecialchars($blad) ?></div>
        <?php endif; ?>
        <form method="post">
          <div class="mb-3">
            <label for="login" class="form-label">Login</label>
            <input id="login" name="login" class="form-control" required autocomplete="username">
          </div>
          <div class="mb-3">
            <label for="haslo" class="form-label">Hasło</label>
            <input id="haslo" name="haslo" type="password" class="form-control" required autocomplete="current-password">
          </div>
          <button class="btn btn-primary w-100">Zaloguj</button>
        </form>
        <p class="mt-3 mb-0"><a href="index.php">← Cennik</a></p>
      </div>
    </div>
  </main>
</body>
