<?php
session_start();


if (!isset($_SESSION['zalogowany']) || $_SESSION['zalogowany'] !== true) {
    header("Location: login.php");
    exit();
}


if (isset($_POST['reset']) || isset($_GET['reset'])) {
    session_destroy();
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['kwota'])) {
    $kwota = floatval($_POST['kwota']);
    $typ = $_POST['typ'];

    if ($typ === 'wplata') {
        $_SESSION['saldo'] += $kwota;
        $_SESSION['historia'][] = "Wpłata: +$kwota zł";
    } elseif ($typ === 'wyplata') {
        if ($_SESSION['saldo'] >= $kwota) {
            $_SESSION['saldo'] -= $kwota;
            $_SESSION['historia'][] = "Wypłata: -$kwota zł";
        } else {
            $_SESSION['historia'][] = "Nieudana wypłata: brak środków";
        }
    }
}

$html = file_get_contents('konto.html');
$html = str_replace('{{saldo}}', number_format($_SESSION['saldo'], 2), $html);

$historiaHtml = '';
foreach (array_reverse($_SESSION['historia']) as $linia) {
    $historiaHtml .= "<li>" . htmlspecialchars($linia) . "</li>\n";
}
$html = str_replace('{{historia}}', $historiaHtml, $html);

echo $html;
