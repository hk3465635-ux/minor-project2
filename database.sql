CREATE DATABASE IF NOT EXISTS fd_trend_shop CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE fd_trend_shop;

CREATE TABLE IF NOT EXISTS admins (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(80) NOT NULL UNIQUE,
  password_hash VARCHAR(255) NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS products (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(180) NOT NULL,
  category VARCHAR(80) NOT NULL,
  subcategory VARCHAR(100) NOT NULL DEFAULT '',
  brand VARCHAR(100) NOT NULL DEFAULT 'FD Basics',
  description TEXT NOT NULL,
  price DECIMAL(10,2) NOT NULL,
  mrp DECIMAL(10,2) NOT NULL,
  stock INT UNSIGNED NOT NULL DEFAULT 0,
  image_url VARCHAR(1000) NULL,
  emoji VARCHAR(30) NOT NULL DEFAULT '👕',
  color VARCHAR(80) NOT NULL DEFAULT 'Assorted',
  material VARCHAR(120) NOT NULL DEFAULT 'Cotton blend',
  sizes VARCHAR(120) NOT NULL DEFAULT 'S,M,L,XL',
  featured TINYINT(1) NOT NULL DEFAULT 0,
  active TINYINT(1) NOT NULL DEFAULT 1,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  INDEX idx_products_category_active (category, active),
  INDEX idx_products_name (name)
);

CREATE TABLE IF NOT EXISTS orders (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  customer_name VARCHAR(150) NOT NULL,
  email VARCHAR(190) NOT NULL,
  phone VARCHAR(40) NOT NULL,
  address TEXT NOT NULL,
  total DECIMAL(10,2) NOT NULL,
  status VARCHAR(40) NOT NULL DEFAULT 'Placed',
  payment_method VARCHAR(80) NOT NULL DEFAULT 'Cash on Delivery (demo)',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  INDEX idx_orders_email (email)
);

CREATE TABLE IF NOT EXISTS order_items (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  order_id BIGINT UNSIGNED NOT NULL,
  product_id INT UNSIGNED NULL,
  product_name VARCHAR(180) NOT NULL,
  unit_price DECIMAL(10,2) NOT NULL,
  quantity INT UNSIGNED NOT NULL,
  size VARCHAR(20) NOT NULL DEFAULT 'M',
  FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
  FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE SET NULL
);

-- Create an admin interactively after import: php admin/create_admin.php

INSERT INTO products
(name,category,brand,description,price,mrp,stock,emoji,color,material,sizes,featured,active)
SELECT * FROM (
 SELECT 'Classic Oversized T-Shirt','T-Shirts','FD Basics','Soft everyday cotton-blend oversized tee with a relaxed fit.',399.00,599.00,25,'👕','Black','Cotton blend','S,M,L,XL,XXL',1 AS featured,1 AS active
 UNION ALL SELECT 'Graphic Streetwear Tee','T-Shirts','Streetline','Bold graphic print for casual streetwear outfits.',499.00,699.00,18,'🎽','White','Cotton','S,M,L,XL',1,1
 UNION ALL SELECT 'Oxford Casual Shirt','Shirts','FD Basics','Smart casual button-down shirt for college and everyday wear.',799.00,999.00,14,'👔','Sky Blue','Cotton blend','S,M,L,XL',0,1
 UNION ALL SELECT 'Oversized Hoodie','Hoodies','North Studio','Warm brushed-fleece hoodie with a roomy silhouette.',1199.00,1599.00,12,'🧥','Charcoal','Fleece cotton blend','S,M,L,XL',1,1
 UNION ALL SELECT 'Relaxed Fit Blue Jeans','Jeans','Denim Lab','Everyday blue denim with a comfortable relaxed fit.',999.00,1399.00,16,'👖','Blue','Denim','28,30,32,34,36',1,1
 UNION ALL SELECT 'Wide-Leg Trousers','Trousers','Modern Form','Tailored wide-leg trousers with a clean minimal look.',899.00,1199.00,11,'👖','Beige','Poly-viscose blend','28,30,32,34,36',0,1
 UNION ALL SELECT 'Floral Midi Dress','Dresses','Bloom Wear','Lightweight floral midi dress for casual outings.',1299.00,1799.00,9,'👗','Floral','Rayon blend','XS,S,M,L,XL',1,1
 UNION ALL SELECT 'Everyday Denim Jacket','Jackets','Denim Lab','Layering jacket with classic denim styling.',1499.00,1999.00,7,'🧥','Indigo','Denim','S,M,L,XL',0,1
 UNION ALL SELECT 'Printed Cotton Kurta','Ethnic Wear','Saanjh','Comfortable printed kurta for everyday ethnic styling.',699.00,999.00,20,'🥻','Maroon','Cotton','S,M,L,XL,XXL',0,1
 UNION ALL SELECT 'Minimal White Sneakers','Footwear','Step Studio','Versatile low-top sneakers for everyday outfits.',1599.00,2199.00,8,'👟','White','Synthetic upper','6,7,8,9,10',1,1
 UNION ALL SELECT 'Everyday Tote Bag','Accessories','Carry Co.','Roomy tote bag for books, essentials and daily use.',349.00,499.00,22,'👜','Tan','Canvas','One Size',0,1
 UNION ALL SELECT 'Classic Baseball Cap','Accessories','Streetline','Adjustable cap to finish casual and sporty outfits.',299.00,399.00,30,'🧢','Black','Cotton twill','One Size',0,1
) AS seed
WHERE NOT EXISTS (SELECT 1 FROM products LIMIT 1);
