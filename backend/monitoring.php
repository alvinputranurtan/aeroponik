<?php

header('Content-Type: application/json');
include '../functions/config.php';

$data = json_decode(file_get_contents('php://input'), true);
if (!$data) {
    http_response_code(400);
    echo json_encode(['message' => 'Invalid JSON']);
    exit;
}

/* Optional: validasi minimal */
$required = ['user_id', 'dht22_kelembaban', 'dht22_suhu', 'ds18b20_suhu1', 'ds18b20_suhu2', 'ph_keasaman', 'status_pompa', 'status_chiller'];
foreach ($required as $k) {
    if (!isset($data[$k])) {
        http_response_code(422);
        echo json_encode(['message' => "Field '$k' wajib ada"]);
        exit;
    }
}

$sql = 'INSERT INTO monitoring 
(user_id, dht22_kelembaban, dht22_suhu, ds18b20_suhu1, ds18b20_suhu2, ph_keasaman, status_pompa, status_chiller) 
VALUES (?, ?, ?, ?, ?, ?, ?, ?)';

$stmt = $conn->prepare($sql);
if (!$stmt) {
    http_response_code(500);
    echo json_encode(['message' => 'Gagal prepare', 'error' => $conn->error]);
    exit;
}

$stmt->bind_param(
    'idddddss',
    $data['user_id'],
    $data['dht22_kelembaban'],
    $data['dht22_suhu'],
    $data['ds18b20_suhu1'],
    $data['ds18b20_suhu2'],
    $data['ph_keasaman'],
    $data['status_pompa'],
    $data['status_chiller']
);

if ($stmt->execute()) {
    echo json_encode(['message' => 'Data berhasil disimpan', 'insert_id' => $stmt->insert_id]);
} else {
    http_response_code(500);
    echo json_encode(['message' => 'Gagal menyimpan data', 'error' => $stmt->error]);
}

$stmt->close();
$conn->close();
