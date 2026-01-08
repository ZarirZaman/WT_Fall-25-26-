<?php
// Include config to start session
require_once '../Model/config.php';

// Include auth functions
require_once '../Model/auth.php';

// Call the logout function from the model
logoutUser();

// Redirect to login page
redirect('login.php');
?>