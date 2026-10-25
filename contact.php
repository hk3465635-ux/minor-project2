<?php
require __DIR__ . '/config.php';
$error = '';
$success = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $name = trim($_POST['name'] ?? '');
    $email = strtolower(trim($_POST['email'] ?? ''));
    $subject = trim($_POST['subject'] ?? '');
    $message = trim($_POST['message'] ?? '');
    if ($name === '' || strlen($name) > 150 || !filter_var($email, FILTER_VALIDATE_EMAIL) || $subject === '' || strlen($subject) > 180 || $message === '' || strlen($message) > 5000) {
        $error = 'Complete each field with a valid email. Message must be under 5,000 characters.';
    } else {
        $stmt = $pdo->prepare('INSERT INTO contact_messages (name,email,subject,message) VALUES (?,?,?,?)');
        $stmt->execute([$name,$email,$subject,$message]);
        $success = 'Your message has been received.';
    }
}
?>
<!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Contact | FD-Trend</title><link rel="stylesheet" href="style.css"></head><body><?php require __DIR__ . '/partials/site_header.php'; ?><main class="wrap section auth-page"><p class="eyebrow">GET IN TOUCH</p><h1>Contact FD-Trend</h1><?php if ($error): ?><div class="notice error"><?= e($error) ?></div><?php elseif ($success): ?><div class="notice"><?= e($success) ?></div><?php endif; ?><form method="post" class="panel auth-form"><input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>"><div class="field"><label for="name">Name</label><input id="name" name="name" maxlength="150" required></div><div class="field"><label for="email">Email</label><input id="email" type="email" name="email" maxlength="190" required></div><div class="field"><label for="subject">Subject</label><input id="subject" name="subject" maxlength="180" required></div><div class="field"><label for="message">Message</label><textarea id="message" name="message" rows="6" maxlength="5000" required></textarea></div><button class="btn">Send message</button></form></main></body></html>
