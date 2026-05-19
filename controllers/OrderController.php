<?php
// controllers/OrderController.php
class OrderController {
    private $conn; private $cart; private $products; private $orders;
    public function __construct($conn) {
        $this->conn     = $conn;
        $this->cart     = new Cart($conn);
        $this->products = new Product($conn);
        $this->orders   = new Order($conn);
    }

    public function place() {
        require_customer();
        csrf_check();

        $pm = $_POST['payment_method'] ?? '';
        if (!in_array($pm, ['cod', 'wallet'], true)) {
            json_out(['error' => 'Invalid payment method'], 400);
        }
        $paymentDb = $pm === 'cod' ? 'cash_on_delivery' : 'online_wallet';

        $uid = (int)$_SESSION['user_id'];
        $res = $this->cart->rawItemsForOrder($uid);
        $items = []; $total = 0.0;
        while ($r = $res->fetch_assoc()) {
            if ($r['quantity'] < 1)            json_out(['error' => 'Invalid quantity'], 400);
            if ($r['quantity'] > $r['stock'])  json_out(['error' => "Not enough stock for {$r['name']}"], 400);
            $total += $r['price'] * $r['quantity'];
            $items[] = $r;
        }
        if (!$items) json_out(['error' => 'Cart is empty'], 400);

        $this->conn->begin_transaction();
        try {
            $oid = $this->orders->create($uid, $total, $paymentDb);
            foreach ($items as $it) {
                $this->orders->addItem($oid, (int)$it['product_id'], (int)$it['quantity'], (float)$it['price']);
                if (!$this->products->decreaseStock((int)$it['product_id'], (int)$it['quantity'])) {
                    throw new Exception('Stock changed');
                }
            }
            $this->cart->clear($uid);
            $this->conn->commit();
            json_out(['success' => true, 'order_id' => $oid, 'total' => $total]);
        } catch (Exception $ex) {
            $this->conn->rollback();
            json_out(['error' => 'Order failed: ' . $ex->getMessage()], 500);
        }
    }

    public function confirmation() {
        require_customer();
        $id = (int)($_GET['id'] ?? 0);
        $order = $this->orders->findForUser($id, (int)$_SESSION['user_id']);
        if (!$order) { http_response_code(404); exit('Order not found'); }
        $items = $this->orders->itemsOf($id);
        view('orders/confirmation', ['order' => $order, 'items' => $items]);
    }
}
