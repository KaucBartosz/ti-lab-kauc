Technologie internetowe · Laboratorium 3
PHP, baza
i mini-CMS
Cennik Szprychy trafia do bazy, a właściciel zmienia go w panelu
admina z logowaniem. Na koniec pierwsze klocki Bootstrapa.
mgr inż. Jan Cybulski · Creospace · UKW Bydgoszcz

Dzisiaj · ok. 2 godziny
Plan zajęć
Czas Blok
0–10 min Codespaces i co to jest CMS
10–25 min Baza i SQL: tabela, SELECT, INSERT, WHERE, DELETE
25–40 min PHP + PDO: połączenie, prepare(), SQL injection
40–60 min Logowanie: hasła, sesja, strażnik panelu
60–70 min Panel i Bootstrap: lista, dodawanie, usuwanie
70–115 min Praca własna: 4 zadania, 4 commity
115–120 min Ostatni push
Technologie internetowe · Lab 3 2

Po co nam baza
Kto dziś zmienia cennik?
Lab 2 · tablica w kodzie Lab 3 · baza i panel
Programista Właściciel firmy
Zmiana ceny to edycja pliku, commit i wdrożenie. Loguje się do panelu i dodaje usługę. Kod się
Klient dzwoni i czeka. nie zmienia.
Treści w bazie, zmiany w panelu. To jest właśnie CMS.
Technologie internetowe · Lab 3 3

Content Management System
Co to jest CMS?
| Front · każdy klient | Baza danych        | Panel · po zalogowaniu |
| -------------------- | ------------------ | ---------------------- |
| Pokazuje treści      | Przechowuje treści | Zmienia treści         |
Czyta z bazy i wyświetla.  Jedno źródło prawdy dla frontu  INSERT, UPDATE, DELETE. Bez
| Tylko SELECT. | i panelu. | dotykania kodu. |
| ------------- | --------- | --------------- |
WordPress Laravel + Filament Strapi (headless) CreoCMS nasz mini-CMS
Technologie internetowe · Lab 3 4

Architektura
Mini-CMS Szprychy: 7 plików
| Plik     | Kto tam trafia | Co robi                         |
| -------- | -------------- | ------------------------------- |
| baza.php | dołączany      | połączenie PDO z plikiem SQLite |
setup.php terminal tabele uslugi i uzytkownicy, dane, konto admina
| index.php   | każdy      | front: cennik z bazy                    |
| ----------- | ---------- | --------------------------------------- |
| login.php   | każdy      | sprawdza login i hasło, zapisuje sesję  |
| auth.php    | dołączany  | strażnik: bez sesji odsyła do logowania |
| admin.php   | zalogowany | panel: lista, dodawanie, usuwanie usług |
| wyloguj.php | zalogowany | niszczy sesję                           |
Technologie internetowe · Lab 3 5

Baza danych w pięć minut
Tabela: kolumny i wiersze
Kolumna
| id  | nazwa | cena | popularnosc |     |
| --- | ----- | ---- | ----------- | --- |
co przechowujemy i jakiego typu: tekst, liczba
| 1   | Przegląd | 120 |     | 45  |
| --- | -------- | --- | --- | --- |
Wiersz
| 2   | Wymiana dętki | 40  |     | 30  |
| --- | ------------- | --- | --- | --- |
jeden rekord, czyli jedna usługa
| 3   | Centrowanie koła | 60  |     | 15  |
| --- | ---------------- | --- | --- | --- |
id
| 4   | Serwis pełny | 350 |     | 10  |
| --- | ------------ | --- | --- | --- |
klucz główny: unikalny numer
nadawany automatycznie
Technologie internetowe · Lab 3 6

SQL: język bazy danych
SQL w siedmiu poleceniach
| Polecenie | Przykład | Co robi |
| --------- | -------- | ------- |
CREATE TABLE CREATE TABLE uslugi (id INTEGER PRIMARY KEY, nazwa TEXT) Tworzy tabelę
INSERT INSERT INTO uslugi (nazwa, cena) VALUES ('Przegląd', 120) Dodaje wiersz
| SELECT | SELECT nazwa, cena FROM uslugi | Pobiera wiersze |
| ------ | ------------------------------ | --------------- |
WHERE SELECT * FROM uslugi WHERE cena < 100 Tylko spełniające warunek
ORDER BY SELECT * FROM uslugi ORDER BY cena DESC Sortuje, DESC malejąco
UPDATE UPDATE uslugi SET cena = 130 WHERE id = 1 Zmienia wiersz
| DELETE | DELETE FROM uslugi WHERE id = 4 | Usuwa wiersz |
| ------ | ------------------------------- | ------------ |
Technologie internetowe · Lab 3 7

Ćwiczenie na żywo
Które wiersze zwróci baza?
SELECT * FROM uslugi WHERE cena > 50 AND popularnosc > 20
| id  | nazwa | cena | popularnosc |     |     |
| --- | ----- | ---- | ----------- | --- | --- |
Tylko wiersz 1, Przegląd. AND
| 1   | Przegląd | 120 |     | 45  | wymaga obu warunków naraz.  |
| --- | -------- | --- | --- | --- | --------------------------- |
Dętka jest popularna, ale za tania.
| 2   | Wymiana dętki    | 40  |     | 30  |     |
| --- | ---------------- | --- | --- | --- | --- |
| 3   | Centrowanie koła | 60  |     | 15  |     |
| 4   | Serwis pełny     | 350 |     | 10  |     |
Technologie internetowe · Lab 3 8

DELETE bez WHERE
= pusta tabela.
Tak samo UPDATE. Najpierw napisz WHERE, potem resztę.
Technologie internetowe · Lab 3 9

PHP + baza · baza.php
Połączenie z bazą: PDO
$pdo = new PDO('sqlite:' . __DIR__ . '/szprycha.sqlite');
// MySQL na hostingu: zmienia się tylko ta linijka
// new PDO('mysql:host=localhost;dbname=szprycha', 'login', 'haslo');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
SQLite ERRMODE FETCH_ASSOC
Cała baza to jeden plik. Bez Błąd w SQL od razu zatrzymuje Wiersz jako tablica z kluczami:
serwera, działa od ręki w skrypt z komunikatem. $usluga['nazwa'].
Codespaces.
Technologie internetowe · Lab 3 10

PHP + baza · index.php
Z tablicy do bazy: jedna zmiana
Lab 2 · dane w kodzie
$cennik = [ ['nazwa' => 'Przegląd', 'cena' => 120], … ];
Lab 3 · dane z bazy
require 'baza.php';
$uslugi = $pdo->query('SELECT nazwa, cena FROM uslugi ORDER BY cena')->fetchAll();
Pętla foreach z Lab 2 zostaje bez zmian. Zmieniło się tylko źródło danych.
Technologie internetowe · Lab 3 11

Bezpieczeństwo · SQL injection
Logowanie bez hasła
Naiwne logowanie: doklejanie tekstu
$sql = "SELECT * FROM uzytkownicy WHERE login = '" . $_POST['login']
. "' AND haslo = '" . $_POST['haslo'] . "'";
Login Baza dostaje
admin' -- SELECT * FROM uzytkownicy WHERE login = 'admin' --' AND haslo = '…'
-- to komentarz w SQL. Sprawdzenie hasła znika, a atakujący jest w panelu jako admin.
Technologie internetowe · Lab 3 12

Bezpieczeństwo · obrona
Prepared statement: zapytanie i dane osobno
Źle: doklejanie Dobrze: parametr
$pdo->query("… WHERE login = '" . $login . "'"); $z = $pdo->prepare('… WHERE login = :login');
$z->execute(['login' => $login]);
Tekst użytkownika staje się częścią polecenia SQL.
Baza szuka loginu „admin' --” dosłownie. Nie ma
takiego, wejścia nie ma.
htmlspecialchars nie chroni bazy
Chroni HTML przed XSS przy wypisywaniu. Przed SQL injection chroni tylko prepare().
Coś od użytkownika w zapytaniu? Zawsze prepare().
Technologie internetowe · Lab 3 13

Logowanie · krok 1
Hasło w bazie? Tylko skrót.
Jawny tekst
// setup.php: zakładanie konta
wyciek bazy = wyciek wszystkich haseł
$hash = password_hash('Szprycha!2026', PASSWORD_DEFAULT);
// $2y$10$Vq0fN3… za każdym razem inny (sól)
md5, sha1
za szybkie: popularne hasła pękają w sekundy
// login.php: sprawdzanie
if (password_verify($haslo, $uzytkownik['haslo_hash'])) {
password_hash
// hasło pasuje
} wolny algorytm z solą. Tak robimy.
Zły login i złe hasło: ten sam komunikat, „Błędny login lub hasło”.
Technologie internetowe · Lab 3 14

Logowanie · krok 2
Sesja: pamięć między kliknięciami
| 1         | 2                | 3           |
| --------- | ---------------- | ----------- |
| Logowanie | Każde kliknięcie | Wylogowanie |
Hasło pasuje, zapisujemy, kto  Przeglądarka odsyła ciasteczko z  Czyścimy i niszczymy sesję.
| to jest. | numerem sesji. PHP odnajduje po  |     |
| -------- | -------------------------------- | --- |
$_SESSION = [];
nim dane.
session_destroy();
session_start();
$_SESSION['uzytkownik']
PHPSESSID=8f3a…
  = 'admin';
HTTP niczego nie pamięta. Dane są na serwerze, przeglądarka trzyma tylko numerek.
Technologie internetowe · Lab 3 15

Logowanie · krok 3
Strażnik panelu: auth.php
Pierwsza linijka każdej strony panelu
auth.php
require 'auth.php';
<?php
session_start();
Ukryty link to nie zabezpieczenie
Każdy może wpisać admin.php w pasek adresu. Chron i
if (empty($_SESSION['uzytkownik'])) {
dopiero strażnik.
header('Location: login.php');
exit;
„headers already sent”
}
session_start() i header() przed jakimkolwiek HTML,
nawet spacją.
Technologie internetowe · Lab 3 16

Formularze w panelu
GET czyta, POST zmienia
GET POST
Dane w adresie strony Dane w treści żądania, nie w adresie
admin.php?ok=dodano admin.php + akcja=dodaj
Do odczytu: lista, filtry, komunikaty. Link można Do zmian: logowanie, dodawanie, usuwanie.
wysłać znajomemu.
$_POST['nazwa']
$_GET['ok']
Zmieniasz coś w bazie? Zawsze POST. Nawet „Usuń” to formularz, nie link.
Technologie internetowe · Lab 3 17

Panel · admin.php
Jeden plik, cały panel
<?php
require 'auth.php'; // 1. strażnik
require 'baza.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') { // 2. obsługa formularzy
$akcja = $_POST['akcja'] ?? '';
if ($akcja === 'dodaj') { /* walidacja + INSERT */ }
if ($akcja === 'usun') { /* DELETE … WHERE id = :id */ }
}
// 3. lista usług do tabel i
$uslugi = $pdo->query('SELECT id, nazwa, cena FROM uslugi ORDER BY nazwa')->fetchAll();
?> <!-- 4. HTML: tabela z przyciskami Usuń + formularz dodawania -->
Oba formularze wysyłają POST do admin.php. Ukryte pole akcja mówi, który to był.
Technologie internetowe · Lab 3 18

admin.php · akcja dodaj
Sprawdź, zapisz, przekieruj
$nazwa = trim($_POST['nazwa'] ?? '');
$cena = (int) ($_POST['cena'] ?? 0);
if ($nazwa !== '' && $cena > 0) { // 1. sprawdź
$z = $pdo->prepare('INSERT INTO uslugi (nazwa, cena) VALUES (:nazwa, :cena)');
$z->execute(['nazwa' => $nazwa, 'cena' => $cena]); // 2. zapisz
header('Location: admin.php?ok=dodano'); // 3. przekieru j
exit;
}
POST admin.php INSERT do bazy Location: admin.php GET admin.php
Odświeżenie strony (F5) powtarza GET, a nie POST, więc usługa nie doda się drugi raz.
Technologie internetowe · Lab 3 19

Na deser · framework CSS
Bootstrap: gotowe klocki do panelu
Jedna linijka w <head>
<link rel="stylesheet"
href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH"
crossorigin="anonymous">
Panel admina Front strony
Bootstrap Własny CSS z Lab 1
Liczy się czytelność i szybka praca. Gotowe Ma wyglądać jak marka klienta, a nie jak tysiące stron
klasy wystarczą. na Bootstrapie.
Technologie internetowe · Lab 3 20

Bootstrap · klasy
Te same znaczniki, dopisane klasy
Usługa dodana.
<table class="table table-striped">
<input class="form-control">
<button class="btn btn-primary">Dodaj</button> Usługa Cena
<button class="btn btn-sm btn-outline-danger">Usuń</button>
Przegląd 120 zł Usuń
<div class="alert alert-success">Usługa dodana.</div>
Wymiana dętki 40 zł Usuń
<div class="row">
<div class="col-lg-8">tabela</div> Nazwa usługi
<div class="col-lg-4">formularz</div>
</div>
Dodaj
Siatka ma 12 kolumn: na dużym ekranie 8 + 4 obok siebie, na telefonie jedna pod drugą.
Technologie internetowe · Lab 3 21

Praca własna · ok. 45 minut
4 zadania, 4 commity
| 1               | 2            | 3               | 4            |
| --------------- | ------------ | --------------- | ------------ |
| Baza, konto     | Logowanie  i | Panel: lista  i | Bootstrap  i |
| admina, cennik  | strażnik     | dodawanie       | usuwanie     |
z bazy
|     | obowiązkowe | na wyższą ocenę | na wyższą ocenę |
| --- | ----------- | --------------- | --------------- |
obowiązkowe
Po każdym zadaniu commit. Błędy PHP i SQL są w terminalu z php -S.
Kod do skopiowania i uzupełnienia jest w materiale.
Technologie internetowe · Lab 3 22

Praca własna · Zadanie 1 z 4 · ok. 12 min
Baza, konto admina, cennik z bazy
baza.php z połączeniem
$pdo->exec('CREATE TABLE IF NOT EXISTS uzytkownicy (
setup.php: 2 tabele, 4 usługi, konto admin
id INTEGER PRIMARY KEY AUTOINCREMENT,
.gitignore z linijką *.sqlite
login TEXT NOT NULL UNIQUE,
index.php: cennik z bazy
haslo_hash TEXT NOT NULL
)');
// konto admina, gdy tabela jest pusta:
$z->execute(['login' => 'admin',
'hash' => password_hash('TwojeHaslo', PASSWORD_DEFAULT)]);
Gotowe, gdy
php setup.php pisze „Baza gotowa.”, a strona pokazuje 4 usług i git commit -m "lab3: baza i cennik"
z bazy.
Technologie internetowe · Lab 3 23

Praca własna · Zadanie 2 z 4 · ok. 12 min
Logowanie i strażnik
login.php: uzupełnij luki ___
$z = $pdo->___(
auth.php i wyloguj.php
'SELECT login, haslo_hash FROM uzytkownicy WHERE login = :login');
admin.php na razie:
$z->execute(['login' => trim($_POST['login'] ?? '')]);
„Witaj, admin”
$u = $z->fetch();
if ($u && ___($_POST['haslo'] ?? '', $u['haslo_hash'])) {
session_regenerate_id(true);
$_SESSION['uzytkownik'] = $u['login'];
header('Location: admin.php'); exit;
}
$blad = 'Błędny login lub hasło.';
Gotowe, gdy
admin.php bez logowania odsyła do login.php, a po git commit -m "lab3: logowanie"
wylogowaniu znów jest zamknięty.
Technologie internetowe · Lab 3 24

Praca własna · Zadanie 3 z 4 · ok. 12 min
Panel: lista i dodawanie
W admin.php tabela wszystkich usług
<form method="post">
Formularz POST z akcja=dodaj
<input type="hidden" name="akcja" value="dodaj">
Obsługa: sprawdź, prepare(),
<label for="nazwa">Nazwa</label>
przekieruj
<input id="nazwa" name="nazwa" required maxlength="100">
<label for="cena">Cena (zł)</label>
<input id="cena" name="cena" type="number" min="1" required>
<button>Dodaj</button>
</form>
Gotowe, gdy
Usługa z panelu jest na froncie, F5 jej nie dubluje, a <b>Test</b> git commit -m "lab3: panel CMS"
to zwykły tekst.
Technologie internetowe · Lab 3 25

Praca własna · Zadanie 4 z 4 · ok. 10 min
Bootstrap i usuwanie
Bootstrap w panelu i logowaniu,
<form method="post" class="d-inline">
front bez zmian
<input type="hidden" name="akcja" value="usun">
navbar, row / col, table, form-
<input type="hidden" name="id" value="<?= (int) $usluga['id'] ?>">
control, btn, alert
<button class="btn btn-sm btn-outline-danger">Usuń</button>
Przycisk „Usuń” w
</form>
każdym wierszu
// obsługa w PHP:
$z = $pdo->prepare('DELETE FROM uslugi WHERE id = :id');
$z->execute(['id' => (int) $_POST['id']]);
Gotowe, gdy
Panel wygląda jak panel, na telefonie formularz schodzi pod git commit -m "lab3: bootstrap"
tabelę, „Usuń” kasuje jedną usługę.
Technologie internetowe · Lab 3 26

Dla szybkich i do domu
Bonus i praca domowa
Bonus · na plus Praca domowa
Edycja usługi: edytuj.php ze strażnikiem i UPDATE Dokończ zadania 1 i 2, najlepie j
Token CSRF w formularzach panelu wszystkie cztery, i zrób git push.
Filtr ceny na froncie: GET i WHERE cena <= :max
Hasła admina nie wpisuj do
Wykres SVG z Lab 2 z kolumny popularnosc
README. Zatrzymaj codespace.
UPDATE uslugi SET nazwa = :nazwa, cena = :cena WHERE id = :id
Technologie internetowe · Lab 3 27

Koniec?
Najpierw git push.
Dziś Szprycha dostała własny CMS: baza, logowanie, panel admina i
pierwsze klasy Bootstrapa.
mgr inż. Jan Cybulski · Creospace · creospace.eu