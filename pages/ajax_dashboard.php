<?php

date_default_timezone_set('Asia/Jakarta');
include __DIR__.'/../functions/config.php';

header('Content-Type: application/json');

$sql = 'SELECT * FROM data ORDER BY id DESC LIMIT 1';
$result = $conn->query($sql);
$row = $result->fetch_assoc();

$status_perangkat = 'Offline';
if ($row) {
    $last_time = strtotime($row['timestamp']);
    if ($last_time !== false && (time() - $last_time) <= 30) {
        $status_perangkat = 'Online';
    }
}

echo json_encode([
    'status_perangkat' => $status_perangkat,
    'status_pompa' => ($row['status_pompa'] == 1) ? 'Hidup' : 'Mati',
    'kelembaban_tanah' => $row['kelembaban_tanah'],
    'kelembaban_udara' => $row['kelembaban_udara'],
    'suhu_udara' => $row['suhu_udara'],
    'tombol_fisik_penyiraman_otomatis' => ($row['tombol_fisik_penyiraman_otomatis'] == 1) ? 'Hidup' : 'Mati',
    'tombol_fisik_menyalakan_pompa' => ($row['tombol_fisik_menyalakan_pompa'] == 1) ? 'Hidup' : 'Mati',
]);
