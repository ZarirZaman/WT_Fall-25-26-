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
            redirect('');
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
        <link rel="stylesheet" href="/ElegantWareUserCustomer/css/style.css">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
    </head>
    <body>
        <header>
            <nav class="navbar">
                <div class="container">
                    <a class="logo" href="index.php">Elegant<span>Ware</span></a>
                </div>
            </nav>
        </header>
        <main>
            <div class="auth-container">
                <div class="auth-box">
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
                    
                    <form id="loginForm" method="POST" action="">
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" id="email" name="email" required 
                                   placeholder="Enter your email">
                        </div>
                        <div class="form-group">
                            <label for="password">Password</label>
                            <input type="password" id="password" name="password" required 
                                   placeholder="Enter your password">
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">Login</button>
                        </div>
                        <div class="auth-links">
                            <p>Don't have an account? <a href="register.php">Register</a></p>
                        </div>
                    </form>
                </div>
            </div>
        </main>
        <script src="js/script.js"></script>
    </body>
</html>