<?php
// setup_db.php — Run this once to create the database and all tables.
$servername = "localhost";
$username   = "root";
$password   = "";

$conn = mysqli_connect($servername, $username, $password);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
echo "1. Connected to MySQL successfully.<br>";

$sql_db = "CREATE DATABASE IF NOT EXISTS computer_shop";
if ($conn->query($sql_db) === TRUE) {
    echo "2. Database 'computer_shop' is ready.<br>";
}

mysqli_select_db($conn, "computer_shop");
echo "3. Database selected. Creating tables...<br><br>";

// Users
$u = "CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    role ENUM('admin', 'customer') NOT NULL DEFAULT 'customer',
    profile_picture VARCHAR(255) DEFAULT NULL,
    remember_token VARCHAR(255) DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";
if ($conn->query($u)) echo "✅ Users table created.<br>";
else echo "❌ Users: " . $conn->error . "<br>";

// Remember-me tokens
$rt = "CREATE TABLE IF NOT EXISTS remember_tokens (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    token_hash VARCHAR(255) NOT NULL,
    expires_at DATETIME NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
)";
if ($conn->query($rt)) echo "✅ Remember Tokens table created.<br>";
else echo "❌ Remember Tokens: " . $conn->error . "<br>";

// Categories
$c = "CREATE TABLE IF NOT EXISTS categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    parent_id INT DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (parent_id) REFERENCES categories(id) ON DELETE RESTRICT
)";
if ($conn->query($c)) echo "✅ Categories table created.<br>";
else echo "❌ Categories: " . $conn->error . "<br>";

// Brands
$b = "CREATE TABLE IF NOT EXISTS brands (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    category_id INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE RESTRICT
)";
if ($conn->query($b)) echo "✅ Brands table created.<br>";
else echo "❌ Brands: " . $conn->error . "<br>";

// Products
$p = "CREATE TABLE IF NOT EXISTS products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    manufacturer_review TEXT DEFAULT NULL,
    price DECIMAL(10, 2) NOT NULL,
    category_id INT NOT NULL,
    brand_id INT NOT NULL,
    stock INT NOT NULL DEFAULT 0,
    image_path VARCHAR(255) DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE RESTRICT,
    FOREIGN KEY (brand_id) REFERENCES brands(id) ON DELETE RESTRICT
)";
if ($conn->query($p)) echo "✅ Products table created.<br>";
else echo "❌ Products: " . $conn->error . "<br>";

// Cart
$ct = "CREATE TABLE IF NOT EXISTS cart (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    product_id INT NOT NULL,
    quantity INT NOT NULL DEFAULT 1,
    added_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY uniq_user_product (user_id, product_id),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
)";
if ($conn->query($ct)) echo "✅ Cart table created.<br>";
else echo "❌ Cart: " . $conn->error . "<br>";

// Orders
$o = "CREATE TABLE IF NOT EXISTS orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    total_amount DECIMAL(10, 2) NOT NULL,
    payment_method ENUM('cash_on_delivery', 'online_wallet') NOT NULL,
    status VARCHAR(50) NOT NULL DEFAULT 'pending',
    order_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
)";
if ($conn->query($o)) echo "✅ Orders table created.<br>";
else echo "❌ Orders: " . $conn->error . "<br>";

// Order Items
$oi = "CREATE TABLE IF NOT EXISTS order_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    product_id INT NOT NULL,
    quantity INT NOT NULL,
    unit_price DECIMAL(10, 2) NOT NULL,
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
)";
if ($conn->query($oi)) echo "✅ Order Items table created.<br>";
else echo "❌ Order Items: " . $conn->error . "<br>";

// Reviews
$r = "CREATE TABLE IF NOT EXISTS reviews (
    id INT AUTO_INCREMENT PRIMARY KEY,
    product_id INT NOT NULL,
    user_id INT NOT NULL,
    reviewer_name VARCHAR(255) NOT NULL,
    comment TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
)";
if ($conn->query($r)) echo "✅ Reviews table created.<br>";
else echo "❌ Reviews: " . $conn->error . "<br>";

echo "<h3>🎉 FRESH START COMPLETE! Database 'computer_shop' is ready.</h3>";
$conn->close();
?>
