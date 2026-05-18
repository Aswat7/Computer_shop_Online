<?php
// models/User.php
// Handles all database operations related to users

function getUserByEmail($conn, $email) {
    $stmt = mysqli_prepare($conn, "SELECT * FROM users WHERE email = ?");
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    return mysqli_fetch_assoc($result);
}

function getUserById($conn, $id) {
    $stmt = mysqli_prepare($conn, "SELECT * FROM users WHERE id = ?");
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    return mysqli_fetch_assoc($result);
}

function getUserByRememberToken($conn, $token_hash) {
    $stmt = mysqli_prepare($conn, "SELECT * FROM users WHERE remember_token = ?");
    mysqli_stmt_bind_param($stmt, "s", $token_hash);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    return mysqli_fetch_assoc($result);
}

function createUser($conn, $name, $email, $password_hash, $role) {
    $stmt = mysqli_prepare($conn, "INSERT INTO users (name, email, password_hash, role) VALUES (?, ?, ?, ?)");
    mysqli_stmt_bind_param($stmt, "ssss", $name, $email, $password_hash, $role);
    return mysqli_stmt_execute($stmt);
}

function updateUserProfile($conn, $id, $name, $email, $profile_picture = null) {
    if ($profile_picture !== null) {
        $stmt = mysqli_prepare($conn, "UPDATE users SET name = ?, email = ?, profile_picture = ? WHERE id = ?");
        mysqli_stmt_bind_param($stmt, "sssi", $name, $email, $profile_picture, $id);
    } else {
        $stmt = mysqli_prepare($conn, "UPDATE users SET name = ?, email = ? WHERE id = ?");
        mysqli_stmt_bind_param($stmt, "ssi", $name, $email, $id);
    }
    return mysqli_stmt_execute($stmt);
}

function updateUserPassword($conn, $id, $new_password_hash) {
    $stmt = mysqli_prepare($conn, "UPDATE users SET password_hash = ? WHERE id = ?");
    mysqli_stmt_bind_param($stmt, "si", $new_password_hash, $id);
    return mysqli_stmt_execute($stmt);
}

function setRememberToken($conn, $id, $token_hash) {
    $stmt = mysqli_prepare($conn, "UPDATE users SET remember_token = ? WHERE id = ?");
    mysqli_stmt_bind_param($stmt, "si", $token_hash, $id);
    return mysqli_stmt_execute($stmt);
}

function clearRememberToken($conn, $id) {
    $stmt = mysqli_prepare($conn, "UPDATE users SET remember_token = NULL WHERE id = ?");
    mysqli_stmt_bind_param($stmt, "i", $id);
    return mysqli_stmt_execute($stmt);
}

function emailExists($conn, $email, $exclude_id = null) {
    if ($exclude_id) {
        $stmt = mysqli_prepare($conn, "SELECT id FROM users WHERE email = ? AND id != ?");
        mysqli_stmt_bind_param($stmt, "si", $email, $exclude_id);
    } else {
        $stmt = mysqli_prepare($conn, "SELECT id FROM users WHERE email = ?");
        mysqli_stmt_bind_param($stmt, "s", $email);
    }
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    return mysqli_num_rows($result) > 0;
}


?>
