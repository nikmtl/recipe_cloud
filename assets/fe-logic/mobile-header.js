/* mobile-header.js
    * This file contains the JavaScript code for handling the mobile menu functionality.
    * It toggles the mobile menu when the hamburger icon is clicked and closes it when the close button is clicked.
    * It also handles the resizing of the window to close the menu if the window is resized to a width greater than 700px.
    * It also closes the menu if a click is detected outside of the menu.
*/

// Get the necessary DOM elements for the mobile menu functionality
const menu = document.querySelector('.mobile-nav');
const hamburgerIcon = document.querySelector('#hamburger-icon');
const mainContent = document.querySelector('main');
const contentOverlay = document.querySelector('.mobile-nav-background');
const hamburgerSVG = `
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-menu h-6 w-6">
                        <path d="M3 12h18"></path>
                        <path d="M3 6h18"></path>
                        <path d="M3 18h18"></path>
                    </svg>`;
const closeSVG = `
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-x h-6 w-6">
                        <path d="M18 6L6 18"></path>
                        <path d="M6 6l12 12"></path>
                    </svg>`;


/*  This function is called when the hamburger icon is clicked and toggles the mobile menu
    It checks the current display style of the menu and toggles it accordingly
    If the menu is displayed, it closes it; if it's not displayed, it opens it
    If the menu is not found, it does nothing */
function toggleMobileMenu() {
     if (menu) {
        const displayStyle = window.getComputedStyle(menu).display;
        if (displayStyle === 'flex') {
            closeMobileMenu();
        } else if (displayStyle === 'none') {
            openMobileMenu();
        } else {
            closeMobileMenu();
        }
    }
}

// This function is called when the close button is clicked and closes the mobile menu and handles the view
function closeMobileMenu() {
    if (menu) {
        menu.style.display = 'none';
        hamburgerIcon.innerHTML = hamburgerSVG;
        contentOverlay.style.display = 'none';
        removeMenuEventListeners();
        document.body.style.overflow = ''; // Restore page scrolling when menu is closed
    }
}

// This function is called when the hamburger icon is clicked and opens the mobile menu and handles the view
function openMobileMenu() {
    if (menu) {
        menu.style.display = 'flex';
        hamburgerIcon.innerHTML = closeSVG;
        contentOverlay.style.display = 'block';
        addMenuEventListeners();
        document.body.style.overflow = 'hidden'; // Prevent page scrolling when menu is open
    }
}

// Update to toggle event listeners based on menu state
function addMenuEventListeners() {
    window.addEventListener('resize', handleResize); // Attach to window instead of document
    document.addEventListener('click', handleOutsideClick);
}

function removeMenuEventListeners() {
    window.removeEventListener('resize', handleResize); // Detach from window instead of document
    document.removeEventListener('click', handleOutsideClick);
}

// Add event listener to the hamburger icon to toggle the mobile menu
function handleResize() {
    if (window.innerWidth > 700) {
        closeMobileMenu();
    }
}

// Add event listener to the hamburger icon to toggle the mobile menu
function handleOutsideClick(event) {
    if (menu) {
        if (event.clientY > menu.getBoundingClientRect().bottom && !menu.contains(event.target)) {
            closeMobileMenu();
        }
    }
}


