<?php
/* account.php
    * This file handles user account management.
    * It handles user profile Updating, password changes, user data export, and account deletion.
    Sections in this file:
    * 1. Update Profile
    * 2. Change Password
    * 3. Export User Data
    * 4. Delete Account
*/

require_once __DIR__ . '/../db.php'; // Include database connection
require_once __DIR__ . '/../protected_page.php'; // Include session management

// Call the appropriate function based on the action
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update_profile') {
    updateProfile($pdo);
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'change_password') {
    changePassword($pdo);
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'export_data') {
    exportUserData($pdo);
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete_account') {
    deleteAccount($pdo);
} else { // Invalid action if no valid POST request or action unknown
    http_response_code(400); // Bad Request
    die("Bad Request: Invalid action.");
}


/* 1. Update Profile function */
// Function to handle profile update
function updateProfile($pdo)
{
    $username = $_SESSION['username'];
    $first_name = trim($_POST['first_name'] ?? '');
    $last_name = trim($_POST['last_name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $bio = trim($_POST['bio'] ?? '');

    // Validate inputs
    $errors = [];
    if (empty($email)) $errors['email'] = 'Email is required.';
    if (strlen($first_name) > 50) $errors['first_name'] = 'First name cannot exceed 50 characters.';
    if (strlen($last_name) > 50) $errors['last_name'] = 'Last name cannot exceed 50 characters.';
    if (strlen($email) > 50) $errors['email'] = 'Email cannot exceed 50 characters.';
    if (strlen($bio) > 500) $errors['bio'] = 'Bio cannot exceed 500 characters.';
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors['email'] = 'Invalid email format.';

    // Check for errors
    if (!empty($errors)) {
        $_SESSION['errors'] = $errors;
        $_SESSION['response_code'] = 422; // Unprocessable Entity - validation errors
        header('Location: ../../settings#profile-section');
        exit;
    }

    // If no errors, proceed with updating the profile
    try {
        // Check if email is already taken by another user
        $stmt = $pdo->prepare("SELECT username FROM users WHERE email = ? AND username != ?");
        $stmt->execute([$email, $username]);
        if ($stmt->fetch()) {
            $_SESSION['errors'] = ['email' => 'Email is already taken by another account.'];
            $_SESSION['response_code'] = 422; // Unprocessable Entity - validation error
            header('Location: ../../settings#profile-section');
            exit;
        }

        // Update only the changed user information
        // Get current user data
        $stmt = $pdo->prepare("SELECT first_name, last_name, email, bio FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $currentData = $stmt->fetch(PDO::FETCH_ASSOC);

        // Build dynamic update query
        $updateFields = [];
        $params = [];

        if ($first_name !== $currentData['first_name']) {
            $updateFields[] = "first_name = ?";
            $params[] = $first_name;
        }

        if ($last_name !== $currentData['last_name']) {
            $updateFields[] = "last_name = ?";
            $params[] = $last_name;
        }

        if ($email !== $currentData['email']) {
            $updateFields[] = "email = ?";
            $params[] = $email;
        }

        if ($bio !== $currentData['bio']) {
            $updateFields[] = "bio = ?";
            $params[] = $bio;
        }

        // Update the user profile if there are any changes dynamically
        if (!empty($updateFields)) {
            $query = "UPDATE users SET " . implode(", ", $updateFields) . " WHERE username = ?";
            $params[] = $username;

            $stmt = $pdo->prepare($query);
            $stmt->execute($params);
        }

        // Redirect back to settings page
        $_SESSION['response_code'] = 200; // OK
        header('Location: ../../settings#profile-section');
        exit;
    } catch (PDOException $e) {
        error_log("Database error while updating account: " . $e->getMessage());
        $_SESSION['response_code'] = 500; // Internal Server Error
        header('Location: ../../settings#profile-section');
        exit;
    }
}

/* * 2. Change Password function
 * This function will handle changing the user's password.
 * It will validate the current password, check the new password strength, and update it in the database.
 */
// function to change password
function changePassword($pdo)
{
    $username = $_SESSION['username'];

    try {
        $currentPassword = $_POST['current_password'];
        $newPassword = $_POST['new_password'];

        // Validate current password
        $stmt = $pdo->prepare("SELECT password_hash FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user || !password_verify($currentPassword, $user['password_hash'])) {
            $_SESSION['errors'] = ['current_password' => 'Current password is incorrect.'];
            $_SESSION['response_code'] = 422; // Unprocessable Entity - validation error
            header('Location: ../../settings#password-section');
            exit;
        }

        // Validate new password
        if (strlen($newPassword) < 8) {
            $_SESSION['errors'] = ['new_password' => 'New password must be at least 8 characters long.'];
            $_SESSION['response_code'] = 422; // Unprocessable Entity - validation error
            header('Location: ../../settings#password-section');
            exit;
        } elseif (strlen($newPassword) > 32) {
            $_SESSION['errors'] = ['new_password' => 'New password cannot exceed 32 characters.'];
            $_SESSION['response_code'] = 422; // Unprocessable Entity - validation error
            header('Location: ../../settings#password-section');
            exit;
        }

        // Update password
        $stmt = $pdo->prepare(query: "UPDATE users SET password_hash = ? WHERE username = ?");
        $stmt->execute([password_hash($newPassword, PASSWORD_DEFAULT), $username]);

        $_SESSION['response_code'] = 200; // OK
        header('Location: ../../settings#password-section');
        exit;
    } catch (PDOException $e) {
        error_log("Database error while updating password: " . $e->getMessage());
        $_SESSION['response_code'] = 500; // Internal Server Error
        header('Location: ../../settings#password-section');
        exit;
    }
}

/* 3. Export User Data function 
    Userdata includes:
    - User profile information (username, first name, last name, email, bio)
    - Recipes created by the user (recipe ID, title, description, ingredients, instructions, creation date)
    - User's favorite recipes (recipe ID, title, description)
    - User's ratings for recipes (recipe ID, rating, comment, date)
*/
// Function to handle user data export
function exportUserData($pdo)
{
    $currentUser = $_SESSION['username'];
    $userData = [];

    try {
        // 1. Get user profile information
        $stmt = $pdo->prepare("SELECT first_name, last_name, username, email, bio, profile_image, created_at FROM users WHERE username = ?");
        $stmt->execute([$currentUser]);
        $userData['profile'] = $stmt->fetch(PDO::FETCH_ASSOC);

        // 2. Get recipes created by the user with ingredients and instructions
        $stmt = $pdo->prepare("SELECT * FROM recipes WHERE user_id = ?");
        $stmt->execute([$currentUser]);
        $recipes = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $userData['recipes'] = [];
        foreach ($recipes as $recipe) {
            $recipeData = $recipe;

            // Get ingredients for this recipe
            $stmt = $pdo->prepare("SELECT * FROM ingredients WHERE recipe_id = ?");
            $stmt->execute([$recipe['id']]);
            $recipeData['ingredients'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Get instructions for this recipe
            $stmt = $pdo->prepare("SELECT * FROM instructions WHERE recipe_id = ? ORDER BY step_number");
            $stmt->execute([$recipe['id']]);
            $recipeData['instructions'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $userData['recipes'][] = $recipeData;
        }

        // 3. Get user's favorite recipes
        $stmt = $pdo->prepare("
        SELECT r.id, r.title, r.description, r.user_id 
        FROM favorites f 
        JOIN recipes r ON f.recipe_id = r.id 
        WHERE f.user_id = ?
    ");
        $stmt->execute([$currentUser]);
        $userData['favorites'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // 4. Get user's ratings for recipes
        $stmt = $pdo->prepare("
        SELECT r.id as recipe_id, rec.title as recipe_title, r.rating, r.comment_text, r.created_at 
        FROM ratings r 
        JOIN recipes rec ON r.recipe_id = rec.id 
        WHERE r.user_id = ?
    ");
        $stmt->execute([$currentUser]);
        $userData['ratings'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Add export timestamp
        $userData['exported_at'] = date('Y-m-d H:i:s');

        // Set headers for JSON download
        header('Content-Type: application/json');
        header('Content-Disposition: attachment; filename="user_data_' . $currentUser . '_' . date('Y-m-d') . '.json"');
        header('Cache-Control: no-cache, must-revalidate');
        header('Expires: 0');

        // Output the JSON data
        echo json_encode($userData, JSON_PRETTY_PRINT);
        $_SESSION['response_code'] = 200; // OK
        exit;
    } catch (Exception $e) {
        // If there's an error, redirect to settings with error message
        // Redirect back to settings page with error message
        error_log("Error exporting user data: " . $e->getMessage());
        $_SESSION['errors'] = ['export' => 'An error occurred while exporting your data. Please try again later.'];
        $_SESSION['response_code'] = 500; // Internal Server Error
        header('Location: ../../settings#account-actions-section');
        exit;
    }
}


/* 4. Delete Account function
    This function will handle permanently deleting the user's account. 
*/
function deleteAccount($pdo){
    $username = $_SESSION['username'];
    try {
        // Start transaction to ensure all deletions happen together
        $pdo->beginTransaction();
        // Delete user's favorites (will be handled by CASCADE but being explicit)
        $stmt = $pdo->prepare("DELETE FROM favorites WHERE user_id = ?");
        $stmt->execute([$username]);

        // Delete ratings given by the user
        $stmt = $pdo->prepare("DELETE FROM ratings WHERE user_id = ?");
        $stmt->execute([$username]);

        // Finally, delete the user account
        $stmt = $pdo->prepare("DELETE FROM users WHERE username = ?");
        $stmt->execute([$username]);

        // Commit the transaction
        $pdo->commit();

        // Destroy session and redirect to home page
        session_destroy();
        // Note: Can't set session variables after session_destroy, 
        // so we'll rely on the redirect to home page without a toast
        $_SESSION['response_code'] = 200; // OK
        header('Location: ../../');
        exit;
    } catch (PDOException $e) {
        // Rollback transaction on error
        $pdo->rollback();
        error_log("Database error in delete_account.php: " . $e->getMessage());
        $_SESSION['errors'] = ['general_account_actions' => 'An error occurred while deleting your account. Please try again later.'];
        $_SESSION['response_code'] = 500; // Internal Server Error
        header('Location: ../../settings#account-actions-section');
        exit;
    }
}
