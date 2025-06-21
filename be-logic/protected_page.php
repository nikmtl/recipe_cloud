<?php
/* protected_page.php
    * This file checks if the user is logged in and redirects to the login page if not.
    * This is just for simple protected pages. For pages that require a spesific account use extra logic.
    * To use this: include this file at the start of your PHP document to protect the page.
*/

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['username'])) {
    header('Location: login.php?msg=To access this page, please log in.');
    exit;
}

?>