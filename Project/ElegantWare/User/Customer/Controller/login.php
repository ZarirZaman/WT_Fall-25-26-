<?php
require_once '../Model/config.php';
require_once '../Model/auth.php';

$message = '';
$message_type = '';

// Check if user is already logged in
if (isLoggedIn()) {
    redirect('index.php');
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    $result = loginUser($email, $password);

    if (is_array($result) && isset($result['success']) && $result['success'] === true) {
        if ($result['user_type'] === 'admin') {
            redirect('admin/admin_login.php');
        } else {
            redirect('dashboard.php');
        }
    } else {
        // Handle both array and boolean returns from loginUser()
        if (is_array($result)) {
            $message = $result['message'] ?? 'Invalid email or password';
        } else {
            $message = 'Invalid email or password';
        }
        $message_type = 'danger';
    }
}

if (isset($_GET['registered']) && $_GET['registered'] === 'true') {
    $message = 'Registration successful! Please login with your credentials.';
    $message_type = 'success';
}

include '../View/html/login_view.php';
?>