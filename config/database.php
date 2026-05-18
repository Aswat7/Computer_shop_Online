<?php

$host = "localhost";
$user = "root";
$pass = "";
$db   = "computer_shop"; // change if your DB name is different

$conn = mysqli_connect($host, $user, $pass, $db);

if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}