/* image-upload.js
    * This file contains the JavaScript code for handling image uploads in the frontend.
    * It allows users to drag and drop images, select images from their device, and preview the selected image.
*/

//Frontend Image upload functionality
document.addEventListener('DOMContentLoaded', function() {
    const dropArea = document.getElementById('image-upload-container');
    const fileInput = document.getElementById('recipe-image');
    const previewContainer = document.getElementById('image-preview-container');
    const previewImage = document.getElementById('preview-image');
    const chooseFileBtn = document.getElementById('choose-file-btn');
    const removeImageBtn = document.getElementById('remove-image-btn');
    
    if (!dropArea || !fileInput || !previewContainer || !previewImage || !removeImageBtn) return;
    
    // Handle click on the upload area
    dropArea.addEventListener('click', function(e) {
        if (e.target !== chooseFileBtn) {
            fileInput.click();
        }
    });
    
    // Handle click on the choose file button
    chooseFileBtn.addEventListener('click', function(e) {
        e.preventDefault();
        fileInput.click();
    });
    
    // Prevent default behaviors for drag events
    ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
        dropArea.addEventListener(eventName, preventDefaults, false);
    });
    
    function preventDefaults(e) {
        e.preventDefault();
        e.stopPropagation();
    }
    
    // Highlight drop area when dragging over it
    ['dragenter', 'dragover'].forEach(eventName => {
        dropArea.addEventListener(eventName, highlight, false);
    });
    
    ['dragleave', 'drop'].forEach(eventName => {
        dropArea.addEventListener(eventName, unhighlight, false);
    });
    
    function highlight() {
        dropArea.classList.add('highlight');
    }
    
    function unhighlight() {
        dropArea.classList.remove('highlight');
    }
    
    // Handle dropped files
    dropArea.addEventListener('drop', handleDrop, false);
    
    function handleDrop(e) {
        const dt = e.dataTransfer;
        const files = dt.files;
        
        if (files.length > 0) {
            fileInput.files = files;
            handleFiles(files);
        }
    }
    
    // Handle file selection
    fileInput.addEventListener('change', function() {
        if (fileInput.files.length > 0) {
            handleFiles(fileInput.files);
        }
    });
      function handleFiles(files) {
        const file = files[0];
        // Check if file is an image in the frontend
        if (!file.type.match('image.*')) {
            alert('Please select an image file (PNG, JPG, or WEBP)');
            return;
        }
        
        // Check file size (10MB max) in the frontend
        if (file.size > 10 * 1024 * 1024) {
            alert('File size exceeds 10MB limit');
            return;
        }
        
        // Display preview
        const reader = new FileReader();
        reader.onload = function(e) {
            previewImage.src = e.target.result;
            previewContainer.style.display = 'block';
            
            // Change the upload text to show selected file name
            const uploadText = document.getElementById('upload-text');
            if (uploadText) {
                uploadText.textContent = file.name;
            }
            
            // Hide the upload container when an image is selected
            dropArea.style.display = 'none';
        }
        reader.readAsDataURL(file);
    }
    
    // Handle remove button click
    removeImageBtn.addEventListener('click', function() {
        // Clear the file input
        fileInput.value = '';
        
        // Hide the preview container
        previewContainer.style.display = 'none';
        
        // Show the upload container again
        dropArea.style.display = 'flex';
        
        // Reset the upload text
        const uploadText = document.getElementById('upload-text');
        if (uploadText) {
            uploadText.textContent = 'Drag & drop your image here';
        }
    });
});
