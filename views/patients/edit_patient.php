<?php
require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../helpers.php';

require_login();

$page_title = 'Edit Patient';
$active = 'patients';

// Validate ID
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Invalid Patient ID");
}

$id = intval($_GET['id']);

// Fetch patient
$stmt = $pdo->prepare("SELECT * FROM patients WHERE id = ?");
$stmt->execute([$id]);
$patient = $stmt->fetch();

if (!$patient) {
    die("Patient not found");
}

// Fetch referrers
$referrers = $pdo->query("SELECT id, name, type FROM referrers ORDER BY name")->fetchAll();

// Build options
$options = '<option value="">Select</option>';
foreach ($referrers as $r) {
    $selected = ($patient['referred_by_id'] == $r['id']) ? 'selected' : '';
    $options .= '<option value="'.$r['id'].'" '.$selected.'>'.
                htmlspecialchars($r['name']).' ('.strtoupper($r['type']).')</option>';
}

// Escape all values
$name = htmlspecialchars($patient['name']);
$age = htmlspecialchars($patient['age']);
$gender = htmlspecialchars($patient['gender']);

$selMale   = ($gender == 'Male')   ? 'selected' : '';
$selFemale = ($gender == 'Female') ? 'selected' : '';
$selOther  = ($gender == 'Other')  ? 'selected' : '';

$contact = htmlspecialchars($patient['contact']);
$aadhaar = htmlspecialchars($patient['aadhaar']);
$fees = htmlspecialchars($patient['fees']);
$discount = htmlspecialchars($patient['discount']);
$notes = htmlspecialchars($patient['notes']);

$content = <<<HTML
<div class="card shadow-sm">
    <div class="card-body">

        <h5 class="mb-3">Edit Patient</h5>

        <form method="post" enctype="multipart/form-data" action="/patient_system_modern/views/patients/update_patient.php">

            <input type="hidden" name="id" value="{$id}">

            <div class="row g-3">

                <div class="col-md-4">
                    <label class="form-label">Name</label>
                    <input type="text" name="name" class="form-control" value="{$name}" required>
                </div>

                <div class="col-md-2">
                    <label class="form-label">Age</label>
                    <input type="number" name="age" class="form-control" value="{$age}" required>
                </div>

                <div class="col-md-2">
                    <label class="form-label">Gender</label>
                    <select name="gender" class="form-select" required>
                        <option value="">Select</option>
                        <option value="Male"   {$selMale}>Male</option>
                        <option value="Female" {$selFemale}>Female</option>
                        <option value="Other"  {$selOther}>Other</option>
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Contact Number</label>
                    <input type="text" name="contact" class="form-control"
                           value="{$contact}" maxlength="10" pattern="[0-9]{10}"
                           oninput="this.value=this.value.replace(/[^0-9]/g,'')" required>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Replace Imaging / X-ray (optional)</label>
                    <input type="file" name="imaging_file" class="form-control" accept=".jpg,.jpeg,.png,.pdf,.dcm">
                    <!-- <small class="text-muted">Leave empty to keep existing file.</small> -->
                </div>

                <div class="col-md-4">
                    <label class="form-label">Referred By (Doctor / ASHA)</label>
                    <select name="referred_by_id" class="form-select">
                        {$options}
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Aadhaar Number</label>
                    <input type="text" name="aadhaar" class="form-control"
                           value="{$aadhaar}" maxlength="12" pattern="[0-9]{12}"
                           oninput="this.value=this.value.replace(/[^0-9]/g,'')">
                </div>

                <div class="col-md-3">
                    <label class="form-label">Fees</label>
                    <input type="number" step="0.01" name="fees" class="form-control" value="{$fees}">
                </div>

                <div class="col-md-3">
                    <label class="form-label">Discount</label>
                    <input type="number" step="0.01" name="discount" class="form-control" value="{$discount}">
                </div>

                <div class="col-md-12">
                    <label class="form-label">Notes</label>
                    <textarea name="notes" class="form-control" rows="3">{$notes}</textarea>
                </div>
            </div>

            <div class="mt-3 d-flex justify-content-end">
                <a href="/patient_system_modern/views/patients/index.php" class="btn btn-outline-secondary me-2">Cancel</a>
                <button class="btn btn-primary" type="submit">Update</button>
            </div>

        </form>

    </div>
</div>
HTML;

include __DIR__ . '/../layout/main.php';
