<<<<<<< HEAD


<?php include __DIR__ . '/layout/admin_layout.php';?>

<div class="page-title">📊 Dashboard</div>

<!-- SUMMARY CARDS -->
<div class="cards">
    <div class="card">
        <div class="number"><?php echo $total_products; ?></div>
        <div class="label">Total Products</div>
    </div>

    <div class="card">
        <div class="number"><?php echo $total_categories; ?></div>
        <div class="label">Total Categories</div>
    </div>

    <div class="card">
        <div class="number"><?php echo $total_brands; ?></div>
        <div class="label">Total Brands</div>
    </div>

    <div class="card">
        <div class="number"><?php echo count($low_stock); ?></div>
        <div class="label">Low Stock Alerts</div>
    </div>
</div>

<!-- LOW STOCK TABLE -->
<?php if (!empty($low_stock)): ?>

    <h3 style="margin-bottom: 12px; color: #dc3545;">
        ⚠️ Low Stock Products (stock < 5)
    </h3>

    <div class="table-box">
        <table>
            <tr>
                <th>Product Name</th>
                <th>Stock Left</th>
                <th>Price (৳)</th>
                <th>Action</th>
            </tr>

            <?php foreach ($low_stock as $p): ?>
                <tr>
                    <td><?php echo htmlspecialchars($p['name']); ?></td>

                    <td>
                        <span class="badge badge-danger">
                            <?php echo $p['stock']; ?> left
                        </span>
                    </td>

                    <td><?php echo number_format($p['price'], 2); ?></td>

                    <td>
                        <a href="../controllers/AdminProductController.php?action=edit&id=<?php echo $p['id']; ?>"
                           class="btn btn-secondary btn-small">
                            Edit
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>

        </table>
    </div>

<?php else: ?>

    <p style="color: green;">✅ All products have enough stock.</p>

<?php endif; ?>

<!-- RECENT ORDERS -->
<h3 style="margin-top:24px;margin-bottom:12px;color:#1a1a2e;">🧾 Recent Orders</h3>
<div class="table-box">
    <table>
        <tr><th>Order ID</th><th>Customer</th><th>Total (৳)</th><th>Payment</th><th>Status</th><th>Date</th></tr>
        <?php if (empty($recent_orders)): ?>
            <tr><td colspan="6" style="text-align:center;color:#888;">No orders yet.</td></tr>
        <?php else: foreach ($recent_orders as $o): ?>
            <tr>
                <td>#<?php echo $o['id']; ?></td>
                <td><?php echo htmlspecialchars($o['customer_name']); ?></td>
                <td><?php echo number_format($o['total_amount'], 2); ?></td>
                <td><?php echo $o['payment_method'] === 'cash_on_delivery' ? 'COD' : 'Wallet'; ?></td>
                <td><?php echo htmlspecialchars($o['status']); ?></td>
                <td><?php echo htmlspecialchars($o['order_date']); ?></td>
            </tr>
        <?php endforeach; endif; ?>
    </table>
</div>

<!-- RECENT REVIEWS -->
<h3 style="margin-top:24px;margin-bottom:12px;color:#1a1a2e;">💬 Recent Reviews</h3>
<div class="table-box">
    <table>
        <tr><th>Product</th><th>Reviewer</th><th>Comment</th><th>Date</th></tr>
        <?php if (empty($recent_reviews)): ?>
            <tr><td colspan="4" style="text-align:center;color:#888;">No reviews yet.</td></tr>
        <?php else: foreach ($recent_reviews as $r): ?>
            <tr>
                <td><?php echo htmlspecialchars($r['product_name']); ?></td>
                <td><?php echo htmlspecialchars($r['reviewer_name']); ?></td>
                <td><?php echo htmlspecialchars($r['comment']); ?></td>
                <td><?php echo htmlspecialchars($r['created_at']); ?></td>
            </tr>
        <?php endforeach; endif; ?>
    </table>
</div>

<?php include __DIR__ . '/layout/admin_footer.php'; ?>
=======
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
>>>>>>> origin/feature/task4-22-49881-3
