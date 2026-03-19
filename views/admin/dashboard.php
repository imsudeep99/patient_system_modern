<?php
require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../helpers.php';

require_login('admin');

$page_title = 'Admin Dashboard';
$active = 'dashboard';

// Simple stats
$totalPatients = $pdo->query('SELECT COUNT(*) AS c FROM patients')->fetch()['c'] ?? 0;
$totalReferrers = $pdo->query('SELECT COUNT(*) AS c FROM referrers')->fetch()['c'] ?? 0;
$totalEmployees = $pdo->query("SELECT COUNT(*) AS c FROM users WHERE role = 'employee'")->fetch()['c'] ?? 0;

$content = <<<HTML
<!-- <div class="d-flex justify-content-between align-items-center mb-3">
    <h3>Patients</h3>

    <a href="/patient_system_modern/controllers/export_patients.php" 
       class="btn btn-sm btn-outline-secondary">
        Download CSV
    </a>
</div> -->

<div class="row g-3">
    <div class="col-md-4">
        <div class="card stat-card">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <p class="text-muted mb-1 small">Total Patients</p>
                    <h3 class="mb-0">{$totalPatients}</h3>
                </div>
                <div class="stat-icon bg-primary-subtle">
                    <i class="bi bi-person-lines-fill"></i>
                    <!-- <a class="nav-link active" href="/patient_system_modern/views/patients/index.php">
                    <i class="bi bi-person-lines-fill"></i>  -->
                </a>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card stat-card">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <p class="text-muted mb-1 small">Doctors / ASHA</p>
                    <h3 class="mb-0">{$totalReferrers}</h3>
                </div>
                <div class="stat-icon bg-success-subtle">
                    <i class="bi bi-person-video2"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card stat-card">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <p class="text-muted mb-1 small">Employees</p>
                    <h3 class="mb-0">{$totalEmployees}</h3>
                </div>
                <div class="stat-icon bg-warning-subtle">
                    <i class="bi bi-people"></i>
                </div>
            </div>
        </div>
    </div>
</div>
HTML;

include __DIR__ . '/../layout/main.php';