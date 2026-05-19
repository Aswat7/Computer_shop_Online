<?php $title = 'Products'; require __DIR__ . '/../layout/header.php'; ?>
<div class="card">
  <h1>Products</h1>
  <form method="get" id="searchForm" action="/index.php" class="row" novalidate>
    <input name="q" value="<?= e($q ?? '') ?>" placeholder="Search products..." maxlength="100" style="max-width:300px">
    <button type="submit">Search</button>
    <?php if (($q ?? '') !== ''): ?><a href="/index.php" class="muted">clear</a><?php endif; ?>
  </form>
</div>
<div class="grid">
<?php foreach (($products ?? []) as $p): ?>
  <div class="product">
    <h3><?= e($p['name']) ?></h3>
    <p class="muted"><?= e($p['description']) ?></p>
    <p><b>&#8377;<?= number_format((float)$p['price'], 2) ?></b> &middot; stock: <?= (int)$p['stock'] ?></p>
    <a href="/product.php?id=<?= (int)$p['id'] ?>"><button>View</button></a>
  </div>
<?php endforeach; ?>
<?php if (empty($products)): ?>
  <p class="muted">No products found.</p>
<?php endif; ?>
</div>
<script>
document.getElementById('searchForm').addEventListener('submit', function (e) {
  const v = this.q.value.trim();
  if (v.length > 100) { e.preventDefault(); alert('Search term too long.'); }
});
</script>
<?php require __DIR__ . '/../layout/footer.php'; ?>
