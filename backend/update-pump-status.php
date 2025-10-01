<?php

header('Content-Type: application/json');
include '../functions/config.php';

$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data['status'])) {
    http_response_code(400);
    echo json_encode(['message' => 'Status tidak ditemukan']);
    exit;
}

$stmt = $conn->prepare('INSERT INTO pump_status (status) VALUES (?)');
$stmt->bind_param('s', $data['status']);

if ($stmt->execute()) {
    echo json_encode(['message' => 'Status pompa berhasil diperbarui']);
} else {
    http_response_code(500);
    echo json_encode(['message' => 'Gagal memperbarui status pompa']);
}
