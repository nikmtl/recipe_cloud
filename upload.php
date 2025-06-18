<!-- upload.php 
    * This file allows users to upload new recipes.
    * It includes a form with multiple steps for entering recipe details, ingredients, instructions, and media.
    * After a short fronted validation in upload.js, this form submits to the upload.php formhandler for processing.
    * Input validation is done in upload.js to prevent the ugly default browser error messages. (e.g.when using the required attribute) 
    * The image upload functionality is handled within image-upload.js to provide a better user experience.
-->

<?php // load header 
require_once 'be-logic\protected_page.php'; // Ensure the user is logged in before accessing this page
include_once 'assets/includes/header.php';
?>
<main>
    <div class="upload-container">
        <div class="upload-header">
            <img src="assets/img/logo.svg" alt="Recipe Cloud Logo" width="24" height="24">
            <h1>Upload New Recipe</h1>
        </div>        <p class="upload-header-subtitle">Share your culinary masterpiece with the world</p>

        <?php
        // Display success message
        if (isset($_SESSION['upload_success'])) {
            echo '<div class="alert alert-success">' . htmlspecialchars($_SESSION['upload_success']) . '</div>';
            unset($_SESSION['upload_success']);
        }
        
        // Display error messages
        if (isset($_SESSION['upload_errors'])) {
            echo '<div class="alert alert-error">';
            echo '<ul>';
            foreach ($_SESSION['upload_errors'] as $error) {
                echo '<li>' . htmlspecialchars($error) . '</li>';
            }
            echo '</ul>';
            echo '</div>';
            unset($_SESSION['upload_errors']);
        }
        ?>

        <div class="section-taps">
            <button id="tap-header-basic-info" class="tap-header" onclick="openTap('tap-basic-info','tap-header-basic-info')">Basic Info</button>
            <button id="tap-header-ingredients" class="tap-header" onclick="openTap('tap-ingredients', 'tap-header-ingredients')">Ingredients</button>
            <button id="tap-header-instructions" class="tap-header" onclick="openTap('tap-instructions', 'tap-header-instructions')">Instructions</button>
            <button id="tap-header-media-and-publish" class="tap-header" onclick="openTap('tap-media-and-publish', 'tap-header-media-and-publish')">Media & Publish</button>
        </div>
        <form method="POST" action="be-logic/upload.php" enctype="multipart/form-data" class="upload-form">
            <div id="tap-basic-info" class="tap">
                <div class="upload-tap-body">
                    <div>
                        <h3>Basic Information</h3>
                        <p>Let's start with the basic details of your recipe</p>
                    </div>
                    <div class="input-group">
                        <label for="recipe-title">Recipe Title</label>
                        <input type="text" id="recipe-title" name="recipe-title" placeholder="e.g., Spaghetti Bolognese">
                    </div>
                    <div class="input-group">
                        <label for="recipe-description">Description</label>
                        <textarea id="recipe-description" name="recipe-description" placeholder="Briefly describe your recipe"></textarea>
                    </div>
                    <div class="input-line" id="recipe-details-input-difficulty">
                        <div class="input-group">
                            <label for="recipe-prep-time">Preparation Time</label>
                            <div class="input-subgroup">
                                <input type="number" id="recipe-prep-time" name="recipe-prep-time" min="0">
                                <select name="recipe-prep-time-format">
                                    <option value="minutes">Minutes</option>
                                    <option value="hours">Hours</option>
                                </select>
                            </div>
                        </div>
                        <div class="input-group">
                            <label for="recipe-cook-time">Cooking Time</label>
                            <div class="input-subgroup">
                                <input type="number" id="recipe-cook-time" name="recipe-cook-time" min="0">
                                <select name="recipe-cook-time-format">
                                    <option value="minutes">Minutes</option>
                                    <option value="hours">Hours</option>
                                </select>
                            </div>
                        </div>
                        <div class="input-group">
                            <label for="recipe-difficulty">Difficulty Level</label>
                            <select id="recipe-difficulty" name="recipe-difficulty">
                                <option value="1">Easy</option>
                                <option value="2">Medium</option>
                                <option value="3">Hard</option>
                            </select>
                        </div>
                    </div>
                    <div class="input-line">
                        <div class="input-group">
                            <label for="recipe-servings">Servings</label>
                            <input type="number" id="recipe-servings" name="recipe-servings" min="1">
                        </div>
                        <div class="input-group">
                            <label for="recipe-category">Category</label>
                            <select id="recipe-category" name="recipe-category">
                                <option value="breakfast">Breakfast</option>

                                <option value="appetizer">Appetizer</option>
                                <option value="salad">Salad and Veggies</option>
                                <option value="soup">Soup</option>
                                <option value="sandwich">Sandwich</option>

                                <option value="main" selected>Main Course</option>
                                <option value="side">Side Dish</option>

                                <option value="snack">Snack and Dips</option>

                                <option value="dessert">Dessert</option>
                                <option value="baking">Baking</option>

                                <option value="sauce">Sauce</option>

                                <option value="drink">Drink</option>

                            </select>
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
                        <p>List all ingredients needed for your recipe</p>
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
                        <p>Provide step-by-step instructions for your recipe</p>
                    </div>
                    <div class="input-line upload-input-input" id="instruction-step-input">
                        <div>
                            <textarea id="instruction-step" name="instruction-step" placeholder="Describe this step..."></textarea>
                        </div>
                        <button type="button" class="icon-button">
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
                    <button type="button" onclick="openTap('tap-media-and-publish', 'tap-header-media-and-publish')">Next: Media & Publish</button>
                </div>
            </div>
            <div id="tap-media-and-publish" class="tap">
                <div class="upload-tap-body">
                    <div>
                        <h3>Media Upload</h3>
                        <p>Upload a image of your finished dish</p>
                    </div>
                    <div class="input-group">
                        <label for="recipe-image">Upload Image</label>
                        <div id="image-upload-container" class="image-upload-container">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-6 w-6">
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
                    <button type="button" onclick="openTap('tap-instructions', 'tap-header-instructions')" class="secondary-button">Back: Instructions</button>
                    <button type="submit">Publish Recipe</button>
                </div>
            </div>
        </form>
    </div>
</main>
<?php // load footer
include_once 'assets/includes/footer.php';
?>