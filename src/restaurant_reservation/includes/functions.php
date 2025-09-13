<?php
// includes/functions.php

/**
 * Starts a session if it hasn't been started yet.
 */
function ensureSessionStarted() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
}

/**
 * Checks if the user is logged in as an admin.
 * If not, redirects to login.php.
 */
function ensureAdmin() {
    ensureSessionStarted();
    if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
        header("Location: ../login.php");
        exit;
    }
}

/**
 * Checks if the user is logged in as a regular user.
 */
function ensureUser() {
    ensureSessionStarted();
    if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'user') {
        header("Location: ../login.php");
        exit;
    }
}

/**
 * Retrieves the CSRF token. If the token hasn't been generated yet,
 * generates a new one and saves it in the session.
 *
 * @return string CSRF token.
 */
function getCsrfToken() {
    ensureSessionStarted();
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

/**
 * Verifies if the submitted CSRF token is valid.
 *
 * @param string $token Token submitted from the form.
 * @return bool True if the token is valid, otherwise false.
 */
function checkCsrfToken($token) {
    ensureSessionStarted();
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

/**
 * Renders the HTML for the reservation status.
 *
 * @param string $status Reservation status (e.g., "pending", "confirmed", "cancelled").
 * @return string HTML element with the appropriate class (badge).
 */
function renderReservationStatus($status) {
    switch ($status) {
        case 'pending':
            return '<span class="badge bg-warning text-dark">Pending</span>';
        case 'confirmed':
            return '<span class="badge bg-success">Confirmed</span>';
        case 'cancelled':
            return '<span class="badge bg-danger">Cancelled</span>';
        default:
            return '<span class="badge bg-secondary">' . htmlspecialchars($status) . '</span>';
    }
}

/**
 * Calculates the offset for pagination based on the current page number
 * and the number of records per page.
 *
 * @param int $page Current page number.
 * @param int $limit Number of records per page.
 * @return int Offset for SQL queries.
 */
function getPaginationOffset($page, $limit) {
    return ($page - 1) * $limit;
}
