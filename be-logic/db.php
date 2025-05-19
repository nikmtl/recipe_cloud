<!--datenbankverbindung.php
 * Diese Datei stellt eine Verbindung zur MySQL-Datenbank her und startet eine PHP-Session.
 * Sie wird in anderen PHP-Dateien eingebunden, um die Datenbankverbindung und Session-Management zu ermöglichen.
-->
<?php


/* Datenbankverbindung */
function connectToDatabase(){
    $host = 'localhost';
    $db   = 'rezeptdatenbank';
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
    return $pdo;

}



/* Logout */
function logout(){
    session_start();
    session_unset(); // Alle Session-Variablen löschen
    session_destroy(); // Session beenden
    header("Location: login.php");
    exit;
}

/* Login */
function login($username, $password) {
    $pdo = connectToDatabase();
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username");
    $stmt->execute(['username' => $username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
        session_start();
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        header("Location: index.php");
        exit;
    } else {
        return "Invalid username or password.";
    }
}
?>