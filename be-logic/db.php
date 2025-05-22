<!--datenbankverbindung.php
 * Diese Datei stellt eine Verbindung zur MySQL-Datenbank her.
    * Sie wird in anderen PHP-Dateien eingebunden, um auf die Datenbank zuzugreifen.
-->
<?php

$pdo = initiateDatabaseConnection();



function initiateDatabaseConnection()
{
    $host = 'localhost';
    $db   = 'recipe_cloud';
    $user = 'root';
    $pass = '';
    $charset = 'utf8mb4';

    $dsn = "mysql:host=$host;dbname=$db;charset=$charset";
    $options = [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION];

    try {
        $pdo = new PDO($dsn, $user, $pass, $options);
        //The PHO has the benefit that the connection is automatically closed when the script ends.
    } catch (\PDOException $e) {
        die("Datenbankverbindung fehlgeschlagen: " . $e->getMessage());
    }

    initializeTables($pdo); // Initialize tables if they don't exist

    return $pdo;
}

function initializeTables($pdo)
{
    // Create users table if it doesn't exist
    $sql = "CREATE TABLE IF NOT EXISTS users (
        username VARCHAR(20) PRIMARY KEY,
        email VARCHAR(50) NOT NULL UNIQUE,
        password_hash VARCHAR(255) NOT NULL
    )";
    $pdo->exec($sql);

    // Create recipes table
    $sql = "CREATE TABLE IF NOT EXISTS recipes (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id VARCHAR(20),
        title VARCHAR(100) NOT NULL,
        description TEXT,
        prep_time_min INT,
        cook_time_min INT,
        difficulty INT CHECK (difficulty >= 1 AND difficulty <= 3),
        servings INT CHECK (servings > 0),
        category INT CHECK CHECK (category = 'breakfast' OR category = 'appetizer' OR category = 'salad' OR category = 'soup' OR category = 'sandwich' OR category = 'main' OR category = 'side' OR category = 'snack' OR category = 'dessert' OR category = 'baking' OR category = 'sauce' OR category = 'drink'),
        image_path VARCHAR(255),
        FOREIGN KEY (user_id) REFERENCES users(username) ON DELETE SET NULL
    )";
    $pdo->exec($sql);

    // Create instructions table
    $sql = "CREATE TABLE IF NOT EXISTS instructions (
        id INT AUTO_INCREMENT PRIMARY KEY,
        instruction TEXT NOT NULL,
        recipe_id INT NOT NULL,
        FOREIGN KEY (recipe_id) REFERENCES recipes(id) ON DELETE CASCADE
    )";
    $pdo->exec($sql);

    // Create ingredients table
    $sql = "CREATE TABLE IF NOT EXISTS ingredients (
        id INT AUTO_INCREMENT PRIMARY KEY,
        amount INT,
        unit VARCHAR(20) CHECK (unit = 'g' OR unit = 'kg' OR unit = 'ml' OR unit = 'l' OR unit = 'cup' OR unit = 'tbsp' OR unit = 'tsp' OR unit = 'oz' OR unit = 'lb'),
        ingredient VARCHAR(100) NOT NULL,
        recipe_id INT NOT NULL,
        FOREIGN KEY (recipe_id) REFERENCES recipes(id) ON DELETE CASCADE
    )";
    $pdo->exec($sql);

    // Create ratings table
    $sql = "CREATE TABLE IF NOT EXISTS ratings (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id VARCHAR(20) NOT NULL,
        recipe_id INT NOT NULL,
        rating INT NOT NULL CHECK (rating >= 1 AND rating <= 5),
        comment_text TEXT,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (user_id) REFERENCES users(username) ON DELETE CASCADE,
        FOREIGN KEY (recipe_id) REFERENCES recipes(id) ON DELETE CASCADE
    )";
    $pdo->exec($sql);

    // Create favorites table
    $sql = "CREATE TABLE IF NOT EXISTS favorites (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id VARCHAR(20) NOT NULL,
        recipe_id INT NOT NULL,
        FOREIGN KEY (user_id) REFERENCES users(username) ON DELETE CASCADE,
        FOREIGN KEY (recipe_id) REFERENCES recipes(id) ON DELETE CASCADE
    )";
    $pdo->exec($sql);
}

/*
Issue	Status	Recommendation
SQL Injection	Safe	Use prepared statements
Password Hashing	Safe	Use password_hash()
Input Validation	Missing	Validate/sanitize all input
Duplicate Check	Missing	Check username/email before insert
CSRF Protection	Missing	Add CSRF tokens
XSS Protection	Missing	Escape output
Error Handling	Weak	Log errors, show generic messages
Session Security	Basic	Regenerate session ID, use user ID
*/