<?php
/* save_recipe.php
    * This file handles saving and unsaving recipes to/from the favorites table
    * It checks if the user is logged in and processes the request accordingly
    Sections in this file:
    * 1. Save Recipe
    * 2. Unsave Recipe
*/
require_once __DIR__ . '/../db.php'; // Include database connection
require_once __DIR__ . '/../protected_page.php'; // Include session management


// Call the appropriate function based on the action
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['recipe_id']) && isset($_POST['action']) && $_POST['action'] === 'save') {
    saveRecipe($pdo);
} else if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['recipe_id']) && isset($_POST['action']) && $_POST['action'] === 'unsave') {
    unsaveRecipe($pdo);
} else {
    http_response_code(400); // Bad Request
    die("Bad Request: Invalid action.");
}

/* 1. Save Recipe */
// Function to save a recipe to the user's favorites
function saveRecipe($pdo){
    $recipe_id = (int)$_POST['recipe_id'];
    $user_id = $_SESSION['username'];

    try {
        // Check if recipe is already saved
        $stmt = $pdo->prepare("SELECT id FROM favorites WHERE user_id = ? AND recipe_id = ?");
        $stmt->execute([$user_id, $recipe_id]);

        if ($stmt->fetch()) {
            $_SESSION['response_code'] = 409; // Conflict
            header('Location: ../../recipe' . '?id=' . $recipe_id);
            error_log("Recipe already saved by user: $user_id for recipe: $recipe_id");
            exit;
        }

        // Save the recipe
        $stmt = $pdo->prepare("INSERT INTO favorites (user_id, recipe_id) VALUES (?, ?)");
        $stmt->execute([$user_id, $recipe_id]);

        // Redirect back to the recipe page
        $_SESSION['response_code'] = 200; // OK
        header('Location: ../../recipe' . '?id=' . $recipe_id);
        exit;
    } catch (PDOException $e) {
        error_log("Database error in save_recipe.php: " . $e->getMessage());
        $_SESSION['errors'] = ['save_recipe' => 'An error occurred while saving the recipe.'];
        $_SESSION['response_code'] = 500; // Internal Server Error
        header('Location: ../../recipe' . '?id=' . $recipe_id);
        exit;
    }
}

/* 2. Unsave Recipe */
// Function to unsave a recipe from the user's favorites
function unsaveRecipe($pdo){
    $recipe_id = (int)$_POST['recipe_id'];
    $user_id = $_SESSION['username'];

    try {
        // Remove the recipe from favorites
        $stmt = $pdo->prepare("DELETE FROM favorites WHERE user_id = ? AND recipe_id = ?");
        $stmt->execute([$user_id, $recipe_id]);
        // Redirect back to the recipe page
        $_SESSION['response_code'] = 200; // OK
        header('Location: ../../recipe' . '?id=' . $recipe_id);
        exit;
    } catch (PDOException $e) {
        error_log("Database error in save_recipe.php: " . $e->getMessage());
        $_SESSION['response_code'] = 500; // Internal Server Error
        header('Location: ../../recipe' . '?id=' . $recipe_id);
        exit;
    }
}