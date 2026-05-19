<?php
session_start();
header('Content-Type: application/json');

require_once '../../models/Cart.php';

if(!isset($_SESSION['user_id'])){

    echo json_encode([
        'status' => false,
        'message' => 'Login required'
    ]);

    exit();
}

$user_id = $_SESSION['user_id'];
$product_id = intval($_POST['product_id']);
$quantity = intval($_POST['quantity']);

Cart::addToCart($user_id, $product_id, $quantity);

echo json_encode([
    'status' => true,
    'message' => 'Added to cart successfully'
]);
?>
