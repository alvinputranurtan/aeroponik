<?php
header('Content-Type: application/json');
date_default_timezone_set("Asia/Jakarta");

$data_file = 'data.json';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);

    if (isset($input['kelembaban'])) {
        $kelembaban = intval($input['kelembaban']);
        $timestamp = date("Y-m-d H:i:s");

        $newData = [
            'kelembaban' => $kelembaban,
            'timestamp' => $timestamp
        ];

        // Ambil data lama dan pastikan bentuk array
        $allData = [];
        if (file_exists($data_file)) {
            $existing = file_get_contents($data_file);
            $decoded = json_decode($existing, true);
            if (is_array($decoded)) {
                $allData = $decoded;
            }
        }

        // Tambah data baru ke array
        $allData[] = $newData;

        // Simpan ke file
        file_put_contents($data_file, json_encode($allData, JSON_PRETTY_PRINT));

        echo json_encode(['status' => 'success', 'message' => 'Data ditambahkan.']);
    } else {
        http_response_code(400);
        echo json_encode(['status' => 'error', 'message' => 'Data kelembaban tidak ditemukan.']);
    }

} elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (file_exists($data_file)) {
        $data = file_get_contents($data_file);
        echo $data;
    } else {
        echo json_encode([]);
    }

} else {
    http_response_code(405);
    echo json_encode(['status' => 'error', 'message' => 'Metode tidak diizinkan.']);
}
