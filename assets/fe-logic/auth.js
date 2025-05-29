/* auth.js
    * This file contains the JavaScript code for validating the registration and login forms on the frontend.
    * It sends frontend user feedback if the input is invalid and prevents the form from being submitted if the input is invalid.
    * This does not include the actual authentication process, which is handled by the backend. But it prevents invalid input from being sent to the backend.
    Sections in this file:
    * 1. Registration form validation
    * 2. Login form validation
    * 3. Helper functions for displaying and clearing error messages
*/

/*1. Registration form validation*/
// Registration form validation dom elements
const registerForm = document.getElementById('register-form');
if (registerForm) {
    const usernameField = document.getElementById('register-username');
    const usernameError = document.getElementById('register-username-errormsg');
    const emailField = document.getElementById('register-email');
    const emailError = document.getElementById('register-email-errormsg');
    const passwordField = document.getElementById('register-password');
    const passwordError = document.getElementById('register-password-errormsg');
    const passwordConfirmField = document.getElementById('register-password-confirm');
    const passwordConfirmError = document.getElementById('register-password-confirm-errormsg');
    const inputFields = [usernameField, emailField, passwordField, passwordConfirmField];


    // Add event listener to the registration form to validate input fields on submission
    registerForm.addEventListener('submit', function (event) {

        const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/; // Regular expression for validating email format

        if (usernameField.value.length < 2 || usernameField.value.length > 20) { // Check if username is between 2 and 20 characters
            displayError(usernameField, usernameError, "Username must be between 3 and 20 characters.");
            event.preventDefault();
        }

        if (!emailPattern.test(emailField.value)) { // Validate email format using regex
            displayError(emailField, emailError, "Email must be a valid email address.");
            event.preventDefault();
        }

        if (emailField.value.length > 50) { // Check if email is shorter than 50 characters
            displayError(emailField, emailError, "Email must be shorter than 50 characters.");
            event.preventDefault();
        }

        if (passwordField.value.length < 8) { // Check if password is longer than 8 characters
            displayError(passwordField, passwordError, "Password must be longer than 8 characters.");
            displayError(passwordField);
            event.preventDefault();
        }

        if (passwordField.value.length > 32) { // Check if password is shorter than 32 characters
            displayError(passwordField, passwordError, "Password must be shorter than 32 characters.");
            displayError(passwordField);
            event.preventDefault();
        }

        if (passwordField.value !== passwordConfirmField.value) { // Check if password and password confirmation match
            displayError(passwordConfirmField, passwordConfirmError, "Passwords do not match.");
            event.preventDefault();
        }
    });

    // Add event listeners to clear error messages on input
    inputFields.forEach(field => {
        field.addEventListener('input', function () {
            clearError(field, field.nextElementSibling);
        });
    });
}




/*2. Login form validation*/
// Login form validation dom elements
const loginForm = document.getElementById('login-form');
if (loginForm) { 
    const loginUsernameField = document.getElementById('login-username');
    const loginUsernameError = document.getElementById('login-username-errormsg');
    const loginPasswordField = document.getElementById('login-password');
    const loginPasswordError = document.getElementById('login-password-errormsg');
    const loginInputFields = [loginUsernameField, loginPasswordField];

    // With the login form, we only need to check if the fields are empty
    // Add event listener to the login form to validate input fields on submission
    loginForm.addEventListener('submit', function (event) {
        if (loginUsernameField.value === '') { // Check if username field is empty
            displayError(loginUsernameField);
            event.preventDefault();
        }

        if (loginPasswordField.value === '') { // Check if password field is empty
            displayError(loginPasswordField);
            event.preventDefault();
        }
    });

    // Add event listeners to clear error messages on input
    loginInputFields.forEach(field => { 
        field.addEventListener('input', function () {
            clearError(field, field.nextElementSibling);
        });
    });
}





/*3. Helper function to display error message */
function displayError(inputField, messageField, message) {
    if (message) {
        messageField.textContent = message;
    }
    inputField.classList.add('error');
}

function clearError(inputField, messageField) {
    messageField.textContent = '';
    inputField.classList.remove('error');
}