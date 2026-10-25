<?php
require __DIR__ . '/config.php';
if (is_admin()) {
    header('Location: ' . public_path('admin/panel.php'));
    exit;
}
header('Location: ' . public_path('admin/login.php'));
exit;
