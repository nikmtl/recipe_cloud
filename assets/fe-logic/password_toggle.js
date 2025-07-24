/* password_toggle.js
    * This file contains the JavaScript code for toggling the visibility of password fields in forms.
    * It allows users to show or hide their password input for better usability.
    * This is particularly useful in registration and settings forms where users need to enter passwords.
*/

function togglePasswordVisibility(passwordFieldId) {
    const passwordField = document.getElementById(passwordFieldId);
    const eyeIcon = passwordField.nextElementSibling.querySelector('.eye-icon');
    const eyeOffIcon = passwordField.nextElementSibling.querySelector('.eye-off-icon');

    if (passwordField.type === 'password') {
        passwordField.type = 'text';
        eyeIcon.style.display = 'none';
        eyeOffIcon.style.display = 'inline';
    } else {
        passwordField.type = 'password';
        eyeIcon.style.display = 'inline';
        eyeOffIcon.style.display = 'none';
    }
}