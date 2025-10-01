<?php

header('Content-Type: application/json');
require_once 'config.php';

$period = $_GET['period'] ?? 'hourly';

if ($period === 'hourly') {
    $sql = "SELECT 
                DATE_FORMAT(created_at, '%H:00') as label,
                ROUND(AVG(NULLIF(dht22_kelembaban, 0)), 1) as kelembaban,
                ROUND(AVG(NULLIF(dht22_suhu, 0)), 1) as suhu_udara,
                ROUND(AVG(NULLIF(ds18b20_suhu1, -127)), 1) as suhu_air1,
                ROUND(AVG(NULLIF(ds18b20_suhu2, -127)), 1) as suhu_air2,
                ROUND(AVG(NULLIF(ph_keasaman, 0)), 1) as ph
            FROM monitoring 
            WHERE created_at >= DATE_SUB(NOW(), INTERVAL 24 HOUR)
            GROUP BY DATE_FORMAT(created_at, '%Y-%m-%d %H')
            ORDER BY created_at ASC";
} else {
    $sql = "SELECT 
                DATE_FORMAT(created_at, '%d/%m') as label,
                ROUND(AVG(NULLIF(dht22_kelembaban, 0)), 1) as kelembaban,
                ROUND(AVG(NULLIF(dht22_suhu, 0)), 1) as suhu_udara,
                ROUND(AVG(NULLIF(ds18b20_suhu1, -127)), 1) as suhu_air1,
                ROUND(AVG(NULLIF(ds18b20_suhu2, -127)), 1) as suhu_air2,
                ROUND(AVG(NULLIF(ph_keasaman, 0)), 1) as ph
            FROM monitoring 
            WHERE created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)
            GROUP BY DATE_FORMAT(created_at, '%Y-%m-%d')
            ORDER BY created_at ASC";
}

$result = $conn->query($sql);

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

echo json_encode($data);
$conn->close();
