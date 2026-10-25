<?php require __DIR__ . '/config.php'; ?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>About FD-Trend | Fashion Trend Intelligence Platform</title>
  <meta name="description" content="About FD-Trend — a fashion trend intelligence platform tracking Punjabi fashion, celebrity looks, streetwear, FD shows, and future fashion predictions.">
  <link rel="stylesheet" href="style.css">
  <style>
    .about-hero { background:linear-gradient(110deg,#1a2b22,#263c32); color:#fff; padding:clamp(28px,6vw,64px); border-radius:10px; margin-bottom:36px; }
    .about-hero h1 { margin:10px 0 14px; font-size:clamp(28px,5vw,48px); }
    .about-hero p { color:#c8d8cb; max-width:600px; font-size:17px; }
    .feature-grid { display:grid; grid-template-columns:repeat(auto-fill,minmax(250px,1fr)); gap:20px; margin:28px 0; }
    .feature-card { background:var(--surface); border:1px solid var(--line); border-radius:10px; padding:24px; }
    .feature-card .icon { font-size:36px; margin-bottom:12px; }
    .feature-card h3 { margin:0 0 8px; }
    .feature-card p { color:var(--muted); font-size:13px; margin:0; }
    .team-section { background:var(--surface); border:1px solid var(--line); border-radius:10px; padding:32px; margin:28px 0; }
    .stat-row { display:flex; gap:20px; flex-wrap:wrap; margin:24px 0; }
    .stat-box { text-align:center; padding:16px 24px; background:var(--soft-green); border-radius:10px; }
    .stat-box strong { display:block; font-size:28px; font-weight:800; color:var(--green); }
    .stat-box span { font-size:12px; color:var(--muted); }
  </style>
</head>
<body>
<?php require __DIR__ . '/partials/site_header.php'; ?>
<main class="wrap section about-page">

  <div class="about-hero">
    <p class="pill">FD-TREND &middot; ABOUT US</p>
    <h1>Fashion Trend Intelligence</h1>
    <p>FD-Trend is your complete fashion intelligence platform — combining real-time trend tracking, FD show highlights, celebrity fashion, and social-media-inspired style predictions into one experience.</p>
  </div>

  <section>
    <p class="eyebrow">WHAT WE DO</p>
    <h2>Your fashion radar, always on.</h2>
    <p>FD-Trend tracks trending outfits, Punjabi fashion, celebrity looks, viral streetwear styles, shoes, watches, handbags, accessories — and uses historical data to forecast what's coming next.</p>
  </section>

  <?php
  // Live stats from database
  $productCount = 0; $trendCount = 0; $showCount = 0; $catCount = 0;
  try { $productCount = (int)$pdo->query('SELECT COUNT(*) FROM products WHERE active=1')->fetchColumn(); } catch (Throwable) {}
  try { $trendCount   = (int)$pdo->query('SELECT COUNT(*) FROM trends WHERE active=1')->fetchColumn(); } catch (Throwable) {}
  try { $showCount    = (int)$pdo->query('SELECT COUNT(*) FROM fd_shows WHERE published=1')->fetchColumn(); } catch (Throwable) {}
  try { $catCount     = (int)$pdo->query('SELECT COUNT(*) FROM trend_categories WHERE active=1')->fetchColumn(); } catch (Throwable) {}
  ?>
  <div class="stat-row">
    <div class="stat-box"><strong><?= $productCount ?></strong><span>Fashion Products</span></div>
    <div class="stat-box"><strong><?= $trendCount ?: '20+' ?></strong><span>Trends Tracked</span></div>
    <div class="stat-box"><strong><?= $showCount ?: '5+' ?></strong><span>FD Shows</span></div>
    <div class="stat-box"><strong><?= $catCount ?: '12' ?></strong><span>Trend Categories</span></div>
  </div>

  <h2>Platform Features</h2>
  <div class="feature-grid">
    <div class="feature-card">
      <div class="icon">🔥</div>
      <h3>Trend Tracking</h3>
      <p>Track 12 fashion categories with real-time popularity scores, rising/stable/declining status, and 30-day history charts.</p>
    </div>
    <div class="feature-card">
      <div class="icon">🎬</div>
      <h3>FD Shows</h3>
      <p>Exclusive coverage of FD show episodes including fashion highlights, featured outfits, and show-related trend analysis.</p>
    </div>
    <div class="feature-card">
      <div class="icon">📊</div>
      <h3>Analytics Dashboard</h3>
      <p>Global trend index, category comparisons, rising trend detection, and 14-day global popularity charts.</p>
    </div>
    <div class="feature-card">
      <div class="icon">🔮</div>
      <h3>Trend Forecasting</h3>
      <p>7, 14, and 30-day trend forecasts based on historical popularity data with clear explanation of predictions.</p>
    </div>
    <div class="feature-card">
      <div class="icon">🛍️</div>
      <h3>Fashion Shop</h3>
      <p>Browse 13 fashion categories with real product photos, size selection, wishlist, and Cash on Delivery orders.</p>
    </div>
    <div class="feature-card">
      <div class="icon">📝</div>
      <h3>Fashion Blog</h3>
      <p>Editorial articles, styling guides, Punjabi fashion updates, celebrity looks, and seasonal fashion stories.</p>
    </div>
  </div>

  <div class="team-section">
    <h2 style="margin-top:0">Our Approach to Data</h2>
    <p>FD-Trend uses administrator-curated fashion content supplemented by historical trend scoring. All charts and forecasts use data stored in our database. We clearly label sample data and do not claim guaranteed predictions or invented statistics as real.</p>
    <p>Trend forecasts are calculated using linear extrapolation from recorded historical scores and are presented as indicative estimates, not guaranteed outcomes. Confidence improves as more historical data accumulates.</p>
    <div style="margin-top:16px">
      <a class="btn" href="trends.php">Explore Trends</a>
      <a class="btn secondary" href="analytics.php" style="margin-left:10px">View Analytics</a>
    </div>
  </div>

  <div class="panel">
    <h2 style="margin-top:0">Contact & Connect</h2>
    <p>Have a fashion tip, want to suggest a trend, or need to get in touch with the FD-Trend team?</p>
    <a class="btn" href="contact.php">Contact Us</a>
    <a class="btn secondary" href="marketing.php" style="margin-left:10px">Read the Blog</a>
  </div>

</main>
<footer class="site-footer" style="margin-top:60px;padding:28px 0;border-top:1px solid var(--line);">
  <div class="wrap footer-inner">
    <a class="brand" href="index.php">FD<span>-Trend</span></a>
    <p>Fashion Trend Intelligence Platform</p>
    <div class="footer-nav">
      <a href="trends.php">Trends</a> &middot;
      <a href="fd-shows.php">FD Shows</a> &middot;
      <a href="analytics.php">Analytics</a> &middot;
      <a href="marketing.php">Blog</a> &middot;
      <a href="shop.php">Shop</a> &middot;
      <a href="about.php">About</a> &middot;
      <a href="contact.php">Contact</a>
    </div>
  </div>
</footer>
<script src="assets/store.js" defer></script>
</body>
</html>
