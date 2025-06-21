<!-- index.php  
    * This is the home/index/landing page of the Recipe Cloud application.
    * It displays the hero section, featured recipes, and how-to steps.
    * It also includes buttons to upload a recipe or browse all recipes.
    TODO: Fetch featured recipes from the database and display them in the featured recipes section.
-->

<?php // Load the header
include_once 'assets/includes/header.php';
?>

<main>
    <div>
        <!-- Title and Welcome Message -->
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
        <!-- Featured Recipes Section -->
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
        <!-- Start Sharing Section -->
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
        <!-- How it works Section -->
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
<?php // Load the footer
include_once 'assets/includes/footer.php';
?>