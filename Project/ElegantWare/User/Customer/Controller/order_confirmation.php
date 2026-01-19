<?php
// Controller/order_confirmation.php - SIMPLIFIED VERSION
session_start();
require_once '../Model/config.php';
require_once '../Model/auth.php';

class OrderConfirmationController {
    
    public function index() {
        if (!isLoggedIn()) {
            redirect('login.php');
        }
        
        $order_id = $_GET['order_id'] ?? $_SESSION['last_order_id'] ?? null;
        
        if (!$order_id) {
            $_SESSION['error_message'] = 'No order found. Please place an order first.';
            redirect('cart.php');
        }
        
        // Get order details from session (saved by checkout controller)
        if (isset($_SESSION['last_order_details'])) {
            $order_details = $_SESSION['last_order_details'];
        } else {
            // Fallback data
            $order_details = $this->getFallbackOrderDetails($order_id);
        }
        
        $user_data = $this->getUserData();
        
        $data = array_merge($order_details, $user_data, [
            'order_id' => $order_id,
            'is_logged_in' => isLoggedIn()
        ]);
        
        include '../View/html/order_confirmation_view.php';
        
        // Clear order details from session after showing
        unset($_SESSION['last_order_details']);
        unset($_SESSION['last_order_id']);
    }
    
    private function getFallbackOrderDetails($order_id) {
        // Static data as fallback
        $order_items = [
            [
                'product_name' => 'Complete Dinner Set',
                'quantity' => 1,
                'price' => 149.99,
                'total' => 149.99
            ]
        ];
        
        $subtotal = 149.99;
        $shipping = 5.99;
        $tax = 12.00;
        $grand_total = 167.98;
        
        return [
            'order_items' => $order_items,
            'subtotal' => $subtotal,
            'shipping' => $shipping,
            'tax' => $tax,
            'grand_total' => $grand_total,
            'order_date' => date('F j, Y'),
            'estimated_delivery' => date('F j, Y', strtotime('+7 days')),
            'order_status' => 'Processing'
        ];
    }
    
    private function getUserData() {
        global $conn;
        $user_id = $_SESSION['user_id'] ?? 0;
        
        if (!$conn) {
            return ['user' => ['username' => 'Customer', 'email' => 'customer@example.com']];
        }
        
        $sql = "SELECT username, email, full_name FROM users WHERE user_id = ?";
        $stmt = $conn->prepare($sql);
        
        if ($stmt) {
            $stmt->bind_param("i", $user_id);
            $stmt->execute();
            $result = $stmt->get_result();
            $user = $result->fetch_assoc();
            $stmt->close();
            
            return ['user' => $user ?: ['username' => 'Customer', 'email' => 'customer@example.com']];
        }
        
        return ['user' => ['username' => 'Customer', 'email' => 'customer@example.com']];
    }
}

// Instantiate and run controller
$controller = new OrderConfirmationController();
$controller->index();
?>