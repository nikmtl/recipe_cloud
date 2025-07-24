/**
 * error-handling.js
 * Handles dynamic error message removal when users interact with form inputs.
 * Removes error styling and error messages when users start typing in input fields.
 */

document.addEventListener('DOMContentLoaded', function() {
    // Get all form inputs and textareas that might have error styling
    const inputs = document.querySelectorAll('input, textarea');
    
    inputs.forEach(input => {
        // Add event listeners for input events (when user starts typing)
        input.addEventListener('input', function() {
            clearFieldError(this);
        });
    });
});

/**
 * Clears error styling and error messages for a specific input field
 * @param {HTMLElement} input - The input element to clear errors for
 */
function clearFieldError(input) {
    // Remove error class from input
    if (input.classList.contains('error-input')) {
        input.classList.remove('error-input');
    }
    
    // Find and remove associated error message
    const errorMessage = findErrorMessage(input);
    if (errorMessage) {
        errorMessage.remove();
    }
}

/**
 * Finds the error message element associated with an input field
 * @param {HTMLElement} input - The input element to find error message for
 * @return {HTMLElement|null} - The error message element or null if not found
 */
function findErrorMessage(input) {
    // Check for error message as next sibling
    let nextElement = input.nextElementSibling;
    while (nextElement) {
        if (nextElement.classList.contains('error-message')) {
            return nextElement;
        }
        nextElement = nextElement.nextElementSibling;
    }
    
    // Check for error message in parent container (for grouped inputs)
    const parent = input.parentElement;
    if (parent) {
        const errorInParent = parent.querySelector('.error-message');
        if (errorInParent) {
            return errorInParent;
        }
        
        // Check parent's next sibling for error message
        let parentNext = parent.nextElementSibling;
        while (parentNext) {
            if (parentNext.classList.contains('error-message')) {
                return parentNext;
            }
            parentNext = parentNext.nextElementSibling;
        }
    }
    
    return null;
}
