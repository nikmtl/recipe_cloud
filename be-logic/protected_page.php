<!-- protected_page.php 
* This file handles user authentication and session management
    add require_once 'protected_page.php '; to protected pages
-->
<?php
session_start();

if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

?>