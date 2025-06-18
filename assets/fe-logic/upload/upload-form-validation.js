/* upload-form-validation.js
    * This file contains the JavaScript code for validating the upload form in the frontend.
    * It checks if the required fields are filled and if the image is selected before allowing submission.
*/

// Helper functions for validation and error handling
function isPositiveNumber(value) {
  const num = parseFloat(value);
  return !isNaN(num) && num > 0;
}

function displayError(inputField, messageField, message) {
  if (messageField) {
    messageField.textContent = message;
    messageField.style.display = 'block';
  }
  if (inputField) {
    inputField.classList.add('error');
  }
}

function clearError(inputField, messageField) {
  if (messageField) {
    messageField.textContent = '';
    messageField.style.display = 'none';
  }
  if (inputField) {
    inputField.classList.remove('error');
  }
}

const uploadForm = document.querySelector('.upload-form');

// Get references to form elements for validation
const titleField = document.getElementById('recipe-title');
const descriptionField = document.getElementById('recipe-description');
const prepTimeField = document.getElementById('recipe-prep-time');
const cookTimeField = document.getElementById('recipe-cook-time');
const difficultyField = document.getElementById('recipe-difficulty');
const servingsField = document.getElementById('recipe-servings');
const categoryField = document.getElementById('recipe-category');

// Create error message elements for input fields that don't have them
function createErrorElementIfMissing(inputField, id) {
  if (!inputField) return;
  
  // Check if error element already exists
  if (document.getElementById(id)) return;
  
  const errorElement = document.createElement('span');
  errorElement.id = id;
  errorElement.className = 'error-message';
  errorElement.style.display = 'none';
  inputField.parentNode.insertBefore(errorElement, inputField.nextSibling);
}

// Wait for DOM to be ready, then create missing error elements
document.addEventListener('DOMContentLoaded', function() {
  createErrorElementIfMissing(document.getElementById('ingredient-amount'), 'ingredient-amount-errormsg');
  createErrorElementIfMissing(document.getElementById('instruction-step'), 'instruction-step-errormsg');
});

// Validate the form on submission
if (uploadForm) {
  uploadForm.addEventListener('submit', function (event) {
    let isValid = true;
    let firstErrorTab = null; // Track the first tab with an error

    // Clear all errors first
    clearAllErrors();    // Title validation - required, max 100 chars
    if (!titleField || !titleField.value.trim()) {
      displayError(titleField, document.getElementById('recipe-title-errormsg'), "Recipe title is required.");
      isValid = false;
      if (!firstErrorTab) firstErrorTab = { tabId: 'tap-basic-info', headerId: 'tap-header-basic-info' };
    } else if (titleField.value.length > 100) {
      displayError(titleField, document.getElementById('recipe-title-errormsg'), "Title must be less than 100 characters.");
      isValid = false;
      if (!firstErrorTab) firstErrorTab = { tabId: 'tap-basic-info', headerId: 'tap-header-basic-info' };
    }

    // Preparation time validation - must be a positive number
    if (prepTimeField && prepTimeField.value.trim() && !isPositiveNumber(prepTimeField.value)) {
      displayError(prepTimeField, document.getElementById('recipe-prep-time-errormsg'), "Preparation time must be a positive number.");
      isValid = false;
      if (!firstErrorTab) firstErrorTab = { tabId: 'tap-basic-info', headerId: 'tap-header-basic-info' };
    }

    // Cooking time validation - must be a positive number
    if (cookTimeField && cookTimeField.value.trim() && !isPositiveNumber(cookTimeField.value)) {
      displayError(cookTimeField, document.getElementById('recipe-cook-time-errormsg'), "Cooking time must be a positive number.");
      isValid = false;
      if (!firstErrorTab) firstErrorTab = { tabId: 'tap-basic-info', headerId: 'tap-header-basic-info' };
    }

    // Servings validation - must be a positive number
    if (!servingsField || !isPositiveNumber(servingsField.value)) {
      displayError(servingsField, document.getElementById('recipe-servings-errormsg'), "Servings must be a positive number.");
      isValid = false;
      if (!firstErrorTab) firstErrorTab = { tabId: 'tap-basic-info', headerId: 'tap-header-basic-info' };
    }// Check if at least one ingredient has been added
    const ingredientList = document.getElementById('ingredient-list');
    if (ingredientList && ingredientList.children.length === 0) {
      const errorElement = document.getElementById('ingredients-errormsg');
      if (errorElement) {
        displayError(null, errorElement, "At least one ingredient is required.");
      }
      isValid = false;
      if (!firstErrorTab) firstErrorTab = { tabId: 'tap-ingredients', headerId: 'tap-header-ingredients' };
    }

    // Check if at least one instruction has been added
    const instructionList = document.getElementById('instruction-list');
    if (instructionList && instructionList.children.length === 0) {
      const errorElement = document.getElementById('instructions-errormsg');
      if (errorElement) {
        displayError(null, errorElement, "At least one instruction step is required.");
      }
      isValid = false;
      if (!firstErrorTab) firstErrorTab = { tabId: 'tap-instructions', headerId: 'tap-header-instructions' };
    }

    // If validation fails, prevent form submission and open the first error tab
    if (!isValid) {
      event.preventDefault();
      if (firstErrorTab && typeof openTap === 'function') {
        openTap(firstErrorTab.tabId, firstErrorTab.headerId);
      }
    }
  });
}

// Helper function to clear all error messages
function clearAllErrors() {
  const errorMessages = document.querySelectorAll('.error-message');
  errorMessages.forEach(error => {
    error.textContent = '';
    error.style.display = 'none';
  });
  
  // Remove error styling from input fields
  const inputFields = [
    titleField, descriptionField, prepTimeField, cookTimeField,
    servingsField
  ];
  
  // Also clear errors from ingredient and instruction fields if they exist
  const ingredientAmountField = document.getElementById('ingredient-amount');
  const ingredientNameField = document.getElementById('ingredient-name');
  const instructionStepField = document.getElementById('instruction-step');
  
  if (ingredientAmountField) inputFields.push(ingredientAmountField);
  if (ingredientNameField) inputFields.push(ingredientNameField);
  if (instructionStepField) inputFields.push(instructionStepField);
  
  inputFields.forEach(field => {
    if (field) {
      field.classList.remove('error');
    }
  });
}

// Add event listeners to clear error messages on input
function addClearErrorListeners() {
  const inputFields = [
    { field: titleField, errorId: 'recipe-title-errormsg' },
    { field: descriptionField, errorId: 'recipe-description-errormsg' },
    { field: prepTimeField, errorId: 'recipe-prep-time-errormsg' },
    { field: cookTimeField, errorId: 'recipe-cook-time-errormsg' },
    { field: servingsField, errorId: 'recipe-servings-errormsg' }
  ];

  inputFields.forEach(({ field, errorId }) => {
    if (field) {
      field.addEventListener('input', function () {
        const errorElement = document.getElementById(errorId);
        clearError(field, errorElement);
      });
    }
  });
}

// Initialize error listeners when DOM is ready
document.addEventListener('DOMContentLoaded', addClearErrorListeners);
