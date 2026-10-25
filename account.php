<?php
require __DIR__ . '/config.php';
require_login();
$user = current_user();
$notice = '';
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $name = trim($_POST['name'] ?? '');
    $password = $_POST['password'] ?? '';
    if ($name === '' || strlen($name) > 150) {
        $error = 'Enter a valid name.';
    } else {
        $pdo->prepare('UPDATE users SET name=? WHERE id=?')->execute([$name, $user['id']]);
        $_SESSION['user_name'] = $name;
        if ($password !== '') {
            if (strlen($password) < 10) $error = 'New password must be at least 10 characters.';
            else {
                $pdo->prepare('UPDATE users SET password_hash=? WHERE id=?')->execute([password_hash($password, PASSWORD_DEFAULT), $user['id']]);
            }
        }
        if ($error === '') $notice = 'Account details saved.';
        $user = current_user();
    }
}
$stmt = $pdo->prepare('SELECT id,total,status,created_at FROM orders WHERE user_id=? ORDER BY id DESC');
$stmt->execute([$user['id']]);
$orders = $stmt->fetchAll();
?>
<!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>My account | FD-Trend</title><link rel="stylesheet" href="style.css"></head><body>
<?php require __DIR__ . '/partials/site_header.php'; ?>
<main class="wrap section"><p class="eyebrow">MY ACCOUNT</p><h1><?= e($user['name']) ?></h1><p class="muted"><?= e($user['email']) ?></p>
<?php if ($error): ?><div class="notice error"><?= e($error) ?></div><?php elseif ($notice): ?><div class="notice"><?= e($notice) ?></div><?php endif; ?>
<section class="panel auth-form"><h2>Account details</h2><form method="post"><input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>"><div class="field"><label for="name">Name</label><input id="name" name="name" maxlength="150" required value="<?= e($user['name']) ?>"></div><div class="field"><label for="password">New password (optional)</label><input id="password" type="password" name="password" minlength="10" autocomplete="new-password"></div><button class="btn">Save details</button></form></section>
<h2>Order history</h2><?php if (!$orders): ?><div class="panel"><p>No account orders yet.</p><a class="btn" href="shop.php">Browse the shop</a></div><?php else: ?><div class="panel tablewrap"><table><thead><tr><th>Order</th><th>Date</th><th>Total</th><th>Status</th></tr></thead><tbody><?php foreach ($orders as $order): ?><tr><td><a href="order_success.php?id=<?= (int)$order['id'] ?>">#<?= (int)$order['id'] ?></a></td><td><?= e($order['created_at']) ?></td><td><?= money($order['total']) ?></td><td><?= e($order['status']) ?></td></tr><?php endforeach; ?></tbody></table></div><?php endif; ?>
<p class="muted"><a href="wishlist.php">Wishlist</a> · <a href="cart.php">Cart</a></p>
</main><script src="assets/store.js" defer></script></body></html>
