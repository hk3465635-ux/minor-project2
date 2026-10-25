<?php
require __DIR__ . '/config.php';
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT) ?: 0;
$stmt = $pdo->prepare('SELECT p.*,c.name AS category_name,c.slug AS category_slug FROM products p LEFT JOIN categories c ON c.id=p.category_id WHERE p.id=? AND p.active=1');
$stmt->execute([$id]);
$product = $stmt->fetch();
if (!$product) { http_response_code(404); exit('Product not found. <a href="shop.php">Back to shop</a>'); }
$error = '';
$notice = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'review') {
    verify_csrf();
    require_login();
    $rating = filter_var($_POST['rating'] ?? null, FILTER_VALIDATE_INT);
    $reviewText = trim($_POST['review_text'] ?? '');
    $purchase = $pdo->prepare('SELECT 1 FROM orders o JOIN order_items oi ON oi.order_id=o.id WHERE o.user_id=? AND oi.product_id=? LIMIT 1');
    $purchase->execute([(int)$_SESSION['user_id'], $id]);
    if (!$purchase->fetchColumn()) $error = 'A verified purchase is required to review this product.';
    elseif ($rating === false || $rating < 1 || $rating > 5) $error = 'Choose a rating from 1 to 5.';
    else {
        $review = $pdo->prepare('INSERT INTO product_reviews (product_id,user_id,reviewer_name,rating,review_text,approved) VALUES (?,?,?,?,?,1) ON DUPLICATE KEY UPDATE rating=VALUES(rating),review_text=VALUES(review_text),approved=1');
        $review->execute([$id, (int)$_SESSION['user_id'], $_SESSION['user_name'] ?? 'Verified Buyer', $rating, $reviewText]);
        $notice = 'Thank you! Your verified review has been published.';
    }
}
$variantStmt = $pdo->prepare('SELECT id,size,color,stock,price_adjustment FROM product_variants WHERE product_id=? AND active=1 ORDER BY size,color');
$variantStmt->execute([$id]);
$variants = $variantStmt->fetchAll();
$totalVariantStock = array_sum(array_column($variants, 'stock'));

$imageStmt = $pdo->prepare('SELECT image_url,alt_text FROM product_images WHERE product_id=? ORDER BY sort_order,id');
$imageStmt->execute([$id]);
$images = $imageStmt->fetchAll();

// If product_images has no rows, use main image
if (empty($images) && !empty($product['image_url'])) {
    $images = [['image_url' => $product['image_url'], 'alt_text' => $product['name']]];
}

// Calculate reviews and ratings
$reviewStmt = $pdo->prepare('SELECT AVG(rating) AS avg_rating, COUNT(*) AS count FROM product_reviews WHERE product_id=? AND approved=1');
$reviewStmt->execute([$id]);
$dbRatings = $reviewStmt->fetch();
$hasDbReviews = (int)($dbRatings['count'] ?? 0) > 0;
$avgRating = $hasDbReviews ? (float)$dbRatings['avg_rating'] : (float)($product['rating'] ?? 4.5);
$reviewCount = $hasDbReviews ? (int)$dbRatings['count'] : (int)($product['review_count'] ?? 0);

$reviewsStmt = $pdo->prepare('SELECT r.rating,r.review_text,r.created_at,COALESCE(u.name, r.reviewer_name, "Verified Buyer") AS name FROM product_reviews r LEFT JOIN users u ON u.id=r.user_id WHERE r.product_id=? AND r.approved=1 ORDER BY r.created_at DESC LIMIT 15');
$reviewsStmt->execute([$id]);
$reviews = $reviewsStmt->fetchAll();

$discountPercent = (int)($product['discount_percent'] ?? 0);
if ($discountPercent === 0 && (float)$product['mrp'] > (float)$product['price']) {
    $discountPercent = (int)round((((float)$product['mrp'] - (float)$product['price']) / (float)$product['mrp']) * 100);
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title><?= e($product['name']) ?> | <?= e($product['brand']) ?> - FD-Trend</title>
  <meta name="description" content="<?= e(text_preview($product['description'], 150)) ?>">
  <link rel="stylesheet" href="style.css">
</head>
<body>
<?php require __DIR__ . '/partials/site_header.php'; ?>
<main class="wrap section">
  <nav class="breadcrumbs" aria-label="Breadcrumb">
    <a href="index.php">Home</a> &rsaquo;
    <a href="categories.php">Categories</a> &rsaquo;
    <a href="shop.php<?= $product['category_slug'] ? '?category=' . rawurlencode($product['category_slug']) : '' ?>"><?= e($product['category_name'] ?: $product['category']) ?></a> &rsaquo;
    <span><?= e($product['name']) ?></span>
  </nav>

  <div class="product-layout">
    <!-- PRODUCT IMAGE GALLERY -->
    <div class="product-gallery-box">
      <div class="main-image-container">
        <?php if (!empty($product['image_url'])): ?>
          <img id="mainProductPhoto" class="main-photo" src="<?= e($product['image_url']) ?>" alt="<?= e($product['name']) ?>" fetchpriority="high">
        <?php else: ?>
          <div class="emoji-image"><?= e($product['emoji'] ?: '👕') ?></div>
        <?php endif; ?>
        <?php if ($discountPercent > 0): ?>
          <span class="gallery-discount-badge"><?= $discountPercent ?>% OFF</span>
        <?php endif; ?>
      </div>
      <?php if (count($images) > 1): ?>
        <div class="gallery-thumbs" aria-label="Product photographs">
          <?php foreach ($images as $idx => $img): ?>
            <button type="button" class="gallery-thumb-btn <?= $idx===0?'active':'' ?>" data-src="<?= e($img['image_url']) ?>" onclick="switchProductPhoto(this)" title="View photograph <?= $idx+1 ?>">
              <img src="<?= e($img['image_url']) ?>" alt="<?= e($img['alt_text'] ?: $product['name']) ?>" loading="lazy">
            </button>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>
    </div>

    <!-- PRODUCT INFO -->
    <section class="panel product-info-panel">
      <div class="product-header">
        <span class="brand-pill"><?= e($product['brand']) ?></span>
        <span class="category-pill"><?= e($product['category_name'] ?: $product['category']) ?></span>
      </div>
      <h1><?= e($product['name']) ?></h1>
      
      <div class="product-ratings-row">
        <span class="stars-gold">★ <?= number_format($avgRating, 1) ?></span>
        <span class="rating-bar-sep">|</span>
        <a href="#customer-reviews" class="rev-link"><?= $reviewCount ?> customer ratings</a>
        <?php if (!empty($product['sku'])): ?>
          <span class="rating-bar-sep">|</span>
          <span class="sku-code">SKU: <strong><?= e($product['sku']) ?></strong></span>
        <?php endif; ?>
      </div>

      <div class="product-pricing">
        <span class="price-big"><?= money($product['price']) ?></span>
        <?php if ($product['mrp'] > $product['price']): ?>
          <span class="oldprice-big"><?= money($product['mrp']) ?></span>
          <span class="save-pill">Save <?= money((float)$product['mrp'] - (float)$product['price']) ?> (<?= $discountPercent ?>% off)</span>
        <?php endif; ?>
        <p class="tax-note">Inclusive of all taxes &middot; Cash on Delivery available</p>
      </div>

      <div class="stock-indicator-box">
        <?php if ($totalVariantStock > 5): ?>
          <span class="badge in-stock-badge">✓ In Stock (<?= $totalVariantStock ?> items available)</span>
        <?php elseif ($totalVariantStock > 0): ?>
          <span class="badge low-stock-badge">⚠ Low Stock: Only <?= $totalVariantStock ?> units left in inventory!</span>
        <?php else: ?>
          <span class="badge out-stock-badge">✕ Out of Stock</span>
        <?php endif; ?>
      </div>

      <hr class="divider">

      <?php if ($variants && $totalVariantStock > 0): ?>
        <form method="post" action="cart.php" class="product-purchase-form">
          <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
          <div class="field">
            <label for="variant_id"><strong>Select Size &amp; Colour</strong></label>
            <select id="variant_id" name="variant_id" required onchange="updateMaxQuantity(this)">
              <?php foreach ($variants as $variant): ?>
                <option value="<?= (int)$variant['id'] ?>" data-stock="<?= (int)$variant['stock'] ?>" <?= (int)$variant['stock'] < 1 ? 'disabled' : '' ?>>
                  Size: <?= e($variant['size']) ?> &middot; Colour: <?= e($variant['color']) ?> &middot; (<?= (int)$variant['stock'] ?> in stock)
                </option>
              <?php endforeach; ?>
            </select>
          </div>

          <div class="field qty-field">
            <label for="quantity"><strong>Quantity</strong></label>
            <input id="quantity" type="number" name="quantity" value="1" min="1" max="<?= max(1, (int)$variants[0]['stock']) ?>" required>
          </div>

          <div class="action-buttons-group">
            <button class="btn btn-cart" name="action" value="add" type="submit">
              🛒 Add to Cart
            </button>
            <button class="btn btn-buynow" name="action" value="buy_now" type="submit">
              ⚡ Buy Now
            </button>
          </div>
        </form>
      <?php else: ?>
        <div class="notice error">Currently out of stock. Please check back soon or browse related styles.</div>
      <?php endif; ?>

      <div class="product-perks">
        <div class="perk-item"><span>🚚</span><div><strong>Free Shipping</strong><small>On all prepaid &amp; COD orders above ₹1,999</small></div></div>
        <div class="perk-item"><span>🔄</span><div><strong>Easy 7-Day Returns</strong><small>No questions asked hassle-free return policy</small></div></div>
        <div class="perk-item"><span>🛡️</span><div><strong>100% Genuine Guarantee</strong><small>Directly sourced from authorized brand feeds</small></div></div>
      </div>

      <div class="wishlist-row">
        <?php if (is_logged_in()): ?>
          <form method="post" action="wishlist.php" class="inline-form">
            <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
            <input type="hidden" name="action" value="add">
            <input type="hidden" name="product_id" value="<?= (int)$product['id'] ?>">
            <input type="hidden" name="return_to" value="product.php?id=<?= (int)$product['id'] ?>">
            <button class="btn secondary">♥ Add to Wishlist</button>
          </form>
        <?php else: ?>
          <p class="muted"><a href="login.php">Log in</a> to save this product to your personal wishlist.</p>
        <?php endif; ?>
      </div>

      <div class="product-description-wrap">
        <h3>Product Details &amp; Specifications</h3>
        <p><?= nl2br(e($product['description'])) ?></p>
        
        <table class="specs-table">
          <tbody>
            <tr><th>Brand</th><td><?= e($product['brand']) ?></td></tr>
            <tr><th>Category</th><td><?= e($product['category_name'] ?: $product['category']) ?></td></tr>
            <?php if (!empty($product['subcategory'])): ?>
              <tr><th>Subcategory</th><td><?= e($product['subcategory']) ?></td></tr>
            <?php endif; ?>
            <tr><th>Material</th><td><?= e($product['material']) ?></td></tr>
            <tr><th>Colour</th><td><?= e($product['color']) ?></td></tr>
            <tr><th>Available Sizes</th><td><?= e($product['sizes']) ?></td></tr>
            <?php if (!empty($product['sku'])): ?>
              <tr><th>SKU / Product ID</th><td><?= e($product['sku']) ?></td></tr>
            <?php endif; ?>
            <tr><th>Inventory Status</th><td><?= $totalVariantStock > 0 ? 'In Stock (' . $totalVariantStock . ' units)' : 'Out of Stock' ?></td></tr>
          </tbody>
        </table>
      </div>
    </section>
  </div>

  <?php if ($error): ?><div class="notice error"><?= e($error) ?></div><?php endif; ?>
  <?php if ($notice): ?><div class="notice"><?= e($notice) ?></div><?php endif; ?>

  <!-- REVIEWS SECTION -->
  <section class="reviews-section" id="customer-reviews">
    <div class="reviews-header">
      <div>
        <h2>Customer Reviews &amp; Ratings</h2>
        <p class="muted">Based on <?= $reviewCount ?> verified customer experiences</p>
      </div>
      <div class="rating-score-badge">
        <strong><?= number_format($avgRating, 1) ?></strong>
        <div>
          <span>★ ★ ★ ★ ★</span>
          <small><?= $reviewCount ?> total ratings</small>
        </div>
      </div>
    </div>

    <?php if ($reviews): ?>
      <div class="grid review-grid">
        <?php foreach ($reviews as $rev): ?>
          <article class="panel review-card">
            <div class="rev-top">
              <strong><?= e($rev['name']) ?></strong>
              <span class="verified-buyer-badge">✓ Verified Buyer</span>
            </div>
            <p class="rev-stars"><?= str_repeat('★', (int)$rev['rating']) ?><?= str_repeat('☆', 5 - (int)$rev['rating']) ?></p>
            <p class="rev-text"><?= nl2br(e($rev['review_text'] ?? '')) ?></p>
            <small class="muted"><?= e($rev['created_at']) ?></small>
          </article>
        <?php endforeach; ?>
      </div>
    <?php else: ?>
      <p class="panel muted">No reviews published yet for this item.</p>
    <?php endif; ?>

    <div class="write-review-box">
      <?php if (is_logged_in()): ?>
        <form method="post" class="panel review-form">
          <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
          <input type="hidden" name="action" value="review">
          <h3>Write a Product Review</h3>
          <p class="muted">Share your experience with the fit, quality, and material.</p>
          <div class="field">
            <label for="rating">Rating</label>
            <select name="rating" id="rating" required>
              <option value="5">★★★★★ 5 Stars - Outstanding</option>
              <option value="4">★★★★☆ 4 Stars - Very Good</option>
              <option value="3">★★★☆☆ 3 Stars - Average</option>
              <option value="2">★★☆☆☆ 2 Stars - Disappointed</option>
              <option value="1">★☆☆☆☆ 1 Star - Poor</option>
            </select>
          </div>
          <div class="field">
            <label for="review_text">Review comments</label>
            <textarea name="review_text" id="review_text" maxlength="2000" rows="4" placeholder="How did this item fit? Would you recommend it?" required></textarea>
          </div>
          <button class="btn">Submit Review</button>
        </form>
      <?php else: ?>
        <p class="panel"><a href="login.php">Log in</a> with your account to submit a review for this purchase.</p>
      <?php endif; ?>
    </div>
  </section>
</main>

<script>
function switchProductPhoto(btn) {
  var main = document.getElementById('mainProductPhoto');
  if (!main) return;
  main.src = btn.dataset.src;
  document.querySelectorAll('.gallery-thumb-btn').forEach(function(b) {
    b.classList.remove('active');
  });
  btn.classList.add('active');
}
function updateMaxQuantity(select) {
  var opt = select.options[select.selectedIndex];
  var stock = parseInt(opt.getAttribute('data-stock') || '1', 10);
  var qtyInput = document.getElementById('quantity');
  if (qtyInput) {
    qtyInput.max = Math.max(1, stock);
    if (parseInt(qtyInput.value, 10) > stock) {
      qtyInput.value = stock;
    }
  }
}
</script>
<script src="assets/store.js" defer></script>
</body>
</html>
