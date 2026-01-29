
document.addEventListener('DOMContentLoaded', function() {
    // Payment method toggle
    const paymentRadios = document.querySelectorAll('input[name="payment_method"]');
    
    paymentRadios.forEach(radio => {
        radio.addEventListener('change', function() {
            // Hide all card forms
            document.querySelectorAll('.card-form').forEach(form => {
                form.style.display = 'none';
            });
            
            // Show card form if credit card is selected
            if (this.id === 'credit-card') {
                const cardForm = this.closest('.payment-option').querySelector('.card-form');
                if (cardForm) {
                    cardForm.style.display = 'block';
                }
            }
        });
    });
    
    // Saved address selection
    const savedAddressRadios = document.querySelectorAll('input[name="use_saved_address"]');
    const newAddressForm = document.querySelector('.address-form');
    
    savedAddressRadios.forEach(radio => {
        radio.addEventListener('change', function() {
            if (this.checked) {
                newAddressForm.style.opacity = '0.6';
                newAddressForm.querySelectorAll('input, select').forEach(input => {
                    input.disabled = true;
                });
            }
        });
    });
    
    // Enable new address form when "New Address" is selected
    const newAddressRadio = document.querySelector('input[value="new"]');
    if (newAddressRadio) {
        newAddressRadio.addEventListener('change', function() {
            newAddressForm.style.opacity = '1';
            newAddressForm.querySelectorAll('input, select').forEach(input => {
                input.disabled = false;
            });
        });
    }
    
    // Form validation
    const checkoutForm = document.querySelector('form');
    if (checkoutForm) {
        checkoutForm.addEventListener('submit', function(e) {
            const termsCheckbox = document.getElementById('terms');
            if (!termsCheckbox.checked) {
                e.preventDefault();
                alert('Please agree to the Terms & Conditions');
                return false;
            }
        });
    }
});