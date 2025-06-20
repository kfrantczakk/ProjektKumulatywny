<?php
session_start();
session_destroy();
session_start();

require_once 'classes/User.php';

if (isset($_SESSION['zalogowany']) && $_SESSION['zalogowany'] === true) {
    header("Location: index.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $login = $_POST['login'];
    $haslo = $_POST['haslo'];

require_once 'db.php'; // plik z połączeniem PDO (opisany niżej)

$stmt = $pdo->prepare("SELECT id FROM uzytkownicy WHERE login = ? AND haslo = ?");
$stmt->execute([$login, $haslo]);
$user = $stmt->fetch();

if ($user) {
    $_SESSION['zalogowany'] = true;
    $_SESSION['user_id'] = $user['id']; // << KLUCZOWE!
    $_SESSION['saldo'] = $_SESSION['saldo'] ?? 0;
    $_SESSION['historia'] = $_SESSION['historia'] ?? [];
    $_SESSION['ostatnia_operacja'] = $_SESSION['ostatnia_operacja'] ?? [];

    setcookie("ostatni_login", $login, time() + 3600);

    header("Location: index.php");
    exit();
} else {
    $blad = "Nieprawidłowy login lub hasło.";
}

}

$html = file_get_contents('login.html');
$html = str_replace('{{blad}}', isset($blad) ? "<p class='error'>$blad</p>" : '', $html);
echo $html;
