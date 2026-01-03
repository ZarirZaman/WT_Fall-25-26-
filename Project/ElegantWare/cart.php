<?php
require_once 'includes/auth.php'; 
$error = '';
$success = '';

// Check if user is logged in
if (!isLoggedIn()) {
    redirect('login.php');
}

// Initialize cart in session if not exists
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Handle GET requests for adding items (from index.php links)
if (isset($_GET['add_to_cart'])) {
    $product_id = intval($_GET['add_to_cart']);
    
    // Check if product already in cart
    $found = false;
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['product_id'] == $product_id) {
            $item['quantity'] += 1;
            $found = true;
            break;
        }
    }
    
    if (!$found) {
        $_SESSION['cart'][] = [
            'product_id' => $product_id,
            'quantity' => 1,
            'added_at' => date('Y-m-d H:i:s')
        ];
    }
    
    $_SESSION['cart_message'] = 'Product added to cart!';
    header('Location: cart.php');
    exit();
}

// Handle POST requests for cart actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add_to_cart'])) {
        $product_id = intval($_POST['product_id']);
        $quantity = intval($_POST['quantity'] ?? 1);
        
        if ($quantity < 1) $quantity = 1;
        
        // Check if product already in cart
        $found = false;
        foreach ($_SESSION['cart'] as &$item) {
            if ($item['product_id'] == $product_id) {
                $item['quantity'] += $quantity;
                $found = true;
                break;
            }
        }
        
        if (!$found) {
            $_SESSION['cart'][] = [
                'product_id' => $product_id,
                'quantity' => $quantity,
                'added_at' => date('Y-m-d H:i:s')
            ];
        }
        
        $_SESSION['cart_message'] = 'Product added to cart successfully!';
        
    } elseif (isset($_POST['update_cart'])) {
        // Update quantities
        if (isset($_POST['quantity']) && is_array($_POST['quantity'])) {
            foreach ($_POST['quantity'] as $index => $quantity) {
                $quantity = intval($quantity);
                if (isset($_SESSION['cart'][$index])) {
                    if ($quantity > 0) {
                        $_SESSION['cart'][$index]['quantity'] = $quantity;
                    } else {
                        unset($_SESSION['cart'][$index]);
                    }
                }
            }
            // Reindex array
            $_SESSION['cart'] = array_values($_SESSION['cart']);
            $_SESSION['cart_message'] = 'Cart updated successfully!';
        }
        
    } elseif (isset($_POST['remove_item'])) {
        // Remove specific item
        $index = intval($_POST['item_index']);
        if (isset($_SESSION['cart'][$index])) {
            unset($_SESSION['cart'][$index]);
            $_SESSION['cart'] = array_values($_SESSION['cart']);
            $_SESSION['cart_message'] = 'Item removed from cart!';
        }
        
    } elseif (isset($_POST['clear_cart'])) {
        // Clear entire cart
        $_SESSION['cart'] = [];
        $_SESSION['cart_message'] = 'Cart cleared successfully!';
    }
    
    // Redirect to avoid form resubmission
    header('Location: cart.php');
    exit();
}

// Sample products database
$products = [
    1 => [
        'id' => 1, 
        'name' => 'Ceramic Dinner Plate Set', 
        'description' => 'Beautiful ceramic dinner plates, set of 6', 
        'price' => 29.99, 
        'category' => 'Plates', 
        'image' => 'plate.jpg'
    ],
    2 => [
        'id' => 2, 
        'name' => 'Porcelain Soup Bowls', 
        'description' => 'Elegant porcelain bowls for soup or salad', 
        'price' => 24.99, 
        'category' => 'Bowls', 
        'image' => 'bowl.jpg'
    ],
    3 => [
        'id' => 3, 
        'name' => 'Coffee Mug Collection', 
        'description' => 'Set of 4 ceramic coffee mugs', 
        'price' => 19.99, 
        'category' => 'Cups', 
        'image' => 'cup.jpg'
    ],
    4 => [
        'id' => 4, 
        'name' => 'Complete Dinner Set', 
        'description' => '32-piece dinner set for family occasions', 
        'price' => 149.99, 
        'category' => 'Sets', 
        'image' => 'set.jpg'
    ],
];

// Calculate cart totals
$cart_items = [];
$cart_total = 0;
$item_count = 0;

foreach ($_SESSION['cart'] as $index => $cart_item) {
    $product_id = $cart_item['product_id'];
    
    if (isset($products[$product_id])) {
        $product = $products[$product_id];
        $quantity = intval($cart_item['quantity']);
        $item_total = $product['price'] * $quantity;
        
        $cart_items[] = [
            'index' => $index,
            'product' => $product,
            'quantity' => $quantity,
            'item_total' => $item_total
        ];
        
        $cart_total += $item_total;
        $item_count += $quantity;
    }
}

// Calculate shipping, tax, and totals
$shipping_fee = ($cart_total > 50 || $item_count == 0) ? 0 : 5.99;
$tax_rate = 0.08; // 8%
$tax_amount = $cart_total * $tax_rate;
$grand_total = $cart_total + $shipping_fee + $tax_amount;

// Get user info for header
$user_id = $_SESSION['user_id'];
$sql = "SELECT username FROM users WHERE user_id = '$user_id'";
$result = $conn->query($sql);
$user = $result->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart - Crockery Store</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <header>
        <nav class="navbar container">
            <a href="index.php" class="logo">Elegant<span>Ware</span></a>
            <ul class="nav-links">
                <li><a href="index.php">Home</a></li>
                <li><a href="#categories">Categories</a></li>
                <li><a href="cart.php" class="active">
                    <i class="fas fa-shopping-cart"></i> Cart
                    <?php if ($item_count > 0): ?>
                        <span class="cart-badge"><?php echo $item_count; ?></span>
                    <?php endif; ?>
                </a></li>
                <li><a href="checkout.php">Checkout</a></li>
                <li><a href="user_dashboard.php">My Account</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
            <?php if (isset($user['username'])): ?>
                <div class="welcome-user" style="color: white;">
                    Welcome, <?php echo htmlspecialchars($user['username']); ?>!
                </div>
            <?php endif; ?>
        </nav>
    </header>

    <main class="cart-page">
        <div class="container">
            <!-- Cart Header -->
            <div class="cart-header">
                <h1>Shopping Cart</h1>
                <div class="cart-breadcrumb">
                    <a href="index.php">Home</a> &gt; 
                    <span>Cart</span>
                </div>
            </div>
            
            <!-- Success Message -->
            <?php if (isset($_SESSION['cart_message'])): ?>
                <div class="alert-message alert-success">
                    <i class="fas fa-check-circle"></i>
                    <?php echo $_SESSION['cart_message']; ?>
                    <?php unset($_SESSION['cart_message']); ?>
                </div>
            <?php endif; ?>
            
            <?php if (empty($cart_items)): ?>
                <!-- Empty Cart -->
                <div class="cart-empty">
                    <i class="fas fa-shopping-cart" style="font-size: 4rem; color: #ddd; margin-bottom: 20px;"></i>
                    <h3>Your shopping cart is empty</h3>
                    <p style="margin-bottom: 25px;">Add some products to your cart and they will appear here.</p>
                    <a href="index.php#products" class="btn btn-primary">
                        <i class="fas fa-shopping-bag"></i> Browse Products
                    </a>
                </div>
            <?php else: ?>
                <!-- Cart with Items -->
                <div class="cart-container">
                    <!-- Cart Items Table -->
                    <div class="cart-items">
                        <form method="POST" action="cart.php">
                            <table class="cart-table">
                                <thead>
                                    <tr>
                                        <th>Product</th>
                                        <th>Price</th>
                                        <th>Quantity</th>
                                        <th>Total</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($cart_items as $item): ?>
                                        <?php $product = $item['product']; ?>
                                        <tr>
                                            <td>
                                                <div class="cart-product">
                                                    <div class="cart-product-image">
                                                        <i class="fas <?php 
                                                            echo $product['category'] === 'Plates' ? 'fa-utensils' : 
                                                                 ($product['category'] === 'Bowls' ? 'fa-bowl-food' : 
                                                                 ($product['category'] === 'Cups' ? 'fa-mug-hot' : 'fa-box-open')); 
                                                        ?>" style="font-size: 2rem; color: #3498db;"></i>
                                                    </div>
                                                    <div>
                                                        <h4 style="margin: 0 0 5px 0; color: #2c3e50;">
                                                            <?php echo htmlspecialchars($product['name']); ?>
                                                        </h4>
                                                        <p style="margin: 0; color: #666; font-size: 0.9rem;">
                                                            <?php echo htmlspecialchars($product['description']); ?>
                                                        </p>
                                                    </div>
                                                </div>
                                            </td>
                                            <td style="font-weight: bold; color: #2c3e50;">
                                                $<?php echo number_format($product['price'], 2); ?>
                                            </td>
                                            <td>
                                                <div class="cart-quantity">
                                                    <button type="button" class="quantity-btn" 
                                                            data-index="<?php echo $item['index']; ?>"
                                                            data-change="-1">
                                                        <i class="fas fa-minus"></i>
                                                    </button>
                                                    <input type="number" 
                                                           name="quantity[<?php echo $item['index']; ?>]" 
                                                           class="quantity-input" 
                                                           value="<?php echo $item['quantity']; ?>" 
                                                           min="1" 
                                                           max="10">
                                                    <button type="button" class="quantity-btn" 
                                                            data-index="<?php echo $item['index']; ?>"
                                                            data-change="1">
                                                        <i class="fas fa-plus"></i>
                                                    </button>
                                                </div>
                                            </td>
                                            <td style="font-weight: bold; color: #27ae60;">
                                                $<?php echo number_format($item['item_total'], 2); ?>
                                            </td>
                                            <td>
                                                <button type="submit" name="remove_item" class="btn-remove">
                                                    <i class="fas fa-trash"></i> Remove
                                                </button>
                                                <input type="hidden" name="item_index" value="<?php echo $item['index']; ?>">
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                            
                            <div style="display: flex; justify-content: space-between; margin-top: 30px; padding-top: 20px; border-top: 1px solid #eee;">
                                <a href="index.php#products" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left"></i> Continue Shopping
                                </a>
                                <div style="display: flex; gap: 10px;">
                                    <button type="submit" name="update_cart" class="btn btn-primary">
                                        <i class="fas fa-sync-alt"></i> Update Cart
                                    </button>
                                    <button type="submit" name="clear_cart" class="btn btn-danger" 
                                            onclick="return confirm('Are you sure you want to clear your cart?')">
                                        <i class="fas fa-trash"></i> Clear Cart
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                    
                    <!-- Cart Summary -->
                    <div class="cart-summary">
                        <h3 style="color: #2c3e50; margin-bottom: 25px; padding-bottom: 15px; border-bottom: 2px solid #3498db;">
                            Order Summary
                        </h3>
                        
                        <!-- Shipping Info -->
                        <?php if ($shipping_fee == 0 && $cart_total > 0): ?>
                            <div style="background: #d4edda; color: #155724; padding: 10px; border-radius: 5px; margin-bottom: 20px; text-align: center;">
                                <i class="fas fa-check-circle"></i> You qualify for FREE shipping!
                            </div>
                        <?php elseif ($cart_total > 0): ?>
                            <div style="background: #fff3cd; color: #856404; padding: 10px; border-radius: 5px; margin-bottom: 20px; text-align: center;">
                                <i class="fas fa-info-circle"></i> 
                                Add $<?php echo number_format(50 - $cart_total, 2); ?> more for FREE shipping
                            </div>
                        <?php endif; ?>
                        
                        <!-- Order Summary -->
                        <div class="summary-row">
                            <span>Subtotal (<?php echo $item_count; ?> items)</span>
                            <span>$<?php echo number_format($cart_total, 2); ?></span>
                        </div>
                        
                        <div class="summary-row">
                            <span>Shipping</span>
                            <span>
                                <?php if ($shipping_fee == 0): ?>
                                    <span style="color: #27ae60;">FREE</span>
                                <?php else: ?>
                                    $<?php echo number_format($shipping_fee, 2); ?>
                                <?php endif; ?>
                            </span>
                        </div>
                        
                        <div class="summary-row">
                            <span>Tax (8%)</span>
                            <span>$<?php echo number_format($tax_amount, 2); ?></span>
                        </div>
                        
                        <div class="summary-row" style="font-size: 1.2rem; font-weight: bold; color: #2c3e50;">
                            <span>Total</span>
                            <span>$<?php echo number_format($grand_total, 2); ?></span>
                        </div>
                        
                        <!-- Checkout Button -->
                        <?php if (count($cart_items) > 0): ?>
                            <a href="checkout.php" class="btn-checkout">
                                <i class="fas fa-lock"></i> Proceed to Checkout
                            </a>
                        <?php else: ?>
                            <button class="btn-checkout" disabled>
                                <i class="fas fa-lock"></i> Proceed to Checkout
                            </button>
                        <?php endif; ?>
                        
                        <a href="index.php#products" class="btn btn-secondary" style="width: 100%; text-align: center; display: block;">
                            <i class="fas fa-shopping-bag"></i> Continue Shopping
                        </a>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </main>

    <script src="js/script.js"></script>
</body>
</html>