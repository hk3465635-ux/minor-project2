<?php
require __DIR__ . '/config.php';
restore_cart($pdo);

function normalize_cart(PDO $pdo): void {
    $normalized = [];
    $find = $pdo->prepare('SELECT v.id,v.product_id,v.size,v.color,v.stock FROM product_variants v JOIN products p ON p.id=v.product_id WHERE v.product_id=? AND v.active=1 AND p.active=1 ORDER BY (v.size=?) DESC,(v.color=?) DESC,(v.stock>0) DESC,v.id LIMIT 1');
    foreach ($_SESSION['cart'] ?? [] as $key => $item) {
        $variantId = (int)($item['variant_id'] ?? 0);
        if ($variantId > 0) {
            $normalized[$variantId] = $item + ['variant_id' => $variantId];
            continue;
        }
        $productId = (int)($item['product_id'] ?? $key);
        $find->execute([$productId, (string)($item['size'] ?? ''), (string)($item['color'] ?? '')]);
        $variant = $find->fetch();
        if ($variant) {
            $normalized[(int)$variant['id']] = [
                'variant_id' => (int)$variant['id'],
                'product_id' => (int)$variant['product_id'],
                'quantity' => max(1, (int)($item['quantity'] ?? 1)),
                'size' => $variant['size'],
                'color' => $variant['color'],
            ];
        }
    }
    $_SESSION['cart'] = $normalized;
}
normalize_cart($pdo);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $action = $_POST['action'] ?? '';
    if (isset($_POST['remove_variant'])) {
        unset($_SESSION['cart'][(int)$_POST['remove_variant']]);
        $_SESSION['flash'] = 'Item removed from cart.';
    } elseif ($action === 'add' || $action === 'buy_now') {
        $id = filter_var($_POST['variant_id'] ?? 0, FILTER_VALIDATE_INT) ?: 0;
        $productId = filter_var($_POST['product_id'] ?? 0, FILTER_VALIDATE_INT) ?: 0;
        if ($id <= 0 && $productId > 0) {
            $reqSize = trim($_POST['size'] ?? '');
            $reqColor = trim($_POST['color'] ?? '');
            $find = $pdo->prepare('SELECT v.id FROM product_variants v JOIN products p ON p.id=v.product_id WHERE v.product_id=? AND v.active=1 AND p.active=1 ORDER BY (v.size=?) DESC, (v.color=?) DESC, (v.stock>0) DESC, v.id LIMIT 1');
            $find->execute([$productId, $reqSize, $reqColor]);
            $foundVarId = $find->fetchColumn();
            if ($foundVarId) $id = (int)$foundVarId;
        }
        $stmt = $pdo->prepare('SELECT v.id,v.product_id,v.size,v.color,v.stock,p.name FROM product_variants v JOIN products p ON p.id=v.product_id WHERE v.id=? AND v.active=1 AND p.active=1');
        $stmt->execute([$id]);
        $p = $stmt->fetch();
        if (!$p) {
            $_SESSION['flash'] = 'That product is no longer available.';
        } elseif ((int)$p['stock'] <= 0) {
            $_SESSION['flash'] = 'Sorry, ' . $p['name'] . ' is currently out of stock.';
        } else {
            $qty = filter_var($_POST['quantity'] ?? 1, FILTER_VALIDATE_INT);
            $qty = $qty === false ? 1 : max(1, $qty);
            $current = (int)($_SESSION['cart'][$id]['quantity'] ?? 0);
            if ($action === 'add') {
                if ($current + $qty > (int)$p['stock']) {
                    $_SESSION['flash'] = 'Only ' . $p['stock'] . ' available in stock for ' . $p['name'] . ' (' . $p['size'] . ' / ' . $p['color'] . ').';
                } else {
                    $_SESSION['cart'][$id] = [
                        'variant_id' => (int)$p['id'],
                        'product_id' => (int)$p['product_id'],
                        'quantity' => $current + $qty,
                        'size' => $p['size'],
                        'color' => $p['color'],
                    ];
                    $_SESSION['flash'] = $p['name'] . ' (' . $p['size'] . ', ' . $p['color'] . ') added to your cart.';
                }
            } else { // buy_now
                $buyQty = min($qty, (int)$p['stock']);
                $_SESSION['cart'][$id] = [
                    'variant_id' => (int)$p['id'],
                    'product_id' => (int)$p['product_id'],
                    'quantity' => $buyQty,
                    'size' => $p['size'],
                    'color' => $p['color'],
                ];
                persist_cart($pdo);
                header('Location: checkout.php');
                exit;
            }
        }
    } elseif ($action === 'update') {
        foreach(($_POST['quantity'] ?? []) as $pid => $qty) {
            $pid = (int)$pid;
            $qty = (int)$qty;
            if ($qty <= 0) {
                unset($_SESSION['cart'][$pid]);
                continue;
            }
            $stmt = $pdo->prepare('SELECT v.stock FROM product_variants v JOIN products p ON p.id=v.product_id WHERE v.id=? AND v.active=1 AND p.active=1');
            $stmt->execute([$pid]);
            $stock = $stmt->fetchColumn();
            if ($stock !== false) {
                $_SESSION['cart'][$pid]['quantity'] = min($qty, (int)$stock);
            }
        }
        $_SESSION['flash'] = 'Cart updated.';
    }
    persist_cart($pdo);
    header('Location: cart.php');
    exit;
}

$cart = $_SESSION['cart'] ?? [];
$items = [];
$total = 0;
if ($cart) {
    $ids = array_map('intval', array_keys($cart));
    $marks = implode(',', array_fill(0, count($ids), '?'));
    $stmt = $pdo->prepare("SELECT p.*, v.id AS variant_id, v.size, v.color, v.stock AS variant_stock, v.price_adjustment FROM product_variants v JOIN products p ON p.id=v.product_id WHERE p.active=1 AND v.active=1 AND v.id IN ($marks)");
    $stmt->execute($ids);
    foreach ($stmt->fetchAll() as $p) {
        $q = min((int)$cart[$p['variant_id']]['quantity'], (int)$p['variant_stock']);
        if ($q < 1) continue;
        $p['quantity'] = $q;
        $p['line_total'] = $q * ((float)$p['price'] + (float)$p['price_adjustment']);
        $total += $p['line_total'];
        $items[] = $p;
    }
}
$flash = $_SESSION['flash'] ?? '';
unset($_SESSION['flash']);
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Your Cart | FD-Trend</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
<?php require __DIR__ . '/partials/site_header.php'; ?>
<main class="wrap section">
  <p class="eyebrow">FD-TREND / SHOPPING BAG</p>
  <h1>Your Shopping Cart</h1>
  <?php if ($flash): ?><div class="notice"><?= e($flash) ?></div><?php endif; ?>
  <?php if (!$items): ?>
    <div class="panel empty-state">
      <div style="font-size:3rem;margin-bottom:12px">🛍️</div>
      <h2>Your cart is empty</h2>
      <p class="muted">Explore our curated collections of T-Shirts, Shirts, Jeans, Jackets, Watches and more.</p>
      <a class="btn" href="shop.php">Start shopping</a>
    </div>
  <?php else: ?>
    <form method="post" class="panel">
      <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
      <input type="hidden" name="action" value="update">
      <div class="tablewrap">
        <table class="cart-table">
          <thead>
            <tr>
              <th>Photo</th>
              <th>Product</th>
              <th>Size / Colour</th>
              <th>Unit price</th>
              <th>Quantity</th>
              <th>Subtotal</th>
              <th>Remove</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($items as $p): ?>
              <tr>
                <td class="cart-thumb-cell">
                  <a href="product.php?id=<?= (int)$p['id'] ?>">
                    <?php if (!empty($p['image_url'])): ?>
                      <img src="<?= e($p['image_url']) ?>" alt="<?= e($p['name']) ?>" class="cart-thumb">
                    <?php else: ?>
                      <span class="cart-emoji"><?= e($p['emoji'] ?: '👕') ?></span>
                    <?php endif; ?>
                  </a>
                </td>
                <td>
                  <strong><a href="product.php?id=<?= (int)$p['id'] ?>"><?= e($p['name']) ?></a></strong>
                  <br><small class="muted"><?= e($p['brand']) ?></small>
                </td>
                <td>
                  <span class="badge"><?= e($p['size']) ?></span>
                  <span class="badge secondary"><?= e($p['color']) ?></span>
                </td>
                <td><?= money((float)$p['price'] + (float)$p['price_adjustment']) ?></td>
                <td>
                  <input class="qty" type="number" min="0" max="<?= (int)$p['variant_stock'] ?>" name="quantity[<?= (int)$p['variant_id'] ?>]" value="<?= (int)$p['quantity'] ?>" style="width:70px">
                  <br><small class="muted"><?= (int)$p['variant_stock'] ?> units in stock</small>
                </td>
                <td><strong><?= money($p['line_total']) ?></strong></td>
                <td>
                  <button class="btn danger small" type="submit" name="remove_variant" value="<?= (int)$p['variant_id'] ?>">Remove</button>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
      <div class="cart-summary-row">
        <div>
          <button class="btn secondary">Update cart quantities</button>
        </div>
        <div style="text-align:right">
          <p class="price" style="font-size:1.4rem;margin:0">Subtotal: <strong><?= money($total) ?></strong></p>
          <p class="muted" style="margin:4px 0 16px">Shipping &amp; taxes calculated at checkout. Real-time stock verified.</p>
          <a class="btn" href="checkout.php" style="font-size:1.1rem;padding:12px 28px">Proceed to Checkout &rarr;</a>
        </div>
      </div>
    </form>
  <?php endif; ?>
</main>
<script src="assets/store.js" defer></script>
</body>
</html>
