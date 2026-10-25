<?php
require __DIR__ . '/config.php';
if ($_SERVER['REQUEST_METHOD'] !== 'POST') { http_response_code(405); exit('Method not allowed'); }
verify_csrf();
$email = strtolower(trim($_POST['email'] ?? ''));
$message = '';
if (!filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($email) > 190) {
    $message = 'Enter a valid email address.';
} else {
    $stmt = $pdo->prepare('INSERT INTO newsletter_subscribers (email,active) VALUES (?,1) ON DUPLICATE KEY UPDATE active=1');
    $stmt->execute([$email]);
    $message = 'You are subscribed. Thanks for joining the FD-Trend letter.';
}
?>
<!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Newsletter | FD-Trend</title><link rel="stylesheet" href="style.css"></head><body><?php require __DIR__ . '/partials/site_header.php'; ?><main class="wrap section"><div class="panel"><p><?= e($message) ?></p><a class="btn secondary" href="index.php">Back to home</a></div></main></body></html>
