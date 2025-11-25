-- winter-E-com Database Schema
-- Create database
CREATE DATABASE IF NOT EXISTS `winter-e-com`;
USE `winter-e-com`;

-- Users table
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'user') DEFAULT 'user',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Categories table
CREATE TABLE IF NOT EXISTS categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Products table
CREATE TABLE IF NOT EXISTS products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    category_id INT NOT NULL,
    name VARCHAR(200) NOT NULL,
    price DECIMAL(10, 2) NOT NULL,
    description TEXT,
    stock INT DEFAULT 0,
    image VARCHAR(255) DEFAULT 'default-product.jpg',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE CASCADE
);

-- Cart table
CREATE TABLE IF NOT EXISTS cart (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    product_id INT NOT NULL,
    quantity INT DEFAULT 1,
    added_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
    UNIQUE KEY unique_user_product (user_id, product_id)
);

-- Orders table
CREATE TABLE IF NOT EXISTS orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    total_amount DECIMAL(10, 2) NOT NULL,
    status ENUM('pending', 'processing', 'completed', 'cancelled') DEFAULT 'pending',
    date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Order items table
CREATE TABLE IF NOT EXISTS order_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    product_id INT NOT NULL,
    quantity INT NOT NULL,
    price DECIMAL(10, 2) NOT NULL,
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
);

-- Insert default admin user (password: 12345678)
INSERT INTO users (username, email, password, role) VALUES 
('admin', 'admin@winter-e-com.com', '$2y$10$4QgEUIy0VCf2FUeCoz0qHuBl1uwPmrOiF/qNkYlZYd1yG620Bzh/6', 'admin');

-- Insert categories
-- Insert categories
INSERT INTO categories (name, description) VALUES
('Jackets & Coats', 'Premium winter jackets, parkas, and coats to keep you warm and stylish'),
('Scarves & Wraps', 'Luxurious scarves, shawls, and wraps for ultimate comfort'),
('Gloves & Mittens', 'High-quality gloves and mittens for cold weather protection'),
('Winter Boots', 'Stylish and durable winter boots for all terrains'),
('Hats & Beanies', 'Cozy hats, beanies, and caps to stay warm'),
('Sweaters & Cardigans', 'Warm knit sweaters and cardigans for layering'),
('Thermal & Base Layers', 'Moisture-wicking thermal and base layer clothing'),
('Winter Accessories', 'Socks, ear warmers, neck warmers, and more'),
('Snow Sports Gear', 'Equipment and apparel for skiing, snowboarding, and winter sports'),
('Thermal Bags & Covers', 'Insulated bags and protective covers for winter activities'),
('Seasonal Sale', 'Special discounts and clearance on select winter items');

-- Insert sample products
INSERT INTO products (category_id, name, price, description, stock, image) VALUES
-- Jackets & Coats
(1, 'Premium Winter Parka', 249.99, 'Waterproof insulated parka with thermal lining and multiple pockets', 45, 'parka.jpg'),
(1, 'Wool Blend Coat', 189.99, 'Elegant wool blend coat with silk lining and classic design', 60, 'wool-coat.jpg'),
(1, 'Down Filled Jacket', 199.99, 'Lightweight yet warm down-filled jacket with water resistance', 55, 'down-jacket.jpg'),
(1, 'Ski Jacket Pro', 279.99, 'Professional skiing jacket with advanced weather protection', 40, 'ski-jacket.jpg'),

-- Scarves & Wraps
(2, 'Cashmere Scarf', 89.99, 'Luxurious pure cashmere scarf in multiple colors', 80, 'cashmere-scarf.jpg'),
(2, 'Wool Plaid Scarf', 34.99, 'Classic wool plaid scarf with fringe edges', 100, 'plaid-scarf.jpg'),
(2, 'Infinity Scarf', 44.99, 'Versatile infinity scarf in soft acrylic blend', 85, 'infinity-scarf.jpg'),
(2, 'Pashmina Wrap', 79.99, 'Elegant pashmina wrap for formal and casual occasions', 70, 'pashmina-wrap.jpg'),

-- Gloves & Mittens
(3, 'Leather Winter Gloves', 59.99, 'Genuine leather gloves with thermal insulation and touchscreen capability', 75, 'leather-gloves.jpg'),
(3, 'Wool Knit Mittens', 34.99, 'Cozy wool knit mittens with fleece lining', 95, 'wool-mittens.jpg'),
(3, 'Thermal Insulated Gloves', 49.99, 'Advanced thermal gloves with moisture-wicking technology', 80, 'thermal-gloves.jpg'),
(3, 'Waterproof Snow Mittens', 54.99, 'Heavy-duty mittens perfect for snow activities', 70, 'snow-mittens.jpg'),

-- Winter Boots
(4, 'Insulated Snow Boots', 129.99, 'Waterproof insulated boots with grip sole for snow and ice', 65, 'snow-boots.jpg'),
(4, 'Leather Winter Boots', 159.99, 'Premium leather boots with thermal lining and stylish design', 50, 'leather-boots.jpg'),
(4, 'Hiking Winter Boots', 189.99, 'All-terrain winter hiking boots with enhanced ankle support', 45, 'hiking-boots.jpg'),
(4, 'Comfort Thermal Boots', 99.99, 'Lightweight comfort boots perfect for everyday winter wear', 80, 'comfort-boots.jpg'),

-- Hats & Beanies
(5, 'Wool Beanie Cap', 29.99, 'Classic wool beanie in various colors with fleece lining', 120, 'beanie.jpg'),
(5, 'Winter Trapper Hat', 49.99, 'Vintage style trapper hat with ear flaps and insulation', 70, 'trapper-hat.jpg'),
(5, 'Knit Pom Hat', 34.99, 'Stylish knit hat with oversized pom-pom decoration', 100, 'pom-hat.jpg'),
(5, 'Thermal Winter Cap', 39.99, 'Advanced thermal cap with moisture management', 85, 'thermal-cap.jpg'),

-- Sweaters & Cardigans
(6, 'Merino Wool Sweater', 94.99, 'Fine merino wool sweater with premium comfort', 55, 'merino-sweater.jpg'),
(6, 'Cable Knit Cardigan', 84.99, 'Classic cable knit cardigan with button front', 60, 'cardigan.jpg'),
(6, 'Chunky Knit Sweater', 79.99, 'Cozy chunky knit sweater perfect for layering', 70, 'chunky-sweater.jpg'),
(6, 'Turtleneck Sweater', 69.99, 'Warm turtleneck sweater in premium fabric', 75, 'turtleneck.jpg'),

-- Thermal & Base Layers
(7, 'Thermal Leggings', 44.99, 'Moisture-wicking thermal leggings for active wear', 90, 'thermal-leggings.jpg'),
(7, 'Base Layer Top', 39.99, 'Breathable base layer top for temperature regulation', 100, 'base-layer-top.jpg'),
(7, 'Thermal Underwear Set', 54.99, 'Complete thermal underwear set with superior insulation', 85, 'thermal-set.jpg'),
(7, 'Merino Wool Base Layer', 69.99, 'Premium merino wool base layer for cold weather', 65, 'merino-base.jpg'),

-- Winter Accessories
(8, 'Wool Thermal Socks', 19.99, 'Pack of 3 pairs of wool thermal socks', 150, 'thermal-socks.jpg'),
(8, 'Fleece Neck Warmer', 24.99, 'Versatile fleece neck warmer and face protection', 110, 'neck-warmer.jpg'),
(8, 'Ear Warmer Headband', 22.99, 'Soft ear warmer headband for extreme cold', 130, 'ear-warmer.jpg'),
(8, 'Hand Warmers Pack', 14.99, 'Reusable hand warmers for extended warmth', 200, 'hand-warmers.jpg'),

-- Snow Sports Gear
(9, 'Ski Goggles Pro', 129.99, 'High-definition ski goggles with UV protection', 50, 'ski-goggles.jpg'),
(9, 'Snowboard Helmet', 159.99, 'Safety-certified snowboard helmet with ventilation', 45, 'snowboard-helmet.jpg'),
(9, 'Snow Leggings', 89.99, 'Insulated snow leggings for skiing and snowboarding', 60, 'snow-leggings.jpg'),
(9, 'Thermal Ski Shirt', 79.99, 'Moisture-wicking ski shirt with thermal properties', 70, 'ski-shirt.jpg'),

-- Thermal Bags & Covers
(10, 'Insulated Boot Bag', 39.99, 'Protective insulated bag for winter boots', 80, 'boot-bag.jpg'),
(10, 'Winter Gear Storage Bag', 49.99, 'Large insulated storage bag for seasonal items', 65, 'storage-bag.jpg'),
(10, 'Thermal Hand Bag', 44.99, 'Heated hand bag with rechargeable thermal pack', 55, 'hand-bag.jpg'),
(10, 'Equipment Cover Set', 59.99, 'Set of protective covers for winter equipment', 50, 'cover-set.jpg'),

-- Seasonal Sale
(11, 'Clearance Winter Bundle', 149.99, 'Assorted winter accessories bundle at special price', 100, 'bundle.jpg'),
(11, 'Last Season Coat', 119.99, 'Premium coat from last season on clearance', 30, 'clearance-coat.jpg');
