<?php
// controllers/AuthController.php
// Handles: Registration, Login, Logout, Remember Me

session_start();

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/User.php';

$action = $_GET['action'] ?? 'login';

// =====================
// AUTO-LOGIN via Remember Me cookie
// =====================
if (!isset($_SESSION['user_id']) && isset($_COOKIE['remember_token'])) {

    $raw_token  = $_COOKIE['remember_token'];
    $token_hash = hash('sha256', $raw_token);

    $user = getUserByRememberToken($conn, $token_hash);

    if ($user) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['name']    = $user['name'];
        $_SESSION['role']    = $user['role'];

        // Redirect automatically
        if ($user['role'] === 'admin') {
            header("Location: AdminDashboardController.php");
        } else {
            header("Location: HomeController.php");
        }
        exit;
    }
}

// =====================
// SHOW REGISTER FORM
// =====================
if ($action === 'register') {

    include __DIR__ . '/../views/auth/register.php';
}

// =====================
// SAVE NEW USER
// =====================
elseif ($action === 'store') {

    $name     = trim($_POST['name'] ?? '');
    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm  = $_POST['confirm_password'] ?? '';
    $role     = $_POST['role'] ?? 'customer';

    $errors = [];

    // =====================
    // VALIDATION
    // =====================

    if ($name === '') {
        $errors['name'] = 'Full name is required.';
    } elseif (strlen($name) < 2) {
        $errors['name'] = 'Name must be at least 2 characters.';
    }

    if ($email === '') {
        $errors['email'] = 'Email address is required.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'Please enter a valid email address.';
    } elseif (emailExists($conn, $email)) {
        $errors['email'] = 'This email is already registered.';
    }

    if ($password === '') {
        $errors['password'] = 'Password is required.';
    } elseif (strlen($password) < 8) {
        $errors['password'] = 'Password must be at least 8 characters.';
    }

    if ($confirm === '') {
        $errors['confirm'] = 'Confirm password is required.';
    } elseif ($confirm !== $password) {
        $errors['confirm'] = 'Passwords do not match.';
    }

    // Secure role check
    if (!in_array($role, ['admin', 'customer'])) {
        $role = 'customer';
    }

    // =====================
    // CREATE USER
    // =====================
    if (empty($errors)) {

        $password_hash = password_hash($password, PASSWORD_DEFAULT);

        createUser($conn, $name, $email, $password_hash, $role);

        header("Location: AuthController.php?action=login&registered=1");
        exit;

    } else {

        include __DIR__ . '/../views/auth/register.php';
    }
}

// =====================
// SHOW LOGIN FORM
// =====================
elseif ($action === 'login') {

    // Already logged in
    if (isset($_SESSION['user_id'])) {

        if ($_SESSION['role'] === 'admin') {
            header("Location: AdminDashboardController.php");
        } else {
            header("Location: HomeController.php");
        }

        exit;
    }

    include __DIR__ . '/../views/auth/login.php';
}

// =====================
// PROCESS LOGIN
// =====================
elseif ($action === 'authenticate') {

    $email       = trim($_POST['email'] ?? '');
    $password    = $_POST['password'] ?? '';
    $remember_me = isset($_POST['remember_me']);

    $errors = [];

    // Validation
    if ($email === '') {
        $errors['email'] = 'Email is required.';
    }

    if ($password === '') {
        $errors['password'] = 'Password is required.';
    }

    // =====================
    // LOGIN
    // =====================
    if (empty($errors)) {

        $user = getUserByEmail($conn, $email);

        if ($user && password_verify($password, $user['password_hash'])) {

            // Session
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['name']    = $user['name'];
            $_SESSION['role']    = $user['role'];

            // =====================
            // REMEMBER ME
            // =====================
            if ($remember_me) {

                $raw_token  = bin2hex(random_bytes(32));
                $token_hash = hash('sha256', $raw_token);

                setRememberToken($conn, $user['id'], $token_hash);

                // 30 Days Cookie
                setcookie(
                    'remember_token',
                    $raw_token,
                    time() + (30 * 24 * 60 * 60),
                    '/',
                    '',
                    false,
                    true
                );
            }

            // Redirect
            if ($user['role'] === 'admin') {
                header("Location: AdminDashboardController.php");
            } else {
                header("Location: HomeController.php");
            }

            exit;

        } else {

            $errors['general'] = 'Invalid email or password.';

            include __DIR__ . '/../views/auth/login.php';
        }

    } else {

        include __DIR__ . '/../views/auth/login.php';
    }
}

// =====================
// LOGOUT
// =====================
elseif ($action === 'logout') {

    // Clear remember token
    if (isset($_SESSION['user_id'])) {
        clearRememberToken($conn, $_SESSION['user_id']);
    }

    // Destroy session
    session_unset();
    session_destroy();

    // Delete cookie
    setcookie('remember_token', '', time() - 3600, '/');

    header("Location: AuthController.php?action=login&logged_out=1");
    exit;
}

// =====================
// INVALID ACTION
// =====================
else {

    header("Location: AuthController.php?action=login");
    exit;
}
?>