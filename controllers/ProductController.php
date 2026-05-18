<?php
require_once __DIR__ . '/../models/Product.php';

class ProductController {

    public function index(){

        $products = Product::getAllProducts();

        include __DIR__ . '/../views/home.php';
    }

    public function details($id){

        $product = Product::getProductById($id);

        include __DIR__ . '/../views/product_details.php';
    }
}
?>
