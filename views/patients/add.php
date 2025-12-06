<?php
require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../helpers.php';

require_login();

$page_title = 'Add Patient';
$active = 'patients';

$referrers = $pdo->query('SELECT id, name, type FROM referrers ORDER BY name')->fetchAll();

$options = '<option value="">Select</option>';
foreach ($referrers as $r) {
    $options .= '<option value="'.$r['id'].'">'.htmlspecialchars($r['name']).' ('.strtoupper($r['type']).')</option>';
}

$content = <<<HTML
<div class="card shadow-sm">
    <div class="card-body">
        <form method="post" enctype="multipart/form-data" action="/patient_system_modern/controllers/save_patient.php">
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Name</label>
                    <input type="text" name="name" class="form-control" required>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Age</label>
                    <input type="number" name="age" class="form-control" required>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Gender</label>
                    <select name="gender" class="form-select" required>
                        <option value="">Select</option>
                        <option>Male</option>
                        <option>Female</option>
                        <option>Other</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Contact Number</label>
                   <input type="text" name="contact" class="form-control" pattern="[0-9]{10}" maxlength="10"oninput="this.value = this.value.replace(/[^0-9]/g, '')" title="Enter a valid 10-digit mobile number"required>

                </div>
                <div class="col-md-4">
                    <label class="form-label">Imaging / X-ray (Upload File)</label>
                    <input type="file" name="imaging_file" class="form-control" accept=".jpg,.jpeg,.png,.pdf,.dcm">
                </div>

                <div class="col-md-4">
                    <label class="form-label">Referred By (Doctor / ASHA)</label>
                    <select name="referred_by_id" class="form-select">
                        {$options}
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Aadhaar Number</label>
                    <input type="text" name="aadhaar" class="form-control"pattern="[0-9]{12}" maxlength="12" oninput="this.value = this.value.replace(/[^0-9]/g, '')" title="Enter a valid 12-digit Aadhaar number">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Fees</label>
                    <input type="number" step="0.01" name="fees" class="form-control">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Discount</label>
                    <input type="number" step="0.01" name="discount" class="form-control">
                </div>
                <div class="col-md-12">
                    <label class="form-label">Notes</label>
                    <textarea name="notes" class="form-control" rows="3"></textarea>
                </div>
            </div>
            <div class="mt-3 d-flex justify-content-end">
                <a href="/patient_system_modern/views/patients/index.php" class="btn btn-outline-secondary me-2">Cancel</a>
                <button class="btn btn-primary" type="submit">Save</button>
            </div>
        </form>
    </div>
</div>
HTML;

include __DIR__ . '/../layout/main.php';
