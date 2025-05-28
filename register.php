<?php
include_once 'assets/includes/header.php';
?>
<main>
    <div class="auth-container">
        <img src="assets/img/logo.svg" alt="Recipe Cloud Logo" class="logo" width="40" height="40">
        <h1>Create an account</h1>
        <p>Enter your information to create an account</p>
        <?php
        // Display any general errors from backend validation
        if (isset($_GET['errors']) && isset($_GET['errors']['general'])) {
            echo '<p class="error-message">' . htmlspecialchars($_GET['errors']['general']) . '</p>';
        }
        ?>
        <form id="register-form" method="POST" action="be-logic/auth.php">
            <input type="hidden" name="action" value="register">
            <div class="input-group">
                <label for="register-username">Username</label>
                <div> <input type="text" id="register-username" name="register-username" placeholder="JohnDoe" autocomplete="username">
                    <!--required is checked by js to prevent the ugly default browser error message-->
                    <p class="error-message" id="register-username-errormsg">
                        <?php if (isset($_GET['errors']) && isset($_GET['errors']['username'])) {
                            echo htmlspecialchars($_GET['errors']['username']);
                        } ?>
                    </p>
                </div>
            </div>
            <div class="input-group">
                <label for="register-email">Email</label>
                <div> <input type="text" id="register-email" name="register-email" placeholder="name@example.com" autocomplete="email">
                    <!--required and email formatting is checked by js to prevent the ugly default browser error message-->
                    <p class="error-message" id="register-email-errormsg">
                        <?php if (isset($_GET['errors']) && isset($_GET['errors']['email'])) {
                            echo htmlspecialchars($_GET['errors']['email']);
                        } ?>
                    </p>
                </div>
            </div>
            <div class="input-group">
                <label for="register-password">Password</label>
                <div> <input type="password" id="register-password" name="register-password" autocomplete="new-password">
                    <!--required is checked by js to prevent the ugly default browser error message-->
                    <p class="error-message" id="register-password-errormsg">
                        <?php if (isset($_GET['errors']) && isset($_GET['errors']['password'])) {
                            echo htmlspecialchars($_GET['errors']['password']);
                        } ?>
                    </p>
                </div>
            </div>
            <div class="input-group">
                <label for="register-password-confirm">Confirm Password</label>
                <div id="register-password-confirm-container"> <input type="password" id="register-password-confirm" name="register-password-confirm" autocomplete="new-password">
                    <!--required is checked by js to prevent the ugly default browser error message-->
                    <p class="error-message" id="register-password-confirm-errormsg">
                        <?php if (isset($_GET['errors']) && isset($_GET['errors']['password_confirm'])) {
                            echo htmlspecialchars($_GET['errors']['password_confirm']);
                        } ?>
                    </p>
                </div>
            </div>
            <button type="submit">Create account</button>
        </form>
        <p class="auth-mode-switch">Already have an account? <a href="Login.php">Login</a></p>

    </div>
</main>

<?php
include_once 'assets/includes/footer.php';
?>