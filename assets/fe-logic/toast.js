/* toas.js
 * This file contains the JavaScript code for displaying toast notifications in the frontend.
 * It provides functions to show success and error messages in a toast format.
 */
// Function to show a toast notification
function showToast(message, type) {
  //create a toast element
  const toast = document.createElement("div");
  toast.className = "toast";
  toast.id = "toast";
  toast.innerHTML = `
        <div class="toast-content">
            <div class="toast-icon ${type}">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" width="24" height="24">
                    ${
                      type === "success"
                        ? '<path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />'
                        : type === "error"
                        ? '<path stroke-linecap="round" stroke-linejoin="round" d="m9.75 9.75 4.5 4.5m0-4.5-4.5 4.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />'
                        : '<path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z" />'
                    }
                </svg>
            </div>
            <div class="toast-text">
                <h4 class="toast-title">
                    ${
                      type === "success"
                        ? 'Success'
                        : type === "error"
                        ? 'Error'
                        : 'Warning'
                    }
                </h4>
                <p class="toast-message">${message}</p>
            </div>
        </div>
        <div class="toast-close toast-icon" onclick="closeToast()">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" width="24" height="24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
            </svg>
        </div>
    `;
  document.body.appendChild(toast);
  
  
  // Show the toast
  setTimeout(() => {
    toast.classList.add("show");
  }, 100);
  // Hide the toast after 3 seconds
  setTimeout(() => {
    closeToast();
  }, 3000);
}

// Function to close the toast notification
function closeToast() {
  const toast = document.getElementById("toast");
  if (toast) {
    toast.classList.remove("show");
    toast.classList.add("hide");
    setTimeout(() => {
      toast.remove();
    }, 500); // Wait for the fade-out animation to complete
  }
}
