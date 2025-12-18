<?php
require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../helpers.php';

require_login();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die("Invalid request");
}

$id = intval($_POST['id']);

$stmt = $pdo->prepare("
    UPDATE referrers SET 
        name = ?,
        hospital_clinic = ?, 
        phone = ?, 
        address = ?,
        type = ?
       
    WHERE id = ?
");

$stmt->execute([
    $_POST['name'],
    $_POST['hospital_clinic'],
    $_POST['phone'],
    $_POST['address'],
    $_POST['type'],
    $id
]);

header('Location: /patient_system_modern/views/referrers/index.php');
exit;
