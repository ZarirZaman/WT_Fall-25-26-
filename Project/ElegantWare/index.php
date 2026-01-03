<?php
require_once 'includes/db.php';

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Initialize cart session if not exists
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Handle adding to cart from index page
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
$cart_count = 0;
if (isset($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $item) {
        $cart_count += $item['quantity'];
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crockery Store - Premium Ceramics & Tableware</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; margin: 0; }
        header { background: linear-gradient(135deg, #2c3e50 0%, #34495e 100%); padding: 1rem 0; }
        .navbar { max-width: 1200px; margin: 0 auto; padding: 0 1.5rem; display: flex; justify-content: space-between; align-items: center; }
        .logo { color: white; font-size: 1.8rem; font-weight: bold; text-decoration: none; }
        .logo span { color: #e74c3c; }
        .nav-links { display: flex; list-style: none; gap: 2rem; align-items: center; }
        .nav-links a { color: white; text-decoration: none; }
        .home-hero { background: linear-gradient(rgba(0,0,0,0.7), rgba(0,0,0,0.7)), url('https://images.unsplash.com/photo-1556909114-f6e7ad7d3136?ixlib=rb-4.0.3'); background-size: cover; color: white; padding: 6rem 0; text-align: center; }
        .hero-content { max-width: 800px; margin: 0 auto; }
        .hero-title { font-size: 3.5rem; margin-bottom: 1rem; }
        .hero-subtitle { font-size: 1.2rem; margin-bottom: 2rem; }
        .btn { padding: 0.9rem 2rem; border-radius: 50px; text-decoration: none; font-weight: bold; display: inline-block; margin: 0 0.5rem; }
        .btn-primary { background: #e74c3c; color: white; }
        .btn-secondary { background: transparent; color: white; border: 2px solid white; }
    </style>
</head>
<body>
    <!-- Header -->
    <header>
        <nav class="navbar">
            <a href="index.php" class="logo">Elegant<span>Ware</span></a>
            <ul class="nav-links">
                <li><a href="index.php" class="active">Home</a></li>
                <li><a href="#categories">Categories</a></li>
                <li><a href="cart.php" class="cart-link">
                    <i class="fas fa-shopping-cart"></i> Cart
                    <?php if ($cart_count > 0): ?>
                        <span class="cart-badge"><?php echo $cart_count; ?></span>
                    <?php endif; ?>
                </a></li>
                <li><a href="checkout.php">Checkout</a></li>
                <?php if(isset($_SESSION['user_id'])): ?>
                    <li><a href="user_dashboard.php">Dashboard</a></li>
                    <li><a href="logout.php">Logout</a></li>
                <?php else: ?>
                    <li><a href="login.php">Login</a></li>
                    <li><a href="register.php">Register</a></li>
                <?php endif; ?>
            </ul>
        </nav>
    </header>

    <!-- Main Content -->
    <main>
        <!-- Hero Section -->
        <section class="home-hero">
            <div class="hero-content">
                <h1 class="hero-title">Crafted by Nature, Inspired by Earth</h1>
                <p class="hero-subtitle">
                    Discover our collection of handmade ceramics, where art meets functionality and every piece tells a story of craftsmanship.
                </p>
                <div class="hero-buttons">
                    <?php if(!isset($_SESSION['user_id'])): ?>
                        <a href="register.php" class="btn btn-primary">
                            <i class="fas fa-user-plus"></i> Get Started
                        </a>
                    <?php endif; ?>
                    <a href="#products" class="btn btn-secondary">
                        <i class="fas fa-shopping-bag"></i> Shop Now
                    </a>
                </div>
            </div>
        </section>

        <!-- Features Section -->
        <section class="features-section" style="padding: 4rem 0; background: white; text-align: center;">
            <div class="container" style="max-width: 1200px; margin: 0 auto; padding: 0 1.5rem;">
                <h2 style="font-size: 2.5rem; color: #2c3e50; margin-bottom: 3rem;">Why Choose Our Store</h2>
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 2rem;">
                    <div style="text-align: center; padding: 2rem; background: #f8f9fa; border-radius: 10px;">
                        <div style="font-size: 2.5rem; color: #e74c3c; margin-bottom: 1rem;">
                            <i class="fas fa-shipping-fast"></i>
                        </div>
                        <h3>Free Shipping</h3>
                        <p>On all orders over $50</p>
                    </div>
                    
                    <div style="text-align: center; padding: 2rem; background: #f8f9fa; border-radius: 10px;">
                        <div style="font-size: 2.5rem; color: #e74c3c; margin-bottom: 1rem;">
                            <i class="fas fa-undo-alt"></i>
                        </div>
                        <h3>30-Day Returns</h3>
                        <p>Easy return policy</p>
                    </div>
                    
                    <div style="text-align: center; padding: 2rem; background: #f8f9fa; border-radius: 10px;">
                        <div style="font-size: 2.5rem; color: #e74c3c; margin-bottom: 1rem;">
                            <i class="fas fa-headset"></i>
                        </div>
                        <h3>24/7 Support</h3>
                        <p>Customer service always available</p>
                    </div>
                    
                    <div style="text-align: center; padding: 2rem; background: #f8f9fa; border-radius: 10px;">
                        <div style="font-size: 2.5rem; color: #e74c3c; margin-bottom: 1rem;">
                            <i class="fas fa-award"></i>
                        </div>
                        <h3>Premium Quality</h3>
                        <p>Handcrafted with care</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Products Section -->
        <section id="products" style="padding: 4rem 0; background: #f8f9fa;">
            <div class="container" style="max-width: 1200px; margin: 0 auto; padding: 0 1.5rem;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 3rem;">
                    <h2 style="font-size: 2.5rem; color: #2c3e50;">Featured Products</h2>
                    <a href="#products" style="color: #e74c3c; text-decoration: none; font-weight: bold;">
                        View All <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
                
                <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 2rem;">
                    <?php foreach ($featured_products as $product): ?>
                        <div style="background: white; border-radius: 10px; overflow: hidden; box-shadow: 0 5px 15px rgba(0,0,0,0.08);">
                            <div style="height: 200px; background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%); display: flex; align-items: center; justify-content: center;">
                                <i class="fas <?php echo $product['image_class']; ?>" style="font-size: 3rem; color: #ddd;"></i>
                            </div>
                            <div style="padding: 1.5rem;">
                                <h3 style="color: #2c3e50; margin-bottom: 0.5rem;"><?php echo htmlspecialchars($product['name']); ?></h3>
                                <span style="background: #e8f4fc; color: #3498db; padding: 0.3rem 0.8rem; border-radius: 20px; font-size: 0.8rem; font-weight: bold; display: inline-block; margin-bottom: 0.5rem;"><?php echo $product['category']; ?></span>
                                <p style="color: #666; font-size: 0.9rem; margin: 10px 0;">
                                    <?php echo htmlspecialchars($product['description']); ?>
                                </p>
                                <div style="font-size: 1.5rem; font-weight: bold; color: #e74c3c; margin: 0.5rem 0;">
                                    $<?php echo number_format($product['price'], 2); ?>
                                </div>
                                <div style="margin-top: 1rem;">
                                    <?php if(isset($_SESSION['user_id'])): ?>
                                        <a href="index.php?add_to_cart=<?php echo $product['id']; ?>" 
                                           style="display: flex; align-items: center; justify-content: center; gap: 0.5rem; background: linear-gradient(135deg, #3498db 0%, #2980b9 100%); color: white; padding: 0.8rem 1.5rem; border-radius: 25px; text-decoration: none; font-weight: bold;"
                                           onclick="return addToCart(event, <?php echo $product['id']; ?>)">
                                            <i class="fas fa-cart-plus"></i> Add to Cart
                                        </a>
                                    <?php else: ?>
                                        <a href="login.php" 
                                           style="display: flex; align-items: center; justify-content: center; gap: 0.5rem; background: linear-gradient(135deg, #95a5a6 0%, #7f8c8d 100%); color: white; padding: 0.8rem 1.5rem; border-radius: 25px; text-decoration: none; font-weight: bold;">
                                            <i class="fas fa-sign-in-alt"></i> Login to Buy
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>

        <!-- Categories Section -->
        <section id="categories" style="padding: 4rem 0; background: white;">
            <div class="container" style="max-width: 1200px; margin: 0 auto; padding: 0 1.5rem;">
                <h2 style="font-size: 2.5rem; color: #2c3e50; text-align: center; margin-bottom: 3rem;">Browse by Category</h2>
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 2rem;">
                    <a href="#products" onclick="filterByCategory('Plates')" style="text-align: center; padding: 2rem; background: #f8f9fa; border-radius: 10px; text-decoration: none; color: inherit;">
                        <div style="width: 80px; height: 80px; margin: 0 auto 1rem; background: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 2rem; color: #e74c3c;">
                            <i class="fas fa-utensils"></i>
                        </div>
                        <h3 style="margin-bottom: 0.5rem;">Plates</h3>
                        <p style="font-size: 0.9rem; color: #666;">Dinner plates, salad plates, dessert plates</p>
                    </a>
                    
                    <a href="#products" onclick="filterByCategory('Bowls')" style="text-align: center; padding: 2rem; background: #f8f9fa; border-radius: 10px; text-decoration: none; color: inherit;">
                        <div style="width: 80px; height: 80px; margin: 0 auto 1rem; background: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 2rem; color: #e74c3c;">
                            <i class="fas fa-bowl-food"></i>
                        </div>
                        <h3 style="margin-bottom: 0.5rem;">Bowls</h3>
                        <p style="font-size: 0.9rem; color: #666;">Soup bowls, salad bowls, serving bowls</p>
                    </a>
                    
                    <a href="#products" onclick="filterByCategory('Cups')" style="text-align: center; padding: 2rem; background: #f8f9fa; border-radius: 10px; text-decoration: none; color: inherit;">
                        <div style="width: 80px; height: 80px; margin: 0 auto 1rem; background: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 2rem; color: #e74c3c;">
                            <i class="fas fa-mug-hot"></i>
                        </div>
                        <h3 style="margin-bottom: 0.5rem;">Cups & Mugs</h3>
                        <p style="font-size: 0.9rem; color: #666;">Coffee mugs, tea cups, espresso cups</p>
                    </a>
                    
                    <a href="#products" onclick="filterByCategory('Sets')" style="text-align: center; padding: 2rem; background: #f8f9fa; border-radius: 10px; text-decoration: none; color: inherit;">
                        <div style="width: 80px; height: 80px; margin: 0 auto 1rem; background: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 2rem; color: #e74c3c;">
                            <i class="fas fa-box-open"></i>
                        </div>
                        <h3 style="margin-bottom: 0.5rem;">Complete Sets</h3>
                        <p style="font-size: 0.9rem; color: #666;">Dinner sets, tea sets, gift sets</p>
                    </a>
                </div>
            </div>
        </section>

        <!-- Call to Action Section -->
        <section style="padding: 5rem 0; background: linear-gradient(135deg, #2c3e50 0%, #34495e 100%); color: white; text-align: center;">
            <div class="container" style="max-width: 1200px; margin: 0 auto; padding: 0 1.5rem;">
                <div>
                    <h2 style="font-size: 2.5rem; margin-bottom: 1.5rem;">Ready to Transform Your Dining Experience?</h2>
                    <p style="font-size: 1.1rem; margin-bottom: 2.5rem; max-width: 600px; margin-left: auto; margin-right: auto;">Join thousands of satisfied customers who have elevated their table settings with our premium ceramics.</p>
                    <div style="display: flex; gap: 1rem; justify-content: center;">
                        <?php if(isset($_SESSION['user_id'])): ?>
                            <a href="cart.php" class="btn btn-primary">
                                <i class="fas fa-shopping-cart"></i> View Cart (<?php echo $cart_count; ?>)
                            </a>
                        <?php else: ?>
                            <a href="register.php" class="btn btn-primary">
                                <i class="fas fa-user-plus"></i> Create Account
                            </a>
                            <a href="login.php" class="btn btn-secondary">
                                <i class="fas fa-sign-in-alt"></i> Login
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <!-- Footer -->
    <footer style="background: #2c3e50; color: white; padding: 4rem 0 2rem;">
        <div class="container" style="max-width: 1200px; margin: 0 auto; padding: 0 1.5rem;">
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 3rem; margin-bottom: 3rem;">
                <div>
                    <h3 style="font-size: 1.3rem; margin-bottom: 1.5rem;">Crockery Store</h3>
                    <p style="color: #bdc3c7; margin-bottom: 1.5rem;">Premium ceramics and tableware for modern living. Crafted with care, designed for life.</p>
                    <div style="display: flex; gap: 1rem;">
                        <a href="#" style="color: white; font-size: 1.2rem;"><i class="fab fa-facebook"></i></a>
                        <a href="#" style="color: white; font-size: 1.2rem;"><i class="fab fa-instagram"></i></a>
                        <a href="#" style="color: white; font-size: 1.2rem;"><i class="fab fa-pinterest"></i></a>
                        <a href="#" style="color: white; font-size: 1.2rem;"><i class="fab fa-twitter"></i></a>
                    </div>
                </div>
                
                <div>
                    <h3 style="font-size: 1.3rem; margin-bottom: 1.5rem;">Quick Links</h3>
                    <ul style="list-style: none; padding: 0;">
                        <li style="margin-bottom: 0.8rem;"><a href="index.php" style="color: #bdc3c7; text-decoration: none;">Home</a></li>
                        <li style="margin-bottom: 0.8rem;"><a href="#products" style="color: #bdc3c7; text-decoration: none;">Products</a></li>
                        <li style="margin-bottom: 0.8rem;"><a href="#categories" style="color: #bdc3c7; text-decoration: none;">Categories</a></li>
                        <li><a href="#about" style="color: #bdc3c7; text-decoration: none;">About Us</a></li>
                    </ul>
                </div>
                
                <div>
                    <h3 style="font-size: 1.3rem; margin-bottom: 1.5rem;">Categories</h3>
                    <ul style="list-style: none; padding: 0;">
                        <li style="margin-bottom: 0.8rem;"><a href="#products" onclick="filterByCategory('Plates')" style="color: #bdc3c7; text-decoration: none;">Plates</a></li>
                        <li style="margin-bottom: 0.8rem;"><a href="#products" onclick="filterByCategory('Bowls')" style="color: #bdc3c7; text-decoration: none;">Bowls</a></li>
                        <li style="margin-bottom: 0.8rem;"><a href="#products" onclick="filterByCategory('Cups')" style="color: #bdc3c7; text-decoration: none;">Cups & Mugs</a></li>
                        <li><a href="#products" onclick="filterByCategory('Sets')" style="color: #bdc3c7; text-decoration: none;">Complete Sets</a></li>
                    </ul>
                </div>
                
                <div>
                    <h3 style="font-size: 1.3rem; margin-bottom: 1.5rem;">Contact Info</h3>
                    <ul style="list-style: none; padding: 0;">
                        <li style="margin-bottom: 1rem; display: flex; align-items: center; gap: 0.8rem;"><i class="fas fa-map-marker-alt" style="color: #e74c3c;"></i> 123 Ceramic Street, Art District</li>
                        <li style="margin-bottom: 1rem; display: flex; align-items: center; gap: 0.8rem;"><i class="fas fa-phone" style="color: #e74c3c;"></i> (123) 456-7890</li>
                        <li style="display: flex; align-items: center; gap: 0.8rem;"><i class="fas fa-envelope" style="color: #e74c3c;"></i> info@crockerystore.com</li>
                    </ul>
                </div>
            </div>
            
            <div style="text-align: center; padding-top: 2rem; border-top: 1px solid #34495e; color: #95a5a6;">
                <p>&copy; <?php echo date('Y'); ?> Crockery Store. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script>
        // Basic add to cart functionality
        function addToCart(event, productId) {
            event.preventDefault();
            alert('Product added to cart!');
            window.location.href = 'index.php?add_to_cart=' + productId;
            return false;
        }
        
        function filterByCategory(category) {
            alert('Filtering by: ' + category);
            return false;
        }
    </script>
</body>
</html>