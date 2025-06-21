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
                <span><?= $total_time ?> mins prep</span>
            </div>
            <div class="recipe-cook-time">
                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10" />
                    <polyline points="12,6 12,12 16,14" />
                </svg>
                <span><?= $recipe['cook_time_min'] ?> mins cook</span>
            </div>
            <div class="recipe-difficulty">
                <span class="difficulty-label">Medium</span>
            </div>
        </div>
        <div class="recipe-action">
            <a class="view-recipe">View Recipe →</a>
        </div>
    </div>
</div>