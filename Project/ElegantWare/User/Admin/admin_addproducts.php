<?php
require_once '../Model/config.php';
require_once '../Model/db.php';
require_once '../Model/auth.php';

// Check if user is admin
if (!isLoggedIn() || $_SESSION['user_type'] !== 'admin') {
    redirect('../Admin/admin_dashboard.php'); 
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'] ?? '';
    $price = $_POST['price'] ?? 0;

    $stmt = $conn->prepare("INSERT INTO products (name, price) VALUES (?, ?)");
    $stmt->bind_param("sd", $name, $price); // s=string, d=double
    $stmt->execute();
    $stmt->close();

    redirect('products.php'); // make sure products.php exists in same folder
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Product</title>
    <link rel="stylesheet" href="admin_cart.css"> <!-- adjust if cart.css is elsewhere -->
</head>
<body>

<h2>Add Product</h2>

<form method="post">
    Name: <input type="text" name="name" required><br><br>
    Price: <input type="number" name="price" step="0.01" required><br><br>
    <button type="submit">Save</button>
</form>

</body>
</html>
