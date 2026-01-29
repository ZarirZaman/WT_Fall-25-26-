// ======================
// PRODUCT FILTERING & SORTING
// ======================

function sortProducts(sortValue) {
    // Get current URL parameters
    const url = new URL(window.location.href);
    const currentCategory = url.searchParams.get('category') || '';
    
    // Build new URL with sort parameter
    let newUrl = 'index.php';
    const params = [];
    
    if (currentCategory) {
        params.push(`category=${currentCategory}`);
    }
    
    if (sortValue && sortValue !== 'high-low') {
        params.push(`sort=${sortValue}`);
    }
    
    if (params.length > 0) {
        newUrl += '?' + params.join('&');
    }
    
    // Navigate to sorted page
    window.location.href = newUrl;
}

function filterByCategory(category) {
    // Get current URL parameters
    const url = new URL(window.location.href);
    const currentSort = url.searchParams.get('sort') || 'high-low';
    
    // Build new URL with category parameter
    let newUrl = 'index.php';
    const params = [];
    
    if (category && category !== 'All') {
        params.push(`category=${category.toLowerCase()}`);
    }
    
    if (currentSort !== 'high-low') {
        params.push(`sort=${currentSort}`);
    }
    
    if (params.length > 0) {
        newUrl += '?' + params.join('&');
    }
    
    // Navigate to filtered page
    window.location.href = newUrl;
}

// ======================
// AJAX ADD TO CART
// ======================

function addToCart(event, element) {
    event.preventDefault();
    
    const productId = element.getAttribute('data-product-id');
    const productName = element.getAttribute('data-product-name');
    
    // Show loading state
    element.classList.add('adding');
    element.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Adding...';
    
    // Show loading overlay
    const loadingOverlay = document.getElementById('loadingOverlay');
    loadingOverlay.style.display = 'flex';
    
    // Make AJAX request
    fetch(`index.php?add_to_cart=${productId}`, {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => {
        if (response.redirected) {
            // Follow redirect for non-AJAX requests
            window.location.href = response.url;
        } else {
            return response.text();
        }
    })
    .then(() => {
        // Update cart badge
        updateCartBadge();
        
        // Show success notification
        showCartNotification(`${productName} added to cart!`, 'success');
        
        // Reset button state
        setTimeout(() => {
            element.classList.remove('adding');
            element.innerHTML = '<i class="fas fa-cart-plus"></i> Add to Cart';
            loadingOverlay.style.display = 'none';
        }, 1000);
    })
    .catch(error => {
        console.error('Error adding to cart:', error);
        
        // Show error notification
        showCartNotification('Error adding to cart. Please try again.', 'error');
        
        // Reset button state
        element.classList.remove('adding');
        element.innerHTML = '<i class="fas fa-cart-plus"></i> Add to Cart';
        loadingOverlay.style.display = 'none';
    });
}

// ======================
// CART FUNCTIONS - UNIVERSAL
// ======================

// Keep track of cart count globally
let currentCartCount = 0;

// Initialize cart badge on page load
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initializeCart);
} else {
    initializeCart();
}

function initializeCart() {
    updateCartBadge();
    
    // Add click handlers for cart buttons if on product page
    document.querySelectorAll('.add-to-cart-btn').forEach(button => {
        button.addEventListener('click', function(e) {
            const productId = this.dataset.productId || this.getAttribute('data-product-id');
            if (productId) {
                addToCart(productId, e);
            }
        });
    });
}

function updateQuantity(index, change) {
    const input = document.querySelector(`input[name="quantity[${index}]"]`);
    if (input) {
        let value = parseInt(input.value) + change;
        
        if (value < 1) value = 1;
        if (value > 10) value = 10;
        
        input.value = value;
        
        // Submit form if on cart page
        const form = input.closest('form');
        if (form && form.id === 'cart-form') {
            form.submit();
        }
    }
}

function addToCart(productId, event) {
    // If event is provided, prevent default and show loading
    if (event) {
        event.preventDefault();
        event.stopPropagation();
        
        const button = event.target.closest('.add-to-cart-btn');
        if (button) {
            const originalHTML = button.innerHTML;
            button.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
            button.disabled = true;
            
            setTimeout(() => {
                window.location.href = 'cart.php?add_to_cart=' + productId;
            }, 500);
            return;
        }
    }
    
    // Default redirect
    window.location.href = 'cart.php?add_to_cart=' + productId;
}

function updateCartBadge() {
    fetch('cart.php?ajax=get_cart_count')
        .then(response => response.json())
        .then(data => {
            if (data.success && data.cart_count !== undefined) {
                setCartCount(data.cart_count);
            }
        })
        .catch(error => {
            console.log('Cart badge update failed, using session value');
            // Fallback: Use PHP session value if available
            const fallbackCount = document.body.dataset.cartCount;
            if (fallbackCount) {
                setCartCount(parseInt(fallbackCount));
            }
        });
}

function setCartCount(count) {
    currentCartCount = count;
    
    // Update all cart badges on page
    document.querySelectorAll('.cart-badge').forEach(badge => {
        if (count > 0) {
            badge.textContent = count;
            badge.style.display = 'inline-block';
        } else {
            badge.style.display = 'none';
        }
    });
    
    // Update cart link text
    document.querySelectorAll('.cart-link').forEach(link => {
        const textSpan = link.querySelector('.cart-text');
        if (textSpan) {
            textSpan.textContent = count > 0 ? `Cart (${count})` : 'Cart';
        }
    });
}

function showCartNotification(message, type = 'success') {
    // Remove existing notifications
    const existing = document.querySelectorAll('.cart-notification');
    existing.forEach(el => el.remove());
    
    // Create notification
    const notification = document.createElement('div');
    notification.className = `cart-notification ${type}`;
    notification.innerHTML = `
        <div class="notification-content">
            <i class="fas fa-${type === 'success' ? 'check' : 'exclamation-circle'}"></i>
            <span>${message}</span>
        </div>
    `;
    
    document.body.appendChild(notification);
    
    // Animate in
    setTimeout(() => notification.classList.add('show'), 10);
    
    // Auto remove
    setTimeout(() => {
        notification.classList.remove('show');
        setTimeout(() => notification.remove(), 300);
    }, 3000);
}

// Quick add function for product listings
function quickAddToCart(productId, element) {
    const button = element || document.querySelector(`[data-product-id="${productId}"]`);
    if (button) {
        const originalText = button.innerHTML;
        button.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
        button.disabled = true;
        
        fetch(`cart.php?quick_add=${productId}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    setCartCount(data.cart_count);
                    showCartNotification('Added to cart!');
                    button.innerHTML = '<i class="fas fa-check"></i> Added';
                    setTimeout(() => {
                        button.innerHTML = originalText;
                        button.disabled = false;
                    }, 1500);
                } else {
                    button.innerHTML = originalText;
                    button.disabled = false;
                    showCartNotification('Failed to add item', 'error');
                }
            })
            .catch(() => {
                button.innerHTML = originalText;
                button.disabled = false;
                window.location.href = `cart.php?add_to_cart=${productId}`;
            });
    }
}

// Remove item from cart (for cart page)
function removeCartItem(index, btn) {
    if (confirm('Remove this item from cart?')) {
        const row = btn.closest('tr');
        row.style.opacity = '0.5';
        btn.disabled = true;
        
        setTimeout(() => {
            window.location.href = `cart.php?remove=${index}`;
        }, 300);
    }
}

// Clear cart
function clearCart() {
    if (confirm('Remove all items from cart?')) {
        window.location.href = 'cart.php?clear_cart=1';
    }
}

// Export functions for module usage
if (typeof module !== 'undefined' && module.exports) {
    module.exports = {
        updateQuantity,
        addToCart,
        updateCartBadge,
        setCartCount,
        showCartNotification,
        quickAddToCart,
        removeCartItem,
        clearCart
    };
}
// ======================
// PRODUCT DESCRIPTION TOGGLE
// ======================

function toggleDescription(element) {
    event.preventDefault();
    
    const descriptionContainer = element.closest('.product-description-container');
    const shortDesc = descriptionContainer.querySelector('.description-short');
    const fullDesc = descriptionContainer.querySelector('.description-full');
    const viewMoreLink = descriptionContainer.querySelector('.view-more-link');
    
    if (shortDesc && fullDesc) {
        if (shortDesc.style.display !== 'none') {
            // Show full description
            shortDesc.style.display = 'none';
            fullDesc.style.display = 'inline';
            viewMoreLink.textContent = 'View Less';
        } else {
            // Show short description
            shortDesc.style.display = 'inline';
            fullDesc.style.display = 'none';
            viewMoreLink.textContent = 'View More';
        }
    }
    
    return false;
}

// ======================
// INITIALIZATION
// ======================

document.addEventListener('DOMContentLoaded', function() {
    // Initialize category filter buttons
    document.querySelectorAll('.category-card, .footer-section ul li a').forEach(link => {
        link.addEventListener('click', function(e) {
            const href = this.getAttribute('href');
            // Only prevent default if it's a filter link (not external link)
            if (href.includes('?category=') || href === 'index.php') {
                e.preventDefault();
                const category = this.textContent.trim();
                filterByCategory(category);
            }
        });
    });
    
    // Initialize quick add buttons
    document.querySelectorAll('.quick-add-btn').forEach(button => {
        button.addEventListener('click', function(e) {
            addToCart(e, this);
        });
    });
    
    // Initialize sort select
    const sortSelect = document.getElementById('sortProducts');
    if (sortSelect) {
        sortSelect.addEventListener('change', function() {
            sortProducts(this.value);
        });
    }
    
    // Initialize view more links for descriptions
    document.querySelectorAll('.view-more-link').forEach(link => {
        link.addEventListener('click', function(e) {
            toggleDescription(this);
        });
    });
    
    // Smooth scrolling for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            const href = this.getAttribute('href');
            if (href !== '#' && !href.includes('?')) {
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
});

// Global cart functions
window.updateCartBadge = updateCartBadge;
window.filterByCategory = filterByCategory;