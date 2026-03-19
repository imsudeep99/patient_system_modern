<?php
require_once __DIR__ . '/../config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $email = $_SESSION['reset_email'];
    $otp = $_POST['otp'];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if (!$user || $user['otp'] != $otp) {
        $_SESSION['error'] = "Invalid OTP!";
        header("Location: ../verify_otp.php");
        exit;
    }

    if (strtotime($user['otp_expiry']) < time()) {
        $_SESSION['error'] = "OTP expired!";
        header("Location: ../verify_otp.php");
        exit;
    }

    $_SESSION['otp_verified'] = true;

    header("Location: ../reset_password.php");
    exit;
}