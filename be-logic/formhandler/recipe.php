<?php
/* recipe.php
    * This file handles recipe management.
    * It handles recipe creation, updating, deletion.
    Sections in this file:
    * 1. Create Recipe
    * 2. Update Recipe
    * 3. Delete Recipe
*/

require_once __DIR__ . '/../db.php'; // Include database connection
require_once __DIR__ . '/../protected_page.php'; // Include session management

// Call the appropriate function based on the action
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'create_recipe') {
    die("Create recipe function not implemented yet.");
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update_recipe') {
    die("Update recipe function not implemented yet.");
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete_recipe') {
    deleteRecipe($pdo);
} else { // Invalid action if no valid POST request or action unknown
    http_response_code(400); // Bad Request
    die("Bad Request: Invalid action.");
}

/* 3. Delete Recipe Function
    * This function handles the deletion of a recipe.
    * It checks if the user is logged in and if they have permission to delete the recipe.
    * If the checks pass, it deletes the associated image file and then deletes the recipe from the database.
*/
// Function to handle recipe deletion
function deleteRecipe($pdo){
    $recipeId = $_POST['recipe_id'];
    $user_id = $_SESSION['username'];

    // Fetch the recipe data to get ownership and get image path
    try {
        $stmt = $pdo->prepare('SELECT user_id, image_path FROM recipes WHERE id = ?');
        $stmt->execute([$recipeId]);
        $recipeData = $stmt->fetch(PDO::FETCH_ASSOC);

        // If no recipe found redirect to home
        if (!$recipeData) {
            http_response_code(404); // Not Found
            header('Location: ../../');
            exit();
        }
    } catch (PDOException $e) {
        error_log("Database error while fetching recipe data: " . $e->getMessage());
        http_response_code(500); // Internal Server Error
        header('Location: ../../recipe?id=' . $recipeId);
        exit();
    }

    $recipeOwner = $recipeData['user_id'];
    $imagePath = $recipeData['image_path'];

    // Check if the logged-in user is the owner of the recipe then delete it
    if ($recipeOwner == $user_id) { 
        // Delete the associated image file if it exists
        if (!empty($imagePath)) {
            try {
                $fullImagePath = __DIR__ . '/../../' . $imagePath;
                if (file_exists($fullImagePath)) {
                    if (!unlink($fullImagePath)) {
                        error_log("Failed to delete image file while deleting recipe: " . $fullImagePath);
                        // Continue with recipe deletion even if image deletion fails
                    }
                }
            } catch (Exception $e) {
                error_log("Error deleting image file while deleting recipe: " . $e->getMessage());
            }
        }

        // Delete the recipe from database
        try {
            $stmt = $pdo->prepare('DELETE FROM recipes WHERE id = ?');
            $stmt->execute([$recipeId]);
            // Redirect to the user's profile page after deletion
            http_response_code(200); // OK
            header('Location: ../../profile');
        } catch (PDOException $e) {
            error_log("Database error while deleting recipe: " . $e->getMessage());
            http_response_code(500); // Internal Server Error
            header('Location: ../../recipe?id=' . $recipeId);
            exit();
        }
    } else {
        http_response_code(403); // Forbidden
        die("You do not have permission to delete this recipe.");
    }
}
