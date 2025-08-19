<?php
/* upload.php 
    * This file allows users to upload new recipes.
    * It includes a form with multiple steps for entering recipe details, ingredients, instructions, and media.
    * After a short fronted validation in upload.js, this form submits to the upload.php formhandler for processing.
    * Input validation is done in upload.js to prevent the ugly default browser error messages. (e.g.when using the required attribute) 
    * The image upload functionality is handled within image-upload.js to provide a better user experience.
*/

// load header 
require_once 'be-logic/protected_page.php'; // Ensure the user is logged in before accessing this page
include_once 'assets/includes/header.php';

// Get errors from session and then clear them
$errors = $_SESSION['errors'] ?? [];
if (isset($_SESSION['errors'])) {
    unset($_SESSION['errors']);
}

// Get preserved form data if it exists (this is used to restore the form data after a failed submission)
$formData = $_SESSION['upload_form_data'] ?? [];

// Clear the preserved data after using it
unset($_SESSION['upload_form_data']);

// Helper function to get form value
function getFormValue($key, $default = ''): string{
    global $formData;
    return htmlspecialchars($formData[$key] ?? $default);
}

// Helper function to check if option is selected
function isSelected($key, $value): string{
    global $formData;
    return isset($formData[$key]) && $formData[$key] === $value ? 'selected' : '';
}
?>
<main>
    <div class="upload-container">
        <div class="upload-header">
            <img src="assets/img/logo.svg" alt="Recipe Cloud Logo" width="24" height="24">
            <h1>Upload New Recipe</h1>
        </div>
        <p class="upload-header-subtitle">Share your culinary masterpiece with the world</p>
        
        <?php
        // Display any general errors from backend validation
        if (isset($errors['general'])) {
            echo '<p class="error-message">' . htmlspecialchars($errors['general']) . '</p>';
        }
        ?>

        <div class="section-tabs">
            <button id="tab-header-basic-info" class="tab-header" onclick="openTab('tab-basic-info','tab-header-basic-info')">Basic Info</button>
            <button id="tab-header-ingredients" class="tab-header" onclick="openTab('tab-ingredients', 'tab-header-ingredients')">Ingredients</button>
            <button id="tab-header-instructions" class="tab-header" onclick="openTab('tab-instructions', 'tab-header-instructions')">Instructions</button>
            <button id="tab-header-media-and-publish" class="tab-header" onclick="openTab('tab-media-and-publish', 'tab-header-media-and-publish')">Media</button>
        </div>
        <form method="POST" action="be-logic/upload.php" enctype="multipart/form-data" class="upload-form">
            <div id="tab-basic-info" class="tab">
                <div class="upload-tab-body">
                    <div>
                        <h3>Basic Information</h3>
                        <p>Let's start with the basic details of your recipe</p>
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
                                        <option value="minutes" <?php echo isSelected('recipe-prep-time-format', 'minutes'); ?>>Minutes</option>
                                        <option value="hours" <?php echo isSelected('recipe-prep-time-format', 'hours'); ?>>Hours</option>
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
                                        <option value="minutes" <?php echo isSelected('recipe-cook-time-format', 'minutes'); ?>>Minutes</option>
                                        <option value="hours" <?php echo isSelected('recipe-cook-time-format', 'hours'); ?>>Hours</option>
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
                                    <option value="" <?php echo isSelected('recipe-difficulty', ''); ?>>Select difficulty</option>
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
                                    <option value="" <?php echo isSelected('recipe-category', ''); ?>>Select category</option>
                                    <option value="breakfast" <?php echo isSelected('recipe-category', 'breakfast'); ?>>Breakfast</option>
                                    <option value="appetizer" <?php echo isSelected('recipe-category', 'appetizer'); ?>>Appetizer</option>
                                    <option value="salad" <?php echo isSelected('recipe-category', 'salad'); ?>>Salad and Veggies</option>
                                    <option value="soup" <?php echo isSelected('recipe-category', 'soup'); ?>>Soup</option>
                                    <option value="sandwich" <?php echo isSelected('recipe-category', 'sandwich'); ?>>Sandwich</option>
                                    <option value="main" <?php echo isSelected('recipe-category', 'main') ?: (!empty($formData) ? '' : 'selected'); ?>>Main Course</option>
                                    <option value="side" <?php echo isSelected('recipe-category', 'side'); ?>>Side Dish</option>
                                    <option value="snack" <?php echo isSelected('recipe-category', 'snack'); ?>>Snack and Dips</option>
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
                    <button class="primary" type="button" onclick="openTab('tab-ingredients', 'tab-header-ingredients')">Next: Ingredients</button>
                </div>
            </div>

            <div id="tab-ingredients" class="tab">
                <div class="upload-tab-body">                    
                    <div>
                        <h3>Ingredients</h3>
                        <p>List all ingredients needed for your recipe</p>
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
                        <button type="button" class="icon-button">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
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
                    <button class="secondary-button" type="button" onclick="openTab('tab-basic-info','tab-header-basic-info')">Back: Basic Info</button>
                    <button type="button" onclick="openTab('tab-instructions', 'tab-header-instructions')">Next: Instructions</button>
                </div>
            </div>
            <div id="tab-instructions" class="tab">
                <div class="upload-tab-body">                    
                    <div>
                        <h3>Instructions</h3>
                        <p>Provide step-by-step instructions for your recipe</p>
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
                        <button type="button" class="icon-button">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
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
                    <button class="secondary-button" type="button" onclick="openTab('tab-ingredients', 'tab-header-ingredients')">Back: Ingredients</button>
                    <button type="button" onclick="openTab('tab-media-and-publish', 'tab-header-media-and-publish')">Next: Media & Publish</button>
                </div>
            </div>
            <div id="tab-media-and-publish" class="tab">
                <div class="upload-tab-body">
                    <div>
                        <h3>Media Upload</h3>
                        <p>Upload a image of your finished dish</p>
                    </div>                    <div class="input-group">
                        <label for="recipe-image">Upload Image</label>
                        <div>
                            <div id="image-upload-container" class="image-upload-container">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h7"></path>
                                    <line x1="16" x2="22" y1="5" y2="5"></line>
                                    <line x1="19" x2="19" y1="2" y2="8"></line>
                                    <circle cx="9" cy="9" r="2"></circle>
                                    <path d="m21 15-3.086-3.086a2 2 0 0 0-2.828 0L6 21"></path>
                                </svg>
                                <p id="upload-text">Drag & drop your image here</p>
                                <span class="upload-info">PNG, JPG or WEBP up to 10MB</span>
                                <button id="choose-file-btn" class="choose-file-btn">Choose File</button>
                                <input type="file" id="recipe-image" name="recipe-image" accept="image/*" class="hidden-file-input">
                            </div>
                            <p class="error-message" id="recipe-image-errormsg">
                                <?php if (isset($errors['image'])) {
                                    echo htmlspecialchars($errors['image']);
                                } ?>
                            </p>
                        </div>
                        <div id="image-preview-container" class="image-preview-container">
                            <img id="preview-image" src="#" alt="Preview">
                            <button type="button" id="remove-image-btn" class="remove-image-btn">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M18 6L6 18"></path>
                                    <path d="M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                    <div class="tips">
                        <h4>Before publishing:</h4>
                        <ul>
                            <li>Double-check all ingredients and measurements</li>
                            <li>Ensure instructions are clear and complete</li>
                            <li>Add a high-quality photo if possible</li>
                            <li>Include any special tips or variations</li>
                        </ul>
                    </div>
                </div>
                <div class="navigation-buttons">
                    <button type="button" onclick="openTab('tab-instructions', 'tab-header-instructions')" class="secondary-button">Back: Instructions</button>
                    <button type="submit">Publish Recipe</button>
                </div>
            </div>
        </form>
    </div>
</main>

<script>
    // Restore ingredients and instructions from preserved form data
    document.addEventListener('DOMContentLoaded', function() {
        <?php if (!empty($formData)): ?>
            // Restore ingredients
            <?php if (isset($formData['ingredient-names']) && is_array($formData['ingredient-names'])): ?>
                const ingredientAmounts = <?php echo json_encode($formData['ingredient-amounts'] ?? []); ?>;
                const ingredientUnits = <?php echo json_encode($formData['ingredient-units'] ?? []); ?>;
                const ingredientNames = <?php echo json_encode($formData['ingredient-names']); ?>;

                // Add each ingredient to the list
                for (let i = 0; i < ingredientNames.length; i++) {
                    if (ingredientNames[i]) {
                        // Set the form fields
                        document.getElementById('ingredient-amount').value = ingredientAmounts[i] || '';
                        document.getElementById('ingredient-unit').value = ingredientUnits[i] || 'g';
                        document.getElementById('ingredient-name').value = ingredientNames[i];

                        // Trigger the add ingredient function
                        if (typeof addIngredient === 'function') {
                            addIngredient();
                        }
                    }
                }

                // Clear the input fields after restoration
                document.getElementById('ingredient-amount').value = '';
                document.getElementById('ingredient-name').value = '';
            <?php endif; ?>

            // Restore instructions
            <?php if (isset($formData['instruction-steps']) && is_array($formData['instruction-steps'])): ?>
                const instructionSteps = <?php echo json_encode($formData['instruction-steps']); ?>;

                // Add each instruction to the list
                for (let i = 0; i < instructionSteps.length; i++) {
                    if (instructionSteps[i]) {
                        // Set the instruction field
                        document.getElementById('instruction-step').value = instructionSteps[i];

                        // Trigger the add instruction function
                        if (typeof addInstruction === 'function') {
                            addInstruction();
                        }
                    }
                }

                // Clear the input field after restoration
                document.getElementById('instruction-step').value = '';
            <?php endif; ?>
        <?php endif; ?>
    });
</script>

<?php // load footer
include_once 'assets/includes/footer.php';
?>
