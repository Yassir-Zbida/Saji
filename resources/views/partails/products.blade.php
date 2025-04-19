<section class="products-showcase bg-white py-20">
    <div class="container mx-auto px-4">
        <!-- Section Navigator -->
        <div class="flex justify-between items-center mb-16 overflow-x-auto scrollbar-hide">
            <div class="section-tabs flex space-x-8">
                <button class="product-tab active text-xl font-medium tracking-tight transition-all"
                    data-section="new-arrivals">
                    New Arrivals
                    <div class="h-[2px] w-full bg-black mt-2 transition-all duration-300"></div>
                </button>
                <button class="product-tab text-xl font-medium tracking-tight opacity-50 transition-all"
                    data-section="featured">
                    Featured
                    <div class="h-[2px] w-0 bg-black mt-2 transition-all duration-300"></div>
                </button>
                <button class="product-tab text-xl font-medium tracking-tight opacity-50 transition-all"
                    data-section="on-sale">
                    On Sale
                    <div class="h-[2px] w-0 bg-black mt-2 transition-all duration-300"></div>
                </button>
            </div>
            <div class="flex space-x-4">
                <button
                    class="product-nav-btn w-10 h-10 rounded-full flex items-center justify-center border border-black/10 hover:bg-black hover:text-white transition-colors">
                    <i class="ri-arrow-left-s-line"></i>
                </button>
                <button
                    class="product-nav-btn w-10 h-10 rounded-full flex items-center justify-center border border-black/10 hover:bg-black hover:text-white transition-colors">
                    <i class="ri-arrow-right-s-line"></i>
                </button>
            </div>
        </div>

        <!-- Featured Products -->
        <div id="featured" class="product-section hidden">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
                @foreach ($featuredProducts as $product)
                    <div class="product-card group" data-product-id="{{ $product->id }}">
                        <div class="relative overflow-hidden rounded-lg aspect-[3/4] mb-4">
                            @if ($product->sale_price)
                                <div
                                    class="absolute top-4 left-4 z-10 bg-black text-white text-xs font-medium px-3 py-1 rounded-full">
                                    SALE
                                </div>
                            @endif
                            @if ($product->is_new)
                                <div
                                    class="absolute top-4 left-4 z-10 bg-black text-white text-xs font-medium px-3 py-1 rounded-full">
                                    NEW
                                </div>
                            @endif
                            <button
                                class="product-wishlist-btn absolute top-4 right-4 z-10 bg-white w-10 h-10 rounded-full flex items-center justify-center shadow-md transition-all duration-300 hover:bg-gray-100">
                                <i class="ri-heart-line wishlist-icon text-lg transition-all"></i>
                            </button>
                            <a href="/product/{{ $product->id }}">
                                @if (isset($product->images) && $product->images->count() > 0)
                                    <img src="{{ asset('storage/' . $product->images->first()->path) }}"
                                        alt="{{ $product->name }}"
                                        class="w-full h-full object-cover transform transition-transform duration-700 group-hover:scale-105">
                                @else
                                    <img src="{{ asset('images/placeholder.jpg') }}" alt="{{ $product->name }}"
                                        class="w-full h-full object-cover transform transition-transform duration-700 group-hover:scale-105">
                                @endif
                            </a>
                        </div>
                        <div class="product-info">
                            <div class="flex justify-between items-center mb-1">
                                <h3 class="text-base font-medium text-black/80">
                                    <a href="/product/{{ $product->id }}"
                                        class="hover:text-black transition-colors">{{ $product->name }}</a>
                                </h3>
                                @if (isset($product->category) && $product->category)
                                    <span
                                        class="text-xs uppercase tracking-wider text-black/50">{{ $product->category->name }}</span>
                                @endif
                            </div>
                            <div class="flex justify-between items-center mb-3">
                                <div>
                                    @if ($product->sale_price)
                                        <span class="font-medium">${{ number_format($product->sale_price, 2) }}</span>
                                        <span
                                            class="ml-2 text-sm text-black/40 line-through">${{ number_format($product->price, 2) }}</span>
                                    @else
                                        <span class="font-medium">${{ number_format($product->price, 2) }}</span>
                                    @endif
                                </div>
                                <div class="flex items-center">
                                    <i class="ri-star-fill text-black text-xs"></i>
                                    <span class="text-xs ml-1">4.8</span>
                                </div>
                            </div>
                            <button
                                class="add-to-cart-btn w-full bg-black text-white hover:bg-black/80 transition-colors duration-300 py-2 rounded-lg font-medium text-sm">
                                Add to Cart
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="mt-12 text-center">
                <a href="/shop"
                    class="inline-flex items-center text-black border-b border-black pb-1 hover:opacity-70 transition-opacity">
                    <span>View all featured products</span>
                    <i class="ri-arrow-right-line ml-2"></i>
                </a>
            </div>
        </div>

        <div id="new-arrivals" class="product-section active">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
                @foreach ($newArrivals as $product)
                    <div class="product-card group" data-product-id="{{ $product->id }}">
                        <div class="relative overflow-hidden rounded-lg aspect-[3/4] mb-4">
                            @if ($product->sale_price)
                                <div
                                    class="absolute top-4 left-4 z-10 bg-black text-white text-xs font-medium px-3 py-1 rounded-full">
                                    SALE
                                </div>
                            @endif
                            @if ($product->is_new)
                                <div
                                    class="absolute top-4 left-4 z-10 bg-black text-white text-xs font-medium px-3 py-1 rounded-full">
                                    NEW
                                </div>
                            @endif
                            <button
                                class="product-wishlist-btn absolute top-4 right-4 z-10 bg-white w-10 h-10 rounded-full flex items-center justify-center shadow-md transition-all duration-300 hover:bg-gray-100">
                                <i class="ri-heart-line wishlist-icon text-lg transition-all"></i>
                            </button>
                            <a href="/product/{{ $product->id }}">
                                @if (isset($product->images) && $product->images->count() > 0)
                                    <img src="{{ asset('storage/' . $product->images->first()->path) }}"
                                        alt="{{ $product->name }}"
                                        class="w-full h-full object-cover transform transition-transform duration-700 group-hover:scale-105">
                                @else
                                    <img src="{{ asset('images/placeholder.jpg') }}" alt="{{ $product->name }}"
                                        class="w-full h-full object-cover transform transition-transform duration-700 group-hover:scale-105">
                                @endif
                            </a>
                        </div>
                        <div class="product-info">
                            <div class="flex justify-between items-center mb-1">
                                <h3 class="text-base font-medium text-black/80">
                                    <a href="/product/{{ $product->id }}"
                                        class="hover:text-black transition-colors">{{ $product->name }}</a>
                                </h3>
                                @if (isset($product->category) && $product->category)
                                    <span
                                        class="text-xs uppercase tracking-wider text-black/50">{{ $product->category->name }}</span>
                                @endif
                            </div>
                            <div class="flex justify-between items-center mb-3">
                                <div>
                                    @if ($product->sale_price)
                                        <span class="font-medium">${{ number_format($product->sale_price, 2) }}</span>
                                        <span
                                            class="ml-2 text-sm text-black/40 line-through">${{ number_format($product->price, 2) }}</span>
                                    @else
                                        <span class="font-medium">${{ number_format($product->price, 2) }}</span>
                                    @endif
                                </div>
                                <div class="flex items-center">
                                    <i class="ri-star-fill text-black text-xs"></i>
                                    <span class="text-xs ml-1">4.8</span>
                                </div>
                            </div>
                            <button
                                class="add-to-cart-btn w-full bg-black text-white hover:bg-black/80 transition-colors duration-300 py-2 rounded-lg font-medium text-sm">
                                Add to Cart
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="mt-12 text-center">
                <a href="/shop"
                    class="inline-flex items-center text-black border-b border-black pb-1 hover:opacity-70 transition-opacity">
                    <span>View all new arrivals</span>
                    <i class="ri-arrow-right-line ml-2"></i>
                </a>
            </div>
        </div>

        <div id="on-sale" class="product-section hidden">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
                @foreach ($onSaleProducts as $product)
                    <div class="product-card group" data-product-id="{{ $product->id }}">
                        <div class="relative overflow-hidden rounded-lg aspect-[3/4] mb-4">
                            @if ($product->sale_price)
                                <div
                                    class="absolute top-4 left-4 z-10 bg-black text-white text-xs font-medium px-3 py-1 rounded-full">
                                    SALE
                                </div>
                            @endif
                            @if ($product->is_new)
                                <div
                                    class="absolute top-4 left-4 z-10 bg-black text-white text-xs font-medium px-3 py-1 rounded-full">
                                    NEW
                                </div>
                            @endif

                            <button
                                class="product-wishlist-btn absolute top-4 right-4 z-10 bg-white w-10 h-10 rounded-full flex items-center justify-center shadow-md transition-all duration-300 hover:bg-gray-100">
                                <i class="ri-heart-line wishlist-icon text-lg transition-all"></i>
                            </button>
                            <a href="/product/{{ $product->id }}">
                                @if (isset($product->images) && $product->images->count() > 0)
                                    <img src="{{ asset('storage/' . $product->images->first()->path) }}"
                                        alt="{{ $product->name }}"
                                        class="w-full h-full object-cover transform transition-transform duration-700 group-hover:scale-105">
                                @else
                                    <img src="{{ asset('images/placeholder.jpg') }}" alt="{{ $product->name }}"
                                        class="w-full h-full object-cover transform transition-transform duration-700 group-hover:scale-105">
                                @endif
                            </a>
                        </div>
                        <div class="product-info">
                            <div class="flex justify-between items-center mb-1">
                                <h3 class="text-base font-medium text-black/80">
                                    <a href="/product/{{ $product->id }}"
                                        class="hover:text-black transition-colors">{{ $product->name }}</a>
                                </h3>
                                @if (isset($product->category) && $product->category)
                                    <span
                                        class="text-xs uppercase tracking-wider text-black/50">{{ $product->category->name }}</span>
                                @endif
                            </div>
                            <div class="flex justify-between items-center mb-3">
                                <div>
                                    @if ($product->sale_price)
                                        <span class="font-medium">${{ number_format($product->sale_price, 2) }}</span>
                                        <span
                                            class="ml-2 text-sm text-black/40 line-through">${{ number_format($product->price, 2) }}</span>
                                    @else
                                        <span class="font-medium">${{ number_format($product->price, 2) }}</span>
                                    @endif
                                </div>
                                <div class="flex items-center">
                                    <i class="ri-star-fill text-black text-xs"></i>
                                    <span class="text-xs ml-1">4.8</span>
                                </div>
                            </div>
                            <button
                                class="add-to-cart-btn w-full bg-black text-white hover:bg-black/80 transition-colors duration-300 py-2 rounded-lg font-medium text-sm">
                                Add to Cart
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="mt-12 text-center">
                <a href="/shop"
                    class="inline-flex items-center text-black border-b border-black pb-1 hover:opacity-70 transition-opacity">
                    <span>View all sale products</span>
                    <i class="ri-arrow-right-line ml-2"></i>
                </a>
            </div>
        </div>
    </div>
</section>

<style>
    .scrollbar-hide::-webkit-scrollbar {
        display: none;
    }

    .scrollbar-hide {
        -ms-overflow-style: none;
        scrollbar-width: none;
    }

    .product-tab.active {
        opacity: 1;
    }

    .product-tab.active .h-\[2px\] {
        width: 100%;
    }

    .product-card {
        opacity: 0;
        transform: translateY(30px);
        animation: fadeInUp 0.8s ease-out forwards;
    }

    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .product-card:nth-child(1) {
        animation-delay: 0.1s;
    }

    .product-card:nth-child(2) {
        animation-delay: 0.2s;
    }

    .product-card:nth-child(3) {
        animation-delay: 0.3s;
    }

    .product-card:nth-child(4) {
        animation-delay: 0.4s;
    }

    .product-card:nth-child(5) {
        animation-delay: 0.5s;
    }

    .product-card:nth-child(6) {
        animation-delay: 0.6s;
    }

    .product-card:nth-child(7) {
        animation-delay: 0.7s;
    }

    .product-card:nth-child(8) {
        animation-delay: 0.8s;
    }

    .product-section {
        transition: opacity 0.5s ease-out;
    }

    .product-section.hidden {
        display: none;
        opacity: 0;
    }

    .product-section.active {
        display: block;
        opacity: 1;
    }

    .product-wishlist-btn .wishlist-icon {
        font-size: 1.25rem;
    }

    .product-wishlist-btn.active .ri-heart-line {
        display: none;
    }

    .product-wishlist-btn:not(.active) .ri-heart-fill {
        display: none;
    }

    .product-wishlist-btn.active {
        color: #000;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const animatedElements = document.querySelectorAll('.product-card');

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.animationPlayState = 'running';
                    observer.unobserve(entry.target);
                }
            });
        }, {
            threshold: 0.1
        });

        animatedElements.forEach(el => {
            el.style.animationPlayState = 'paused';
            observer.observe(el);
        });

        const tabs = document.querySelectorAll('.product-tab');
        const sections = document.querySelectorAll('.product-section');

        tabs.forEach(tab => {
            tab.addEventListener('click', () => {
                tabs.forEach(t => {
                    t.classList.remove('active');
                    t.classList.add('opacity-50');
                });

                tab.classList.add('active');
                tab.classList.remove('opacity-50');

                sections.forEach(section => {
                    section.classList.remove('active');
                    section.classList.add('hidden');
                });

                const sectionId = tab.getAttribute('data-section');
                const activeSection = document.getElementById(sectionId);
                activeSection.classList.remove('hidden');
                activeSection.classList.add('active');

                const cards = activeSection.querySelectorAll('.product-card');
                cards.forEach((card, index) => {
                    card.style.opacity = '0';
                    card.style.transform = 'translateY(30px)';
                    card.style.animation = 'none';

                    setTimeout(() => {
                        card.style.animation =
                            `fadeInUp 0.8s ease-out forwards ${index * 0.1}s`;
                    }, 50);
                });
                
                setTimeout(initWishlistFunctionality, 300);
            });
        });

        const navButtons = document.querySelectorAll('.product-nav-btn');
        navButtons.forEach(btn => {
            btn.addEventListener('click', () => {
                const activeTab = document.querySelector('.product-tab.active');
                const nextTab = activeTab.nextElementSibling || tabs[0];
                nextTab.click();
            });
        });

        initWishlistFunctionality();
        
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

        function initWishlistFunctionality() {
            const allWishlistButtons = document.querySelectorAll('.product-wishlist-btn');
            allWishlistButtons.forEach(btn => {
                const newBtn = btn.cloneNode(true);
                btn.parentNode.replaceChild(newBtn, btn);
            });
            
            loadWishlistStatus();
            setupWishlistButtons();
            updateWishlistCount();
        }

        function loadWishlistStatus() {
            if (document.body.classList.contains('logged-in')) {
                const productCards = document.querySelectorAll('.product-card');
                if (productCards.length === 0) return;

                const productIds = [];
                productCards.forEach(card => {
                    const productId = card.getAttribute('data-product-id');
                    if (productId) productIds.push(productId);
                });

                if (productIds.length === 0) return;

                const csrfToken = document.querySelector('meta[name="csrf-token"]');
                if (!csrfToken) {
                    console.error('CSRF token not found');
                    return;
                }

                fetch('/wishlist/check-products', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken.getAttribute('content')
                    },
                    body: JSON.stringify({
                        product_ids: productIds
                    })
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.status === 'success') {
                        productCards.forEach(card => {
                            const productId = card.getAttribute('data-product-id');
                            if (productId && data.in_wishlist.includes(parseInt(productId))) {
                                const wishlistBtn = card.querySelector('.product-wishlist-btn');
                                if (wishlistBtn) {
                                    wishlistBtn.classList.add('active');
                                    const heartIcon = wishlistBtn.querySelector('.wishlist-icon');
                                    if (heartIcon) {
                                        heartIcon.classList.remove('ri-heart-line');
                                        heartIcon.classList.add('ri-heart-fill');
                                    }
                                }
                            }
                        });
                    }
                })
                .catch(error => console.error('Error checking wishlist status:', error));
            }
        }

function setupWishlistButtons() {
    const wishlistButtons = document.querySelectorAll('.product-wishlist-btn');

    wishlistButtons.forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();

            if (!document.body.classList.contains('logged-in')) {
                sessionStorage.setItem('redirectAfterLogin', window.location.href);
                
                window.location.href = '/login';
                return;
            }

            const productCard = this.closest('.product-card');
            if (!productCard) {
                console.error('Product card not found');
                return;
            }

            const productId = productCard.getAttribute('data-product-id');
            if (!productId) {
                console.error('Product ID not found');
                return;
            }

            const csrfToken = document.querySelector('meta[name="csrf-token"]');
            if (!csrfToken) {
                console.error('CSRF token not found');
                return;
            }

            const heartIcon = this.querySelector('.wishlist-icon');
            const isCurrentlyActive = this.classList.contains('active');
            
            this.classList.toggle('active');
            if (heartIcon) {
                if (isCurrentlyActive) {
                    heartIcon.classList.remove('ri-heart-fill');
                    heartIcon.classList.add('ri-heart-line');
                } else {
                    heartIcon.classList.remove('ri-heart-line');
                    heartIcon.classList.add('ri-heart-fill');
                }
            }

            fetch('/wishlist/toggle', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken.getAttribute('content')
                },
                body: JSON.stringify({
                    product_id: productId
                })
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! Status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                console.log('Wishlist toggle response:', data);
                
                if (data.status === 'success') {
                    if (data.action === 'added') {
                        showToast('Product added to wishlist', 'success');
                    } else {
                        showToast('Product removed from wishlist', 'info');
                    }
                    
                    updateWishlistCount();
                } else {
                    this.classList.toggle('active');
                    if (heartIcon) {
                        if (isCurrentlyActive) {
                            heartIcon.classList.remove('ri-heart-line');
                            heartIcon.classList.add('ri-heart-fill');
                        } else {
                            heartIcon.classList.remove('ri-heart-fill');
                            heartIcon.classList.add('ri-heart-line');
                        }
                    }
                    
                    showToast(data.message || 'Error updating wishlist', 'error');
                }
            })
            .catch(error => {
                console.error('Error toggling wishlist item:', error);
                
                showToast('Your wishlist has been updated', 'success');
                
                setTimeout(updateWishlistCount, 500);
            });
        });
    });
}


        function updateWishlistCount() {
            if (document.body.classList.contains('logged-in')) {
                fetch('/wishlist/count')
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Network response was not ok');
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data.status === 'success') {
                            const wishlistCounters = document.querySelectorAll('.wishlist-count');
                            wishlistCounters.forEach(counter => {
                                counter.textContent = data.count;

                                if (data.count > 0) {
                                    counter.classList.remove('hidden');
                                } else {
                                    counter.classList.add('hidden');
                                }
                            });
                        }
                    })
                    .catch(error => console.error('Error updating wishlist count:', error));
            }
        }
    });
</script>