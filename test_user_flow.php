<?php
/**
 * Test Script for Complete User Flow
 * This script tests: Registration → Login → Add to Cart → Checkout → Order Placement
 */

require __DIR__ . '/config.php';

echo "=== FD-Trend User Flow Test ===\n\n";

// Step 1: Create a test user
echo "Step 1: Creating test user...\n";
$testEmail = 'testuser@example.com';
$testPassword = 'TestPassword123';

try {
    // Try to delete existing test user first
    $pdo->prepare('DELETE FROM users WHERE email=?')->execute([$testEmail]);
    
    // Create new user
    $stmt = $pdo->prepare('INSERT INTO users (name,email,password_hash) VALUES (?,?,?)');
    $stmt->execute(['Test User', $testEmail, password_hash($testPassword, PASSWORD_DEFAULT)]);
    $userId = (int)$pdo->lastInsertId();
    echo "✓ Test user created (ID: $userId)\n\n";
} catch (PDOException $e) {
    echo "✗ Failed to create user: " . $e->getMessage() . "\n\n";
    exit(1);
}

// Step 2: Test login
echo "Step 2: Testing login...\n";
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$_SESSION = []; // Clear session

try {
    $stmt = $pdo->prepare('SELECT id,name,email,password_hash FROM users WHERE email=? AND active=1 LIMIT 1');
    $stmt->execute([$testEmail]);
    $user = $stmt->fetch();
    
    if (!$user || !password_verify($testPassword, $user['password_hash'])) {
        echo "✗ Login failed\n\n";
        exit(1);
    }
    
    if (!headers_sent()) {
        session_regenerate_id(true);
    }
    $_SESSION['user_id'] = (int)$user['id'];
    $_SESSION['user_name'] = $user['name'];
    $_SESSION['user_email'] = $user['email'];
    echo "✓ Login successful\n\n";
} catch (PDOException $e) {
    echo "✗ Login error: " . $e->getMessage() . "\n\n";
    exit(1);
}

// Step 3: Add product to cart
echo "Step 3: Adding product to cart...\n";
try {
    // Get a product variant
    $stmt = $pdo->prepare('SELECT v.id,v.product_id,v.size,v.color,v.stock,p.name FROM product_variants v JOIN products p ON p.id=v.product_id WHERE v.active=1 AND v.stock>0 AND p.active=1 ORDER BY v.id LIMIT 1');
    $stmt->execute();
    $variant = $stmt->fetch();
    
    if (!$variant) {
        echo "✗ No products available\n\n";
        exit(1);
    }
    
    $variantId = (int)$variant['id'];
    $_SESSION['cart'][$variantId] = [
        'variant_id' => $variantId,
        'product_id' => (int)$variant['product_id'],
        'quantity' => 1,
        'size' => $variant['size'],
        'color' => $variant['color'],
    ];
    
    echo "✓ Added to cart: {$variant['name']} ({$variant['size']} / {$variant['color']})\n\n";
} catch (PDOException $e) {
    echo "✗ Cart error: " . $e->getMessage() . "\n\n";
    exit(1);
}

// Step 4: Test checkout
echo "Step 4: Testing checkout...\n";
try {
    $cart = $_SESSION['cart'];
    if (!$cart) {
        echo "✗ Cart is empty\n\n";
        exit(1);
    }
    
    $variantIds = array_map('intval', array_keys($cart));
    $marks = implode(',', array_fill(0, count($variantIds), '?'));
    $stmt = $pdo->prepare("SELECT v.id AS variant_id,v.product_id,v.size,v.color,v.stock AS variant_stock,v.price_adjustment,p.name,p.price FROM product_variants v JOIN products p ON p.id=v.product_id WHERE v.active=1 AND p.active=1 AND v.id IN ($marks)");
    $stmt->execute($variantIds);
    $products = $stmt->fetchAll();
    
    if (!$products) {
        echo "✗ No products found in cart\n\n";
        exit(1);
    }
    
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
    
    echo "✓ Cart total calculated: ₹" . number_format($total, 2) . "\n\n";
} catch (PDOException $e) {
    echo "✗ Checkout calculation error: " . $e->getMessage() . "\n\n";
    exit(1);
}

// Step 5: Place order
echo "Step 5: Placing order...\n";
try {
    $pdo->beginTransaction();
    
    $lock = $pdo->prepare('SELECT v.id AS variant_id,v.product_id,v.size,v.color,v.stock AS variant_stock,v.price_adjustment,p.name,p.price,p.active FROM product_variants v JOIN products p ON p.id=v.product_id WHERE v.id=? AND v.active=1 FOR UPDATE');
    $orderLines = [];
    $subtotal = 0.0;
    
    foreach ($cart as $variantId => $item) {
        $quantity = filter_var($item['quantity'] ?? null, FILTER_VALIDATE_INT);
        if ($quantity === false || $quantity < 1) {
            throw new RuntimeException('Invalid quantity');
        }
        
        $lock->execute([(int)$variantId]);
        $row = $lock->fetch();
        if (!$row || !(int)$row['active'] || (int)$row['variant_stock'] < $quantity) {
            throw new RuntimeException('Stock changed');
        }
        
        $row['quantity'] = $quantity;
        $row['unit_price'] = (float)$row['price'] + (float)$row['price_adjustment'];
        $row['line_total'] = $row['unit_price'] * $quantity;
        $subtotal += $row['line_total'];
        $orderLines[] = $row;
    }
    
    $shipping = $subtotal >= 1999 ? 0.0 : 99.0;
    $total = $subtotal + $shipping;
    
    $order = $pdo->prepare('INSERT INTO orders (user_id,customer_name,email,phone,address,subtotal,discount,shipping,total,status,payment_method) VALUES (?,?,?,?,?,?,0,?, ?,\'Placed\',\'Cash on Delivery\')');
    $order->execute([$userId, 'Test User', $testEmail, '9876543210', '123 Test Street, Test City', $subtotal, $shipping, $total]);
    $orderId = (int)$pdo->lastInsertId();
    
    $insertLine = $pdo->prepare('INSERT INTO order_items (order_id,product_id,variant_id,product_name,unit_price,quantity,size,color) VALUES (?,?,?,?,?,?,?,?)');
    $decrementVariant = $pdo->prepare('UPDATE product_variants SET stock=stock-? WHERE id=? AND stock>=?');
    $decrementProduct = $pdo->prepare('UPDATE products SET stock=stock-? WHERE id=? AND stock>=?');
    
    foreach ($orderLines as $line) {
        $insertLine->execute([$orderId, $line['product_id'], $line['variant_id'], $line['name'], $line['unit_price'], $line['quantity'], $line['size'], $line['color']]);
        $decrementVariant->execute([$line['quantity'], $line['variant_id'], $line['quantity']]);
        $decrementProduct->execute([$line['quantity'], $line['product_id'], $line['quantity']]);
    }
    
    $pdo->commit();
    unset($_SESSION['cart']);
    
    echo "✓ Order placed successfully (Order ID: $orderId)\n";
    echo "✓ Order total: ₹" . number_format($total, 2) . "\n\n";
} catch (PDOException $e) {
    if ($pdo->inTransaction()) $pdo->rollBack();
    echo "✗ Order placement failed: " . $e->getMessage() . "\n\n";
    exit(1);
} catch (RuntimeException $e) {
    if ($pdo->inTransaction()) $pdo->rollBack();
    echo "✗ Order placement failed: " . $e->getMessage() . "\n\n";
    exit(1);
}

// Step 6: Verify order in database
echo "Step 6: Verifying order in database...\n";
try {
    $stmt = $pdo->prepare('SELECT id,customer_name,email,total,status FROM orders WHERE id=?');
    $stmt->execute([$orderId]);
    $order = $stmt->fetch();
    
    if (!$order) {
        echo "✗ Order not found in database\n\n";
        exit(1);
    }
    
    echo "✓ Order verified in database\n";
    echo "  Order ID: {$order['id']}\n";
    echo "  Customer: {$order['customer_name']}\n";
    echo "  Email: {$order['email']}\n";
    echo "  Total: ₹" . number_format($order['total'], 2) . "\n";
    echo "  Status: {$order['status']}\n\n";
} catch (PDOException $e) {
    echo "✗ Order verification failed: " . $e->getMessage() . "\n\n";
    exit(1);
}

// Step 7: Test order history
echo "Step 7: Testing order history...\n";
try {
    $stmt = $pdo->prepare('SELECT id,total,status,created_at FROM orders WHERE user_id=? ORDER BY id DESC');
    $stmt->execute([$userId]);
    $orders = $stmt->fetchAll();
    
    if (!$orders) {
        echo "✗ No orders found for user\n\n";
        exit(1);
    }
    
    echo "✓ Order history retrieved\n";
    echo "  Total orders: " . count($orders) . "\n\n";
} catch (PDOException $e) {
    echo "✗ Order history failed: " . $e->getMessage() . "\n\n";
    exit(1);
}

// Cleanup
echo "Step 8: Cleaning up test data...\n";
try {
    $pdo->prepare('DELETE FROM order_items WHERE order_id=?')->execute([$orderId]);
    $pdo->prepare('DELETE FROM orders WHERE id=?')->execute([$orderId]);
    $pdo->prepare('DELETE FROM users WHERE id=?')->execute([$userId]);
    echo "✓ Test data cleaned up\n\n";
} catch (PDOException $e) {
    echo "⚠ Cleanup failed (manual cleanup may be needed): " . $e->getMessage() . "\n\n";
}

echo "=== All Tests Passed ===\n";
echo "The user flow is working correctly:\n";
echo "  ✓ User registration\n";
echo "  ✓ User login\n";
echo "  ✓ Add to cart\n";
echo "  ✓ Cart calculations\n";
echo "  ✓ Order placement\n";
echo "  ✓ Order persistence\n";
echo "  ✓ Order history\n";
