<?php
// public/product.php — product detail
require_once __DIR__ . '/../config/database.php';
(new ProductController($conn))->show();
