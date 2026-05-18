<?php
// public/cart.php — cart page
require_once __DIR__ . '/../config/database.php';
(new CartController($conn))->index();
