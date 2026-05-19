<?php
// models/User.php
<<<<<<< HEAD
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
=======
class User {
    private $conn;
    public function __construct($conn) { $this->conn = $conn; }

    public function findByEmail($email) {
        $stmt = $this->conn->prepare("SELECT id,name,email,password_hash,role FROM users WHERE email=?");
        $stmt->bind_param('s', $email);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function emailExists($email) {
        $stmt = $this->conn->prepare("SELECT id FROM users WHERE email=?");
        $stmt->bind_param('s', $email);
        $stmt->execute();
        return (bool)$stmt->get_result()->fetch_assoc();
    }

    public function create($name, $email, $passwordHash, $role = 'customer') {
        $stmt = $this->conn->prepare(
            "INSERT INTO users (name,email,password_hash,role) VALUES (?,?,?,?)"
        );
        $stmt->bind_param('ssss', $name, $email, $passwordHash, $role);
        $stmt->execute();
        return $stmt->insert_id;
    }

    public function customers() {
        return $this->conn->query(
            "SELECT id,name,email,created_at FROM users WHERE role='customer' ORDER BY id DESC"
        );
    }

    public function deleteCustomer($id) {
        $stmt = $this->conn->prepare("DELETE FROM users WHERE id=? AND role='customer'");
        $stmt->bind_param('i', $id);
        return $stmt->execute();
    }

    public function saveRememberToken($userId, $hash, $expiresAt) {
        $stmt = $this->conn->prepare(
            "INSERT INTO remember_tokens (user_id,token_hash,expires_at) VALUES (?,?,?)"
        );
        $stmt->bind_param('iss', $userId, $hash, $expiresAt);
        return $stmt->execute();
    }

    public function clearRememberTokens($userId) {
        $stmt = $this->conn->prepare("DELETE FROM remember_tokens WHERE user_id=?");
        $stmt->bind_param('i', $userId);
        return $stmt->execute();
    }
}
>>>>>>> origin/feature/task4-22-49881-3
