/**
 * Shop.js - Handles all e-commerce functionality for shop pages
 * Including cart operations, wishlist management, and filtering
 */

document.addEventListener('DOMContentLoaded', function() {
    // Initialize components
    initFilterSidebar();
    initProductViewToggle();
    initSorting();
    initAddToCart();
    initWishlist();
    initLoadMore();
    initPriceRangeSlider();
    initQuickView();
    initMiniCart();

    // Check if user is logged in by looking for the class on body
    const isLoggedIn = document.body.classList.contains('logged-in');

    /**
     * Filter Sidebar Functionality
     * Toggle the filter sidebar and apply filters
     */
    function initFilterSidebar() {
        const openFilterBtn = document.getElementById('openFilters');
        const closeFilterBtn = document.getElementById('closeFilters');
        const filterModal = document.getElementById('filterModal');
        const filterSidebar = document.getElementById('filterSidebar');
        const overlay = document.getElementById('overlay');
        const applyAllFiltersBtn = document.getElementById('apply-all-filters');
        const clearAllFiltersBtn = document.getElementById('clear-all-filters');
        const applyPriceFilterBtn = document.getElementById('apply-price-filter');

        // Toggle filter sidebar
        if (openFilterBtn) {
            openFilterBtn.addEventListener('click', function(e) {
                e.preventDefault();
                filterModal.classList.remove('hidden');
                setTimeout(() => {
                    filterSidebar.classList.remove('-translate-x-full');
                }, 50);
            });
        }

        // Close filter sidebar
        if (closeFilterBtn) {
            closeFilterBtn.addEventListener('click', function(e) {
                e.preventDefault();
                closeFilterSidebar();
            });
        }

        if (overlay) {
            overlay.addEventListener('click', closeFilterSidebar);
        }

        function closeFilterSidebar() {
            filterSidebar.classList.add('-translate-x-full');
            setTimeout(() => {
                filterModal.classList.add('hidden');
            }, 300);
        }

        // Apply all filters
        if (applyAllFiltersBtn) {
            applyAllFiltersBtn.addEventListener('click', function(e) {
                e.preventDefault();
                applyFiltersWithAjax();
                closeFilterSidebar();
            });
        }

        // Apply price filter
        if (applyPriceFilterBtn) {
            applyPriceFilterBtn.addEventListener('click', function(e) {
                e.preventDefault();
                applyPriceFilterWithAjax();
            });
        }

        // Clear all filters
        if (clearAllFiltersBtn) {
            clearAllFiltersBtn.addEventListener('click', function(e) {
                e.preventDefault();
                clearAllFiltersWithAjax();
            });
        }

        // Handle individual filter tag removal
        const filterTags = document.querySelectorAll('.filter-tag button');
        filterTags.forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                const filterType = this.getAttribute('data-remove');
                const filterValue = this.getAttribute('data-value');
                removeFilterWithAjax(filterType, filterValue);
            });
        });

        // Handle category checkboxes
        const categoryCheckboxes = document.querySelectorAll('.category-filter');
        categoryCheckboxes.forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                // Store filter change to be applied later
                const filterContainer = document.getElementById('applied-filters');
                if (filterContainer) {
                    filterContainer.classList.remove('hidden');
                }
            });
        });

        // Handle color checkboxes
        const colorCheckboxes = document.querySelectorAll('.color-filter');
        colorCheckboxes.forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                // Store filter change to be applied later
                const filterContainer = document.getElementById('applied-filters');
                if (filterContainer) {
                    filterContainer.classList.remove('hidden');
                }
            });
        });
    }

    /**
     * Price Range Slider
     */
    function initPriceRangeSlider() {
        const priceMinSlider = document.getElementById('price-min');
        const priceMinValue = document.getElementById('price-min-value');
        const priceMaxValue = document.getElementById('price-max-value');
        const priceRangeProgress = document.getElementById('price-range-progress');

        if (priceMinSlider && priceMinValue && priceMaxValue) {
            let minPrice = parseInt(priceMinSlider.value);
            let maxPrice = 100; // Default max price

            // Initialize displayed values
            priceMinValue.textContent = minPrice;
            priceMaxValue.textContent = maxPrice;

            // Update progress bar
            if (priceRangeProgress) {
                priceRangeProgress.style.width = `${(minPrice / 100) * 100}%`;
            }

            priceMinSlider.addEventListener('input', function() {
                minPrice = parseInt(this.value);
                priceMinValue.textContent = minPrice;
                
                // Update progress bar
                if (priceRangeProgress) {
                    priceRangeProgress.style.width = `${(minPrice / 100) * 100}%`;
                }
            });
        }
    }

    /**
     * Apply price filter with AJAX
     */
    function applyPriceFilterWithAjax() {
        const minPrice = document.getElementById('price-min-value').textContent;
        const maxPrice = document.getElementById('price-max-value').textContent;
        
        // Show loading state
        const productsContainer = document.getElementById('products-container');
        if (productsContainer) {
            productsContainer.classList.add('opacity-50');
            productsContainer.innerHTML = '<div class="col-span-full text-center py-10"><div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-primary"></div></div>';
        }
        
        // Build filter params
        const params = new URLSearchParams(window.location.search);
        params.set('min_price', minPrice);
        params.set('max_price', maxPrice);
        
        // Make AJAX request
        fetchFilteredProducts(params);
    }

    /**
     * Apply all selected filters with AJAX
     */
    function applyFiltersWithAjax() {
        // Get all selected categories
        const selectedCategories = [];
        document.querySelectorAll('.category-filter:checked').forEach(checkbox => {
            selectedCategories.push(checkbox.value);
        });

        // Get all selected colors
        const selectedColors = [];
        document.querySelectorAll('.color-filter:checked').forEach(checkbox => {
            selectedColors.push(checkbox.value);
        });

        // Get price range
        const minPrice = document.getElementById('price-min-value').textContent;
        const maxPrice = document.getElementById('price-max-value').textContent;

        // Show loading state
        const productsContainer = document.getElementById('products-container');
        if (productsContainer) {
            productsContainer.classList.add('opacity-50');
            productsContainer.innerHTML = '<div class="col-span-full text-center py-10"><div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-primary"></div></div>';
        }

        // Build query parameters
        const params = new URLSearchParams();
        
        // Add categories
        selectedCategories.forEach(category => {
            params.append('category[]', category);
        });

        // Add colors
        selectedColors.forEach(color => {
            params.append('color[]', color);
        });

        // Add price range
        if (minPrice) {
            params.set('min_price', minPrice);
        }
        if (maxPrice) {
            params.set('max_price', maxPrice);
        }

        // Keep sort parameter if exists
        const currentUrl = new URL(window.location.href);
        const sort = currentUrl.searchParams.get('sort');
        if (sort) {
            params.set('sort', sort);
        }

        // Make AJAX request
        fetchFilteredProducts(params);
    }

    /**
     * Clear all filters with AJAX
     */
    function clearAllFiltersWithAjax() {
        // Show loading state
        const productsContainer = document.getElementById('products-container');
        if (productsContainer) {
            productsContainer.classList.add('opacity-50');
            productsContainer.innerHTML = '<div class="col-span-full text-center py-10"><div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-primary"></div></div>';
        }
        
        // Reset all checkboxes and inputs
        document.querySelectorAll('.category-filter, .color-filter').forEach(checkbox => {
            checkbox.checked = false;
        });
        
        // Make AJAX request with empty params
        fetchFilteredProducts(new URLSearchParams());
    }

    /**
     * Remove a filter with AJAX
     */
    function removeFilterWithAjax(type, value) {
        // Build params from current URL, removing the specified filter
        const params = new URLSearchParams(window.location.search);
        
        if (type === 'price') {
            params.delete('min_price');
            params.delete('max_price');
        } else if (type === 'category' || type === 'color') {
            // For array params like category[] or color[]
            const values = params.getAll(`${type}[]`);
            params.delete(`${type}[]`);
            
            values.forEach(val => {
                if (val !== value) {
                    params.append(`${type}[]`, val);
                }
            });
        } else {
            // For single value params
            params.delete(type);
        }
        
        // Show loading state
        const productsContainer = document.getElementById('products-container');
        if (productsContainer) {
            productsContainer.classList.add('opacity-50');
            productsContainer.innerHTML = '<div class="col-span-full text-center py-10"><div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-primary"></div></div>';
        }
        
        // Make AJAX request
        fetchFilteredProducts(params);
    }

    /**
     * Fetch filtered products with AJAX - FIXED VERSION
     */
    function fetchFilteredProducts(params) {
        // Update URL for history without reloading
        const newUrl = `${window.location.pathname}?${params.toString()}`;
        window.history.pushState({ path: newUrl }, '', newUrl);
        
        // Show loading state
        const productsContainer = document.getElementById('products-container');
        if (productsContainer) {
            productsContainer.classList.add('opacity-50');
            productsContainer.innerHTML = '<div class="col-span-full text-center py-10"><div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-primary"></div></div>';
        }
        
        // Get CSRF token
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        
        // FIXED: Use GET request instead of POST for filtering
        fetch(`/shop/filter?${params.toString()}`, {
            method: 'GET',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error(`Network response was not ok: ${response.status}`);
            }
            return response.text();
        })
        .then(html => {
            // Create a temporary div to parse the HTML
            const temp = document.createElement('div');
            temp.innerHTML = html;
            
            // Get products from the response
            const newProductsContainer = temp.querySelector('#products-container');
            
            if (productsContainer && newProductsContainer) {
                productsContainer.innerHTML = newProductsContainer.innerHTML;
                productsContainer.classList.remove('opacity-50');
                
                // Re-initialize functionality for new products
                initAddToCart();
                initWishlist();
                
                // Update load more button if it exists
                const loadMoreContainer = document.getElementById('load-more-container');
                const newLoadMoreContainer = temp.querySelector('#load-more-container');
                
                if (loadMoreContainer && newLoadMoreContainer) {
                    loadMoreContainer.innerHTML = newLoadMoreContainer.innerHTML;
                    if (newLoadMoreContainer.classList.contains('hidden')) {
                        loadMoreContainer.classList.add('hidden');
                    } else {
                        loadMoreContainer.classList.remove('hidden');
                    }
                    
                    // Re-initialize load more
                    initLoadMore();
                }
            }
            
            // Update filter tags
            const appliedFilters = document.getElementById('applied-filters');
            const newAppliedFilters = temp.querySelector('#applied-filters');
            
            if (appliedFilters && newAppliedFilters) {
                appliedFilters.innerHTML = newAppliedFilters.innerHTML;
                
                if (newAppliedFilters.classList.contains('hidden')) {
                    appliedFilters.classList.add('hidden');
                } else {
                    appliedFilters.classList.remove('hidden');
                }
                
                // Re-initialize filter tag event handlers
                const filterTags = appliedFilters.querySelectorAll('.filter-tag button');
                filterTags.forEach(button => {
                    button.addEventListener('click', function(e) {
                        e.preventDefault();
                        const filterType = this.getAttribute('data-remove');
                        const filterValue = this.getAttribute('data-value');
                        removeFilterWithAjax(filterType, filterValue);
                    });
                });
            }
        })
        .catch(error => {
            console.error('Error fetching filtered products:', error);
            // If error, show message and restore container
            if (productsContainer) {
                productsContainer.classList.remove('opacity-50');
                productsContainer.innerHTML = '<div class="col-span-full text-center py-10">Error loading products. Please try again.</div>';
            }
        });
    }

    /**
     * Product View Toggle (Grid/List)
     */
    function initProductViewToggle() {
        const viewToggleBtns = document.querySelectorAll('.view-toggle-btn');
        const productsContainer = document.getElementById('products-container');
        
        if (viewToggleBtns.length && productsContainer) {
            // Get saved view preference from localStorage
            const savedView = localStorage.getItem('shop_view_preference') || 'grid';
            
            // Set initial view
            productsContainer.setAttribute('data-view', savedView);
            viewToggleBtns.forEach(btn => {
                if (btn.getAttribute('data-view') === savedView) {
                    btn.classList.add('active');
                } else {
                    btn.classList.remove('active');
                }
            });
            
            // Add event listeners
            viewToggleBtns.forEach(btn => {
                btn.addEventListener('click', function(e) {
                    e.preventDefault();
                    const view = this.getAttribute('data-view');
                    
                    // Update active button
                    viewToggleBtns.forEach(b => b.classList.remove('active'));
                    this.classList.add('active');
                    
                    // Update container view
                    productsContainer.setAttribute('data-view', view);
                    
                    // Save preference
                    localStorage.setItem('shop_view_preference', view);
                });
            });
        }
    }

    /**
     * Product Sorting with AJAX
     */
    function initSorting() {
        const sortSelect = document.getElementById('sort-select');
        
        if (sortSelect) {
            sortSelect.addEventListener('change', function() {
                // Show loading state
                const productsContainer = document.getElementById('products-container');
                if (productsContainer) {
                    productsContainer.classList.add('opacity-50');
                    productsContainer.innerHTML = '<div class="col-span-full text-center py-10"><div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-primary"></div></div>';
                }
                
                // Get current filters
                const params = new URLSearchParams(window.location.search);
                params.set('sort', this.value);
                
                // Make AJAX request
                fetchFilteredProducts(params);
            });
        }
    }

    /**
     * Add to Cart Functionality
     */
    function initAddToCart() {
        const addToCartButtons = document.querySelectorAll('.add-to-cart-btn');
        
        // Remove previous event listeners by cloning and replacing
        addToCartButtons.forEach(button => {
            const newButton = button.cloneNode(true);
            button.parentNode.replaceChild(newButton, button);
            
            newButton.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation(); // Prevent event bubbling to parent links
                
                const productId = this.getAttribute('data-product-id');
                
                if (!productId) {
                    console.error('Product ID not found');
                    return;
                }
                
                // Disable button and show loading state
                this.disabled = true;
                const originalText = this.innerHTML;
                this.innerHTML = '<i class="ri-loader-4-line animate-spin mr-2"></i> Adding...';
                
                addToCart(productId, 1)
                    .then(response => {
                        // Re-enable button and restore text
                        this.disabled = false;
                        this.innerHTML = originalText;
                        
                        if (response.success) {
                            showToast(response.message, 'success');
                            updateCartCount(response.cart.total_quantity);
                            updateMiniCart(response.cart);
                        } else {
                            showToast(response.message, 'error');
                        }
                    })
                    .catch(error => {
                        console.error('Error adding to cart:', error);
                        showToast('Error adding product to cart', 'error');
                        
                        // Re-enable button and restore text
                        this.disabled = false;
                        this.innerHTML = originalText;
                    });
            });
        });
    }

    /**
     * Add a product to cart
     * @param {number} productId - The product ID
     * @param {number} quantity - Quantity to add
     * @param {number|null} variationId - Optional product variation ID
     * @returns {Promise} - Promise with cart data
     */
    function addToCart(productId, quantity = 1, variationId = null) {
        // Get CSRF token
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        
        const data = {
            product_id: productId,
            quantity: quantity
        };
        
        if (variationId) {
            data.product_variation_id = variationId;
        }
        
        return fetch('/ajax/cart/add', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify(data)
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        });
    }

    /**
     * Update cart item quantity
     * @param {number} cartItemId - The cart item ID
     * @param {number} quantity - New quantity
     * @returns {Promise} - Promise with cart data
     */
    function updateCartItem(cartItemId, quantity) {
        // Get CSRF token
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        
        return fetch(`/ajax/cart/update/${cartItemId}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({ quantity: quantity })
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        });
    }

    /**
     * Remove an item from cart
     * @param {number} cartItemId - The cart item ID
     * @returns {Promise} - Promise with cart data
     */
    function removeCartItem(cartItemId) {
        // Get CSRF token
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        
        return fetch(`/ajax/cart/remove/${cartItemId}`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        });
    }

    /**
     * Initialize mini cart
     */
    function initMiniCart() {
        // Toggle mini cart
        const miniCartToggle = document.querySelector('.mini-cart-toggle');
        const miniCart = document.querySelector('.mini-cart');
        
        if (miniCartToggle && miniCart) {
            miniCartToggle.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                
                miniCart.classList.toggle('show');
                
                // Close on outside click
                document.addEventListener('click', function closeMiniCart(e) {
                    if (!miniCart.contains(e.target) && e.target !== miniCartToggle) {
                        miniCart.classList.remove('show');
                        document.removeEventListener('click', closeMiniCart);
                    }
                });
                
                // Load cart data if needed
                if (miniCart.classList.contains('show')) {
                    getCart();
                }
            });
        }
        
        // Handle quantity changes within mini cart
        document.addEventListener('click', function(e) {
            // Quantity increment/decrement
            if (e.target.classList.contains('mini-cart-qty-btn')) {
                e.preventDefault();
                
                const itemContainer = e.target.closest('.mini-cart-item');
                const itemId = itemContainer.getAttribute('data-id');
                const qtyElement = itemContainer.querySelector('.mini-cart-qty');
                let quantity = parseInt(qtyElement.textContent);
                
                if (e.target.classList.contains('increment')) {
                    quantity++;
                } else if (e.target.classList.contains('decrement') && quantity > 1) {
                    quantity--;
                }
                
                qtyElement.textContent = quantity;
                
                // Update cart via AJAX
                updateCartItem(itemId, quantity)
                    .then(response => {
                        if (response.success) {
                            updateCartCount(response.cart.total_quantity);
                            updateMiniCart(response.cart);
                        } else {
                            showToast(response.message, 'error');
                            // Reset quantity if error
                            getCart();
                        }
                    })
                    .catch(error => {
                        console.error('Error updating cart item:', error);
                        showToast('Error updating quantity', 'error');
                        // Reset quantity if error
                        getCart();
                    });
            }
            
            // Remove item
            if (e.target.closest('.remove-cart-item')) {
                e.preventDefault();
                
                const button = e.target.closest('.remove-cart-item');
                const itemId = button.getAttribute('data-id');
                
                removeCartItem(itemId)
                    .then(response => {
                        if (response.success) {
                            updateCartCount(response.cart.total_quantity);
                            updateMiniCart(response.cart);
                            showToast(response.message, 'success');
                        } else {
                            showToast(response.message, 'error');
                        }
                    })
                    .catch(error => {
                        console.error('Error removing cart item:', error);
                        showToast('Error removing item from cart', 'error');
                    });
            }
        });
    }

    /**
     * Update the cart count in header
     * @param {number} count - New cart count
     */
    function updateCartCount(count) {
        const cartCounters = document.querySelectorAll('.cart-count');
        
        cartCounters.forEach(counter => {
            counter.textContent = count;
            
            if (count > 0) {
                counter.classList.remove('hidden');
            } else {
                counter.classList.add('hidden');
            }
        });
    }

    /**
     * Update the mini cart contents
     * @param {Object} cartData - Cart data from API
     */
    function updateMiniCart(cartData) {
        const miniCartContainer = document.querySelector('.mini-cart-items');
        const miniCartSubtotal = document.querySelector('.mini-cart-subtotal');
        const miniCartTotal = document.querySelector('.mini-cart-total');
        const miniCartEmpty = document.querySelector('.mini-cart-empty');
        
        if (miniCartContainer && miniCartSubtotal && miniCartTotal) {
            if (cartData.items.length === 0) {
                // Cart is empty
                miniCartContainer.innerHTML = '';
                if (miniCartEmpty) {
                    miniCartEmpty.classList.remove('hidden');
                }
            } else {
                // Cart has items
                if (miniCartEmpty) {
                    miniCartEmpty.classList.add('hidden');
                }
                
                // Update items
                let html = '';
                
                cartData.items.forEach(item => {
                    html += `
                    <div class="mini-cart-item flex py-3 border-b border-gray-100" data-id="${item.id}">
                        <div class="w-20 h-20 flex-shrink-0 mr-4">
                            <img src="${item.image_path}" alt="${item.name}" class="w-full h-full object-cover rounded">
                        </div>
                        <div class="flex-1">
                            <h4 class="font-medium text-sm">${item.name}</h4>
                            ${item.variation_name ? `<p class="text-xs text-gray-500">${item.variation_name}</p>` : ''}
                            <div class="flex justify-between items-center mt-2">
                                <div class="flex items-center">
                                    <button class="mini-cart-qty-btn decrement w-6 h-6 flex items-center justify-center border rounded-l">-</button>
                                    <span class="mini-cart-qty w-8 h-6 flex items-center justify-center border-t border-b">${item.quantity}</span>
                                    <button class="mini-cart-qty-btn increment w-6 h-6 flex items-center justify-center border rounded-r">+</button>
                                </div>
                                <div class="text-sm">
                                    ${item.sale_price ? 
                                        `<span class="font-medium">${parseFloat(item.sale_price).toFixed(2)} €</span>` : 
                                        `<span class="font-medium">${parseFloat(item.price).toFixed(2)} €</span>`
                                    }
                                </div>
                                <button class="remove-cart-item text-gray-400 hover:text-red-500 transition-colors" data-id="${item.id}">
                                    <i class="ri-delete-bin-line"></i>
                                </button>
                            </div>
                        </div>
                    </div>`;
                });
                
                miniCartContainer.innerHTML = html;
                
                // Update totals
                miniCartSubtotal.textContent = parseFloat(cartData.subtotal).toFixed(2) + ' €';
                miniCartTotal.textContent = parseFloat(cartData.total).toFixed(2) + ' €';
            }
        }
    }

    /**
     * Wishlist Functionality - FIXED
     */
    function initWishlist() {
        // Toggle wishlist for product cards
        const wishlistButtons = document.querySelectorAll('.add-to-wishlist');
        
        // Remove previous event listeners by cloning and replacing
        wishlistButtons.forEach(button => {
            const newButton = button.cloneNode(true);
            button.parentNode.replaceChild(newButton, button);
            
            newButton.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation(); // IMPORTANT: Stop the event from bubbling up
                
                // If parent is a link, prevent navigation
                const parentLink = this.closest('a');
                if (parentLink) {
                    e.preventDefault();
                    e.stopPropagation();
                }
                
                if (!isLoggedIn) {
                    // Redirect to login if not logged in
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
                
                // Get CSRF token
                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                
                // Toggle wishlist state visually
                const heartIcon = this.querySelector('i');
                const isAlreadyInWishlist = heartIcon.classList.contains('ri-heart-fill');
                
                if (isAlreadyInWishlist) {
                    heartIcon.classList.remove('ri-heart-fill');
                    heartIcon.classList.add('ri-heart-line');
                } else {
                    heartIcon.classList.remove('ri-heart-line');
                    heartIcon.classList.add('ri-heart-fill');
                }
                
                // Make API call to toggle wishlist
                fetch('/wishlist/toggle', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({ product_id: productId })
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.status === 'success') {
                        // Update wishlist count
                        // Update wishlist count and show message
        updateWishlistCount();
                        
        // Show success message
        if (data.action === 'added') {
            showToast('Product added to wishlist', 'success');
        } else {
            showToast('Product removed from wishlist', 'info');
        }
    } else {
        // If error, revert visual change
        if (isAlreadyInWishlist) {
            heartIcon.classList.remove('ri-heart-line');
            heartIcon.classList.add('ri-heart-fill');
        } else {
            heartIcon.classList.remove('ri-heart-fill');
            heartIcon.classList.add('ri-heart-line');
        }
        
        showToast(data.message || 'Error updating wishlist', 'error');
    }
})
.catch(error => {
    console.error('Error toggling wishlist:', error);
    
    // If error, revert visual change
    if (isAlreadyInWishlist) {
        heartIcon.classList.remove('ri-heart-line');
        heartIcon.classList.add('ri-heart-fill');
    } else {
        heartIcon.classList.remove('ri-heart-fill');
        heartIcon.classList.add('ri-heart-line');
    }
    
    showToast('Error updating wishlist', 'error');
});
            });
        });
        
        // Handle wishlist buttons on product showcase sections (similar to home page)
        const productWishlistButtons = document.querySelectorAll('.product-wishlist-btn');
        
        productWishlistButtons.forEach(button => {
            const newButton = button.cloneNode(true);
            button.parentNode.replaceChild(newButton, button);
            
            newButton.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation(); // IMPORTANT: Stop the event from bubbling up
                
                // If parent is a link, prevent navigation
                const parentLink = this.closest('a');
                if (parentLink) {
                    e.preventDefault();
                    e.stopPropagation();
                }
                
                if (!isLoggedIn) {
                    // Redirect to login if not logged in
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
                
                // Get CSRF token
                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                
                // Toggle wishlist state visually
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
                
                // Make API call to toggle wishlist
                fetch('/wishlist/toggle', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({ product_id: productId })
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.status === 'success') {
                        // Update wishlist count
                        updateWishlistCount();
                        
                        // Show success message
                        if (data.action === 'added') {
                            showToast('Product added to wishlist', 'success');
                        } else {
                            showToast('Product removed from wishlist', 'info');
                        }
                    } else {
                        // If error, revert visual change
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
                    console.error('Error toggling wishlist:', error);
                    
                    // If error, revert visual change
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
                    
                    showToast('Error updating wishlist', 'error');
                });
            });
        });
        
        // Check if products are in wishlist and pre-fill heart icons
        if (isLoggedIn) {
            loadWishlistStatus();
        }
    }

    /**
     * Load wishlist status for products
     */
    function loadWishlistStatus() {
        const productCards = document.querySelectorAll('.product-card');
        if (productCards.length === 0) return;
        
        const productIds = [];
        productCards.forEach(card => {
            const productId = card.getAttribute('data-product-id');
            if (productId) productIds.push(productId);
        });
        
        if (productIds.length === 0) return;
        
        // Get CSRF token
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        
        fetch('/wishlist/check-products', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({ product_ids: productIds })
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
                        // Handle regular wishlist buttons
                        const wishlistBtn = card.querySelector('.add-to-wishlist');
                        if (wishlistBtn) {
                            const heartIcon = wishlistBtn.querySelector('i');
                            if (heartIcon) {
                                heartIcon.classList.remove('ri-heart-line');
                                heartIcon.classList.add('ri-heart-fill');
                            }
                        }
                        
                        // Handle showcase wishlist buttons
                        const showcaseWishlistBtn = card.querySelector('.product-wishlist-btn');
                        if (showcaseWishlistBtn) {
                            showcaseWishlistBtn.classList.add('active');
                            const heartIcon = showcaseWishlistBtn.querySelector('.wishlist-icon');
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

    /**
     * Update wishlist count in header
     */
    function updateWishlistCount() {
        if (!isLoggedIn) return;
        
        fetch('/wishlist/count', {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
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

    /**
     * Load More Products
     */
    function initLoadMore() {
        const loadMoreBtn = document.getElementById('load-more-btn');
        const loadMoreContainer = document.getElementById('load-more-container');
        const productsContainer = document.getElementById('products-container');
        
        if (loadMoreBtn && productsContainer) {
            // Remove any existing event listener
            const newLoadMoreBtn = loadMoreBtn.cloneNode(true);
            if (loadMoreBtn.parentNode) {
                loadMoreBtn.parentNode.replaceChild(newLoadMoreBtn, loadMoreBtn);
            }
            
            let page = 2; // Start from page 2 since page 1 is already loaded
            
            newLoadMoreBtn.addEventListener('click', function(e) {
                e.preventDefault();
                
                // Show loading state
                const loadingIcon = this.querySelector('i');
                if (loadingIcon) {
                    loadingIcon.classList.remove('hidden');
                }
                this.disabled = true;
                
                // Get current URL and add page parameter
                const currentUrl = new URL(window.location.href);
                const params = new URLSearchParams(currentUrl.search);
                params.set('page', page);
                
                // Add AJAX header
                fetch(`${currentUrl.pathname}?${params.toString()}`, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.text();
                })
                .then(html => {
                    // Create a temporary div to parse the HTML
                    const temp = document.createElement('div');
                    temp.innerHTML = html;
                    
                    // Get products from the response
                    const newProducts = temp.querySelectorAll('.product-card');
                    
                    if (newProducts.length > 0) {
                        // Append new products to container
                        newProducts.forEach(product => {
                            productsContainer.appendChild(product);
                        });
                        
                        // Increment page
                        page++;
                        
                        // Check if there are more products
                        const hasMorePages = temp.querySelector('#load-more-container:not(.hidden)');
                        if (!hasMorePages) {
                            loadMoreContainer.classList.add('hidden');
                        }
                        
                        // Initialize functionality for new products
                        initAddToCart();
                        initWishlist();
                    } else {
                        loadMoreContainer.classList.add('hidden');
                    }
                    
                    // Hide loading state
                    if (loadingIcon) {
                        loadingIcon.classList.add('hidden');
                    }
                    this.disabled = false;
                })
                .catch(error => {
                    console.error('Error loading more products:', error);
                    showToast('Error loading more products', 'error');
                    
                    // Hide loading state
                    if (loadingIcon) {
                        loadingIcon.classList.add('hidden');
                    }
                    this.disabled = false;
                });
            });
        }
    }

    /**
     * Quick View Functionality
     */
    function initQuickView() {
        const quickViewButtons = document.querySelectorAll('.quick-view-btn');
        
        // Clone and replace to remove previous event listeners
        quickViewButtons.forEach(button => {
            const newButton = button.cloneNode(true);
            button.parentNode.replaceChild(newButton, button);
            
            newButton.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                
                const productId = this.getAttribute('data-product-id');
                if (!productId) {
                    console.error('Product ID not found');
                    return;
                }
                
                // Get CSRF token
                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                
                // Show loading state
                showQuickViewModal(true);
                
                // Fetch product details
                fetch('/shop/quick-view', {
                    method: 'GET',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({ product_id: productId })
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.text();
                })
                .then(html => {
                    // Update modal content and show it
                    const modalContent = document.querySelector('.quick-view-content');
                    if (modalContent) {
                        modalContent.innerHTML = html;
                    }
                    
                    // Initialize functionality within modal
                    initQuickViewFunctionality();
                })
                .catch(error => {
                    console.error('Error loading quick view:', error);
                    hideQuickViewModal();
                    showToast('Error loading product details', 'error');
                });
            });
        });
    }

    /**
     * Show/hide quick view modal
     * @param {boolean} loading - Whether to show loading state
     */
    function showQuickViewModal(loading = false) {
        const quickViewModal = document.getElementById('quick-view-modal');
        const quickViewContent = document.querySelector('.quick-view-content');
        
        if (!quickViewModal) return;
        
        quickViewModal.classList.remove('hidden');
        
        if (loading && quickViewContent) {
            quickViewContent.innerHTML = `
                <div class="flex items-center justify-center p-12">
                    <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-primary"></div>
                </div>
            `;
        }
        
        setTimeout(() => {
            quickViewModal.classList.add('opacity-100');
            document.body.classList.add('overflow-hidden');
        }, 50);
    }
    
    /**
     * Hide quick view modal
     */
    function hideQuickViewModal() {
        const quickViewModal = document.getElementById('quick-view-modal');
        
        if (!quickViewModal) return;
        
        quickViewModal.classList.remove('opacity-100');
        
        setTimeout(() => {
            quickViewModal.classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
        }, 300);
    }
    
    /**
     * Initialize functionality within quick view modal
     */
    function initQuickViewFunctionality() {
        // Close button
        const closeModalBtn = document.querySelector('.quick-view-close');
        if (closeModalBtn) {
            closeModalBtn.addEventListener('click', function(e) {
                e.preventDefault();
                hideQuickViewModal();
            });
        }
        
        // Quantity buttons
        const quantityInput = document.querySelector('.quick-view-quantity');
        const decrementBtn = document.querySelector('.decrement-quantity');
        const incrementBtn = document.querySelector('.increment-quantity');
        
        if (quantityInput && decrementBtn && incrementBtn) {
            decrementBtn.addEventListener('click', function(e) {
                e.preventDefault();
                let value = parseInt(quantityInput.value);
                if (value > 1) {
                    quantityInput.value = value - 1;
                }
            });
            
            incrementBtn.addEventListener('click', function(e) {
                e.preventDefault();
                let value = parseInt(quantityInput.value);
                const max = parseInt(quantityInput.getAttribute('max') || 99);
                if (value < max) {
                    quantityInput.value = value + 1;
                }
            });
        }
        
        // Variation selector
        const variationSelects = document.querySelectorAll('.variation-select');
        variationSelects.forEach(select => {
            select.addEventListener('change', function() {
                const variationId = this.value;
                const priceElement = document.querySelector('.variation-price');
                const stockElement = document.querySelector('.variation-stock');
                const addToCartBtn = document.querySelector('.quick-view-add-to-cart');
                
                if (variationId) {
                    // Get variation data from data attribute
                    const variations = JSON.parse(this.getAttribute('data-variations') || '{}');
                    const selectedVariation = variations[variationId];
                    
                    if (selectedVariation) {
                        // Update price
                        if (priceElement && selectedVariation.price) {
                            if (selectedVariation.sale_price) {
                                priceElement.innerHTML = `
                                    <span class="text-xl font-medium">${parseFloat(selectedVariation.sale_price).toFixed(2)} €</span>
                                    <span class="text-gray-500 line-through ml-2">${parseFloat(selectedVariation.price).toFixed(2)} €</span>
                                `;
                            } else {
                                priceElement.innerHTML = `
                                    <span class="text-xl font-medium">${parseFloat(selectedVariation.price).toFixed(2)} €</span>
                                `;
                            }
                        }
                        
                        // Update stock status
                        if (stockElement && typeof selectedVariation.in_stock !== 'undefined') {
                            if (selectedVariation.in_stock) {
                                stockElement.innerHTML = `
                                    <span class="text-green-500">In Stock</span>
                                    <span class="text-gray-600 ml-2">(${selectedVariation.quantity} available)</span>
                                `;
                                
                                // Update max quantity
                                if (quantityInput) {
                                    quantityInput.setAttribute('max', selectedVariation.quantity);
                                    if (parseInt(quantityInput.value) > selectedVariation.quantity) {
                                        quantityInput.value = selectedVariation.quantity;
                                    }
                                }
                                
                                // Enable add to cart button
                                if (addToCartBtn) {
                                    addToCartBtn.disabled = false;
                                }
                            } else {
                                stockElement.innerHTML = `<span class="text-red-500">Out of Stock</span>`;
                                
                                // Disable add to cart button
                                if (addToCartBtn) {
                                    addToCartBtn.disabled = true;
                                }
                            }
                        }
                        
                        // Update add to cart button data
                        if (addToCartBtn) {
                            addToCartBtn.setAttribute('data-variation-id', variationId);
                        }
                    }
                }
            });
        });
        
        // Quick view add to cart button
        const addToCartBtn = document.querySelector('.quick-view-add-to-cart');
        if (addToCartBtn) {
            addToCartBtn.addEventListener('click', function(e) {
                e.preventDefault();
                
                const productId = this.getAttribute('data-product-id');
                const variationId = this.getAttribute('data-variation-id');
                const quantityInput = document.querySelector('.quick-view-quantity');
                const quantity = quantityInput ? parseInt(quantityInput.value) : 1;
                
                if (!productId) {
                    console.error('Product ID not found');
                    return;
                }
                
                // Disable button and show loading state
                this.disabled = true;
                const originalText = this.innerHTML;
                this.innerHTML = '<i class="ri-loader-4-line animate-spin mr-2"></i> Adding...';
                
                addToCart(productId, quantity, variationId)
                    .then(response => {
                        // Re-enable button and restore text
                        this.disabled = false;
                        this.innerHTML = originalText;
                        
                        if (response.success) {
                            showToast(response.message, 'success');
                            updateCartCount(response.cart.total_quantity);
                            updateMiniCart(response.cart);
                            
                            // Hide modal after successful add
                            hideQuickViewModal();
                        } else {
                            showToast(response.message, 'error');
                        }
                    })
                    .catch(error => {
                        console.error('Error adding to cart:', error);
                        showToast('Error adding product to cart', 'error');
                        
                        // Re-enable button and restore text
                        this.disabled = false;
                        this.innerHTML = originalText;
                    });
            });
        }
        
        // Image gallery
        const thumbnails = document.querySelectorAll('.product-thumbnail');
        const mainImage = document.querySelector('.product-main-image img');
        
        if (thumbnails.length && mainImage) {
            thumbnails.forEach(thumb => {
                thumb.addEventListener('click', function(e) {
                    e.preventDefault();
                    const imageSrc = this.getAttribute('data-image');
                    
                    // Update main image
                    mainImage.src = imageSrc;
                    
                    // Update active thumbnail
                    thumbnails.forEach(t => t.classList.remove('border-primary'));
                    this.classList.add('border-primary');
                });
            });
        }
    }
    
    /**
     * Show toast notification
     * @param {string} message - Message to display
     * @param {string} type - Type of toast (success, error, info)
     */
    function showToast(message, type = 'info') {
        let toastContainer = document.querySelector('.toast-container');
        
        if (!toastContainer) {
            toastContainer = document.createElement('div');
            toastContainer.className = 'toast-container fixed bottom-4 right-4 z-50';
            document.body.appendChild(toastContainer);
        }
        
        const toast = document.createElement('div');
        toast.className = 'toast p-4 mb-3 rounded-lg shadow-lg flex items-center justify-between transition-all transform translate-y-2 opacity-0';
        
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
    
    /**
     * Get cart contents
     * Updates the mini cart display
     */
    function getCart() {
        fetch('/ajax/cart', {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                updateCartCount(data.cart.total_quantity);
                updateMiniCart(data.cart);
            }
        })
        .catch(error => {
            console.error('Error fetching cart:', error);
        });
    }
});

// Initialize on document load
document.addEventListener('DOMContentLoaded', function() {
    // Load cart data
    const miniCart = document.querySelector('.mini-cart');
    if (miniCart) {
        fetch('/ajax/cart', {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                // Update cart count in header
                const cartCounters = document.querySelectorAll('.cart-count');
                cartCounters.forEach(counter => {
                    counter.textContent = data.cart.total_quantity;
                    
                    if (data.cart.total_quantity > 0) {
                        counter.classList.remove('hidden');
                    } else {
                        counter.classList.add('hidden');
                    }
                });
                
                // Update mini cart contents
                const miniCartContainer = document.querySelector('.mini-cart-items');
                const miniCartSubtotal = document.querySelector('.mini-cart-subtotal');
                const miniCartTotal = document.querySelector('.mini-cart-total');
                const miniCartEmpty = document.querySelector('.mini-cart-empty');
                
                if (miniCartContainer && miniCartSubtotal && miniCartTotal) {
                    if (data.cart.items.length === 0) {
                        // Cart is empty
                        miniCartContainer.innerHTML = '';
                        if (miniCartEmpty) {
                            miniCartEmpty.classList.remove('hidden');
                        }
                    } else {
                        // Cart has items
                        if (miniCartEmpty) {
                            miniCartEmpty.classList.add('hidden');
                        }
                        
                        // Update items
                        let html = '';
                        
                        data.cart.items.forEach(item => {
                            html += `
                            <div class="mini-cart-item flex py-3 border-b border-gray-100" data-id="${item.id}">
                                <div class="w-20 h-20 flex-shrink-0 mr-4">
                                    <img src="${item.image_path}" alt="${item.name}" class="w-full h-full object-cover rounded">
                                </div>
                                <div class="flex-1">
                                    <h4 class="font-medium text-sm">${item.name}</h4>
                                    ${item.variation_name ? `<p class="text-xs text-gray-500">${item.variation_name}</p>` : ''}
                                    <div class="flex justify-between items-center mt-2">
                                        <div class="flex items-center">
                                            <button class="mini-cart-qty-btn decrement w-6 h-6 flex items-center justify-center border rounded-l">-</button>
                                            <span class="mini-cart-qty w-8 h-6 flex items-center justify-center border-t border-b">${item.quantity}</span>
                                            <button class="mini-cart-qty-btn increment w-6 h-6 flex items-center justify-center border rounded-r">+</button>
                                        </div>
                                        <div class="text-sm">
                                            ${item.sale_price ? 
                                                `<span class="font-medium">${parseFloat(item.sale_price).toFixed(2)} €</span>` : 
                                                `<span class="font-medium">${parseFloat(item.price).toFixed(2)} €</span>`
                                            }
                                        </div>
                                        <button class="remove-cart-item text-gray-400 hover:text-red-500 transition-colors" data-id="${item.id}">
                                            <i class="ri-delete-bin-line"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>`;
                        });
                        
                        miniCartContainer.innerHTML = html;
                        
                        // Update totals
                        miniCartSubtotal.textContent = parseFloat(data.cart.subtotal).toFixed(2) + ' €';
                        miniCartTotal.textContent = parseFloat(data.cart.total).toFixed(2) + ' €';
                    }
                }
            }
        })
        .catch(error => {
            console.error('Error fetching cart:', error);
        });
    }
    
    // Check for wishlist count if user is logged in
    if (document.body.classList.contains('logged-in')) {
        fetch('/wishlist/count', {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
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
        .catch(error => {
            console.error('Error fetching wishlist count:', error);
        });
    }
});