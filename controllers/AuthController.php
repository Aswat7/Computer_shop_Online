<?php
// controllers/AuthController.php
<<<<<<< HEAD
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
=======
class AuthController {
    private $conn; private $users;
    public function __construct($conn) {
        $this->conn  = $conn;
        $this->users = new User($conn);
    }

    public function register() {
        $errors = []; $name = ''; $email = '';
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            csrf_check_form();
            $name  = trim($_POST['name']  ?? '');
            $email = trim($_POST['email'] ?? '');
            $pass  = $_POST['password']   ?? '';

            if (!v_len($name, 2, 100))  $errors[] = 'Name must be 2-100 chars.';
            if (!v_email($email))       $errors[] = 'Invalid email.';
            if (!v_password($pass))     $errors[] = 'Password must be 8+ chars and include a letter and a number.';

            if (!$errors) {
                if ($this->users->emailExists($email)) {
                    $errors[] = 'Email already registered.';
                } else {
                    $hash = password_hash($pass, PASSWORD_DEFAULT);
                    $this->users->create($name, $email, $hash, 'customer');
                    header('Location: /auth/login.php?registered=1'); exit;
                }
            }
        }
        view('auth/register', ['errors' => $errors, 'name' => $name, 'email' => $email]);
    }

    public function login() {
        $errors = []; $email = '';
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            csrf_check_form();
            $email    = trim($_POST['email'] ?? '');
            $pass     = $_POST['password']    ?? '';
            $remember = !empty($_POST['remember']);

            if (!v_email($email) || $pass === '') {
                $errors[] = 'Enter a valid email and password.';
            } else {
                $u = $this->users->findByEmail($email);
                if (!$u || !password_verify($pass, $u['password_hash'])) {
                    $errors[] = 'Invalid credentials.';
                } else {
                    session_regenerate_id(true);
                    $_SESSION['user_id'] = $u['id'];
                    $_SESSION['name']    = $u['name'];
                    $_SESSION['email']   = $u['email'];
                    $_SESSION['role']    = $u['role'];

                    if ($remember) {
                        $token = bin2hex(random_bytes(32));
                        $hash  = password_hash($token, PASSWORD_DEFAULT);
                        $exp   = date('Y-m-d H:i:s', time() + 60 * 60 * 24 * 30);
                        $this->users->saveRememberToken($u['id'], $hash, $exp);
                        setcookie('remember', $u['id'] . ':' . $token, [
                            'expires' => time() + 60 * 60 * 24 * 30,
                            'path'    => '/', 'httponly' => true, 'samesite' => 'Lax',
                        ]);
                    }
                    header('Location: /index.php'); exit;
                }
            }
        }
        view('auth/login', ['errors' => $errors, 'email' => $email]);
    }

    public function logout() {
        if (!empty($_SESSION['user_id'])) {
            $this->users->clearRememberTokens((int)$_SESSION['user_id']);
        }
        setcookie('remember', '', ['expires' => time() - 3600, 'path' => '/']);
        $_SESSION = [];
        session_destroy();
        header('Location: /index.php'); exit;
    }
}
>>>>>>> origin/feature/task4-22-49881-3
