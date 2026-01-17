<?php
require_once '../Model/config.php';
require_once '../Model/db.php';
require_once '../Model/auth.php';

if (!isLoggedIn() || $_SESSION['user_type'] !== 'admin') {
    redirect('../login.php');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $price = $_POST['price'];

    mysqli_query($conn,
        "INSERT INTO products (name, price)
         VALUES ('$name', '$price')"
    );

    redirect('products.php');
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Product</title>
     <link rel="stylesheet" href="../cart.css">
</head>
<body>

<h2>Add Product</h2>

<form method="post">
    Name: <input type="text" name="name"><br><br>
    Price: <input type="number" name="price"><br><br>
    <button type="submit">Save</button>
</form>

</body>
</html>
