<?php
<<<<<<< HEAD
<<<<<<< HEAD

$host = "localhost";
$user = "root";
$pass = "";
$db   = "computer_shop"; // change if your DB name is different

$conn = mysqli_connect($host, $user, $pass, $db);

if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}
=======
=======
// config/database.php — DB connection, session, CSRF + auth helpers
// Database name: computer_shop (do NOT alter the shared schema)

if (session_status() === PHP_SESSION_NONE) {
    session_set_cookie_params([
        'lifetime' => 0,
        'path'     => '/',
        'httponly' => true,
        'samesite' => 'Lax',
    ]);
    session_start();
}

>>>>>>> origin/feature/task4-22-49881-3
$servername = "localhost";
$username   = "root";
$password   = "";
$dbname     = "computer_shop";

$conn = mysqli_connect($servername, $username, $password, $dbname);
<<<<<<< HEAD

if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}
?>
>>>>>>> origin/feature/task3-22-46877-1
=======
if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}
$conn->set_charset('utf8mb4');

/* ---------- Generic helpers ---------- */
function e($s) { return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8'); }

function json_out($data, $code = 200) {
    http_response_code($code);
    header('Content-Type: application/json');
    echo json_encode($data);
    exit;
}

function view($path, $vars = []) {
    extract($vars, EXTR_SKIP);
    require __DIR__ . '/../views/' . $path . '.php';
}

/* ---------- CSRF ---------- */
function csrf_token() {
    if (empty($_SESSION['csrf'])) $_SESSION['csrf'] = bin2hex(random_bytes(32));
    return $_SESSION['csrf'];
}
function csrf_field() {
    return '<input type="hidden" name="csrf" value="' . e(csrf_token()) . '">';
}
function csrf_check() {
    $t = $_POST['csrf'] ?? $_SERVER['HTTP_X_CSRF_TOKEN'] ?? '';
    if (!hash_equals(csrf_token(), $t)) {
        json_out(['error' => 'Invalid CSRF token'], 419);
    }
}
function csrf_check_form() {
    $t = $_POST['csrf'] ?? '';
    if (!hash_equals(csrf_token(), $t)) {
        http_response_code(419);
        exit('Invalid CSRF token');
    }
}

/* ---------- Auth ---------- */
function current_user() {
    return empty($_SESSION['user_id']) ? null : [
        'id'    => $_SESSION['user_id'],
        'name'  => $_SESSION['name']  ?? '',
        'email' => $_SESSION['email'] ?? '',
        'role'  => $_SESSION['role']  ?? '',
    ];
}
function is_api_request() {
    return str_contains($_SERVER['REQUEST_URI'] ?? '', '/api/');
}
function require_login() {
    if (empty($_SESSION['user_id'])) {
        if (is_api_request()) json_out(['error' => 'Login required'], 401);
        header('Location: /auth/login.php'); exit;
    }
}
function require_admin() {
    require_login();
    if (($_SESSION['role'] ?? '') !== 'admin') {
        if (is_api_request()) json_out(['error' => 'Admin only'], 403);
        http_response_code(403); exit('Admin only');
    }
}
function require_customer() {
    require_login();
    if (($_SESSION['role'] ?? '') !== 'customer') {
        if (is_api_request()) json_out(['error' => 'Customers only'], 403);
        http_response_code(403); exit('Customers only');
    }
}

/* ---------- "Remember me" auto-login ---------- */
function try_remember_login($conn) {
    if (!empty($_SESSION['user_id'])) return;
    if (empty($_COOKIE['remember'])) return;
    $raw = $_COOKIE['remember'];
    if (!str_contains($raw, ':')) return;
    [$uid, $token] = explode(':', $raw, 2);
    $uid = (int)$uid;
    $stmt = $conn->prepare("SELECT rt.token_hash, u.id, u.name, u.email, u.role
                            FROM remember_tokens rt JOIN users u ON u.id=rt.user_id
                            WHERE rt.user_id=? AND rt.expires_at > NOW()");
    $stmt->bind_param('i', $uid);
    $stmt->execute();
    $res = $stmt->get_result();
    while ($row = $res->fetch_assoc()) {
        if (password_verify($token, $row['token_hash'])) {
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['name']    = $row['name'];
            $_SESSION['email']   = $row['email'];
            $_SESSION['role']    = $row['role'];
            return;
        }
    }
}
try_remember_login($conn);

/* ---------- Server-side validation helpers ---------- */
function v_email($s){ return filter_var(trim($s), FILTER_VALIDATE_EMAIL); }
function v_len($s, $min, $max){ $n = mb_strlen(trim($s)); return $n >= $min && $n <= $max; }
function v_int_pos($v){ return filter_var($v, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]); }
function v_password($s){
    return is_string($s) && strlen($s) >= 8
        && preg_match('/[A-Za-z]/', $s) && preg_match('/\d/', $s);
}

/* ---------- Autoload models / controllers ---------- */
spl_autoload_register(function ($class) {
    foreach (['models', 'controllers'] as $dir) {
        $f = __DIR__ . "/../$dir/$class.php";
        if (is_file($f)) { require_once $f; return; }
    }
});
>>>>>>> origin/feature/task4-22-49881-3
