/* tab-view.js
    * This file contains the JavaScript code for handling tab views in the frontend.
    * It allows users to switch between different tabs by clicking on the corresponding headers.
    * It also ensures that only one tab is open at a time.
*/

// This function is called when a tab header is clicked and opens the corresponding tab
function openTab(tabId, headerId) {
    // Get the tab and header DOM elements by their IDs
    const tab = document.querySelector(`#${tabId}`);
    const otherTabs = document.querySelectorAll('.tab');

    const header = document.querySelector(`#${headerId}`);
    const otherHeaders = document.querySelectorAll('.tab-header');

    // Check if the tab and header exist
    if (!tab || !header) {
        console.error(`Tab or header with ID ${tabId} or ${headerId} not found.`);
        return;
    }

    // Close all other tabs and headers
    otherHeaders.forEach(otherHeader => {
        if (otherHeader.id !== headerId) {
            otherHeader.classList.remove('active-tab');
        }
    });
    otherTabs.forEach(otherTab => {
        if (otherTab.id !== tabId) {
            otherTab.style.display = 'none';
        }
    });

    // Activate the current header and show the corresponding tab
    header.classList.add('active-tab');
    tab.style.display = 'block';
    
    // Update the URL anchor to reflect the current active tab
    // This allows users to bookmark or share the URL with the specific tab open
    if (window.history && window.history.replaceState) {
        const newUrl = window.location.pathname + window.location.search + '#' + tabId;
        window.history.replaceState(null, '', newUrl);
    }
}

//Open the first tab by default or the tab specified in the URL anchor
document.addEventListener('DOMContentLoaded', () => {
    // IMPORTANT: This approach handles a specific browser issue where loading a page with an 
    // anchor fragment (e.g., #tab-ingredients) can interfere with favicon and other resource loading.
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
    const allTabs = document.querySelectorAll('.tab');
    const allHeaders = document.querySelectorAll('.tab-header');
    
    // Hide all tabs and remove active class from all headers
    allTabs.forEach(tab => {
        tab.style.display = 'none';
    });
    allHeaders.forEach(header => {
        header.classList.remove('active-tab');
    });
    
    // Function to process the anchor after resources are loaded
    function processAnchor() {
        let targetTabId = null;
        let targetHeaderId = null;
        
        if (originalHash) {
            // Check if the anchor corresponds to a tab
            const targetTab = document.querySelector(`#${originalHash}`);
            if (targetTab && targetTab.classList.contains('tab')) {
                targetTabId = originalHash;
                // Find the corresponding header by looking for the header that opens this tab
                const allHeadersArray = Array.from(allHeaders);
                const correspondingHeader = allHeadersArray.find(header => {
                    const onclickAttr = header.getAttribute('onclick');
                    if (onclickAttr) {
                        const match = onclickAttr.match(/openTab\('([^']+)',\s*'([^']+)'\)/);
                        return match && match[1] === targetTabId;
                    }
                    return false;
                });
                
                if (correspondingHeader) {
                    targetHeaderId = correspondingHeader.id;
                }
            }
        }
        
        // If we found a target tab from the URL anchor, open it and restore the hash
        if (targetTabId && targetHeaderId) {
            openTab(targetTabId, targetHeaderId);
        } else {
            // Otherwise, open the first tab by default
            const firstHeader = document.querySelector('.tab-header');
            if (firstHeader) {
                // Get the onclick attribute to extract the tab ID
                const onclickAttr = firstHeader.getAttribute('onclick');
                if (onclickAttr) {
                    // Extract tabId and headerId from onclick="openTab('tab-instructions','tab-header-instructions')"
                    const match = onclickAttr.match(/openTab\('([^']+)',\s*'([^']+)'\)/);
                    if (match) {
                        const tabId = match[1];
                        const headerId = match[2];
                        // Use the existing openTab function to properly open the first tab
                        openTab(tabId, headerId);
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