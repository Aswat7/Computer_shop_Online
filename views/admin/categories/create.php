<?php include __DIR__ . '/../../layout/header.php'; ?>

<div class="page-title">📁 Add New Category</div>

<!-- ERROR MESSAGE -->
<?php if (!empty($error)): ?>
    <div class="alert alert-error">❌ <?php echo htmlspecialchars($error); ?></div>
<?php endif; ?>

<div class="form-box">
    <form method="POST" action="../controllers/AdminCategoryController.php?action=store" onsubmit="return validateCategoryForm()">

        <!-- Category Name -->
        <div class="form-group">
            <label for="name">Category Name *</label>
            <input type="text" id="name" name="name" placeholder="e.g. Storage, Monitor, RAM"
                   value="<?php echo htmlspecialchars($_POST['name'] ?? ''); ?>">
            <div class="error-text" id="name-error"></div>
        </div>

        <!-- Parent Category (optional) -->
        <div class="form-group">
            <label for="parent_id">Parent Category (optional)</label>
            <select id="parent_id" name="parent_id">
                <option value="">— None (Top Level Category) —</option>
                <?php foreach ($topCategories as $cat): ?>
                    <option value="<?php echo $cat['id']; ?>"
                        <?php echo (isset($_POST['parent_id']) && $_POST['parent_id'] == $cat['id']) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($cat['name']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <!-- Buttons -->
        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Save Category</button>
            <a href="../controllers/AdminCategoryController.php?action=list" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>

<!-- Link the external JS file -->
<script src="../public/js/category.js"></script>

<?php include __DIR__ . '/../../layout/footer.php'; ?>
