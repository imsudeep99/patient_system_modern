<?php
require_once __DIR__ . '/config.php';

if (!isset($_SESSION['otp_verified'])) {
    header("Location: forgot_password.php");
    exit;
}
?>

<form method="post" action="controllers/reset_password.php">
    <input type="password" name="password" placeholder="New Password" required>
    <button type="submit">Reset Password</button>
</form>