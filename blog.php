<?php
/* blog.php — redirects to marketing.php preserving all query params */
$qs = $_SERVER['QUERY_STRING'] ?? '';
header('Location: marketing.php' . ($qs !== '' ? '?' . $qs : ''));
exit;
