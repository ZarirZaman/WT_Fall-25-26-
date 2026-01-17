<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ElegantWare - Premium Ceramics & Tableware</title>
    <link rel="stylesheet" href="<?php echo ASSETS_URL; ?>css/index.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <header>
        <nav class="navbar">
            <div class="container">
                <a href="index.php" class="logo">Elegant<span>Ware</span></a>
                <div class="search-container">
                            <form id="searchForm" method="get" action="index.php" class="search-form">
                                <div class="search-input-group">
                                    <input type="text"
                                    name="search" id="searchInput" 
                                    class="search-input" placeholder="Search products..." 
                                    value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>" autocomplete="off">
                        <button type="submit" class="search-btn">
                            <i class="fas fa-search"></i>
                        </button>
                        <div class="search-suggestions" id="searchSuggestions"></div>
                        </div>
                            </form>
                        </div>
                <ul class="nav-links">
                    <li><a href="index.php" class="active">Home</a></li>
                    <li><a href="#categories">Categories</a></li>
                    <li>
    <a href="cart.php" class="cart-link">
        <i class="fas fa-shopping-cart"></i> Cart
        <?php 
        // Check if cart count exists and is valid
        if (!empty($data['cart_count']) && is_numeric($data['cart_count']) && $data['cart_count'] > 0): 
        ?>
            <span class="cart-badge"><?php echo htmlspecialchars($data['cart_count']); ?></span>
        <?php endif; ?>
    </a>
</li>
                    <li><a href="#products">Checkout</a></li>
                    
                        
                    
                    <?php if ($data['user'] && isset($data['user']['username'])): ?>
                <div class="welcome-user">
                    <i class="fas fa-user-circle"></i> Welcome, <?php echo htmlspecialchars($data['user']['username']); ?>!
                </div>
            <?php endif; ?>
                    <?php if(isset($data['is_logged_in']) && $data['is_logged_in']): ?>
                        <li><a href="#dashboard">Profile</a></li>
                        <li><a href="logout.php">Logout</a></li>
                    <?php else: ?>
                        <li><a href="login.php">Login</a></li>
                        <li><a href="register.php">Register</a></li>
                    <?php endif; ?>
                </ul>
            </div>
        </nav>
    </header>
    <main>
        <section class="home-hero">
            <div class="hero-content">
                <h1 class="hero-title">Crafted by Nature, Inspired by Earth</h1>
                <p class="hero-subtitle">
                    Discover our collection of handmade ceramics, where art meets functionality and every piece tells a story of craftsmanship.
                </p>
                <div class="hero-buttons">
                    <?php if(!isset($data['is_logged_in']) || !$data['is_logged_in']): ?>
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
        <!-- Categories Section -->
        <section id="categories" class="categories-section">
            <div class="container">
                <h2 class="section-title">Browse by Category</h2>
                <div class="categories-grid">
                    <a href="index.php?category=plates" class="category-card">
                        <div class="category-icon">
                            <i class="fas fa-utensils"></i>
                        </div>
                        <h3>Plates</h3>
                        <p>Dinner plates, salad plates, dessert plates</p>
                    </a>
                    
                    <a href="index.php?category=bowls" class="category-card">
                        <div class="category-icon">
                            <i class="fas fa-bowl-food"></i>
                        </div>
                        <h3>Bowls</h3>
                        <p>Soup bowls, salad bowls, serving bowls</p>
                    </a>
                    
                    <a href="index.php?category=cups" class="category-card">
                        <div class="category-icon">
                            <i class="fas fa-mug-hot"></i>
                        </div>
                        <h3>Cups & Mugs</h3>
                        <p>Coffee mugs, tea cups, espresso cups</p>
                    </a>
                    
                    <a href="index.php?category=sets" class="category-card">
                        <div class="category-icon">
                            <i class="fas fa-box-open"></i>
                        </div>
                        <h3>Complete Sets</h3>
                        <p>Dinner sets, tea sets, gift sets</p>
                    </a>
                </div>
            </div>
        </section>

        <!-- Products Section -->
        <section id="products" class="products-section">
           <div class="container">
                <div class="section-header">
                   <span class="product-count">(<?php echo $data['total_products']; ?> products)</span>
            
            <!-- Search Results Message -->
            <?php if (!empty($data['search_term'])): ?>
                <div class="search-results-message">
                    <i class="fas fa-search"></i>
                    Search results for: "<strong><?php echo htmlspecialchars($data['search_term']); ?></strong>"
                    <?php if ($data['total_products'] === 0): ?>
                        - No products found
                    <?php endif; ?>
                    <a href="index.php" class="clear-search-btn">
                        <i class="fas fa-times"></i> Clear Search
                    </a>
                </div>
            <?php endif; ?>
            
            <div class="sort-controls">
                <span class="sort-label">Sort by: </span>
                <select id="sortProducts" class="sort-select">
                    <option value="high-low" <?php echo $data['current_sort'] == 'high-low' ? 'selected' : ''; ?>>Price: High to Low</option>                    
                    <option value="low-high" <?php echo $data['current_sort'] == 'low-high' ? 'selected' : ''; ?>>Price: Low to High</option>                    
                    <option value="name-asc" <?php echo $data['current_sort'] == 'name-asc' ? 'selected' : ''; ?>>Name: A to Z</option>                    
                    <option value="name-desc" <?php echo $data['current_sort'] == 'name-desc' ? 'selected' : ''; ?>>Name: Z to A</option>
                </select>
            </div>
                </div>
            </div>
        
        <div class="products-grid">
            <?php if (isset($data['featured_products']) && !empty($data['featured_products'])): ?>
                <?php foreach ($data['featured_products'] as $product): 
                    // Split description into short and full versions
                    $short_desc = (isset($product['description']) && strlen($product['description']) > 80) 
                        ? substr($product['description'], 0, 80) . '...' 
                        : (isset($product['description']) ? $product['description'] : '');
                    $has_long_desc = isset($product['description']) && strlen($product['description']) > 80;
                ?>
                    <div class="product-card" 
                         data-price="<?php echo isset($product['price']) ? $product['price'] : '0.00'; ?>" 
                         data-name="<?php echo isset($product['name']) ? htmlspecialchars($product['name']) : ''; ?>">
                        <div class="product-image">
                            <i class="fas <?php echo isset($product['image_class']) ? $product['image_class'] : 'fa-box-open'; ?>"></i>
                        </div>
                        <div class="product-info">
                            <h3><?php echo isset($product['name']) ? htmlspecialchars($product['name']) : 'Product'; ?></h3>
                            <span class="product-category"><?php echo isset($product['category']) ? $product['category'] : 'Uncategorized'; ?></span>
                            
                            <!-- Product Description with View More -->
                            <div class="product-description-container">
                                <p class="product-description">
                                    <?php if ($has_long_desc): ?>
                                        <span class="description-short"><?php echo htmlspecialchars($short_desc); ?></span>
                                        <span class="description-full" style="display: none;">
                                            <?php echo htmlspecialchars($product['description']); ?>
                                        </span>
                                        <a href="#" class="view-more-link">
                                            View More
                                        </a>
                                    <?php else: ?>
                                        <span class="description-full">
                                            <?php echo isset($product['description']) ? htmlspecialchars($product['description']) : 'No description available.'; ?>
                                        </span>
                                    <?php endif; ?>
                                </p>
                            </div>
                            
                            <div class="product-price">
                                $<?php echo isset($product['price']) ? number_format($product['price'], 2) : '0.00'; ?>
                                <span class="price-badge"><?php echo isset($product['category']) ? $product['category'] : 'Uncategorized'; ?></span>
                            </div>
                            
                            <div class="product-rating">
                                <?php
                                // Generate random rating for demo
                                $rating = rand(35, 50) / 10;
                                $full_stars = floor($rating);
                                $half_star = ($rating - $full_stars) >= 0.5;
                                ?>
                                <div class="stars">
                                    <?php for ($i = 1; $i <= 5; $i++): ?>
                                        <?php if ($i <= $full_stars): ?>
                                            <i class="fas fa-star"></i>
                                        <?php elseif ($half_star && $i == $full_stars + 1): ?>
                                            <i class="fas fa-star-half-alt"></i>
                                        <?php else: ?>
                                            <i class="far fa-star"></i>
                                        <?php endif; ?>
                                    <?php endfor; ?>
                                    <span class="rating-value"><?php echo number_format($rating, 1); ?></span>
                                </div>
                            </div>
                            
                            <div class="product-actions">
                                <?php if(isset($data['is_logged_in']) && $data['is_logged_in']): ?>
                                    <form method="POST" action="cart.php" class="add-to-cart-form" style="display: inline;">
                                    <input type="hidden" name="product_id" value="<?php echo isset($product['id']) ? $product['id'] : ''; ?>">
                                    <input type="hidden" name="quantity" value="1">
                                    <button type="submit" name="add_to_cart" class="add-to-cart-btn quick-add-btn" 
                                    data-product-id="<?php echo isset($product['id']) ? $product['id'] : ''; ?>"
                                    data-product-name="<?php echo isset($product['name']) ? htmlspecialchars($product['name']) : 'Product'; ?>">
                                    <i class="fas fa-cart-plus"></i> Add to Cart
                                    </button>
                                   </form>
                                <?php else: ?>
                                    <a href="login.php" 
                                       class="add-to-cart-btn login-to-buy-btn">
                                        <i class="fas fa-sign-in-alt"></i> Login to Buy
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="no-products">
                    <i class="fas fa-search fa-3x"></i>
                    <h3>No products available</h3>
                    <p>Check back soon for our featured products!</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>

        <!-- Call to Action Section -->
        <section class="cta-section">
            <div class="container">
                <div class="cta-content">
                    <h2>Ready to Transform Your Dining Experience?</h2>
                    <p>Join thousands of satisfied customers who have elevated their table settings with our premium ceramics.</p>
                    <div class="cta-buttons">
                        <?php if(isset($data['is_logged_in']) && $data['is_logged_in']): ?>
                            <a href="cart.php" class="btn btn-primary">
                                <i class="fas fa-shopping-cart"></i> View Cart (<?php echo isset($data['cart_count']) ? $data['cart_count'] : 0; ?>)
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
    <footer>
        <div class="container">
            <div class="footer-content">
                <div class="footer-section">
                    <h3>ElegantWare</h3>
                    <p>Premium ceramics and tableware for modern living. Crafted with care, designed for life.</p>
                    <div class="social-links">
                        <a href="#"><i class="fab fa-facebook"></i></a>
                        <a href="#"><i class="fab fa-instagram"></i></a>
                        <a href="#"><i class="fab fa-pinterest"></i></a>
                        <a href="#"><i class="fab fa-twitter"></i></a>
                    </div>
                </div>
                
                <div class="footer-section">
                    <h3>Quick Links</h3>
                    <ul>
                        <li><a href="index.php">Home</a></li>
                        <li><a href="#products">Products</a></li>
                        <li><a href="#categories">Categories</a></li>
                        <li><a href="contact.php">Contact Us</a></li>
                    </ul>
                </div>
                
                <div class="footer-section">
                    <h3>Categories</h3>
                    <ul>
                        <li><a href="index.php?category=plates">Plates</a></li>
                        <li><a href="index.php?category=bowls">Bowls</a></li>
                        <li><a href="index.php?category=cups">Cups & Mugs</a></li>
                        <li><a href="index.php?category=sets">Complete Sets</a></li>
                    </ul>
                </div>
                
                <div class="footer-section">
                    <h3>Contact Info</h3>
                    <ul class="contact-info">
                        <li><i class="fas fa-map-marker-alt"></i> 123 Ceramic Street, Art District</li>
                        <li><i class="fas fa-phone"></i> 01629902495</li>
                        <li><i class="fas fa-envelope"></i> info@elegantware.com</li>
                    </ul>
                </div>
            </div>
            
            <div class="footer-bottom">
                <p>&copy; <?php echo date('Y'); ?> ElegantWare. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <!-- Loading Overlay -->
    <div class="loading-overlay" id="loadingOverlay" style="display: none;">
        <div class="loading-spinner">
            <i class="fas fa-spinner fa-spin fa-3x"></i>
        </div>
    </div>

    <script src="<?php echo ASSETS_URL; ?>js/index.js"></script>
</body>
</html>