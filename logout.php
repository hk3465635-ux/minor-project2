<?php
require __DIR__ . '/config.php';

// Support both GET and POST for logout
unset($_SESSION['user_id'], $_SESSION['user_name'], $_SESSION['user_email']);
session_regenerate_id(true);

$_SESSION['flash'] = 'You have been logged out successfully.';
header('Location: ' . public_path('login.php'));
exit;
