<?php
// admin/index.php
require_once '../includes/functions.php';
ensureAdmin();

$pageTitle = "Admin Panel";
$customCss = "admin.css";

require_once '../includes/header.php';
require_once '../config/config.php';

// Fetch upcoming reservations
$queryUpcoming = "
    SELECT r.*, u.first_name, u.last_name, t.table_name 
    FROM reservations r
    JOIN users u ON r.user_id = u.id
    JOIN tables t ON r.table_id = t.id
    WHERE CONCAT(r.reservation_date, ' ', r.reservation_time) >= NOW()
      AND r.status != 'cancelled'
    ORDER BY r.reservation_date, r.reservation_time
    LIMIT 5
";
$stmtUpcoming = $pdo->prepare($queryUpcoming);
$stmtUpcoming->execute();
$upcomingReservations = $stmtUpcoming->fetchAll(PDO::FETCH_ASSOC);

// Fetch statistics
try {
    $stmtTotalTables = $pdo->query("SELECT COUNT(*) AS total FROM tables");
    $totalTables = $stmtTotalTables->fetch(PDO::FETCH_ASSOC)['total'];

    $stmtTotalReservations = $pdo->query("SELECT COUNT(*) AS total FROM reservations");
    $totalReservations = $stmtTotalReservations->fetch(PDO::FETCH_ASSOC)['total'];

    $stmtStatusReservations = $pdo->query("SELECT status, COUNT(*) AS count FROM reservations GROUP BY status");
    $statusReservations = $stmtStatusReservations->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    error_log("Error fetching statistics: " . $e->getMessage());
    $totalTables = $totalReservations = 0;
    $statusReservations = [];
}
?>
<div class="container mt-4">
    <div class="admin-bg">
        <header class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="text-light m-0"><i class="bi bi-calendar-check-fill"></i> Admin Panel</h1>
            <div>
                <a href="../logout.php" class="btn btn-sm btn-danger"><i class="bi bi-box-arrow-right"></i> Logout</a>
            </div>
        </header>
        <p class="lead text-light">Welcome, Administrator! Below you will find statistics and upcoming reservations.</p>
        <div class="mb-4">
            <a href="tables.php" class="btn btn-outline-light me-2"><i class="bi bi-layout-text-window-reverse"></i> Manage Tables</a>
            <a href="reservations.php" class="btn btn-outline-light"><i class="bi bi-calendar-check"></i> Manage Reservations</a>
        </div>
        <!-- Statistics Panel -->
        <section class="mb-4">
            <div class="row g-3">
                <div class="col-md-4">
                    <div class="card card-dark shadow-sm h-100">
                        <div class="card-body text-center">
                            <h5 class="card-title"><i class="bi bi-chair"></i> Total Number of Tables</h5>
                            <p class="card-text display-4"><?php echo htmlspecialchars($totalTables); ?></p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card card-dark shadow-sm h-100">
                        <div class="card-body text-center">
                            <h5 class="card-title"><i class="bi bi-calendar-event"></i> Total Number of Reservations</h5>
                            <p class="card-text display-4"><?php echo htmlspecialchars($totalReservations); ?></p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card card-dark shadow-sm h-100">
                        <div class="card-body">
                            <h5 class="card-title"><i class="bi bi-clipboard-data"></i> Reservations by Status</h5>
                            <ul class="list-unstyled mb-0">
                                <?php
                                $statuses = [
                                    'pending'   => 'Pending',
                                    'confirmed' => 'Confirmed',
                                    'cancelled' => 'Cancelled'
                                ];
                                foreach ($statuses as $key => $label) {
                                    $count = 0;
                                    foreach ($statusReservations as $status) {
                                        if ($status['status'] === $key) {
                                            $count = $status['count'];
                                            break;
                                        }
                                    }
                                    echo "<li><strong>$label:</strong> " . htmlspecialchars($count) . "</li>";
                                }
                                ?>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- Upcoming Reservations -->
        <section class="mb-4">
            <h2 class="text-light"><i class="bi bi-clock-history"></i> Upcoming Reservations</h2>
            <?php if ($upcomingReservations): ?>
                <div class="table-responsive">
                    <table class="table table-bordered table-hover bg-light">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>Client</th>
                            <th>Table</th>
                            <th>Date</th>
                            <th>Time</th>
                            <th>Number of People</th>
                            <th>Status</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($upcomingReservations as $res): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($res['id']); ?></td>
                                <td><?php echo htmlspecialchars($res['first_name'] . ' ' . $res['last_name']); ?></td>
                                <td><?php echo htmlspecialchars($res['table_name']); ?></td>
                                <td><?php echo htmlspecialchars(date("d-m-Y", strtotime($res['reservation_date']))); ?></td>
                                <td><?php echo htmlspecialchars(date("H:i", strtotime($res['reservation_time']))); ?></td>
                                <td><?php echo (int)$res['num_of_people']; ?></td>
                                <td><?php echo renderReservationStatus($res['status']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="alert alert-info">No upcoming reservations.</div>
            <?php endif; ?>
        </section>
    </div>
</div>
<?php require_once '../includes/footer.php'; ?>
