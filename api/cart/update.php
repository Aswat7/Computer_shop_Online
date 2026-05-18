<?php
header('Content-Type: application/json');

require_once '../../models/Cart.php';

$cart_id = intval($_POST['cart_id']);
$quantity = intval($_POST['quantity']);

Cart::updateQuantity($cart_id, $quantity);

echo json_encode([
    'status' => true,
    'message' => 'Updated'
]);
?>
