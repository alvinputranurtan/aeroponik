<?php

header('Content-Type: application/json');
require_once 'config.php';

$period = $_GET['period'] ?? 'hourly';

if ($period === 'hourly') {
    $sql = "SELECT 
                DATE_FORMAT(timestamp, '%H:00') as label,
                ROUND(AVG(kelembaban_tanah), 1) as kelembaban_tanah,
                ROUND(AVG(kelembaban_udara), 1) as kelembaban_udara,
                ROUND(AVG(suhu_udara), 1) as suhu_udara,
                COUNT(*) as sample_count
            FROM data 
            WHERE timestamp >= DATE_SUB(NOW(), INTERVAL 24 HOUR)
                AND kelembaban_tanah >= 0 AND kelembaban_tanah <= 100
                AND kelembaban_udara >= 0 AND kelembaban_udara <= 100
                AND suhu_udara >= 0 AND suhu_udara <= 100
            GROUP BY DATE_FORMAT(timestamp, '%Y-%m-%d %H')
            ORDER BY timestamp ASC";
} else {
    $sql = "SELECT 
                DATE_FORMAT(timestamp, '%d/%m') as label,
                ROUND(AVG(kelembaban_tanah), 1) as kelembaban_tanah,
                ROUND(AVG(kelembaban_udara), 1) as kelembaban_udara,
                ROUND(AVG(suhu_udara), 1) as suhu_udara,
                COUNT(*) as sample_count
            FROM data 
            WHERE timestamp >= DATE_SUB(NOW(), INTERVAL 7 DAY)
                AND kelembaban_tanah >= 0 AND kelembaban_tanah <= 100
                AND kelembaban_udara >= 0 AND kelembaban_udara <= 100
                AND suhu_udara >= 0 AND suhu_udara <= 100
            GROUP BY DATE_FORMAT(timestamp, '%Y-%m-%d')
            ORDER BY timestamp ASC";
}

$result = $conn->query($sql);
$data = [
    'labels' => [],
    'kelembaban_tanah' => [],
    'kelembaban_udara' => [],
    'suhu_udara' => [],
];

while ($row = $result->fetch_assoc()) {
    $data['labels'][] = $row['label'];
    $data['kelembaban_tanah'][] = (float) $row['kelembaban_tanah'];
    $data['kelembaban_udara'][] = (float) $row['kelembaban_udara'];
    $data['suhu_udara'][] = (float) $row['suhu_udara'];
}

echo json_encode($data);
