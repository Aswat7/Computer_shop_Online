<?php
// models/Review.php
class Review {
    private $conn;
    public function __construct($conn) { $this->conn = $conn; }

    public function forProduct($productId) {
        $stmt = $this->conn->prepare("
            SELECT r.id, r.comment, r.created_at, r.user_id,
                   COALESCE(u.name,'(deleted)') AS reviewer
            FROM reviews r LEFT JOIN users u ON u.id = r.user_id
            WHERE r.product_id = ? ORDER BY r.created_at DESC");
        $stmt->bind_param('i', $productId);
        $stmt->execute();
        return $stmt->get_result();
    }

    public function add($productId, $userId, $reviewerName, $comment) {
        $stmt = $this->conn->prepare(
            "INSERT INTO reviews (product_id,user_id,reviewer_name,comment)
             VALUES (?,?,?,?)"
        );
        $stmt->bind_param('iiss', $productId, $userId, $reviewerName, $comment);
        $stmt->execute();
        return $stmt->insert_id;
    }

    public function deleteAsAdmin($id) {
        $stmt = $this->conn->prepare("DELETE FROM reviews WHERE id=?");
        $stmt->bind_param('i', $id);
        $stmt->execute();
        return $stmt->affected_rows;
    }

    public function deleteOwn($id, $userId) {
        $stmt = $this->conn->prepare("DELETE FROM reviews WHERE id=? AND user_id=?");
        $stmt->bind_param('ii', $id, $userId);
        $stmt->execute();
        return $stmt->affected_rows;
    }

    public function all() {
        return $this->conn->query("
            SELECT r.*, COALESCE(u.name,'(deleted)') AS uname, p.name AS pname
            FROM reviews r LEFT JOIN users u ON u.id = r.user_id
            JOIN products p ON p.id = r.product_id
            ORDER BY r.id DESC");
    }

    public function recent($limit = 10) {
        $limit = (int)$limit;
        return $this->conn->query("
            SELECT r.*, COALESCE(u.name,'(deleted)') AS uname, p.name AS pname
            FROM reviews r LEFT JOIN users u ON u.id = r.user_id
            JOIN products p ON p.id = r.product_id
            ORDER BY r.id DESC LIMIT $limit");
    }
}
