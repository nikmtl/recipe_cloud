<?php
/* delete_review.php
    * This file handles the deletion of user reviews for recipes.
    * It checks if the user is logged in and has permission to delete the review.
    * After successful deletion, it redirects the user back to the recipe page.
*/

require_once __DIR__ . '/db.php';
require_once __DIR__ . '/protected_page.php';

// Check if request is POST and has required parameters
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['recipe_id'])) {
    header('Location: ../recipes.php');
    exit();
}

$recipe_id = (int)$_POST['recipe_id'];
$user_id = $_SESSION['username'];

try {
    // Delete the user's review for this recipe
    $stmt = $pdo->prepare("DELETE FROM ratings WHERE user_id = ? AND recipe_id = ?");
    $result = $stmt->execute([$user_id, $recipe_id]);
      //redirect back to the recipe page
    header('Location: ../recipe.php?id=' . $recipe_id);
    exit();

} catch (PDOException $e) {
    error_log("Database error in delete_review.php: " . $e->getMessage());
    header('Location: ../recipe.php?id=' . $recipe_id);
    exit();
}
?>
