<?php
// Include config to start session and get database connection
require_once '../Model/config.php';

// Include auth functions
require_once '../Model/auth.php';

$message = '';
$message_type = '';

// Check if user is already logged in
if (isLoggedIn()) {
    redirect('index.php');
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $result = registerUser($_POST);
    
    if ($result['success']) {
        redirect('login.php?registered=true');
    } else {
        $message = $result['message'];
        $message_type = 'danger';
    }
}

// Include the view
include '../View/html/register_view.php';
?>