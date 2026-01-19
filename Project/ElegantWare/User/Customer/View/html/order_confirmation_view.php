<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Confirmation - ElegantWare</title>
    <link rel="stylesheet" href="<?php echo ASSETS_URL; ?>css/cart.css">
    <link rel="stylesheet" href="<?php echo ASSETS_URL; ?>css/confirmation.css">
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
                        </div>
                    </form>
                </div>
                <ul class="nav-links">
                    <li><a href="index.php">Home</a></li>
                    <li><a href="#categories">Categories</a></li>
                    <li>
                        <a href="cart.php" class="cart-link">
                            <i class="fas fa-shopping-cart"></i> Cart
                            <span class="cart-badge">0</span>
                        </a>
                    </li>
                    <li><a href="checkout.php">Checkout</a></li>
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

    <main class="cart-page">
        <div class="container">
            <!-- Breadcrumb -->
            <div class="cart-header">
                <h1>Order Confirmation</h1>
                <div class="cart-breadcrumb">
                    <a href="index.php">Home</a> &gt; 
                    <a href="cart.php">Cart</a> &gt; 
                    <a href="checkout.php">Checkout</a> &gt; 
                    <span>Confirmation</span>
                </div>
            </div>
            
            <div class="confirmation-wrapper">
                <!-- Print Button -->
                <button class="print-button" onclick="window.print()">
                    <i class="fas fa-print"></i> Print Receipt
                </button>
                
                <!-- Confirmation Header -->
                <div class="confirmation-header">
                    <div class="confirmation-icon">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <h1 class="confirmation-title">Thank You for Your Order!</h1>
                    <p class="confirmation-subtitle">Your order has been successfully placed and is being processed.</p>
                    
                    <div class="order-id">
                        Order ID: <strong><?php echo htmlspecialchars($data['order_id']); ?></strong>
                    </div>
                    
                    <div class="status-badge">
                        <i class="fas fa-clock"></i> Status: <?php echo $data['order_status']; ?>
                    </div>
                </div>
                
                <!-- Order Information -->
                <div class="info-section">
                    <h2>Order Information</h2>
                    <div class="info-grid">
                        <div class="info-card">
                            <h4><i class="fas fa-calendar-alt"></i> Order Date</h4>
                            <p><?php echo $data['order_date']; ?></p>
                        </div>
                        
                        <div class="info-card">
                            <h4><i class="fas fa-truck"></i> Estimated Delivery</h4>
                            <p><?php echo $data['estimated_delivery']; ?></p>
                            <p class="small-text">(7-10 business days)</p>
                        </div>
                        
                        <div class="info-card">
                            <h4><i class="fas fa-user"></i> Customer</h4>
                            <p><?php echo htmlspecialchars($data['user']['full_name'] ?? 'Customer'); ?></p>
                            <p><?php echo htmlspecialchars($data['user']['email'] ?? ''); ?></p>
                        </div>
                    </div>
                </div>
                <!-- Order Items -->
                <div class="info-section">
                     <h2>Order Details</h2>
                        <div class="order-items">
                        <?php if (isset($data['order_items']) && is_array($data['order_items'])): ?>
                        <?php foreach ($data['order_items'] as $item): ?>
                        <div class="order-item">
                          <div class="item-details">
                            <h4><?php echo htmlspecialchars($item['product_name'] ?? 'Product'); ?></h4>
                             <p class="item-quantity">Quantity: <?php echo $item['quantity'] ?? 0; ?></p>
                            <p>Unit Price: $<?php echo number_format($item['price'] ?? 0, 2); ?></p>
                        </div>                          
                        <div class="item-price">                        
                            $<?php echo number_format($item['total'] ?? 0, 2); ?>
                        </div>                
                    </div>
                    <?php endforeach; ?>        
                    <?php else: ?>            
                        <div class="no-items">                
                            <p>No items found in this order.</p>            
                        </div>        
                        <?php endif; ?>                    
                </div>
                <!-- Order Total -->
                <div class="order-total">    
                    <div class="total-row">        
                        <span>Subtotal:</span>       
                        <span>$<?php echo number_format($data['subtotal'] ?? 0, 2); ?></span>                    
                    </div>
                    <div class="total-row">        
                        <span>Shipping:</span>        
                        <span>            
                            <?php if (($data['shipping'] ?? 0) == 0): ?>                
                                <span class="free-text">FREE</span>            
                                <?php else: ?>                
                                    $<?php echo number_format($data['shipping'] ?? 0, 2); ?>            
                                <?php endif; ?>        
                        </span>                    
                            </div>    
                            <div class="total-row">        
                                <span>Tax (8%):</span>        
                                <span>$<?php echo number_format($data['tax'] ?? 0, 2); ?></span>    
                            </div>        
                            <div class="total-row grand-total">        
                                <span>Total Amount:</span>        
                                <span>$<?php echo number_format($data['grand_total'] ?? 0, 2); ?></span>    
                            </div>
                </div>
                 
                <!-- Next Steps -->
                <div class="info-section">
                    <h2>What Happens Next?</h2>
                    <div class="info-grid">
                        <div class="info-card">
                            <h4><i class="fas fa-envelope"></i> Email Confirmation</h4>
                            <p>A confirmation email has been sent to your registered email address.</p>
                        </div>
                        
                        <div class="info-card">
                            <h4><i class="fas fa-box"></i> Order Processing</h4>
                            <p>Your order is being prepared for shipment. We'll notify you when it ships.</p>
                        </div>
                        
                        <div class="info-card">
                            <h4><i class="fas fa-headset"></i> Need Help?</h4>
                            <p>Contact our support team if you have any questions about your order.</p>
                            <p class="small-text">Email: support@elegantware.com</p>
                        </div>
                    </div>
                </div>
                
                <!-- Action Buttons -->
                <div class="action-buttons">
                    <a href="index.php#products" class="btn-continue">
                        <i class="fas fa-shopping-bag"></i> Continue Shopping
                    </a>
                    
                    <a href="order_history.php" class="btn-view-orders">
                        <i class="fas fa-history"></i> View Order History
                    </a>
                </div>
                
                <!-- Important Notes -->
                <div class="info-section" style="border-bottom: none; text-align: center;">
                    <p class="small-text" style="color: #666;">
                        <i class="fas fa-info-circle"></i>
                        Please keep your order ID (#<?php echo htmlspecialchars($data['order_id']); ?>) for future reference.
                        You can track your order status in your account dashboard.
                    </p>
                </div>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer>
        <div class="container">
            <div class="footer-content">
                <div class="footer-section">
                    <h3>ElegantWare</h3>
                    <p>Premium ceramics and tableware for modern living.</p>
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
                    <h3>Customer Service</h3>
                    <ul>
                        <li><a href="order_history.php">Order History</a></li>
                        <li><a href="shipping.php">Shipping Policy</a></li>
                        <li><a href="returns.php">Returns & Refunds</a></li>
                        <li><a href="faq.php">FAQ</a></li>
                    </ul>
                </div>
                
                <div class="footer-section">
                    <h3>Contact Info</h3>
                    <ul class="contact-info">
                        <li><i class="fas fa-map-marker-alt"></i> 123 Ceramic Street, Art District</li>
                        <li><i class="fas fa-phone"></i> 01629902495</li>
                        <li><i class="fas fa-envelope"></i> support@elegantware.com</li>
                    </ul>
                </div>
            </div>
            
            <div class="footer-bottom">
                <p>&copy; <?php echo date('Y'); ?> ElegantWare. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script src="<?php echo ASSETS_URL; ?>js/confirmation.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.5.1/dist/confetti.browser.min.js"></script>
</body>
</html>