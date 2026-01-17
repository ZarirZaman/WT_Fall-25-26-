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

    if ($result['success'] === true) {

        // Redirect based on user type
        if ($result['user_type'] === 'admin') {
            redirect('admin/dashboard.php');
        } else {
            redirect('dashboard.php');
        }

    } else {
        $message = $result['message'] ?? 'Invalid email or password';
        $message_type = 'danger';
    }
}

// Check for registration success message
if (isset($_GET['registered']) && $_GET['registered'] === 'true') {
    $message = 'Registration successful! Please login with your credentials.';
    $message_type = 'success';
}

// Include the view
include '../View/html/login_view.php';
?>
