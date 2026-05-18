<?php
// models/User.php
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
