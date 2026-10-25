<?php
require __DIR__ . '/config.php';
require_login();
$userId = (int)$_SESSION['user_id'];
$notice = $_SESSION['flash'] ?? '';
unset($_SESSION['flash']);
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $productId = filter_var($_POST['product_id'] ?? null, FILTER_VALIDATE_INT) ?: 0;
    $action = $_POST['action'] ?? '';
    if ($action === 'remove') {
        $pdo->prepare('DELETE FROM wishlist_items WHERE user_id=? AND product_id=?')->execute([$userId,$productId]);
        $_SESSION['flash'] = 'Removed from your wishlist.';
    } elseif ($action === 'add') {
        $exists = $pdo->prepare('SELECT COUNT(*) FROM products WHERE id=? AND active=1');
        $exists->execute([$productId]);
        if ((int)$exists->fetchColumn()) {
            $pdo->prepare('INSERT IGNORE INTO wishlist_items (user_id,product_id) VALUES (?,?)')->execute([$userId,$productId]);
            $_SESSION['flash'] = 'Saved to your wishlist.';
        }
    }
    $back = $_POST['return_to'] ?? 'wishlist.php';
    if (!preg_match('~^(wishlist\.php|product\.php\?id=\d+|shop\.php(?:\?.*)?)$~', $back)) $back = 'wishlist.php';
    header('Location: ' . $back);
    exit;
}
$stmt = $pdo->prepare('SELECT p.*,c.name AS category_name,COALESCE(v.total_stock,p.stock) AS available_stock FROM wishlist_items w JOIN products p ON p.id=w.product_id LEFT JOIN categories c ON c.id=p.category_id LEFT JOIN (SELECT product_id,SUM(stock) total_stock FROM product_variants WHERE active=1 GROUP BY product_id) v ON v.product_id=p.id WHERE w.user_id=? AND p.active=1 ORDER BY w.created_at DESC');
$stmt->execute([$userId]);
$products = $stmt->fetchAll();
?>
<!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Wishlist | FD-Trend</title><link rel="stylesheet" href="style.css"></head><body><?php require __DIR__ . '/partials/site_header.php'; ?><main class="wrap section"><p class="eyebrow">YOUR SAVED PIECES</p><h1>Wishlist</h1><?php if ($notice): ?><div class="notice"><?= e($notice) ?></div><?php endif; ?><?php if (!$products): ?><div class="panel empty-state"><h2>Your wishlist is empty</h2><a class="btn" href="shop.php">Browse the catalog</a></div><?php else: ?><div class="grid"><?php foreach ($products as $product): ?><article class="card"><?php if ($product['image_url']): ?><a href="product.php?id=<?= (int)$product['id'] ?>"><img class="product-image" src="<?= e($product['image_url']) ?>" alt="<?= e($product['name']) ?>"></a><?php else: ?><div class="emoji-image"><?= e($product['emoji']) ?></div><?php endif; ?><div class="cardbody"><span class="tag"><?= e($product['category_name'] ?: $product['category']) ?></span><h2><a href="product.php?id=<?= (int)$product['id'] ?>"><?= e($product['name']) ?></a></h2><strong class="price"><?= money($product['price']) ?></strong><p class="stock-note"><?= (int)$product['available_stock'] ?> in stock</p><div class="cardactions"><a class="btn" href="product.php?id=<?= (int)$product['id'] ?>">View details</a><form method="post"><input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>"><input type="hidden" name="action" value="remove"><input type="hidden" name="product_id" value="<?= (int)$product['id'] ?>"><button class="btn secondary">Remove</button></form></div></div></article><?php endforeach; ?></div><?php endif; ?></main></body></html>
