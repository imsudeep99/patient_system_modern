<?php
require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../helpers.php';

require_login();

$page_title = 'Doctor / ASHA';
$active = 'Doctor / ASHA';

// Validate ID
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Invalid Doctor ID");
}

$id = intval($_GET['id']);

// Fetch Doctor
$stmt = $pdo->prepare("SELECT * FROM referrers WHERE id = ?");
$stmt->execute([$id]);
$doctors = $stmt->fetch();

if (!$doctors) {
    die("Doctor not found");
}

// Escape all values
$name = htmlspecialchars($doctors['name']);
$contact = htmlspecialchars($doctors['phone']);
$hospital_clinic = htmlspecialchars($doctors['hospital_clinic']);
$address = htmlspecialchars($doctors['address']);
$type = htmlspecialchars($doctors['type']);

$typeOptions = '<option value="">Select</option>';

$typeOptions .= '<option value="doctor" '.($type === 'doctor' ? 'selected' : '').'>Doctor</option>';
$typeOptions .= '<option value="asha" '.($type === 'asha' ? 'selected' : '').'>ASHA</option>';


$content = <<<HTML
<div class="card shadow-sm">
    <div class="card-body">
        <form method="post" action="/patient_system_modern/views/referrers/update_doctors.php">

            <input type="hidden" name="id" value="{$id}">
            
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Type</label>
                    <!-- <select name="type" class="form-select" required>
                        <option value="">Select</option>
                        <option value="doctor">Doctor</option>
                        <option value="asha">ASHA</option>
                    </select> -->
                    <select name="type" class="form-select" required>
                        {$typeOptions}
                    </select>

                </div>
                <div class="col-md-4">
                    <label class="form-label">Name</label>
                    <input type="text" name="name" class="form-control" value="{$name}"  required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Contact Number</label>
                   <input type="text" name="phone" value="{$contact}"  class="form-control" pattern="[0-9]{10}" maxlength="10"oninput="this.value = this.value.replace(/[^0-9]/g, '')" title="Enter a valid 10-digit mobile number"required>

                </div>
                <div class="col-md-6">
                    <label class="form-label">Hospital / Clinic</label>
                    <input type="text" name="hospital_clinic" value="{$hospital_clinic}"  class="form-control">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Address</label>
                    <input type="text" name="address" value="{$address}"  class="form-control">
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
