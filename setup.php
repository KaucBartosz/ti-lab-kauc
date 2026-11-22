<?php
require 'baza.php';

$pdo->exec('CREATE TABLE IF NOT EXISTS uslugi (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    nazwa TEXT NOT NULL,
    czas TEXT,
    cena INTEGER NOT NULL,
    popularnosc INTEGER DEFAULT 0
)');
$pdo->exec('CREATE TABLE IF NOT EXISTS uzytkownicy (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    login TEXT NOT NULL UNIQUE,
    haslo_hash TEXT NOT NULL
)');

// migracja dla istniejącej bazy
try { $pdo->exec('ALTER TABLE uslugi ADD COLUMN popularnosc INTEGER DEFAULT 0'); } catch (PDOException $e) { }

if ((int) $pdo->query('SELECT COUNT(*) FROM uslugi')->fetchColumn() === 0) {
    $z = $pdo->prepare('INSERT INTO uslugi (nazwa, czas, cena, popularnosc) VALUES (:nazwa, :czas, :cena, :popularnosc)');
    $startowe = [
        ['nazwa' => 'Regulacja biegów i hamulców', 'czas' => 'ok. 40 min', 'cena' => 60, 'popularnosc' => 30],
        ['nazwa' => 'Wymiana dętki lub opony', 'czas' => 'ok. 20 min', 'cena' => 20, 'popularnosc' => 45],
        ['nazwa' => 'Wymiana łańcucha', 'czas' => 'ok. 30 min', 'cena' => 40, 'popularnosc' => 20],
        ['nazwa' => 'Serwis kompletny', 'czas' => '2 dni', 'cena' => 180, 'popularnosc' => 10],
    ];
    foreach ($startowe as $u) {
        $z->execute($u);
    }
}

if ((int) $pdo->query('SELECT COUNT(*) FROM uzytkownicy')->fetchColumn() === 0) {
    $z = $pdo->prepare('INSERT INTO uzytkownicy (login, haslo_hash) VALUES (:login, :haslo_hash)');
    $z->execute([
        'login' => 'admin',
        'haslo_hash' => password_hash('Szprycha!2026', PASSWORD_DEFAULT),
    ]);
}

echo "Baza gotowa.\n";
