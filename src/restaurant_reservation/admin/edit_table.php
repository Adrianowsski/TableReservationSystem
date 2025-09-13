<?php
// admin/edit_table.php
require_once '../includes/functions.php';
ensureAdmin();
require_once '../config/config.php';

$csrfToken = getCsrfToken();
$successMsg = "";
$errorMsg = "";

// Retrieve the table ID from the GET parameter
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: tables.php");
    exit;
}

$tableId = (int)$_GET['id'];

// Fetch table data
$stmt = $pdo->prepare("SELECT * FROM tables WHERE id = :id");
$stmt->execute(['id' => $tableId]);
$table = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$table) {
    header("Location: tables.php");
    exit;
}

// Handle update (POST)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['csrf_token']) || !checkCsrfToken($_POST['csrf_token'])) {
        $errorMsg = "Security verification error (CSRF token).";
    } else {
        $tableName = trim($_POST['table_name']);
        $seats = (int)$_POST['seats'];
        $location = trim($_POST['location']);

        if (empty($tableName)) {
            $errorMsg = "Table name cannot be empty.";
        } elseif ($seats <= 0) {
            $errorMsg = "The number of seats must be greater than 0.";
        } else {
            try {
                $stmtUpdate = $pdo->prepare("UPDATE tables SET table_name = :tn, seats = :s, location = :loc WHERE id = :id");
                $stmtUpdate->execute([
                    'tn'  => $tableName,
                    's'   => $seats,
                    'loc' => $location,
                    'id'  => $tableId
                ]);
                $successMsg = "The table has been successfully updated.";
                // Refresh table data from the database
                $stmt = $pdo->prepare("SELECT * FROM tables WHERE id = :id");
                $stmt->execute(['id' => $tableId]);
                $table = $stmt->fetch(PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                $errorMsg = "An error occurred while updating the table.";
            }
        }
    }
}

$pageTitle = "Edit Table";
$customCss = "admin_tables.css";
require_once '../includes/header.php';
?>

<div class="container mt-4">
    <h1 class="mb-3">Edit Table</h1>

    <?php if (!empty($successMsg)): ?>
        <div class="alert alert-success alert-dismissible fade show"><?php echo htmlspecialchars($successMsg); ?></div>
    <?php endif; ?>
    <?php if (!empty($errorMsg)): ?>
        <div class="alert alert-danger alert-dismissible fade show"><?php echo htmlspecialchars($errorMsg); ?></div>
    <?php endif; ?>

    <form action="" method="post" class="row g-3">
        <input type="hidden" name="csrf_token" value="<?php echo $csrfToken; ?>">
        <div class="col-md-4">
            <label for="table_name" class="form-label">Table Name / Number:</label>
            <input type="text" id="table_name" name="table_name" class="form-control" required value="<?php echo htmlspecialchars($table['table_name']); ?>">
        </div>
        <div class="col-md-4">
            <label for="seats" class="form-label">Number of Seats:</label>
            <input type="number" id="seats" name="seats" class="form-control" min="1" required value="<?php echo (int)$table['seats']; ?>">
        </div>
        <div class="col-md-4">
            <label for="location" class="form-label">Location:</label>
            <input type="text" id="location" name="location" class="form-control" value="<?php echo htmlspecialchars($table['location']); ?>">
        </div>
        <div class="col-12">
            <button type="submit" class="btn btn-primary"><i class="bi bi-pencil-square"></i> Update Table</button>
            <a href="tables.php" class="btn btn-secondary">Back to List</a>
        </div>
    </form>
</div>

<?php require_once '../includes/footer.php'; ?>
