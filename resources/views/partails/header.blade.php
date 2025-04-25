<!-- Navbar Top -->
<div class="navbar-top py-2 hidden lg:block bg-black text-white">
    <div class="container mx-auto px-4">
        <div class="flex justify-between items-center">
            <div class="flex items-center ">
                <a href="#" class="text-sm font-jost hover-underline mr-4">Gift Cards</a>
                <a href="#" class="text-sm font-jost hover-underline mx-4">Track Order</a>
                <a href="#" class="text-sm font-jost hover-underline mx-4">About Us</a>
            </div>

            <div class="flex items-center space-x-6">
                <div class="flex items-center">
                    <i class="ri-phone-line text-sm mr-2"></i>
                    <span class="text-sm font-jost">(+212) 492-1044</span>
                </div>
                <div class="flex items-center group cursor-pointer">
                    <span class="text-sm font-jost hover-underline">Customer Support</span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Main Header -->
<header class="bg-white py-4 sticky main-header px-4 top-0 z-30 transition-all duration-300 ">
    <div class="container mx-auto lg:px-4 sm:px-2">
        <div class="flex items-center justify-between">
            <button id="menuToggle"
                class="lg:hidden text-gray-900 focus:outline-none transition hover:opacity-70 rounded-md">
                <i class="ri-menu-line text-2xl"></i>
            </button>

            <a href="/" class="flex items-center">
                <span class="font-jakarta text-2xl tracking-wider font-semibold">SAJI HOME</span>
            </a>

            <div class="hidden lg:block relative flex-grow max-w-lg mx-8 group search-container">
                <input type="text" placeholder="Search products"
                    class="w-full pl-10 pr-4 py-3 h-10 border border-gray-200 rounded-md focus:outline-none focus:border-gray-900 bg-transparent transition-all text-sm font-jost search-input">
                <i class="ri-search-line search-icon text-gray-400 group-hover:text-gray-600 transition-all"></i>
            </div>

            <div class="flex items-center">
                <a href="{{ route('wishlist.index') }}"
                    class="mx-4 wishlist-nav-link hidden md:flex items-center justify-center w-10 h-10 border border-black transition rounded-md hover:bg-black hover:text-white relative">
                    <i class="ri-heart-line text-xl transition-transform"></i>
                    @if (Auth::check() && Auth::user()->wishlistItems->count() > 0)
                        <span
                            class="wishlist-count absolute -top-2 -right-2 bg-black text-white text-xs w-5 h-5 flex items-center justify-center rounded-full">
                            {{ Auth::user()->wishlistItems->count() }}
                        </span>
                    @endif
                </a>

                <a href="{{ Auth::check() ? (Auth::user()->isAdmin() || Auth::user()->isManager() || Auth::user()->isSupportAgent() ? route('admin.dashboard') : '/account') : '/login' }}"
                    class="mr-4 account-btn hidden md:flex items-center justify-center w-10 h-10 border border-black transition rounded-md hover:bg-black hover:text-white ">
                    <i class="ri-user-line text-xl transition-transform"></i>
                </a>

                <button id="cartToggle"
                    class="cart-btn flex items-center space-x-1 bg-black text-white px-4 py-3 h-10 transition rounded-md hover:opacity-90">
                    <i class="ri-shopping-bag-line text-xl text-white"></i>
                    <span class="font-jost font-medium hidden md:block">0.00 $</span>
                </button>
            </div>
        </div>
    </div>
</header>

<!-- Mobile Search -->
<div class="lg:hidden px-4 py-3 bg-white border-t border-gray-100 rounded-md">
    <div class="relative group search-container">
        <input type="text" placeholder="Search products"
            class="w-full pl-10 pr-4 py-3 h-12 border border-gray-200 rounded-md focus:outline-none focus:border-gray-900 bg-transparent transition-all text-sm font-jost search-input">
        <i class="ri-search-line search-icon text-gray-400 group-hover:text-gray-600 transition-all"></i>
    </div>
</div>

<!-- Category Navigation -->
<nav class="category-nav py-4 border-b border-gray-100 bg-white rounded-md hidden md:block">
    <div class="container mx-auto px-4">
        <div class="scrollbar-hide overflow-x-auto">
            <div class="flex justify-center min-w-max">
                <a href="#" class="category-link flex gap-1 justify-center items-center text-black mx-4">
                    <img src="{{ asset('icons/Chair.svg') }}" alt="Chairs" class="w-5 h-5 mb-1 category-icon">
                    <span class="text-sm font-jost">Chairs</span>
                </a>
                <a href="#" class="category-link flex gap-1 justify-center items-center text-black mx-4">
                    <img src="{{ asset('icons/tables.svg') }}" alt="Tables" class="w-5 h-5 mb-1 category-icon">
                    <span class="text-sm font-jost">Tables</span>
                </a>
                <a href="#" class="category-link flex gap-1.5 justify-center items-center text-black mx-4">
                    <img src="{{ asset('icons/sofas.svg') }}" alt="Sofas" class="w-5 h-5 mb-1 category-icon">
                    <span class="text-sm font-jost">Sofas</span>
                </a>
                <a href="#" class="category-link flex gap-1 justify-center items-center text-black mx-4">
                    <img src="{{ asset('icons/Armchairs.svg') }}" alt="Armchairs" class="w-5 h-5 mb-1 category-icon">
                    <span class="text-sm font-jost">Armchairs</span>
                </a>
                <a href="#" class="category-link flex gap-1.5 justify-center items-center text-black mx-4">
                    <img src="{{ asset('icons/Beds.svg') }}" alt="Beds" class="w-5 h-5 mb-1 category-icon">
                    <span class="text-sm font-jost">Beds</span>
                </a>
                <a href="#" class="category-link flex gap-1 justify-center items-center text-black mx-4">
                    <img src="{{ asset('icons/storage.svg') }}" alt="Storage" class="w-5 h-5 mb-1 category-icon">
                    <span class="text-sm font-jost">Storage</span>
                </a>
                <a href="#" class="category-link flex gap-1 justify-center items-center text-black mx-4">
                    <img src="{{ asset('icons/textiles.svg') }}" alt="Textiles" class="w-5 h-5 mb-1 category-icon">
                    <span class="text-sm font-jost">Textiles</span>
                </a>
                <a href="#" class="category-link flex gap-1 justify-center items-center text-black mx-4">
                    <img src="{{ asset('icons/lighting.svg') }}" alt="Lighting" class="w-5 h-5 mb-1 category-icon">
                    <span class="text-sm font-jost">Lighting</span>
                </a>
                <a href="#" class="category-link flex gap-1 justify-center items-center text-black mx-4">
                    <img src="{{ asset('icons/toys.svg') }}" alt="Toys" class="w-5 h-5 mb-1 category-icon">
                    <span class="text-sm font-jost">Toys</span>
                </a>
                <a href="#" class="category-link flex gap-1 justify-center items-center text-black mx-4">
                    <img src="{{ asset('icons/Decor.svg') }}" alt="Decor" class="w-5 h-5 mb-1 category-icon">
                    <span class="text-sm font-jost">Decor</span>
                </a>
            </div>
        </div>
    </div>
</nav>

<!-- Mobile Sidebar  -->
<div id="mobileSidebar"
    class="sidebar overflow-y-scroll scrollbar-hide fixed top-0 left-0 h-full w-80 bg-white z-40 shadow-xl overflow-y-auto hidden">
    <div class="p-4 border-b border-gray-100 flex justify-between items-center">
        <span class="font-jakarta text-2xl tracking-wider font-semibold">SAJI</span>
        <button id="closeSidebar" class="text-gray-500 hover:text-gray-900 transition rounded-md">
            <i class="ri-close-line text-2xl"></i>
        </button>
    </div>

    <!-- Sidebar content -->
    <div class="p-4 border-b border-gray-100">
        <h3 class="text-xs uppercase text-gray-500 font-medium mb-3 font-jost">Select Currency</h3>
        <div class="grid grid-cols-2 gap-2">
            <div
                class="p-2 border border-gray-200 flex items-center hover:border-gray-900 cursor-pointer transition-all rounded-md">
                <img src="{{ asset('icons/flag-eu.svg') }}" alt="EU Flag" class="w-5 h-3 mr-2 category-icon">
                <span class="text-sm font-jost">EUR (€)</span>
            </div>
            <div
                class="p-2 border border-gray-200 flex items-center hover:border-gray-900 cursor-pointer transition-all rounded-md">
                <img src="{{ asset('icons/flag-usa.svg') }}" alt="US Flag" class="w-5 h-3 mr-2 category-icon">
                <span class="text-sm font-jost">USD ($)</span>
            </div>
        </div>
    </div>

    <div class="p-4">
        <div class="space-y-4">
            <div class="border-b border-gray-100 pb-4">
                <h3 class="text-xs uppercase text-gray-500 font-medium mb-3 font-jost">Account</h3>
                @if (Auth::check())
                    <a href="/profile" class="flex items-center py-2 text-gray-600 hover:text-gray-900 transition">
                        <i class="ri-user-line text-lg mr-3"></i>
                        <span class="font-jost">My Account</span>
                    </a>
                    <a href="{{ route('wishlist.index') }}"
                        class="flex items-center py-2 text-gray-600 hover:text-gray-900 transition">
                        <i class="ri-heart-line text-lg mr-3"></i>
                        <span class="font-jost">Wishlist</span>
                        @if (Auth::user()->wishlistItems->count() > 0)
                            <span
                                class="ml-2 bg-black text-white text-xs w-5 h-5 flex items-center justify-center rounded-full">
                                {{ Auth::user()->wishlistItems->count() }}
                            </span>
                        @endif
                    </a>
                @else
                    <a href="{{ route('login') }}"
                        class="flex items-center py-2 text-gray-600 hover:text-gray-900 transition">
                        <i class="ri-user-line text-lg mr-3"></i>
                        <span class="font-jost">Login / Register</span>
                    </a>
                    <a href="{{ route('login') }}"
                        class="flex items-center py-2 text-gray-600 hover:text-gray-900 transition">
                        <i class="ri-heart-line text-lg mr-3"></i>
                        <span class="font-jost">Wishlist</span>
                    </a>
                @endif
            </div>

            <div class="border-b border-gray-100 pb-4">
                <h3 class="text-xs uppercase text-gray-500 font-medium mb-3 font-jost">Categories</h3>
                <div class="grid grid-cols-1 gap-2">
                    <a href="#" class="flex items-center py-2 text-gray-600 hover:text-gray-900 transition">
                        <img src="{{ asset('icons/Chair.svg') }}" alt="Chair" class="w-5 h-5 mr-3 category-icon">
                        <span class="font-jost">Chairs</span>
                    </a>
                    <a href="#" class="flex items-center py-2 text-gray-600 hover:text-gray-900 transition">
                        <img src="{{ asset('icons/tables.svg') }}" alt="Table"
                            class="w-5 h-5 mr-3 category-icon">
                        <span class="font-jost">Tables</span>
                    </a>
                    <a href="#" class="flex items-center py-2 text-gray-600 hover:text-gray-900 transition">
                        <img src="{{ asset('icons/sofas.svg') }}" alt="Sofa" class="w-5 h-5 mr-3 category-icon">
                        <span class="font-jost">Sofas</span>
                    </a>
                    <a href="#" class="flex items-center py-2 text-gray-600 hover:text-gray-900 transition">
                        <img src="{{ asset('icons/Armchairs.svg') }}" alt="Armchair"
                            class="w-5 h-5 mr-3 category-icon">
                        <span class="font-jost">Armchairs</span>
                    </a>
                    <a href="#" class="flex items-center py-2 text-gray-600 hover:text-gray-900 transition">
                        <img src="{{ asset('icons/Beds.svg') }}" alt="Bed" class="w-5 h-5 mr-3 category-icon">
                        <span class="font-jost">Beds</span>
                    </a>
                    <a href="#" class="flex items-center py-2 text-gray-600 hover:text-gray-900 transition">
                        <img src="{{ asset('icons/storage.svg') }}" alt="Storage"
                            class="w-5 h-5 mr-3 category-icon">
                        <span class="font-jost">Storage</span>
                    </a>
                    <a href="#" class="flex items-center py-2 text-gray-600 hover:text-gray-900 transition">
                        <img src="{{ asset('icons/textiles.svg') }}" alt="Textiles"
                            class="w-5 h-5 mr-3 category-icon">
                        <span class="font-jost">Textiles</span>
                    </a>
                    <a href="#" class="flex items-center py-2 text-gray-600 hover:text-gray-900 transition">
                        <img src="{{ asset('icons/lighting.svg') }}" alt="Lighting"
                            class="w-5 h-5 mr-3 category-icon">
                        <span class="font-jost">Lighting</span>
                    </a>
                    <a href="#" class="flex items-center py-2 text-gray-600 hover:text-gray-900 transition">
                        <img src="{{ asset('icons/toys.svg') }}" alt="Toys" class="w-5 h-5 mr-3 category-icon">
                        <span class="font-jost">Toys</span>
                    </a>
                    <a href="#" class="flex items-center py-2 text-gray-600 hover:text-gray-900 transition">
                        <img src="{{ asset('icons/Decor.svg') }}" alt="Decor" class="w-5 h-5 mr-3 category-icon">
                        <span class="font-jost">Decoration</span>
                    </a>
                </div>
            </div>
            <div
                class="bg-black text-white p-3 text-center text-sm rounded-md border border-black hover:bg-white hover:text-black transition-all duration-300">
                <a href="" class="block font-jost">Free shipping for all orders over €1,300</a>
            </div>

            <div class="border-b border-gray-100 pb-4">
                <h3 class="text-xs uppercase text-gray-500 font-medium mb-3 font-jost">Information</h3>
                <a href="#" class="block py-2 text-gray-600 hover:text-gray-900 transition font-jost">Gift
                    Cards</a>
                <a href="#"
                    class="block py-2 text-gray-600 hover:text-gray-900 transition font-jost">Showrooms</a>
                <a href="#" class="block py-2 text-gray-600 hover:text-gray-900 transition font-jost">About
                    Us</a>
            </div>
        </div>
    </div>
</div>

<!-- Cart Sidebar -->
<div id="cartSidebar"
    class="cart-sidebar fixed top-0 right-0 h-full w-80 md:w-96 bg-white z-40 shadow-xl overflow-hidden flex flex-col hidden">
    <div class="p-4 border-b border-gray-100 flex justify-between items-center">
        <h2 class="font-jakarta text-xl tracking-wide font-semibold">Shopping Cart</h2>
        <button id="closeCart" class="text-gray-500 hover:text-gray-900 transition rounded-md">
            <i class="ri-close-line text-2xl"></i>
        </button>
    </div>

    <div class="flex-1 overflow-y-auto cart-items-container">
        <div class="flex flex-col items-center justify-center p-4 h-full">
            <h3 class="font-jost font-medium mb-1 px-4">Your cart is empty</h3>
            <p class="text-sm text-gray-500 text-center mb-6 font-jost mx-14">It seems you haven't added any products
                to your cart yet.</p>
        </div>
    </div>

    <div class="sticky bottom-0 bg-white p-4 border-t border-gray-100 mt-auto">
        <div class="flex justify-between items-center mb-4 font-jost">
            <span class="font-medium">Subtotal</span>
            <span class="font-bold text-lg">0.00 $</span>
        </div>
        <div class="flex flex-col gap-2">
            <a href="/checkout"
                class="block w-full bg-black text-white py-3 rounded-md hover:opacity-80 transition font-jost text-center">
                Checkout
            </a>
            <a href="/cart"
                class="block w-full border border-black py-2 rounded-md hover:bg-black hover:text-white transition-all duration-300 font-jost text-center">
                View Cart
            </a>
        </div>
    </div>
</div>

<div id="overlay" class="overlay fixed inset-0 bg-black opacity-50 z-30 hidden"></div>

<style>
    .cart-sidebar {
        transform: translateX(100%);
        transition: transform 0.3s ease-in-out;
        display: flex;
        flex-direction: column;
    }

    .cart-sidebar:not(.hidden) {
        transform: translateX(0);
    }

    .cart-items-container {
        scrollbar-width: thin;
        scrollbar-color: #c1c1c1 #f1f1f1;
        flex: 1 1 auto;
        overflow-y: auto;
    }

    .cart-items-container::-webkit-scrollbar {
        width: 6px;
    }

    .cart-items-container::-webkit-scrollbar-track {
        background: #f1f1f1;
    }

    .cart-items-container::-webkit-scrollbar-thumb {
        background: #c1c1c1;
        border-radius: 3px;
    }

    .cart-items-container::-webkit-scrollbar-thumb:hover {
        background: #a8a8a8;
    }

    .cart-item {
        transition: background-color 0.2s;
    }

    .cart-item:hover {
        background-color: rgba(0, 0, 0, 0.02);
    }

    .cart-qty-btn {
        cursor: pointer;
        transition: background-color 0.2s;
    }

    .cart-qty-btn:hover {
        background-color: rgba(0, 0, 0, 0.05);
    }

    .remove-cart-item {
        transition: color 0.2s;
    }

    .remove-cart-item:hover {
        color: #f44336;
    }

    @keyframes cartItemAdded {
        0% {
            background-color: rgba(0, 0, 0, 0.05);
        }

        50% {
            background-color: rgba(0, 0, 0, 0.1);
        }

        100% {
            background-color: transparent;
        }
    }

    .cart-item-added {
        animation: cartItemAdded 1s ease-out;
    }

    .toast-container {
        z-index: 9999;
    }

    .toast {
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        transition: all 0.3s;
    }

    .add-to-cart-btn.loading {
        opacity: 0.7;
        cursor: wait;
    }
</style>


{{-- <script>
    document.addEventListener('DOMContentLoaded', function() {
        initCartFunctionality();


        function initCartFunctionality() {
            const addToCartButtons = document.querySelectorAll('.add-to-cart-btn');
            addToCartButtons.forEach(btn => {
                btn.addEventListener('click', handleAddToCart);
            });

            const cartToggleBtn = document.getElementById('cartToggle');
            const closeCartBtn = document.getElementById('closeCart');
            const overlay = document.getElementById('overlay');

            if (cartToggleBtn) {
                cartToggleBtn.addEventListener('click', toggleCartSidebar);
            }

            if (closeCartBtn) {
                closeCartBtn.addEventListener('click', closeCartSidebar);
            }

            if (overlay) {
                overlay.addEventListener('click', closeCartSidebar);
            }

            loadCartData();
        }


        function handleAddToCart(e) {
            e.preventDefault();

            const btn = e.currentTarget;
            const productCard = btn.closest('.product-card');

            if (!productCard) {
                console.error('Product card not found');
                showToast('Error adding product to cart', 'error');
                return;
            }

            const productId = productCard.getAttribute('data-product-id');
            if (!productId) {
                console.error('Product ID not found');
                showToast('Error adding product to cart', 'error');
                return;
            }

            btn.innerHTML =
                '<span class="flex justify-center items-center"><i class="ri-loader-4-line animate-spin mr-2"></i>Adding...</span>';
            btn.disabled = true;

            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            const formData = new FormData();
            formData.append('product_id', productId);
            formData.append('quantity', 1);

            fetch('/cart/ajax/add', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    btn.innerHTML = 'Add to Cart';
                    btn.disabled = false;

                    if (data.success) {
                        showToast('Product added to cart', 'success');
                        updateCartDisplay(data.cart);

                        toggleCartSidebar();
                    } else {
                        showToast(data.message || 'Error adding product to cart', 'error');
                    }
                })
                .catch(error => {
                    console.error('Error adding to cart:', error);
                    btn.innerHTML = 'Add to Cart';
                    btn.disabled = false;
                    showToast('Error adding product to cart', 'error');
                });
        }

        function loadCartData() {
            fetch('/cart/ajax/get', {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        updateCartDisplay(data.cart);
                    }
                })
                .catch(error => {
                    console.error('Error loading cart data:', error);
                });
        }

        /**
         * Update cart quantity
         */
        function updateCartQuantity(itemId, newQuantity) {
            if (newQuantity < 1) return;

            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            const formData = new FormData();
            formData.append('quantity', newQuantity);

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
                        updateCartDisplay(data.cart);
                        showToast('Cart updated', 'success');
                        // Notify cart page about the update
                        notifyCartPageUpdate(data.cart);
                    } else {
                        showToast(data.message || 'Error updating cart', 'error');
                        loadCartData();
                    }
                })
                .catch(error => {
                    console.error('Error updating cart:', error);
                    showToast('Error updating cart', 'error');
                    loadCartData();
                });
        }


        /**
         * Remove item from cart
         */
        function removeCartItem(itemId) {
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

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
                        updateCartDisplay(data.cart);
                        showToast('Item removed from cart', 'success');
                        // Notify cart page about the update
                        notifyCartPageUpdate(data.cart);
                    } else {
                        showToast(data.message || 'Error removing item', 'error');
                    }
                })
                .catch(error => {
                    console.error('Error removing item:', error);
                    showToast('Error removing item', 'error');
                });
        }

        /**
         * Update the cart display
         */

        function updateCartDisplay(cart) {
            const cartBtnTotal = document.querySelector('.cart-btn span');
            if (cartBtnTotal) {
                const currencySymbol = cartBtnTotal.textContent.trim().charAt(cartBtnTotal.textContent.trim()
                    .length - 1);
                cartBtnTotal.textContent = `${cart.subtotal.toFixed(2)} ${currencySymbol}`;
            }

            const cartSidebar = document.getElementById('cartSidebar');
            if (!cartSidebar) return;

            const cartContent = cartSidebar.querySelector('.cart-items-container');
            if (!cartContent) return;

            const cartSubtotal = cartSidebar.querySelector(
                '.sticky.bottom-0 .flex.justify-between span:last-child');
            if (cartSubtotal) {
                cartSubtotal.textContent = `${cart.subtotal.toFixed(2)} $`;
            }

            if (cart.items.length === 0) {
                cartContent.innerHTML = `
            <div class="flex flex-col items-center justify-center p-4 h-full">
                <h3 class="font-jost font-medium mb-1 px-4">Your cart is empty</h3>
                <p class="text-sm text-gray-500 text-center mb-6 font-jost mx-14">It seems you haven't added any products to your cart yet.</p>
            </div>
        `;
                return;
            }

            let cartItemsHtml = `<div class="p-4">`;

            cart.items.forEach(item => {
                const price = item.sale_price ?? item.price;
                const totalPrice = price * item.quantity;

                const imagePath = item.image_path && item.image_path !== 'null' ?
                    item.image_path :
                    '/images/placeholder.jpg';

                const categoryName = item.category_name || '';

                cartItemsHtml += `
            <div class="cart-item flex border-b border-gray-100 pb-4 mb-4 rounded-lg p-2" data-item-id="${item.id}">
                <div class="w-20 h-20 bg-gray-50 rounded-md overflow-hidden flex-shrink-0">
                    <img src="${imagePath}" alt="${item.name}" class="w-full h-full object-cover" 
                        onerror="this.onerror=null; this.src='/images/placeholder.jpg';">
                </div>
                <div class="ml-4 flex-1">
                    <div class="flex justify-between">
                        <div>
                            <h4 class="font-medium text-sm">${item.name}</h4>
                            <p class="text-xs text-gray-500">${categoryName}</p>
                        </div>
                        <button class="remove-cart-item text-gray-400 hover:text-red-500 transition-colors" onclick="removeCartItem(${item.id})">
                            <i class="ri-close-line"></i>
                        </button>
                    </div>
                    ${item.variation_name ? `<p class="text-xs text-gray-500 mb-2">${item.variation_name}</p>` : ''}
                    <div class="flex justify-between items-center mt-2">
                        <div class="flex items-center border border-gray-200 rounded-md">
                            <button class="cart-qty-btn px-2 py-1 text-sm" onclick="updateCartQuantity(${item.id}, ${item.quantity - 1})">-</button>
                            <span class="px-2 py-1 text-sm">${item.quantity}</span>
                            <button class="cart-qty-btn px-2 py-1 text-sm" onclick="updateCartQuantity(${item.id}, ${item.quantity + 1})">+</button>
                        </div>
                        <span class="font-medium text-sm">${totalPrice.toFixed(2)} $</span>
                    </div>
                </div>
            </div>
        `;
            });

            cartItemsHtml += `</div>`;
            cartContent.innerHTML = cartItemsHtml;

            window.updateCartQuantity = updateCartQuantity;
            window.removeCartItem = removeCartItem;
        }


        function toggleCartSidebar() {
            const cartSidebar = document.getElementById('cartSidebar');
            const overlay = document.getElementById('overlay');

            if (cartSidebar && overlay) {
                cartSidebar.classList.toggle('hidden');
                overlay.classList.toggle('hidden');

                if (!cartSidebar.classList.contains('hidden')) {
                    document.body.style.overflow = 'hidden';
                } else {
                    document.body.style.overflow = '';
                }
            }
        }


        function closeCartSidebar() {
            const cartSidebar = document.getElementById('cartSidebar');
            const overlay = document.getElementById('overlay');

            if (cartSidebar && overlay) {
                cartSidebar.classList.add('hidden');
                overlay.classList.add('hidden');
                document.body.style.overflow = '';
            }
        }


        function showToast(message, type = 'info') {
            let toastContainer = document.querySelector('.toast-container');

            if (!toastContainer) {
                toastContainer = document.createElement('div');
                toastContainer.className = 'toast-container fixed bottom-4 right-4 z-50';
                document.body.appendChild(toastContainer);
            }

            const toast = document.createElement('div');
            toast.className =
                `toast p-4 mb-3 rounded-lg shadow-lg flex items-center justify-between transition-all transform translate-y-2 opacity-0`;

            switch (type) {
                case 'success':
                    toast.classList.add('bg-green-500', 'text-white');
                    break;
                case 'error':
                    toast.classList.add('bg-red-500', 'text-white');
                    break;
                case 'info':
                    toast.classList.add('bg-blue-500', 'text-white');
                    break;
                default:
                    toast.classList.add('bg-gray-800', 'text-white');
            }

            toast.innerHTML = `
            <span>${message}</span>
            <button class="ml-4 focus:outline-none" onclick="this.parentElement.remove()">
                <i class="ri-close-line"></i>
            </button>
        `;

            toastContainer.appendChild(toast);

            setTimeout(() => {
                toast.classList.remove('translate-y-2', 'opacity-0');
                toast.classList.add('translate-y-0', 'opacity-100');
            }, 10);

            setTimeout(() => {
                toast.classList.add('translate-y-2', 'opacity-0');
                setTimeout(() => {
                    toast.remove();
                }, 300);
            }, 4000);
        }

        function notifyCartPageUpdate(cartData) {
            // Create a custom event that will be caught by the cart page
            const event = new CustomEvent('headerCartUpdated', {
                detail: cartData,
                bubbles: true,
                cancelable: true
            });
            document.dispatchEvent(event);
        }

        window.showToast = showToast;
        window.toggleCartSidebar = toggleCartSidebar;
        window.closeCartSidebar = closeCartSidebar;
        window.updateCartQuantity = updateCartQuantity;
        window.removeCartItem = removeCartItem;
    });

    document.addEventListener('headerCartUpdated', function(event) {
    console.log('Cart update detected from header sidebar');
    if (event.detail) {
        // Use the cart data passed from the header
        renderCart(event.detail);
    } else {
        // Fallback - reload cart data
        loadCart();
    }
});

// Make your renderCart function available globally
window.renderCart = renderCart;

</script> --}}


<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Make functions globally available
        window.showToast = showToast;
        window.toggleCartSidebar = toggleCartSidebar;
        window.closeCartSidebar = closeCartSidebar;
        window.updateCartQuantity = updateCartQuantity;
        window.removeCartItem = removeCartItem;
        window.updateCartDisplay = updateCartDisplay;
        window.loadCartData = loadCartData;
        
        initCartFunctionality();
        
        function initCartFunctionality() {
            const addToCartButtons = document.querySelectorAll('.add-to-cart-btn');
            addToCartButtons.forEach(btn => {
                btn.addEventListener('click', handleAddToCart);
            });
            
            const cartToggleBtn = document.getElementById('cartToggle');
            const closeCartBtn = document.getElementById('closeCart');
            const overlay = document.getElementById('overlay');
            
            if (cartToggleBtn) {
                cartToggleBtn.addEventListener('click', toggleCartSidebar);
            }
            
            if (closeCartBtn) {
                closeCartBtn.addEventListener('click', closeCartSidebar);
            }
            
            if (overlay) {
                overlay.addEventListener('click', closeCartSidebar);
            }
            
            loadCartData();
        }
        
        function handleAddToCart(e) {
            e.preventDefault();
            
            const btn = e.currentTarget;
            const productCard = btn.closest('.product-card');
            
            if (!productCard) {
                console.error('Product card not found');
                showToast('Error adding product to cart', 'error');
                return;
            }
            
            const productId = productCard.getAttribute('data-product-id');
            if (!productId) {
                console.error('Product ID not found');
                showToast('Error adding product to cart', 'error');
                return;
            }
            
            btn.innerHTML = '<span class="flex justify-center items-center"><i class="ri-loader-4-line animate-spin mr-2"></i>Adding...</span>';
            btn.disabled = true;
            
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            
            const formData = new FormData();
            formData.append('product_id', productId);
            formData.append('quantity', 1); 
            
            fetch('/cart/ajax/add', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                btn.innerHTML = 'Add to Cart';
                btn.disabled = false;
                
                if (data.success) {
                    showToast('Product added to cart', 'success');
                    updateCartDisplay(data.cart);
                    
                    // Notify cart page about the update
                    notifyCartPageUpdate(data.cart);
                    
                    toggleCartSidebar();
                } else {
                    showToast(data.message || 'Error adding product to cart', 'error');
                }
            })
            .catch(error => {
                console.error('Error adding to cart:', error);
                btn.innerHTML = 'Add to Cart';
                btn.disabled = false;
                showToast('Error adding product to cart', 'error');
            });
        }
        
        function loadCartData() {
            fetch('/cart/ajax/get', {
                method: 'GET',
                headers: {
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    updateCartDisplay(data.cart);
                }
            })
            .catch(error => {
                console.error('Error loading cart data:', error);
            });
        }
        
        /**
         * Update cart quantity
         */
        function updateCartQuantity(itemId, newQuantity) {
            if (newQuantity < 1) return;
            
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            
            const formData = new FormData();
            formData.append('quantity', newQuantity);
            
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
                    updateCartDisplay(data.cart);
                    showToast('Cart updated', 'success');
                    
                    // Notify cart page about the update
                    notifyCartPageUpdate(data.cart);
                } else {
                    showToast(data.message || 'Error updating cart', 'error');
                    loadCartData();
                }
            })
            .catch(error => {
                console.error('Error updating cart:', error);
                showToast('Error updating cart', 'error');
                loadCartData();
            });
        }
        
        /**
         * Remove item from cart
         */
        function removeCartItem(itemId) {
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            
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
                    updateCartDisplay(data.cart);
                    showToast('Item removed from cart', 'success');
                    
                    // Notify cart page about the update
                    notifyCartPageUpdate(data.cart);
                } else {
                    showToast(data.message || 'Error removing item', 'error');
                }
            })
            .catch(error => {
                console.error('Error removing item:', error);
                showToast('Error removing item', 'error');
            });
        }
        
        /**
         * Update the cart display
         */
        function updateCartDisplay(cart) {
            const cartBtnTotal = document.querySelector('.cart-btn span');
            if (cartBtnTotal) {
                const currencySymbol = cartBtnTotal.textContent.trim().charAt(cartBtnTotal.textContent.trim().length - 1);
                // Use total instead of subtotal to account for discounts
                const displayValue = cart.total !== undefined ? cart.total : cart.subtotal;
                cartBtnTotal.textContent = `${parseFloat(displayValue).toFixed(2)} ${currencySymbol}`;
            }
            
            const cartSidebar = document.getElementById('cartSidebar');
            if (!cartSidebar) return;
            
            const cartContent = cartSidebar.querySelector('.cart-items-container');
            if (!cartContent) return;
            
            const cartSubtotal = cartSidebar.querySelector('.sticky.bottom-0 .flex.justify-between span:last-child');
            if (cartSubtotal) {
                // Use total instead of subtotal to account for discounts
                const displayValue = cart.total !== undefined ? cart.total : cart.subtotal;
                cartSubtotal.textContent = `${parseFloat(displayValue).toFixed(2)} $`;
            }
            
            if (!cart.items || cart.items.length === 0) {
                cartContent.innerHTML = `
                    <div class="flex flex-col items-center justify-center p-4 h-full">
                        <h3 class="font-jost font-medium mb-1 px-4">Your cart is empty</h3>
                        <p class="text-sm text-gray-500 text-center mb-6 font-jost mx-14">It seems you haven't added any products to your cart yet.</p>
                    </div>
                `;
                return;
            }
            
            let cartItemsHtml = `<div class="p-4">`;
            
            cart.items.forEach(item => {
                const price = item.sale_price ?? item.price;
                const totalPrice = price * item.quantity;
                
                const imagePath = item.image_path && item.image_path !== 'null' ? 
                    item.image_path : 
                    '/images/placeholder.jpg';
                    
                const categoryName = item.category_name || '';
                
                cartItemsHtml += `
                    <div class="cart-item flex border-b border-gray-100 pb-4 mb-4 rounded-lg p-2" data-item-id="${item.id}">
                        <div class="w-20 h-20 bg-gray-50 rounded-md overflow-hidden flex-shrink-0">
                            <img src="${imagePath}" alt="${item.name}" class="w-full h-full object-cover" 
                                onerror="this.onerror=null; this.src='/images/placeholder.jpg';">
                        </div>
                        <div class="ml-4 flex-1">
                            <div class="flex justify-between">
                                <div>
                                    <h4 class="font-medium text-sm">${item.name}</h4>
                                    <p class="text-xs text-gray-500">${categoryName}</p>
                                </div>
                                <button class="remove-cart-item text-gray-400 hover:text-red-500 transition-colors" onclick="removeCartItem(${item.id})">
                                    <i class="ri-close-line"></i>
                                </button>
                            </div>
                            ${item.variation_name ? `<p class="text-xs text-gray-500 mb-2">${item.variation_name}</p>` : ''}
                            <div class="flex justify-between items-center mt-2">
                                <div class="flex items-center border border-gray-200 rounded-md">
                                    <button class="cart-qty-btn px-2 py-1 text-sm" onclick="updateCartQuantity(${item.id}, ${item.quantity - 1})">-</button>
                                    <span class="px-2 py-1 text-sm">${item.quantity}</span>
                                    <button class="cart-qty-btn px-2 py-1 text-sm" onclick="updateCartQuantity(${item.id}, ${item.quantity + 1})">+</button>
                                </div>
                                <span class="font-medium text-sm">${totalPrice.toFixed(2)} $</span>
                            </div>
                        </div>
                    </div>
                `;
            });
            
            cartItemsHtml += `</div>`;
            cartContent.innerHTML = cartItemsHtml;
        }
        
        function toggleCartSidebar() {
            const cartSidebar = document.getElementById('cartSidebar');
            const overlay = document.getElementById('overlay');
            
            if (cartSidebar && overlay) {
                cartSidebar.classList.toggle('hidden');
                overlay.classList.toggle('hidden');
                
                if (!cartSidebar.classList.contains('hidden')) {
                    document.body.style.overflow = 'hidden';
                    loadCartData(); // Refresh cart data when opening sidebar
                } else {
                    document.body.style.overflow = '';
                }
            }
        }
        
        function closeCartSidebar() {
            const cartSidebar = document.getElementById('cartSidebar');
            const overlay = document.getElementById('overlay');
            
            if (cartSidebar && overlay) {
                cartSidebar.classList.add('hidden');
                overlay.classList.add('hidden');
                document.body.style.overflow = '';
            }
        }
        
        function showToast(message, type = 'info') {
            let toastContainer = document.querySelector('.toast-container');
    
            if (!toastContainer) {
                toastContainer = document.createElement('div');
                toastContainer.className = 'toast-container fixed bottom-4 right-4 z-50';
                document.body.appendChild(toastContainer);
            }
    
            const toast = document.createElement('div');
            toast.className = `toast p-4 mb-3 rounded-lg shadow-lg flex items-center justify-between transition-all transform translate-y-2 opacity-0`;
    
            switch (type) {
                case 'success':
                    toast.classList.add('bg-green-500', 'text-white');
                    break;
                case 'error':
                    toast.classList.add('bg-red-500', 'text-white');
                    break;
                case 'info':
                    toast.classList.add('bg-blue-500', 'text-white');
                    break;
                default:
                    toast.classList.add('bg-gray-800', 'text-white');
            }
    
            toast.innerHTML = `
                <span>${message}</span>
                <button class="ml-4 focus:outline-none" onclick="this.parentElement.remove()">
                    <i class="ri-close-line"></i>
                </button>
            `;
    
            toastContainer.appendChild(toast);
    
            setTimeout(() => {
                toast.classList.remove('translate-y-2', 'opacity-0');
                toast.classList.add('translate-y-0', 'opacity-100');
            }, 10);
    
            setTimeout(() => {
                toast.classList.add('translate-y-2', 'opacity-0');
                setTimeout(() => {
                    toast.remove();
                }, 300);
            }, 4000);
        }
        
        // Function to notify the cart page about updates
        function notifyCartPageUpdate(cartData) {
            console.log('Notifying cart page about update');
            
            // Check if we're on the cart page
            const isCartPage = document.getElementById('cartContainer') !== null;
            
            // If we're on the cart page, just update it directly
            if (isCartPage && typeof renderCart === 'function') {
                console.log('Found cart page - updating directly');
                renderCart(cartData);
                return;
            }
            
            // Otherwise, create and dispatch a custom event
            const event = new CustomEvent('headerCartUpdated', {
                detail: cartData,
                bubbles: true
            });
            
            console.log('Dispatching headerCartUpdated event');
            document.dispatchEvent(event);
        }
    });
    </script>