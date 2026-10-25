<?php
require __DIR__ . '/config.php';

$returnTo = trim($_GET['return_to'] ?? $_POST['return_to'] ?? 'account.php');
if ($returnTo === '' || str_starts_with($returnTo, 'http://') || str_starts_with($returnTo, 'https://') || str_starts_with($returnTo, '//')) {
    $returnTo = 'account.php';
}

if (is_logged_in()) {
    header('Location: ' . public_path($returnTo));
    exit;
}

$error = '';
$flash = $_SESSION['flash'] ?? '';
unset($_SESSION['flash']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Please enter a valid email address.';
    } elseif ($password === '') {
        $error = 'Please enter your account password.';
    } else {
        if (attempt_user_login($pdo, $email, $password)) {
            $_SESSION['flash'] = 'Welcome back, ' . htmlspecialchars($_SESSION['user_name'] ?? 'Customer') . '!';
            header('Location: ' . public_path($returnTo));
            exit;
        }
        $error = 'Invalid email or password. Please verify your credentials or register a new account.';
    }
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Customer Login | FD-Trend</title>
  <meta name="description" content="Sign in to your FD-Trend customer account to track orders and save your wishlist.">
  <link rel="stylesheet" href="style.css">
</head>
<body>
<?php require __DIR__ . '/partials/site_header.php'; ?>
<main class="wrap section auth-page">
  <p class="eyebrow">CUSTOMER PORTAL</p>
  <h1>Customer Login</h1>
  <p class="muted">Access your FD-Trend account to review your order history, manage saved delivery addresses, and track your active purchases.</p>

  <?php if ($flash): ?>
    <div class="notice"><?= e($flash) ?></div>
  <?php endif; ?>

  <?php if ($error): ?>
    <div class="notice error"><?= e($error) ?></div>
  <?php endif; ?>

  <form method="post" class="panel auth-form" id="customerLoginForm">
    <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
    <input type="hidden" name="return_to" value="<?= e($returnTo) ?>">

    <div class="field">
      <label for="email">Email Address</label>
      <input id="email" type="email" name="email" autocomplete="email" required placeholder="name@example.com" value="<?= e($_POST['email'] ?? '') ?>">
    </div>

    <div class="field">
      <label for="password">Password</label>
      <input id="password" type="password" name="password" autocomplete="current-password" required placeholder="Enter your password">
    </div>

    <button class="btn" type="submit" id="submitLoginBtn">Log in to Customer Account</button>

    <div class="auth-helper-links" style="margin-top: 18px; border-top: 1px solid var(--line, #e2e8f0); padding-top: 14px;">
      <p class="muted">New customer? <a href="register.php?return_to=<?= urlencode($returnTo) ?>"><strong>Create an account here &rarr;</strong></a></p>
      <p class="muted" style="font-size: 13px; margin-top: 8px;">Are you a store manager? <a href="admin/login.php"><strong>Dedicated Admin Login &rarr;</strong></a></p>
    </div>
  </form>
</main>
<script src="assets/store.js" defer></script>
</body>
</html>
