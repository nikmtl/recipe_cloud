<?php
/*update_account.php
    * Handles updating user account information
*/

require_once 'protected_page.php';
require_once 'db.php';

// Check if form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_SESSION['username'];
    $first_name = trim($_POST['first_name']);
    $last_name = trim($_POST['last_name']);
    $email = trim($_POST['email']);
    $bio = trim($_POST['bio']);
    
    // Validate input
    if (empty($first_name) || empty($last_name) || empty($email)) {
        header('Location: ../settings.php');
        exit;
    }
    
    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        header('Location: ../settings.php');
        exit;
    }
    
    try {
        // Check if email is already taken by another user
        $stmt = $pdo->prepare("SELECT username FROM users WHERE email = ? AND username != ?");
        $stmt->execute([$email, $username]);
        if ($stmt->fetch()) {
            header('Location: ../settings.php?error=email_taken');
            exit;
        }
        
        // Update user information
        $stmt = $pdo->prepare("
            UPDATE users 
            SET first_name = ?, last_name = ?, email = ?, bio = ? 
            WHERE username = ?
        ");
        $stmt->execute([$first_name, $last_name, $email, $bio, $username]);
        
        // Redirect back to settings page
        header('Location: ../settings.php');
        exit;
        
    } catch (PDOException $e) {
        error_log("Database error in update_account.php: " . $e->getMessage());
        header('Location: ../settings.php');
        exit;
    }
} else {
    // If not POST request, redirect to settings
    header('Location: ../settings.php');
    exit;
}
?>
