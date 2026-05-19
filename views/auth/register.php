<<<<<<< HEAD
<?php
// views/auth/register.php

$page_title = 'Register';

// No category bar on auth pages
$top_categories = [];

include __DIR__ . '/../layout/header.php';
?>

<div class="page-wrapper">

    <div class="form-box" style="margin-top: 20px;">

        <h2>🖥️ Create Account</h2>

        <!-- Success Message -->
        <?php if (isset($_GET['registered'])): ?>
            <div class="alert alert-success">
                ✅ Account created! Please log in.
            </div>
        <?php endif; ?>

        <!-- General Error -->
        <?php if (!empty($errors['general'] ?? '')): ?>
            <div class="alert alert-error">
                ❌ <?php echo htmlspecialchars($errors['general']); ?>
            </div>
        <?php endif; ?>

        <form
            method="POST"
            action="../controllers/AuthController.php?action=store"
            onsubmit="return validateRegisterForm()"
        >

            <!-- Full Name -->
            <div class="form-group">

                <label for="name">Full Name *</label>

                <input
                    type="text"
                    id="name"
                    name="name"
                    placeholder="e.g. Ahmed Rahman"
                    value="<?php echo htmlspecialchars($_POST['name'] ?? ''); ?>"
                >

                <div class="error-text" id="name-error">
                    <?php echo htmlspecialchars($errors['name'] ?? ''); ?>
                </div>

            </div>

            <!-- Email -->
            <div class="form-group">

                <label for="email">Email Address *</label>

                <input
                    type="email"
                    id="email"
                    name="email"
                    placeholder="e.g. ahmed@example.com"
                    value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>"
                >

                <div class="error-text" id="email-error">
                    <?php echo htmlspecialchars($errors['email'] ?? ''); ?>
                </div>

            </div>

            <!-- Password -->
            <div class="form-group">

                <label for="password">
                    Password * (min. 8 characters)
                </label>

                <input
                    type="password"
                    id="password"
                    name="password"
                    placeholder="Enter a strong password"
                >

                <div class="error-text" id="password-error">
                    <?php echo htmlspecialchars($errors['password'] ?? ''); ?>
                </div>

            </div>

            <!-- Confirm Password -->
            <div class="form-group">

                <label for="confirm_password">
                    Confirm Password *
                </label>

                <input
                    type="password"
                    id="confirm_password"
                    name="confirm_password"
                    placeholder="Re-enter your password"
                >

                <div class="error-text" id="confirm-error">
                    <?php echo htmlspecialchars($errors['confirm'] ?? ''); ?>
                </div>

            </div>

            <!-- Role -->
            <div class="form-group">

                <label for="role">Account Type *</label>

                <select id="role" name="role">

                    <option
                        value="customer"
                        <?php echo (($_POST['role'] ?? '') === 'customer') ? 'selected' : ''; ?>
                    >
                        Customer
                    </option>

                    <option
                        value="admin"
                        <?php echo (($_POST['role'] ?? '') === 'admin') ? 'selected' : ''; ?>
                    >
                        Admin
                    </option>

                </select>

                <div class="error-text" id="role-error"></div>

            </div>

            <!-- Button -->
            <div class="form-actions">
                <button type="submit" class="btn btn-primary">
                    Create Account
                </button>
            </div>

        </form>

        <div class="form-footer">
            Already have an account?
            <a href="../controllers/AuthController.php?action=login">
                Log In
            </a>
        </div>

    </div>

</div>

<script src="../public/js/auth.js"></script>

<?php include __DIR__ . '/../layout/footer.php'; ?>
=======
<?php $title = 'Register'; require __DIR__ . '/../layout/header.php'; ?>
<div class="card" style="max-width:480px;margin:auto">
  <h1>Create account</h1>
  <?php foreach ($errors as $er): ?><p class="err"><?= e($er) ?></p><?php endforeach; ?>
  <form method="post" id="regForm" novalidate>
    <?= csrf_field() ?>
    <p><label>Name<br><input name="name" value="<?= e($name) ?>" required minlength="2" maxlength="100"></label></p>
    <p><label>Email<br><input type="email" name="email" value="<?= e($email) ?>" required maxlength="150"></label></p>
    <p><label>Password<br><input type="password" name="password" id="pw" required minlength="8"></label>
       <br><span class="muted">Min 8 chars, with a letter and a number.</span></p>
    <button type="submit">Register</button>
  </form>
</div>
<script>
// JS validation (client side)
document.getElementById('regForm').addEventListener('submit', function (e) {
  const name  = this.name.value.trim();
  const email = this.email.value.trim();
  const pw    = this.password.value;
  const emailOk = /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
  const pwOk    = pw.length >= 8 && /[A-Za-z]/.test(pw) && /\d/.test(pw);
  if (name.length < 2 || !emailOk || !pwOk) {
    e.preventDefault();
    alert('Please fix: name 2+ chars, valid email, password 8+ with letter & number.');
  }
});
</script>
<?php require __DIR__ . '/../layout/footer.php'; ?>
>>>>>>> origin/feature/task4-22-49881-3
