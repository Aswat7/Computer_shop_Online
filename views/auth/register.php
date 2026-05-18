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
