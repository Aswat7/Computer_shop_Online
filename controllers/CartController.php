<?php
require_once __DIR__ . '/../models/Cart.php';

class CartController {

    public function cartPage(){

        session_start();

        if(!isset($_SESSION['user_id'])){
            header("Location: login.php");
            exit();
        }

        $user_id = $_SESSION['user_id'];

        $items = Cart::getCartItems($user_id);

        include __DIR__ . '/../views/cart.php';
    }
}
?>
