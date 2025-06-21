<?php
/* submit_review.php
    * This file handles the submission of user reviews for recipes.
    * It checks if the user is logged in and has permission to submit a review.
    * It validates the input data, checks if the user has already rated the recipe, and either updates or inserts the rating.
    * After successful submission, it redirects the user back to the recipe page.
*/

require_once __DIR__ . '/db.php';
require_once __DIR__ . '/protected_page.php';

// Check if form was submitted via POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../recipes.php');
    exit();
}

// Validate required fields
if (!isset($_POST['recipe_id']) || !isset($_POST['rating'])) {
    header('Location: ../recipes.php');
    exit();
}

$recipe_id = (int)$_POST['recipe_id'];
$rating = (int)$_POST['rating'];
$comment = trim($_POST['comment']);
$user_id = $_SESSION['username'];

// Validate rating (1-5 stars)
if ($rating < 1 || $rating > 5 || !is_numeric($rating)) {
    header('Location: ../recipe.php?id=' . $recipe_id);
    exit();
}

try {
    // Check if user has already rated this recipe
    $stmt = $pdo->prepare("SELECT id FROM ratings WHERE user_id = ? AND recipe_id = ?");
    $stmt->execute([$user_id, $recipe_id]);
    $existing_rating = $stmt->fetch();

    if ($existing_rating) {
        // Update existing rating
        $stmt = $pdo->prepare("UPDATE ratings SET rating = ?, comment_text = ? WHERE user_id = ? AND recipe_id = ?");
        $stmt->execute([$rating, $comment, $user_id, $recipe_id]);
    } else {
        // Insert new rating
        $stmt = $pdo->prepare("INSERT INTO ratings (user_id, recipe_id, rating, comment_text) VALUES (?, ?, ?, ?)");
        $stmt->execute([$user_id, $recipe_id, $rating, $comment]);
    }    // Redirect back to recipe page with success message
    header('Location: ../recipe.php?id=' . $recipe_id);
    exit();

} catch (PDOException $e) {
    error_log("Database error in submit_review.php: " . $e->getMessage());
    header('Location: ../recipe.php?id=' . $recipe_id);
    exit();
}
?>
