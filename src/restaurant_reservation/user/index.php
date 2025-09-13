<?php
// user/index.php
require_once '../includes/functions.php';
ensureUser();

$pageTitle = "User Panel";
$customCss = "user.css";

require_once '../includes/header.php';
require_once '../config/config.php';

// Fetch user data
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = :id");
$stmt->execute(['id' => $_SESSION['user_id']]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$user) {
    header("Location: ../logout.php");
    exit;
}

// Fetch active reservations
$stmt2 = $pdo->prepare("
    SELECT r.*, t.table_name
    FROM reservations r
    JOIN tables t ON r.table_id = t.id
    WHERE r.user_id = :user_id AND r.status != 'cancelled'
    ORDER BY r.reservation_date ASC
");
$stmt2->execute(['user_id' => $_SESSION['user_id']]);
$reservations = $stmt2->fetchAll(PDO::FETCH_ASSOC);
?>
<div class="container">
    <div class="user-bg">
        <header class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="m-0"><i class="bi bi-person-circle"></i> User Panel</h1>
            <a href="../logout.php" class="btn btn-sm btn-danger"><i class="bi bi-box-arrow-right"></i> Logout</a>
        </header>
        <p class="lead">Welcome, <strong><?php echo htmlspecialchars($user['first_name']); ?></strong>! Below you will find information about your account and active reservations.</p>
        <div class="row g-3">
            <div class="col-md-4">
                <div class="card card-dark shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title"><i class="bi bi-person"></i> Personal Information</h5>
                        <p class="mb-1"><strong>First Name:</strong> <?php echo htmlspecialchars($user['first_name']); ?></p>
                        <p class="mb-1"><strong>Last Name:</strong> <?php echo htmlspecialchars($user['last_name']); ?></p>
                        <p class="mb-1"><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></p>
                    </div>
                </div>
            </div>
            <div class="col-md-8">
                <div class="card card-dark shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title"><i class="bi bi-bookmark-check"></i> Your Active Reservations</h5>
                        <p>You can view details or go to the "My Reservations" section.</p>
                        <a href="reservations.php" class="btn btn-outline-light"><i class="bi bi-calendar-check"></i> My Reservations</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="mt-4 mb-4">
            <a href="reservations.php" class="btn btn-outline-light me-2"><i class="bi bi-calendar-check"></i> Manage Reservations</a>
            <a href="../menu.php" class="btn btn-outline-light"><i class="bi bi-card-list"></i> View Menu</a>
        </div>
        <h3 class="text-light">Your Current Reservations</h3>
        <?php if (empty($reservations)): ?>
            <div class="alert alert-info mt-3">You have no active reservations.</div>
        <?php else: ?>
            <div class="table-responsive mt-3">
                <table class="table table-hover bg-light">
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>Date</th>
                        <th>Table</th>
                        <th>Number of People</th>
                        <th>Status</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($reservations as $reservation): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($reservation['id']); ?></td>
                            <td><?php echo htmlspecialchars($reservation['reservation_date']); ?></td>
                            <td><?php echo htmlspecialchars($reservation['table_name']); ?></td>
                            <td><?php echo (int)$reservation['num_of_people']; ?></td>
                            <td><?php echo renderReservationStatus($reservation['status']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>
<?php require_once '../includes/footer.php'; ?>
