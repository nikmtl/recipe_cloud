<?php
/*edit_recipe.php 
    * This file allows users to edit existing recipes.
    * It includes a form with multiple steps for editing recipe details, ingredients, instructions, and media.
    * The form is pre-populated with existing recipe data.
    * Only the recipe owner can edit their recipes.
    * After validation, this form submits to the edit_recipe.php handler for processing.
*/
// Load dependencies
require_once 'be-logic/protected_page.php'; // Ensure the user is logged in
require_once 'be-logic/db.php';

// Check if recipe ID is provided
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header('Location: recipes.php');
    exit();
}

$recipe_id = (int)$_GET['id'];

// Fetch recipe details and verify ownership
try {
    $stmt = $pdo->prepare("SELECT * FROM recipes WHERE id = ?");
    $stmt->execute([$recipe_id]);
    $recipe = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$recipe) {
        $_SESSION['error'] = "Recipe not found.";
        header('Location: recipes.php');
        exit();
    }    // Check if the current user owns this recipe
    if ($recipe['user_id'] !== $_SESSION['username']) {
        $_SESSION['error'] = "You can only edit your own recipes.";
        header('Location: recipes.php');
        exit();
    }

    // Fetch ingredients
    $stmt = $pdo->prepare("SELECT * FROM ingredients WHERE recipe_id = ?");
    $stmt->execute([$recipe_id]);
    $ingredients = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Fetch instructions
    $stmt = $pdo->prepare("SELECT * FROM instructions WHERE recipe_id = ? ORDER BY step_number");
    $stmt->execute([$recipe_id]);
    $instructions = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    error_log("Database error: " . $e->getMessage());
    $_SESSION['error'] = "Database error occurred.";
    header('Location: recipes.php');
    exit();
}

include_once 'assets/includes/header.php';

// Get errors from session and then clear them
$errors = $_SESSION['errors'] ?? [];
if (isset($_SESSION['errors'])) {
    unset($_SESSION['errors']);
}

// Get preserved form data if it exists (this is used to restore the form data after a failed submission)
$formData = $_SESSION['edit_form_data'] ?? [];

// Clear the preserved data after using it
unset($_SESSION['edit_form_data']);

// Helper function to get form value - prioritize form data over database data
function getFormValue($key, $default = ''): string
{
    global $formData, $recipe;
    if (isset($formData[$key])) {
        return htmlspecialchars($formData[$key]);
    }
    // Map form fields to database fields
    $dbMapping = [
        'recipe-title' => 'title',
        'recipe-description' => 'description',
        'recipe-prep-time' => 'prep_time_min',
        'recipe-cook-time' => 'cook_time_min',
        'recipe-difficulty' => 'difficulty',
        'recipe-servings' => 'servings',
        'recipe-category' => 'category'
    ];

    if (isset($dbMapping[$key]) && isset($recipe[$dbMapping[$key]])) {
        $value = $recipe[$dbMapping[$key]];
        // Convert time from minutes to display format
        if (($key === 'recipe-prep-time' || $key === 'recipe-cook-time') && $value !== null) {
            return htmlspecialchars($value); // Keep in minutes for now
        }
        return htmlspecialchars($value);
    }

    return htmlspecialchars($default);
}

// Helper function to check if option is selected
function isSelected($key, $value): string
{
    global $formData, $recipe;
    if (isset($formData[$key])) {
        return $formData[$key] === $value ? 'selected' : '';
    }

    $dbMapping = [
        'recipe-prep-time-format' => 'prep_time_format',
        'recipe-cook-time-format' => 'cook_time_format',
        'recipe-difficulty' => 'difficulty',
        'recipe-category' => 'category'
    ];

    if (isset($dbMapping[$key]) && isset($recipe[$dbMapping[$key]])) {
        return $recipe[$dbMapping[$key]] == $value ? 'selected' : '';
    }

    // Default time format is minutes
    if (($key === 'recipe-prep-time-format' || $key === 'recipe-cook-time-format') && $value === 'minutes') {
        return 'selected';
    }

    return '';
}
?>

<main>
    <div class="upload-container">
        <a class="back-button" href="recipe.php?id=<?php echo $recipe_id; ?>">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="m12 19-7-7 7-7"></path>
                <path d="M19 12H5"></path>
            </svg>
            Back to Recipe
        </a>
        <div class="upload-header">
            <img src="assets/img/logo.svg" alt="Recipe Cloud Logo" width="24" height="24">
            <h1>Edit Recipe</h1>
        </div>
        <p class="upload-header-subtitle">Update your culinary masterpiece</p>
        <?php
        // Display success message if it exists
        if (isset($_SESSION['success'])) {
            echo '<p class="success-message">' . htmlspecialchars($_SESSION['success']) . '</p>';
            unset($_SESSION['success']);
        }

        // Display any general errors from backend validation
        if (isset($errors['general'])) {
            echo '<p class="error-message">' . htmlspecialchars($errors['general']) . '</p>';
        }
        ?>

        <div class="section-taps">
            <button id="tap-header-basic-info" class="tap-header" onclick="openTap('tap-basic-info','tap-header-basic-info')">Basic Info</button>
            <button id="tap-header-ingredients" class="tap-header" onclick="openTap('tap-ingredients', 'tap-header-ingredients')">Ingredients</button>
            <button id="tap-header-instructions" class="tap-header" onclick="openTap('tap-instructions', 'tap-header-instructions')">Instructions</button>
            <button id="tap-header-media-and-publish" class="tap-header" onclick="openTap('tap-media-and-publish', 'tap-header-media-and-publish')">Media & Update</button>
        </div>

        <form method="POST" action="be-logic/edit_recipe.php" enctype="multipart/form-data" class="upload-form">
            <input type="hidden" name="recipe_id" value="<?php echo $recipe_id; ?>">

            <div id="tap-basic-info" class="tap">
                <div class="upload-tap-body">
                    <div>
                        <h3>Basic Information</h3>
                        <p>Update the basic details of your recipe</p>
                    </div>

                    <div class="input-group">
                        <label for="recipe-title">Recipe Title</label>
                        <div>
                            <input type="text" id="recipe-title" name="recipe-title" placeholder="e.g., Spaghetti Bolognese" value="<?php echo getFormValue('recipe-title'); ?>">
                            <p class="error-message" id="recipe-title-errormsg">
                                <?php if (isset($errors['title'])) {
                                    echo htmlspecialchars($errors['title']);
                                } ?>
                            </p>
                        </div>
                    </div>

                    <div class="input-group">
                        <label for="recipe-description">Description</label>
                        <div>
                            <textarea id="recipe-description" name="recipe-description" placeholder="Briefly describe your recipe"><?php echo getFormValue('recipe-description'); ?></textarea>
                            <p class="error-message" id="recipe-description-errormsg">
                                <?php if (isset($errors['description'])) {
                                    echo htmlspecialchars($errors['description']);
                                } ?>
                            </p>
                        </div>
                    </div>

                    <div class="input-line" id="recipe-details-input-difficulty">
                        <div class="input-group">
                            <label for="recipe-prep-time">Preparation Time</label>
                            <div>
                                <div class="input-subgroup">
                                    <input type="number" id="recipe-prep-time" name="recipe-prep-time" min="0" value="<?php echo getFormValue('recipe-prep-time'); ?>">
                                    <select name="recipe-prep-time-format">
                                        <option value="minutes" <?php echo isSelected('recipe-prep-time-format', 'minutes'); ?>>minutes</option>
                                        <option value="hours" <?php echo isSelected('recipe-prep-time-format', 'hours'); ?>>hours</option>
                                    </select>
                                </div>
                                <p class="error-message" id="recipe-prep-time-errormsg">
                                    <?php if (isset($errors['prep_time'])) {
                                        echo htmlspecialchars($errors['prep_time']);
                                    } ?>
                                </p>
                            </div>
                        </div>

                        <div class="input-group">
                            <label for="recipe-cook-time">Cooking Time</label>
                            <div>
                                <div class="input-subgroup">
                                    <input type="number" id="recipe-cook-time" name="recipe-cook-time" min="0" value="<?php echo getFormValue('recipe-cook-time'); ?>">
                                    <select name="recipe-cook-time-format">
                                        <option value="minutes" <?php echo isSelected('recipe-cook-time-format', 'minutes'); ?>>minutes</option>
                                        <option value="hours" <?php echo isSelected('recipe-cook-time-format', 'hours'); ?>>hours</option>
                                    </select>
                                </div>
                                <p class="error-message" id="recipe-cook-time-errormsg">
                                    <?php if (isset($errors['cook_time'])) {
                                        echo htmlspecialchars($errors['cook_time']);
                                    } ?>
                                </p>
                            </div>
                        </div>

                        <div class="input-group">
                            <label for="recipe-difficulty">Difficulty Level</label>
                            <div>
                                <select id="recipe-difficulty" name="recipe-difficulty">
                                    <option value="">Select difficulty</option>
                                    <option value="1" <?php echo isSelected('recipe-difficulty', '1'); ?>>Easy</option>
                                    <option value="2" <?php echo isSelected('recipe-difficulty', '2'); ?>>Medium</option>
                                    <option value="3" <?php echo isSelected('recipe-difficulty', '3'); ?>>Hard</option>
                                </select>
                                <p class="error-message" id="recipe-difficulty-errormsg">
                                    <?php if (isset($errors['difficulty'])) {
                                        echo htmlspecialchars($errors['difficulty']);
                                    } ?>
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="input-line">
                        <div class="input-group">
                            <label for="recipe-servings">Servings</label>
                            <div>
                                <input type="number" id="recipe-servings" name="recipe-servings" min="1" value="<?php echo getFormValue('recipe-servings'); ?>">
                                <p class="error-message" id="recipe-servings-errormsg">
                                    <?php if (isset($errors['servings'])) {
                                        echo htmlspecialchars($errors['servings']);
                                    } ?>
                                </p>
                            </div>
                        </div>

                        <div class="input-group">
                            <label for="recipe-category">Category</label>
                            <div>
                                <select id="recipe-category" name="recipe-category">
                                    <option value="">Select category</option>
                                    <option value="breakfast" <?php echo isSelected('recipe-category', 'breakfast'); ?>>Breakfast</option>
                                    <option value="appetizer" <?php echo isSelected('recipe-category', 'appetizer'); ?>>Appetizer</option>
                                    <option value="salad" <?php echo isSelected('recipe-category', 'salad'); ?>>Salad</option>
                                    <option value="soup" <?php echo isSelected('recipe-category', 'soup'); ?>>Soup</option>
                                    <option value="sandwich" <?php echo isSelected('recipe-category', 'sandwich'); ?>>Sandwich</option>
                                    <option value="main" <?php echo isSelected('recipe-category', 'main'); ?>>Main Course</option>
                                    <option value="side" <?php echo isSelected('recipe-category', 'side'); ?>>Side Dish</option>
                                    <option value="snack" <?php echo isSelected('recipe-category', 'snack'); ?>>Snack</option>
                                    <option value="dessert" <?php echo isSelected('recipe-category', 'dessert'); ?>>Dessert</option>
                                    <option value="baking" <?php echo isSelected('recipe-category', 'baking'); ?>>Baking</option>
                                    <option value="sauce" <?php echo isSelected('recipe-category', 'sauce'); ?>>Sauce</option>
                                    <option value="drink" <?php echo isSelected('recipe-category', 'drink'); ?>>Drink</option>
                                </select>
                                <p class="error-message" id="recipe-category-errormsg">
                                    <?php if (isset($errors['category'])) {
                                        echo htmlspecialchars($errors['category']);
                                    } ?>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="navigation-buttons">
                    <button class="primary" type="button" onclick="openTap('tap-ingredients', 'tap-header-ingredients')">Next: Ingredients</button>
                </div>
            </div>

            <div id="tap-ingredients" class="tap">
                <div class="upload-tap-body">
                    <div>
                        <h3>Ingredients</h3>
                        <p>Update all ingredients needed for your recipe</p>
                        <?php if (isset($errors['ingredients'])): ?>
                            <p class="error-message" id="ingredients-errormsg">
                                <?php echo htmlspecialchars($errors['ingredients']); ?>
                            </p>
                        <?php endif; ?>
                    </div>

                    <div class="input-line upload-input-input">
                        <div class="input-subgroup">
                            <input type="number" id="ingredient-amount" name="ingredient-amount" min="0">
                            <select id="ingredient-unit" name="ingredient-unit">
                                <option value="g">g</option>
                                <option value="kg">kg</option>
                                <option value="ml">ml</option>
                                <option value="l">l</option>
                                <option value="cup">cup</option>
                                <option value="tbsp">tbsp</option>
                                <option value="tsp">tsp</option>
                                <option value="oz">oz</option>
                                <option value="lb">lb</option>
                            </select>
                        </div>
                        <div id="ingredient-name-input">
                            <input type="text" id="ingredient-name" name="ingredient-name" placeholder="e.g., Spaghetti">
                            <span id="ingredient-name-errormsg" class="error-message"></span>
                        </div>
                        <button type="button" class="icon-button" id="add-ingredient-btn">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-plus h-4 w-4 mr-2">
                                <path d="M5 12h14"></path>
                                <path d="M12 5v14"></path>
                            </svg>
                            Add
                        </button>
                    </div>

                    <div id="ingredient-list">
                        <!-- List of added ingredients will be displayed here -->
                    </div>

                    <div id="ingredient-list-empty">
                        <p>No ingredients added yet</p>
                    </div>

                    <div class="tips">
                        <h4>Tips for adding ingredients:</h4>
                        <ul>
                            <li>Add one ingredient per line</li>
                            <li>Specify preparation if needed (e.g., chopped, minced)</li>
                            <li>List ingredients in the order they will be used</li>
                        </ul>
                    </div>
                </div>
                <div class="navigation-buttons">
                    <button class="secondary-button" type="button" onclick="openTap('tap-basic-info','tap-header-basic-info')">Back: Basic Info</button>
                    <button type="button" onclick="openTap('tap-instructions', 'tap-header-instructions')">Next: Instructions</button>
                </div>
            </div>

            <div id="tap-instructions" class="tap">
                <div class="upload-tap-body">
                    <div>
                        <h3>Instructions</h3>
                        <p>Update step-by-step instructions for your recipe</p>
                        <?php if (isset($errors['instructions'])): ?>
                            <p class="error-message" id="instructions-errormsg">
                                <?php echo htmlspecialchars($errors['instructions']); ?>
                            </p>
                        <?php endif; ?>
                    </div>

                    <div class="input-line upload-input-input" id="instruction-step-input">
                        <div>
                            <textarea id="instruction-step" name="instruction-step" placeholder="Describe this step..."></textarea>
                        </div>
                        <button type="button" class="icon-button" id="add-instruction-btn">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-plus h-4 w-4 mr-2">
                                <path d="M5 12h14"></path>
                                <path d="M12 5v14"></path>
                            </svg>
                            Add Step
                        </button>
                    </div>

                    <div id="instruction-list">
                        <!-- List of added instructions will be displayed here -->
                    </div>

                    <div id="instruction-list-empty">
                        <p>No instructions added yet</p>
                    </div>

                    <div class="tips">
                        <h4>Tips for writing instructions:</h4>
                        <ul>
                            <li>Add one step per line</li>
                            <li>Be clear and concise</li>
                            <li>Include cooking times and temperatures if applicable</li>
                            <li>Mention visual cues (e.g., "until golden brown")</li>
                        </ul>
                    </div>
                </div>
                <div class="navigation-buttons">
                    <button class="secondary-button" type="button" onclick="openTap('tap-ingredients', 'tap-header-ingredients')">Back: Ingredients</button>
                    <button type="button" onclick="openTap('tap-media-and-publish', 'tap-header-media-and-publish')">Next: Media & Update</button>
                </div>
            </div>

            <div id="tap-media-and-publish" class="tap">
                <div class="upload-tap-body">
                    <div>
                        <h3>Media Upload</h3>
                        <p>Update the image of your finished dish</p>
                    </div>

                    <?php if (!empty($recipe['image_path'])): ?>
                        <div class="current-image">
                            <h4>Current Image:</h4>
                            <img src="<?php echo htmlspecialchars($recipe['image_path']); ?>" alt="Current recipe image" style="max-width: 200px; height: auto; border-radius: 8px;">
                        </div>
                    <?php endif; ?>

                    <div class="input-group">
                        <label for="recipe-image">Upload New Image (optional)</label>
                        <div>
                            <div id="image-upload-container" class="image-upload-container">
                                <input type="file" id="recipe-image" name="recipe-image" accept="image/*" style="display: none;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-6 w-6">
                                    <rect width="18" height="18" x="3" y="3" rx="2" ry="2"></rect>
                                    <circle cx="9" cy="9" r="2"></circle>
                                    <path d="m21 15-3.086-3.086a2 2 0 0 0-2.828 0L6 21"></path>
                                </svg>
                                <p id="upload-text">Drag & drop your image here</p>
                                <span class="upload-info">PNG, JPG or WEBP up to 10MB</span>
                                <button id="choose-file-btn" class="choose-file-btn" type="button">Choose File</button>
                            </div>
                            <p class="error-message" id="recipe-image-errormsg">
                                <?php if (isset($errors['image'])) {
                                    echo htmlspecialchars($errors['image']);
                                } ?>
                            </p>
                        </div>
                        <div id="image-preview-container" class="image-preview-container" style="display: none;">
                            <img id="preview-image" src="#" alt="Preview">
                            <button type="button" id="remove-image-btn" class="remove-image-btn">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M18 6 6 18"></path>
                                    <path d="m6 6 12 12"></path>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <div class="tips">
                        <h4>Before updating:</h4>
                        <ul>
                            <li>Double-check all ingredients and measurements</li>
                            <li>Ensure instructions are clear and complete</li>
                            <li>Add a high-quality photo if possible</li>
                            <li>Include any special tips or variations</li>
                        </ul>
                    </div>
                </div>
                <div class="navigation-buttons">
                    <button type="button" onclick="openTap('tap-instructions', 'tap-header-instructions')" class="secondary-button">Back: Instructions</button>
                    <div class="button-group">
                        <button type="button" class="secondary-button warning-button" onclick="confirmDeleteRecipe(<?php echo $recipe_id; ?>)">Delete Recipe</button>
                        <button type="submit" class="primary">Update Recipe</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</main>

<script>
    // Load existing ingredients and instructions when page loads
    document.addEventListener('DOMContentLoaded', function() {
        // Load existing ingredients
        <?php if (!empty($ingredients)): ?>
            const existingIngredients = <?php echo json_encode($ingredients); ?>;
            existingIngredients.forEach(function(ingredient) {
                // Set the form fields
                document.getElementById('ingredient-amount').value = ingredient.amount || '';
                document.getElementById('ingredient-unit').value = ingredient.unit || 'g';
                document.getElementById('ingredient-name').value = ingredient.ingredient;

                // Trigger the add ingredient function
                if (typeof addIngredient === 'function') {
                    addIngredient();
                }
            });

            // Clear the input fields after loading
            document.getElementById('ingredient-amount').value = '';
            document.getElementById('ingredient-name').value = '';
        <?php endif; ?>

        // Load existing instructions
        <?php if (!empty($instructions)): ?>
            const existingInstructions = <?php echo json_encode($instructions); ?>;
            existingInstructions.forEach(function(instruction) {
                // Set the instruction field
                document.getElementById('instruction-step').value = instruction.instruction;

                // Trigger the add instruction function
                if (typeof addInstruction === 'function') {
                    addInstruction();
                }
            });

            // Clear the input field after loading
            document.getElementById('instruction-step').value = '';
        <?php endif; ?>

        // Restore form data if validation failed
        <?php if (!empty($formData)): ?>
            // Restore ingredients from form data
            <?php if (isset($formData['ingredient-names']) && is_array($formData['ingredient-names'])): ?>
                const ingredientAmounts = <?php echo json_encode($formData['ingredient-amounts'] ?? []); ?>;
                const ingredientUnits = <?php echo json_encode($formData['ingredient-units'] ?? []); ?>;
                const ingredientNames = <?php echo json_encode($formData['ingredient-names']); ?>;

                // Clear existing ingredients first
                if (typeof clearIngredients === 'function') {
                    clearIngredients();
                }

                // Add each ingredient to the list
                for (let i = 0; i < ingredientNames.length; i++) {
                    if (ingredientNames[i]) {
                        document.getElementById('ingredient-amount').value = ingredientAmounts[i] || '';
                        document.getElementById('ingredient-unit').value = ingredientUnits[i] || 'g';
                        document.getElementById('ingredient-name').value = ingredientNames[i];

                        if (typeof addIngredient === 'function') {
                            addIngredient();
                        }
                    }
                }

                document.getElementById('ingredient-amount').value = '';
                document.getElementById('ingredient-name').value = '';
            <?php endif; ?>

            // Restore instructions from form data
            <?php if (isset($formData['instruction-steps']) && is_array($formData['instruction-steps'])): ?>
                const instructionSteps = <?php echo json_encode($formData['instruction-steps']); ?>;

                // Clear existing instructions first
                if (typeof clearInstructions === 'function') {
                    clearInstructions();
                }

                // Add each instruction to the list
                for (let i = 0; i < instructionSteps.length; i++) {
                    if (instructionSteps[i]) {
                        document.getElementById('instruction-step').value = instructionSteps[i];

                        if (typeof addInstruction === 'function') {
                            addInstruction();
                        }
                    }
                }

                document.getElementById('instruction-step').value = '';
            <?php endif; ?>
        <?php endif; ?>
    });

    // Function to confirm recipe deletion
    function confirmDeleteRecipe(recipeId) {
        if (confirm('Are you sure you want to delete this recipe? This action cannot be undone.')) {
            // Create a form to submit the delete request
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = 'be-logic/delete_recipe.php';

            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'recipe_id';
            input.value = recipeId;

            form.appendChild(input);
            document.body.appendChild(form);
            form.submit();
        }
    }
</script>

<?php
include_once 'assets/includes/footer.php';
?>
