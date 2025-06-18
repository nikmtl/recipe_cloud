/* upload-form-validation.js
    * This file contains the JavaScript code for validating the upload form in the frontend.
    * It checks if the required fields are filled and if the image is selected before allowing submission.
*/

const uploadForm = document.querySelector('.upload-form');

// Get references to form elements for validation
const titleField = document.getElementById('recipe-title');
const descriptionField = document.getElementById('recipe-description');
const prepTimeField = document.getElementById('recipe-prep-time');
const cookTimeField = document.getElementById('recipe-cook-time');
const difficultyField = document.getElementById('recipe-difficulty');
const servingsField = document.getElementById('recipe-servings');
const categoryField = document.getElementById('recipe-category');

// Create error message elements for each field
createErrorElement(titleField, 'title-errormsg');
createErrorElement(descriptionField, 'description-errormsg');
createErrorElement(prepTimeField, 'prep-time-errormsg');
createErrorElement(cookTimeField, 'cook-time-errormsg');
createErrorElement(servingsField, 'servings-errormsg');
createErrorElement(ingredientAmountField, 'ingredient-amount-errormsg');
createErrorElement(ingredientNameField, 'ingredient-name-errormsg');
createErrorElement(instructionStepField, 'instruction-step-errormsg');

// Helper function to create error message element
function createErrorElement(inputField, id) {
  if (!inputField) return;

  const errorElement = document.createElement('span');
  errorElement.id = id;
  errorElement.className = 'error-message';
  inputField.parentNode.insertBefore(errorElement, inputField.nextSibling);
}

// Validate the form on submission
if (uploadForm) {
  uploadForm.addEventListener('submit', function (event) {
    let isValid = true;
    let firstErrorTab = null; // Track the first tab with an error

    // Clear all errors first
    clearAllErrors();

    // Title validation - required, max 100 chars
    if (!titleField.value.trim()) {
      displayError(titleField, document.getElementById('title-errormsg'), "Recipe title is required.");
      isValid = false;
      if (!firstErrorTab) firstErrorTab = { tabId: 'tap-basic-info', headerId: 'tap-header-basic-info' };
    } else if (titleField.value.length > 100) {
      displayError(titleField, document.getElementById('title-errormsg'), "Title must be less than 100 characters.");
      isValid = false;
      if (!firstErrorTab) firstErrorTab = { tabId: 'tap-basic-info', headerId: 'tap-header-basic-info' };
    }

    // Preparation time validation - must be a positive number
    if (prepTimeField.value.trim() && !isPositiveNumber(prepTimeField.value)) {
      displayError(prepTimeField, document.getElementById('prep-time-errormsg'), "Preparation time must be a positive number.");
      isValid = false;
      if (!firstErrorTab) firstErrorTab = { tabId: 'tap-basic-info', headerId: 'tap-header-basic-info' };
    }

    // Cooking time validation - must be a positive number
    if (cookTimeField.value.trim() && !isPositiveNumber(cookTimeField.value)) {
      displayError(cookTimeField, document.getElementById('cook-time-errormsg'), "Cooking time must be a positive number.");
      isValid = false;
      if (!firstErrorTab) firstErrorTab = { tabId: 'tap-basic-info', headerId: 'tap-header-basic-info' };
    }

    // Servings validation - must be a positive number
    if (!isPositiveNumber(servingsField.value)) {
      displayError(servingsField, document.getElementById('servings-errormsg'), "Servings must be a positive number.");
      isValid = false;
      if (!firstErrorTab) firstErrorTab = { tabId: 'tap-basic-info', headerId: 'tap-header-basic-info' };
    }

    // Check if at least one ingredient has been added
    const ingredientList = document.getElementById('ingredient-list');
    if (ingredientList && ingredientList.children.length === 0) {
      const errorElement = document.getElementById('ingredient-name-errormsg');
      displayError(ingredientNameField, errorElement, "At least one ingredient is required.");
      isValid = false;
      if (!firstErrorTab) firstErrorTab = { tabId: 'tap-ingredients', headerId: 'tap-header-ingredients' };
    }

    // Check if at least one instruction has been added
    const instructionList = document.getElementById('instruction-list');
    if (instructionList && instructionList.children.length === 0) {
      const errorElement = document.getElementById('instruction-step-errormsg');
      displayError(instructionStepField, errorElement, "At least one instruction step is required.");
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
    servingsField, ingredientAmountField, ingredientNameField, instructionStepField
  ];
  
  inputFields.forEach(field => {
    if (field) {
      field.classList.remove('error');
    }
  });
}

// Add event listeners to clear error messages on input
const inputFields = [
  titleField, descriptionField, prepTimeField, cookTimeField,
  servingsField, ingredientAmountField, ingredientNameField, instructionStepField
];

inputFields.forEach(field => {
  if (field) {
    field.addEventListener('input', function () {
      const errorId = field.id.replace(/-/g, '-') + '-errormsg';
      clearError(field, document.getElementById(errorId));
    });
  }
});
