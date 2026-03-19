<?php
require_once __DIR__ . '/../config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $email = $_SESSION['reset_email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $stmt = $pdo->prepare("UPDATE users SET password = ?, otp = NULL, otp_expiry = NULL WHERE email = ?");
    $stmt->execute([$password, $email]);

    session_destroy();

    $_SESSION['success'] = "Password updated successfully!";
    header("Location: ../index.php");
    exit;
}