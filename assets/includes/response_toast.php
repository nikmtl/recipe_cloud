<?php 
/* response_toast.php
    * This file is used to display generic response messages in a toast notification format.
    * It is included in various pages to show general success or error messages.
    * It creates 3 types of toasts: success, error, and warning.
    * Session errors are handled separately by other components.
 */

// Simple function to display generic toast messages based on HTTP response codes
function displayToast() {
    // Check if there's a response code in session first, fallback to actual HTTP response code
    $responseCode = isset($_SESSION['response_code']) ? $_SESSION['response_code'] : null;
    $toastMessage = '';
    $toastType = '';
    
    // Define generic messages based on HTTP response codes
    switch ($responseCode) {
        // Success codes
        case 200:
            $toastMessage = 'Operation completed successfully!';
            $toastType = 'success';
            break;
        case 201:
            $toastMessage = 'Created successfully!';
            $toastType = 'success';
            break;
            
        // Client error codes
        case 400:
            $toastMessage = 'Invalid request. Please check your input.';
            $toastType = 'error';
            break;
        case 401:
            $toastMessage = 'Authentication failed. Please check your credentials.';
            $toastType = 'error';
            break;
        case 403:
            $toastMessage = 'You do not have permission to perform this action.';
            $toastType = 'error';
            break;
        case 404:
            $toastMessage = 'The requested resource was not found.';
            $toastType = 'error';
            break;
        case 409:
            $toastMessage = 'This action conflicts with existing data.';
            $toastType = 'warning';
            break;
        case 422:
            $toastMessage = 'Please correct the validation errors and try again.';
            $toastType = 'warning';
            break;
            
        // Server error codes
        case 500:
            $toastMessage = 'A server error occurred. Please try again later.';
            $toastType = 'error';
            break;
            
        default:
            // Don't show toast for other response codes
            return;
    }
    
    // Only show toast if we have a message
    if (!empty($toastMessage)) {
        echo "<script>
            document.addEventListener('DOMContentLoaded', function() {
                showToast('" . addslashes($toastMessage) . "', '" . $toastType . "');
            });
        </script>";
    }
    
    // Clear the response code from session after displaying
    if (isset($_SESSION['response_code'])) {
        unset($_SESSION['response_code']);
    }
}

