<<<<<<< HEAD
<?php
// views/auth/login.php

$page_title = 'Login';
$top_categories = [];

include __DIR__ . '/../layout/header.php';
?>

<div class="page-wrapper">
    <div class="form-box" style="margin-top: 20px;">

        <h2>🔐 Welcome Back</h2>

        <!-- Flash messages -->
        <?php if (isset($_GET['registered'])): ?>
            <div class="alert alert-success">
                ✅ Registration successful! Please log in.
            </div>
        <?php endif; ?>

        <?php if (isset($_GET['logged_out'])): ?>
            <div class="alert alert-success">
                👋 You have been logged out successfully.
            </div>
        <?php endif; ?>

        <!-- General Error -->
        <?php if (!empty($errors['general'] ?? '')): ?>
            <div class="alert alert-error">
                ❌ <?php echo htmlspecialchars($errors['general']); ?>
            </div>
        <?php endif; ?>

        <form method="POST"
              action="../controllers/AuthController.php?action=authenticate"
              onsubmit="return validateLoginForm()">

            <!-- Email -->
            <div class="form-group">

                <label for="email">Email Address *</label>

                <input
                    type="email"
                    id="email"
                    name="email"
                    placeholder="Enter your email"
                    value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>"
                >

                <div class="error-text" id="email-error">
                    <?php echo htmlspecialchars($errors['email'] ?? ''); ?>
                </div>

            </div>

            <!-- Password -->
            <div class="form-group">

                <label for="password">Password *</label>

                <input
                    type="password"
                    id="password"
                    name="password"
                    placeholder="Enter your password"
                >

                <div class="error-text" id="password-error">
                    <?php echo htmlspecialchars($errors['password'] ?? ''); ?>
                </div>

            </div>

            <!-- Remember Me -->
            <div class="form-group" style="display:flex; align-items:center; gap:8px;">

                <input
                    type="checkbox"
                    id="remember_me"
                    name="remember_me"
                    style="width:auto; cursor:pointer;"
                >

                <label
                    for="remember_me"
                    style="font-weight:normal; cursor:pointer; margin-bottom:0;"
                >
                    Remember me for 30 days
                </label>

            </div>

            <!-- Button -->
            <div class="form-actions">
                <button type="submit" class="btn btn-primary">
                    Log In
                </button>
            </div>

        </form>

        <div class="form-footer">
            Don't have an account?
            <a href="../controllers/AuthController.php?action=register">
                Register here
            </a>
        </div>

    </div>
</div>

<script src="../public/js/auth.js"></script>

<?php include __DIR__ . '/../layout/footer.php'; ?>
=======
<?php $title = 'Login'; require __DIR__ . '/../layout/header.php'; ?>
<div class="card" style="max-width:420px;margin:auto">
  <h1>Login</h1>
  <?php if (isset($_GET['registered'])): ?><p class="ok">Account created. Please log in.</p><?php endif; ?>
  <?php foreach ($errors as $er): ?><p class="err"><?= e($er) ?></p><?php endforeach; ?>
  <form method="post" id="loginForm" novalidate>
    <?= csrf_field() ?>
    <p><label>Email<br><input type="email" name="email" required value="<?= e($email) ?>"></label></p>
    <p><label>Password<br><input type="password" name="password" required></label></p>
    <p><label><input type="checkbox" name="remember" style="width:auto"> Remember me</label></p>
    <button type="submit">Login</button>
  </form>
  <p class="muted">Demo: admin@shop.test / Passw0rd! · alice@shop.test / Passw0rd!</p>
</div>
<script>
document.getElementById('loginForm').addEventListener('submit', function (e) {
  const email = this.email.value.trim(), pw = this.password.value;
  if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email) || pw.length < 1) {
    e.preventDefault(); alert('Enter valid email and password.');
  }
});
</script>
<?php require __DIR__ . '/../layout/footer.php'; ?>
>>>>>>> origin/feature/task4-22-49881-3
