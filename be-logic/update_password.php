<?php
/*update_password.php
    * Handles updating user password
*/

require_once 'protected_page.php';
require_once 'db.php';


// Check if form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_SESSION['username'];
    
    try {
        $currentPassword = $_POST['current_password'];
        $newPassword = $_POST['new_password'];

        // Validate current password
        $stmt = $pdo->prepare("SELECT password_hash FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user || !password_verify($currentPassword, $user['password_hash'])) {
            header('Location: ../settings.php?error=invalid_password');
            exit;
        }

        // Update password
        $stmt = $pdo->prepare(query: "UPDATE users SET password_hash = ? WHERE username = ?");
        $stmt->execute([password_hash($newPassword, PASSWORD_DEFAULT), $username]);

        header('Location: ../settings.php?message=password_updated');
        exit;
    } catch (PDOException $e) {
        error_log("Database error in update_password.php: " . $e->getMessage());
        header('Location: ../settings.php');
        exit;
    }
} else {
    // If not POST request, redirect to settings
    header('Location: ../settings.php?error=invalid_request');
    exit;
}
?>
