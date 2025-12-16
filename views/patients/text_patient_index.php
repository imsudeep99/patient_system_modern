<style>
.btn-primary:hover {
    background-color: #013063ff !important;
    border-color: #004a99 !important;
}
</style>

<?php
require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../helpers.php';

require_login();

$page_title = 'Patients';
$active = 'patients';

/* ==========================
   USER ROLE
========================== */
$userRole = $_SESSION['user']['role'] ?? 'employee';

/* ==========================
   FETCH REFERRERS
========================== */
$referrerList = $pdo
    ->query("SELECT id, name, type FROM referrers ORDER BY name")
    ->fetchAll();

/* ==========================
   FILTER CONDITIONS
========================== */
$where  = [];
$params = [];

/* 🔐 EMPLOYEE → last 7 days only */
if ($userRole === 'employee') {
    $where[] = "p.created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)";
}

/* Search */
if (!empty($_GET['search'])) {
    $where[] = "(p.name LIKE :search1 OR p.contact LIKE :search2)";
    $params[':search1'] = "%{$_GET['search']}%";
    $params[':search2'] = "%{$_GET['search']}%";
}

/* Date filter */
if (!empty($_GET['date'])) {
    $where[] = "DATE(p.created_at) = :date";
    $params[':date'] = $_GET['date'];
}

/* Referrer filter */
if (!empty($_GET['referrer'])) {
    $where[] = "p.referred_by_id = :ref";
    $params[':ref'] = $_GET['referrer'];
}

/* ==========================
   MAIN QUERY
========================== */
$sql = "SELECT p.*, r.name AS referrer_name, r.type AS referrer_type
        FROM patients p
        LEFT JOIN referrers r ON p.referred_by_id = r.id";

if ($where) {
    $sql .= " WHERE " . implode(" AND ", $where);
}

$sql .= " ORDER BY p.created_at DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$patients = $stmt->fetchAll();

/* ==========================
   TABLE ROWS
========================== */
$rows = '';

foreach ($patients as $p) {

    $ref = $p['referrer_name']
        ? htmlspecialchars($p['referrer_name']) . ' (' . strtoupper($p['referrer_type']) . ')'
        : '-';

    $rows .= '
    <tr>
        <td>' . htmlspecialchars($p['name']) . '</td>
        <td>' . htmlspecialchars($p['age']) . '</td>
        <td>' . htmlspecialchars($p['gender']) . '</td>
        <td>' . htmlspecialchars($p['contact']) . '</td>
        <td>' . $ref . '</td>
        <td>' . htmlspecialchars($p['fees']) . '</td>
        <td>' . date("d-m-Y", strtotime($p["created_at"])) . '</td>
        <td>
            <a href="/patient_system_modern/views/patients/view_patient.php?id=' . $p['id'] . '" 
               class="btn btn-sm btn-primary">View</a>

            <a href="/patient_system_modern/views/patients/edit_patient.php?id=' . $p['id'] . '" 
               class="btn btn-sm btn-warning">Edit</a>

            <a href="/patient_system_modern/views/patients/delete_patient.php?id=' . $p['id'] . '" 
               class="btn btn-sm btn-danger"
               onclick="return confirm(\'Are you sure?\');">
               Delete
            </a>
        </td>
    </tr>';
}

/* ==========================
   FILTER UI VALUES
========================== */
$searchValue = htmlspecialchars($_GET['search'] ?? '', ENT_QUOTES);
$dateValue   = htmlspecialchars($_GET['date'] ?? '', ENT_QUOTES);

$referrerOptions = '<option value="">All Referrers</option>';
$currentRef = $_GET['referrer'] ?? '';

foreach ($referrerList as $r) {
    $selected = ($currentRef == $r['id']) ? 'selected' : '';
    $referrerOptions .= "<option value=\"{$r['id']}\" {$selected}>
        " . htmlspecialchars($r['name']) . " (" . strtoupper($r['type']) . ")
    </option>";
}

/* ==========================
   PAGE CONTENT
========================== */
$content = <<<HTML
<div class="d-flex justify-content-between align-items-center mb-3">
    <h6 class="mb-0">Patient List</h6>

    <div class="d-flex gap-2">
        <a href="/patient_system_modern/views/patients/add.php" class="btn btn-primary btn-sm">
            Add Patient
        </a>

        <a href="/patient_system_modern/controllers/export_patients.php" class="btn btn-primary btn-sm">
            Download CSV
        </a>
    </div>
</div>

HTML;

if ($userRole === 'employee') {
    $content .= '<div class="alert alert-info">
        Showing patients from the last 7 days only
    </div>';
}

$content .= <<<HTML
<form method="GET" class="card p-3 mb-3">
    <div class="row g-2">
        <div class="col-md-4">
            <input type="text" name="search" class="form-control"
                   placeholder="Search by Name or Contact"
                   value="{$searchValue}">
        </div>

        <div class="col-md-3">
            <input type="date" name="date" class="form-control" value="{$dateValue}">
        </div>

        <div class="col-md-3">
            <select name="referrer" class="form-control">
                {$referrerOptions}
            </select>
        </div>

        <div class="col-md-2">
            <button class="btn btn-primary w-100">Filter</button>
        </div>
    </div>
</form>

<div class="card shadow-sm">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-sm align-middle">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Age</th>
                        <th>Gender</th>
                        <th>Contact</th>
                        <th>Referred By</th>
                        <th>Fees</th>
                        <th>Date</th>
                        <th>Action</th>
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
