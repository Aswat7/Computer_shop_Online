<?php
// controllers/ProfileController.php
// Handles: View profile, Update profile info, Change password

session_start();
require_once '../config/database.php';
require_once '../models/User.php';

// Auto-login via Remember Me
if (!isset($_SESSION['user_id']) && isset($_COOKIE['remember_token'])) {
    $raw_token  = $_COOKIE['remember_token'];
    $token_hash = hash('sha256', $raw_token);
    $user_cookie = getUserByRememberToken($conn, $token_hash);
    if ($user_cookie) {
        $_SESSION['user_id'] = $user_cookie['id'];
        $_SESSION['name']    = $user_cookie['name'];
        $_SESSION['role']    = $user_cookie['role'];
    }
}

// --- AUTH GATE: must be logged in ---
if (!isset($_SESSION['user_id'])) {
    header("Location: AuthController.php?action=login");
    exit;
}

$action  = $_GET['action'] ?? 'view';
$user_id = $_SESSION['user_id'];
$errors  = [];
$success = '';

// =====================
// VIEW PROFILE
// =====================
if ($action === 'view') {
    $user = getUserById($conn, $user_id);
    include '../views/profile/view.php';
}

// =====================
// UPDATE PROFILE (name, email, picture)
// =====================
elseif ($action === 'update') {
    $name  = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');

    // PHP Validation
    if ($name === '') {
        $errors['name'] = 'Full name is required.';
    } elseif (strlen($name) < 2) {
        $errors['name'] = 'Name must be at least 2 characters.';
    }

    if ($email === '') {
        $errors['email'] = 'Email is required.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'Please enter a valid email address.';
    } elseif (emailExists($conn, $email, $user_id)) {
        $errors['email'] = 'This email is already in use by another account.';
    }

    // Handle profile picture upload
    $profile_picture = null;
    if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] === 0) {
        $allowed_types = ['image/jpeg', 'image/png'];
        $max_size      = 2 * 1024 * 1024; // 2MB
        $file_type     = mime_content_type($_FILES['profile_picture']['tmp_name']);
        $file_size     = $_FILES['profile_picture']['size'];

        if (!in_array($file_type, $allowed_types)) {
            $errors['picture'] = 'Only JPEG and PNG images are allowed.';
        } elseif ($file_size > $max_size) {
            $errors['picture'] = 'Image must be smaller than 2MB.';
        } else {
            $ext        = ($file_type === 'image/png') ? 'png' : 'jpg';
            $filename   = 'profile_' . $user_id . '_' . time() . '.' . $ext;
            $upload_dir = '../public/uploads/profiles/';
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0755, true);
            }
            $profile_picture = $upload_dir . $filename;
            move_uploaded_file($_FILES['profile_picture']['tmp_name'], $profile_picture);
        }
    }

    if (empty($errors)) {
        updateUserProfile($conn, $user_id, $name, $email, $profile_picture);
        // Update session name
        $_SESSION['name'] = $name;
        $success = 'profile_updated';
        $user    = getUserById($conn, $user_id);
        include '../views/profile/view.php';
    } else {
        $user = getUserById($conn, $user_id);
        include '../views/profile/view.php';
    }
}

// =====================
// CHANGE PASSWORD
// =====================
elseif ($action === 'change_password') {
    $current  = $_POST['current_password'] ?? '';
    $new_pass = $_POST['new_password'] ?? '';
    $confirm  = $_POST['confirm_password'] ?? '';

    $user = getUserById($conn, $user_id);

    if ($current === '') {
        $errors['current'] = 'Current password is required.';
    } elseif (!password_verify($current, $user['password_hash'])) {
        $errors['current'] = 'Current password is incorrect.';
    }

    if ($new_pass === '') {
        $errors['new_pass'] = 'New password is required.';
    } elseif (strlen($new_pass) < 8) {
        $errors['new_pass'] = 'New password must be at least 8 characters.';
    }

    if ($confirm !== $new_pass) {
        $errors['confirm'] = 'Passwords do not match.';
    }

    if (empty($errors)) {
        $new_hash = password_hash($new_pass, PASSWORD_DEFAULT);
        updateUserPassword($conn, $user_id, $new_hash);
        $success = 'password_changed';
        $user    = getUserById($conn, $user_id);
        include '../views/profile/view.php';
    } else {
        include '../views/profile/view.php';
    }
}
?>
