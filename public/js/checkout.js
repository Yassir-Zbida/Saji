document.addEventListener('DOMContentLoaded', function() {
    const checkoutForm = document.getElementById('checkout-form');
    const steps = document.querySelectorAll('.step');
    const stepContents = document.querySelectorAll('.step-content');
    const progress = document.querySelector('.progress');
    let currentStep = 1;

    const shippingAddressIdField = document.createElement("input");
    shippingAddressIdField.type = "hidden";
    shippingAddressIdField.name = "shipping_address_id";
    checkoutForm.appendChild(shippingAddressIdField);

    const billingAddressIdField = document.createElement("input");
    billingAddressIdField.type = "hidden";
    billingAddressIdField.name = "billing_address_id";
    checkoutForm.appendChild(billingAddressIdField);

    document.querySelectorAll('.step-next').forEach(button => {
        button.addEventListener('click', () => {
            if (validateCurrentStep()) {
                goToStep(currentStep + 1);
            }
        });
    });

    document.querySelectorAll('.step-prev').forEach(button => {
        button.addEventListener('click', () => {
            goToStep(currentStep - 1);
        });
    });

    function goToStep(stepNumber) {
        if (stepNumber < 1 || stepNumber > 3) return;

        // Update step indicators
        steps.forEach(step => {
            const stepNum = parseInt(step.dataset.step);
            step.classList.remove('active', 'completed');
            
            if (stepNum === stepNumber) {
                step.classList.add('active');
            } else if (stepNum < stepNumber) {
                step.classList.add('completed');
            }
        });

        stepContents.forEach(content => {
            content.classList.remove('active');
            if (parseInt(content.dataset.step) === stepNumber) {
                content.classList.add('active');
            }
        });

        const progressPercentage = ((stepNumber - 1) / 2) * 100;
        progress.style.width = progressPercentage + '%';

        currentStep = stepNumber;

        window.scrollTo({
            top: document.querySelector('.checkout-steps').offsetTop - 20,
            behavior: 'smooth'
        });
    }

    function validateCurrentStep() {
        const currentContent = document.querySelector(`.step-content[data-step="${currentStep}"]`);
        const requiredFields = currentContent.querySelectorAll('[required]');
        let isValid = true;

        const existingAlert = currentContent.querySelector('.alert-error');
        if (existingAlert) {
            existingAlert.remove();
        }

        requiredFields.forEach(field => {
            if (!field.value.trim()) {
                isValid = false;
                field.classList.add('border-red-500');
                
                field.addEventListener('input', () => {
                    field.classList.remove('border-red-500');
                }, { once: true });
            }
        });

        const shippingAddressType = document.querySelector('input[name="shipping_address_type"]:checked');
        if (shippingAddressType && shippingAddressType.value === "existing" && !shippingAddressIdField.value) {
            isValid = false;
            document.querySelectorAll('input[name="shipping_address_type"][value="existing"]').forEach((radio) => {
                const label = radio.nextElementSibling;
                if (label) {
                    label.classList.add("text-red-500");
                }
            });
        }

        const billingAddressType = document.querySelector('input[name="billing_address_type"]:checked');
        if (billingAddressType && billingAddressType.value === "existing" && !billingAddressIdField.value) {
            isValid = false;
            document.querySelectorAll('input[name="billing_address_type"][value="existing"]').forEach((radio) => {
                const label = radio.nextElementSibling;
                if (label) {
                    label.classList.add("text-red-500");
                }
            });
        }

        if (!isValid) {
            const alertDiv = document.createElement("div");
            alertDiv.className = "alert-error bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4";
            alertDiv.innerHTML = "<strong>Error!</strong> Please fill in all required fields.";
            currentContent.insertBefore(alertDiv, currentContent.firstChild);

            setTimeout(() => {
                if (alertDiv.parentNode) {
                    alertDiv.parentNode.removeChild(alertDiv);
                }
            }, 5000);
            
            alert('Please fill in all required fields.');
        }

        return isValid;
    }

    const shippingAddressRadios = document.querySelectorAll('input[name="shipping_address_type"]');
    const shippingAddressForm = document.getElementById('shipping-address-form');

    shippingAddressRadios.forEach(radio => {
        radio.addEventListener('change', (e) => {
            document.querySelectorAll('input[name="shipping_address_type"]').forEach(r => {
                const label = r.nextElementSibling;
                if (label) {
                    label.classList.remove('text-red-500');
                }
            });

            if (e.target.value === 'new') {
                shippingAddressForm.style.display = 'grid';
                setFormFieldsRequired(shippingAddressForm, true);
                shippingAddressIdField.value = '';
            } else {
                shippingAddressForm.style.display = 'none';
                setFormFieldsRequired(shippingAddressForm, false);
                
                if (e.target.value === 'existing') {
                    // Fill form with saved address data
                    const addressData = JSON.parse(e.target.dataset.address || '{}');
                    fillAddressForm('shipping', addressData);
                    
                    // Set the shipping_address_id field value
                    shippingAddressIdField.value = e.target.id.replace('shipping_', '');
                }
            }
        });
    });

    // Handle billing address selection
    const billingAddressRadios = document.querySelectorAll('input[name="billing_address_type"]');
    const billingAddressForm = document.getElementById('billing-address-form');

    billingAddressRadios.forEach(radio => {
        radio.addEventListener('change', (e) => {
            // Reset error styling
            document.querySelectorAll('input[name="billing_address_type"]').forEach(r => {
                const label = r.nextElementSibling;
                if (label) {
                    label.classList.remove('text-red-500');
                }
            });

            if (e.target.value === 'new') {
                billingAddressForm.style.display = 'grid';
                setFormFieldsRequired(billingAddressForm, true);
                billingAddressIdField.value = '';
            } else if (e.target.value === 'existing') {
                billingAddressForm.style.display = 'none';
                setFormFieldsRequired(billingAddressForm, false);
                
                // Show saved billing addresses
                const savedBillingAddresses = document.getElementById('saved-billing-addresses');
                if (savedBillingAddresses) {
                    savedBillingAddresses.style.display = 'block';
                    
                    // Set billing_address_id if an address is already selected
                    const selectedAddress = savedBillingAddresses.querySelector('input[name="billing_address"]:checked');
                    if (selectedAddress) {
                        billingAddressIdField.value = selectedAddress.value;
                    }
                }
            } else {
                // same_as_shipping
                billingAddressForm.style.display = 'none';
                setFormFieldsRequired(billingAddressForm, false);
                
                // Use the same ID as shipping
                if (shippingAddressIdField.value) {
                    billingAddressIdField.value = shippingAddressIdField.value;
                }
            }
        });
    });

    // Add event listener for saved billing addresses
    document.addEventListener('click', (e) => {
        if (e.target && e.target.name === 'billing_address') {
            billingAddressIdField.value = e.target.value;
            
            // Reset error styling
            const billingTypeRadio = document.querySelector('input[name="billing_address_type"][value="existing"]');
            if (billingTypeRadio) {
                const label = billingTypeRadio.nextElementSibling;
                if (label) {
                    label.classList.remove('text-red-500');
                }
            }
        }
    });

    function setFormFieldsRequired(form, required) {
        const inputs = form.querySelectorAll('input, select');
        inputs.forEach(input => {
            if (required) {
                input.setAttribute('required', 'required');
            } else {
                input.removeAttribute('required');
            }
        });
    }

    function fillAddressForm(type, data) {
        document.querySelector(`input[name="${type}_first_name"]`).value = data.first_name || '';
        document.querySelector(`input[name="${type}_last_name"]`).value = data.last_name || '';
        document.querySelector(`input[name="${type}_company"]`).value = data.company || '';
        document.querySelector(`input[name="${type}_address_line_1"]`).value = data.address_line_1 || '';
        document.querySelector(`input[name="${type}_address_line_2"]`).value = data.address_line_2 || '';
        document.querySelector(`input[name="${type}_city"]`).value = data.city || '';
        document.querySelector(`input[name="${type}_state"]`).value = data.state || '';
        document.querySelector(`input[name="${type}_postal_code"]`).value = data.postal_code || '';
        document.querySelector(`select[name="${type}_country"]`).value = data.country || 'FR';
        document.querySelector(`input[name="${type}_phone"]`).value = data.phone || '';
        document.querySelector(`input[name="${type}_email"]`).value = data.email || '';
    }

    // Handle payment method selection
    const paymentMethods = document.querySelectorAll('.payment-method');
    const cardFields = document.getElementById('card-fields');

    paymentMethods.forEach(method => {
        method.addEventListener('click', (e) => {
            // Prevent radio button inside from triggering double
            if (e.target.type === 'radio') return;
            
            // Get the radio button inside this payment method
            const radio = method.querySelector('input[type="radio"]');
            radio.checked = true;
            
            // Update UI
            paymentMethods.forEach(m => m.classList.remove('selected'));
            method.classList.add('selected');
            
            // Show/hide card fields
            if (radio.value === 'card') {
                cardFields.style.display = 'block';
            } else {
                cardFields.style.display = 'none';
            }
            
            // Log the selected payment method for debugging
            console.log('Payment method selected:', radio.value);
        });
    });

    // Handle coupon code application
    const applyCouponBtn = document.getElementById('apply-coupon-btn');
    const couponInput = document.getElementById('coupon-code');

    if (applyCouponBtn) {
        applyCouponBtn.addEventListener('click', async () => {
            if (applyCouponBtn.classList.contains('remove-coupon')) {
                // Handle coupon removal
                try {
                    const response = await fetch(window.routes.removeCoupon, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    });
                    
                    const data = await response.json();
                    
                    if (data.success) {
                        updateOrderSummary(data.cart);
                        couponInput.value = '';
                        couponInput.disabled = false;
                        applyCouponBtn.innerHTML = 'Apply';
                        applyCouponBtn.classList.remove('remove-coupon');
                        
                        // Hide discount row
                        const discountRow = document.querySelector('.discount-row');
                        if (discountRow) {
                            discountRow.classList.add('hidden');
                        }
                    }
                } catch (error) {
                    console.error('Error removing coupon:', error);
                    alert('Error removing coupon. Please try again.');
                }
                return;
            }
            
            const couponCode = couponInput.value.trim();
            
            if (!couponCode) {
                alert('Please enter a coupon code.');
                return;
            }
            
            applyCouponBtn.disabled = true;
            applyCouponBtn.innerHTML = '<i class="ri-loader-4-line animate-spin"></i>';
            
            try {
                const response = await fetch(window.routes.applyCoupon, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({ coupon_code: couponCode })
                });
                
                const data = await response.json();
                
                if (data.success) {
                    // Update the UI with the new totals
                    updateOrderSummary(data.cart);
                    couponInput.value = data.cart.coupon_code;
                    couponInput.disabled = true;
                    applyCouponBtn.innerHTML = 'Remove';
                    applyCouponBtn.classList.add('remove-coupon');
                    applyCouponBtn.disabled = false;
                    
                    // Show discount row
                    const discountRow = document.querySelector('.discount-row');
                    if (discountRow) {
                        discountRow.classList.remove('hidden');
                        discountRow.querySelector('.discount-amount').textContent = `-€${data.cart.discount.toFixed(2)}`;
                    }
                } else {
                    alert(data.message);
                    applyCouponBtn.disabled = false;
                    applyCouponBtn.textContent = 'Apply';
                }
            } catch (error) {
                console.error('Error applying coupon:', error);
                alert('Error applying coupon. Please try again.');
                applyCouponBtn.disabled = false;
                applyCouponBtn.textContent = 'Apply';
            }
        });
    }
    
    // Function to update order summary
    function updateOrderSummary(cart) {
        document.querySelector('.subtotal-amount').textContent = `€${cart.subtotal.toFixed(2)}`;
        document.querySelector('.shipping-amount').textContent = cart.shipping == 0 ? 'Free' : `€${cart.shipping.toFixed(2)}`;
        document.querySelector('.tax-amount').textContent = `€${cart.tax.toFixed(2)}`;
        document.querySelector('.total-amount').textContent = `€${cart.total.toFixed(2)}`;
        
        if (document.querySelector('#place-order-btn .btn-text')) {
            document.querySelector('#place-order-btn .btn-text').textContent = `Place Order • €${cart.total.toFixed(2)}`;
        }
    }

    checkoutForm.addEventListener('submit', async (e) => {
        e.preventDefault();
        
        if (!validateCurrentStep()) return;
        
        const placeOrderBtn = document.getElementById('place-order-btn');
        const btnText = placeOrderBtn.querySelector('.btn-text');
        const btnLoading = placeOrderBtn.querySelector('.btn-loading');
        
        placeOrderBtn.disabled = true;
        btnText.style.display = 'none';
        btnLoading.style.display = 'inline-flex';
        
        try {
            const formData = new FormData(checkoutForm);
            
            const selectedPaymentMethod = document.querySelector('input[name="payment_method"]:checked');
            if (selectedPaymentMethod) {
                console.log('Selected payment method:', selectedPaymentMethod.value);
            } else {
                console.warn('No payment method selected');
            }
            
            const response = await fetch(checkoutForm.action, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: formData
            });
            
            console.log('Response status:', response.status);
            
            const data = await response.json();
            console.log('Server response:', data);
            
            if (data.success) {
                if (data.redirect_url) {
                    console.log('Redirecting to:', data.redirect_url);
                    
                    setTimeout(() => {
                        window.location.href = data.redirect_url;
                    }, 100);
                } else {
                    console.error('No redirect URL provided in the response');
                    alert('Order processed successfully, but no redirect URL was provided.');
                    
                    placeOrderBtn.disabled = false;
                    btnText.style.display = 'inline-flex';
                    btnLoading.style.display = 'none';
                }
            } else {
                alert(data.message || 'An error occurred while processing your order.');
                
                placeOrderBtn.disabled = false;
                btnText.style.display = 'inline-flex';
                btnLoading.style.display = 'none';
            }
        } catch (error) {
            console.error('Error processing order:', error);
            alert('Error processing your order. Please try again.');
            
            placeOrderBtn.disabled = false;
            btnText.style.display = 'inline-flex';
            btnLoading.style.display = 'none';
        }
    });

    const cardNumberInput = document.querySelector('input[placeholder="1234 5678 9012 3456"]');
    if (cardNumberInput) {
        cardNumberInput.addEventListener('input', (e) => {
            let value = e.target.value.replace(/\D/g, '');
            let formattedValue = '';
            
            for (let i = 0; i < value.length; i++) {
                if (i > 0 && i % 4 === 0) {
                    formattedValue += ' ';
                }
                formattedValue += value[i];
            }
            
            e.target.value = formattedValue.slice(0, 19);
        });
    }

    // Format expiry date input
    const expiryInput = document.querySelector('input[placeholder="MM/YY"]');
    if (expiryInput) {
        expiryInput.addEventListener('input', (e) => {
            let value = e.target.value.replace(/\D/g, '');
            
            if (value.length >= 2) {
                value = value.slice(0, 2) + '/' + value.slice(2);
            }
            
            e.target.value = value.slice(0, 5); 
        });
    }

    const cvcInput = document.querySelector('input[placeholder="123"]');
    if (cvcInput) {
        cvcInput.addEventListener('input', (e) => {
            e.target.value = e.target.value.replace(/\D/g, '').slice(0, 4);
        });
    }

    const selectedPaymentMethod = document.querySelector('.payment-method input[type="radio"]:checked');
    if (selectedPaymentMethod) {
        selectedPaymentMethod.closest('.payment-method').classList.add('selected');
        if (selectedPaymentMethod.value === 'card') {
            cardFields.style.display = 'block';
        }
    }

    const style = document.createElement('style');
    style.textContent = `
        .text-red-500 {
            color: #ef4444;
        }
        .border-red-500 {
            border-color: #ef4444;
        }
        .bg-red-100 {
            background-color: #fee2e2;
        }
        .border-red-400 {
            border-color: #f87171;
        }
        .text-red-700 {
            color: #b91c1c;
        }
        .alert-error {
            margin-bottom: 1rem;
            padding: 0.75rem 1rem;
            border-radius: 0.25rem;
        }
    `;
    document.head.appendChild(style);

    const preSelectedShippingAddress = document.querySelector('input[name="shipping_address_type"][value="existing"]:checked');
    if (preSelectedShippingAddress) {
        shippingAddressIdField.value = preSelectedShippingAddress.id.replace('shipping_', '');
        shippingAddressForm.style.display = 'none';
        setFormFieldsRequired(shippingAddressForm, false);
    }

    const preSelectedBillingType = document.querySelector('input[name="billing_address_type"]:checked');
    if (preSelectedBillingType) {
        if (preSelectedBillingType.value === 'existing') {
            const selectedBillingAddress = document.querySelector('input[name="billing_address"]:checked');
            if (selectedBillingAddress) {
                billingAddressIdField.value = selectedBillingAddress.value;
            }
            billingAddressForm.style.display = 'none';
            setFormFieldsRequired(billingAddressForm, false);
        } else if (preSelectedBillingType.value === 'same_as_shipping') {
            billingAddressForm.style.display = 'none';
            setFormFieldsRequired(billingAddressForm, false);
            if (shippingAddressIdField.value) {
                billingAddressIdField.value = shippingAddressIdField.value;
            }
        }
    }
    
    console.log('Checkout form initialized');
});