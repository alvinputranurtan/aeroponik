<?php

header('Content-Type: application/json');
include __DIR__.'/../functions/config.php';

// Ambil data terbaru
$sql = 'SELECT * FROM monitoring ORDER BY id DESC LIMIT 1';
$result = $conn->query($sql);
$row = $result->fetch_assoc();

// Cek status perangkat
$status_perangkat = 'Offline';
if ($row) {
    $last_time = strtotime($row['created_at']);
    if ($last_time !== false && (time() - $last_time) <= 15) {
        $status_perangkat = 'Online';
    }
}

// Kirim response JSON
echo json_encode([
    'status_perangkat' => $status_perangkat,
    'status_pompa' => $row['status_pompa'],
    'status_chiller' => $row['status_chiller'],
    'dht22_kelembaban' => floatval($row['dht22_kelembaban']),
    'dht22_suhu' => floatval($row['dht22_suhu']),
    'ds18b20_suhu1' => floatval($row['ds18b20_suhu1']),
    'ds18b20_suhu2' => floatval($row['ds18b20_suhu2']),
    'ph_keasaman' => floatval($row['ph_keasaman']),
]);
