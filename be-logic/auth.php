<!-- auth.php
    * This file handles user authentication logic including registration, login, and logout.
    * It uses prepared statements to prevent SQL injection and validates user input.
    * The Inputs get sanitized and validated before being processed in the backend too, to ensure security.
    * It also checks for existing users and handles errors appropriately.
    * It connects to the database and processes user input from forms.
    * It also manages session data for logged-in users.
    Sections in this file:
    * 1. User Registration
    * 2. User Login
    * 3. User Logout

    // TODO for the future: Add CSRF protection
    // TODO for the future: Add XSS protection
-->

<?php
require_once __DIR__ . '/db.php';

// Initialize session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Process actions based on the form submission and run the appropriate function
if (isset($_POST['action']) && $_POST['action'] === 'register') {
    registerUser($pdo);
} elseif (isset($_POST['action']) && $_POST['action'] === 'login') {
    loginUser($pdo);
} elseif (isset($_POST['action']) && $_POST['action'] === 'logout') {
    logoutUser();
} else {
    echo "Invalid action.";
}

/* 1. User Registration */
// Function to handle user registration
function registerUser($pdo): void {
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
        if ($stmt->execute([$username, $hashedPassword, $email])) { // If insert is successful
            $_SESSION['username'] = $username; // Set username in session
            header('Location: ../profile.php?u=' . $_SESSION["username"]); // Redirect to profile page
            exit;
        } 
    } catch (PDOException $e) { // Catch any database errors this should not happen, but just in case
        $errors['general'] = "Error registering user. Please try again.";
        $errorQuery = http_build_query(['errors' => $errors]);
        header('Location: ../register.php?' . $errorQuery); // Redirect back to register page with error
        exit;
    }
}


/* 2. User Login */
// Function to handle user login
function loginUser($pdo): never {
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
    }    
    
    // Fetch user from the database
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    // Check if user exists and verify password
    if (!$user) {
        // Username not found -> set error
        $errors['username'] = "Username not found.";
        $errorQuery = http_build_query(['errors' => $errors]);
        header('Location: ../login.php?' . $errorQuery);
        exit;
    } elseif (!password_verify($password, $user['password_hash'])) {
        // Password incorrect -> set error
        $errors['password'] = "Incorrect password.";
        $errorQuery = http_build_query(['errors' => $errors]);
        header('Location: ../login.php?' . $errorQuery);
        exit;
    } else { // Login successful
        // Clear any previous session data
        session_unset(); // Unset all session variables
        session_regenerate_id(true); // Regenerate session ID to prevent session fixation attacks
        // This ensures that the session is secure and prevents session hijacking.
        // This is important for security, especially after a successful login.
        // Set user ID in session (session already started at top)
        $_SESSION['username'] = $username; // Set username in session
        // Redirect to profile page
        header('Location: ../profile.php?u=' . $_SESSION["username"]);
        exit;
    }
}

/* 3. User Logout */
// Function to handle user logout
function logoutUser(): never {
    session_destroy();  // Destroy the session to log out the user
    // Redirect to index page
    header('Location: ../index.php');
    exit;
}

?>

