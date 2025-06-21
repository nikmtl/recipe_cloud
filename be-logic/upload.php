<?php
/* upload.php
    * This file handles the recipe upload form submission.
    * It validates all inputs, handles file uploads, and creates the recipe in the database.

    Processing includes:
    - Form validation for all recipe fields
    - Image file upload and validation
    - Recipe creation in database
    - Ingredients insertion
    - Instructions insertion
    - Error handling and user feedback
*/

require_once __DIR__ . '/db.php';
require_once __DIR__ . '/protected_page.php';


// Check if form was submitted via POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../upload.php');
    exit();
}

try {
    // Validate and process the recipe upload
    $result = processRecipeUpload($pdo);    
    if ($result['success']) {
        // Redirect to the newly created recipe page
        header('Location: ../recipe.php?id=' . $result['recipe_id']);
        exit();
    } else {
        // Store errors and form data in session and redirect back to upload form
        $_SESSION['errors'] = $result['errors'];
        $_SESSION['upload_form_data'] = $_POST; // Preserve form data
        header('Location: ../upload.php');
        exit();
    }
} catch (Exception $e) {    // Handle unexpected errors
    error_log("Recipe upload error: " . $e->getMessage());
    $_SESSION['errors'] = ["An unexpected error occurred. Please try again."];
    $_SESSION['upload_form_data'] = $_POST; // Preserve form data
    header('Location: ../upload.php');
    exit();
}

/**
 * Main function to process the recipe upload
 */
function processRecipeUpload($pdo): array{
    $errors = [];

    // Validate basic recipe information
    $basicInfo = validateBasicInfo($errors);

    // Validate ingredients
    $ingredients = validateIngredients($errors);

    // Validate instructions
    $instructions = validateInstructions($errors);

    // Handle image upload
    $imagePath = handleImageUpload($errors);

    // If validation failed, return errors
    if (!empty($errors)) {
        return ['success' => false, 'errors' => $errors];
    }

    // Start database transaction
    $pdo->beginTransaction();

    try {
        // Insert recipe into database
        $recipeId = insertRecipe($pdo, $basicInfo, $imagePath);

        // Insert ingredients
        insertIngredients($pdo, $recipeId, $ingredients);

        // Insert instructions
        insertInstructions($pdo, $recipeId, $instructions);

        // Commit transaction
        $pdo->commit();

        return ['success' => true, 'recipe_id' => $recipeId];
    } catch (Exception $e) {
        // Rollback transaction on error
        $pdo->rollback();
        error_log("Database error during recipe upload: " . $e->getMessage());
        throw $e;
    }
}

/**
 * Validate basic recipe information
 */
function validateBasicInfo(&$errors): array{
    $basicInfo = [];    // Recipe title validation
    $title = trim(htmlspecialchars($_POST['recipe-title'] ?? ''));
    if (empty($title)) {
        $errors['title'] = "Recipe title is required.";
    } elseif (strlen($title) > 100) {
        $errors['title'] = "Title must be less than 100 characters.";
    } else {
        $basicInfo['title'] = $title;
    }    // Recipe description validation
    $description = trim(htmlspecialchars($_POST['recipe-description'] ?? ''));
    if (strlen($description) > 1000) {
        $errors['description'] = "Description must be less than 1000 characters.";
    }
    $basicInfo['description'] = $description;
    // Preparation time validation
    $prepTime = $_POST['recipe-prep-time'] ?? '';
    $prepTimeFormat = $_POST['recipe-prep-time-format'] ?? 'minutes';
    if (!empty($prepTime)) {        if (!is_numeric($prepTime) || $prepTime < 0) {
            $errors['prep_time'] = "Preparation time must be a positive number.";
        } else {
            // Convert to minutes if hours
            $prepTimeMinutes = ($prepTimeFormat === 'hours') ? $prepTime * 60 : $prepTime;
            $basicInfo['prep_time_min'] = (int)$prepTimeMinutes;
        }
    } else {
        $basicInfo['prep_time_min'] = null;
    }

    // Cooking time validation
    $cookTime = $_POST['recipe-cook-time'] ?? '';
    $cookTimeFormat = $_POST['recipe-cook-time-format'] ?? 'minutes';
    if (!empty($cookTime)) {        if (!is_numeric($cookTime) || $cookTime < 0) {
            $errors['cook_time'] = "Cooking time must be a positive number.";
        } else {
            // Convert to minutes if hours
            $cookTimeMinutes = ($cookTimeFormat === 'hours') ? $cookTime * 60 : $cookTime;
            $basicInfo['cook_time_min'] = (int)$cookTimeMinutes;
        }
    } else {
        $basicInfo['cook_time_min'] = null;
    }    // Difficulty validation
    $difficulty = $_POST['recipe-difficulty'] ?? '';
    if (empty($difficulty)) {
        $errors['difficulty'] = "Difficulty level is required.";
    } elseif (!in_array($difficulty, ['1', '2', '3'])) {
        $errors['difficulty'] = "Invalid difficulty level.";
    } else {
        $basicInfo['difficulty'] = (int)$difficulty;
    }    // Servings validation
    $servings = $_POST['recipe-servings'] ?? '';
    if (empty($servings)) {
        $errors['servings'] = "Servings must be a positive number.";
    } elseif (!is_numeric($servings) || $servings < 1) {
        $errors['servings'] = "Servings must be a positive number.";
    } else {
        $basicInfo['servings'] = (int)$servings;
    }    // Category validation
    $category = $_POST['recipe-category'] ?? '';
    $validCategories = ['breakfast', 'appetizer', 'salad', 'soup', 'sandwich', 'main', 'side', 'snack', 'dessert', 'baking', 'sauce', 'drink'];
    if (empty($category)) {
        $errors['category'] = "Recipe category is required.";
    } elseif (!in_array($category, $validCategories)) {
        $errors['category'] = "Invalid recipe category.";
    } else {
        $basicInfo['category'] = $category;
    }

    return $basicInfo;
}

/**
 * Validate ingredients
 */
function validateIngredients(&$errors): array{
    $ingredients = [];

    $amounts = $_POST['ingredient-amounts'] ?? [];
    $units = $_POST['ingredient-units'] ?? [];
    $names = $_POST['ingredient-names'] ?? [];    // Check if ingredients exist
    if (empty($names)) {
        $errors['ingredients'] = "At least one ingredient is required.";
        return $ingredients;
    }

    // Validate each ingredient
    for ($i = 0; $i < count($names); $i++) {
        $name = trim(htmlspecialchars($names[$i] ?? ''));
        $amount = trim($amounts[$i] ?? '');
        $unit = trim($units[$i] ?? '');        if (empty($name)) {
            $errors['ingredients'] = "Ingredient name is required.";
            continue;
        }

        if (strlen($name) > 100) {
            $errors['ingredients'] = "Ingredient name must be less than 100 characters.";
            continue;
        }        // Validate amount if provided
        $hasValidAmount = false;
        $finalAmount = null;
        if (!empty($amount)) {
            if (!is_numeric($amount) || $amount < 0) {
                $errors['ingredients'] = "Amount must be a positive number.";
                continue;
            }
            $hasValidAmount = true;
            $finalAmount = (int)$amount;
        }

        // Validate unit - only if there's a valid amount
        $finalUnit = null;
        if (!empty($unit)) {
            $validUnits = ['g', 'kg', 'ml', 'l', 'cup', 'tbsp', 'tsp', 'oz', 'lb'];
            if (!in_array($unit, $validUnits)) {
                $errors['ingredients'] = "Invalid ingredient unit: $unit";
                continue;
            }
            // Only store unit if there's a valid amount
            if ($hasValidAmount) {
                $finalUnit = $unit;
            }
        }

        $ingredients[] = [
            'amount' => $finalAmount,
            'unit' => $finalUnit,
            'ingredient' => $name
        ];
    }

    return $ingredients;
}

/**
 * Validate instructions
 */
function validateInstructions(&$errors): array {
    $instructions = [];

    $steps = $_POST['instruction-steps'] ?? [];    // Check if instructions exist
    if (empty($steps)) {
        $errors['instructions'] = "At least one instruction step is required.";
        return $instructions;
    }

    // Validate each instruction
    foreach ($steps as $index => $step) {
        $step = trim(htmlspecialchars($step));
        if (empty($step)) {
            $errors['instructions'] = "Instruction step is required.";
            continue;
        }

        if (strlen($step) > 1000) {
            $errors['instructions'] = "Instruction step must be less than 1000 characters.";
            continue;
        }

        $instructions[] = [
            'step_number' => $index + 1,
            'instruction' => $step
        ];
    }

    return $instructions;
}

/**
 * Handle image upload
 */
function handleImageUpload(&$errors): ?string {
    // Check if file was uploaded
    if (!isset($_FILES['recipe-image']) || $_FILES['recipe-image']['error'] === UPLOAD_ERR_NO_FILE) {
        return null; // No image uploaded, which is okay
    }

    $file = $_FILES['recipe-image'];    // Check for upload errors
    if ($file['error'] !== UPLOAD_ERR_OK) {
        $errors['image'] = "Error uploading image file.";
        return null;
    }

    // Validate file size (10MB max)
    $maxSize = 10 * 1024 * 1024; // 10MB in bytes
    if ($file['size'] > $maxSize) {
        $errors['image'] = "Image file size must be less than 10MB.";
        return null;
    }

    // Validate file type
    $allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/webp'];
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mimeType = finfo_file($finfo, $file['tmp_name']);
    finfo_close($finfo);

    if (!in_array($mimeType, $allowedTypes)) {
        $errors['image'] = "Invalid image format. Only JPG, PNG and WEBP are allowed.";
        return null;
    }    // Create uploads directory if it doesn't exist
    $uploadDir = __DIR__ . '/../uploads/recipes/';
    if (!is_dir($uploadDir)) {
        if (!mkdir($uploadDir, 0755, true)) {
            $errors['image'] = "Failed to create upload directory.";
            return null;
        }
    }

    // Generate unique filename
    $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
    $filename = uniqid('recipe_', true) . '.' . $extension;
    $filepath = $uploadDir . $filename;

    // Move uploaded file
    if (!move_uploaded_file($file['tmp_name'], $filepath)) {
        $errors['image'] = "Failed to save uploaded image.";
        return null;
    }

    // Return relative path for database storage
    return 'uploads/recipes/' . $filename;
}

/**
 * Insert recipe into database
 */
function insertRecipe($pdo, $basicInfo, $imagePath): int
{
    $sql = "INSERT INTO recipes (user_id, title, description, prep_time_min, cook_time_min, difficulty, servings, category, image_path) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        $_SESSION['username'],
        $basicInfo['title'],
        $basicInfo['description'],
        $basicInfo['prep_time_min'],
        $basicInfo['cook_time_min'],
        $basicInfo['difficulty'],
        $basicInfo['servings'],
        $basicInfo['category'],
        $imagePath
    ]);

    return $pdo->lastInsertId();
}

/**
 * Insert ingredients into database
 */
function insertIngredients($pdo, $recipeId, $ingredients): void
{
    $sql = "INSERT INTO ingredients (amount, unit, ingredient, recipe_id) VALUES (?, ?, ?, ?)";
    $stmt = $pdo->prepare($sql);

    foreach ($ingredients as $ingredient) {
        $stmt->execute([
            $ingredient['amount'],
            $ingredient['unit'],
            $ingredient['ingredient'],
            $recipeId
        ]);
    }
}

/**
 * Insert instructions into database
 */
function insertInstructions($pdo, $recipeId, $instructions): void
{
    $sql = "INSERT INTO instructions (step_number, instruction, recipe_id) VALUES (?, ?, ?)";
    $stmt = $pdo->prepare($sql);

    foreach ($instructions as $instruction) {
        $stmt->execute([
            $instruction['step_number'],
            $instruction['instruction'],
            $recipeId
        ]);
    }
}
