<?php
/* load_standard_data.php
    * This file is responsible for loading standard data for testing purposes.
*/

// Include the database connection file
require_once 'db.php';

/**
 * Main function to create standard test recipes
 * Call this function to populate the database with sensible test recipes
 */
function createStandardRecipes($pdo): array {
    $results = [];
    
    // First, create a default user for Recipe Cloud if it doesn't exist
    $recipeCloudUser = createRecipeCloudUser($pdo);
    
    if (!$recipeCloudUser['success']) {
        $results[] = $recipeCloudUser;
        return $results;
    }
    
    // Define standard recipes
    $standardRecipes = getStandardRecipesData();
    
    // Insert each recipe
    foreach ($standardRecipes as $recipeData) {
        $result = insertStandardRecipe($pdo, $recipeData);
        $results[] = $result;
    }
    
    return $results;
}

/**
 * Create the Recipe Cloud default user
 */
function createRecipeCloudUser($pdo): array {
    try {
        // Check if Recipe Cloud user already exists
        $stmt = $pdo->prepare("SELECT username FROM users WHERE username = ?");
        $stmt->execute(['recipecloud']);
        
        if ($stmt->fetch()) {
            return ['success' => true, 'message' => 'Recipe Cloud user already exists'];
        }
        
        // Create Recipe Cloud user
        $sql = "INSERT INTO users (first_name, last_name, username, email, password_hash, bio) 
                VALUES (?, ?, ?, ?, ?, ?)";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            'Recipe',
            'Cloud',
            'recipecloud',
            'admin@recipecloud.com',
            password_hash('RecipeCloud2025!', PASSWORD_DEFAULT),
            'Official Recipe Cloud account providing delicious and tested recipes for everyone to enjoy!'
        ]);
        
        return ['success' => true, 'message' => 'Recipe Cloud user created successfully'];
        
    } catch (Exception $e) {
        return ['success' => false, 'message' => 'Error creating Recipe Cloud user: ' . $e->getMessage()];
    }
}

/**
 * Get array of standard recipe data
 * These recipes are AI generated and meant for testing purposes
 */
function getStandardRecipesData(): array {
    return [
        [
            'title' => 'Classic Chocolate Chip Cookies',
            'description' => 'Soft and chewy chocolate chip cookies that are perfect for any occasion. A timeless favorite that never goes out of style.',
            'prep_time_min' => 15,
            'cook_time_min' => 12,
            'difficulty' => 1,
            'servings' => 24,
            'category' => 'dessert',
            'image_filename' => 'chocolate-chip-cookies.jpg',
            'ingredients' => [
                ['amount' => 225, 'unit' => 'g', 'ingredient' => 'all-purpose flour'],
                ['amount' => 1, 'unit' => 'tsp', 'ingredient' => 'baking soda'],
                ['amount' => 1, 'unit' => 'tsp', 'ingredient' => 'salt'],
                ['amount' => 226, 'unit' => 'g', 'ingredient' => 'unsalted butter, softened'],
                ['amount' => 150, 'unit' => 'g', 'ingredient' => 'granulated sugar'],
                ['amount' => 165, 'unit' => 'g', 'ingredient' => 'brown sugar, packed'],
                ['amount' => 2, 'unit' => null, 'ingredient' => 'large eggs'],
                ['amount' => 2, 'unit' => 'tsp', 'ingredient' => 'vanilla extract'],
                ['amount' => 340, 'unit' => 'g', 'ingredient' => 'chocolate chips']
            ],
            'instructions' => [
                'Preheat oven to 375°F (190°C). Line baking sheets with parchment paper.',
                'In a medium bowl, whisk together flour, baking soda, and salt. Set aside.',
                'In a large bowl, cream together softened butter and both sugars until light and fluffy.',
                'Beat in eggs one at a time, then add vanilla extract.',
                'Gradually mix in the flour mixture until just combined.',
                'Fold in chocolate chips until evenly distributed.',
                'Drop rounded tablespoons of dough onto prepared baking sheets, spacing 2 inches apart.',
                'Bake for 9-11 minutes until golden brown around edges but still soft in center.',
                'Cool on baking sheet for 5 minutes before transferring to a wire rack.'
            ]
        ],        [
            'title' => 'Mediterranean Quinoa Salad',
            'description' => 'Fresh and healthy quinoa salad packed with Mediterranean flavors. Perfect as a light lunch or side dish.',
            'prep_time_min' => 20,
            'cook_time_min' => 15,
            'difficulty' => 1,
            'servings' => 6,
            'category' => 'salad',
            'image_filename' => 'mediterranean-quinoa-salad.jpg',
            'ingredients' => [
                ['amount' => 200, 'unit' => 'g', 'ingredient' => 'quinoa'],
                ['amount' => 400, 'unit' => 'ml', 'ingredient' => 'vegetable broth'],
                ['amount' => 200, 'unit' => 'g', 'ingredient' => 'cherry tomatoes, halved'],
                ['amount' => 1, 'unit' => null, 'ingredient' => 'cucumber, diced'],
                ['amount' => 100, 'unit' => 'g', 'ingredient' => 'feta cheese, crumbled'],
                ['amount' => 80, 'unit' => 'g', 'ingredient' => 'kalamata olives, pitted'],
                ['amount' => 50, 'unit' => 'g', 'ingredient' => 'red onion, finely chopped'],
                ['amount' => 3, 'unit' => 'tbsp', 'ingredient' => 'extra virgin olive oil'],
                ['amount' => 2, 'unit' => 'tbsp', 'ingredient' => 'lemon juice'],
                ['amount' => 1, 'unit' => 'tsp', 'ingredient' => 'dried oregano'],
                ['amount' => null, 'unit' => null, 'ingredient' => 'salt and pepper to taste'],
                ['amount' => 30, 'unit' => 'g', 'ingredient' => 'fresh parsley, chopped']
            ],
            'instructions' => [
                'Rinse quinoa under cold water until water runs clear.',
                'In a medium saucepan, bring vegetable broth to a boil.',
                'Add quinoa, reduce heat to low, cover and simmer for 15 minutes.',
                'Remove from heat and let stand 5 minutes, then fluff with a fork.',
                'Let quinoa cool to room temperature.',
                'In a large bowl, combine cooled quinoa, tomatoes, cucumber, feta, olives, and red onion.',
                'In a small bowl, whisk together olive oil, lemon juice, oregano, salt, and pepper.',
                'Pour dressing over salad and toss to combine.',
                'Garnish with fresh parsley and serve chilled or at room temperature.'
            ]
        ],        [
            'title' => 'Homemade Chicken Noodle Soup',
            'description' => 'Comforting and hearty chicken noodle soup made from scratch. Perfect for cold days or when you need some comfort food.',
            'prep_time_min' => 30,
            'cook_time_min' => 45,
            'difficulty' => 2,
            'servings' => 8,
            'category' => 'soup',
            'image_filename' => 'chicken-noodle-soup.jpg',
            'ingredients' => [
                ['amount' => 1, 'unit' => 'kg', 'ingredient' => 'whole chicken'],
                ['amount' => 2, 'unit' => null, 'ingredient' => 'bay leaves'],
                ['amount' => 2, 'unit' => null, 'ingredient' => 'carrots, sliced'],
                ['amount' => 2, 'unit' => null, 'ingredient' => 'celery stalks, chopped'],
                ['amount' => 1, 'unit' => null, 'ingredient' => 'medium onion, diced'],
                ['amount' => 3, 'unit' => null, 'ingredient' => 'garlic cloves, minced'],
                ['amount' => 200, 'unit' => 'g', 'ingredient' => 'egg noodles'],
                ['amount' => 2, 'unit' => 'tbsp', 'ingredient' => 'olive oil'],
                ['amount' => 1, 'unit' => 'tsp', 'ingredient' => 'dried thyme'],
                ['amount' => null, 'unit' => null, 'ingredient' => 'salt and pepper to taste'],
                ['amount' => 30, 'unit' => 'g', 'ingredient' => 'fresh parsley, chopped']
            ],
            'instructions' => [
                'Place whole chicken in a large pot and cover with water. Add bay leaves.',
                'Bring to a boil, then reduce heat and simmer for 1 hour until chicken is tender.',
                'Remove chicken from pot and let cool. Strain and reserve the broth.',
                'When cool enough to handle, remove skin and shred chicken meat.',
                'Heat olive oil in the same pot over medium heat.',
                'Add onion, carrots, and celery. Cook until vegetables start to soften, about 5 minutes.',
                'Add garlic and thyme, cook for another minute.',
                'Pour in reserved broth and bring to a boil.',
                'Add egg noodles and cook according to package directions.',
                'Stir in shredded chicken and season with salt and pepper.',
                'Garnish with fresh parsley before serving.'
            ]
        ],        [
            'title' => 'Avocado Toast with Poached Egg',
            'description' => 'Trendy and nutritious breakfast featuring creamy avocado and a perfectly poached egg on toasted sourdough.',
            'prep_time_min' => 10,
            'cook_time_min' => 5,
            'difficulty' => 2,
            'servings' => 2,
            'category' => 'breakfast',
            'image_filename' => 'avocado-toast-poached-egg.jpg',
            'ingredients' => [
                ['amount' => 2, 'unit' => null, 'ingredient' => 'slices sourdough bread'],
                ['amount' => 1, 'unit' => null, 'ingredient' => 'ripe avocado'],
                ['amount' => 2, 'unit' => null, 'ingredient' => 'fresh eggs'],
                ['amount' => 1, 'unit' => 'tbsp', 'ingredient' => 'white vinegar'],
                ['amount' => 1, 'unit' => 'tbsp', 'ingredient' => 'lemon juice'],
                ['amount' => null, 'unit' => null, 'ingredient' => 'salt and pepper to taste'],
                ['amount' => null, 'unit' => null, 'ingredient' => 'red pepper flakes (optional)'],
                ['amount' => 1, 'unit' => 'tbsp', 'ingredient' => 'extra virgin olive oil']
            ],
            'instructions' => [
                'Fill a medium saucepan with water and bring to a gentle simmer. Add vinegar.',
                'Toast the sourdough slices until golden brown.',
                'Cut avocado in half, remove pit, and mash with lemon juice, salt, and pepper.',
                'Crack each egg into a small bowl.',
                'Create a gentle whirlpool in the simmering water with a spoon.',
                'Carefully drop one egg into the center of the whirlpool. Repeat with second egg.',
                'Poach eggs for 3-4 minutes until whites are set but yolks are still runny.',
                'Spread mashed avocado evenly on toasted bread.',
                'Using a slotted spoon, carefully place poached eggs on top of avocado.',
                'Drizzle with olive oil, season with salt, pepper, and red pepper flakes if desired.'
            ]
        ],        [
            'title' => 'Beef Stir-Fry with Vegetables',
            'description' => 'Quick and flavorful beef stir-fry with colorful vegetables in a savory sauce. Perfect for busy weeknight dinners.',
            'prep_time_min' => 20,
            'cook_time_min' => 15,
            'difficulty' => 2,
            'servings' => 4,
            'category' => 'main',
            'image_filename' => 'beef-stir-fry.jpg',
            'ingredients' => [
                ['amount' => 500, 'unit' => 'g', 'ingredient' => 'beef sirloin, sliced thin'],
                ['amount' => 2, 'unit' => 'tbsp', 'ingredient' => 'vegetable oil'],
                ['amount' => 1, 'unit' => null, 'ingredient' => 'bell pepper, sliced'],
                ['amount' => 1, 'unit' => null, 'ingredient' => 'broccoli crown, cut into florets'],
                ['amount' => 100, 'unit' => 'g', 'ingredient' => 'snap peas'],
                ['amount' => 1, 'unit' => null, 'ingredient' => 'carrot, julienned'],
                ['amount' => 3, 'unit' => null, 'ingredient' => 'garlic cloves, minced'],
                ['amount' => 1, 'unit' => 'tbsp', 'ingredient' => 'fresh ginger, grated'],
                ['amount' => 3, 'unit' => 'tbsp', 'ingredient' => 'soy sauce'],
                ['amount' => 2, 'unit' => 'tbsp', 'ingredient' => 'oyster sauce'],
                ['amount' => 1, 'unit' => 'tbsp', 'ingredient' => 'cornstarch'],
                ['amount' => 2, 'unit' => 'tbsp', 'ingredient' => 'water'],
                ['amount' => 1, 'unit' => 'tsp', 'ingredient' => 'sesame oil'],
                ['amount' => 2, 'unit' => null, 'ingredient' => 'green onions, chopped']
            ],
            'instructions' => [
                'Slice beef against the grain into thin strips. Pat dry with paper towels.',
                'Mix cornstarch with water to make a slurry. Set aside.',
                'Heat 1 tablespoon oil in a large wok or skillet over high heat.',
                'Add beef and stir-fry for 2-3 minutes until browned. Remove and set aside.',
                'Add remaining oil to the same pan.',
                'Add garlic and ginger, stir-fry for 30 seconds until fragrant.',
                'Add harder vegetables (broccoli, carrots) first, stir-fry for 2 minutes.',
                'Add bell pepper and snap peas, stir-fry for another 2 minutes.',
                'Return beef to pan and add soy sauce and oyster sauce.',
                'Add cornstarch slurry and stir until sauce thickens, about 1 minute.',
                'Remove from heat, drizzle with sesame oil, and garnish with green onions.',
                'Serve immediately over steamed rice.'
            ]
        ],        [
            'title' => 'Classic Banana Bread',
            'description' => 'Moist and delicious banana bread perfect for using up overripe bananas. Great for breakfast or as a snack.',
            'prep_time_min' => 15,
            'cook_time_min' => 60,
            'difficulty' => 1,
            'servings' => 12,
            'category' => 'baking',
            'image_filename' => 'banana-bread.jpg',
            'ingredients' => [
                ['amount' => 190, 'unit' => 'g', 'ingredient' => 'all-purpose flour'],
                ['amount' => 1, 'unit' => 'tsp', 'ingredient' => 'baking soda'],
                ['amount' => 1, 'unit' => 'tsp', 'ingredient' => 'salt'],
                ['amount' => 115, 'unit' => 'g', 'ingredient' => 'unsalted butter, melted'],
                ['amount' => 150, 'unit' => 'g', 'ingredient' => 'brown sugar'],
                ['amount' => 1, 'unit' => null, 'ingredient' => 'large egg, beaten'],
                ['amount' => 1, 'unit' => 'tsp', 'ingredient' => 'vanilla extract'],
                ['amount' => 3, 'unit' => null, 'ingredient' => 'very ripe bananas, mashed'],
                ['amount' => 80, 'unit' => 'g', 'ingredient' => 'chopped walnuts (optional)']
            ],
            'instructions' => [
                'Preheat oven to 350°F (175°C). Grease a 9x5 inch loaf pan.',
                'In a large bowl, mix flour, baking soda, and salt.',
                'In another bowl, mix melted butter and brown sugar.',
                'Stir in egg and vanilla extract.',
                'Add mashed bananas and mix well.',
                'Add wet ingredients to dry ingredients and stir until just combined.',
                'Fold in walnuts if using.',
                'Pour batter into prepared loaf pan.',
                'Bake for 55-65 minutes until a toothpick inserted in center comes out clean.',                'Cool in pan for 10 minutes, then turn out onto wire rack to cool completely.'
            ]
        ],        [
            'title' => 'Creamy Mushroom Risotto',
            'description' => 'Rich and creamy Italian risotto with mixed mushrooms and parmesan cheese. A comforting and elegant dish perfect for dinner.',
            'prep_time_min' => 15,
            'cook_time_min' => 30,
            'difficulty' => 3,
            'servings' => 4,
            'category' => 'main',
            'image_filename' => 'mushroom-risotto.jpg',
            'ingredients' => [
                ['amount' => 300, 'unit' => 'g', 'ingredient' => 'arborio rice'],
                ['amount' => 1, 'unit' => 'l', 'ingredient' => 'warm chicken or vegetable stock'],
                ['amount' => 400, 'unit' => 'g', 'ingredient' => 'mixed mushrooms, sliced'],
                ['amount' => 1, 'unit' => null, 'ingredient' => 'medium onion, finely chopped'],
                ['amount' => 3, 'unit' => null, 'ingredient' => 'garlic cloves, minced'],
                ['amount' => 125, 'unit' => 'ml', 'ingredient' => 'dry white wine'],
                ['amount' => 50, 'unit' => 'g', 'ingredient' => 'butter'],
                ['amount' => 2, 'unit' => 'tbsp', 'ingredient' => 'olive oil'],
                ['amount' => 100, 'unit' => 'g', 'ingredient' => 'parmesan cheese, grated'],
                ['amount' => 2, 'unit' => 'tbsp', 'ingredient' => 'fresh parsley, chopped'],
                ['amount' => null, 'unit' => null, 'ingredient' => 'salt and black pepper to taste']
            ],
            'instructions' => [
                'Heat olive oil and half the butter in a large, heavy-bottomed pan over medium heat.',
                'Add mushrooms and cook until golden brown and moisture has evaporated, about 8 minutes.',
                'Season mushrooms with salt and pepper, then remove and set aside.',
                'In the same pan, add onion and cook until softened, about 3 minutes.',
                'Add garlic and cook for another minute until fragrant.',
                'Add rice and stir for 2 minutes until grains are coated and lightly toasted.',
                'Pour in wine and stir until mostly absorbed.',
                'Add warm stock one ladle at a time, stirring constantly until absorbed before adding more.',
                'Continue for 18-20 minutes until rice is creamy but still has a slight bite.',
                'Stir in cooked mushrooms, remaining butter, and half the parmesan.',
                'Season with salt and pepper, garnish with parsley and remaining parmesan.'
            ]
        ],        [
            'title' => 'Fish Tacos with Lime Crema',
            'description' => 'Fresh and zesty fish tacos with crispy white fish, crunchy cabbage slaw, and tangy lime crema. Perfect for a light and flavorful meal.',
            'prep_time_min' => 25,
            'cook_time_min' => 10,
            'difficulty' => 2,
            'servings' => 4,
            'category' => 'main',
            'image_filename' => 'fish-tacos.jpg',
            'ingredients' => [
                ['amount' => 500, 'unit' => 'g', 'ingredient' => 'white fish fillets (cod or tilapia)'],
                ['amount' => 8, 'unit' => null, 'ingredient' => 'small corn tortillas'],
                ['amount' => 200, 'unit' => 'g', 'ingredient' => 'red cabbage, thinly sliced'],
                ['amount' => 100, 'unit' => 'g', 'ingredient' => 'green cabbage, thinly sliced'],
                ['amount' => 1, 'unit' => null, 'ingredient' => 'avocado, sliced'],
                ['amount' => 125, 'unit' => 'ml', 'ingredient' => 'sour cream'],
                ['amount' => 2, 'unit' => null, 'ingredient' => 'limes, juiced'],
                ['amount' => 1, 'unit' => 'tsp', 'ingredient' => 'cumin'],
                ['amount' => 1, 'unit' => 'tsp', 'ingredient' => 'chili powder'],
                ['amount' => 1, 'unit' => 'tsp', 'ingredient' => 'paprika'],
                ['amount' => 2, 'unit' => 'tbsp', 'ingredient' => 'vegetable oil'],
                ['amount' => 30, 'unit' => 'g', 'ingredient' => 'fresh cilantro, chopped'],
                ['amount' => null, 'unit' => null, 'ingredient' => 'salt and pepper to taste']
            ],
            'instructions' => [
                'Mix cumin, chili powder, paprika, salt, and pepper in a small bowl.',
                'Pat fish fillets dry and season both sides with spice mixture.',
                'Heat oil in a large skillet over medium-high heat.',
                'Cook fish for 3-4 minutes per side until golden and flakes easily.',
                'Remove from heat and break into bite-sized pieces.',
                'In a bowl, combine sour cream with half the lime juice and salt to make crema.',
                'Mix both cabbages with remaining lime juice and a pinch of salt.',
                'Warm tortillas in a dry skillet or microwave.',
                'Assemble tacos: place fish on tortillas, top with cabbage slaw.',
                'Add avocado slices and drizzle with lime crema.',
                'Garnish with fresh cilantro and serve immediately.'
            ]
        ],        [
            'title' => 'Berry Parfait with Greek Yogurt',
            'description' => 'Healthy and delicious layered parfait with creamy Greek yogurt, fresh berries, and crunchy granola. Perfect for breakfast or a light dessert.',
            'prep_time_min' => 10,
            'cook_time_min' => 0,
            'difficulty' => 1,
            'servings' => 4,
            'category' => 'breakfast',
            'image_filename' => 'berry-parfait.jpg',
            'ingredients' => [
                ['amount' => 500, 'unit' => 'g', 'ingredient' => 'Greek yogurt, plain'],
                ['amount' => 200, 'unit' => 'g', 'ingredient' => 'mixed berries (strawberries, blueberries, raspberries)'],
                ['amount' => 100, 'unit' => 'g', 'ingredient' => 'granola'],
                ['amount' => 2, 'unit' => 'tbsp', 'ingredient' => 'honey'],
                ['amount' => 1, 'unit' => 'tsp', 'ingredient' => 'vanilla extract'],
                ['amount' => 30, 'unit' => 'g', 'ingredient' => 'sliced almonds'],
                ['amount' => 1, 'unit' => 'tbsp', 'ingredient' => 'chia seeds (optional)'],
                ['amount' => 10, 'unit' => 'g', 'ingredient' => 'fresh mint leaves for garnish']
            ],
            'instructions' => [
                'In a bowl, mix Greek yogurt with honey and vanilla extract until smooth.',
                'Wash and prepare berries - hull strawberries and cut into slices.',
                'In clear glasses or bowls, start with a layer of yogurt mixture.',
                'Add a layer of mixed berries on top of yogurt.',
                'Sprinkle a layer of granola over the berries.',
                'Repeat layers until glasses are filled, ending with berries on top.',
                'Garnish with sliced almonds and chia seeds if using.',
                'Top with a fresh mint leaf for decoration.',
                'Serve immediately or chill for up to 2 hours before serving.',
                'Best enjoyed fresh to maintain granola crunchiness.'
            ]
        ]
    ];
}

/**
 * Insert a single standard recipe into the database
 */
function insertStandardRecipe($pdo, $recipeData): array {
    try {
        // Check if recipe already exists (by title and user)
        $checkSql = "SELECT id FROM recipes WHERE title = ? AND user_id = ?";
        $checkStmt = $pdo->prepare($checkSql);
        $checkStmt->execute([$recipeData['title'], 'recipecloud']);
        
        if ($checkStmt->fetch()) {
            return [
                'success' => true, 
                'message' => 'Recipe already exists (skipped): ' . $recipeData['title']
            ];
        }
        
        // Start transaction
        $pdo->beginTransaction();
        
        // Handle image path - check if image exists in standard folder
        $imagePath = null;
        if (isset($recipeData['image_filename'])) {
            $standardImagePath = __DIR__ . '/../uploads/recipes/standard/' . $recipeData['image_filename'];
            if (file_exists($standardImagePath)) {
                $imagePath = 'uploads/recipes/standard/' . $recipeData['image_filename'];
            }
        }
        
        // Insert recipe
        $sql = "INSERT INTO recipes (user_id, title, description, prep_time_min, cook_time_min, difficulty, servings, category, image_path) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            'recipecloud',
            $recipeData['title'],
            $recipeData['description'],
            $recipeData['prep_time_min'],
            $recipeData['cook_time_min'],
            $recipeData['difficulty'],
            $recipeData['servings'],
            $recipeData['category'],
            $imagePath
        ]);
        
        $recipeId = $pdo->lastInsertId();
        
        // Insert ingredients
        $ingredientSql = "INSERT INTO ingredients (amount, unit, ingredient, recipe_id) VALUES (?, ?, ?, ?)";
        $ingredientStmt = $pdo->prepare($ingredientSql);
        
        foreach ($recipeData['ingredients'] as $ingredient) {
            $ingredientStmt->execute([
                $ingredient['amount'],
                $ingredient['unit'],
                $ingredient['ingredient'],
                $recipeId
            ]);
        }
        
        // Insert instructions
        $instructionSql = "INSERT INTO instructions (step_number, instruction, recipe_id) VALUES (?, ?, ?)";
        $instructionStmt = $pdo->prepare($instructionSql);
        
        foreach ($recipeData['instructions'] as $index => $instruction) {
            $instructionStmt->execute([
                $index + 1,
                $instruction,
                $recipeId
            ]);
        }
        
        // Commit transaction
        $pdo->commit();
        
        return [
            'success' => true, 
            'message' => 'Successfully created recipe: ' . $recipeData['title'],
            'recipe_id' => $recipeId
        ];
        
    } catch (Exception $e) {
        // Rollback transaction
        $pdo->rollback();
        return [
            'success' => false, 
            'message' => 'Error creating recipe "' . $recipeData['title'] . '": ' . $e->getMessage()
        ];
    }
}

/**
 * Function to call when you want to create standard recipes
 * Usage: Call this function from a script or webpage to populate the database
 */
function loadStandardData(): void {
    global $pdo;
    
    echo "<h2>Loading Standard Recipe Data...</h2>\n";
    
    $results = createStandardRecipes($pdo);
    
    $added = 0;
    $skipped = 0;
    $errors = 0;
    
    foreach ($results as $result) {
        if ($result['success']) {
            if (strpos($result['message'], 'already exists') !== false || strpos($result['message'], 'skipped') !== false) {
                echo "<p style='color: orange;'>⚠ " . $result['message'] . "</p>\n";
                $skipped++;
            } else {
                echo "<p style='color: green;'>✓ " . $result['message'] . "</p>\n";
                $added++;
            }
        } else {
            echo "<p style='color: red;'>✗ " . $result['message'] . "</p>\n";
            $errors++;
        }
    }
    
    echo "<h3>Standard data loading completed!</h3>\n";
    echo "<p><strong>Summary:</strong> $added recipes added, $skipped recipes skipped (already exist), $errors errors</p>\n";
}

loadStandardData();
