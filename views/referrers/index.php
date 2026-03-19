<?php
require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../helpers.php';

require_login();

$page_title = 'Doctors / ASHA';
$active = 'referrers';

// Logged-in user
$user = $_SESSION['user'];

// Fetch referrers
$referrers = $pdo->query('SELECT * FROM referrers ORDER BY type, name')->fetchAll();

$rows = '';

foreach ($referrers as $r) {

    //  Permission Logic
    $canEdit = false;
    $canDelete = false;

    if (strtolower($user['role']) === 'admin') {
        $canEdit = true;
        $canDelete = true;
    } else {
        // Check 48 hours
        $createdTime = strtotime($r['created_at']);
        $diffHours = (time() - $createdTime) / 3600;

        if ($diffHours <= 48) {
            $canEdit = true;
            $canDelete = true;
        }
    }

    //  Edit Button
    $editBtn = $canEdit
        ? '<a href="/patient_system_modern/views/referrers/edit_doctors.php?id='.$r['id'].'" 
            class="btn btn-sm btn-warning">Edit</a>'
        : '<button class="btn btn-sm btn-secondary" disabled title="Only allowed within 48 hours">Edit</button>';

    //  Delete Button
    $deleteBtn = $canDelete
        ? '<a href="/patient_system_modern/views/referrers/delete_doctors.php?id='.$r['id'].'" 
            class="btn btn-sm btn-danger"
            onclick="return confirm(\'Are you sure?\');">Delete</a>'
        : '<button class="btn btn-sm btn-secondary" disabled title="Only allowed within 48 hours">Delete</button>';

    $rows .= '<tr>
        <td>'.htmlspecialchars($r['id']).'</td>
        <td>'.htmlspecialchars($r['name']).'</td>
        <td>'.htmlspecialchars(strtoupper($r['type'])).'</td>
        <td>'.htmlspecialchars($r['phone']).'</td>
        <td>'.htmlspecialchars($r['hospital_clinic']).'</td>
        <td>'.htmlspecialchars($r['address']).'</td>
        <td>
            <a href="/patient_system_modern/views/referrers/view_doctors.php?id='.$r['id'].'" 
               class="btn btn-sm btn-primary">View</a>

            '.$editBtn.'
            '.$deleteBtn.'
        </td>
    </tr>';
}

$content = <<<HTML
<div class="d-flex justify-content-between align-items-center mb-3">
    <h6 class="mb-0">Doctors / ASHA List</h6>
    <a href="/patient_system_modern/views/referrers/add.php" class="btn btn-primary btn-sm">
        <i class="bi bi-plus"></i> Add
    </a>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-sm align-middle">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Type</th>
                        <th>Phone</th>
                        <th>Hospital / Clinic</th>
                        <th>Address</th>
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