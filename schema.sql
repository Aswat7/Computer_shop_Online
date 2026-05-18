-- schema.sql — Database setup for computer_shop
-- Run this file (or use setup_db.php) to initialise the database.

CREATE DATABASE IF NOT EXISTS computer_shop CHARACTER SET utf8mb4;
USE computer_shop;

-- Users
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    role ENUM('admin', 'customer') NOT NULL DEFAULT 'customer',
    profile_picture VARCHAR(255) DEFAULT NULL,
    remember_token VARCHAR(255) DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Remember-me tokens (used by the "Remember me" login feature)
CREATE TABLE IF NOT EXISTS remember_tokens (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    token_hash VARCHAR(255) NOT NULL,
    expires_at DATETIME NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Categories
CREATE TABLE IF NOT EXISTS categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    parent_id INT DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (parent_id) REFERENCES categories(id) ON DELETE RESTRICT
);

-- Brands
CREATE TABLE IF NOT EXISTS brands (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    category_id INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE RESTRICT
);

-- Products
CREATE TABLE IF NOT EXISTS products (
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
);

-- Cart (named 'cart' to match application code)
CREATE TABLE IF NOT EXISTS cart (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    product_id INT NOT NULL,
    quantity INT NOT NULL DEFAULT 1,
    added_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY uniq_user_product (user_id, product_id),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
);

-- Orders
CREATE TABLE IF NOT EXISTS orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    total_amount DECIMAL(10, 2) NOT NULL,
    payment_method ENUM('cash_on_delivery', 'online_wallet') NOT NULL,
    status VARCHAR(50) NOT NULL DEFAULT 'pending',
    order_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Order Items
CREATE TABLE IF NOT EXISTS order_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    product_id INT NOT NULL,
    quantity INT NOT NULL,
    unit_price DECIMAL(10, 2) NOT NULL,
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
);

-- Reviews
CREATE TABLE IF NOT EXISTS reviews (
    id INT AUTO_INCREMENT PRIMARY KEY,
    product_id INT NOT NULL,
    user_id INT NOT NULL,
    reviewer_name VARCHAR(255) NOT NULL,
    comment TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Demo data (requires categories and brands to exist first)
-- Insert a demo category and brand so sample products can be added
INSERT IGNORE INTO categories (id, name) VALUES (1, 'Computer Peripherals');
INSERT IGNORE INTO brands (id, name, category_id) VALUES (1, 'Generic', 1);

INSERT IGNORE INTO users (name, email, password_hash, role) VALUES
('Admin', 'admin@shop.test',
 '$2y$10$e0Mwz7p8u3qjk2QwGdGCa.WuoQ8kS3xQ1QVqv5T7n6q3uA7gO9C6S', 'admin'),
('Alice', 'alice@shop.test',
 '$2y$10$e0Mwz7p8u3qjk2QwGdGCa.WuoQ8kS3xQ1QVqv5T7n6q3uA7gO9C6S', 'customer');
-- Password for both demo users: Passw0rd!

INSERT IGNORE INTO products (name, description, price, category_id, brand_id, stock) VALUES
('Wireless Mouse',    'Ergonomic 2.4GHz wireless mouse', 799.00,  1, 1, 25),
('Mechanical Keyboard','RGB blue switches, 87 keys',     3499.00, 1, 1, 10),
('USB-C Hub',         '7-in-1 aluminum hub',             1599.00, 1, 1, 15);
