<?php
require_once __DIR__ . '/../config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $email = trim($_POST['email']);

    // check user
    $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user) {

        // generate secure token
        $token = bin2hex(random_bytes(32));

        // expiry (1 hour)
        $expiry = date("Y-m-d H:i:s", strtotime("+1 hour"));

        // store token + expiry
        $stmt = $pdo->prepare("UPDATE users SET reset_token=?, token_expiry=? WHERE email=?");
        $stmt->execute([$token, $expiry, $email]);

        // reset link
        $link = "http://localhost/patient_system_modern/reset_password.php?token=$token";

        // TEMP: show link (for testing)
        $_SESSION['message'] = "Reset link: <a href='$link'>$link</a>";

        // 👉 PRODUCTION: send email instead

    } else {
        $_SESSION['message'] = "If email exists, reset link sent."; 
        // (security: don’t reveal user existence)
    }

    header("Location: ../forgot_password.php");
    exit;
}