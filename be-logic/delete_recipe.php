<?php
/* delete_recipe.php
    * This file handles the deletion of a recipe.
    * It checks if the user is logged in and if they have permission to delete the recipe.
    * If the checks pass, it deletes the associated image file and then deletes the recipe from the database.
*/


require_once __DIR__ . '/db.php';
require_once __DIR__ . '/protected_page.php';


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['recipe_id'])) {
    // Get the recipe ID from the POST data
    $recipeId = $_POST['recipe_id'];
    $user_id = $_SESSION['username'];    // get the owner and image path of the recipe
    try {
        $stmt = $pdo->prepare('SELECT user_id, image_path FROM recipes WHERE id = ?');
        $stmt->execute([$recipeId]);
        $recipeData = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$recipeData) {
            header('Location: ../index.php');
            exit();
        }
        
        $recipeOwner = $recipeData['user_id'];
        $imagePath = $recipeData['image_path'];
    } catch (PDOException $e) {
        error_log("Database error: " . $e->getMessage());
        header('Location: ../index.php');
        exit();
    }
    if ($recipeId && $recipeOwner == $user_id) {
        // Delete the associated image file if it exists
        if (!empty($imagePath)) {
            $fullImagePath = __DIR__ . '/../' . $imagePath;
            if (file_exists($fullImagePath)) {
                if (!unlink($fullImagePath)) {
                    error_log("Failed to delete image file: " . $fullImagePath);
                    // Continue with recipe deletion even if image deletion fails
                }
            }
        }
        
        // Delete the recipe from database
        try {
            $stmt = $pdo->prepare('DELETE FROM recipes WHERE id = ?');
            $stmt->execute([$recipeId]);
            // Redirect to the user's profile page after deletion
            header('Location: ../profile.php');
        } catch (PDOException $e) {
            error_log("Database error: " . $e->getMessage());
            header('Location: ../recipe.php?id=' . $recipeId);
            exit();
        }    }
} else {
    header('Location: ../profile.php?');
    exit();
}
