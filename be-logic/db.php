<!--datenbankverbindung.php
 * Diese Datei stellt eine Verbindung zur MySQL-Datenbank her.
    * Sie wird in anderen PHP-Dateien eingebunden, um auf die Datenbank zuzugreifen.
-->
<?php

$pdo = initiateDatabaseConnection();



function initiateDatabaseConnection(){
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

function initializeTables($pdo) {
    //TODO: add more checks for tables to prevent wrong data in the database (e.g. check if the difficulty is between 1 and 5)
    //TODO: change the category to be a part of the recipe table
    // Create users table if it doesn't exist
    $sql = "CREATE TABLE IF NOT EXISTS users (
        username VARCHAR(20) PRIMARY KEY,
        email VARCHAR(50) NOT NULL UNIQUE,
        password_hash VARCHAR(255) NOT NULL
    )";
    $pdo->exec($sql);

    // Create category table
    $sql = "CREATE TABLE IF NOT EXISTS category (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(50) NOT NULL
    )";
    $pdo->exec($sql);

    // Create recipes table
    $sql = "CREATE TABLE IF NOT EXISTS recipes (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id VARCHAR(20),
        title VARCHAR(100) NOT NULL,
        description TEXT,
        prep_time INT,
        cook_time INT,
        difficulty INT,
        servings INT,
        category_id INT,
        image_path VARCHAR(255),
        FOREIGN KEY (user_id) REFERENCES users(username) ON DELETE SET NULL,
        FOREIGN KEY (category_id) REFERENCES category(id) ON DELETE SET NULL
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
        amount VARCHAR(20),
        unit VARCHAR(20),
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

