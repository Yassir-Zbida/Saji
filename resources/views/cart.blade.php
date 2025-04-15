<!-- resources/views/cart.blade.php -->
@extends('layouts.app')

@section('title', 'Shopping Cart')

@section('styles')
<style>
    .cart-header {
        padding: 3rem 0;
        background-color: var(--gray-100);
        text-align: center;
    }
    
    .cart-title {
        font-size: 2.5rem;
        font-weight: 700;
        margin-bottom: 1rem;
    }
    
    .cart-container {
        padding: 3rem 0;
    }
    
    .cart-empty {
        text-align: center;
        padding: 3rem 0;
    }
    
    .cart-empty-icon {
        font-size: 4rem;
        color: var(--gray-400);
        margin-bottom: 1rem;
    }
    
    .cart-empty-title {
        font-size: 1.5rem;
        font-weight: 600;
        margin-bottom: 1rem;
    }
    
    .cart-empty-text {
        color: var(--gray-600);
        margin-bottom: 2rem;
    }
    
    .cart-content {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 2rem;
    }
    
    @media (max-width: 768px) {
        .cart-content {
            grid-template-columns: 1fr;
        }
    }
    
    .cart-items {
        width: 100%;
    }
    
    .cart-item {
        display: grid;
        grid-template-columns: 100px 1fr auto;
        gap: 1.5rem;
        padding: 1.5rem 0;
        border-bottom: 1px solid var(--gray-200);
    }
    
    .cart-item-image {
        width: 100px;
        height: 100px;
        object-fit: cover;
    }
    
    .cart-item-details {
        display: flex;
        flex-direction: column;
    }
    
    .cart-item-title {
        font-size: 1.125rem;
        font-weight: 500;
        margin-bottom: 0.5rem;
    }
    
    .cart-item-variant {
        font-size: 0.875rem;
        color: var(--gray-600);
        margin-bottom: 0.5rem;
    }
    
    .cart-item-price {
        font-size: 1rem;
        font-weight: 600;
        margin-bottom: 1rem;
    }
    
    .cart-item-quantity {
        display: flex;
        align-items: center;
    }
    
    .quantity-input {
        display: flex;
        align-items: center;
        width: fit-content;
        border: 1px solid var(--gray-300);
    }
    
    .quantity-btn {
        width: 2rem;
        height: 2rem;
        display: flex;
        align-items: center;
        justify-content: center;
        background: transparent;
        border: none;
        cursor: pointer;
    }
    
    .quantity-value {
        width: 2.5rem;
        height: 2rem;
        text-align: center;
        border: none;
        border-left: 1px solid var(--gray-300);
        border-right: 1px solid var(--gray-300);
    }
    
    .cart-item-remove {
        background: transparent;
        border: none;
        color: var(--gray-500);
        cursor: pointer;
        margin-left: 1rem;
        transition: color 0.3s ease;
    }
    
    .cart-item-remove:hover {
        color: var(--black);
    }
    
    .cart-item-total {
        font-size: 1.125rem;
        font-weight: 600;
        display: flex;
        align-items: center;
    }
    
    .cart-actions {
        display: flex;
        justify-content: space-between;
        margin-top: 2rem;
    }
    
    .cart-summary {
        background: var(--gray-100);
        padding: 2rem;
    }
    
    .summary-title {
        font-size: 1.25rem;
        font-weight: 600;
        margin-bottom: 1.5rem;
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
    
    .promo-code {
        margin-top: 1.5rem;
        padding-top: 1.5rem;
        border-top: 1px solid var(--gray-300);
    }
    
    .promo-title {
        font-size: 1rem;
        font-weight: 500;
        margin-bottom: 1rem;
    }
    
    .promo-form {
        display: flex;
    }
    
    .promo-input {
        flex: 1;
        padding: 0.75rem;
        border: 1px solid var(--gray-300);
        border-right: none;
    }
    
    .promo-btn {
        padding: 0.75rem 1rem;
        background: var(--gray-800);
        color: var(--white);
        border: 1px solid var(--gray-800);
        cursor: pointer;
        transition: all 0.3s ease;
    }
    
    .promo-btn:hover {
        background: var(--black);
        border-color: var(--black);
    }
</style>
@endsection

@section('content')
<div class="cart-header">
    <div class="container">
        <h1 class="cart-title">Shopping Cart</h1>
    </div>
</div>

<div class="container">
    <div class="cart-container">
        @if(count($cartItems) > 0)
            <div class="cart-content">
                <div class="cart-items">
                    @foreach($cartItems as $item)
                    <div class="cart-item">
                        <img src="{{ asset($item->product->image) }}" alt="{{ $item->product->name }}" class="cart-item-image">
                        
                        <div class="cart-item-details">
                            <h3 class="cart-item-title">{{ $item->product->name }}</h3>
                            
                            @if($item->variant)
                            <p class="cart-item-variant">{{ $item->variant }}</p>
                            @endif
                            
                            <p class="cart-item-price">${{ number_format($item->product->price, 2) }}</p>
                            
                            <div class="cart-item-quantity">
                                <div class="quantity-input">
                                    <button class="quantity-btn decrease-quantity" data-id="{{ $item->id }}">
                                        <i class="ri-subtract-line"></i>
                                    </button>
                                    <input type="number" class="quantity-value" value="{{ $item->quantity }}" min="1" max="{{ $item->product->stock }}" data-id="{{ $item->id }}">
                                    <button class="quantity-btn increase-quantity" data-id="{{ $item->id }}">
                                        <i class="ri-add-line"></i>
                                    </button>
                                </div>
                                
                                <button class="cart-item-remove" data-id="{{ $item->id }}">
                                    <i class="ri-delete-bin-line"></i> Remove
                                </button>
                            </div>
                        </div>
                        
                        <div class="cart-item-total">
                            ${{ number_format($item->product->price * $item->quantity, 2) }}
                        </div>
                    </div>
                    @endforeach
                    
                    <div class="cart-actions">
                        <a href="{{ route('shop') }}" class="btn">Continue Shopping</a>
                        <button class="btn" id="clear-cart">Clear Cart</button>
                    </div>
                </div>
                
                <div class="cart-summary">
                    <h2 class="summary-title">Order Summary</h2>
                    
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
                    
                    <div class="promo-code">
                        <h3 class="promo-title">Promo Code</h3>
                        <form class="promo-form">
                            <input type="text" class="promo-input" placeholder="Enter promo code">
                            <button type="submit" class="promo-btn">Apply</button>
                        </form>
                    </div>
                    
                    <a href="{{ route('checkout') }}" class="btn btn-primary" style="width: 100%; margin-top: 1.5rem;">Proceed to Checkout</a>
                </div>
            </div>
        @else
            <div class="cart-empty">
                <i class="ri-shopping-bag-line cart-empty-icon"></i>
                <h2 class="cart-empty-title">Your cart is empty</h2>
                <p class="cart-empty-text">Looks like you haven't added any products to your cart yet.</p>
                <a href="{{ route('shop') }}" class="btn btn-primary">Start Shopping</a>
            </div>
        @endif
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Update quantity
    const quantityInputs = document.querySelectorAll('.quantity-value');
    const decreaseButtons = document.querySelectorAll('.decrease-quantity');
    const increaseButtons = document.querySelectorAll('.increase-quantity');
    
    quantityInputs.forEach(input => {
        input.addEventListener('change', () => {
            const itemId = input.getAttribute('data-id');
            const quantity = parseInt(input.value);
            
            if (quantity < 1) {
                input.value = 1;
            }
            
            updateCartItem(itemId, input.value);
        });
    });
    
    decreaseButtons.forEach(button => {
        button.addEventListener('click', () => {
            const itemId = button.getAttribute('data-id');
            const input = document.querySelector(`.quantity-value[data-id="${itemId}"]`);
            const currentValue = parseInt(input.value);
            
            if (currentValue > 1) {
                input.value = currentValue - 1;
                updateCartItem(itemId, input.value);
            }
        });
    });
    
    increaseButtons.forEach(button => {
        button.addEventListener('click', () => {
            const itemId = button.getAttribute('data-id');
            const input = document.querySelector(`.quantity-value[data-id="${itemId}"]`);
            const currentValue = parseInt(input.value);
            const maxValue = parseInt(input.getAttribute('max'));
            
            if (currentValue < maxValue) {
                input.value = currentValue + 1;
                updateCartItem(itemId, input.value);
            }
        });
    });
    
    // Remove item
    const removeButtons = document.querySelectorAll('.cart-item-remove');
    
    removeButtons.forEach(button => {
        button.addEventListener('click', () => {
            const itemId = button.getAttribute('data-id');
            removeCartItem(itemId);
        });
    });
    
    // Clear cart
    const clearCartButton = document.getElementById('clear-cart');
    
    if (clearCartButton) {
        clearCartButton.addEventListener('click', () => {
            if (confirm('Are you sure you want to clear your cart?')) {
                clearCart();
            }
        });
    }
    
    // Apply promo code
    const promoForm = document.querySelector('.promo-form');
    
    if (promoForm) {
        promoForm.addEventListener('submit', (e) => {
            e.preventDefault();
            const promoCode = promoForm.querySelector('.promo-input').value;
            applyPromoCode(promoCode);
        });
    }
    
    // Functions to handle cart operations
    function updateCartItem(itemId, quantity) {
        // Send AJAX request to update cart item
        fetch(`/cart/update/${itemId}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ quantity })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                window.location.reload();
            }
        })
        .catch(error => console.error('Error:', error));
    }
    
    function removeCartItem(itemId) {
        // Send AJAX request to remove cart item
        fetch(`/cart/remove/${itemId}`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                window.location.reload();
            }
        })
        .catch(error => console.error('Error:', error));
    }
    
    function clearCart() {
        // Send AJAX request to clear cart
        fetch('/cart/clear', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                window.location.reload();
            }
        })
        .catch(error => console.error('Error:', error));
    }
    
    function applyPromoCode(promoCode) {
        // Send AJAX request to apply promo code
        fetch('/cart/promo', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ promo_code: promoCode })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                window.location.reload();
            } else {
                alert(data.message || 'Invalid promo code');
            }
        })
        .catch(error => console.error('Error:', error));
    }
</script>
@endsection