<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <link rel="icon" href="src/img/logo_with_bg.svg" type="image/svg+xml">
    <meta name="description" content="Recipe Cloud - Your go-to place for delicious recipes.">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recipe Cloud</title>

    <!-- Load stylesheets -->
    <link rel="stylesheet" href="assets/css/style.css">

    <!-- Load Inter font from Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap" rel="stylesheet">

</head>

<body>
    <header>
        <div>
            <div class="logo-container">
                <img src="assets/img/logo.svg" alt="Recipe Cloud Logo" class="logo" width="24" height="24">
                <h1>Recipe Cloud</h1>
            </div>
            <div class="nav-links">
                <a href="">Home</a>
                <a href="recipes.php">Recipes</a>
                <a href="upload.php">Upload</a>
            </div>
            <div class="auth-buttons">
                <button class="gost-button icon-button">
                    <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-search h-5 w-5">
                        <circle cx="11" cy="11" r="8"></circle>
                        <path d="m21 21-4.3-4.3"></path>
                    </svg>
                </button>
                <button class="secondary-button icon-button">
                    <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-user h-4 w-4 mr-2">
                        <path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"></path>
                        <circle cx="12" cy="7" r="4"></circle>
                    </svg>
                    Sign In
                </button>
                <button>Register</button>
            </div>
        </div>
    </header>


</body>

</html>