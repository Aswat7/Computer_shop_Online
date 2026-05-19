<?php
session_start();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Product Details</title>
</head>
<body>

<img src="<?php echo $product['image_path']; ?>" width="250">

<h1><?php echo htmlspecialchars($product['name']); ?></h1>

<p><?php echo htmlspecialchars($product['description']); ?></p>

<p>
<?php echo htmlspecialchars($product['manufacturer_review']); ?>
</p>

<h3>৳<?php echo $product['price']; ?></h3>

<p>
Stock:
<?php
if($product['stock'] > 0){
    echo "Available";
}else{
    echo "Out of Stock";
}
?>
</p>

<?php if(isset($_SESSION['user_id'])) { ?>

<input type="number" id="qty" value="1" min="1">

<button onclick="addToCart(<?php echo $product['id']; ?>)">
Add To Cart
</button>

<?php } else { ?>

<a href="login.php">Login to order</a>

<?php } ?>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<script>
function addToCart(productId){

    let quantity = document.getElementById('qty').value;

    $.ajax({
        url: 'api/cart/add.php',
        method: 'POST',
        data: {
            product_id: productId,
            quantity: quantity
        },

        success:function(response){
            alert(response.message);
        }
    });
}
</script>

</body>
</html>
