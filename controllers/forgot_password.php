<?php
require_once __DIR__ . '/../config.php';

// PHPMailer manual include
require __DIR__ . '/../PHPMailer/src/PHPMailer.php';
require __DIR__ . '/../PHPMailer/src/SMTP.php';
require __DIR__ . '/../PHPMailer/src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $email = trim($_POST['email']);

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    // ❌ Only admin
    if (!$user || strtolower($user['role']) !== 'admin') {
        $_SESSION['error'] = "Only admin can reset password!";
        header("Location: ../forgot_password.php");
        exit;
    }

    // OTP
    $otp = rand(100000, 999999);
    $expiry = date("Y-m-d H:i:s", strtotime("+10 minutes"));

    $stmt = $pdo->prepare("UPDATE users SET otp=?, otp_expiry=? WHERE id=?");
    $stmt->execute([$otp, $expiry, $user['id']]);

    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'sudeeppandey61@gmail.com';

        // 🔥 IMPORTANT: Use Gmail App Password (not normal password)
        $mail->Password = 'kqwc egsq xmxu odif';  

        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        $mail->setFrom('sudeeppandey61@gmail.com', 'Patient System');
        $mail->addAddress($email);

        $mail->isHTML(true);
        $mail->Subject = 'Password Reset OTP';
        $mail->Body = "<h2>Your OTP is: $otp</h2><p>Valid for 10 minutes</p>";

        $mail->send();

        $_SESSION['success'] = "OTP sent successfully!";
        $_SESSION['reset_email'] = $email;

        header("Location: ../verify_otp.php");
        exit;

    } catch (Exception $e) {
        $_SESSION['error'] = "Mailer Error: " . $mail->ErrorInfo;
        header("Location: ../forgot_password.php");
        exit;
    }
}