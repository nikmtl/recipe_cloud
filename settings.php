<?php
/* profile.php
    * Displays user profile information including name, username, joined date, recipe count, favorites count, and bio.
    * Requires user to be logged in.
    * It also shows the the users recipes and favorites.
    * It also shows how many times their recipes were favorited by others. This is useful for users to see how popular their recipes are and gives the user a sense of accomplishment.
*/

//load header
require_once 'be-logic/protected_page.php';
require_once 'be-logic/get_user_profile.php';
include_once 'assets/includes/header.php';

// Fetch user information
$currentUser = $_SESSION['username'];
$userProfile = getUserProfile($currentUser);

if (!$userProfile) {
    echo "<p>Error loading profile information.</p>";
    exit;
}
?>
<main>
    <div class="profile-container">
        <div class="backnav">
            <a class="back-button" href="profile.php"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
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
            <p>Update your personal information and profile details</p>
            <form action="be-logic/update_account.php" method="POST">
                <div class="profile-field-group">
                    <div>
                        <label for="first_name">First Name</label>
                        <input type="text" id="first_name" name="first_name" value="<?php echo htmlspecialchars($userProfile['first_name']); ?>" required>
                    </div>
                    <div>
                        <label for="last_name">Last Name</label>
                        <input type="text" id="last_name" name="last_name" value="<?php echo htmlspecialchars($userProfile['last_name']); ?>" required>
                    </div>
                </div>
                <div>
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($userProfile['email']); ?>" required>
                </div>
                <div>
                    <label for="bio">Bio</label>
                    <textarea id="bio" name="bio" style="resize: none;"><?php echo htmlspecialchars($userProfile['bio']); ?></textarea>
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
            <h2>Account Actions</h2>
            <p>Manage your account session and data</p>
            <form action="be-logic/auth.php" method="POST" id="logout-form">
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
            <!--TODO: add feat to get all user data and download as JSON or CSV because of legal reasons-->
            <hr>
            <form action="be-logic/delete_account.php" method="POST">
                <div id="danger-zone">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3"></path>
                        <path d="M12 9v4"></path>
                        <path d="M12 17h.01"></path>
                    </svg>
                    <strong>Danger Zone:</strong>
                    Once you delete your account, there is no going back. This action cannot be undone.
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
