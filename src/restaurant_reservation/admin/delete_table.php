<?php
// admin/delete_table.php
require_once '../includes/functions.php';
ensureAdmin();
require_once '../config/config.php';

// Check if the table ID is provided
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: tables.php");
    exit;
}

$tableId = (int)$_GET['id'];

try {
    $stmt = $pdo->prepare("DELETE FROM tables WHERE id = :id");
    $stmt->execute(['id' => $tableId]);
    header("Location: tables.php?msg=deleted");
    exit;
} catch (PDOException $e) {
    header("Location: tables.php?msg=error");
    exit;
}
