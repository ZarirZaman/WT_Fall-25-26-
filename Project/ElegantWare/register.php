<?php
require_once 'includes/auth.php';
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
?>
<!DOCTYPE HTML>
<html>
    <head>
        <title>Register - ELegantWare</title>
        <link rel="stylesheet" href="css/style.css">
    </head>
    <body>
        <main>
        <div class="auth-container">
            <div class="auth-box">
                <h1 class="auth-title">Create Account</h1>
                <?php if ($message): ?>
                    <div class="alert alert-<?php echo $message_type; ?>">
                        <?php echo htmlspecialchars($message); ?>
                    </div>
                <?php endif; ?>
                <form id="registerForm" method="POST" action="">
                    <div class="form-group">
                        <label for="fullName">Full Name</label>
                        <input type="text" id="fullName" name="fullName" required>
                    </div>
                    <div class="form-group">
                        <label for="username">Username</label>
                        <input type="text" id="username" name="username" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" required>
                    </div>
                    <div class="form-group">                
                        <label for="password">Password</label>                
                        <input type="password" id="password" name="password" required>                
                        <small style="color: grey;">                    
                            Password must be at least 8 characters with uppercase, lowercase and number.                
                        </small>
                    </div>            
                    <div  class="form-group">                
                        <label for="confirm_password">Confirm Password *</label>                
                        <input type="password" id="confirm_password" name="confirm_password" required>                         
                    </div>            
                    <div class="form-group">                        
                        <button type="submit" class="btn btn-success">Register</button>            
                    </div>
                </form>
                <div class="auth-links">
                    <p>Already have an account? <a href="login.php">Login</a></p>        
                </div>
            </div>
        </div>
        </main>
            <script src="js/script.js"></script>
    </body>
