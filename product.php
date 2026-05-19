<?php
require_once 'controllers/ProductController.php';

$id = $_GET['id'];

$controller = new ProductController();

$controller->details($id);
?>
