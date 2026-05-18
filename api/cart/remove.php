<?php
header('Content-Type: application/json');

require_once '../../models/Cart.php';

$cart_id = intval($_POST['cart_id']);

Cart::removeItem($cart_id);

echo json_encode([
    'status' => true,
    'message' => 'Removed'
]);
?>
