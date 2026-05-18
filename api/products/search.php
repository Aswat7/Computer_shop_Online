<?php
require_once '../../models/Product.php';

$q = $_GET['q'] ?? '';

$result = Product::searchProducts($q);

while($row = mysqli_fetch_assoc($result)){
?>

<div class="card">

    <img src="<?php echo $row['image_path']; ?>" width="150">

    <h3><?php echo htmlspecialchars($row['name']); ?></h3>

    <p><?php echo htmlspecialchars($row['manufacturer_review']); ?></p>

    <h4>৳<?php echo $row['price']; ?></h4>

</div>

<?php } ?>
