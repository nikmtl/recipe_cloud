<?php
/*delete_account.php
    * Handles permanently deleting user account and all associated data
*/

require_once 'protected_page.php';
require_once 'db.php';


// Check if form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_SESSION['username'];
    
    try {
        // Start transaction to ensure all deletions happen together
        $pdo->beginTransaction();
          // Get all recipe IDs and image paths belonging to the user for cleanup
        $stmt = $pdo->prepare("SELECT id, image_path FROM recipes WHERE user_id = ?");
        $stmt->execute([$username]);
        $recipes = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $recipeIds = array_column($recipes, 'id');
        
        // Delete recipe images from file system
        foreach ($recipes as $recipe) {
            if (!empty($recipe['image_path'])) {
                $imagePath = '../uploads/recipes/' . basename($recipe['image_path']);
                if (file_exists($imagePath)) {
                    unlink($imagePath);
                }
            }
        }
        
        // Delete user's favorites (will be handled by CASCADE but being explicit)
        $stmt = $pdo->prepare("DELETE FROM favorites WHERE user_id = ?");
        $stmt->execute([$username]);
        
        // Delete ratings given by the user
        $stmt = $pdo->prepare("DELETE FROM ratings WHERE user_id = ?");
        $stmt->execute([$username]);
        
        // Delete ingredients for user's recipes (CASCADE will handle this)
        if (!empty($recipeIds)) {
            $placeholders = str_repeat('?,', count($recipeIds) - 1) . '?';
            $stmt = $pdo->prepare("DELETE FROM ingredients WHERE recipe_id IN ($placeholders)");
            $stmt->execute($recipeIds);
            
            // Delete instructions for user's recipes (CASCADE will handle this)
            $stmt = $pdo->prepare("DELETE FROM instructions WHERE recipe_id IN ($placeholders)");
            $stmt->execute($recipeIds);
            
            // Delete ratings for user's recipes (CASCADE will handle this)
            $stmt = $pdo->prepare("DELETE FROM ratings WHERE recipe_id IN ($placeholders)");
            $stmt->execute($recipeIds);
            
            // Delete favorites for user's recipes (CASCADE will handle this)
            $stmt = $pdo->prepare("DELETE FROM favorites WHERE recipe_id IN ($placeholders)");
            $stmt->execute($recipeIds);
        }
        
        // Delete user's recipes (CASCADE will handle related data)
        $stmt = $pdo->prepare("DELETE FROM recipes WHERE user_id = ?");
        $stmt->execute([$username]);
        
        // Finally, delete the user account
        $stmt = $pdo->prepare("DELETE FROM users WHERE username = ?");
        $stmt->execute([$username]);
        
        // Commit the transaction
        $pdo->commit();
        
        // Destroy session and redirect to home page
        session_destroy();
        header('Location: ../index.php?message=account_deleted');
        exit;
        
    } catch (PDOException $e) {
        // Rollback transaction on error
        $pdo->rollback();
        error_log("Database error in delete_account.php: " . $e->getMessage());
        header('Location: ../settings.php?error=delete_failed');
        exit;
    }
} else {
    // If not POST request, redirect to settings
    header('Location: ../settings.php');
    exit;
}
?>
