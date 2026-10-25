<?php
declare(strict_types=1);

require __DIR__ . '/config.php';

echo "=== Seeding Full 11-Category Authentic Catalog for FD-Trend ===\n\n";

// 1. Ensure schema columns exist
function ensureCol(PDO $pdo, string $tbl, string $col, string $def): void {
    $q = $pdo->prepare("SELECT COUNT(*) FROM information_schema.COLUMNS WHERE TABLE_SCHEMA=DATABASE() AND TABLE_NAME=? AND COLUMN_NAME=?");
    $q->execute([$tbl, $col]);
    if (!$q->fetchColumn()) {
        $pdo->exec("ALTER TABLE `$tbl` ADD COLUMN `$col` $def");
        echo "✓ Added column $col to $tbl\n";
    }
}

ensureCol($pdo, 'categories', 'image_url', 'VARCHAR(1000) NULL AFTER description');
ensureCol($pdo, 'products', 'sku', 'VARCHAR(80) NULL AFTER sizes');
ensureCol($pdo, 'products', 'rating', 'DECIMAL(3,2) NOT NULL DEFAULT 4.50 AFTER sku');
ensureCol($pdo, 'products', 'review_count', 'INT UNSIGNED NOT NULL DEFAULT 0 AFTER rating');
ensureCol($pdo, 'products', 'discount_percent', 'INT UNSIGNED NOT NULL DEFAULT 0 AFTER review_count');
ensureCol($pdo, 'product_reviews', 'reviewer_name', 'VARCHAR(150) NULL AFTER user_id');
$pdo->exec("ALTER TABLE `product_reviews` MODIFY `user_id` BIGINT UNSIGNED NULL");

// 2. Define the Core Categories
$categoriesDef = [
    [
        'name' => 'T-Shirts',
        'slug' => 't-shirts',
        'description' => "Graphic streetwear, heavy cotton box tees, vintage washed tees, and premium everyday crewnecks.",
        'image_url' => 'https://images.unsplash.com/photo-1521572267360-ee0c2909d518?auto=format&fit=crop&w=800&q=80',
        'sort_order' => 1,
        'subcategories' => ['Graphic Tees', 'Oversized Tees', 'Crewneck T-Shirts', 'Solid Basics']
    ],
    [
        'name' => 'Shirts',
        'slug' => 'shirts',
        'description' => "Classic Oxford button-downs, breathable linen shirts, check overshirts, and tailored formal wear.",
        'image_url' => 'https://images.unsplash.com/photo-1596755094514-f87e34085b2c?auto=format&fit=crop&w=800&q=80',
        'sort_order' => 2,
        'subcategories' => ['Casual Shirts', 'Linen Shirts', 'Dress Shirts', 'Check & Plaid Shirts']
    ],
    [
        'name' => 'Jeans',
        'slug' => 'jeans',
        'description' => "Authentic denim in slim, straight, relaxed, and tapered cuts from heritage and modern brands.",
        'image_url' => 'https://images.unsplash.com/photo-1542272604-787c3835535d?auto=format&fit=crop&w=800&q=80',
        'sort_order' => 3,
        'subcategories' => ['Slim Fit Jeans', 'Straight Fit Jeans', 'Relaxed Denim', 'Tapered Jeans']
    ],
    [
        'name' => 'Trousers',
        'slug' => 'trousers',
        'description' => "Smart tailored chinos, formal dress trousers, cargo pants, and relaxed linen bottoms.",
        'image_url' => 'https://images.unsplash.com/photo-1624378439575-d8705ad7ae80?auto=format&fit=crop&w=800&q=80',
        'sort_order' => 4,
        'subcategories' => ['Chinos & Khakis', 'Formal Trousers', 'Cargo Pants', 'Linen Trousers']
    ],
    [
        'name' => 'Jackets',
        'slug' => 'jackets',
        'description' => "Classic denim trucker jackets, leather moto jackets, bombers, windbreakers, and winter puffers.",
        'image_url' => 'https://images.unsplash.com/photo-1551028719-00167b16eac5?auto=format&fit=crop&w=800&q=80',
        'sort_order' => 5,
        'subcategories' => ['Bomber Jackets', 'Denim Jackets', 'Leather Jackets', 'Puffer Jackets', 'Windbreakers']
    ],
    [
        'name' => 'Hoodies',
        'slug' => 'hoodies',
        'description' => "Heavyweight fleece pullovers, full-zip street hoodies, oversized drop-shoulder fits, and loopback cottons.",
        'image_url' => 'https://images.unsplash.com/photo-1556905055-8f358a7a47b2?auto=format&fit=crop&w=800&q=80',
        'sort_order' => 6,
        'subcategories' => ['Pullover Hoodies', 'Zip-Up Hoodies', 'Oversized Hoodies', 'Fleece Hoodies']
    ],
    [
        'name' => 'Watches',
        'slug' => 'watches',
        'description' => "Precision chronograph timepieces, classic leather strap watches, gold vintage digitals, and automatic chronometers.",
        'image_url' => 'https://images.unsplash.com/photo-1522335789203-aabd1fc54bc9?auto=format&fit=crop&w=800&q=80',
        'sort_order' => 7,
        'subcategories' => ['Chronograph Watches', 'Leather Strap Watches', 'Vintage Digital', 'Automatic Watches']
    ],
    [
        'name' => 'Goggles / Sunglasses',
        'slug' => 'sunglasses',
        'description' => "Classic polarized Wayfarers, iconic metal Aviators, retro round sunglasses, and UV protection shades.",
        'image_url' => 'https://images.unsplash.com/photo-1511499767150-a48a237f0083?auto=format&fit=crop&w=800&q=80',
        'sort_order' => 8,
        'subcategories' => ['Wayfarer Sunglasses', 'Aviator Sunglasses', 'Round Retro', 'Sport Polarized']
    ],
    [
        'name' => 'Bags and Purses',
        'slug' => 'bags-and-purses',
        'description' => "Structured leather handbags, spacious canvas shopper totes, sleek crossbody bags, backpacks, and clutches.",
        'image_url' => 'https://images.unsplash.com/photo-1584917865442-de89df76afd3?auto=format&fit=crop&w=800&q=80',
        'sort_order' => 9,
        'subcategories' => ['Tote Bags', 'Shoulder Bags', 'Crossbody Bags', 'Backpacks', 'Purses & Clutches']
    ],
    [
        'name' => 'Shoes',
        'slug' => 'shoes',
        'description' => "Iconic lifestyle sneakers, responsive running trainers, high-top canvas classics, and leather dress shoes.",
        'image_url' => 'https://images.unsplash.com/photo-1542291026-7eec264c27ff?auto=format&fit=crop&w=800&q=80',
        'sort_order' => 10,
        'subcategories' => ['Lifestyle Sneakers', 'Running Shoes', 'High-Top Sneakers', 'Canvas Classics', 'Formal Shoes']
    ],
    [
        'name' => 'Accessories',
        'slug' => 'accessories',
        'description' => "Italian leather belts, sterling silver jewellery, RFID wallets, caps, scarves, and essential styling accessories.",
        'image_url' => 'https://images.unsplash.com/photo-1599643478518-a784e5dc4c8f?auto=format&fit=crop&w=800&q=80',
        'sort_order' => 11,
        'subcategories' => ['Leather Belts', 'Wallets', 'Necklaces & Pendants', 'Caps & Hats', 'Bracelets & Cuffs']
    ],
];

// Helper to upsert categories
$catCache = [];
foreach ($categoriesDef as $c) {
    // Check if exists by slug
    $stmt = $pdo->prepare('SELECT id FROM categories WHERE slug=? LIMIT 1');
    $stmt->execute([$c['slug']]);
    $catId = $stmt->fetchColumn();
    if ($catId) {
        $update = $pdo->prepare('UPDATE categories SET name=?, description=?, image_url=?, sort_order=?, active=1, parent_id=NULL WHERE id=?');
        $update->execute([$c['name'], $c['description'], $c['image_url'], $c['sort_order'], $catId]);
        $parentId = (int)$catId;
    } else {
        $insert = $pdo->prepare('INSERT INTO categories (name, slug, description, image_url, sort_order, active, parent_id) VALUES (?, ?, ?, ?, ?, 1, NULL)');
        $insert->execute([$c['name'], $c['slug'], $c['description'], $c['image_url'], $c['sort_order']]);
        $parentId = (int)$pdo->lastInsertId();
    }
    $catCache[$c['slug']] = $parentId;
    $catCache[$c['name']] = $parentId;

    // Subcategories
    foreach ($c['subcategories'] as $pos => $subName) {
        $subSlug = $c['slug'] . '-' . trim(strtolower(preg_replace('/[^a-zA-Z0-9]+/', '-', $subName)), '-');
        $stmtSub = $pdo->prepare('SELECT id FROM categories WHERE slug=? LIMIT 1');
        $stmtSub->execute([$subSlug]);
        $subId = $stmtSub->fetchColumn();
        if ($subId) {
            $pdo->prepare('UPDATE categories SET name=?, parent_id=?, sort_order=?, active=1 WHERE id=?')
                ->execute([$subName, $parentId, $pos + 1, $subId]);
            $subCatId = (int)$subId;
        } else {
            $pdo->prepare('INSERT INTO categories (parent_id, name, slug, sort_order, active) VALUES (?, ?, ?, ?, 1)')
                ->execute([$parentId, $subName, $subSlug, $pos + 1]);
            $subCatId = (int)$pdo->lastInsertId();
        }
        $catCache[$c['slug'] . ':' . $subName] = $subCatId;
    }
}

// Add aliases in categories table if needed so old slugs map cleanly
$aliasSlugs = [
    'bags-and-handbags' => $catCache['bags-and-purses'],
    'shoes-and-sneakers' => $catCache['shoes'],
    'jewellery-and-accessories' => $catCache['accessories'],
    'goggles' => $catCache['sunglasses'],
];
foreach ($aliasSlugs as $aSlug => $tParent) {
    $stmtA = $pdo->prepare('SELECT id FROM categories WHERE slug=? LIMIT 1');
    $stmtA->execute([$aSlug]);
    $existing = $stmtA->fetchColumn();
    if ($existing) {
        $pdo->prepare('UPDATE categories SET active=1 WHERE id=?')->execute([$existing]);
    }
}

// Make sure older combined 'jackets-and-hoodies' category does not steal from Jackets and Hoodies
$pdo->exec("UPDATE categories SET active=0 WHERE slug='jackets-and-hoodies'");

echo "✓ Categories configured cleanly.\n";

// 3. Define 10 Products for EVERY Category
require __DIR__ . '/catalog_data.php';

echo "Total products defined in catalog data: " . count($catalogProducts) . "\n";

$upsertedCount = 0;
foreach ($catalogProducts as $p) {
    $parentCatId = $catCache[$p['category_slug']] ?? null;
    $subCatId = $catCache[$p['category_slug'] . ':' . $p['subcategory']] ?? $parentCatId;
    $mainCatName = $p['category_name'];

    $discount = 0;
    if ($p['mrp'] > $p['price']) {
        $discount = (int)round((($p['mrp'] - $p['price']) / $p['mrp']) * 100);
    }

    // Check if exists by SKU
    $stmtFind = $pdo->prepare('SELECT id FROM products WHERE sku=? LIMIT 1');
    $stmtFind->execute([$p['sku']]);
    $existingId = $stmtFind->fetchColumn();

    if ($existingId) {
        $pid = (int)$existingId;
        $update = $pdo->prepare('UPDATE products SET 
            category_id=?, category=?, subcategory=?, name=?, brand=?, description=?, 
            price=?, mrp=?, stock=?, image_url=?, color=?, material=?, sizes=?, 
            sku=?, rating=?, review_count=?, discount_percent=?, featured=?, active=1 
            WHERE id=?');
        $update->execute([
            $subCatId, $mainCatName, $p['subcategory'], $p['name'], $p['brand'], $p['description'],
            $p['price'], $p['mrp'], $p['stock'], $p['image_url'], $p['color'], $p['material'], $p['sizes'],
            $p['sku'], $p['rating'], $p['review_count'], $discount, $p['featured'] ?? 0, $pid
        ]);
    } else {
        $insert = $pdo->prepare('INSERT INTO products 
            (category_id, category, subcategory, name, brand, description, price, mrp, stock, image_url, emoji, color, material, sizes, sku, rating, review_count, discount_percent, featured, active) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 1)');
        $insert->execute([
            $subCatId, $mainCatName, $p['subcategory'], $p['name'], $p['brand'], $p['description'],
            $p['price'], $p['mrp'], $p['stock'], $p['image_url'], '✨', $p['color'], $p['material'], $p['sizes'],
            $p['sku'], $p['rating'], $p['review_count'], $discount, $p['featured'] ?? 0
        ]);
        $pid = (int)$pdo->lastInsertId();
    }

    // Sync Gallery Images
    $pdo->prepare('DELETE FROM product_images WHERE product_id=?')->execute([$pid]);
    $pdo->prepare('INSERT INTO product_images (product_id, image_url, alt_text, sort_order) VALUES (?, ?, ?, 0)')
        ->execute([$pid, $p['image_url'], $p['name'] . ' - Main View']);
    $order = 1;
    if (!empty($p['gallery'])) {
        foreach ($p['gallery'] as $galleryUrl) {
            $pdo->prepare('INSERT INTO product_images (product_id, image_url, alt_text, sort_order) VALUES (?, ?, ?, ?)')
                ->execute([$pid, $galleryUrl, $p['name'] . ' - Detail View ' . $order, $order]);
            $order++;
        }
    }

    // Sync Variants
    $sizesList = array_values(array_unique(array_filter(array_map('trim', explode(',', $p['sizes'])))));
    $colorsList = array_values(array_unique(array_filter(array_map('trim', explode(',', $p['color'])))));
    if (!$sizesList) $sizesList = ['One Size'];
    if (!$colorsList) $colorsList = ['Standard'];

    $variantCombos = [];
    foreach ($sizesList as $s) {
        foreach ($colorsList as $c) {
            $variantCombos[] = ['size' => $s, 'color' => $c];
        }
    }
    $totalStock = (int)$p['stock'];
    $comboCount = max(1, count($variantCombos));
    $baseStock = intdiv($totalStock, $comboCount);
    $remainder = $totalStock % $comboCount;

    $upsertVariant = $pdo->prepare('INSERT INTO product_variants (product_id, size, color, sku, stock, active) VALUES (?, ?, ?, ?, ?, 1) ON DUPLICATE KEY UPDATE stock=VALUES(stock), active=1');
    foreach ($variantCombos as $idx => $combo) {
        $variantStock = $totalStock > 0 ? ($baseStock + ($idx < $remainder ? 1 : 0)) : 0;
        $variantSku = $p['sku'] . '-' . strtoupper(substr(preg_replace('/[^a-zA-Z0-9]/', '', $combo['size']), 0, 4));
        $upsertVariant->execute([$pid, $combo['size'], $combo['color'], $variantSku, $variantStock]);
    }

    // Reviews
    if (!empty($p['reviews'])) {
        foreach ($p['reviews'] as $rev) {
            $checkReview = $pdo->prepare('SELECT id FROM product_reviews WHERE product_id=? AND reviewer_name=? LIMIT 1');
            $checkReview->execute([$pid, $rev[0]]);
            if (!$checkReview->fetchColumn()) {
                $pdo->prepare('INSERT INTO product_reviews (product_id, user_id, reviewer_name, rating, review_text, approved) VALUES (?, NULL, ?, ?, ?, 1)')
                    ->execute([$pid, $rev[0], $rev[1], $rev[2]]);
            }
        }
    }

    $upsertedCount++;
}

echo "✓ Successfully seeded $upsertedCount products.\n";

// Ensure admin exists
$adminCheck = $pdo->prepare('SELECT id FROM admins WHERE username=?');
$adminCheck->execute(['admin']);
$adminId = $adminCheck->fetchColumn();
if ($adminId) {
    $pdo->prepare('UPDATE admins SET password_hash=? WHERE id=?')
        ->execute([password_hash('Admin123456', PASSWORD_DEFAULT), $adminId]);
    echo "✓ Admin password verified/updated for username: admin\n";
} else {
    $pdo->prepare('INSERT INTO admins (username, password_hash) VALUES (?, ?)')
        ->execute(['admin', password_hash('Admin123456', PASSWORD_DEFAULT)]);
    echo "✓ Created admin account (username: admin, password: Admin123456)\n";
}

// Print Category breakdown
$counts = $pdo->query("SELECT c.name, COUNT(p.id) as cnt FROM categories c JOIN products p ON (p.category_id=c.id OR p.category_id IN (SELECT id FROM categories sub WHERE sub.parent_id=c.id)) WHERE c.parent_id IS NULL AND p.active=1 GROUP BY c.id ORDER BY c.sort_order")->fetchAll();
echo "\nCategory product counts:\n";
foreach ($counts as $r) {
    echo "  - {$r['name']}: {$r['cnt']} products\n";
}
echo "\nDone!\n";
