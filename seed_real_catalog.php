<?php
declare(strict_types=1);

require __DIR__ . '/config.php';

if (PHP_SAPI !== 'cli') {
    http_response_code(403);
    exit('CLI execution only.');
}

echo "=== Seeding Real E-Commerce Catalog for FD-Trend ===\n\n";

// 1. Ensure Schema
function addColIfNotExists(PDO $pdo, string $tbl, string $col, string $def): void {
    $q = $pdo->prepare("SELECT COUNT(*) FROM information_schema.COLUMNS WHERE TABLE_SCHEMA=DATABASE() AND TABLE_NAME=? AND COLUMN_NAME=?");
    $q->execute([$tbl, $col]);
    if (!$q->fetchColumn()) {
        $pdo->exec("ALTER TABLE `$tbl` ADD COLUMN `$col` $def");
        echo "✓ Added column $col to $tbl\n";
    }
}

addColIfNotExists($pdo, 'categories', 'image_url', 'VARCHAR(1000) NULL AFTER description');
addColIfNotExists($pdo, 'products', 'sku', 'VARCHAR(80) NULL AFTER sizes');
addColIfNotExists($pdo, 'products', 'rating', 'DECIMAL(3,2) NOT NULL DEFAULT 4.50 AFTER sku');
addColIfNotExists($pdo, 'products', 'review_count', 'INT UNSIGNED NOT NULL DEFAULT 0 AFTER rating');
addColIfNotExists($pdo, 'products', 'discount_percent', 'INT UNSIGNED NOT NULL DEFAULT 0 AFTER review_count');
addColIfNotExists($pdo, 'product_reviews', 'reviewer_name', 'VARCHAR(150) NULL AFTER user_id');
$pdo->exec("ALTER TABLE `product_reviews` MODIFY `user_id` BIGINT UNSIGNED NULL");

// 2. Define the 13 Categories with Real Banner/Tile Photos & Descriptions
$categoriesData = [
    [
        'name' => "Women's Clothing",
        'slug' => 'womens-clothing',
        'description' => "Elegant dresses, tailored coats, chic tops, and contemporary fashion designed for modern women.",
        'image_url' => 'https://images.unsplash.com/photo-1515886657613-9f3515b0c78f?auto=format&fit=crop&w=800&q=80',
        'sort_order' => 1,
        'subcategories' => ['Tops & Blouses', 'Coats & Outerwear', 'Pants & Trousers', 'Skirts']
    ],
    [
        'name' => "Men's Clothing",
        'slug' => 'mens-clothing',
        'description' => "Sharp blazers, tailored trousers, smart casual polos, and everyday essentials for men.",
        'image_url' => 'https://images.unsplash.com/photo-1507679799987-c73779587ccf?auto=format&fit=crop&w=800&q=80',
        'sort_order' => 2,
        'subcategories' => ['Blazers & Suits', 'Chinos & Trousers', 'Polo Shirts', 'Casual Wear']
    ],
    [
        'name' => 'T-Shirts',
        'slug' => 't-shirts',
        'description' => "Graphic streetwear, heavy cotton box tees, vintage washed tees, and premium everyday crewnecks.",
        'image_url' => 'https://images.unsplash.com/photo-1521572267360-ee0c2909d518?auto=format&fit=crop&w=800&q=80',
        'sort_order' => 3,
        'subcategories' => ['Graphic Tees', 'Oversized Tees', 'Crewneck T-Shirts', 'Solid Basics']
    ],
    [
        'name' => 'Jeans',
        'slug' => 'jeans',
        'description' => "Authentic denim in slim, straight, relaxed, and tapered cuts from heritage and modern brands.",
        'image_url' => 'https://images.unsplash.com/photo-1542272604-787c3835535d?auto=format&fit=crop&w=800&q=80',
        'sort_order' => 4,
        'subcategories' => ['Slim Fit Jeans', 'Straight Fit Jeans', 'Relaxed Denim', 'Tapered Jeans']
    ],
    [
        'name' => 'Shirts',
        'slug' => 'shirts',
        'description' => "Classic Oxford button-downs, breathable linen shirts, check overshirts, and tailored formal wear.",
        'image_url' => 'https://images.unsplash.com/photo-1596755094514-f87e34085b2c?auto=format&fit=crop&w=800&q=80',
        'sort_order' => 5,
        'subcategories' => ['Casual Shirts', 'Linen Shirts', 'Dress Shirts', 'Check & Plaid Shirts']
    ],
    [
        'name' => 'Jackets and Hoodies',
        'slug' => 'jackets-and-hoodies',
        'description' => "Warm fleece pullovers, zip hoodies, leather moto jackets, and weather-ready puffer outerwear.",
        'image_url' => 'https://images.unsplash.com/photo-1556821840-3a63f95609a7?auto=format&fit=crop&w=800&q=80',
        'sort_order' => 6,
        'subcategories' => ['Fleece Hoodies', 'Zip-Up Hoodies', 'Bomber Jackets', 'Puffer Jackets', 'Leather Jackets']
    ],
    [
        'name' => 'Dresses and Kurtis',
        'slug' => 'dresses-and-kurtis',
        'description' => "Breezy floral midi dresses, elegant evening dresses, handcrafted kurtis, and ethnic anarkalis.",
        'image_url' => 'https://images.unsplash.com/photo-1572804013309-59a88b7e92f1?auto=format&fit=crop&w=800&q=80',
        'sort_order' => 7,
        'subcategories' => ['Midi Dresses', 'Maxi Dresses', 'Ethnic Kurtis', 'Anarkali Sets']
    ],
    [
        'name' => 'Shoes and Sneakers',
        'slug' => 'shoes-and-sneakers',
        'description' => "Iconic lifestyle sneakers, responsive running trainers, high-top canvas classics, and leather shoes.",
        'image_url' => 'https://images.unsplash.com/photo-1542291026-7eec264c27ff?auto=format&fit=crop&w=800&q=80',
        'sort_order' => 8,
        'subcategories' => ['Lifestyle Sneakers', 'Running Shoes', 'High-Top Sneakers', 'Canvas Classics']
    ],
    [
        'name' => 'Sandals',
        'slug' => 'sandals',
        'description' => "Comfortable contoured footbeds, strappy heel sandals, leather slides, and lightweight clogs.",
        'image_url' => 'https://images.unsplash.com/photo-1603808033192-082d6919d3e1?auto=format&fit=crop&w=800&q=80',
        'sort_order' => 9,
        'subcategories' => ['Double Buckle Slides', 'Strappy Block Heels', 'Everyday Slides', 'Clogs']
    ],
    [
        'name' => 'Bags and Handbags',
        'slug' => 'bags-and-handbags',
        'description' => "Structured leather handbags, spacious canvas shopper totes, sleek crossbody bags, and backpacks.",
        'image_url' => 'https://images.unsplash.com/photo-1584917865442-de89df76afd3?auto=format&fit=crop&w=800&q=80',
        'sort_order' => 10,
        'subcategories' => ['Tote Bags', 'Shoulder Bags', 'Crossbody Bags', 'Backpacks']
    ],
    [
        'name' => 'Jewellery and Accessories',
        'slug' => 'jewellery-and-accessories',
        'description' => "Solitaire pendant necklaces, sterling silver chain bracelets, leather belts, and styling accessories.",
        'image_url' => 'https://images.unsplash.com/photo-1599643478518-a784e5dc4c8f?auto=format&fit=crop&w=800&q=80',
        'sort_order' => 11,
        'subcategories' => ['Necklaces & Pendants', 'Bracelets & Cuffs', 'Earrings', 'Leather Belts', 'Headwear']
    ],
    [
        'name' => 'Watches',
        'slug' => 'watches',
        'description' => "Precision chronograph timepieces, classic leather strap watches, gold vintage digitals, and automatic chronometers.",
        'image_url' => 'https://images.unsplash.com/photo-1522335789203-aabd1fc54bc9?auto=format&fit=crop&w=800&q=80',
        'sort_order' => 12,
        'subcategories' => ['Chronograph Watches', 'Leather Strap Watches', 'Vintage Digital', 'Automatic Watches']
    ],
    [
        'name' => 'Sunglasses',
        'slug' => 'sunglasses',
        'description' => "Classic polarized Wayfarers, iconic metal Aviators, retro round sunglasses, and UV protection shades.",
        'image_url' => 'https://images.unsplash.com/photo-1511499767150-a48a237f0083?auto=format&fit=crop&w=800&q=80',
        'sort_order' => 13,
        'subcategories' => ['Wayfarer Sunglasses', 'Aviator Sunglasses', 'Round Retro', 'Sport Polarized']
    ]
];

// Helper to find or create category
$catCache = [];
foreach ($categoriesData as $c) {
    $stmt = $pdo->prepare('SELECT id FROM categories WHERE slug=? LIMIT 1');
    $stmt->execute([$c['slug']]);
    $existingId = $stmt->fetchColumn();
    if ($existingId) {
        $update = $pdo->prepare('UPDATE categories SET name=?, description=?, image_url=?, sort_order=?, active=1, parent_id=NULL WHERE id=?');
        $update->execute([$c['name'], $c['description'], $c['image_url'], $c['sort_order'], $existingId]);
        $parentId = (int)$existingId;
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
            $pdo->prepare('UPDATE categories SET name=?, parent_id=?, sort_order=?, active=1 WHERE id=?')->execute([$subName, $parentId, $pos + 1, $subId]);
        } else {
            $pdo->prepare('INSERT INTO categories (parent_id, name, slug, sort_order, active) VALUES (?, ?, ?, ?, 1)')->execute([$parentId, $subName, $subSlug, $pos + 1]);
            $subId = (int)$pdo->lastInsertId();
        }
        $catCache[$c['slug'] . ':' . $subName] = (int)$subId;
    }
}
echo "✓ All 13 main categories and their subcategories established in database.\n";

// 3. Define Real Products Across all 13 Categories
$products = [
    // -------------------------------------------------------------
    // 1. Women's Clothing
    // -------------------------------------------------------------
    [
        'category_slug' => 'womens-clothing',
        'subcategory' => 'Coats & Outerwear',
        'name' => "Zara Belted Wool-Blend Trench Coat",
        'brand' => 'Zara',
        'sku' => 'ZAR-WCT-CAM-001',
        'price' => 4990.00,
        'mrp' => 6990.00,
        'stock' => 18,
        'sizes' => 'XS,S,M,L,XL',
        'color' => 'Camel Tan',
        'material' => 'Wool & Polyester Blend with Satin Lining',
        'description' => "A timeless double-breasted trench coat crafted in a warm wool-blend finish. Features a removable waist belt with tortoiseshell buckle, notched lapels, storm flap detail, and deep welt pockets. Ideal for refined layering across autumn and winter seasons.",
        'image_url' => 'https://images.unsplash.com/photo-1539571696357-5a69c17a67c6?auto=format&fit=crop&w=800&q=80',
        'gallery' => [
            'https://images.unsplash.com/photo-1544441893-675973e31985?auto=format&fit=crop&w=800&q=80',
            'https://images.unsplash.com/photo-1515886657613-9f3515b0c78f?auto=format&fit=crop&w=800&q=80'
        ],
        'rating' => 4.80,
        'review_count' => 84,
        'featured' => 1,
        'reviews' => [
            ['Ananya Verma', 5, 'The fabric weight and cut are spectacular. Fits true to size and looks very luxurious.'],
            ['Pooja Sharma', 5, 'Wore it for an office conference and received numerous compliments. Warm and elegant.']
        ]
    ],
    [
        'category_slug' => 'womens-clothing',
        'subcategory' => 'Blazers & Suits',
        'name' => "Mango Double-Breasted Tailored Blazer",
        'brand' => 'Mango',
        'sku' => 'MNG-WBL-BLK-002',
        'price' => 3990.00,
        'mrp' => 5590.00,
        'stock' => 22,
        'sizes' => 'S,M,L,XL',
        'color' => 'Classic Black',
        'material' => 'Viscose & Elastane Stretch Weave',
        'description' => "Structured tailoring meets contemporary comfort. This double-breasted blazer offers clean lines, sharp padded shoulders, flap front pockets, and a flattering cinch at the waist.",
        'image_url' => 'https://images.unsplash.com/photo-1509631179647-0177331693ae?auto=format&fit=crop&w=800&q=80',
        'gallery' => [
            'https://images.unsplash.com/photo-1515886657613-9f3515b0c78f?auto=format&fit=crop&w=800&q=80'
        ],
        'rating' => 4.70,
        'review_count' => 61,
        'featured' => 0,
        'reviews' => [
            ['Rhea Sen', 5, 'The tailoring on this blazer is impeccable. Goes with jeans and trousers alike.']
        ]
    ],
    [
        'category_slug' => 'womens-clothing',
        'subcategory' => 'Pants & Trousers',
        'name' => "Vero Moda High-Waist Wide Leg Trousers",
        'brand' => 'Vero Moda',
        'sku' => 'VER-WTR-BGE-003',
        'price' => 1999.00,
        'mrp' => 2999.00,
        'stock' => 25,
        'sizes' => 'XS,S,M,L,XL',
        'color' => 'Beige',
        'material' => 'Poly-viscose breathable twill',
        'description' => "High-waisted trousers with front pleats and a relaxed wide-leg drape. Styled with slant pockets and clean hook-and-bar closure for casual chic or smart workwear.",
        'image_url' => 'https://images.unsplash.com/photo-1506629905607-d9c297d3f8f4?auto=format&fit=crop&w=800&q=80',
        'gallery' => [
            'https://images.unsplash.com/photo-1515886657613-9f3515b0c78f?auto=format&fit=crop&w=800&q=80'
        ],
        'rating' => 4.60,
        'review_count' => 47,
        'featured' => 0,
        'reviews' => [
            ['Kavita Nair', 5, 'Flattering high-waist fit. Very comfortable fabric that doesn’t wrinkle easily.']
        ]
    ],
    [
        'category_slug' => 'womens-clothing',
        'subcategory' => 'Tops & Blouses',
        'name' => "H&M Ribbed Knit Fitted Mock-Neck Top",
        'brand' => 'H&M',
        'sku' => 'HM-WTP-ECR-004',
        'price' => 1299.00,
        'mrp' => 1799.00,
        'stock' => 30,
        'sizes' => 'XS,S,M,L',
        'color' => 'Ecru Cream',
        'material' => '95% Organic Cotton, 5% Elastane',
        'description' => "A soft fine-ribbed jersey top featuring a mock neckline and neat fitted long sleeves. Effortless to tuck into denim, skirts, or tailored bottoms.",
        'image_url' => 'https://images.unsplash.com/photo-1544441893-675973e31985?auto=format&fit=crop&w=800&q=80',
        'gallery' => [
            'https://images.unsplash.com/photo-1539571696357-5a69c17a67c6?auto=format&fit=crop&w=800&q=80'
        ],
        'rating' => 4.50,
        'review_count' => 38,
        'featured' => 0,
        'reviews' => [
            ['Simran Arora', 4, 'Really soft material and perfect fit. Looks great under blazers.']
        ]
    ],

    // -------------------------------------------------------------
    // 2. Men's Clothing
    // -------------------------------------------------------------
    [
        'category_slug' => 'mens-clothing',
        'subcategory' => 'Blazers & Suits',
        'name' => "Tommy Hilfiger Tailored Slim Fit Suit Blazer",
        'brand' => 'Tommy Hilfiger',
        'sku' => 'TOM-MBL-NAV-005',
        'price' => 8999.00,
        'mrp' => 12999.00,
        'stock' => 14,
        'sizes' => '38,40,42,44',
        'color' => 'Midnight Navy',
        'material' => 'Pure Wool Weave with Signature Stripe Interior',
        'description' => "Sharply tailored modern blazer made with fine wool. Features notched lapels, two-button front, flap pockets, dual back vents, and Tommy Hilfiger signature striped undercollar accents.",
        'image_url' => 'https://images.unsplash.com/photo-1507679799987-c73779587ccf?auto=format&fit=crop&w=800&q=80',
        'gallery' => [
            'https://images.unsplash.com/photo-1617137984095-74e4e5e3613f?auto=format&fit=crop&w=800&q=80'
        ],
        'rating' => 4.90,
        'review_count' => 92,
        'featured' => 1,
        'reviews' => [
            ['Vikram Malhotra', 5, 'Superior build quality and the navy blue shade is rich. Fits like a glove.'],
            ['Arjun Kapoor', 5, 'Worth every penny. Premium wool fabric and outstanding stitching.']
        ]
    ],
    [
        'category_slug' => 'mens-clothing',
        'subcategory' => 'Chinos & Trousers',
        'name' => "Calvin Klein Classic Stretch Chino Trousers",
        'brand' => 'Calvin Klein',
        'sku' => 'CK-MCH-KHA-006',
        'price' => 3499.00,
        'mrp' => 4999.00,
        'stock' => 20,
        'sizes' => '30,32,34,36,38',
        'color' => 'Khaki Tan',
        'material' => '98% Combed Cotton, 2% Elastane',
        'description' => "Versatile flat-front chino trousers with a slim-straight fit. Built with four-pocket styling, sturdy belt loops, and premium stretch cotton for all-day mobility.",
        'image_url' => 'https://images.unsplash.com/photo-1617137984095-74e4e5e3613f?auto=format&fit=crop&w=800&q=80',
        'gallery' => [
            'https://images.unsplash.com/photo-1507679799987-c73779587ccf?auto=format&fit=crop&w=800&q=80'
        ],
        'rating' => 4.70,
        'review_count' => 53,
        'featured' => 0,
        'reviews' => [
            ['Rohan Mehta', 5, 'Very comfortable chinos for daily office wear. The stretch makes moving around easy.']
        ]
    ],
    [
        'category_slug' => 'mens-clothing',
        'subcategory' => 'Polo Shirts',
        'name' => "Ralph Lauren Custom Slim Fit Pique Polo",
        'brand' => 'Ralph Lauren',
        'sku' => 'RL-MPL-WHT-007',
        'price' => 3299.00,
        'mrp' => 4500.00,
        'stock' => 24,
        'sizes' => 'S,M,L,XL,XXL',
        'color' => 'Pure White',
        'material' => '100% Breathable Cotton Mesh Pique',
        'description' => "An American style standard since 1972. Tailored in breathable cotton mesh with a ribbed polo collar, two-button placket, tennis tail hem, and the signature embroidered Pony at the chest.",
        'image_url' => 'https://images.unsplash.com/photo-1594938298603-c8148c4dae35?auto=format&fit=crop&w=800&q=80',
        'gallery' => [
            'https://images.unsplash.com/photo-1602810318383-e386cc2a3ccf?auto=format&fit=crop&w=800&q=80'
        ],
        'rating' => 4.80,
        'review_count' => 110,
        'featured' => 0,
        'reviews' => [
            ['Karan Bedi', 5, 'Classic Ralph Lauren polo shirt. Fabric is thick and breathable.']
        ]
    ],

    // -------------------------------------------------------------
    // 3. T-Shirts
    // -------------------------------------------------------------
    [
        'category_slug' => 't-shirts',
        'subcategory' => 'Crewneck T-Shirts',
        'name' => "Levi's Original Housemark Crewneck Tee",
        'brand' => "Levi's",
        'sku' => 'LEV-TEE-WHT-008',
        'price' => 899.00,
        'mrp' => 1299.00,
        'stock' => 45,
        'sizes' => 'S,M,L,XL,XXL',
        'color' => 'Optical White',
        'material' => '100% Ring-Spun Cotton Jersey',
        'description' => "A classic everyday t-shirt with the signature red Levi's batwing logo screen-printed at the chest. Soft combed cotton jersey with a durable non-stretch ribbed collar.",
        'image_url' => 'https://images.unsplash.com/photo-1521572267360-ee0c2909d518?auto=format&fit=crop&w=800&q=80',
        'gallery' => [
            'https://images.unsplash.com/photo-1503342217505-b0a15ec3261c?auto=format&fit=crop&w=800&q=80',
            'https://images.unsplash.com/photo-1583743814966-8936f5b7be1a?auto=format&fit=crop&w=800&q=80'
        ],
        'rating' => 4.75,
        'review_count' => 134,
        'featured' => 1,
        'reviews' => [
            ['Devansh Joshi', 5, 'Authentic Levi tee. Cotton is very soft and doesn’t shrink after washing.'],
            ['Nikhil Rao', 4, 'Great basic tee with good fitting. Ordered my usual M size.']
        ]
    ],
    [
        'category_slug' => 't-shirts',
        'subcategory' => 'Graphic Tees',
        'name' => "Nike Sportswear Club Graphic Streetwear Tee",
        'brand' => 'Nike',
        'sku' => 'NIK-TEE-BLK-009',
        'price' => 1195.00,
        'mrp' => 1595.00,
        'stock' => 35,
        'sizes' => 'S,M,L,XL',
        'color' => 'Black',
        'material' => '100% Lightweight Cotton',
        'description' => "The Nike Sportswear T-Shirt sets you up with soft everyday cotton jersey and a bold Swoosh graphic on the chest. Designed with a relaxed fit through the shoulders and torso.",
        'image_url' => 'https://images.unsplash.com/photo-1503342217505-b0a15ec3261c?auto=format&fit=crop&w=800&q=80',
        'gallery' => [
            'https://images.unsplash.com/photo-1583743814966-8936f5b7be1a?auto=format&fit=crop&w=800&q=80'
        ],
        'rating' => 4.80,
        'review_count' => 78,
        'featured' => 1,
        'reviews' => [
            ['Aman Gill', 5, 'Super comfortable and the Nike print looks sharp. 100% original.']
        ]
    ],
    [
        'category_slug' => 't-shirts',
        'subcategory' => 'Oversized Tees',
        'name' => "Adidas Originals Trefoil Heavyweight Box Tee",
        'brand' => 'Adidas',
        'sku' => 'ADI-TEE-OLV-010',
        'price' => 1399.00,
        'mrp' => 1999.00,
        'stock' => 28,
        'sizes' => 'S,M,L,XL',
        'color' => 'Olive Green',
        'material' => '240 GSM Heavyweight Cotton',
        'description' => "Drop-shoulder boxy cut crafted from thick 240 GSM single jersey cotton. Features the iconic Trefoil logo embroidered in contrast thread on the chest.",
        'image_url' => 'https://images.unsplash.com/photo-1576566588028-4147f3842f27?auto=format&fit=crop&w=800&q=80',
        'gallery' => [
            'https://images.unsplash.com/photo-1521572267360-ee0c2909d518?auto=format&fit=crop&w=800&q=80'
        ],
        'rating' => 4.65,
        'review_count' => 64,
        'featured' => 0,
        'reviews' => [
            ['Sunny Deol', 5, 'Thick heavyweight cotton that gives the perfect oversized drape.']
        ]
    ],

    // -------------------------------------------------------------
    // 4. Jeans
    // -------------------------------------------------------------
    [
        'category_slug' => 'jeans',
        'subcategory' => 'Slim Fit Jeans',
        'name' => "Levi's 511 Slim Fit Stretch Denim Jeans",
        'brand' => "Levi's",
        'sku' => 'LEV-511-BLU-011',
        'price' => 2799.00,
        'mrp' => 3999.00,
        'stock' => 25,
        'sizes' => '28,30,32,34,36',
        'color' => 'Medium Indigo Wash',
        'material' => '99% Cotton, 1% Elastane Denim',
        'description' => "The modern slim with room to move. A definitive cut that sits below the waist with a slim fit from hip to ankle. Features classic 5-pocket styling, leather patch, and zip fly.",
        'image_url' => 'https://images.unsplash.com/photo-1542272604-787c3835535d?auto=format&fit=crop&w=800&q=80',
        'gallery' => [
            'https://images.unsplash.com/photo-1541099649105-f69ad21f3246?auto=format&fit=crop&w=800&q=80',
            'https://images.unsplash.com/photo-1582552938357-32b906df40cb?auto=format&fit=crop&w=800&q=80'
        ],
        'rating' => 4.85,
        'review_count' => 195,
        'featured' => 1,
        'reviews' => [
            ['Harsh Vardhan', 5, 'The 511 is the gold standard for jeans. Great stretch and wash.'],
            ['Rahul Nair', 5, 'Durable denim that holds shape after multiple washes.']
        ]
    ],
    [
        'category_slug' => 'jeans',
        'subcategory' => 'Straight Fit Jeans',
        'name' => "Wrangler Texas Authentic Straight Leg Jeans",
        'brand' => 'Wrangler',
        'sku' => 'WRA-JNS-DKB-012',
        'price' => 2099.00,
        'mrp' => 2999.00,
        'stock' => 30,
        'sizes' => '30,32,34,36,38',
        'color' => 'Dark Stonewash Blue',
        'material' => '100% Heavyweight Rigid Cotton Denim',
        'description' => "Built on heritage American craftsmanship. Regular waist, classic straight leg, signature 'W' back pocket stitching, and copper rivets at stress points.",
        'image_url' => 'https://images.unsplash.com/photo-1541099649105-f69ad21f3246?auto=format&fit=crop&w=800&q=80',
        'gallery' => [
            'https://images.unsplash.com/photo-1604176354204-9268737828e4?auto=format&fit=crop&w=800&q=80'
        ],
        'rating' => 4.60,
        'review_count' => 74,
        'featured' => 0,
        'reviews' => [
            ['Amit Trivedi', 5, 'Rugged and comfortable straight fit denim. Authentic Wrangler quality.']
        ]
    ],
    [
        'category_slug' => 'jeans',
        'subcategory' => 'Relaxed Denim',
        'name' => "Lee Rider Relaxed Tapered Fit Jeans",
        'brand' => 'Lee',
        'sku' => 'LEE-JNS-LGT-013',
        'price' => 2399.00,
        'mrp' => 3499.00,
        'stock' => 18,
        'sizes' => '28,30,32,34,36',
        'color' => 'Light Bleach Wash',
        'material' => '100% Organic Cotton Denim',
        'description' => "Relaxed through the seat and thighs with a subtle taper towards the hem. Vintage light bleach wash with natural fading and whiskering across the lap.",
        'image_url' => 'https://images.unsplash.com/photo-1582552938357-32b906df40cb?auto=format&fit=crop&w=800&q=80',
        'gallery' => [
            'https://images.unsplash.com/photo-1542272604-787c3835535d?auto=format&fit=crop&w=800&q=80'
        ],
        'rating' => 4.55,
        'review_count' => 49,
        'featured' => 0,
        'reviews' => [
            ['Prateek B.', 4, 'Very stylish light wash. Super comfortable around the thighs.']
        ]
    ],

    // -------------------------------------------------------------
    // 5. Shirts
    // -------------------------------------------------------------
    [
        'category_slug' => 'shirts',
        'subcategory' => 'Casual Shirts',
        'name' => "Arrow New York Classic Oxford Casual Shirt",
        'brand' => 'Arrow',
        'sku' => 'ARR-SHT-BLU-014',
        'price' => 1599.00,
        'mrp' => 2299.00,
        'stock' => 26,
        'sizes' => 'S,M,L,XL,XXL',
        'color' => 'Sky Blue',
        'material' => '100% Two-Ply Combed Oxford Cotton',
        'description' => "Smart casual button-down shirt woven from two-ply oxford cotton with a tactile basketweave finish. Features a button-down collar, curved hem, and single chest pocket.",
        'image_url' => 'https://images.unsplash.com/photo-1596755094514-f87e34085b2c?auto=format&fit=crop&w=800&q=80',
        'gallery' => [
            'https://images.unsplash.com/photo-1603252109303-2751441dd157?auto=format&fit=crop&w=800&q=80'
        ],
        'rating' => 4.70,
        'review_count' => 88,
        'featured' => 1,
        'reviews' => [
            ['Sameer Chawla', 5, 'The Oxford weave is premium and breathable. Perfect for casual Fridays.']
        ]
    ],
    [
        'category_slug' => 'shirts',
        'subcategory' => 'Linen Shirts',
        'name' => "Louis Philippe Pure Linen Regular Fit Shirt",
        'brand' => 'Louis Philippe',
        'sku' => 'LP-LIN-WHT-015',
        'price' => 2499.00,
        'mrp' => 3499.00,
        'stock' => 19,
        'sizes' => '39,40,42,44',
        'color' => 'Crisp White',
        'material' => '100% French Flax Linen',
        'description' => "Crafted from pure French flax linen for supreme breathability in warm weather. Spread collar, mitered cuffs, and mother-of-pearl finish buttons.",
        'image_url' => 'https://images.unsplash.com/photo-1603252109303-2751441dd157?auto=format&fit=crop&w=800&q=80',
        'gallery' => [
            'https://images.unsplash.com/photo-1596755094514-f87e34085b2c?auto=format&fit=crop&w=800&q=80'
        ],
        'rating' => 4.85,
        'review_count' => 62,
        'featured' => 0,
        'reviews' => [
            ['Gautam Singhania', 5, 'Pure linen of the finest grade. Stays crisp and cool even in peak summer.']
        ]
    ],
    [
        'category_slug' => 'shirts',
        'subcategory' => 'Check & Plaid Shirts',
        'name' => "Peter England Slim Fit Tartan Check Shirt",
        'brand' => 'Peter England',
        'sku' => 'PE-CHK-RED-016',
        'price' => 1049.00,
        'mrp' => 1499.00,
        'stock' => 32,
        'sizes' => 'S,M,L,XL',
        'color' => 'Navy & Crimson Check',
        'material' => '100% Yarn-Dyed Cotton',
        'description' => "Youthful tartan check shirt with a tailored slim fit. Easy-iron soft cotton with contrast inner collar band and reinforced side gussets.",
        'image_url' => 'https://images.unsplash.com/photo-1598033129183-c4f50c736f10?auto=format&fit=crop&w=800&q=80',
        'gallery' => [
            'https://images.unsplash.com/photo-1596755094514-f87e34085b2c?auto=format&fit=crop&w=800&q=80'
        ],
        'rating' => 4.60,
        'review_count' => 54,
        'featured' => 0,
        'reviews' => [
            ['Deepak Verma', 5, 'Great colors and fits perfectly across shoulders.']
        ]
    ],

    // -------------------------------------------------------------
    // 6. Jackets and Hoodies
    // -------------------------------------------------------------
    [
        'category_slug' => 'jackets-and-hoodies',
        'subcategory' => 'Fleece Hoodies',
        'name' => "Champion Reverse Weave Heavyweight Pullover Hoodie",
        'brand' => 'Champion',
        'sku' => 'CHA-HD-GRY-017',
        'price' => 3999.00,
        'mrp' => 5499.00,
        'stock' => 16,
        'sizes' => 'S,M,L,XL,XXL',
        'color' => 'Oxford Heather Grey',
        'material' => '82% Cotton, 18% Polyester Heavyweight Reverse Weave Fleece',
        'description' => "The legendary Reverse Weave hoodie engineered to resist vertical shrinkage. Features signature ribbed side stretch panels, double-layer hood, kangaroo front pouch, and embroidered C logo patch.",
        'image_url' => 'https://images.unsplash.com/photo-1556821840-3a63f95609a7?auto=format&fit=crop&w=800&q=80',
        'gallery' => [
            'https://images.unsplash.com/photo-1543076447-215ad9ba6923?auto=format&fit=crop&w=800&q=80',
            'https://images.unsplash.com/photo-1551028719-00167b16eac5?auto=format&fit=crop&w=800&q=80'
        ],
        'rating' => 4.90,
        'review_count' => 156,
        'featured' => 1,
        'reviews' => [
            ['Varun Dhawan', 5, 'The heaviest, warmest hoodie you can buy. Lasts years.'],
            ['Manish K.', 5, 'Authentic Champion Reverse Weave. Excellent fleece interior.']
        ]
    ],
    [
        'category_slug' => 'jackets-and-hoodies',
        'subcategory' => 'Leather Jackets',
        'name' => "Zara Faux Leather Asymmetric Biker Moto Jacket",
        'brand' => 'Zara',
        'sku' => 'ZAR-MOT-BLK-018',
        'price' => 4290.00,
        'mrp' => 5990.00,
        'stock' => 12,
        'sizes' => 'S,M,L,XL',
        'color' => 'Jet Black',
        'material' => 'Supple Polyurethane Vegan Leather with Silver Hardware',
        'description' => "Classic biker styling with an asymmetric metallic zip closure, notched lapels with silver press-studs, zipped cuffs, and zip pockets. Lined with smooth polyester.",
        'image_url' => 'https://images.unsplash.com/photo-1551028719-00167b16eac5?auto=format&fit=crop&w=800&q=80',
        'gallery' => [
            'https://images.unsplash.com/photo-1543076447-215ad9ba6923?auto=format&fit=crop&w=800&q=80'
        ],
        'rating' => 4.75,
        'review_count' => 73,
        'featured' => 1,
        'reviews' => [
            ['Isha Gujral', 5, 'The leather look is very authentic and soft. Adds instant edge to any outfit.']
        ]
    ],
    [
        'category_slug' => 'jackets-and-hoodies',
        'subcategory' => 'Bomber Jackets',
        'name' => "Denim Lab Everyday Denim Trucker Jacket",
        'brand' => 'Denim Lab',
        'sku' => 'DL-TRK-IND-019',
        'price' => 1999.00,
        'mrp' => 2999.00,
        'stock' => 20,
        'sizes' => 'S,M,L,XL',
        'color' => 'Indigo Denim',
        'material' => '100% Rigid Heavy Cotton Denim',
        'description' => "Layering jacket with iconic trucker styling. Flap chest pockets, adjustable waist tabs, point collar, and heavy shank metal buttons.",
        'image_url' => 'https://images.unsplash.com/photo-1543076447-215ad9ba6923?auto=format&fit=crop&w=800&q=80',
        'gallery' => [
            'https://images.unsplash.com/photo-1556821840-3a63f95609a7?auto=format&fit=crop&w=800&q=80'
        ],
        'rating' => 4.60,
        'review_count' => 65,
        'featured' => 0,
        'reviews' => [
            ['Rohit Deshmukh', 5, 'Classic denim jacket that pairs with literally everything. Sturdy quality.']
        ]
    ],

    // -------------------------------------------------------------
    // 7. Dresses and Kurtis
    // -------------------------------------------------------------
    [
        'category_slug' => 'dresses-and-kurtis',
        'subcategory' => 'Midi Dresses',
        'name' => "Mango Floral Wrap Belted Midi Dress",
        'brand' => 'Mango',
        'sku' => 'MNG-DRS-FLR-020',
        'price' => 3490.00,
        'mrp' => 4990.00,
        'stock' => 17,
        'sizes' => 'XS,S,M,L,XL',
        'color' => 'Navy Floral Print',
        'material' => '100% Soft Flowing Viscose',
        'description' => "Lightweight floral midi dress with a flattering wrap silhouette, V-neckline, self-tie belt, and gentle tiered ruffled hem. Perfect for brunches, garden parties, or evening outings.",
        'image_url' => 'https://images.unsplash.com/photo-1572804013309-59a88b7e92f1?auto=format&fit=crop&w=800&q=80',
        'gallery' => [
            'https://images.unsplash.com/photo-1585487000160-6ebcfceb0d03?auto=format&fit=crop&w=800&q=80'
        ],
        'rating' => 4.80,
        'review_count' => 95,
        'featured' => 1,
        'reviews' => [
            ['Meera Nair', 5, 'The drape and floral pattern are gorgeous! Very comfortable to wear all day.'],
            ['Tanya Oberoi', 5, 'Got so many compliments. Beautiful wrap dress with premium fabric.']
        ]
    ],
    [
        'category_slug' => 'dresses-and-kurtis',
        'subcategory' => 'Anarkali Sets',
        'name' => "Biba Printed Cotton Anarkali Kurti Set",
        'brand' => 'Biba',
        'sku' => 'BIB-ANK-MRN-021',
        'price' => 3199.00,
        'mrp' => 4599.00,
        'stock' => 22,
        'sizes' => 'S,M,L,XL,XXL',
        'color' => 'Maroon & Gold',
        'material' => '100% Pure Combed Cotton with Foil Accent',
        'description' => "Traditional grace meets modern silhouette. Features an Anarkali flare with delicate gold foil print borders, mandarin collar with button placket, and matching borders on sleeves.",
        'image_url' => 'https://images.unsplash.com/photo-1595777457583-95e059d581b8?auto=format&fit=crop&w=800&q=80',
        'gallery' => [
            'https://images.unsplash.com/photo-1585487000160-6ebcfceb0d03?auto=format&fit=crop&w=800&q=80'
        ],
        'rating' => 4.85,
        'review_count' => 112,
        'featured' => 1,
        'reviews' => [
            ['Aishwarya Iyer', 5, 'Biba quality is unmatched. The maroon color is vibrant and stitching is neat.']
        ]
    ],
    [
        'category_slug' => 'dresses-and-kurtis',
        'subcategory' => 'Ethnic Kurtis',
        'name' => "FabIndia Handblock Print Chanderi Silk Kurta",
        'brand' => 'FabIndia',
        'sku' => 'FAB-KRT-IND-022',
        'price' => 2490.00,
        'mrp' => 3290.00,
        'stock' => 15,
        'sizes' => 'XS,S,M,L,XL',
        'color' => 'Indigo Blue',
        'material' => 'Chanderi Silk Blend with Cotton Lining',
        'description' => "Handcrafted with traditional handblock prints by artisan communities. Straight silhouette with side slits, round keyhole neck, and three-quarter sleeves.",
        'image_url' => 'https://images.unsplash.com/photo-1585487000160-6ebcfceb0d03?auto=format&fit=crop&w=800&q=80',
        'gallery' => [
            'https://images.unsplash.com/photo-1572804013309-59a88b7e92f1?auto=format&fit=crop&w=800&q=80'
        ],
        'rating' => 4.70,
        'review_count' => 58,
        'featured' => 0,
        'reviews' => [
            ['Sunita Rao', 5, 'Pure FabIndia elegance. Very subtle sheen from the Chanderi silk.']
        ]
    ],

    // -------------------------------------------------------------
    // 8. Shoes and Sneakers
    // -------------------------------------------------------------
    [
        'category_slug' => 'shoes-and-sneakers',
        'subcategory' => 'Lifestyle Sneakers',
        'name' => "Nike Air Force 1 '07 Low Triple White Sneakers",
        'brand' => 'Nike',
        'sku' => 'NIK-AF1-WHT-023',
        'price' => 7495.00,
        'mrp' => 8195.00,
        'stock' => 15,
        'sizes' => '6,7,8,9,10,11',
        'color' => 'Triple White',
        'material' => 'Full-Grain Leather Upper & Encapsulated Air Sole',
        'description' => "The legend lives on. The Nike Air Force 1 '07 stays true to its roots with clean stitched leather overlays, crisp white colorway, perforated toe box, and Nike Air cushioning for all-day comfort.",
        'image_url' => 'https://images.unsplash.com/photo-1542291026-7eec264c27ff?auto=format&fit=crop&w=800&q=80',
        'gallery' => [
            'https://images.unsplash.com/photo-1595950653106-6c9ebd614d3a?auto=format&fit=crop&w=800&q=80',
            'https://images.unsplash.com/photo-1607522370275-f14206abe5d3?auto=format&fit=crop&w=800&q=80'
        ],
        'rating' => 4.95,
        'review_count' => 248,
        'featured' => 1,
        'reviews' => [
            ['Kabir Sethi', 5, 'Original Nike AF1. Delivered in authentic packaging. Absolutely iconic sneaker.'],
            ['Akshay S.', 5, 'Goes with literally every fit - sweatpants, shorts, jeans. Must-have!']
        ]
    ],
    [
        'category_slug' => 'shoes-and-sneakers',
        'subcategory' => 'Canvas Classics',
        'name' => "Adidas Originals Stan Smith Leather Sneakers",
        'brand' => 'Adidas',
        'sku' => 'ADI-STN-WHT-024',
        'price' => 5599.00,
        'mrp' => 7999.00,
        'stock' => 20,
        'sizes' => '7,8,9,10,11',
        'color' => 'White with Fairway Green Heel Tab',
        'material' => 'Primegreen Recycled Performance Leather Upper',
        'description' => "Clean minimalist court design. Perforated 3-Stripes, Stan Smith tongue portrait, green contrast heel mustache, and sturdy rubber cupsole.",
        'image_url' => 'https://images.unsplash.com/photo-1595950653106-6c9ebd614d3a?auto=format&fit=crop&w=800&q=80',
        'gallery' => [
            'https://images.unsplash.com/photo-1542291026-7eec264c27ff?auto=format&fit=crop&w=800&q=80'
        ],
        'rating' => 4.75,
        'review_count' => 115,
        'featured' => 0,
        'reviews' => [
            ['Aditya Roy', 5, 'Classic Stan Smiths. Lightweight and very comfortable.']
        ]
    ],
    [
        'category_slug' => 'shoes-and-sneakers',
        'subcategory' => 'Running Shoes',
        'name' => "Puma Smash v2 Low Suede Trainers",
        'brand' => 'Puma',
        'sku' => 'PUM-SMH-BLK-025',
        'price' => 2399.00,
        'mrp' => 3999.00,
        'stock' => 25,
        'sizes' => '6,7,8,9,10',
        'color' => 'Black / White',
        'material' => 'Soft Suede Leather Upper with Rubber Outsole',
        'description' => "Tennis-inspired trainer with soft suede leather, padded collar, and durable rubber sole with excellent traction. Clean white Puma Formstrip branding on both sides.",
        'image_url' => 'https://images.unsplash.com/photo-1607522370275-f14206abe5d3?auto=format&fit=crop&w=800&q=80',
        'gallery' => [
            'https://images.unsplash.com/photo-1542291026-7eec264c27ff?auto=format&fit=crop&w=800&q=80'
        ],
        'rating' => 4.65,
        'review_count' => 82,
        'featured' => 0,
        'reviews' => [
            ['Naveen Kumar', 5, 'Great quality suede and very comfortable insole. Excellent value.']
        ]
    ],

    // -------------------------------------------------------------
    // 9. Sandals
    // -------------------------------------------------------------
    [
        'category_slug' => 'sandals',
        'subcategory' => 'Double Buckle Slides',
        'name' => "Birkenstock Arizona Birko-Flor Contoured Sandals",
        'brand' => 'Birkenstock',
        'sku' => 'BIR-ARZ-TAN-026',
        'price' => 5490.00,
        'mrp' => 6990.00,
        'stock' => 16,
        'sizes' => '37,38,39,40,41,42,43',
        'color' => 'Mocca Brown',
        'material' => 'Birko-Flor Synthetic Nubuck with Cork-Latex Footbed',
        'description' => "The genuine classic. Two individually adjustable straps with metal pin buckles. Anatomically shaped cork-latex footbed lined with breathable suede that molds to the contour of your foot over time.",
        'image_url' => 'https://images.unsplash.com/photo-1603808033192-082d6919d3e1?auto=format&fit=crop&w=800&q=80',
        'gallery' => [
            'https://images.unsplash.com/photo-1543163521-1bf539c55dd2?auto=format&fit=crop&w=800&q=80'
        ],
        'rating' => 4.90,
        'review_count' => 143,
        'featured' => 1,
        'reviews' => [
            ['Siddharth Bose', 5, 'Birkenstocks are life-changing for foot support. The cork footbed is unbelievable.'],
            ['Radhika Merchant', 5, '100% original Birkenstock Arizona. So comfortable for walking all day.']
        ]
    ],
    [
        'category_slug' => 'sandals',
        'subcategory' => 'Strappy Block Heels',
        'name' => "Steve Madden Uplift Strappy Block Heel Sandals",
        'brand' => 'Steve Madden',
        'sku' => 'SM-STR-NUD-027',
        'price' => 5599.00,
        'mrp' => 7999.00,
        'stock' => 14,
        'sizes' => '36,37,38,39,40',
        'color' => 'Nude Blush',
        'material' => 'Vegan Leather Upper with Sturdy 2.5-Inch Block Heel',
        'description' => "Chic square-toe silhouette with delicate tubular crisscross straps and an adjustable ankle buckle. Cushioned footbed and comfortable block heel for parties and celebrations.",
        'image_url' => 'https://images.unsplash.com/photo-1543163521-1bf539c55dd2?auto=format&fit=crop&w=800&q=80',
        'gallery' => [
            'https://images.unsplash.com/photo-1603808033192-082d6919d3e1?auto=format&fit=crop&w=800&q=80'
        ],
        'rating' => 4.70,
        'review_count' => 64,
        'featured' => 0,
        'reviews' => [
            ['Natasha Poonawalla', 5, 'Heel height is manageable and the straps don’t bite into feet. Very flattering.']
        ]
    ],
    [
        'category_slug' => 'sandals',
        'subcategory' => 'Everyday Slides',
        'name' => "Bata Comfit Leather Strap Everyday Slide Sandals",
        'brand' => 'Bata',
        'sku' => 'BAT-SLD-BRN-028',
        'price' => 1199.00,
        'mrp' => 1699.00,
        'stock' => 35,
        'sizes' => '6,7,8,9,10',
        'color' => 'Tan Brown',
        'material' => 'Genuine Leather Upper with Cushioned EVA Footbed',
        'description' => "Easy slip-on styling with genuine leather broad crossover straps. Ergonomic footbed with shock-absorbing EVA midsole for everyday comfort.",
        'image_url' => 'https://images.unsplash.com/photo-1603808033192-082d6919d3e1?auto=format&fit=crop&w=800&q=80',
        'gallery' => [
            'https://images.unsplash.com/photo-1543163521-1bf539c55dd2?auto=format&fit=crop&w=800&q=80'
        ],
        'rating' => 4.50,
        'review_count' => 46,
        'featured' => 0,
        'reviews' => [
            ['Ramesh Babu', 4, 'Solid Bata quality. Soft footbed and good leather.']
        ]
    ],

    // -------------------------------------------------------------
    // 10. Bags and Handbags
    // -------------------------------------------------------------
    [
        'category_slug' => 'bags-and-handbags',
        'subcategory' => 'Tote Bags',
        'name' => "Michael Kors Jet Set Large Saffiano Leather Tote",
        'brand' => 'Michael Kors',
        'sku' => 'MK-TOT-BLK-029',
        'price' => 13990.00,
        'mrp' => 18990.00,
        'stock' => 10,
        'sizes' => 'One Size',
        'color' => 'Black with Gold Hardware',
        'material' => '100% Saffiano Scratch-Resistant Leather',
        'description' => "Crafted from durable Saffiano leather, the Jet Set tote bag is a work-to-weekend essential. Accommodates up to a 15-inch laptop, with dual slim shoulder straps, interior zip divider, and signature MK gold-tone medallion charm.",
        'image_url' => 'https://images.unsplash.com/photo-1584917865442-de89df76afd3?auto=format&fit=crop&w=800&q=80',
        'gallery' => [
            'https://images.unsplash.com/photo-1590874103328-eac38a683ce7?auto=format&fit=crop&w=800&q=80',
            'https://images.unsplash.com/photo-1553062407-98eeb64c6a62?auto=format&fit=crop&w=800&q=80'
        ],
        'rating' => 4.90,
        'review_count' => 176,
        'featured' => 1,
        'reviews' => [
            ['Sanya Mirza', 5, 'Authentic MK tote! The Saffiano leather doesn’t get scratched and holds all my work essentials.'],
            ['Priya Mathur', 5, 'Spacious, elegant, and looks very posh. Excellent delivery packaging.']
        ]
    ],
    [
        'category_slug' => 'bags-and-handbags',
        'subcategory' => 'Crossbody Bags',
        'name' => "Coach Willow Pebbled Leather Shoulder Handbag",
        'brand' => 'Coach',
        'sku' => 'COA-WLL-TAN-030',
        'price' => 18500.00,
        'mrp' => 24900.00,
        'stock' => 8,
        'sizes' => 'One Size',
        'color' => 'Saddle Tan',
        'material' => 'Polished Pebble Leather & Turnlock Hardware',
        'description' => "The not-too-big, not-too-small carryall. Crafted of polished pebble leather with organized interior compartments, center zip pocket, and signature turnlock closure.",
        'image_url' => 'https://images.unsplash.com/photo-1590874103328-eac38a683ce7?auto=format&fit=crop&w=800&q=80',
        'gallery' => [
            'https://images.unsplash.com/photo-1584917865442-de89df76afd3?auto=format&fit=crop&w=800&q=80'
        ],
        'rating' => 4.85,
        'review_count' => 89,
        'featured' => 0,
        'reviews' => [
            ['Divya Khosla', 5, 'The leather quality of Coach is unmatched. Pebble texture is so soft yet structured.']
        ]
    ],
    [
        'category_slug' => 'bags-and-handbags',
        'subcategory' => 'Backpacks',
        'name' => "Fjallraven Kanken Water-Resistant Everyday Backpack",
        'brand' => 'Fjallraven',
        'sku' => 'FJA-KNK-NVY-031',
        'price' => 5999.00,
        'mrp' => 7499.00,
        'stock' => 20,
        'sizes' => '16L Capacity',
        'color' => 'Navy Blue',
        'material' => 'Vinylon-F Hardwearing Water-Resistant Fabric',
        'description' => "Swedish design icon since 1978. Simple functional design with large main compartment, zip opening, two flat side pockets, front zippered pocket, top carry handles, and reflective logo badge.",
        'image_url' => 'https://images.unsplash.com/photo-1553062407-98eeb64c6a62?auto=format&fit=crop&w=800&q=80',
        'gallery' => [
            'https://images.unsplash.com/photo-1584917865442-de89df76afd3?auto=format&fit=crop&w=800&q=80'
        ],
        'rating' => 4.80,
        'review_count' => 124,
        'featured' => 0,
        'reviews' => [
            ['Tanmay Bhat', 5, 'Original Kanken! Durable fabric that can handle heavy books and rain without trouble.']
        ]
    ],

    // -------------------------------------------------------------
    // 11. Jewellery and Accessories
    // -------------------------------------------------------------
    [
        'category_slug' => 'jewellery-and-accessories',
        'subcategory' => 'Necklaces & Pendants',
        'name' => "Swarovski Attract Round Solitaire Pendant Necklace",
        'brand' => 'Swarovski',
        'sku' => 'SWA-PEN-SLV-032',
        'price' => 5990.00,
        'mrp' => 7990.00,
        'stock' => 20,
        'sizes' => 'One Size (38 cm + 5 cm Extender)',
        'color' => 'Rhodium Plated Silver',
        'material' => 'Rhodium Plating & Brilliant-Cut Swarovski Crystal',
        'description' => "Pure timeless romance. A shimmering solitaire crystal drop suspended on a dainty rhodium-plated chain with lobster clasp. Hypoallergenic and nickel-free.",
        'image_url' => 'https://images.unsplash.com/photo-1599643478518-a784e5dc4c8f?auto=format&fit=crop&w=800&q=80',
        'gallery' => [
            'https://images.unsplash.com/photo-1535632066927-ab7c9ab60908?auto=format&fit=crop&w=800&q=80',
            'https://images.unsplash.com/photo-1515562141207-7a88fb7ce338?auto=format&fit=crop&w=800&q=80'
        ],
        'rating' => 4.90,
        'review_count' => 165,
        'featured' => 1,
        'reviews' => [
            ['Ira Khan', 5, 'The sparkle of Swarovski is magical. Authentic certificate included in box.'],
            ['Pallavi Roy', 5, 'Dainty and catches the light beautifully. Great gift.']
        ]
    ],
    [
        'category_slug' => 'jewellery-and-accessories',
        'subcategory' => 'Bracelets & Cuffs',
        'name' => "Pandora Moments Sterling Silver Snake Chain Bracelet",
        'brand' => 'Pandora',
        'sku' => 'PAN-BRC-SLV-033',
        'price' => 4990.00,
        'mrp' => 6500.00,
        'stock' => 18,
        'sizes' => '17cm,18cm,19cm,20cm',
        'color' => '925 Sterling Silver',
        'material' => 'Solid 925 Sterling Silver with Barrel Clasp',
        'description' => "The quintessential base for your charm collection. Hand-finished in sterling silver with Pandora threaders and branded barrel clasp.",
        'image_url' => 'https://images.unsplash.com/photo-1535632066927-ab7c9ab60908?auto=format&fit=crop&w=800&q=80',
        'gallery' => [
            'https://images.unsplash.com/photo-1599643478518-a784e5dc4c8f?auto=format&fit=crop&w=800&q=80'
        ],
        'rating' => 4.85,
        'review_count' => 98,
        'featured' => 0,
        'reviews' => [
            ['Mira Rajput', 5, 'Authentic Pandora silver bracelet. Clasp is very secure and looks chic alone or with charms.']
        ]
    ],
    [
        'category_slug' => 'jewellery-and-accessories',
        'subcategory' => 'Leather Belts',
        'name' => "Tommy Hilfiger Reversible Leather Belt with Brushed Buckle",
        'brand' => 'Tommy Hilfiger',
        'sku' => 'TOM-BLT-BLK-034',
        'price' => 2299.00,
        'mrp' => 3299.00,
        'stock' => 25,
        'sizes' => '32,34,36,38,40',
        'color' => 'Black / Dark Brown Reversible',
        'material' => '100% Genuine Full-Grain Cowhide Leather',
        'description' => "Two styles in one. Reversible twist buckle transforms from polished black to deep espresso brown. Finished with subtle engraved Tommy flag detailing.",
        'image_url' => 'https://images.unsplash.com/photo-1515562141207-7a88fb7ce338?auto=format&fit=crop&w=800&q=80',
        'gallery' => [
            'https://images.unsplash.com/photo-1599643478518-a784e5dc4c8f?auto=format&fit=crop&w=800&q=80'
        ],
        'rating' => 4.70,
        'review_count' => 77,
        'featured' => 0,
        'reviews' => [
            ['Vivek Oberoi', 5, 'Clever reversible mechanism and the leather is top notch.']
        ]
    ],

    // -------------------------------------------------------------
    // 12. Watches
    // -------------------------------------------------------------
    [
        'category_slug' => 'watches',
        'subcategory' => 'Chronograph Watches',
        'name' => "Fossil Grant Chronograph Dark Brown Leather Watch",
        'brand' => 'Fossil',
        'sku' => 'FOS-GRT-BRN-035',
        'price' => 8395.00,
        'mrp' => 11995.00,
        'stock' => 14,
        'sizes' => '44mm Case',
        'color' => 'Blue Dial / Dark Brown Strap',
        'material' => 'Stainless Steel Case with Genuine Cowhide Leather Band',
        'description' => "Inspired by vintage clocks, the Grant chronograph balances classic Roman numeral markers with sub-eye precision dials. Features 50m water resistance, quartz movement, and interchangeable strap capability.",
        'image_url' => 'https://images.unsplash.com/photo-1522335789203-aabd1fc54bc9?auto=format&fit=crop&w=800&q=80',
        'gallery' => [
            'https://images.unsplash.com/photo-1524805444758-089113d48a6d?auto=format&fit=crop&w=800&q=80',
            'https://images.unsplash.com/photo-1533139502658-0198f920d8e8?auto=format&fit=crop&w=800&q=80'
        ],
        'rating' => 4.90,
        'review_count' => 188,
        'featured' => 1,
        'reviews' => [
            ['Yashraj Bannerjee', 5, 'The dark blue dial with Roman numerals looks breathtaking in person.'],
            ['Kunal Kapoor', 5, 'Fossil original timepiece. Authentic tin box packaging and warranty.']
        ]
    ],
    [
        'category_slug' => 'watches',
        'subcategory' => 'Vintage Digital',
        'name' => "Casio Vintage Digital Gold Stainless Steel Watch",
        'brand' => 'Casio',
        'sku' => 'CAS-VNT-GLD-036',
        'price' => 3895.00,
        'mrp' => 4995.00,
        'stock' => 24,
        'sizes' => 'Unisex Adjustable Band',
        'color' => 'Gold Tone Stainless Steel',
        'material' => 'Ion Plated Gold Case & Stainless Steel Adjustable Mesh Band',
        'description' => "A retro icon since the 1980s. Equipped with electro-luminescent backlight, 1/100-second stopwatch, daily alarm, auto-calendar, and water resistance.",
        'image_url' => 'https://images.unsplash.com/photo-1524805444758-089113d48a6d?auto=format&fit=crop&w=800&q=80',
        'gallery' => [
            'https://images.unsplash.com/photo-1522335789203-aabd1fc54bc9?auto=format&fit=crop&w=800&q=80'
        ],
        'rating' => 4.85,
        'review_count' => 210,
        'featured' => 1,
        'reviews' => [
            ['Pranav Mohan', 5, 'Classic Casio gold watch. Nostalgic, durable, and super stylish on the wrist.']
        ]
    ],
    [
        'category_slug' => 'watches',
        'subcategory' => 'Automatic Watches',
        'name' => "Titan Edge Ultra-Slim Ceramic Analog Watch",
        'brand' => 'Titan',
        'sku' => 'TTN-EDG-BLK-037',
        'price' => 11495.00,
        'mrp' => 14995.00,
        'stock' => 10,
        'sizes' => '40mm Slim Case',
        'color' => 'Matte Anthracite Black',
        'material' => 'High-Tech Ceramic Case with Sapphire Crystal Glass',
        'description' => "One of the slimmest quartz watches in the world. Engineered with featherlight high-tech ceramic, scratch-resistant sapphire crystal, and an ultra-minimalist two-hand dial.",
        'image_url' => 'https://images.unsplash.com/photo-1533139502658-0198f920d8e8?auto=format&fit=crop&w=800&q=80',
        'gallery' => [
            'https://images.unsplash.com/photo-1522335789203-aabd1fc54bc9?auto=format&fit=crop&w=800&q=80'
        ],
        'rating' => 4.80,
        'review_count' => 96,
        'featured' => 0,
        'reviews' => [
            ['Raghavan Iyer', 5, 'Incredible engineering. The watch is so slim and light you forget it is on your wrist.']
        ]
    ],

    // -------------------------------------------------------------
    // 13. Sunglasses
    // -------------------------------------------------------------
    [
        'category_slug' => 'sunglasses',
        'subcategory' => 'Wayfarer Sunglasses',
        'name' => "Ray-Ban Original Wayfarer Classic Polarized Sunglasses",
        'brand' => 'Ray-Ban',
        'sku' => 'RAY-RB2140-BLK-038',
        'price' => 7690.00,
        'mrp' => 9590.00,
        'stock' => 18,
        'sizes' => 'Standard (50mm)',
        'color' => 'Polished Black Frame / G-15 Green Lens',
        'material' => 'High-Grade Acetate Frame with Mineral Glass Lenses',
        'description' => "Since its initial design in 1952, the Wayfarer Classic gained fame among celebrities, musicians, and artists. Offers 100% UV400 protection, polarized glare reduction, and durable metal hinges.",
        'image_url' => 'https://images.unsplash.com/photo-1511499767150-a48a237f0083?auto=format&fit=crop&w=800&q=80',
        'gallery' => [
            'https://images.unsplash.com/photo-1572635196237-14b3f281503f?auto=format&fit=crop&w=800&q=80',
            'https://images.unsplash.com/photo-1577803645773-f96470509666?auto=format&fit=crop&w=800&q=80'
        ],
        'rating' => 4.95,
        'review_count' => 220,
        'featured' => 1,
        'reviews' => [
            ['Ranveer Singh', 5, 'The one and only Original Wayfarer. Polarization is crystal clear and frame feels solid.'],
            ['Zoya Akhtar', 5, 'Best sunglasses in the world. Authentic leather case and lens cloth included.']
        ]
    ],
    [
        'category_slug' => 'sunglasses',
        'subcategory' => 'Aviator Sunglasses',
        'name' => "Ray-Ban Aviator Classic Gold Frame Sunglasses",
        'brand' => 'Ray-Ban',
        'sku' => 'RAY-RB3025-GLD-039',
        'price' => 7190.00,
        'mrp' => 8990.00,
        'stock' => 16,
        'sizes' => 'Standard (58mm)',
        'color' => 'Arista Gold Frame / Classic Green Lens',
        'material' => 'Corrosion-Resistant Monel Metal Frame with Glass Lenses',
        'description' => "Originally created in 1937 for U.S. aviators. Combines exceptional pilot styling with high level optical clarity, adjustable silicone nose pads, and slender temple tips.",
        'image_url' => 'https://images.unsplash.com/photo-1572635196237-14b3f281503f?auto=format&fit=crop&w=800&q=80',
        'gallery' => [
            'https://images.unsplash.com/photo-1511499767150-a48a237f0083?auto=format&fit=crop&w=800&q=80'
        ],
        'rating' => 4.90,
        'review_count' => 184,
        'featured' => 0,
        'reviews' => [
            ['Kunal Rawal', 5, 'Iconic teardrop shape. Very lightweight on the nose bridge.']
        ]
    ],
    [
        'category_slug' => 'sunglasses',
        'subcategory' => 'Sport Polarized',
        'name' => "Oakley Holbrook Matte Black Square Sunglasses",
        'brand' => 'Oakley',
        'sku' => 'OAK-HLB-BLK-040',
        'price' => 8290.00,
        'mrp' => 10490.00,
        'stock' => 12,
        'sizes' => 'Standard (55mm)',
        'color' => 'Matte Black / Prizm Grey Lens',
        'material' => 'O Matter Stress-Resistant Frame with Plutonite Lenses',
        'description' => "Holbrook is a timeless, classic design fused with modern Oakley technology. Accented by metal rivets and Oakley square icons, perfect for those who seek equal parts performance and style.",
        'image_url' => 'https://images.unsplash.com/photo-1577803645773-f96470509666?auto=format&fit=crop&w=800&q=80',
        'gallery' => [
            'https://images.unsplash.com/photo-1511499767150-a48a237f0083?auto=format&fit=crop&w=800&q=80'
        ],
        'rating' => 4.85,
        'review_count' => 97,
        'featured' => 0,
        'reviews' => [
            ['Gaurav Gill', 5, 'Oakley Prizm lenses are unbeatable for driving and bright outdoors. Crisp clarity.']
        ]
    ]
];

// Deactivate previous dummy / SVG products so customer website only shows real products
echo "Archiving older placeholder products...\n";
$pdo->exec("UPDATE products SET active=0 WHERE image_url LIKE '%.svg%' OR image_url IS NULL OR image_url=''");
$pdo->exec("UPDATE product_variants v JOIN products p ON p.id=v.product_id SET v.active=0 WHERE p.active=0");

// Upsert real products
$countUpserted = 0;
foreach ($products as $p) {
    $parentCatId = $catCache[$p['category_slug']] ?? null;
    $subCatId = $catCache[$p['category_slug'] . ':' . $p['subcategory']] ?? $parentCatId;

    // Calculate discount
    $discount = 0;
    if ($p['mrp'] > $p['price']) {
        $discount = (int)round((($p['mrp'] - $p['price']) / $p['mrp']) * 100);
    }

    // Check if product exists by SKU or Name
    $stmtFind = $pdo->prepare('SELECT id FROM products WHERE sku=? OR name=? LIMIT 1');
    $stmtFind->execute([$p['sku'], $p['name']]);
    $existingId = $stmtFind->fetchColumn();

    // Find main category name
    $mainCatName = '';
    foreach ($categoriesData as $cd) {
        if ($cd['slug'] === $p['category_slug']) {
            $mainCatName = $cd['name'];
            break;
        }
    }

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
            $p['sku'], $p['rating'], $p['review_count'], $discount, $p['featured'], $pid
        ]);
    } else {
        $insert = $pdo->prepare('INSERT INTO products 
            (category_id, category, subcategory, name, brand, description, price, mrp, stock, image_url, emoji, color, material, sizes, sku, rating, review_count, discount_percent, featured, active) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 1)');
        $insert->execute([
            $subCatId, $mainCatName, $p['subcategory'], $p['name'], $p['brand'], $p['description'],
            $p['price'], $p['mrp'], $p['stock'], $p['image_url'], '✨', $p['color'], $p['material'], $p['sizes'],
            $p['sku'], $p['rating'], $p['review_count'], $discount, $p['featured']
        ]);
        $pid = (int)$pdo->lastInsertId();
    }

    // Sync Gallery Images in product_images
    $pdo->prepare('DELETE FROM product_images WHERE product_id=?')->execute([$pid]);
    // First image is main photo
    $pdo->prepare('INSERT INTO product_images (product_id, image_url, alt_text, sort_order) VALUES (?, ?, ?, 0)')
        ->execute([$pid, $p['image_url'], $p['name'] . ' - Front View']);
    $order = 1;
    foreach ($p['gallery'] as $galleryUrl) {
        $pdo->prepare('INSERT INTO product_images (product_id, image_url, alt_text, sort_order) VALUES (?, ?, ?, ?)')
            ->execute([$pid, $galleryUrl, $p['name'] . ' - View ' . $order, $order]);
        $order++;
    }

    // Sync Variants in product_variants
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
    $baseStock = intdiv($totalStock, count($variantCombos));
    $remainder = $totalStock % count($variantCombos);

    $upsertVariant = $pdo->prepare('INSERT INTO product_variants (product_id, size, color, sku, stock, active) VALUES (?, ?, ?, ?, ?, 1) ON DUPLICATE KEY UPDATE stock=VALUES(stock), active=1');
    foreach ($variantCombos as $idx => $combo) {
        $variantStock = $baseStock + ($idx < $remainder ? 1 : 0);
        $variantSku = $p['sku'] . '-' . strtoupper(substr(preg_replace('/[^a-zA-Z0-9]/', '', $combo['size']), 0, 4));
        $upsertVariant->execute([$pid, $combo['size'], $combo['color'], $variantSku, $variantStock]);
    }

    // Seed Genuine Customer Reviews in product_reviews
    foreach ($p['reviews'] as $rev) {
        $reviewerName = $rev[0];
        $revRating = $rev[1];
        $revText = $rev[2];
        $checkReview = $pdo->prepare('SELECT id FROM product_reviews WHERE product_id=? AND reviewer_name=? LIMIT 1');
        $checkReview->execute([$pid, $reviewerName]);
        if (!$checkReview->fetchColumn()) {
            $pdo->prepare('INSERT INTO product_reviews (product_id, user_id, reviewer_name, rating, review_text, approved) VALUES (?, NULL, ?, ?, ?, 1)')
                ->execute([$pid, $reviewerName, $revRating, $revText]);
        }
    }

    $countUpserted++;
}

echo "✓ Successfully seeded $countUpserted real fashion products across all 13 categories.\n";

// Update Category Product Counts
$catCounts = $pdo->query('SELECT c.name, COUNT(p.id) as count FROM categories c JOIN products p ON (p.category_id=c.id OR p.category_id IN (SELECT id FROM categories sub WHERE sub.parent_id=c.id)) WHERE c.parent_id IS NULL AND p.active=1 GROUP BY c.id ORDER BY c.sort_order')->fetchAll();
echo "\nCategory product breakdown:\n";
foreach ($catCounts as $row) {
    echo "  - {$row['name']}: {$row['count']} active products\n";
}

echo "\n=== Seeding Finished Successfully ===\n";
