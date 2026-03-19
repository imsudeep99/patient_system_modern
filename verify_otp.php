<?php
require_once __DIR__ . '/config.php';

$error = '';
$success = '';

// ✅ Must come from forgot password
if (!isset($_SESSION['reset_email'])) {
    header("Location: forgot_password.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $otp = trim($_POST['otp'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm = $_POST['confirm_password'] ?? '';

    if (empty($otp) || empty($password) || empty($confirm)) {
        $error = "All fields are required!";
    } elseif ($password !== $confirm) {
        $error = "Passwords do not match!";
    } elseif (strlen($password) < 6) {
        $error = "Password must be at least 6 characters!";
    } else {

        $email = $_SESSION['reset_email'];

        // ✅ Fetch user with OTP + expiry + role
        $stmt = $pdo->prepare("SELECT otp, otp_expiry, role FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if (!$user) {
            $error = "User not found!";
        } 
        // ❌ Only admin allowed
        elseif (strtolower($user['role']) !== 'admin') {
            $error = "Only admin can reset password!";
        } 
        // ❌ OTP mismatch
        elseif ($user['otp'] != $otp) {
            $error = "Invalid OTP!";
        } 
        // ❌ OTP expired
        elseif (strtotime($user['otp_expiry']) < time()) {
            $error = "OTP expired! Please request again.";
        } 
        else {

            // ✅ Update password
            $hashed = password_hash($password, PASSWORD_DEFAULT);

            $stmt = $pdo->prepare("UPDATE users SET password = ?, otp = NULL, otp_expiry = NULL WHERE email = ?");
            $stmt->execute([$hashed, $email]);

            unset($_SESSION['reset_email']);

            $success = "Password updated successfully! Redirecting...";
            header("refresh:2;url=index.php");
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Reset Password - Patient System</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap + Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    <style>
    body {
        background: linear-gradient(135deg, #4facfe, #00f2fe);
        min-height: 100vh;
    }

    .card {
        border-radius: 20px;
    }

    .form-control {
        border-radius: 12px;
        font-size: 18px;
    }

    .otp-input {
        text-align: center;
        letter-spacing: 8px;
        font-size: 22px;
    }

    .btn {
        border-radius: 12px;
        font-weight: 600;
    }

    .logo {
        font-size: 40px;
        color: #0d6efd;
    }
    </style>
</head>

<body class="d-flex align-items-center justify-content-center">

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-5">

                <div class="card shadow-lg border-0 p-4">

                    <!-- Header -->
                    <div class="text-center mb-4">
                        <div class="logo mb-2">
                            <i class="bi bi-shield-lock"></i>
                        </div>
                        <h3 class="fw-bold">Reset Password</h3>
                        <p class="text-muted">Enter OTP & new password</p>
                    </div>

                    <!-- Messages -->
                    <?php if ($error): ?>
                    <div class="alert alert-danger text-center py-2">
                        <?php echo htmlspecialchars($error); ?>
                    </div>
                    <?php endif; ?>

                    <?php if ($success): ?>
                    <div class="alert alert-success text-center py-2">
                        <?php echo htmlspecialchars($success); ?>
                    </div>
                    <?php endif; ?>

                    <!-- FORM -->
                    <form method="post" action="">

                        <!-- OTP -->
                        <div class="mb-3">
                            <label class="form-label">Enter OTP</label>
                            <input type="text" name="otp" maxlength="6" class="form-control otp-input"
                                placeholder="••••••" required>
                        </div>

                        <!-- New Password -->
                        <div class="mb-3">
                            <label class="form-label">New Password</label>
                            <div class="input-group">
                                <input type="password" name="password" class="form-control" required>
                                <span class="input-group-text toggle-password">
                                    <i class="bi bi-eye-slash"></i>
                                </span>
                            </div>
                        </div>

                        <!-- Confirm Password -->
                        <div class="mb-3">
                            <label class="form-label">Confirm Password</label>
                            <div class="input-group">
                                <input type="password" name="confirm_password" class="form-control" required>
                                <span class="input-group-text toggle-password">
                                    <i class="bi bi-eye-slash"></i>
                                </span>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-success w-100">
                            <i class="bi bi-check-circle"></i> Update Password
                        </button>

                    </form>

                    <!-- Footer -->
                    <div class="text-center mt-3">
                        <a href="forgot_password.php" class="text-decoration-none">
                            <i class="bi bi-arrow-left"></i> Back
                        </a>
                    </div>

                </div>

                <p class="text-center text-white mt-3 small">
                    &copy; <?php echo date('Y'); ?> Patient System
                </p>

            </div>
        </div>
    </div>

    <script>
    document.querySelectorAll('.toggle-password').forEach(function(el) {
        el.addEventListener('click', function() {
            let input = this.previousElementSibling;
            let icon = this.querySelector('i');

            if (input.type === "password") {
                input.type = "text";
                icon.classList.replace('bi-eye-slash', 'bi-eye');
            } else {
                input.type = "password";
                icon.classList.replace('bi-eye', 'bi-eye-slash');
            }
        });
    });
    </script>

</body>

</html>