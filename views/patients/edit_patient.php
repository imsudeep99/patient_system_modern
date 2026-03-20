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

// Referrers
$referrers = $pdo->query("SELECT id, name, type FROM referrers ORDER BY name")->fetchAll();

$options = '<option value="">Select</option>';
foreach ($referrers as $r) {
    $selected = ($patient['referred_by_id'] == $r['id']) ? 'selected' : '';
    $options .= '<option value="'.$r['id'].'" '.$selected.'>'.
        htmlspecialchars($r['name']).' ('.strtoupper($r['type']).')</option>';
}

// Escape values
$name = htmlspecialchars($patient['name']);
$age = htmlspecialchars($patient['age']);
$gender = htmlspecialchars($patient['gender']);
$contact = htmlspecialchars($patient['contact']);
$aadhaar = htmlspecialchars($patient['aadhaar']);
$fees = htmlspecialchars($patient['fees']);
$discount = htmlspecialchars($patient['discount']);
$notes = htmlspecialchars($patient['notes']);

$selMale = ($gender == 'Male') ? 'selected' : '';
$selFemale = ($gender == 'Female') ? 'selected' : '';
$selOther = ($gender == 'Other') ? 'selected' : '';

//  IMAGE LOGIC
// $imaging = $patient['imaging'] ?? '';

// $imagingHtml = '<span class="text-muted">No file uploaded</span>';

// if (!empty($imaging)) {
//     $fileUrl = "/patient_system_modern/uploads/" . $imaging;
//     $filePath = __DIR__ . '/../../uploads/' . $imaging;

//     if (file_exists($filePath)) {
//         $ext = strtolower(pathinfo($imaging, PATHINFO_EXTENSION));

//         if (in_array($ext, ['jpg', 'jpeg', 'png', 'pdf', 'dcm', 'webp'])) {
//             $imagingHtml = '<img src="'.$fileUrl.'" class="img-fluid border rounded" style="max-height:150px;">';
//         } elseif ($ext === 'pdf') {
//             $imagingHtml = '<a href="'.$fileUrl.'" target="_blank">View PDF</a>';
//         } else {
//             $imagingHtml = 'File uploaded';
//         }
//     } else {
//         $imagingHtml = 'File missing';
//     }
// }


// IMAGE LOGIC  and pdf dowanload 
$imaging = $patient['imaging'] ?? '';

$imagingHtml = '<span class="text-muted">No file uploaded</span>';

if (!empty($imaging)) {

    // ✅ CORRECT PATH (based on your folder)
    $fileUrl  = "/patient_system_modern/uploads/" . $imaging;
    $filePath = __DIR__ . '/../../uploads/' . $imaging;

    if (file_exists($filePath)) {

        $ext = strtolower(pathinfo($imaging, PATHINFO_EXTENSION));

        if (in_array($ext, ['jpg','jpeg','png','webp'])) {

            $imagingHtml = '<img src="'.$fileUrl.'" 
                class="img-fluid border rounded" 
                style="max-height:150px;">';

        } elseif ($ext === 'pdf') {

            $imagingHtml = '<a href="'.$fileUrl.'" target="_blank" class="btn btn-primary btn-sm">View PDF</a>';

        } elseif (in_array($ext, ['doc','docx','dcm'])) {

            $imagingHtml = '<a href="'.$fileUrl.'" target="_blank" class="btn btn-success btn-sm">Download File</a>';

        } else {

            $imagingHtml = '<a href="'.$fileUrl.'" target="_blank">Open File</a>';
        }

    } else {
        $imagingHtml = '<span class="text-danger">File missing</span>';
    }
}

$content = <<<HTML
<div class="card shadow-sm">
<div class="card-body">

<h5 class="mb-3">Edit Patient</h5>

<form method="post" enctype="multipart/form-data" action="/patient_system_modern/views/patients/update_patient.php">

<input type="hidden" name="id" value="{$id}">

<div class="row g-3">

<div class="col-md-4">
<label>Name</label>
<input type="text" name="name" class="form-control" value="{$name}" required>
</div>

<div class="col-md-2">
<label>Age</label>
<input type="number" name="age" class="form-control" value="{$age}" required>
</div>

<div class="col-md-2">
<label>Gender</label>
<select name="gender" class="form-select">
<option value="">Select</option>
<option value="Male" {$selMale}>Male</option>
<option value="Female" {$selFemale}>Female</option>
<option value="Other" {$selOther}>Other</option>
</select>
</div>

<div class="col-md-4">
<label>Contact</label>
<input type="text" name="contact" class="form-control" value="{$contact}">
</div>

<div class="col-md-4">
<label>Current Imaging</label><br>
{$imagingHtml}
</div>

<div class="col-md-4">
<label>Replace Imaging</label>
<input type="file" name="imaging_file" class="form-control">
</div>

<div class="col-md-4">
<label>Referred By</label>
<select name="referred_by_id" class="form-select">
{$options}
</select>
</div>

<div class="col-md-4">
    <label>Aadhaar</label>
    <input type="text" name="aadhaar" class="form-control"
       value="{$aadhaar}" maxlength="12"
       oninput="this.value=this.value.replace(/[^0-9]/g,'')"
       pattern="[0-9]{12}" title="Enter 12 digit Aadhaar">
</div>

<div class="col-md-3">
    <label>Fees</label>
    <input type="text" name="fees" class="form-control"
       value="{$fees}" maxlength="4"
       oninput="this.value=this.value.replace(/[^0-9.]/g,'')">
</div>

<div class="col-md-3">
    <label>Discount</label>
    <input type="text" name="discount" class="form-control"
       value="{$discount}" maxlength="4"
       oninput="this.value=this.value.replace(/[^0-9.]/g,'')">
</div>

<div class="col-md-12">
    <label>Notes</label>
    <textarea name="notes" class="form-control">{$notes}</textarea>
</div>

</div>

<div class="mt-3 text-end">
    <a href="/patient_system_modern/views/patients/index.php" class="btn btn-outline-secondary me-2">Cancel</a>
    <button class="btn btn-primary">Update</button>
</div>

</form>
</div>
</div>
HTML;

include __DIR__ . '/../layout/main.php';