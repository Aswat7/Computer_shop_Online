<?php
// controllers/AdminController.php
class AdminController {
    private $conn; private $users; private $reviews; private $orders;
    public function __construct($conn) {
        $this->conn    = $conn;
        $this->users   = new User($conn);
        $this->reviews = new Review($conn);
        $this->orders  = new Order($conn);
    }

    public function dashboard() {
        require_admin();
        $orders  = $this->orders->recent(10);
        $reviews = $this->reviews->recent(10);
        view('admin/dashboard', ['orders' => $orders, 'reviews' => $reviews]);
    }

    public function customers() {
        require_admin();
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'delete') {
            csrf_check_form();
            $id = (int)($_POST['id'] ?? 0);
            if ($id > 0 && $id !== (int)$_SESSION['user_id']) {
                $this->users->deleteCustomer($id);
            }
            header('Location: /admin/customers.php'); exit;
        }
        $customers = $this->users->customers();
        view('admin/customers', ['customers' => $customers]);
    }

    public function reviews() {
        require_admin();
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'delete') {
            csrf_check_form();
            $id = (int)($_POST['id'] ?? 0);
            if ($id > 0) $this->reviews->deleteAsAdmin($id);
            header('Location: /admin/reviews.php'); exit;
        }
        $reviews = $this->reviews->all();
        view('admin/reviews', ['reviews' => $reviews]);
    }
}
