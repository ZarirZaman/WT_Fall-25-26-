<!DOCTYPE HTML>
<html>
    <head>
        <title>Register - ELegantWare</title>
    </head>
    <body>
        <h1>Create Account</h1>
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
                <input type="checkbox" onclick="togglePasswordVisibility('password')"> Show Password
            </div>
            <div  class="form-group">
                <label for="confirm_password">Confirm Password *</label>
                <input type="password" id="confirm_password" name="confirm_password" required>
                <input type="checkbox" onclick="togglePasswordVisibility('confirm_password')"> Show Password
            </div>
            <div class="form-group">
                        <button type="submit" class="btn btn-success">Register</button>
            </div>
        </form>
        <div class="auth-links">
                    <p>Already have an account? Login </a></p>
        </div>
    </body>
