<?php
require __DIR__ . '/config.php';

$search = trim($_GET['q'] ?? '');
$categorySlug = trim($_GET['category'] ?? '');
$subcategorySlug = trim($_GET['subcategory'] ?? '');
$size = trim($_GET['size'] ?? '');
$color = trim($_GET['color'] ?? '');
$minPrice = filter_var($_GET['min_price'] ?? '', FILTER_VALIDATE_FLOAT);
$maxPrice = filter_var($_GET['max_price'] ?? '', FILTER_VALIDATE_FLOAT);
$sort = $_GET['sort'] ?? 'featured';

$categoryAliases = [
    'goggles' => 'sunglasses',
    'goggles-sunglasses' => 'sunglasses',
    'bags' => 'bags-and-purses',
    'bags-and-handbags' => 'bags-and-purses',
    'handbags' => 'bags-and-purses',
    'purses' => 'bags-and-purses',
    'shoes-and-sneakers' => 'shoes',
    'sneakers' => 'shoes',
    'accessories' => 'accessories',
    'jewellery-and-accessories' => 'accessories',
    'jewelry' => 'accessories',
    'tshirts' => 't-shirts',
    'pants' => 'trousers',
    'jackets-and-hoodies' => 'jackets',
];
if (isset($categoryAliases[$categorySlug])) {
    $categorySlug = $categoryAliases[$categorySlug];
}

$categoryStmt = $pdo->query("SELECT c.id,c.name,c.slug,c.parent_id,COUNT(DISTINCT p.id) AS product_count FROM categories c LEFT JOIN categories leaf ON (leaf.id=c.id OR leaf.parent_id=c.id) LEFT JOIN products p ON p.category_id=leaf.id AND p.active=1 WHERE c.active=1 GROUP BY c.id ORDER BY c.parent_id,c.sort_order,c.name");
$categories = $categoryStmt->fetchAll();

$availableSizes = $pdo->query('SELECT DISTINCT v.size FROM product_variants v JOIN products p ON p.id=v.product_id WHERE v.active=1 AND v.stock>0 AND p.active=1 ORDER BY v.size')->fetchAll(PDO::FETCH_COLUMN);
$availableColors = $pdo->query('SELECT DISTINCT v.color FROM product_variants v JOIN products p ON p.id=v.product_id WHERE v.active=1 AND v.stock>0 AND p.active=1 ORDER BY v.color')->fetchAll(PDO::FETCH_COLUMN);

$selectedCategory = null;
if ($categorySlug !== '') {
    $stmt = $pdo->prepare('SELECT id,name,slug,parent_id FROM categories WHERE slug=? AND active=1 LIMIT 1');
    $stmt->execute([$categorySlug]);
    $selectedCategory = $stmt->fetch() ?: null;
}

$selectedSubcategory = null;
if ($subcategorySlug !== '') {
    $stmt = $pdo->prepare('SELECT id,name,slug,parent_id FROM categories WHERE slug=? AND active=1 LIMIT 1');
    $stmt->execute([$subcategorySlug]);
    $selectedSubcategory = $stmt->fetch() ?: null;
    if ($selectedSubcategory && $selectedCategory && (int)$selectedSubcategory['parent_id'] !== (int)$selectedCategory['id']) {
        $selectedSubcategory = null;
    }
}

$subcategories = [];
if ($selectedCategory && empty($selectedCategory['parent_id'])) {
    $stmt = $pdo->prepare("SELECT c.id,c.name,c.slug,COUNT(DISTINCT p.id) AS product_count FROM categories c LEFT JOIN products p ON p.category_id=c.id AND p.active=1 WHERE c.parent_id=? AND c.active=1 GROUP BY c.id ORDER BY c.sort_order,c.name");
    $stmt->execute([$selectedCategory['id']]);
    $subcategories = $stmt->fetchAll();
}

$where = ['p.active=1'];
$params = [];
if ($search !== '') {
    $where[] = '(p.name LIKE ? OR p.description LIKE ? OR p.brand LIKE ?)';
    $term = '%' . $search . '%';
    array_push($params, $term, $term, $term);
}
if ($selectedSubcategory) {
    $where[] = 'p.category_id=?';
    $params[] = $selectedSubcategory['id'];
} elseif ($selectedCategory) {
    if (empty($selectedCategory['parent_id'])) {
        $where[] = '(p.category_id=? OR p.category_id IN (SELECT id FROM categories WHERE parent_id=? AND active=1))';
        array_push($params, $selectedCategory['id'], $selectedCategory['id']);
    } else {
        $where[] = 'p.category_id=?';
        $params[] = $selectedCategory['id'];
    }
}

if ($minPrice !== false && $minPrice !== null && $minPrice !== '') {
    $where[] = 'p.price>=?';
    $params[] = max(0, $minPrice);
}
if ($maxPrice !== false && $maxPrice !== null && $maxPrice !== '') {
    $where[] = 'p.price<=?';
    $params[] = max(0, $maxPrice);
}
if ($size !== '') {
    $where[] = 'EXISTS (SELECT 1 FROM product_variants vf WHERE vf.product_id=p.id AND vf.size=? AND vf.active=1 AND vf.stock>0)';
    $params[] = $size;
}
if ($color !== '') {
    $where[] = 'EXISTS (SELECT 1 FROM product_variants vf WHERE vf.product_id=p.id AND vf.color=? AND vf.active=1 AND vf.stock>0)';
    $params[] = $color;
}
if (($_GET['availability'] ?? '') === 'in_stock') {
    $where[] = 'EXISTS (SELECT 1 FROM product_variants vf WHERE vf.product_id=p.id AND vf.active=1 AND vf.stock>0)';
}

$sortOptions = [
    'featured' => 'p.featured DESC,p.created_at DESC,p.id DESC',
    'newest' => 'p.created_at DESC,p.id DESC',
    'price_asc' => 'p.price ASC,p.id DESC',
    'price_desc' => 'p.price DESC,p.id DESC',
    'popular' => 'COALESCE(sales.units,0) DESC,p.featured DESC,p.id DESC',
];
if (!isset($sortOptions[$sort])) $sort = 'featured';

$sql = 'SELECT p.*,COALESCE(v.total_stock,p.stock) AS available_stock,c.name AS category_name,c.slug AS category_slug,COALESCE(sales.units,0) AS sold_units,(SELECT vf.id FROM product_variants vf WHERE vf.product_id=p.id AND vf.active=1 AND vf.stock>0 ORDER BY vf.id LIMIT 1) AS default_variant_id FROM products p LEFT JOIN categories c ON c.id=p.category_id LEFT JOIN (SELECT product_id,SUM(stock) AS total_stock FROM product_variants WHERE active=1 GROUP BY product_id) v ON v.product_id=p.id LEFT JOIN (SELECT product_id,SUM(quantity) AS units FROM order_items WHERE product_id IS NOT NULL GROUP BY product_id) sales ON sales.product_id=p.id WHERE ' . implode(' AND ', $where) . ' ORDER BY ' . $sortOptions[$sort];
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$products = $stmt->fetchAll();

$flash = $_SESSION['flash'] ?? '';
unset($_SESSION['flash']);

$categoryPages = [
    't-shirts' => 'tshirts.php',
    'shirts' => 'shirts.php',
    'jeans' => 'jeans.php',
    'trousers' => 'trousers.php',
    'jackets' => 'jackets.php',
    'hoodies' => 'hoodies.php',
    'watches' => 'watches.php',
    'sunglasses' => 'goggles.php',
    'bags-and-purses' => 'bags-and-purses.php',
    'shoes' => 'shoes.php',
    'accessories' => 'accessories.php',
    'womens-clothing' => 'womens-clothing.php',
    'mens-clothing' => 'mens-clothing.php',
    'dresses-and-kurtis' => 'dresses-and-kurtis.php',
    'sandals' => 'sandals.php',
    // Aliases
    'goggles' => 'goggles.php',
    'bags' => 'bags-and-purses.php',
    'bags-and-handbags' => 'bags-and-purses.php',
    'shoes-and-sneakers' => 'shoes.php',
    'jewellery-and-accessories' => 'accessories.php',
    'jackets-and-hoodies' => 'jackets.php',
];
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title><?= $selectedCategory ? e($selectedCategory['name']) . ' | ' : '' ?>Shop | FD-Trend</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
<?php require __DIR__ . '/partials/site_header.php'; ?>
<main class="wrap section">
  <section class="shop-heading">
    <p class="eyebrow">FD-TREND / <?= $selectedCategory ? strtoupper(e($selectedCategory['name'])) : 'CATALOG' ?></p>
    <h1><?= $selectedCategory ? e($selectedCategory['name']) : 'Find your next favourite fit.' ?></h1>
    <p class="muted">
      <?php if ($selectedCategory): ?>
        Showing authentic <?= e($selectedCategory['name']) ?> with accurate stock, original images, sizes and colours.
      <?php else: ?>
        Everyday essentials, streetwear, denim, watches and accessories. Real-time inventory and verified categories.
      <?php endif; ?>
    </p>
  </section>

  <?php if ($flash): ?><div class="notice"><?= e($flash) ?></div><?php endif; ?>

  <?php if ($categories): ?>
    <nav class="category-chips" aria-label="Product categories">
      <a class="chip <?= empty($selectedCategory) ? 'active' : '' ?>" href="shop.php">All products</a>
      <?php foreach ($categories as $cat): ?>
        <?php if ($cat['parent_id'] !== null) continue; ?>
        <?php $catHref = $categoryPages[$cat['slug']] ?? ('category.php?slug=' . rawurlencode($cat['slug'])); ?>
        <a class="chip <?= $selectedCategory && (int)$selectedCategory['id'] === (int)$cat['id'] ? 'active' : '' ?>" href="<?= e($catHref) ?>">
          <?= e($cat['name']) ?> <span><?= (int)$cat['product_count'] ?></span>
        </a>
      <?php endforeach; ?>
    </nav>
  <?php endif; ?>

  <?php if ($subcategories): ?>
    <div class="subcategory-list">
      <a class="chip <?= empty($selectedSubcategory) ? 'active' : '' ?>" href="shop.php?category=<?= rawurlencode($selectedCategory['slug']) ?>">
        All <?= e($selectedCategory['name']) ?> (<?= (int)$selectedCategory['product_count'] ?>)
      </a>
      <?php foreach ($subcategories as $sub): ?>
        <a class="chip <?= $selectedSubcategory && (int)$selectedSubcategory['id'] === (int)$sub['id'] ? 'active' : '' ?>" href="shop.php?category=<?= rawurlencode($selectedCategory['slug']) ?>&amp;subcategory=<?= rawurlencode($sub['slug']) ?>">
          <?= e($sub['name']) ?> (<?= (int)$sub['product_count'] ?>)
        </a>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>

  <form class="filters shop-filters" method="get" action="shop.php">
    <input type="search" name="q" value="<?= e($search) ?>" placeholder="Search by name, brand, or style">
    <select name="category">
      <option value="">All categories (13 categories)</option>
      <?php foreach ($categories as $cat): ?>
        <?php if ($cat['parent_id'] !== null) continue; ?>
        <option value="<?= e($cat['slug']) ?>" <?= $selectedCategory && (int)$selectedCategory['id'] === (int)$cat['id'] ? 'selected' : '' ?>>
          <?= e($cat['name']) ?> (<?= (int)$cat['product_count'] ?>)
        </option>
      <?php endforeach; ?>
    </select>
    <?php if ($selectedCategory && $subcategories): ?>
      <select name="subcategory">
        <option value="">All subcategories</option>
        <?php foreach ($subcategories as $sub): ?>
          <option value="<?= e($sub['slug']) ?>" <?= $selectedSubcategory && (int)$selectedSubcategory['id'] === (int)$sub['id'] ? 'selected' : '' ?>>
            <?= e($sub['name']) ?> (<?= (int)$sub['product_count'] ?>)
          </option>
        <?php endforeach; ?>
      </select>
    <?php endif; ?>
    <input type="number" name="min_price" min="0" step="1" placeholder="Min ₹" value="<?= e((string)($_GET['min_price'] ?? '')) ?>">
    <input type="number" name="max_price" min="0" step="1" placeholder="Max ₹" value="<?= e((string)($_GET['max_price'] ?? '')) ?>">
    <select name="size">
      <option value="">Any size</option>
      <?php foreach ($availableSizes as $option): ?>
        <option <?= $size === $option ? 'selected' : '' ?>><?= e($option) ?></option>
      <?php endforeach; ?>
    </select>
    <select name="color">
      <option value="">Any colour</option>
      <?php foreach ($availableColors as $option): ?>
        <option <?= $color === $option ? 'selected' : '' ?>><?= e($option) ?></option>
      <?php endforeach; ?>
    </select>
    <select name="availability">
      <option value="">All stock</option>
      <option value="in_stock" <?= ($_GET['availability'] ?? '') === 'in_stock' ? 'selected' : '' ?>>In stock only</option>
    </select>
    <select name="sort">
      <option value="featured">Sort: Featured</option>
      <option value="newest" <?= $sort === 'newest' ? 'selected' : '' ?>>Sort: Newest</option>
      <option value="price_asc" <?= $sort === 'price_asc' ? 'selected' : '' ?>>Price: Low to High</option>
      <option value="price_desc" <?= $sort === 'price_desc' ? 'selected' : '' ?>>Price: High to Low</option>
      <option value="popular" <?= $sort === 'popular' ? 'selected' : '' ?>>Sort: Popular</option>
    </select>
    <button class="btn">Apply Filters</button>
    <a class="btn secondary" href="shop.php">Reset</a>
  </form>

  <div class="shop-results-header">
    <h2>Products <span class="muted">(<?= count($products) ?> available)</span></h2>
    <?php if ($selectedCategory): ?>
      <p class="muted">Browsing category: <strong><?= e($selectedCategory['name']) ?></strong><?php if ($selectedSubcategory): ?> &rsaquo; <strong><?= e($selectedSubcategory['name']) ?></strong><?php endif; ?></p>
    <?php endif; ?>
  </div>

  <?php if ($products): ?>
    <div class="grid">
      <?php foreach ($products as $p): ?>
        <?php
          $discountPct = (int)($p['discount_percent'] ?? 0);
          if ($discountPct === 0 && (float)$p['mrp'] > (float)$p['price']) {
              $discountPct = (int)round((((float)$p['mrp'] - (float)$p['price']) / (float)$p['mrp']) * 100);
          }
          $stockCount = (int)$p['available_stock'];
          $sizesList = array_values(array_unique(array_filter(array_map('trim', explode(',', $p['sizes'] ?? ''))))) ?: ['Standard'];
          $colorsList = array_values(array_unique(array_filter(array_map('trim', explode(',', $p['color'] ?? ''))))) ?: ['Default'];
        ?>
        <article class="card product-card">
          <a href="product.php?id=<?= (int)$p['id'] ?>" class="product-link">
            <?php if (!empty($p['image_url'])): ?>
              <div class="img-wrap">
                <img class="product-image" src="<?= e($p['image_url']) ?>" alt="<?= e($p['name']) ?>" loading="lazy">
                <?php if ($discountPct > 0): ?>
                  <span class="discount-badge"><?= $discountPct ?>% OFF</span>
                <?php endif; ?>
              </div>
            <?php else: ?>
              <div class="emoji-image"><?= e($p['emoji'] ?: '👕') ?></div>
            <?php endif; ?>
          </a>
          <div class="cardbody">
            <div class="card-meta">
              <span class="brand-tag"><?= e($p['brand']) ?></span>
              <span class="tag"><?= e($p['category_name'] ?: $p['category']) ?></span>
            </div>
            <h3><a href="product.php?id=<?= (int)$p['id'] ?>"><?= e($p['name']) ?></a></h3>
            <?php if (!empty($p['sku'])): ?>
              <span class="sku-label">SKU: <?= e($p['sku']) ?></span>
            <?php endif; ?>
            <div class="price-row">
              <span class="price"><?= money($p['price']) ?></span>
              <?php if ($p['mrp'] > $p['price']): ?>
                <span class="oldprice"><?= money($p['mrp']) ?></span>
                <span class="save-tag">Save <?= money((float)$p['mrp'] - (float)$p['price']) ?></span>
              <?php endif; ?>
            </div>
            <div class="rating-row">
              <?php $ratingVal = (float)($p['rating'] ?? 4.5); $revCount = (int)($p['review_count'] ?? 0); ?>
              <span class="stars">★ <?= number_format($ratingVal, 1) ?></span>
              <span class="rev-count">(<?= $revCount ?> reviews)</span>
            </div>
            <p class="muted desc-preview"><?= e(text_preview($p['description'], 85)) ?></p>

            <?php if ($stockCount > 5): ?>
              <p class="stock-status in-stock">● In stock (<?= $stockCount ?> units)</p>
            <?php elseif ($stockCount > 0): ?>
              <p class="stock-status low-stock">⚠ Low stock: Only <?= $stockCount ?> left!</p>
            <?php else: ?>
              <p class="stock-status out-of-stock">✕ Out of stock</p>
            <?php endif; ?>

            <form method="post" action="cart.php" class="card-action-form">
              <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
              <input type="hidden" name="product_id" value="<?= (int)$p['id'] ?>">
              <input type="hidden" name="quantity" value="1">
              
              <div class="card-variant-options">
                <div class="card-opt-field">
                  <label for="size-<?= (int)$p['id'] ?>">Select Size:</label>
                  <select id="size-<?= (int)$p['id'] ?>" name="size" class="card-select">
                    <?php foreach ($sizesList as $s): ?>
                      <option value="<?= e($s) ?>"><?= e($s) ?></option>
                    <?php endforeach; ?>
                  </select>
                </div>
                <div class="card-opt-field">
                  <label for="color-<?= (int)$p['id'] ?>">Select Colour:</label>
                  <select id="color-<?= (int)$p['id'] ?>" name="color" class="card-select">
                    <?php foreach ($colorsList as $c): ?>
                      <option value="<?= e($c) ?>"><?= e($c) ?></option>
                    <?php endforeach; ?>
                  </select>
                </div>
              </div>

              <div class="cardactions">
                <a class="btn secondary" href="product.php?id=<?= (int)$p['id'] ?>">View Product</a>
                <?php if ($stockCount > 0): ?>
                  <button class="btn secondary" type="submit" name="action" value="add" title="Add item to your cart">Add to Cart</button>
                  <button class="btn" type="submit" name="action" value="buy_now" title="Buy immediately and proceed to checkout">Buy Now</button>
                <?php else: ?>
                  <button class="btn secondary" type="button" disabled title="Currently out of stock">Out of Stock</button>
                <?php endif; ?>
              </div>
            </form>
          </div>
        </article>
      <?php endforeach; ?>
    </div>
  <?php else: ?>
    <div class="panel empty-state">
      <h3>No matching products</h3>
      <p>We couldn't find any products matching your current filters.</p>
      <a class="btn" href="shop.php">Clear filters and view all products</a>
    </div>
  <?php endif; ?>
</main>
<script src="assets/store.js" defer></script>
</body>
</html>
