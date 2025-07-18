<?php
/* recipe_card.php 
    * This file contains the HTML structure for displaying a recipe card.
    * It includes the recipe image, title, rating, description, author, and time details.
    * To use this: include this file in your PHP document where you want to display a recipe card. Make sure the `$recipe` array with the necessary data exists.
*/


// Add CSS file for recipe card styling
echo '<link rel="stylesheet" href="assets/css/recipe_card.css">';
?>
<div class="recipe-card" onclick="location.href='recipe.php?id=<?= $recipe['id'] ?>'">
    <div class="recipe-card-image">
        <?php if ($recipe['image_path']): ?>
            <img src="<?= htmlspecialchars($recipe['image_path']) ?>" alt="<?= htmlspecialchars($recipe['title']) ?>">
        <?php else: ?>
            <div class="no-image">
                <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="3" y="3" width="18" height="18" rx="2" ry="2" />
                    <circle cx="8.5" cy="8.5" r="1.5" />
                    <polyline points="21,15 16,10 5,21" />
                </svg>
            </div>
        <?php endif; ?>
        <?php if (isset($_SESSION['username']) && $_SESSION['username'] !== $recipe['username'] && isset($pdo)): ?>
        <div class="recipe-card-quick-favorite">
            <?php 
            // Check if current user has this recipe in favorites
            try {
                $stmt = $pdo->prepare("SELECT id FROM favorites WHERE user_id = ? AND recipe_id = ?");
                $stmt->execute([$_SESSION['username'], $recipe['id']]);
                $is_favorited = $stmt->fetch() ? true : false;
            } catch (Exception $e) {
                error_log("Error checking favorite status: " . $e->getMessage());
                $is_favorited = false;
            }
            ?>
            <?php if ($is_favorited): ?>
            <svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 24 24" fill="red" stroke="red" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z"></path>
            </svg>
            <?php endif; ?>
        </div>
        <?php endif; ?>
    </div>
    <div class="recipe-card-content">
        <div class="recipe-header">
            <h3><?= htmlspecialchars($recipe['title']) ?></h3>
            <?php if ($recipe['rating_count'] > 0): ?>
                <div class="recipe-rating">
                    <span class="star-icon">★</span>
                    <span class="rating-value"><?= number_format($recipe['avg_rating'], 1) ?></span>
                </div>
            <?php endif; ?>
        </div>
        <p class="recipe-description"><?= $description ?></p>
        <p class="recipe-author">By <?= htmlspecialchars($recipe['username']) ?></p>
        <div class="recipe-meta">
            <div class="recipe-time">
                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10" />
                    <polyline points="12,6 12,12 16,14" />
                </svg>
                <span><?= $recipe['prep_time_min'] ?> mins prep</span>
            </div>
            <div class="recipe-cook-time">
                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10" />
                    <polyline points="12,6 12,12 16,14" />
                </svg>
                <span><?= $recipe['cook_time_min'] ?> mins cook</span>
            </div>
            <div class="recipe-difficulty">
                <span class="difficulty-label">
                    <?php
                    switch ($recipe['difficulty']) {
                        case '1':
                            echo 'Easy';
                            break;
                        case '2':
                            echo 'Medium';
                            break;
                        case '3':
                            echo 'Hard';
                            break;
                    }
                    ?>
                </span>
            </div>
        </div>
        <div class="recipe-action">
            <a class="view-recipe">View Recipe →</a>
        </div>
    </div>
</div>