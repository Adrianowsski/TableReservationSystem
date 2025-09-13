<?php
// admin/change_status.php
require_once '../includes/functions.php';
ensureAdmin();
require_once '../config/config.php';

if (isset($_GET['id'], $_GET['status'])) {
    $resId = (int)$_GET['id'];
    $newStatus = $_GET['status'];
    $stmt = $pdo->prepare("UPDATE reservations SET status = :st WHERE id = :id");
    $stmt->execute([
        'st' => $newStatus,
        'id' => $resId
    ]);
}
header("Location: reservations.php");
exit;
