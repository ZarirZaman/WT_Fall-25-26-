// confirmation.js

document.addEventListener('DOMContentLoaded', function() {
    // Print functionality
    const printButton = document.querySelector('.print-button');
    if (printButton) {
        printButton.addEventListener('click', function(e) {
            e.preventDefault();
            window.print();
        });
    }
    
    // Confetti effect for celebration
    setTimeout(function() {
        if (typeof confetti === 'function') {
            // Main burst
            confetti({
                particleCount: 150,
                spread: 70,
                origin: { y: 0.6 },
                colors: ['#d4a762', '#28a745', '#6c757d', '#ffc107']
            });
            
            // Side bursts after delay
            setTimeout(() => {
                confetti({
                    particleCount: 50,
                    angle: 60,
                    spread: 55,
                    origin: { x: 0 },
                    colors: ['#d4a762', '#28a745']
                });
                confetti({
                    particleCount: 50,
                    angle: 120,
                    spread: 55,
                    origin: { x: 1 },
                    colors: ['#6c757d', '#ffc107']
                });
            }, 250);
        }
    }, 1000);
    
    // Save order ID to clipboard
    const orderIdElement = document.querySelector('.order-id');
    if (orderIdElement) {
        orderIdElement.addEventListener('click', function() {
            const orderId = this.querySelector('strong').textContent;
            navigator.clipboard.writeText(orderId).then(function() {
                // Show success message
                const originalText = orderIdElement.innerHTML;
                orderIdElement.innerHTML = '<i class="fas fa-check"></i> Copied to clipboard!';
                orderIdElement.style.backgroundColor = '#d4edda';
                orderIdElement.style.color = '#155724';
                orderIdElement.style.borderColor = '#c3e6cb';
                
                setTimeout(function() {
                    orderIdElement.innerHTML = originalText;
                    orderIdElement.style.backgroundColor = '';
                    orderIdElement.style.color = '';
                    orderIdElement.style.borderColor = '';
                }, 2000);
            });
        });
        
        // Add cursor pointer to indicate it's clickable
        orderIdElement.style.cursor = 'pointer';
        orderIdElement.title = 'Click to copy order ID';
    }
    
    // Button loading states
    const actionButtons = document.querySelectorAll('.btn-continue, .btn-view-orders');
    actionButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            if (this.href === '#' || this.getAttribute('href') === 'javascript:void(0)') {
                e.preventDefault();
                return;
            }
            
            // Add loading state
            this.classList.add('loading');
            
            // Simulate loading delay for demo
            setTimeout(() => {
                this.classList.remove('loading');
            }, 1000);
        });
    });
    
    // Initialize form functionality
    initOrderForm();
    initConfettiButton();
    
    // Countdown timer for estimated delivery (optional)
    function updateDeliveryCountdown() {
        const deliveryDateElement = document.querySelector('.info-card:nth-child(2) p');
        if (!deliveryDateElement) return;
        
        const deliveryText = deliveryDateElement.textContent;
        const deliveryDate = new Date(deliveryText);
        
        if (isNaN(deliveryDate.getTime())) return;
        
        const now = new Date();
        const timeDiff = deliveryDate - now;
        
        if (timeDiff > 0) {
            const days = Math.floor(timeDiff / (1000 * 60 * 60 * 24));
            const hours = Math.floor((timeDiff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            
            let countdownText = '';
            if (days > 0) {
                countdownText = `${days} day${days !== 1 ? 's' : ''}`;
                if (hours > 0) {
                    countdownText += ` ${hours} hour${hours !== 1 ? 's' : ''}`;
                }
            } else if (hours > 0) {
                countdownText = `${hours} hour${hours !== 1 ? 's' : ''}`;
            }
            
            if (countdownText) {
                const countdownElement = document.createElement('p');
                countdownElement.className = 'delivery-countdown';
                countdownElement.innerHTML = `<i class="fas fa-clock"></i> Delivery in: <strong>${countdownText}</strong>`;
                countdownElement.style.marginTop = '10px';
                countdownElement.style.fontSize = '0.9rem';
                countdownElement.style.color = '#d4a762';
                
                const deliveryCard = document.querySelector('.info-card:nth-child(2)');
                if (deliveryCard && !deliveryCard.querySelector('.delivery-countdown')) {
                    deliveryCard.appendChild(countdownElement);
                }
            }
        }
    }
    
    // Initialize countdown if delivery date is present
    setTimeout(updateDeliveryCountdown, 500);
    
    // Add smooth scroll to top on page load
    window.scrollTo({ top: 0, behavior: 'smooth' });
    
    // Notification for order completion
    if ('Notification' in window && Notification.permission === 'granted') {
        setTimeout(() => {
            const orderIdElement = document.querySelector('.order-id strong');
            if (orderIdElement) {
                new Notification('Order Confirmed!', {
                    body: `Your order ${orderIdElement.textContent} has been confirmed.`,
                    icon: '/favicon.ico'
                });
            }
        }, 2000);
    }
});

// FORM HANDLING FUNCTIONS
function initOrderForm() {
    const form = document.getElementById('orderFeedbackForm');
    if (!form) return;
    
    // Auto-select first star rating
    const firstStar = form.querySelector('.rating-stars input[value="5"]');
    if (firstStar) {
        firstStar.checked = true;
        highlightStars(5);
    }
    
    // Form submission
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        submitOrderForm(this);
    });
    
    // Real-time validation
    const inputs = form.querySelectorAll('input, select, textarea');
    inputs.forEach(input => {
        input.addEventListener('blur', validateField);
        input.addEventListener('input', clearFieldError);
    });
    
    // Star rating hover effect
    const stars = form.querySelectorAll('.rating-stars label');
    stars.forEach(star => {
        star.addEventListener('mouseenter', function() {
            const rating = this.getAttribute('for').replace('star', '');
            highlightStars(rating);
        });
        
        star.addEventListener('mouseleave', function() {
            const checkedStar = form.querySelector('.rating-stars input:checked');
            if (checkedStar) {
                const rating = checkedStar.value;
                highlightStars(rating);
            }
        });
    });
}

function highlightStars(rating) {
    const stars = document.querySelectorAll('.rating-stars label');
    stars.forEach((star, index) => {
        if (index >= 5 - rating) {
            star.style.color = '#ffc107';
        } else {
            star.style.color = '#ddd';
        }
    });
}

function validateField() {
    const field = this;
    const errorElement = field.parentElement.querySelector('.error-message');
    
    if (field.hasAttribute('required') && !field.value.trim()) {
        field.classList.add('error');
        if (errorElement) {
            errorElement.textContent = 'This field is required';
            errorElement.style.display = 'block';
        }
        return false;
    }
    
    field.classList.remove('error');
    if (errorElement) {
        errorElement.style.display = 'none';
    }
    return true;
}

function clearFieldError() {
    this.classList.remove('error');
    const errorElement = this.parentElement.querySelector('.error-message');
    if (errorElement) {
        errorElement.style.display = 'none';
    }
}

function submitOrderForm(form) {
    // Validate all required fields
    const requiredFields = form.querySelectorAll('[required]');
    let isValid = true;
    
    requiredFields.forEach(field => {
        if (!validateField.call(field)) {
            isValid = false;
        }
    });
    
    if (!isValid) {
        showFormMessage('Please fill in all required fields correctly.', 'error');
        return;
    }
    
    // Show loading state
    const submitBtn = form.querySelector('.form-submit');
    const originalText = submitBtn.innerHTML;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Submitting...';
    submitBtn.disabled = true;
    
    // Collect form data
    const formData = new FormData(form);
    const data = {};
    formData.forEach((value, key) => {
        data[key] = value;
    });
    
    // Simulate API call (replace with actual AJAX call)
    setTimeout(() => {
        // Show success message
        showFormMessage('Thank you! Your preferences have been saved successfully.', 'success');
        
        // Reset button
        submitBtn.innerHTML = originalText;
        submitBtn.disabled = false;
        
        // Trigger confetti celebration
        triggerFormConfetti();
        
        // Save to localStorage for demo
        const orderId = data.order_id;
        if (orderId) {
            localStorage.setItem(`order_${orderId}_feedback`, JSON.stringify(data));
        }
        
    }, 1500);
}

function showFormMessage(message, type = 'success') {
    let messageElement = document.querySelector('.form-message');
    
    if (!messageElement) {
        messageElement = document.createElement('div');
        messageElement.className = 'form-message';
        const form = document.getElementById('orderFeedbackForm');
        form.insertBefore(messageElement, form.firstChild);
    }
    
    messageElement.textContent = message;
    messageElement.className = `form-message ${type}`;
    messageElement.style.display = 'flex';
    
    // Add icon based on message type
    if (type === 'success') {
        messageElement.innerHTML = `<i class="fas fa-check-circle"></i> ${message}`;
    } else {
        messageElement.innerHTML = `<i class="fas fa-exclamation-circle"></i> ${message}`;
    }
    
    // Auto-hide after 5 seconds
    setTimeout(() => {
        messageElement.style.display = 'none';
    }, 5000);
}

function triggerFormConfetti() {
    if (typeof confetti === 'function') {
        confetti({
            particleCount: 100,
            spread: 70,
            origin: { y: 0.6 },
            colors: ['#d4a762', '#28a745', '#6c757d', '#ffc107']
        });
        
        setTimeout(() => {
            confetti({
                particleCount: 50,
                angle: 60,
                spread: 55,
                origin: { x: 0 },
                colors: ['#d4a762', '#28a745']
            });
        }, 300);
    }
}

// Confetti button functionality
function initConfettiButton() {
    const confettiBtn = document.querySelector('.confetti-button');
    if (!confettiBtn) return;
    
    confettiBtn.addEventListener('click', function() {
        triggerFormConfetti();
        
        // Add animation to button
        this.style.animation = 'pulse 0.5s';
        setTimeout(() => {
            this.style.animation = '';
        }, 500);
    });
    
    // Add pulse animation style
    const style = document.createElement('style');
    style.textContent = `
        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.2); }
            100% { transform: scale(1); }
        }
    `;
    document.head.appendChild(style);
}