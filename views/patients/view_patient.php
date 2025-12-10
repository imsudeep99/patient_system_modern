<?php
require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../helpers.php';

require_login();

// PAGE SETTINGS
$page_title = "View Patient";
$active = "patients";

// Validate ID
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Invalid patient ID");
}

$id = intval($_GET['id']);

// Fetch patient
$stmt = $pdo->prepare("
    SELECT p.*, r.name AS referrer_name, r.type AS referrer_type
    FROM patients p
    LEFT JOIN referrers r ON p.referred_by_id = r.id
    WHERE p.id = ?
");
$stmt->execute([$id]);
$patient = $stmt->fetch();

if (!$patient) {
    die("Patient not found");
}

// Start collecting page content
ob_start();
?>

<style>
.patient-wrapper {
    max-width: 1000px;
    margin: 20px auto;
}

.page-title {
    font-size: 24px;
    font-weight: 700;
    margin-bottom: 25px;
    color: #e5e9ef;
    background: #0a39e3;
}

.section-header {
    font-size: 17px;
    font-weight: 700;
    margin-top: 25px;
    border-left: 4px solid #0d6efd;
    padding-left: 10px;
    color: #333;
}

.info-item {
    background: #fff;
    border: 1px solid #e4e6eb;
    border-radius: 10px;
    padding: 15px 18px;
    margin-bottom: 15px;
}

.info-label {
    color: #666;
    font-weight: 600;
    font-size: 13px;
}

.info-value {
    font-size: 16px;
    font-weight: 500;
    color: #000;
}

.notes-box {
    background: #f4f7ff;
    border-left: 4px solid #0d6efd;
    padding: 18px;
    border-radius: 10px;
    margin-top: 10px;
}

.btn-modern {
    padding: 8px 20px;
    font-weight: 600;
    border-radius: 6px;
    font-size: 14px;
}

.btn-back { background:#6c757d; color:white !important; }
.btn-edit { background:#f0ad4e; color:white !important; }
.btn-delete { background:#dc3545; color:white !important; }
</style>

<div class="patient-wrapper">

    <!-- <div class="page-title">
        Patient Profile
    </div> -->

    <!-- BASIC INFO -->
    <div class="section-header">Basic Information</div>

    <div class="row mt-2">

        <div class="col-md-4">
            <div class="info-item">
                <div class="info-label">Name</div>
                <div class="info-value"><?= htmlspecialchars($patient['name']) ?></div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="info-item">
                <div class="info-label">Age</div>
                <div class="info-value"><?= htmlspecialchars($patient['age']) ?></div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="info-item">
                <div class="info-label">Gender</div>
                <div class="info-value"><?= htmlspecialchars($patient['gender']) ?></div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="info-item">
                <div class="info-label">Contact Number</div>
                <div class="info-value"><?= htmlspecialchars($patient['contact']) ?></div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="info-item">
                <div class="info-label">Aadhaar Number</div>
                <div class="info-value"><?= htmlspecialchars($patient['aadhaar']) ?></div>
            </div>
        </div>

    </div>

    <!-- ADDITIONAL DETAILS -->
    <div class="section-header">Additional Details</div>

    <div class="row mt-2">

        <div class="col-md-4">
            <div class="info-item">
                <div class="info-label">Referred By</div>
                <div class="info-value">
                    <?= $patient['referrer_name']
                        ? htmlspecialchars($patient['referrer_name']) . " (" . strtoupper($patient['referrer_type']) . ")"
                        : '-' ?>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="info-item">
                <div class="info-label">Fees</div>
                <div class="info-value">₹ <?= htmlspecialchars($patient['fees']) ?></div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="info-item">
                <div class="info-label">Date Added</div>
                <div class="info-value"><?= date("d M Y, h:i A", strtotime($patient['created_at'])) ?></div>
            </div>
        </div>

    </div>

    <!-- NOTES -->
    <div class="section-header">Notes</div>
    <div class="notes-box">
        <?= nl2br(htmlspecialchars($patient['notes'] ?: "No notes added.")) ?>
    </div>

    <!-- IMAGING -->
    <?php if (!empty($patient['imaging_file'])): ?>
        <div class="section-header">Imaging / X-Ray File</div>

        <a href="/patient_system_modern/uploads/<?= htmlspecialchars($patient['imaging_file']) ?>"
           class="btn btn-primary btn-modern mt-2"
           target="_blank">View File</a>
    <?php endif; ?>

    <hr class="my-4">

    <!-- BUTTONS -->
    <div class="d-flex justify-content-between align-items-center mt-3">

        <a href="index.php" class="btn-modern btn-back">← Back</a>

        <div>
            <a href="edit_patient.php?id=<?= $patient['id'] ?>" class="btn-modern btn-edit">Edit</a>

            <a href="delete_patient.php?id=<?= $patient['id'] ?>"
               class="btn-modern btn-delete"
               onclick="return confirm('Are you sure you want to delete this patient?');">
               Delete
            </a>
        </div>

    </div>

</div>

<?php
// End page content
$content = ob_get_clean();

// Load main layout
include __DIR__ . '/../layout/main.php';
?>
