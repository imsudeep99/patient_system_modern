<?php
require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../helpers.php';

require_login();

$page_title = 'Add Doctor / ASHA';
$active = 'referrers';

$content = <<<HTML
<div class="card shadow-sm">
    <div class="card-body">
        <form method="post" action="/patient_system_modern/controllers/save_referrer.php">
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Type</label>
                    <select name="type" class="form-select" required>
                        <option value="">Select</option>
                        <option value="doctor">Doctor</option>
                        <option value="asha">ASHA</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Name</label>
                    <input type="text" name="name" class="form-control" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Contact Number</label>
                   <input type="text" name="contact" class="form-control" pattern="[0-9]{10}" maxlength="10"oninput="this.value = this.value.replace(/[^0-9]/g, '')" title="Enter a valid 10-digit mobile number"required>

                </div>
                <div class="col-md-6">
                    <label class="form-label">Hospital / Clinic</label>
                    <input type="text" name="hospital_clinic" class="form-control">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Address</label>
                    <input type="text" name="address" class="form-control">
                </div>
            </div>
            <div class="mt-3 d-flex justify-content-end">
                <a href="/patient_system_modern/views/referrers/index.php" class="btn btn-outline-secondary me-2">Cancel</a>
                <button class="btn btn-primary" type="submit">Save</button>
            </div>
        </form>
    </div>
</div>
HTML;

include __DIR__ . '/../layout/main.php';
