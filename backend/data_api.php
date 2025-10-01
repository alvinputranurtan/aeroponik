<?php

header('Content-Type: application/json');
include '../functions/config.php';

// ✅ ESP mengirim data sensor (POST)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);

    $timestamp = $input['timestamp'] ?? null;  // waktu dari ESP
    $status_pompa = $input['status_pompa'] ?? 0;
    $kelembaban_tanah = $input['kelembaban_tanah'] ?? 0;
    $kelembaban_udara = $input['kelembaban_udara'] ?? 0;
    $suhu_udara = $input['suhu_udara'] ?? 0;
    $tombol_auto = $input['tombol_fisik_penyiraman_otomatis'] ?? 0;
    $tombol_pompa = $input['tombol_fisik_menyalakan_pompa'] ?? 0;

    $sql = 'INSERT INTO data (timestamp, status_pompa, kelembaban_tanah, kelembaban_udara, suhu_udara, tombol_fisik_penyiraman_otomatis, tombol_fisik_menyalakan_pompa)
            VALUES (?, ?, ?, ?, ?, ?, ?)';

    $stmt = $conn->prepare($sql);
    $stmt->bind_param('siiiiii', $timestamp, $status_pompa, $kelembaban_tanah, $kelembaban_udara, $suhu_udara, $tombol_auto, $tombol_pompa);

    if ($stmt->execute()) {
        echo json_encode(['message' => 'Data sensor tersimpan']);
    } else {
        echo json_encode(['error' => $conn->error]);
    }
}
