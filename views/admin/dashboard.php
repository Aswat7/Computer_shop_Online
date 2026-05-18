<?php $title = 'Admin Dashboard'; require __DIR__ . '/../layout/header.php'; ?>
<div class="card"><h1>Admin Dashboard</h1></div>

<div class="card"><h2>Recent Orders</h2>
<table>
  <tr><th>#</th><th>Customer</th><th>Total</th><th>Payment</th><th>Status</th><th>Date</th></tr>
  <?php while ($o = $orders->fetch_assoc()): ?>
  <tr>
    <td><?= (int)$o['id'] ?></td>
    <td><?= e($o['uname']) ?></td>
    <td>₹<?= number_format($o['total_amount'], 2) ?></td>
    <td><?= e($o['payment_method']) ?></td>
    <td><?= e($o['status']) ?></td>
    <td><?= e($o['order_date']) ?></td>
  </tr>
  <?php endwhile; ?>
</table></div>

<div class="card"><h2>Recent Reviews</h2>
<table>
  <tr><th>#</th><th>Product</th><th>User</th><th>Comment</th><th>Date</th></tr>
  <?php while ($r = $reviews->fetch_assoc()): ?>
  <tr>
    <td><?= (int)$r['id'] ?></td>
    <td><?= e($r['pname']) ?></td>
    <td><?= e($r['uname']) ?></td>
    <td><?= e($r['comment']) ?></td>
    <td><?= e($r['created_at']) ?></td>
  </tr>
  <?php endwhile; ?>
</table></div>
<?php require __DIR__ . '/../layout/footer.php'; ?>
