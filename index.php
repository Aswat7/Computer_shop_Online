<?php
<<<<<<< HEAD
// index.php — entry point. Redirects to Home.
header("Location: controllers/HomeController.php");
exit;
=======
require_once 'controllers/ProductController.php';

$controller = new ProductController();

$controller->index();
>>>>>>> origin/feature/task3-22-46877-1
?>
