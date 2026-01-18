<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/* =====================
   DATABASE CONFIG
===================== */
define('DB_HOST', 'localhost');
define('DB_NAME', 'crockery_store');  // Same database as Customer
define('DB_USER', 'root');
define('DB_PASS', '');

// Database connection
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

/* =====================
   SITE SETTINGS
===================== */
define('SITE_NAME', 'ElegantWare - Admin');

/* =====================
   PATH SETTINGS
   (You are inside Admin/Model/)
===================== */
define('BASE_PATH', dirname(__DIR__));     // points to Admin folder
define('MODEL_PATH', BASE_PATH . '/Model/');
define('CONTROLLER_PATH', BASE_PATH . '/Controller/');
define('VIEW_PATH', BASE_PATH . '/View/');

/* =====================
   WEB URLS
===================== */
define('WEB_ROOT', '/ElegantWare/Admin/');   // Admin URL
define('ASSETS_URL', WEB_ROOT . 'View/');
