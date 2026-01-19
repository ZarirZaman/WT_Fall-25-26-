<?php
// Model/shipping.php

class Shipping {
    private $db;

    public function __construct() {
        global $conn;
        $this->db = $conn;
        if (!$this->db) die("Database connection not found.");
    }

    public function saveAddress($userId, $data) {
        // Only columns that exist in table
        $sql = "INSERT INTO shipping_addresses 
                (user_id, first_name, last_name, street_address, city, state, zip_code, phone, is_default) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = $this->db->prepare($sql);
        if (!$stmt) die("Prepare failed: " . $this->db->error);

        // Assign variables
        $first_name = $data['first_name'];
        $last_name = $data['last_name'];
        $street_address = $data['street_address'];
        $city = $data['city'];
        $state = $data['state'];
        $zip_code = $data['zip_code'];
        $phone = $data['phone'];
        $is_default = $data['is_default'] ?? 0;

        // 9 variables for 9 columns
        $stmt->bind_param(
            "isssssssi",
            $userId,
            $first_name,
            $last_name,
            $street_address,
            $city,
            $state,
            $zip_code,
            $phone,
            $is_default
        );

        if ($stmt->execute()) {
            $stmt->close();
            return true;
        } else {
            error_log("Insert failed: " . $stmt->error);
            $stmt->close();
            return false;
        }
    }

    public function getUserAddresses($userId) {
        $sql = "SELECT * FROM shipping_addresses WHERE user_id = ? ORDER BY is_default DESC";
        $stmt = $this->db->prepare($sql);
        if (!$stmt) die("Prepare failed: " . $this->db->error);

        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        $addresses = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        return $addresses;
    }
}
?>
