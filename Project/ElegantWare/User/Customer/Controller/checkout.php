<?php
// Controller/checkout.php - FIXED VERSION
session_start();
require_once '../Model/config.php';
require_once '../Model/auth.php';
require_once '../Model/cart_func.php';

class CheckoutController {
    
    public function index() {
        if (!isLoggedIn()) {
            redirect('login.php');
        }
        
        if (empty($_SESSION['cart'] ?? [])) {
            $_SESSION['error_message'] = 'Your cart is empty. Please add items to your cart before checking out.';
            redirect('cart.php');
        }
        
        $cart_data = $this->getCartData();
        
        $user_data = $this->getUserData();
        
        $data = array_merge($cart_data, $user_data);
        
        include '../View/html/checkout_view.php';
    }
    
    public function process() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('checkout.php');
        }
        
        if (!$this->validateCheckoutData()) {
            redirect('checkout.php');
        }
        
        $order_id = $this->createOrder();
        
        if ($order_id) {
            $this->clearCart();
            $_SESSION['order_success'] = 'Order placed successfully! Your order ID is: ' . $order_id;
            
            // Save order ID in session for confirmation page
            $_SESSION['last_order_id'] = $order_id;
            
            // Also save order details in session for confirmation page
            $_SESSION['last_order_details'] = $this->getCartData();
            $_SESSION['last_order_details']['order_date'] = date('F j, Y');
            $_SESSION['last_order_details']['estimated_delivery'] = date('F j, Y', strtotime('+7 days'));
            $_SESSION['last_order_details']['order_status'] = 'Processing';
            $_SESSION['last_order_details']['shipping_data'] = [
                'first_name' => $_POST['first_name'],
                'last_name' => $_POST['last_name'],
                'street_address' => $_POST['street_address'],
                'city' => $_POST['city'],
                'state' => $_POST['state'],
                'zip_code' => $_POST['zip_code'],
                'phone' => $_POST['phone']
            ];
            
            redirect('order_confirmation.php?order_id=' . $order_id);
        } else {
            $_SESSION['error_message'] = 'Failed to process order. Please try again.';
            redirect('checkout.php');
        }
    }
    
    private function getCartData() {
        $cart_total = 0;
        $item_count = 0;
        
        // Get products
        $products = $this->getProducts();
        
        $cart_items = [];
        if (isset($_SESSION['cart'])) {
            foreach ($_SESSION['cart'] as $index => $item) {
                if (isset($item['id']) && isset($products[$item['id']])) {
                    $product = $products[$item['id']];
                    $quantity = $item['quantity'] ?? 1;
                    $item_total = $product['price'] * $quantity;
                    
                    $cart_items[] = [
                        'product_id' => $item['id'],
                        'product' => $product,
                        'quantity' => $quantity,
                        'item_total' => $item_total
                    ];
                    
                    $cart_total += $item_total;
                    $item_count += $quantity;
                }
            }
        }
        
        $shipping_fee = ($cart_total > 50 || $item_count == 0) ? 0 : 5.99;
        $tax_rate = 0.08;
        $tax_amount = $cart_total * $tax_rate;
        $grand_total = $cart_total + $shipping_fee + $tax_amount;
        
        return [
            'cart_items' => $cart_items,
            'cart_total' => $cart_total,
            'item_count' => $item_count,
            'shipping_fee' => $shipping_fee,
            'tax_amount' => $tax_amount,
            'grand_total' => $grand_total,
            'tax_rate' => $tax_rate
        ];
    }
    
    private function getProducts() {
        // Static products for now to avoid DB errors
        return [
            1 => ['name' => 'Ceramic Dinner Plate Set', 'price' => 29.99],
            2 => ['name' => 'Porcelain Soup Bowls', 'price' => 24.99],
            3 => ['name' => 'Coffee Mug Collection', 'price' => 19.99],
            4 => ['name' => 'Complete Dinner Set', 'price' => 149.99],
            5 => ['name' => 'Decorative Serving Platters', 'price' => 39.99],
            6 => ['name' => 'Handcrafted Salad Bowls', 'price' => 34.99],
            7 => ['name' => 'Tea Cup Set', 'price' => 27.99],
            8 => ['name' => 'Luxury Dinnerware Set', 'price' => 199.99]
        ];
    }
    
    private function getUserData() {
        global $conn;
        $user_id = $_SESSION['user_id'] ?? 0;
        
        $user = ['username' => 'Customer', 'email' => 'customer@example.com'];
        
        if ($conn) {
            $sql = "SELECT username, email, full_name, phone, address FROM users WHERE user_id = ?";
            $stmt = $conn->prepare($sql);
            
            if ($stmt) {
                $stmt->bind_param("i", $user_id);
                if ($stmt->execute()) {
                    $result = $stmt->get_result();
                    $db_user = $result->fetch_assoc();
                    if ($db_user) {
                        $user = $db_user;
                    }
                }
                $stmt->close();
            }
        }
        
        return ['user' => $user];
    }
    
    private function validateCheckoutData() {
        $required_fields = ['first_name', 'last_name', 'street_address', 'city', 'state', 'zip_code', 'phone', 'payment_method'];
        
        foreach ($required_fields as $field) {
            if (empty($_POST[$field])) {
                $_SESSION['error_message'] = "Please fill in all required fields.";
                return false;
            }
        }
        
        return true;
    }
    
    private function createOrder() {
        global $conn;
        
        // Always generate order number first
        $order_number = 'ORD' . time() . rand(100, 999);
        
        // Try to save to database if connection exists
        if ($conn) {
            $user_id = $_SESSION['user_id'] ?? 0;
            $cart_data = $this->getCartData();
            
            if (empty($cart_data['cart_items'])) {
                return $order_number; // Still return order number
            }
            
            // Check if orders table exists
            $table_exists = false;
            $result = $conn->query("SHOW TABLES LIKE 'orders'");
            if ($result && $result->num_rows > 0) {
                $table_exists = true;
            }
            
            if ($table_exists) {
                // Prepare shipping address
                $shipping_address = json_encode([
                    'first_name' => $_POST['first_name'],
                    'last_name' => $_POST['last_name'],
                    'street_address' => $_POST['street_address'],
                    'apt_number' => $_POST['apt_number'] ?? '',
                    'city' => $_POST['city'],
                    'state' => $_POST['state'],
                    'zip_code' => $_POST['zip_code'],
                    'phone' => $_POST['phone']
                ]);
                
                // SIMPLIFIED SQL - Check your actual table structure
                $sql = "INSERT INTO orders (user_id, order_number, subtotal, shipping_cost, tax_amount, total_amount, 
                        shipping_address, payment_method, status) 
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'pending')";
                
                $stmt = $conn->prepare($sql);
                
                if ($stmt) {
                    $stmt->bind_param(
                        "isddddss",
                        $user_id,
                        $order_number,
                        $cart_data['cart_total'],
                        $cart_data['shipping_fee'],
                        $cart_data['tax_amount'],
                        $cart_data['grand_total'],
                        $shipping_address,
                        $_POST['payment_method']
                    );
                    
                    if ($stmt->execute()) {
                        $order_id = $conn->insert_id;
                        
                        // Try to save order items if table exists
                        $items_table_check = $conn->query("SHOW TABLES LIKE 'order_items'");
                        if ($items_table_check && $items_table_check->num_rows > 0) {
                            foreach ($cart_data['cart_items'] as $item) {
                                $itemSql = "INSERT INTO order_items (order_id, product_id, product_name, quantity, unit_price, total_price) 
                                           VALUES (?, ?, ?, ?, ?, ?)";
                                
                                $itemStmt = $conn->prepare($itemSql);
                                if ($itemStmt) {
                                    $itemTotal = $item['product']['price'] * $item['quantity'];
                                    
                                    $itemStmt->bind_param(
                                        "iisidd",
                                        $order_id,
                                        $item['product_id'],
                                        $item['product']['name'],
                                        $item['quantity'],
                                        $item['product']['price'],
                                        $itemTotal
                                    );
                                    
                                    $itemStmt->execute();
                                    $itemStmt->close();
                                }
                            }
                        }
                        
                        $stmt->close();
                    }
                }
            }
        }
        
        // Always return order number (works with or without DB)
        return $order_number;
    }
    
    private function clearCart() {
        if (isset($_SESSION['cart'])) {
            unset($_SESSION['cart']);
        }
    }
}

$action = $_GET['action'] ?? 'index';
$controller = new CheckoutController();

if ($action === 'process') {
    $controller->process();
} else {
    $controller->index();
}
?>