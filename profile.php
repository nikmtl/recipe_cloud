<?php //load header
/* * profile.php
    * Displays user profile information including name, username, joined date, recipe count, favorites count, and bio.
    * Requires user to be logged in.
    * It also shows the the users recipes and favorites.
    * It also shows how many times their recipes were favorited by others. This is useful for users to see how popular their recipes are and gives the user a sense of accomplishment.
*/
require_once 'be-logic\protected_page.php';
require_once 'be-logic\get_user_profile.php';
require_once 'be-logic\db.php';
include_once 'assets/includes/header.php';

// Fetch user information
$currentUser = $_SESSION['username'];
$userProfile = getUserProfile($currentUser);

if (!$userProfile) {
    echo "<p>Error loading profile information.</p>";
    exit;
}

// Fetch user's own recipes
$userRecipes = [];
try {
    $stmt = $pdo->prepare("
        SELECT r.*, u.username, 
               COALESCE(AVG(rt.rating), 0) as avg_rating, 
               COALESCE(COUNT(rt.rating), 0) as rating_count
        FROM recipes r 
        LEFT JOIN users u ON r.user_id = u.username 
        LEFT JOIN ratings rt ON r.id = rt.recipe_id
        WHERE r.user_id = ?
        GROUP BY r.id
        ORDER BY r.id DESC
    ");
    $stmt->execute([$currentUser]);
    $userRecipes = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    error_log("Error fetching user recipes: " . $e->getMessage());
}

// Fetch user's favorite recipes
$favoriteRecipes = [];
try {
    $stmt = $pdo->prepare("
        SELECT r.*, u.username, 
               COALESCE(AVG(rt.rating), 0) as avg_rating, 
               COALESCE(COUNT(rt.rating), 0) as rating_count
        FROM recipes r 
        LEFT JOIN users u ON r.user_id = u.username 
        LEFT JOIN ratings rt ON r.id = rt.recipe_id
        INNER JOIN favorites f ON r.id = f.recipe_id
        WHERE f.user_id = ?
        GROUP BY r.id
        ORDER BY r.id DESC
    ");
    $stmt->execute([$currentUser]);
    $favoriteRecipes = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    error_log("Error fetching favorite recipes: " . $e->getMessage());
}
?>
<main>
    <div class="profile-container">
        <div class="profile-information">
            <div class="profile-header">
                <div>
                    <h1><?php echo htmlspecialchars($userProfile['first_name'] . ' ' . $userProfile['last_name']); ?></h1>
                    <p class="username <?php echo ($userProfile['first_name'] ? '' : 'no-name'); ?>">@<?php echo htmlspecialchars($userProfile['username']); ?></p>
                    <p class="joined-date">Joined <?php echo htmlspecialchars($userProfile['joined_date']); ?></p>
                </div>
                <button class="secondary-button icon-button" onclick="window.location.href='settings.php'">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M12.22 2h-.44a2 2 0 0 0-2 2v.18a2 2 0 0 1-1 1.73l-.43.25a2 2 0 0 1-2 0l-.15-.08a2 2 0 0 0-2.73.73l-.22.38a2 2 0 0 0 .73 2.73l.15.1a2 2 0 0 1 1 1.72v.51a2 2 0 0 1-1 1.74l-.15.09a2 2 0 0 0-.73 2.73l.22.38a2 2 0 0 0 2.73.73l.15-.08a2 2 0 0 1 2 0l.43.25a2 2 0 0 1 1 1.73V20a2 2 0 0 0 2 2h.44a2 2 0 0 0 2-2v-.18a2 2 0 0 1 1-1.73l.43-.25a2 2 0 0 1 2 0l.15.08a2 2 0 0 0 2.73-.73l.22-.39a2 2 0 0 0-.73-2.73l-.15-.08a2 2 0 0 1-1-1.74v-.5a2 2 0 0 1 1-1.74l.15-.09a2 2 0 0 0 .73-2.73l-.22-.38a2 2 0 0 0-2.73-.73l-.15.08a2 2 0 0 1-2 0l-.43-.25a2 2 0 0 1-1-1.73V4a2 2 0 0 0-2-2z"></path>
                        <circle cx="12" cy="12" r="3"></circle>
                    </svg>
                    Settings
                </button>
            </div>

            <?php if (!empty($userProfile['bio'])): ?>
                <div class="profile-bio">
                    <p><?php echo htmlspecialchars($userProfile['bio']); ?></p>
                </div>
            <?php endif; ?>

            <div class="profile-stats">
                <div>
                    <h3><?php echo $userProfile['recipe_count']; ?></h3>
                    <p>Recipes</p>
                </div>
                <div>
                    <h3><?php echo $userProfile['favorites_count']; ?></h3>
                    <p>Favorites</p>
                </div>
                <div>
                    <h3><?php echo $userProfile['favorited_by_others']; ?></h3>
                    <p>Times Favorited</p>
                </div>
            </div>
        </div>

        <div class="section-taps">
            <button id="tap-header-my-recipes" class="tap-header" onclick="openTap('tap-my-recipes','tap-header-my-recipes')">My Recipes</button>
            <button id="tap-header-favorites" class="tap-header" onclick="openTap('tap-favorites', 'tap-header-favorites')">Favorites</button>
        </div>
        <div id="tap-my-recipes" class="tap" style="display: block;">
            <div class="recipes-grid">
                <?php
                if (!empty($userRecipes)) {
                    foreach ($userRecipes as $recipe) {
                        $total_time = (int)$recipe['prep_time_min'] + (int)$recipe['cook_time_min'];
                        $description = $recipe['description'] ? htmlspecialchars(substr($recipe['description'], 0, 60)) : 'Classic recipe with delicious ingredients';
                        include 'assets/includes/recipe_card.php';
                    }
                }
                ?>
            </div>
            <?php if (empty($userRecipes)) : ?>
                <div>
                    <p class="no-recipes">You haven't created any recipes yet. <a href="upload.php">Create your first recipe!</a></p>
                </div>
            <?php endif; ?>
        </div>

        <div id="tap-favorites" class="tap">
            <div class="recipes-grid">
                <?php
                if (!empty($favoriteRecipes)) {
                    foreach ($favoriteRecipes as $recipe) {
                        $total_time = (int)$recipe['prep_time_min'] + (int)$recipe['cook_time_min'];
                        $description = $recipe['description'] ? htmlspecialchars(substr($recipe['description'], 0, 60)) : 'Classic recipe with delicious ingredients';
                        include 'assets/includes/recipe_card.php';
                    }
                }
                ?>
            </div>
            <?php if (empty($favoriteRecipes)) : ?>
                <div>
                    <p class="no-recipes">You haven't favorited any recipes yet. <a href="recipes.php">Browse recipes to find your favorites!</a></p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</main>

<?php // load footer
include_once 'assets/includes/footer.php';
?>