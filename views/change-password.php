<?php
require_once __DIR__ . '/../config.php';

// 🔐 Login check
if (!isset($_SESSION['user'])) {
    header('Location: /patient_system_modern/index.php');
    exit;
}

// 🔒 Admin only access
if (!isset($_SESSION['user']['role']) || strtolower($_SESSION['user']['role']) !== 'admin') {
    $_SESSION['error'] = "Access denied! Only admin allowed.";
    header('Location: /patient_system_modern/dashboard.php');
    exit;
}

// ✅ Fetch all users
$stmt = $pdo->query("SELECT id, name, email FROM users ORDER BY name ASC");
$users = $stmt->fetchAll();

// messages
$error = $_SESSION['error'] ?? '';
$success = $_SESSION['success'] ?? '';

unset($_SESSION['error'], $_SESSION['success']);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Change Password</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <style>
    body {
        background: #f5f7fa;
    }

    .card {
        border-radius: 12px;
    }

    .form-control {
        border-radius: 8px;
    }

    .btn-save {
        background-color: #4CAF50;
        color: #fff;
        border-radius: 8px;
    }

    .btn-save:hover {
        background-color: #45a049;
    }

    .toggle-password {
        position: absolute;
        right: 15px;
        top: 38px;
        cursor: pointer;
        color: #6c757d;
    }

    .form-control.pe-5 {
        padding-right: 40px;
    }
    </style>
</head>

<body>

    <div class="container d-flex justify-content-center align-items-center vh-100">
        <div class="card shadow p-4" style="width: 420px;">

            <h3 class="text-center mb-4">🔐 Change User Password (Admin)</h3>

            <!-- Alerts -->
            <?php if ($error): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>

            <?php if ($success): ?>
            <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
            <?php endif; ?>

            <form method="post" action="/patient_system_modern/controllers/change_password.php">

                <!-- 👇 USER SELECT -->
                <div class="mb-3">
                    <label class="form-label">Select User</label>
                    <select name="user_id" class="form-control" required>
                        <option value="">-- Select User --</option>
                        <?php foreach ($users as $u): ?>
                        <option value="<?= $u['id'] ?>">
                            <?= htmlspecialchars($u['name']) ?> (<?= htmlspecialchars($u['email']) ?>)
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- New Password -->
                <div class="mb-3 position-relative">
                    <label class="form-label">New Password</label>
                    <input type="password" id="new_password" name="new_password" class="form-control pe-5" required>
                    <i class="fa-solid fa-eye-slash toggle-password" onclick="togglePassword('new_password', this)"></i>
                </div>

                <!-- Confirm Password -->
                <div class="mb-3 position-relative">
                    <label class="form-label">Confirm Password</label>
                    <input type="password" id="confirm_password" name="confirm_password" class="form-control pe-5"
                        required>
                    <i class="fa-solid fa-eye-slash toggle-password"
                        onclick="togglePassword('confirm_password', this)"></i>
                </div>

                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-save">Update Password</button>
                    <a href="/patient_system_modern/dashboard.php" class="btn btn-outline-secondary">Cancel</a>
                </div>

            </form>
        </div>
    </div>

    <script>
    function togglePassword(fieldId, icon) {
        const input = document.getElementById(fieldId);

        if (input.type === "password") {
            input.type = "text";
            icon.classList.replace("fa-eye-slash", "fa-eye");
        } else {
            input.type = "password";
            icon.classList.replace("fa-eye", "fa-eye-slash");
        }
    }
    </script>

</body>

</html>