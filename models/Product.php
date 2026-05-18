<?php
require_once __DIR__ . '/../config/database.php';

class Product {

    public static function getAllProducts() {
        global $conn;

        $sql = "SELECT p.*, b.name as brand_name, c.name as category_name
                FROM products p
                JOIN brands b ON p.brand_id = b.id
                JOIN categories c ON p.category_id = c.id";

        return mysqli_query($conn, $sql);
    }

    public static function getProductById($id) {
        global $conn;

        $sql = "SELECT * FROM products WHERE id = ?";

        $stmt = mysqli_prepare($conn, $sql);

        mysqli_stmt_bind_param($stmt, "i", $id);

        mysqli_stmt_execute($stmt);

        $result = mysqli_stmt_get_result($stmt);

        return mysqli_fetch_assoc($result);
    }

    public static function searchProducts($keyword) {
        global $conn;

        $sql = "SELECT * FROM products WHERE name LIKE ?";

        $stmt = mysqli_prepare($conn, $sql);

        $search = "%$keyword%";

        mysqli_stmt_bind_param($stmt, "s", $search);

        mysqli_stmt_execute($stmt);

        return mysqli_stmt_get_result($stmt);
    }
}
?>
