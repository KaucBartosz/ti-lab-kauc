<?php
require 'baza.php';

$pdo->exec('CREATE TABLE IF NOT EXISTS uslugi(
id INTEGER PRIMARY KEY AUTOINCREMENT,
nazwa TEXT NOT NULL,
czas TEXT,
cena INTEGER NOT NULL
)');
$pdo->exec('CREATE TABLE IF NOT EXISTS uzytkownicy(
id INTEGER PRIMARY KEY AUTOINCREMENT,
login TEXT NOT NULL UNIQUE,
haslo_hash TEXT NOT NULL
)');

if ($pdo->query('SELECT COUNT(*) FROM uslugi')->fetchColumn()==0{
    $z = $pdo->prepare('INSERT INTO uslugi (nazwa, czas, cena) VALUES (:nazwa, :czas, :cena)');
    $startowe = [
        ['nazwa' => 'Regulacja biegów i hamulców', 'czas' => 'ok. 40 min', 'cena'=>60],
        ['nazwa' => 'Wymiana dętki lub opony', 'czas' => 'ok. 20 min', 'cena'=> 20],
        ['nazwa' => 'Wymiana łancucha', 'czas' => 'ok. 30 min', 'cena'=>40],
        ['nazwa' => 'Serwis kompletny', 'czas' => '2 dni', 'cena'=>180],
    ];
foreach ($startowe as $u){
    $z->execute($u);
}
})


?>
