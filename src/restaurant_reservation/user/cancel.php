<?php
// user/cancel.php
require_once '../includes/functions.php';
ensureUser(); // Ensure the user is logged in
require_once '../config/config.php';

// Check if a valid reservation ID is provided
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: reservations.php");
    exit;
}

$reservationId = (int)$_GET['id'];

// Ensure the reservation belongs to the logged-in user
$stmt = $pdo->prepare("SELECT * FROM reservations WHERE id = :id AND user_id = :user_id");
$stmt->execute([
    'id'       => $reservationId,
    'user_id'  => $_SESSION['user_id']
]);
$reservation = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$reservation) {
    // The reservation does not exist or does not belong to the user
    header("Location: reservations.php");
    exit;
}

// Update the reservation status to "cancelled"
$stmt = $pdo->prepare("UPDATE reservations SET status = 'cancelled' WHERE id = :id");
$stmt->execute(['id' => $reservationId]);

header("Location: reservations.php?msg=cancelled");
exit;
?>
