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

if ($metoda === 'POST') {
    session_start();
    if (empty($_SESSION['uzytkownik'])) {
        odpowiedz(401, ['blad' => 'Nie jesteś zalogowany.']);
    }
    $contentType = $_SERVER['CONTENT_TYPE'] ?? '';
    if (!str_starts_with($contentType, 'application/json')) {
        odpowiedz(415, ['blad' => 'Wymagany Content-Type: application/json.']);
    }
    $dane = json_decode(file_get_contents('php://input'), true);
    if (!is_array($dane)) {
        odpowiedz(400, ['blad' => 'Zepsuty JSON.']);
    }
    $nazwa = is_string($dane['nazwa'] ?? null) ? trim($dane['nazwa']) : '';
    $cena = (int) ($dane['cena'] ?? 0);
    if ($nazwa === '' || $cena <= 0) {
        odpowiedz(400, ['blad' => 'Uzupełnij nazwę i cenę.']);
    }
    $z = $pdo->prepare('INSERT INTO uslugi (nazwa, czas, cena, popularnosc) VALUES (:nazwa, :czas, :cena, :popularnosc)');
    $z->execute(['nazwa' => $nazwa, 'czas' => '-', 'cena' => $cena, 'popularnosc' => 0]);
    $id = (int) $pdo->lastInsertId();
    odpowiedz(201, ['id' => $id, 'nazwa' => $nazwa, 'czas' => '-', 'cena' => $cena, 'popularnosc' => 0]);
}

header('Allow: GET, POST');
odpowiedz(405, ['blad' => 'Metoda niedozwolona.']);
