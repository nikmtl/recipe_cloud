<?php
/*load_more_recipes.php 
    * This file handles the AJAX request to load more recipes based on search, sort, and category filters.
    * It returns a JSON response with the recipes and pagination information the response is handled in the frontend in load-more-recipes.js.
    * This uses the AJAX request method to fetch more recipes without reloading the page.
*/
require_once 'db.php';

// Only allow AJAX requests
if (!isset($_SERVER['HTTP_X_REQUESTED_WITH']) || strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) !== 'xmlhttprequest') {
    http_response_code(400);
    exit('Bad Request');
}

// Get parameters
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
    }

    // Get total count for pagination
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
    $recipes = $stmt->fetchAll(PDO::FETCH_ASSOC);    $response = [
        'success' => true,
        'recipes' => [],
        'has_more' => $total_recipes > ($offset + $limit),
        'total_recipes' => $total_recipes,
        'current_offset' => $offset,
        'debug_info' => [
            'sort' => $sort,
            'search' => $search,
            'category' => $category,
            'count_query' => $count_query
        ]
    ];    // Generate HTML for each recipe using the recipe card include
    if (!empty($recipes)) {
        foreach ($recipes as $recipe) {
            $total_time = (int)$recipe['prep_time_min'] + (int)$recipe['cook_time_min'];
            $description = $recipe['description'] ? htmlspecialchars(substr($recipe['description'], 0, 60)) : 'Classic recipe with delicious ingredients';
            
            // Use output buffering to capture the included recipe card HTML
            ob_start();
            include '../assets/includes/recipe_card.php';
            $recipe_html = ob_get_clean();
            
            $response['recipes'][] = $recipe_html;
        }
    }

    header('Content-Type: application/json');
    echo json_encode($response);

} catch (PDOException $e) {
    error_log("Database error in load_more_recipes.php: " . $e->getMessage());
    header('Content-Type: application/json');
    echo json_encode([
        'success' => false,
        'error' => 'Database error occurred'
    ]);
}
?>
