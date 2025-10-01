<?php

// Set timezone untuk Jakarta
date_default_timezone_set('Asia/Jakarta');

header('Content-Type: application/json');
require_once 'config.php';

if (isset($_POST['status'])) {
    $status = $_POST['status'];

    // Validasi status
    if (!in_array($status, ['ON', 'OFF'])) {
        echo json_encode(['success' => false, 'message' => 'Status tidak valid']);
        exit;
    }

    // Gunakan NOW() dengan timezone yang sudah diset
    $sql = 'UPDATE pump_status SET 
            status = ?, 
            updated_at = NOW()
            WHERE id = 1';

    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $status);
    $success = $stmt->execute();

    echo json_encode(['success' => $success]);
}
