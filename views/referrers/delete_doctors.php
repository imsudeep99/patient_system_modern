<?php
require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../helpers.php';

require_login();

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Invalid ID");
}

$id = intval($_GET['id']);

// Delete record
$stmt = $pdo->prepare("DELETE FROM referrers WHERE id = ?");
$stmt->execute([$id]);

// Set session flash message
$_SESSION['success'] = "Doctors deleted successfully";

header("Location: index.php");
exit;
