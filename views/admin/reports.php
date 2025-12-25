<?php
require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../helpers.php';

require_login('admin');

$page_title = 'Reports';
$active = 'reports';

$from = $_GET['from'] ?? date('Y-m-01');
$to   = $_GET['to'] ?? date('Y-m-d');

$stmt = $pdo->prepare("SELECT COUNT(*) AS total FROM patients WHERE DATE(created_at) BETWEEN ? AND ?");
$stmt->execute([$from, $to]);
$totalPatients = $stmt->fetch()['total'] ?? 0;

$byDoctor = $pdo->prepare("SELECT r.name, r.type, COUNT(p.id) AS c 
    FROM patients p 
    JOIN referrers r ON p.referred_by_id = r.id
    WHERE DATE(p.created_at) BETWEEN ? AND ?
    GROUP BY r.id
    ORDER BY c DESC");
$byDoctor->execute([$from, $to]);
$rowsData = $byDoctor->fetchAll();

$rows = '';
foreach ($rowsData as $r) {
    $rows .= '<tr>
        <td>'.htmlspecialchars($r['name']).'</td>
        <td>'.htmlspecialchars(strtoupper($r['type'])).'</td>
        <td>'.htmlspecialchars($r['c']).'</td>
    </tr>';
}

$content = <<<HTML
<div class="card shadow-sm mb-3">
    <div class="card-body">
        <form method="get" class="row g-3 align-items-end">
            <div class="col-md-3">
                <label class="form-label">From Date</label>
                <input type="date" name="from" value="{$from}" class="form-control">
            </div>
            <div class="col-md-3">
                <label class="form-label">To Date</label>
                <input type="date" name="to" value="{$to}" class="form-control">
            </div>
            <div class="col-md-3">
                <button class="btn btn-primary mt-3" type="submit">
                    <i class="bi bi-funnel"></i> Apply Filters
                </button>
                <a href="/patient_system_modern/views/admin/reports.php" class="btn btn-primary mt-3"><i class="fas fa-redo me-1"></i> Reset</a>
            </div>

            <div class="col-md-3 text-md-end">
                <p class="mb-0 mt-3"><strong>Total Patients:</strong> {$totalPatients}</p>
            </div>
        </form>
    </div>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <h6 class="mb-3">Patients by Doctor / ASHA</h6>
        <div class="table-responsive">
            <table class="table table-sm align-middle">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Type</th>
                        <th>Total Patients</th>
                    </tr>
                </thead>
                <tbody>
                    {$rows}
                </tbody>
            </table>
        </div>
    </div>
</div>
HTML;

include __DIR__ . '/../layout/main.php';