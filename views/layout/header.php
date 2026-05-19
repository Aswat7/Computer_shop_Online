<<<<<<< HEAD
<!-- views/layout/header.php -->
<!-- Shared navbar for all public-facing pages (home, profile, etc.) -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Computer Shop – <?php echo $page_title ?? 'Online PC Store'; ?></title>
    <style>
        /* ===== RESET & BASE ===== */
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; background: #f0f2f5; color: #333; }
        a { text-decoration: none; color: inherit; }

        /* ===== TOP NAVBAR ===== */
        .navbar {
            background: #1a1a2e;
            color: white;
            padding: 0 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            height: 58px;
            position: sticky;
            top: 0;
            z-index: 100;
            box-shadow: 0 2px 8px rgba(0,0,0,0.3);
        }
        .navbar .logo {
            font-size: 20px;
            font-weight: bold;
            color: #e94560;
            letter-spacing: 1px;
        }
        .navbar .logo span { color: white; }
        .nav-links { display: flex; align-items: center; gap: 6px; }
        .nav-links a {
            color: #ccc;
            font-size: 14px;
            padding: 6px 12px;
            border-radius: 4px;
            transition: background 0.2s, color 0.2s;
        }
        .nav-links a:hover { background: #0f3460; color: white; }
        .nav-links .btn-nav {
            background: #e94560;
            color: white;
            padding: 7px 16px;
            border-radius: 5px;
            font-size: 13px;
            font-weight: bold;
        }
        .nav-links .btn-nav:hover { background: #c73652; }
        .nav-links .btn-nav-outline {
            border: 1px solid #e94560;
            color: #e94560;
            padding: 6px 14px;
            border-radius: 5px;
            font-size: 13px;
        }
        .nav-links .btn-nav-outline:hover { background: #e94560; color: white; }
        .nav-user { font-size: 13px; color: #aab; }

        /* ===== CATEGORY BAR ===== */
        .category-bar {
            background: #16213e;
            padding: 0 24px;
            display: flex;
            gap: 0;
            overflow-x: auto;
            scrollbar-width: none;
        }
        .category-bar::-webkit-scrollbar { display: none; }
        .category-bar a {
            color: #aab;
            font-size: 13px;
            padding: 11px 18px;
            white-space: nowrap;
            border-bottom: 3px solid transparent;
            transition: color 0.2s, border-color 0.2s;
            display: block;
        }
        .category-bar a:hover {
            color: white;
            border-bottom-color: #e94560;
        }

        /* ===== PAGE WRAPPER ===== */
        .page-wrapper { max-width: 1200px; margin: 0 auto; padding: 28px 20px; }

        /* ===== ALERT ===== */
        .alert {
            padding: 12px 16px;
            border-radius: 6px;
            margin-bottom: 18px;
            font-size: 14px;
        }
        .alert-success { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .alert-error   { background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }

        /* ===== FORM BOX ===== */
        .form-box {
            background: white;
            border-radius: 10px;
            padding: 32px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.08);
            max-width: 500px;
            margin: 0 auto;
        }
        .form-box h2 { font-size: 22px; color: #1a1a2e; margin-bottom: 22px; }
        .form-group { margin-bottom: 16px; }
        .form-group label {
            display: block;
            font-size: 13px;
            font-weight: bold;
            color: #444;
            margin-bottom: 6px;
        }
        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 10px 13px;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 14px;
            transition: border-color 0.2s;
        }
        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: #e94560;
        }
        .error-text { color: #dc3545; font-size: 12px; margin-top: 4px; }
        .form-actions { margin-top: 22px; }
        .btn {
            display: inline-block;
            padding: 10px 22px;
            border-radius: 6px;
            font-size: 14px;
            font-weight: bold;
            cursor: pointer;
            border: none;
            transition: background 0.2s;
        }
        .btn-primary { background: #e94560; color: white; width: 100%; text-align: center; }
        .btn-primary:hover { background: #c73652; }
        .btn-secondary { background: #0f3460; color: white; }
        .btn-secondary:hover { background: #0a2540; }
        .form-footer { text-align: center; margin-top: 16px; font-size: 13px; color: #666; }
        .form-footer a { color: #e94560; font-weight: bold; }

        /* ===== PRODUCT CARD GRID ===== */
        .section-title {
            font-size: 20px;
            font-weight: bold;
            color: #1a1a2e;
            margin-bottom: 18px;
            padding-bottom: 8px;
            border-bottom: 3px solid #e94560;
            display: inline-block;
        }
        .product-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }
        .product-card {
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.07);
            overflow: hidden;
            transition: transform 0.2s, box-shadow 0.2s;
        }
        .product-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 18px rgba(0,0,0,0.12);
        }
        .product-card .card-img {
            width: 100%;
            height: 160px;
            object-fit: cover;
            background: #f0f2f5;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 48px;
        }
        .product-card img { width: 100%; height: 160px; object-fit: cover; }
        .product-card .card-body { padding: 14px; }
        .product-card .card-name {
            font-size: 14px;
            font-weight: bold;
            color: #1a1a2e;
            margin-bottom: 6px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .product-card .card-review {
            font-size: 12px;
            color: #666;
            margin-bottom: 8px;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        .product-card .card-price {
            font-size: 16px;
            font-weight: bold;
            color: #e94560;
        }

        /* ===== HERO BANNER ===== */
        .hero {
            background: linear-gradient(135deg, #1a1a2e 0%, #0f3460 60%, #16213e 100%);
            color: white;
            padding: 60px 24px;
            text-align: center;
            margin-bottom: 0;
        }
        .hero h1 { font-size: 36px; margin-bottom: 12px; }
        .hero h1 span { color: #e94560; }
        .hero p { font-size: 16px; color: #aab; margin-bottom: 24px; }
        .hero .hero-btns { display: flex; gap: 12px; justify-content: center; flex-wrap: wrap; }
        .hero .hero-btn {
            padding: 12px 28px;
            border-radius: 6px;
            font-size: 15px;
            font-weight: bold;
            cursor: pointer;
        }
        .hero .hero-btn-primary { background: #e94560; color: white; }
        .hero .hero-btn-outline { border: 2px solid white; color: white; }
        .hero .hero-btn-primary:hover { background: #c73652; }
        .hero .hero-btn-outline:hover { background: rgba(255,255,255,0.1); }

        /* ===== PROFILE PAGE ===== */
        .profile-wrapper { display: flex; gap: 28px; flex-wrap: wrap; }
        .profile-sidebar {
            width: 220px;
            flex-shrink: 0;
        }
        .profile-sidebar .avatar-box {
            background: white;
            border-radius: 10px;
            padding: 24px;
            text-align: center;
            box-shadow: 0 2px 8px rgba(0,0,0,0.07);
            margin-bottom: 16px;
        }
        .profile-sidebar .avatar-box img {
            width: 90px;
            height: 90px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid #e94560;
            margin-bottom: 10px;
        }
        .profile-sidebar .avatar-box .avatar-placeholder {
            width: 90px;
            height: 90px;
            border-radius: 50%;
            background: #e94560;
            color: white;
            font-size: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 10px;
        }
        .profile-sidebar .avatar-box .user-name { font-weight: bold; font-size: 15px; color: #1a1a2e; }
        .profile-sidebar .avatar-box .user-role {
            font-size: 12px;
            color: white;
            background: #0f3460;
            display: inline-block;
            padding: 2px 10px;
            border-radius: 10px;
            margin-top: 4px;
            text-transform: capitalize;
        }
        .profile-nav {
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0,0,0,0.07);
        }
        .profile-nav a {
            display: block;
            padding: 12px 18px;
            font-size: 14px;
            color: #444;
            border-left: 3px solid transparent;
            transition: all 0.2s;
        }
        .profile-nav a:hover, .profile-nav a.active {
            background: #f0f2f5;
            color: #e94560;
            border-left-color: #e94560;
        }
        .profile-main { flex: 1; min-width: 280px; }
        .profile-section {
            background: white;
            border-radius: 10px;
            padding: 28px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.07);
            margin-bottom: 20px;
        }
        .profile-section h3 {
            font-size: 17px;
            color: #1a1a2e;
            margin-bottom: 18px;
            padding-bottom: 10px;
            border-bottom: 2px solid #f0f2f5;
        }
        .profile-section .form-group { margin-bottom: 14px; }
        .profile-section .btn { width: auto; padding: 9px 22px; }
    </style>
</head>
<body>

<!-- TOP NAVBAR -->
<nav class="navbar">
    <div class="logo">🖥️ <span>Computer</span>Shop</div>
    <div class="nav-links">
        <a href="/Task1/controllers/HomeController.php">🏠 Home</a>
        <?php if (isset($_SESSION['user_id'])): ?>
            <span class="nav-user">👤 <?php echo htmlspecialchars($_SESSION['name']); ?></span>
            <a href="/Task1/controllers/ProfileController.php?action=view">My Profile</a>
            <a href="/Task1/controllers/AuthController.php?action=logout" class="btn-nav-outline">Logout</a>
        <?php else: ?>
            <a href="/Task1/controllers/AuthController.php?action=login" class="btn-nav-outline">Login</a>
            <a href="/Task1/controllers/AuthController.php?action=register" class="btn-nav">Register</a>
        <?php endif; ?>
    </div>
</nav>

=======
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
>>>>>>> origin/feature/task4-22-49881-3
