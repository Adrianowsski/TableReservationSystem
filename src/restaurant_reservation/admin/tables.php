<?php
// admin/tables.php
require_once '../includes/functions.php';
ensureAdmin();

$pageTitle = "Manage Tables";
$customCss = "admin_tables.css";

require_once '../includes/header.php';
require_once '../config/config.php';

$csrfToken = getCsrfToken();
$successMsg = "";
$errorMsg = "";

// Handling the addition of a new table
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add') {
    if (!isset($_POST['csrf_token']) || !checkCsrfToken($_POST['csrf_token'])) {
        $errorMsg = "Security verification error (CSRF token).";
    } else {
        $tableName = trim($_POST['table_name']);
        $seats     = (int)$_POST['seats'];
        $location  = trim($_POST['location']);

        if (empty($tableName)) {
            $errorMsg = "Table name cannot be empty.";
        } elseif ($seats <= 0) {
            $errorMsg = "The number of seats must be greater than 0.";
        } else {
            try {
                $stmt = $pdo->prepare("INSERT INTO tables (table_name, seats, location) VALUES (:tn, :s, :loc)");
                $stmt->execute([
                    'tn'  => $tableName,
                    's'   => $seats,
                    'loc' => $location
                ]);
                $successMsg = "Table has been added successfully.";
            } catch (PDOException $e) {
                $errorMsg = "An error occurred while adding the table.";
            }
        }
    }
}

// Pagination
$limit = 10;
$page  = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$offset = getPaginationOffset($page, $limit);

$totalStmt = $pdo->query("SELECT COUNT(*) FROM tables");
$totalRows = $totalStmt->fetchColumn();
$totalPages = ceil($totalRows / $limit);

$stmt = $pdo->prepare("SELECT * FROM tables ORDER BY id DESC LIMIT :limit OFFSET :offset");
$stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$tables = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="container mt-4">
    <header class="mb-4">
        <h1 class="mb-3 text-dark"><i class="bi bi-layout-text-window-reverse"></i> Manage Tables</h1>
        <div class="btn-group mb-3">
            <a href="index.php" class="btn btn-outline-dark"><i class="bi bi-house-door-fill"></i> Admin Panel</a>
            <a href="../logout.php" class="btn btn-outline-dark"><i class="bi bi-box-arrow-right"></i> Logout</a>
        </div>
    </header>

    <?php if (!empty($successMsg)): ?>
        <div class="alert alert-success alert-dismissible fade show"><?php echo htmlspecialchars($successMsg); ?></div>
    <?php endif; ?>
    <?php if (!empty($errorMsg)): ?>
        <div class="alert alert-danger alert-dismissible fade show"><?php echo htmlspecialchars($errorMsg); ?></div>
    <?php endif; ?>

    <!-- Add Table Form -->
    <section class="mb-4">
        <div class="card shadow-sm">
            <div class="card-header text-white">
                <i class="bi bi-plus-circle-fill"></i> Add New Table
            </div>
            <div class="card-body">
                <form method="post" class="row g-3">
                    <input type="hidden" name="csrf_token" value="<?php echo $csrfToken; ?>">
                    <input type="hidden" name="action" value="add">
                    <div class="col-md-4">
                        <label for="table_name" class="form-label">Table Name / Number:</label>
                        <input type="text" id="table_name" name="table_name" class="form-control" required>
                    </div>
                    <div class="col-md-4">
                        <label for="seats" class="form-label">Number of Seats:</label>
                        <input type="number" id="seats" name="seats" class="form-control" min="1" required>
                    </div>
                    <div class="col-md-4">
                        <label for="location" class="form-label">Location:</label>
                        <input type="text" id="location" name="location" class="form-control">
                    </div>
                    <div class="col-12">
                        <button type="submit" class="btn btn-outline-dark"><i class="bi bi-check-circle-fill"></i> Add Table</button>
                    </div>
                </form>
            </div>
        </div>
    </section>

    <!-- Table List -->
    <section>
        <h2 class="mb-3 text-dark"><i class="bi bi-list-ul"></i> Current Tables</h2>
        <div class="table-responsive">
            <table class="table table-striped table-hover align-middle">
                <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Seats</th>
                    <th>Location</th>
                    <th class="text-center">Actions</th>
                </tr>
                </thead>
                <tbody>
                <?php if ($tables): ?>
                    <?php foreach ($tables as $t): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($t['id']); ?></td>
                            <td><?php echo htmlspecialchars($t['table_name']); ?></td>
                            <td><?php echo (int)$t['seats']; ?></td>
                            <td><?php echo htmlspecialchars($t['location']); ?></td>
                            <td class="text-center">
                                <!-- Link to Edit Table -->
                                <a href="edit_table.php?id=<?php echo $t['id']; ?>" class="btn btn-sm btn-outline-secondary me-1">
                                    <i class="bi bi-pencil-square"></i> Edit
                                </a>
                                <!-- Link to Delete Table -->
                                <a href="delete_table.php?id=<?php echo $t['id']; ?>" class="btn btn-sm btn-outline-danger"
                                   onclick="return confirm('Are you sure you want to delete this table?');">
                                    <i class="bi bi-trash-fill"></i> Delete
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="5" class="text-center">No tables to display.</td></tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </section>

    <!-- Pagination -->
    <?php if ($totalPages > 1): ?>
        <section class="mt-4">
            <nav>
                <ul class="pagination justify-content-center">
                    <?php if ($page > 1): ?>
                        <li class="page-item"><a class="page-link" href="?page=<?php echo $page - 1; ?>">&#171;</a></li>
                    <?php endif; ?>
                    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                        <li class="page-item <?php echo ($i == $page) ? 'active' : ''; ?>">
                            <a class="page-link" href="?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                        </li>
                    <?php endfor; ?>
                    <?php if ($page < $totalPages): ?>
                        <li class="page-item"><a class="page-link" href="?page=<?php echo $page + 1; ?>">&#187;</a></li>
                    <?php endif; ?>
                </ul>
            </nav>
        </section>
    <?php endif; ?>
</div>

<?php require_once '../includes/footer.php'; ?>
