<?php
require_once '../Model/config.php';
require_once '../Model/db.php';
require_once '../Model/auth.php';

if (!isLoggedIn() || $_SESSION['user_type'] !== 'admin') {
    redirect('../login.php');
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="../cart.css">
</head>
<body>

<h2>Admin Dashboard</h2>

<ul>
    <li><a href="add_product.php">Add Product</a></li>
    <li><a href="products.php">Manage Products</a></li>
    <li><a href="../logout.php">Logout</a></li>
</ul>

</body>
</html>
