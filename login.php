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
                <?php session_start();
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
        <div class="auth-container">
            <img src="assets/img/logo.svg" alt="Recipe Cloud Logo" class="logo" width="40" height="40">
            <h1>Welcome Back</h1>
            <p>Enter your credentials to access your account</p>
            <form id="login-form" method="POST" action="be-logic/auth.php">
                <input type="hidden" name="action" value="login">
                <div class="input-group">
                    <label for="login-username">Username</label>
                    <div> 
                        <input type="text" id="login-username" name="login-username" autocomplete="username" placeholder="JohnDoe">
                        <!--required is checked by js to prevent the ugly default browser error message-->
                        <p class="error-message" id="login-username-errormsg"></p>
                    </div>
                </div>
                <div class="input-group">
                    <label for="login-password">Password</label>
                    <div>
                        <input type="password" id="login-password" name="login-password" autocomplete="current-password"> 
                        <!--required is checked by js to prevent the ugly default browser error message-->
                        <p class="error-message" id="login-password-errormsg"></p>
                    </div>
                </div>
                <button type="submit">Sign in</button>
            </form>
            <p class="auth-mode-switch">Don't have an account? <a href="register.php">Register</a></p>

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