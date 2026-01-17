<?php

function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function isAdmin() {
    return isset($_SESSION['user_type']) && $_SESSION['user_type'] === 'admin';
}

function redirect($url) {
    header("Location: $url");
    exit();
}

function registerUser($data) {
    global $conn;
    
    // Extract data from array
    $fullName = $data['fullName'];
    $username = $data['username'];
    $email = $data['email'];
    $password = $data['password'];
    
    // Check if user already exists
    if (userExists($username, $email)) {
        return [
            'success' => false,
            'message' => 'Username or email already exists'
        ];
    }
    
    // Hash password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    $userType = 'user';
    $isActive = 1;
    $currentDate = date('Y-m-d H:i:s');
    
    $sql = "INSERT INTO users (username, email, password_hash, full_name, user_type, is_active, registration_date) 
            VALUES (?, ?, ?, ?, ?, ?, ?)";
    
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        return [
            'success' => false,
            'message' => 'Database error: ' . $conn->error
        ];
    }
    
    $stmt->bind_param("sssssis", $username, $email, $hashedPassword, $fullName, $userType, $isActive, $currentDate);
    
    if ($stmt->execute()) {
        return [
            'success' => true,
            'message' => 'Registration successful!'
        ];
    } else {
        return [
            'success' => false,
            'message' => 'Registration failed: ' . $stmt->error
        ];
    }
}

function loginUser($email, $password) {
    global $conn;
    
    $sql = "SELECT user_id, username, password_hash, full_name, user_type FROM users 
            WHERE (username = ? OR email = ?) AND is_active = 1";
    
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        return false;
    }
    
    $stmt->bind_param("ss", $email, $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        
        if (password_verify($password, $user['password_hash'])) {
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['full_name'] = $user['full_name'];
            $_SESSION['user_type'] = $user['user_type'];
            return true;
        }
    }
    
    return false;
}

function logoutUser() {
    $_SESSION = array();
    
    if (session_status() === PHP_SESSION_ACTIVE) {
        session_destroy();
    }
    
    session_start();
}

function userExists($username, $email) {
    global $conn;
    
    $sql = "SELECT user_id FROM users WHERE username = ? OR email = ?";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        return false;
    }
    
    $stmt->bind_param("ss", $username, $email);
    $stmt->execute();
    $stmt->store_result();
    
    return $stmt->num_rows > 0;
}
?>