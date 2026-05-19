<?php
// controllers/AdminProductController.php
session_start();

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/Product.php';
require_once __DIR__ . '/../models/Category.php';
require_once __DIR__ . '/../models/Brand.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../views/admin/login.php");
    exit;
}

$action = $_GET['action'] ?? 'list';
$error  = "";

if ($action === 'list') {
    $products = getAllProducts($conn);
    include '../views/admin/products/index.php';
} 

elseif ($action === 'create') {
    $categories = getAllCategories($conn);
    $brands     = getAllBrands($conn);
    include '../views/admin/products/create.php';
} 

elseif ($action === 'getBrands') {

    $category_id = (int)($_GET['category_id'] ?? 0);

    $sql = "SELECT * FROM brands WHERE category_id = $category_id";

    $result = mysqli_query($conn, $sql);

    $brands = [];

    while ($row = mysqli_fetch_assoc($result)) {
        $brands[] = $row;
    }

    header('Content-Type: application/json');

    echo json_encode($brands);

    exit;
}

elseif ($action === 'store') {
    $name        = trim($_POST['name'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $review      = trim($_POST['manufacturer_review'] ?? '');
    $price       = $_POST['price'] ?? 0;
    $category_id = (int)($_POST['category_id'] ?? 0);
    $brand_id    = (int)($_POST['brand_id'] ?? 0);
    $stock       = (int)($_POST['stock'] ?? 0);
    $image_path  = "";

    if ($name === '' || $description === '' || !is_numeric($price)) {
        $error = "Invalid input. Please fill all required fields.";
    }

    if ($error === '' && isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
        $upload_dir_fs = __DIR__ . '/../public/uploads/products/';
        $upload_dir_db = 'uploads/products/';

        if (!is_dir($upload_dir_fs)) {
            mkdir($upload_dir_fs, 0777, true);
        }

        $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $filename = time() . '_' . rand(1000, 9999) . '.' . $ext;
        move_uploaded_file($_FILES['image']['tmp_name'], $upload_dir_fs . $filename);
        $image_path = $upload_dir_db . $filename;
    }

    if ($error === '') {
        createProduct($conn, $name, $description, $review, (float)$price, $category_id, $brand_id, $stock, $image_path);
        header("Location: AdminProductController.php?action=list&success=created");
        exit;
    }

    $categories = getAllCategories($conn);
    $brands     = getAllBrands($conn);
    include '../views/admin/products/create.php';
}

elseif ($action === 'update') {
    $id          = (int)$_POST['id'];
    $name        = trim($_POST['name'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $review      = trim($_POST['manufacturer_review'] ?? '');
    $price       = $_POST['price'] ?? 0;
    $category_id = (int)$_POST['category_id'];
    $brand_id    = (int)$_POST['brand_id'];
    $stock       = (int)$_POST['stock'];

    $old_product = getProductById($conn, $id);
    $image_path  = $old_product['image_path'];

    if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
        $upload_dir_fs = __DIR__ . '/../public/uploads/products/';
        $upload_dir_db = 'uploads/products/';

        if (!is_dir($upload_dir_fs)) {
            mkdir($upload_dir_fs, 0777, true);
        }

        if (!empty($old_product['image_path'])) {
            $old_file = __DIR__ . '/../public/' . $old_product['image_path'];
            if (file_exists($old_file)) unlink($old_file);
        }

        $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $filename = time() . '_' . rand(1000, 9999) . '.' . $ext;
        move_uploaded_file($_FILES['image']['tmp_name'], $upload_dir_fs . $filename);
        $image_path = $upload_dir_db . $filename;
    }

    updateProduct($conn, $id, $name, $description, $review, (float)$price, $category_id, $brand_id, $stock, $image_path);
    header("Location: AdminProductController.php?action=list&success=updated");
    exit;
}

elseif ($action === 'edit') {
    $id = (int)$_GET['id'];

    $product = getProductById($conn, $id);
    $categories = getAllCategories($conn);
    $brands = getAllBrands($conn);

    if (!$product) {
        die("Product not found");
    }

    include '../views/admin/products/edit.php';
}

elseif ($action === 'delete') {
    $id = (int)$_GET['id'];
    deleteProduct($conn, $id);
    header("Location: AdminProductController.php?action=list&success=deleted");
    exit;
}
?>