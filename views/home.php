<!DOCTYPE html>
<html>
<head>
    <title>Computer Shop</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<h1>All Products</h1>

<input type="text" id="search" placeholder="Search products...">

<div id="productArea">

<?php while($row = mysqli_fetch_assoc($products)) { ?>

<div class="card">

    <img src="<?php echo $row['image_path']; ?>" width="150">

    <h3><?php echo htmlspecialchars($row['name']); ?></h3>

    <p><?php echo htmlspecialchars($row['manufacturer_review']); ?></p>

    <h4>৳<?php echo $row['price']; ?></h4>

    <a href="product.php?id=<?php echo $row['id']; ?>">Details</a>

</div>

<?php } ?>

</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="assets/js/script.js"></script>

</body>
</html>
