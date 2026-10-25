<?php
require __DIR__ . '/config.php';
if (is_logged_in()) { header('Location: account.php'); exit; }
$order = null;
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	verify_csrf();
	$orderId = filter_var($_POST['order_id'] ?? null, FILTER_VALIDATE_INT);
	$email = strtolower(trim($_POST['email'] ?? ''));
	if ($orderId === false || $orderId < 1 || !filter_var($email,FILTER_VALIDATE_EMAIL)) {
		$error = 'Enter a valid order number and the email used at checkout.';
	} else {
		$stmt = $pdo->prepare('SELECT id,email,total,status,created_at FROM orders WHERE id=? AND email=? AND user_id IS NULL LIMIT 1');
		$stmt->execute([$orderId,$email]);
		$order = $stmt->fetch() ?: null;
		if (!$order) $error = 'No guest order matched those details.';
	}
}
?>
<!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Track a guest order | FD-Trend</title><link rel="stylesheet" href="style.css"></head><body><?php require __DIR__ . '/partials/site_header.php'; ?><main class="wrap section auth-page"><p class="eyebrow">ORDER SUPPORT</p><h1>Track a guest order</h1><p class="muted">Enter the order number and checkout email. Account orders appear in your account history.</p><?php if ($error): ?><div class="notice error"><?= e($error) ?></div><?php endif; ?><?php if ($order): ?><section class="panel"><h2>Order #<?= (int)$order['id'] ?></h2><p>Status: <strong><?= e($order['status']) ?></strong></p><p>Total: <?= money($order['total']) ?></p><p class="muted">Placed <?= e($order['created_at']) ?></p></section><?php endif; ?><form method="post" class="panel auth-form"><input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>"><div class="field"><label for="order_id">Order number</label><input id="order_id" name="order_id" type="number" min="1" inputmode="numeric" required></div><div class="field"><label for="email">Checkout email</label><input id="email" name="email" type="email" maxlength="190" required></div><button class="btn">Find guest order</button></form><p><a href="login.php">Log in for account order history</a></p></main></body></html>