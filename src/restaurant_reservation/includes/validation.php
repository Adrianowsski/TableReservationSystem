<?php
// includes/validation.php

function validateEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

function validateText($text) {
    return !empty(trim($text));
}

function validatePassword($password) {
    return strlen($password) >= 8;
}
