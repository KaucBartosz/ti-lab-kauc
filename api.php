<?php
require 'baza.php';
header('Content-Type: application/json; charset=utf-8');

function odpowiedz(int $kod, array $dane): void {
    http_response_code($kod);
    echo json_encode($dane, JSON_UNESCAPED_UNICODE);
    exit;
}

$metoda = $_SERVER['REQUEST_METHOD'];

if ($metoda === 'GET') {
    $max = isset($_GET['max']) ? (int) $_GET['max'] : 0;
    if ($max > 0) {
        $z = $pdo->prepare('SELECT id, nazwa, czas, cena, popularnosc FROM uslugi WHERE cena <= :max ORDER BY cena');
        $z->execute(['max' => $max]);
        $uslugi = $z->fetchAll();
    } else {
        $uslugi = $pdo->query('SELECT id, nazwa, czas, cena, popularnosc FROM uslugi ORDER BY cena')->fetchAll();
    }
    odpowiedz(200, $uslugi);
}

odpowiedz(405, ['blad' => 'Metoda niedozwolona.']);
