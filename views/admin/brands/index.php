<?php
include __DIR__ . '/../layout/admin_layout.php';
?>

<div class="page-title">🏷️ Brand Management</div>

<!-- MESSAGES -->
<?php if (isset($_GET['success'])): ?>
    <div class="alert alert-success">
        <?php
        if ($_GET['success'] === 'created') echo "✅ Brand created successfully!";
        if ($_GET['success'] === 'updated') echo "✅ Brand updated successfully!";
        if ($_GET['success'] === 'deleted') echo "✅ Brand deleted successfully!";
        ?>
    </div>
<?php endif; ?>

<?php if (isset($_GET['error'])): ?>
    <div class="alert alert-error">
        <?php
        if ($_GET['error'] === 'has_products') echo "❌ Cannot delete: This brand has products.";
        ?>
    </div>
<?php endif; ?>

<!-- ADD BUTTON -->
<div style="margin-bottom: 16px;">
    <a href="../controllers/AdminBrandController.php?action=create"
       class="btn btn-primary">
        + Add New Brand
    </a>
</div>

<!-- TABLE -->
<div class="table-box">
    <table>
        <tr>
            <th>#</th>
            <th>Brand Name</th>
            <th>Category</th>
            <th>Created At</th>
            <th>Actions</th>
        </tr>

        <?php if (empty($brands)): ?>
        <tr>
            <td colspan="5" style="text-align:center; color:#999;">
                No brands yet.
            </td>
        </tr>
        <?php endif; ?>

        <?php foreach ($brands as $brand): ?>
        <tr>
            <td><?php echo $brand['id']; ?></td>

            <td><?php echo htmlspecialchars($brand['name']); ?></td>

            <td>
                <span class="badge badge-success">
                    <?php echo htmlspecialchars($brand['category_name']); ?>
                </span>
            </td>

            <td>
                <?php echo date('d M Y', strtotime($brand['created_at'])); ?>
            </td>

            <td>
                <a href="../controllers/AdminBrandController.php?action=edit&id=<?php echo $brand['id']; ?>"
                   class="btn btn-secondary btn-small">
                    Edit
                </a>

                <a href="../controllers/AdminBrandController.php?action=delete&id=<?php echo $brand['id']; ?>"
                   class="btn btn-danger btn-small"
                   onclick="return confirm('Are you sure?')">
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