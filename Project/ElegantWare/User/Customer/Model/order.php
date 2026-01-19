<?php
// Model/Order.php

require_once 'db.php';

class Order {
    private $db;

    public function __construct() {
        global $conn; // use mysqli connection
        $this->db = $conn;
        if (!$this->db) die("Database connection not found.");
    }

    // Create a new order
    public function createOrder($userId, $total, $shipping, $tax, $paymentMethod) {
        $sql = "INSERT INTO orders (user_id, total_amount, shipping_cost, tax_amount, payment_method, status)  
                VALUES (?, ?, ?, ?, ?, 'pending')";

        $stmt = $this->db->prepare($sql);
        if (!$stmt) die("Prepare failed: " . $this->db->error);

        // Bind parameters
        // i = int, d = double (float), s = string
        $stmt->bind_param("iddds", $userId, $total, $shipping, $tax, $paymentMethod);

        $result = $stmt->execute();
        if ($result) {
            $orderId = $this->db->insert_id; // get the last inserted ID
            $stmt->close();
            return $orderId;
        } else {
            error_log("Insert order failed: " . $stmt->error);
            $stmt->close();
            return false;
        }
    }

    // Add a single order item
    public function addOrderItem($orderId, $productId, $quantity, $price) {
        $sql = "INSERT INTO order_items (order_id, product_id, quantity, price) 
                VALUES (?, ?, ?, ?)";

        $stmt = $this->db->prepare($sql);
        if (!$stmt) die("Prepare failed: " . $this->db->error);

        $stmt->bind_param("iiid", $orderId, $productId, $quantity, $price);

        $result = $stmt->execute();
        $stmt->close();

        return $result;
    }

    // Get all orders of a user
    public function getUserOrders($userId) {
        $sql = "SELECT * FROM orders WHERE user_id = ? ORDER BY order_date DESC";
        $stmt = $this->db->prepare($sql);
        if (!$stmt) die("Prepare failed: " . $this->db->error);

        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        $orders = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        return $orders;
    }
}
?>
