// View/js/confirmation.js

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
    
    // Order status simulation (for demo purposes)
    function simulateOrderStatus() {
        const statusBadge = document.querySelector('.status-badge');
        if (!statusBadge) return;
        
        const statuses = [
            { status: 'Processing', color: '#d4edda', icon: 'fa-clock' },
            { status: 'Packaged', color: '#fff3cd', icon: 'fa-box' },
            { status: 'Shipped', color: '#cce5ff', icon: 'fa-truck' },
            { status: 'Out for Delivery', color: '#d1ecf1', icon: 'fa-truck-loading' },
            { status: 'Delivered', color: '#d4edda', icon: 'fa-check-circle' }
        ];
        
        let currentIndex = 0;
        
        // Update status every 5 seconds for demo
        setInterval(() => {
            if (currentIndex < statuses.length) {
                const newStatus = statuses[currentIndex];
                statusBadge.innerHTML = `<i class="fas ${newStatus.icon}"></i> Status: ${newStatus.status}`;
                statusBadge.style.backgroundColor = newStatus.color;
                
                // Update color based on status
                if (newStatus.status === 'Delivered') {
                    statusBadge.style.color = '#155724';
                    statusBadge.style.border = '1px solid #c3e6cb';
                } else if (newStatus.status === 'Shipped') {
                    statusBadge.style.color = '#004085';
                    statusBadge.style.border = '1px solid #b8daff';
                }
                
                currentIndex++;
            }
        }, 5000);
    }
    
    // Start status simulation (comment out for production)
    // simulateOrderStatus();
    
    // Add smooth scroll to top on page load
    window.scrollTo({ top: 0, behavior: 'smooth' });
    
    // Notification for order completion
    if ('Notification' in window && Notification.permission === 'granted') {
        setTimeout(() => {
            new Notification('Order Confirmed!', {
                body: `Your order ${document.querySelector('.order-id strong').textContent} has been confirmed.`,
                icon: '/favicon.ico'
            });
        }, 2000);
    }
});