<?php
require_once __DIR__ . '/db.php';

// TODO: Add backend side input validation and sanitization
// TODO: Add backend side error handling
if (isset($_POST['action']) && $_POST['action'] === 'register') {
    registerUser($pdo);
} elseif (isset($_POST['action']) && $_POST['action'] === 'login') {
    loginUser($pdo);
} elseif (isset($_POST['action']) && $_POST['action'] === 'logout') {
    logoutUser();
}else {
    echo "Invalid action.";
}

function registerUser($pdo) {
    $username = $_POST['register-username'];
    $password = $_POST['register-password'];
    $email = $_POST['register-email'];

    // Hash the password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    //TODO: Check if username or email already exists
    // Insert user into the database
    $stmt = $pdo->prepare("INSERT INTO users (username, password_hash, email) VALUES (?, ?, ?)");
    if ($stmt->execute([$username, $hashedPassword, $email])) {
        // Start session and set user ID
        session_start();
        $_SESSION['username'] = $username;
        header('Location: ../profile.php?u=' . $_SESSION["username"]);
        exit;
    } else {
        echo "Error registering user.";
    }
}

function loginUser($pdo) {
    $username = $_POST['login-username'];
    $password = $_POST['login-password'];

    // Fetch user from the database
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password_hash'])) {
        // Start session and set user ID
        session_start();
        $_SESSION['username'] = $username;
        header('Location: ../profile.php?u=' . $_SESSION["username"]);
        //header('Location: ../profile.php?u=' . $_SESSION["username"]);
        exit;
    } else {
        echo "Invalid username or password.";
    }
}

function logoutUser() {
    session_start();
    session_destroy();
    header('Location: ../index.php');
    exit;
}


// TODO for the future: Add CSRF protection
// TODO for the future: Add XSS protection

?>

