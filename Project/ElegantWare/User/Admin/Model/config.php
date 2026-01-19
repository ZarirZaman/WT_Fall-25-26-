<?php
// config.php - Main configuration file

/* =====================
   DATABASE CONFIG
===================== */
define('DB_HOST', 'localhost');
define('DB_NAME', 'crockery_store');
define('DB_USER', 'root');
define('DB_PASS', '');

$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
if ($conn->connect_error) {
    die("Database connection failed");
}

/* =====================
   SITE SETTINGS
===================== */
define('SITE_NAME', 'ElegantWare');

/* =====================
   PATH SETTINGS
   Adjust based on your actual structure
===================== */
define('BASE_PATH', dirname(__DIR__)); // Go up from Model/ to project root
define('MODEL_PATH', BASE_PATH . '/Model/');
// Web URLs - adjust to match your XAMPP setup
define('WEB_ROOT', '/WT_Fall-25-26-/Project/ElegantWare/');
define('ADMIN_URL', WEB_ROOT . 'User/Admin/');
define('ASSETS_URL', WEB_ROOT . 'assets/');

// Auto-start session only if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>