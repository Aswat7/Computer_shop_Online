<?php include __DIR__ . '/../../layout/header.php'; ?>

<div class="page-title">📦 Add New Product</div>

<!-- ERROR -->
<?php if (!empty($error)): ?>
    <div class="alert alert-error">❌ <?php echo htmlspecialchars($error); ?></div>
<?php endif; ?>

<div class="form-box" style="max-width: 700px;">
    <form method="POST" action="../controllers/AdminProductController.php?action=store"
          enctype="multipart/form-data" onsubmit="return validateProductForm()">

        <!-- Product Name -->
        <div class="form-group">
            <label for="name">Product Name *</label>
            <input type="text" id="name" name="name" placeholder="e.g. Samsung 970 EVO SSD 1TB"
                   value="<?php echo htmlspecialchars($_POST['name'] ?? ''); ?>">
            <div class="error-text" id="name-error"></div>
        </div>

        <!-- Description -->
        <div class="form-group">
            <label for="description">Description *</label>
            <textarea id="description" name="description" placeholder="Write a full product description..."><?php echo htmlspecialchars($_POST['description'] ?? ''); ?></textarea>
            <div class="error-text" id="desc-error"></div>
        </div>

        <!-- Manufacturer Review -->
        <div class="form-group">
            <label for="manufacturer_review">Manufacturer Review</label>
            <textarea id="manufacturer_review" name="manufacturer_review" placeholder="Short note from the manufacturer..."><?php echo htmlspecialchars($_POST['manufacturer_review'] ?? ''); ?></textarea>
        </div>

        <!-- Price -->
        <div class="form-group">
            <label for="price">Price (৳) *</label>
            <input type="number" id="price" name="price" step="0.01" min="0.01" placeholder="e.g. 4500.00"
                   value="<?php echo htmlspecialchars($_POST['price'] ?? ''); ?>">
            <div class="error-text" id="price-error"></div>
        </div>

        <!-- Stock -->
        <div class="form-group">
            <label for="stock">Stock Quantity *</label>
            <input type="number" id="stock" name="stock" min="0" placeholder="e.g. 50"
                   value="<?php echo htmlspecialchars($_POST['stock'] ?? '0'); ?>">
            <div class="error-text" id="stock-error"></div>
        </div>

        <!-- Category — when changed, brands load via AJAX -->
        <div class="form-group">
            <label for="category_id">Category *</label>
            <select id="category_id" name="category_id" onchange="loadBrands(this.value)">
                <option value="">— Select Category —</option>
                <?php foreach ($categories as $cat): ?>
                    <option value="<?php echo $cat['id']; ?>"
                        <?php echo (isset($_POST['category_id']) && $_POST['category_id'] == $cat['id']) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($cat['name']); ?>
                        <?php echo $cat['parent_name'] ? ' (sub of ' . htmlspecialchars($cat['parent_name']) . ')' : ''; ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <div class="error-text" id="category-error"></div>
        </div>

        <!-- Brand — filled automatically by AJAX when category changes -->
        <div class="form-group">
            <label for="brand_id">Brand *</label>
            <select id="brand_id" name="brand_id">
                <option value="">— Select Category First —</option>
                <?php
                if (!empty($_POST['brand_id'])) {
                    foreach ($brands as $b) {
                        $sel = ($b['id'] == $_POST['brand_id']) ? ' selected' : '';
                        echo '<option value="' . $b['id'] . '"' . $sel . '>' . htmlspecialchars($b['name']) . '</option>';
                    }
                }
                ?>
            </select>
            <div class="error-text" id="brand-error"></div>
        </div>

        <!-- Image Upload -->
        <div class="form-group">
            <label for="image">Product Image (JPEG or PNG, max 2MB)</label>
            <input type="file" id="image" name="image" accept=".jpg,.jpeg,.png" onchange="validateImageInput(this)">
            <div class="error-text" id="image-error"></div>
        </div>

        <!-- Buttons -->
        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Save Product</button>
            <a href="../controllers/AdminProductController.php?action=list" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>

<!-- Link the external JS file (contains AJAX + all validation) -->
<script src="../public/js/product.js"></script>

<?php include __DIR__ . '/../../layout/footer.php'; ?>
