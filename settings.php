<?php
/* profile.php
    * Displays user profile information including name, username, joined date, recipe count, favorites count, and bio.
    * Requires user to be logged in.
    * It also shows the the users recipes and favorites.
    * It also shows how many times their recipes were favorited by others. This is useful for users to see how popular their recipes are and gives the user a sense of accomplishment.
*/

//load header
require_once 'be-logic/protected_page.php';
require_once 'be-logic/fetch_user_profile.php';
include_once 'assets/includes/header.php';

// Fetch user information
$currentUser = $_SESSION['username'];
$userProfile = fetchUserProfile($currentUser);

if (!$userProfile) {
    die("Error loading profile information.");
}

?>
<main>
    <div class="profile-container">
        <div class="backnav">
            <a class="back-button" href="profile">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="m12 19-7-7 7-7"></path>
                    <path d="M19 12H5"></path>
                </svg>Back to profile
            </a>
        </div>
        <div class="settings-header">
            <h1>Account Settings</h1>
            <p>Manage your account preferences and security settings</p>
        </div>
        <div class="section">
            <h2>Profile Information</h2>
            <?php if (isset($_SESSION['errors']['general_profile'])) {
                echo "<p class=\"error-message\">
                        " . htmlspecialchars($_SESSION['errors']['general_profile']) . "
                    </p>";
                unset($_SESSION['errors']['general_profile']);
            } else {
                echo "<p>Update your personal information and profile details</p>";
            }
            ?>
            <form action="be-logic/formhandler/account.php" method="POST">
                <input type="hidden" name="action" value="update_profile">
                <div class="profile-field-group">
                    <div>
                        <label for="first_name">First Name</label>
                        <input type="text" id="first_name" name="first_name" value="<?php echo htmlspecialchars($userProfile['first_name'] ?? ''); ?>">
                        <?php if (isset($_SESSION['errors']['first_name'])) {
                            echo "<p class=\"error-message\">
                                    " . htmlspecialchars($_SESSION['errors']['first_name']) . "
                                </p>";
                            unset($_SESSION['errors']['first_name']);
                        } ?>
                    </div>
                    <div>
                        <label for="last_name">Last Name</label>
                        <input type="text" id="last_name" name="last_name" value="<?php echo htmlspecialchars($userProfile['last_name'] ?? ''); ?>">
                        <?php if (isset($_SESSION['errors']['last_name'])) {
                            echo "<p class=\"error-message\">
                                    " . htmlspecialchars($_SESSION['errors']['last_name']) . "
                                </p>";
                            unset($_SESSION['errors']['last_name']);
                        } ?>
                    </div>
                </div>
                <div>
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($userProfile['email'] ?? ''); ?>">
                    <?php if (isset($_SESSION['errors']['email'])) {
                        echo "<p class=\"error-message\">
                                " . htmlspecialchars($_SESSION['errors']['email']) . "
                            </p>";
                        unset($_SESSION['errors']['email']);
                    } ?>
                </div>
                <div>
                    <label for="bio">Bio</label>
                    <textarea id="bio" name="bio" style="resize: none;"><?php echo htmlspecialchars($userProfile['bio'] ?? ''); ?></textarea>
                    <?php if (isset($_SESSION['errors']['bio'])) {
                        echo "<p class=\"error-message\">
                                " . htmlspecialchars($_SESSION['errors']['bio']) . "
                            </p>";
                        unset($_SESSION['errors']['bio']);
                    } ?>
                </div>
                <button type="submit" class="icon-button smt-bttn">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M15.2 3a2 2 0 0 1 1.4.6l3.8 3.8a2 2 0 0 1 .6 1.4V19a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2z"></path>
                        <path d="M17 21v-7a1 1 0 0 0-1-1H8a1 1 0 0 0-1 1v7"></path>
                        <path d="M7 3v4a1 1 0 0 0 1 1h7"></path>
                    </svg>
                    Save Changes
                </button>
            </form>
        </div>
        <div class="section">
            <h2>Update Password</h2>
            <?php if (isset($_SESSION['errors']['password'])) {
                echo "<p class=\"error-message\">
                        " . htmlspecialchars($_SESSION['errors']['password']) . "
                    </p>";
                unset($_SESSION['errors']['password']);
            } else if (isset($_SESSION['success']['password'])) {
                echo "<p class=\"success-message\">
                        " . htmlspecialchars($_SESSION['success']['password']) . "
                    </p>";
                unset($_SESSION['success']['password']);
            } else {
                echo "<p>Update your password to keep your account secure</p>";
            } ?>
            <form action="be-logic/formhandler/account.php" method="POST">
                <input type="hidden" name="action" value="change_password">
                <div class="input-group">
                    <label for="current_password">Current Password</label>
                    <div>
                        <input type="password" id="current_password" name="current_password">
                        <button type="button" class="password-toggle" tabindex="-1" onclick="togglePasswordVisibility('current_password')">
                            <svg xmlns="http://www.w3.org/2000/svg" class="eye-icon" fill="none" width="16" height="16" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                            </svg>
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" display="none" class="eye-off-icon" width="16" height="16" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 0 0 1.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.451 10.451 0 0 1 12 4.5c4.756 0 8.773 3.162 10.065 7.498a10.522 10.522 0 0 1-4.293 5.774M6.228 6.228 3 3m3.228 3.228 3.65 3.65m7.894 7.894L21 21m-3.228-3.228-3.65-3.65m0 0a3 3 0 1 0-4.243-4.243m4.242 4.242L9.88 9.88" />
                            </svg>
                        </button>
                        <?php if (isset($_SESSION['errors']['current_password'])) {
                            echo "<p class=\"error-message\">
                                " . htmlspecialchars($_SESSION['errors']['current_password']) . "
                            </p>";
                            unset($_SESSION['errors']['current_password']);
                        } ?>
                    </div>
                </div>
                <div class="input-group">
                    <label for="new_password">New Password</label>
                    <div>
                        <input type="password" id="new_password" name="new_password">
                        <button type="button" class="password-toggle" tabindex="-1" onclick="togglePasswordVisibility('new_password')">
                            <svg xmlns="http://www.w3.org/2000/svg" class="eye-icon" fill="none" width="16" height="16" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                            </svg>
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" display="none" class="eye-off-icon" width="16" height="16" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 0 0 1.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.451 10.451 0 0 1 12 4.5c4.756 0 8.773 3.162 10.065 7.498a10.522 10.522 0 0 1-4.293 5.774M6.228 6.228 3 3m3.228 3.228 3.65 3.65m7.894 7.894L21 21m-3.228-3.228-3.65-3.65m0 0a3 3 0 1 0-4.243-4.243m4.242 4.242L9.88 9.88" />
                            </svg>
                        </button>
                        <?php if (isset($_SESSION['errors']['new_password'])) {
                            echo "<p class=\"error-message\">
                                " . htmlspecialchars($_SESSION['errors']['new_password']) . "
                            </p>";
                            unset($_SESSION['errors']['new_password']);
                        } ?>
                    </div>
                </div>
                <button type="submit" class="icon-button smt-bttn">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 5.25a3 3 0 0 1 3 3m3 0a6 6 0 0 1-7.029 5.912c-.563-.097-1.159.026-1.563.43L10.5 17.25H8.25v2.25H6v2.25H2.25v-2.818c0-.597.237-1.17.659-1.591l6.499-6.499c.404-.404.527-1 .43-1.563A6 6 0 1 1 21.75 8.25Z" />
                    </svg>
                    Update Password
                </button>
            </form>
        </div>
        <div class="section">
            <h2>Account Actions</h2>
            <?php if (isset($_SESSION['errors']['general_account_actions'])) {
                echo "<p class=\"error-message\">
                        " . htmlspecialchars($_SESSION['errors']['general_account_actions']) . "
                    </p>";
                unset($_SESSION['errors']['general_account_actions']);
            } else {
                echo "<p>Manage your account session and data</p>";
            } ?>
            <form action="be-logic/formhandler/auth.php" method="POST" id="logout-form">
                <input type="hidden" name="action" value="logout">
                <div>
                    <h4>Logout</h4>
                    <p>Sign out of your account on this device</p>
                </div>
                <button type="submit" class="icon-button secondary-button smt-bttn">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                        <polyline points="16 17 21 12 16 7"></polyline>
                        <line x1="21" x2="9" y1="12" y2="12"></line>
                    </svg>
                    Logout
                </button>
            </form>
            <form action="be-logic/formhandler/account.php" method="POST" id="export-form">
                <input type="hidden" name="action" value="export_data">
                <div>
                    <h4>Export Data</h4>
                    <p>Download all your data associated with your account.</p>
                </div>
                <button type="submit" class="icon-button secondary-button smt-bttn">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3" />
                    </svg>
                    Export Data
                </button>
            </form>
            <hr>
            <form action="be-logic/formhandler/account.php" method="POST" id="delete-account-form">
                <input type="hidden" name="action" value="delete_account">
                <div id="danger-zone">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3"></path>
                        <path d="M12 9v4"></path>
                        <path d="M12 17h.01"></path>
                    </svg>
                    <div>
                        <strong>Danger Zone:</strong>
                        Once you delete your account, there is no going back. This action cannot be undone.
                    </div>
                </div>
                <div class="delete-account">
                    <div>
                        <h4>Delete Account</h4>
                        <p>Permanently delete your account. With this action, your user data, including your profile information and settings, will be removed. However, your recipes will remain in the system but will no longer be associated with your account.</p>
                    </div>
                    <button type="submit" class="icon-button delete-button smt-bttn" onclick="return confirm('Are you sure you want to delete your account? This action cannot be undone.');">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M3 6h18"></path>
                            <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"></path>
                            <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"></path>
                            <line x1="10" x2="10" y1="11" y2="17"></line>
                            <line x1="14" x2="14" y1="11" y2="17"></line>
                        </svg>
                        Delete Account
                    </button>
                </div>
            </form>
        </div>
    </div>


</main>

<?php // load footer
include_once 'assets/includes/footer.php';
?>