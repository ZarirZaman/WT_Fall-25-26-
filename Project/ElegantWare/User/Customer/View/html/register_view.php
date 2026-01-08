<!DOCTYPE HTML>
<html>
    <head>
        <title>Register - ElegantWare</title>
        <link rel="stylesheet" href="<?php echo ASSETS_URL; ?>css/register.css">
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
                        <button type="submit" class="btn btn-primary">Register</button>            
                    </div>
                </form>
                <div class="auth-links">
                    <p>Already have an account? <a href="login.php">Login</a></p>        
                </div>
            </div>
        </div>
        </main>
        <script src="<?php echo ASSETS_URL; ?>js/register.js"></script>
    </body>
</html>