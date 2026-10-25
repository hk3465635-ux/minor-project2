<?php
declare(strict_types=1);
require __DIR__ . '/config.php';

// Check tables
$tablesExist = false;
try { $pdo->query('SELECT 1 FROM fd_shows LIMIT 1'); $tablesExist = true; } catch (Throwable) {}

$shows = [];
$featuredShows = [];
$categories = [];
$singleShow = null;
$showOutfits = [];
$search = trim($_GET['q'] ?? '');
$category = trim($_GET['cat'] ?? '');
$showId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT) ?: 0;

if ($tablesExist) {
    // Load single show
    if ($showId > 0) {
        $stmt = $pdo->prepare('SELECT * FROM fd_shows WHERE id=? AND published=1 LIMIT 1');
        $stmt->execute([$showId]);
        $singleShow = $stmt->fetch() ?: null;

        if ($singleShow) {
            $outfitStmt = $pdo->prepare('SELECT fo.*, p.name AS product_name, p.price, p.image_url AS product_image FROM fd_show_outfits fo LEFT JOIN products p ON p.id=fo.related_product_id WHERE fo.show_id=? ORDER BY fo.sort_order, fo.id');
            $outfitStmt->execute([$showId]);
            $showOutfits = $outfitStmt->fetchAll();
        }
    } else {
        // List all shows
        $categories = $pdo->query("SELECT DISTINCT category FROM fd_shows WHERE published=1 ORDER BY category")->fetchAll(PDO::FETCH_COLUMN);
        $featuredShows = $pdo->query('SELECT * FROM fd_shows WHERE published=1 AND featured=1 ORDER BY air_date DESC, id DESC LIMIT 3')->fetchAll();

        $where = ['published=1'];
        $params = [];
        if ($search !== '') {
            $where[] = '(title LIKE ? OR description LIKE ? OR featured_designer LIKE ?)';
            $term = '%' . $search . '%';
            array_push($params, $term, $term, $term);
        }
        if ($category !== '') {
            $where[] = 'category=?';
            $params[] = $category;
        }
        $stmt = $pdo->prepare('SELECT * FROM fd_shows WHERE ' . implode(' AND ', $where) . ' ORDER BY featured DESC, air_date DESC, id DESC');
        $stmt->execute($params);
        $shows = $stmt->fetchAll();
    }
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title><?= $singleShow ? (e($singleShow['title']) . ' | FD Shows') : 'FD Shows | FD-Trend' ?></title>
  <meta name="description" content="<?= $singleShow ? e(mb_strimwidth($singleShow['description'] ?? '', 0, 155, '…')) : 'FD Shows — fashion highlights, featured outfits, and trend analysis from FD show episodes.' ?>">
  <link rel="stylesheet" href="style.css">
  <style>
    .fdshows-hero { background:linear-gradient(110deg,#1a2b22,#263c32); color:#fff; padding:clamp(24px,5vw,52px); border-radius:10px; margin-bottom:32px; }
    .fdshows-hero h1 { margin:10px 0 12px; font-size:clamp(26px,5vw,44px); }
    .fdshows-hero p { color:#c8d8cb; max-width:580px; }
    .featured-shows-grid { display:grid; grid-template-columns:repeat(auto-fill,minmax(300px,1fr)); gap:20px; margin-bottom:32px; }
    .show-card { background:var(--surface); border:1px solid var(--line); border-radius:10px; overflow:hidden; display:flex; flex-direction:column; transition:box-shadow .2s,transform .2s; }
    .show-card:hover { box-shadow:0 8px 28px rgba(20,39,31,.12); transform:translateY(-3px); }
    .show-card-img { height:210px; background:linear-gradient(135deg,#263c32,#1a2b22); display:flex; align-items:center; justify-content:center; font-size:64px; position:relative; }
    .show-card-img img { width:100%; height:100%; object-fit:cover; }
    .show-featured-badge { position:absolute; top:12px; right:12px; background:var(--accent); color:#fff; border-radius:20px; padding:3px 10px; font-size:11px; font-weight:700; }
    .show-card-body { padding:18px; flex:1; display:flex; flex-direction:column; gap:8px; }
    .show-cat-tag { background:var(--soft-green); color:var(--green); border-radius:20px; padding:3px 10px; font-size:11px; font-weight:700; display:inline-block; }
    .show-card-body h2 { margin:0; font-size:17px; line-height:1.3; }
    .show-meta { font-size:12px; color:var(--muted); display:flex; flex-wrap:wrap; gap:10px; }
    .show-meta span { display:flex; align-items:center; gap:4px; }
    .show-card-body p { font-size:13px; color:var(--muted); line-height:1.5; margin:0; }
    .show-card-footer { margin-top:auto; padding-top:12px; border-top:1px solid var(--line); display:flex; justify-content:space-between; align-items:center; }
    /* Outfit gallery */
    .outfit-gallery { display:grid; grid-template-columns:repeat(auto-fill,minmax(220px,1fr)); gap:18px; }
    .outfit-card { background:var(--surface); border:1px solid var(--line); border-radius:8px; overflow:hidden; }
    .outfit-card-img { height:180px; background:#e8ede9; display:flex; align-items:center; justify-content:center; font-size:48px; }
    .outfit-card-img img { width:100%; height:100%; object-fit:cover; }
    .outfit-card-body { padding:14px; }
    .outfit-card-body h3 { margin:0 0 6px; font-size:15px; }
    /* Filter bar */
    .filter-row { display:flex; gap:10px; flex-wrap:wrap; margin-bottom:24px; }
    .filter-row form { display:flex; gap:8px; flex-wrap:wrap; width:100%; }
    .notice-setup { background:#fef3cd; border:1px solid #ffc107; border-radius:8px; padding:20px; margin:24px 0; }
    .show-detail-meta { display:flex; gap:20px; flex-wrap:wrap; margin-top:14px; font-size:13px; color:#c8d8cb; }
  </style>
</head>
<body>
<?php require __DIR__ . '/partials/site_header.php'; ?>
<main class="wrap section">

  <?php if (!$tablesExist): ?>
    <div class="fdshows-hero">
      <p class="pill">FD-TREND &middot; FD SHOWS</p>
      <h1>🎬 FD Shows</h1>
    </div>
    <div class="notice-setup">
      <h2>⚠ FD Shows database tables not found</h2>
      <p>Run the migration from the command line to create the FD Shows tables and seed sample data:</p>
      <code>php migrate_trends.php</code>
    </div>

  <?php elseif ($singleShow): ?>
    <!-- Single Show Detail -->
    <p><a class="text-link" href="fd-shows.php">← Back to FD Shows</a></p>
    <div class="fdshows-hero">
      <span class="show-cat-tag" style="margin-bottom:10px;display:inline-block"><?= e($singleShow['category']) ?></span>
      <h1>🎬 <?= e($singleShow['title']) ?></h1>
      <?php if (!empty($singleShow['season'])): ?>
        <p style="color:#a8d5b5;font-weight:700"><?= e($singleShow['season']) ?><?= $singleShow['episode_number'] ? ' · Episode ' . (int)$singleShow['episode_number'] : '' ?></p>
      <?php endif; ?>
      <div class="show-detail-meta">
        <?php if (!empty($singleShow['air_date'])): ?>
          <span>📅 <?= date('d M Y', strtotime($singleShow['air_date'])) ?></span>
        <?php endif; ?>
        <?php if (!empty($singleShow['location'])): ?>
          <span>📍 <?= e($singleShow['location']) ?></span>
        <?php endif; ?>
        <?php if (!empty($singleShow['featured_designer'])): ?>
          <span>✨ <?= e($singleShow['featured_designer']) ?></span>
        <?php endif; ?>
      </div>
    </div>

    <?php if (!empty($singleShow['image_url'])): ?>
      <div style="margin-bottom:24px;border-radius:10px;overflow:hidden;max-height:480px;">
        <img src="<?= e($singleShow['image_url']) ?>" alt="<?= e($singleShow['title']) ?>" style="width:100%;object-fit:cover;">
      </div>
    <?php endif; ?>

    <div class="panel">
      <h2 style="margin-top:0">About This Show</h2>
      <p><?= nl2br(e($singleShow['description'] ?? '')) ?></p>
    </div>

    <!-- Show Outfits / Looks -->
    <?php if ($showOutfits): ?>
    <section>
      <h2>🧥 Featured Outfits & Looks</h2>
      <div class="outfit-gallery">
        <?php foreach ($showOutfits as $outfit): ?>
        <div class="outfit-card">
          <div class="outfit-card-img">
            <?php if (!empty($outfit['image_url'])): ?>
              <img src="<?= e($outfit['image_url']) ?>" alt="<?= e($outfit['look_title']) ?>" loading="lazy">
            <?php else: ?>
              <span>👗</span>
            <?php endif; ?>
          </div>
          <div class="outfit-card-body">
            <h3><?= e($outfit['look_title']) ?></h3>
            <?php if (!empty($outfit['description'])): ?>
              <p style="font-size:12px;color:var(--muted)"><?= e(mb_strimwidth($outfit['description'], 0, 90, '…')) ?></p>
            <?php endif; ?>
            <?php if ($outfit['related_product_id'] && !empty($outfit['product_name'])): ?>
              <a class="btn small secondary" href="product.php?id=<?= (int)$outfit['related_product_id'] ?>" style="margin-top:8px">Shop <?= e($outfit['product_name']) ?> →</a>
            <?php endif; ?>
          </div>
        </div>
        <?php endforeach; ?>
      </div>
    </section>
    <?php else: ?>
    <div class="panel">
      <p class="muted">No featured outfits added for this show yet. Check back soon or browse other shows.</p>
    </div>
    <?php endif; ?>

    <div style="margin-top:24px">
      <a class="btn" href="trends.php?cat=fd-shows">View FD Show Trends</a>
      <a class="btn secondary" href="fd-shows.php" style="margin-left:10px">All FD Shows</a>
    </div>

  <?php else: ?>
    <!-- Shows List -->
    <div class="fdshows-hero">
      <p class="pill">FD-TREND &middot; FD SHOWS</p>
      <h1>🎬 FD Shows</h1>
      <p>Explore fashion highlights, runway looks, featured outfits, and trend analysis from FD show episodes and fashion events.</p>
      <p style="font-size:12px;color:#a8d5b5;margin-top:8px">📌 Show content is curated and managed by the FD-Trend editorial team.</p>
    </div>

    <!-- Featured Shows -->
    <?php if ($featuredShows): ?>
    <h2>⭐ Featured Shows</h2>
    <div class="featured-shows-grid">
      <?php foreach ($featuredShows as $show): ?>
      <div class="show-card">
        <div class="show-card-img">
          <?php if (!empty($show['image_url'])): ?>
            <img src="<?= e($show['image_url']) ?>" alt="<?= e($show['title']) ?>" loading="lazy">
          <?php else: ?>
            <span>🎬</span>
          <?php endif; ?>
          <?php if ($show['featured']): ?><span class="show-featured-badge">⭐ Featured</span><?php endif; ?>
        </div>
        <div class="show-card-body">
          <span class="show-cat-tag"><?= e($show['category']) ?></span>
          <h2><a href="fd-shows.php?id=<?= (int)$show['id'] ?>"><?= e($show['title']) ?></a></h2>
          <div class="show-meta">
            <?php if ($show['air_date']): ?><span>📅 <?= date('d M Y', strtotime($show['air_date'])) ?></span><?php endif; ?>
            <?php if ($show['location']): ?><span>📍 <?= e($show['location']) ?></span><?php endif; ?>
          </div>
          <p><?= e(mb_strimwidth($show['description'] ?? '', 0, 100, '…')) ?></p>
          <div class="show-card-footer">
            <span style="font-size:12px;color:var(--muted)"><?= e($show['featured_designer'] ?? '') ?></span>
            <a class="btn small" href="fd-shows.php?id=<?= (int)$show['id'] ?>" id="showDetails<?= (int)$show['id'] ?>">View Details →</a>
          </div>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
    <?php endif; ?>

    <!-- Filter & Search -->
    <div class="filter-row">
      <form method="get">
        <input type="search" name="q" value="<?= e($search) ?>" placeholder="Search shows…">
        <select name="cat" id="showCatFilter">
          <option value="">All Categories</option>
          <?php foreach ($categories as $cat): ?>
            <option <?= $category === $cat ? 'selected' : '' ?>><?= e($cat) ?></option>
          <?php endforeach; ?>
        </select>
        <button class="btn" type="submit" id="applyShowFilters">Search</button>
        <?php if ($search || $category): ?><a class="btn secondary" href="fd-shows.php">Clear</a><?php endif; ?>
      </form>
    </div>

    <!-- All Shows Grid -->
    <?php if ($shows): ?>
    <h2>All FD Shows</h2>
    <div class="featured-shows-grid" id="fdShowsGrid">
      <?php foreach ($shows as $show): ?>
      <div class="show-card">
        <div class="show-card-img">
          <?php if (!empty($show['image_url'])): ?>
            <img src="<?= e($show['image_url']) ?>" alt="<?= e($show['title']) ?>" loading="lazy">
          <?php else: ?>
            <span>🎬</span>
          <?php endif; ?>
        </div>
        <div class="show-card-body">
          <span class="show-cat-tag"><?= e($show['category']) ?></span>
          <h2><a href="fd-shows.php?id=<?= (int)$show['id'] ?>"><?= e($show['title']) ?></a></h2>
          <div class="show-meta">
            <?php if ($show['air_date']): ?><span>📅 <?= date('d M Y', strtotime($show['air_date'])) ?></span><?php endif; ?>
            <?php if ($show['location']): ?><span>📍 <?= e($show['location']) ?></span><?php endif; ?>
          </div>
          <p><?= e(mb_strimwidth($show['description'] ?? '', 0, 100, '…')) ?></p>
          <div class="show-card-footer">
            <span style="font-size:12px;color:var(--muted)"><?= e($show['featured_designer'] ?? '') ?></span>
            <a class="btn small secondary" href="fd-shows.php?id=<?= (int)$show['id'] ?>" id="showCard<?= (int)$show['id'] ?>">View Details →</a>
          </div>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
    <?php else: ?>
    <div class="panel empty-state" style="text-align:center;padding:48px">
      <h2>No shows found</h2>
      <p class="muted">Try changing the search or category filter.</p>
      <a class="btn" href="fd-shows.php">View all shows</a>
    </div>
    <?php endif; ?>
  <?php endif; ?>

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
      <a href="shop.php">Shop</a>
    </div>
  </div>
</footer>
<script src="assets/store.js" defer></script>
</body>
</html>
