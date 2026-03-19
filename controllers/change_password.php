<?php
require_once __DIR__ . '/../config.php';

// 🔐 Login check
if (!isset($_SESSION['user'])) {
    header('Location: /patient_system_modern/index.php');
    exit;
}

// 🔒 Admin only
if (!isset($_SESSION['user']['role']) || strtolower($_SESSION['user']['role']) !== 'admin') {
    $_SESSION['error'] = "Unauthorized!";
    header('Location: /patient_system_modern/dashboard.php');
    exit;
}

// get data
$user_id = $_POST['user_id'] ?? null;
$new = $_POST['new_password'] ?? '';
$confirm = $_POST['confirm_password'] ?? '';

// validation
if (!$user_id) {
    $_SESSION['error'] = "Please select user";
    header('Location: /patient_system_modern/views/change-password.php');
    exit;
}

if ($new !== $confirm) {
    $_SESSION['error'] = "Passwords do not match";
    header('Location: /patient_system_modern/views/change-password.php');
    exit;
}

// update password
$newHash = password_hash($new, PASSWORD_DEFAULT);

$stmt = $pdo->prepare("UPDATE users SET password=? WHERE id=?");
$stmt->execute([$newHash, $user_id]);

$_SESSION['success'] = "Password updated successfully!";
header('Location: /patient_system_modern/index.php');
exit;