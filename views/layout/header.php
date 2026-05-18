<?php
// views/layout/header.php
$u     = current_user();
$title = $title ?? 'ShopLite';
?><!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title><?= e($title) ?></title>
<link rel="stylesheet" href="/assets/style.css">
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
</head>
<body>
<header>
  <a href="/index.php" class="brand">🛒 ShopLite</a>
  <a href="/index.php">Products</a>
  <?php if ($u && $u['role'] === 'customer'): ?>
    <a href="/cart.php">Cart</a>
  <?php endif; ?>
  <?php if ($u && $u['role'] === 'admin'): ?>
    <a href="/admin/dashboard.php">Dashboard</a>
    <a href="/admin/customers.php">Customers</a>
    <a href="/admin/reviews.php">Reviews</a>
  <?php endif; ?>
  <?php if ($u): ?>
    <span>Hi, <?= e($u['name']) ?> (<?= e($u['role']) ?>)</span>
    <a href="/auth/logout.php">Logout</a>
  <?php else: ?>
    <a href="/auth/login.php">Login</a>
    <a href="/auth/register.php">Register</a>
  <?php endif; ?>
</header>
<main>
