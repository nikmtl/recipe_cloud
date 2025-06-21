/* load-more-recipes.js
    * This script handles the "Load More" functionality for recipes.
    * It fetches additional recipes via AJAX and appends them to the existing list.
*/
document.addEventListener('DOMContentLoaded', function () {
    const loadMoreBtn = document.getElementById('load-more-btn');
    const recipesContainer = document.querySelector('.recipes');

    if (loadMoreBtn) {
        loadMoreBtn.addEventListener('click', function (e) {
            e.preventDefault();

            const btn = e.target;
            const originalText = btn.textContent;

            // Show loading state
            btn.textContent = 'Loading...';
            btn.disabled = true;
            btn.style.opacity = '0.6';
            // Get current parameters
            const urlParams = new URLSearchParams(window.location.search);
            // Use stored offset from button or URL param as fallback
            const currentOffset = parseInt(btn.dataset.currentOffset || urlParams.get('offset') || '0');
            const newOffset = currentOffset + 8; // Assuming limit is 8
            // Build request URL
            const requestUrl = new URL('be-logic/load_more_recipes.php', window.location.origin + window.location.pathname.replace('recipes.php', ''));
            requestUrl.searchParams.set('search', urlParams.get('search') || '');
            requestUrl.searchParams.set('sort', urlParams.get('sort') || 'newest');
            requestUrl.searchParams.set('category', urlParams.get('category') || '');
            requestUrl.searchParams.set('offset', newOffset.toString());

            // Make AJAX request
            fetch(requestUrl.toString(), {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                }).then(data => {
                    // Temporary debug logging
                    console.log('AJAX Response Debug:', data.debug_info);
                    if (data.success && data.recipes.length > 0) {
                        // Append new recipes to the container
                        data.recipes.forEach(recipeHtml => {
                            recipesContainer.insertAdjacentHTML('beforeend', recipeHtml);
                        });

                        // Store the offset 
                        btn.dataset.currentOffset = newOffset.toString();

                        // Hide button if no more recipes
                        if (!data.has_more) {
                            btn.parentElement.style.display = 'none';
                        } else {
                            // Reset button state
                            btn.textContent = originalText;
                            btn.disabled = false;
                            btn.style.opacity = '1';
                        }
                    } else {
                        // No more recipes or error
                        btn.parentElement.style.display = 'none';
                    }
                })
                .catch(error => {
                    console.error('Error loading more recipes:', error);
                    // Reset button state on error
                    btn.textContent = originalText;
                    btn.disabled = false;
                    btn.style.opacity = '1';

                    // Show error message
                    const errorMsg = document.createElement('p');
                    errorMsg.textContent = 'Error loading more recipes. Please try again.';
                    errorMsg.style.color = 'red';
                    errorMsg.style.textAlign = 'center';
                    btn.parentElement.appendChild(errorMsg);

                    // Remove error message after 3 seconds
                    setTimeout(() => {
                        if (errorMsg.parentElement) {
                            errorMsg.parentElement.removeChild(errorMsg);
                        }
                    }, 3000);
                });
        });
    }
});
