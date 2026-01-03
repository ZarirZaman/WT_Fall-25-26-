<?php
// includes/db.php

$host = 'localhost';
$user = 'root'; // Default XAMPP user
$password = ''; // Default XAMPP password (empty)
$database = 'crockery_store'; // Your database name

// Create connection
$conn = new mysqli($host, $user, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
// Enable error reporting
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

// Set charset to UTF-8
$conn->set_charset("utf8mb4");
?>