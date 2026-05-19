<?php
// public/order_confirmation.php
require_once __DIR__ . '/../config/database.php';
(new OrderController($conn))->confirmation();
