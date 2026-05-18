<!DOCTYPE html>
<html>
<head>
    <title>Cart</title>
</head>
<body>

<h1>Your Cart</h1>

<table border="1" cellpadding="10">

<tr>
    <th>Name</th>
    <th>Quantity</th>
    <th>Price</th>
    <th>Subtotal</th>
    <th>Action</th>
</tr>

<?php
$total = 0;

while($row = mysqli_fetch_assoc($items)) {

$subtotal = $row['quantity'] * $row['price'];
$total += $subtotal;
?>

<tr>

<td><?php echo $row['name']; ?></td>

<td>
<input type="number" value="<?php echo $row['quantity']; ?>"
 onchange="updateCart(<?php echo $row['id']; ?>, this.value)">
</td>

<td><?php echo $row['price']; ?></td>

<td><?php echo $subtotal; ?></td>

<td>
<button onclick="removeCart(<?php echo $row['id']; ?>)">
Remove
</button>
</td>

</tr>

<?php } ?>

</table>

<h2>Total: ৳<?php echo $total; ?></h2>

</body>
</html>
