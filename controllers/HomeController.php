<?php
// Task 1 — Home / landing (auth foundation only)
session_start();

require_once '../config/database.php';
require_once '../models/User.php';

// Auto-login via Remember Me cookie
if (!isset($_SESSION['user_id']) && isset($_COOKIE['remember_token'])) {
    $raw_token  = $_COOKIE['remember_token'];
    $token_hash = hash('sha256', $raw_token);
    $user = getUserByRememberToken($conn, $token_hash);
    if ($user) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['name']    = $user['name'];
        $_SESSION['role']    = $user['role'];
    }
}

include '../views/home/index.php';
