<?php
require __DIR__ . '/config.php';
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT) ?: 0;
$stmt = $pdo->prepare("SELECT m.*,p.name AS product_name FROM marketing_posts m LEFT JOIN products p ON p.id=m.product_id WHERE m.id=? AND m.type='reel' AND m.published=1 AND (m.published_at IS NULL OR m.published_at<=NOW())");
$stmt->execute([$id]);
$reel = $stmt->fetch();
if (!$reel) { http_response_code(404); exit('Reel not found. <a href="reels.php">Back to reels</a>'); }
$sessionKey = hash('sha256', session_id());
$eventExists = $pdo->prepare('SELECT COUNT(*) FROM reel_events WHERE reel_id=? AND session_key=? AND event_type=?');
$eventExists->execute([$id,$sessionKey,'view']);
if (!(int)$eventExists->fetchColumn()) {
    $pdo->prepare("INSERT IGNORE INTO reel_events (reel_id,session_key,event_type) VALUES (?,?,'view')")->execute([$id,$sessionKey]);
    $pdo->prepare('UPDATE marketing_posts SET view_count=view_count+1 WHERE id=?')->execute([$id]);
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $likeInsert = $pdo->prepare("INSERT IGNORE INTO reel_events (reel_id,session_key,event_type) VALUES (?,?,'like')");
    $likeInsert->execute([$id,$sessionKey]);
    if ($likeInsert->rowCount() === 1) $pdo->prepare('UPDATE marketing_posts SET like_count=like_count+1 WHERE id=?')->execute([$id]);
    header('Location: reel.php?id=' . $id); exit;
}
$count = $pdo->prepare('SELECT view_count,like_count FROM marketing_posts WHERE id=?');
$count->execute([$id]);
$stats = $count->fetch();
$liked = $pdo->prepare("SELECT COUNT(*) FROM reel_events WHERE reel_id=? AND session_key=? AND event_type='like'");
$liked->execute([$id,$sessionKey]);
$hasLiked = (int)$liked->fetchColumn() > 0;
?>
<!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title><?= e($reel['title']) ?> | FD-Trend Reels</title><link rel="stylesheet" href="style.css"></head><body><?php require __DIR__ . '/partials/site_header.php'; ?><main class="wrap section"><a class="text-link" href="reels.php">← All reels</a><article class="reel-detail"><div class="reel-media reel-player" data-reel-player><?php if ($reel['video_url']): ?><video controls playsinline preload="metadata" poster="<?= e($reel['image_url'] ?? '') ?>"><source src="<?= e($reel['video_url']) ?>">Video playback is not supported by this browser.</video><?php elseif ($reel['image_url']): ?><div class="reel-motion reel-motion-large" style="background-image:url('<?= e($reel['image_url']) ?>')"></div><button type="button" class="reel-toggle" data-reel-toggle>Play</button><?php else: ?><div class="content-placeholder">STYLE PREVIEW</div><?php endif; ?></div><div class="panel"><span class="tag"><?= e($reel['category']) ?></span><h1><?= e($reel['title']) ?></h1><p class="lead"><?= e($reel['caption']) ?></p><?php if ($reel['body']): ?><p><?= nl2br(e($reel['body'])) ?></p><?php endif; ?><div class="reel-stats"><span><?= (int)$stats['view_count'] ?> views</span><span><?= (int)$stats['like_count'] ?> likes</span></div><form method="post"><input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>"><button class="btn secondary" <?= $hasLiked?'disabled':'' ?>><?= $hasLiked?'Liked':'Like this reel' ?></button></form><?php if ($reel['product_id']): ?><a class="btn" href="product.php?id=<?= (int)$reel['product_id'] ?>">Shop <?= e($reel['product_name'] ?? 'the look') ?></a><?php endif; ?></div></article></main><script src="assets/store.js" defer></script></body></html>
