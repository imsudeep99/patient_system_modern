<?php
require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../helpers.php';

require_login();

/*

| Fetch Referrer

*/
$id = (int)($_GET['id'] ?? 0);

if (!$id) {
    header('Location: /patient_system_modern/views/referrers/index.php');
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM referrers WHERE id = ?");
$stmt->execute([$id]);
$referrer = $stmt->fetch();

if (!$referrer) {
    $_SESSION['error'] = 'Referrer not found.';
    header('Location: /patient_system_modern/views/referrers/index.php');
    exit;
}

$page_title = 'View Referrer';
$active = 'referrers';

$content = <<<HTML
<div class="container-fluid">

    <!-- ===== BASIC INFORMATION ===== -->
    <div class="mb-4">
        <h5 class="border-start border-3 border-primary ps-2 mb-3">
            Basic Information
        </h5>

        <div class="row g-3">
            <div class="col-md-4">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <small class="text-muted">Type</small>
                        <h6 class="fw-bold mb-0 text-uppercase">
                            {$referrer['type']}
                        </h6>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <small class="text-muted">Name</small>
                        <h6 class="fw-bold mb-0">
                            {$referrer['name']}
                        </h6>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <small class="text-muted">Contact Number</small>
                        <h6 class="fw-bold mb-0">
                            {$referrer['phone']}
                        </h6>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ===== ADDITIONAL DETAILS ===== -->
    <div class="mb-4">
        <h5 class="border-start border-3 border-primary ps-2 mb-3">
            Additional Details
        </h5>

        <div class="row g-3">
            <div class="col-md-6">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <small class="text-muted">Hospital / Clinic</small>
                        <h6 class="fw-bold mb-0">
                            {$referrer['hospital_clinic']}
                        </h6>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <small class="text-muted">Address</small>
                        <h6 class="fw-bold mb-0">
                            {$referrer['address']}
                        </h6>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ===== METADATA ===== -->
    <div class="mb-4">
        <h5 class="border-start border-3 border-primary ps-2 mb-3">
            Metadata
        </h5>

        <div class="row g-3">
            <div class="col-md-4">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <small class="text-muted">Date Added</small>
                        <h6 class="fw-bold mb-0">
                            {$referrer['created_at']}
                        </h6>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ===== ACTION BUTTONS ===== -->
    <div class="d-flex justify-content-between mt-4">
        <a href="/patient_system_modern/views/referrers/index.php"
           class="btn btn-secondary">
            ← Back
        </a>

        <div>
            <a href="/patient_system_modern/views/referrers/edit_doctors.php?id={$referrer['id']}"
               class="btn btn-warning me-2">
                Edit
            </a>

            <a href="/patient_system_modern/views/referrers/delete_doctors.php?id={$referrer['id']}"
               class="btn btn-danger"
               onclick="return confirm('Are you sure you want to delete this referrer?');">
                Delete
            </a>
        </div>
    </div>

</div>
HTML;

include __DIR__ . '/../layout/main.php';
