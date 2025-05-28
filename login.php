<?php
include_once 'assets/includes/header.php';
?>
<main>
    <div class="auth-container">
        <img src="assets/img/logo.svg" alt="Recipe Cloud Logo" class="logo" width="40" height="40">
        <h1>Welcome Back</h1>
        <?php
        // Display info message if user want to do something that requires login
        if (isset($_GET['msg'])) {
            echo '<p>' . htmlspecialchars($_GET['msg']) . '</p>';
        } else {
            echo '<p>Enter your credentials to access your account</p>';
        }

        // Display any general errors from backend validation
        if (isset($_GET['errors']) && isset($_GET['errors']['general'])) {
            echo '<p class="error-message">' . htmlspecialchars($_GET['errors']['general']) . '</p>';
        }
        ?>
        <form id="login-form" method="POST" action="be-logic/auth.php">
            <input type="hidden" name="action" value="login">
            <div class="input-group">
                <label for="login-username">Username</label>
                <div>
                    <input type="text" id="login-username" name="login-username" autocomplete="username" placeholder="JohnDoe">
                    <!--required is checked by js to prevent the ugly default browser error message-->
                    <p class="error-message" id="login-username-errormsg">
                        <?php if (isset($_GET['errors']) && isset($_GET['errors']['username'])) {
                            echo htmlspecialchars($_GET['errors']['username']);
                        } ?>
                    </p>
                </div>
            </div>
            <div class="input-group">
                <label for="login-password">Password</label>
                <div>
                    <input type="password" id="login-password" name="login-password" autocomplete="current-password">
                    <!--required is checked by js to prevent the ugly default browser error message-->
                    <p class="error-message" id="login-password-errormsg">
                        <?php if (isset($_GET['errors']) && isset($_GET['errors']['password'])) {
                            echo htmlspecialchars($_GET['errors']['password']);
                        } ?>
                    </p>
                </div>
            </div>
            <button type="submit">Sign in</button>
        </form>
        <p class="auth-mode-switch">Don't have an account? <a href="register.php">Register</a></p>

    </div>
</main>
<?php
include_once 'assets/includes/footer.php';
?>