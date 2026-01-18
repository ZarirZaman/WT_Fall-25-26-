<?php
// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

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
    header("Location: $url");
    exit();
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
    $stmt->execute();
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
    return false;
}

/**
 * Logout function
 */
function logoutAdmin() {
    $_SESSION = array();

    if (session_status() === PHP_SESSION_ACTIVE) {
        session_destroy();
    }

    session_start();
}
?>
