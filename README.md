System Rezerwacji Stolika – Restauracja

Aplikacja umożliwia użytkownikom dokonywanie rezerwacji stolików w restauracji poprzez
przeglądarkę internetową. System obsługuje logowanie, rejestrację, zarządzanie
rezerwacjami (operacje CRUD), a także posiada dedykowany panel administracyjny do
zarządzania rezerwacjami i stolikami.
Technologie:

● Backend: PHP

● Baza Danych: MySQL

● Frontend: HTML, CSS (Bootstrap), JavaScript

● Bezpieczeństwo: Sesje PHP, walidacja danych, tokeny CSRF, szyfrowanie haseł

![Zrzut ekranu 2025-01-22 173147](https://github.com/user-attachments/assets/dc013060-a58d-4457-8d05-483e6ffb2502)

Architektura Aplikacji

Projekt został podzielony na następujące moduły:

Moduły Użytkownika

● user/index.php:

Panel użytkownika, wyświetla dane osobowe, aktywne rezerwacje oraz linki do
zarządzania rezerwacjami.

![Zrzut ekranu 2025-01-22 175546](https://github.com/user-attachments/assets/71c3ebb0-ae26-4420-aa5b-2d8da418e39a)

user/cancel.php:

Skrypt służący do anulowania rezerwacji – sprawdza autentyczność użytkownika
oraz weryfikuje, czy dana rezerwacja należy do zalogowanego użytkownika.

Moduły Rezerwacji

● reservations.php:
Strona umożliwiająca wybór terminu (data, godzina), liczby osób oraz wyszukanie dostępnych stolików. Po wyborze stolika następuje zapis rezerwacji do bazy.

![Zrzut ekranu 2025-01-22 175609](https://github.com/user-attachments/assets/94a683a5-2bb2-4e95-a441-1ac0812202fc)

Moduły Autoryzacyjne

● login.php:

Formularz logowania, weryfikuje dane użytkownika i przekierowuje odpowiednio
(użytkownik lub administrator).

![Zrzut ekranu 2025-01-22 175518](https://github.com/user-attachments/assets/c415d49d-82c0-436e-8364-cc2a5f00ae86)

register.php:

Formularz rejestracji nowego użytkownika – walidacja pól, szyfrowanie hasła oraz zapis do bazy danych.

![Zrzut ekranu 2025-01-22 175439](https://github.com/user-attachments/assets/59305b5f-73f3-4821-841e-124aeaadac90)

Moduły Administracyjne

● admin/index.php:

Panel administratora prezentujący statystyki (np. liczba stolików, rezerwacji, status
rezerwacji) oraz listę najbliższych rezerwacji.

![Zrzut ekranu 2025-01-22 175652](https://github.com/user-attachments/assets/699c8b8a-a50d-431f-a79d-cc39a5dcd5f6)

admin/reservations.php:

Zarządzanie rezerwacjami – możliwość filtrowania (po dacie i statusie) oraz zmiany
statusu rezerwacji (np. potwierdzenie lub anulowanie).

![Zrzut ekranu 2025-01-22 180733](https://github.com/user-attachments/assets/9761b8c8-ac06-4773-82bc-c887db8e4981)

admin/tables.php, admin/edit_table.php, admin/delete_table.php:

Zarządzanie stolikami – dodawanie, edycja oraz usuwanie rekordów stolików.

![Zrzut ekranu 2025-01-22 175709](https://github.com/user-attachments/assets/d3d7f563-2294-44de-81c4-431ea82d1f16)

Moduły Prezentacji Treści

● menu.php:

Strona prezentująca menu restauracji (przystawki, dania główne, desery) z opisami, zdjęciami oraz cenami.


![Zrzut ekranu 2025-01-22 173109](https://github.com/user-attachments/assets/47f7a558-4a91-4a98-8f54-2f4b1445d322)

![Zrzut ekranu 2025-01-22 173118](https://github.com/user-attachments/assets/c85544b6-a7a0-4943-b420-a4d5c9ec9156)

index.php:
Strona powitalna wyświetlająca główne informacje o restauracji oraz przyciski
logowania/rejestracji.

![Zrzut ekranu 2025-01-22 173147](https://github.com/user-attachments/assets/9643c359-8760-4005-abba-a84879724232)

Struktura Bazy Danych

Baza danych MySQL składa się z minimum trzech głównych tabel:

3.1. Tabela users

● Pola:
○ id (PK)
○ first_name
○ last_name
○ email
○ password
○ role (wartość: admin lub user)

3.2. Tabela reservations

● Pola:
○ id
○ user_id (FK - powiązanie z users)
○ table_id (FK - powiązanie z tables)
○ reservation_date
○ reservation_time
○ num_of_people
○ status (wartości: pending, confirmed, cancelled)

3.3. Tabela tables

● Pola:
○ id
○ table_name (lub numer stolika)
○ seats (liczba miejsc)
○ location (lokalizacja w restauracji)


![Zrzut ekranu 2025-01-22 175304](https://github.com/user-attachments/assets/f025f28a-678a-4683-b156-ecdd7205b95a)

![Zrzut ekranu 2025-01-22 173441](https://github.com/user-attachments/assets/144301f3-b7c6-4599-ac5b-10cc9de6d046)

Przepływ Danych i Funkcjonalności Systemu

4.1. Rejestracja i Logowanie

● Rejestracja (register.php):

Użytkownik wypełnia formularz rejestracyjny. Dane są walidowane przy użyciu funkcji
z pliku includes/validation.php. Po zatwierdzeniu dane użytkownika (wraz z
zaszyfrowanym hasłem) są zapisywane w tabeli users.

![Zrzut ekranu 2025-01-22 175439](https://github.com/user-attachments/assets/eebebadb-fb9e-4e39-ad49-414e250b7cad)

Logowanie (login.php):

Użytkownik loguje się, podając e-mail i hasło. Po weryfikacji hasła (funkcja
password_verify) ustawiane są zmienne sesyjne (np. user_id i role).
Następuje przekierowanie do odpowiedniego panelu (użytkownika lub
administratora).


![Zrzut ekranu 2025-01-22 175518](https://github.com/user-attachments/assets/b5bd5859-cf3f-4157-9b40-483cd7f15309)

Zarządzanie Rezerwacjami

● Użytkownik:

○ Po zalogowaniu, użytkownik widzi swoje aktywne rezerwacje w panelu (plik
user/index.php).

○ Formularz rezerwacji (reservations.php) umożliwia wybór daty, godziny i
liczby osób, a następnie wyszukanie dostępnych stolików. Jeśli stolik jest
wolny, rezerwacja zostaje zapisana w bazie.

○ Opcja anulowania rezerwacji (przez user/cancel.php) zmienia status
rezerwacji na „cancelled”.
Screenshot: Widok formularza rezerwacji i lista aktywnych rezerwacji

![Zrzut ekranu 2025-01-22 175546](https://github.com/user-attachments/assets/65cddda7-e769-460c-b1b4-9a004f3ea2a9)

![Zrzut ekranu 2025-01-22 175609](https://github.com/user-attachments/assets/36167c42-592d-4a04-b412-edee6c968019)

![Zrzut ekranu 2025-01-22 181440](https://github.com/user-attachments/assets/8b44b82f-38b0-4e2e-be33-aaf276383fe3)

![Zrzut ekranu 2025-01-22 181500](https://github.com/user-attachments/assets/29e9840e-3231-4b73-b672-237f635f478a)

Administrator:

Administrator ma możliwość filtrowania, przeglądania i zmiany statusu rezerwacji (np.
potwierdzenie lub anulowanie) za pomocą modułów w panelu administracyjnym
(admin/reservations.php).

![Zrzut ekranu 2025-01-22 173016](https://github.com/user-attachments/assets/65956e94-2df1-4208-a1e1-cd4154162a78)

![Zrzut ekranu 2025-01-22 181641](https://github.com/user-attachments/assets/80ebe680-7db1-4e40-993e-28573c34039c)

![Zrzut ekranu 2025-01-22 181630](https://github.com/user-attachments/assets/3f25170d-e62b-4986-be89-36b9162db51a)

Zarządzanie Stolikiem (CRUD)

● Dodawanie, edycja, usuwanie stolików:

Moduły administracyjne (admin/tables.php, admin/edit_table.php,
admin/delete_table.php) umożliwiają administratorowi tworzenie nowych
rekordów stolików, edycję istniejących oraz ich usuwanie.

![Zrzut ekranu 2025-01-22 175709](https://github.com/user-attachments/assets/efbf5d48-a890-4641-9eeb-bbbc2d81e778)
![Zrzut ekranu 2025-01-22 175729](https://github.com/user-attachments/assets/8bc6199a-6afc-4310-a39c-9f6cc712ff22)

Panel Administratora

Panel administratora (admin/index.php) prezentuje kluczowe statystyki:

● Łączna liczba stolików

● Łączna liczba rezerwacji

● Rozbicie rezerwacji według statusu

● Lista najbliższych rezerwacji

![Zrzut ekranu 2025-01-22 175652](https://github.com/user-attachments/assets/a9b178cf-94c7-4e11-b173-717e05dc820a)

Moduły Wspólne i Mechanizmy Pomocnicze

5.1. Konfiguracja

● Plik config/config.php:
Zawiera ustawienia połączenia z bazą danych (host, nazwa bazy, użytkownik, hasło)
oraz inne parametry aplikacji (np. godziny otwarcia).

// config/config.php
●
● $host = 'localhost';
● $db_name = 'restaurant_db';
● $db_user = 'root';
● $db_password = '';
●
● try {
● $dsn = "mysql:host=$host;dbname=$db_name;charset=utf8";
● $pdo = new PDO($dsn, $db_user, $db_password, [
● PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
● ]);
● } catch(PDOException $e) {
● echo "Błąd połączenia z bazą: " . $e->getMessage();
● exit;
● }
●
● $appConfig = [
● 'open_hour' => 10,
● 'close_hour' => 22,
● 'max_reservations_per_day' => 50
● ];

Funkcje Pomocnicze (includes/functions.php)

● ensureSessionStarted() – Inicjalizuje sesję, jeśli jeszcze nie została
uruchomiona.

● ensureAdmin() / ensureUser() – Sprawdzają, czy użytkownik jest zalogowany
oraz czy posiada odpowiednią rolę.

● getCsrfToken() i checkCsrfToken($token) – Generują oraz weryfikują
tokeny CSRF, zabezpieczając formularze.

● renderReservationStatus($status) – Renderuje status rezerwacji jako
odpowiadający badge z odpowiednim kolorem (np. zielony dla potwierdzonych,
czerwony dla anulowanych).

● getPaginationOffset($page, $limit) – Oblicza wartość offset dla zapytań
SQL przy paginacji.

● // includes/functions.php
●
● /**
● * Starts a session if it hasn't been started yet.
● */
● function ensureSessionStarted() {
● if (session_status() === PHP_SESSION_NONE) {
● session_start();
● }
● }
●
● /**
● * Checks if the user is logged in as an admin.
● * If not, redirects to login.php.
● */
● function ensureAdmin() {
● ensureSessionStarted();
● if (!isset($_SESSION['user_id']) || $_SESSION['role'] !==
'admin') {
● header("Location: ../login.php");
● exit;
● }
● }
●
● /**
● * Checks if the user is logged in as a regular user.
● */
● function ensureUser() {
● ensureSessionStarted();
● if (!isset($_SESSION['user_id']) || $_SESSION['role'] !==
'user') {
● header("Location: ../login.php");
● exit;
● }
● }
●
● /**
● * Retrieves the CSRF token. If the token hasn't been generated
yet,
● * generates a new one and saves it in the session.
● *
● * @return string CSRF token.
● */
● function getCsrfToken() {
● ensureSessionStarted();
● if (empty($_SESSION['csrf_token'])) {
● $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
● }
● return $_SESSION['csrf_token'];
● }
●
● /**
● * Verifies if the submitted CSRF token is valid.
● *
● * @param string $token Token submitted from the form.
● * @return bool True if the token is valid, otherwise false.
● */
● function checkCsrfToken($token) {
● ensureSessionStarted();
● return isset($_SESSION['csrf_token']) &&
hash_equals($_SESSION['csrf_token'], $token);
● }
●
● /**
● * Renders the HTML for the reservation status.
● *
● * @param string $status Reservation status (e.g., "pending",
"confirmed", "cancelled").
● * @return string HTML element with the appropriate class
(badge).
● */
● function renderReservationStatus($status) {
● switch ($status) {
● case 'pending':
● return '<span class="badge bg-warning
text-dark">Pending</span>';
● case 'confirmed':
● return '<span class="badge
bg-success">Confirmed</span>';
● case 'cancelled':
● return '<span class="badge
bg-danger">Cancelled</span>';
● default:
● return '<span class="badge bg-secondary">' .
htmlspecialchars($status) . '</span>';
● }
● }
●
● /**
● * Calculates the offset for pagination based on the current
page number
● * and the number of records per page.
● *
● * @param int $page Current page number.
● * @param int $limit Number of records per page.
● * @return int Offset for SQL queries.
● */
● function getPaginationOffset($page, $limit) {
● return ($page - 1) * $limit;
● }

System Komunikatów (includes/messages.php)
● setMessage($type, $message) – Ustawia komunikaty dla użytkownika (sukces,
błąd).
● displayMessages() – Wyświetla zapisane komunikaty w formie alertów. 

// includes/messages.php
● require_once 'functions.php';
●
● function setMessage($type, $message) {
● ensureSessionStarted();
● $_SESSION['messages'][$type][] = $message;
● }
●
● function displayMessages() {
● ensureSessionStarted();
● if (!empty($_SESSION['messages'])) {
● foreach ($_SESSION['messages'] as $type => $messages)
{
● foreach ($messages as $msg) {
● echo '<div class="alert alert-' .
htmlspecialchars($type) . '">' . htmlspecialchars($msg) .
'</div>';
● }
● }
● unset($_SESSION['messages']);
● }
● }

Walidacja Danych (includes/validation.php)

● validateEmail($email) – Weryfikuje poprawność adresu e-mail.

● validateText($text) – Sprawdza, czy pole tekstowe nie jest puste.

● validatePassword($password) – Weryfikuje minimalną długość hasła (np.
minimum 8 znaków).

// includes/validation.php
●
● function validateEmail($email) {
● return filter_var($email, FILTER_VALIDATE_EMAIL);
● }
●
● function validateText($text) {
● return !empty(trim($text));
● }
●
● function validatePassword($password) {
● return strlen($password) >= 8;
● }

Generowanie Opcji Czasowych (includes/time_options.php)

● generateTimeOptions($selectedTime = "") – Generuje listę opcji czasu (od
13:00 do 22:00, co 15 minut) do formularza rezerwacji.

// includes/time_options.php
●
● function generateTimeOptions($selectedTime = "") {
● $options = "";
● $start = new DateTime("13:00");
● $end = new DateTime("22:00");
● while ($start <= $end) {
● $timeValue = $start->format("H:i");
● $selected = ($timeValue === $selectedTime) ? '
selected' : '';
● $options .= "<option
value=\"$timeValue\"$selected>$timeValue</option>\n";
● $start->modify("+15 minutes");
● }
● return $options;
● }

Podsumowanie

Projekt został opracowany modularnie, z wyraźnym podziałem na moduły użytkownika,
rezerwacji, autoryzacji oraz administracyjne. Każdy kluczowy element jest wspierany
odpowiednimi funkcjami pomocniczymi, a bezpieczeństwo i walidacja danych są
zaimplementowane zgodnie z najlepszymi praktykami (CSRF, sesje, szyfrowanie haseł).
Dokumentacja wraz z zrzutami ekranów (wskazanymi wyżej) stanowi kompletny opis
funkcjonalności i struktury aplikacji, ułatwiającym zarówno ocenę projektu, jak i przyszłą
rozbudowę systemu.


