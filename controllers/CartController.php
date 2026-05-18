<?php
// controllers/CartController.php
class CartController {
    private $conn; private $cart; private $products;
    public function __construct($conn) {
        $this->conn     = $conn;
        $this->cart     = new Cart($conn);
        $this->products = new Product($conn);
    }

    public function index() {
        require_customer();
        $uid = (int)$_SESSION['user_id'];
        $res = $this->cart->itemsForUser($uid);
        $items = []; $total = 0;
        while ($r = $res->fetch_assoc()) { $items[] = $r; $total += $r['subtotal']; }
        view('cart/index', ['items' => $items, 'total' => $total]);
    }

    /* ---------- JSON / AJAX endpoints ---------- */

    public function add() {
        require_customer();
        csrf_check();
        $pid = (int)($_POST['product_id'] ?? 0);
        $qty = (int)($_POST['quantity']   ?? 0);
        if ($pid <= 0 || $qty < 1) json_out(['error' => 'Invalid input'], 400);

        $p = $this->products->stockOf($pid);
        if (!$p) json_out(['error' => 'Product not found'], 404);
        if ($qty > (int)$p['stock']) json_out(['error' => 'Not enough stock'], 400);

        $this->cart->add((int)$_SESSION['user_id'], $pid, $qty);
        json_out(['success' => true]);
    }

    public function remove() {
        require_customer();
        csrf_check();
        $id = (int)($_POST['id'] ?? 0);
        if ($id <= 0) json_out(['error' => 'Invalid id'], 400);
        $this->cart->remove($id, (int)$_SESSION['user_id']);
        json_out(['success' => true]);
    }
}
