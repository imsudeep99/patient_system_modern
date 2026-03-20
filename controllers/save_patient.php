<?php
require_once __DIR__ . '/../config.php';

session_start(); // ✅ ensure session works

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /patient_system_modern/views/patients/index.php');
    exit;
}

// ==========================
// GET & CLEAN INPUT
// ==========================
$name   = trim($_POST['name'] ?? '');
$age    = (int)($_POST['age'] ?? 0);
$gender = trim($_POST['gender'] ?? '');
$contact = trim($_POST['contact'] ?? '');
$referred_by_id = !empty($_POST['referred_by_id']) ? (int)$_POST['referred_by_id'] : null;
$aadhaar = trim($_POST['aadhaar'] ?? '');
$fees = $_POST['fees'] !== '' ? (float)$_POST['fees'] : null;
$discount = $_POST['discount'] !== '' ? (float)$_POST['discount'] : null;
$notes = trim($_POST['notes'] ?? '');

// ==========================
// BASIC VALIDATION
// ==========================
if ($name === '' || $age <= 0 || $gender === '' || $contact === '') {
    $_SESSION['error'] = 'Please fill all required fields.';
    header('Location: /patient_system_modern/views/patients/add.php');
    exit;
}

// ==========================
// AGE VALIDATION
// ==========================
if ($age < 0 || $age > 100) {
    $_SESSION['error'] = "Age must be between 0 and 100";
    header("Location: /patient_system_modern/views/patients/add.php");
    exit;
}

// ==========================
// FEES VALIDATION
// ==========================
if (!empty($fees) && $fees > 9999) {
    $_SESSION['error'] = "Fees must be maximum 4 digits";
    header("Location: /patient_system_modern/views/patients/add.php");
    exit;
}

// ==========================
// DISCOUNT VALIDATION
// ==========================
if (!empty($discount) && $discount > 9999) {
    $_SESSION['error'] = "Discount must be maximum 4 digits";
    header("Location: /patient_system_modern/views/patients/add.php");
    exit;
}

// ==========================
// AADHAAR DUPLICATE CHECK
// ==========================
if (!empty($aadhaar)) {
    $stmt = $pdo->prepare("SELECT id FROM patients WHERE aadhaar = ?");
    $stmt->execute([$aadhaar]);

    if ($stmt->fetch()) {
        $_SESSION['error'] = "Aadhaar already exists!";
        header("Location: /patient_system_modern/views/patients/add.php");
        exit;
    }
}

// ==========================
// IMAGE UPLOAD
// ==========================
$imaging = null;

if (!empty($_FILES['imaging_file']['name'])) {

    $ext = strtolower(pathinfo($_FILES['imaging_file']['name'], PATHINFO_EXTENSION));
    $allowed = ['jpg', 'jpeg', 'png', 'pdf', 'dcm', 'webp'];

    if (!in_array($ext, $allowed)) {
        $_SESSION['error'] = "Invalid file type!";
        header("Location: /patient_system_modern/views/patients/add.php");
        exit;
    }

    $uploadDir = __DIR__ . '/../uploads/patient_files/';

    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    $filename = 'IMG_' . time() . '_' . rand(1000,9999) . '.' . $ext;
    $destination = $uploadDir . $filename;

    if (move_uploaded_file($_FILES['imaging_file']['tmp_name'], $destination)) {
        $imaging = $filename;
    } else {
        $_SESSION['error'] = "Image upload failed!";
        header("Location: /patient_system_modern/views/patients/add.php");
        exit;
    }
}

// ==========================
// INSERT INTO DATABASE
// ==========================
$stmt = $pdo->prepare("
    INSERT INTO patients 
    (name, age, gender, contact, imaging, referred_by_id, aadhaar, fees, discount, notes, created_at)
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())
");

$stmt->execute([
    $name,
    $age,
    $gender,
    $contact,
    $imaging,
    $referred_by_id,
    $aadhaar,
    $fees,
    $discount,
    $notes
]);

// ==========================
// SUCCESS REDIRECT
// ==========================
header('Location: /patient_system_modern/views/patients/index.php?success=1');
exit;