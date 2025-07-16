<?php
/* index.php  
    * This is the home/index/landing page of the Recipe Cloud application.
    * It displays the hero section, featured recipes, and how-to steps.
    * The featured recipes are fetched from the database and displayed in a grid format.
    * It also includes buttons to upload a recipe or browse all recipes.
*/
require_once 'be-logic/db.php'; // Load database connection
include_once 'assets/includes/header.php'; // Load the header
?>

<main>
    <div>
        <!-- Title and Welcome Message -->
        <div class="hero-container">
            <h1>Recipe Cloud</h1>
            <p>Discover, share, and rate delicious recipes from around the world</p>
            <div>
                <button class="icon-button large-button" onclick="location.href='upload.php'">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-plus mr-2 h-4 w-4">
                        <path d="M5 12h14"></path>
                        <path d="M12 5v14"></path>
                    </svg>
                    Upload Recipe
                </button>
                <button class="secondary-button large-button" onclick="location.href='recipes.php'">
                    Browse All Recipes
                </button>
            </div>
        </div>
        <!-- Featured Recipes Section -->
        <div class="featured-recipes-container">
            <div class="featured-recipes-header">
                <h2>Featured Recipes</h2>
                <a href="recipes.php">View all</a>
            </div>
            <div class="featured-recipes">
                <?php                // Fetch featured recipes (top 4 best rated recipes)
                try {
                    $stmt = $pdo->prepare("
                        SELECT r.*, u.username, 
                               AVG(rt.rating) as avg_rating, 
                               COUNT(rt.rating) as rating_count
                        FROM recipes r 
                        LEFT JOIN users u ON r.user_id = u.username 
                        LEFT JOIN ratings rt ON r.id = rt.recipe_id
                        GROUP BY r.id
                        HAVING rating_count > 0
                        ORDER BY avg_rating DESC, rating_count DESC
                        LIMIT 4
                    ");
                    $stmt->execute();
                    $featured_recipes = $stmt->fetchAll(PDO::FETCH_ASSOC);                    // Fill with additional recipes if less than 4 featured recipes found
                    if (count($featured_recipes) < 4) {
                        $featured_ids = array_column($featured_recipes, 'id');
                        $excluded_condition = "";
                        if (count($featured_ids) > 0) {
                            $placeholders = str_repeat('?,', count($featured_ids) - 1) . '?';
                            $excluded_condition = "AND r.id NOT IN ($placeholders)";
                        }
                        
                        $stmt = $pdo->prepare("
                            SELECT r.*, u.username, 
                                   COALESCE(AVG(rt.rating), 0) as avg_rating, 
                                   COALESCE(COUNT(rt.rating), 0) as rating_count
                            FROM recipes r 
                            LEFT JOIN users u ON r.user_id = u.username 
                            LEFT JOIN ratings rt ON r.id = rt.recipe_id
                            WHERE 1=1 $excluded_condition
                            GROUP BY r.id
                            ORDER BY r.id DESC
                            LIMIT " . (4 - count($featured_recipes))
                        );
                        if (count($featured_ids) > 0) {
                            $stmt->execute($featured_ids);
                        } else {
                            $stmt->execute();
                        }
                        $additional_recipes = $stmt->fetchAll(PDO::FETCH_ASSOC);
                        $featured_recipes = array_merge($featured_recipes, $additional_recipes);
                    }                    // Display featured recipes
                    foreach ($featured_recipes as $recipe){
                        $total_time = (int)$recipe['prep_time_min'] + (int)$recipe['cook_time_min'];
                        if ($recipe['description']) {
                            $full_description = $recipe['description'];
                            $description = htmlspecialchars(substr($full_description, 0, 60));
                            if (strlen($full_description) > 60) {
                                $description .= '...';
                            }
                        } else {
                            $description = 'Classic recipe with delicious ingredients';
                        }
                        include 'assets/includes/recipe_card.php';
                    }

                    if (empty($featured_recipes)) {
                        echo '<p>No recipes available yet. <a href="upload.php">Upload the first recipe!</a></p>';
                    }
                } catch (PDOException $e) {
                    echo '<p>Error loading featured recipes. Please try again later.</p>';
                    error_log("Database error: " . $e->getMessage());
                }
                ?>
            </div>
        </div>
        <!-- Start Sharing Section -->
        <div class="start-sharing-container">
            <div class="start-sharing">
                <h2>Share Your Culinary Creations</h2>
                <p>Have a recipe that everyone loves? Share it with our community and get feedback from food enthusiasts.</p>
                <button class="medium-button" onclick="location.href='upload.php'">
                    Start Sharing
                </button>
            </div>
            <div class="start-sharing-image">
                <div>
                    <img src="assets/img/logo.svg" alt="logo">
                </div>
            </div>
        </div>
        <!-- How it works Section -->
        <div class="how-to-container">
            <h2>How it works</h2>
            <div>
                <div class="how-to-step">
                    <div>
                        <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-plus h-8 w-8 text-primary">
                            <path d="M5 12h14"></path>
                            <path d="M12 5v14"></path>
                        </svg>
                    </div>
                    <h3>Upload</h3>
                    <p>Share your favorite recipes with detailed instructions and photos</p>
                </div>
                <div class="how-to-step">
                    <div>
                        <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-8 w-8 text-primary">
                            <path d="M12 20v-6M6 20V10M18 20V4"></path>
                        </svg>
                    </div>
                    <h3>Discover</h3>
                    <p>Find new recipes from chefs and home cooks around the world</p>
                </div>
                <div class="how-to-step">
                    <div>
                        <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-star h-8 w-8 text-primary">
                            <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon>
                        </svg>
                    </div>
                    <h3>Rate & Review</h3>
                    <p>Give feedback on recipes you've tried and help others find the best ones</p>
                </div>
            </div>
        </div>
    </div>
</main>
<?php // Load the footer
include_once 'assets/includes/footer.php';
?>
