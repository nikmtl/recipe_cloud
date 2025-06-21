/*  upload-form-ingredients.js
 * This file contains the JavaScript code for managing ingredients in the recipe upload form.
 * It allows users to add, remove, and validate ingredients before submission.
 * The ingredients are displayed in a list format with options to remove each item.
*/



const ingredientAmountField = document.getElementById('ingredient-amount');
const ingredientUnitField = document.getElementById('ingredient-unit');
const ingredientNameField = document.getElementById('ingredient-name');

// Function to add an ingredient to the list
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
  
  // Remove "no ingredients" message if it exists
  const noIngredientsMsg = ingredientList.querySelector('.no-items-message');
  if (noIngredientsMsg) {
    ingredientList.removeChild(noIngredientsMsg);
  }
  
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
  removeButton.classList.add('ghost-button');
  removeButton.textContent = 'Remove';
  removeButton.addEventListener('click', function () {
    ingredientList.removeChild(listItem);
    updateIngredientsDisplay();
  });

  // Create instruction buttons div and wrap the remove button
  const instructionButtonsDiv = document.createElement('div');
  instructionButtonsDiv.classList.add('instruction-buttons');
  instructionButtonsDiv.appendChild(removeButton);

  // Add elements to the list item
  listItem.textContent = ingredientText + ' ';
  listItem.appendChild(amountInput);
  listItem.appendChild(unitInput);
  listItem.appendChild(nameInput);
  listItem.appendChild(instructionButtonsDiv);

  // Add to list and clear inputs
  ingredientList.appendChild(listItem);
  ingredientAmountField.value = '';
  ingredientNameField.value = '';
  
  // update the no ingredients message 
  updateIngredientsDisplay();

  // Clear any error messages
  clearError(ingredientAmountField, document.getElementById('ingredient-amount-errormsg'));
  clearError(ingredientNameField, document.getElementById('ingredient-name-errormsg'));
}

// Helper function to update ingredients display
function updateIngredientsDisplay() {
  const ingredientList = document.getElementById('ingredient-list');
  const ingredientListEmptyMsg = document.getElementById('ingredient-list-empty');
  if (ingredientList.children.length === 0) {
    // Show "no ingredients" message
    ingredientListEmptyMsg.style.display = 'block';
  } else {
    ingredientListEmptyMsg.style.display = 'none';
  }
}

// Helper function to clear all ingredients (for edit recipe functionality)
function clearIngredients() {
  const ingredientList = document.getElementById('ingredient-list');
  ingredientList.innerHTML = '';
  updateIngredientsDisplay();
}