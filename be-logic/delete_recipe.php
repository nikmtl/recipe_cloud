<!-- delete_recipe.php
    * This file handles the deletion of a recipe.
    * It checks if the user is logged in and if they have permission to delete the recipe.
    * If the checks pass, it deletes the recipe from the database.
-->

<?php
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/protected_page.php';


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['recipe_id'])) {
    // Get the recipe ID from the POST data
    $recipeId = $_POST['recipe_id'];
    $user_id = $_SESSION['username'];

    // get the owner of the recipe
    try {
        $stmt = $pdo->prepare('SELECT user_id FROM recipes WHERE id = ?');
        $stmt->execute([$recipeId]);
        $recipeOwner = $stmt->fetchColumn();
    } catch (PDOException $e) {
        error_log("Database error: " . $e->getMessage());
        header('Location: ../index.php');
        exit();
    }


    if ($recipeId && $recipeOwner == $user_id) {
        // Delete the recipe
        try {
            $stmt = $pdo->prepare('DELETE FROM recipes WHERE id = ?');
            $stmt->execute([$recipeId]);
            // Redirect to the user's profile page after deletion
            header('Location: ../profile.php?u=' . $user_id);
        } catch (PDOException $e) {
            error_log("Database error: " . $e->getMessage());
            header('Location: ../recipe.php?id=' . $recipeId);
            exit();
        }    }
} else {
    header('Location: ../profile.php?u=' . $_SESSION['username']);
    exit();
}
