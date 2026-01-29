
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
// View More/Less functionality for product descriptions
function toggleDescription(link) {
    const card = link.closest('.product-card');
    const shortDesc = card.querySelector('.description-short');
    const fullDesc = card.querySelector('.description-full');
    
    if (fullDesc.style.display === 'none' || !fullDesc.style.display) {
        shortDesc.style.display = 'none';
        fullDesc.style.display = 'inline';
        link.textContent = 'View Less';
    } else {
        shortDesc.style.display = 'inline';
        fullDesc.style.display = 'none';
        link.textContent = 'View More';
    }
}

// Product sorting functionality
function sortProducts(sortBy) {
    const grid = document.querySelector('.products-grid');
    const products = Array.from(grid.querySelectorAll('.product-card'));
    
    products.sort((a, b) => {
        const priceA = parseFloat(a.dataset.price);
        const priceB = parseFloat(b.dataset.price);
        const nameA = a.dataset.name.toLowerCase();
        const nameB = b.dataset.name.toLowerCase();
        
        switch(sortBy) {
            case 'high-low':
                return priceB - priceA;
            case 'low-high':
                return priceA - priceB;
            case 'name-asc':
                return nameA.localeCompare(nameB);
            case 'name-desc':
                return nameB.localeCompare(nameA);
            default:
                return 0;
        }
    });
    
    // Reorder products in the grid
    products.forEach(product => grid.appendChild(product));
}
// View More/Less functionality for product descriptions
function toggleDescription(link) {
    const card = link.closest('.product-card');
    const shortDesc = card.querySelector('.description-short');
    const fullDesc = card.querySelector('.description-full');
    
    if (fullDesc.style.display === 'none' || !fullDesc.style.display) {
        shortDesc.style.display = 'none';
        fullDesc.style.display = 'inline';
        link.textContent = 'View Less';
    } else {
        shortDesc.style.display = 'inline';
        fullDesc.style.display = 'none';
        link.textContent = 'View More';
    }
}

// View More/Less functionality
document.addEventListener('DOMContentLoaded', function() {
    // Setup View More links
    document.querySelectorAll('.view-more-link').forEach(function(link) {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            toggleDescription(this);
        });
    });
    
    // Setup sorting
    const sortSelect = document.getElementById('sortProducts');
    if (sortSelect) {
        // Set initial value
        sortSelect.value = 'price-high-low';
        
        // Add event listener
        sortSelect.addEventListener('change', function() {
            sortProducts(this.value);
        });
    }
    
    // Setup Add to Cart buttons
    document.querySelectorAll('.add-to-cart-btn').forEach(function(btn) {
        if (btn.href.includes('add_to_cart')) {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                const productId = this.href.split('add_to_cart=')[1];
                addToCart(productId, e);
            });
        }
    });
});

// Function to toggle description visibility
function toggleDescription(link) {
    const productCard = link.closest('.product-card');
    const shortDesc = productCard.querySelector('.description-short');
    const fullDesc = productCard.querySelector('.description-full');
    
    if (fullDesc.style.display === 'none' || fullDesc.style.display === '') {
        if (shortDesc) shortDesc.style.display = 'none';
        fullDesc.style.display = 'inline';
        link.textContent = 'View Less';
    } else {
        if (shortDesc) shortDesc.style.display = 'inline';
        fullDesc.style.display = 'none';
        link.textContent = 'View More';
    }
}

// Function to sort products
function sortProducts(sortBy) {
    const productsGrid = document.getElementById('productsGrid');
    if (!productsGrid) return;
    
    const productCards = Array.from(productsGrid.querySelectorAll('.product-card'));
    
    productCards.sort(function(a, b) {
        const priceA = parseFloat(a.getAttribute('data-price')) || 0;
        const priceB = parseFloat(b.getAttribute('data-price')) || 0;
        const nameA = (a.getAttribute('data-name') || '').toLowerCase();
        const nameB = (b.getAttribute('data-name') || '').toLowerCase();
        
        switch(sortBy) {
            case 'price-high-low':
                return priceB - priceA; // High to low
            case 'price-low-high':
                return priceA - priceB; // Low to high
            case 'name-a-z':
                return nameA.localeCompare(nameB); // A to Z
            case 'name-z-a':
                return nameB.localeCompare(nameA); // Z to A
            default:
                return 0;
        }
    });
    
    // Clear the grid and re-add sorted products
    productsGrid.innerHTML = '';
    productCards.forEach(function(card) {
        productsGrid.appendChild(card);
    });
    
    // Re-attach event listeners to View More links after sorting
    setTimeout(function() {
        document.querySelectorAll('.view-more-link').forEach(function(link) {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                toggleDescription(this);
            });
        });
    }, 100);
}

// Function to handle Add to Cart
function addToCart(productId, event) {
    const button = event.target.closest('.add-to-cart-btn');
    if (button) {
        const originalHTML = button.innerHTML;
        const originalText = button.textContent;
        
        // Show loading state
        button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Adding...';
        button.disabled = true;
        
        // Simulate API delay
        setTimeout(function() {
            // Redirect to add to cart
            window.location.href = 'index.php?add_to_cart=' + productId;
        }, 500);
    }
    return false;
}
// ======================
// SEARCH FUNCTIONALITY
// ======================

function initSearch() {
    const searchForm = document.getElementById('searchForm');
    const searchInput = document.getElementById('searchInput');
    const searchSuggestions = document.getElementById('searchSuggestions');
    
    if (!searchInput) return;
    
    let searchTimeout;
    let lastSearchTerm = '';
    
    // Real-time search suggestions
    searchInput.addEventListener('input', function(e) {
        clearTimeout(searchTimeout);
        const term = this.value.trim();
        
        if (term.length < 2) {
            searchSuggestions.style.display = 'none';
            return;
        }
        
        if (term === lastSearchTerm) return;
        lastSearchTerm = term;
        
        searchTimeout = setTimeout(() => {
            fetchSearchSuggestions(term);
        }, 300);
    });
    
    // Handle form submission
    if (searchForm) {
        searchForm.addEventListener('submit', function(e) {
            const term = searchInput.value.trim();
            if (term.length < 2) {
                e.preventDefault();
                showAlert('Please enter at least 2 characters to search', 'warning');
                return;
            }
            
            // Show loading
            const submitBtn = this.querySelector('.search-btn');
            if (submitBtn) {
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
            }
        });
    }
    
    // Hide suggestions when clicking outside
    document.addEventListener('click', function(e) {
        if (!searchInput.contains(e.target) && !searchSuggestions.contains(e.target)) {
            searchSuggestions.style.display = 'none';
        }
    });
    
    // Keyboard navigation for suggestions
    searchInput.addEventListener('keydown', function(e) {
        const suggestions = searchSuggestions.querySelectorAll('.search-suggestion-item');
        let currentFocus = -1;
        
        if (e.key === 'ArrowDown') {
            e.preventDefault();
            currentFocus++;
            highlightSuggestion(suggestions, currentFocus);
        } else if (e.key === 'ArrowUp') {
            e.preventDefault();
            currentFocus--;
            highlightSuggestion(suggestions, currentFocus);
        } else if (e.key === 'Enter' && currentFocus > -1) {
            e.preventDefault();
            if (suggestions[currentFocus]) {
                suggestions[currentFocus].click();
            }
        }
    });
}

function highlightSuggestion(suggestions, index) {
    suggestions.forEach(s => s.classList.remove('highlighted'));
    if (suggestions[index]) {
        suggestions[index].classList.add('highlighted');
        suggestions[index].scrollIntoView({ block: 'nearest' });
    }
}

function fetchSearchSuggestions(term) {
    // In a real application, you would make an AJAX request here
    // For now, we'll simulate with the existing products data
    
    const suggestions = document.getElementById('searchSuggestions');
    suggestions.innerHTML = '';
    suggestions.style.display = 'block';
    
    // Get product data from the page
    const productCards = document.querySelectorAll('.product-card');
    const suggestionsData = [];
    
    productCards.forEach(card => {
        const name = card.querySelector('h3').textContent;
        const category = card.querySelector('.product-category').textContent;
        const price = card.dataset.price;
        const id = card.querySelector('.add-to-cart-btn')?.dataset.productId;
        
        if (name.toLowerCase().includes(term.toLowerCase()) || 
            category.toLowerCase().includes(term.toLowerCase())) {
            suggestionsData.push({ name, category, price, id });
        }
    });
    
    if (suggestionsData.length === 0) {
        suggestions.innerHTML = '<div class="no-suggestions">No matching products found</div>';
        return;
    }
    
    suggestionsData.slice(0, 5).forEach(item => {
        const div = document.createElement('div');
        div.className = 'search-suggestion-item';
        div.innerHTML = `
            <i class="fas fa-search"></i>
            <span class="search-suggestion-name">${item.name}</span>
            <span class="search-suggestion-category">${item.category}</span>
        `;
        
        div.addEventListener('click', function() {
            window.location.href = `index.php?search=${encodeURIComponent(item.name.split(' ')[0])}`;
        });
        
        suggestions.appendChild(div);
    });
}

// ======================
// UPDATE DOM CONTENT LOADED
// ======================

document.addEventListener('DOMContentLoaded', function() {
    // Initialize search
    initSearch();
    
    // Initialize cart if on cart page
    if (document.querySelector('.cart-page')) {
        initializeCart();
    }
    
    // ... rest of your existing DOMContentLoaded code ...
});