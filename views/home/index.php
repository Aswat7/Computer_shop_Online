<?php $page_title = 'Home'; include __DIR__ . '/../layout/header.php'; ?>

<section class="hero">
    <h1>Welcome to <span>Computer Shop</span></h1>
    <p>Task 1 — User authentication & profile foundation.</p>
    <div class="hero-btns">
        <?php if (!isset($_SESSION['user_id'])): ?>
            <a href="/Task1/controllers/AuthController.php?action=login" class="hero-btn hero-btn-primary">Login</a>
            <a href="/Task1/controllers/AuthController.php?action=register" class="hero-btn hero-btn-outline">Register</a>
        <?php else: ?>
            <a href="/Task1/controllers/ProfileController.php?action=view" class="hero-btn hero-btn-primary">My Profile</a>
        <?php endif; ?>
    </div>
</section>

<div class="page-wrapper">
    <h2 class="section-title">About Task 1</h2>
    <p style="margin-top:14px;line-height:1.7;color:#444;">
        This part covers user registration, login (with Remember Me),
        logout, and profile view/edit. It is the MVC foundation that
        Tasks 2, 3, and 4 build on top of. The database is shared
        across all four tasks.
    </p>
</div>

<?php include __DIR__ . '/../layout/footer.php'; ?>
