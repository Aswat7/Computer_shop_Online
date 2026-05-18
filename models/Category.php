<?php
// models/Category.php
// This file talks to the database for everything related to categories

function getAllCategories($conn) {
    // Get all categories, also show parent name if it has one
    $sql = "SELECT c.*, p.name AS parent_name 
            FROM categories c 
            LEFT JOIN categories p ON c.parent_id = p.id 
            ORDER BY c.created_at DESC";
    $result = mysqli_query($conn, $sql);
    $categories = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $categories[] = $row;
    }
    return $categories;
}

function getTopLevelCategories($conn) {
    // Only get categories that have no parent (top-level)
    $sql = "SELECT * FROM categories WHERE parent_id IS NULL ORDER BY name ASC";
    $result = mysqli_query($conn, $sql);
    $categories = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $categories[] = $row;
    }
    return $categories;
}

function getCategoryById($conn, $id) {
    $stmt = mysqli_prepare($conn, "SELECT * FROM categories WHERE id = ?");
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    return mysqli_fetch_assoc($result);
}

function createCategory($conn, $name, $parent_id) {
    if ($parent_id == "") {
        $parent_id = null;
    }
    $stmt = mysqli_prepare($conn, "INSERT INTO categories (name, parent_id) VALUES (?, ?)");
    mysqli_stmt_bind_param($stmt, "si", $name, $parent_id);
    return mysqli_stmt_execute($stmt);
}

function updateCategory($conn, $id, $name, $parent_id) {
    if ($parent_id == "") {
        $parent_id = null;
    }
    $stmt = mysqli_prepare($conn, "UPDATE categories SET name = ?, parent_id = ? WHERE id = ?");
    mysqli_stmt_bind_param($stmt, "sii", $name, $parent_id, $id);
    return mysqli_stmt_execute($stmt);
}

function deleteCategory($conn, $id) {
    // Check if this category has child categories
    $stmt = mysqli_prepare($conn, "SELECT COUNT(*) as total FROM categories WHERE parent_id = ?");
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($result);
    if ($row['total'] > 0) {
        return "has_children";
    }

    // Check if this category has products
    $stmt2 = mysqli_prepare($conn, "SELECT COUNT(*) as total FROM products WHERE category_id = ?");
    mysqli_stmt_bind_param($stmt2, "i", $id);
    mysqli_stmt_execute($stmt2);
    $result2 = mysqli_stmt_get_result($stmt2);
    $row2 = mysqli_fetch_assoc($result2);
    if ($row2['total'] > 0) {
        return "has_products";
    }

    // Safe to delete
    $stmt3 = mysqli_prepare($conn, "DELETE FROM categories WHERE id = ?");
    mysqli_stmt_bind_param($stmt3, "i", $id);
    mysqli_stmt_execute($stmt3);
    return "deleted";
}
?>
