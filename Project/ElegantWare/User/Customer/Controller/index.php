<?php
require_once '../Model/config.php';
require_once '../Model/auth.php';
require_once '../Model/cart_func.php';

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

if (isset($_GET['add_to_cart'])) {
    $product_id = intval($_GET['add_to_cart']);
    addToCart($product_id);
    
    $_SESSION['cart_message'] = 'Product added to cart successfully!';
    header('Location: cart.php');
    exit();
}

$search_term = isset($_GET['search']) ? trim($_GET['search']) : '';

// Sample products for home page
$all_products = [
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
// Filter products by search term
if (!empty($search_term)) {
    $search_lower = strtolower($search_term);
    $featured_products = array_filter($all_products, function($product) use ($search_lower) {
        return (strpos(strtolower($product['name']), $search_lower) !== false) ||
               (strpos(strtolower($product['description']), $search_lower) !== false) ||
               (strpos(strtolower($product['category']), $search_lower) !== false);
    });
    $featured_products = array_values($featured_products); // Reset array keys
} else {
    $featured_products = $all_products;
}

// Get category filter if present
$current_category = isset($_GET['category']) ? $_GET['category'] : '';
$current_sort = isset($_GET['sort']) ? $_GET['sort'] : 'high-low';

// Filter products by category if category is specified
if (!empty($current_category)) {
    $featured_products = array_filter($featured_products, function($product) use ($current_category) {
        return strtolower($product['category']) === strtolower($current_category);
    });
    $featured_products = array_values($featured_products); // Reset array keys
}

// Sort products based on sort parameter
switch ($current_sort) {
    case 'low-high':
        usort($featured_products, function($a, $b) {
            return $a['price'] <=> $b['price']; 
        });
        break;
    case 'name-asc':
        usort($featured_products, function($a, $b) {
            return strcmp($a['name'], $b['name']); 
        });
        break;
    case 'name-desc':
        usort($featured_products, function($a, $b) {
            return strcmp($b['name'], $a['name']); 
        });
        break;
    case 'high-low':
    default:
        usort($featured_products, function($a, $b) {
            return $b['price'] <=> $a['price']; 
        });
        break;
}

$categories = ['All', 'Plates', 'Bowls', 'Cups', 'Sets'];

$cart_count = calculateCartCount();

$user = null;
if (isLoggedIn() && isset($_SESSION['user_id'])) {
    if (isset($_SESSION['full_name']) && $_SESSION['full_name']) {
        $user = ['username' => $_SESSION['full_name']];
    } else {
        $user_id = $_SESSION['user_id'];
        $sql = "SELECT username FROM users WHERE user_id = ?";
        $stmt = $conn->prepare($sql);
        
        if ($stmt) {
            $stmt->bind_param("i", $user_id);
            $stmt->execute();
            $result = $stmt->get_result();
            $user = $result->fetch_assoc();
            $stmt->close();
        }
    }
}

$data = [
    'featured_products' => $featured_products,
    'categories' => $categories,
    'current_category' => $current_category,
    'current_sort' => $current_sort,
    'search_term' => $search_term,
    'total_products' => count($featured_products),
    'cart_count' => $cart_count,
    'is_logged_in' => isLoggedIn(),
    'user_id' => $_SESSION['user_id'] ?? null,
    'full_name' => $_SESSION['full_name'] ?? '',
    'user' => $user
];

include '../View/html/index_view.php';
?>