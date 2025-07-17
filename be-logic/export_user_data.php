<?php
/* export_user_data.php
    * This file handles the export of user data.
    * It allows users to download their account data.
    Userdata includes:
    - User profile information (username, first name, last name, email, bio)
    - Recipes created by the user (recipe ID, title, description, ingredients, instructions, creation date)
    - User's favorite recipes (recipe ID, title, description)
    - User's ratings for recipes (recipe ID, rating, comment, date)
*/
// Load dependencies
require_once __DIR__ . '/protected_page.php'; // Ensure the user is logged in
require_once __DIR__ . '/db.php';

// Fetch the current user's username from the session
$currentUser = $_SESSION['username'];

$userData = [];

try {
    // 1. Get user profile information
    $stmt = $pdo->prepare("SELECT username, first_name, last_name, email, bio, created_at FROM users WHERE username = ?");
    $stmt->execute([$currentUser]);
    $userData['profile'] = $stmt->fetch(PDO::FETCH_ASSOC);

    // 2. Get recipes created by the user with ingredients and instructions
    $stmt = $pdo->prepare("SELECT id, title, description, prep_time_min, cook_time_min, difficulty, servings, category, image_path FROM recipes WHERE user_id = ?");
    $stmt->execute([$currentUser]);
    $recipes = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $userData['recipes'] = [];
    foreach ($recipes as $recipe) {
        $recipeData = $recipe;
        
        // Get ingredients for this recipe
        $stmt = $pdo->prepare("SELECT amount, unit, ingredient FROM ingredients WHERE recipe_id = ?");
        $stmt->execute([$recipe['id']]);
        $recipeData['ingredients'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Get instructions for this recipe
        $stmt = $pdo->prepare("SELECT step_number, instruction FROM instructions WHERE recipe_id = ? ORDER BY step_number");
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
    exit;

} catch (Exception $e) {
    // If there's an error, redirect to settings with error message
    header('Location: ../settings.php?error=' . urlencode('Failed to export user data: ' . $e->getMessage()));
    exit;
}
?>
