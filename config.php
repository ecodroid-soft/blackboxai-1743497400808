<?php
// Database configuration
define('DB_HOST', 'localhost');
define('DB_NAME', 'satta_db');
define('DB_USER', 'root');
define('DB_PASS', '');

// Error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Session configuration
ini_set('session.cookie_httponly', 1);
ini_set('session.use_only_cookies', 1);
session_start();

// Time zone setting
date_default_timezone_set('Asia/Kolkata');

// Site configuration
define('SITE_NAME', 'Satta Result');
define('ADMIN_EMAIL', 'admin@example.com');
?>