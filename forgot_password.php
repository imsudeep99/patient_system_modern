<?php
require_once __DIR__ . '/config.php';

$message = $_SESSION['message'] ?? '';
unset($_SESSION['message']);
?>

<!DOCTYPE html>
<html>

<head>
    <title>Forgot Password</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

    <div class="container mt-5">
        <div class="col-md-4 mx-auto">
            <div class="card p-4 shadow">
                <h4 class="text-center mb-3">Forgot Password</h4>

                <?php if ($message): ?>
                <div class="alert alert-info"><?php echo $message; ?></div>
                <?php endif; ?>

                <form method="post" action="/patient_system_modern/controllers/forgot_password.php">
                    <div class="mb-3">
                        <label>Email</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>

                    <button type="submit" class="btn btn-primary w-100">
                        Send Reset Link
                    </button>
                </form>

                <div class="text-center mt-3">
                    <a href="index.php">Back to Login</a>
                </div>
            </div>
        </div>
    </div>

</body>

</html>