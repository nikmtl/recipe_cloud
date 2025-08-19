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
    
    // Update the URL anchor to reflect the current active tab
    // This allows users to bookmark or share the URL with the specific tab open
    if (window.history && window.history.replaceState) {
        const newUrl = window.location.pathname + window.location.search + '#' + tapId;
        window.history.replaceState(null, '', newUrl);
    }
}

//Open the first tap by default or the tab specified in the URL anchor
document.addEventListener('DOMContentLoaded', () => {
    // IMPORTANT: This approach handles a specific browser issue where loading a page with an 
    // anchor fragment (e.g., #tap-ingredients) can interfere with favicon and other resource loading.
    // The solution: temporarily clear the hash during initial load, then restore it after resources load.
    //
    // Why this weird approach is necessary:
    // 1. Browsers may resolve relative resource paths differently when an anchor is present
    // 2. This can cause favicons and other assets to fail loading or disappear
    // 3. By temporarily removing the hash, we ensure clean resource loading
    // 4. After everything loads, we process the original anchor and restore the URL state
    //
    // Timeline: URL with hash → temp clean URL → resources load → process anchor → restore hash URL
    
    // Store the original hash and temporarily clear it to prevent favicon issues
    const originalHash = window.location.hash.substring(1);
    
    // Temporarily clear the hash if it exists to prevent resource loading issues
    if (originalHash && window.history && window.history.replaceState) {
        const cleanUrl = window.location.pathname + window.location.search;
        window.history.replaceState(null, '', cleanUrl);
    }
    
    // Hide all tabs initially
    const allTaps = document.querySelectorAll('.tap');
    const allHeaders = document.querySelectorAll('.tap-header');
    
    // Hide all tabs and remove active class from all headers
    allTaps.forEach(tap => {
        tap.style.display = 'none';
    });
    allHeaders.forEach(header => {
        header.classList.remove('active-tap');
    });
    
    // Function to process the anchor after resources are loaded
    function processAnchor() {
        let targetTapId = null;
        let targetHeaderId = null;
        
        if (originalHash) {
            // Check if the anchor corresponds to a tab
            const targetTap = document.querySelector(`#${originalHash}`);
            if (targetTap && targetTap.classList.contains('tap')) {
                targetTapId = originalHash;
                // Find the corresponding header by looking for the header that opens this tab
                const allHeadersArray = Array.from(allHeaders);
                const correspondingHeader = allHeadersArray.find(header => {
                    const onclickAttr = header.getAttribute('onclick');
                    if (onclickAttr) {
                        const match = onclickAttr.match(/openTap\('([^']+)',\s*'([^']+)'\)/);
                        return match && match[1] === targetTapId;
                    }
                    return false;
                });
                
                if (correspondingHeader) {
                    targetHeaderId = correspondingHeader.id;
                }
            }
        }
        
        // If we found a target tab from the URL anchor, open it and restore the hash
        if (targetTapId && targetHeaderId) {
            openTap(targetTapId, targetHeaderId);
        } else {
            // Otherwise, open the first tab by default
            const firstHeader = document.querySelector('.tap-header');
            if (firstHeader) {
                // Get the onclick attribute to extract the tap ID
                const onclickAttr = firstHeader.getAttribute('onclick');
                if (onclickAttr) {
                    // Extract tapId and headerId from onclick="openTap('tap-instructions','tap-header-instructions')"
                    const match = onclickAttr.match(/openTap\('([^']+)',\s*'([^']+)'\)/);
                    if (match) {
                        const tapId = match[1];
                        const headerId = match[2];
                        // Use the existing openTap function to properly open the first tab
                        openTap(tapId, headerId);
                    } else {
                        console.warn('Could not parse onclick attribute for first tab header.');
                    }
                } else {
                    console.warn('First tab header has no onclick attribute.');
                }
            } else {
                console.warn('No tab headers found to activate by default.');
            }
        }
    }
    
    // Wait for all resources to load before processing the anchor
    if (document.readyState === 'complete') {
        // If everything is already loaded, process immediately
        setTimeout(processAnchor, 50);
    } else {
        // Wait for the window to fully load (including images, styles, etc.)
        window.addEventListener('load', () => {
            setTimeout(processAnchor, 50);
        });
    }
});