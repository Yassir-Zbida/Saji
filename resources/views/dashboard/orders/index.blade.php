@extends('layouts.admin')

@section('title', 'Orders')

@section('content')
    <div class="container mx-auto" x-data="ordersData()">
        <!-- Page Header -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6">
            <div>
                <h1 class="text-2xl font-semibold text-gray-900">Orders</h1>
                <p class="mt-1 text-sm text-gray-500">Manage and view all customer orders</p>
            </div>
            <div class="mt-4 md:mt-0 flex space-x-3">

                <button type="button" onclick="exportOrders()"
                    class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition-colors">
                    <i class="ri-download-line mr-2"></i>
                    Export Orders
                </button>

                <button type="button" @click="showFilters = !showFilters"
                    class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary hover:bg-primary/90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition-colors">
                    <i class="ri-filter-3-line mr-2"></i>
                    Filters
                </button>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5 mb-6">
            <!-- Total Orders -->
            <div class="bg-white rounded-lg border border-gray-200 shadow-sm p-5">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Total Orders</p>
                        <h3 class="text-2xl font-bold text-gray-900 mt-1" x-text="stats.totalOrders">{{ $totalOrders }}
                        </h3>
                    </div>
                    <div class="w-12 h-12 bg-primary/10 rounded-full flex items-center justify-center">
                        <i class="ri-shopping-basket-line text-xl text-primary"></i>
                    </div>
                </div>
                <div class="mt-3 flex items-center text-sm">
                    <span class="text-green-500 font-medium flex items-center">
                        <i class="ri-arrow-up-line mr-1"></i> <span
                            x-text="stats.orderGrowth + '%'">{{ $orderGrowth }}%</span>
                    </span>
                    <span class="text-gray-500 ml-2">from last month</span>
                </div>
            </div>

            <!-- Pending Orders -->
            <div class="bg-white rounded-lg border border-gray-200 shadow-sm p-5">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Pending Orders</p>
                        <h3 class="text-2xl font-bold text-gray-900 mt-1" x-text="stats.pendingOrders">{{ $pendingOrders }}
                        </h3>
                    </div>
                    <div class="w-12 h-12 bg-yellow-50 rounded-full flex items-center justify-center">
                        <i class="ri-time-line text-xl text-yellow-600"></i>
                    </div>
                </div>
                <div class="mt-3 flex items-center text-sm">
                    <span class="text-red-500 font-medium flex items-center">
                        <i class="ri-arrow-up-line mr-1"></i> 8%
                    </span>
                    <span class="text-gray-500 ml-2">from last month</span>
                </div>
            </div>

            <!-- Completed Orders -->
            <div class="bg-white rounded-lg border border-gray-200 shadow-sm p-5">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Completed Orders</p>
                        <h3 class="text-2xl font-bold text-gray-900 mt-1" x-text="stats.completedOrders">
                            {{ $completedOrders }}</h3>
                    </div>
                    <div class="w-12 h-12 bg-green-50 rounded-full flex items-center justify-center">
                        <i class="ri-check-line text-xl text-green-600"></i>
                    </div>
                </div>
                <div class="mt-3 flex items-center text-sm">
                    <span class="text-green-500 font-medium flex items-center">
                        <i class="ri-arrow-up-line mr-1"></i> 16%
                    </span>
                    <span class="text-gray-500 ml-2">from last month</span>
                </div>
            </div>

            <!-- Total Revenue -->
            <div class="bg-white rounded-lg border border-gray-200 shadow-sm p-5">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Total Revenue</p>
                        <h3 class="text-2xl font-bold text-gray-900 mt-1">€<span
                                x-text="formatNumber(stats.totalRevenue)">{{ number_format($totalRevenue, 2) }}</span></h3>
                    </div>
                    <div class="w-12 h-12 bg-blue-50 rounded-full flex items-center justify-center">
                        <i class="ri-money-euro-circle-line text-xl text-blue-600"></i>
                    </div>
                </div>
                <div class="mt-3 flex items-center text-sm">
                    <span class="text-green-500 font-medium flex items-center">
                        <i class="ri-arrow-up-line mr-1"></i> 23%
                    </span>
                    <span class="text-gray-500 ml-2">from last month</span>
                </div>
            </div>
        </div>

        <!-- Filter Section -->
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm mb-6 transition-all duration-300 hover:shadow-md"
            x-show="showFilters" data-aos="fade-up">
            <div class="p-5 border-b border-gray-100 flex justify-between items-center">
                <div class="flex items-center">
                    <i class="ri-filter-3-line text-lg text-primary mr-3"></i>
                    <h3 class="text-lg font-medium text-gray-900">Filter Orders</h3>
                </div>
                <button @click="showFilters = false" class="text-gray-400 hover:text-gray-600 transition-colors">
                    <i class="ri-close-line text-lg"></i>
                </button>
            </div>

            <div class="p-5">
                <form id="orderFilterForm" @submit.prevent="applyFilters()">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-5">
                        <!-- Status Filter -->
                        <div class="relative">
                            <label for="status" class="block text-sm font-medium text-gray-700 mb-1.5">Order
                                Status</label>
                            <div class="relative rounded-lg border border-gray-200">
                                <select id="status" name="status" x-model="filters.status"
                                    class="block  w-full pl-4 pr-10 py-2.5 rounded-lg border-gray-200 bg-gray-50 focus:border-primary focus:ring focus:ring-primary/20 focus:ring-opacity-50 text-sm transition-all shadow-sm">
                                    <option value="">All Statuses</option>
                                    <option value="pending">Pending</option>
                                    <option value="processing">Processing</option>
                                    <option value="shipped">Shipped</option>
                                    <option value="delivered">Delivered</option>
                                    <option value="completed">Completed</option>
                                    <option value="cancelled">Cancelled</option>
                                </select>
                                <div
                                    class="absolute hidden inset-y-0 right-0 flex items-center pr-3 pointer-events-none text-gray-400">
                                    <i class="ri-arrow-down-s-line"></i>
                                </div>
                            </div>
                        </div>

                        <!-- Payment Filter -->
                        <div class="relative">
                            <label for="payment" class="block text-sm font-medium text-gray-700 mb-1.5">Payment
                                Status</label>
                            <div class="relative rounded-lg border border-gray-200">
                                <select id="payment" name="payment_status" x-model="filters.payment_status"
                                    class="block w-full pl-4 pr-10 py-2.5 rounded-lg border-gray-200 bg-gray-50 focus:border-primary focus:ring focus:ring-primary/20 focus:ring-opacity-50 text-sm transition-all shadow-sm">
                                    <option value="">All Payments</option>
                                    <option value="paid">Paid</option>
                                    <option value="pending">Pending</option>
                                    <option value="failed">Failed</option>
                                    <option value="refunded">Refunded</option>
                                </select>
                                <div
                                    class="hidden absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none text-gray-400">
                                    <i class="ri-arrow-down-s-line"></i>
                                </div>
                            </div>
                        </div>

                        <!-- From Date Filter -->
                        <div>
                            <label for="from_date" class="block text-sm font-medium text-gray-700 mb-1.5">From
                                Date</label>
                            <div class="relative rounded-lg border border-gray-200">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="ri-calendar-line text-gray-400"></i>
                                </div>
                                <input type="date" id="from_date" name="date_from" x-model="filters.date_from"
                                    class="block w-full pl-10 pr-4 py-2.5 rounded-lg border-gray-200 bg-gray-50 focus:border-primary focus:ring focus:ring-primary/20 focus:ring-opacity-50 text-sm transition-all shadow-sm">
                            </div>
                        </div>

                        <!-- To Date Filter -->
                        <div>
                            <label for="to_date" class="block text-sm font-medium text-gray-700 mb-1.5">To Date</label>
                            <div class="relative rounded-lg border border-gray-200">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="ri-calendar-line text-gray-400"></i>
                                </div>
                                <input type="date" id="to_date" name="date_to" x-model="filters.date_to"
                                    class="block w-full pl-10 pr-4 py-2.5 rounded-lg border-gray-200 bg-gray-50 focus:border-primary focus:ring focus:ring-primary/20 focus:ring-opacity-50 text-sm transition-all shadow-sm">
                            </div>
                        </div>

                        <!-- Search Filter -->
                        <div>
                            <label for="search" class="block text-sm font-medium text-gray-700 mb-1.5">Search
                                Orders</label>
                            <div class="relative rounded-lg border border-gray-200">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="ri-search-line text-gray-400"></i>
                                </div>
                                <input type="text" id="search" name="search" x-model="filters.search"
                                    class="block w-full pl-10 pr-4 py-2.5 rounded-lg border-gray-200 bg-gray-50 focus:border-primary focus:ring focus:ring-primary/20 focus:ring-opacity-50 text-sm transition-all shadow-sm"
                                    placeholder="Order # ...">
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="mt-5 flex justify-end gap-3">
                        <button type="button" @click="resetFilters()"
                            class="inline-flex items-center px-4 py-2.5 border border-gray-200 rounded-lg shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition-colors">
                            <i class="ri-refresh-line mr-2"></i>
                            Reset
                        </button>
                        <button type="submit"
                            class="inline-flex items-center px-5 py-2.5 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-primary hover:bg-primary/90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition-colors">
                            <i class="ri-filter-line mr-2"></i>
                            Apply Filters
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Orders Table -->
        <div class="bg-white rounded-lg border border-gray-200 shadow-sm overflow-hidden">
            <div class="flex justify-between items-center p-5 border-b border-gray-100">
                <h3 class="text-lg font-medium text-gray-900">Recent Orders</h3>
                <div class="flex items-center space-x-2">
                    <span class="text-sm text-gray-500">Showing <span x-text="ordersCount"></span> of <span
                            x-text="stats.totalOrders"></span> orders</span>
                </div>
            </div>

            <div class="overflow-x-auto" x-show="!isLoading">
                <table class="w-full">
                    <thead>
                        <tr class="bg-gray-50">
                            <th scope="col"
                                class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-b border-gray-200">
                                Order #</th>
                            <th scope="col"
                                class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-b border-gray-200">
                                Customer</th>
                            <th scope="col"
                                class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-b border-gray-200">
                                Date</th>
                            <th scope="col"
                                class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-b border-gray-200">
                                Items</th>
                            <th scope="col"
                                class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-b border-gray-200">
                                Total</th>
                            <th scope="col"
                                class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-b border-gray-200">
                                Status</th>
                            <th scope="col"
                                class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-b border-gray-200">
                                Payment</th>
                            <th scope="col"
                                class="px-6 py-4 text-right text-xs font-medium text-gray-500 uppercase tracking-wider border-b border-gray-200">
                                Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <template x-for="order in orders" :key="order.id">
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-5 whitespace-nowrap">
                                    <span class="text-sm font-medium text-primary"
                                        x-text="'#' + order.order_number"></span>
                                </td>
                                <td class="px-6 py-5 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div
                                            class="flex-shrink-0 h-8 w-8 rounded-full bg-gray-200 flex items-center justify-center text-gray-500">
                                            <i class="ri-user-line"></i>
                                        </div>
                                        <div class="ml-3">
                                            <span class="text-sm font-medium text-gray-900"
                                                x-text="order.user ? order.user.name : 'Guest'"></span>
                                            <p class="text-xs text-gray-500"
                                                x-text="order.user ? order.user.email : 'N/A'"></p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-5 whitespace-nowrap">
                                    <div class="flex flex-col">
                                        <span class="text-sm font-medium text-gray-900"
                                            x-text="formatDate(order.created_at)"></span>
                                        <span class="text-xs text-gray-500" x-text="formatTime(order.created_at)"></span>
                                    </div>
                                </td>
                                <td class="px-6 py-5 whitespace-nowrap">
                                    <span class="text-sm text-gray-900"
                                        x-text="(order.items_count || '0') + ' items'"></span>
                                </td>
                                <td class="px-6 py-5 whitespace-nowrap">
                                    <span class="text-sm font-medium text-gray-900"
                                        x-text="'€' + formatNumber(order.total_amount)"></span>
                                </td>
                                <td class="px-6 py-5 whitespace-nowrap">
                                    <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-medium"
                                        :class="getStatusClasses(order.status)">
                                        <i :class="getStatusIcon(order.status) + ' mr-1'"></i>
                                        <span x-text="capitalizeFirst(order.status)"></span>
                                    </span>
                                </td>
                                <td class="px-6 py-5 whitespace-nowrap">
                                    <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-medium"
                                        :class="getPaymentClasses(order.payment_status)">
                                        <i :class="getPaymentIcon(order.payment_status) + ' mr-1'"></i>
                                        <span x-text="capitalizeFirst(order.payment_status)"></span>
                                    </span>
                                </td>
                                <td class="px-6 py-5 whitespace-nowrap text-right">
                                    <div class="flex flex-wrap justify-end gap-2">
                                        <a :href="'/admin/orders/' + order.id"
                                            class="inline-flex items-center px-3 py-1.5 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary hover:bg-primary/90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition-colors">
                                            <i class="ri-eye-line mr-1"></i>
                                            View
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>

            <!-- Loading Indicator -->
            <div class="p-10 flex justify-center items-center" x-show="isLoading">
                <div class="animate-spin rounded-full h-10 w-10 border-b-2 border-primary"></div>
            </div>

            <!-- Empty State -->
            <div class="text-center py-16" x-show="!isLoading && orders.length === 0">
                <div class="inline-flex items-center justify-center w-20 h-20 bg-gray-100 rounded-full mb-6">
                    <i class="ri-shopping-bag-line text-3xl text-gray-400"></i>
                </div>
                <h3 class="text-xl font-medium text-primary mb-3">No orders found</h3>
                <p class="text-gray-500 mb-8 max-w-md mx-auto">There are no orders matching your criteria.</p>
                <button @click="resetFilters()"
                    class="inline-flex items-center px-6 py-3 border border-gray-300 bg-white text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                    <i class="ri-refresh-line mr-2"></i> Reset Filters
                </button>
            </div>

            <!-- Pagination -->
            <div class="px-6 py-4 border-t border-gray-200 flex items-center justify-between"
                x-show="!isLoading && orders.length > 0">
                <div class="flex-1 flex justify-between sm:hidden">
                    <button @click="prevPage()" :disabled="currentPage === 1"
                        class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50"
                        :class="{ 'opacity-50 cursor-not-allowed': currentPage === 1 }">
                        Previous
                    </button>
                    <button @click="nextPage()" :disabled="currentPage === lastPage"
                        class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50"
                        :class="{ 'opacity-50 cursor-not-allowed': currentPage === lastPage }">
                        Next
                    </button>
                </div>
                <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                    <div>
                        <p class="text-sm text-gray-700">
                            Showing
                            <span class="font-medium" x-text="(currentPage - 1) * perPage + 1"></span>
                            to
                            <span class="font-medium" x-text="Math.min(currentPage * perPage, totalOrders)"></span>
                            of
                            <span class="font-medium" x-text="totalOrders"></span>
                            results
                        </p>
                    </div>
                    <div>
                        <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                            <button @click="prevPage()" :disabled="currentPage === 1"
                                class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50"
                                :class="{ 'opacity-50 cursor-not-allowed': currentPage === 1 }">
                                <span class="sr-only">Previous</span>
                                <i class="ri-arrow-left-s-line"></i>
                            </button>
                            <!-- Pagination Numbers -->
                            <template x-for="page in paginationPages" :key="page">
                                <button @click="goToPage(page)"
                                    class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm font-medium hover:bg-gray-50"
                                    :class="page === currentPage ?
                                        'z-10 bg-primary text-white border-primary hover:bg-primary/90' :
                                        'text-gray-500'">
                                    <span x-text="page" class="text-black  "></span>
                                </button>
                            </template>
                            <button @click="nextPage()" :disabled="currentPage === lastPage"
                                class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50"
                                :class="{ 'opacity-50 cursor-not-allowed': currentPage === lastPage }">
                                <span class="sr-only">Next</span>
                                <i class="ri-arrow-right-s-line"></i>
                            </button>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

<script>
    function ordersData() {
        return {
            orders: [],
            stats: {
                totalOrders: {{ $totalOrders ?? 0 }},
                pendingOrders: {{ $pendingOrders ?? 0 }},
                completedOrders: {{ $completedOrders ?? 0 }},
                totalRevenue: {{ $totalRevenue ?? 0 }},
                orderGrowth: {{ $orderGrowth ?? 0 }}
            },
            filters: {
                status: '',
                payment_status: '',
                date_from: '',
                date_to: '',
                search: ''
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
                const options = {
                    month: 'short',
                    day: 'numeric',
                    year: 'numeric'
                };
                return date.toLocaleDateString('en-US', options);
            },

            formatTime(dateString) {
                const date = new Date(dateString);
                return date.toLocaleTimeString('en-US', {
                    hour: 'numeric',
                    minute: 'numeric',
                    hour12: true
                });
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
            }
        }
    }


    function exportOrders() {
        let queryParams = new URLSearchParams(window.location.search);
        window.location.href = "{{ route('admin.orders.export') }}?" + queryParams.toString();
    }
</script>
