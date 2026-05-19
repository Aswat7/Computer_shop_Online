<?php
// models/Product.php

function getAllProducts($conn) {

    $sql = "SELECT 
                p.*, 
                c.name AS category_name, 
                b.name AS brand_name
            FROM products p
            JOIN categories c ON p.category_id = c.id
            JOIN brands b ON p.brand_id = b.id
            ORDER BY p.created_at DESC";

    $result = mysqli_query($conn, $sql);

    $products = [];

    while ($row = mysqli_fetch_assoc($result)) {
        $products[] = $row;
    }

    return $products;
}

function getProductById($conn, $id) {

    $stmt = mysqli_prepare(
        $conn,
        "SELECT * FROM products WHERE id = ?"
    );

    mysqli_stmt_bind_param($stmt, "i", $id);

    mysqli_stmt_execute($stmt);

    return mysqli_fetch_assoc(
        mysqli_stmt_get_result($stmt)
    );
}

/* =========================================================
   CREATE PRODUCT
========================================================= */
function createProduct(
    $conn,
    $name,
    $description,
    $manufacturer_review,
    $price,
    $category_id,
    $brand_id,
    $stock,
    $image_path
) {

    $sql = "INSERT INTO products
            (
                name,
                description,
                manufacturer_review,
                price,
                category_id,
                brand_id,
                stock,
                image_path
            )
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = mysqli_prepare($conn, $sql);

    if (!$stmt) {
        die(mysqli_error($conn));
    }

    mysqli_stmt_bind_param(
        $stmt,
        "sssdiiss",
        $name,
        $description,
        $manufacturer_review,
        $price,
        $category_id,
        $brand_id,
        $stock,
        $image_path
    );

    return mysqli_stmt_execute($stmt);
}

/* =========================================================
   UPDATE PRODUCT
========================================================= */
function updateProduct(
    $conn,
    $id,
    $name,
    $description,
    $manufacturer_review,
    $price,
    $category_id,
    $brand_id,
    $stock,
    $image_path
) {

    $sql = "UPDATE products
            SET
                name=?,
                description=?,
                manufacturer_review=?,
                price=?,
                category_id=?,
                brand_id=?,
                stock=?,
                image_path=?
            WHERE id=?";

    $stmt = mysqli_prepare($conn, $sql);

    if (!$stmt) {
        die(mysqli_error($conn));
    }

    mysqli_stmt_bind_param(
        $stmt,
        "sssdiissi",
        $name,
        $description,
        $manufacturer_review,
        $price,
        $category_id,
        $brand_id,
        $stock,
        $image_path,
        $id
    );

    return mysqli_stmt_execute($stmt);
}

/* =========================================================
   DELETE PRODUCT
========================================================= */
function deleteProduct($conn, $id) {

    $product = getProductById($conn, $id);

    $stmt = mysqli_prepare(
        $conn,
        "DELETE FROM products WHERE id = ?"
    );

    mysqli_stmt_bind_param($stmt, "i", $id);

    if (mysqli_stmt_execute($stmt)) {

        if ($product && !empty($product['image_path'])) {

            $file = __DIR__ . '/../public/' . $product['image_path'];

            if (file_exists($file)) {
                unlink($file);
            }
        }

        return true;
    }

    return false;
}

/* =========================================================
   DASHBOARD TOTALS
========================================================= */
function getTotalProducts($conn) {

    $result = mysqli_query(
        $conn,
        "SELECT COUNT(*) AS total FROM products"
    );

    $row = mysqli_fetch_assoc($result);

    return $row['total'];
}

function getTotalCategories($conn) {

    $result = mysqli_query(
        $conn,
        "SELECT COUNT(*) AS total FROM categories"
    );

    $row = mysqli_fetch_assoc($result);

    return $row['total'];
}

function getTotalBrands($conn) {

    $result = mysqli_query(
        $conn,
        "SELECT COUNT(*) AS total FROM brands"
    );

    $row = mysqli_fetch_assoc($result);

    return $row['total'];
}

/* =========================================================
   LOW STOCK PRODUCTS
========================================================= */
function getLowStockProducts($conn) {

    $sql = "SELECT *
            FROM products
            WHERE stock < 5
            ORDER BY stock ASC";

    $result = mysqli_query($conn, $sql);

    $products = [];

    while ($row = mysqli_fetch_assoc($result)) {
        $products[] = $row;
    }

    return $products;
}

/* =========================================================
   HOME PAGE PRODUCTS
========================================================= */
function getFeaturedProducts($conn, $limit = 6)
{
    $limit = (int)$limit;

    $sql = "SELECT
                p.id,
                p.name,
                p.description,
                p.manufacturer_review,
                p.price,
                p.stock,
                p.image_path,
                c.name AS category_name,
                b.name AS brand_name
            FROM products p
            INNER JOIN categories c
                ON p.category_id = c.id
            INNER JOIN brands b
                ON p.brand_id = b.id
            ORDER BY p.created_at DESC
            LIMIT $limit";

    $result = mysqli_query($conn, $sql);

    if (!$result) {
        die("SQL Error: " . mysqli_error($conn));
    }

    $products = [];

    while ($row = mysqli_fetch_assoc($result)) {
        $products[] = $row;
    }

    return $products;
}
?>