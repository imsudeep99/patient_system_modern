<style>
.btn-primary:hover {
    background-color: #013063ff !important;
    /* your hover bg color */
    border-color: #004a99 !important;
    /* optional border color */
}
</style>

<!-- <?php if (isset($_GET['deleted'])): ?>
    <div class="alert alert-success">
        Patient deleted successfully.
    </div>

    <script>
        setTimeout(() => {
            document.querySelector('.alert').style.display = 'none';
        }, 2000);
    </script>
<?php endif; ?> -->
<?php
require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../helpers.php';

require_login();

$page_title = 'Patients';
$active = 'patients';

/*
   FETCH REFERRERS FOR FILTER
 */
$referrerList = $pdo->query("SELECT id, name, type FROM referrers ORDER BY name")->fetchAll();

/*
   APPLY FILTERS
 */
$where = [];
$params = [];

// Search (name/contact)
if (!empty($_GET['search'])) {
    $where[] = "(p.name LIKE :search1 OR p.contact LIKE :search2)";
    $params[':search1'] = "%".$_GET['search']."%";
    $params[':search2'] = "%".$_GET['search']."%";
}

// Date Filter
if (!empty($_GET['date'])) {
    $where[] = "DATE(p.created_at) = :date";
    $params[':date'] = $_GET['date'];
}

// Referrer Filter
if (!empty($_GET['referrer'])) {
    $where[] = "p.referred_by_id = :ref";
    $params[':ref'] = $_GET['referrer'];
}

$sql = "SELECT p.*, r.name AS referrer_name, r.type AS referrer_type 
        FROM patients p 
        LEFT JOIN referrers r ON p.referred_by_id = r.id";

if ($where) {
    $sql .= " WHERE ".implode(" AND ", $where);
}

$sql .= " ORDER BY p.created_at DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$patients = $stmt->fetchAll();


/*
   TABLE ROWS
 */
$rows = '';
foreach ($patients as $p) {

    $ref = $p['referrer_name']
        ? $p['referrer_name'].' ('.strtoupper($p['referrer_type']).')'
        : '-';

    $rows .= '<tr>
        <td>'.htmlspecialchars($p['name']).'</td>
        <td>'.htmlspecialchars($p['age']).'</td>
        <td>'.htmlspecialchars($p['gender']).'</td>
        <td>'.htmlspecialchars($p['contact']).'</td>
        <td>'.htmlspecialchars($ref).'</td>
        <td>'.htmlspecialchars($p['fees']).'</td>
        <td>'.htmlspecialchars(date("d-m-Y", strtotime($p["created_at"]))).'</td>
        <td>
            <a href="/patient_system_modern/views/patients/view_patient.php?id='.$p['id'].'" 
               class="btn btn-sm btn-primary">View</a>

            <a href="/patient_system_modern/views/patients/edit_patient.php?id='.$p['id'].'" 
               class="btn btn-sm btn-warning">Edit</a>

            <a href="/patient_system_modern/views/patients/delete_patient.php?id='.$p['id'].'" 
               class="btn btn-sm btn-danger"
               onclick="return confirm(\'Are you sure?\');">
               Delete
            </a>
        </td>
    </tr>';
}


/*
   BUILD FILTER VALUES & OPTIONS
 */
$searchValue = htmlspecialchars($_GET['search'] ?? '', ENT_QUOTES, 'UTF-8');
$dateValue   = htmlspecialchars($_GET['date'] ?? '', ENT_QUOTES, 'UTF-8');

$referrerOptions = '<option value="">All Referrers</option>';
$currentRef = $_GET['referrer'] ?? '';

foreach ($referrerList as $r) {
    $id       = (int)$r['id'];
    $selected = ($currentRef !== '' && (string)$currentRef === (string)$id) ? 'selected' : '';
    $name     = htmlspecialchars($r['name'], ENT_QUOTES, 'UTF-8');
    $type     = strtoupper($r['type']);

    $referrerOptions .= "<option value=\"{$id}\" {$selected}>{$name} ({$type})</option>";
}

/*
   PAGE CONTENT (HEREDOC)
 */
$content = <<<HTML
<div class="d-flex justify-content-between align-items-center mb-3">
    
    <h6 class="mb-0">Patient List</h6>

    <div class="d-flex gap-2">
        <a href="/patient_system_modern/views/patients/add.php" 
           class="btn btn-primary btn-sm">
            <i class="bi bi-plus"></i> Add Patient
        </a>

        <a href="/patient_system_modern/controllers/export_patients.php" 
          
           class="btn btn-primary btn-sm">
            Download CSV
        </a>
    </div>

</div>


<!-- <div class="d-flex justify-content-between align-items-center mb-3">
    <h3>Patients</h3>

    <a href="/patient_system_modern/controllers/export_patients.php" 
       class="btn btn-sm btn-outline-secondary">
        Download CSV
    </a>
</div> -->


<form method="GET" class="card p-3 mb-3">
    <div class="row g-2">

        <div class="col-md-4">
            <input type="text" name="search" class="form-control"
                placeholder="Search by Name or Contact"
                value="{$searchValue}">
        </div>

        <div class="col-md-3">
            <input type="date" name="date" class="form-control"
                value="{$dateValue}">
        </div>

        <div class="col-md-3">
            <select name="referrer" class="form-control">
                {$referrerOptions}
            </select>
        </div>

        <!-- <div class="col-md-2">
            <button class="btn btn-primary w-100">Filter</button>
            <a href="index.php" class="btn btn-outline-secondary"><i class="fas fa-redo me-1"></i> Reset</a>
        </div> -->
            <div class="col-md-2 d-flex align-items-end">
                <button type="submit" class="btn btn-primary me-2"><i class="fas fa-search me-1"></i> Filter</button>
                <a href="/patient_system_modern/views/patients/index.php" class="btn btn-outline-secondary"><i class="fas fa-redo me-1"></i> Reset</a>
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