<?php
// controllers/AuthController.php
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
