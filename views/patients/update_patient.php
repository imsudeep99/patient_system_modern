<?php
require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../helpers.php';

require_login();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die("Invalid request");
}

$id = intval($_POST['id']);

// get old image
$stmt = $pdo->prepare("SELECT imaging FROM patients WHERE id = ?");
$stmt->execute([$id]);
$old = $stmt->fetch();

$filename = $old['imaging'] ?? '';

// upload new file
if (!empty($_FILES['imaging_file']['name'])) {

    $uploadDir = __DIR__ . '/../../uploads/';

    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    $newName = time() . '_' . basename($_FILES['imaging_file']['name']);
    $target = $uploadDir . $newName;

    if (move_uploaded_file($_FILES['imaging_file']['tmp_name'], $target)) {

        // delete old file
        if (!empty($old['imaging'])) {
            $oldPath = $uploadDir . $old['imaging'];
            if (file_exists($oldPath)) {
                unlink($oldPath);
            }
        }

        $filename = $newName;
    }
}

// Aadhaar duplicate check (excluding current ID)
if (!empty($_POST['aadhaar'])) {

    $stmt = $pdo->prepare("SELECT id FROM patients WHERE aadhaar = ? AND id != ?");
    $stmt->execute([$_POST['aadhaar'], $_POST['id']]);

    if ($stmt->fetch()) {
        $_SESSION['error'] = "Aadhaar already exists!";
        header("Location: edit_patient.php?id=" . $_POST['id']);
        exit;
    }
}

// update DB
$stmt = $pdo->prepare("
    UPDATE patients SET 
        name = ?, 
        age = ?, 
        gender = ?, 
        contact = ?, 
        aadhaar = ?, 
        referred_by_id = ?, 
        fees = ?,
        notes = ?,
        discount = ?,
        imaging = ?
    WHERE id = ?
");

$stmt->execute([
    $_POST['name'],
    $_POST['age'],
    $_POST['gender'],
    $_POST['contact'],
    $_POST['aadhaar'],
    $_POST['referred_by_id'] ?: null,
    $_POST['fees'],
    $_POST['notes'],
    $_POST['discount'],
    $filename,
    $id
]);

header("Location: index.php?updated=1");
exit;