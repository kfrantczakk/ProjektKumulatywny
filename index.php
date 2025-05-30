<?php
session_start();

if (!isset($_SESSION['zalogowany']) || $_SESSION['zalogowany'] !== true) {
    header("Location: login.php");
    exit();
}

if (!isset($_SESSION['saldo'])) $_SESSION['saldo'] = 0.00;
if (!isset($_SESSION['historia'])) $_SESSION['historia'] = [];
if (!isset($_SESSION['ostatnia_operacja'])) $_SESSION['ostatnia_operacja'] = '';
if (!isset($_SESSION['karty'])) {
    $_SESSION['karty'] = [
        ['numer' => '1234 5678 9012 3456', 'data' => '12/26', 'typ' => 'Visa'],
        ['numer' => '9876 5432 1098 7654', 'data' => '11/25', 'typ' => 'Mastercard']
    ];
}

if (isset($_POST['reset']) || isset($_GET['reset'])) {
    session_destroy();
    header("Location: login.php");
    exit();
}

if (isset($_POST['czysc_historia'])) {
    $_SESSION['historia'] = [];
    $_SESSION['ostatnia_operacja'] = 'Historia wyczyszczona.';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $czas = date('Y-m-d H:i:s');

    // Only handle deposits now (wpłata only)
    if (isset($_POST['kwota']) && isset($_POST['typ'])) {
        $kwota = floatval($_POST['kwota']);
        $typ = $_POST['typ'];
        if ($kwota > 0 && $typ === 'wplata') {
            $_SESSION['saldo'] += $kwota;
            $_SESSION['historia'][] = "$czas – Wpłata: +$kwota zł";
            $_SESSION['ostatnia_operacja'] = "Wpłata: $kwota zł";
        }
    }

    if (isset($_POST['przelew'])) {
        $odbiorca = htmlspecialchars($_POST['odbiorca']);
        $kwota = floatval($_POST['kwota_przelew']);
        if ($kwota > 0 && $_SESSION['saldo'] >= $kwota) {
            $_SESSION['saldo'] -= $kwota;
            $_SESSION['historia'][] = "$czas – Przelew do $odbiorca: -$kwota zł";
            $_SESSION['ostatnia_operacja'] = "Przelew do $odbiorca: $kwota zł";
        } else {
            $_SESSION['historia'][] = "$czas – Nieudany przelew do $odbiorca";
            $_SESSION['ostatnia_operacja'] = "Nieudany przelew do $odbiorca";
        }
    }
}

$blik = str_pad(random_int(100000, 999999), 6, '0', STR_PAD_LEFT);

// Template replacement
$html = file_get_contents('konto.html');
$html = str_replace('{{saldo}}', number_format($_SESSION['saldo'], 2), $html);
$html = str_replace('{{ostatnia_operacja}}', htmlspecialchars($_SESSION['ostatnia_operacja']), $html);
$html = str_replace('{{blik}}', $blik, $html);

$kartyHtml = '';
foreach ($_SESSION['karty'] as $karta) {
    $kartyHtml .= "<li>{$karta['typ']}: {$karta['numer']} (do {$karta['data']})</li>";
}
$html = str_replace('{{karty}}', $kartyHtml, $html);

// Show only last 3 transactions initially
$historia_reversed = array_reverse($_SESSION['historia']);
$ostatnie_3 = array_slice($historia_reversed, 0, 3);
$pozostale = array_slice($historia_reversed, 3);

$historiaHtml = '';
foreach ($ostatnie_3 as $linia) {
    $historiaHtml .= "<li>" . htmlspecialchars($linia) . "</li>\n";
}

$pozostaleHtml = '';
foreach ($pozostale as $linia) {
    $pozostaleHtml .= "<li>" . htmlspecialchars($linia) . "</li>\n";
}

$html = str_replace('{{historia}}', $historiaHtml, $html);
$html = str_replace('{{pozostale_historia}}', $pozostaleHtml, $html);

echo $html;
?>
