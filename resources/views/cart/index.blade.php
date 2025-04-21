@extends('layouts.app')

@section('content')
    <div class="container py-12 md:px-12 mb-12">
        <div class="mb-8">
            <h1 class="text-3xl font-medium text-primary mb-2 font-jost">My Cart</h1>
            <p class="text-gray-600">Items you're ready to purchase</p>
        </div>

        <div class="bg-white rounded-lg shadow-soft p-6 pb-12 border border-gray-200">
            <div id="cartContainer">
                <div class="flex justify-center p-8">
                    <div class="spinner-border animate-spin inline-block w-8 h-8 border-4 rounded-full border-t-transparent"
                        role="status">
                        <span class="sr-only">Loading...</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Toast Container -->
    <div id="toast-container" class="fixed bottom-6 right-6 z-50"></div>

    <!-- Cart Empty Template -->
    <template id="cartEmptyTemplate">
        <div class="text-center py-16">
            <div class="inline-block p-6 rounded-full bg-secondary mb-6">
                <i class="ri-shopping-cart-line text-5xl text-gray-400"></i>
            </div>
            <h2 class="text-2xl font-medium text-primary mb-3">Your cart is empty</h2>
            <p class="text-gray-600 mb-8 max-w-md mx-auto">Start adding products to your cart to begin checkout</p>
            <a href="/shop"
                class="inline-flex items-center bg-black text-white py-3 px-8 rounded-lg hover:bg-black/80 transition duration-300">
                <span>Explore Products</span>
                <i class="ri-arrow-right-line ml-2"></i>
            </a>
        </div>
    </template>

    <!-- Cart Items Template -->
    <template id="cartItemsTemplate">
        <div class="cart-items-container">
            <div class="flex justify-between items-center mb-8">
                <p class="text-gray-700"><span class="font-medium cart-item-count">0</span> <span
                        class="cart-item-text">items</span></p>
                <button type="button" id="clear-cart"
                    class="text-gray-600 hover:text-primary flex items-center transition duration-200 group">
                    <i class="ri-delete-bin-7-line mr-1"></i> Clear all
                </button>
            </div>

            <div class="lg:flex lg:gap-8">
                <!-- Cart Items -->
                <div class="lg:w-2/3">
                    <div class="cart-items-list space-y-4">
                        <!-- Cart items will be inserted here -->
                    </div>
                </div>

                <!-- Cart Summary -->
                <div class="lg:w-1/3 mt-8 lg:mt-0">
                    <div class="bg-gray-50 rounded-lg p-6 sticky top-24">
                        <h3 class="text-lg font-medium mb-4">Order Summary</h3>

                        <div class="space-y-4 mb-6">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Subtotal</span>
                                <span class="cart-subtotal font-medium">$0.00</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Shipping</span>
                                <span>Calculated at checkout</span>
                            </div>
                            <div id="discount-row" class="flex justify-between hidden">
                                <span class="text-gray-600">Discount</span>
                                <span class="cart-discount text-green-600">-$0.00</span>
                            </div>
                        </div>

                        <!-- Coupon Code -->
                        <div class="mb-6">
                            <div class="relative">
                                <input type="text" id="coupon-code" placeholder="Enter coupon code"
                                    class="w-full border border-gray-300 rounded-lg pl-3 pr-24 py-3 focus:ring-1 focus:ring-black focus:border-black transition-all text-sm">
                                <button id="apply-coupon"
                                    class="absolute right-1 top-1 bg-black text-white px-4 py-2 rounded-md text-sm hover:bg-black/80 transition-colors">
                                    Apply
                                </button>
                            </div>
                        </div>

                        <div class="border-t border-gray-200 pt-4 mb-6">
                            <div class="flex justify-between items-center">
                                <span class="font-medium">Total</span>
                                <span class="text-xl font-medium cart-total">$0.00</span>
                            </div>
                        </div>

                        <a href="/checkout"
                            class="w-full bg-black text-white py-3 rounded-lg flex items-center justify-center hover:bg-black/80 transition-colors font-medium mb-3">
                            Proceed to Checkout
                        </a>

                        <a href="/shop"
                            class="w-full border border-black text-black py-2.5 rounded-lg flex items-center justify-center hover:bg-black hover:text-white transition-colors">
                            Continue Shopping
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </template>

    <!-- Cart Item Template -->
    <template id="cartItemTemplate">
        <div class="cart-item bg-gray-50 hover:bg-gray-100 rounded-lg p-4 flex gap-4 transition-all duration-200"
            data-id="">
            <div class="w-24 h-24 bg-white rounded-md overflow-hidden flex-shrink-0">
                <img src="" alt="" class="w-full h-full object-cover item-image">
            </div>

            <div class="flex-grow">
                <div class="flex justify-between">
                    <div>
                        <h3 class="font-medium text-sm item-name"></h3>
                        <p class="text-xs text-gray-500 item-category"></p>
                        <p class="text-xs text-gray-500 mt-1 item-variation hidden"></p>
                    </div>
                    <button class="cart-item-remove text-gray-400 hover:text-red-500 transition-colors p-1">
                        <i class="ri-close-line"></i>
                    </button>
                </div>

                <div class="flex justify-between items-end mt-auto">
                    <div class="flex items-center mt-3">
                        <div class="quantity-controls flex border border-gray-300 rounded-md overflow-hidden">
                            <button
                                class="cart-qty-decrease w-8 h-8 flex items-center justify-center bg-white hover:bg-gray-100 transition-colors">
                                <i class="ri-subtract-line"></i>
                            </button>
                            <input type="number" min="1" value="1"
                                class="cart-qty-input w-12 h-8 text-center border-x border-gray-300 focus:outline-none">
                            <button
                                class="cart-qty-increase w-8 h-8 flex items-center justify-center bg-white hover:bg-gray-100 transition-colors">
                                <i class="ri-add-line"></i>
                            </button>
                        </div>
                    </div>

                    <div class="text-right">
                        <div class="item-price font-medium"></div>
                        <div class="item-original-price text-xs text-gray-400 line-through hidden"></div>
                    </div>
                </div>
            </div>
        </div>
    </template>
@endsection

<style>
    @keyframes spin {
        from {
            transform: rotate(0deg);
        }

        to {
            transform: rotate(360deg);
        }
    }

    .animate-spin {
        animation: spin 1s linear infinite;
    }

    .cart-item {
        transition: transform 0.3s ease, opacity 0.3s ease, background-color 0.2s ease;
    }

    .cart-item.removing {
        transform: translateX(20px);
        opacity: 0;
    }

    .cart-item-remove i {
        transition: transform 0.2s ease;
    }

    .cart-item-remove:hover i {
        transform: rotate(90deg);
    }

    .cart-qty-input::-webkit-inner-spin-button,
    .cart-qty-input::-webkit-outer-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }

    .cart-qty-input {
        -moz-appearance: textfield;
    }

    .cart-qty-decrease,
    .cart-qty-increase {
        transition: all 0.2s;
    }

    .cart-qty-decrease:active,
    .cart-qty-increase:active {
        transform: scale(0.9);
    }

    #apply-coupon {
        transition: all 0.2s;
    }

    #apply-coupon:active {
        transform: scale(0.97);
    }

    /* Toast animation */
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(10px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes fadeOutDown {
        from {
            opacity: 1;
            transform: translateY(0);
        }

        to {
            opacity: 0;
            transform: translateY(10px);
        }
    }

    .fade-in-up {
        animation: fadeInUp 0.3s ease forwards;
    }

    .fade-out-down {
        animation: fadeOutDown 0.3s ease forwards;
    }

    .shadow-soft {
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Get CSRF token
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        const headers = {
            'X-CSRF-TOKEN': csrfToken,
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        };

        // Initialize cart
        loadCart();

        // Event delegation for cart actions
        document.addEventListener('click', function(e) {
            // Clear cart
            if (e.target.closest('#clear-cart')) {
                if (confirm('Are you sure you want to clear your cart?')) {
                    clearCart();
                }
            }

            // Remove cart item
            if (e.target.closest('.cart-item-remove')) {
                const cartItem = e.target.closest('.cart-item');
                const itemId = cartItem.dataset.id;

                cartItem.classList.add('removing');
                setTimeout(() => {
                    removeCartItem(itemId);
                }, 300);
            }

            // Decrease quantity
            if (e.target.closest('.cart-qty-decrease')) {
                const input = e.target.closest('.quantity-controls').querySelector('.cart-qty-input');
                const cartItem = e.target.closest('.cart-item');
                let quantity = parseInt(input.value);

                if (quantity > 1) {
                    quantity--;
                    input.value = quantity;
                    updateCartItem(cartItem.dataset.id, quantity);
                }
            }

            // Increase quantity
            if (e.target.closest('.cart-qty-increase')) {
                const input = e.target.closest('.quantity-controls').querySelector('.cart-qty-input');
                const cartItem = e.target.closest('.cart-item');
                let quantity = parseInt(input.value);

                quantity++;
                input.value = quantity;
                updateCartItem(cartItem.dataset.id, quantity);
            }

            // Apply coupon
            if (e.target.id === 'apply-coupon') {
                if (e.target.classList.contains('remove-coupon')) {
                    removeCoupon();
                } else {
                    const couponCode = document.getElementById('coupon-code').value.trim();
                    if (couponCode) {
                        applyCoupon(couponCode);
                    } else {
                        showToast('Please enter a coupon code', 'error');
                    }
                }
            }
        });

        // Handle quantity input changes
        document.addEventListener('change', function(e) {
            if (e.target.classList.contains('cart-qty-input')) {
                const cartItem = e.target.closest('.cart-item');
                let quantity = parseInt(e.target.value);

                if (isNaN(quantity) || quantity < 1) {
                    quantity = 1;
                    e.target.value = quantity;
                }

                updateCartItem(cartItem.dataset.id, quantity);
            }
        });

        /**
         * Load cart data
         */
        function loadCart() {
            fetch('/cart/ajax/get', {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        renderCart(data.cart);
                    }
                })
                .catch(error => {
                    console.error('Error loading cart:', error);
                    showToast('Error loading cart data', 'error');
                    renderEmptyCart();
                });
        }

        /**
         * Render cart based on data
         */
        function renderCart(cart) {
            const cartContainer = document.getElementById('cartContainer');

            if (!cart.items || cart.items.length === 0) {
                renderEmptyCart();
                return;
            }

            // Clone the cart items template
            const template = document.getElementById('cartItemsTemplate');
            const cartContent = template.content.cloneNode(true);

            // Update item count
            cartContent.querySelector('.cart-item-count').textContent = cart.items.length;
            cartContent.querySelector('.cart-item-text').textContent = cart.items.length === 1 ? 'item' :
                'items';

            // Update cart totals
            cartContent.querySelector('.cart-subtotal').textContent =
                `$${parseFloat(cart.subtotal).toFixed(2)}`;
            
            // Handle discount display
            const discountRow = cartContent.querySelector('#discount-row');
            if (cart.discount && cart.discount > 0) {
                discountRow.classList.remove('hidden');
                cartContent.querySelector('.cart-discount').textContent = `-$${parseFloat(cart.discount).toFixed(2)}`;
            } else {
                discountRow.classList.add('hidden');
            }
            
            // Update total (should be subtotal - discount)
            cartContent.querySelector('.cart-total').textContent = `$${parseFloat(cart.total).toFixed(2)}`;

            // Display coupon code if applied
            if (cart.coupon_code) {
                const couponInput = cartContent.querySelector('#coupon-code');
                if (couponInput) {
                    couponInput.value = cart.coupon_code;
                    couponInput.setAttribute('disabled', 'disabled');
                    
                    // Change apply button to "Remove" button
                    const applyButton = cartContent.querySelector('#apply-coupon');
                    if (applyButton) {
                        applyButton.textContent = 'Remove';
                        applyButton.classList.add('remove-coupon');
                    }
                }
            }

            // Add cart items
            const cartItemsList = cartContent.querySelector('.cart-items-list');
            const cartItemTemplate = document.getElementById('cartItemTemplate');

            cart.items.forEach(item => {
                const cartItemContent = cartItemTemplate.content.cloneNode(true);
                const cartItem = cartItemContent.querySelector('.cart-item');

                // Set item ID
                cartItem.dataset.id = item.id;

                // Set image
                const image = cartItemContent.querySelector('.item-image');
                image.src = item.image_path || '/images/placeholder.jpg';
                image.alt = item.name;
                image.onerror = function() {
                    this.onerror = null;
                    this.src = '/images/placeholder.jpg';
                };

                // Set name and category
                cartItemContent.querySelector('.item-name').textContent = item.name;

                if (item.category_name) {
                    cartItemContent.querySelector('.item-category').textContent = item.category_name;
                } else {
                    cartItemContent.querySelector('.item-category').classList.add('hidden');
                }

                // Set variation if exists
                if (item.variation_name) {
                    const variationEl = cartItemContent.querySelector('.item-variation');
                    variationEl.textContent = `Variation: ${item.variation_name}`;
                    variationEl.classList.remove('hidden');
                }

                // Set price
                const priceEl = cartItemContent.querySelector('.item-price');
                const originalPriceEl = cartItemContent.querySelector('.item-original-price');

                const price = item.sale_price || item.price;
                const total = price * item.quantity;
                priceEl.textContent = `$${parseFloat(total).toFixed(2)}`;

                if (item.sale_price && item.original_price && item.sale_price < item.original_price) {
                    const originalTotal = item.original_price * item.quantity;
                    originalPriceEl.textContent = `$${parseFloat(originalTotal).toFixed(2)}`;
                    originalPriceEl.classList.remove('hidden');
                }

                // Set quantity
                cartItemContent.querySelector('.cart-qty-input').value = item.quantity;

                // Add to list
                cartItemsList.appendChild(cartItemContent);
            });

            // Replace container content
            cartContainer.innerHTML = '';
            cartContainer.appendChild(cartContent);

            // Apply animations
            const cartItems = document.querySelectorAll('.cart-item');
            cartItems.forEach((item, index) => {
                item.style.opacity = '0';
                item.style.transform = 'translateY(10px)';

                setTimeout(() => {
                    item.style.transition = 'all 0.3s ease';
                    item.style.opacity = '1';
                    item.style.transform = 'translateY(0)';
                }, 50 + (index * 50));
            });

            updateHeaderCartTotal(cart.total); // Use total instead of subtotal
        }

        /**
         * Render empty cart
         */
        function renderEmptyCart() {
            const cartContainer = document.getElementById('cartContainer');
            const template = document.getElementById('cartEmptyTemplate');

            cartContainer.innerHTML = '';
            cartContainer.appendChild(template.content.cloneNode(true));
        }

        /**
         * Update cart item quantity
         */
        function updateCartItem(itemId, quantity) {
            const formData = new FormData();
            formData.append('quantity', quantity);

            fetch(`/cart/ajax/update/${itemId}`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        loadCart();
                        showToast('Cart updated', 'success');

                        if (data.cart && data.cart.total !== undefined) {
                            updateHeaderCartTotal(data.cart.total);
                        }
                    } else {
                        showToast(data.message || 'Error updating cart', 'error');
                        loadCart();
                    }
                })
                .catch(error => {
                    console.error('Error updating cart:', error);
                    showToast('Error updating cart', 'error');
                    loadCart(); 
                });
        }

        /**
         * Remove item from cart
         */
        function removeCartItem(itemId) {
            fetch(`/cart/ajax/remove/${itemId}`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        loadCart();
                        showToast('Item removed from cart', 'success');
                    } else {
                        showToast(data.message || 'Error removing item', 'error');
                        loadCart();
                    }
                })
                .catch(error => {
                    console.error('Error removing item:', error);
                    showToast('Error removing item', 'error');
                    loadCart();
                });
        }

        /**
         * Clear cart
         */
        function clearCart() {
            fetch('/cart/ajax/clear', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        renderEmptyCart();
                        showToast('Cart cleared', 'success');

                        updateHeaderCartTotal(0);
                    } else {
                        showToast(data.message || 'Error clearing cart', 'error');
                    }
                })
                .catch(error => {
                    console.error('Error clearing cart:', error);
                    showToast('Error clearing cart', 'error');
                });
        }

        function updateHeaderCartTotal(total) {
            const headerCartTotal = document.querySelector('header .cart-btn span');

            if (headerCartTotal) {
                const currencySymbol = headerCartTotal.textContent.trim().charAt(0);

                headerCartTotal.textContent = `${currencySymbol}${parseFloat(total).toFixed(2)}`;
            }
        }

        /**
         * Apply coupon
         */
        function applyCoupon(couponCode) {
            const formData = new FormData();
            formData.append('coupon_code', couponCode);

            fetch('/cart/ajax/apply-coupon', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        loadCart();
                        showToast('Coupon applied successfully', 'success');
                    } else {
                        showToast(data.message || 'Invalid coupon code', 'error');
                    }
                })
                .catch(error => {
                    console.error('Error applying coupon:', error);
                    showToast('Error applying coupon', 'error');
                });
        }

        /**
         * Remove coupon
         */
        function removeCoupon() {
            fetch('/cart/ajax/remove-coupon', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        loadCart();
                        showToast('Coupon removed successfully', 'success');
                    } else {
                        showToast(data.message || 'Error removing coupon', 'error');
                    }
                })
                .catch(error => {
                    console.error('Error removing coupon:', error);
                    showToast('Error removing coupon', 'error');
                    loadCart();
                });
        }

        /**
         * Show toast notification
         */
        function showToast(message, type = 'info') {
            let container = document.getElementById('toast-container');

            if (!container) {
                container = document.createElement('div');
                container.id = 'toast-container';
                container.className = 'fixed bottom-6 right-6 z-50';
                document.body.appendChild(container);
            }

            const toast = document.createElement('div');
            toast.className =
                'px-4 py-3 rounded-lg shadow-lg mb-3 flex items-center justify-between opacity-0 transform translate-y-2 fade-in-up';

            switch (type) {
                case 'success':
                    toast.classList.add('bg-green-500', 'text-white');
                    break;
                case 'error':
                    toast.classList.add('bg-red-500', 'text-white');
                    break;
                default:
                    toast.classList.add('bg-black', 'text-white');
            }

            toast.innerHTML = `
            <span>${message}</span>
            <button class="ml-4" onclick="this.parentElement.classList.add('fade-out-down'); setTimeout(() => this.parentElement.remove(), 300);">
                <i class="ri-close-line"></i>
            </button>
        `;

            container.appendChild(toast);

            setTimeout(() => {
                if (toast.parentNode) {
                    toast.classList.add('fade-out-down');
                    setTimeout(() => {
                        if (toast.parentNode) toast.remove();
                    }, 300);
                }
            }, 4000);
        }
    });
</script>

<script>
    
    window.renderCart = renderCart;
    
    document.addEventListener('headerCartUpdated', function(event) {
        console.log('Cart update detected from header sidebar');
        if (event.detail) {
            console.log('Rendering cart with data from event');
            renderCart(event.detail);
        } else {
            console.log('No cart data in event, loading from server');
            loadCart();
        }
    });
    
    console.log('Cart page update listener initialized');
</script>