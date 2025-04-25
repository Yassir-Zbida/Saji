/**
 * Admin dashboard JavaScript file
 */

// Initialize CSRF token for all AJAX requests
document.addEventListener('DOMContentLoaded', function() {
    // Set up CSRF token for AJAX requests
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    
    // Configure all AJAX requests to include CSRF token
    window.axios = require('axios');
    window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
    window.axios.defaults.headers.common['X-CSRF-TOKEN'] = csrfToken;

    // Initialize mobile menu functionality
    initMobileMenu();

    // Initialize sidebar dropdown menus
    initSidebarDropdowns();

    // Initialize any notifications
    initNotifications();

    // Initialize any tooltips
    initTooltips();
});

/**
 * Initialize mobile menu functionality
 */
function initMobileMenu() {
    const mobileMenuButton = document.getElementById('mobile-menu-button');
    const sidebar = document.getElementById('sidebar');

    if (mobileMenuButton && sidebar) {
        // Toggle sidebar on mobile
        mobileMenuButton.addEventListener('click', function() {
            sidebar.classList.toggle('-translate-x-full');
            sidebar.classList.toggle('translate-x-0');
        });

        // Close sidebar when clicking outside on mobile
        document.addEventListener('click', function(event) {
            const isClickInsideSidebar = sidebar.contains(event.target);
            const isClickOnMenuButton = mobileMenuButton.contains(event.target);
            
            if (!isClickInsideSidebar && !isClickOnMenuButton && window.innerWidth < 1024) {
                sidebar.classList.add('-translate-x-full');
                sidebar.classList.remove('translate-x-0');
            }
        });
    }
}

/**
 * Initialize sidebar dropdown menus
 */
function initSidebarDropdowns() {
    const dropdownToggles = document.querySelectorAll('.sidebar-dropdown-toggle');

    dropdownToggles.forEach(toggle => {
        toggle.addEventListener('click', function() {
            const parent = this.closest('li');
            const submenu = parent.querySelector('.sidebar-submenu');
            const arrow = this.querySelector('.ri-arrow-right-s-line');

            // Toggle submenu
            if (submenu) {
                submenu.classList.toggle('hidden');
                arrow.classList.toggle('transform');
                arrow.classList.toggle('rotate-90');
            }
        });
    });
}

/**
 * Initialize notifications
 */
function initNotifications() {
    // Fade out alert messages after 5 seconds
    const alerts = document.querySelectorAll('.alert-message');
    
    alerts.forEach(alert => {
        setTimeout(() => {
            alert.classList.add('opacity-0');
            setTimeout(() => {
                alert.remove();
            }, 500);
        }, 5000);

        // Close button functionality
        const closeBtn = alert.querySelector('.alert-close-btn');
        if (closeBtn) {
            closeBtn.addEventListener('click', () => {
                alert.classList.add('opacity-0');
                setTimeout(() => {
                    alert.remove();
                }, 500);
            });
        }
    });
}

/**
 * Initialize tooltips
 */
function initTooltips() {
    const tooltipTriggers = document.querySelectorAll('[data-tooltip]');
    
    tooltipTriggers.forEach(trigger => {
        trigger.addEventListener('mouseenter', function() {
            const tooltipText = this.getAttribute('data-tooltip');
            
            const tooltip = document.createElement('div');
            tooltip.classList.add('tooltip', 'absolute', 'bg-gray-800', 'text-white', 'text-xs', 'py-1', 'px-2', 'rounded', 'opacity-0', 'transition-opacity', 'z-50');
            tooltip.textContent = tooltipText;
            
            document.body.appendChild(tooltip);
            
            const triggerRect = this.getBoundingClientRect();
            tooltip.style.top = `${triggerRect.top - tooltip.offsetHeight - 5 + window.scrollY}px`;
            tooltip.style.left = `${triggerRect.left + (triggerRect.width / 2) - (tooltip.offsetWidth / 2) + window.scrollX}px`;
            
            setTimeout(() => {
                tooltip.classList.replace('opacity-0', 'opacity-100');
            }, 10);
            
            this.addEventListener('mouseleave', function onMouseLeave() {
                tooltip.classList.replace('opacity-100', 'opacity-0');
                
                setTimeout(() => {
                    document.body.removeChild(tooltip);
                }, 200);
                
                this.removeEventListener('mouseleave', onMouseLeave);
            });
        });
    });
}

/**
 * Dashboard specific functionality
 */
// Fetch dashboard summary data
function fetchDashboardSummary() {
    axios.get('/admin/dashboard/summary')
        .then(response => {
            updateDashboardCounters(response.data);
        })
        .catch(error => {
            console.error('Error fetching dashboard summary:', error);
        });
}

// Update dashboard counter elements
function updateDashboardCounters(data) {
    // Update counters with new data
    if (data.totalProducts) {
        document.getElementById('total-products-count').textContent = data.totalProducts;
    }
    if (data.totalOrders) {
        document.getElementById('total-orders-count').textContent = data.totalOrders;
    }
    if (data.totalCustomers) {
        document.getElementById('total-customers-count').textContent = data.totalCustomers;
    }
    if (data.openTickets) {
        document.getElementById('open-tickets-count').textContent = data.openTickets;
    }
}

/**
 * Product management functions
 */
// Toggle product status (active/inactive)
function toggleProductStatus(productId, currentStatus) {
    const newStatus = currentStatus === 'active' ? 'inactive' : 'active';
    
    axios.patch(`/admin/products/${productId}/status`, {
        status: newStatus
    })
    .then(response => {
        if (response.data.success) {
            // Update the UI to reflect the new status
            const statusBadge = document.querySelector(`#product-${productId} .status-badge`);
            if (statusBadge) {
                statusBadge.textContent = newStatus === 'active' ? 'Active' : 'Inactive';
                statusBadge.classList.remove('bg-green-100', 'text-green-800', 'bg-gray-100', 'text-gray-800');
                statusBadge.classList.add(newStatus === 'active' ? 'bg-green-100' : 'bg-gray-100');
                statusBadge.classList.add(newStatus === 'active' ? 'text-green-800' : 'text-gray-800');
            }
            
            // Show success message
            showNotification('Product status updated successfully', 'success');
        }
    })
    .catch(error => {
        console.error('Error updating product status:', error);
        showNotification('Failed to update product status', 'error');
    });
}

/**
 * Order management functions
 */
// Update order status
function updateOrderStatus(orderId, status) {
    axios.patch(`/admin/orders/${orderId}/status`, {
        status: status
    })
    .then(response => {
        if (response.data.success) {
            // Update the UI to reflect the new status
            const statusBadge = document.querySelector(`#order-${orderId} .status-badge`);
            if (statusBadge) {
                statusBadge.textContent = status.charAt(0).toUpperCase() + status.slice(1);
                
                // Remove all current status classes
                statusBadge.classList.remove(
                    'bg-yellow-100', 'text-yellow-800',
                    'bg-blue-100', 'text-blue-800',
                    'bg-green-100', 'text-green-800',
                    'bg-red-100', 'text-red-800',
                    'bg-gray-100', 'text-gray-800'
                );
                
                // Add new status classes
                switch (status) {
                    case 'pending':
                        statusBadge.classList.add('bg-yellow-100', 'text-yellow-800');
                        break;
                    case 'processing':
                        statusBadge.classList.add('bg-blue-100', 'text-blue-800');
                        break;
                    case 'completed':
                        statusBadge.classList.add('bg-green-100', 'text-green-800');
                        break;
                    case 'cancelled':
                        statusBadge.classList.add('bg-red-100', 'text-red-800');
                        break;
                    default:
                        statusBadge.classList.add('bg-gray-100', 'text-gray-800');
                }
            }
            
            // Show success message
            showNotification('Order status updated successfully', 'success');
        }
    })
    .catch(error => {
        console.error('Error updating order status:', error);
        showNotification('Failed to update order status', 'error');
    });
}

/**
 * Utility functions
 */
// Show notification/toast message
function showNotification(message, type = 'info') {
    const notification = document.createElement('div');
    notification.classList.add(
        'fixed', 'right-4', 'bottom-4', 'p-4', 'rounded-lg', 'shadow-lg', 
        'transition-opacity', 'duration-500', 'opacity-0', 'z-50',
        'flex', 'items-center'
    );
    
    // Set notification color based on type
    switch (type) {
        case 'success':
            notification.classList.add('bg-green-100', 'text-green-800', 'border-l-4', 'border-green-500');
            notification.innerHTML = '<i class="ri-checkbox-circle-line text-xl mr-3"></i>';
            break;
        case 'error':
            notification.classList.add('bg-red-100', 'text-red-800', 'border-l-4', 'border-red-500');
            notification.innerHTML = '<i class="ri-error-warning-line text-xl mr-3"></i>';
            break;
        case 'warning':
            notification.classList.add('bg-yellow-100', 'text-yellow-800', 'border-l-4', 'border-yellow-500');
            notification.innerHTML = '<i class="ri-alert-line text-xl mr-3"></i>';
            break;
        default:
            notification.classList.add('bg-blue-100', 'text-blue-800', 'border-l-4', 'border-blue-500');
            notification.innerHTML = '<i class="ri-information-line text-xl mr-3"></i>';
    }
    
    // Add message
    notification.innerHTML += `<div>${message}</div>`;
    
    // Add close button
    const closeButton = document.createElement('button');
    closeButton.classList.add('ml-auto', 'text-gray-500', 'hover:text-gray-700');
    closeButton.innerHTML = '<i class="ri-close-line"></i>';
    closeButton.addEventListener('click', () => {
        notification.classList.replace('opacity-100', 'opacity-0');
        setTimeout(() => {
            document.body.removeChild(notification);
        }, 500);
    });
    notification.appendChild(closeButton);
    
    // Add to the DOM
    document.body.appendChild(notification);
    
    // Show notification
    setTimeout(() => {
        notification.classList.replace('opacity-0', 'opacity-100');
    }, 10);
    
    // Auto-hide after 5 seconds
    setTimeout(() => {
        notification.classList.replace('opacity-100', 'opacity-0');
        setTimeout(() => {
            if (document.body.contains(notification)) {
                document.body.removeChild(notification);
            }
        }, 500);
    }, 5000);
}