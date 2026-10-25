<?php
/**
 * Test Script for Admin Flow
 * This script tests: Admin Login → Add Product → Edit Product → Delete Product → View Orders
 */

require __DIR__ . '/config.php';

echo "=== FD-Trend Admin Flow Test ===\n\n";

// Step 1: Test admin login
echo "Step 1: Testing admin login...\n";
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$_SESSION = []; // Clear session

try {
    $adminUsername = 'admin';
    $adminPassword = 'Admin123456';
    
    $stmt = $pdo->prepare('SELECT id,username,password_hash FROM admins WHERE username=? LIMIT 1');
    $stmt->execute([$adminUsername]);
    $admin = $stmt->fetch();
    
    if (!$admin || !password_verify($adminPassword, $admin['password_hash'])) {
        echo "✗ Admin login failed\n\n";
        exit(1);
    }
    
    if (!headers_sent()) {
        session_regenerate_id(true);
    }
    unset($_SESSION['user_id'], $_SESSION['user_name'], $_SESSION['user_email'], $_SESSION['cart']);
    $_SESSION['admin_id'] = (int)$admin['id'];
    $_SESSION['admin_username'] = $admin['username'];
    
    echo "✓ Admin login successful\n\n";
} catch (PDOException $e) {
    echo "✗ Admin login error: " . $e->getMessage() . "\n\n";
    exit(1);
}

// Step 2: Test adding a product
echo "Step 2: Testing add product...\n";
try {
    // Get a category
    $stmt = $pdo->prepare('SELECT id,name FROM categories WHERE parent_id IS NULL AND active=1 LIMIT 1');
    $stmt->execute();
    $category = $stmt->fetch();
    
    if (!$category) {
        echo "✗ No category found\n\n";
        exit(1);
    }
    
    $testProductName = 'Test Product ' . time();
    $stmt = $pdo->prepare('INSERT INTO products (category_id,category,subcategory,name,brand,description,price,mrp,stock,image_url,emoji,color,material,sizes,featured,active) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)');
    $stmt->execute([
        $category['id'],
        $category['name'],
        '',
        $testProductName,
        'Test Brand',
        'Test description for product',
        999.00,
        1249.00,
        10,
        '/uploads/catalog/test-product.svg',
        '👕',
        'Black',
        'Cotton',
        'S,M,L,XL',
        0,
        1
    ]);
    
    $productId = (int)$pdo->lastInsertId();
    echo "✓ Product added (ID: $productId, Name: $testProductName)\n\n";
} catch (PDOException $e) {
    echo "✗ Add product failed: " . $e->getMessage() . "\n\n";
    exit(1);
}

// Step 3: Test editing product
echo "Step 3: Testing edit product...\n";
try {
    $newPrice = 1499.00;
    $stmt = $pdo->prepare('UPDATE products SET price=? WHERE id=?');
    $stmt->execute([$newPrice, $productId]);
    
    $stmt = $pdo->prepare('SELECT price FROM products WHERE id=?');
    $stmt->execute([$productId]);
    $updatedPrice = $stmt->fetchColumn();
    
    if ((float)$updatedPrice !== $newPrice) {
        echo "✗ Product edit failed\n\n";
        exit(1);
    }
    
    echo "✓ Product edited (Price updated to ₹$newPrice)\n\n";
} catch (PDOException $e) {
    echo "✗ Edit product failed: " . $e->getMessage() . "\n\n";
    exit(1);
}

// Step 4: Test viewing products
echo "Step 4: Testing view products...\n";
try {
    $stmt = $pdo->prepare('SELECT id,name,price,active FROM products WHERE id=?');
    $stmt->execute([$productId]);
    $product = $stmt->fetch();
    
    if (!$product) {
        echo "✗ Product not found\n\n";
        exit(1);
    }
    
    echo "✓ Product retrieved\n";
    echo "  ID: {$product['id']}\n";
    echo "  Name: {$product['name']}\n";
    echo "  Price: ₹" . number_format($product['price'], 2) . "\n";
    echo "  Active: " . ($product['active'] ? 'Yes' : 'No') . "\n\n";
} catch (PDOException $e) {
    echo "✗ View product failed: " . $e->getMessage() . "\n\n";
    exit(1);
}

// Step 5: Test archiving product
echo "Step 5: Testing archive product...\n";
try {
    $stmt = $pdo->prepare('UPDATE products SET active=0 WHERE id=?');
    $stmt->execute([$productId]);
    
    $stmt = $pdo->prepare('SELECT active FROM products WHERE id=?');
    $stmt->execute([$productId]);
    $isActive = $stmt->fetchColumn();
    
    if ((int)$isActive !== 0) {
        echo "✗ Product archive failed\n\n";
        exit(1);
    }
    
    echo "✓ Product archived\n\n";
} catch (PDOException $e) {
    echo "✗ Archive product failed: " . $e->getMessage() . "\n\n";
    exit(1);
}

// Step 6: Test viewing orders
echo "Step 6: Testing view orders...\n";
try {
    $stmt = $pdo->query('SELECT id,customer_name,email,total,status,created_at FROM orders ORDER BY id DESC LIMIT 10');
    $orders = $stmt->fetchAll();
    
    echo "✓ Orders retrieved\n";
    echo "  Total orders: " . count($orders) . "\n";
    if ($orders) {
        foreach (array_slice($orders, 0, 3) as $order) {
            echo "  - Order #{$order['id']}: {$order['customer_name']} - ₹" . number_format($order['total'], 2) . " ({$order['status']})\n";
        }
    }
    echo "\n";
} catch (PDOException $e) {
    echo "✗ View orders failed: " . $e->getMessage() . "\n\n";
    exit(1);
}

// Step 7: Test viewing customers
echo "Step 7: Testing view customers...\n";
try {
    $stmt = $pdo->query('SELECT u.id,u.name,u.email,u.created_at,COUNT(o.id) AS order_count FROM users u LEFT JOIN orders o ON o.user_id=u.id GROUP BY u.id ORDER BY u.created_at DESC LIMIT 10');
    $customers = $stmt->fetchAll();
    
    echo "✓ Customers retrieved\n";
    echo "  Total customers: " . count($customers) . "\n";
    if ($customers) {
        foreach (array_slice($customers, 0, 3) as $customer) {
            echo "  - {$customer['name']} ({$customer['email']}) - {$customer['order_count']} orders\n";
        }
    }
    echo "\n";
} catch (PDOException $e) {
    echo "✗ View customers failed: " . $e->getMessage() . "\n\n";
    exit(1);
}

// Step 8: Test viewing statistics
echo "Step 8: Testing view statistics...\n";
try {
    $productCount = (int)$pdo->query('SELECT COUNT(*) FROM products WHERE active=1')->fetchColumn();
    $categoryCount = (int)$pdo->query('SELECT COUNT(*) FROM categories WHERE active=1')->fetchColumn();
    $customerCount = (int)$pdo->query('SELECT COUNT(*) FROM users WHERE active=1')->fetchColumn();
    $orderCount = (int)$pdo->query('SELECT COUNT(*) FROM orders')->fetchColumn();
    $sales = (float)$pdo->query("SELECT COALESCE(SUM(total),0) FROM orders WHERE status<>'Cancelled'")->fetchColumn();
    
    echo "✓ Statistics retrieved\n";
    echo "  Active products: $productCount\n";
    echo "  Active categories: $categoryCount\n";
    echo "  Customers: $customerCount\n";
    echo "  Orders: $orderCount\n";
    echo "  Total sales: ₹" . number_format($sales, 2) . "\n\n";
} catch (PDOException $e) {
    echo "✗ Statistics failed: " . $e->getMessage() . "\n\n";
    exit(1);
}

// Cleanup
echo "Step 9: Cleaning up test data...\n";
try {
    $pdo->prepare('DELETE FROM products WHERE id=?')->execute([$productId]);
    echo "✓ Test product deleted\n\n";
} catch (PDOException $e) {
    echo "⚠ Cleanup failed: " . $e->getMessage() . "\n\n";
}

echo "=== All Admin Tests Passed ===\n";
echo "The admin flow is working correctly:\n";
echo "  ✓ Admin login\n";
echo "  ✓ Add product\n";
echo "  ✓ Edit product\n";
echo "  ✓ Archive product\n";
echo "  ✓ View products\n";
echo "  ✓ View orders\n";
echo "  ✓ View customers\n";
echo "  ✓ View statistics\n";
