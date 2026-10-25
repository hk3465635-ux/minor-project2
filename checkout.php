<?php
require __DIR__ . '/config.php';
restore_cart($pdo);
$cart = $_SESSION['cart'] ?? [];
if (!$cart) { header('Location: cart.php'); exit; }
$variantIds = array_map('intval', array_keys($cart));
$marks = implode(',', array_fill(0, count($variantIds), '?'));
$stmt = $pdo->prepare("SELECT v.id AS variant_id,v.product_id,v.size,v.color,v.stock AS variant_stock,v.price_adjustment,p.name,p.price FROM product_variants v JOIN products p ON p.id=v.product_id WHERE v.active=1 AND p.active=1 AND v.id IN ($marks)");
$stmt->execute($variantIds);
$products = $stmt->fetchAll();
$subtotal = 0.0;
foreach ($products as &$product) {
    $quantity = max(1, (int)($cart[$product['variant_id']]['quantity'] ?? 1));
    $product['quantity'] = $quantity;
    $product['unit_price'] = (float)$product['price'] + (float)$product['price_adjustment'];
    $product['line_total'] = $product['unit_price'] * $quantity;
    $subtotal += $product['line_total'];
}
unset($product);
$shipping = $subtotal >= 1999 ? 0.0 : 99.0;
$total = $subtotal + $shipping;
$error = '';
$user = current_user();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $name = trim($_POST['customer_name'] ?? '');
    $email = strtolower(trim($_POST['email'] ?? ''));
    $phone = trim($_POST['phone'] ?? '');
    $address = trim($_POST['address'] ?? '');
    if ($name === '' || strlen($name) > 150 || !filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($phone) < 7 || strlen($phone) > 40 || $address === '' || strlen($address) > 4000) {
        $error = 'Enter a name, valid email, phone number, and delivery address.';
    } else {
        try {
            $pdo->beginTransaction();
            $lock = $pdo->prepare('SELECT v.id AS variant_id,v.product_id,v.size,v.color,v.stock AS variant_stock,v.price_adjustment,p.name,p.price,p.active FROM product_variants v JOIN products p ON p.id=v.product_id WHERE v.id=? AND v.active=1 FOR UPDATE');
            $orderLines = [];
            $subtotal = 0.0;
            foreach ($cart as $variantId => $item) {
                $quantity = filter_var($item['quantity'] ?? null, FILTER_VALIDATE_INT);
                if ($quantity === false || $quantity < 1) throw new RuntimeException('Cart contains an invalid quantity. Return to your cart and update it.');
                $lock->execute([(int)$variantId]);
                $row = $lock->fetch();
                if (!$row || !(int)$row['active'] || (int)$row['variant_stock'] < $quantity) throw new RuntimeException('Stock changed for an item. Return to the cart and update quantities.');
                $row['quantity'] = $quantity;
                $row['unit_price'] = (float)$row['price'] + (float)$row['price_adjustment'];
                $row['line_total'] = $row['unit_price'] * $quantity;
                $subtotal += $row['line_total'];
                $orderLines[] = $row;
            }
            $shipping = $subtotal >= 1999 ? 0.0 : 99.0;
            $total = $subtotal + $shipping;
            $order = $pdo->prepare('INSERT INTO orders (user_id,customer_name,email,phone,address,subtotal,discount,shipping,total,status,payment_method) VALUES (?,?,?,?,?,?,0,?, ?,\'Placed\',\'Cash on Delivery\')');
            $order->execute([$user['id'] ?? null, $name, $email, $phone, $address, $subtotal, $shipping, $total]);
            $orderId = (int)$pdo->lastInsertId();
            $insertLine = $pdo->prepare('INSERT INTO order_items (order_id,product_id,variant_id,product_name,unit_price,quantity,size,color) VALUES (?,?,?,?,?,?,?,?)');
            $decrementVariant = $pdo->prepare('UPDATE product_variants SET stock=stock-? WHERE id=? AND stock>=?');
            $decrementProduct = $pdo->prepare('UPDATE products SET stock=stock-? WHERE id=? AND stock>=?');
            foreach ($orderLines as $line) {
                $insertLine->execute([$orderId, $line['product_id'], $line['variant_id'], $line['name'], $line['unit_price'], $line['quantity'], $line['size'], $line['color']]);
                $decrementVariant->execute([$line['quantity'], $line['variant_id'], $line['quantity']]);
                $decrementProduct->execute([$line['quantity'], $line['product_id'], $line['quantity']]);
                if ($decrementVariant->rowCount() !== 1 || $decrementProduct->rowCount() !== 1) throw new RuntimeException('Stock changed while placing the order. Please retry.');
            }
            $pdo->commit();
            unset($_SESSION['cart']);
            persist_cart($pdo);
            $_SESSION['last_order_id'] = $orderId;
            header('Location: order_success.php?id=' . $orderId); exit;
        } catch (Throwable $exception) {
            if ($pdo->inTransaction()) $pdo->rollBack();
            if ($exception instanceof RuntimeException) $error = $exception->getMessage();
            else { error_log('Checkout failed: ' . $exception->getMessage()); $error = 'Could not place the order. Please try again.'; }
        }
    }
}
?>
<!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Checkout | FD-Trend</title><link rel="stylesheet" href="style.css"></head><body>
<?php require __DIR__ . '/partials/site_header.php'; ?>
<main class="wrap section"><p class="eyebrow">SECURE CHECKOUT</p><h1>Delivery details</h1><p class="muted">Cash on Delivery only. No online payment is collected.</p><?php if ($error): ?><div class="notice error"><?= e($error) ?></div><?php endif; ?><div class="row"><form method="post" class="panel checkout-form"><input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>"><div class="field"><label for="customer_name">Full name</label><input id="customer_name" name="customer_name" maxlength="150" required value="<?= e($_POST['customer_name'] ?? $user['name'] ?? '') ?>"></div><div class="field"><label for="email">Email</label><input id="email" type="email" name="email" maxlength="190" required value="<?= e($_POST['email'] ?? $user['email'] ?? '') ?>"></div><div class="field"><label for="phone">Phone</label><input id="phone" name="phone" maxlength="40" required value="<?= e($_POST['phone'] ?? '') ?>"></div><div class="field"><label for="address">Delivery address</label><textarea id="address" name="address" rows="4" maxlength="4000" required><?= e($_POST['address'] ?? '') ?></textarea></div><button class="btn">Place COD order</button></form><aside class="panel order-summary"><h2>Order summary</h2><?php foreach ($products as $product): ?><p><?= e($product['name']) ?> · <?= e($product['size']) ?> / <?= e($product['color']) ?> × <?= (int)$product['quantity'] ?> <span><?= money($product['line_total']) ?></span></p><?php endforeach; ?><hr><p>Subtotal <span><?= money($subtotal) ?></span></p><p>Shipping <span><?= $shipping > 0 ? money($shipping) : 'Free' ?></span></p><h2>Total <span><?= money($total) ?></span></h2><small class="muted">Free shipping on orders of ₹1,999 or more.</small></aside></div></main></body></html>
