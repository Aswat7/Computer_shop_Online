<?php
<<<<<<< HEAD

$host = "localhost";
$user = "root";
$pass = "";
$db   = "computer_shop"; // change if your DB name is different

$conn = mysqli_connect($host, $user, $pass, $db);

if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}
=======
$servername = "localhost";
$username   = "root";
$password   = "";
$dbname     = "computer_shop";

$conn = mysqli_connect($servername, $username, $password, $dbname);

if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}
?>
>>>>>>> origin/feature/task3-22-46877-1
