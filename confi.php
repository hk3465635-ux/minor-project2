<?php
declare(strict_types=1);

if (session_status() !== PHP_SESSION_ACTIVE) {
    $isHttps = !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off';
    session_set_cookie_params([
        'lifetime' => 0,
        'path' => '/',
        'secure' => $isHttps,
        'httponly' => true,
        'samesite' => 'Lax',
    ]);
    ini_set('session.use_strict_mode', '1');
    session_start();
}

/*
  Update these values to match your local MySQL setup.
  Common XAMPP/MAMP local defaults are host=127.0.0.1, user=root.
  Add your MySQL password if you have set one.
*/
$localSettings = is_readable(__DIR__ . '/.env')
    ? (parse_ini_file(__DIR__ . '/.env', false, INI_SCANNER_RAW) ?: [])
    : [];
$setting = static fn(string $name, string $default = ''): string =>
    (string)(getenv($name) ?: ($localSettings[$name] ?? $default));

$dbHost = $setting('FD_DB_HOST', '127.0.0.1');
$dbName = $setting('FD_DB_NAME', 'fd_trend_shop');
$dbUser = $setting('FD_DB_USER');
$dbPass = $setting('FD_DB_PASSWORD');

try {
    $pdo = new PDO(
        "mysql:host={$dbHost};dbname={$dbName};charset=utf8mb4",
        $dbUser,
        $dbPass,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]
    );
} catch (PDOException $e) {
    error_log('FD-Trend database connection failed: ' . $e->getMessage());
    http_response_code(500);
    exit('Database connection failed. Check the local database configuration and ensure MariaDB/MySQL is running.');
}

function e(?string $value): string {
    return htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8');
}
function text_preview(string $text, int $maxCharacters = 95): string {
    if (function_exists('mb_strimwidth')) {
        return mb_strimwidth($text, 0, $maxCharacters, '…');
    }
    if (preg_match('/^(.{0,' . $maxCharacters . '})./us', $text, $matches)) {
        return $matches[1] . '…';
    }
    return $text;
}
function money(float|int|string $amount): string {
    return '₹' . number_format((float)$amount, 2);
}
function csrf_token(): string {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}
function verify_csrf(): void {
    if (!hash_equals($_SESSION['csrf_token'] ?? '', $_POST['csrf_token'] ?? '')) {
        http_response_code(419);
        exit('Session token expired. Please go back and try again.');
    }
}
function cart_count(): int {
    $cart = $_SESSION['cart'] ?? [];
    return array_sum(array_map(static fn($item): int => (int)($item['quantity'] ?? 0), $cart));
}
function public_path(string $path = ''): string {
    $scriptName = str_replace('\\', '/', (string)($_SERVER['SCRIPT_NAME'] ?? '/index.php'));
    $dir = rtrim(dirname($scriptName), '/');
    if (basename($dir) === 'admin') {
        $dir = rtrim(dirname($dir), '/');
    }
    $base = ($dir === '' || $dir === '/') ? '' : $dir;
    return $base . '/' . ltrim($path, '/');
}
function is_logged_in(): bool {
    return !empty($_SESSION['user_id']);
}
function require_login(): void {
    if (!is_logged_in()) {
        $uri = $_SERVER['REQUEST_URI'] ?? '';
        $redirectParam = $uri !== '' ? '?return_to=' . urlencode($uri) : '';
        header('Location: ' . public_path('login.php' . $redirectParam));
        exit;
    }
}
function current_user(): ?array {
    if (!is_logged_in()) {
        return null;
    }
    return [
        'id' => (int)$_SESSION['user_id'],
        'name' => (string)($_SESSION['user_name'] ?? ''),
        'email' => (string)($_SESSION['user_email'] ?? ''),
    ];
}
function persist_cart(PDO $pdo): void {
    if (!is_logged_in()) {
        return;
    }
    $userId = (int)$_SESSION['user_id'];
    $pdo->prepare('DELETE FROM cart_items WHERE user_id=?')->execute([$userId]);
    $insert = $pdo->prepare('INSERT INTO cart_items (user_id,variant_id,quantity) SELECT ?,v.id,LEAST(?,v.stock) FROM product_variants v JOIN products p ON p.id=v.product_id WHERE v.id=? AND v.active=1 AND p.active=1 AND v.stock>0');
    foreach ($_SESSION['cart'] ?? [] as $variantId => $item) {
        $quantity = max(1, (int)($item['quantity'] ?? 1));
        $insert->execute([$userId, $quantity, (int)$variantId]);
    }
}
function restore_cart(PDO $pdo): void {
    if (!is_logged_in() || !empty($_SESSION['cart'])) {
        return;
    }
    $query = $pdo->prepare('SELECT c.variant_id,c.quantity,v.product_id,v.size,v.color,v.stock AS available_stock FROM cart_items c JOIN product_variants v ON v.id=c.variant_id JOIN products p ON p.id=v.product_id WHERE c.user_id=? AND v.active=1 AND v.stock>0 AND p.active=1');
    $query->execute([(int)$_SESSION['user_id']]);
    foreach ($query->fetchAll() as $item) {
        $_SESSION['cart'][(int)$item['variant_id']] = [
            'variant_id' => (int)$item['variant_id'],
            'product_id' => (int)$item['product_id'],
            'quantity' => min((int)$item['quantity'], (int)$item['available_stock']),
            'size' => $item['size'],
            'color' => $item['color'],
        ];
    }
}
function merge_customer_cart(PDO $pdo, int $userId): void {
    $merged = [];
    $saved = $pdo->prepare('SELECT c.variant_id,c.quantity FROM cart_items c JOIN product_variants v ON v.id=c.variant_id WHERE c.user_id=? AND v.active=1');
    $saved->execute([$userId]);
    foreach ($saved->fetchAll() as $item) {
        $merged[(int)$item['variant_id']] = ['quantity' => (int)$item['quantity']];
    }

    $legacyVariant = $pdo->prepare('SELECT v.id FROM product_variants v WHERE v.product_id=? AND v.size=? AND (?="" OR v.color=?) AND v.active=1 ORDER BY v.id LIMIT 1');
    $variantStock = $pdo->prepare('SELECT stock FROM product_variants WHERE id=? AND active=1');
    foreach ($_SESSION['cart'] ?? [] as $key => $item) {
        $variantId = (int)($item['variant_id'] ?? 0);
        if ($variantId < 1) {
            $productId = (int)($item['product_id'] ?? $key);
            $size = trim((string)($item['size'] ?? ''));
            $color = trim((string)($item['color'] ?? ''));
            $legacyVariant->execute([$productId, $size, $color, $color]);
            $variantId = (int)($legacyVariant->fetchColumn() ?: 0);
        }
        if ($variantId < 1) continue;
        $variantStock->execute([$variantId]);
        $stock = (int)($variantStock->fetchColumn() ?: 0);
        if ($stock < 1) continue;
        $existing = (int)($merged[$variantId]['quantity'] ?? 0);
        $merged[$variantId] = ['quantity' => min($stock, $existing + max(1, (int)($item['quantity'] ?? 1)))];
    }

    $_SESSION['cart'] = $merged;
    persist_cart($pdo);
}
function is_admin(): bool {
    return !empty($_SESSION['admin_id']);
}
function require_admin(): void {
    if (!is_admin()) {
        header('Location: ' . public_path('admin/login.php'));
        exit;
    }
}
function attempt_user_login(PDO $pdo, string $email, string $password): bool {
    $email = strtolower(trim($email));
    $stmt = $pdo->prepare('SELECT id,name,email,password_hash FROM users WHERE email=? AND active=1 LIMIT 1');
    $stmt->execute([$email]);
    $user = $stmt->fetch();
    if (!$user || !password_verify($password, $user['password_hash'])) {
        return false;
    }
    session_regenerate_id(true);
    unset($_SESSION['admin_id'], $_SESSION['admin_username']);
    $_SESSION['user_id'] = (int)$user['id'];
    $_SESSION['user_name'] = $user['name'];
    $_SESSION['user_email'] = $user['email'];
    merge_customer_cart($pdo, (int)$user['id']);
    return true;
}
function attempt_admin_login(PDO $pdo, string $username, string $password): bool {
    $username = trim($username);
    $stmt = $pdo->prepare('SELECT id,username,password_hash FROM admins WHERE username=? LIMIT 1');
    $stmt->execute([$username]);
    $admin = $stmt->fetch();
    if (!$admin || !password_verify($password, $admin['password_hash'])) {
        return false;
    }
    session_regenerate_id(true);
    unset($_SESSION['user_id'], $_SESSION['user_name'], $_SESSION['user_email'], $_SESSION['cart']);
    $_SESSION['admin_id'] = (int)$admin['id'];
    $_SESSION['admin_username'] = $admin['username'];
    return true;
}
function store_uploaded_media(array $file, string $directory, array $allowedMime, int $maxBytes): ?string {
    if (($file['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_NO_FILE) {
        return null;
    }
    if (($file['error'] ?? UPLOAD_ERR_OK) !== UPLOAD_ERR_OK || ($file['size'] ?? 0) > $maxBytes) {
        throw new RuntimeException('The upload failed or is larger than the allowed size.');
    }
    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $mime = $finfo->file($file['tmp_name'] ?? '') ?: '';
    if (!isset($allowedMime[$mime])) {
        throw new RuntimeException('That file type is not allowed.');
    }
    if (!is_dir($directory) && !mkdir($directory, 0755, true) && !is_dir($directory)) {
        throw new RuntimeException('Could not create the upload directory.');
    }
    $name = bin2hex(random_bytes(18)) . '.' . $allowedMime[$mime];
    if (!move_uploaded_file($file['tmp_name'], $directory . '/' . $name)) {
        throw new RuntimeException('Could not save the uploaded file.');
    }
    return basename($directory) === 'products'
        ? '/uploads/products/' . $name
        : '/uploads/' . basename($directory) . '/' . $name;
}

if (is_logged_in()) {
    restore_cart($pdo);
}
