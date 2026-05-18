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
