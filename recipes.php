<?php
require_once 'be-logic/db.php';
include_once 'assets/includes/header.php'; //load header
?>
<main>
    <div class="recipes-container">
        <h1>All Recipes</h1>
        <p>Browse our collection of delicious recipes</p>

        <div class="search-form">
            <form id="search-form" method="GET" action="recipes.php">
                <input type="text" name="search" placeholder="Search recipes..." value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
                <div class="select-container">
                    <select name="sort" id="sort">
                        <?php
                        $sort_options = [
                            'newest' => 'Newest',
                            'highest_rated' => 'Highest Rated',
                            'quickest' => 'Quickest'
                        ];

                        $current_sort = isset($_GET['sort']) ? $_GET['sort'] : 'newest';

                        foreach ($sort_options as $value => $label) {
                            $selected = ($current_sort == $value) ? 'selected' : '';
                            echo "<option value=\"$value\" $selected>" . htmlspecialchars($label) . "</option>";
                        }
                        ?>
                    </select>
                    <select name="category">
                        <option value="">All Categories</option>
                        <?php
                        // Available categories based on database schema
                        $categories = [
                            'breakfast' => 'Breakfast',
                            'appetizer' => 'Appetizer',
                            'salad' => 'Salad',
                            'soup' => 'Soup',
                            'sandwich' => 'Sandwich',
                            'main' => 'Main Course',
                            'side' => 'Side Dish',
                            'snack' => 'Snack',
                            'dessert' => 'Dessert',
                            'baking' => 'Baking',
                            'sauce' => 'Sauce',
                            'drink' => 'Drink'
                        ];

                        foreach ($categories as $value => $label) {
                            $selected = (isset($_GET['category']) && $_GET['category'] == $value) ? 'selected' : '';
                            echo "<option value=\"$value\" $selected>" . htmlspecialchars($label) . "</option>";
                        }
                        ?>
                    </select>
                    <input class="primary-button" type="submit" value="Search">
                </div>
            </form>
        </div>
        <div class="recipes">
            <?php
            // Get search parameters
            $search = isset($_GET['search']) ? trim($_GET['search']) : '';
            $sort = isset($_GET['sort']) ? $_GET['sort'] : 'newest';
            $category = isset($_GET['category']) ? $_GET['category'] : '';
            $offset = isset($_GET['offset']) ? (int)$_GET['offset'] : 0;
            $limit = 8;

            try {
                // Build the base query
                $base_query = "
                    FROM recipes r 
                    LEFT JOIN users u ON r.user_id = u.username 
                    LEFT JOIN ratings rt ON r.id = rt.recipe_id
                    WHERE 1=1
                ";

                $params = [];

                // Add search condition
                if (!empty($search)) {
                    $base_query .= " AND (r.title LIKE ? OR r.description LIKE ? OR u.username LIKE ?)";
                    $search_param = "%$search%";
                    $params[] = $search_param;
                    $params[] = $search_param;
                    $params[] = $search_param;
                }

                // Add category condition
                if (!empty($category)) {
                    $base_query .= " AND r.category = ?";
                    $params[] = $category;
                }

                $base_query .= " GROUP BY r.id";

                // Add sorting
                switch ($sort) {
                    case 'highest_rated':
                        $order_by = " HAVING COUNT(rt.rating) > 0 ORDER BY AVG(rt.rating) DESC, COUNT(rt.rating) DESC";
                        break;
                    case 'quickest':
                        $order_by = " ORDER BY (r.prep_time_min + r.cook_time_min) ASC";
                        break;
                    case 'newest':
                    default:
                        $order_by = " ORDER BY r.id DESC";
                        break;
                }                  // Get total count for pagination
                // We need to count distinct recipes, not grouped results
                $count_base_query = "
                    FROM recipes r 
                    LEFT JOIN users u ON r.user_id = u.username 
                    LEFT JOIN ratings rt ON r.id = rt.recipe_id
                    WHERE 1=1
                ";
                
                $count_params = [];
                
                // Add search condition to count query
                if (!empty($search)) {
                    $count_base_query .= " AND (r.title LIKE ? OR r.description LIKE ? OR u.username LIKE ?)";
                    $search_param = "%$search%";
                    $count_params[] = $search_param;
                    $count_params[] = $search_param;
                    $count_params[] = $search_param;
                }

                // Add category condition to count query
                if (!empty($category)) {
                    $count_base_query .= " AND r.category = ?";
                    $count_params[] = $category;
                }
                
                if ($sort === 'highest_rated') {
                    // For highest rated, count only recipes that have ratings
                    $count_query = "SELECT COUNT(DISTINCT r.id) as total " . $count_base_query . " AND rt.rating IS NOT NULL";
                } else {
                    // For other sorts, count all recipes
                    $count_query = "SELECT COUNT(DISTINCT r.id) as total " . $count_base_query;
                }
                  $count_stmt = $pdo->prepare($count_query);
                $count_stmt->execute($count_params);
                $total_recipes = $count_stmt->fetch(PDO::FETCH_ASSOC)['total'];
                // Get recipes with limit and offset
                $recipes_query = "
                    SELECT r.*, u.username, 
                           COALESCE(AVG(rt.rating), 0) as avg_rating, 
                           COALESCE(COUNT(rt.rating), 0) as rating_count
                    " . $base_query . $order_by . "
                    LIMIT $limit OFFSET $offset
                ";

                $stmt = $pdo->prepare($recipes_query);
                $stmt->execute($params);
                $recipes = $stmt->fetchAll(PDO::FETCH_ASSOC);

                // Display recipes
                if (!empty($recipes)) {
                    foreach ($recipes as $recipe) {
                        $total_time = (int)$recipe['prep_time_min'] + (int)$recipe['cook_time_min'];
                        $description = $recipe['description'] ? htmlspecialchars(substr($recipe['description'], 0, 60)) : 'Classic recipe with delicious ingredients';
                        include 'assets/includes/recipe_card.php';
                    }
                } else {
                    echo '<p>No recipes found.</p>';
                }            } catch (PDOException $e) {
                echo '<p>Error loading recipes. Please try again later.</p>';
                error_log("Database error in recipes.php: " . $e->getMessage());
                // Initialize total_recipes to 0 in case of error
                $total_recipes = 0;
            }
            ?>
        </div>        <?php
        // Show "Load More" button if there are more recipes
        if (isset($total_recipes) && $total_recipes > ($offset + $limit)) {
            $next_offset = $offset + $limit;
            $current_params = $_GET;
            $current_params['offset'] = $next_offset;
            
            echo '<div class="load-more-container" style="text-align: center; margin-top: 2rem;">';
            echo '<button id="load-more-btn" class="secondary-button medium-button" type="button">Load More Recipes</button>';
            echo '</div>';
        }
        ?>
    </div>
</main>
<?php // load footer
include_once 'assets/includes/footer.php';
?>