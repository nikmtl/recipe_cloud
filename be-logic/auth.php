<?php
require_once __DIR__ . '/db.php';

// Initialize session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Process actions
if (isset($_POST['action']) && $_POST['action'] === 'register') {
    registerUser($pdo);
} elseif (isset($_POST['action']) && $_POST['action'] === 'login') {
    loginUser($pdo);
} elseif (isset($_POST['action']) && $_POST['action'] === 'logout') {
    logoutUser();
} else {
    echo "Invalid action.";
}

function registerUser($pdo) {
    $errors = [];
    
    // Get and sanitize input fields
    $username = trim(htmlspecialchars($_POST['register-username'] ?? ''));
    $password = $_POST['register-password'] ?? '';
    $email = trim(htmlspecialchars($_POST['register-email'] ?? ''));
    
    // Validate username
    if (empty($username)) {
        $errors['username'] = "Username is required.";
    } elseif (strlen($username) < 2 || strlen($username) > 20) {
        $errors['username'] = "Username must be between 3 and 20 characters.";
    }
    
    // Check if username already exists
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE username = ?");
    $stmt->execute([$username]);
    if ($stmt->fetchColumn() > 0) {
        $errors['username'] = "Username already exists.";
    }
    
    // Validate email
    if (empty($email)) {
        $errors['email'] = "Email is required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = "Email must be a valid email address.";
    } elseif (strlen($email) > 50) {
        $errors['email'] = "Email must be shorter than 50 characters.";
    }
    
    // Check if email already exists
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE email = ?");
    $stmt->execute([$email]);
    if ($stmt->fetchColumn() > 0) {
        $errors['email'] = "Email already exists.";
    }
    
    // Validate password
    if (empty($password)) {
        $errors['password'] = "Password is required.";
    } elseif (strlen($password) < 8) {
        $errors['password'] = "Password must be longer than 8 characters.";
    } elseif (strlen($password) > 32) {
        $errors['password'] = "Password must be shorter than 32 characters.";
    }
    
    // If there are errors, redirect back to register page with errors
    if (!empty($errors)) {
        $errorQuery = http_build_query(['errors' => $errors]);
        header('Location: ../register.php?' . $errorQuery);
        exit;
    }
    
    // If validation passes, hash password and insert user
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    
    // Insert user into the database
    $stmt = $pdo->prepare("INSERT INTO users (username, password_hash, email) VALUES (?, ?, ?)");
    
    try {
        if ($stmt->execute([$username, $hashedPassword, $email])) {
            // Set user ID in session (session already started at top)
            $_SESSION['username'] = $username;
            header('Location: ../profile.php?u=' . $_SESSION["username"]);
            exit;
        } 
    } catch (PDOException $e) {
        $errors['general'] = "Error registering user. Please try again.";
        $errorQuery = http_build_query(['errors' => $errors]);
        header('Location: ../register.php?' . $errorQuery);
        exit;
    }
}

function loginUser($pdo) {
    $errors = [];
    
    // Get and sanitize input fields
    $username = trim(htmlspecialchars($_POST['login-username'] ?? ''));
    $password = $_POST['login-password'] ?? '';
    
    // Validate username
    if (empty($username)) {
        $errors['username'] = "Username is required.";
    }
    
    // Validate password
    if (empty($password)) {
        $errors['password'] = "Password is required.";
    }
    
    // If there are errors, redirect back to login page with errors
    if (!empty($errors)) {
        $errorQuery = http_build_query(['errors' => $errors]);
        header('Location: ../login.php?' . $errorQuery);
        exit;
    }    // Fetch user from the database
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    if (!$user) {
        // Username not found
        $errors['username'] = "Username not found.";
        $errorQuery = http_build_query(['errors' => $errors]);
        header('Location: ../login.php?' . $errorQuery);
        exit;
    } elseif (!password_verify($password, $user['password_hash'])) {
        // Password incorrect
        $errors['password'] = "Incorrect password.";
        $errorQuery = http_build_query(['errors' => $errors]);
        header('Location: ../login.php?' . $errorQuery);
        exit;
    } else {
        // Set user ID in session (session already started at top)
        $_SESSION['username'] = $username;
        header('Location: ../profile.php?u=' . $_SESSION["username"]);
        exit;
    }
}

function logoutUser() {
    // Session already started at top
    session_destroy();
    header('Location: ../index.php');
    exit;
}


// TODO for the future: Add CSRF protection
// TODO for the future: Add XSS protection

?>

