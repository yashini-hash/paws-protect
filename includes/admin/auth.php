<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (
    empty($_SESSION['user_id']) ||
    empty($_SESSION['role']) ||
    $_SESSION['role'] !== 'admin'
) {
    session_unset();
    session_destroy();
    header("Location: /paws&protect/login.php");
    exit();
}
