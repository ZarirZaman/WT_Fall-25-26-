<?php
// auth.php - Authentication functions
require_once __DIR__ . '/db.php'; // Include db.php which includes config.php

/* =====================
   LOGIN / AUTH FUNCTIONS
===================== */

/**
 * Check if any user is logged in
 */
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

/**
 * Check if logged-in user is an admin
 */
function isAdmin() {
    return isset($_SESSION['user_type']) && $_SESSION['user_type'] === 'admin';
}

/**
 * Redirect to a given URL
 */
function redirect($url) {
    header("Location: " . $url);
    exit();
}

/**
 * Login function for any user (including admin)
 */
function loginUser($emailOrUsername, $password) {
    global $conn;

    $sql = "SELECT user_id, username, email, password_hash, full_name, user_type, is_active 
            FROM users 
            WHERE (username = ? OR email = ?) 
              AND is_active = 1";

    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        error_log("Prepare failed: " . $conn->error);
        return false;
    }

    $stmt->bind_param("ss", $emailOrUsername, $emailOrUsername);
    
    if (!$stmt->execute()) {
        error_log("Execute failed: " . $stmt->error);
        return false;
    }
    
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password_hash'])) {
            // Set session variables
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['full_name'] = $user['full_name'];
            $_SESSION['user_type'] = $user['user_type'];
            return true;
        }
    }
    
    $stmt->close();
    return false;
}

/**
 * Login function specifically for admin
 */
function loginAdmin($emailOrUsername, $password) {
    global $conn;

    $sql = "SELECT user_id, username, password_hash, full_name, user_type 
            FROM users 
            WHERE (username = ? OR email = ?) 
              AND is_active = 1 
              AND user_type = 'admin'";

    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        return false;
    }

    $stmt->bind_param("ss", $emailOrUsername, $emailOrUsername);
    
    if (!$stmt->execute()) {
        return false;
    }
    
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password_hash'])) {
            // Set session variables
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['full_name'] = $user['full_name'];
            $_SESSION['user_type'] = $user['user_type'];
            return true;
        }
    }
    
    $stmt->close();
    return false;
}

/**
 * Logout function
 */
function logoutUser() {
    $_SESSION = array();

    // If it's desired to kill the session, also delete the session cookie.
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params["path"], $params["domain"],
            $params["secure"], $params["httponly"]
        );
    }

    session_destroy();
}
?>