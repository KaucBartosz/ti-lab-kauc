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
  <title>Szprycha — logowanie</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <main>
    <h1>Panel Szprychy</h1>
    <?php if ($blad !== ''): ?>
      <p><?= htmlspecialchars($blad) ?></p>
    <?php endif; ?>
    <form method="post">
      <label for="login">Login</label>
      <input id="login" name="login" required autocomplete="username">
      <label for="haslo">Hasło</label>
      <input id="haslo" name="haslo" type="password" required autocomplete="current-password">
      <button type="submit">Zaloguj</button>
    </form>
    <p><a href="index.php">← Cennik</a></p>
  </main>
</body>
</html>
