<?php
require_once '../Model/config.php';
require_once '../Model/auth.php';

if (!isset($_SESSION['admin'])) {
    header("Location: admin_login.php");
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
    <link rel="stylesheet" href="cart.css">
</head>
<body>

<header>
    <div class="container navbar">
        <a class="logo">Admin<span>Panel</span></a>
        <ul class="nav-links">
            <li><a href="products.php">Products</a></li>
            <li><a href="users.php">Users</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </div>
</header>

<div class="container" style="margin-top:40px;">
    <h2 class="section-title">Dashboard</h2>

    <div class="features-grid">
        <div class="feature-card">
            <h3>Manage Products</h3>
            <p>Add, edit, or delete products</p>
            <a href="products.php" class="btn btn-primary">Open</a>
        </div>

        <div class="feature-card">
            <h3>View Users</h3>
            <p>View registered users</p>
            <a href="users.php" class="btn btn-primary">Open</a>
        </div>
    </div>
</div>

</body>
</html>
