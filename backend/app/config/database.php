<?php
declare(strict_types=1);

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

function db(): mysqli {
    static $conn = null;
    if ($conn instanceof mysqli) return $conn;

    $host = getenv('DB_HOST') ?: 'localhost';
    $port = (int)(getenv('DB_PORT') ?: 3306);
    $name = getenv('DB_NAME') ?: 'bd-dinokinggames'; 
    $user = getenv('DB_USER') ?: 'user_dinokinggames';
    $pass = getenv('DB_PASS') ?: 'Vr2!Km6@Qn8#Xp7W';

    $conn = new mysqli($host, $user, $pass, $name, $port);
    $conn->set_charset('utf8mb4');
    return $conn;
}