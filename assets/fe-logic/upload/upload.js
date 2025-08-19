/* upload.js
  * This file contains the general JavaScript code for the recipe upload page
  * The form validation, ingredient and instruction management, and tab navigation functionality are implemented in external files in this directory.
*/

// Add event delegation for ingredient and instruction buttons because they are dynamically created by the tab view
document.addEventListener('DOMContentLoaded', function() {
  // Initialize ingredients display on page load
  updateIngredientsDisplay();
  // Initialize instructions display on page load
  updateInstructionsDisplay();

  // Use event delegation for the add ingredient button
  document.addEventListener('click', function(event) {
    // Check if the clicked element is the add ingredient button
    if (event.target.closest('#tab-ingredients .icon-button')) {
      event.preventDefault();
      addIngredient();
    }
    
    // Check if the clicked element is the add instruction button
    if (event.target.closest('#tab-instructions .icon-button')) {
      event.preventDefault();
      addInstruction();
    }
  });
});