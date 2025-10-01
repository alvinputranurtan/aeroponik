<?php
include 'config.php';
include 'functions.php';
include 'csrf.php';

// Proteksi brute force sederhana
if (!isset($_SESSION['login_attempt'])) {
    $_SESSION['login_attempt'] = 0;
    $_SESSION['last_attempt_time'] = time();
}
if (time() - $_SESSION['last_attempt_time'] > 900) {
    $_SESSION['login_attempt'] = 0;
}

if ($_SESSION['login_attempt'] >= 5) {
    $_SESSION['error'] = "Terlalu banyak percobaan. Coba 15 menit lagi.";
    log_activity("Brute force block for IP: {$_SERVER['REMOTE_ADDR']}");
    header("Location: login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (!verify_csrf_token($_POST['csrf_token'])) {
        $_SESSION['error'] = "CSRF token tidak valid.";
        log_activity("CSRF attack attempt.");
        header("Location: login.php");
        exit;
    }

    $username = clean_input($_POST['username']);
    $password = $_POST['password'];

    if (strlen($username) > 50 || strlen($password) > 255) {
        $_SESSION['error'] = "Input tidak valid.";
        header("Location: login.php");
        exit;
    }

    $stmt = $conn->prepare("SELECT id, username, password FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows === 1) {
        $stmt->bind_result($id, $uname, $hashed_password);
        $stmt->fetch();

        if (password_verify($password, $hashed_password)) {
            session_regenerate_id(true);
            $_SESSION['user_id'] = $id;
            $_SESSION['username'] = htmlspecialchars($uname);
            $_SESSION['login_attempt'] = 0;
            log_activity("User $uname login sukses.");
            header("Location: ../index.php");
            exit;
        }
    }

    // Jika gagal
    $_SESSION['login_attempt']++;
    $_SESSION['last_attempt_time'] = time();
    $_SESSION['error'] = "Username atau password salah.";
    log_activity("Failed login for username: $username, IP: {$_SERVER['REMOTE_ADDR']}");
    header("Location: login.php");
    exit;
}
?>