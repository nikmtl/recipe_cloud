<?php
/* save_recipe.php
    * This file handles saving and unsaving recipes to/from the favorites table
    * It checks if the user is logged in and processes the request accordingly
*/
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/protected_page.php'; // Include session management


// Check if request method is POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../recipes.php');
    exit;
}

// Validate required fields
if (!isset($_POST['recipe_id']) || !isset($_POST['action'])) {
    header('Location: ../recipes.php');
    exit;
}

$recipe_id = (int)$_POST['recipe_id'];
$action = $_POST['action']; // 'save' or 'unsave'
$user_id = $_SESSION['username'];

try {
    if ($action === 'save') {
        // Check if recipe is already saved
        $stmt = $pdo->prepare("SELECT id FROM favorites WHERE user_id = ? AND recipe_id = ?");
        $stmt->execute([$user_id, $recipe_id]);

        if ($stmt->fetch()) {
            header('Location: ../recipe.php' . '?id=' . $recipe_id);
            exit;
        }

        // Save the recipe
        $stmt = $pdo->prepare("INSERT INTO favorites (user_id, recipe_id) VALUES (?, ?)");
        $stmt->execute([$user_id, $recipe_id]);

        header('Location: ../recipe.php' . '?id=' . $recipe_id);
        exit;

    } elseif ($action === 'unsave') {
        // Remove the recipe from favorites
        $stmt = $pdo->prepare("DELETE FROM favorites WHERE user_id = ? AND recipe_id = ?");
        $stmt->execute([$user_id, $recipe_id]);

        header('Location: ../recipe.php' . '?id=' . $recipe_id);
        exit;

    } else {
        header('Location: ../recipe.php' . '?id=' . $recipe_id);
        exit;
    }

} catch (PDOException $e) {
    error_log("Database error in save_recipe.php: " . $e->getMessage());
    header('Location: ../recipe.php' . '?id=' . $recipe_id);
    exit;
}
?>
