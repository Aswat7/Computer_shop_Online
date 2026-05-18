<?php $title = $product['name']; require __DIR__ . '/../layout/header.php'; ?>
<div class="card">
  <h1><?= e($product['name']) ?></h1>
  <p class="muted"><?= e($product['description']) ?></p>
  <p><b>₹<?= number_format($product['price'], 2) ?></b> · Stock: <?= (int)$product['stock'] ?></p>

  <?php if ($u && $u['role'] === 'customer'): ?>
    <form id="cartForm" class="row" novalidate>
      <input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>">
      <input type="hidden" name="product_id" value="<?= $pid ?>">
      <input type="number" name="quantity" value="1" min="1" max="<?= (int)$product['stock'] ?>" style="max-width:100px">
      <button type="submit">Add to Cart</button>
      <span id="cartMsg"></span>
    </form>
  <?php endif; ?>
</div>

<div class="card">
  <h2>Reviews</h2>
  <div id="reviews">Loading...</div>

  <?php if ($u && $u['role'] === 'customer'): ?>
    <h3>Add a Review</h3>
    <form id="reviewForm" novalidate>
      <input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>">
      <input type="hidden" name="product_id" value="<?= $pid ?>">
      <p><textarea name="comment" required maxlength="1000" placeholder="Your honest review..."></textarea></p>
      <button type="submit">Submit Review</button>
      <span id="msg" class="muted"></span>
    </form>
  <?php else: ?>
    <p class="muted">Log in as a customer to post a review.</p>
  <?php endif; ?>
</div>

<script>
const PID     = <?= (int)$pid ?>;
const MY_UID  = <?= $u ? (int)$u['id'] : 0 ?>;
const MY_ROLE = <?= json_encode($u['role'] ?? '') ?>;

function esc(s){ return $('<div>').text(s == null ? '' : s).html(); }

// AJAX/JSON endpoint: list reviews
function loadReviews(){
  $.getJSON('/api/reviews_list.php', { product_id: PID }, function (d) {
    let h = '';
    if (!d.reviews.length) h = '<p class="muted">No reviews yet.</p>';
    d.reviews.forEach(r => {
      const canDel = (MY_UID && r.user_id == MY_UID) || MY_ROLE === 'admin';
      h += `<div class="review">
        <b>${esc(r.reviewer)}</b> <small class="muted">${esc(r.created_at)}</small>
        <p>${esc(r.comment)}</p>
        ${canDel ? `<button class="danger del" data-id="${r.id}">Delete</button>` : ''}
      </div>`;
    });
    $('#reviews').html(h);
  });
}
loadReviews();

// JS validation + AJAX submit for review
$('#reviewForm').on('submit', function (e) {
  e.preventDefault();
  const c = $(this).find('[name=comment]').val().trim();
  if (!c)           { $('#msg').text('Comment cannot be empty.'); return; }
  if (c.length>1000){ $('#msg').text('Max 1000 chars.'); return; }
  $.post('/api/reviews_add.php', $(this).serialize(), function () {
    $('#msg').text('Posted!'); $('#reviewForm')[0].reset(); loadReviews();
  }, 'json').fail(x => $('#msg').text(x.responseJSON?.error || 'Error'));
});

$(document).on('click', '.del', function () {
  if (!confirm('Delete this review?')) return;
  $.ajax({
    url: '/api/reviews_delete.php', type: 'POST', dataType: 'json',
    data: { id: $(this).data('id'), csrf: '<?= e(csrf_token()) ?>' }
  }).done(loadReviews).fail(x => alert(x.responseJSON?.error || 'Error'));
});

// JS validation + AJAX add-to-cart
$('#cartForm').on('submit', function (e) {
  e.preventDefault();
  const q = parseInt(this.quantity.value, 10);
  if (!q || q < 1) { $('#cartMsg').text('Invalid quantity'); return; }
  $.post('/api/cart_add.php', $(this).serialize(), function () {
    $('#cartMsg').html('<span class="ok">Added to cart.</span>');
  }, 'json').fail(x => $('#cartMsg').html('<span class="err">' + (x.responseJSON?.error || 'Error') + '</span>'));
});
</script>
<?php require __DIR__ . '/../layout/footer.php'; ?>
