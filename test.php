<?php
require 'config.php';

$sql = "SELECT * FROM uzytkownicy";
$stmt = $pdo->query($sql);

foreach ($stmt as $row) {
    echo "Login: " . $row['login'] . "<br>";
}
