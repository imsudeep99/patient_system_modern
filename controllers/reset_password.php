<?php
require_once __DIR__ . '/../config.php';
// require_once __DIR__ . '/../db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $token = $_POST['token'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $stmt = $pdo->prepare("UPDATE users SET password=?, reset_token=NULL WHERE reset_token=?");
    $stmt->execute([$password, $token]);

    $_SESSION['success'] = "Password updated successfully!";
    header("Location: ../index.php");
}