<?php
require_once __DIR__ . '/config.php';

$token = $_GET['token'] ?? '';

$stmt = $pdo->prepare("SELECT * FROM users WHERE reset_token=?");
$stmt->execute([$token]);
$user = $stmt->fetch();

if (!$user) {
    die("Invalid or expired token");
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Reset Password</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
    body {
        background: linear-gradient(135deg, #4facfe, #00f2fe);
        height: 100vh;
    }

    .card {
        border-radius: 15px;
    }

    .form-control {
        border-radius: 10px;
    }

    .btn {
        border-radius: 10px;
    }

    .title {
        font-weight: bold;
        color: #333;
    }
    </style>
</head>

<body class="d-flex align-items-center justify-content-center">

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-4">

                <div class="card shadow p-4">
                    <h3 class="text-center mb-4 title">Reset Password</h3>

                    <form method="post" action="/patient_system_modern/controllers/reset_password.php">
                        <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>">

                        <div class="mb-3">
                            <label class="form-label">New Password</label>
                            <input type="password" name="password" class="form-control" placeholder="Enter new password"
                                required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Confirm Password</label>
                            <input type="password" name="confirm_password" class="form-control"
                                placeholder="Confirm password" required>
                        </div>

                        <button class="btn btn-primary w-100">Reset Password</button>
                    </form>

                    <div class="text-center mt-3">
                        <a href="/patient_system_modern/index.php" class="text-decoration-none">Back to Login</a>
                    </div>
                </div>

            </div>
        </div>
    </div>

</body>

</html>