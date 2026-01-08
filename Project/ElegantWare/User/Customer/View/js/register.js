// ======================
// FORM VALIDATION FUNCTIONS
// ======================

function validatePassword(password) {
    const regex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$/;
    return regex.test(password);
}

function showAlert(message, type) {
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert alert-${type}`;
    alertDiv.innerHTML = `
        ${message}
        <button type="button" class="alert-close" onclick="this.parentElement.remove()">&times;</button>
    `;
    
    const main = document.querySelector('main');
    if (main) {
        main.prepend(alertDiv);
    }
    
    setTimeout(() => {
        if (alertDiv.parentNode) {
            alertDiv.remove();
        }
    }, 5000);
}

function togglePasswordVisibility(inputId) {
    const input = document.getElementById(inputId);
    if (input) {
        const type = input.getAttribute('type') === 'password' ? 'text' : 'password';
        input.setAttribute('type', type);
    }
}

// ======================
// DOM CONTENT LOADED
// ======================

document.addEventListener('DOMContentLoaded', function() {
    // Form validation for registration
    const registerForm = document.getElementById('registerForm');
    if (registerForm) {
        registerForm.addEventListener('submit', function(e) {
            const password = document.getElementById('password')?.value;
            const confirmPassword = document.getElementById('confirm_password')?.value;
            
            if (password !== confirmPassword) {
                e.preventDefault();
                showAlert('Passwords do not match', 'danger');
                return;
            }
            
            if (!validatePassword(password)) {
                e.preventDefault();
                showAlert('Password must be at least 8 characters with uppercase, lowercase and number', 'danger');
            }
        });
    }
    
    // Auto-hide alerts after 5 seconds
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        setTimeout(() => {
            alert.style.opacity = '0';
            setTimeout(() => {
                if (alert.parentNode) {
                    alert.remove();
                }
            }, 300);
        }, 5000);
    });
    
    // Toggle password visibility
    const showPasswordCheckboxes = document.querySelectorAll('input[type="checkbox"][onclick*="togglePasswordVisibility"]');
    showPasswordCheckboxes.forEach(checkbox => {
        const onclickAttr = checkbox.getAttribute('onclick');
        const match = onclickAttr.match(/togglePasswordVisibility\('([^']+)'\)/);
        if (match) {
            const inputId = match[1];
            checkbox.addEventListener('click', function() {
                togglePasswordVisibility(inputId);
            });
            checkbox.removeAttribute('onclick');
        }
    });
});