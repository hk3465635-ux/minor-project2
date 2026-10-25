<?php
declare(strict_types=1);
require __DIR__ . '/config.php';

// ─── Filters ──────────────────────────────────────────────────────────────────
$categorySlug = trim($_GET['cat'] ?? '');
$status       = trim($_GET['status'] ?? '');
$sort         = trim($_GET['sort'] ?? 'score');
$search       = trim($_GET['q'] ?? '');
$validSorts   = ['score' => 'popularity_score DESC', 'rising' => 'status="Rising" DESC, popularity_score DESC', 'name' => 't.name ASC', 'newest' => 't.created_at DESC'];
$orderBy      = $validSorts[$sort] ?? 'popularity_score DESC';

// ─── Check if trends tables exist ─────────────────────────────────────────────
$tablesExist = false;
try {
    $pdo->query('SELECT 1 FROM trends LIMIT 1');
    $tablesExist = true;
} catch (Throwable) {
    $tablesExist = false;
}

$trendCategories = [];
$selectedCategory = null;
$trends = [];
$totalTrends = 0;
$risingCount = 0;
$stableCount = 0;
$decliningCount = 0;
$topTrend = null;

if ($tablesExist) {
    // Load categories
    $trendCategories = $pdo->query('SELECT id, name, slug, icon, description FROM trend_categories WHERE active=1 ORDER BY sort_order, name')->fetchAll();

    // Load selected category
    if ($categorySlug !== '') {
        $catStmt = $pdo->prepare('SELECT id, name, slug, description, icon FROM trend_categories WHERE slug=? AND active=1 LIMIT 1');
        $catStmt->execute([$categorySlug]);
        $selectedCategory = $catStmt->fetch() ?: null;
    }

    // Build query
    $where  = ['t.active=1'];
    $params = [];

    if ($selectedCategory) {
        $where[]  = 't.trend_category_id=?';
        $params[] = $selectedCategory['id'];
    }
    if ($status !== '' && in_array($status, ['Rising','Stable','Declining'], true)) {
        $where[]  = 't.status=?';
        $params[] = $status;
    }
    if ($search !== '') {
        $where[]  = '(t.name LIKE ? OR t.description LIKE ? OR t.hashtag LIKE ?)';
        $term     = '%' . $search . '%';
        array_push($params, $term, $term, $term);
    }

    $whereSQL = 'WHERE ' . implode(' AND ', $where);
    $sql = "SELECT t.*, tc.name AS category_name, tc.slug AS category_slug, tc.icon AS category_icon
            FROM trends t
            LEFT JOIN trend_categories tc ON tc.id = t.trend_category_id
            {$whereSQL}
            ORDER BY {$orderBy}";
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $trends = $stmt->fetchAll();

    // Stats
    $statsStmt = $pdo->prepare("SELECT
        COUNT(*) AS total,
        SUM(status='Rising') AS rising,
        SUM(status='Stable') AS stable,
        SUM(status='Declining') AS declining
        FROM trends t {$whereSQL}");
    $statsStmt->execute($params);
    $stats = $statsStmt->fetch();
    $totalTrends  = (int)($stats['total']    ?? 0);
    $risingCount  = (int)($stats['rising']   ?? 0);
    $stableCount  = (int)($stats['stable']   ?? 0);
    $decliningCount = (int)($stats['declining'] ?? 0);

    // Top trending
    if ($trends) {
        $topTrend = $trends[0];
    }
}

$pageTitle = $selectedCategory ? (htmlspecialchars($selectedCategory['name']) . ' Trends | FD-Trend') : 'Trending Fashion | FD-Trend';
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title><?= $pageTitle ?></title>
  <meta name="description" content="Explore trending fashion categories: Punjabi fashion, celebrity looks, streetwear, shoes, watches, handbags and upcoming fashion trends.">
  <link rel="stylesheet" href="style.css">
  <style>
    /* ── Trends Page Extras ─────────────────────────────────── */
    .trends-hero { background: linear-gradient(110deg,#1a2b22 0%,#263c32 60%),radial-gradient(ellipse at 80% 10%,rgba(187,52,73,.4) 0,transparent 40%); color:#fff; padding:clamp(28px,6vw,60px); border-radius:10px; margin-bottom:36px; }
    .trends-hero h1 { margin:8px 0 12px; font-size:clamp(28px,5vw,48px); }
    .trends-hero p { color:#c8d8cb; max-width:600px; }
    .trends-stats { display:flex; gap:20px; flex-wrap:wrap; margin-top:24px; }
    .tstat { background:rgba(255,255,255,.08); border:1px solid rgba(255,255,255,.12); border-radius:10px; padding:12px 20px; text-align:center; min-width:100px; }
    .tstat strong { display:block; font-size:26px; font-weight:700; }
    .tstat span { font-size:12px; color:#c0ccc4; }
    /* Category filter bar */
    .cat-filter-bar { display:flex; gap:10px; flex-wrap:wrap; margin-bottom:28px; align-items:center; }
    .cat-chip { display:inline-flex; align-items:center; gap:6px; padding:8px 16px; border:1px solid var(--line); border-radius:30px; background:var(--surface); color:var(--ink); font-size:13px; font-weight:600; cursor:pointer; transition:.2s; white-space:nowrap; }
    .cat-chip:hover, .cat-chip.active { background:var(--accent); color:#fff; border-color:var(--accent); }
    /* Trend Cards */
    .trends-grid { display:grid; grid-template-columns:repeat(auto-fill,minmax(290px,1fr)); gap:22px; }
    .trend-card { background:var(--surface); border:1px solid var(--line); border-radius:10px; overflow:hidden; display:flex; flex-direction:column; transition:box-shadow .2s,transform .2s; }
    .trend-card:hover { box-shadow:0 8px 30px rgba(20,39,31,.12); transform:translateY(-3px); }
    .trend-img { width:100%; height:190px; object-fit:cover; background:#e8ede9; display:flex; align-items:center; justify-content:center; font-size:60px; }
    .trend-img img { width:100%; height:100%; object-fit:cover; }
    .trend-body { padding:18px; flex:1; display:flex; flex-direction:column; gap:8px; }
    .trend-meta { display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:6px; }
    .trend-cat-tag { background:var(--soft-green); color:var(--green); border-radius:20px; padding:3px 10px; font-size:11px; font-weight:700; letter-spacing:.04em; }
    .trend-status { border-radius:20px; padding:3px 10px; font-size:11px; font-weight:700; }
    .trend-status.rising  { background:#fff0f0; color:#c0392b; }
    .trend-status.stable  { background:#f0f5ff; color:#2563eb; }
    .trend-status.declining { background:#fef9e7; color:#b7950b; }
    .trend-name { font-size:17px; font-weight:700; line-height:1.3; color:var(--ink); margin:0; }
    .trend-desc { font-size:13px; color:var(--muted); line-height:1.5; margin:0; }
    .trend-score-row { display:flex; align-items:center; gap:10px; margin-top:4px; }
    .trend-score-bar { flex:1; height:6px; background:#e5e9e3; border-radius:4px; overflow:hidden; }
    .trend-score-fill { height:100%; background:linear-gradient(90deg,var(--green),#a8d5b5); border-radius:4px; }
    .trend-score-num { font-size:13px; font-weight:700; color:var(--green); min-width:34px; }
    .trend-hashtag { font-size:12px; color:var(--accent); font-weight:600; }
    .trend-footer { display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:8px; margin-top:auto; padding-top:10px; border-top:1px solid var(--line); }
    .trend-source { font-size:11px; color:var(--muted); }
    /* Filters row */
    .filter-row { display:flex; gap:10px; flex-wrap:wrap; align-items:flex-end; margin-bottom:24px; }
    .filter-row form { display:flex; gap:8px; flex-wrap:wrap; align-items:flex-end; width:100%; }
    .filter-row input[type=search] { flex:1; min-width:180px; }
    /* Empty state */
    .trends-empty { text-align:center; padding:60px 20px; background:var(--surface); border:1px solid var(--line); border-radius:10px; }
    .trends-empty p { color:var(--muted); max-width:400px; margin:12px auto 24px; }
    /* No tables banner */
    .notice-setup { background:#fef3cd; border:1px solid #ffc107; border-radius:8px; padding:20px; margin:24px 0; }
    .notice-setup h2 { margin:0 0 8px; font-size:18px; }
    @media(max-width:700px) { .cat-filter-bar { gap:6px; } .cat-chip { font-size:12px; padding:7px 12px; } }
  </style>
</head>
<body>
<?php require __DIR__ . '/partials/site_header.php'; ?>
<main class="wrap section">

  <!-- Hero -->
  <div class="trends-hero">
    <p class="pill">FD-TREND &middot; FASHION INTELLIGENCE</p>
    <h1><?= $selectedCategory ? (e($selectedCategory['icon']) . ' ' . e($selectedCategory['name'])) : '🔥 Trending Fashion' ?></h1>
    <p><?= $selectedCategory ? e($selectedCategory['description'] ?? '') : 'Discover what\'s hot across 12 fashion categories — from Punjabi ethnic wear to viral streetwear and upcoming trend forecasts.' ?></p>
    <?php if ($tablesExist): ?>
    <div class="trends-stats">
      <div class="tstat"><strong><?= $totalTrends ?></strong><span>Trends Found</span></div>
      <div class="tstat"><strong><?= $risingCount ?></strong><span>Rising ↑</span></div>
      <div class="tstat"><strong><?= $stableCount ?></strong><span>Stable →</span></div>
      <div class="tstat"><strong><?= $decliningCount ?></strong><span>Declining ↓</span></div>
    </div>
    <?php endif; ?>
  </div>

  <?php if (!$tablesExist): ?>
  <div class="notice-setup">
    <h2>⚠ Trends database tables not yet created</h2>
    <p>Run the migration from the command line to create the trends database tables and seed sample data:</p>
    <code>php migrate_trends.php</code>
    <p style="margin-top:12px">After running the migration, refresh this page to see all trending fashion content.</p>
  </div>
  <?php else: ?>

  <!-- Category Filter Chips -->
  <div class="cat-filter-bar">
    <a class="cat-chip <?= $categorySlug === '' ? 'active' : '' ?>" href="trends.php">All Trends</a>
    <?php foreach ($trendCategories as $cat): ?>
      <a class="cat-chip <?= $categorySlug === $cat['slug'] ? 'active' : '' ?>" href="trends.php?cat=<?= urlencode($cat['slug']) ?>&status=<?= urlencode($status) ?>&sort=<?= urlencode($sort) ?>">
        <?= e($cat['icon']) ?> <?= e($cat['name']) ?>
      </a>
    <?php endforeach; ?>
  </div>

  <!-- Filter Row -->
  <div class="filter-row">
    <form method="get">
      <?php if ($categorySlug): ?><input type="hidden" name="cat" value="<?= e($categorySlug) ?>"><?php endif; ?>
      <input type="search" name="q" value="<?= e($search) ?>" placeholder="Search trends…" id="trendSearch">
      <select name="status" id="statusFilter">
        <option value="">All Statuses</option>
        <option value="Rising" <?= $status === 'Rising' ? 'selected' : '' ?>>↑ Rising</option>
        <option value="Stable" <?= $status === 'Stable' ? 'selected' : '' ?>>→ Stable</option>
        <option value="Declining" <?= $status === 'Declining' ? 'selected' : '' ?>>↓ Declining</option>
      </select>
      <select name="sort" id="sortSelect">
        <option value="score"  <?= $sort === 'score'  ? 'selected' : '' ?>>Sort: Popularity</option>
        <option value="rising" <?= $sort === 'rising' ? 'selected' : '' ?>>Sort: Rising First</option>
        <option value="name"   <?= $sort === 'name'   ? 'selected' : '' ?>>Sort: Name A-Z</option>
        <option value="newest" <?= $sort === 'newest' ? 'selected' : '' ?>>Sort: Newest</option>
      </select>
      <button class="btn" type="submit" id="applyTrendFilters">Apply Filters</button>
      <?php if ($search || $status || $categorySlug): ?>
        <a class="btn secondary" href="trends.php" id="clearTrendFilters">Clear</a>
      <?php endif; ?>
    </form>
  </div>

  <!-- Trend Cards Grid -->
  <?php if ($trends): ?>
  <div class="trends-grid" id="trendsGrid">
    <?php foreach ($trends as $trend):
      $scoreClass = $trend['status'] === 'Rising' ? 'rising' : ($trend['status'] === 'Declining' ? 'declining' : 'stable');
      $scoreArrow = $trend['status'] === 'Rising' ? '↑' : ($trend['status'] === 'Declining' ? '↓' : '→');
      $score = (float)$trend['popularity_score'];
    ?>
    <div class="trend-card" id="trend-<?= (int)$trend['id'] ?>">
      <div class="trend-img">
        <?php if (!empty($trend['image_url'])): ?>
          <img src="<?= e($trend['image_url']) ?>" alt="<?= e($trend['name']) ?>" loading="lazy">
        <?php else: ?>
          <span><?= e($trend['category_icon'] ?? '✨') ?></span>
        <?php endif; ?>
      </div>
      <div class="trend-body">
        <div class="trend-meta">
          <span class="trend-cat-tag"><?= e($trend['category_name'] ?? 'Fashion') ?></span>
          <span class="trend-status <?= $scoreClass ?>"><?= $scoreArrow ?> <?= e($trend['status']) ?></span>
        </div>
        <h2 class="trend-name"><?= e($trend['name']) ?></h2>
        <p class="trend-desc"><?= e(mb_strimwidth($trend['description'] ?? '', 0, 110, '…')) ?></p>
        <?php if (!empty($trend['hashtag'])): ?>
          <span class="trend-hashtag"><?= e($trend['hashtag']) ?></span>
        <?php endif; ?>
        <div class="trend-score-row">
          <div class="trend-score-bar">
            <div class="trend-score-fill" style="width:<?= round($score) ?>%"></div>
          </div>
          <span class="trend-score-num"><?= round($score) ?>%</span>
        </div>
        <div class="trend-footer">
          <span class="trend-source">📡 <?= e($trend['source']) ?> · <?= e(date('d M', strtotime($trend['last_updated_at']))) ?></span>
          <a class="btn small secondary" href="trend-detail.php?id=<?= (int)$trend['id'] ?>" id="viewTrend<?= (int)$trend['id'] ?>">View Details →</a>
        </div>
      </div>
    </div>
    <?php endforeach; ?>
  </div>
  <?php else: ?>
  <div class="trends-empty" id="trendsEmpty">
    <div style="font-size:48px">🔍</div>
    <h2>No trends found</h2>
    <p>Try changing the category, status filter, or search term to find the trends you're looking for.</p>
    <a class="btn" href="trends.php" id="resetTrendFilters">View all trends</a>
  </div>
  <?php endif; ?>

  <?php endif; /* tablesExist */ ?>
</main>

<footer class="site-footer" style="margin-top:60px;padding:28px 0;border-top:1px solid var(--line);">
  <div class="wrap footer-inner">
    <a class="brand" href="index.php">FD<span>-Trend</span></a>
    <p>Fashion Trend Intelligence Platform — Discover, track, and forecast fashion trends.</p>
    <div class="footer-nav">
      <a href="trends.php">Trends</a> &middot;
      <a href="analytics.php">Analytics</a> &middot;
      <a href="fd-shows.php">FD Shows</a> &middot;
      <a href="marketing.php">Blog</a> &middot;
      <a href="shop.php">Shop</a> &middot;
      <a href="contact.php">Contact</a>
    </div>
  </div>
</footer>
<script src="assets/store.js" defer></script>
<script>
  // Auto-submit sort/status filters on change
  document.getElementById('sortSelect')?.addEventListener('change', function() {
    this.closest('form').submit();
  });
  document.getElementById('statusFilter')?.addEventListener('change', function() {
    this.closest('form').submit();
  });
</script>
</body>
</html>
