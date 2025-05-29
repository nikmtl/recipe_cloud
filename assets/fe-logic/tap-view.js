/* tap-view.js
    * This file contains the JavaScript code for handling tap views in the frontend.
    * It allows users to switch between different taps by clicking on the corresponding headers.
    * It also ensures that only one tap is open at a time.
*/

// This function is called when a tap header is clicked and opens the corresponding tap
function openTap(tapId, headerId) {
    // Get the tap and header DOM elements by their IDs
    const tap = document.querySelector(`#${tapId}`);
    const otherTaps = document.querySelectorAll('.tap');

    const header = document.querySelector(`#${headerId}`);
    const otherHeaders = document.querySelectorAll('.tap-header');

    // Check if the tap and header exist
    if (!tap || !header) {
        console.error(`Tap or header with ID ${tapId} or ${headerId} not found.`);
        return;
    }

    // Close all other taps and headers
    otherHeaders.forEach(otherHeader => {
        if (otherHeader.id !== headerId) {
            otherHeader.classList.remove('active-tap');
        }
    });
    otherTaps.forEach(otherTap => {
        if (otherTap.id !== tapId) {
            otherTap.style.display = 'none';
        }
    });

    // Activate the current header and show the corresponding tap
    header.classList.add('active-tap');
    tap.style.display = 'block';
}