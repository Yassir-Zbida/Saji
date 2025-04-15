<!-- resources/views/checkout.blade.php -->
@extends('layouts.app')

@section('title', 'Checkout')

@section('styles')
<style>
    .checkout-header {
        padding: 3rem 0;
        background-color: var(--gray-100);
        text-align: center;
    }
    
    .checkout-title {
        font-size: 2.5rem;
        font-weight: 700;
        margin-bottom: 1rem;
    }
    
    .checkout-container {
        padding: 3rem 0;
    }
    
    .checkout-content {
        display: grid;
        grid-template-columns: 1.5fr 1fr;
        gap: 2rem;
    }
    
    @media (max-width: 992px) {
        .checkout-content {
            grid-template-columns: 1fr;
        }
    }
    
    .checkout-form {
        width: 100%;
    }
    
    .form-section {
        margin-bottom: 2rem;
    }
    
    .section-title {
        font-size: 1.25rem;
        font-weight: 600;
        margin-bottom: 1.5rem;
        padding-bottom: 0.5rem;
        border-bottom: 1px solid var(--gray-200);
    }
    
    .form-row {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 1rem;
        margin-bottom: 1rem;
    }
    
    .form-group {
        margin-bottom: 1rem;
    }
    
    .form-label {
        display: block;
        font-size: 0.875rem;
        font-weight: 500;
        margin-bottom: 0.5rem;
    }
    
    .form-control {
        width: 100%;
        padding: 0.75rem;
        border: 1px solid var(--gray-300);
        transition: border-color 0.3s ease;
    }
    
    .form-control:focus {
        outline: none;
        border-color: var(--gray-800);
    }
    
    .form-check {
        display: flex;
        align-items: center;
        margin-bottom: 0.5rem;
    }
    
    .form-check-input {
        margin-right: 0.5rem;
    }
    
    .form-check-label {
        font-size: 0.875rem;
    }
    
    .payment-methods {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 1rem;
        margin-bottom: 1rem;
    }
    
    .payment-method {
        position: relative;
        border: 1px solid var(--gray-300);
        padding: 1rem;
        text-align: center;
        cursor: pointer;
        transition: all 0.3s ease;
    }
    
    .payment-method.active {
        border-color: var(--black);
    }
    
    .payment-method-input {
        position: absolute;
        opacity: 0;
    }
    
    .payment-method-label {
        display: block;
        cursor: pointer;
    }
    
    .payment-method-icon {
        font-size: 1.5rem;
        margin-bottom: 0.5rem;
    }
    
    .payment-method-name {
        font-size: 0.875rem;
        font-weight: 500;
    }
    
    .order-summary {
        background: var(--gray-100);
        padding: 2rem;
    }
    
    .summary-title {
        font-size: 1.25rem;
        font-weight: 600;
        margin-bottom: 1.5rem;
    }
    
    .summary-items {
        margin-bottom: 1.5rem;
    }
    
    .summary-item {
        display: flex;
        justify-content: space-between;
        margin-bottom: 1rem;
        padding-bottom: 1rem;
        border-bottom: 1px solid var(--gray-200);
    }
    
    .summary-item-info {
        display: flex;
        align-items: center;
    }
    
    .summary-item-image {
        width: 60px;
        height: 60px;
        object-fit: cover;
        margin-right: 1rem;
    }
    
    .summary-item-name {
        font-size: 0.875rem;
        font-weight: 500;
    }
    
    .summary-item-quantity {
        font-size: 0.75rem;
        color: var(--gray-600);
    }
    
    .summary-item-price {
        font-size: 0.875rem;
        font-weight: 500;
    }
    
    .summary-row {
        display: flex;
        justify-content: space-between;
        margin-bottom: 1rem;
    }
    
    .summary-label {
        color: var(--gray-600);
    }
    
    .summary-value {
        font-weight: 500;
    }
    
    .summary-total {
        display: flex;
        justify-content: space-between;
        margin-top: 1.5rem;
        padding-top: 1.5rem;
        border-top: 1px solid var(--gray-300);
        font-size: 1.125rem;
        font-weight: 600;
    }
    
    .checkout-steps {
        display: flex;
        margin-bottom: 2rem;
    }
    
    .checkout-step {
        flex: 1;
        text-align: center;
        padding: 1rem;
        background: var(--gray-200);
        color: var(--gray-600);
        position: relative;
    }
    
    .checkout-step.active {
        background: var(--gray-800);
        color: var(--white);
    }
    
    .checkout-step:not(:last-child)::after {
        content: '';
        position: absolute;
        top: 50%;
        right: -10px;
        transform: translateY(-50%);
        width: 20px;
        height: 20px;
        background: inherit;
        clip-path: polygon(0 50%, 50% 0, 50% 100%);
        z-index: 1;
    }
</style>
@endsection

@section('content')
<div class="checkout-header">
    <div class="container">
        <h1 class="checkout-title">Checkout</h1>
    </div>
</div>

<div class="container">
    <div class="checkout-container">
        <div class="checkout-steps">
            <div class="checkout-step active">
                <span>Shipping</span>
            </div>
            <div class="checkout-step">
                <span>Payment</span>
            </div>
            <div class="checkout-step">
                <span>Confirmation</span>
            </div>
        </div>
        
        <div class="checkout-content">
            <div class="checkout-form">
                <form id="checkout-form" action="{{ route('checkout.process') }}" method="POST">
                    @csrf
                    
                    <div class="form-section">
                        <h2 class="section-title">Contact Information</h2>
                        
                        <div class="form-row">
                            <div class="form-group">
                                <label for="email" class="form-label">Email Address *</label>
                                <input type="email" id="email" name="email" class="form-control" required value="{{ auth()->user()->email ?? '' }}">
                            </div>
                            
                            <div class="form-group">
                                <label for="phone" class="form-label">Phone Number *</label>
                                <input type="tel" id="phone" name="phone" class="form-control" required>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-section">
                        <h2 class="section-title">Shipping Address</h2>
                        
                        <div class="form-row">
                            <div class="form-group">
                                <label for="first_name" class="form-label">First Name *</label>
                                <input type="text" id="first_name" name="first_name" class="form-control" required value="{{ auth()->user()->first_name ?? '' }}">
                            </div>
                            
                            <div class="form-group">
                                <label for="last_name" class="form-label">Last Name *</label>
                                <input type="text" id="last_name" name="last_name" class="form-control" required value="{{ auth()->user()->last_name ?? '' }}">
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="address" class="form-label">Address *</label>
                            <input type="text" id="address" name="address" class="form-control" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="apartment" class="form-label">Apartment, Suite, etc. (optional)</label>
                            <input type="text" id="apartment" name="apartment" class="form-control">
                        </div>
                        
                        <div class="form-row">
                            <div class="form-group">
                                <label for="city" class="form-label">City *</label>
                                <input type="text" id="city" name="city" class="form-control" required>
                            </div>
                            
                            <div class="form-group">
                                <label for="state" class="form-label">State/Province *</label>
                                <input type="text" id="state" name="state" class="form-control" required>
                            </div>
                        </div>
                        
                        <div class="form-row">
                            <div class="form-group">
                                <label for="zip" class="form-label">Zip/Postal Code *</label>
                                <input type="text" id="zip" name="zip" class="form-control" required>
                            </div>
                            
                            <div class="form-group">
                                <label for="country" class="form-label">Country *</label>
                                <select id="country" name="country" class="form-control" required>
                                    <option value="">Select Country</option>
                                    <option value="US">United States</option>
                                    <option value="CA">Canada</option>
                                    <option value="GB">United Kingdom</option>
                                    <option value="AU">Australia</option>
                                    <!-- Add more countries as needed -->
                                </select>
                            </div>
                        </div>
                        
                        <div class="form-check">
                            <input type="checkbox" id="save_address" name="save_address" class="form-check-input">
                            <label for="save_address" class="form-check-label">Save this address for future orders</label>
                        </div>
                        
                        <div class="form-check">
                            <input type="checkbox" id="same_billing" name="same_billing" class="form-check-input" checked>
                            <label for="same_billing" class="form-check-label">Billing address same as shipping</label>
                        </div>
                    </div>
                    
                    <div class="form-section" id="billing-address-section" style="display: none;">
                        <h2 class="section-title">Billing Address</h2>
                        
                        <!-- Billing address fields (similar to shipping address) -->
                        <!-- Hidden by default, shown when "same_billing" is unchecked -->
                    </div>
                    
                    <div class="form-section">
                        <h2 class="section-title">Shipping Method</h2>
                        
                        <div class="form-check">
                            <input type="radio" id="shipping_standard" name="shipping_method" value="standard" class="form-check-input" checked>
                            <label for="shipping_standard" class="form-check-label">Standard Shipping (3-5 business days) - $5.99</label>
                        </div>
                        
                        <div class="form-check">
                            <input type="radio" id="shipping_express" name="shipping_method" value="express" class="form-check-input">
                            <label for="shipping_express" class="form-check-label">Express Shipping (1-2 business days) - $12.99</label>
                        </div>
                    </div>
                    
                    <div class="form-section">
                        <h2 class="section-title">Payment Method</h2>
                        
                        <div class="payment-methods">
                            <div class="payment-method active">
                                <input type="radio" id="payment_credit_card" name="payment_method" value="credit_card" class="payment-method-input" checked>
                                <label for="payment_credit_card" class="payment-method-label">
                                    <div class="payment-method-icon">
                                        <i class="ri-bank-card-line"></i>
                                    </div>
                                    <div class="payment-method-name">Credit Card</div>
                                </label>
                            </div>
                            
                            <div class="payment-method">
                                <input type="radio" id="payment_paypal" name="payment_method" value="paypal" class="payment-method-input">
                                <label for="payment_paypal" class="payment-method-label">
                                    <div class="payment-method-icon">
                                        <i class="ri-paypal-line"></i>
                                    </div>
                                    <div class="payment-method-name">PayPal</div>
                                </label>
                            </div>
                            
                            <div class="payment-method">
                                <input type="radio" id="payment_bank_transfer" name="payment_method" value="bank_transfer" class="payment-method-input">
                                <label for="payment_bank_transfer" class="payment-method-label">
                                    <div class="payment-method-icon">
                                        <i class="ri-bank-line"></i>
                                    </div>
                                    <div class="payment-method-name">Bank Transfer</div>
                                </label>
                            </div>
                        </div>
                        
                        <div id="credit-card-fields">
                            <div class="form-group">
                                <label for="card_number" class="form-label">Card Number *</label>
                                <input type="text" id="card_number" name="card_number" class="form-control" placeholder="1234 5678 9012 3456">
                            </div>
                            
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="card_expiry" class="form-label">Expiration Date *</label>
                                    <input type="text" id="card_expiry" name="card_expiry" class="form-control" placeholder="MM/YY">
                                </div>
                                
                                <div class="form-group">
                                    <label for="card_cvc" class="form-label">CVC *</label>
                                    <input type="text" id="card_cvc" name="card_cvc" class="form-control" placeholder="123">
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label for="card_name" class="form-label">Name on Card *</label>
                                <input type="text" id="card_name" name="card_name" class="form-control">
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-section">
                        <h2 class="section-title">Order Notes</h2>
                        
                        <div class="form-group">
                            <label for="notes" class="form-label">Notes (optional)</label>
                            <textarea id="notes" name="notes" class="form-control" rows="3" placeholder="Special instructions for delivery or any other notes"></textarea>
                        </div>
                    </div>
                    
                    <button type="submit" class="btn btn-primary" style="width: 100%;">Place Order</button>
                </form>
            </div>
            
            <div class="order-summary">
                <h2 class="summary-title">Order Summary</h2>
                
                <div class="summary-items">
                    @foreach($cartItems as $item)
                    <div class="summary-item">
                        <div class="summary-item-info">
                            <img src="{{ asset($item->product->image) }}" alt="{{ $item->product->name }}" class="summary-item-image">
                            <div>
                                <div class="summary-item-name">{{ $item->product->name }}</div>
                                <div class="summary-item-quantity">Qty: {{ $item->quantity }}</div>
                            </div>
                        </div>
                        <div class="summary-item-price">${{ number_format($item->product->price * $item->quantity, 2) }}</div>
                    </div>
                    @endforeach
                </div>
                
                <div class="summary-row">
                    <span class="summary-label">Subtotal</span>
                    <span class="summary-value">${{ number_format($subtotal, 2) }}</span>
                </div>
                
                <div class="summary-row">
                    <span class="summary-label">Shipping</span>
                    <span class="summary-value">${{ number_format($shipping, 2) }}</span>
                </div>
                
                @if($discount > 0)
                <div class="summary-row">
                    <span class="summary-label">Discount</span>
                    <span class="summary-value">-${{ number_format($discount, 2) }}</span>
                </div>
                @endif
                
                <div class="summary-row">
                    <span class="summary-label">Tax</span>
                    <span class="summary-value">${{ number_format($tax, 2) }}</span>
                </div>
                
                <div class="summary-total">
                    <span>Total</span>
                    <span>${{ number_format($total, 2) }}</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Toggle billing address section
    const sameBillingCheckbox = document.getElementById('same_billing');
    const billingAddressSection = document.getElementById('billing-address-section');
    
    sameBillingCheckbox.addEventListener('change', function() {
        if (this.checked) {
            billingAddressSection.style.display = 'none';
        } else {
            billingAddressSection.style.display = 'block';
        }
    });
    
    // Payment method selection
    const paymentMethods = document.querySelectorAll('.payment-method');
    const paymentInputs = document.querySelectorAll('.payment-method-input');
    const creditCardFields = document.getElementById('credit-card-fields');
    
    paymentMethods.forEach(method => {
        method.addEventListener('click', function() {
            // Update active class
            paymentMethods.forEach(m => m.classList.remove('active'));
            this.classList.add('active');
            
            // Check the radio input
            const input = this.querySelector('.payment-method-input');
            input.checked = true;
            
            // Show/hide credit card fields
            if (input.value === 'credit_card') {
                creditCardFields.style.display = 'block';
            } else {
                creditCardFields.style.display = 'none';
            }
        });
    });
    
    // Form validation
    const checkoutForm = document.getElementById('checkout-form');
    
    checkoutForm.addEventListener('submit', function(e) {
        let isValid = true;
        
        // Basic validation
        const requiredFields = this.querySelectorAll('[required]');
        
        requiredFields.forEach(field => {
            if (!field.value.trim()) {
                isValid = false;
                field.style.borderColor = 'red';
            } else {
                field.style.borderColor = '';
            }
        });
        
        // Credit card validation if selected
        if (document.getElementById('payment_credit_card').checked) {
            const cardNumber = document.getElementById('card_number');
            const cardExpiry = document.getElementById('card_expiry');
            const cardCVC = document.getElementById('card_cvc');
            const cardName = document.getElementById('card_name');
            
            if (!cardNumber.value.trim() || !cardExpiry.value.trim() || !cardCVC.value.trim() || !cardName.value.trim()) {
                isValid = false;
                
                if (!cardNumber.value.trim()) cardNumber.style.borderColor = 'red';
                if (!cardExpiry.value.trim()) cardExpiry.style.borderColor = 'red';
                if (!cardCVC.value.trim()) cardCVC.style.borderColor = 'red';
                if (!cardName.value.trim()) cardName.style.borderColor = 'red';
            }
        }
        
        if (!isValid) {
            e.preventDefault();
            alert('Please fill in all required fields.');
        }
    });
</script>
@endsection