<?php
if (!isset($page_title)) { $page_title = 'Dashboard'; }
$user = $_SESSION['user'] ?? null;
$role = $user['role'] ?? '';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title><?php echo htmlspecialchars($page_title); ?> - Patient System</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="/patient_system_modern/assets/css/style.css" rel="stylesheet">
</head>

<body>
    <div class="d-flex wrapper">
        <nav class="sidebar">
            <div class="sidebar-header">
                <div class="logo-icon"><i class="bi bi-hospital"></i></div>
                <span class="logo-text">Patient System</span>
            </div>
            <ul class="nav flex-column mt-3">
                <li class="nav-item">
                    <a class="nav-link<?php echo ($active ?? '') === 'dashboard' ? ' active' : ''; ?>"
                        href="/patient_system_modern/dashboard.php">
                        <i class="bi bi-speedometer2"></i> Dashboard
                    </a>
                </li>
                <?php if ($role === 'admin'): ?>
                <li class="nav-item">
                    <a class="nav-link<?php echo ($active ?? '') === 'employees' ? ' active' : ''; ?>"
                        href="/patient_system_modern/views/admin/employees.php">
                        <i class="bi bi-people"></i> Employees
                    </a>
                </li>
                <?php endif; ?>
                <li class="nav-item">
                    <a class="nav-link<?php echo ($active ?? '') === 'patients' ? ' active' : ''; ?>"
                        href="/patient_system_modern/views/patients/index.php">
                        <i class="bi bi-person-lines-fill"></i> Patients
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link<?php echo ($active ?? '') === 'referrers' ? ' active' : ''; ?>"
                        href="/patient_system_modern/views/referrers/index.php">
                        <i class="bi bi-person-video2"></i> Doctors / ASHA
                    </a>
                </li>
                <?php if ($role === 'admin'): ?>
                <li class="nav-item">
                    <a class="nav-link<?php echo ($active ?? '') === 'reports' ? ' active' : ''; ?>"
                        href="/patient_system_modern/views/admin/reports.php">
                        <i class="bi bi-graph-up"></i> Reports
                    </a>
                </li>
                <?php endif; ?>
                <li class="nav-item mt-auto">
                    <a class="nav-link" href="/patient_system_modern/logout.php">
                        <i class="bi bi-box-arrow-right"></i> Logout
                    </a>
                </li>
            </ul>
        </nav>
        <div class="content flex-grow-1">
            <header class="topbar d-flex align-items-center justify-content-between">
                <h5 class="mb-0"><?php echo htmlspecialchars($page_title); ?></h5>

                <div class="d-flex align-items-center gap-2">
                    <span class="small text-muted">
                        <?php echo htmlspecialchars($user['name'] ?? ''); ?>
                        (<?php echo htmlspecialchars(strtoupper($role)); ?>)
                    </span>

                    <?php if (isset($_SESSION['user']['role']) && strtolower($_SESSION['user']['role']) === 'admin'): ?>
                    <a href="/patient_system_modern/views/change-password.php" class="btn btn-warning btn-sm">
                        <i class="bi bi-key"></i> Change Password
                    </a>
                    <?php endif; ?>

                    <a href="/patient_system_modern/logout.php" class="btn btn-sm btn-outline-secondary">
                        <i class="bi bi-box-arrow-right"></i> Logout
                    </a>
                </div>
            </header>
            <main class="p-4">
                <?php if (isset($content)) echo $content; ?>
            </main>
        </div>
    </div>
</body>

</html>