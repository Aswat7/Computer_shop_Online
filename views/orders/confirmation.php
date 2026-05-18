<?php $title = 'Order #' . (int)$order['id']; require __DIR__ . '/../layout/header.php'; ?>
<div class="card">
<h1>✅ Order Confirmed</h1>
<p><b>Order ID:</b> <?= (int)$order['id'] ?></p>
<p><b>Payment:</b> <?= e($order['payment_method']) ?></p>
<p><b>Status:</b> <?= e($order['status']) ?></p>
<table>
<tr><th>Product</th><th>Qty</th><th>Unit</th><th>Subtotal</th></tr>
<?php while ($r = $items->fetch_assoc()): ?>
<tr>
  <td><?= e($r['name']) ?></td>
  <td><?= (int)$r['quantity'] ?></td>
  <td>₹<?= number_format($r['unit_price'], 2) ?></td>
  <td>₹<?= number_format($r['unit_price'] * $r['quantity'], 2) ?></td>
</tr>
<?php endwhile; ?>
</table>
<h3>Total: ₹<?= number_format($order['total_amount'], 2) ?></h3>
<a href="/index.php"><button>Continue Shopping</button></a>
</div>
<?php require __DIR__ . '/../layout/footer.php'; ?>
