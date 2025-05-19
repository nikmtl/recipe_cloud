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

    <!-- load Inter font from Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap" rel="stylesheet">


    <!-- load stylesheets -->
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/home.css">

    <!-- load fontend logic -->
    <script src="assets/fe-logic/view.js" defer></script>
</head>

<body>
    <header>
        <div>
            <div class="logo-container" onclick="location.href='index.php'">
                <img src="assets/img/logo.svg" alt="Recipe Cloud Logo" class="logo" width="24" height="24">
                <h1>Recipe Cloud</h1>
            </div>
            <div class="nav-links desktop-only">
                <a href="">Home</a>
                <a href="recipes.php">Recipes</a>
                <a href="upload.php">Upload</a>
                <?php if (isset($_SESSION["user_id"])): ?>
                    <a href="profile.php?user_id=<?php echo $_SESSION['user_id']; ?>">My Profile</a>
                <?php endif; ?>
            </div>
            <?php if (!isset($_SESSION["user_id"])): ?>
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
            <?php else: ?>
                <div class=" desktop-only">
                    <button class="secondary-button icon-button">
                        <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-user h-4 w-4 mr-2">
                            <path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"></path>
                            <circle cx="12" cy="7" r="4"></circle>
                        </svg>
                        <?php echo $_SESSION["username"]; ?>
                    </button>
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
        <a href="">Home</a>
        <a href="recipes.php">Recipes</a>
        <a href="upload.php">Upload</a>
        <?php if (isset($_SESSION["user_id"])): ?>
            <a href="profile.php?user_id=<?php echo $_SESSION['user_id']; ?>">My Profile</a>
        <?php endif; ?>
        <button class="secondary-button icon-button"  onclick="location.href='login.php'">
            <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-user h-4 w-4 mr-2">
                <path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"></path>
                <circle cx="12" cy="7" r="4"></circle>
            </svg>
            Sign In
        </button>
        <button onclick="location.href='register.php'">Register</button>
    </div>
    <div class="mobile-nav-background mobile-only" id="mobile-nav-background"></div>
    <main>
        <div>
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
            <div class="featured-recipes-container">
                <div class="featured-recipes-header">
                    <h2>Featured Recipes</h2>
                    <a href="recipes.php">View all</a>
                </div>
                <div class="featured-recipes">
                    <?php
                    //TODO Fetch featured recipes from the database
                    ?>
                </div>
            </div>
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
                <h2>Connect</h2>
                <div>
                    <a href="https://github.com/Edamame04/recipe_cloud">GitHub</a>
                    <a href="https://github.com/Edamame04/recipe_cloud/issues">Report a Bug</a>
                </div>
            </div>
        </div>
    </footer>
</body>

</html>