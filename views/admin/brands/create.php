<?php include __DIR__ . '/../../layout/header.php'; ?>

<div class="page-title">🏷️ Add New Brand</div>

<?php if (!empty($error)): ?>
    <div class="alert alert-error">❌ <?php echo htmlspecialchars($error); ?></div>
<?php endif; ?>

<div class="form-box">
    <form method="POST" action="../controllers/AdminBrandController.php?action=store" onsubmit="return validateBrandForm()">

        <div class="form-group">
            <label>Brand Name *</label>
            <input type="text" name="name"
                   value="<?php echo htmlspecialchars($_POST['name'] ?? ''); ?>">
        </div>

        <div class="form-group">
            <label>Category *</label>
            <select name="category_id">
                <option value="">— Select Category —</option>
                <?php foreach ($categories as $cat): ?>
                    <option value="<?php echo $cat['id']; ?>"
                        <?php echo (isset($_POST['category_id']) && $_POST['category_id'] == $cat['id']) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($cat['name']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Save Brand</button>
            <a href="../controllers/AdminBrandController.php?action=list" class="btn btn-secondary">Cancel</a>
        </div>

    </form>
</div>

<script src="../public/js/brand.js"></script>

<?php include __DIR__ . '/../../layout/footer.php'; ?>