// Add this to a dedicated JS file or inline in your Blade view within a script tag
console.log('Admin Orders JS Loaded');
document.addEventListener('DOMContentLoaded', function() {
    // Initialize Alpine.js component
    window.ordersData = function() {
        return {
            orders: [],
            stats: {
                totalOrders: 0,
                pendingOrders: 0,
                completedOrders: 0,
                totalRevenue: 0,
                orderGrowth: 0
            },
            filters: {
                status: '',
                payment_status: '',
                date_from: '',
                date_to: '',
                search: '',
                user_id: ''
            },
            pagination: {
                current_page: 1,
                per_page: 15,
                total: 0,
                last_page: 1
            },
            isLoading: true,
            showFilters: false,
            
            init() {
                this.fetchOrders();
                
                // Check URL params for filters
                const urlParams = new URLSearchParams(window.location.search);
                this.filters = {
                    status: urlParams.get('status') || '',
                    payment_status: urlParams.get('payment_status') || '',
                    date_from: urlParams.get('date_from') || '',
                    date_to: urlParams.get('date_to') || '',
                    search: urlParams.get('search') || '',
                    user_id: urlParams.get('user_id') || ''
                };
                
                this.showFilters = Object.values(this.filters).some(val => val !== '');
            },
            
            get ordersCount() {
                return this.orders.length;
            },
            
            get currentPage() {
                return this.pagination.current_page;
            },
            
            set currentPage(page) {
                this.pagination.current_page = page;
            },
            
            get lastPage() {
                return this.pagination.last_page;
            },
            
            get perPage() {
                return this.pagination.per_page;
            },
            
            get totalOrders() {
                return this.pagination.total;
            },
            
            get paginationPages() {
                const pages = [];
                const maxPagesToShow = 5;
                
                if (this.lastPage <= maxPagesToShow) {
                    for (let i = 1; i <= this.lastPage; i++) {
                        pages.push(i);
                    }
                } else {
                    // Always show first page
                    pages.push(1);
                    
                    // Calculate start and end pages to show
                    let startPage = Math.max(2, this.currentPage - 1);
                    let endPage = Math.min(this.lastPage - 1, this.currentPage + 1);
                    
                    // Add ellipsis if needed
                    if (startPage > 2) {
                        pages.push('...');
                    }
                    
                    // Add pages
                    for (let i = startPage; i <= endPage; i++) {
                        pages.push(i);
                    }
                    
                    // Add ellipsis if needed
                    if (endPage < this.lastPage - 1) {
                        pages.push('...');
                    }
                    
                    // Always show last page
                    pages.push(this.lastPage);
                }
                
                return pages;
            },
            
            fetchOrders() {
                this.isLoading = true;
                
                // Build the query string
                const queryParams = new URLSearchParams();
                queryParams.append('page', this.currentPage);
                
                for (const [key, value] of Object.entries(this.filters)) {
                    if (value) {
                        queryParams.append(key, value);
                    }
                }
                
                fetch(`/admin/orders/data?${queryParams.toString()}`)
                    .then(response => response.json())
                    .then(data => {
                        this.orders = data.orders.data;
                        this.pagination = {
                            current_page: data.orders.current_page,
                            per_page: data.orders.per_page,
                            total: data.orders.total,
                            last_page: data.orders.last_page
                        };
                        this.stats = data.stats;
                        this.isLoading = false;
                    })
                    .catch(error => {
                        console.error('Error fetching orders:', error);
                        this.isLoading = false;
                    });
            },
            
            applyFilters() {
                this.currentPage = 1;
                this.fetchOrders();
                this.updateURLParams();
            },
            
            resetFilters() {
                this.filters = {
                    status: '',
                    payment_status: '',
                    user_id: '',
                    date_from: '',
                    date_to: '',
                    search: ''
                };
                this.currentPage = 1;
                this.fetchOrders();
                this.updateURLParams();
            },
            
            updateURLParams() {
                const queryParams = new URLSearchParams();
                
                for (const [key, value] of Object.entries(this.filters)) {
                    if (value) {
                        queryParams.append(key, value);
                    }
                }
                
                const newUrl = window.location.pathname + (queryParams.toString() ? `?${queryParams.toString()}` : '');
                window.history.pushState({}, '', newUrl);
            },
            
            nextPage() {
                if (this.currentPage < this.lastPage) {
                    this.currentPage++;
                    this.fetchOrders();
                }
            },
            
            prevPage() {
                if (this.currentPage > 1) {
                    this.currentPage--;
                    this.fetchOrders();
                }
            },
            
            goToPage(page) {
                if (page !== '...' && page !== this.currentPage) {
                    this.currentPage = page;
                    this.fetchOrders();
                }
            },
            
            formatDate(dateString) {
                const date = new Date(dateString);
                const options = { month: 'short', day: 'numeric', year: 'numeric' };
                return date.toLocaleDateString('en-US', options);
            },
            
            formatTime(dateString) {
                const date = new Date(dateString);
                return date.toLocaleTimeString('en-US', { hour: 'numeric', minute: 'numeric', hour12: true });
            },
            
            formatNumber(number) {
                return parseFloat(number).toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ",");
            },
            
            capitalizeFirst(string) {
                if (!string) return '';
                return string.charAt(0).toUpperCase() + string.slice(1);
            },
            
            getStatusClasses(status) {
                const classes = {
                    completed: 'bg-green-50 text-green-700 border border-green-200',
                    processing: 'bg-blue-50 text-blue-700 border border-blue-200',
                    shipped: 'bg-indigo-50 text-indigo-700 border border-indigo-200',
                    cancelled: 'bg-red-50 text-red-700 border border-red-200',
                    pending: 'bg-yellow-50 text-yellow-700 border border-yellow-200',
                    delivered: 'bg-emerald-50 text-emerald-700 border border-emerald-200',
                    default: 'bg-gray-50 text-gray-700 border border-gray-200'
                };
                
                return classes[status?.toLowerCase()] || classes.default;
            },
            
            getStatusIcon(status) {
                const icons = {
                    completed: 'ri-check-line',
                    processing: 'ri-loader-2-line',
                    shipped: 'ri-truck-line',
                    cancelled: 'ri-close-line',
                    pending: 'ri-time-line',
                    delivered: 'ri-checkbox-circle-line',
                    default: 'ri-information-line'
                };
                
                return icons[status?.toLowerCase()] || icons.default;
            },
            
            getPaymentClasses(paymentStatus) {
                const classes = {
                    paid: 'bg-green-50 text-green-700 border border-green-200',
                    pending: 'bg-yellow-50 text-yellow-700 border border-yellow-200',
                    failed: 'bg-red-50 text-red-700 border border-red-200',
                    refunded: 'bg-purple-50 text-purple-700 border border-purple-200',
                    default: 'bg-gray-50 text-gray-700 border border-gray-200'
                };
                
                return classes[paymentStatus?.toLowerCase()] || classes.default;
            },
            
            getPaymentIcon(paymentStatus) {
                const icons = {
                    paid: 'ri-bank-card-line',
                    pending: 'ri-time-line',
                    failed: 'ri-close-circle-line',
                    refunded: 'ri-refund-line',
                    default: 'ri-information-line'
                };
                
                return icons[paymentStatus?.toLowerCase()] || icons.default;
            },
            
            updateOrderStatus(orderId, newStatus) {
                fetch(`/admin/orders/${orderId}/status`, {
                    method: 'PATCH',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        status: newStatus,
                        notify: true
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Update the order in the list
                        const index = this.orders.findIndex(o => o.id === orderId);
                        if (index !== -1) {
                            this.orders[index].status = newStatus;
                        }
                        
                        // Show success notification
                        this.showNotification('Success', data.message, 'success');
                        
                        // Refresh stats
                        this.fetchOrders();
                    } else {
                        this.showNotification('Error', data.message, 'error');
                    }
                })
                .catch(error => {
                    console.error('Error updating order status:', error);
                    this.showNotification('Error', 'Failed to update order status', 'error');
                });
            },
            
            updatePaymentStatus(orderId, newStatus) {
                fetch(`/admin/orders/${orderId}/payment`, {
                    method: 'PATCH',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        payment_status: newStatus,
                        notify: true
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Update the order in the list
                        const index = this.orders.findIndex(o => o.id === orderId);
                        if (index !== -1) {
                            this.orders[index].payment_status = newStatus;
                        }
                        
                        // Show success notification
                        this.showNotification('Success', data.message, 'success');
                        
                        // Refresh stats
                        this.fetchOrders();
                    } else {
                        this.showNotification('Error', data.message, 'error');
                    }
                })
                .catch(error => {
                    console.error('Error updating payment status:', error);
                    this.showNotification('Error', 'Failed to update payment status', 'error');
                });
            },
            
            deleteOrder(orderId) {
                if (!confirm('Are you sure you want to delete this order? This action cannot be undone.')) {
                    return;
                }
                
                fetch(`/admin/orders/${orderId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Remove the order from the list
                        this.orders = this.orders.filter(o => o.id !== orderId);
                        
                        // Show success notification
                        this.showNotification('Success', data.message, 'success');
                        
                        // Refresh stats
                        this.fetchOrders();
                    } else {
                        this.showNotification('Error', data.message, 'error');
                    }
                })
                .catch(error => {
                    console.error('Error deleting order:', error);
                    this.showNotification('Error', 'Failed to delete order', 'error');
                });
            },
            
            showNotification(title, message, type) {
                // Implement your preferred notification system here
                // For example, using Toastify, SweetAlert2, or a custom notification
                
                // Example implementation with SweetAlert2 (if available)
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        title: title,
                        text: message,
                        icon: type,
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 3000,
                        timerProgressBar: true
                    });
                } 
                // Example implementation with Toastify (if available)
                else if (typeof Toastify !== 'undefined') {
                    Toastify({
                        text: `${title}: ${message}`,
                        duration: 3000,
                        close: true,
                        gravity: "top",
                        position: "right",
                        backgroundColor: type === 'success' ? "#4caf50" : "#f44336"
                    }).showToast();
                }
                // Fallback to console and basic alert
                else {
                    console.log(`${type.toUpperCase()}: ${title} - ${message}`);
                    alert(`${title}: ${message}`);
                }
            },
            
            exportOrders() {
                // Build query string with current filters
                const queryParams = new URLSearchParams();
                
                for (const [key, value] of Object.entries(this.filters)) {
                    if (value) {
                        queryParams.append(key, value);
                    }
                }
                
                // Redirect to export URL with current filters
                window.location.href = `/admin/orders/export?${queryParams.toString()}`;
            },
            
            // Quick status update dropdown handlers
            handleStatusChange(event, orderId) {
                const newStatus = event.target.value;
                if (newStatus) {
                    this.updateOrderStatus(orderId, newStatus);
                    // Reset the select after using it
                    event.target.value = '';
                }
            },
            
            handlePaymentChange(event, orderId) {
                const newStatus = event.target.value;
                if (newStatus) {
                    this.updatePaymentStatus(orderId, newStatus);
                    // Reset the select after using it
                    event.target.value = '';
                }
            },
            
            // Helper method to check if order has items with specific properties
            hasDigitalProducts(order) {
                if (!order.items || !order.items.length) return false;
                return order.items.some(item => item.product && item.product.is_digital);
            },
            
            hasPhysicalProducts(order) {
                if (!order.items || !order.items.length) return false;
                return order.items.some(item => item.product && !item.product.is_digital);
            },
            
            // Calculate order totals
            getItemsTotal(order) {
                if (!order.items || !order.items.length) return 0;
                return order.items.reduce((total, item) => {
                    return total + (item.quantity * item.price);
                }, 0);
            },
            
            // Format currency with symbol
            formatCurrency(amount, symbol = '€') {
                return `${symbol}${this.formatNumber(amount)}`;
            }
        };
    };
});