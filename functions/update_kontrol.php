<?php

header('Content-Type: application/json');
include 'config.php';

if (isset($_POST['mode_otomatis'])) {
    $status = $_POST['mode_otomatis'];
    $sql = "UPDATE kontrol_data SET mode_otomatis = $status WHERE id = 1";
    $success = $conn->query($sql);
    echo json_encode(['success' => $success]);
}

if (isset($_POST['status_pompa'])) {
    $status = $_POST['status_pompa'];
    $sql = "UPDATE kontrol_data SET status_pompa = $status WHERE id = 1";
    $success = $conn->query($sql);
    echo json_encode(['success' => $success]);
}

if (isset($_POST['sistem_penyiraman'])) {
    $status = $_POST['sistem_penyiraman'];
    $sql = "UPDATE kontrol_realtime SET sistem_penyiraman = $status";
    $success = $conn->query($sql);
    echo json_encode(['success' => $success]);
}

if (isset($_POST['tekan_untuk_hidupkan_sprayer'])) {
    $status = $_POST['tekan_untuk_hidupkan_sprayer'];
    $sql = "UPDATE kontrol_realtime SET tekan_untuk_hidupkan_sprayer = $status";
    $success = $conn->query($sql);
    echo json_encode(['success' => $success]);
}
