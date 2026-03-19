<?php
require_once __DIR__ . '/config.php';

$message = $_SESSION['message'] ?? '';
$error = $_SESSION['error'] ?? '';

unset($_SESSION['message'], $_SESSION['error']);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Forgot Password - Patient System</title>
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
    }

    .input-group-text {
        border-radius: 12px 0 0 12px;
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
                            <i class="bi bi-key"></i>
                        </div>
                        <h3 class="fw-bold">Forgot Password</h3>
                        <p class="text-muted">Enter your email to receive OTP</p>
                    </div>

                    <!-- Alerts -->
                    <?php if ($message): ?>
                    <div class="alert alert-success text-center py-2">
                        <?php echo htmlspecialchars($message); ?>
                    </div>
                    <?php endif; ?>

                    <?php if ($error): ?>
                    <div class="alert alert-danger text-center py-2">
                        <?php echo htmlspecialchars($error); ?>
                    </div>
                    <?php endif; ?>

                    <!-- Form -->
                    <form method="post" action="/patient_system_modern/controllers/forgot_password.php">

                        <div class="mb-3">
                            <label class="form-label">Email Address</label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="bi bi-envelope"></i>
                                </span>
                                <input type="email" name="email" class="form-control" placeholder="Enter your email"
                                    required>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary w-100">
                            <i class="bi bi-send"></i> Send OTP
                        </button>

                    </form>

                    <!-- Footer -->
                    <div class="text-center mt-3">
                        <a href="index.php" class="text-decoration-none">
                            <i class="bi bi-arrow-left"></i> Back to Login
                        </a>
                    </div>

                </div>

                <p class="text-center text-white mt-3 small">
                    &copy; <?php echo date('Y'); ?> Patient System
                </p>

            </div>
        </div>
    </div>

</body>

</html>