<?php
require_once 'be-logic\protected_page.php';
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <!-- meta tags -->
    <meta charset="UTF-8">
    <meta name="description" content="Recipe Cloud - Your go-to place for delicious recipes.">
    <meta name="keywords" content="recipes, cooking, food, share, discover">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="author" content="Edamame04">
    <meta name="theme-color" content="#ffffff">
    <meta name="application-name" content="Recipe Cloud">


    <!-- favicon and title -->
    <link rel="icon" href="assets/img/logo_with_bg.svg" type="image/svg+xml">
    <title>Recipe Cloud</title>

    <!-- load stylesheets -->
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/upload.css">

    <!-- load fontend view logic -->
    <script src="assets/fe-logic/view.js" defer></script>
    <script src="assets/fe-logic/upload.js" defer></script>

    <!-- load Inter font from Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap" rel="stylesheet">
</head>

<body>
    <header>
        <div>
            <div class="logo-container" onclick="location.href='index.php'">
                <img src="assets/img/logo.svg" alt="Recipe Cloud Logo" class="logo" width="24" height="24">
                <h1>Recipe Cloud</h1>
            </div>
            <div class="nav-links desktop-only">
                <a href="index.php">Home</a>
                <a href="recipes.php">Recipes</a>
                <a href="upload.php">Upload</a>
                <?php
                if (isset($_SESSION["username"])): ?>
                    <a href="profile.php?u=<?php echo $_SESSION['username']; ?>">My Profile</a>
                <?php endif; ?>
            </div>
            <?php if (isset($_SESSION["username"])): ?>
                <form class=" desktop-only" method="POST" action="be-logic/auth.php">
                    <input type="hidden" name="action" value="logout">
                    <button class="secondary-button icon-button" type="submit">
                        Sign Out
                        <svg xmlns="http://www.w3.org/2000/svg" id="Layer_1" data-name="Layer 1" viewBox="0 0 24 24">
                            <path d="m24,12s0,.002,0,.003c-.002.673-.266,1.304-.746,1.776l-4.142,4.077c-.097.096-.224.144-.351.144-.129,0-.258-.05-.356-.149-.193-.196-.191-.513.006-.707l4.142-4.077c.164-.162.281-.356.356-.566H6.5c-.276,0-.5-.224-.5-.5s.224-.5.5-.5h16.41c-.075-.213-.192-.409-.358-.572l-4.141-4.072c-.197-.193-.2-.51-.006-.707.193-.198.51-.2.707-.006l4.141,4.072c.481.474.747,1.106.747,1.782,0,0,0,.001,0,.002,0,0,0,0,0,0Zm-12.5,3c-.276,0-.5.224-.5.5v4c0,1.93-1.57,3.5-3.5,3.5h-3c-1.93,0-3.5-1.57-3.5-3.5V4.5c0-1.93,1.57-3.5,3.5-3.5h3c1.93,0,3.5,1.57,3.5,3.5v4c0,.276.224.5.5.5s.5-.224.5-.5v-4c0-2.481-2.019-4.5-4.5-4.5h-3C2.019,0,0,2.019,0,4.5v15c0,2.481,2.019,4.5,4.5,4.5h3c2.481,0,4.5-2.019,4.5-4.5v-4c0-.276-.224-.5-.5-.5Z" />
                        </svg>
                    </button>
                </form>
            <?php else: ?>
                <div class="auth-buttons  desktop-only">
                    <button class="gost-button icon-button" onclick="location.href='recipes.php'">
                        <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-search h-5 w-5">
                            <circle cx="11" cy="11" r="8"></circle>
                            <path d="m21 21-4.3-4.3"></path>
                        </svg>
                    </button>
                    <button class="secondary-button icon-button" onclick="location.href='login.php'">
                        <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-user h-4 w-4 mr-2">
                            <path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"></path>
                            <circle cx="12" cy="7" r="4"></circle>
                        </svg>
                        Sign In
                    </button>
                    <button onclick="location.href='register.php'">Register</button>
                </div>
            <?php endif; ?>
            <!-- Mobile Hamburger Menu Button -->
            <div class="mobile-only" style="width: fit-content">
                <button id="hamburger-icon" class="icon-button gost-button" onclick="toggleMobileMenu()">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-menu h-6 w-6">
                        <path d="M3 12h18"></path>
                        <path d="M3 6h18"></path>
                        <path d="M3 18h18"></path>
                    </svg>
                </button>
            </div>
        </div>
    </header>
    <!-- Mobile Navigation Menu -->
    <div class="mobile-nav mobile-only">
        <a href="index.php">Home</a>
        <a href="recipes.php">Recipes</a>
        <a href="upload.php">Upload</a>
        <?php if (isset($_SESSION["username"])): ?>
            <a href="profile.php?u=<?php echo $_SESSION['username']; ?>">My Profile</a>
        <?php endif; ?>
        <?php if (isset($_SESSION["username"])): ?>
            <form method="POST" action="be-logic/auth.php">
                <input type="hidden" name="action" value="logout">
                <button class="secondary-button icon-button" type="submit">
                    Sign Out
                    <svg xmlns="http://www.w3.org/2000/svg" id="Layer_1" data-name="Layer 1" viewBox="0 0 24 24">
                        <path d="m24,12s0,.002,0,.003c-.002.673-.266,1.304-.746,1.776l-4.142,4.077c-.097.096-.224.144-.351.144-.129,0-.258-.05-.356-.149-.193-.196-.191-.513.006-.707l4.142-4.077c.164-.162.281-.356.356-.566H6.5c-.276,0-.5-.224-.5-.5s.224-.5.5-.5h16.41c-.075-.213-.192-.409-.358-.572l-4.141-4.072c-.197-.193-.2-.51-.006-.707.193-.198.51-.2.707-.006l4.141,4.072c.481.474.747,1.106.747,1.782,0,0,0,.001,0,.002,0,0,0,0,0,0Zm-12.5,3c-.276,0-.5.224-.5.5v4c0,1.93-1.57,3.5-3.5,3.5h-3c-1.93,0-3.5-1.57-3.5-3.5V4.5c0-1.93,1.57-3.5,3.5-3.5h3c1.93,0,3.5,1.57,3.5,3.5v4c0,.276.224.5.5.5s.5-.224.5-.5v-4c0-2.481-2.019-4.5-4.5-4.5h-3C2.019,0,0,2.019,0,4.5v15c0,2.481,2.019,4.5,4.5,4.5h3c2.481,0,4.5-2.019,4.5-4.5v-4c0-.276-.224-.5-.5-.5Z" />
                    </svg>
                </button>
            </form>
        <?php else: ?>
            <button class="secondary-button icon-button" onclick="location.href='login.php'">
                <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-user h-4 w-4 mr-2">
                    <path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"></path>
                    <circle cx="12" cy="7" r="4"></circle>
                </svg>
                Sign In
            </button>
            <button onclick="location.href='register.php'">Register</button>

        <?php endif; ?>
    </div>
    <div class="mobile-nav-background mobile-only" id="mobile-nav-background"></div>
    <main>
        <div class="upload-container">
            <div class="upload-header">
                <img src="assets/img/logo.svg" alt="Recipe Cloud Logo" width="24" height="24">
                <h1>Upload New Recipe</h1>
            </div>
            <p class="upload-header-subtitle">Share your culinary masterpiece with the world</p>

            <div class="section-taps">
                <button id="tap-header-basic-info" class="tap-header" onclick="openTap('tap-basic-info','tap-header-basic-info')">Basic Info</button>
                <button id="tap-header-ingredients" class="tap-header" onclick="openTap('tap-ingredients', 'tap-header-ingredients')">Ingredients</button>
                <button id="tap-header-instructions" class="tap-header" onclick="openTap('tap-instructions', 'tap-header-instructions')">Instructions</button>
                <button id="tap-header-media-and-publish" class="tap-header" onclick="openTap('tap-media-and-publish', 'tap-header-media-and-publish')">Media & Publish</button>
            </div>
            <form method="POST" action="be-logic/upload.php" enctype="multipart/form-data">
                <div id="tap-basic-info" class="tap">
                    <h3>Basic Information</h3>
                    <p>Let's start with the basic details of your recipe</p>
                    <div class="input-group">
                        <label for="recipe-title">Recipe Title</label>
                        <input type="text" id="recipe-title" name="recipe-title" placeholder="e.g., Spaghetti Bolognese">
                    </div>
                    <div class="input-group">
                        <label for="recipe-description">Description</label>
                        <textarea id="recipe-description" name="recipe-description" placeholder="e.g., A classic Italian pasta dish with a rich and savory sauce."></textarea>
                    </div>
                    <div class="input-line">
                        <div class="input-group">
                            <label for="recipe-prep-time">Preparation Time</label>
                            <input type="text" id="recipe-prep-time" name="recipe-prep-time" placeholder="e.g., 30 minutes">
                        </div>
                        <div class="input-group">
                            <label for="recipe-cook-time">Cooking Time</label>
                            <input type="text" id="recipe-cook-time" name="recipe-cook-time" placeholder="e.g., 1 hour">
                        </div>
                        <div class="input-group">
                            <label for="recipe-difficulty">Difficulty</label>
                            <select id="recipe-difficulty" name="recipe-difficulty">
                                <option value="easy">Easy</option>
                                <option value="medium">Medium</option>
                                <option value="hard">Hard</option>
                            </select>
                        </div>
                    </div>
                    <div>
                        <label for="recipe-servings">Servings</label>
                        <input type="number" id="recipe-servings" name="recipe-servings" placeholder="e.g., 4">
                        <label for="recipe-category">Category</label>
                        <select id="recipe-category" name="recipe-category">
                            <option value="breakfast">Breakfast</option>

                            <option value="appetizer">Appetizer</option>
                            <option value="salad">Salad and Veggies</option>
                            <option value="soup">Soup</option>
                            <option value="sandwich">Sandwich</option>

                            <option value="main">Main Course</option>
                            <option value="side">Side Dish</option>

                            <option value="snack">Snack and Dips</option>

                            <option value="dessert">Dessert</option>
                            <option value="baking">Baking</option>

                            <option value="sauce">Sauce</option>

                            <option value="drink">Drink</option>

                        </select>
                    </div>
                    <div class="navigation-buttons">
                        <button class="primary" type="button" onclick="openTap('tap-ingredients', 'tap-header-ingredients')">Next: Ingredients</button>
                    </div>
                </div>

                <div id="tap-ingredients" class="tap">
                    <h3>Ingredients</h3>
                    <p>List all ingredients needed for your recipe</p>
                    <div class="input-line">
                        <input type="text" id="ingredient-amount" name="ingredient-amount" placeholder="e.g., 200">
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
                        </select> <input type="text" id="ingredient-name" name="ingredient-name" placeholder="e.g., Spaghetti">
                        <button type="button"> Add </button>
                    </div>
                    <div id="ingredient-list">
                        <!-- List of added ingredients will be displayed here -->
                    </div>
                    <div class="tips">
                        <p>Tips for adding ingredients:</p>
                        <ul>
                            <li>Add one ingredient per line</li>
                            <li>Specify preparation if needed (e.g., chopped, minced)</li>
                            <li>List ingredients in the order they will be used</li>
                        </ul>
                    </div>
                    <div class="navigation-buttons">
                        <button class="secondary-button" type="button" onclick="openTap('tap-basic-info','tap-header-basic-info')">Back: Basic Info</button>
                        <button type="button" onclick="openTap('tap-instructions', 'tap-header-instructions')">Next: Instructions</button>
                    </div>
                </div>

                <div id="tap-instructions" class="tap">
                    <h3>Instructions</h3>
                    <p>Provide step-by-step instructions for your recipe</p>
                    <div class="input-line"> <textarea id="instruction-step" name="instruction-step" placeholder="e.g., Boil water in a large pot."></textarea>
                        <button type="button"> Add Step</button>
                    </div>
                    <div id="instruction-list">
                        <!-- List of added instructions will be displayed here -->
                    </div>
                    <div class="tips">
                        <p>Tips for writing instructions:</p>
                        <ul>
                            <li>Add one step per line</li>
                            <li>Be clear and concise</li>
                            <li>Include cooking times and temperatures if applicable</li>
                            <li>Mention visual cues (e.g., "until golden brown")</li>
                        </ul>
                    </div>
                    <div class="navigation-buttons">
                        <button class="secondary-button" type="button" onclick="openTap('tap-ingredients', 'tap-header-ingredients')">Back: Ingredients</button>
                        <button type="button" onclick="openTap('tap-media-and-publish', 'tap-header-media-and-publish')">Next: Media & Publish</button>
                    </div>
                </div>

                <div id="tap-media-and-publish" class="tap">
                    <h3>Media Upload</h3>
                    <p>Upload a image of your finished dish</p>
                    <div class="input-group">
                        <label for="recipe-image">Upload Image</label>
                        <input type="file" id="recipe-image" name="recipe-image" accept="image/*">
                    </div>
                    <div class="tips">
                        <p>Before publishing:</p>
                        <ul>
                            <li>Double-check all ingredients and measurements</li>
                            <li>Ensure instructions are clear and complete</li>
                            <li>Add a high-quality photo if possible</li>
                            <li>Include any special tips or variations</li>
                        </ul>

                    </div>
                    <div class="navigation-buttons">
                        <button type="button" onclick="openTap('tap-instructions', 'tap-header-instructions')" class="secondary-button">Back: Instructions</button>
                        <button type="submit">Publish Recipe</button>
                    </div>
                </div>
            </form>
        </div>
    </main>
    <footer>
        <div>
            <div class="footer-logo-container">
                <div class="footer-logo">
                    <img src="assets/img/logo.svg" alt="Recipe Cloud Logo" class="logo" width="24" height="24">
                    <h1>Recipe Cloud</h1>
                </div>
                <p>Share and discover delicious recipes from around the world.</p>
            </div>
            <div class="footer-links-container">
                <h2>Quick Links</h2>
                <div>
                    <a href="">Home</a>
                    <a href="recipes.php">Recipes</a>
                    <a href="upload.php">Upload</a>
                </div>
            </div>
            <div class="footer-links-container">
                <h2>Legal (add if site goes live)</h2>
                <div>
                    <a href="">Privacy Policy</a>
                    <a href="">Terms of Service</a>
                    <a href="">Cookie Preferences</a>
                    <a href="">Contact Us</a>
                </div>
            </div>
            <div class="footer-links-container">
                <h2>Contact</h2>
                <div>
                    <a href="https://github.com/Edamame04/recipe_cloud" target="_blank">GitHub</a>
                    <a href="https://github.com/Edamame04/recipe_cloud/issues" target="_blank">Report a Bug</a>
                </div>
            </div>
        </div>
    </footer>
</body>

</html>