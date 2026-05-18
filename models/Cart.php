<?php
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
