/* upload-form-instruction.js
    * This script handles the functionality for adding ingredients and instructions
    * to the recipe upload form. It allows users to dynamically add, remove, and validate
    * these items before submission.
*/

// Get reference to the instruction step field
const instructionStepField = document.getElementById('instruction-step');


// Function to add an instruction step to the list
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
  // Create button container
  const buttonContainer = document.createElement('div');
  buttonContainer.classList.add('instruction-buttons');

  // Create move up button
  const moveUpButton = document.createElement('button');
  moveUpButton.type = 'button';
  moveUpButton.classList.add('ghost-button');
  moveUpButton.textContent = '↑';
  moveUpButton.title = 'Move Up';
  moveUpButton.addEventListener('click', function () {
    moveInstructionUp(listItem);
  });

  // Create move down button
  const moveDownButton = document.createElement('button');
  moveDownButton.type = 'button';
  moveDownButton.classList.add('ghost-button');
  moveDownButton.textContent = '↓';
  moveDownButton.title = 'Move Down';
  moveDownButton.addEventListener('click', function () {
    moveInstructionDown(listItem);
  });

  // Create remove button
  const removeButton = document.createElement('button');
  removeButton.type = 'button';
  removeButton.classList.add('ghost-button');
  removeButton.textContent = 'Remove';
  removeButton.addEventListener('click', function () {
    instructionList.removeChild(listItem);
    // Update step numbers
    updateInstructionNumbers();
    // Update display
    updateInstructionsDisplay();
    // Update move button states
    updateMoveButtonStates();
  });

  // Add buttons to container
  buttonContainer.appendChild(moveUpButton);
  buttonContainer.appendChild(moveDownButton);
  buttonContainer.appendChild(removeButton);

  // Add elements to the list item
  const textDiv = document.createElement('div');
  textDiv.classList.add('instruction-text');
  textDiv.innerHTML = '<span class="step-number">' + stepNumber + '</span> ' + step;
  
  listItem.appendChild(textDiv);
  listItem.appendChild(stepInput);
  listItem.appendChild(buttonContainer);

  // Add to list and clear input
  instructionList.appendChild(listItem);
  instructionStepField.value = '';
  // Update the no instructions message
  updateInstructionsDisplay();

  // Update move button states
  updateMoveButtonStates();

  // Clear any error message
  clearError(instructionStepField, document.getElementById('instruction-step-errormsg'));
}

// Helper function to update instruction step numbers
function updateInstructionNumbers() {
  const instructionItems = document.querySelectorAll('.instruction-item');
  instructionItems.forEach((item, index) => {
    const stepNumberSpan = item.querySelector('.step-number');
    if (stepNumberSpan) {
      stepNumberSpan.textContent = (index + 1);
    }
  });
}

// Function to move instruction up
function moveInstructionUp(listItem) {
  const previousSibling = listItem.previousElementSibling;
  if (previousSibling) {
    const instructionList = document.getElementById('instruction-list');
    instructionList.insertBefore(listItem, previousSibling);
    updateInstructionNumbers();
    updateMoveButtonStates();
  }
}

// Function to move instruction down
function moveInstructionDown(listItem) {
  const nextSibling = listItem.nextElementSibling;
  if (nextSibling) {
    const instructionList = document.getElementById('instruction-list');
    instructionList.insertBefore(nextSibling, listItem);
    updateInstructionNumbers();
    updateMoveButtonStates();
  }
}

// Function to update move button states
function updateMoveButtonStates() {
  const instructionItems = document.querySelectorAll('.instruction-item');
  instructionItems.forEach((item, index) => {
    const moveUpButton = item.querySelector('button[title="Move Up"]');
    const moveDownButton = item.querySelector('button[title="Move Down"]');
    
    if (moveUpButton) {
      moveUpButton.disabled = (index === 0);
    }
    if (moveDownButton) {
      moveDownButton.disabled = (index === instructionItems.length - 1);
    }
  });
}

// Helper function to update instructions display
function updateInstructionsDisplay() {
  const instructionList = document.getElementById('instruction-list');
  const instructionListEmptyMsg = document.getElementById('instruction-list-empty');
  if (instructionList.children.length === 0) {
    // Show "no instructions" message
    instructionListEmptyMsg.style.display = 'block';
  } else {
    instructionListEmptyMsg.style.display = 'none';
    // Update move button states when display is updated
    updateMoveButtonStates();
  }
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

// Helper function to clear all instructions (for edit recipe functionality)
function clearInstructions() {
  const instructionList = document.getElementById('instruction-list');
  instructionList.innerHTML = '';
  updateInstructionsDisplay();
}
