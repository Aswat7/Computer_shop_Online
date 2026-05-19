<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
?>

<?php include __DIR__ . '/../../layout/header.php'; ?>

<div class="page-title">📦 Edit Product</div>

<?php if (!empty($error)): ?>
    <div class="alert alert-error">
        ❌ <?php echo htmlspecialchars($error); ?>
    </div>
<?php endif; ?>

<div class="form-box" style="max-width:700px;">

    <form method="POST"
          action="../controllers/AdminProductController.php?action=update"
          enctype="multipart/form-data">

        <input type="hidden" name="id" value="<?php echo $product['id']; ?>">

        <div class="form-group">
            <label for="name">Product Name *</label>
            <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($product['name']); ?>">
        </div>

        <div class="form-group">
            <label for="description">Description *</label>
            <textarea id="description" name="description"><?php echo htmlspecialchars($product['description']); ?></textarea>
        </div>

        <div class="form-group">
            <label for="manufacturer_review">Manufacturer Review</label>
            <textarea id="manufacturer_review" name="manufacturer_review"><?php echo htmlspecialchars($product['manufacturer_review'] ?? ''); ?></textarea>
        </div>

        <div class="form-group">
            <label for="price">Price *</label>
            <input type="number" id="price" step="0.01" name="price" value="<?php echo $product['price']; ?>">
        </div>

        <div class="form-group">
            <label for="stock">Stock *</label>
            <input type="number" id="stock" name="stock" value="<?php echo $product['stock']; ?>">
        </div>

        <div class="form-group">
            <label for="category_id">Category *</label>
            <select id="category_id" name="category_id">
                <?php foreach ($categories as $cat): ?>
                    <option value="<?php echo $cat['id']; ?>" <?php echo ($product['category_id'] == $cat['id']) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($cat['name']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label for="brand_id">Brand *</label>
            <select id="brand_id" name="brand_id">
                <?php foreach ($brands as $b): ?>
                    <option value="<?php echo $b['id']; ?>" <?php echo ($product['brand_id'] == $b['id']) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($b['name']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <?php if (!empty($product['image_path'])): ?>
            <div class="form-group">
                <label>Current Image</label><br>
                <img src="/Task2/public/<?php echo htmlspecialchars($product['image_path']); ?>"
                     style="width:100px; height:100px; object-fit:cover; border-radius:6px; border:1px solid #ddd; margin-top:8px;">
            </div>
        <?php endif; ?>

        <div class="form-group">
            <label for="image">Upload New Image</label>
            <input type="file" id="image" name="image" accept=".jpg,.jpeg,.png">
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Update Product</button>
            <a href="../controllers/AdminProductController.php?action=list" class="btn btn-secondary">Cancel</a>
        </div>

    </form>
</div>

<?php include __DIR__ . '/../../layout/footer.php'; ?>