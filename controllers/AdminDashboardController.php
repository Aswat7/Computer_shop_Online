<?php
// controllers/AdminDashboardController.php

session_start();

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/Product.php';

// =====================
// ADMIN CHECK
// =====================
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../views/admin/login.php");
    exit;
}

// =====================
// DASHBOARD DATA
// =====================
$total_products   = getTotalProducts($conn);
$total_categories = getTotalCategories($conn);
$total_brands     = getTotalBrands($conn);
$low_stock        = getLowStockProducts($conn);
$recent_orders = [];
$recent_reviews = [];

// =====================
// LOAD VIEW
// =====================
include __DIR__ . '/../views/admin/dashboard.php';
?>
