<?php
// admin/reservations.php
require_once '../includes/functions.php';
ensureAdmin();

$pageTitle = "Reservations - Admin Panel";
$customCss = "admin_reservations.css";

require_once '../includes/header.php';
require_once '../config/config.php';

// Filtering reservations
$whereClauses = [];
$sqlParams = [];
if (!empty($_GET['date'])) {
    $whereClauses[] = 'r.reservation_date = :date';
    $sqlParams['date'] = $_GET['date'];
}
if (!empty($_GET['status'])) {
    $whereClauses[] = 'r.status = :status';
    $sqlParams['status'] = $_GET['status'];
}
$where = (count($whereClauses)) ? 'WHERE ' . implode(' AND ', $whereClauses) : '';

// Pagination
$limit = 5;
$page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$offset = getPaginationOffset($page, $limit);

// Fetching the number of reservations
$countSql = "SELECT COUNT(*) FROM reservations r 
              JOIN users u ON r.user_id = u.id 
              JOIN tables t ON r.table_id = t.id 
              $where";
$countStmt = $pdo->prepare($countSql);
$countStmt->execute($sqlParams);
$totalRows = $countStmt->fetchColumn();
$totalPages = ceil($totalRows / $limit);

// Fetching reservations
$sql = "SELECT r.*, u.first_name, u.last_name, t.table_name
        FROM reservations r
        JOIN users u ON r.user_id = u.id
        JOIN tables t ON r.table_id = t.id
        $where
        ORDER BY r.reservation_date DESC, r.reservation_time DESC
        LIMIT :limit OFFSET :offset";
$stmt = $pdo->prepare($sql);
foreach ($sqlParams as $key => $val) {
    $stmt->bindValue(":$key", $val, PDO::PARAM_STR);
}
$stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$allRes = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<div class="container my-4">
    <header class="mb-4">
        <h1 class="mb-3"><i class="bi bi-calendar-check-fill"></i> Manage Reservations</h1>
        <div class="btn-group mb-3" role="group">
            <a href="index.php" class="btn btn-secondary"><i class="bi bi-house-door-fill"></i> Admin Panel</a>
            <a href="../logout.php" class="btn btn-danger"><i class="bi bi-box-arrow-right"></i> Logout</a>
        </div>
    </header>
    <!-- Filter Panel -->
    <section class="mb-4">
        <div class="card">
            <div class="card-header"><i class="bi bi-funnel-fill"></i> Filter Reservations</div>
            <div class="card-body">
                <form method="get" class="row g-3">
                    <div class="col-md-4">
                        <label for="date" class="form-label">Date:</label>
                        <input type="date" id="date" name="date" class="form-control" value="<?php echo $_GET['date'] ?? ''; ?>">
                    </div>
                    <div class="col-md-4">
                        <label for="status" class="form-label">Status:</label>
                        <select id="status" name="status" class="form-select">
                            <option value="">(all)</option>
                            <option value="pending" <?php if(($_GET['status'] ?? '') === 'pending') echo 'selected'; ?>>Pending</option>
                            <option value="confirmed" <?php if(($_GET['status'] ?? '') === 'confirmed') echo 'selected'; ?>>Confirmed</option>
                            <option value="cancelled" <?php if(($_GET['status'] ?? '') === 'cancelled') echo 'selected'; ?>>Cancelled</option>
                        </select>
                    </div>
                    <div class="col-md-4 align-self-end">
                        <button type="submit" class="btn btn-secondary w-100"><i class="bi bi-search"></i> Filter</button>
                    </div>
                </form>
            </div>
        </div>
    </section>
    <!-- Reservations Table -->
    <section>
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead class="table-secondary">
                <tr>
                    <th>ID</th>
                    <th>Client</th>
                    <th>Table</th>
                    <th>Date</th>
                    <th>Time</th>
                    <th>Number of People</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                <?php if ($allRes): ?>
                    <?php foreach($allRes as $r): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($r['id']); ?></td>
                            <td><?php echo htmlspecialchars($r['first_name'] . ' ' . $r['last_name']); ?></td>
                            <td><?php echo htmlspecialchars($r['table_name']); ?></td>
                            <td><?php echo htmlspecialchars($r['reservation_date']); ?></td>
                            <td><?php echo htmlspecialchars($r['reservation_time']); ?></td>
                            <td><?php echo (int)$r['num_of_people']; ?></td>
                            <td><?php echo renderReservationStatus($r['status']); ?></td>
                            <td>
                                <?php if ($r['status'] !== 'cancelled'): ?>
                                    <a href="change_status.php?id=<?php echo $r['id']; ?>&status=confirmed" class="btn btn-success btn-sm me-1"><i class="bi bi-check-circle-fill"></i> Confirm</a>
                                    <a href="change_status.php?id=<?php echo $r['id']; ?>&status=cancelled" class="btn btn-warning btn-sm"><i class="bi bi-x-circle-fill"></i> Cancel</a>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="8" class="text-center">No reservations to display.</td></tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </section>
    <!-- Pagination -->
    <section class="mt-4">
        <nav>
            <ul class="pagination justify-content-center">
                <?php if ($page > 1): ?>
                    <li class="page-item"><a class="page-link" href="?page=<?php echo ($page - 1) . '&status=' . ($_GET['status'] ?? '') . '&date=' . ($_GET['date'] ?? ''); ?>">Previous</a></li>
                <?php endif; ?>
                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                    <li class="page-item <?php echo ($i == $page) ? 'active' : ''; ?>">
                        <a class="page-link" href="?page=<?php echo $i . '&status=' . ($_GET['status'] ?? '') . '&date=' . ($_GET['date'] ?? ''); ?>"><?php echo $i; ?></a>
                    </li>
                <?php endfor; ?>
                <?php if ($page < $totalPages): ?>
                    <li class="page-item"><a class="page-link" href="?page=<?php echo ($page + 1) . '&status=' . ($_GET['status'] ?? '') . '&date=' . ($_GET['date'] ?? ''); ?>">Next</a></li>
                <?php endif; ?>
            </ul>
        </nav>
    </section>
</div>
<?php require_once '../includes/footer.php'; ?>
