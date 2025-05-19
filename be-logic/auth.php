// This file handles user authentication and session management
// add require_once 'auth.php'; to protected pages

<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

?>