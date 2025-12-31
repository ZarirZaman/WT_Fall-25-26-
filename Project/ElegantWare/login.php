<?php
require_once 'includes/auth.php';
$error = '';
$success = '';
if (isLoggedIn()) {
    redirect('index.php');
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    
    $result = loginUser($email, $password);
    
    if ($result['success']) {
        // Redirect based on user type
        if ($result['user_type'] === 'admin') {
            redirect('admin/admin_dashboard.php');
        } else {
            redirect('index.php');
        }
    } else {
        $error = $result['message'];
    }
}

?>
<!DOCTYPE HTML>
<html>
    <head>
        <title>Login - ElegantWare</title>
        <link rel="stylesheet" href="css/styles.css">
    </head>
    <body>
        <header>
            <nav class="navbar container">
                <a class="navbar-brand" href="index.php">ElegantWare</a>
                

            </nav>
        </header>
        <main>
            <div class="auth-container">
                <div class ="auth-box">
                    <h1 class="auth-title">Login</h1>
                     <?php if ($error): ?>
                    <div class="alert alert-danger">
                        <?php echo htmlspecialchars($error); ?>
                    </div>
                <?php endif; ?>
                
                <?php if (isset($_GET['registered']) && $_GET['registered'] == 'true'): ?>
                    <div class="alert alert-success">
                        Registration successful! Please login.
                    </div>
                <?php endif; ?>
                    <form id="loginForm" method="post" action="">
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" id="email" name="email" required>
                        </div>
                        <div class="form-group">                        
                            <label for="password">Password</label>                       
                            <input type="password" id="password" name="password" required>
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">Login</button>
                        </div>
                        <div class="auth-links">
                            <p>Don't have an account? <a href="register.php">Register </a></p>
                        </div>

                    </form>
                </div>
            </div>
        </main>
        <script src="js/script.js"></script>
    </body>