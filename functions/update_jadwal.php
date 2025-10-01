<?php

header('Content-Type: application/json');
include 'config.php';

// Hapus semua jadwal yang ada
$conn->query('DELETE FROM kontrol_data');

// Insert jadwal baru
if (isset($_POST['mulai']) && isset($_POST['menit'])) {
    $mulai = $_POST['mulai'];
    $menit = $_POST['menit'];

    $success = true;

    for ($i = 0; $i < count($mulai); ++$i) {
        if (!empty($mulai[$i]) && !empty($menit[$i])) {
            $sql = "INSERT INTO kontrol_data (hidupkan_sprayer_pada_jam, hidupkan_sprayer_selama_menit) 
                    VALUES ('{$mulai[$i]}', {$menit[$i]})";
            if (!$conn->query($sql)) {
                $success = false;
                break;
            }
        }
    }

    echo json_encode(['success' => $success]);
}
