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
$notice = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $name = trim($_POST['name'] ?? '');
    $email = strtolower(trim($_POST['email'] ?? ''));
    $password = $_POST['password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';

    if ($name === '' || strlen($name) > 150) {
        $error = 'Please enter your full name (maximum 150 characters).';
    } elseif ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Please enter a valid email address.';
    } elseif (strlen($password) < 6) {
        $error = 'Password must be at least 6 characters long.';
    } elseif ($confirmPassword !== '' && $password !== $confirmPassword) {
        $error = 'The passwords do not match. Please re-enter your password.';
    } else {
        // Pre-check for duplicate email
        $checkStmt = $pdo->prepare('SELECT id FROM users WHERE email=? LIMIT 1');
        $checkStmt->execute([$email]);
        if ($checkStmt->fetchColumn()) {
            $error = 'An account with that email already exists. Please log in instead.';
        } else {
            try {
                $hash = password_hash($password, PASSWORD_DEFAULT);
                $stmt = $pdo->prepare('INSERT INTO users (name, email, password_hash, active) VALUES (?, ?, ?, 1)');
                $stmt->execute([$name, $email, $hash]);

                session_regenerate_id(true);
                unset($_SESSION['admin_id'], $_SESSION['admin_username']);
                $_SESSION['user_id'] = (int)$pdo->lastInsertId();
                $_SESSION['user_name'] = $name;
                $_SESSION['user_email'] = $email;

                merge_customer_cart($pdo, (int)$_SESSION['user_id']);

                $_SESSION['flash'] = 'Account created successfully! Welcome to FD-Trend, ' . htmlspecialchars($name) . '.';
                header('Location: ' . public_path($returnTo));
                exit;
            } catch (PDOException $exception) {
                if ((string)$exception->getCode() === '23000') {
                    $error = 'An account with that email already exists. Please log in.';
                } else {
                    error_log('Registration error: ' . $exception->getMessage());
                    $error = 'Registration could not be completed. Please try again.';
                }
            }
        }
    }
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Customer Registration | FD-Trend</title>
  <meta name="description" content="Create a new customer account at FD-Trend to enjoy personalized recommendations and order tracking.">
  <link rel="stylesheet" href="style.css">
</head>
<body>
<?php require __DIR__ . '/partials/site_header.php'; ?>
<main class="wrap section auth-page">
  <p class="eyebrow">NEW CUSTOMER</p>
  <h1>Create your account</h1>
  <p class="muted">Join FD-Trend to save your favourite fashion pieces, place Cash on Delivery orders, and view complete order tracking.</p>

  <?php if ($error): ?>
    <div class="notice error"><?= e($error) ?></div>
  <?php endif; ?>

  <form method="post" class="panel auth-form" id="customerRegisterForm">
    <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
    <input type="hidden" name="return_to" value="<?= e($returnTo) ?>">

    <div class="field">
      <label for="name">Full Name</label>
      <input id="name" name="name" maxlength="150" required placeholder="e.g. Harleen Kaur" value="<?= e($_POST['name'] ?? '') ?>">
    </div>

    <div class="field">
      <label for="email">Email Address</label>
      <input id="email" type="email" name="email" maxlength="190" required placeholder="name@example.com" value="<?= e($_POST['email'] ?? '') ?>">
    </div>

    <div class="field">
      <label for="password">Create Password (at least 6 characters)</label>
      <input id="password" type="password" name="password" minlength="6" autocomplete="new-password" required placeholder="Create a secure password">
    </div>

    <div class="field">
      <label for="confirm_password">Confirm Password</label>
      <input id="confirm_password" type="password" name="confirm_password" minlength="6" autocomplete="new-password" required placeholder="Confirm your password">
    </div>

    <button class="btn" type="submit" id="submitRegisterBtn">Create Customer Account</button>

    <div class="auth-helper-links" style="margin-top: 18px; border-top: 1px solid var(--line, #e2e8f0); padding-top: 14px;">
      <p class="muted">Already have an account? <a href="login.php?return_to=<?= urlencode($returnTo) ?>"><strong>Log in here &rarr;</strong></a></p>
    </div>
  </form>
</main>
<script src="assets/store.js" defer></script>
</body>
</html>
