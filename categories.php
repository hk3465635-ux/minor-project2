<?php
require __DIR__ . '/config.php';
$categories = $pdo->query("SELECT c.id,c.name,c.slug,c.parent_id,c.description,c.image_url,COUNT(DISTINCT p.id) AS product_count FROM categories c LEFT JOIN categories leaf ON (leaf.id=c.id OR leaf.parent_id=c.id) LEFT JOIN products p ON p.category_id=leaf.id AND p.active=1 WHERE c.active=1 GROUP BY c.id ORDER BY c.parent_id,c.sort_order,c.name")->fetchAll();
$roots = [];
$categoryPages = [
    'womens-clothing' => 'womens-clothing.php',
    'mens-clothing' => 'mens-clothing.php',
    't-shirts' => 'tshirts.php',
    'jeans' => 'jeans.php',
    'shirts' => 'shirts.php',
    'jackets-and-hoodies' => 'jackets-and-hoodies.php',
    'dresses-and-kurtis' => 'dresses-and-kurtis.php',
    'shoes-and-sneakers' => 'shoes-and-sneakers.php',
    'sandals' => 'sandals.php',
    'bags-and-handbags' => 'bags-and-handbags.php',
    'jewellery-and-accessories' => 'jewellery-and-accessories.php',
    'watches' => 'watches.php',
    'sunglasses' => 'sunglasses.php',
    // Aliases
    'hoodies' => 'jackets-and-hoodies.php',
    'jackets' => 'jackets-and-hoodies.php',
    'shoes' => 'shoes-and-sneakers.php',
    'accessories' => 'jewellery-and-accessories.php'
];

foreach ($categories as $category) {
    if ($category['parent_id'] === null) {
        $roots[(int)$category['id']] = $category + ['children' => []];
    }
}
foreach ($categories as $category) {
    if ($category['parent_id'] !== null && isset($roots[(int)$category['parent_id']])) {
        $roots[(int)$category['parent_id']]['children'][] = $category;
    }
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>All Fashion Categories | FD-Trend</title>
  <meta name="description" content="Explore our 13 fashion categories: Women's, Men's, T-Shirts, Jeans, Shirts, Jackets, Dresses, Shoes, Sandals, Bags, Jewellery, Watches, and Sunglasses.">
  <link rel="stylesheet" href="style.css">
</head>
<body>
<?php require __DIR__ . '/partials/site_header.php'; ?>
<main class="wrap section">
  <div class="shop-heading">
    <p class="eyebrow">FD-TREND / DISCOVER</p>
    <h1>Shop by Category</h1>
    <p class="muted">Explore 13 distinct categories featuring genuine brand photography, real inventory, and verified product specifications.</p>
  </div>

  <div class="category-cards-grid">
    <?php foreach ($roots as $category): 
      $catUrl = $categoryPages[$category['slug']] ?? ('category.php?slug=' . rawurlencode($category['slug']));
      $pCount = (int)$category['product_count'];
    ?>
      <article class="category-card-item">
        <a href="<?= e($catUrl) ?>" class="cat-card-media" title="Browse <?= e($category['name']) ?>">
          <?php if (!empty($category['image_url'])): ?>
            <img src="<?= e($category['image_url']) ?>" alt="<?= e($category['name']) ?>" loading="lazy">
          <?php else: ?>
            <div class="cat-card-placeholder">FD / <?= e(strtoupper($category['name'])) ?></div>
          <?php endif; ?>
          <span class="cat-badge-overlay"><?= $pCount ?> <?= $pCount === 1 ? 'Product' : 'Products' ?></span>
        </a>
        <div class="cat-card-content">
          <div class="cat-card-title-row">
            <h2><a href="<?= e($catUrl) ?>"><?= e($category['name']) ?></a></h2>
          </div>
          <?php if (!empty($category['description'])): ?>
            <p class="cat-card-desc"><?= e($category['description']) ?></p>
          <?php endif; ?>
          <?php if (!empty($category['children'])): ?>
            <div class="cat-subchips">
              <?php foreach ($category['children'] as $child): ?>
                <a class="chip small" href="shop.php?category=<?= rawurlencode($category['slug']) ?>&amp;subcategory=<?= rawurlencode($child['slug']) ?>">
                  <?= e($child['name']) ?>
                </a>
              <?php endforeach; ?>
            </div>
          <?php endif; ?>
          <div class="cat-card-actions">
            <a class="btn secondary small" href="<?= e($catUrl) ?>">Explore <?= e($category['name']) ?> &rarr;</a>
          </div>
        </div>
      </article>
    <?php endforeach; ?>
  </div>
</main>
<script src="assets/store.js" defer></script>
</body>
</html>
