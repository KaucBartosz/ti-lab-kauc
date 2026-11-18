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
