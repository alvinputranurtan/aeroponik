<?php

// Set timezone untuk Jakarta
date_default_timezone_set('Asia/Jakarta');

header('Content-Type: application/json');
require_once 'config.php';

if (isset($_POST['active_duration']) && isset($_POST['inactive_duration'])) {
    $active = (int) $_POST['active_duration'];
    $inactive = (int) $_POST['inactive_duration'];

    // Validasi input
    if ($active < 1 || $active > 60 || $inactive < 1 || $inactive > 60) {
        echo json_encode(['success' => false, 'message' => 'Durasi harus antara 1-60 menit']);
        exit;
    }

    // Set MySQL timezone ke Asia/Jakarta
    $conn->query("SET time_zone = '+07:00'");

    $sql = 'UPDATE pump_duration SET 
            active_duration = ?, 
            inactive_duration = ?,
            updated_at = NOW()
            WHERE id = 1';

    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ii', $active, $inactive);
    $success = $stmt->execute();

    echo json_encode(['success' => $success]);
}
