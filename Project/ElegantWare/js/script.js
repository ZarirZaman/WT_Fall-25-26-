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
// CART FUNCTIONS
// ======================

function updateQuantity(index, change) {
    const input = document.querySelector(`input[name="quantity[${index}]"]`);
    if (input) {
        let value = parseInt(input.value) + change;
        
        if (value < 1) value = 1;
        if (value > 10) value = 10;
        
        input.value = value;
    }
}

function showCartNotification(message, type = 'success') {
    // Remove existing notifications
    const existingNotifications = document.querySelectorAll('.cart-notification');
    existingNotifications.forEach(notif => notif.remove());
    
    // Create new notification
    const notification = document.createElement('div');
    notification.className = `cart-notification ${type}`;
    notification.innerHTML = `
        <div class="notification-content">
            <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'}"></i>
            <span>${message}</span>
        </div>
    `;
    
    document.body.appendChild(notification);
    
    // Show animation
    setTimeout(() => {
        notification.classList.add('show');
    }, 10);
    
    // Auto-remove after 3 seconds
    setTimeout(() => {
        notification.classList.remove('show');
        setTimeout(() => {
            if (notification.parentNode) {
                notification.parentNode.removeChild(notification);
            }
        }, 300);
    }, 3000);
}

function addToCart(productId, event) {
    // Show loading animation
    const button = event?.target?.closest('.add-to-cart-btn');
    if (button) {
        const originalText = button.innerHTML;
        button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Adding...';
        button.disabled = true;
        
        setTimeout(() => {
            window.location.href = 'cart.php?add_to_cart=' + productId;
        }, 500);
    } else {
        window.location.href = 'cart.php?add_to_cart=' + productId;
    }
}

// ======================
// CART INITIALIZATION
// ======================

function initializeCart() {
    // Quantity button listeners
    document.querySelectorAll('.quantity-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const index = this.getAttribute('data-index');
            const change = parseInt(this.getAttribute('data-change'));
            updateQuantity(index, change);
        });
    });
    
    // Remove item confirmation
    document.querySelectorAll('.btn-remove').forEach(btn => {
        btn.addEventListener('click', function(e) {
            if (!confirm('Are you sure you want to remove this item from your cart?')) {
                e.preventDefault();
            }
        });
    });
    
    // Clear cart confirmation
    const clearCartBtn = document.getElementById('clearCartBtn');
    if (clearCartBtn) {
        clearCartBtn.addEventListener('click', function(e) {
            if (!confirm('Are you sure you want to clear your entire cart?')) {
                e.preventDefault();
            }
        });
    }
    
    // Auto-hide cart messages
    const cartMessages = document.querySelectorAll('.alert-message');
    cartMessages.forEach(msg => {
        setTimeout(() => {
            msg.style.opacity = '0';
            setTimeout(() => {
                if (msg.parentNode) {
                    msg.parentNode.removeChild(msg);
                }
            }, 300);
        }, 5000);
    });
    
    // Checkout button validation
    const checkoutBtn = document.querySelector('.btn-checkout');
    if (checkoutBtn) {
        checkoutBtn.addEventListener('click', function(e) {
            // Check if cart has items using global cartData or DOM
            let hasItems = false;
            
            if (typeof cartData !== 'undefined') {
                hasItems = cartData.hasItems;
            } else {
                // Fallback: check DOM elements
                const emptyCart = document.querySelector('.cart-empty');
                const cartTableRows = document.querySelectorAll('.cart-table tbody tr');
                hasItems = !emptyCart && cartTableRows.length > 0;
            }
            
            if (!hasItems) {
                e.preventDefault();
                showCartNotification('Your cart is empty!', 'error');
                return false;
            }
            
            return true;
        });
    }
    
    // Form validation for cart updates
    const cartForm = document.getElementById('cartForm');
    if (cartForm) {
        cartForm.addEventListener('submit', function(e) {
            const submitBtn = e.submitter;
            
            if (submitBtn && submitBtn.name === 'update_cart') {
                // Validate quantities before updating
                const quantityInputs = document.querySelectorAll('.quantity-input');
                let isValid = true;
                
                quantityInputs.forEach(input => {
                    const value = parseInt(input.value);
                    if (isNaN(value) || value < 1 || value > 10) {
                        isValid = false;
                        input.style.borderColor = '#e74c3c';
                    } else {
                        input.style.borderColor = '';
                    }
                });
                
                if (!isValid) {
                    e.preventDefault();
                    showCartNotification('Please enter valid quantities (1-10)', 'error');
                    return false;
                }
            }
            
            return true;
        });
    }
}

// ======================
// DOM CONTENT LOADED
// ======================

document.addEventListener('DOMContentLoaded', function() {
    // Initialize cart if on cart page
    if (document.querySelector('.cart-page')) {
        initializeCart();
    }
    
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



// Update cart badge (can be called from other pages)
function updateCartBadge(count) {
    const badges = document.querySelectorAll('.cart-badge');
    badges.forEach(badge => {
        if (count > 0) {
            badge.textContent = count;
            badge.style.display = 'inline-block';
        } else {
            badge.style.display = 'none';
        }
    });
}

// Smooth scrolling for anchor links
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function(e) {
        const href = this.getAttribute('href');
        if (href !== '#') {
            e.preventDefault();
            const target = document.querySelector(href);
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        }
    });
});