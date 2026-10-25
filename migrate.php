<?php
require __DIR__ . '/config.php';

if (PHP_SAPI !== 'cli') {
    http_response_code(404);
    exit;
}

function migrationColumnExists(PDO $pdo, string $table, string $column): bool {
    $query = $pdo->prepare('SELECT COUNT(*) FROM information_schema.COLUMNS WHERE TABLE_SCHEMA=DATABASE() AND TABLE_NAME=? AND COLUMN_NAME=?');
    $query->execute([$table, $column]);
    return (int)$query->fetchColumn() > 0;
}

function migrationIndexExists(PDO $pdo, string $table, string $index): bool {
    $query = $pdo->prepare('SELECT COUNT(*) FROM information_schema.STATISTICS WHERE TABLE_SCHEMA=DATABASE() AND TABLE_NAME=? AND INDEX_NAME=?');
    $query->execute([$table, $index]);
    return (int)$query->fetchColumn() > 0;
}

function migrationForeignKeyExists(PDO $pdo, string $table, string $constraint): bool {
    $query = $pdo->prepare('SELECT COUNT(*) FROM information_schema.TABLE_CONSTRAINTS WHERE CONSTRAINT_SCHEMA=DATABASE() AND TABLE_NAME=? AND CONSTRAINT_NAME=? AND CONSTRAINT_TYPE="FOREIGN KEY"');
    $query->execute([$table, $constraint]);
    return (int)$query->fetchColumn() > 0;
}

function migrationAddColumn(PDO $pdo, string $table, string $column, string $definition): void {
    if (!migrationColumnExists($pdo, $table, $column)) {
        $pdo->exec("ALTER TABLE `{$table}` ADD COLUMN `{$column}` {$definition}");
    }
}

function categorySlug(string $name): string {
    $slug = strtolower(trim($name));
    $slug = preg_replace('/[^a-z0-9]+/', '-', $slug) ?? '';
    return trim($slug, '-') ?: 'category';
}

function createCatalogIllustration(string $category, string $productName): string {
    $slug = categorySlug($productName);
    $directory = __DIR__ . '/uploads/catalog';
    if (!is_dir($directory) && !mkdir($directory, 0755, true) && !is_dir($directory)) {
        throw new RuntimeException('Unable to create local catalog illustrations directory.');
    }
    $path = $directory . '/' . $slug . '.svg';
    if (!is_file($path)) {
        $palette = [
            ['#e7efe9','#174a3b','#a83f4c'],
            ['#f1e9df','#593f32','#b75a42'],
            ['#e5edf2','#263e50','#b66b40'],
            ['#f1e7ea','#642e42','#c18d55'],
            ['#e9e9df','#3f4935','#ad4c42'],
            ['#e8e5ef','#403950','#b45b52'],
        ];
        $colors = $palette[abs(crc32($productName)) % count($palette)];
        $shape = match ($category) {
            'Hoodies' => '<path d="M286 260 350 218 400 252 450 218 514 260 572 382 520 408 490 350 500 710 300 710 310 350 280 408 228 382Z"/><path d="M350 218Q400 155 450 218L438 290 362 290Z" fill="none" stroke="' . $colors[1] . '" stroke-width="24"/><path d="M350 505h100v112H350z" fill="' . $colors[0] . '" opacity=".7"/>',
            'Shoes' => '<path d="M236 565Q285 548 323 452L373 468Q393 533 447 552L550 588Q586 601 588 645L588 681 226 681Q201 628 236 565Z"/><path d="M310 552 424 582M338 515 452 574M368 492 476 585" fill="none" stroke="' . $colors[0] . '" stroke-width="15"/><path d="M220 668h374" stroke="' . $colors[2] . '" stroke-width="24"/>',
            'T-Shirts' => '<path d="M286 250 350 220 400 250 450 220 514 250 582 338 520 384 490 344 490 710 310 710 310 344 280 384 218 338Z"/><path d="M350 220Q400 296 450 220" fill="none" stroke="' . $colors[0] . '" stroke-width="18"/>',
            'Jeans' => '<path d="M300 230h200l-17 222 35 268H410l-25-210-25 210H282l35-268Z"/><path d="M400 240v267M318 455h164" stroke="' . $colors[0] . '" stroke-width="14"/>',
            'Jackets' => '<path d="M286 258 350 220 400 252 450 220 514 258 575 380 520 410 490 350 500 710H300l10-360-30 60-55-30Z"/><path d="M400 252v458M342 252l58 75 58-75" fill="none" stroke="' . $colors[0] . '" stroke-width="16"/><circle cx="420" cy="376" r="7" fill="' . $colors[2] . '"/><circle cx="420" cy="430" r="7" fill="' . $colors[2] . '"/>',
            default => '<path d="M286 350 350 300 450 300 514 350 550 700H250Z"/><path d="M350 300Q400 240 450 300M315 420h170" fill="none" stroke="' . $colors[0] . '" stroke-width="18"/>',
        };
        $label = htmlspecialchars($productName, ENT_QUOTES | ENT_XML1, 'UTF-8');
        $categoryLabel = htmlspecialchars(strtoupper($category), ENT_QUOTES | ENT_XML1, 'UTF-8');
        $svg = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 800 1000" role="img" aria-label="' . $label . '"><rect width="800" height="1000" fill="' . $colors[0] . '"/><circle cx="680" cy="160" r="110" fill="' . $colors[2] . '" opacity=".18"/><circle cx="120" cy="820" r="170" fill="' . $colors[1] . '" opacity=".08"/><text x="72" y="106" font-family="Georgia,serif" font-size="26" letter-spacing="5" fill="' . $colors[1] . '">FD / ' . $categoryLabel . '</text><g fill="' . $colors[1] . '" stroke="' . $colors[1] . '" stroke-linejoin="round">' . $shape . '</g><text x="72" y="835" font-family="Georgia,serif" font-size="35" fill="' . $colors[1] . '">' . $label . '</text><text x="74" y="880" font-family="Arial,sans-serif" font-size="16" letter-spacing="3" fill="' . $colors[1] . '" opacity=".65">FD-TREND STUDIO SAMPLE</text></svg>';
        file_put_contents($path, $svg, LOCK_EX);
    }
    return '/uploads/catalog/' . $slug . '.svg';
}

function findOrCreateCategory(PDO $pdo, string $name, ?int $parentId, int $sortOrder = 100): int {
    $query = $pdo->prepare($parentId === null
        ? 'SELECT id FROM categories WHERE parent_id IS NULL AND name=? LIMIT 1'
        : 'SELECT id FROM categories WHERE parent_id=? AND name=? LIMIT 1');
    $query->execute($parentId === null ? [$name] : [$parentId, $name]);
    $id = $query->fetchColumn();
    if ($id !== false) {
        return (int)$id;
    }

    $slugBase = categorySlug($name);
    $slug = $parentId === null ? $slugBase : $slugBase . '-' . $parentId;
    $insert = $pdo->prepare('INSERT INTO categories (parent_id,name,slug,sort_order,active) VALUES (?,?,?,?,1)');
    $insert->execute([$parentId, $name, $slug, $sortOrder]);
    return (int)$pdo->lastInsertId();
}

$pdo->exec("CREATE TABLE IF NOT EXISTS categories (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    parent_id INT UNSIGNED NULL,
    name VARCHAR(100) NOT NULL,
    slug VARCHAR(140) NOT NULL UNIQUE,
    description VARCHAR(500) NULL,
    sort_order INT NOT NULL DEFAULT 100,
    active TINYINT(1) NOT NULL DEFAULT 1,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_categories_parent FOREIGN KEY (parent_id) REFERENCES categories(id) ON DELETE SET NULL,
    INDEX idx_categories_parent_active (parent_id, active)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");

$pdo->exec("CREATE TABLE IF NOT EXISTS users (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(150) NOT NULL,
    email VARCHAR(190) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    active TINYINT(1) NOT NULL DEFAULT 1,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");

$pdo->exec("CREATE TABLE IF NOT EXISTS product_images (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    product_id INT UNSIGNED NOT NULL,
    image_url VARCHAR(1000) NOT NULL,
    alt_text VARCHAR(255) NULL,
    sort_order INT NOT NULL DEFAULT 0,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_product_images_product FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
    INDEX idx_product_images_product_order (product_id, sort_order)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");

$pdo->exec("CREATE TABLE IF NOT EXISTS product_variants (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    product_id INT UNSIGNED NOT NULL,
    size VARCHAR(30) NOT NULL,
    color VARCHAR(80) NOT NULL,
    sku VARCHAR(100) NULL UNIQUE,
    stock INT UNSIGNED NOT NULL DEFAULT 0,
    price_adjustment DECIMAL(10,2) NOT NULL DEFAULT 0,
    active TINYINT(1) NOT NULL DEFAULT 1,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_product_variants_product FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
    UNIQUE KEY uq_variant_product_size_color (product_id,size,color),
    INDEX idx_variants_product_active (product_id,active)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");

$pdo->exec("CREATE TABLE IF NOT EXISTS cart_items (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT UNSIGNED NOT NULL,
    variant_id INT UNSIGNED NOT NULL,
    quantity INT UNSIGNED NOT NULL,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_cart_items_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    CONSTRAINT fk_cart_items_variant FOREIGN KEY (variant_id) REFERENCES product_variants(id) ON DELETE CASCADE,
    UNIQUE KEY uq_cart_user_variant (user_id,variant_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");

$pdo->exec("CREATE TABLE IF NOT EXISTS wishlist_items (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT UNSIGNED NOT NULL,
    product_id INT UNSIGNED NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_wishlist_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    CONSTRAINT fk_wishlist_product FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
    UNIQUE KEY uq_wishlist_user_product (user_id,product_id),
    INDEX idx_wishlist_user_created (user_id,created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");

$pdo->exec("CREATE TABLE IF NOT EXISTS product_reviews (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    product_id INT UNSIGNED NOT NULL,
    user_id BIGINT UNSIGNED NOT NULL,
    rating TINYINT UNSIGNED NOT NULL,
    review_text TEXT NULL,
    approved TINYINT(1) NOT NULL DEFAULT 0,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_reviews_product FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
    CONSTRAINT fk_reviews_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    UNIQUE KEY uq_review_user_product (user_id,product_id),
    INDEX idx_reviews_product_approved (product_id,approved)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");

$pdo->exec("CREATE TABLE IF NOT EXISTS marketing_posts (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    type VARCHAR(20) NOT NULL,
    title VARCHAR(180) NOT NULL,
    slug VARCHAR(200) NOT NULL UNIQUE,
    category VARCHAR(80) NOT NULL DEFAULT 'Style Inspiration',
    caption VARCHAR(500) NOT NULL DEFAULT '',
    body MEDIUMTEXT NULL,
    image_url VARCHAR(1000) NULL,
    video_url VARCHAR(1000) NULL,
    product_id INT UNSIGNED NULL,
    published TINYINT(1) NOT NULL DEFAULT 0,
    featured TINYINT(1) NOT NULL DEFAULT 0,
    published_at DATETIME NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_marketing_product FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE SET NULL,
    INDEX idx_marketing_type_status (type,published,featured,published_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");

$pdo->exec("CREATE TABLE IF NOT EXISTS reel_events (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    reel_id BIGINT UNSIGNED NOT NULL,
    session_key CHAR(64) NOT NULL,
    event_type ENUM('view','like') NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_reel_events_post FOREIGN KEY (reel_id) REFERENCES marketing_posts(id) ON DELETE CASCADE,
    UNIQUE KEY uq_reel_event_once (reel_id,session_key,event_type),
    INDEX idx_reel_event_type (reel_id,event_type)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");

$pdo->exec("CREATE TABLE IF NOT EXISTS newsletter_subscribers (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(190) NOT NULL UNIQUE,
    active TINYINT(1) NOT NULL DEFAULT 1,
    subscribed_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");

$pdo->exec("CREATE TABLE IF NOT EXISTS contact_messages (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(150) NOT NULL,
    email VARCHAR(190) NOT NULL,
    subject VARCHAR(180) NOT NULL,
    message TEXT NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_contact_created (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");

$pdo->exec("CREATE TABLE IF NOT EXISTS store_settings (
    setting_key VARCHAR(100) NOT NULL PRIMARY KEY,
    setting_value VARCHAR(1000) NOT NULL DEFAULT '',
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");

migrationAddColumn($pdo, 'marketing_posts', 'view_count', 'INT UNSIGNED NOT NULL DEFAULT 0');
migrationAddColumn($pdo, 'marketing_posts', 'like_count', 'INT UNSIGNED NOT NULL DEFAULT 0');

migrationAddColumn($pdo, 'products', 'category_id', 'INT UNSIGNED NULL');
migrationAddColumn($pdo, 'orders', 'user_id', 'BIGINT UNSIGNED NULL');
migrationAddColumn($pdo, 'orders', 'subtotal', 'DECIMAL(10,2) NOT NULL DEFAULT 0');
migrationAddColumn($pdo, 'orders', 'discount', 'DECIMAL(10,2) NOT NULL DEFAULT 0');
migrationAddColumn($pdo, 'orders', 'shipping', 'DECIMAL(10,2) NOT NULL DEFAULT 0');
migrationAddColumn($pdo, 'order_items', 'variant_id', 'INT UNSIGNED NULL');
migrationAddColumn($pdo, 'order_items', 'color', "VARCHAR(80) NOT NULL DEFAULT 'Assorted'");

if (!migrationIndexExists($pdo, 'products', 'idx_products_category_id_active')) {
    $pdo->exec('ALTER TABLE products ADD INDEX idx_products_category_id_active (category_id,active)');
}
if (!migrationForeignKeyExists($pdo, 'products', 'fk_products_category')) {
    $pdo->exec('ALTER TABLE products ADD CONSTRAINT fk_products_category FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE SET NULL');
}
if (!migrationForeignKeyExists($pdo, 'orders', 'fk_orders_user')) {
    $pdo->exec('ALTER TABLE orders ADD CONSTRAINT fk_orders_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL');
}
if (!migrationForeignKeyExists($pdo, 'order_items', 'fk_order_items_variant')) {
    $pdo->exec('ALTER TABLE order_items ADD CONSTRAINT fk_order_items_variant FOREIGN KEY (variant_id) REFERENCES product_variants(id) ON DELETE SET NULL');
}

$mainCategories = ['Hoodies', 'Shoes', 'T-Shirts', 'Jeans', 'Jackets', 'Accessories'];
foreach ($mainCategories as $position => $name) {
    findOrCreateCategory($pdo, $name, null, $position + 1);
}

$hoodiesId = findOrCreateCategory($pdo, 'Hoodies', null, 1);
$hoodieSubcategories = ['Oversized Hoodies', 'Pullover Hoodies', 'Zip-Up Hoodies', 'Printed Hoodies', 'Cropped Hoodies', 'Plain Hoodies'];
foreach ($hoodieSubcategories as $position => $name) {
    findOrCreateCategory($pdo, $name, $hoodiesId, $position + 1);
}

$legacyProducts = $pdo->query('SELECT p.id,p.name,p.category,p.subcategory,p.sizes,p.color,p.stock,c.name AS assigned_category,c.parent_id AS assigned_parent_id,parent.name AS assigned_parent_name FROM products p LEFT JOIN categories c ON c.id=p.category_id LEFT JOIN categories parent ON parent.id=c.parent_id ORDER BY p.id')->fetchAll();
$categoryAliases = ['Footwear' => 'Shoes'];
foreach ($legacyProducts as $product) {
    $storedRoot = $product['assigned_parent_id'] !== null ? $product['assigned_parent_name'] : ($product['assigned_category'] ?: $product['category']);
    $rootName = $categoryAliases[$storedRoot] ?? $storedRoot;
    $subcategoryName = $product['assigned_parent_id'] !== null
        ? trim((string)$product['assigned_category'])
        : trim((string)$product['subcategory']);
    if ($subcategoryName === '' && $product['category'] === 'Hoodies' && stripos($product['name'], 'oversized') !== false) {
        $subcategoryName = 'Oversized Hoodies';
    }
    if (in_array($subcategoryName, ['Oversized Hoodies','Pullover Hoodies','Zip-Up Hoodies','Printed Hoodies','Cropped Hoodies','Plain Hoodies'], true)) {
        $rootName = 'Hoodies';
    }
    if ($product['category'] === 'Shirts') $rootName = 'T-Shirts';
    $rootCategoryId = findOrCreateCategory($pdo, $rootName, null);
    $assignedCategoryId = $subcategoryName === ''
        ? $rootCategoryId
        : findOrCreateCategory($pdo, $subcategoryName, $rootCategoryId);
    $updateCategory = $pdo->prepare('UPDATE products SET category_id=?,category=?,subcategory=? WHERE id=? AND (category_id IS NULL OR category_id<>? OR category<>? OR subcategory<>?)');
    $legacySubcategory = $subcategoryName;
    $updateCategory->execute([$assignedCategoryId, $rootName, $legacySubcategory, $product['id'], $assignedCategoryId, $rootName, $legacySubcategory]);

    $variantCount = $pdo->prepare('SELECT COUNT(*) FROM product_variants WHERE product_id=?');
    $variantCount->execute([$product['id']]);
    if ((int)$variantCount->fetchColumn() > 0) {
        continue;
    }

    $sizes = array_values(array_unique(array_filter(array_map('trim', explode(',', (string)$product['sizes'])))));
    $colors = array_values(array_unique(array_filter(array_map('trim', explode(',', (string)$product['color'])))));
    if (!$sizes) $sizes = ['One Size'];
    if (!$colors) $colors = ['Assorted'];
    $combinations = [];
    foreach ($sizes as $size) {
        foreach ($colors as $color) {
            $combinations[] = [$size, $color];
        }
    }
    $stock = (int)$product['stock'];
    $baseStock = intdiv($stock, count($combinations));
    $remainder = $stock % count($combinations);
    $insertVariant = $pdo->prepare('INSERT INTO product_variants (product_id,size,color,stock,active) VALUES (?,?,?,?,1)');
    foreach ($combinations as $index => [$size, $color]) {
        $insertVariant->execute([$product['id'], $size, $color, $baseStock + ($index < $remainder ? 1 : 0)]);
    }
}

$catalogNames = ['Hoodies','Shoes','T-Shirts','Jeans','Jackets','Accessories'];
$inactiveNames = ['Shirts','Trousers','Sweatshirts','Co-ord Sets','Dresses','Ethnic Wear','Footwear'];
$archiveProducts = $pdo->prepare('UPDATE products SET active=0 WHERE category_id IN (SELECT id FROM categories WHERE parent_id IS NULL AND name IN (' . implode(',',array_fill(0,count($inactiveNames),'?')) . '))');
$archiveProducts->execute($inactiveNames);
$pdo->prepare('UPDATE product_variants v JOIN products p ON p.id=v.product_id SET v.active=0 WHERE p.active=0')->execute();
$pdo->prepare('UPDATE categories SET active=0 WHERE parent_id IS NULL AND name IN (' . implode(',',array_fill(0,count($inactiveNames),'?')) . ')')->execute($inactiveNames);

$sampleCatalog = [
 'Hoodies' => [
  ['Northline Fleece Pullover','Brushed cotton fleece, relaxed shoulders and a warm everyday hand feel.',1199,14,'Forest Green','S,M,L,XL'],
  ['Studio Zip Hoodie','Midweight zip hoodie with a clean profile and soft ribbed cuffs.',1399,11,'Charcoal','S,M,L,XL'],
  ['Cloud Loopback Hoodie','Soft loopback cotton with a roomy hood and neat kangaroo pocket.',1299,16,'Stone','S,M,L,XL'],
  ['Varsity Panel Hoodie','Color-blocked sleeves and a comfortable brushed interior.',1599,8,'Navy','S,M,L,XL'],
  ['Minimal Cropped Hoodie','A shorter relaxed cut with a structured hem and soft fleece.',1099,9,'Oatmeal','XS,S,M,L'],
  ['Trail Graphic Hoodie','Small front mark and a durable heavyweight cotton blend.',1499,12,'Black','S,M,L,XL'],
  ['Rib Hem Everyday Hoodie','Easy layering weight with deep rib trims and a double-layer hood.',1249,13,'Moss','S,M,L,XL'],
  ['Weekend Washed Hoodie','Garment-washed color and a relaxed fit for off-duty wear.',1699,7,'Washed Blue','S,M,L,XL'],
  ['Essential Pullover Hoodie','A versatile plain pullover made for repeat wear.',999,18,'Burgundy','S,M,L,XL'],
  ['Athletic Performance Hoodie','Moisture-wicking fabric with four-way stretch for active wear.',1799,10,'Black','S,M,L,XL'],
 ],
 'Shoes' => [
  ['Courtline Canvas Low','Low-profile canvas sneaker with a cushioned footbed.',1899,12,'White','6,7,8,9,10'],
  ['Everyday Knit Runner','Breathable knit upper and a flexible lightweight sole.',2499,9,'Grey','6,7,8,9,10'],
  ['Suede Weekend Sneaker','Soft suede finish with a padded collar for daily comfort.',2999,7,'Sand','6,7,8,9,10'],
  ['City Classic High Top','Ankle-height canvas shape with durable foxing detail.',2199,10,'Black','6,7,8,9,10'],
  ['Cloudstep Walking Shoe','Supportive cushioning and a breathable mesh upper.',2799,13,'Navy','6,7,8,9,10'],
  ['Retro Court Sneaker','Vintage court-inspired lines with a clean contrast sole.',2599,8,'Green','6,7,8,9,10'],
  ['Trail Flex Trainer','Grippy outsole and secure lace fit for active days.',3299,6,'Olive','6,7,8,9,10'],
  ['Minimal Leather Low','Smooth leather upper and understated everyday styling.',3599,5,'Cream','6,7,8,9,10'],
  ['Easy Slip-On Sneaker','Stretch panels and a soft insole for quick everyday wear.',1999,15,'Black','6,7,8,9,10'],
  ['Premium Leather Oxford','Classic dress shoe with premium leather upper and cushioned sole.',4499,4,'Brown','6,7,8,9,10'],
 ],
 'T-Shirts' => [
  ['Heavyweight Box Tee','Dense cotton jersey with a broad, easy boxy silhouette.',699,25,'Ecru','S,M,L,XL'],
  ['Pocket Everyday Tee','A small chest pocket on soft combed cotton.',549,21,'Olive','S,M,L,XL'],
  ['Relaxed Stripe Tee','Fine horizontal stripes and a comfortable regular fit.',799,15,'Navy Stripe','S,M,L,XL'],
  ['Washed Graphic Tee','Subtle front graphic on a garment-washed cotton base.',899,12,'Washed Black','S,M,L,XL'],
  ['Ribbed Crew Tee','Textured rib jersey and a neat crew neckline.',649,19,'Cream','XS,S,M,L'],
  ['Everyday Longline Tee','A little extra length with a clean straight hem.',599,17,'Slate','S,M,L,XL'],
  ['Studio Logo Tee','Minimal embroidered mark with a soft hand feel.',749,14,'White','S,M,L,XL'],
  ['Colorblock Panel Tee','Contrast shoulder panels in breathable cotton jersey.',849,10,'Blue','S,M,L,XL'],
  ['Vintage Wash Henley','Soft henley neckline with buttons and a gentle vintage wash.',949,13,'Vintage Grey','S,M,L,XL'],
 ],
 'Jeans' => [
  ['Straight Indigo Denim','Classic straight leg with a comfortable mid rise.',2299,12,'Indigo','28,30,32,34,36'],
  ['Relaxed Black Jean','Room through the leg with a clean dark wash.',2499,10,'Black','28,30,32,34,36'],
  ['Tapered Everyday Jean','A subtle taper and soft stretch for daily movement.',2399,14,'Mid Blue','28,30,32,34,36'],
  ['Light Wash Wide Jean','A relaxed wide leg in a soft summer wash.',2599,8,'Light Blue','28,30,32,34,36'],
  ['Utility Pocket Denim','Workwear-inspired pockets on durable cotton denim.',2799,7,'Stonewash','28,30,32,34,36'],
  ['Classic Dark Slim Jean','A streamlined fit with a deep indigo rinse.',2499,11,'Dark Indigo','28,30,32,34,36'],
  ['Vintage Fade Jean','Subtle vintage fading with reinforced seams.',2699,9,'Vintage Blue','28,30,32,34,36'],
  ['Easy Pull-On Denim','Comfort waistband with the look of classic denim.',2199,16,'Blue','28,30,32,34,36'],
  ['Cropped Straight Jean','An ankle-length straight cut with a neat finish.',2399,8,'Washed Grey','28,30,32,34,36'],
  ['High Rise Skinny Jean','Figure-hugging skinny fit with a comfortable high rise.',2299,9,'Dark Wash','28,30,32,34,36'],
 ],
 'Jackets' => [
  ['Utility Field Jacket','Lightweight cotton shell with practical utility pockets.',3299,9,'Olive','S,M,L,XL'],
  ['Classic Denim Jacket','Structured denim layer with button front and chest pockets.',2999,11,'Indigo','S,M,L,XL'],
  ['Quilted Liner Jacket','Light insulation and diamond quilting for cool evenings.',3599,7,'Black','S,M,L,XL'],
  ['Coach Windbreaker','Packable water-resistant shell with a simple snap front.',2799,13,'Navy','S,M,L,XL'],
  ['Corduroy Overshirt Jacket','Soft cord texture and a relaxed layering shape.',3199,8,'Rust','S,M,L,XL'],
  ['Canvas Chore Jacket','Durable canvas with workwear pockets and easy layering.',3399,10,'Tan','S,M,L,XL'],
  ['Lightweight Harrington','A clean collar, zip front, and breathable lining.',3799,6,'Forest','S,M,L,XL'],
  ['Padded City Jacket','Warm synthetic fill in a practical everyday silhouette.',4199,5,'Charcoal','S,M,L,XL'],
  ['Cropped Utility Jacket','Shorter cut with adjustable hem and sturdy hardware.',3499,7,'Stone','XS,S,M,L'],
  ['Waterproof Rain Shell','Fully waterproof jacket with sealed seams and adjustable hood.',4499,6,'Black','S,M,L,XL'],
 ],
 'Accessories' => [
  ['Canvas Crossbody Bag','Compact daily bag with an adjustable woven strap.',1299,20,'Natural','One Size'],
  ['Rib Knit Beanie','Soft rib-knit hat with a clean folded cuff.',599,25,'Charcoal','One Size'],
  ['Everyday Leather Belt','Classic buckle and a smooth durable finish.',999,16,'Brown','S,M,L'],
  ['Woven Weekend Tote','Roomy woven carryall for everyday essentials.',899,18,'Natural','One Size'],
  ['Ribbed Crew Socks Set','Three-pair cotton blend set with a comfortable cuff.',499,30,'Assorted','One Size'],
  ['Compact Card Holder','Slim card case with multiple slots.',799,12,'Black','One Size'],
  ['Minimal Metal Sunglasses','Lightweight frame with UV-protective lenses.',1499,9,'Tortoise','One Size'],
  ['Soft Cotton Scarf','Light woven scarf for simple seasonal layering.',1099,11,'Rust Print','One Size'],
  ['Structured Crossbody Purse','Compact purse with organized compartments and adjustable strap.',1599,14,'Black','One Size'],
 ],
];

$imageIds = [
 '1521572163474-6864f9cf17ab','1503342217505-b0a15ec3261c','1596755094514-f87e34085b2c','1556821840-3a63f95609a7','1542272604-787c3835535d','1506629905607-d9c297d3f8f4','1595777457583-95e059d581b8','1543076447-215ad9ba6923','1583391733956-6c78276477e1','1542291026-7eec264c27ff','1590874103328-eac38a683ce7','1588850561407-ed78c282e89b','1529139574466-a303027c1d8b','1551028719-00167b16eac5','1509631179647-0177331693ae','1483985988355-763728e1935b','1490481651871-ab68de25d43d','1525507119028-ed4c629a60a3','1485230897760-7a7f3e398a42','1539109136881-3be0616acf4b','1548126032-079a0fb0099d','1523381210434-271e8be1f52b','1503341455253-b2e723bb3dbb','1539533018447-63fcce2678e3','1515886657613-9f3515b0c78f','1529139574466-a303027c1d8b','1524504388940-b1c1722653e1','1487222477894-8943e31ef7b2','1512436991641-6745cdb1723f','1551488831-00ddcb6c6bd3','1564584217132-2271feaeb3c5','1515886657613-9f3515b0c78f','1503342217505-b0a15ec3261c','1551028719-00167b16eac5','1523381210434-271e8be1f52b','1525507119028-ed4c629a60a3','1539109136881-3be0616acf4b','1490481651871-ab68de25d43d','1548126032-079a0fb0099d','1483985988355-763728e1935b','1509631179647-0177331693ae','1529139574466-a303027c1d8b','1485230897760-7a7f3e398a42','1539533018447-63fcce2678e3','1515886657613-9f3515b0c78f','1551488831-00ddcb6c6bd3','1564584217132-2271feaeb3c5','1490481651871-ab68de25d43d','1523381210434-271e8be1f52b','1503341455253-b2e723bb3dbb','1544441893-675973e31985','1529139574466-a303027c1d8b'
];
$imageCursor = 0;
$catalogInsert = $pdo->prepare('INSERT INTO products (category_id,category,subcategory,name,brand,description,price,mrp,stock,image_url,emoji,color,material,sizes,featured,active) VALUES (?,?,\'\',?,?,?,?,?,?,?,?,?,?,?,?,1)');
$variantInsert = $pdo->prepare('INSERT INTO product_variants (product_id,size,color,stock,active) VALUES (?,?,?,?,1)');
$catalogCount = $pdo->prepare('SELECT COUNT(*) FROM products WHERE category_id=? AND active=1');
foreach ($catalogNames as $catalogName) {
    $categoryId = findOrCreateCategory($pdo,$catalogName,null);
    $pdo->prepare('UPDATE categories SET active=1 WHERE id=?')->execute([$categoryId]);
    $catalogCount->execute([$categoryId]);
    $needed = max(0,10-(int)$catalogCount->fetchColumn());
    $samples = $sampleCatalog[$catalogName] ?? [];
    foreach ($samples as $sample) {
        if ($needed < 1) break;
        [$name,$description,$price,$stock,$color,$sizesCsv] = $sample;
        $exists = $pdo->prepare('SELECT id FROM products WHERE name=? LIMIT 1');
        $exists->execute([$name]);
        if ($exists->fetchColumn()) continue;
        $imageUrl = createCatalogIllustration($catalogName, $name);
        $emoji = ['Hoodies'=>'🧥','Shoes'=>'👟','T-Shirts'=>'👕','Jeans'=>'👖','Jackets'=>'🧥','Accessories'=>'👜'][$catalogName];
        $sizes = array_values(array_filter(array_map('trim',explode(',',$sizesCsv))));
        $mrp = round($price * 1.25,2);
        $catalogInsert->execute([$categoryId,$catalogName,$name,'FD-Trend Studio',$description,$price,$mrp,$stock,$imageUrl,$emoji,$color,'Cotton blend',$sizesCsv,0]);
        $productId = (int)$pdo->lastInsertId();
        $base = intdiv((int)$stock,count($sizes));
        $remainder = (int)$stock % count($sizes);
        foreach ($sizes as $index=>$size) $variantInsert->execute([$productId,$size,$color,$base+($index<$remainder?1:0)]);
        $imageRow = $pdo->prepare('INSERT INTO product_images (product_id,image_url,alt_text,sort_order) VALUES (?,?,?,0)');
        $imageRow->execute([$productId,$imageUrl,$name]);
        $needed--;
    }
}

$pdo->prepare('UPDATE categories SET active=0 WHERE parent_id IS NULL AND name NOT IN (' . implode(',',array_fill(0,count($catalogNames),'?')) . ')')->execute($catalogNames);

$marketingCount = (int)$pdo->query('SELECT COUNT(*) FROM marketing_posts')->fetchColumn();
if ($marketingCount === 0) {
    $samplePosts = [
        ['article','Relaxed layers for everyday plans','relaxed-everyday-layers','Streetwear','FD-Trend editorial sample · Outfit inspiration','Build a simple streetwear rotation with a relaxed tee, comfortable denim, and one light layer. Keep color choices flexible so each piece works across more than one outfit.',null],
        ['article','One hoodie, three easy outfits','one-hoodie-three-outfits','Hoodie Styling','FD-Trend editorial sample · Styling guide','Start with a clean hoodie as the anchor. Pair it with denim for an everyday look, layer it under a jacket on colder days, or balance its volume with a straighter trouser.',(int)($pdo->query("SELECT id FROM products WHERE category='Hoodies' AND active=1 ORDER BY id LIMIT 1")->fetchColumn() ?: 0) ?: null],
        ['campaign','The everyday edit','fdtrend-everyday-edit','New Arrivals','Sample campaign · Shop current catalog pieces','A small edit of everyday essentials from the current FD-Trend catalog. Product availability and pricing are read live from the store catalog.',(int)($pdo->query("SELECT id FROM products WHERE active=1 ORDER BY featured DESC,id LIMIT 1")->fetchColumn() ?: 0) ?: null],
    ];
    $insertPost = $pdo->prepare('INSERT INTO marketing_posts (type,title,slug,category,caption,body,product_id,published,featured,published_at) VALUES (?,?,?,?,?,?,?,1,?,NOW())');
    foreach ($samplePosts as $index => $post) {
        $insertPost->execute([...$post, $index === 2 ? 1 : 0]);
    }
}

$reelCount = (int)$pdo->query("SELECT COUNT(*) FROM marketing_posts WHERE type='reel'")->fetchColumn();
if ($reelCount < 6) {
    $reelSeeds = [
        ['Streetwear','Relaxed layers, one easy uniform','An editorial placeholder reel about building a relaxed streetwear outfit.','Hoodies'],
        ['Casual Wear','A clean tee, three everyday fits','An editorial placeholder reel about styling a versatile everyday tee.','T-Shirts'],
        ['Winter Fashion','Layering for colder mornings','An editorial placeholder reel about practical seasonal layering.','Jackets'],
        ['Denim Days','Five ways to wear jeans','An editorial placeholder reel about versatile denim styling.','Jeans'],
        ['Sneaker Culture','The perfect everyday sneaker','An editorial placeholder reel about choosing and styling casual sneakers.','Shoes'],
        ['Accessory Edit','Small details, big impact','An editorial placeholder reel about elevating outfits with accessories.','Accessories'],
    ];
    $reelInsert = $pdo->prepare("INSERT INTO marketing_posts (type,title,slug,category,caption,body,image_url,product_id,published,featured,published_at) SELECT 'reel',?,?,?,?,?,p.image_url,p.id,1,0,NOW() FROM products p WHERE p.category=? AND p.active=1 ORDER BY id LIMIT 1");
    foreach ($reelSeeds as [$category,$title,$caption,$productCategory]) {
        $slug = categorySlug($title);
        $exists = $pdo->prepare('SELECT COUNT(*) FROM marketing_posts WHERE slug=?');
        $exists->execute([$slug]);
        if ((int)$exists->fetchColumn() === 0) $reelInsert->execute([$title,$slug,$category,$caption,'Video placeholder: an admin can attach an actual video URL before publishing video playback.',$productCategory]);
    }
}

fwrite(STDOUT, "Migration complete. Existing products and orders were preserved.\n");
