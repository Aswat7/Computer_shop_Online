<?php $title = 'Cart'; require __DIR__ . '/../layout/header.php'; ?>
<div class="card">
<h1>Your Cart</h1>
<?php if (!$items): ?>
  <p class="muted">Cart is empty. <a href="/index.php">Browse products</a>.</p>
<?php else: ?>
<table>
  <tr><th>Product</th><th>Qty</th><th>Unit</th><th>Subtotal</th><th></th></tr>
  <?php foreach ($items as $i): ?>
  <tr>
    <td><?= e($i['name']) ?></td>
    <td><?= (int)$i['quantity'] ?></td>
    <td>₹<?= number_format($i['price'], 2) ?></td>
    <td>₹<?= number_format($i['subtotal'], 2) ?></td>
    <td><button class="danger removeItem" data-id="<?= (int)$i['id'] ?>">Remove</button></td>
  </tr>
  <?php endforeach; ?>
</table>
<h3>Total: ₹<?= number_format($total, 2) ?></h3>

<h2>Checkout</h2>
<form id="checkoutForm" novalidate>
  <input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>">
  <p>
    <label><input type="radio" name="payment_method" value="cod" checked style="width:auto"> Cash on Delivery</label>
    &nbsp;&nbsp;
    <label><input type="radio" name="payment_method" value="wallet" style="width:auto"> Online Wallet</label>
  </p>
  <button type="submit">Place Order</button>
  <span id="msg" class="muted"></span>
</form>
<?php endif; ?>
</div>

<script>
$('#checkoutForm').on('submit', function (e) {
  e.preventDefault();
  const pm = $('input[name=payment_method]:checked').val();
  if (!['cod', 'wallet'].includes(pm)) { return alert('Choose a payment method'); }
  if (!confirm('Place order?')) return;
  $.post('/api/order_place.php', $(this).serialize(), function (d) {
    window.location = '/order_confirmation.php?id=' + d.order_id;
  }, 'json').fail(x => $('#msg').html('<span class="err">' + (x.responseJSON?.error || 'Error') + '</span>'));
});
$('.removeItem').on('click', function () {
  if (!confirm('Remove item?')) return;
  $.post('/api/cart_remove.php',
    { id: $(this).data('id'), csrf: '<?= e(csrf_token()) ?>' },
    function () { location.reload(); }, 'json'
  ).fail(x => alert(x.responseJSON?.error || 'Error'));
});
</script>
<?php require __DIR__ . '/../layout/footer.php'; ?>
