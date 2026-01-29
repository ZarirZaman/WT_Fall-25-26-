<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

define('DB_HOST', 'localhost');
define('DB_NAME', 'crockery_store');
define('DB_USER', 'root');
define('DB_PASS', '');

$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
if ($conn->connect_error) {
    die("Database connection failed");
}

define('SITE_NAME', 'ElegantWare');

define('BASE_PATH', dirname(__DIR__));   
define('MODEL_PATH', BASE_PATH . '/Model/');
define('CONTROLLER_PATH', BASE_PATH . '/Controller/');
define('VIEW_PATH', BASE_PATH . '/View/');

/* =====================
   WEB URLS
===================== */
define('WEB_ROOT', 'http://localhost/ElegantWare/');
define('ASSETS_URL', WEB_ROOT . 'View/');
