<?php
require 'auth.php';
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
    <p><a href="wyloguj.php">Wyloguj</a></p>
    <p><a href="index.php">Cennik</a></p>
  </main>
</body>
</html>
