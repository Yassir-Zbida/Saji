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
            <button id="menuToggle" class="lg:hidden text-gray-900 focus:outline-none transition hover:opacity-70 rounded-md">
                <i class="ri-menu-line text-2xl"></i>
            </button>
            
            <a href="#" class="flex items-center">
                <span class="font-jakarta text-2xl tracking-wider font-semibold">SAJI HOME</span>
            </a>
            
            <div class="hidden lg:block relative flex-grow max-w-lg mx-8 group search-container">
                <input type="text" placeholder="Search products" class="w-full pl-10 pr-4 py-3 h-10 border border-gray-200 rounded-md focus:outline-none focus:border-gray-900 bg-transparent transition-all text-sm font-jost search-input">
                <i class="ri-search-line search-icon text-gray-400 group-hover:text-gray-600 transition-all"></i>
            </div>
            
            <div class="flex items-center space-x-4">
                <button class="wishlist-btn hidden md:flex items-center justify-center w-10 h-10 border border-black transition rounded-md hover:bg-black hover:text-white">
                    <i class="ri-heart-line text-xl transition-transform"></i>
                </button>
                
                <a href="/login" class="account-btn hidden md:flex items-center justify-center w-10 h-10 border border-black transition rounded-md hover:bg-black hover:text-white ">
                    <i class="ri-user-line text-xl transition-transform"></i>
                </a>
                
                <button id="cartToggle" class="cart-btn flex items-center space-x-1 bg-black text-white px-4 py-3 h-10 transition rounded-md hover:opacity-90">
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
        <input type="text" placeholder="Search products" class="w-full pl-10 pr-4 py-3 h-12 border border-gray-200 rounded-md focus:outline-none focus:border-gray-900 bg-transparent transition-all text-sm font-jost search-input">
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
<div id="mobileSidebar" class="sidebar overflow-y-scroll scrollbar-hide fixed top-0 left-0 h-full w-80 bg-white z-40 shadow-xl overflow-y-auto hidden">
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
            <div class="p-2 border border-gray-200 flex items-center hover:border-gray-900 cursor-pointer transition-all rounded-md">
                <img src="{{ asset('icons/flag-eu.svg') }}" alt="EU Flag" class="w-5 h-3 mr-2 category-icon">
                <span class="text-sm font-jost">EUR (€)</span>
            </div>
            <div class="p-2 border border-gray-200 flex items-center hover:border-gray-900 cursor-pointer transition-all rounded-md">
                <img src="{{ asset('icons/flag-usa.svg') }}" alt="US Flag" class="w-5 h-3 mr-2 category-icon">
                <span class="text-sm font-jost">USD ($)</span>
            </div>
        </div>
    </div>
    
    <div class="p-4">
        <div class="space-y-4">
            <div class="border-b border-gray-100 pb-4">
                <h3 class="text-xs uppercase text-gray-500 font-medium mb-3 font-jost">Account</h3>
                <a href="#" class="flex items-center py-2 text-gray-600 hover:text-gray-900 transition">
                    <i class="ri-user-line text-lg mr-3"></i>
                    <span class="font-jost">Login / Register</span>
                </a>
                <a href="#" class="flex items-center py-2 text-gray-600 hover:text-gray-900 transition">
                    <i class="ri-heart-line text-lg mr-3"></i>
                    <span class="font-jost">Wishlist</span>
                </a>
            </div>
            
            <div class="border-b border-gray-100 pb-4">
                <h3 class="text-xs uppercase text-gray-500 font-medium mb-3 font-jost">Categories</h3>
                <div class="grid grid-cols-1 gap-2">
                    <a href="#" class="flex items-center py-2 text-gray-600 hover:text-gray-900 transition">
                        <img src="{{ asset('icons/Chair.svg') }}" alt="Chair" class="w-5 h-5 mr-3 category-icon">
                        <span class="font-jost">Chairs</span>
                    </a>
                    <a href="#" class="flex items-center py-2 text-gray-600 hover:text-gray-900 transition">
                        <img src="{{ asset('icons/tables.svg') }}" alt="Table" class="w-5 h-5 mr-3 category-icon">
                        <span class="font-jost">Tables</span>
                    </a>
                    <a href="#" class="flex items-center py-2 text-gray-600 hover:text-gray-900 transition">
                        <img src="{{ asset('icons/sofas.svg') }}" alt="Sofa" class="w-5 h-5 mr-3 category-icon">
                        <span class="font-jost">Sofas</span>
                    </a>
                    <a href="#" class="flex items-center py-2 text-gray-600 hover:text-gray-900 transition">
                        <img src="{{ asset('icons/Armchairs.svg') }}" alt="Armchair" class="w-5 h-5 mr-3 category-icon">
                        <span class="font-jost">Armchairs</span>
                    </a>
                    <a href="#" class="flex items-center py-2 text-gray-600 hover:text-gray-900 transition">
                        <img src="{{ asset('icons/Beds.svg') }}" alt="Bed" class="w-5 h-5 mr-3 category-icon">
                        <span class="font-jost">Beds</span>
                    </a>
                    <a href="#" class="flex items-center py-2 text-gray-600 hover:text-gray-900 transition">
                        <img src="{{ asset('icons/storage.svg') }}" alt="Storage" class="w-5 h-5 mr-3 category-icon">
                        <span class="font-jost">Storage</span>
                    </a>
                    <a href="#" class="flex items-center py-2 text-gray-600 hover:text-gray-900 transition">
                        <img src="{{ asset('icons/textiles.svg') }}" alt="Textiles" class="w-5 h-5 mr-3 category-icon">
                        <span class="font-jost">Textiles</span>
                    </a>
                    <a href="#" class="flex items-center py-2 text-gray-600 hover:text-gray-900 transition">
                        <img src="{{ asset('icons/lighting.svg') }}" alt="Lighting" class="w-5 h-5 mr-3 category-icon">
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
            <div class="bg-black text-white p-3 text-center text-sm rounded-md border border-black hover:bg-white hover:text-black transition-all duration-300">
                <a href="" class="block font-jost">Free shipping for all orders over €1,300</a>
            </div>
            
            <div class="border-b border-gray-100 pb-4">
                <h3 class="text-xs uppercase text-gray-500 font-medium mb-3 font-jost">Information</h3>
                <a href="#" class="block py-2 text-gray-600 hover:text-gray-900 transition font-jost">Gift Cards</a>
                <a href="#" class="block py-2 text-gray-600 hover:text-gray-900 transition font-jost">Showrooms</a>
                <a href="#" class="block py-2 text-gray-600 hover:text-gray-900 transition font-jost">About Us</a>
            </div>
        </div>
    </div>
</div>

<!-- Cart Sidebar -->
<div id="cartSidebar" class="cart-sidebar fixed top-0 right-0 h-full w-80 md:w-96 bg-white z-40 shadow-xl overflow-hidden flex flex-col hidden">
    <div class="p-4 border-b border-gray-100 flex justify-between items-center">
        <h2 class="font-jakarta text-xl tracking-wide font-semibold">Shopping Cart</h2>
        <button id="closeCart" class="text-gray-500 hover:text-gray-900 transition rounded-md">
            <i class="ri-close-line text-2xl"></i>
        </button>
    </div>
    
    <div class="flex-1 flex flex-col items-center justify-center p-4">
        
        <h3 class="font-jost font-medium mb-1 px-4">Your cart is empty</h3>
        <p class="text-sm text-gray-500 text-center mb-6 font-jost mx-14">It seems you haven't added any products to your cart yet.</p>
        
    </div>
    
    <div class="p-4 border-t border-gray-100">
        <div class="flex justify-between mb-4 font-jost">
            <span>Subtotal</span>
            <span class="font-medium">0.00 $</span>
        </div>
        <button class="w-full bg-black text-white py-3 rounded-md hover:opacity-80 transition font-jost">
            Checkout
        </button>
        <button class="w-full border border-black py-2 mt-2 rounded-md hover:bg-black hover:text-white transition-all duration-300 font-jost">
            View Cart
        </button>
    </div>
</div>

<div id="overlay" class="overlay fixed inset-0 bg-black z-30 hidden"></div>