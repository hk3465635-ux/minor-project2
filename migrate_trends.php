<?php
/**
 * FD-Trend: Trends & FD Shows Database Migration
 * Run from CLI: php migrate_trends.php
 */
require __DIR__ . '/config.php';

if (PHP_SAPI !== 'cli') {
    http_response_code(404);
    exit('This migration script can only be run from the command line.');
}

echo "FD-Trend Trends & FD Shows Migration\n";
echo str_repeat('=', 50) . "\n\n";

// ─── Trend Categories ──────────────────────────────────────────────────────────
$pdo->exec("CREATE TABLE IF NOT EXISTS trend_categories (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    slug VARCHAR(140) NOT NULL UNIQUE,
    description VARCHAR(500) NULL,
    icon VARCHAR(10) NOT NULL DEFAULT '✨',
    sort_order INT NOT NULL DEFAULT 100,
    active TINYINT(1) NOT NULL DEFAULT 1,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
echo "✓ trend_categories table created/verified\n";

// ─── Trends ───────────────────────────────────────────────────────────────────
$pdo->exec("CREATE TABLE IF NOT EXISTS trends (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    trend_category_id INT UNSIGNED NULL,
    name VARCHAR(180) NOT NULL,
    slug VARCHAR(200) NOT NULL UNIQUE,
    description TEXT NULL,
    image_url VARCHAR(1000) NULL,
    source VARCHAR(150) NOT NULL DEFAULT 'Admin Curated',
    source_url VARCHAR(1000) NULL,
    popularity_score DECIMAL(5,2) NOT NULL DEFAULT 50.00 COMMENT '0-100 score',
    status ENUM('Rising','Stable','Declining') NOT NULL DEFAULT 'Stable',
    hashtag VARCHAR(100) NULL,
    region VARCHAR(80) NOT NULL DEFAULT 'Global',
    featured TINYINT(1) NOT NULL DEFAULT 0,
    active TINYINT(1) NOT NULL DEFAULT 1,
    last_updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_trends_category FOREIGN KEY (trend_category_id) REFERENCES trend_categories(id) ON DELETE SET NULL,
    INDEX idx_trends_category_active (trend_category_id, active),
    INDEX idx_trends_status (status),
    INDEX idx_trends_score (popularity_score DESC)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
echo "✓ trends table created/verified\n";

// ─── Trend History (for forecasting) ─────────────────────────────────────────
$pdo->exec("CREATE TABLE IF NOT EXISTS trend_history (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    trend_id INT UNSIGNED NOT NULL,
    score DECIMAL(5,2) NOT NULL,
    status ENUM('Rising','Stable','Declining') NOT NULL DEFAULT 'Stable',
    recorded_at DATE NOT NULL,
    notes VARCHAR(300) NULL,
    CONSTRAINT fk_trend_history_trend FOREIGN KEY (trend_id) REFERENCES trends(id) ON DELETE CASCADE,
    UNIQUE KEY uq_trend_history_day (trend_id, recorded_at),
    INDEX idx_trend_history_date (recorded_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
echo "✓ trend_history table created/verified\n";

// ─── Trend Analytics Cache ─────────────────────────────────────────────────────
$pdo->exec("CREATE TABLE IF NOT EXISTS trend_analytics (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    metric_key VARCHAR(100) NOT NULL UNIQUE,
    metric_value VARCHAR(500) NOT NULL DEFAULT '',
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
echo "✓ trend_analytics table created/verified\n";

// ─── FD Shows ─────────────────────────────────────────────────────────────────
$pdo->exec("CREATE TABLE IF NOT EXISTS fd_shows (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(180) NOT NULL,
    slug VARCHAR(200) NOT NULL UNIQUE,
    description TEXT NULL,
    season VARCHAR(80) NULL,
    episode_number INT UNSIGNED NULL,
    air_date DATE NULL,
    location VARCHAR(150) NULL,
    featured_designer VARCHAR(150) NULL,
    image_url VARCHAR(1000) NULL,
    video_url VARCHAR(1000) NULL,
    category VARCHAR(80) NOT NULL DEFAULT 'Fashion Show',
    featured TINYINT(1) NOT NULL DEFAULT 0,
    published TINYINT(1) NOT NULL DEFAULT 1,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_fd_shows_published (published, featured),
    INDEX idx_fd_shows_air_date (air_date)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
echo "✓ fd_shows table created/verified\n";

// ─── FD Show Outfits / Looks ──────────────────────────────────────────────────
$pdo->exec("CREATE TABLE IF NOT EXISTS fd_show_outfits (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    show_id INT UNSIGNED NOT NULL,
    look_title VARCHAR(180) NOT NULL,
    description TEXT NULL,
    image_url VARCHAR(1000) NULL,
    related_product_id INT UNSIGNED NULL,
    sort_order INT NOT NULL DEFAULT 0,
    CONSTRAINT fk_outfit_show FOREIGN KEY (show_id) REFERENCES fd_shows(id) ON DELETE CASCADE,
    INDEX idx_outfit_show (show_id, sort_order)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
echo "✓ fd_show_outfits table created/verified\n";

// ─── Saved Trends (user favorites) ────────────────────────────────────────────
$pdo->exec("CREATE TABLE IF NOT EXISTS saved_trends (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT UNSIGNED NOT NULL,
    trend_id INT UNSIGNED NOT NULL,
    saved_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_saved_trends_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    CONSTRAINT fk_saved_trends_trend FOREIGN KEY (trend_id) REFERENCES trends(id) ON DELETE CASCADE,
    UNIQUE KEY uq_saved_trend_user (user_id, trend_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
echo "✓ saved_trends table created/verified\n";

// ─── Seed Trend Categories ─────────────────────────────────────────────────────
$defaultCategories = [
    ['Punjabi Fashion',      'punjabi-fashion',     'Traditional and modern Punjabi outfits, ethnic wear, and regional styles.', '🎽', 1],
    ['FD Shows',             'fd-shows',            'Fashion from FD show episodes, runway looks, and editorial picks.',         '🎬', 2],
    ['Celebrity Fashion',    'celebrity-fashion',   'Outfits and looks worn by celebrities and influencers.',                   '⭐', 3],
    ["Men's Fashion",        'mens-fashion',        'Trending outfits, styles, and accessories for men.',                       '👔', 4],
    ["Women's Fashion",      'womens-fashion',      'Trending outfits, dresses, and styles for women.',                         '👗', 5],
    ['Streetwear',           'streetwear',          'Streetwear, urban fashion, and casual cool styles.',                       '🧢', 6],
    ['T-Shirts & Jeans',     'tshirts-and-jeans',   'Everyday casual combos featuring tees, denim, and basics.',               '👖', 7],
    ['Shoes & Sneakers',     'shoes-and-sneakers',  'Trending footwear, sneakers, sports shoes, and casual kicks.',             '👟', 8],
    ['Watches & Goggles',    'watches-and-goggles', 'Trending timepieces, sunglasses, and eyewear accessories.',               '⌚', 9],
    ['Handbags & Accessories','handbags-accessories','Bags, clutches, belts, jewellery, and fashion accessories.',              '👜', 10],
    ['Viral Trends',         'viral-trends',        'Viral fashion moments, social media trends, and internet styles.',         '🔥', 11],
    ['Upcoming Trends',      'upcoming-trends',     'Predicted and forecasted future fashion trends.',                          '🚀', 12],
];

$checkCat = $pdo->prepare('SELECT id FROM trend_categories WHERE slug=? LIMIT 1');
$insertCat = $pdo->prepare('INSERT INTO trend_categories (name, slug, description, icon, sort_order, active) VALUES (?,?,?,?,?,1)');
foreach ($defaultCategories as [$name, $slug, $desc, $icon, $sort]) {
    $checkCat->execute([$slug]);
    if (!$checkCat->fetchColumn()) {
        $insertCat->execute([$name, $slug, $desc, $icon, $sort]);
        echo "  + Inserted trend category: {$name}\n";
    }
}
echo "✓ Trend categories seeded\n\n";

// ─── Seed Sample Trends ────────────────────────────────────────────────────────
$sampleTrends = [
    // Punjabi Fashion
    ['Phulkari Embroidery Revival', 'phulkari-embroidery-revival', 'punjabi-fashion', 'Traditional Punjabi Phulkari embroidery making a massive comeback in modern silhouettes and fusion wear.', 87.5, 'Rising', '#Phulkari', 'Punjab Region'],
    ['Patiala Salwar Modern Cuts', 'patiala-salwar-modern-cuts', 'punjabi-fashion', 'Classic Patiala salwar reimagined with contemporary cuts and vibrant colors for daily and festive wear.', 74.0, 'Stable', '#PatialaSalwar', 'North India'],
    ['Punjabi Groom Sherwani', 'punjabi-groom-sherwani', 'punjabi-fashion', 'Rich embroidered sherwanis with turban combos dominating wedding season collections.', 91.2, 'Rising', '#SherwaniTrend', 'Punjab'],

    // Celebrity Fashion
    ['Bollywood Ethnic Fusion', 'bollywood-ethnic-fusion', 'celebrity-fashion', 'Bollywood celebrities blending traditional Indian wear with Western cuts for red carpet events.', 82.3, 'Rising', '#BollywoodFashion', 'India'],
    ['Celebrity Airport Looks', 'celebrity-airport-looks', 'celebrity-fashion', 'Comfortable yet stylish airport fashion by A-listers setting trends for casual travellers.', 68.5, 'Stable', '#AirportFashion', 'Global'],

    // Men's Fashion
    ['Oversized Co-ord Sets', 'oversized-coord-sets-men', 'mens-fashion', 'Men matching oversized tops and bottoms in complementary tones — the ultimate streetwear flex.', 88.0, 'Rising', '#CoordSets', 'Global'],
    ['Relaxed Linen Shirts', 'relaxed-linen-shirts', 'mens-fashion', 'Breathable linen shirts in earthy tones taking over summer and resort fashion for men.', 72.5, 'Stable', '#LinenShirt', 'Global'],
    ['Cargo Trousers Comeback', 'cargo-trousers-comeback', 'mens-fashion', 'Utility cargo pants with multiple pockets making a strong return in both streetwear and casual fashion.', 85.5, 'Rising', '#CargoTrousers', 'Global'],

    // Women's Fashion
    ['Maxi Dress Summer', 'maxi-dress-summer', 'womens-fashion', 'Flowy maxi dresses in floral and pastel prints dominating summer collections worldwide.', 79.5, 'Rising', '#MaxiDress', 'Global'],
    ['Crop Top Ethnic Fusion', 'crop-top-ethnic-fusion', 'womens-fashion', 'Crop tops paired with lehengas or palazzos — the modern fusion look taking India by storm.', 83.0, 'Rising', '#CropTopFusion', 'India'],

    // Streetwear
    ['Graphic Hoodies', 'graphic-hoodies-streetwear', 'streetwear', 'Bold graphic prints and oversized hoodies dominating urban streetwear scenes across cities.', 86.0, 'Rising', '#GraphicHoodie', 'Global'],
    ['Vintage Denim Jacket', 'vintage-denim-jacket', 'streetwear', 'Distressed and vintage-washed denim jackets back in heavy rotation for streetwear enthusiasts.', 70.0, 'Stable', '#VintageDenim', 'Global'],

    // Shoes & Sneakers
    ['Chunky Sneakers', 'chunky-sneakers', 'shoes-and-sneakers', 'Dad shoes and chunky-soled sneakers continue their reign as the must-have footwear piece.', 89.5, 'Rising', '#ChunkySneakers', 'Global'],
    ['Classic White Sneakers', 'classic-white-sneakers', 'shoes-and-sneakers', 'Minimalist white sneakers remain a timeless wardrobe staple everyone is wearing.', 76.0, 'Stable', '#WhiteSneakers', 'Global'],

    // Viral Trends
    ['Dopamine Dressing', 'dopamine-dressing', 'viral-trends', 'Wearing bright, cheerful colors to boost mood — dopamine dressing is going viral globally.', 92.0, 'Rising', '#DopamineDressing', 'Global'],
    ['Quiet Luxury', 'quiet-luxury', 'viral-trends', 'Understated, logo-free luxury fashion embraced by minimalists and old-money aesthetics lovers.', 88.5, 'Rising', '#QuietLuxury', 'Global'],

    // Upcoming Trends
    ['AI-Designed Fabrics 2027', 'ai-designed-fabrics-2027', 'upcoming-trends', 'Machine-learning designed fabric patterns and AI-generated fashion collections forecasted to go mainstream.', 65.0, 'Rising', '#AIFashion', 'Global'],
    ['Sustainable Knitwear', 'sustainable-knitwear', 'upcoming-trends', 'Recycled yarn and eco-conscious knitwear predicted to dominate next season closets.', 71.0, 'Rising', '#SustainableFashion', 'Global'],

    // Watches & Goggles
    ['Oversized Sunglasses', 'oversized-sunglasses', 'watches-and-goggles', 'Giant frames and retro-inspired oversized shades are back as the accessories statement of the season.', 80.5, 'Rising', '#OverSizedSunnies', 'Global'],
    ['Smart Minimalist Watches', 'smart-minimalist-watches', 'watches-and-goggles', 'Clean-dial, minimalist smartwatches blending luxury aesthetics with smart technology.', 77.0, 'Stable', '#MinimalistWatch', 'Global'],

    // Handbags
    ['Mini Bag Trend', 'mini-bag-trend', 'handbags-accessories', 'Tiny micro and mini bags dominating the accessories section — the smaller the better.', 84.0, 'Rising', '#MiniBag', 'Global'],
    ['Woven Tote Bags', 'woven-tote-bags', 'handbags-accessories', 'Handcrafted woven and rattan tote bags for the boho and summer aesthetic lover.', 69.5, 'Stable', '#WovenBag', 'Global'],
];

$getCatId = $pdo->prepare('SELECT id FROM trend_categories WHERE slug=? LIMIT 1');
$checkTrend = $pdo->prepare('SELECT id FROM trends WHERE slug=? LIMIT 1');
$insertTrend = $pdo->prepare('INSERT INTO trends (trend_category_id, name, slug, description, popularity_score, status, hashtag, region, featured, active, source) VALUES (?,?,?,?,?,?,?,?,1,1,"Admin Curated")');

foreach ($sampleTrends as [$name, $slug, $catSlug, $desc, $score, $status, $hashtag, $region]) {
    $checkTrend->execute([$slug]);
    if ($checkTrend->fetchColumn()) continue;
    $getCatId->execute([$catSlug]);
    $catId = $getCatId->fetchColumn() ?: null;
    $insertTrend->execute([$catId, $name, $slug, $desc, $score, $status, $hashtag, $region]);
    echo "  + Inserted trend: {$name}\n";
}
echo "✓ Sample trends seeded\n\n";

// ─── Seed Historical Data for Forecasting ─────────────────────────────────────
$trends = $pdo->query('SELECT id FROM trends')->fetchAll(PDO::FETCH_COLUMN);
$insertHistory = $pdo->prepare('INSERT IGNORE INTO trend_history (trend_id, score, status, recorded_at) VALUES (?,?,?,?)');
$getScore = $pdo->prepare('SELECT popularity_score, status FROM trends WHERE id=?');

foreach ($trends as $trendId) {
    $getScore->execute([$trendId]);
    $row = $getScore->fetch();
    if (!$row) continue;
    $baseScore = (float)$row['popularity_score'];
    $baseStatus = $row['status'];
    // Seed 30 days of history
    for ($i = 29; $i >= 0; $i--) {
        $date = date('Y-m-d', strtotime("-{$i} days"));
        $variance = ($baseStatus === 'Rising') ? ($i * 0.6) : (($baseStatus === 'Declining') ? -($i * 0.5) : mt_rand(-3, 3));
        $score = max(0, min(100, round($baseScore - $variance + mt_rand(-5, 5), 2)));
        $status = $score >= 75 ? 'Rising' : ($score >= 45 ? 'Stable' : 'Declining');
        $insertHistory->execute([$trendId, $score, $status, $date]);
    }
}
echo "✓ 30-day trend history seeded for forecasting\n\n";

// ─── Seed Sample FD Shows ──────────────────────────────────────────────────────
$sampleShows = [
    ['FD Trend Punjab Fashion Week S1', 'fd-trend-punjab-fashion-week-s1', 'Inaugural Punjab Fashion Week showcasing traditional Phulkari, Punjabi ethnic wear, and fusion collections.', 'Season 1', 1, '2026-03-15', 'Chandigarh, Punjab', 'Manish Malhotra Guest Collection', 'Ethnic & Regional', 1],
    ['FD Street Style Awards 2026', 'fd-street-style-awards-2026', 'Annual award show celebrating the best streetwear looks, viral fashion moments, and social media style icons.', '2026', null, '2026-05-20', 'New Delhi', 'Multiple Featured Designers', 'Streetwear & Urban', 1],
    ['FD Bridal Collection Showcase', 'fd-bridal-collection-showcase', 'Exclusive showcase of the finest bridal collections including Lehenga Choli, Sherwani, and fusion bridal wear.', 'Special Edition', null, '2026-07-10', 'Mumbai', 'Sabyasachi Inspired Looks', 'Bridal Fashion', 0],
    ['FD Trend Report: Monsoon Edition', 'fd-trend-report-monsoon-edition', 'Seasonal trend report covering the most stylish and practical fashion choices for the monsoon season in India.', '2026', null, '2026-08-01', 'Online / Digital', 'FD Trend Editorial', 'Seasonal', 0],
    ['FD Celebrity Spotlight: Diwali Picks', 'fd-celebrity-spotlight-diwali', 'Special festive edition featuring celebrity-curated Diwali outfit picks, ethnic wear recommendations, and accessory guides.', '2026 Festive', null, '2026-10-20', 'Online / Digital', 'Various Celebrities', 'Festive & Celebrity', 1],
];

$checkShow = $pdo->prepare('SELECT id FROM fd_shows WHERE slug=? LIMIT 1');
$insertShow = $pdo->prepare('INSERT INTO fd_shows (title, slug, description, season, episode_number, air_date, location, featured_designer, category, featured, published) VALUES (?,?,?,?,?,?,?,?,?,?,1)');

foreach ($sampleShows as [$title, $slug, $desc, $season, $episode, $date, $location, $designer, $cat, $featured]) {
    $checkShow->execute([$slug]);
    if (!$checkShow->fetchColumn()) {
        $insertShow->execute([$title, $slug, $desc, $season, $episode, $date, $location, $designer, $cat, $featured]);
        echo "  + Inserted FD Show: {$title}\n";
    }
}
echo "✓ Sample FD Shows seeded\n\n";

// ─── Analytics Metrics ─────────────────────────────────────────────────────────
$metrics = [
    ['global_trend_index',     '84'],
    ['total_trends_analyzed',  (string)$pdo->query('SELECT COUNT(*) FROM trends')->fetchColumn()],
    ['rising_trends',          (string)$pdo->query("SELECT COUNT(*) FROM trends WHERE status='Rising'")->fetchColumn()],
    ['declining_trends',       (string)$pdo->query("SELECT COUNT(*) FROM trends WHERE status='Declining'")->fetchColumn()],
    ['stable_trends',          (string)$pdo->query("SELECT COUNT(*) FROM trends WHERE status='Stable'")->fetchColumn()],
    ['most_popular_category',  'Viral Trends'],
    ['data_last_updated',      date('Y-m-d H:i:s')],
];
$upsertMetric = $pdo->prepare('INSERT INTO trend_analytics (metric_key, metric_value) VALUES (?,?) ON DUPLICATE KEY UPDATE metric_value=VALUES(metric_value), updated_at=NOW()');
foreach ($metrics as [$key, $val]) {
    $upsertMetric->execute([$key, $val]);
}
echo "✓ Analytics metrics seeded\n\n";

echo str_repeat('=', 50) . "\n";
echo "Migration complete! All FD-Trend tables and sample data are ready.\n";
echo "You can now use trends.php, analytics.php, and fd-shows.php.\n";
