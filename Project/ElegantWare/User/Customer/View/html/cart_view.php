<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart - ElegantWare</title>
    <link rel="stylesheet" href="<?php echo ASSETS_URL; ?>css/cart.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <header>
        <nav class="navbar container">
            <nav>
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
                <li><a href="index.php">Home</a></li>
                <li><a href="index.php#categories">Categories</a></li>
                <li><a href="cart.php" class="active">
                    <i class="fas fa-shopping-cart"></i> Cart
                    <?php if ($data['item_count'] > 0): ?>
                        <span class="cart-badge"><?php echo $data['item_count']; ?></span>
                    <?php endif; ?>
                </a></li>
                <li><a href="checkout.php">Checkout</a></li>
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
            <!-- Cart Header -->
            <div class="cart-header">
                <h1>Shopping Cart</h1>
                <div class="cart-breadcrumb">
                    <a href="index.php">Home</a> &gt; 
                    <span>Cart</span>
                </div>
            </div>
            
            <!-- Success Message -->
            <?php if ($data['cart_message']): ?>
                <div class="alert-message alert-success">
                    <i class="fas fa-check-circle"></i>
                    <?php echo $data['cart_message']; ?>
                </div>
            <?php endif; ?>
            
            <?php if (empty($data['cart_items'])): ?>
                <!-- Empty Cart -->
                <div class="cart-empty">
                    <i class="fas fa-shopping-cart"></i>
                    <h3>Your shopping cart is empty</h3>
                    <p>Add some products to your cart and they will appear here.</p>
                    <a href="index.php#products" class="btn btn-primary">
                        <i class="fas fa-shopping-bag"></i> Browse Products
                    </a>
                </div>
            <?php else: ?>
                <!-- Cart with Items -->
                <div class="cart-container">
                    <!-- Cart Items Table -->
                    <div class="cart-items">
                        <form method="POST" action="cart.php" id="cartForm">
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
                                    <?php foreach ($data['cart_items'] as $item): ?>
                                        <?php $product = $item['product']; ?>
                                        <tr>
                                            <td>
                                                <div class="cart-product">
                                                    <div class="cart-product-image">
                                                        <i class="fas <?php 
                                                            echo $product['category'] === 'Plates' ? 'fa-utensils' : 
                                                                 ($product['category'] === 'Bowls' ? 'fa-bowl-food' : 
                                                                 ($product['category'] === 'Cups' ? 'fa-mug-hot' : 'fa-box-open')); 
                                                        ?>"></i>
                                                    </div>
                                                    <div>
                                                        <h4><?php echo htmlspecialchars($product['name']); ?></h4>
                                                        <p><?php echo htmlspecialchars($product['description']); ?></p>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="price-cell">
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
                                            <td class="total-cell">
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
                            
                            <div class="cart-actions">
                                <a href="index.php#products" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left"></i> Continue Shopping
                                </a>
                                <div class="action-buttons">
                                    <button type="submit" name="update_cart" class="btn btn-primary">
                                        <i class="fas fa-sync-alt"></i> Update Cart
                                    </button>
                                    <button type="submit" name="clear_cart" class="btn btn-danger" id="clearCartBtn">
                                        <i class="fas fa-trash"></i> Clear Cart
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                    
                    <!-- Cart Summary -->
                    <div class="cart-summary">
                        <h3 class="summary-title">Order Summary</h3>
                        
                        <!-- Shipping Info -->
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
                        
                        <!-- Order Summary -->
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
                        
                        <!-- Checkout Button -->
                        <?php if (count($data['cart_items']) > 0): ?>
                            <a href="checkout.php" class="btn-checkout">
                                <i class="fas fa-lock"></i> Proceed to Checkout
                            </a>
                        <?php else: ?>
                        <a href="checkout.php" class="btn-checkout">
                            <i class="fas fa-lock"></i> Proceed to Checkout
                        </a>
                        <?php endif; ?>
                        
                        <a href="index.php#products" class="continue-shopping">
                            <i class="fas fa-shopping-bag"></i> Continue Shopping
                        </a>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </main>

    <!-- Cart Notification -->
    <div class="cart-notification" id="cartNotification"></div>

    <script src="<?php echo ASSETS_URL; ?>js/cart.js"></script>
</body>
</html>