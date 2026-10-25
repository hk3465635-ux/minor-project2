<?php
declare(strict_types=1);
require __DIR__ . '/config.php';

// Check tables
$tablesExist = false;
try { $pdo->query('SELECT 1 FROM trends LIMIT 1'); $tablesExist = true; } catch (Throwable) {}

$metrics = [];
$categoryData = [];
$topTrends = [];
$risingTrends = [];
$trendChartData = [];
$analyticsData = [];

if ($tablesExist) {
    // Metric cards
    $analyticsData = $pdo->query('SELECT metric_key, metric_value FROM trend_analytics')->fetchAll(PDO::FETCH_KEY_PAIR);

    $stats = $pdo->query("SELECT
        COUNT(*) AS total,
        SUM(status='Rising') AS rising,
        SUM(status='Stable') AS stable,
        SUM(status='Declining') AS declining,
        ROUND(AVG(popularity_score),1) AS avg_score,
        MAX(popularity_score) AS max_score
        FROM trends WHERE active=1")->fetch();

    $metrics = [
        ['title' => 'Global Trend Index',   'value' => ($analyticsData['global_trend_index'] ?? $stats['avg_score'] ?? 0),  'icon' => '🌐', 'unit' => '/100'],
        ['title' => 'Trends Analyzed',      'value' => $stats['total'] ?? 0,    'icon' => '📊', 'unit' => ''],
        ['title' => 'Rising Trends',        'value' => $stats['rising'] ?? 0,   'icon' => '↑',  'unit' => ' trending up'],
        ['title' => 'Declining Trends',     'value' => $stats['declining'] ?? 0,'icon' => '↓',  'unit' => ' declining'],
        ['title' => 'Avg. Popularity',      'value' => $stats['avg_score'] ?? 0,'icon' => '⭐', 'unit' => '%'],
        ['title' => 'Data Last Updated',    'value' => date('d M Y'),           'icon' => '🕒', 'unit' => ''],
    ];

    // Category-wise stats
    $categoryData = $pdo->query("SELECT tc.name, tc.icon, COUNT(t.id) AS trend_count,
        ROUND(AVG(t.popularity_score),1) AS avg_score,
        SUM(t.status='Rising') AS rising
        FROM trend_categories tc
        LEFT JOIN trends t ON t.trend_category_id=tc.id AND t.active=1
        WHERE tc.active=1
        GROUP BY tc.id ORDER BY avg_score DESC")->fetchAll();

    // Top 5 trends by score
    $topTrends = $pdo->query("SELECT t.id, t.name, t.popularity_score, t.status, tc.name AS cat, tc.icon FROM trends t LEFT JOIN trend_categories tc ON tc.id=t.trend_category_id WHERE t.active=1 ORDER BY t.popularity_score DESC LIMIT 5")->fetchAll();

    // Rising trends
    $risingTrends = $pdo->query("SELECT t.id, t.name, t.popularity_score, t.status, tc.name AS cat, tc.icon FROM trends t LEFT JOIN trend_categories tc ON tc.id=t.trend_category_id WHERE t.active=1 AND t.status='Rising' ORDER BY t.popularity_score DESC LIMIT 6")->fetchAll();

    // Chart data: last 14 days global average
    $chartRows = $pdo->query("SELECT recorded_at, ROUND(AVG(score),1) AS avg_score, SUM(status='Rising') AS rising_count FROM trend_history WHERE recorded_at >= DATE_SUB(CURDATE(), INTERVAL 14 DAY) GROUP BY recorded_at ORDER BY recorded_at ASC")->fetchAll();
    $trendChartData = $chartRows;
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Trend Analytics Dashboard | FD-Trend</title>
  <meta name="description" content="Fashion trend analytics dashboard — global trend index, rising trends, category comparison, and forecasts.">
  <link rel="stylesheet" href="style.css">
  <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
  <style>
    .analytics-hero { background:linear-gradient(110deg,#1a2b22,#263c32); color:#fff; padding:clamp(24px,5vw,52px); border-radius:10px; margin-bottom:32px; }
    .analytics-hero h1 { margin:8px 0 8px; font-size:clamp(26px,5vw,44px); }
    .analytics-hero p { color:#c8d8cb; max-width:580px; }
    /* Metric Cards */
    .metric-grid { display:grid; grid-template-columns:repeat(auto-fill,minmax(175px,1fr)); gap:16px; margin-bottom:32px; }
    .metric-card { background:var(--surface); border:1px solid var(--line); border-radius:10px; padding:20px; text-align:center; }
    .metric-card .icon { font-size:28px; margin-bottom:8px; }
    .metric-card .value { font-size:32px; font-weight:800; color:var(--accent); font-family:Georgia,serif; }
    .metric-card .unit { font-size:12px; color:var(--muted); font-weight:600; }
    .metric-card .label { font-size:12px; color:var(--muted); margin-top:4px; }
    /* Charts grid */
    .charts-grid { display:grid; grid-template-columns:1fr 1fr; gap:24px; margin-bottom:32px; }
    @media(max-width:780px){ .charts-grid { grid-template-columns:1fr; } }
    .chart-panel { background:var(--surface); border:1px solid var(--line); border-radius:10px; padding:22px; }
    .chart-panel h2 { margin:0 0 16px; font-size:17px; }
    .chart-container { position:relative; height:240px; }
    /* Top trends table */
    .top-trends-list { display:flex; flex-direction:column; gap:10px; }
    .top-trend-row { display:flex; align-items:center; gap:14px; padding:12px; border:1px solid var(--line); border-radius:8px; background:var(--surface); }
    .top-trend-rank { font-size:24px; font-weight:800; color:var(--muted); min-width:32px; text-align:center; }
    .top-trend-info { flex:1; }
    .top-trend-info strong { display:block; }
    .top-trend-info small { color:var(--muted); font-size:12px; }
    .top-trend-score { font-size:20px; font-weight:800; color:var(--accent); }
    .status-pill { border-radius:20px; padding:2px 9px; font-size:11px; font-weight:700; }
    .status-pill.rising   { background:#fff0f0; color:#c0392b; }
    .status-pill.stable   { background:#f0f5ff; color:#2563eb; }
    .status-pill.declining{ background:#fef9e7; color:#b7950b; }
    /* Category table */
    .notice-setup { background:#fef3cd; border:1px solid #ffc107; border-radius:8px; padding:20px; margin:24px 0; }
    .sample-label { display:inline-block; background:#fef3cd; color:#856404; border:1px solid #ffc107; border-radius:4px; font-size:11px; padding:2px 8px; margin-left:8px; }
  </style>
</head>
<body>
<?php require __DIR__ . '/partials/site_header.php'; ?>
<main class="wrap section">

  <div class="analytics-hero">
    <p class="pill">FD-TREND &middot; ANALYTICS DASHBOARD</p>
    <h1>📊 Trend Analytics</h1>
    <p>Real-time fashion trend metrics, category comparisons, popularity trends, and 14-day global tracking.</p>
    <p style="font-size:12px;color:#a8d5b5;margin-top:10px">
      <span class="sample-label">Sample Data</span> — Charts use data stored in your database. Connect live APIs for real-time updates.
    </p>
  </div>

  <?php if (!$tablesExist): ?>
  <div class="notice-setup">
    <h2>⚠ Analytics database tables not found</h2>
    <p>Run the migration from the command line:</p>
    <code>php migrate_trends.php</code>
    <p>After migration, refresh this page to see all analytics data.</p>
  </div>
  <?php else: ?>

  <!-- Metric Cards -->
  <div class="metric-grid">
    <?php foreach ($metrics as $m): ?>
    <div class="metric-card">
      <div class="icon"><?= e($m['icon']) ?></div>
      <div class="value"><?= e((string)$m['value']) ?><span class="unit"><?= e($m['unit']) ?></span></div>
      <div class="label"><?= e($m['title']) ?></div>
    </div>
    <?php endforeach; ?>
  </div>

  <!-- Charts Row -->
  <div class="charts-grid">
    <!-- Line Chart: 14-Day Average -->
    <div class="chart-panel">
      <h2>📈 14-Day Global Trend Average <span class="sample-label">Database</span></h2>
      <div class="chart-container">
        <canvas id="lineChart"></canvas>
      </div>
    </div>

    <!-- Bar Chart: Category Comparison -->
    <div class="chart-panel">
      <h2>📊 Category Avg. Popularity <span class="sample-label">Database</span></h2>
      <div class="chart-container">
        <canvas id="barChart"></canvas>
      </div>
    </div>

    <!-- Doughnut: Status Breakdown -->
    <div class="chart-panel">
      <h2>🍩 Trend Status Breakdown</h2>
      <div class="chart-container">
        <canvas id="doughnutChart"></canvas>
      </div>
    </div>

    <!-- Rising Trends Preview -->
    <div class="chart-panel">
      <h2>🔥 Currently Rising Trends</h2>
      <div style="overflow-y:auto;max-height:240px;">
        <?php if ($risingTrends): ?>
          <?php foreach ($risingTrends as $rt): ?>
          <div class="top-trend-row" style="margin-bottom:8px">
            <div class="top-trend-info">
              <strong><?= e($rt['icon'] ?? '✨') ?> <?= e($rt['name']) ?></strong>
              <small><?= e($rt['cat'] ?? '') ?></small>
            </div>
            <span class="status-pill rising">↑ <?= round((float)$rt['popularity_score']) ?>%</span>
          </div>
          <?php endforeach; ?>
        <?php else: ?>
          <p class="muted">No rising trends found.</p>
        <?php endif; ?>
      </div>
    </div>
  </div>

  <!-- Top 5 Trends Table -->
  <section class="panel">
    <h2 style="margin-top:0">🏆 Top 5 Trending Fashion Right Now</h2>
    <div class="top-trends-list">
      <?php foreach ($topTrends as $i => $t):
        $sc = strtolower($t['status']);
      ?>
      <div class="top-trend-row">
        <div class="top-trend-rank">#<?= $i + 1 ?></div>
        <div class="top-trend-info">
          <strong><?= e($t['icon'] ?? '✨') ?> <a href="trend-detail.php?id=<?= (int)$t['id'] ?>"><?= e($t['name']) ?></a></strong>
          <small><?= e($t['cat'] ?? '') ?></small>
        </div>
        <span class="status-pill <?= $sc ?>"><?= $t['status'] === 'Rising' ? '↑' : ($t['status'] === 'Declining' ? '↓' : '→') ?> <?= e($t['status']) ?></span>
        <div class="top-trend-score"><?= round((float)$t['popularity_score']) ?>%</div>
      </div>
      <?php endforeach; ?>
    </div>
  </section>

  <!-- Category Table -->
  <section class="panel">
    <h2 style="margin-top:0">📋 Category-Wise Analysis</h2>
    <div class="tablewrap">
      <table>
        <thead>
          <tr><th>Category</th><th>Trends</th><th>Avg. Score</th><th>Rising Count</th><th>Explore</th></tr>
        </thead>
        <tbody>
          <?php foreach ($categoryData as $cat): ?>
          <tr>
            <td><strong><?= e($cat['icon'] ?? '✨') ?> <?= e($cat['name']) ?></strong></td>
            <td><?= (int)$cat['trend_count'] ?></td>
            <td><?= $cat['avg_score'] ?? 'N/A' ?>%</td>
            <td><?= (int)$cat['rising'] ?> rising</td>
            <td><a class="btn secondary small" href="trends.php?cat=<?= urlencode(strtolower(str_replace(' ', '-', $cat['name']))) ?>">View trends</a></td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </section>

  <?php endif; ?>
</main>

<?php if ($tablesExist): ?>
<script>
// ── Line Chart ──
const lineLabels = <?= json_encode(array_column($trendChartData, 'recorded_at')) ?>;
const lineScores = <?= json_encode(array_map('floatval', array_column($trendChartData, 'avg_score'))) ?>;
new Chart(document.getElementById('lineChart').getContext('2d'),{
  type:'line',
  data:{
    labels: lineLabels.length ? lineLabels : ['No Data'],
    datasets:[{label:'Avg. Trend Score',data:lineScores.length ? lineScores : [0],borderColor:'#24694e',backgroundColor:'rgba(36,105,78,.12)',fill:true,tension:.4,pointRadius:4,borderWidth:2}]
  },
  options:{responsive:true,maintainAspectRatio:false,plugins:{legend:{display:false}},scales:{y:{min:0,max:100,ticks:{callback:v=>v+'%'}},x:{ticks:{maxTicksLimit:8}}}}
});

// ── Bar Chart (Category) ──
const catLabels = <?= json_encode(array_column($categoryData, 'name')) ?>;
const catScores = <?= json_encode(array_map('floatval', array_column($categoryData, 'avg_score'))) ?>;
const barColors = catLabels.map((_,i)=>`hsl(${(i*30)%360},55%,42%)`);
new Chart(document.getElementById('barChart').getContext('2d'),{
  type:'bar',
  data:{labels:catLabels,datasets:[{label:'Avg. Score',data:catScores,backgroundColor:barColors,borderRadius:6}]},
  options:{responsive:true,maintainAspectRatio:false,plugins:{legend:{display:false}},scales:{y:{min:0,max:100,ticks:{callback:v=>v+'%'}},x:{ticks:{maxRotation:40,minRotation:20}}}}
});

// ── Doughnut ──
const rising   = <?= (int)($stats['rising']   ?? 0) ?>;
const stable   = <?= (int)($stats['stable']   ?? 0) ?>;
const declining= <?= (int)($stats['declining'] ?? 0) ?>;
new Chart(document.getElementById('doughnutChart').getContext('2d'),{
  type:'doughnut',
  data:{
    labels:['Rising','Stable','Declining'],
    datasets:[{data:[rising,stable,declining],backgroundColor:['#c0392b','#2563eb','#b7950b'],borderWidth:2}]
  },
  options:{responsive:true,maintainAspectRatio:false,plugins:{legend:{position:'bottom'}}}
});
</script>
<?php endif; ?>
<script src="assets/store.js" defer></script>
</body>
</html>
