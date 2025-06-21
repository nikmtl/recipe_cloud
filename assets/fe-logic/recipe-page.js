/* recipe-page.js
    * This file contains the JavaScript code for handling the recipe page  frontend-functionality.
    Sections in this file:
    * 1. Default tab functionality
    * 2. Copy link functionality
    * 3. Star rating functionality and review functionality
    * 4. Ingredient amount calculation based on servings
    * 5. Save/unsave recipe functionality
    * 6. Delete recipe functionality
*/


document.addEventListener('DOMContentLoaded', function () { // Ensure the DOM is fully loaded before executing the script
    /* 1. Default tab functionality*/
    openTap('tap-instructions', 'tap-header-instructions')


    /* 2. Copy link functionality */
    document.getElementById('copy-link-btn').addEventListener('click', function () {
        var link = window.location.href;
        navigator.clipboard.writeText(link).then(function () {
            var button = document.getElementById('copy-link-btn');
            button.style.backgroundColor = '#22c55e'; // Change button color to indicate success
            setTimeout(function () {
                button.style.backgroundColor = ''; // Reset button color after 1.5 seconds
            }, 1500);
        });
    });


    /* 3. Star rating functionality and review functionality */
    const starLabels = document.querySelectorAll('.star-label');
    const starInputs = document.querySelectorAll('input[name="rating"]');
    const submitBtn = document.getElementById('submit-review-btn');

    if (starLabels.length > 0 && starInputs.length > 0 && submitBtn) {
        // Initialize display based on existing rating
        const checkedInput = document.querySelector('input[name="rating"]:checked');
        if (checkedInput) {
            updateStarDisplay(parseInt(checkedInput.value));
            enableSubmitButton();
        } else {
            disableSubmitButton();
        }

        starLabels.forEach((label, index) => {
            label.addEventListener('click', function () {
                // Set the corresponding radio button as checked
                starInputs[index].checked = true;
                updateStarDisplay(index + 1);
                enableSubmitButton();
            });
        });

        function updateStarDisplay(rating) {
            starLabels.forEach((label, index) => {
                if (index < rating) {
                    label.style.color = '#ffd700';
                } else {
                    label.style.color = '#ddd';
                }
            });
        }

        function enableSubmitButton() {
            submitBtn.disabled = false;
        }

        function disableSubmitButton() {
            submitBtn.disabled = true;
        }
    }

    // Remove review functionality
    const removeReviewBtn = document.getElementById('remove-review-btn');
    if (removeReviewBtn) {
        removeReviewBtn.addEventListener('click', function () {
            // Create a form to submit the delete request
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = 'be-logic/delete_review.php';

            // Add recipe_id hidden input
            const recipeIdInput = document.createElement('input');
            recipeIdInput.type = 'hidden';
            recipeIdInput.name = 'recipe_id';
            recipeIdInput.value = document.querySelector('input[name="recipe_id"]').value;

            form.appendChild(recipeIdInput);
            document.body.appendChild(form);
            form.submit();
        });
    }

    /* 4. Ingredient amount calculation based on servings */
    // Function to calculate the ingredient amount based on the number of servings
    // This is done in the frontend to provide immediate feedback to the user
    const servingsInput = document.getElementById('servings-input');
    const decreaseBtn = document.getElementById('decrease-servings');
    const increaseBtn = document.getElementById('increase-servings');
    const ingredientsList = document.getElementById('ingredients-list');
    const originalServingsElement = document.getElementById('original-servings');
    // Check if all required elements exist before proceeding
    if (servingsInput && decreaseBtn && increaseBtn && ingredientsList && originalServingsElement) {        const originalServings = originalServingsElement.value; 
        
        function updateIngredients() {
            const currentServings = parseInt(servingsInput.value);
            const ratio = currentServings / originalServings;

            const ingredientItems = ingredientsList.querySelectorAll('li');

            ingredientItems.forEach(item => {
                const originalAmountStr = item.dataset.originalAmount;
                
                // Only perform calculation if ingredient has a valid amount
                if (originalAmountStr && originalAmountStr.trim() !== '' && !isNaN(parseFloat(originalAmountStr))) {
                    const originalAmount = parseFloat(originalAmountStr);
                    let newAmount = originalAmount * ratio;

                    // Format the amount nicely
                    if (newAmount < 1 && newAmount > 0) {
                        // Convert to fraction for small amounts
                        newAmount = formatAsFraction(newAmount);
                    } else if (newAmount % 1 === 0) {
                        // Whole number
                        newAmount = Math.round(newAmount).toString();
                    } else {
                        // Decimal, round to 2 places
                        newAmount = newAmount.toFixed(2);
                        // Remove trailing zeros
                        newAmount = parseFloat(newAmount).toString();
                    }

                    const amountElement = item.querySelector('.ingredient-amount');
                    if (amountElement) {
                        amountElement.textContent = newAmount;
                    }
                }
                // If no valid amount, skip calculation - ingredient will display as-is
            });
        }

        function formatAsFraction(decimal) {
            const fractions = {
                0.125: '1/8',
                0.25: '1/4',
                0.33: '1/3',
                0.375: '3/8',
                0.5: '1/2',
                0.625: '5/8',
                0.66: '2/3',
                0.67: '2/3',
                0.75: '3/4',
                0.875: '7/8'
            };

            // Round to nearest common fraction
            for (let [dec, frac] of Object.entries(fractions)) {
                if (Math.abs(decimal - parseFloat(dec)) < 0.05) {
                    return frac;
                }
            }

            // If no common fraction found, return rounded decimal
            return decimal.toFixed(2);
        }
        // Event listeners
        decreaseBtn.addEventListener('click', function () {
            let currentValue = parseInt(servingsInput.value);
            if (currentValue > 1) {
                servingsInput.value = currentValue - 1;
                updateIngredients();
            }
        });

        increaseBtn.addEventListener('click', function () {
            let currentValue = parseInt(servingsInput.value);
            if (currentValue < 50) {
                servingsInput.value = currentValue + 1;
                updateIngredients();
            }
        });

        servingsInput.addEventListener('input', function () {
            let value = parseInt(this.value);
            if (value >= 1 && value <= 50) {
                updateIngredients();
            }
        });
    } else {
        // Servings elements not found - functionality will be disabled
    }

    /* 5. Save/unsave recipe functionality */
    // Handle save recipe button
    const saveBtn = document.getElementById('save-recipe-btn');
    const unsaveBtn = document.getElementById('unsave-recipe-btn');
    
    if (saveBtn) {
        saveBtn.addEventListener('click', function() {
            // Get recipe ID from the URL parameters
            const urlParams = new URLSearchParams(window.location.search);
            const recipeId = urlParams.get('id');
            
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = 'be-logic/save_recipe.php';
            
            const recipeIdInput = document.createElement('input');
            recipeIdInput.type = 'hidden';
            recipeIdInput.name = 'recipe_id';
            recipeIdInput.value = recipeId;
            
            const actionInput = document.createElement('input');
            actionInput.type = 'hidden';
            actionInput.name = 'action';
            actionInput.value = 'save';
            
            form.appendChild(recipeIdInput);
            form.appendChild(actionInput);
            document.body.appendChild(form);
            form.submit();
        });
    }
    
    if (unsaveBtn) {
        unsaveBtn.addEventListener('click', function() {
            // Get recipe ID from the URL parameters
            const urlParams = new URLSearchParams(window.location.search);
            const recipeId = urlParams.get('id');
            
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = 'be-logic/save_recipe.php';
            
            const recipeIdInput = document.createElement('input');
            recipeIdInput.type = 'hidden';
            recipeIdInput.name = 'recipe_id';
            recipeIdInput.value = recipeId;
            
            const actionInput = document.createElement('input');
            actionInput.type = 'hidden';
            actionInput.name = 'action';
            actionInput.value = 'unsave';
            
            form.appendChild(recipeIdInput);
            form.appendChild(actionInput);
            document.body.appendChild(form);
            form.submit();
        });
    }

    /* 6. Delete recipe functionality */
    const deleteRecipeBtn = document.getElementById('delete-recipe-btn');
    if (deleteRecipeBtn) {
        deleteRecipeBtn.addEventListener('click', function () {
            if (confirm('Are you sure you want to delete this recipe? This action cannot be undone.')) {
                // Create a form to submit the delete request
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = 'be-logic/delete_recipe.php';

                // Add recipe_id hidden input
                const recipeIdInput = document.createElement('input');
                recipeIdInput.type = 'hidden';
                recipeIdInput.name = 'recipe_id';
                recipeIdInput.value = document.querySelector('input[name="recipe_id"]').value;

                form.appendChild(recipeIdInput);
                document.body.appendChild(form);
                form.submit();
            }
        });
    }
});