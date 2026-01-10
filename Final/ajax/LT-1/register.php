<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Real-time Username Check</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }
        
        .container {
            width: 100%;
            max-width: 500px;
            background-color: white;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }
        
        .header {
            background: linear-gradient(90deg, #4b6cb7 0%, #182848 100%);
            color: white;
            padding: 25px 30px;
            text-align: center;
        }
        
        .header h1 {
            font-size: 28px;
            margin-bottom: 8px;
        }
        
        .header p {
            opacity: 0.9;
            font-size: 15px;
        }
        
        .form-container {
            padding: 30px;
        }
        
        .form-group {
            margin-bottom: 25px;
            position: relative;
        }
        
        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #333;
        }
        
        .input-with-icon {
            position: relative;
        }
        
        .input-with-icon i {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #7a7a7a;
        }
        
        input {
            width: 100%;
            padding: 14px 14px 14px 45px;
            border: 2px solid #e1e5ee;
            border-radius: 8px;
            font-size: 16px;
            transition: all 0.3s;
        }
        
        input:focus {
            outline: none;
            border-color: #4b6cb7;
            box-shadow: 0 0 0 3px rgba(75, 108, 183, 0.1);
        }
        
        .requirements {
            font-size: 13px;
            color: #666;
            margin-top: 5px;
            display: flex;
            justify-content: space-between;
        }
        
        .char-count {
            font-weight: 600;
        }
        
        .validation-area {
            margin-top: 10px;
            min-height: 40px;
            display: flex;
            align-items: center;
        }
        
        .status-message {
            display: flex;
            align-items: center;
            gap: 10px;
            font-weight: 500;
            padding: 8px 15px;
            border-radius: 6px;
            width: 100%;
            opacity: 0;
            transform: translateY(-5px);
            transition: all 0.3s ease;
        }
        
        .status-message.show {
            opacity: 1;
            transform: translateY(0);
        }
        
        .status-message.available {
            background-color: rgba(76, 175, 80, 0.1);
            color: #2e7d32;
        }
        
        .status-message.taken {
            background-color: rgba(244, 67, 54, 0.1);
            color: #c62828;
        }
        
        .status-message.checking {
            background-color: rgba(33, 150, 243, 0.1);
            color: #1565c0;
        }
        
        .status-message.error {
            background-color: rgba(255, 152, 0, 0.1);
            color: #ef6c00;
        }
        
        .loader {
            width: 18px;
            height: 18px;
            border: 2px solid #f3f3f3;
            border-top: 2px solid #3498db;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }
        
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        
        .submit-btn {
            background: linear-gradient(90deg, #4b6cb7 0%, #182848 100%);
            color: white;
            border: none;
            padding: 16px;
            width: 100%;
            border-radius: 8px;
            font-size: 17px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            margin-top: 10px;
        }
        
        .submit-btn:hover:not(:disabled) {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(75, 108, 183, 0.3);
        }
        
        .submit-btn:disabled {
            background: linear-gradient(90deg, #cccccc 0%, #aaaaaa 100%);
            cursor: not-allowed;
            transform: none;
            box-shadow: none;
        }
        
        .footer {
            text-align: center;
            margin-top: 25px;
            padding-top: 20px;
            border-top: 1px solid #eee;
            color: #777;
            font-size: 14px;
        }
        
        .footer a {
            color: #4b6cb7;
            text-decoration: none;
            font-weight: 600;
        }
        
        .existing-usernames {
            background-color: #f9f9f9;
            padding: 15px;
            border-radius: 8px;
            margin-top: 20px;
            font-size: 14px;
        }
        
        .existing-usernames h3 {
            margin-bottom: 10px;
            color: #444;
            font-size: 16px;
        }
        
        .username-list {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
        }
        
        .username-list span {
            background-color: #e9ecef;
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 13px;
            color: #555;
        }
        
        @media (max-width: 600px) {
            .container {
                border-radius: 10px;
            }
            
            .form-container {
                padding: 20px;
            }
            
            .header {
                padding: 20px;
            }
            
            .header h1 {
                font-size: 24px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Create Your Account</h1>
            <p>Check username availability in real-time as you type</p>
        </div>
        
        <div class="form-container">
            <form id="registrationForm">
                <div class="form-group">
                    <label for="username">Username</label>
                    <div class="input-with-icon">
                        <i class="fas fa-user"></i>
                        <input type="text" id="username" name="username" 
                               placeholder="Choose a username (min. 3 characters)" 
                               autocomplete="off">
                    </div>
                    <div class="requirements">
                        <span>Only letters, numbers, and underscores allowed</span>
                        <span class="char-count"><span id="charCount">0</span>/20</span>
                    </div>
                    <div class="validation-area">
                        <div id="usernameStatus" class="status-message">
                            <span id="statusText">Enter a username to check availability</span>
                        </div>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="email">Email Address</label>
                    <div class="input-with-icon">
                        <i class="fas fa-envelope"></i>
                        <input type="email" id="email" name="email" placeholder="Enter your email" required>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="password">Password</label>
                    <div class="input-with-icon">
                        <i class="fas fa-lock"></i>
                        <input type="password" id="password" name="password" placeholder="Create a strong password" required>
                    </div>
                </div>
                
                <button type="submit" class="submit-btn" id="submitBtn" disabled>
                    Create Account
                </button>
            </form>
            
            <div class="existing-usernames">
                <h3>Already taken usernames (for testing):</h3>
                <div class="username-list" id="takenUsernames">
                    <!-- This will be populated by JavaScript -->
                </div>
            </div>
            
            <div class="footer">
                <p>Already have an account? <a href="#">Sign In</a></p>
                <p>By registering, you agree to our <a href="#">Terms & Conditions</a></p>
            </div>
        </div>
    </div>

    <script>
        // DOM Elements
        const usernameInput = document.getElementById('username');
        const charCount = document.getElementById('charCount');
        const usernameStatus = document.getElementById('usernameStatus');
        const statusText = document.getElementById('statusText');
        const submitBtn = document.getElementById('submitBtn');
        const registrationForm = document.getElementById('registrationForm');
        const takenUsernamesContainer = document.getElementById('takenUsernames');
        
        // Variables for debouncing
        let debounceTimer;
        const debounceDelay = 300; // milliseconds
        let currentRequest = null;
        
        // Predefined taken usernames for display (should match PHP array)
        const takenUsernames = [
            'cristiano', 'ronaldo', 'neymar', 'messi','ramos', 'maldini', 'casillas', 'zarir', 'kroos', 'beckham', 'modric', 'mbappe'
        ];
        
        // Display taken usernames for testing
        function displayTakenUsernames() {
            takenUsernames.forEach(username => {
                const usernameElement = document.createElement('span');
                usernameElement.textContent = username;
                takenUsernamesContainer.appendChild(usernameElement);
            });
        }
        
        // Update character count
        function updateCharCount() {
            const count = usernameInput.value.length;
            charCount.textContent = count;
            
            // Change color based on length
            if (count < 3) {
                charCount.style.color = '#c62828';
            } else if (count > 20) {
                charCount.style.color = '#c62828';
            } else {
                charCount.style.color = '#2e7d32';
            }
        }
        
        // Show status message with appropriate styling
        function showStatus(message, type) {
            // Reset classes
            usernameStatus.className = 'status-message';
            
            // Add appropriate class based on type
            usernameStatus.classList.add(type);
            usernameStatus.classList.add('show');
            
            // Update message
            statusText.textContent = message;
            
            // Add icon based on type
            let icon = '';
            switch(type) {
                case 'available':
                    icon = '<i class="fas fa-check-circle"></i>';
                    break;
                case 'taken':
                    icon = '<i class="fas fa-times-circle"></i>';
                    break;
                case 'checking':
                    icon = '<div class="loader"></div>';
                    break;
                case 'error':
                    icon = '<i class="fas fa-exclamation-triangle"></i>';
                    break;
                default:
                    icon = '<i class="fas fa-info-circle"></i>';
            }
            
            statusText.innerHTML = icon + ' ' + message;
        }
        
        // username availability Check via AJAX
        function checkUsernameAvailability(username) {
            // Abort previous request if still pending
            if (currentRequest) {
                currentRequest.abort();
            }
            
            // Only check if username is at least 3 characters
            if (username.length < 3) {
                if (username.length === 0) {
                    showStatus('Enter a username to check availability', 'info');
                } else {
                    showStatus('Username must be at least 3 characters', 'error');
                }
                submitBtn.disabled = true;
                return;
            }
            
            // Show checking status
            showStatus('Checking username availability...', 'checking');
            submitBtn.disabled = true;
            
            // Create XMLHttpRequest
            currentRequest = new XMLHttpRequest();
            const url = `check_username.php?username=${encodeURIComponent(username)}`;
            
            currentRequest.open('GET', url, true);
            
            currentRequest.onreadystatechange = function() {
                if (currentRequest.readyState === 4) {
                    if (currentRequest.status === 200) {
                        try {
                            const response = JSON.parse(currentRequest.responseText);
                            
                            if (response.available) {
                                showStatus(response.message, 'available');
                                submitBtn.disabled = false;
                            } else {
                                showStatus(response.message, 'taken');
                                submitBtn.disabled = true;
                            }
                        } catch (e) {
                            showStatus('Error parsing server response', 'error');
                            submitBtn.disabled = true;
                        }
                    } else {
                        showStatus('Server error. Please try again.', 'error');
                        submitBtn.disabled = true;
                    }
                    
                    currentRequest = null;
                }
            };
            
            currentRequest.onerror = function() {
                showStatus('Network error. Please check your connection.', 'error');
                submitBtn.disabled = true;
                currentRequest = null;
            };
            
            // Send the request
            currentRequest.send();
        }
        
        // Debounce function to limit API calls
        function debounceCheckUsername(username) {
            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(() => {
                checkUsernameAvailability(username);
            }, debounceDelay);
        }
        
        // Handle form submission
        function handleFormSubmit(event) {
            event.preventDefault();
            
            // Get form values
            const username = usernameInput.value.trim();
            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;
            // For demonstration, just show an alert
            alert(`Account creation successful!\n\nUsername: ${username}\nEmail: ${email}\n\n`);
            
            // Reset form
            registrationForm.reset();
            updateCharCount();
            showStatus('Enter a username to check availability', 'info');
            submitBtn.disabled = true;
            
            // Focus back on username field
            usernameInput.focus();
        }
        
        // Initialize the application
        function init() {
            // Display taken usernames
            displayTakenUsernames();
            
            // Set up event listeners
            usernameInput.addEventListener('input', function() {
                const username = this.value.trim();
                updateCharCount();
                
                // Clear any existing debounce timer
                clearTimeout(debounceTimer);
                
                // Only check if username is at least 3 characters
                if (username.length >= 3) {
                    debounceCheckUsername(username);
                } else if (username.length === 0) {
                    showStatus('Enter a username to check availability', 'info');
                    submitBtn.disabled = true;
                } else {
                    showStatus('Username must be at least 3 characters', 'error');
                    submitBtn.disabled = true;
                }
            });
            
            usernameInput.addEventListener('keydown', function() {
                // Clear debounce timer on keydown to reset the delay
                clearTimeout(debounceTimer);
            });
            
            // Handle form submission
            registrationForm.addEventListener('submit', handleFormSubmit);
            
            // Focus on username field when page loads
            usernameInput.focus();
        }
        
        // Initialize when the DOM is fully loaded
        document.addEventListener('DOMContentLoaded', init);
    </script>
</body>
</html>