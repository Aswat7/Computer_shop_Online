// public/js/category.js
// JavaScript validation for the category create and edit forms

function validateCategoryForm() {
    var name      = document.getElementById('name').value.trim();
    var nameError = document.getElementById('name-error');

    // Reset error first
    nameError.textContent = '';

    if (name === '') {
        nameError.textContent = 'Category name is required.';
        document.getElementById('name').focus();
        return false; // Stop form from submitting
    }

    if (name.length < 2) {
        nameError.textContent = 'Category name must be at least 2 characters.';
        return false;
    }

    return true; // Allow form to submit
}
