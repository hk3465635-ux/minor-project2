<?php
session_start();
$path = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
if (preg_match('~(^|/)\.(?!well-known/)~', $path)
    || preg_match('~\.(env|sql)$~i', $path)
    || preg_match('~^/uploads/.*\.(php[0-9]?|phtml|phar)$~i', $path)) {
    http_response_code(404);
    exit;
}
if ($path === '/') {
    // If no one is logged in, show welcome.php
    // If user is logged in, redirect to index.php
    // If admin is logged in, redirect to admin panel
    if (!empty($_SESSION['admin_id'])) {
        header('Location: admin/panel.php');
        exit;
    } elseif (!empty($_SESSION['user_id'])) {
        header('Location: index.php');
        exit;
    } else {
        require __DIR__ . '/welcome.php';
        exit;
    }
}
if (is_file(__DIR__ . $path)) {
    return false;
}
http_response_code(404);
echo 'Not found';
