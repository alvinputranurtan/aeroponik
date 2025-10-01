<?php

header('Content-Type: application/json');
include '../functions/config.php';

$result = $conn->query('SELECT active_duration, inactive_duration 
                        FROM pump_duration 
                        ORDER BY updated_at DESC 
                        LIMIT 1');

if ($result->num_rows > 0) {
    echo json_encode($result->fetch_assoc());
} else {
    echo json_encode([
        'active_duration' => 0,
        'inactive_duration' => 0,
    ]);
}
