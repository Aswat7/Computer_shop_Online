<?php
// models/Brand.php
// This file talks to the database for everything related to brands

function getAllBrands($conn) {
    // Get all brands, also show which category they belong to
    $sql = "SELECT b.*, c.name AS category_name 
            FROM brands b 
            JOIN categories c ON b.category_id = c.id 
            ORDER BY b.created_at DESC";
    $result = mysqli_query($conn, $sql);
    $brands = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $brands[] = $row;
    }
    return $brands;
}

function getBrandById($conn, $id) {
    $stmt = mysqli_prepare($conn, "SELECT * FROM brands WHERE id = ?");
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    return mysqli_fetch_assoc($result);
}

function getBrandsByCategory($conn, $category_id) {
    // Used by AJAX to load brands when a category is selected
    $stmt = mysqli_prepare($conn, "SELECT * FROM brands WHERE category_id = ? ORDER BY name ASC");
    mysqli_stmt_bind_param($stmt, "i", $category_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $brands = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $brands[] = $row;
    }
    return $brands;
}

function createBrand($conn, $name, $category_id) {
    $stmt = mysqli_prepare($conn, "INSERT INTO brands (name, category_id) VALUES (?, ?)");
    mysqli_stmt_bind_param($stmt, "si", $name, $category_id);
    return mysqli_stmt_execute($stmt);
}

function updateBrand($conn, $id, $name, $category_id) {
    $stmt = mysqli_prepare($conn, "UPDATE brands SET name = ?, category_id = ? WHERE id = ?");
    mysqli_stmt_bind_param($stmt, "sii", $name, $category_id, $id);
    return mysqli_stmt_execute($stmt);
}

function deleteBrand($conn, $id) {
    // Check if any products use this brand
    $stmt = mysqli_prepare($conn, "SELECT COUNT(*) as total FROM products WHERE brand_id = ?");
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($result);
    if ($row['total'] > 0) {
        return "has_products";
    }

    // Safe to delete
    $stmt2 = mysqli_prepare($conn, "DELETE FROM brands WHERE id = ?");
    mysqli_stmt_bind_param($stmt2, "i", $id);
    mysqli_stmt_execute($stmt2);
    return "deleted";
}
?>
