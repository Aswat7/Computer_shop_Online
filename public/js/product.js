// public/js/product.js
// ===============================================
// PRODUCT AJAX + VALIDATION
// ===============================================

// -------------------------------------------------------
// AJAX: Load brands when category changes
// -------------------------------------------------------
function loadBrands(categoryId) {

    var brandSelect = document.getElementById('brand_id');

    // Reset if no category selected
    if (categoryId === '') {

        brandSelect.innerHTML =
            '<option value="">— Select Category First —</option>';

        return;
    }

    // Loading text
    brandSelect.innerHTML =
        '<option value="">Loading brands...</option>';

    // AJAX request
    var xhr = new XMLHttpRequest();

    xhr.open(
        "GET",
        "/Task1/controllers/AdminProductController.php?action=getBrands&category_id=" + categoryId,
        true
    );

    xhr.onload = function () {

        if (xhr.status === 200) {

            try {

                var data = JSON.parse(xhr.responseText);

                // Clear dropdown
                brandSelect.innerHTML = '';

                // No brands found
                if (data.length === 0) {

                    brandSelect.innerHTML =
                        '<option value="">No brands found</option>';

                    return;
                }

                // Default option
                brandSelect.innerHTML =
                    '<option value="">— Select Brand —</option>';

                // Add brands
                for (var i = 0; i < data.length; i++) {

                    brandSelect.innerHTML +=
                        '<option value="' + data[i].id + '">' +
                        data[i].name +
                        '</option>';
                }

            } catch (e) {

                console.log(e);

                brandSelect.innerHTML =
                    '<option value="">Error loading brands</option>';
            }
        }
    };

    xhr.onerror = function () {

        brandSelect.innerHTML =
            '<option value="">Server error</option>';
    };

    xhr.send();
}

// -------------------------------------------------------
// IMAGE VALIDATION
// -------------------------------------------------------
function validateImageInput(input) {

    var imageError = document.getElementById('image-error');

    imageError.textContent = '';

    if (input.files.length === 0) return;

    var file = input.files[0];

    var allowedTypes = [
        'image/jpeg',
        'image/png'
    ];

    var maxSize = 2 * 1024 * 1024;

    // File type check
    if (allowedTypes.indexOf(file.type) === -1) {

        imageError.textContent =
            'Only JPEG and PNG images are allowed.';

        input.value = '';

        return;
    }

    // File size check
    if (file.size > maxSize) {

        imageError.textContent =
            'Image must be smaller than 2MB.';

        input.value = '';
    }
}

// -------------------------------------------------------
// FORM VALIDATION
// -------------------------------------------------------
function validateProductForm() {

    var valid = true;

    // Helper
    function showError(id, msg) {

        document.getElementById(id).textContent = msg;

        valid = false;
    }

    // Clear old errors
    var errorIds = [
        'name-error',
        'desc-error',
        'price-error',
        'stock-error',
        'category-error',
        'brand-error',
        'image-error'
    ];

    for (var i = 0; i < errorIds.length; i++) {

        document.getElementById(errorIds[i]).textContent = '';
    }

    // Get values
    var name =
        document.getElementById('name').value.trim();

    var desc =
        document.getElementById('description').value.trim();

    var price =
        parseFloat(document.getElementById('price').value);

    var stock =
        parseInt(document.getElementById('stock').value);

    var categoryId =
        document.getElementById('category_id').value;

    var brandId =
        document.getElementById('brand_id').value;

    // Validation
    if (name === '') {

        showError(
            'name-error',
            'Product name is required.'
        );
    }

    if (desc === '') {

        showError(
            'desc-error',
            'Description is required.'
        );
    }

    if (isNaN(price) || price <= 0) {

        showError(
            'price-error',
            'Price must be greater than 0.'
        );
    }

    if (isNaN(stock) || stock < 0) {

        showError(
            'stock-error',
            'Stock must be 0 or more.'
        );
    }

    if (categoryId === '') {

        showError(
            'category-error',
            'Please select a category.'
        );
    }

    if (brandId === '') {

        showError(
            'brand-error',
            'Please select a brand.'
        );
    }

    return valid;
}