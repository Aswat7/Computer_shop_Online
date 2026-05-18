<?php
// controllers/AdminCategoryController.php
// This file handles all category actions: show list, create, edit, delete

session_start();
require_once '../config/database.php';
require_once '../models/Category.php';

// --- ADMIN GATE: Only admin can access this page ---
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../views/admin/login.php");
    exit;
}

$action = $_GET['action'] ?? 'list';
$error = "";
$success = "";

// =====================
// SHOW LIST
// =====================
if ($action === 'list') {
    $categories = getAllCategories($conn);
    include '../views/admin/categories/index.php';
}

// =====================
// SHOW CREATE FORM
// =====================
elseif ($action === 'create') {
    $topCategories = getTopLevelCategories($conn);
    include '../views/admin/categories/create.php';
}

// =====================
// SAVE NEW CATEGORY
// =====================
elseif ($action === 'store') {
    $name      = trim($_POST['name'] ?? '');
    $parent_id = $_POST['parent_id'] ?? '';

    // PHP validation
    if ($name === '') {
        $error = "Category name cannot be empty.";
        $topCategories = getTopLevelCategories($conn);
        include '../views/admin/categories/create.php';
    } else {
        createCategory($conn, $name, $parent_id);
        header("Location: AdminCategoryController.php?action=list&success=created");
        exit;
    }
}

// =====================
// SHOW EDIT FORM
// =====================
elseif ($action === 'edit') {
    $id       = (int)$_GET['id'];
    $category = getCategoryById($conn, $id);
    $topCategories = getTopLevelCategories($conn);
    include '../views/admin/categories/edit.php';
}

// =====================
// SAVE EDITED CATEGORY
// =====================
elseif ($action === 'update') {
    $id        = (int)$_POST['id'];
    $name      = trim($_POST['name'] ?? '');
    $parent_id = $_POST['parent_id'] ?? '';

    // PHP validation
    if ($name === '') {
        $error = "Category name cannot be empty.";
        $category = getCategoryById($conn, $id);
        $topCategories = getTopLevelCategories($conn);
        include '../views/admin/categories/edit.php';
    } else {
        // Prevent a category from being its own parent
        if ($parent_id == $id) {
            $error = "A category cannot be its own parent.";
            $category = getCategoryById($conn, $id);
            $topCategories = getTopLevelCategories($conn);
            include '../views/admin/categories/edit.php';
        } else {
            updateCategory($conn, $id, $name, $parent_id);
            header("Location: AdminCategoryController.php?action=list&success=updated");
            exit;
        }
    }
}

// =====================
// DELETE CATEGORY
// =====================
elseif ($action === 'delete') {
    $id     = (int)$_GET['id'];
    $result = deleteCategory($conn, $id);

    if ($result === "has_children") {
        header("Location: AdminCategoryController.php?action=list&error=has_children");
    } elseif ($result === "has_products") {
        header("Location: AdminCategoryController.php?action=list&error=has_products");
    } else {
        header("Location: AdminCategoryController.php?action=list&success=deleted");
    }
    exit;
}
?>
