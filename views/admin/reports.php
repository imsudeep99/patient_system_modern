<?php
require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../helpers.php';

require_login('admin');

$page_title = 'Reports';
$active = 'reports';

/**
 *  FILTER LOGIC
 */
if (isset($_GET['reset'])) {
    $from = '';
    $to   = '';
} elseif (isset($_GET['from']) || isset($_GET['to'])) {
    $from = $_GET['from'] ?? '';
    $to   = $_GET['to'] ?? '';
} else {
    // first load
    $from = date('Y-m-01');
    $to   = date('Y-m-d');
}

/**
 * ✅ WHERE CONDITIONS (FIXED 🔥)
 */
$wherePatients = '';
$whereJoin = '';
$params = [];

if (!empty($from) && !empty($to)) {
    $wherePatients = "WHERE DATE(created_at) BETWEEN ? AND ?";
    $whereJoin     = "WHERE DATE(p.created_at) BETWEEN ? AND ?";
    $params = [$from, $to];
}

/**
 * ✅ TOTAL PATIENTS
 */
$stmt = $pdo->prepare("SELECT COUNT(*) AS total FROM patients $wherePatients");
$stmt->execute($params);
$totalPatients = $stmt->fetch()['total'] ?? 0;

/**
 *  DOCTOR REPORT 
 */
$byDoctor = $pdo->prepare("
    SELECT r.name, r.type, COUNT(p.id) AS c 
    FROM patients p 
    JOIN referrers r ON p.referred_by_id = r.id
    $whereJoin
    GROUP BY r.id
    ORDER BY c DESC
");
$byDoctor->execute($params);
$rowsData = $byDoctor->fetchAll();

/**
 *  TABLE ROWS
 */
$rows = '';
foreach ($rowsData as $r) {
    $rows .= '<tr>
        <td>'.htmlspecialchars($r['name']).'</td>
        <td>'.htmlspecialchars(strtoupper($r['type'])).'</td>
        <td>'.htmlspecialchars($r['c']).'</td>
    </tr>';
}

if (empty($rows)) {
    $rows = '<tr><td colspan="3" class="text-center text-muted">No data found</td></tr>';
}

/**
 *  UI
 */
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

                <a href="/patient_system_modern/views/admin/reports.php?reset=1" 
                   class="btn btn-secondary mt-3">
                   <i class="fas fa-redo me-1"></i> Reset
                </a>
            </div>

            <div class="col-md-3 text-md-end">
                <p class="mb-0 mt-3">
                    <strong>Total Patients:</strong> {$totalPatients}
                </p>
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