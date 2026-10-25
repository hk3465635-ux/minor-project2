<?php
require __DIR__ . '/config.php';
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
$stmt = $pdo->prepare('SELECT id,user_id,customer_name,email,phone,address,subtotal,shipping,total,status,payment_method,created_at FROM orders WHERE id=?');
$stmt->execute([$id ?: 0]);
$order = $stmt->fetch();
$canView = $order && ((int)($_SESSION['last_order_id'] ?? 0) === (int)$order['id'] || (is_logged_in() && (int)$order['user_id'] === (int)$_SESSION['user_id']) || is_admin());
if (!$canView) { http_response_code(404); exit('Order not found.'); }
$items = $pdo->prepare('SELECT product_name,quantity,unit_price,size,color FROM order_items WHERE order_id=?');
$items->execute([(int)$order['id']]);
$lines = $items->fetchAll();
?>
<!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Order #<?= (int)$order['id'] ?> | FD-Trend</title><link rel="stylesheet" href="style.css"></head><body>
<?php require __DIR__ . '/partials/site_header.php'; ?>
<main class="wrap section"><div class="panel"><p class="eyebrow">CASH ON DELIVERY</p><h1>Order #<?= (int)$order['id'] ?></h1><p>Thank you, <?= e($order['customer_name']) ?>.</p><p><b>Status:</b> <?= e($order['status']) ?></p><p><b>Payment:</b> <?= e($order['payment_method']) ?></p><p><b>Placed:</b> <?= e($order['created_at']) ?></p><p><b>Delivery address:</b> <?= nl2br(e($order['address'])) ?></p>
<div class="tablewrap"><table><thead><tr><th>Item</th><th>Options</th><th>Qty</th><th>Line total</th></tr></thead><tbody><?php foreach ($lines as $line): ?><tr><td><?= e($line['product_name']) ?></td><td><?= e($line['size']) ?> / <?= e($line['color']) ?></td><td><?= (int)$line['quantity'] ?></td><td><?= money((float)$line['unit_price'] * (int)$line['quantity']) ?></td></tr><?php endforeach; ?></tbody></table></div>
<p>Subtotal <?= money($order['subtotal'] ?: $order['total']) ?> · Shipping <?= money($order['shipping'] ?? 0) ?></p>
<p class="price">Total <?= money($order['total']) ?></p>
<p class="muted">No online payment was collected. Pay the courier when your order arrives.</p>
<a class="btn" href="<?= is_logged_in() ? 'account.php' : 'orders.php' ?>">Order tracking</a>
<a class="btn secondary" href="shop.php">Continue shopping</a>
</div></main><script src="assets/store.js" defer></script></body></html>
