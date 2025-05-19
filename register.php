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
    <link rel="stylesheet" href="assets/css/auth.css">

    <!-- load fontend view logic -->
    <script src="assets/fe-logic/view.js" defer></script>
    <script src="assets/fe-logic/auth.js" defer></script>

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
        <button  onclick="location.href='login.php'">Register</button>
    </div>
    <div class="mobile-nav-background mobile-only" id="mobile-nav-background"></div>
    <main>
        <div class="auth-container">
            <img src="assets/img/logo.svg" alt="Recipe Cloud Logo" class="logo" width="40" height="40">
            <h1>Create an account</h1>
            <p>Enter your information to create an account</p>
            <form id="register-form" method="POST" action="be-logic/auth.php">
                <input type="hidden" name="action" value="register">
                <div class="input-group">
                    <label for="register-username">Username</label>
                    <div>
                        <input type="text" id="register-username" name="register-username" placeholder="JohnDoe" autocomplete="username">
                        <!--required is checked by js to prevent the ugly default browser error message-->
                        <p class="error-message" id="register-username-errormsg"></p>
                    </div>
                </div>
                <div class="input-group">
                    <label for="register-email">Email</label>
                    <div>
                        <input type="text" id="register-email" name="register-email" placeholder="name@example.com" autocomplete="email">
                        <!--required and email formatting is checked by js to prevent the ugly default browser error message-->
                        <p class="error-message" id="register-email-errormsg"></p>
                    </div>
                </div>
                <div class="input-group">
                    <label for="register-password">Password</label>
                    <div>
                        <input type="password" id="register-password" name="register-password" autocomplete="new-password">
                        <!--required is checked by js to prevent the ugly default browser error message-->
                        <p class="error-message" id="register-password-errormsg"></p>
                    </div>
                </div>
                <div class="input-group">
                    <label for="register-password-confirm">Confirm Password</label>
                    <div id="register-password-confirm-container">
                        <input type="password" id="register-password-confirm" autocomplete="new-password">
                        <!--required is checked by js to prevent the ugly default browser error message-->
                        <p class="error-message" id="register-password-confirm-errormsg"></p>
                    </div>
                </div>
                <button type="submit">Create account</button>
            </form>
            <p class="auth-mode-switch">Already have an account? <a href="Login.php">Login</a></p>

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