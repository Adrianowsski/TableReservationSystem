<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit;
}
require_once '../config/config.php';

$userId    = $_SESSION['user_id'];
$errors    = [];
$success   = "";

// Function to append seconds to HH:MM format
function formatTime($time) {
    return (strlen($time) === 5) ? $time . ':00' : $time;
}

// Function to generate time options in 15-minute intervals between 13:00 and 22:00
function generateTimeOptions($selectedTime = "") {
    $options = "";
    $start   = new DateTime("13:00");
    $end     = new DateTime("22:00");
    while ($start <= $end) {
        $timeValue = $start->format("H:i");
        $selected  = ($timeValue === $selectedTime) ? ' selected' : '';
        $options  .= "<option value=\"$timeValue\"$selected>$timeValue</option>\n";
        $start->modify("+15 minutes");
    }
    return $options;
}

$reservationDate = $_POST['reservation_date'] ?? "";
$reservationTime = $_POST['reservation_time'] ?? "";
$numOfPeople     = isset($_POST['num_of_people']) ? (int)$_POST['num_of_people'] : 0;
$action          = $_POST['action'] ?? "";

$availableTables = [];

// Form handling
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validation
    if (empty($reservationDate)) {
        $errors['reservation_date'] = "Please select a reservation date.";
    }
    if (empty($reservationTime)) {
        $errors['reservation_time'] = "Please select a reservation time.";
    }
    if ($numOfPeople <= 0) {
        $errors['num_of_people'] = "Please enter a valid number of people.";
    }

    if (!empty($reservationTime)) {
        $timeFormatted = formatTime($reservationTime);
        if ($timeFormatted < "13:00:00" || $timeFormatted > "22:00:00") {
            $errors['reservation_time'] = "Reservations can only be made between 13:00 and 22:00.";
        }
    }

    if (!empty($reservationDate) && !empty($reservationTime)) {
        $currentDateTime = new DateTime();
        $chosenDateTime  = new DateTime("$reservationDate " . formatTime($reservationTime));
        if ($chosenDateTime < $currentDateTime) {
            $errors['reservation_date'] = "You cannot make a reservation in the past.";
        }
    }

    // Step 1: Check available tables
    if ($action === 'check' && empty($errors)) {
        // Determine required table size
        if ($numOfPeople >= 1 && $numOfPeople <= 6) {
            $requiredSeats = $numOfPeople;
        } elseif ($numOfPeople == 7 || $numOfPeople == 8) {
            $requiredSeats = 8;
        } else {
            $errors['num_of_people'] = "We do not have a table for this many people.";
        }

        if (empty($errors)) {
            $timeParam = formatTime($reservationTime);
            $query = "
                SELECT * FROM tables
                WHERE seats = :req_seats
                  AND id NOT IN (
                    SELECT table_id 
                    FROM reservations
                    WHERE reservation_date = :res_date
                      AND status != 'cancelled'
                      AND (
                        (STR_TO_DATE(:res_time, '%H:%i:%s') < ADDTIME(reservation_time, '01:30:00'))
                        AND (ADDTIME(:res_time, '01:30:00') > reservation_time)
                      )
                  )
            ";
            $stmt = $pdo->prepare($query);
            $stmt->execute([
                'req_seats' => $requiredSeats,
                'res_date'  => $reservationDate,
                'res_time'  => $timeParam
            ]);
            $availableTables = $stmt->fetchAll(PDO::FETCH_ASSOC);
            if (!$availableTables) {
                $errors['general'] = "No tables available for the selected time and number of people.";
            }
        }
    }
    // Step 2: Make reservation
    elseif ($action === 'reserve' && empty($errors)) {
        $tableId = isset($_POST['table_id']) ? (int)$_POST['table_id'] : 0;
        if (empty($tableId)) {
            $errors['table_id'] = "Please select a table from the available options.";
        }
        if (empty($errors)) {
            $timeParam = formatTime($reservationTime);
            // Check availability again
            $stmt = $pdo->prepare("
                SELECT COUNT(*) FROM reservations
                WHERE table_id = :tbl
                  AND reservation_date = :res_date
                  AND status != 'cancelled'
                  AND (
                    (STR_TO_DATE(:res_time, '%H:%i:%s') < ADDTIME(reservation_time, '01:30:00'))
                    AND (ADDTIME(:res_time, '01:30:00') > reservation_time)
                  )
            ");
            $stmt->execute([
                'tbl'      => $tableId,
                'res_date' => $reservationDate,
                'res_time' => $timeParam
            ]);
            $count = $stmt->fetchColumn();
            if ($count > 0) {
                $errors['table_id'] = "The selected table is no longer available.";
            }

            if (empty($errors)) {
                $insert = $pdo->prepare("
                    INSERT INTO reservations (user_id, table_id, reservation_date, reservation_time, num_of_people)
                    VALUES (:u, :t, :d, :h, :n)
                ");
                $insert->execute([
                    'u' => $userId,
                    't' => $tableId,
                    'd' => $reservationDate,
                    'h' => $timeParam,
                    'n' => $numOfPeople
                ]);
                $success = "Your reservation has been saved and is awaiting confirmation.";

                // Reset fields
                $reservationDate = "";
                $reservationTime = "";
                $numOfPeople     = 0;
            }
        }
    }
}

// Fetch user reservations
$stmt = $pdo->prepare("
    SELECT r.*, t.table_name
    FROM reservations r
    JOIN tables t ON r.table_id = t.id
    WHERE r.user_id = :uid
    ORDER BY r.reservation_date DESC, r.reservation_time DESC
");
$stmt->execute(['uid' => $userId]);
$userReservations = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Set title and custom style
$pageTitle = "Table Reservations";
$customCss = "reservations.css";

// Load common header
require_once '../includes/header.php';
?>


    <div class="container">
        <h1 class="mt-4">Table Reservations</h1>
        <p>
            <a href="index.php" class="btn btn-secondary me-2">
                <i class="bi bi-house-door-fill"></i> User Panel
            </a>
            <a href="../logout.php" class="btn btn-danger">
                <i class="bi bi-box-arrow-right"></i> Logout
            </a>
        </p>

        <!-- General message (e.g., no tables available) -->
        <?php if (isset($errors['general'])): ?>
            <div class="alert alert-danger">
                <?php echo $errors['general']; ?>
            </div>
        <?php endif; ?>

        <?php if ($success): ?>
            <div class="alert alert-success">
                <?php echo $success; ?>
            </div>
        <?php endif; ?>

        <!-- Form for checking table availability -->
        <?php if ($action !== 'reserve' || empty($availableTables)): ?>
            <h2>Check Table Availability</h2>
            <form action="" method="post" class="row g-3 mb-3">
                <div class="col-md-4">
                    <label for="reservation_date" class="form-label">Reservation Date:</label>
                    <input type="date"
                           name="reservation_date"
                           id="reservation_date"
                           class="form-control <?php echo isset($errors['reservation_date']) ? 'is-invalid' : ''; ?>"
                           value="<?php echo htmlspecialchars($reservationDate); ?>"
                           required>
                    <?php if (isset($errors['reservation_date'])): ?>
                        <div class="invalid-feedback">
                            <?php echo $errors['reservation_date']; ?>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="col-md-4">
                    <label for="reservation_time" class="form-label">Reservation Time (24h):</label>
                    <select name="reservation_time"
                            id="reservation_time"
                            class="form-control <?php echo isset($errors['reservation_time']) ? 'is-invalid' : ''; ?>"
                            required>
                        <option value="">-- Select Time --</option>
                        <?php echo generateTimeOptions($reservationTime); ?>
                    </select>
                    <?php if (isset($errors['reservation_time'])): ?>
                        <div class="invalid-feedback">
                            <?php echo $errors['reservation_time']; ?>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="col-md-4">
                    <label for="num_of_people" class="form-label">Number of People:</label>
                    <input type="number"
                           name="num_of_people"
                           id="num_of_people"
                           min="1"
                           class="form-control <?php echo isset($errors['num_of_people']) ? 'is-invalid' : ''; ?>"
                           value="<?php echo htmlspecialchars($numOfPeople); ?>"
                           required>
                    <?php if (isset($errors['num_of_people'])): ?>
                        <div class="invalid-feedback">
                            <?php echo $errors['num_of_people']; ?>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="col-12">
                    <button type="submit" name="action" value="check" class="btn btn-turquoise">
                        <i class="bi bi-search"></i> Check Availability
                    </button>
                </div>
            </form>
        <?php endif; ?>

        <!-- List of available tables -->
        <?php if ($action === 'check' && empty($errors) && !empty($availableTables)): ?>
            <h2>Available Tables</h2>
            <form action="" method="post">
                <!-- Hidden fields -->
                <input type="hidden" name="reservation_date" value="<?php echo htmlspecialchars($reservationDate); ?>">
                <input type="hidden" name="reservation_time" value="<?php echo htmlspecialchars($reservationTime); ?>">
                <input type="hidden" name="num_of_people" value="<?php echo htmlspecialchars($numOfPeople); ?>">

                <div class="mb-3">
                    <label for="table_id" class="form-label">Select a Table:</label>
                    <select name="table_id"
                            id="table_id"
                            class="form-select <?php echo isset($errors['table_id']) ? 'is-invalid' : ''; ?>"
                            required>
                        <option value="">-- Select Table --</option>
                        <?php foreach ($availableTables as $table): ?>
                            <option value="<?php echo $table['id']; ?>">
                                <?php
                                echo htmlspecialchars($table['table_name'])
                                    . " ({$table['seats']} seats, "
                                    . htmlspecialchars($table['location'])
                                    . ")";
                                ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <?php if (isset($errors['table_id'])): ?>
                        <div class="invalid-feedback">
                            <?php echo $errors['table_id']; ?>
                        </div>
                    <?php endif; ?>
                </div>
                <button type="submit" name="action" value="reserve" class="btn btn-success">
                    <i class="bi bi-check-circle-fill"></i> Confirm Reservation
                </button>
            </form>
        <?php endif; ?>

        <!-- User reservations summary -->
        <h2 class="mt-5">Your Reservations</h2>
        <?php if (!$userReservations): ?>
            <p>You have no reservations yet.</p>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>Table</th>
                        <th>Date</th>
                        <th>Time</th>
                        <th>Number of People</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($userReservations as $res): ?>
                        <tr>
                            <td><?php echo (int)$res['id']; ?></td>
                            <td><?php echo htmlspecialchars($res['table_name']); ?></td>
                            <td><?php echo htmlspecialchars($res['reservation_date']); ?></td>
                            <td><?php echo htmlspecialchars($res['reservation_time']); ?></td>
                            <td><?php echo (int)$res['num_of_people']; ?></td>
                            <td><?php echo htmlspecialchars($res['status']); ?></td>
                            <td>
                                <!-- Show "Cancel" button only if the reservation is not already canceled -->
                                <?php if ($res['status'] !== 'cancelled'): ?>
                                    <a href="cancel.php?id=<?php echo $res['id']; ?>"
                                       class="btn btn-sm btn-danger"
                                       onclick="return confirm('Are you sure you want to cancel this reservation?');">
                                        <i class="bi bi-x-circle-fill"></i> Cancel
                                    </a>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>

<?php
// Load footer
require_once '../includes/footer.php';

