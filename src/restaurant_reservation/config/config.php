<?php
// config/config.php

$host = 'localhost';
$db_name = 'restaurant_db';
$db_user = 'root';
$db_password = '';

try {
    $dsn = "mysql:host=$host;dbname=$db_name;charset=utf8";
    $pdo = new PDO($dsn, $db_user, $db_password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);
} catch(PDOException $e) {
    echo "Błąd połączenia z bazą: " . $e->getMessage();
    exit;
}

$appConfig = [
    'open_hour' => 10,
    'close_hour' => 22,
    'max_reservations_per_day' => 50
];
