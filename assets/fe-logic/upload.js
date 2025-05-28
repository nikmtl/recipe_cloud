
/* upload.js
This file contains the JavaScript code for the recipe upload form.
It includes validation for the form inputs based on database constraints
and provides frontend user feedback for invalid inputs.
*/

// Open the first tab by default without showing overlay
window.addEventListener('load', function() {
  openTap('tap-basic-info','tap-header-basic-info');
  console.log("Recipe upload form loaded successfully.");
});

// Recipe form validation
document.addEventListener('DOMContentLoaded', function() {
  const uploadForm = document.querySelector('form');
  
  // Get references to form elements for validation
  const titleField = document.getElementById('recipe-title');
  const descriptionField = document.getElementById('recipe-description');
  const prepTimeField = document.getElementById('recipe-prep-time');
  const cookTimeField = document.getElementById('recipe-cook-time');
  const difficultyField = document.getElementById('recipe-difficulty');
  const servingsField = document.getElementById('recipe-servings');
  const categoryField = document.getElementById('recipe-category');
  const ingredientAmountField = document.getElementById('ingredient-amount');
  const ingredientUnitField = document.getElementById('ingredient-unit');
  const ingredientNameField = document.getElementById('ingredient-name');
  const instructionStepField = document.getElementById('instruction-step');
  
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
    uploadForm.addEventListener('submit', function(event) {
      let isValid = true;
      
      // Title validation - required, max 100 chars
      if (!titleField.value.trim()) {
        displayError(titleField, document.getElementById('title-errormsg'), "Recipe title is required.");
        isValid = false;
      } else if (titleField.value.length > 100) {
        displayError(titleField, document.getElementById('title-errormsg'), "Title must be less than 100 characters.");
        isValid = false;
      }
      
      // Preparation time validation - must be a positive number
      if (prepTimeField.value.trim() && !isPositiveNumber(prepTimeField.value)) {
        displayError(prepTimeField, document.getElementById('prep-time-errormsg'), "Preparation time must be a positive number.");
        isValid = false;
      }
      
      // Cooking time validation - must be a positive number
      if (cookTimeField.value.trim() && !isPositiveNumber(cookTimeField.value)) {
        displayError(cookTimeField, document.getElementById('cook-time-errormsg'), "Cooking time must be a positive number.");
        isValid = false;
      }
      
      // Servings validation - must be a positive number
      if (!isPositiveNumber(servingsField.value)) {
        displayError(servingsField, document.getElementById('servings-errormsg'), "Servings must be a positive number.");
        isValid = false;
      }
      
      // Check if at least one ingredient has been added
      const ingredientList = document.getElementById('ingredient-list');
      if (ingredientList && ingredientList.children.length === 0) {
        const errorElement = document.getElementById('ingredient-name-errormsg');
        displayError(ingredientNameField, errorElement, "At least one ingredient is required.");
        isValid = false;
      }
      
      // Check if at least one instruction has been added
      const instructionList = document.getElementById('instruction-list');
      if (instructionList && instructionList.children.length === 0) {
        const errorElement = document.getElementById('instruction-step-errormsg');
        displayError(instructionStepField, errorElement, "At least one instruction step is required.");
        isValid = false;
      }
      
      // If validation fails, prevent form submission
      if (!isValid) {
        event.preventDefault();
      }
    });
    
    // Add event listeners for ingredient and instruction steps
    const addIngredientButton = document.querySelector('#ingredient-name').nextElementSibling;
    if (addIngredientButton) {
      addIngredientButton.addEventListener('click', function() {
        addIngredient();
      });
    }
    
    const addInstructionButton = document.querySelector('#instruction-step').nextElementSibling;
    if (addInstructionButton) {
      addInstructionButton.addEventListener('click', function() {
        addInstruction();
      });
    }
  }
  
  // Helper function to add an ingredient to the list
  function addIngredient() {
    const amount = ingredientAmountField.value.trim();
    const unit = ingredientUnitField.value;
    const name = ingredientNameField.value.trim();
    
    // Validate ingredient name
    if (!name) {
      displayError(ingredientNameField, document.getElementById('ingredient-name-errormsg'), "Ingredient name is required.");
      return;
    }
    
    if (name.length > 100) {
      displayError(ingredientNameField, document.getElementById('ingredient-name-errormsg'), "Ingredient name must be less than 100 characters.");
      return;
    }
    
    // Validate amount if provided
    if (amount && !isPositiveNumber(amount)) {
      displayError(ingredientAmountField, document.getElementById('ingredient-amount-errormsg'), "Amount must be a positive number.");
      return;
    }
    
    // Create a new ingredient list item
    const ingredientList = document.getElementById('ingredient-list');
    const listItem = document.createElement('div');
    listItem.classList.add('ingredient-item');
    
    // Format the ingredient text
    let ingredientText = '';
    if (amount) {
      ingredientText += amount + ' ' + unit + ' ';
    }
    ingredientText += name;
    
    // Create hidden inputs to store the data
    const amountInput = document.createElement('input');
    amountInput.type = 'hidden';
    amountInput.name = 'ingredient-amounts[]';
    amountInput.value = amount;
    
    const unitInput = document.createElement('input');
    unitInput.type = 'hidden';
    unitInput.name = 'ingredient-units[]';
    unitInput.value = unit;
    
    const nameInput = document.createElement('input');
    nameInput.type = 'hidden';
    nameInput.name = 'ingredient-names[]';
    nameInput.value = name;
    
    // Create remove button
    const removeButton = document.createElement('button');
    removeButton.type = 'button';
    removeButton.textContent = 'Remove';
    removeButton.addEventListener('click', function() {
      ingredientList.removeChild(listItem);
    });
    
    // Add elements to the list item
    listItem.textContent = ingredientText + ' ';
    listItem.appendChild(amountInput);
    listItem.appendChild(unitInput);
    listItem.appendChild(nameInput);
    listItem.appendChild(removeButton);
    
    // Add to list and clear inputs
    ingredientList.appendChild(listItem);
    ingredientAmountField.value = '';
    ingredientNameField.value = '';
    
    // Clear any error messages
    clearError(ingredientAmountField, document.getElementById('ingredient-amount-errormsg'));
    clearError(ingredientNameField, document.getElementById('ingredient-name-errormsg'));
  }
  
  // Helper function to add an instruction step to the list
  function addInstruction() {
    const step = instructionStepField.value.trim();
    
    // Validate instruction
    if (!step) {
      displayError(instructionStepField, document.getElementById('instruction-step-errormsg'), "Instruction step is required.");
      return;
    }
    
    // Create a new instruction list item
    const instructionList = document.getElementById('instruction-list');
    const listItem = document.createElement('div');
    listItem.classList.add('instruction-item');
    
    // Step number (based on current list length)
    const stepNumber = instructionList.children.length + 1;
    
    // Create hidden input to store the instruction
    const stepInput = document.createElement('input');
    stepInput.type = 'hidden';
    stepInput.name = 'instruction-steps[]';
    stepInput.value = step;
    
    // Create remove button
    const removeButton = document.createElement('button');
    removeButton.type = 'button';
    removeButton.textContent = 'Remove';
    removeButton.addEventListener('click', function() {
      instructionList.removeChild(listItem);
      // Update step numbers
      updateInstructionNumbers();
    });
    
    // Add elements to the list item
    listItem.innerHTML = '<span class="step-number">Step ' + stepNumber + ':</span> ' + step;
    listItem.appendChild(stepInput);
    listItem.appendChild(removeButton);
    
    // Add to list and clear input
    instructionList.appendChild(listItem);
    instructionStepField.value = '';
    
    // Clear any error message
    clearError(instructionStepField, document.getElementById('instruction-step-errormsg'));
  }
  
  // Helper function to update instruction step numbers
  function updateInstructionNumbers() {
    const instructionItems = document.querySelectorAll('.instruction-item');
    instructionItems.forEach((item, index) => {
      const stepNumberSpan = item.querySelector('.step-number');
      if (stepNumberSpan) {
        stepNumberSpan.textContent = 'Step ' + (index + 1) + ':';
      }
    });
  }
  
  // Helper functions for validation and error handling
  function isPositiveNumber(value) {
    return /^[1-9]\d*$/.test(value);
  }
  
  function displayError(inputField, messageField, message) {
    if (messageField) {
      messageField.textContent = message;
    }
    if (inputField) {
      inputField.classList.add('error');
    }
  }
  
  function clearError(inputField, messageField) {
    if (messageField) {
      messageField.textContent = '';
    }
    if (inputField) {
      inputField.classList.remove('error');
    }
  }
  
  // Add event listeners to clear error messages on input
  const inputFields = [
    titleField, descriptionField, prepTimeField, cookTimeField, 
    servingsField, ingredientAmountField, ingredientNameField, instructionStepField
  ];
  
  inputFields.forEach(field => {
    if (field) {
      field.addEventListener('input', function() {
        const errorId = field.id.replace(/-/g, '-') + '-errormsg';
        clearError(field, document.getElementById(errorId));
      });
    }
  });
});