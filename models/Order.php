<?php
// models/Order.php
class Order {
    private $conn;
    public function __construct($conn) { $this->conn = $conn; }

    public function create($userId, $total, $paymentMethod) {
        $stmt = $this->conn->prepare(
            "INSERT INTO orders (user_id,total_amount,payment_method,status)
             VALUES (?,?,?, 'pending')"
        );
        $stmt->bind_param('ids', $userId, $total, $paymentMethod);
        $stmt->execute();
        return $stmt->insert_id;
    }

    public function addItem($orderId, $productId, $qty, $unitPrice) {
        $stmt = $this->conn->prepare(
            "INSERT INTO order_items (order_id,product_id,quantity,unit_price)
             VALUES (?,?,?,?)"
        );
        $stmt->bind_param('iiid', $orderId, $productId, $qty, $unitPrice);
        return $stmt->execute();
    }

    public function findForUser($id, $userId) {
        $stmt = $this->conn->prepare("SELECT * FROM orders WHERE id=? AND user_id=?");
        $stmt->bind_param('ii', $id, $userId);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function itemsOf($orderId) {
        $stmt = $this->conn->prepare(
            "SELECT oi.*, p.name FROM order_items oi
             JOIN products p ON p.id = oi.product_id WHERE oi.order_id=?"
        );
        $stmt->bind_param('i', $orderId);
        $stmt->execute();
        return $stmt->get_result();
    }

    public function recent($limit = 10) {
        $limit = (int)$limit;
        return $this->conn->query(
            "SELECT o.*, COALESCE(u.name,'(deleted)') AS uname
             FROM orders o LEFT JOIN users u ON u.id=o.user_id
             ORDER BY o.id DESC LIMIT $limit"
        );
    }
}
