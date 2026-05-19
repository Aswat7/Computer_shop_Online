<?php
<<<<<<< HEAD
require_once __DIR__ . '/../config/database.php';

class Cart {

    public static function addToCart($user_id, $product_id, $quantity) {

        global $conn;

        $check = "SELECT * FROM cart WHERE user_id=? AND product_id=?";

        $stmt = mysqli_prepare($conn, $check);

        mysqli_stmt_bind_param($stmt, "ii", $user_id, $product_id);

        mysqli_stmt_execute($stmt);

        $result = mysqli_stmt_get_result($stmt);

        if(mysqli_num_rows($result) > 0){

            $update = "UPDATE cart SET quantity = quantity + ? WHERE user_id=? AND product_id=?";

            $stmt2 = mysqli_prepare($conn, $update);

            mysqli_stmt_bind_param($stmt2, "iii", $quantity, $user_id, $product_id);

            return mysqli_stmt_execute($stmt2);

        } else {

            $insert = "INSERT INTO cart(user_id, product_id, quantity) VALUES(?,?,?)";

            $stmt3 = mysqli_prepare($conn, $insert);

            mysqli_stmt_bind_param($stmt3, "iii", $user_id, $product_id, $quantity);

            return mysqli_stmt_execute($stmt3);
        }
    }

    public static function getCartItems($user_id){

        global $conn;

        $sql = "SELECT cart.*, products.name, products.price
                FROM cart
                JOIN products ON cart.product_id = products.id
                WHERE cart.user_id=?";

        $stmt = mysqli_prepare($conn, $sql);

        mysqli_stmt_bind_param($stmt, "i", $user_id);

        mysqli_stmt_execute($stmt);

        return mysqli_stmt_get_result($stmt);
    }

    public static function updateQuantity($cart_id, $quantity){

        global $conn;

        $sql = "UPDATE cart SET quantity=? WHERE id=?";

        $stmt = mysqli_prepare($conn, $sql);

        mysqli_stmt_bind_param($stmt, "ii", $quantity, $cart_id);

        return mysqli_stmt_execute($stmt);
    }

    public static function removeItem($cart_id){

        global $conn;

        $sql = "DELETE FROM cart WHERE id=?";

        $stmt = mysqli_prepare($conn, $sql);

        mysqli_stmt_bind_param($stmt, "i", $cart_id);

        return mysqli_stmt_execute($stmt);
    }
}
?>
=======
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
>>>>>>> origin/feature/task4-22-49881-3
