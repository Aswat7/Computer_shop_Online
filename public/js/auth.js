// public/js/auth.js
// JavaScript client-side validation for:
// 1. Registration form
// 2. Login form
// 3. Profile update form
// 4. Change password form

// -------------------------------------------------------
// Helper: show an error message in a DOM element
// -------------------------------------------------------
function showErr(id, msg) {
    var el = document.getElementById(id);
    if (el) el.textContent = msg;
}

function clearErr(id) {
    var el = document.getElementById(id);
    if (el) el.textContent = '';
}

// -------------------------------------------------------
// REGISTRATION FORM VALIDATION
// -------------------------------------------------------
function validateRegisterForm() {
    var valid = true;

    // Clear all errors
    ['name-error', 'email-error', 'password-error', 'confirm-error', 'role-error'].forEach(clearErr);

    var name    = document.getElementById('name').value.trim();
    var email   = document.getElementById('email').value.trim();
    var pass    = document.getElementById('password').value;
    var confirm = document.getElementById('confirm_password').value;
    var role    = document.getElementById('role').value;

    if (name === '') {
        showErr('name-error', 'Full name is required.');
        valid = false;
    } else if (name.length < 2) {
        showErr('name-error', 'Name must be at least 2 characters.');
        valid = false;
    }

    if (email === '') {
        showErr('email-error', 'Email address is required.');
        valid = false;
    } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
        showErr('email-error', 'Please enter a valid email address.');
        valid = false;
    }

    if (pass === '') {
        showErr('password-error', 'Password is required.');
        valid = false;
    } else if (pass.length < 8) {
        showErr('password-error', 'Password must be at least 8 characters.');
        valid = false;
    }

    if (confirm === '') {
        showErr('confirm-error', 'Please confirm your password.');
        valid = false;
    } else if (confirm !== pass) {
        showErr('confirm-error', 'Passwords do not match.');
        valid = false;
    }

    if (role === '') {
        showErr('role-error', 'Please select an account type.');
        valid = false;
    }

    return valid;
}

// -------------------------------------------------------
// LOGIN FORM VALIDATION
// -------------------------------------------------------
function validateLoginForm() {
    var valid = true;

    ['email-error', 'password-error'].forEach(clearErr);

    var email = document.getElementById('email').value.trim();
    var pass  = document.getElementById('password').value;

    if (email === '') {
        showErr('email-error', 'Email is required.');
        valid = false;
    } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
        showErr('email-error', 'Please enter a valid email address.');
        valid = false;
    }

    if (pass === '') {
        showErr('password-error', 'Password is required.');
        valid = false;
    }

    return valid;
}

// -------------------------------------------------------
// PROFILE UPDATE FORM VALIDATION
// -------------------------------------------------------
function validateProfileForm() {
    var valid = true;

    ['name-error', 'email-error', 'picture-error'].forEach(clearErr);

    var name  = document.getElementById('name').value.trim();
    var email = document.getElementById('email').value.trim();

    if (name === '') {
        showErr('name-error', 'Full name is required.');
        valid = false;
    } else if (name.length < 2) {
        showErr('name-error', 'Name must be at least 2 characters.');
        valid = false;
    }

    if (email === '') {
        showErr('email-error', 'Email is required.');
        valid = false;
    } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
        showErr('email-error', 'Please enter a valid email address.');
        valid = false;
    }

    return valid;
}

// -------------------------------------------------------
// PROFILE PICTURE VALIDATION (file type + size)
// -------------------------------------------------------
function validateProfilePicture(input) {
    clearErr('picture-error');

    if (input.files.length === 0) return;

    var file         = input.files[0];
    var allowedTypes = ['image/jpeg', 'image/png'];
    var maxSize      = 2 * 1024 * 1024; // 2MB

    if (allowedTypes.indexOf(file.type) === -1) {
        showErr('picture-error', 'Only JPEG and PNG images are allowed.');
        input.value = '';
        return;
    }

    if (file.size > maxSize) {
        showErr('picture-error', 'Image must be smaller than 2MB.');
        input.value = '';
    }
}

// -------------------------------------------------------
// CHANGE PASSWORD FORM VALIDATION
// -------------------------------------------------------
function validatePasswordForm() {
    var valid = true;

    ['current-error', 'new-pass-error', 'confirm-error'].forEach(clearErr);

    var current = document.getElementById('current_password').value;
    var newPass  = document.getElementById('new_password').value;
    var confirm  = document.getElementById('confirm_password').value;

    if (current === '') {
        showErr('current-error', 'Current password is required.');
        valid = false;
    }

    if (newPass === '') {
        showErr('new-pass-error', 'New password is required.');
        valid = false;
    } else if (newPass.length < 8) {
        showErr('new-pass-error', 'New password must be at least 8 characters.');
        valid = false;
    }

    if (confirm === '') {
        showErr('confirm-error', 'Please confirm the new password.');
        valid = false;
    } else if (confirm !== newPass) {
        showErr('confirm-error', 'Passwords do not match.');
        valid = false;
    }

    return valid;
}
