<?php
// models/Product.php
class Product {
    private $conn;
    public function __construct($conn) { $this->conn = $conn; }

    public function all() {
        $res = $this->conn->query("SELECT * FROM products ORDER BY id DESC");
        return $res ? $res->fetch_all(MYSQLI_ASSOC) : [];
    }

    public function search($q) {
        $like = '%' . $q . '%';
        $stmt = $this->conn->prepare(
            "SELECT * FROM products WHERE name LIKE ? OR description LIKE ? ORDER BY id DESC"
        );
        $stmt->bind_param('ss', $like, $like);
        $stmt->execute();
        $res = $stmt->get_result();
        return $res ? $res->fetch_all(MYSQLI_ASSOC) : [];
    }

    public function find($id) {
        $stmt = $this->conn->prepare("SELECT * FROM products WHERE id=?");
        $stmt->bind_param('i', $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function stockOf($id) {
        $stmt = $this->conn->prepare("SELECT stock FROM products WHERE id=?");
        $stmt->bind_param('i', $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function decreaseStock($id, $qty) {
        $stmt = $this->conn->prepare(
            "UPDATE products SET stock=stock-? WHERE id=? AND stock>=?"
        );
        $stmt->bind_param('iii', $qty, $id, $qty);
        $stmt->execute();
        return $stmt->affected_rows > 0;
    }
}
