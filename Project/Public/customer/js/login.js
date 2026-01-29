
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

document.addEventListener('DOMContentLoaded', function() {
    // Form validation for login
    const loginForm = document.getElementById('loginForm');
    if (loginForm) {
        loginForm.addEventListener('submit', function(e) {
            const email = document.getElementById('email')?.value;
            const password = document.getElementById('password')?.value;
            
            if (!email || !password) {
                e.preventDefault();
                showAlert('Please fill in all fields', 'danger');
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
    
    // Toggle password visibility (if you add a show password checkbox)
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