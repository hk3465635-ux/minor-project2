<?php
require __DIR__ . '/config.php';
if (is_admin()) { header('Location: ' . public_path('admin/panel.php')); exit; }
if (is_logged_in()) { header('Location: ' . public_path('index.php')); exit; }

$userError = '';
$registerError = '';
$adminError = '';
$activePanel = $_POST['form_type'] ?? 'user_login';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $formType = $_POST['form_type'] ?? '';
    if ($formType === 'user_login') {
        if (attempt_user_login($pdo, $_POST['email'] ?? '', $_POST['password'] ?? '')) {
            header('Location: ' . public_path('index.php')); exit;
        }
        $userError = 'Email or password is incorrect.';
    } elseif ($formType === 'user_register') {
        $name = trim($_POST['name'] ?? '');
        $email = strtolower(trim($_POST['email'] ?? ''));
        $password = $_POST['password'] ?? '';
        if ($name === '' || strlen($name) > 150 || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $registerError = 'Enter a valid name and email address.';
        } elseif (strlen($password) < 10) {
            $registerError = 'Use a password with at least 10 characters.';
        } else {
            try {
                $stmt = $pdo->prepare('INSERT INTO users (name,email,password_hash) VALUES (?,?,?)');
                $stmt->execute([$name, $email, password_hash($password, PASSWORD_DEFAULT)]);
                session_regenerate_id(true);
                unset($_SESSION['admin_id'], $_SESSION['admin_username']);
                $_SESSION['user_id'] = (int)$pdo->lastInsertId();
                $_SESSION['user_name'] = $name;
                $_SESSION['user_email'] = $email;
                merge_customer_cart($pdo, (int)$_SESSION['user_id']);
                header('Location: ' . public_path('index.php')); exit;
            } catch (PDOException $exception) {
                if ((string)$exception->getCode() === '23000') $registerError = 'An account with that email already exists.';
                else { error_log('Registration failed: ' . $exception->getMessage()); $registerError = 'Registration could not be completed. Please try again.'; }
            }
        }
    } elseif ($formType === 'admin_login') {
        if (attempt_admin_login($pdo, $_POST['username'] ?? '', $_POST['password'] ?? '')) {
            header('Location: ' . public_path('admin/panel.php')); exit;
        }
        $adminError = 'Invalid admin username or password.';
    }
}

$categories = $pdo->query("SELECT c.name,c.slug,COUNT(DISTINCT p.id) AS product_count FROM categories c LEFT JOIN categories child ON child.parent_id=c.id LEFT JOIN products p ON (p.category_id=c.id OR p.category_id=child.id) AND p.active=1 WHERE c.parent_id IS NULL AND c.active=1 GROUP BY c.id ORDER BY c.sort_order LIMIT 6")->fetchAll();
$heroImage = $pdo->query("SELECT image_url FROM products WHERE active=1 AND image_url IS NOT NULL AND image_url<>'' ORDER BY featured DESC,id DESC LIMIT 1")->fetchColumn() ?: '/uploads/catalog/northline-fleece-pullover.svg';
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="description" content="Welcome to FD-Trend. Sign in as a customer or administrator.">
  <title>Welcome to FD-Trend</title>
  <link rel="stylesheet" href="style.css">
</head>
<body class="welcome-body">
<main class="welcome-shell">
  <div class="welcome-top"><a class="brand" href="welcome.php">FD<span>-Trend</span></a><a class="text-link" href="index.php">Browse the store →</a></div>
  <section class="welcome-hero">
    <div class="welcome-copy">
      <p class="eyebrow">EVERYDAY FASHION · FD-TREND</p>
      <h1>Sign in to shop.<br><em>Or manage the store.</em></h1>
      <p>Choose customer access to register, shop, and track orders, or admin access to manage the catalog, reels, and orders.</p>
    </div>
    <div class="welcome-photo">
      <img src="<?= e($heroImage) ?>" alt="FD-Trend collection" fetchpriority="high">
      <span>THE NEW EVERYDAY</span>
    </div>
  </section>
  <section class="welcome-access" id="access">
    <div class="welcome-access-heading"><p class="eyebrow">YOUR FD-TREND</p><h2>Choose how to continue</h2></div>
    <div class="welcome-options">
      <article class="welcome-option">
        <span class="welcome-index">01 / CUSTOMER</span>
        <h2>User login</h2>
        <p>Registered customers can save a wishlist, place Cash on Delivery orders, and view order history.</p>
        <?php if ($userError): ?><div class="notice error"><?= e($userError) ?></div><?php endif; ?>
        <form method="post" class="welcome-form">
          <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
          <input type="hidden" name="form_type" value="user_login">
          <div class="field"><label for="user_email">Email</label><input id="user_email" type="email" name="email" autocomplete="email" required value="<?= $activePanel==='user_login' ? e($_POST['email'] ?? '') : '' ?>"></div>
          <div class="field"><label for="user_password">Password</label><input id="user_password" type="password" name="password" autocomplete="current-password" required></div>
          <button class="btn">User login</button>
        </form>
        <details class="welcome-register" <?= $registerError || $activePanel==='user_register' ? 'open' : '' ?>>
          <summary>Create a customer account</summary>
          <?php if ($registerError): ?><div class="notice error"><?= e($registerError) ?></div><?php endif; ?>
          <form method="post" class="welcome-form">
            <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
            <input type="hidden" name="form_type" value="user_register">
            <div class="field"><label for="reg_name">Name</label><input id="reg_name" name="name" maxlength="150" required value="<?= $activePanel==='user_register' ? e($_POST['name'] ?? '') : '' ?>"></div>
            <div class="field"><label for="reg_email">Email</label><input id="reg_email" type="email" name="email" maxlength="190" required value="<?= $activePanel==='user_register' ? e($_POST['email'] ?? '') : '' ?>"></div>
            <div class="field"><label for="reg_password">Password (10+ characters)</label><input id="reg_password" type="password" name="password" minlength="10" autocomplete="new-password" required></div>
            <button class="btn secondary">Register</button>
          </form>
        </details>
      </article>
      <article class="welcome-option admin-welcome">
        <span class="welcome-index">02 / STORE TEAM</span>
        <h2>Admin login</h2>
        <p>Administrators manage products, stock, customers, orders, and fashion reels. Customer passwords cannot open this dashboard.</p>
        <?php if ($adminError): ?><div class="notice error"><?= e($adminError) ?></div><?php endif; ?>
        <form method="post" class="welcome-form" action="welcome.php">
          <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
          <input type="hidden" name="form_type" value="admin_login">
          <div class="field"><label for="admin_username">Admin username</label><input id="admin_username" name="username" required autocomplete="username"></div>
          <div class="field"><label for="admin_password">Admin password</label><input id="admin_password" type="password" name="password" required autocomplete="current-password"></div>
          <button class="btn">Admin login</button>
        </form>
      </article>
    </div>
  </section>
  <section class="welcome-categories">
    <div><p class="eyebrow">THE COLLECTION</p><h2>Shop by category</h2></div>
    <div class="category-chips"><?php foreach ($categories as $category): ?><a class="chip" href="category.php?slug=<?= rawurlencode($category['slug']) ?>"><?= e($category['name']) ?><span><?= (int)$category['product_count'] ?></span></a><?php endforeach; ?></div>
    <p class="muted"><a class="text-link" href="index.php">Continue as guest →</a></p>
  </section>
  <footer class="welcome-footer"><span>FD-Trend · Fashion for everyday</span><nav><a href="reels.php">Reels</a><a href="marketing.php">Style journal</a><a href="contact.php">Contact</a></nav></footer>
</main>
<script src="assets/store.js" defer></script>
</body>
</html>
