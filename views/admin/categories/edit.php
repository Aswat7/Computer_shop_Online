<?php include __DIR__ . '/../../layout/header.php'; ?>

<div class="page-title">📁 Edit Category</div>

<!-- ERROR MESSAGE -->
<?php if (!empty($error)): ?>
    <div class="alert alert-error">❌ <?php echo htmlspecialchars($error); ?></div>
<?php endif; ?>

<div class="form-box">
    <form method="POST" action="../controllers/AdminCategoryController.php?action=update" onsubmit="return validateCategoryForm()">

        <!-- Hidden ID so controller knows which category to update -->
        <input type="hidden" name="id" value="<?php echo $category['id']; ?>">

        <!-- Category Name -->
        <div class="form-group">
            <label for="name">Category Name *</label>
            <input type="text" id="name" name="name"
                   value="<?php echo htmlspecialchars($category['name']); ?>">
            <div class="error-text" id="name-error"></div>
        </div>

        <!-- Parent Category -->
        <div class="form-group">
            <label for="parent_id">Parent Category (optional)</label>
            <select id="parent_id" name="parent_id">
                <option value="">— None (Top Level) —</option>
                <?php foreach ($topCategories as $cat): ?>
                    <!-- Don't show itself as an option (can't be its own parent) -->
                    <?php if ($cat['id'] !== $category['id']): ?>
                        <option value="<?php echo $cat['id']; ?>"
                            <?php echo ($category['parent_id'] == $cat['id']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($cat['name']); ?>
                        </option>
                    <?php endif; ?>
                <?php endforeach; ?>
            </select>
        </div>

        <!-- Buttons -->
        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Update Category</button>
            <a href="../controllers/AdminCategoryController.php?action=list" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>

<!-- Link the external JS file -->
<script src="../public/js/category.js"></script>

<?php include __DIR__ . '/../../layout/footer.php'; ?>
