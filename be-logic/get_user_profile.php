<?php
/* get_user_profile.php
    * This file fetches user profile information including statistics
    * Returns user data: first name, last name, username, joined date, recipe count, favorites count, and how many times their recipes were favorited
*/

require_once 'db.php';

function getUserProfile($username) {
    global $pdo;
    
    try {
        // Get basic user information
        $stmt = $pdo->prepare("
            SELECT first_name, last_name, username, email, bio, profile_image, created_at 
            FROM users 
            WHERE username = ?
        ");
        $stmt->execute([$username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$user) {
            return null;
        }
        
        // Get count of recipes created by user
        $stmt = $pdo->prepare("SELECT COUNT(*) as recipe_count FROM recipes WHERE user_id = ?");
        $stmt->execute([$username]);
        $recipeCount = $stmt->fetch(PDO::FETCH_ASSOC)['recipe_count'];
        
        // Get count of favorites made by user
        $stmt = $pdo->prepare("SELECT COUNT(*) as favorites_count FROM favorites WHERE user_id = ?");
        $stmt->execute([$username]);
        $favoritesCount = $stmt->fetch(PDO::FETCH_ASSOC)['favorites_count'];
        
        // Get count of how many times other users favorited this user's recipes
        $stmt = $pdo->prepare("
            SELECT COUNT(*) as favorited_by_others 
            FROM favorites f
            INNER JOIN recipes r ON f.recipe_id = r.id
            WHERE r.user_id = ? AND f.user_id != ?
        ");
        $stmt->execute([$username, $username]);
        $favoritedByOthers = $stmt->fetch(PDO::FETCH_ASSOC)['favorited_by_others'];
        
        // Format joined date
        $joinedDate = new DateTime($user['created_at']);
        $formattedJoinedDate = $joinedDate->format('F Y'); // e.g., "January 2025"
        
        return [
            'first_name' => $user['first_name'],
            'last_name' => $user['last_name'],
            'username' => $user['username'],
            'email' => $user['email'],
            'bio' => $user['bio'],
            'joined_date' => $formattedJoinedDate,
            'recipe_count' => $recipeCount,
            'favorites_count' => $favoritesCount,
            'favorited_by_others' => $favoritedByOthers
        ];
        
    } catch (PDOException $e) {
        error_log("Error fetching user profile: " . $e->getMessage());
        return null;
    }
}
?>
