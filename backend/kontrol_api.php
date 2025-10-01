<?php

header('Content-Type: application/json');
include '../functions/config.php';

// ✅ ESP kirim data kontrol
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);

    $kontrolData = $input['kontrol_data'] ?? [];
    $kontrolRealtime = $input['kontrol_realtime'] ?? null;

    // --- kontrol_data ---
    if (!empty($kontrolData)) {
        $conn->query('TRUNCATE TABLE kontrol_data');
        $stmt = $conn->prepare('INSERT INTO kontrol_data (hidupkan_sprayer_pada_jam, hidupkan_sprayer_selama_menit) VALUES (?, ?)');
        foreach ($kontrolData as $row) {
            $jam = $row['hidupkan_sprayer_pada_jam'];
            $menit = $row['hidupkan_sprayer_selama_menit'];
            $stmt->bind_param('si', $jam, $menit);
            $stmt->execute();
        }
    }

    // --- kontrol_realtime ---
    if (!empty($kontrolRealtime)) {
        $conn->query('TRUNCATE TABLE kontrol_realtime');
        $stmt2 = $conn->prepare('INSERT INTO kontrol_realtime (sistem_penyiraman, tekan_untuk_hidupkan_sprayer) VALUES (?, ?)');
        $stmt2->bind_param('ii', $kontrolRealtime['sistem_penyiraman'], $kontrolRealtime['tekan_untuk_hidupkan_sprayer']);
        $stmt2->execute();
    }

    echo json_encode(['message' => 'Data kontrol diperbarui']);
}

// ✅ ESP ambil data kontrol (GET)
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $result1 = $conn->query('SELECT * FROM kontrol_data');
    $kontrol_data = [];
    while ($row = $result1->fetch_assoc()) {
        $kontrol_data[] = $row;
    }

    $result2 = $conn->query('SELECT * FROM kontrol_realtime');
    $kontrol_realtime = [];
    while ($row = $result2->fetch_assoc()) {
        $kontrol_realtime[] = $row;
    }

    echo json_encode([
        'kontrol_data' => $kontrol_data,
        'kontrol_realtime' => $kontrol_realtime,
    ]);
}
