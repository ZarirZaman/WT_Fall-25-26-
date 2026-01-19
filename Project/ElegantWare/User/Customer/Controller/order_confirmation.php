<?php
session_start();

require_once '../Model/config.php';
require_once '../Model/auth.php';
require_once '../Model/shipping.php';

class OrderConfirmationController {
    public function index() {
        if (!isLoggedIn()) redirect('login.php');

        $order_id = $_GET['order_id'] ?? $_SESSION['last_order_id'] ?? null;
        if (!$order_id) {
            $_SESSION['error_message'] = 'No order found.';
            redirect('cart.php');
        }

        $order_details = $_SESSION['last_order_details'] ?? $this->getFallbackOrderDetails($order_id);
        $user_data = $this->getUserData();
        $data = array_merge($order_details, $user_data, ['order_id'=>$order_id]);

        // Save shipping address
        if (isset($_SESSION['shipping_info'], $_SESSION['user_id'])) {
            $shipping = new Shipping();
            if ($shipping->saveAddress($_SESSION['user_id'], $_SESSION['shipping_info'])) {
                $_SESSION['message'] = 'Shipping address saved successfully!';
            } else {
                $_SESSION['error_message'] = 'Failed to save shipping address.';
            }
            unset($_SESSION['shipping_info']);
        }

        include '../View/html/order_confirmation_view.php';

        unset($_SESSION['last_order_details'], $_SESSION['last_order_id']);
    }

    private function getFallbackOrderDetails($order_id) {
        return [
            'order_items' => [['product_name'=>'Complete Dinner Set','quantity'=>1,'price'=>149.99,'total'=>149.99]],
            'subtotal'=>149.99, 'shipping'=>5.99, 'tax'=>12.00, 'grand_total'=>167.98,
            'order_date'=>date('F j, Y'),
            'estimated_delivery'=>date('F j, Y', strtotime('+7 days')),
            'order_status'=>'Processing'
        ];
    }

    private function getUserData() {
        global $conn;
        $user_id = $_SESSION['user_id'] ?? 0;
        $stmt = $conn->prepare("SELECT username,email,full_name FROM users WHERE user_id=?");
        if ($stmt) {
            $stmt->bind_param("i",$user_id);
            $stmt->execute();
            $result = $stmt->get_result();
            $user = $result->fetch_assoc() ?: ['username'=>'Customer','email'=>'customer@example.com'];
            $stmt->close();
            return ['user'=>$user];
        }
        return ['user'=>['username'=>'Customer','email'=>'customer@example.com']];
    }
}

$controller = new OrderConfirmationController();
$controller->index();
?>
