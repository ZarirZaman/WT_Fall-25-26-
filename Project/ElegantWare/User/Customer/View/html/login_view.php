<!DOCTYPE HTML>
<html>
    <head>
        <title>Login - ElegantWare</title>
        <link rel="stylesheet" href="<?php echo ASSETS_URL; ?>css/login.css">
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
                <h1 class="auth-title">Login to Your Account</h1>
                <?php if ($message): ?>
                    <div class="alert alert-<?php echo $message_type; ?>">
                        <?php echo htmlspecialchars($message); ?>
                    </div>
                <?php endif; ?>
                <form id="loginForm" method="POST" action="">
                    <div class="form-group">
                        <label for="email">Email or Username</label>
                        <input type="text" id="email" name="email" required>
                    </div>
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" id="password" name="password" required>
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary">Login</button>
                    </div>
                </form>
                <div class="auth-links">
                    <p>Don't have an account? <a href="register.php">Register</a></p>
                </div>
            </div>
        </div>
        </main>
        <script src="<?php echo ASSETS_URL; ?>js/login.js"></script>
    </body>
</html>