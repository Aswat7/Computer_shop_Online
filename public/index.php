<?php
// public/index.php — front entry: product listing
require_once __DIR__ . '/../config/database.php';
(new ProductController($conn))->index();
