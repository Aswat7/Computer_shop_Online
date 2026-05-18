<?php
// controllers/AdminBrandController.php
// This file handles all brand actions: show list, create, edit, delete

session_start();
require_once '../config/database.php';
require_once '../models/Brand.php';
require_once '../models/Category.php';

// --- ADMIN GATE ---
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../views/admin/login.php");
    exit;
}

$action = $_GET['action'] ?? 'list';
$error = "";

// =====================
// SHOW LIST
// =====================
if ($action === 'list') {
    $brands = getAllBrands($conn);
    include '../views/admin/brands/index.php';
}

// =====================
// SHOW CREATE FORM
// =====================
elseif ($action === 'create') {
    $categories = getAllCategories($conn);
    include '../views/admin/brands/create.php';
}

// =====================
// SAVE NEW BRAND
// =====================
elseif ($action === 'store') {
    $name        = trim($_POST['name'] ?? '');
    $category_id = (int)$_POST['category_id'];

    // PHP validation
    if ($name === '') {
        $error = "Brand name cannot be empty.";
        $categories = getAllCategories($conn);
        include '../views/admin/brands/create.php';
    } elseif ($category_id <= 0) {
        $error = "Please select a category.";
        $categories = getAllCategories($conn);
        include '../views/admin/brands/create.php';
    } else {
        createBrand($conn, $name, $category_id);
        header("Location: AdminBrandController.php?action=list&success=created");
        exit;
    }
}

// =====================
// SHOW EDIT FORM
// =====================
elseif ($action === 'edit') {
    $id         = (int)$_GET['id'];
    $brand      = getBrandById($conn, $id);
    $categories = getAllCategories($conn);
    include '../views/admin/brands/edit.php';
}

// =====================
// SAVE EDITED BRAND
// =====================
elseif ($action === 'update') {
    $id          = (int)$_POST['id'];
    $name        = trim($_POST['name'] ?? '');
    $category_id = (int)$_POST['category_id'];

    if ($name === '') {
        $error = "Brand name cannot be empty.";
        $brand = getBrandById($conn, $id);
        $categories = getAllCategories($conn);
        include '../views/admin/brands/edit.php';
    } elseif ($category_id <= 0) {
        $error = "Please select a category.";
        $brand = getBrandById($conn, $id);
        $categories = getAllCategories($conn);
        include '../views/admin/brands/edit.php';
    } else {
        updateBrand($conn, $id, $name, $category_id);
        header("Location: AdminBrandController.php?action=list&success=updated");
        exit;
    }
}

// =====================
// DELETE BRAND
// =====================
elseif ($action === 'delete') {
    $id     = (int)$_GET['id'];
    $result = deleteBrand($conn, $id);

    if ($result === "has_products") {
        header("Location: AdminBrandController.php?action=list&error=has_products");
    } else {
        header("Location: AdminBrandController.php?action=list&success=deleted");
    }
    exit;
}
?>
