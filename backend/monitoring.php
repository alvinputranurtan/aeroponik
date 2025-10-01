<?php

header('Content-Type: application/json');
include '../functions/config.php';

$data = json_decode(file_get_contents('php://input'), true);

if (!$data) {
    http_response_code(400);
    echo json_encode(['message' => 'Invalid JSON']);
    exit;
}

$stmt = $conn->prepare('INSERT INTO monitoring 
    (user_id, dht22_kelembaban, dht22_suhu, ds18b20_suhu, ph_keasaman, tds_kualitas_air, turbidity_kekeruhan) 
    VALUES (?, ?, ?, ?, ?, ?, ?)');

$stmt->bind_param('idddddd',
    $data['user_id'],
    $data['dht22_kelembaban'],
    $data['dht22_suhu'],
    $data['ds18b20_suhu'],
    $data['ph_keasaman'],
    $data['tds_kualitas_air'],
    $data['turbidity_kekeruhan']
);

if ($stmt->execute()) {
    echo json_encode(['message' => 'Data berhasil disimpan']);
} else {
    http_response_code(500);
    echo json_encode(['message' => 'Gagal menyimpan data']);
}
