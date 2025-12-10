<?php
require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../helpers.php';

require_login();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die("Invalid request");
}

$id = intval($_POST['id']);

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
        discount = ?
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
    $id
]);

header("Location: index.php?updated=1");
exit;
