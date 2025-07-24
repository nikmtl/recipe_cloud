<?php
/* header.php 
    * This file contains the header section of the website.
    * It includes the logo, navigation links, and authentication buttons.
    * It also includes the mobile navigation menu for smaller screens.
    * It also handles the JS and CSS includes for different pages.
    * This is used across all pages of the site.
    * To use this: include this file at the start of your PHP document to display the header.
    * The logic to open and close the mobile menu is handled in the view.js file.
*/

// Start session once if not already active its needed to check if the user is logged in.
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <!-- meta tags -->
    <meta charset="UTF-8">
    <meta name="description" content="Recipe Cloud - Your go-to place for delicious recipes.">
    <meta name="keywords" content="recipes, cooking, food, share, discover">
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> <!-- Ensures the page is responsive on mobile devices -->
    <meta name="author" content="Edamame04">
    <meta name="theme-color" content="#ffffff">
    <meta name="application-name" content="Recipe Cloud">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <meta name="apple-mobile-web-app-title" content="Recipe Cloud">


    <!-- favicon and title -->
    <link rel="icon" type="image/x-icon" sizes="any" href="/assets/img/favicon.ico" data-base-href="/assets/img/favicon">
    <title>Recipe Cloud</title>

    <!-- load stylesheets -->
    <link rel="stylesheet" href="assets/css/styles.css">
    <link rel="stylesheet" href="assets/css/header.css">
    <link rel="stylesheet" href="assets/css/footer.css">
    <link rel="stylesheet" href="assets/css/button.css">
    <link rel="stylesheet" href="assets/css/form.css">
    <link rel="stylesheet" href="assets/css/toast.css">

    <!-- load frontend mobile header logic -->
    <script src="assets/fe-logic/mobile-header.js" defer></script>

    <!-- load Inter font from Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap" rel="stylesheet">

    <!-- load Toast notification system -->
    <?php include __DIR__ . '/response_toast.php'; ?>
    <?php displayToast(); ?>
    <script src="assets/fe-logic/toast.js" defer></script>

    <!-- Load page spesific stuff-->
    <?php
    $currentPage = basename($_SERVER['PHP_SELF']);
    switch ($currentPage) {
        case 'index.php':
            echo '<link rel="stylesheet" href="assets/css/index.css">';
            break;
        case 'upload.php':
        case 'edit_recipe.php':
            echo '
                <script src="assets/fe-logic/tap-view.js" defer></script>
                <link rel="stylesheet" href="assets/css/taps.css">
                <link rel="stylesheet" href="assets/css/upload.css">
                <link rel="stylesheet" href="assets/css/image-upload.css">
                <script src="assets/fe-logic/upload/upload.js" defer></script>
                <script src="assets/fe-logic/upload/upload-form-ingredients.js" defer></script>
                <script src="assets/fe-logic/upload/upload-form-instructions.js" defer></script>
                <script src="assets/fe-logic/upload/upload-form-validation.js" defer></script>
                <script src="assets/fe-logic/upload/upload-form-image-upload.js" defer></script>';
            break;
        case 'login.php':
        case 'register.php':
            echo '
                <link rel="stylesheet" href="assets/css/password_toggle.css">
                <script src="assets/fe-logic/password_toggle.js" defer></script>
                <link rel="stylesheet" href="assets/css/auth.css">
                <script src="assets/fe-logic/auth.js" defer></script>';
            break;
        case 'recipe.php':
            echo '
                <script src="assets/fe-logic/tap-view.js" defer></script>
                <link rel="stylesheet" href="assets/css/taps.css">
                <link rel="stylesheet" href="assets/css/recipe.css">
                <script src="assets/fe-logic/recipe-page.js" defer></script>';
            break;
        case 'recipes.php':
            echo '
                <link rel="stylesheet" href="assets/css/recipes.css">
                <script src="assets/fe-logic/load-more-recipes.js" defer></script>';
            break;
        case 'profile.php':
            echo '
                <script src="assets/fe-logic/tap-view.js" defer></script>
                <link rel="stylesheet" href="assets/css/taps.css">
                <link rel="stylesheet" href="assets/css/profile.css">';
            break;
        case 'settings.php':
            echo '
                <link rel="stylesheet" href="assets/css/password_toggle.css">
                <script src="assets/fe-logic/password_toggle.js" defer></script>
                <link rel="stylesheet" href="assets/css/settings.css">';
            break;
    }
    ?>
</head>

<body>
    <!-- Header Section -->
    <header>
        <div>
            <!-- logo -->
            <div class="logo-container" onclick="location.href='/'">
                <img src="assets/img/logo.svg" alt="Recipe Cloud Logo" class="logo" width="24" height="24">
                <h1>Recipe Cloud</h1>
            </div>
            <!-- Navigation Links -->
            <div class="nav-links desktop-only">
                <a href="/">Home</a>
                <a href="recipes">Recipes</a>
                <a href="upload">Upload</a>
                <?php if (isset($_SESSION["username"])): ?> <!-- If user is logged in, show profile link -->
                    <a href="profile">My Profile</a>
                <?php endif; ?>
            </div>
            <!-- Authentication Buttons (Desktop) -->
            <?php if (isset($_SESSION["username"])): ?> <!-- If user is logged in, show sign out button -->
                <form class=" desktop-only" method="POST" action="be-logic/formhandler/auth">
                    <input type="hidden" name="action" value="logout">
                    <button class="secondary-button icon-button" type="submit">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                            <polyline points="16 17 21 12 16 7"></polyline>
                            <line x1="21" x2="9" y1="12" y2="12"></line>
                        </svg>
                        Logout
                    </button>
                </form>
            <?php else: ?> <!-- If user is not logged in, show search and sign in buttons -->
                <div class="auth-buttons  desktop-only">
                    <button class="ghost-button icon-button" onclick="location.href='recipes'">
                        <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="11" cy="11" r="8"></circle>
                            <path d="m21 21-4.3-4.3"></path>
                        </svg>
                    </button>
                    <button class="secondary-button icon-button" onclick="location.href='login'">
                        <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"></path>
                            <circle cx="12" cy="7" r="4"></circle>
                        </svg>
                        Sign In
                    </button>
                    <button onclick="location.href='register'">Register</button>
                </div>
            <?php endif; ?>
            <!-- Mobile Hamburger Menu Button (for smaller screens) -->
            <div class="mobile-only" style="width: fit-content">
                <button id="hamburger-icon" class="icon-button ghost-button" onclick="toggleMobileMenu()">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M3 12h18"></path>
                        <path d="M3 6h18"></path>
                        <path d="M3 18h18"></path>
                    </svg>
                </button>
            </div>
        </div>
    </header>
    <!-- Mobile Navigation Menu -->
    <div class="mobile-nav">
        <a href="/">Home</a>
        <a href="recipes">Recipes</a>
        <a href="upload">Upload</a>
        <?php if (isset($_SESSION["username"])): ?> <!-- If user is logged in, show profile link -->
            <a href="profile?>">My Profile</a>
        <?php endif; ?>
        <?php if (isset($_SESSION["username"])): ?> <!-- If user is logged in, show sign out button -->
            <form method="POST" action="be-logic/formhandler/auth">
                <input type="hidden" name="action" value="logout">
                <button class="secondary-button icon-button" type="submit">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                        <polyline points="16 17 21 12 16 7"></polyline>
                        <line x1="21" x2="9" y1="12" y2="12"></line>
                    </svg>
                    Logout
                </button>
            </form>
        <?php else: ?> <!-- If user is not logged in, show search and sign in buttons -->
            <button class="secondary-button icon-button" onclick="location.href='login'">
                <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"></path>
                    <circle cx="12" cy="7" r="4"></circle>
                </svg>
                Sign In
            </button>
            <button onclick="location.href='register'">Register</button>
        <?php endif; ?>
    </div>
    <div class="mobile-nav-background" id="mobile-nav-background"></div> <!-- Background for mobile menu to dim the rest of the page -->