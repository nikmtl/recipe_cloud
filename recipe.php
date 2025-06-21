<!-- recipe.php
    * This file is the recipe page for displaying individual recipes.
    * It includes all the necessary information about the recipe, such as ingredients, instructions, and user reviews.
    * The page also handles user interactions, such as submitting reviews and saving recipes.
    //TODO for the future: Add a limit of reviews for the recipe, or change that it doesn't load all reviews at once.
-->

<?php // Get all the necessary data for the recipe page from the database
require_once 'be-logic/db.php';
if (!isset($_SESSION)) {
    session_start();
}
// Check if recipe ID is provided
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header('Location: index.php');
    exit();
}

$recipe_id = (int)$_GET['id'];

// Fetch recipe details with user information
try {
    $stmt = $pdo->prepare("
        SELECT r.*, u.username
        FROM recipes r 
        LEFT JOIN users u ON r.user_id = u.username 
        WHERE r.id = ?
    ");
    $stmt->execute([$recipe_id]);
    $recipe = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$recipe) {
        header('Location: recipes.php');
        exit();
    }

    $recipe['total_time_min'] = (int)$recipe['prep_time_min'] + (int)$recipe['cook_time_min'];

    // Fetch ingredients
    $stmt = $pdo->prepare("SELECT * FROM ingredients WHERE recipe_id = ?");
    $stmt->execute([$recipe_id]);
    $ingredients = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Fetch instructions
    $stmt = $pdo->prepare("SELECT * FROM instructions WHERE recipe_id = ? ORDER BY step_number");
    $stmt->execute([$recipe_id]);
    $instructions = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Fetch ratings and calculate average
    $stmt = $pdo->prepare("
        SELECT AVG(rating) as avg_rating, COUNT(*) as rating_count 
        FROM ratings 
        WHERE recipe_id = ?
    ");
    $stmt->execute([$recipe_id]);
    $rating_data = $stmt->fetch(PDO::FETCH_ASSOC);

    // Fetch individual ratings with user info
    $stmt = $pdo->prepare("
        SELECT r.*, u.username
        FROM ratings r 
        LEFT JOIN users u ON r.user_id = u.username 
        WHERE r.recipe_id = ? 
        ORDER BY r.created_at DESC
    ");
    $stmt->execute([$recipe_id]);
    $reviews = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Check if current user has already reviewed this recipe and fetch their review
    $user_review = null;
    if (isset($_SESSION['username'])) {
        $stmt = $pdo->prepare("SELECT * FROM ratings WHERE user_id = ? AND recipe_id = ?");
        $stmt->execute([$_SESSION['username'], $recipe_id]);
        $user_review = $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Check if the user has saved this recipe
    $saved_recipe = false;
    if (isset($_SESSION['username'])) {
        $stmt = $pdo->prepare("SELECT * FROM favorites WHERE user_id = ? AND recipe_id = ?");
        $stmt->execute([$_SESSION['username'], $recipe_id]);
        $saved_recipe = $stmt->fetch(PDO::FETCH_ASSOC) ? true : false;
    }
} catch (PDOException $e) {
    error_log("Database error: " . $e->getMessage());
    header('Location: recipes.php');
    exit();
}

include_once 'assets/includes/header.php'; //load header
?>



<main>
    <div class="recipe-container">
        <a class="back-button" href="recipes.php"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-arrow-left mr-2 h-4 w-4" __v0_r="0,3543,3557">
                <path d="m12 19-7-7 7-7"></path>
                <path d="M19 12H5"></path>
            </svg>Back to Recipes</a>
        <div>
            <div class="recipe-image-header">
                <img src="<?php echo htmlspecialchars($recipe['image_path']); ?>" alt="<?php echo htmlspecialchars($recipe['title']); ?>" class="recipe-image">
            </div>
            <div class="recipe-content">
                <div class="recipe-details">
                    <div class="recipe-header">
                        <h1><?php echo htmlspecialchars($recipe['title']); ?></h1>
                        <p class="recipe-description"><?php echo nl2br(htmlspecialchars($recipe['description'])); ?></p>
                        <p>Recipe by <?php echo htmlspecialchars($recipe['username']); ?></p>
                        <div class="recipe-meta">
                            <div onclick="openTap('tap-reviews', 'tap-header-reviews')">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="#facc15" stroke="#facc15" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class=" __v0_r=" 0,4261,4307">
                                    <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon>
                                </svg>
                                <?php if ($rating_data['rating_count'] > 0): ?>
                                    <span><?php echo round($rating_data['avg_rating'], 1); ?> <span id="rating-count">(<?php echo htmlspecialchars($rating_data['rating_count']); ?> ratings)</span></span>
                                <?php else: ?>
                                    <span id="no-ratings">No ratings yet</span>
                                <?php endif; ?>
                            </div>
                            <div>
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <circle cx="12" cy="12" r="10"></circle>
                                    <polyline points="12 6 12 12 16 14"></polyline>
                                </svg>
                                <span><?php echo htmlspecialchars($recipe['total_time_min']); ?> mins</span>
                            </div>
                            <div>
                                <?php if ($recipe['difficulty'] == '1'): ?>
                                    <span class="badge badge-easy">Easy</span>
                                <?php elseif ($recipe['difficulty'] == '2'): ?>
                                    <span class="badge badge-medium">Medium</span>
                                <?php elseif ($recipe['difficulty'] == '3'): ?>
                                    <span class="badge badge-hard">Hard</span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <div>
                        <div class="section-taps">
                            <button id="tap-header-instructions" class="tap-header" onclick="openTap('tap-instructions','tap-header-instructions')">Instructions</button>
                            <button id="tap-header-ingredients" class="tap-header" onclick="openTap('tap-ingredients', 'tap-header-ingredients')">Ingredients</button>
                            <button id="tap-header-reviews" class="tap-header" onclick="openTap('tap-reviews', 'tap-header-reviews')">Reviews</button>
                        </div>
                        <div id="tap-instructions" class="tap">
                            <div class="instructions-list">
                                <?php foreach ($instructions as $instruction): ?>
                                    <div>
                                        <span class="instruction-number"><?php echo htmlspecialchars($instruction['step_number']); ?></span>
                                        <p><?php echo htmlspecialchars($instruction['instruction']); ?></p>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        <div id="tap-ingredients" class="tap">
                            <div class="ingredients-list">
                                <div class="servings-adjuster">
                                    <h4>Ingredients</h4>
                                    <div class="serving-controls">
                                        <label for="servings-input">Servings:</label>
                                        <div class="serving-input-group">
                                            <button type="button" id="decrease-servings" class="serving-btn">-</button>
                                            <input type="number" id="servings-input" value="<?php echo htmlspecialchars($recipe['servings']); ?>" min="1" max="50">
                                            <input type="hidden" id="original-servings" value="<?php echo htmlspecialchars($recipe['servings']); ?>">
                                            <button type="button" id="increase-servings" class="serving-btn">+</button>
                                        </div>
                                    </div>
                                </div>                                <ul id="ingredients-list">
                                    <?php foreach ($ingredients as $ingredient): ?>
                                        <li data-original-amount="<?php echo htmlspecialchars($ingredient['amount'] ?? ''); ?>" data-unit="<?php echo htmlspecialchars($ingredient['unit'] ?? ''); ?>" data-ingredient="<?php echo htmlspecialchars($ingredient['ingredient']); ?>">
                                            <span class="indicator"></span>
                                            <?php if (!empty($ingredient['amount'])): ?>
                                                <span class="ingredient-amount"><?php echo htmlspecialchars($ingredient['amount']); ?></span>
                                            <?php endif; ?>
                                            <?php if (!empty($ingredient['unit'])): ?>
                                                <span class="ingredient-unit"><?php echo htmlspecialchars($ingredient['unit']); ?></span>
                                            <?php endif; ?>
                                            <span class="ingredient-name"><?php echo htmlspecialchars($ingredient['ingredient']); ?></span>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>

                        </div>

                        <div id="tap-reviews" class="tap">
                            <?php if (isset($_SESSION['username'])): ?>
                                <div id="review-form">
                                    <h4><?php echo $user_review ? 'Update Your Review' : 'Rate this recipe'; ?></h4>
                                    <?php if ($user_review): ?>
                                        <p>You previously reviewed this recipe. You can update your review below.</p>
                                    <?php endif; ?>
                                    <form action="be-logic/submit_review.php" method="POST">
                                        <input type="hidden" name="recipe_id" value="<?php echo htmlspecialchars($recipe['id']); ?>">

                                        <div>
                                            <div class="star-input">
                                                <input type="radio" name="rating" value="1" id="star1" style="display: none;" required <?php echo ($user_review && $user_review['rating'] == 1) ? 'checked' : ''; ?>>
                                                <label for="star1" class="star-label">★</label>

                                                <input type="radio" name="rating" value="2" id="star2" style="display: none;" <?php echo ($user_review && $user_review['rating'] == 2) ? 'checked' : ''; ?>>
                                                <label for="star2" class="star-label">★</label>

                                                <input type="radio" name="rating" value="3" id="star3" style="display: none;" <?php echo ($user_review && $user_review['rating'] == 3) ? 'checked' : ''; ?>>
                                                <label for="star3" class="star-label">★</label>

                                                <input type="radio" name="rating" value="4" id="star4" style="display: none;" <?php echo ($user_review && $user_review['rating'] == 4) ? 'checked' : ''; ?>>
                                                <label for="star4" class="star-label">★</label>

                                                <input type="radio" name="rating" value="5" id="star5" style="display: none;" <?php echo ($user_review && $user_review['rating'] == 5) ? 'checked' : ''; ?>>
                                                <label for="star5" class="star-label">★</label>
                                            </div>
                                        </div>

                                        <div>
                                            <textarea id="comment" name="comment" rows="4" placeholder="Share your experience with this recipe..."><?php echo $user_review ? htmlspecialchars($user_review['comment_text']) : ''; ?></textarea>
                                        </div>
                                        <div class="review-buttons">
                                            <?php if ($user_review): ?>
                                                <button id="remove-review-btn" type="button" class="secondary-button">
                                                    Remove Review
                                                </button>
                                            <?php endif; ?>
                                            <button id="submit-review-btn" type="submit" <?php echo !$user_review ? 'disabled' : ''; ?>>
                                                <?php echo $user_review ? 'Update Review' : 'Submit Review'; ?>
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            <?php else: ?>
                                <div id="login-prompt">
                                    <p>Please <a href="login.php">log in</a> to leave a review.</p>
                                </div>
                            <?php endif; ?>

                            <div id="reviews-list">
                                <?php if (!empty($reviews)): ?>
                                    <h3>Reviews (<?php echo count($reviews); ?>)</h3>
                                    <div>
                                        <?php foreach ($reviews as $review): ?>
                                            <div class="review">
                                                <div>
                                                    <p class="review-username"><?php echo htmlspecialchars($review['username']); ?></p>
                                                    <div class="review-meta">
                                                        <div class="star-rating">
                                                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                                                <span style="color: <?php echo $i <= $review['rating'] ? '#ffd700' : '#ddd'; ?>;">★</span>
                                                            <?php endfor; ?>
                                                        </div>
                                                        <small><?php
                                                                $created = strtotime($review['created_at']);
                                                                $now = time();
                                                                $diff = $now - $created;

                                                                if ($diff < 60) {
                                                                    echo "just now";
                                                                } elseif ($diff < 3600) {
                                                                    $mins = floor($diff / 60);
                                                                    echo $mins . " minute" . ($mins > 1 ? "s" : "") . " ago";
                                                                } elseif ($diff < 86400) {
                                                                    $hours = floor($diff / 3600);
                                                                    echo $hours . " hour" . ($hours > 1 ? "s" : "") . " ago";
                                                                } elseif ($diff < 604800) {
                                                                    $days = floor($diff / 86400);
                                                                    echo $days . " day" . ($days > 1 ? "s" : "") . " ago";
                                                                } elseif ($diff < 2592000) {
                                                                    $weeks = floor($diff / 604800);
                                                                    echo $weeks . " week" . ($weeks > 1 ? "s" : "") . " ago";
                                                                } else {
                                                                    echo date('M j, Y', $created);
                                                                }
                                                                ?></small>
                                                    </div>
                                                </div>
                                                <p class="review-comment"><?php echo nl2br(htmlspecialchars($review['comment_text'])); ?></p>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                <?php else: ?>
                                    <p>No reviews yet. Be the first to review this recipe!</p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>




                <div id="recipe-quick-info-and-actions">
                    <div class="recipe-quick-info">
                        <h2>Recipe Details</h2>
                        <div>
                            <p>Prep Time:</p>
                            <span><?php echo htmlspecialchars($recipe['prep_time_min']); ?> mins</span>
                        </div>
                        <div>
                            <p>Cook Time:</p>
                            <span><?php echo htmlspecialchars($recipe['cook_time_min']); ?> mins</span>
                        </div>
                        <div>
                            <p>Total Time:</p>
                            <span><?php echo htmlspecialchars($recipe['total_time_min']); ?> mins</span>
                        </div>
                        <div>
                            <p>Difficulty:</p>
                            <span>
                                <?php if ($recipe['difficulty'] == '1'): ?>
                                    Easy
                                <?php elseif ($recipe['difficulty'] == '2'): ?>
                                    Medium
                                <?php elseif ($recipe['difficulty'] == '3'): ?>
                                    Hard
                                <?php endif; ?>
                            </span>
                        </div>
                    </div>
                    <div>
                        <?php
                        $current_url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
                        ?>
                        <h2>Share This Recipe</h2>
                        <div class="social-share-buttons">
                            <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode($current_url); ?>" target="_blank" rel="noopener noreferrer"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                    <path d="M16 8.049c0-4.446-3.582-8.05-8-8.05C3.58 0-.002 3.603-.002 8.05c0 4.017 2.926 7.347 6.75 7.951v-5.625h-2.03V8.05H6.75V6.275c0-2.017 1.195-3.131 3.022-3.131.876 0 1.791.157 1.791.157v1.98h-1.009c-.993 0-1.303.621-1.303 1.258v1.51h2.218l-.354 2.326H9.25V16c3.824-.604 6.75-3.934 6.75-7.951z"></path>
                                </svg>
                            </a>
                            <a href="https://twitter.com/intent/tweet?text=<?php echo urlencode('Check out this recipe: ' . $recipe['title']); ?>&url=<?php echo urlencode($current_url); ?>" target="_blank" rel="noopener noreferrer"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                    <path d="M8 0a8 8 0 0 0-2.915 15.452c-.07-.633-.134-1.606.027-2.297.146-.625.938-3.977.938-3.977s-.239-.479-.239-1.187c0-1.113.645-1.943 1.448-1.943.682 0 1.012.512 1.012 1.127 0 .686-.437 1.712-.663 2.663-.188.796.4 1.446 1.185 1.446 1.422 0 2.515-1.5 2.515-3.664 0-1.915-1.377-3.254-3.342-3.254-2.276 0-3.612 1.707-3.612 3.471 0 .688.265 1.425.595 1.826a.24.24 0 0 1 .056.23c-.061.252-.196.796-.222.907-.035.146-.116.177-.268.107-1-.465-1.624-1.926-1.624-3.1 0-2.523 1.834-4.84 5.286-4.84 2.775 0 4.932 1.977 4.932 4.62 0 2.757-1.739 4.976-4.151 4.976-.811 0-1.573-.421-1.834-.919l-.498 1.902c-.181.695-.669 1.566-.995 2.097A8 8 0 1 0 8 0z"></path>
                                </svg>
                            </a>
                            <a href="https://www.pinterest.com/pin/create/button/?url=<?php echo urlencode($current_url); ?>&media=<?php echo urlencode((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]" . $recipe['image_path']); ?>&description=<?php echo urlencode($recipe['title']); ?>" target="_blank" rel="noopener noreferrer"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                    <path d="M5.026 15c6.038 0 9.341-5.003 9.341-9.334 0-.14 0-.282-.006-.422A6.685 6.685 0 0 0 16 3.542a6.658 6.658 0 0 1-1.889.518 3.301 3.301 0 0 0 1.447-1.817 6.533 6.533 0 0 1-2.087.793A3.286 3.286 0 0 0 7.875 6.03a9.325 9.325 0 0 1-6.767-3.429 3.289 3.289 0 0 0 1.018 4.382A3.323 3.323 0 0 1 .64 6.575v.045a3.288 3.288 0 0 0 2.632 3.218 3.203 3.203 0 0 1-.865.115 3.23 3.23 0 0 1-.614-.057 3.283 3.283 0 0 0 3.067 2.277A6.588 6.588 0 0 1 .78 13.58a6.32 6.32 0 0 1-.78-.045A9.344 9.344 0 0 0 5.026 15z"></path>
                                </svg>
                            </a>
                            <a id="copy-link-btn"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" with="16" height="16">
                                    <path fill-rule="evenodd" d="M7.502 6h7.128A3.375 3.375 0 0 1 18 9.375v9.375a3 3 0 0 0 3-3V6.108c0-1.505-1.125-2.811-2.664-2.94a48.972 48.972 0 0 0-.673-.05A3 3 0 0 0 15 1.5h-1.5a3 3 0 0 0-2.663 1.618c-.225.015-.45.032-.673.05C8.662 3.295 7.554 4.542 7.502 6ZM13.5 3A1.5 1.5 0 0 0 12 4.5h4.5A1.5 1.5 0 0 0 15 3h-1.5Z" clip-rule="evenodd" />
                                    <path fill-rule="evenodd" d="M3 9.375C3 8.339 3.84 7.5 4.875 7.5h9.75c1.036 0 1.875.84 1.875 1.875v11.25c0 1.035-.84 1.875-1.875 1.875h-9.75A1.875 1.875 0 0 1 3 20.625V9.375Zm9.586 4.594a.75.75 0 0 0-1.172-.938l-2.476 3.096-.908-.907a.75.75 0 0 0-1.06 1.06l1.5 1.5a.75.75 0 0 0 1.116-.062l3-3.75Z" clip-rule="evenodd" />
                                </svg>
                            </a>
                        </div>
                    </div>
                    <?php if (isset($_SESSION['username']) && $_SESSION['username'] !== $recipe['user_id']): ?>
                        <div class="recipe-actions-save">
                            <?php if (!$saved_recipe): ?>
                                <h2>Save This Recipe</h2>
                                <button id="save-recipe-btn" class="secondary-button">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-thumbs-up mr-2 h-4 w-4" __v0_r="0,16425,16439">
                                        <path d="M7 10v12"></path>
                                        <path d="M15 5.88 14 10h5.83a2 2 0 0 1 1.92 2.56l-2.33 8A2 2 0 0 1 17.5 22H4a2 2 0 0 1-2-2v-8a2 2 0 0 1 2-2h2.76a2 2 0 0 0 1.79-1.11L12 2h0a3.13 3.13 0 0 1 3 3.88Z"></path>
                                    </svg>
                                    Save Recipe
                                </button>
                            <?php else: ?>
                                <h2>Saved Recipe</h2>
                                <button id="unsave-recipe-btn" class="secondary-button">Unsave Recipe</button>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                    <?php if (isset($_SESSION['username']) && $_SESSION['username'] === $recipe['user_id']): ?>
                        <div class="recipe-actions-edit">
                            <h2>Edit Your Recipe</h2>
                            <a href="edit_recipe.php?id=<?php echo htmlspecialchars($recipe['id']); ?>" class="imitate-secondary-button">
                                Edit Recipe
                            </a>
                            <button class="secondary-button warning-button" id="delete-recipe-btn">
                                Delete Recipe
                            </button>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</main>

<?php // load footer
include_once 'assets/includes/footer.php';
?>