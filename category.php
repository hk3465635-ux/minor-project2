<?php
require __DIR__ . '/config.php';
$slug = trim($_GET['slug'] ?? $_GET['category'] ?? '');
if ($slug === '') {
    header('Location: categories.php');
    exit;
}
$_GET['category'] = $slug;
require __DIR__ . '/shop.php';
