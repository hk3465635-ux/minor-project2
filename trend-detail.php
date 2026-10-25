<?php
declare(strict_types=1);
require __DIR__ . '/config.php';

// Check if trend detail is being viewed
$trendId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT) ?: 0;
$trend = null;
$trendHistory = [];
$relatedTrends = [];
$tablesExist = false;

try {
    $pdo->query('SELECT 1 FROM trends LIMIT 1');
    $tablesExist = true;
} catch (Throwable) {}

if ($tablesExist && $trendId > 0) {
    $stmt = $pdo->prepare('SELECT t.*, tc.name AS category_name, tc.slug AS category_slug, tc.icon AS category_icon FROM trends t LEFT JOIN trend_categories tc ON tc.id=t.trend_category_id WHERE t.id=? AND t.active=1 LIMIT 1');
    $stmt->execute([$trendId]);
    $trend = $stmt->fetch() ?: null;

    if ($trend) {
        // Load 30-day history
        $histStmt = $pdo->prepare('SELECT recorded_at, score, status FROM trend_history WHERE trend_id=? ORDER BY recorded_at ASC LIMIT 30');
        $histStmt->execute([$trendId]);
        $trendHistory = $histStmt->fetchAll();

        // Related trends in same category
        if ($trend['trend_category_id']) {
            $relStmt = $pdo->prepare('SELECT t.id, t.name, t.slug, t.popularity_score, t.status, tc.icon AS category_icon FROM trends t LEFT JOIN trend_categories tc ON tc.id=t.trend_category_id WHERE t.trend_category_id=? AND t.id<>? AND t.active=1 ORDER BY t.popularity_score DESC LIMIT 4');
            $relStmt->execute([$trend['trend_category_id'], $trendId]);
            $relatedTrends = $relStmt->fetchAll();
        }
    }
}

if (!$tablesExist || !$trend) {
    header('Location: trends.php');
    exit;
}

// Build forecast (simple linear extrapolation from last 7 days)
$forecast7 = $forecast14 = $forecast30 = null;
if (count($trendHistory) >= 7) {
    $recent = array_slice($trendHistory, -7);
    $scores = array_column($recent, 'score');
    $n = count($scores);
    $sumX = $n * ($n - 1) / 2;
    $sumY = array_sum($scores);
    $sumXY = 0; $sumX2 = 0;
    foreach ($scores as $i => $s) { $sumXY += $i * $s; $sumX2 += $i * $i; }
    $denom = $n * $sumX2 - $sumX * $sumX;
    if ($denom !== 0.0) {
        $slope = ($n * $sumXY - $sumX * $sumY) / $denom;
        $intercept = ($sumY - $slope * $sumX) / $n;
        $forecast7  = max(0, min(100, round($intercept + $slope * ($n + 6), 1)));
        $forecast14 = max(0, min(100, round($intercept + $slope * ($n + 13), 1)));
        $forecast30 = max(0, min(100, round($intercept + $slope * ($n + 29), 1)));
    }
}
$currentScore = (float)$trend['popularity_score'];
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title><?= e($trend['name']) ?> | Trend Detail | FD-Trend</title>
  <meta name="description" content="<?= e(mb_strimwidth($trend['description'] ?? '', 0, 155, '…')) ?>">
  <link rel="stylesheet" href="style.css">
  <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
  <style>
    .trend-detail-hero { background:linear-gradient(110deg,#1a2b22,#263c32); color:#fff; padding:clamp(24px,5vw,52px); border-radius:10px; margin-bottom:32px; }
    .trend-detail-hero h1 { margin:10px 0 14px; font-size:clamp(26px,5vw,44px); }
    .detail-grid { display:grid; grid-template-columns:1fr 360px; gap:28px; align-items:start; }
    @media(max-width:860px){ .detail-grid { grid-template-columns:1fr; } }
    .detail-sidebar .panel { margin-bottom:20px; }
    .score-display { text-align:center; padding:24px; }
    .score-big { font-size:64px; font-weight:800; font-family:Georgia,serif; color:var(--accent); }
    .forecast-cards { display:grid; grid-template-columns:repeat(3,1fr); gap:10px; }
    .forecast-card { background:var(--soft-green); border-radius:8px; padding:14px; text-align:center; }
    .forecast-card strong { display:block; font-size:22px; color:var(--green); }
    .forecast-card span { font-size:11px; color:var(--muted); }
    .related-trend-row { display:flex; justify-content:space-between; align-items:center; padding:10px 0; border-bottom:1px solid var(--line); }
    .related-trend-row:last-child { border:none; }
    .status-pill { border-radius:20px; padding:2px 9px; font-size:11px; font-weight:700; }
    .status-pill.rising { background:#fff0f0; color:#c0392b; }
    .status-pill.stable { background:#f0f5ff; color:#2563eb; }
    .status-pill.declining { background:#fef9e7; color:#b7950b; }
    .chart-container { position:relative; height:260px; }
    .trend-detail-meta { display:flex; gap:18px; flex-wrap:wrap; margin-top:14px; font-size:13px; color:#c8d8cb; }
    .trend-detail-meta span { display:flex; align-items:center; gap:5px; }
  </style>
</head>
<body>
<?php require __DIR__ . '/partials/site_header.php'; ?>
<main class="wrap section">

  <!-- Back -->
  <p><a class="text-link" href="trends.php<?= $trend['category_slug'] ? '?cat=' . urlencode($trend['category_slug']) : '' ?>">← Back to <?= e($trend['category_name'] ?? 'Trends') ?></a></p>

  <!-- Hero -->
  <div class="trend-detail-hero">
    <p class="pill"><?= e($trend['category_icon'] ?? '✨') ?> <?= e($trend['category_name'] ?? 'Fashion') ?></p>
    <h1><?= e($trend['name']) ?></h1>
    <?php if (!empty($trend['hashtag'])): ?>
      <p style="color:#a8d5b5;font-size:15px;font-weight:700"><?= e($trend['hashtag']) ?></p>
    <?php endif; ?>
    <div class="trend-detail-meta">
      <span>📡 <?= e($trend['source']) ?></span>
      <span>🌍 <?= e($trend['region'] ?? 'Global') ?></span>
      <span>🕒 Updated <?= date('d M Y', strtotime($trend['last_updated_at'])) ?></span>
    </div>
  </div>

  <div class="detail-grid">
    <!-- Main Column -->
    <div>
      <!-- Description -->
      <section class="panel">
        <h2 style="margin-top:0">About This Trend</h2>
        <p><?= nl2br(e($trend['description'] ?? '')) ?></p>
      </section>

      <!-- History Chart -->
      <?php if ($trendHistory): ?>
      <section class="panel">
        <h2 style="margin-top:0">📈 30-Day Trend History</h2>
        <div class="chart-container">
          <canvas id="historyChart"></canvas>
        </div>
        <p class="muted" style="font-size:12px;margin-top:10px">⚠ Chart shows stored popularity scores. Data source: <?= e($trend['source']) ?>.</p>
      </section>
      <?php endif; ?>

      <!-- Forecast -->
      <?php if ($forecast7 !== null): ?>
      <section class="panel">
        <h2 style="margin-top:0">🔮 Trend Forecast</h2>
        <p class="muted" style="font-size:13px">Based on a linear extrapolation of the last 7 days of recorded data. This is an indicative forecast, not a guaranteed prediction.</p>
        <div class="forecast-cards">
          <div class="forecast-card">
            <strong><?= $forecast7 ?>%</strong>
            <span>7-Day Forecast</span>
          </div>
          <div class="forecast-card">
            <strong><?= $forecast14 ?>%</strong>
            <span>14-Day Forecast</span>
          </div>
          <div class="forecast-card">
            <strong><?= $forecast30 ?>%</strong>
            <span>30-Day Forecast</span>
          </div>
        </div>
        <?php
          $direction = ($forecast7 ?? $currentScore) > $currentScore ? 'expected to rise' : (($forecast7 ?? $currentScore) < $currentScore ? 'expected to decline' : 'expected to remain stable');
        ?>
        <p style="margin-top:14px;font-size:13px;">
          <strong>Why?</strong> This trend is <strong><?= $direction ?></strong> based on recent momentum in the last 7 recorded days
          (slope: <?= round((($forecast7 ?? $currentScore) - $currentScore) / 7, 2) ?> points/day).
          Current score: <strong><?= round($currentScore) ?>%</strong> → 7-Day Forecast: <strong><?= $forecast7 ?>%</strong>.
        </p>
      </section>
      <?php else: ?>
      <section class="panel">
        <h2 style="margin-top:0">🔮 Trend Forecast</h2>
        <p class="muted">Insufficient historical data for a reliable forecast. At least 7 days of history are required.</p>
      </section>
      <?php endif; ?>
    </div>

    <!-- Sidebar -->
    <div class="detail-sidebar">
      <!-- Score panel -->
      <div class="panel score-display">
        <p class="muted" style="margin:0;font-size:12px">POPULARITY SCORE</p>
        <div class="score-big"><?= round($currentScore) ?></div>
        <div style="height:10px;background:#e5e9e3;border-radius:5px;overflow:hidden;margin:8px 0">
          <div style="height:100%;width:<?= round($currentScore) ?>%;background:linear-gradient(90deg,var(--green),#a8d5b5);border-radius:5px"></div>
        </div>
        <?php $sc = strtolower($trend['status']); ?>
        <span class="status-pill <?= $sc ?>">
          <?= $trend['status'] === 'Rising' ? '↑' : ($trend['status'] === 'Declining' ? '↓' : '→') ?>
          <?= e($trend['status']) ?>
        </span>
        <p style="font-size:12px;color:var(--muted);margin-top:10px">Score: 0–100 (100 = Peak popularity)</p>
      </div>

      <!-- Related Trends -->
      <?php if ($relatedTrends): ?>
      <div class="panel">
        <h3 style="margin-top:0">Related Trends</h3>
        <?php foreach ($relatedTrends as $rel):
          $rsc = strtolower($rel['status']);
        ?>
          <div class="related-trend-row">
            <span><?= e($rel['category_icon'] ?? '✨') ?> <a href="trend-detail.php?id=<?= (int)$rel['id'] ?>"><?= e($rel['name']) ?></a></span>
            <span class="status-pill <?= $rsc ?>"><?= round((float)$rel['popularity_score']) ?>%</span>
          </div>
        <?php endforeach; ?>
      </div>
      <?php endif; ?>

      <!-- Share / Action -->
      <div class="panel">
        <h3 style="margin-top:0">Explore More</h3>
        <p><a class="btn" href="trends.php?cat=<?= urlencode($trend['category_slug'] ?? '') ?>" style="width:100%;display:block;text-align:center" id="moreTrendsBtn">More <?= e($trend['category_name'] ?? 'Trends') ?></a></p>
        <p><a class="btn secondary" href="analytics.php" style="width:100%;display:block;text-align:center" id="analyticsBtn">View Analytics Dashboard</a></p>
        <?php if (is_logged_in()): ?>
          <p><button class="btn secondary" style="width:100%" id="saveTrendBtn" onclick="saveTrend(<?= (int)$trend['id'] ?>)">♡ Save Trend</button></p>
        <?php else: ?>
          <p><a class="btn secondary" href="login.php" style="width:100%;display:block;text-align:center">Login to Save Trend</a></p>
        <?php endif; ?>
      </div>
    </div>
  </div>
</main>

<?php if ($trendHistory): ?>
<script>
const labels = <?= json_encode(array_column($trendHistory, 'recorded_at')) ?>;
const scores = <?= json_encode(array_map('floatval', array_column($trendHistory, 'score'))) ?>;
<?php if ($forecast7 !== null): ?>
const extLabels = [...labels];
const extScores = [...scores];
const today = new Date();
for(let i=1;i<=30;i++){
  const d = new Date(today); d.setDate(d.getDate()+i);
  extLabels.push(d.toISOString().slice(0,10));
}
const forecastBase = scores[scores.length-1];
const forecastPoints = Array(30).fill(null);
// Linear estimate for forecast overlay
<?php endif; ?>
const ctx = document.getElementById('historyChart').getContext('2d');
new Chart(ctx,{
  type:'line',
  data:{
    labels:labels,
    datasets:[{
      label:'Popularity Score',
      data:scores,
      borderColor:'#24694e',
      backgroundColor:'rgba(36,105,78,.1)',
      fill:true,
      tension:.4,
      pointRadius:3,
      borderWidth:2
    }]
  },
  options:{
    responsive:true,
    maintainAspectRatio:false,
    plugins:{legend:{display:false},tooltip:{callbacks:{label:c=>`Score: ${c.raw}%`}}},
    scales:{y:{min:0,max:100,ticks:{callback:v=>v+'%'}},x:{ticks:{maxTicksLimit:8}}}
  }
});
</script>
<?php endif; ?>

<script>
function saveTrend(id) {
  fetch('api/save-trend.php', {
    method:'POST',
    headers:{'Content-Type':'application/json'},
    body:JSON.stringify({trend_id:id, csrf_token:'<?= e(csrf_token()) ?>'})
  }).then(r=>r.json()).then(d=>{
    const btn = document.getElementById('saveTrendBtn');
    if(d.saved) { btn.textContent='♥ Saved!'; btn.disabled=true; }
    else alert(d.message || 'Could not save trend.');
  }).catch(()=>alert('Network error. Please try again.'));
}
</script>
<script src="assets/store.js" defer></script>
</body>
</html>
