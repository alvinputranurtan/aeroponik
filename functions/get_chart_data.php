<?php

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *'); // Tambahkan CORS header
require_once 'config.php';

// Debug mode
$debug = isset($_GET['debug']) ? true : false;

try {
    $period = $_GET['period'] ?? 'hourly';

    if ($period === 'hourly') {
        $sql = "SELECT 
                    DATE_FORMAT(created_at, '%H:00') as label,
                    ROUND(AVG(dht22_kelembaban), 1) as kelembaban,
                    ROUND(AVG(dht22_suhu), 1) as suhu_udara,
                    ROUND(AVG(ds18b20_suhu1), 1) as suhu_air1,
                    ROUND(AVG(ds18b20_suhu2), 1) as suhu_air2,
                    ROUND(AVG(ph_keasaman), 1) as ph
                FROM monitoring 
                WHERE created_at >= DATE_SUB(NOW(), INTERVAL 24 HOUR)
                GROUP BY DATE_FORMAT(created_at, '%Y-%m-%d %H')
                ORDER BY created_at ASC";
    } else {
        $sql = "SELECT 
                    DATE_FORMAT(created_at, '%d/%m') as label,
                    ROUND(AVG(dht22_kelembaban), 1) as kelembaban,
                    ROUND(AVG(dht22_suhu), 1) as suhu_udara,
                    ROUND(AVG(ds18b20_suhu1), 1) as suhu_air1,
                    ROUND(AVG(ds18b20_suhu2), 1) as suhu_air2,
                    ROUND(AVG(ph_keasaman), 1) as ph
                FROM monitoring 
                WHERE created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)
                GROUP BY DATE_FORMAT(created_at, '%Y-%m-%d')
                ORDER BY created_at ASC";
    }

    $result = $conn->query($sql);
    if (!$result) {
        throw new Exception($conn->error);
    }

    $data = [
        'labels' => [],
        'kelembaban' => [],
        'suhu_udara' => [],
        'suhu_air1' => [],
        'suhu_air2' => [],
        'ph' => [],
    ];

    while ($row = $result->fetch_assoc()) {
        $data['labels'][] = $row['label'];
        $data['kelembaban'][] = (float) $row['kelembaban'];
        $data['suhu_udara'][] = (float) $row['suhu_udara'];
        $data['suhu_air1'][] = (float) $row['suhu_air1'];
        $data['suhu_air2'][] = (float) $row['suhu_air2'];
        $data['ph'][] = (float) $row['ph'];
    }

    if ($debug) {
        $data['sql'] = $sql;
        $data['error'] = null;
    }

    echo json_encode($data);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'error' => $e->getMessage(),
        'sql' => $debug ? $sql : null,
    ]);
}

$conn->close();
