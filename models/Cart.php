<?php
// models/Cart.php
class Cart {
    private $conn;
    public function __construct($conn) { $this->conn = $conn; }

    public function itemsForUser($userId) {
        $stmt = $this->conn->prepare("
            SELECT c.id, c.quantity, p.id AS pid, p.name, p.price,
                   (p.price * c.quantity) AS subtotal
            FROM cart c JOIN products p ON p.id = c.product_id
            WHERE c.user_id = ?");
        $stmt->bind_param('i', $userId);
        $stmt->execute();
        return $stmt->get_result();
    }

    public function rawItemsForOrder($userId) {
        $stmt = $this->conn->prepare("
            SELECT c.product_id, c.quantity, p.price, p.stock, p.name
            FROM cart c JOIN products p ON p.id = c.product_id
            WHERE c.user_id = ?");
        $stmt->bind_param('i', $userId);
        $stmt->execute();
        return $stmt->get_result();
    }

    public function add($userId, $productId, $qty) {
        $stmt = $this->conn->prepare(
            "INSERT INTO cart (user_id,product_id,quantity) VALUES (?,?,?)
             ON DUPLICATE KEY UPDATE quantity = quantity + VALUES(quantity)"
        );
        $stmt->bind_param('iii', $userId, $productId, $qty);
        return $stmt->execute();
    }

    public function remove($id, $userId) {
        $stmt = $this->conn->prepare("DELETE FROM cart WHERE id=? AND user_id=?");
        $stmt->bind_param('ii', $id, $userId);
        return $stmt->execute();
    }

    public function clear($userId) {
        $stmt = $this->conn->prepare("DELETE FROM cart WHERE user_id=?");
        $stmt->bind_param('i', $userId);
        return $stmt->execute();
    }
}
