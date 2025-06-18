
<?php 
require_once 'be-logic/db.php';
//load header
include_once 'assets/includes/header.php';

// Display success message if redirected from upload
if (isset($_SESSION['upload_success'])) {
    echo '<main><div class="alert alert-success">' . htmlspecialchars($_SESSION['upload_success']) . '</div></main>';
    unset($_SESSION['upload_success']);
}
?>
<main>
    <div>
        <h1>All Recipes</h1>
        <p>Browse our collection of delicious recipes</p>
        
        <?php
        try {
            // Fetch all recipes from database
            $sql = "SELECT r.*, u.username FROM recipes r 
                    JOIN users u ON r.user_id = u.username 
                    ORDER BY r.id DESC";
            $stmt = $pdo->prepare($sql);
            $stmt->execute();
            $recipes = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            if (empty($recipes)) {
                echo '<p>No recipes found. <a href="upload.php">Upload the first recipe!</a></p>';
            } else {
                echo '<div class="recipes-grid">';
                foreach ($recipes as $recipe) {
                    echo '<div class="recipe-card">';
                    if ($recipe['image_path']) {
                        echo '<img src="' . htmlspecialchars($recipe['image_path']) . '" alt="' . htmlspecialchars($recipe['title']) . '">';
                    }
                    echo '<h3>' . htmlspecialchars($recipe['title']) . '</h3>';
                    echo '<p>By ' . htmlspecialchars($recipe['username']) . '</p>';
                    if ($recipe['description']) {
                        echo '<p>' . htmlspecialchars(substr($recipe['description'], 0, 100)) . '...</p>';
                    }
                    echo '<div class="recipe-meta">';
                    if ($recipe['prep_time_min']) echo '<span>Prep: ' . $recipe['prep_time_min'] . ' min</span>';
                    if ($recipe['cook_time_min']) echo '<span>Cook: ' . $recipe['cook_time_min'] . ' min</span>';
                    echo '<span>Difficulty: ' . $recipe['difficulty'] . '/3</span>';
                    echo '<span>Serves: ' . $recipe['servings'] . '</span>';
                    echo '</div>';
                    echo '</div>';
                }
                echo '</div>';
            }
        } catch (Exception $e) {
            echo '<p>Error loading recipes.</p>';
            error_log("Error loading recipes: " . $e->getMessage());
        }
        ?>
    </div>
</main>

<?php // load footer
include_once 'assets/includes/footer.php';
?>