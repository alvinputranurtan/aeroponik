<?php

// Only configure session if not already started
if (session_status() === PHP_SESSION_NONE) {
    ini_set('session.cookie_httponly', 1);
    ini_set('session.cookie_secure', 1);
    ini_set('session.gc_maxlifetime', 86400); // 24 jam
    session_start();
}

// Wajib pakai HTTPS di server produksi
// if (empty($_SERVER['HTTPS']) || $_SERVER['HTTPS'] === 'off') {
//     die("Akses hanya boleh melalui HTTPS");
// }

// Set PHP timezone
date_default_timezone_set('Asia/Jakarta');

// Koneksi DB
$host = 'inosakti.com';
$user = 'inosakti_useraeroponik';
$password = 'InoSakti2025';
$dbname = 'inosakti_aeroponik';

$conn = new mysqli($host, $user, $password, $dbname);
if ($conn->connect_error) {
    exit('Koneksi gagal: '.$conn->connect_error);
}
$conn->set_charset('utf8mb4');

// Set MySQL timezone
$conn->query("SET time_zone = '+07:00'");

// Regenerate session ID if not already done
if (!isset($_SESSION['initialized'])) {
    session_regenerate_id(true);
    $_SESSION['initialized'] = true;
}
