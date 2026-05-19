<?php $title = 'All Reviews'; require __DIR__ . '/../layout/header.php'; ?>
<div class="card"><h1>All Reviews</h1>
<table>
  <tr><th>#</th><th>Product</th><th>User</th><th>Comment</th><th>Date</th><th></th></tr>
  <?php while ($r = $reviews->fetch_assoc()): ?>
  <tr>
    <td><?= (int)$r['id'] ?></td>
    <td><?= e($r['pname']) ?></td>
    <td><?= e($r['uname']) ?></td>
    <td><?= e($r['comment']) ?></td>
    <td><?= e($r['created_at']) ?></td>
    <td>
      <form method="post" onsubmit="return confirm('Delete review?')">
        <?= csrf_field() ?>
        <input type="hidden" name="action" value="delete">
        <input type="hidden" name="id" value="<?= (int)$r['id'] ?>">
        <button class="danger">Delete</button>
      </form>
    </td>
  </tr>
  <?php endwhile; ?>
</table></div>
<?php require __DIR__ . '/../layout/footer.php'; ?>
