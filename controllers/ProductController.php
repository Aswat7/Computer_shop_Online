<?php
// controllers/ProductController.php
class ProductController {
    private $conn; private $products; private $reviews;
    public function __construct($conn) {
        $this->conn     = $conn;
        $this->products = new Product($conn);
        $this->reviews  = new Review($conn);
    }

    public function index() {
        $q = trim($_GET['q'] ?? '');
        if (mb_strlen($q) > 100) $q = mb_substr($q, 0, 100);
        $products = $q !== '' ? $this->products->search($q) : $this->products->all();
        view('products/index', ['products' => $products, 'q' => $q]);
    }

    public function show() {
        $pid = (int)($_GET['id'] ?? 0);
        if ($pid <= 0) { http_response_code(404); exit('Product not found'); }
        $product = $this->products->find($pid);
        if (!$product) { http_response_code(404); exit('Product not found'); }
        $u = current_user();
        view('products/show', ['product' => $product, 'u' => $u, 'pid' => $pid]);
    }
}
