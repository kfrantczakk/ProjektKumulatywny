<?php

$accounts = [];

function stworzKonto($nazwa, $stanBaza = 0){
    global $accounts;

    $numerKonta = uniqid("ACC");
    $accounts[$numerKonta] = [
        'wlasciciel' => $nazwa,
        'stan' => $stanBaza,
    ];

    echo "Konto utworzone dla {$nazwa}. Numer konta to: {$numerKonta}<br>";
    return $numerKonta;
}

function wplata($numerKonta, $wartosc) {
    global $accounts;

    if (isset($accounts[$numerKonta])) {
        $accounts[$numerKonta]['stan'] += $wartosc;
        echo "Wpłacono {$wartosc} na konto {$numerKonta}.<br>";
    } else {
        echo "Konto nie istnieje!<br>";
    }
}

function wyplata($numerKonta, $wartosc) {
    global $accounts;
    if (isset($accounts[$numerKonta])) {
        if ($accounts[$numerKonta]['balance'] >= $wartosc) {
            $accounts[$numerKonta]['balance'] -= $wartosc;
            echo "Wypłacono {$wartosc} z konta {$numerKonta}.<br>";
        } else {
            echo "Niewystarczajace środki na koncie!<br>";
        }
    } else {
        echo "Konto nie istnieje!<br>"; 
    }
}

function sprawdzStan($numerKonta) {
    global $accounts;

    if (isset($accounts[$numerKonta])) {
        $stan = $accounts[$numerKonta]['stan'];
        echo "Stan konta {$numerKonta} wynosi {$stan}.<br>";
    } else {
        echo "Konto nie istnieje!<br>"; 
    }
}