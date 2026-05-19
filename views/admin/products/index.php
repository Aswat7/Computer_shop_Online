<?php
include __DIR__ . '/../layout/admin_layout.php';
?>

<div class="page-title">📦 Product Management</div>

<!-- MESSAGES -->
<?php if (isset($_GET['success'])): ?>
    <div class="alert alert-success">
        <?php
        if ($_GET['success'] === 'created') echo "✅ Product created successfully!";
        if ($_GET['success'] === 'updated') echo "✅ Product updated successfully!";
        if ($_GET['success'] === 'deleted') echo "✅ Product deleted successfully!";
        ?>
    </div>
<?php endif; ?>

<!-- ADD BUTTON -->
<div style="margin-bottom: 16px;">
    <a href="../controllers/AdminProductController.php?action=create" class="btn btn-primary">
        + Add New Product
    </a>
</div>

<!-- PRODUCTS TABLE -->
<div class="table-box">
    <table>
        <tr>
            <th>Image</th>
            <th>Name</th>
            <th>Category</th>
            <th>Brand</th>
            <th>Price (৳)</th>
            <th>Stock</th>
            <th>Actions</th>
        </tr>

        <?php if (count($products) === 0): ?>
            <tr>
                <td colspan="7" style="text-align:center; color:#999;">
                    No products yet.
                </td>
            </tr>
        <?php endif; ?>

        <?php foreach ($products as $p): ?>
            <tr>

                <!-- IMAGE FIXED -->
<td>

<?php if (!empty($p['image_path'])): ?>

    <img
        src="/Task2/public/<?php echo $p['image_path']; ?>"
        width="60"
        height="60"
        style="object-fit:cover; border-radius:6px;"
    >

<?php else: ?>

    📦

<?php endif; ?>

</td>

                <td><?php echo htmlspecialchars($p['name']); ?></td>
                <td><?php echo htmlspecialchars($p['category_name']); ?></td>
                <td><?php echo htmlspecialchars($p['brand_name']); ?></td>
                <td><?php echo number_format($p['price'], 2); ?></td>

                <!-- STOCK -->
                <td>
                    <?php if ($p['stock'] < 5): ?>
                        <span class="badge badge-danger"><?php echo $p['stock']; ?></span>
                    <?php else: ?>
                        <span class="badge badge-success"><?php echo $p['stock']; ?></span>
                    <?php endif; ?>
                </td>

                <!-- ACTIONS -->
                <td>
                    <a href="../controllers/AdminProductController.php?action=edit&id=<?php echo $p['id']; ?>"
                       class="btn btn-secondary btn-small">Edit</a>

                    <a href="../controllers/AdminProductController.php?action=delete&id=<?php echo $p['id']; ?>"
                       class="btn btn-danger btn-small"
                       onclick="return confirm('Delete this product? This cannot be undone.')">
                       Delete
                    </a>
                </td>

            </tr>
        <?php endforeach; ?>

    </table>
</div>

<?php
include __DIR__ . '/../layout/admin_footer.php';
?>