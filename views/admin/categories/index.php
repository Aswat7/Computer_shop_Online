<?php
include __DIR__ . '/../layout/admin_layout.php';
?>

<div class="page-title">📁 Category Management</div>

<!-- SUCCESS / ERROR MESSAGES -->
<?php if (isset($_GET['success'])): ?>
    <div class="alert alert-success">
        <?php
        if ($_GET['success'] === 'created') echo "✅ Category created successfully!";
        if ($_GET['success'] === 'updated') echo "✅ Category updated successfully!";
        if ($_GET['success'] === 'deleted') echo "✅ Category deleted successfully!";
        ?>
    </div>
<?php endif; ?>

<?php if (isset($_GET['error'])): ?>
    <div class="alert alert-error">
        <?php
        if ($_GET['error'] === 'has_children') echo "❌ Cannot delete: This category has sub-categories.";
        if ($_GET['error'] === 'has_products') echo "❌ Cannot delete: This category has products.";
        ?>
    </div>
<?php endif; ?>

<!-- ADD BUTTON -->
<div style="margin-bottom: 16px;">
    <a href="../controllers/AdminCategoryController.php?action=create" class="btn btn-primary">
        + Add New Category
    </a>
</div>

<!-- TABLE -->
<div class="table-box">
    <table>
        <tr>
            <th>#</th>
            <th>Category Name</th>
            <th>Parent Category</th>
            <th>Created At</th>
            <th>Actions</th>
        </tr>

        <?php if (empty($categories)): ?>
        <tr>
            <td colspan="5" style="text-align:center; color:#999;">
                No categories yet.
            </td>
        </tr>
        <?php endif; ?>

        <?php foreach ($categories as $cat): ?>
        <tr>
            <td><?php echo $cat['id']; ?></td>
            <td><?php echo htmlspecialchars($cat['name']); ?></td>

            <td>
                <?php if (!empty($cat['parent_name'])): ?>
                    <span class="badge badge-success">
                        <?php echo htmlspecialchars($cat['parent_name']); ?>
                    </span>
                <?php else: ?>
                    <span style="color:#999;">— Top Level —</span>
                <?php endif; ?>
            </td>

            <td>
                <?php echo date('d M Y', strtotime($cat['created_at'])); ?>
            </td>

            <td>
                <a href="../controllers/AdminCategoryController.php?action=edit&id=<?php echo $cat['id']; ?>"
                   class="btn btn-secondary btn-small">Edit</a>

                <a href="../controllers/AdminCategoryController.php?action=delete&id=<?php echo $cat['id']; ?>"
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