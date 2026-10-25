<?php
require __DIR__ . '/config.php';
$categoryRows = $pdo->query("SELECT c.id,c.name,c.slug,c.image_url,c.description,COUNT(DISTINCT p.id) AS product_count FROM categories c LEFT JOIN categories leaf ON leaf.id=c.id OR leaf.parent_id=c.id LEFT JOIN products p ON p.category_id=leaf.id AND p.active=1 WHERE c.active=1 AND c.parent_id IS NULL GROUP BY c.id ORDER BY c.sort_order,c.name")->fetchAll();
$featured = $pdo->query('SELECT p.*,c.name AS category_name,COALESCE(v.total_stock,p.stock) AS available_stock FROM products p LEFT JOIN categories c ON c.id=p.category_id LEFT JOIN (SELECT product_id,SUM(stock) AS total_stock FROM product_variants WHERE active=1 GROUP BY product_id) v ON v.product_id=p.id WHERE p.active=1 AND p.featured=1 ORDER BY p.created_at DESC,p.id DESC LIMIT 8')->fetchAll();
$campaigns = $pdo->query("SELECT id,title,slug,category,caption,image_url,product_id FROM marketing_posts WHERE type='campaign' AND published=1 AND (published_at IS NULL OR published_at<=NOW()) ORDER BY featured DESC,published_at DESC,id DESC LIMIT 3")->fetchAll();
$social = $pdo->query("SELECT setting_key,setting_value FROM store_settings WHERE setting_key IN ('social_instagram','social_tiktok','social_youtube') AND setting_value<>''")->fetchAll(PDO::FETCH_KEY_PAIR);
$homeReels = $pdo->query("SELECT m.id,m.title,m.caption,m.category,m.image_url,m.video_url,m.view_count,m.like_count,m.product_id,p.name AS product_name FROM marketing_posts m LEFT JOIN products p ON p.id=m.product_id WHERE m.type='reel' AND m.published=1 AND (m.published_at IS NULL OR m.published_at<=NOW()) ORDER BY m.featured DESC,m.published_at DESC,m.id DESC LIMIT 6")->fetchAll();

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
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>FD-Trend | Real Fashion, Authentic Collections</title>
  <meta name="description" content="Shop genuine fashion across 13 categories: Women's, Men's, T-Shirts, Jeans, Shirts, Jackets, Dresses, Shoes, Sandals, Bags, Jewellery, Watches, and Sunglasses.">
  <link rel="stylesheet" href="style.css">
</head>
<body>
<?php require __DIR__ . '/partials/site_header.php'; ?>
<main>
  <!-- HERO SECTION -->
  <section class="home-hero">
    <div class="wrap home-hero-grid">
      <div class="home-hero-copy">
        <p class="eyebrow">FD-TREND &middot; 2026 NEW DROPS</p>
        <h1>Real clothes.<br><em>Genuine style.</em></h1>
        <p>Explore verified collections across 13 fashion categories with authentic brand photography, live inventory, and Cash on Delivery.</p>
        <div class="hero-buttons">
          <a class="btn" href="shop.php">Shop all products &rarr;</a>
          <a class="btn secondary" href="categories.php">Browse 13 categories</a>
        </div>
      </div>
      <div class="hero-image-wrap">
        <?php if (!empty($featured[0]['image_url'])): ?>
          <img src="<?= e($featured[0]['image_url']) ?>" alt="<?= e($featured[0]['name']) ?>" fetchpriority="high">
        <?php else: ?>
          <div class="hero-placeholder">FD<span> / </span>FORM</div>
        <?php endif; ?>
        <span class="hero-image-caption">Curated Originals &middot; FD-Trend Studio</span>
      </div>
    </div>
  </section>

  <!-- TRUST STRIP -->
  <section class="trust-strip">
    <div class="wrap trust-grid">
      <div class="trust-item"><span>✨</span><div><strong>100% Genuine Products</strong><small>Authentic brand photographs &amp; real SKUs</small></div></div>
      <div class="trust-item"><span>📦</span><div><strong>Cash on Delivery</strong><small>Pay at your doorstep with zero risk</small></div></div>
      <div class="trust-item"><span>🚚</span><div><strong>Free Shipping &gt; ₹1,999</strong><small>Express delivery directly to your home</small></div></div>
      <div class="trust-item"><span>⚡</span><div><strong>Real-Time Stock</strong><small>Live database inventory tracking</small></div></div>
    </div>
  </section>

  <!-- 13 CATEGORIES SECTION -->
  <section class="wrap home-section">
    <div class="section-heading">
      <div>
        <p class="eyebrow">13 PRODUCT CATEGORIES</p>
        <h2>Find your category</h2>
      </div>
      <a class="text-link" href="categories.php">All 13 categories &rarr;</a>
    </div>

    <div class="category-grid-13">
      <?php foreach ($categoryRows as $category): 
        $catHref = $categoryPages[$category['slug']] ?? ('category.php?slug=' . rawurlencode($category['slug']));
        $count = (int)$category['product_count'];
      ?>
        <a class="category-tile-card" href="<?= e($catHref) ?>">
          <div class="cat-tile-img">
            <?php if (!empty($category['image_url'])): ?>
              <img src="<?= e($category['image_url']) ?>" alt="<?= e($category['name']) ?>" loading="lazy">
            <?php else: ?>
              <div class="cat-tile-placeholder">FD</div>
            <?php endif; ?>
            <span class="cat-tile-badge"><?= $count ?></span>
          </div>
          <div class="cat-tile-body">
            <strong><?= e($category['name']) ?></strong>
            <small><?= $count === 1 ? '1 item' : $count . ' items' ?></small>
          </div>
        </a>
      <?php endforeach; ?>
    </div>
  </section>

  <!-- FEATURED PRODUCTS -->
  <section class="home-section featured-band">
    <div class="wrap">
      <div class="section-heading">
        <div>
          <p class="eyebrow">AUTHENTIC EDITS</p>
          <h2>Featured pieces</h2>
        </div>
        <a class="text-link" href="shop.php">Shop all products &rarr;</a>
      </div>
      <?php if ($featured): ?>
        <div class="grid">
          <?php foreach ($featured as $product): 
            $discountPct = (int)($product['discount_percent'] ?? 0);
            if ($discountPct === 0 && (float)$product['mrp'] > (float)$product['price']) {
              $discountPct = (int)round((((float)$product['mrp'] - (float)$product['price']) / (float)$product['mrp']) * 100);
            }
          ?>
            <article class="card product-card">
              <a href="product.php?id=<?= (int)$product['id'] ?>" class="product-link">
                <?php if (!empty($product['image_url'])): ?>
                  <div class="img-wrap">
                    <img class="product-image" src="<?= e($product['image_url']) ?>" alt="<?= e($product['name']) ?>" loading="lazy">
                    <?php if ($discountPct > 0): ?>
                      <span class="discount-badge"><?= $discountPct ?>% OFF</span>
                    <?php endif; ?>
                  </div>
                <?php else: ?>
                  <div class="emoji-image"><?= e($product['emoji'] ?: '👕') ?></div>
                <?php endif; ?>
              </a>
              <div class="cardbody">
                <div class="card-meta">
                  <span class="brand-tag"><?= e($product['brand']) ?></span>
                  <span class="tag"><?= e($product['category_name'] ?: $product['category']) ?></span>
                </div>
                <h3><a href="product.php?id=<?= (int)$product['id'] ?>"><?= e($product['name']) ?></a></h3>
                <div class="price-row">
                  <span class="price"><?= money($product['price']) ?></span>
                  <?php if ($product['mrp'] > $product['price']): ?>
                    <span class="oldprice"><?= money($product['mrp']) ?></span>
                    <span class="save-tag"><?= $discountPct ?>% off</span>
                  <?php endif; ?>
                </div>
                <div class="rating-row">
                  <span class="stars">★ <?= number_format((float)($product['rating'] ?? 4.5), 1) ?></span>
                  <span class="rev-count">(<?= (int)($product['review_count'] ?? 0) ?>)</span>
                </div>
                <p class="stock-note in-stock">● <?= (int)$product['available_stock'] ?> in stock</p>
                <div class="cardactions">
                  <a class="btn secondary" href="product.php?id=<?= (int)$product['id'] ?>">View details</a>
                  <a class="btn" href="product.php?id=<?= (int)$product['id'] ?>">Order now</a>
                </div>
              </div>
            </article>
          <?php endforeach; ?>
        </div>
      <?php else: ?>
        <p class="panel">No featured products yet. Browse the catalog.</p>
      <?php endif; ?>
    </div>
  </section>

  <!-- STYLE NOTES -->
  <section class="wrap home-section inspiration-strip">
    <div>
      <p class="eyebrow">EDITORIAL INSPIRATION</p>
      <h2>Wear it your way.</h2>
      <p class="muted">Outfit ideas, seasonal drops, and fashion reels from the FD-Trend styling studio.</p>
    </div>
    <div class="cardactions">
      <a class="btn" href="reels.php">Watch style reels</a>
      <a class="btn secondary" href="marketing.php">Read journal</a>
    </div>
  </section>

  <!-- REELS -->
  <?php if ($homeReels): ?>
    <section class="wrap home-section">
      <div class="section-heading">
        <div>
          <p class="eyebrow">FASHION REELS</p>
          <h2>Style in motion</h2>
        </div>
        <a class="text-link" href="reels.php">All reels &rarr;</a>
      </div>
      <div class="grid reels-grid">
        <?php foreach ($homeReels as $reel): ?>
          <article class="card reel-card">
            <div class="reel-player" data-reel-player>
              <?php if ($reel['video_url']): ?>
                <video playsinline loop preload="metadata" poster="<?= e($reel['image_url'] ?? '') ?>">
                  <source src="<?= e($reel['video_url']) ?>">
                </video>
              <?php elseif ($reel['image_url']): ?>
                <div class="reel-motion" style="background-image:url('<?= e($reel['image_url']) ?>')"></div>
              <?php else: ?>
                <div class="content-placeholder"><?= e(strtoupper($reel['category'])) ?></div>
              <?php endif; ?>
              <button type="button" class="reel-toggle" data-reel-toggle>Play</button>
            </div>
            <div class="cardbody">
              <span class="tag"><?= e($reel['category']) ?></span>
              <h3><a href="reel.php?id=<?= (int)$reel['id'] ?>"><?= e($reel['title']) ?></a></h3>
              <p><?= e($reel['caption']) ?></p>
              <p class="reel-stats">
                <span><?= (int)$reel['view_count'] ?> views</span>
                <span><?= (int)$reel['like_count'] ?> likes</span>
              </p>
              <a class="text-link" href="reel.php?id=<?= (int)$reel['id'] ?>">Open reel &rarr;</a>
              <?php if ($reel['product_id']): ?>
                <a class="text-link" href="product.php?id=<?= (int)$reel['product_id'] ?>">Shop <?= e($reel['product_name'] ?? 'the look') ?> &rarr;</a>
              <?php endif; ?>
            </div>
          </article>
        <?php endforeach; ?>
      </div>
    </section>
  <?php endif; ?>

  <!-- NEWSLETTER -->
  <section class="newsletter-band">
    <div class="wrap newsletter-inner">
      <div>
        <p class="eyebrow">THE FD-TREND LETTER</p>
        <h2>Authentic fashion in your inbox.</h2>
        <p>New drops, seasonal lookbooks, and exclusive member discounts.</p>
      </div>
      <form method="post" action="newsletter.php" class="newsletter-form">
        <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
        <label class="sr-only" for="newsletter_email">Email address</label>
        <input id="newsletter_email" type="email" name="email" placeholder="Enter your email address" required maxlength="190">
        <button class="btn">Subscribe</button>
      </form>
    </div>
  </section>
</main>

<footer class="site-footer">
  <div class="wrap footer-inner">
    <a class="brand" href="index.php">FD<span>-Trend</span></a>
    <p>Authentic fashion e-commerce with verified product data and genuine photography.</p>
    <div class="footer-nav">
      <a href="shop.php">Shop</a> &middot;
      <a href="categories.php">Categories</a> &middot;
      <a href="orders.php">Track Order</a> &middot;
      <a href="contact.php">Contact</a> &middot;
      <a href="admin/login.php">Admin Portal</a>
    </div>
    <div class="social-links">
      <?php foreach (['instagram'=>'Instagram','tiktok'=>'TikTok','youtube'=>'YouTube'] as $key=>$label): if (!empty($social['social_'.$key])): ?>
        <a href="<?= e($social['social_'.$key]) ?>" target="_blank" rel="noopener noreferrer"><?= e($label) ?></a>
      <?php endif; endforeach; ?>
    </div>
  </div>
</footer>
<script src="assets/store.js" defer></script>
</body>
</html>
