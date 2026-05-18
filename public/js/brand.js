// public/js/brand.js
// JavaScript validation for the brand create and edit forms

function validateBrandForm() {
    var name          = document.getElementById('name').value.trim();
    var categoryId    = document.getElementById('category_id').value;
    var nameError     = document.getElementById('name-error');
    var categoryError = document.getElementById('category-error');
    var valid         = true;

    // Reset errors
    nameError.textContent     = '';
    categoryError.textContent = '';

    if (name === '') {
        nameError.textContent = 'Brand name is required.';
        valid = false;
    }

    if (categoryId === '') {
        categoryError.textContent = 'Please select a category.';
        valid = false;
    }

    return valid;
}
