<!--db.php
    * This file handles database connection and table initialization.
    Sections in this file:
    * 1. Database Connection
    * 2. Table Initialization
-->
<?php

// This calles the start of a connection to the database and initializes the necessary tables if they do not exist.
$pdo = initiateDatabaseConnection();



/*1. Database Connection */
function initiateDatabaseConnection(): PDO{
    // Database connection parameters
    // Adjust these parameters according to your database configuration if necessary.
    // For example, if you are using a different database server or credentials.
    $host = 'localhost';
    $db   = 'recipe_cloud'; //do not forget to create the database in your MySQL server before running this script.
    $user = 'root';
    $pass = '';
    $charset = 'utf8mb4';

    $dsn = "mysql:host=$host;dbname=$db;charset=$charset";
    $options = [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]; // Set the error mode to exception for better error handling
    // This allows to catch and handle database errors more gracefully.
    // This is useful for debugging and ensuring that the application can handle database errors without crashing.

    try {
        $pdo = new PDO($dsn, $user, $pass, $options);
        //The PDO has the benefit, to the method we learned in the course, that the connection is automatically closed when the script ends.
    } catch (\PDOException $e) {
        die("Datenbankverbindung fehlgeschlagen: " . $e->getMessage());
        // If the connection fails, an error message is displayed and the script is terminated.
    }

    initializeTables($pdo); // Initialize tables if they don't exist

    return $pdo; // Return the PDO instance for further use 
}


/*2. Table Initialization*/
// This function initializes the necessary tables in the database if they do not already exist.
// It creates the users, recipes, instructions, ingredients, ratings, and favorites tables with appropriate columns and constraints.
// See the db documentation for more details on the table structure and constraints.
function initializeTables($pdo): void{
    // Create users table if it doesn't exist
    $sql = "CREATE TABLE IF NOT EXISTS users (
        first_name VARCHAR(50) NOT NULL,
        last_name VARCHAR(50) NOT NULL,
        username VARCHAR(20) PRIMARY KEY,
        email VARCHAR(50) NOT NULL UNIQUE,
        password_hash VARCHAR(255) NOT NULL,
        bio TEXT,
        profile_image VARCHAR(255),
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )";
    $pdo->exec($sql);

    // Create recipes table if it doesn't exist
    $sql = "CREATE TABLE IF NOT EXISTS recipes (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id VARCHAR(20),
        title VARCHAR(100) NOT NULL,
        description TEXT,
        prep_time_min INT,
        cook_time_min INT,
        difficulty INT CHECK (difficulty >= 1 AND difficulty <= 3),
        servings INT CHECK (servings > 0),
        category INT CHECK (category = 'breakfast' OR category = 'appetizer' OR category = 'salad' OR category = 'soup' OR category = 'sandwich' OR category = 'main' OR category = 'side' OR category = 'snack' OR category = 'dessert' OR category = 'baking' OR category = 'sauce' OR category = 'drink'),
        image_path VARCHAR(255),
        FOREIGN KEY (user_id) REFERENCES users(username) ON DELETE SET NULL
    )";
    $pdo->exec($sql);

    // Create instructions table if it doesn't exist
    $sql = "CREATE TABLE IF NOT EXISTS instructions (
        id INT AUTO_INCREMENT PRIMARY KEY,
        instruction TEXT NOT NULL,
        recipe_id INT NOT NULL,
        FOREIGN KEY (recipe_id) REFERENCES recipes(id) ON DELETE CASCADE
    )";
    $pdo->exec($sql);

    // Create ingredients table if it doesn't exist
    $sql = "CREATE TABLE IF NOT EXISTS ingredients (
        id INT AUTO_INCREMENT PRIMARY KEY,
        amount INT,
        unit VARCHAR(20) CHECK (unit = 'g' OR unit = 'kg' OR unit = 'ml' OR unit = 'l' OR unit = 'cup' OR unit = 'tbsp' OR unit = 'tsp' OR unit = 'oz' OR unit = 'lb'),
        ingredient VARCHAR(100) NOT NULL,
        recipe_id INT NOT NULL,
        FOREIGN KEY (recipe_id) REFERENCES recipes(id) ON DELETE CASCADE
    )";
    $pdo->exec($sql);

    // Create ratings table if it doesn't exist
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

    // Create favorites table if it doesn't exist
    $sql = "CREATE TABLE IF NOT EXISTS favorites (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id VARCHAR(20) NOT NULL,
        recipe_id INT NOT NULL,
        FOREIGN KEY (user_id) REFERENCES users(username) ON DELETE CASCADE,
        FOREIGN KEY (recipe_id) REFERENCES recipes(id) ON DELETE CASCADE
    )";
    $pdo->exec($sql);
}