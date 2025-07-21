<?php
/* review.php
    * This file handles user reviews for recipes.
    * It checks if the user is logged in and has permission to submit a review.
    * It validates the input data, checks if the user has already rated the recipe, and either updates or inserts the rating.
    * After successful submission, it redirects the user back to the recipe page.
    Sections in this file:
    * 1. Submit Review
    * 2. Update Review
    * 3. Delete Review
    * 4. Helper Function to Get Review Data and Validate It
*/

require_once __DIR__ . '/../db.php'; // Include database connection
require_once __DIR__ . '/../protected_page.php'; // Include session management

// Call the appropriate function based on the action
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'submit' && isset($_POST['recipe_id']) && isset($_POST['rating'])) { //submit review
    submitReview($pdo);
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update' && isset($_POST['recipe_id']) && isset($_POST['rating'])) { //update review
    updateReview($pdo);
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete' && isset($_POST['recipe_id'])) { //delete review
    deleteReview($pdo);
} else { // Invalid action if no valid POST request or action unknown
    echo "Invalid action.";
    exit;
}

/* 1. Submit review function */
// Function to handle review submission
function submitReview($pdo){

    $reviewData = getReviewData();
    if (!$reviewData) return; // Check if review data is valid
    

    try {
        // Insert new rating
        $stmt = $pdo->prepare("INSERT INTO ratings (user_id, recipe_id, rating, comment_text) VALUES (?, ?, ?, ?)");
        $stmt->execute([$reviewData['user_id'], $reviewData['recipe_id'], $reviewData['rating'], $reviewData['comment']]);

        // Redirect back to recipe page
        $_SESSION['Success'] = ['review' => "Your review has been submitted successfully."];
        header('Location: ../../recipe?id=' . $reviewData['recipe_id']);
        exit();
    } catch (PDOException $e) {
        error_log("Database error while submitting review: " . $e->getMessage());
        $_SESSION['errors'] = ['review' => 'An error occurred while submitting your review.'];
        header('Location: ../../recipe?id=' . $reviewData['recipe_id']);
        exit();
    }
}

/* 2. Update review function */
// Function to handle review update
function updateReview($pdo){
    $reviewData = getReviewData();
    if (!$reviewData) return; // Check if review data is valid
    
    try {
        // Update existing rating
        $stmt = $pdo->prepare("UPDATE ratings SET rating = ?, comment_text = ? WHERE user_id = ? AND recipe_id = ?");
        $stmt->execute([$reviewData['rating'], $reviewData['comment'], $reviewData['user_id'], $reviewData['recipe_id']]);

        // Redirect back to recipe page
        $_SESSION['Success'] = ['review' => "Your review has been submitted successfully."];
        header('Location: ../../recipe?id=' . $reviewData['recipe_id']);
        exit();
    } catch (PDOException $e) {
        error_log("Database error while submitting review: " . $e->getMessage());
        $_SESSION['errors'] = ['review' => 'An error occurred while submitting your review.'];
        header('Location: ../../recipe?id=' . $reviewData['recipe_id']);
        exit();
    }
}

/* 3. Delete review function */
// Function to handle review deletion
function deleteReview($pdo){
   
    // Get the recipe ID from the POST data
    $recipeId = $_POST['recipe_id'];
    $user_id = $_SESSION['username'];    

    try {
        // Delete the existing rating
        $stmt = $pdo->prepare("DELETE FROM ratings WHERE user_id = ? AND recipe_id = ?");
        $stmt->execute([$user_id, $recipeId]);

        // Redirect back to recipe page
        $_SESSION['Success'] = ['review' => "Your review has been deleted successfully."];
        header('Location: ../../recipe?id=' . $recipeId);
        exit();
    } catch (PDOException $e) {
        error_log("Database error while deleting review: " . $e->getMessage());
        $_SESSION['errors'] = ['review' => 'An error occurred while deleting your review.'];
        header('Location: ../../recipe?id=' . $recipeId);
        exit();
    }
}


/* 4. Helper function to get the form data and validate it */
// Function to get review data and validate it
function getReviewData(): array {
    $recipe_id = (int)$_POST['recipe_id'];
    $rating = (int)$_POST['rating'];
    $comment = trim($_POST['comment']);
    $user_id = $_SESSION['username'];

    // Validate rating (1-5 stars)
    if ($rating < 1 || $rating > 5 || !is_numeric($rating)) {
        echo "Invalid action. Rating must be between 1 and 5.";
        exit;
    }

    // Validate comment length
    if (strlen($comment) > 500) {
        $_SESSION['errors'] = ['review_comment' => 'Comment must be shorter than 500 characters.'];
        header('Location: ../../recipe?id=' . $recipe_id);
        exit;
    }

    return [
        'recipe_id' => $recipe_id,
        'rating' => $rating,
        'comment' => $comment,
        'user_id' => $user_id
    ];
}