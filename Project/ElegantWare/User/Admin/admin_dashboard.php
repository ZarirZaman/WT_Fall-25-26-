<?php
// Go up 2 levels from User/Admin/ to project root, then to Model/
require_once __DIR__ . '/../../Model/config.php';
require_once __DIR__ . '/../../Model/db.php';
require_once __DIR__ . '/../../Model/auth.php';

// Debug: Check if files are loading
if (!function_exists('isLoggedIn')) {
    die("Error: auth.php functions not loaded. Check file paths.");
}

// Only allow admins
if (!isLoggedIn()) {
    header('Location: ../login.php');
    exit();
}

if (!isAdmin()) {
    die("Access denied. Admin privileges required.");
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard - <?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="admin_cart.css">
</head>
<body>

<h2>Admin Dashboard</h2>
<p>Welcome, <?php echo htmlspecialchars($_SESSION['full_name'] ?? 'Admin'); ?>!</p>

<ul>
    <li><a href="admin_addproducts.php">Add Product</a></li>
    <li><a href="admin_products.php">Manage Products</a></li>
    <li><a href="../../logout.php">Logout</a></li>
</ul>

</body>
</html>