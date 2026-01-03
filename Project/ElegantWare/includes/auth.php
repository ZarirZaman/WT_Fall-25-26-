<?php
// Start session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once 'includes/db.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);

// CHECK LOGIN

function isLoggedIn() {
    return isset($_SESSION['user_id']);
}


function redirect($path) {
    header("Location: $path");
    exit;
}

// REGISTER USER 
function registerUser($data) {
    global $conn;

    // Get form data
    $fullName = trim($data['fullName']);
    $username = trim($data['username']);
    $email    = trim($data['email']);
    $password = $data['password'];
    $confirm  = $data['confirm_password'];

    // Empty check
    if (empty($fullName) || empty($username) || empty($email) || empty($password) || empty($confirm)) {
        return ['success' => false, 'message' => 'All fields are required'];
    }

    // Password match
    if ($password !== $confirm) {
        return ['success' => false, 'message' => 'Passwords do not match'];
    }

    // Password strength
    if (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$/', $password)) {
        return ['success' => false, 'message' => 'Password must be at least 8 characters with uppercase, lowercase and number'];
    }

    // Check if email exists
    $checkEmailSql = "SELECT user_id FROM users WHERE email = ?";
    $checkEmail = $conn->prepare($checkEmailSql);
    
    if ($checkEmail === false) {
        error_log("Email check prepare failed: " . $conn->error);
        return ['success' => false, 'message' => 'System error. Please try again.'];
    }
    
    $checkEmail->bind_param("s", $email);
    $checkEmail->execute();
    $checkEmail->store_result();

    if ($checkEmail->num_rows > 0) {
        return ['success' => false, 'message' => 'Email already registered'];
    }
    
    // Check if username exists
    $checkUserSql = "SELECT user_id FROM users WHERE username = ?";
    $checkUser = $conn->prepare($checkUserSql);
    
    if ($checkUser === false) {
        error_log("Username check prepare failed: " . $conn->error);
        return ['success' => false, 'message' => 'System error. Please try again.'];
    }
    
    $checkUser->bind_param("s", $username);
    $checkUser->execute();
    $checkUser->store_result();

    if ($checkUser->num_rows > 0) {
        return ['success' => false, 'message' => 'Username already taken'];
    }

    // Insert user - CORRECTED FOR YOUR TABLE COLUMNS
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    $userType = 'user'; // Default user type
    $isActive = 1; // Active by default
    $currentDate = date('Y-m-d H:i:s');
    
    // Note: user_id is auto-increment, registration_date has default, last_login is NULL
    $insertSql = "INSERT INTO users (username, email, password_hash, full_name, user_type, is_active, registration_date) 
                  VALUES (?, ?, ?, ?, ?, ?, ?)";
    
    $stmt = $conn->prepare($insertSql);
    
    if ($stmt === false) {
        $errorMsg = "SQL Error: " . $conn->error . " | Query: " . $insertSql;
        error_log($errorMsg);
        return ['success' => false, 'message' => 'Database error: ' . htmlspecialchars($conn->error)];
    }
    
    // Bind parameters: username, email, password_hash, full_name, user_type, is_active, registration_date
    $stmt->bind_param("sssssis", $username, $email, $hashedPassword, $fullName, $userType, $isActive, $currentDate);

    if ($stmt->execute()) {
        return ['success' => true];
    } else {
        error_log("Execute failed: " . $stmt->error);
        return ['success' => false, 'message' => 'Registration failed: ' . $stmt->error];
    }
}

// LOGIN USER 
function loginUser($username, $password) {
    global $conn;
    
    $sql = "SELECT user_id, username, password_hash, full_name, user_type FROM users 
            WHERE (username = ? OR email = ?) AND is_active = 1";
    
    $stmt = $conn->prepare($sql);
    
    if ($stmt === false) {
        error_log("Login prepare failed: " . $conn->error);
        return ['success' => false, 'message' => 'System error'];
    }
    
    $stmt->bind_param("ss", $username, $username);
    
    if (!$stmt->execute()) {
        return ['success' => false, 'message' => 'System error'];
    }
    
    $result = $stmt->get_result();
    
    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        
        if (password_verify($password, $user['password_hash'])) {
            // Update last login
            $updateSql = "UPDATE users SET last_login = NOW() WHERE user_id = ?";
            $updateStmt = $conn->prepare($updateSql);
            $updateStmt->bind_param("i", $user['user_id']);
            $updateStmt->execute();
            
            // Set session variables
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['full_name'] = $user['full_name'];
            $_SESSION['user_type'] = $user['user_type'];
            
            return ['success' => true];
        }
    }
    
    return ['success' => false, 'message' => 'Invalid username/email or password'];
}

// LOGOUT USER
function logoutUser() {
    session_destroy();
    session_start(); // Start fresh session for messages if needed
}

// CHECK IF USER IS ADMIN
function isAdmin() {
    return isset($_SESSION['user_type']) && $_SESSION['user_type'] === 'admin';
}