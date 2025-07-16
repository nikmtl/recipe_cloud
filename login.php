<?php // load header
/* login.php 
    * This file contains the login form for users to sign in to their accounts.
    * It includes validation messages and error handling.
    * After a short fronted validation in auth.js, this form submits to the auth.php formhandler for processing and login and session management logic
    * Input validation is done in auth.js to prevent the ugly default browser error messages. (e.g.when using the required attribute) 
*/
include_once 'assets/includes/header.php';

// Start session to read error messages
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Get errors from session and then clear them
$errors = $_SESSION['errors'] ?? [];
if (isset($_SESSION['errors'])) {
    unset($_SESSION['errors']);
}
?>
<main>
    <div class="auth-container">
        <!-- Logo and Welcome Message -->
        <img src="assets/img/logo.svg" alt="Recipe Cloud Logo" class="logo" width="40" height="40">
        <h1>Welcome Back</h1>
        <?php
        // Display info message if user want to do something that requires login
        if (isset($_GET['msg'])) {
            echo '<p>' . htmlspecialchars($_GET['msg']) . '</p>';
        } else {
            echo '<p>Enter your credentials to access your account</p>';
        }        // Display any general errors from backend validation
        if (isset($errors['general'])) {
            echo '<p class="error-message">' . htmlspecialchars($errors['general']) . '</p>';
        }
        ?>
        <form id="login-form" method="POST" action="be-logic/auth.php">
            <input type="hidden" name="action" value="login">
            <div class="input-group">
                <label for="login-username">Username</label>
                <div>
                    <input type="text" id="login-username" name="login-username" autocomplete="username" placeholder="JohnDoe">
                    <p class="error-message" id="login-username-errormsg">
                        <?php if (isset($errors['username'])) {
                            echo htmlspecialchars($errors['username']);
                        } ?>
                    </p>
                </div>
            </div>
            <div class="input-group">
                <label for="login-password">Password</label>
                <div>
                    <input type="password" id="login-password" name="login-password" autocomplete="current-password">
                    <p class="error-message" id="login-password-errormsg">
                        <?php if (isset($errors['password'])) {
                            echo htmlspecialchars($errors['password']);
                        } ?>
                    </p>
                </div>
            </div>
            <button type="submit">Sign in</button>
        </form>
        <p class="auth-mode-switch">Don't have an account? <a href="register.php">Register</a></p>

    </div>
</main>
<?php // load footer
include_once 'assets/includes/footer.php';
?>
