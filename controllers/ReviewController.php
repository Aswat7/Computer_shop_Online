<?php
// controllers/ReviewController.php — AJAX/JSON endpoints
class ReviewController {
    private $conn; private $reviews;
    public function __construct($conn) {
        $this->conn    = $conn;
        $this->reviews = new Review($conn);
    }

    public function listJson() {
        $pid = (int)($_GET['product_id'] ?? 0);
        if ($pid <= 0) json_out(['error' => 'Invalid product'], 400);
        $res = $this->reviews->forProduct($pid);
        $out = []; while ($r = $res->fetch_assoc()) $out[] = $r;
        json_out(['reviews' => $out]);
    }

    public function add() {
        require_customer();
        csrf_check();
        $pid     = (int)($_POST['product_id'] ?? 0);
        $comment = trim($_POST['comment'] ?? '');
        if ($pid <= 0)                     json_out(['error' => 'Invalid product'], 400);
        if ($comment === '')               json_out(['error' => 'Comment required'], 400);
        if (mb_strlen($comment) > 1000)    json_out(['error' => 'Comment too long'], 400);

        $id = $this->reviews->add(
            $pid,
            (int)$_SESSION['user_id'],
            (string)($_SESSION['name'] ?? 'Anonymous'),
            $comment
        );
        json_out(['success' => true, 'id' => $id]);
    }

    public function delete() {
        require_login();
        csrf_check();
        $id = (int)($_POST['id'] ?? $_GET['id'] ?? 0);
        if ($id <= 0) json_out(['error' => 'Invalid id'], 400);

        $affected = ($_SESSION['role'] ?? '') === 'admin'
            ? $this->reviews->deleteAsAdmin($id)
            : $this->reviews->deleteOwn($id, (int)$_SESSION['user_id']);

        if ($affected === 0) json_out(['error' => 'Not found or not allowed'], 404);
        json_out(['success' => true]);
    }
}
