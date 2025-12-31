<?php
require_once 'functions.php';

function registerUser($data) {
    global $conn;
    
    $username = $data['username'];
    $email = $data['email'];
    $password = $data['password'];
    $confirm_password = $data['confirm_password'];
    $full_name = $data['full_name'] ?? '';
    
    // Basic validation
    if (empty($username) || empty($email) || empty($password)) {
        return ['success' => false, 'message' => 'All fields are required'];
    }
    
    if ($password !== $confirm_password) {
        return ['success' => false, 'message' => 'Passwords do not match'];
    }
    
    // Check if user exists
    $sql = "SELECT user_id FROM users WHERE email = '$email' OR username = '$username'";
    $result = $conn->query($sql);
    
    if ($result->num_rows > 0) {
        return ['success' => false, 'message' => 'Username or email already exists'];
    }
    
    // Hash password
    $password_hash = password_hash($password, PASSWORD_DEFAULT);
    
    // Insert user
    $sql = "INSERT INTO users (username, email, password_hash, full_name) VALUES ('$username', '$email', '$password_hash', '$full_name')";
    
    if ($conn->query($sql)) {
        return ['success' => true, 'message' => 'Registration successful! Please login.'];
    } else {
        return ['success' => false, 'message' => 'Registration failed'];
    }
}

function loginUser($email, $password) {
    global $conn;
    
    // Simple admin override for testing
    if ($email === 'admin@crockery.com' && $password === 'admin123') {
        // Check if admin exists
        $sql = "SELECT * FROM users WHERE email = 'admin@crockery.com'";
        $result = $conn->query($sql);
        
        if ($result->num_rows === 0) {
            // Create admin
            $hash = password_hash('admin123', PASSWORD_DEFAULT);
            $conn->query("INSERT INTO users (username, email, password_hash, full_name, user_type) VALUES ('admin', 'admin@crockery.com', '$hash', 'Admin User', 'admin')");
            
            $sql = "SELECT * FROM users WHERE email = 'admin@crockery.com'";
            $result = $conn->query($sql);
        }
        
        $user = $result->fetch_assoc();
        
        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['email'] = $user['email'];
        $_SESSION['user_type'] = $user['user_type'];
        
        return ['success' => true, 'user_type' => 'admin'];
    }
    
    // Normal user login
    $sql = "SELECT * FROM users WHERE email = '$email'";
    $result = $conn->query($sql);
    
    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        
        if (password_verify($password, $user['password_hash'])) {
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['user_type'] = $user['user_type'];
            
            return ['success' => true, 'user_type' => $user['user_type']];
        }
    }
    
    return ['success' => false, 'message' => 'Invalid email or password'];
}
?>