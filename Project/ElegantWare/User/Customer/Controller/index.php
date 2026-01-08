<?php
require_once '../Model/config.php';
require_once '../Model/auth.php';
require_once '../Model/cart_func.php';

// Initialize cart session if not exists
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Handle adding to cart from index page
if (isset($_GET['add_to_cart'])) {
    $product_id = intval($_GET['add_to_cart']);
    addToCart($product_id);
    
    $_SESSION['cart_message'] = 'Product added to cart successfully!';
    header('Location: cart.php');
    exit();
}

// Sample products for home page
$featured_products = [
    ['id' => 1, 'name' => 'Ceramic Dinner Plate Set', 'description' => 'Beautiful ceramic dinner plates, set of 6', 
     'price' => 29.99, 'category' => 'Plates', 'image_class' => 'fa-utensils'],
    
    ['id' => 2, 'name' => 'Porcelain Soup Bowls', 'description' => 'Elegant porcelain bowls for soup or salad', 
     'price' => 24.99, 'category' => 'Bowls', 'image_class' => 'fa-bowl-food'],
    
    ['id' => 3, 'name' => 'Coffee Mug Collection', 'description' => 'Set of 4 ceramic coffee mugs', 
     'price' => 19.99, 'category' => 'Cups', 'image_class' => 'fa-mug-hot'],
    
    ['id' => 4, 'name' => 'Complete Dinner Set', 'description' => '32-piece dinner set for family occasions', 
     'price' => 149.99, 'category' => 'Sets', 'image_class' => 'fa-box-open'],
     
    ['id' => 5, 'name' => 'Decorative Serving Platters', 'description' => 'Set of 4 decorative serving platters', 
     'price' => 39.99, 'category' => 'Plates', 'image_class' => 'fa-utensils'],
     
    ['id' => 6, 'name' => 'Handcrafted Salad Bowls', 'description' => 'Unique handcrafted bowls for salads', 
     'price' => 34.99, 'category' => 'Bowls', 'image_class' => 'fa-bowl-food'],
     
    ['id' => 7, 'name' => 'Tea Cup Set', 'description' => 'Elegant tea cup set with saucers, set of 4', 
     'price' => 27.99, 'category' => 'Cups', 'image_class' => 'fa-mug-hot'],
     
    ['id' => 8, 'name' => 'Luxury Dinnerware Set', 'description' => '24-piece luxury dinnerware set for special occasions', 
     'price' => 199.99, 'category' => 'Sets', 'image_class' => 'fa-box-open'],
];

// Calculate cart item count for badge
$cart_count = calculateCartCount();

// Prepare data for view
$data = [
    'featured_products' => $featured_products,
    'cart_count' => $cart_count,
    'is_logged_in' => isLoggedIn(),
    'user_id' => $_SESSION['user_id'] ?? null,
    'full_name' => $_SESSION['full_name'] ?? ''
];

// Include the view
include '../View/html/index_view.php';
?>