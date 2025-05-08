<?php
// return if the user is not found or password is incorrect
if (!isset($_SESSION["user_id"])) {
    header("Location: index.php?view=login");
    exit;
}
?>