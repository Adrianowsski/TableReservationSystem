<?php
// includes/messages.php
require_once 'functions.php';

function setMessage($type, $message) {
    ensureSessionStarted();
    $_SESSION['messages'][$type][] = $message;
}

function displayMessages() {
    ensureSessionStarted();
    if (!empty($_SESSION['messages'])) {
        foreach ($_SESSION['messages'] as $type => $messages) {
            foreach ($messages as $msg) {
                echo '<div class="alert alert-' . htmlspecialchars($type) . '">' . htmlspecialchars($msg) . '</div>';
            }
        }
        unset($_SESSION['messages']);
    }
}
