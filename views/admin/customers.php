<?php $title = 'Customers'; require __DIR__ . '/../layout/header.php'; ?>
<div class="card"><h1>Customers</h1>
<table>
  <tr><th>#</th><th>Name</th><th>Email</th><th>Joined</th><th></th></tr>
  <?php while ($u = $customers->fetch_assoc()): ?>
  <tr>
    <td><?= (int)$u['id'] ?></td>
    <td><?= e($u['name']) ?></td>
    <td><?= e($u['email']) ?></td>
    <td><?= e($u['created_at']) ?></td>
    <td>
      <form method="post" onsubmit="return confirm('Delete this customer?')">
        <?= csrf_field() ?>
        <input type="hidden" name="action" value="delete">
        <input type="hidden" name="id" value="<?= (int)$u['id'] ?>">
        <button class="danger">Delete</button>
      </form>
    </td>
  </tr>
  <?php endwhile; ?>
</table></div>
<?php require __DIR__ . '/../layout/footer.php'; ?>
