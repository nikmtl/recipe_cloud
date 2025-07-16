<?php 
/* register.php 
    * This file is the registration page for new users to create an account.
    * It includes validation messages and error handling.
    * After a short fronted validation in auth.js, this form submits to the auth.php formhandler for processing and registarion and session management logic
    * Input validation is done in auth.js to prevent the ugly default browser error messages. (e.g.when using the required attribute) 
*/

// load header
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
        <img src="assets/img/logo.svg" alt="Recipe Cloud Logo" class="logo" width="40" height="40">
        <h1>Create an account</h1>
        <p>Enter your information to create an account</p>
        <?php
        // Display any general errors from backend validation
        if (isset($errors['general'])) {
            echo '<p class="error-message">' . htmlspecialchars($errors['general']) . '</p>';
        }
        ?>
        <form id="register-form" method="POST" action="be-logic/auth.php">
            <input type="hidden" name="action" value="register">
            <div class="input-group">
                <label for="register-username">Username</label>
                <div> <input type="text" id="register-username" name="register-username" placeholder="JohnDoe" autocomplete="username">
                    <p class="error-message" id="register-username-errormsg">
                        <?php if (isset($errors['username'])) {
                            echo htmlspecialchars($errors['username']);
                        } ?>
                    </p>
                </div>
            </div>
            <div class="input-group">
                <label for="register-email">Email</label>
                <div> <input type="text" id="register-email" name="register-email" placeholder="name@example.com" autocomplete="email">
                    <p class="error-message" id="register-email-errormsg">
                        <?php if (isset($errors['email'])) {
                            echo htmlspecialchars($errors['email']);
                        } ?>
                    </p>
                </div>
            </div>
            <div class="input-group">
                <label for="register-password">Password</label>
                <div> <input type="password" id="register-password" name="register-password" autocomplete="new-password">
                    <p class="error-message" id="register-password-errormsg">
                        <?php if (isset($errors['password'])) {
                            echo htmlspecialchars($errors['password']);
                        } ?>
                    </p>
                </div>
            </div>
            <div class="input-group">
                <label for="register-password-confirm">Confirm Password</label>
                <div id="register-password-confirm-container"> <input type="password" id="register-password-confirm" name="register-password-confirm" autocomplete="new-password">
                    <p class="error-message" id="register-password-confirm-errormsg">
                        <?php if (isset($errors['password_confirm'])) {
                            echo htmlspecialchars($errors['password_confirm']);
                        } ?>
                    </p>
                </div>
            </div>
            <button type="submit">Create account</button>
        </form>
        <p class="auth-mode-switch">Already have an account? <a href="Login.php">Login</a></p>

    </div>
</main>

<?php // load footer
include_once 'assets/includes/footer.php';
?>
