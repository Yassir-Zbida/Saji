/**
 * Update cart count in UI
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
 * Initialize cart buttons and their functionality
 */
function initCartButtons() {
    // Main add to cart button
    const addToCartBtn = document.getElementById('add-to-cart');
    if (addToCartBtn) {
        // Remove any existing event listeners
        const newBtn = addToCartBtn.cloneNode(true);
        if (addToCartBtn.parentNode) {
            addToCartBtn.parentNode.replaceChild(newBtn, addToCartBtn);
        }
        
        // Add new event listener
        newBtn.setAttribute('data-processing', 'false');
        newBtn.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            // Check if already processing
            if (this.getAttribute('data-processing') === 'true') {
                console.log('Already processing this button, ignoring click');
                return;
            }
            
            // Mark as processing
            this.setAttribute('data-processing', 'true');
            
            // Get product ID and quantity
            const productId = this.getAttribute('data-product-id');
            const quantity = document.getElementById('quantity') ? 
                             parseInt(document.getElementById('quantity').value) || 1 : 1;
            
            // Get selected attributes
            let attributes = {};
            
            // Get color if available
            const selectedColor = document.querySelector('input[name="color"]:checked');
            if (selectedColor) {
                attributes.color = selectedColor.value;
            }
            
            // Get size if available
            const selectedSize = document.querySelector('input[name="size"]:checked');
            if (selectedSize) {
                attributes.size = selectedSize.value;
            }
            
            // Show loading state
            const originalText = this.innerHTML;
            this.classList.add('opacity-75', 'cursor-not-allowed');
            this.innerHTML = '<i class="ri-loader-4-line animate-spin mr-2"></i> Adding...';
            this.disabled = true;
            
            // Add to cart
            addToCart(productId, quantity, attributes);
            
            // Reset button after a timeout regardless of API response
            setTimeout(() => {
                this.innerHTML = originalText;
                this.classList.remove('opacity-75', 'cursor-not-allowed');
                this.disabled = false;
                this.setAttribute('data-processing', 'false');
            }, 5000); // 5 second failsafe
        });
    }
    
    // Related products add to cart buttons
    const relatedAddToCartBtns = document.querySelectorAll('.product-card .add-to-cart-btn');
    relatedAddToCartBtns.forEach(btn => {
        // Remove any existing event listeners
        const newBtn = btn.cloneNode(true);
        if (btn.parentNode) {
            btn.parentNode.replaceChild(newBtn, btn);
        }
        
        // Add new event listener
        newBtn.setAttribute('data-processing', 'false');
        newBtn.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            // Check if already processing
            if (this.getAttribute('data-processing') === 'true') {
                console.log('Already processing this button, ignoring click');
                return;
            }
            
            // Mark as processing
            this.setAttribute('data-processing', 'true');
            
            // Get product ID from button or parent card
            let productId = this.getAttribute('data-product-id');
            if (!productId) {
                const productCard = this.closest('.product-card');
                if (productCard) {
                    productId = productCard.getAttribute('data-product-id');
                }
            }
            
            // Show loading state
            const originalText = this.innerHTML;
            this.classList.add('opacity-75', 'cursor-not-allowed');
            this.innerHTML = '<i class="ri-loader-4-line animate-spin mr-2"></i> Adding...';
            this.disabled = true;
            
            // Add to cart with default quantity 1
            addToCart(productId, 1, {});
            
            // Reset button after a timeout regardless of API response
            setTimeout(() => {
                this.innerHTML = originalText;
                this.classList.remove('opacity-75', 'cursor-not-allowed');
                this.disabled = false;
                this.setAttribute('data-processing', 'false');
            }, 5000); // 5 second failsafe
        });
    });
}

/**
 * Initialize wishlist buttons and their functionality
 */
function initWishlistButtons() {
    // Main wishlist button
    const wishlistBtn = document.getElementById('add-to-wishlist');
    if (wishlistBtn) {
        // Remove any existing event listeners
        const newBtn = wishlistBtn.cloneNode(true);
        if (wishlistBtn.parentNode) {
            wishlistBtn.parentNode.replaceChild(newBtn, wishlistBtn);
        }
        
        // Add new event listener
        newBtn.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            const productId = this.getAttribute('data-product-id');
            const icon = this.querySelector('.wishlist-icon');
            
            // Toggle wishlist status
            toggleWishlist(productId, icon);
        });
    }
    
    // Related products wishlist buttons
    const relatedWishlistBtns = document.querySelectorAll('.product-card .product-wishlist-btn');
    relatedWishlistBtns.forEach(btn => {
        // Remove any existing event listeners
        const newBtn = btn.cloneNode(true);
        if (btn.parentNode) {
            btn.parentNode.replaceChild(newBtn, btn);
        }
        
        // Add new event listener
        newBtn.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
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
            
            const icon = this.querySelector('.wishlist-icon');
            
            // Toggle wishlist status
            toggleWishlist(productId, icon);
        });
    });
}/**
 * Check the wishlist status for all products on the page
 * Note: This function is currently disabled as the route /wishlist/check is not available
 */
/*
function checkWishlistStatus() {
    // Get all product IDs from the page
    const productCards = document.querySelectorAll('[data-product-id]');
    const productIds = Array.from(productCards).map(card => card.getAttribute('data-product-id'));
    
    if (productIds.length === 0) return;
    
    console.log('Checking wishlist status for products:', productIds);
    
    // Determine the correct URL for wishlist check
    let wishlistCheckUrl = '/wishlist/check';
    
    try {
        // Some Laravel applications may have a route function
        if (typeof route === 'function') {
            wishlistCheckUrl = route('wishlist.check');
        }
    } catch(e) {
        console.log('Using default wishlist check URL:', wishlistCheckUrl);
    }
    
    // Make AJAX request with jQuery if available
    if (typeof $ !== 'undefined' && typeof $.ajax === 'function') {
        $.ajax({
            url: wishlistCheckUrl,
            type: 'POST',
            data: {
                product_ids: productIds
            },
            headers: {
                'X-CSRF-TOKEN': csrfToken
            },
            success: function(response) {
                updateWishlistIcons(response);
            },
            error: function(xhr, status, error) {
                console.error('Error checking wishlist status:', error);
            }
        });
    }
    // Otherwise use fetch API
    else {
        // Make AJAX request
        fetch(wishlistCheckUrl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                product_ids: productIds
            })
        })
        .then(response => {
            // If response is not ok but redirected, it might be to login page
            if (response.redirected) {
                // Don't redirect, just log it - wishlist status check should be non-blocking
                console.log('Redirected when checking wishlist status, user might not be logged in');
                return { success: false };
            }
            
            // Try to parse JSON response
            try {
                return response.json();
            } catch (e) {
                console.error('Error parsing wishlist status response:', e);
                return { success: false };
            }
        })
        .then(data => {
            updateWishlistIcons(data);
        })
        .catch(error => {
            console.error('Error checking wishlist status:', error);
        });
    }
}
*/

/**
 * Update wishlist icons based on server response
 */
/*
function updateWishlistIcons(data) {
    if (!data || !data.success) {
        return;
    }

    // Update wishlist icons for items in wishlist
    if (data.wishlist_items && Array.isArray(data.wishlist_items)) {
        data.wishlist_items.forEach(productId => {
            const wishlistIcons = document.querySelectorAll(`[data-product-id="${productId}"] .wishlist-icon`);
            wishlistIcons.forEach(icon => {
                icon.classList.remove('ri-heart-line');
                icon.classList.add('ri-heart-fill', 'text-red-500');
            });
        });
    }
}
*/

/**
 * Reset all add to cart buttons to their original state
 */
function resetAddToCartButtons() {
    // Reset main add to cart button
    const mainAddToCartBtn = document.getElementById('add-to-cart');
    if (mainAddToCartBtn) {
        mainAddToCartBtn.classList.remove('opacity-75', 'cursor-not-allowed');
        mainAddToCartBtn.innerHTML = '<i class="ri-shopping-bag-2-line mr-2"></i> Add to Cart';
    }
    
    // Reset related products add to cart buttons
    const relatedAddToCartBtns = document.querySelectorAll('.product-card .add-to-cart-btn');
    relatedAddToCartBtns.forEach(btn => {
        btn.classList.remove('opacity-75', 'cursor-not-allowed');
        btn.innerHTML = '<i class="ri-shopping-bag-2-line mr-2"></i> Add to Cart';
    });
}

/**
 * Reset wishlist icon to default state
 */
function resetWishlistIcon(icon) {
    if (!icon) return;
    
    icon.classList.remove('ri-loader-4-line', 'animate-spin', 'ri-heart-fill', 'text-red-500');
    icon.classList.add('ri-heart-line');
}

/**
 * Update the cart count in the header
 * @param {number} count - The new cart count
 */
function updateCartCount(count) {
    if (typeof count !== 'number') return;
    
    const cartCountElements = document.querySelectorAll('.cart-count');
    cartCountElements.forEach(element => {
        element.textContent = count;
        
        // If count is 0, hide the element, otherwise show it
        if (count === 0) {
            element.classList.add('hidden');
        } else {
            element.classList.remove('hidden');
        }
    });
    
    // Some sites might use a data attribute for the cart count
    const cartButtons = document.querySelectorAll('[data-cart-count]');
    cartButtons.forEach(button => {
        button.setAttribute('data-cart-count', count);
    });
}

/**
 * Update wishlist count in the UI
 */
function updateWishlistCount() {
    if (document.body.classList.contains('logged-in')) {
        fetch('/wishlist/count', {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
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
                
                // Update header wishlist count if the function exists
                if (typeof window.updateWishlistDisplay === 'function') {
                    window.updateWishlistDisplay(data.count);
                }
            }
        })
        .catch(error => console.error('Error updating wishlist count:', error));
    }
}

/**
 * Mark a product as being in the cart in localStorage
 */
function markProductAsInCart(productId) {
    const productsInCart = JSON.parse(localStorage.getItem('productsInCart') || '[]');
    if (!productsInCart.includes(productId)) {
        productsInCart.push(productId);
        localStorage.setItem('productsInCart', JSON.stringify(productsInCart));
    }
}

/**
 * Fetch cart count from server
 */
function fetchCartCount() {
    // Only proceed if we have cart counters on the page
    if (document.querySelectorAll('.cart-count').length === 0) return;
    
    fetch('/cart/ajax/get', {
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
        }
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.json();
    })
    .then(data => {
        if (data.success && data.cart) {
            // Update cart count in UI
            updateCartCount(data.cart.items.length);
            
            // Update cart display in header if the function exists
            if (typeof window.updateCartDisplay === 'function') {
                window.updateCartDisplay(data.cart);
            }
            
            // Update our local storage with accurate cart data
            const productsInCart = data.cart.items.map(item => item.product_id.toString());
            localStorage.setItem('productsInCart', JSON.stringify(productsInCart));
            
            // Update all add to cart buttons based on cart contents
            updateAddToCartButtons(productsInCart);
        }
    })
    .catch(error => {
        console.error('Error fetching cart data:', error);
    });
}

/**
 * Update all Add to Cart buttons based on which products are in the cart
 */
function updateAddToCartButtons(productsInCart) {
    const addToCartButtons = document.querySelectorAll('.add-to-cart-btn');
    
    addToCartButtons.forEach(btn => {
        // Get product ID from button or parent card
        let productId = btn.getAttribute('data-product-id');
        if (!productId) {
            const productCard = btn.closest('.product-card');
            if (productCard) {
                productId = productCard.getAttribute('data-product-id');
            }
        }
        
        if (productId && productsInCart.includes(productId.toString())) {
            // This product is in the cart, update button icon but not background
            const bagIcon = btn.querySelector('.ri-shopping-bag-2-line, .ri-shopping-bag-line');
            if (bagIcon) {
                bagIcon.classList.remove('ri-shopping-bag-2-line', 'ri-shopping-bag-line');
                bagIcon.classList.add('ri-checkbox-circle-line');
            }
        }
    });
}

/**
 * Initialize zoom functionality on the main product image
 */
function initImageZoom() {
    const mainImageContainer = document.getElementById('mainImage');
    if (!mainImageContainer) return;
    
    const mainImage = mainImageContainer.querySelector('img');
    if (!mainImage) return;
    
    // Create zoom container if it doesn't exist
    let zoomContainer = mainImageContainer.querySelector('.zoom-container');
    
    if (!zoomContainer) {
        // Create zoom container
        zoomContainer = document.createElement('div');
        zoomContainer.className = 'zoom-container absolute inset-0 bg-white bg-opacity-0 hidden';
        zoomContainer.style.zIndex = 10;
        
        // Create zoomed image
        const zoomedImage = document.createElement('img');
        zoomedImage.className = 'zoomed-image absolute';
        zoomedImage.src = mainImage.src;
        
        zoomContainer.appendChild(zoomedImage);
        mainImageContainer.appendChild(zoomContainer);
    }
    
    // Handle mouse events
    mainImageContainer.addEventListener('mouseenter', function() {
        zoomContainer.classList.remove('hidden');
        
        // Update zoomed image source to match current main image
        const zoomedImage = zoomContainer.querySelector('.zoomed-image');
        if (zoomedImage) {
            zoomedImage.src = mainImage.src;
        }
    });
    
    mainImageContainer.addEventListener('mouseleave', function() {
        zoomContainer.classList.add('hidden');
    });
    
    mainImageContainer.addEventListener('mousemove', function(e) {
        // Get container dimensions
        const rect = mainImageContainer.getBoundingClientRect();
        
        // Calculate position ratio (0 to 1)
        const x = (e.clientX - rect.left) / rect.width;
        const y = (e.clientY - rect.top) / rect.height;
        
        // Move the zoomed image
        const zoomedImage = zoomContainer.querySelector('.zoomed-image');
        if (zoomedImage) {
            zoomedImage.style.transform = `translate(-${x * 50}%, -${y * 50}%) scale(2)`;
        }
    });
}

/**
 * Show toast notification
 */
// Toast container reference
let toastContainer = null;

/**
 * Shows a toast notification with title and message
 * @param {string} title - The title of the notification
 * @param {string} message - The message content
 * @param {string} type - Type of notification ('success', 'error', 'warning', 'info')
 */
function showNotification(title, message, type = "info") {
  console.log(`${type.toUpperCase()}: ${title} - ${message}`);
  
  // Create toast container if it doesn't exist
  if (!toastContainer) {
    toastContainer = document.createElement("div");
    toastContainer.className = "toast-container fixed bottom-4 right-4 z-50 w-72";
    document.body.appendChild(toastContainer);
  }
  
  // Check if a toast with the same title and message already exists
  const existingToasts = toastContainer.querySelectorAll(".toast");
  for (let i = 0; i < existingToasts.length; i++) {
    const toastTitle = existingToasts[i].querySelector(".toast-title")?.textContent;
    const toastMessage = existingToasts[i].querySelector(".toast-message")?.textContent;
    if (toastTitle === title && toastMessage === message) {
      return; // Don't show duplicate toast
    }
  }
  
  // Create toast element
  const toast = document.createElement("div");
  toast.className = 
    "toast p-4 mb-3 rounded-lg shadow-lg flex flex-col transition-all transform translate-y-2 opacity-0";
  
  // Add color based on type
  switch (type) {
    case "success":
      toast.classList.add("bg-green-500", "text-white");
      break;
    case "error":
      toast.classList.add("bg-red-500", "text-white");
      break;
    case "warning":
      toast.classList.add("bg-yellow-500", "text-white");
      break;
    default:
      toast.classList.add("bg-blue-500", "text-white");
  }
  
  // Set toast content with title and message
  toast.innerHTML = `
    <div class="flex items-center justify-between w-full">
      <span class="font-bold toast-title">${title}</span>
      <button class="ml-4 focus:outline-none" aria-label="Close">
        <i class="ri-close-line"></i>
      </button>
    </div>
    <span class="toast-message mt-1">${message}</span>
  `;
  
  // Add close button functionality
  toast.querySelector("button").addEventListener("click", function() {
    toast.classList.add("translate-y-2", "opacity-0");
    setTimeout(() => {
      toast.remove();
    }, 300);
  });
  
  // Add to container
  toastContainer.appendChild(toast);
  
  // Animate in
  setTimeout(() => {
    toast.classList.remove("translate-y-2", "opacity-0");
    toast.classList.add("translate-y-0", "opacity-100");
  }, 10);
  
  // Auto close after 4 seconds
  setTimeout(() => {
    if (toast.parentNode) {
      toast.classList.add("translate-y-2", "opacity-0");
      setTimeout(() => {
        if (toast.parentNode) {
          toast.remove();
        }
      }, 300);
    }
  }, 4000);
}

// Usage examples:
// showNotification("Success", "Your changes have been saved!", "success");
// showNotification("Error", "Something went wrong. Please try again.", "error");
// showNotification("Warning", "Your session will expire soon", "warning");
// showNotification("Info", "New features are available", "info");

/**
 * Submit a product review
 * @param {HTMLFormElement} form - The review form element
 */
function submitReview(form) {
    if (!form) {
        console.error('No form provided for review submission');
        return false;
    }
    
    // Show loading state
    const submitButton = form.querySelector('button[type="submit"]');
    const originalButtonText = submitButton ? submitButton.innerHTML : '';
    
    if (submitButton) {
        submitButton.disabled = true;
        submitButton.innerHTML = '<i class="ri-loader-4-line animate-spin mr-2"></i> Submitting...';
    }
    
    // Simply submit the form normally since Ajax request is not working
    setTimeout(() => {
        form.submit();
    }, 500);
    
    // Prevent default form submission
    return false;
}/**
 * product-detail.js
 * Handles all functionality for the product detail page
 */

// Set up CSRF token for all AJAX requests
let csrfToken;
try {
    csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
} catch (error) {
    console.warn('CSRF token not found. Some functionality may not work correctly.');
}

/**
 * Initialize all product detail page functionality
 */
document.addEventListener('DOMContentLoaded', function() {
    console.log('Product detail JS loaded at:', new Date().toISOString());
    
    // Image Gallery Functionality
    initImageGallery();
    
    // Quantity Selector
    initQuantitySelector();
    
    // Tab Functionality
    initTabSystem();
    
    // Cart & Wishlist Buttons
    initCartButtons();
    initWishlistButtons();
    
    // Check products in cart
    fetchCartCount();
    
    // Check wishlist status
    loadWishlistStatus();
    
    // Update wishlist count
    updateWishlistCount();
    
    // Initialize image zoom if available
    initImageZoom();
});

/**
 * Initialize product image gallery with thumbnails
 */
function initImageGallery() {
    const mainImage = document.getElementById('mainImage');
    const thumbnails = document.querySelectorAll('.thumbnail-item');
    
    if (!mainImage || thumbnails.length === 0) return;
    
    thumbnails.forEach(thumb => {
        thumb.addEventListener('click', function() {
            // Update main image
            const imageUrl = this.getAttribute('data-image');
            mainImage.querySelector('img').src = imageUrl;
            
            // Update active state
            thumbnails.forEach(t => t.classList.remove('ring-2', 'ring-primary'));
            this.classList.add('ring-2', 'ring-primary');
        });
    });
}

/**
 * Initialize quantity input with increment/decrement buttons
 */
function initQuantitySelector() {
    const quantityInput = document.getElementById('quantity');
    const minusBtn = document.querySelector('.quantity-btn.minus');
    const plusBtn = document.querySelector('.quantity-btn.plus');
    
    if (!quantityInput || !minusBtn || !plusBtn) return;
    
    minusBtn.addEventListener('click', function() {
        const currentValue = parseInt(quantityInput.value);
        if (currentValue > 1) {
            quantityInput.value = currentValue - 1;
        }
    });
    
    plusBtn.addEventListener('click', function() {
        const currentValue = parseInt(quantityInput.value);
        quantityInput.value = currentValue + 1;
    });
    
    // Ensure we handle direct input correctly
    quantityInput.addEventListener('change', function() {
        let value = parseInt(this.value);
        if (isNaN(value) || value < 1) {
            this.value = 1;
        }
    });
}

/**
 * Initialize tab system for product details
 */
function initTabSystem() {
    const tabButtons = document.querySelectorAll('.tab-btn');
    const tabPanes = document.querySelectorAll('.tab-pane');
    
    if (tabButtons.length === 0 || tabPanes.length === 0) return;
    
    tabButtons.forEach(button => {
        button.addEventListener('click', function() {
            const tabId = this.getAttribute('data-tab');
            
            // Update active tab button
            tabButtons.forEach(btn => {
                btn.classList.remove('active', 'border-primary', 'text-primary');
                btn.classList.add('text-gray-500', 'border-transparent');
            });
            
            this.classList.add('active', 'border-primary', 'text-primary');
            this.classList.remove('text-gray-500', 'border-transparent');
            
            // Show active tab content
            tabPanes.forEach(pane => {
                pane.classList.add('hidden');
                pane.classList.remove('active');
            });
            
            const activeTab = document.getElementById(tabId + '-tab');
            if (activeTab) {
                activeTab.classList.remove('hidden');
                activeTab.classList.add('active');
            }
        });
    });
}

/**
 * Initialize all add to cart buttons
 */
function initCartButtons() {
    // Main add to cart button
    const addToCartBtn = document.getElementById('add-to-cart');
    if (addToCartBtn) {
        addToCartBtn.addEventListener('click', function() {
            const productId = this.getAttribute('data-product-id');
            const quantity = document.getElementById('quantity').value || 1;
            
            // Get selected attributes if available
            let attributes = {};
            
            // Get color if available
            const selectedColor = document.querySelector('input[name="color"]:checked');
            if (selectedColor) {
                attributes.color = selectedColor.value;
            }
            
            // Get size if available
            const selectedSize = document.querySelector('input[name="size"]:checked');
            if (selectedSize) {
                attributes.size = selectedSize.value;
            }
            
            // Show loading state
            this.classList.add('opacity-75', 'cursor-not-allowed');
            this.innerHTML = '<i class="ri-loader-4-line animate-spin mr-2"></i> Adding...';
            
            // Add to cart
            addToCart(productId, quantity, attributes);
        });
    }
    
    // Related products add to cart buttons
    const relatedAddToCartBtns = document.querySelectorAll('.product-card .add-to-cart-btn');
    relatedAddToCartBtns.forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const productId = this.getAttribute('data-product-id');
            
            // Show loading state
            this.classList.add('opacity-75', 'cursor-not-allowed');
            this.innerHTML = '<i class="ri-loader-4-line animate-spin mr-2"></i> Adding...';
            
            // Add to cart with default quantity 1
            addToCart(productId, 1, {});
        });
    });
}

/**
 * Initialize all wishlist buttons
 */
function initWishlistButtons() {
    // Main wishlist button
    const wishlistBtn = document.getElementById('add-to-wishlist');
    if (wishlistBtn) {
        wishlistBtn.addEventListener('click', function() {
            const productId = this.getAttribute('data-product-id');
            const icon = this.querySelector('.wishlist-icon');
            
            // Toggle wishlist status
            toggleWishlist(productId, icon);
        });
    }
    
    // Related products wishlist buttons
    const relatedWishlistBtns = document.querySelectorAll('.product-card .product-wishlist-btn');
    relatedWishlistBtns.forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const productId = this.closest('.product-card').getAttribute('data-product-id');
            const icon = this.querySelector('.wishlist-icon');
            
            // Toggle wishlist status
            toggleWishlist(productId, icon);
        });
    });
}

/**
 * Add a product to the cart via AJAX
 * @param {number} productId - The ID of the product to add
 * @param {number} quantity - The quantity to add
 * @param {Object} attributes - Additional product attributes (color, size, etc.)
 */
function addToCart(productId, quantity, attributes = {}) {
    // Validate inputs
    if (!productId) {
        console.error('No product ID provided');
        showNotification('Error', 'Invalid product information', 'error');
        resetAddToCartButtons();
        return;
    }
    
    // Ensure quantity is a number and at least 1
    quantity = parseInt(quantity) || 1;
    if (quantity < 1) quantity = 1;
    
    console.log('Adding to cart:', { product_id: productId, quantity });
    
    const csrfToken = document.querySelector('meta[name="csrf-token"]');
    if (!csrfToken) {
        console.error('CSRF token not found');
        showNotification('Error', 'CSRF token not found', 'error');
        resetAddToCartButtons();
        return;
    }
    
    // Create form data for the request
    const formData = new FormData();
    formData.append('product_id', productId);
    formData.append('quantity', quantity);
    
    // Add attributes if they exist
    if (Object.keys(attributes).length > 0) {
        for (const [key, value] of Object.entries(attributes)) {
            formData.append(`attributes[${key}]`, value);
        }
    }
    
    // Use the correct endpoint for adding to cart - matching your shop.js file
    fetch('/cart/ajax/add', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': csrfToken.getAttribute('content'),
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: formData
    })
    .then(response => {
        if (!response.ok) {
            console.error('Server response:', response.status, response.statusText);
            throw new Error(`HTTP error! Status: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        console.log('Add to cart response:', data);
        
        // Handle successful response
        if (data.success) {
            // Show success notification
            showNotification('Success', data.message || 'Product added to cart', 'success');
            
            // Mark this product as in cart in localStorage
            markProductAsInCart(productId);
            
            // Update cart display if the function exists in the global scope
            if (data.cart && typeof window.updateCartDisplay === 'function') {
                window.updateCartDisplay(data.cart);
            } else {
                // If cart count is not in the response, try to fetch it separately
                fetchCartCount();
            }
            
            // Open cart sidebar if the function exists
            if (typeof window.toggleCartSidebar === 'function') {
                window.toggleCartSidebar();
            }
        } else {
            // Show error notification
            showNotification('Error', data.message || 'Failed to add product to cart', 'error');
        }
        
        // Reset add to cart button regardless of outcome
        resetAddToCartButtons();
    })
    .catch(error => {
        console.error('Error adding to cart:', error);
        showNotification('Error', 'Something went wrong. Please try again.', 'error');
        resetAddToCartButtons();
    });
}

/**
 * Handle cart response from server
 */
function handleCartResponse(data) {
    // Handle successful response
    if (data && data.success) {
        // Show success notification
        showNotification('Success', data.message || 'Product added to cart', 'success');
        
        // Update cart count in header if it exists
        if (data.cart_count !== undefined) {
            updateCartCount(data.cart_count);
        }
        
        // Reset add to cart button
        resetAddToCartButtons();
        
        // Update mini cart if it exists
        if (typeof updateMiniCart === 'function') {
            updateMiniCart();
        }
    } else {
        // Show error notification
        showNotification('Error', (data && data.message) || 'Failed to add product to cart', 'error');
        resetAddToCartButtons();
    }
}

/**
 * Reset all add to cart buttons to their original state
 */
function resetAddToCartButtons() {
    // Reset main add to cart button
    const mainAddToCartBtn = document.getElementById('add-to-cart');
    if (mainAddToCartBtn) {
        mainAddToCartBtn.classList.remove('opacity-75', 'cursor-not-allowed');
        mainAddToCartBtn.innerHTML = '<i class="ri-shopping-bag-2-line mr-2"></i> Add to Cart';
    }
    
    // Reset related products add to cart buttons
    const relatedAddToCartBtns = document.querySelectorAll('.product-card .add-to-cart-btn');
    relatedAddToCartBtns.forEach(btn => {
        btn.classList.remove('opacity-75', 'cursor-not-allowed');
        btn.innerHTML = '<i class="ri-shopping-bag-2-line mr-2"></i> Add to Cart';
    });
}

/**
 * Toggle wishlist status for a product
 * @param {number} productId - The ID of the product to toggle
 * @param {HTMLElement} icon - The wishlist icon element to update
 */
function toggleWishlist(productId, icon) {
    // Validate inputs
    if (!productId) {
        console.error('No product ID provided for wishlist toggle');
        return;
    }
    
    // Check if icon exists
    if (!icon) {
        console.error('No icon element provided for wishlist toggle');
        return;
    }
    
    // Redirect to login if user is not logged in
    if (!document.body.classList.contains('logged-in')) {
        sessionStorage.setItem('redirectAfterLogin', window.location.href);
        window.location.href = '/login';
        return;
    }
    
    // Get CSRF token
    const csrfToken = document.querySelector('meta[name="csrf-token"]');
    if (!csrfToken) {
        console.error('CSRF token not found');
        showNotification('Error', 'CSRF token not found', 'error');
        return;
    }
    
    // Show loading state
    icon.classList.remove('ri-heart-line', 'ri-heart-fill', 'text-red-500');
    icon.classList.add('ri-loader-4-line', 'animate-spin');
    
    // Determine if it's currently in wishlist
    const isCurrentlyActive = icon.classList.contains('ri-heart-fill');
    
    // Toggle active state immediately for better UX
    if (isCurrentlyActive) {
        // If it was active and we're removing from wishlist
        icon.classList.remove('ri-heart-fill', 'text-red-500');
        icon.classList.add('ri-heart-line');
    } else {
        // If it was not active and we're adding to wishlist
        icon.classList.remove('ri-heart-line');
        icon.classList.add('ri-heart-fill', 'text-red-500');
    }
    
    // Send request to server using the correct endpoint from your shop.js
    fetch('/wishlist/toggle', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken.getAttribute('content'),
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: JSON.stringify({
            product_id: productId
        })
    })
    .then(response => response.json())
    .then(data => {
        console.log('Wishlist toggle response:', data);
        
        // Remove loading animation
        icon.classList.remove('ri-loader-4-line', 'animate-spin');
        
        if (data.status === 'success') {
            if (data.action === 'added') {
                // Product was added to wishlist
                icon.classList.add('ri-heart-fill', 'text-red-500');
                showNotification('Success', 'Product added to wishlist', 'success');
            } else {
                // Product was removed from wishlist
                icon.classList.add('ri-heart-line');
                icon.classList.remove('ri-heart-fill', 'text-red-500');
                showNotification('Success', 'Product removed from wishlist', 'info');
            }
            
            // Update wishlist count
            updateWishlistCount();
        } else {
            // Error handling - revert icon to original state
            resetWishlistIcon(icon);
            showNotification('Error', data.message || 'Failed to update wishlist', 'error');
        }
    })
    .catch(error => {
        console.error('Error toggling wishlist:', error);
        resetWishlistIcon(icon);
        showNotification('Error', 'Something went wrong. Please try again.', 'error');
    });
}

/**
 * Handle wishlist response from server
 */
function handleWishlistResponse(data, icon) {
    // Remove loading state from icon
    icon.classList.remove('ri-loader-4-line', 'animate-spin');
    
    if (data && data.success) {
        if (data.added) {
            // Product was added to wishlist
            icon.classList.add('ri-heart-fill', 'text-red-500');
            showNotification('Success', data.message || 'Product added to wishlist', 'success');
        } else {
            // Product was removed from wishlist
            icon.classList.add('ri-heart-line');
            showNotification('Success', data.message || 'Product removed from wishlist', 'success');
        }
        
        // Update wishlist count in header if it exists
        if (data.wishlist_count !== undefined) {
            updateWishlistCount(data.wishlist_count);
        }
    } else {
        // Error handling
        resetWishlistIcon(icon);
        showNotification('Error', (data && data.message) || 'Failed to update wishlist', 'error');
    }
}

/**
 * Reset wishlist icon to default state
 */
function resetWishlistIcon(icon) {
    icon.classList.remove('ri-loader-4-line', 'animate-spin', 'ri-heart-fill', 'text-red-500');
    icon.classList.add('ri-heart-line');
}

/**
 * Update the cart count in the header
 * @param {number} count - The new cart count
 */
function updateCartCount(count) {
    const cartCountElements = document.querySelectorAll('.cart-count');
    cartCountElements.forEach(element => {
        element.textContent = count;
        
        // If count is 0, hide the element, otherwise show it
        if (count === 0) {
            element.classList.add('hidden');
        } else {
            element.classList.remove('hidden');
        }
    });
}

/**
 * Update the wishlist count in the header
 * @param {number} count - The new wishlist count
 */
function updateWishlistCount(count) {
    const wishlistCountElements = document.querySelectorAll('.wishlist-count');
    wishlistCountElements.forEach(element => {
        element.textContent = count;
        
        // If count is 0, hide the element, otherwise show it
        if (count === 0) {
            element.classList.add('hidden');
        } else {
            element.classList.remove('hidden');
        }
    });
}

/**
 * Show a notification to the user
 * @param {string} title - The notification title
 * @param {string} message - The notification message
 * @param {string} type - The notification type (success, error, info, warning)
 */
function showNotification(title, message, type = 'info') {
    // Check if notification container exists, create if not
    let notificationContainer = document.getElementById('notification-container');
    
    if (!notificationContainer) {
        notificationContainer = document.createElement('div');
        notificationContainer.id = 'notification-container';
        notificationContainer.className = 'fixed top-4 right-4 z-50 w-72 space-y-4';
        document.body.appendChild(notificationContainer);
    }
    
    // Create notification element
    const notification = document.createElement('div');
    notification.className = 'notification rounded-lg shadow-lg p-4 transform transition-all duration-300 translate-x-full';
    
    // Add color based on type
    switch (type) {
        case 'success':
            notification.classList.add('bg-green-50', 'border-l-4', 'border-green-500', 'text-green-700');
            break;
        case 'error':
            notification.classList.add('bg-red-50', 'border-l-4', 'border-red-500', 'text-red-700');
            break;
        case 'warning':
            notification.classList.add('bg-yellow-50', 'border-l-4', 'border-yellow-500', 'text-yellow-700');
            break;
        default:
            notification.classList.add('bg-blue-50', 'border-l-4', 'border-blue-500', 'text-blue-700');
    }
    
    // Set notification content
    notification.innerHTML = `
        <div class="flex justify-between items-start">
            <div>
                <h3 class="font-medium">${title}</h3>
                <p class="text-sm mt-1">${message}</p>
            </div>
            <button class="close-notification text-gray-400 hover:text-gray-600">
                <i class="ri-close-line text-lg"></i>
            </button>
        </div>
    `;
    
    // Add to container
    notificationContainer.appendChild(notification);
    
    // Animate in
    setTimeout(() => {
        notification.classList.remove('translate-x-full');
    }, 10);
    
    // Set up close button
    const closeButton = notification.querySelector('.close-notification');
    closeButton.addEventListener('click', () => {
        closeNotification(notification);
    });
    
    // Auto close after 5 seconds
    setTimeout(() => {
        closeNotification(notification);
    }, 5000);
}

/**
 * Close a notification
 * @param {HTMLElement} notification - The notification element to close
 */
function closeNotification(notification) {
    // Animate out
    notification.classList.add('translate-x-full', 'opacity-0');
    
    // Remove after animation
    setTimeout(() => {
        if (notification.parentNode) {
            notification.parentNode.removeChild(notification);
        }
    }, 300);
}

/**
 * Initialize zoom functionality on the main product image
 */
function initImageZoom() {
    const mainImageContainer = document.getElementById('mainImage');
    if (!mainImageContainer) return;
    
    const mainImage = mainImageContainer.querySelector('img');
    if (!mainImage) return;
    
    // Create zoom container
    const zoomContainer = document.createElement('div');
    zoomContainer.className = 'zoom-container absolute inset-0 bg-white bg-opacity-90 hidden';
    zoomContainer.style.zIndex = 10;
    
    // Create zoomed image
    const zoomedImage = document.createElement('img');
    zoomedImage.className = 'zoomed-image absolute';
    zoomedImage.src = mainImage.src;
    
    zoomContainer.appendChild(zoomedImage);
    mainImageContainer.appendChild(zoomContainer);
    
    // Handle mouse events
    mainImageContainer.addEventListener('mouseenter', function() {
        zoomContainer.classList.remove('hidden');
    });
    
    mainImageContainer.addEventListener('mouseleave', function() {
        zoomContainer.classList.add('hidden');
    });
    
    mainImageContainer.addEventListener('mousemove', function(e) {
        // Get container dimensions
        const rect = mainImageContainer.getBoundingClientRect();
        
        // Calculate position ratio (0 to 1)
        const x = (e.clientX - rect.left) / rect.width;
        const y = (e.clientY - rect.top) / rect.height;
        
        // Move the zoomed image
        zoomedImage.style.transform = `translate(-${x * 50}%, -${y * 50}%) scale(2)`;
    });
}

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    // Check if we're on product detail page
    const mainImage = document.getElementById('mainImage');
    if (mainImage) {
        initImageZoom();
    }
    
    // Check for existing wishlist status and update icons accordingly
    checkWishlistStatus();
});

/**
 * Check the wishlist status for all products on the page
 */
function checkWishlistStatus() {
    // Get all product IDs from the page
    const productCards = document.querySelectorAll('[data-product-id]');
    const productIds = Array.from(productCards).map(card => card.getAttribute('data-product-id'));
    
    if (productIds.length === 0) return;
    
    console.log('Checking wishlist status for products:', productIds);
    
    // Determine the correct URL for wishlist check
    let wishlistCheckUrl = '/wishlist/check';
    
    try {
        // Some Laravel applications may have a route function
        if (typeof route === 'function') {
            wishlistCheckUrl = route('wishlist.check');
        }
    } catch(e) {
        console.log('Using default wishlist check URL:', wishlistCheckUrl);
    }
    
    // Make AJAX request with jQuery if available
    if (typeof $ !== 'undefined' && typeof $.ajax === 'function') {
        $.ajax({
            url: wishlistCheckUrl,
            type: 'POST',
            data: {
                product_ids: productIds
            },
            headers: {
                'X-CSRF-TOKEN': csrfToken
            },
            success: function(response) {
                updateWishlistIcons(response);
            },
            error: function(xhr, status, error) {
                console.error('Error checking wishlist status:', error);
            }
        });
    }
    // Otherwise use fetch API
    else {
        // Make AJAX request
        fetch(wishlistCheckUrl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                product_ids: productIds
            })
        })
        .then(response => {
            // If response is not ok but redirected, it might be to login page
            if (response.redirected) {
                // Don't redirect, just log it - wishlist status check should be non-blocking
                console.log('Redirected when checking wishlist status, user might not be logged in');
                return { success: false };
            }
            
            // Try to parse JSON response
            try {
                return response.json();
            } catch (e) {
                console.error('Error parsing wishlist status response:', e);
                return { success: false };
            }
        })
        .then(data => {
            updateWishlistIcons(data);
        })
        .catch(error => {
            console.error('Error checking wishlist status:', error);
        });
    }
}

/**
 * Update wishlist icons based on server response
 */
function updateWishlistIcons(data) {
    if (!data || !data.success) {
        return;
    }

    // Update wishlist icons for items in wishlist
    if (data.wishlist_items && Array.isArray(data.wishlist_items)) {
        data.wishlist_items.forEach(productId => {
            const wishlistIcons = document.querySelectorAll(`[data-product-id="${productId}"] .wishlist-icon`);
            wishlistIcons.forEach(icon => {
                icon.classList.remove('ri-heart-line');
                icon.classList.add('ri-heart-fill', 'text-red-500');
            });
        });
    }
}

/**
 * Reset all add to cart buttons to their original state
 */
function resetAddToCartButtons() {
    // Reset main add to cart button
    const mainAddToCartBtn = document.getElementById('add-to-cart');
    if (mainAddToCartBtn) {
        mainAddToCartBtn.classList.remove('opacity-75', 'cursor-not-allowed');
        mainAddToCartBtn.innerHTML = '<i class="ri-shopping-bag-2-line mr-2"></i> Add to Cart';
    }
    
    // Reset related products add to cart buttons
    const relatedAddToCartBtns = document.querySelectorAll('.product-card .add-to-cart-btn');
    relatedAddToCartBtns.forEach(btn => {
        btn.classList.remove('opacity-75', 'cursor-not-allowed');
        btn.innerHTML = '<i class="ri-shopping-bag-2-line mr-2"></i> Add to Cart';
    });
}

/**
 * Update the cart count in the header
 * @param {number} count - The new cart count
 */
function updateCartCount(count) {
    if (typeof count !== 'number') return;
    
    const cartCountElements = document.querySelectorAll('.cart-count');
    cartCountElements.forEach(element => {
        element.textContent = count;
        
        // If count is 0, hide the element, otherwise show it
        if (count === 0) {
            element.classList.add('hidden');
        } else {
            element.classList.remove('hidden');
        }
    });
    
    // Some sites might use a data attribute for the cart count
    const cartButtons = document.querySelectorAll('[data-cart-count]');
    cartButtons.forEach(button => {
        button.setAttribute('data-cart-count', count);
    });
}

/**
 * Update the wishlist count in the header
 * @param {number} count - The new wishlist count
 */
function updateWishlistCount(count) {
    if (typeof count !== 'number') return;
    
    const wishlistCountElements = document.querySelectorAll('.wishlist-count');
    wishlistCountElements.forEach(element => {
        element.textContent = count;
        
        // If count is 0, hide the element, otherwise show it
        if (count === 0) {
            element.classList.add('hidden');
        } else {
            element.classList.remove('hidden');
        }
    });
    
    // Some sites might use a data attribute for the wishlist count
    const wishlistButtons = document.querySelectorAll('[data-wishlist-count]');
    wishlistButtons.forEach(button => {
        button.setAttribute('data-wishlist-count', count);
    });
}

/**
 * Initialize zoom functionality on the main product image
 */
function initImageZoom() {
    const mainImageContainer = document.getElementById('mainImage');
    if (!mainImageContainer) return;
    
    const mainImage = mainImageContainer.querySelector('img');
    if (!mainImage) return;
    
    // Create zoom container if it doesn't exist
    let zoomContainer = mainImageContainer.querySelector('.zoom-container');
    
    if (!zoomContainer) {
        // Create zoom container
        zoomContainer = document.createElement('div');
        zoomContainer.className = 'zoom-container absolute inset-0 bg-white bg-opacity-0 hidden';
        zoomContainer.style.zIndex = 10;
        
        // Create zoomed image
        const zoomedImage = document.createElement('img');
        zoomedImage.className = 'zoomed-image absolute';
        zoomedImage.src = mainImage.src;
        
        zoomContainer.appendChild(zoomedImage);
        mainImageContainer.appendChild(zoomContainer);
    }
    
    // Handle mouse events
    mainImageContainer.addEventListener('mouseenter', function() {
        zoomContainer.classList.remove('hidden');
        
        // Update zoomed image source to match current main image
        const zoomedImage = zoomContainer.querySelector('.zoomed-image');
        if (zoomedImage) {
            zoomedImage.src = mainImage.src;
        }
    });
    
    mainImageContainer.addEventListener('mouseleave', function() {
        zoomContainer.classList.add('hidden');
    });
    
    mainImageContainer.addEventListener('mousemove', function(e) {
        // Get container dimensions
        const rect = mainImageContainer.getBoundingClientRect();
        
        // Calculate position ratio (0 to 1)
        const x = (e.clientX - rect.left) / rect.width;
        const y = (e.clientY - rect.top) / rect.height;
        
        // Move the zoomed image
        const zoomedImage = zoomContainer.querySelector('.zoomed-image');
        if (zoomedImage) {
            zoomedImage.style.transform = `translate(-${x * 50}%, -${y * 50}%) scale(2)`;
        }
    });
}

/**
 * Show a notification to the user
 * @param {string} title - The notification title
 * @param {string} message - The notification message
 * @param {string} type - The notification type (success, error, info, warning)
 */
function showNotification(title, message, type = 'info') {
    console.log(`${type.toUpperCase()}: ${title} - ${message}`);
    
    // Check if notification container exists, create if not
    let notificationContainer = document.getElementById('notification-container');
    
    if (!notificationContainer) {
        notificationContainer = document.createElement('div');
        notificationContainer.id = 'notification-container';
        notificationContainer.className = 'fixed top-4 right-4 z-50 w-72 space-y-4';
        document.body.appendChild(notificationContainer);
    }
    
    // Create notification element
    const notification = document.createElement('div');
    notification.className = 'notification rounded-lg shadow-lg p-4 transform transition-all duration-300 translate-x-full';
    
    // Add color based on type
    switch (type) {
        case 'success':
            notification.classList.add('bg-green-50', 'border-l-4', 'border-green-500', 'text-green-700');
            break;
        case 'error':
            notification.classList.add('bg-red-50', 'border-l-4', 'border-red-500', 'text-red-700');
            break;
        case 'warning':
            notification.classList.add('bg-yellow-50', 'border-l-4', 'border-yellow-500', 'text-yellow-700');
            break;
        default:
            notification.classList.add('bg-blue-50', 'border-l-4', 'border-blue-500', 'text-blue-700');
    }
    
    // Set notification content
    notification.innerHTML = `
        <div class="flex justify-between items-start">
            <div>
                <h3 class="font-medium">${title}</h3>
                <p class="text-sm mt-1">${message}</p>
            </div>
            <button class="close-notification text-gray-400 hover:text-gray-600">
                <i class="ri-close-line text-lg"></i>
            </button>
        </div>
    `;
    
    // Add to container
    notificationContainer.appendChild(notification);
    
    // Animate in
    setTimeout(() => {
        notification.classList.remove('translate-x-full');
    }, 10);
    
    // Set up close button
    const closeButton = notification.querySelector('.close-notification');
    if (closeButton) {
        closeButton.addEventListener('click', () => {
            closeNotification(notification);
        });
    }
    
    // Auto close after 5 seconds
    setTimeout(() => {
        closeNotification(notification);
    }, 5000);
}

/**
 * Close a notification
 * @param {HTMLElement} notification - The notification element to close
 */
function closeNotification(notification) {
    if (!notification) return;
    
    // Animate out
    notification.classList.add('translate-x-full', 'opacity-0');
    
    // Remove after animation
    setTimeout(() => {
        if (notification.parentNode) {
            notification.parentNode.removeChild(notification);
        }
    }, 300);
}

/**
 * Submit a product review
 * @param {HTMLFormElement} form - The review form element
 */
function submitReview(form) {
    if (!form) {
        console.error('No form provided for review submission');
        return false;
    }
    
    // Show loading state
    const submitButton = form.querySelector('button[type="submit"]');
    const originalButtonText = submitButton ? submitButton.innerHTML : '';
    
    if (submitButton) {
        submitButton.disabled = true;
        submitButton.innerHTML = '<i class="ri-loader-4-line animate-spin mr-2"></i> Submitting...';
    }
    
    // Get form data
    const formData = new FormData(form);
    
    // Determine correct URL for the form submission
    let formUrl = form.action;
    
    // Make AJAX request with jQuery if available
    if (typeof $ !== 'undefined' && typeof $.ajax === 'function') {
        $.ajax({
            url: formUrl,
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            headers: {
                'X-CSRF-TOKEN': csrfToken
            },
            success: function(response) {
                handleReviewResponse(response, form, submitButton, originalButtonText);
            },
            error: function(xhr, status, error) {
                console.error('Error submitting review:', error);
                showNotification('Error', 'Something went wrong. Please try again.', 'error');
                
                // Reset button
                if (submitButton) {
                    submitButton.disabled = false;
                    submitButton.innerHTML = originalButtonText;
                }
            }
        });
    }
    // Otherwise use fetch API
    else {
        // Make AJAX request
        fetch(formUrl, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken
            },
            body: formData
        })
        .then(response => {
            // If we got a redirect, it might be to login page
            if (response.redirected) {
                window.location.href = response.url;
                return;
            }
            
            // Try to parse JSON response
            try {
                return response.json();
            } catch (e) {
                // If not JSON, might be HTML response
                return { success: response.ok };
            }
        })
        .then(data => {
            handleReviewResponse(data, form, submitButton, originalButtonText);
        })
        .catch(error => {
            console.error('Error submitting review:', error);
            showNotification('Error', 'Something went wrong. Please try again.', 'error');
            
            // Reset button
            if (submitButton) {
                submitButton.disabled = false;
                submitButton.innerHTML = originalButtonText;
            }
        });
    }
    
    // Prevent form submission
    return false;
}

/**
 * Handle review submission response
 */
function handleReviewResponse(data, form, submitButton, originalButtonText) {
    if (data && data.success) {
        // Show success notification
        showNotification('Success', data.message || 'Review submitted successfully', 'success');
        
        // Reset form
        if (form) {
            form.reset();
        }
        
        // Reload page to show new review
        setTimeout(() => {
            window.location.reload();
        }, 1500);
    } else {
        // Show error notification
        showNotification('Error', (data && data.message) || 'Failed to submit review', 'error');
        
        // Reset button
        if (submitButton) {
            submitButton.disabled = false;
            submitButton.innerHTML = originalButtonText;
        }
    }
}

/**
 * Submit a product review
 * @param {HTMLFormElement} form - The review form element
 */
function submitReview(form) {
    // Show loading state
    const submitButton = form.querySelector('button[type="submit"]');
    const originalButtonText = submitButton.innerHTML;
    submitButton.disabled = true;
    submitButton.innerHTML = '<i class="ri-loader-4-line animate-spin mr-2"></i> Submitting...';
    
    // Get form data
    const formData = new FormData(form);
    
    // Make AJAX request
    fetch(form.action, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json'
        },
        body: formData
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            // Show success notification
            showNotification('Success', data.message || 'Review submitted successfully', 'success');
            
            // Reset form
            form.reset();
            
            // Reload page to show new review
            setTimeout(() => {
                window.location.reload();
            }, 1500);
        } else {
            // Show error notification
            showNotification('Error', data.message || 'Failed to submit review', 'error');
            
            // Reset button
            submitButton.disabled = false;
            submitButton.innerHTML = originalButtonText;
        }
    })
    .catch(error => {
        console.error('Error submitting review:', error);
        showNotification('Error', 'Something went wrong. Please try again.', 'error');
        
        // Reset button
        submitButton.disabled = false;
        submitButton.innerHTML = originalButtonText;
    });
    
    // Prevent form submission
    return false;
}