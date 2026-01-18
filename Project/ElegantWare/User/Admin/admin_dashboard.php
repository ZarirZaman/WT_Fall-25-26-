<?php
require_once 'Model/config.php';
require_once 'Model/db.php';
require_once 'Model/auth.php';

// Only allow admins
if (!isLoggedIn() || $_SESSION['user_type'] !== 'admin') {
    redirect('Admin/admin_dashboard.php');
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="admin_cart.css"> <!-- adjust if cart.css is elsewhere -->
</head>
<body>

<h2>Admin Dashboard</h2>

<ul>
    <li><a href="admin_addproducts.php">Add Product</a></li>
    <li><a href="products.php">Manage Products</a></li>
    <li><a href="../logout.php">Logout</a></li>
</ul>

</body>
</html>
