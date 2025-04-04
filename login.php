<?php
session_start();


if (isset($_SESSION['zalogowany']) && $_SESSION['zalogowany'] === true) {
    header("Location: index.php");
    exit();
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $login = $_POST['login'];
    $haslo = $_POST['haslo'];


    if ($login === 'admin' && $haslo === '1234') {
        $_SESSION['zalogowany'] = true;
        $_SESSION['saldo'] = $_SESSION['saldo'] ?? 0;
        $_SESSION['historia'] = $_SESSION['historia'] ?? [];
        header("Location: index.php");
        exit();
    } else {
        $blad = "Nieprawidłowy login lub hasło.";
    }
}

$html = file_get_contents('login.html');
$html = str_replace('{{blad}}', isset($blad) ? "<p class='error'>$blad</p>" : '', $html);
echo $html;
