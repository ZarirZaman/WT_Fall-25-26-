<?php
// db.php - Database connection only
require_once __DIR__ . '/config.php'; // Include config first

// Create database connection if not already created
global $conn;
if (!isset($conn)) {
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    
    // Check connection
    if ($conn->connect_error) {
        die("Database connection failed: " . $conn->connect_error);
    }
    
    // Set charset to UTF-8
    $conn->set_charset("utf8mb4");
}

// Optional: Database helper functions
function getDBConnection() {
    global $conn;
    return $conn;
}

function closeDBConnection() {
    global $conn;
    if ($conn) {
        $conn->close();
    }
}
?>