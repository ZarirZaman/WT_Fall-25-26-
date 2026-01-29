<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - ElegantWare</title>
    <link rel="stylesheet" href="/WT_Fall-25-26-/Project/Public/customer/css/cart.css">
    <link rel="stylesheet" href="/WT_Fall-25-26-/Project/Public/customer/css/checkout.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* Additional checkout-specific styles */
        .checkout-wrapper {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 30px;
            margin-top: 30px;
        }
        
        .checkout-section {
            background: white;
            border-radius: 8px;
            padding: 25px;
            margin-bottom: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
            margin-bottom: 15px;
        }
        
        .form-group {
            margin-bottom: 15px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: 500;
            color: #555;
        }
        
        .form-group input,
        .form-group select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
        }
        
        .payment-methods {
            margin-top: 15px;
        }
        
        .payment-option {
            margin-bottom: 10px;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        
        .payment-option input {
            margin-right: 10px;
        }
        
        .btn-checkout-submit {
            width: 100%;
            padding: 15px;
            background: #d4a762;
            color: white;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            margin-top: 20px;
        }
        
        .btn-checkout-submit:hover {
            background: #c49550;
        }
        
        @media (max-width: 768px) {
            .checkout-wrapper {
                grid-template-columns: 1fr;
            }
            
            .form-row {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <header>
        <nav class="navbar container">
            <nav>
                <a href="index.php" class="logo">Elegant<span>Ware</span></a>
                <br>                        
                    <form id="searchForm" method="get" action="index.php" class="search-form">                                
                        <div class="search-input-group">                                    
                            <input type="text"name="search" id="searchInput" class="search-input" placeholder="Search products..." value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>" autocomplete="off">                            
                            <button type="submit" class="search-btn"> <i class="fas fa-search"></i></button>                                                   
                            <div class="search-suggestions" id="searchSuggestions"></div>                        
                        </div>                            
                    </form>                        
                <br>
                <ul class="nav-links">
                    <li><a href="index.php">Home</a></li>
                    <li><a href="index.php#categories">Categories</a></li>
                    <li><a href="cart.php">
                        <i class="fas fa-shopping-cart"></i> Cart
                        <?php if ($data['item_count'] > 0): ?>
                            <span class="cart-badge"><?php echo $data['item_count']; ?></span>
                        <?php endif; ?>
                    </a></li>
                    <li><a href="checkout.php" class="active">Checkout</a></li>
                    <?php if ($data['user'] && isset($data['user']['username'])): ?>
                    <div class="welcome-user">
                        <i class="fas fa-user-circle"></i> Welcome, <?php echo htmlspecialchars($data['user']['username']); ?>!
                    </div>
                    <?php endif; ?>
                    <li><a href="logout.php">Logout</a></li>
                </ul>
            </nav>
        </nav>
    </header>

    <main class="cart-page">
        <div class="container">
            <!-- Checkout Header -->
            <div class="cart-header">
                <h1>Checkout</h1>
                <div class="cart-breadcrumb">
                    <a href="index.php">Home</a> &gt; 
                    <a href="cart.php">Cart</a> &gt; 
                    <span>Checkout</span>
                </div>
            </div>
            
            <!-- Error Message -->
            <?php if (isset($_SESSION['error_message'])): ?>
                <div class="alert-message alert-error">
                    <i class="fas fa-exclamation-circle"></i>
                    <?php echo $_SESSION['error_message']; ?>
                    <?php unset($_SESSION['error_message']); ?>
                </div>
            <?php endif; ?>
            
            <div class="checkout-wrapper">
                <!-- Left Column: Shipping & Payment -->
                <div class="checkout-left">
                    <form method="POST" action="checkout.php?action=process">
                        <!-- Shipping Address Section -->
                        <section class="checkout-section">
                            <h2>Shipping Address</h2>
                            <div class="address-form">
                                <div class="form-row">
                                    <div class="form-group">
                                        <label>First Name *</label>
                                        <input type="text" name="first_name" required 
                                               value="<?php echo $data['user']['full_name'] ? explode(' ', $data['user']['full_name'])[0] : ''; ?>">
                                    </div>
                                    <div class="form-group">
                                        <label>Last Name *</label>
                                        <input type="text" name="last_name" required
                                               value="<?php echo $data['user']['full_name'] ? (explode(' ', $data['user']['full_name'])[1] ?? '') : ''; ?>">
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <label>Street Address *</label>
                                    <input type="text" name="street_address" required
                                           value="<?php echo $data['user']['address'] ?? ''; ?>">
                                </div>
                                
                                <div class="form-row">
                                    <div class="form-group">
                                        <label>City *</label>
                                        <input type="text" name="city" required>
                                    </div>
                                    <div class="form-group">
                                        <label>State *</label>
                                        <select name="state" required>
                                            <option value="">Select State</option>
                                            <option value="AL">Alabama</option>
                                            <option value="AK">Alaska</option>
                                            <option value="BD">Bangladesh</option>
                                            <option value="IN">India</option>
                                            <option value="PAK">Pakistan</option>
                                            <option value="NEV">Nevada</option>
                                            <option value="AUS">Australia</option>
                                            <option value="NZ">New Zealand</option>
                                            <option value="POR">Portugal</option>
                                            <option value="SPN">Spain</option>
                                        </select>
                                    </div>
                                </div>
                                
                                <div class="form-row">
                                    <div class="form-group">
                                        <label>ZIP Code *</label>
                                        <input type="text" name="zip_code" required>
                                    </div>
                                    <div class="form-group">
                                        <label>Phone *</label>
                                        <input type="tel" name="phone" required
                                               value="<?php echo $data['user']['phone'] ?? ''; ?>">
                                    </div>
                                </div>
                            </div>
                        </section>

                        <!-- Payment Method Section -->
                        <section class="checkout-section">
                            <h2>Payment Method</h2>
                            <div class="payment-methods">
                                <div class="payment-option">
                                    <input type="radio" id="credit-card" name="payment_method" value="credit_card" checked required>
                                    <label for="credit-card">Credit/Debit Card</label>
                                </div>
                                
                                <div class="payment-option">
                                    <input type="radio" id="paypal" name="payment_method" value="paypal">
                                    <label for="paypal">PayPal</label>
                                </div>
                                
                                <div class="payment-option">
                                    <input type="radio" id="cod" name="payment_method" value="cod">
                                    <label for="cod">Cash on Delivery</label>
                                </div>
                            </div>
                        </section>
                        
                        <!-- Terms and Conditions -->
                        <div class="terms-agreement">
                            <input type="checkbox" id="terms" required>
                            <label for="terms">
                                I agree to the <a href="#">Terms & Conditions</a> and <a href="#">Privacy Policy</a>
                            </label>
                        </div>
                        
                        <button type="submit" class="btn-checkout-submit">
                            <i class="fas fa-lock"></i> Place Your Order
                        </button>
                    </form>
                </div>

                <!-- Right Column: Order Summary -->
                <div class="checkout-right">
                    <div class="cart-summary">
                        <h3 class="summary-title">Order Summary</h3>
                        
                        <?php if ($data['shipping_fee'] == 0 && $data['cart_total'] > 0): ?>
                            <div class="shipping-info free-shipping">
                                <i class="fas fa-check-circle"></i> You qualify for FREE shipping!
                            </div>
                        <?php elseif ($data['cart_total'] > 0): ?>
                            <div class="shipping-info shipping-offer">
                                <i class="fas fa-info-circle"></i> 
                                Add $<?php echo number_format(50 - $data['cart_total'], 2); ?> more for FREE shipping
                            </div>
                        <?php endif; ?>
                        
                        <!-- Cart Items -->
                        <?php foreach ($data['cart_items'] as $item): ?>
                        <div class="cart-item-summary">
                            <div class="item-info">
                                <h4><?php echo htmlspecialchars($item['product']['name']); ?></h4>
                                <p>Qty: <?php echo $item['quantity']; ?></p>
                            </div>
                            <div class="item-price">
                                $<?php echo number_format($item['item_total'], 2); ?>
                            </div>
                        </div>
                        <?php endforeach; ?>
                        
                        <!-- Summary Totals -->
                        <div class="summary-row">
                            <span>Subtotal (<?php echo $data['item_count']; ?> items)</span>
                            <span>$<?php echo number_format($data['cart_total'], 2); ?></span>
                        </div>
                        
                        <div class="summary-row">
                            <span>Shipping</span>
                            <span>
                                <?php if ($data['shipping_fee'] == 0): ?>
                                    <span class="free-text">FREE</span>
                                <?php else: ?>
                                    $<?php echo number_format($data['shipping_fee'], 2); ?>
                                <?php endif; ?>
                            </span>
                        </div>
                        
                        <div class="summary-row">
                            <span>Tax (<?php echo ($data['tax_rate'] * 100); ?>%)</span>
                            <span>$<?php echo number_format($data['tax_amount'], 2); ?></span>
                        </div>
                        
                        <div class="summary-row grand-total">
                            <span>Total</span>
                            <span>$<?php echo number_format($data['grand_total'], 2); ?></span>
                        </div>
                        
                        <a href="cart.php" class="continue-shopping">
                            <i class="fas fa-arrow-left"></i> Back to Cart
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script>
        document.querySelector('form').addEventListener('submit', function(e) {
            const terms = document.getElementById('terms');
            if (!terms.checked) {
                e.preventDefault();
                alert('Please agree to the Terms & Conditions');
                terms.focus();
                return false;
            }
            if (!confirm('Are you sure you want to place this order?')) {
                e.preventDefault();
                return false;
            }
        });
        <script src="/WT_Fall-25-26-/Project/Public/customer/js/checkout.js"></script>
    </script>
</body>
</html>