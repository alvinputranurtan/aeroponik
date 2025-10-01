<?php

header('Content-Type: application/json');
include '../functions/config.php';

// Baca input JSON
$data = json_decode(file_get_contents('php://input'), true);

// Validasi JSON
if (!$data) {
    http_response_code(400);
    echo json_encode(['message' => 'Invalid JSON']);
    exit;
}

// Query insert (sesuaikan dengan struktur tabel monitoring)
$stmt = $conn->prepare('INSERT INTO monitoring 
    (user_id, dht22_kelembaban, dht22_suhu, ds18b20_suhu, ph_keasaman, tds_kualitas_air, status_pompa, status_chiller) 
    VALUES (?, ?, ?, ?, ?, ?, ?, ?)');

// Binding parameter
$stmt->bind_param(
    'idddddss',
    $data['user_id'],
    $data['dht22_kelembaban'],
    $data['dht22_suhu'],
    $data['ds18b20_suhu'],
    $data['ph_keasaman'],
    $data['tds_kualitas_air'],
    $data['status_pompa'],
    $data['status_chiller']
);

// Eksekusi query
if ($stmt->execute()) {
    echo json_encode([
        'message' => 'Data berhasil disimpan',
        'insert_id' => $stmt->insert_id,
    ]);
} else {
    http_response_code(500);
    echo json_encode(['message' => 'Gagal menyimpan data', 'error' => $stmt->error]);
}

$stmt->close();
$conn->close();
