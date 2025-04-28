@extends('layouts.admin')

@section('title', 'Orders')

@section('content')
    <div class="container mx-auto" x-data="ordersData()">
        <!-- Page Header -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6">
            <div>
                <h1 class="text-2xl font-semibold text-gray-900">Orders</h1>
                <p class="mt-1 text-sm text-gray-500">Manage and track all customer orders</p>
            </div>
            <div class="mt-4 md:mt-0 flex space-x-3">
                <a href="#"
                    class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition-colors">
                    <i class="ri-download-line mr-2"></i>
                    Export
                </a>
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
                        <h3 class="text-2xl font-bold text-gray-900 mt-1" x-text="stats.totalOrders"></h3>
                    </div>
                    <div class="w-12 h-12 bg-primary/10 rounded-full flex items-center justify-center">
                        <i class="ri-shopping-cart-line text-xl text-primary"></i>
                    </div>
                </div>
                <div class="mt-3 flex items-center text-sm">
                    <span class="text-green-500 font-medium flex items-center">
                        <i class="ri-arrow-up-line mr-1"></i> <span x-text="stats.orderGrowth + '%'"></span>
                    </span>
                    <span class="text-gray-500 ml-2">from last month</span>
                </div>
            </div>

            <!-- Pending Orders -->
            <div class="bg-white rounded-lg border border-gray-200 shadow-sm p-5">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Pending Orders</p>
                        <h3 class="text-2xl font-bold text-gray-900 mt-1" x-text="stats.pendingOrders"></h3>
                    </div>
                    <div class="w-12 h-12 bg-yellow-50 rounded-full flex items-center justify-center">
                        <i class="ri-time-line text-xl text-yellow-600"></i>
                    </div>
                </div>
                <div class="mt-3 flex items-center text-sm">
                    <span class="text-yellow-500 font-medium flex items-center">
                        <i class="ri-information-line mr-1"></i> Need attention
                    </span>
                </div>
            </div>

            <!-- Total Revenue -->
            <div class="bg-white rounded-lg border border-gray-200 shadow-sm p-5">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Total Revenue</p>
                        <h3 class="text-2xl font-bold text-gray-900 mt-1">
                            <span x-text="'€' + formatNumber(stats.totalRevenue)"></span>
                        </h3>
                    </div>
                    <div class="w-12 h-12 bg-green-50 rounded-full flex items-center justify-center">
                        <i class="ri-money-euro-circle-line text-xl text-green-600"></i>
                    </div>
                </div>
                <div class="mt-3 flex items-center text-sm">
                    <span class="text-green-500 font-medium flex items-center">
                        <i class="ri-arrow-up-line mr-1"></i> <span x-text="stats.revenueGrowth + '%'"></span>
                    </span>
                    <span class="text-gray-500 ml-2">from last month</span>
                </div>
            </div>

            <!-- Average Order Value -->
            <div class="bg-white rounded-lg border border-gray-200 shadow-sm p-5">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Avg. Order Value</p>
                        <h3 class="text-2xl font-bold text-gray-900 mt-1">
                            <span x-text="'€' + formatNumber(stats.avgOrderValue)"></span>
                        </h3>
                    </div>
                    <div class="w-12 h-12 bg-blue-50 rounded-full flex items-center justify-center">
                        <i class="ri-bar-chart-line text-xl text-blue-600"></i>
                    </div>
                </div>
                <div class="mt-3 flex items-center text-sm">
                    <span class="text-blue-500 font-medium flex items-center">
                        <i class="ri-line-chart-line mr-1"></i> Customer value
                    </span>
                </div>
            </div>
        </div>

        <!-- Filter Section -->
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm mb-6 transition-all duration-300 hover:shadow-md"
            x-show="showFilters" x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 transform -translate-y-4"
            x-transition:enter-end="opacity-100 transform translate-y-0"
            x-transition:leave="transition ease-in duration-300"
            x-transition:leave-start="opacity-100 transform translate-y-0"
            x-transition:leave-end="opacity-0 transform -translate-y-4">
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
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
                        <!-- Status Filter -->
                        <div class="relative">
                            <label for="status" class="block text-sm font-medium text-gray-700 mb-1.5">Status</label>
                            <div class="relative rounded-lg border border-gray-200">
                                <select id="status" name="status" x-model="filters.status"
                                    class="block w-full pl-4 pr-10 py-2.5 rounded-lg border-gray-200 bg-gray-50 focus:border-primary focus:ring focus:ring-primary/20 focus:ring-opacity-50 text-sm transition-all shadow-sm">
                                    <option value="">All Statuses</option>
                                    <option value="pending">Pending</option>
                                    <option value="processing">Processing</option>
                                    <option value="shipped">Shipped</option>
                                    <option value="delivered">Delivered</option>
                                    <option value="cancelled">Cancelled</option>
                                </select>
                            </div>
                        </div>

                        <!-- Payment Status Filter -->
                        <div class="relative">
                            <label for="payment_status" class="block text-sm font-medium text-gray-700 mb-1.5">Payment
                                Status</label>
                            <div class="relative rounded-lg border border-gray-200">
                                <select id="payment_status" name="payment_status" x-model="filters.payment_status"
                                    class="block w-full pl-4 pr-10 py-2.5 rounded-lg border-gray-200 bg-gray-50 focus:border-primary focus:ring focus:ring-primary/20 focus:ring-opacity-50 text-sm transition-all shadow-sm">
                                    <option value="">All Payment Statuses</option>
                                    <option value="paid">Paid</option>
                                    <option value="pending">Pending</option>
                                    <option value="failed">Failed</option>
                                    <option value="refunded">Refunded</option>
                                </select>
                            </div>
                        </div>

                        <!-- Date Range Filter -->
                        <div class="relative col-span-1 md:col-span-2 lg:col-span-1">
                            <label for="date_range" class="block text-sm font-medium text-gray-700 mb-1.5">Date Range</label>
                            <div class="grid grid-cols-2 gap-3">
                                <div class="relative rounded-lg border border-gray-200">
                                    <input type="date" id="date_from" name="date_from" x-model="filters.date_from"
                                        class="block w-full px-4 py-2.5 rounded-lg border-gray-200 bg-gray-50 focus:border-primary focus:ring focus:ring-primary/20 focus:ring-opacity-50 text-sm transition-all shadow-sm">
                                </div>
                                <div class="relative rounded-lg border border-gray-200">
                                    <input type="date" id="date_to" name="date_to" x-model="filters.date_to"
                                        class="block w-full px-4 py-2.5 rounded-lg border-gray-200 bg-gray-50 focus:border-primary focus:ring focus:ring-primary/20 focus:ring-opacity-50 text-sm transition-all shadow-sm">
                                </div>
                            </div>
                        </div>

                        <!-- Search Filter -->
                        <div class="relative md:col-span-2 lg:col-span-2">
                            <label for="search" class="block text-sm font-medium text-gray-700 mb-1.5">Search
                                Orders</label>
                            <div class="relative rounded-lg border border-gray-200">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="ri-search-line text-gray-400"></i>
                                </div>
                                <input type="text" id="search" name="search" x-model="filters.search"
                                    class="block w-full pl-10 pr-4 py-2.5 rounded-lg border-gray-200 bg-gray-50 focus:border-primary focus:ring focus:ring-primary/20 focus:ring-opacity-50 text-sm transition-all shadow-sm"
                                    placeholder="Order #, customer name, email...">
                            </div>
                        </div>

                        <!-- Price Range Filter -->
                        <div class="relative col-span-1">
                            <label for="price_range" class="block text-sm font-medium text-gray-700 mb-1.5">Price Range
                                (€)</label>
                            <div class="grid grid-cols-2 gap-3">
                                <div class="relative rounded-lg border border-gray-200">
                                    <input type="number" id="min_price" name="min_price" x-model="filters.min_price"
                                        class="block w-full px-4 py-2.5 rounded-lg border-gray-200 bg-gray-50 focus:border-primary focus:ring focus:ring-primary/20 focus:ring-opacity-50 text-sm transition-all shadow-sm"
                                        placeholder="Min">
                                </div>
                                <div class="relative rounded-lg border border-gray-200">
                                    <input type="number" id="max_price" name="max_price" x-model="filters.max_price"
                                        class="block w-full px-4 py-2.5 rounded-lg border-gray-200 bg-gray-50 focus:border-primary focus:ring focus:ring-primary/20 focus:ring-opacity-50 text-sm transition-all shadow-sm"
                                        placeholder="Max">
                                </div>
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
                <h3 class="text-lg font-medium text-gray-900">Order Management</h3>
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
                                Order</th>
                            <th scope="col"
                                class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-b border-gray-200">
                                Customer</th>
                            <th scope="col"
                                class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-b border-gray-200">
                                Date</th>
                            <th scope="col"
                                class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-b border-gray-200">
                                Status</th>
                            <th scope="col"
                                class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-b border-gray-200">
                                Payment</th>
                            <th scope="col"
                                class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-b border-gray-200">
                                Items</th>
                            <th scope="col"
                                class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-b border-gray-200">
                                Total</th>
                            <th scope="col"
                                class="px-6 py-4 text-right text-xs font-medium text-gray-500 uppercase tracking-wider border-b border-gray-200">
                                Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <template x-for="order in orders" :key="order.id">
                            <tr class="hover:bg-gray-50 transition-colors" :class="{'bg-blue-50/30': order.isNew}">
                                <td class="px-6 py-5 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 mr-3">
                                            <span class="w-10 h-10 rounded-md bg-primary/10 flex items-center justify-center">
                                                <i class="ri-shopping-bag-line text-primary text-lg"></i>
                                            </span>
                                        </div>
                                        <div>
                                            <div class="text-sm font-medium text-gray-900">#<span x-text="order.order_number"></span></div>
                                            <div class="text-xs text-gray-500" x-show="order.isNew">
                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800">
                                                    <i class="ri-notification-badge-line mr-1"></i> New
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-5">
                                    <div class="text-sm font-medium text-gray-900" x-text="order.customer.name"></div>
                                    <div class="text-xs text-gray-500" x-text="order.customer.email"></div>
                                </td>
                                <td class="px-6 py-5 whitespace-nowrap">
                                    <div class="text-sm text-gray-900" x-text="formatDate(order.created_at)"></div>
                                    <div class="text-xs text-gray-500" x-text="formatTime(order.created_at)"></div>
                                </td>
                                <td class="px-6 py-5 whitespace-nowrap">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium"
                                        :class="getStatusClasses(order.status)">
                                        <i class="mr-1" :class="getStatusIcon(order.status)"></i>
                                        <span x-text="capitalizeFirstLetter(order.status)"></span>
                                    </span>
                                </td>
                                <td class="px-6 py-5 whitespace-nowrap">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium"
                                        :class="getPaymentStatusClasses(order.payment_status)">
                                        <i class="mr-1" :class="getPaymentStatusIcon(order.payment_status)"></i>
                                        <span x-text="capitalizeFirstLetter(order.payment_status)"></span>
                                    </span>
                                </td>
                                <td class="px-6 py-5 whitespace-nowrap">
                                    <span class="flex items-center">
                                        <span class="text-sm font-medium text-gray-900" x-text="order.items_count"></span>
                                        <span class="w-6 h-6 ml-2 bg-gray-100 rounded-full flex items-center justify-center cursor-pointer"
                                            @click="toggleOrderItems(order)" x-show="order.items_count > 0">
                                            <i class="ri-eye-line text-gray-500 text-xs"></i>
                                        </span>
                                    </span>
                                </td>
                                <td class="px-6 py-5 whitespace-nowrap">
                                    <span class="text-sm font-medium text-gray-900" x-text="'€' + formatNumber(order.total)"></span>
                                </td>
                                <td class="px-6 py-5 whitespace-nowrap text-right">
                                    <div class="flex justify-end gap-2">
                                        <div class="relative" x-data="{ open: false }">
                                            <button @click="open = !open" type="button"
                                                class="inline-flex items-center px-3 py-1.5 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition-colors">
                                                <i class="ri-more-2-fill mr-1"></i>
                                                Actions
                                            </button>
                                            <div x-show="open" @click.away="open = false"
                                                class="origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 focus:outline-none z-10"
                                                x-transition:enter="transition ease-out duration-100"
                                                x-transition:enter-start="transform opacity-0 scale-95"
                                                x-transition:enter-end="transform opacity-100 scale-100"
                                                x-transition:leave="transition ease-in duration-75"
                                                x-transition:leave-start="transform opacity-100 scale-100"
                                                x-transition:leave-end="transform opacity-0 scale-95">
                                                <div class="py-1" role="menu" aria-orientation="vertical">
                                                    <a href="#" @click.prevent="updateOrderStatus(order, 'processing')"
                                                        class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                                                        role="menuitem" x-show="order.status === 'pending'">
                                                        <i class="ri-loader-2-line mr-3 text-gray-500"></i>
                                                        Mark as Processing
                                                    </a>
                                                    <a href="#" @click.prevent="updateOrderStatus(order, 'shipped')"
                                                        class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                                                        role="menuitem" x-show="order.status === 'processing'">
                                                        <i class="ri-truck-line mr-3 text-gray-500"></i>
                                                        Mark as Shipped
                                                    </a>
                                                    <a href="#" @click.prevent="updateOrderStatus(order, 'delivered')"
                                                        class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                                                        role="menuitem" x-show="order.status === 'shipped'">
                                                        <i class="ri-check-double-line mr-3 text-gray-500"></i>
                                                        Mark as Delivered
                                                    </a>
                                                    <a href="#" @click.prevent="showInvoice(order)"
                                                        class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                                                        role="menuitem">
                                                        <i class="ri-file-list-3-line mr-3 text-gray-500"></i>
                                                        View Invoice
                                                    </a>
                                                    <a href="#" @click.prevent="printOrderDetails(order)"
                                                        class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                                                        role="menuitem">
                                                        <i class="ri-printer-line mr-3 text-gray-500"></i>
                                                        Print Order
                                                    </a>
                                                    <a href="#" @click.prevent="confirmCancelOrder(order)"
                                                        class="flex items-center px-4 py-2 text-sm text-red-600 hover:bg-red-50"
                                                        role="menuitem" x-show="order.status !== 'cancelled' && order.status !== 'delivered'">
                                                        <i class="ri-close-circle-line mr-3 text-red-500"></i>
                                                        Cancel Order
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                        <a :href="'/admin/orders/' + order.id"
                                            class="inline-flex items-center px-3 py-1.5 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary hover:bg-primary/90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition-colors">
                                            <i class="ri-eye-line mr-1"></i>
                                            View
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            <!-- Order Items Expansion -->
                            <tr x-show="order.showItems" class="bg-gray-50 border-b border-gray-100">
                                <td colspan="8" class="px-6 py-4">
                                    <div class="overflow-x-auto">
                                        <table class="min-w-full divide-y divide-gray-200">
                                            <thead class="bg-gray-100">
                                                <tr>
                                                    <th scope="col"
                                                        class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                        Product</th>
                                                    <th scope="col"
                                                        class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                        SKU</th>
                                                    <th scope="col"
                                                        class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                        Price</th>
                                                    <th scope="col"
                                                        class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                        Quantity</th>
                                                    <th scope="col"
                                                        class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                        Subtotal</th>
                                                </tr>
                                            </thead>
                                            <tbody class="bg-white divide-y divide-gray-100">
                                                <template x-for="item in order.items" :key="item.id">
                                                    <tr>
                                                        <td class="px-4 py-3 whitespace-nowrap">
                                                            <div class="flex items-center">
                                                                <div
                                                                    class="h-10 w-10 rounded-md bg-gray-100 border border-gray-200 flex-shrink-0 overflow-hidden">
                                                                    <img :src="item.product_image || '/images/placeholder.jpg'"
                                                                        alt="" class="h-full w-full object-cover object-center">
                                                                </div>
                                                                <div class="ml-3">
                                                                    <div class="text-sm font-medium text-gray-900" x-text="item.product_name">
                                                                    </div>
                                                                    <div class="text-xs text-gray-500" x-show="item.variant">
                                                                        <span x-text="item.variant"></span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500" x-text="item.sku">
                                                        </td>
                                                        <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">
                                                            <span x-text="'€' + formatNumber(item.price)"></span>
                                                        </td>
                                                        <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500" x-text="item.quantity">
                                                        </td>
                                                        <td class="px-4 py-3 whitespace-nowrap text-sm font-medium text-gray-900">
                                                            <span x-text="'€' + formatNumber(item.price * item.quantity)"></span>
                                                        </td>
                                                    </tr>
                                                </template>
                                            </tbody>
                                            <tfoot class="bg-gray-50">
                                                <tr>
                                                    <td colspan="3" class="px-4 py-3 text-sm text-gray-500 text-right">
                                                    </td>
                                                    <td class="px-4 py-3 text-sm font-medium text-gray-900 text-right">Subtotal:</td>
                                                    <td class="px-4 py-3 text-sm font-medium text-gray-900">
                                                        <span x-text="'€' + formatNumber(order.subtotal)"></span>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td colspan="3" class="px-4 py-3 text-sm text-gray-500 text-right">
                                                    </td>
                                                    <td class="px-4 py-3 text-sm font-medium text-gray-900 text-right">Shipping:</td>
                                                    <td class="px-4 py-3 text-sm font-medium text-gray-900">
                                                        <span x-text="'€' + formatNumber(order.shipping_cost)"></span>
                                                    </td>
                                                </tr>
                                                <tr x-show="order.discount > 0">
                                                    <td colspan="3" class="px-4 py-3 text-sm text-gray-500 text-right">
                                                    </td>
                                                    <td class="px-4 py-3 text-sm font-medium text-gray-900 text-right">Discount:</td>
                                                    <td class="px-4 py-3 text-sm font-medium text-green-600">
                                                        <span x-text="'-€' + formatNumber(order.discount)"></span>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td colspan="3" class="px-4 py-3 text-sm text-gray-500 text-right">
                                                    </td>
                                                    <td class="px-4 py-3 text-sm font-medium text-gray-900 text-right">Tax:</td>
                                                    <td class="px-4 py-3 text-sm font-medium text-gray-900">
                                                        <span x-text="'€' + formatNumber(order.tax)"></span>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td colspan="3" class="px-4 py-3 text-sm text-gray-500 text-right">
                                                    </td>
                                                    <td class="px-4 py-3 text-sm font-bold text-gray-900 text-right">Total:</td>
                                                    <td class="px-4 py-3 text-sm font-bold text-gray-900">
                                                        <span x-text="'€' + formatNumber(order.total)"></span>
                                                    </td>
                                                </tr>
                                            </tfoot>
                                        </table>

                                        <div class="mt-3 text-sm flex items-center justify-end">
                                            <a :href="'/admin/orders/' + order.id"
                                                class="text-primary hover:text-primary/80 font-medium flex items-center transition-colors">
                                                <span>See full order details</span>
                                                <i class="ri-arrow-right-line ml-1"></i>
                                            </a>
                                        </div>
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
                    <i class="ri-shopping-cart-line text-3xl text-gray-400"></i>
                </div>
                <h3 class="text-xl font-medium text-primary mb-3">No orders found</h3>
                <p class="text-gray-500 mb-8 max-w-md mx-auto">There are no orders matching your criteria.</p>
                <div class="flex justify-center gap-4">
                    <button @click="resetFilters()"
                        class="inline-flex items-center px-6 py-3 border border-gray-300 bg-white text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                        <i class="ri-refresh-line mr-2"></i> Reset Filters
                    </button>
                </div>
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
                                    <span x-text="page"></span>
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

    <!-- Order Status Update Modal -->
    <div x-data="{ showModal: false, order: null, newStatus: '', statusNote: '' }" x-show="showModal" class="fixed z-50 inset-0 overflow-y-auto"
        x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div x-show="showModal" class="fixed inset-0 transition-opacity" aria-hidden="true"
                x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-50" x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-50" x-transition:leave-end="opacity-0">
                <div class="absolute inset-0 bg-gray-900 opacity-50"></div>
            </div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div x-show="showModal"
                class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div
                            class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-primary/10 sm:mx-0 sm:h-10 sm:w-10">
                            <i class="ri-refresh-line text-primary"></i>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                            <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                                Update Order Status
                            </h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500" x-text="'Order #' + (order ? order.order_number : '')">
                                </p>
                            </div>

                            <div class="mt-4">
                                <label for="order_status" class="block text-sm font-medium text-gray-700 mb-1">New
                                    Status</label>
                                <select id="order_status" x-model="newStatus"
                                    class="block w-full pl-3 pr-10 py-2 text-base rounded-md border-gray-300 focus:outline-none focus:ring-primary focus:border-primary">
                                    <option value="pending">Pending</option>
                                    <option value="processing">Processing</option>
                                    <option value="shipped">Shipped</option>
                                    <option value="delivered">Delivered</option>
                                    <option value="cancelled">Cancelled</option>
                                </select>
                            </div>

                            <div class="mt-4">
                                <label for="status_note" class="block text-sm font-medium text-gray-700 mb-1">Status
                                    Note (Optional)</label>
                                <textarea id="status_note" x-model="statusNote" rows="3"
                                    class="block w-full shadow-sm focus:ring-primary focus:border-primary sm:text-sm border-gray-300 rounded-md"
                                    placeholder="Add a note about this status change..."></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="button" @click="submitStatusUpdate()"
                        class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-primary text-base font-medium text-white hover:bg-primary/90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary sm:ml-3 sm:w-auto sm:text-sm">
                        Update Status
                    </button>
                    <button type="button" @click="showModal = false"
                        class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Cancel
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Invoice Modal -->
    <div x-data="{ showInvoiceModal: false, invoiceOrder: null }" x-show="showInvoiceModal" class="fixed z-50 inset-0 overflow-y-auto"
        x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div x-show="showInvoiceModal" class="fixed inset-0 transition-opacity" aria-hidden="true"
                x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-50" x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-50" x-transition:leave-end="opacity-0">
                <div class="absolute inset-0 bg-gray-900 opacity-50"></div>
            </div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div x-show="showInvoiceModal"
                class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
                <div class="bg-white p-6">
                    <div class="flex justify-between items-center border-b border-gray-200 pb-4 mb-5">
                        <h2 class="text-xl font-semibold text-gray-900">Invoice</h2>
                        <div class="flex items-center space-x-2">
                            <button type="button" @click="printInvoice()"
                                class="inline-flex items-center px-3 py-1.5 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none">
                                <i class="ri-printer-line mr-1.5"></i>
                                Print
                            </button>
                            <button type="button" @click="downloadInvoicePDF()"
                                class="inline-flex items-center px-3 py-1.5 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none">
                                <i class="ri-download-line mr-1.5"></i>
                                Download PDF
                            </button>
                            <button type="button" @click="showInvoiceModal = false"
                                class="inline-flex items-center justify-center h-8 w-8 rounded-md text-gray-400 hover:text-gray-500 focus:outline-none">
                                <i class="ri-close-line text-xl"></i>
                            </button>
                        </div>
                    </div>

                    <div id="invoice-content" class="bg-white p-6">
                        <!-- Invoice header -->
                        <div class="flex justify-between mb-8">
                            <div>
                                <img src="/logo.png" alt="Company Logo" class="h-10">
                                <p class="mt-2 text-sm text-gray-600">Company Name, Inc.</p>
                                <p class="text-sm text-gray-600">123 Commerce St.</p>
                                <p class="text-sm text-gray-600">City, State ZIP</p>
                                <p class="text-sm text-gray-600">support@company.com</p>
                            </div>
                            <div class="text-right">
                                <h2 class="text-xl font-bold text-gray-900 uppercase">Invoice</h2>
                                <p class="text-gray-600 mt-1">#<span x-text="invoiceOrder?.invoice_number || ''"></span></p>
                                <p class="text-sm text-gray-600 mt-2">Date: <span
                                        x-text="formatDate(invoiceOrder?.created_at)"></span></p>
                                <p class="text-sm text-gray-600">Order #<span
                                        x-text="invoiceOrder?.order_number || ''"></span></p>
                            </div>
                        </div>

                        <!-- Customer info -->
                        <div class="grid grid-cols-2 gap-8 mb-8">
                            <div>
                                <h3 class="text-gray-800 font-semibold mb-2">Bill To:</h3>
                                <p class="text-sm text-gray-600" x-text="invoiceOrder?.customer?.name || ''"></p>
                                <p class="text-sm text-gray-600" x-text="invoiceOrder?.customer?.email || ''"></p>
                                <p class="text-sm text-gray-600" x-text="invoiceOrder?.billing_address?.line1 || ''"></p>
                                <p class="text-sm text-gray-600" x-text="invoiceOrder?.billing_address?.city || ''"></p>
                                <p class="text-sm text-gray-600" x-text="(invoiceOrder?.billing_address?.state || '') + ' ' + (invoiceOrder?.billing_address?.postal_code || '')"></p>
                                <p class="text-sm text-gray-600" x-text="invoiceOrder?.billing_address?.country || ''"></p>
                            </div>
                            <div>
                                <h3 class="text-gray-800 font-semibold mb-2">Ship To:</h3>
                                <p class="text-sm text-gray-600" x-text="invoiceOrder?.shipping_address?.line1 || ''"></p>
                                <p class="text-sm text-gray-600" x-text="invoiceOrder?.shipping_address?.city || ''"></p>
                                <p class="text-sm text-gray-600" x-text="(invoiceOrder?.shipping_address?.state || '') + ' ' + (invoiceOrder?.shipping_address?.postal_code || '')"></p>
                                <p class="text-sm text-gray-600" x-text="invoiceOrder?.shipping_address?.country || ''"></p>
                            </div>
                        </div>

                        <!-- Invoice items -->
                        <table class="min-w-full bg-white mb-8">
                            <thead>
                                <tr class="bg-gray-50 border-b border-gray-200">
                                    <th class="py-3 px-4 text-left text-sm font-semibold text-gray-700">Item</th>
                                    <th class="py-3 px-4 text-left text-sm font-semibold text-gray-700">SKU</th>
                                    <th class="py-3 px-4 text-right text-sm font-semibold text-gray-700">Price</th>
                                    <th class="py-3 px-4 text-right text-sm font-semibold text-gray-700">Quantity</th>
                                    <th class="py-3 px-4 text-right text-sm font-semibold text-gray-700">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                <template x-for="item in invoiceOrder?.items" :key="item.id">
                                    <tr class="border-b border-gray-100">
                                        <td class="py-3 px-4 text-sm text-gray-800">
                                            <div x-text="item.product_name"></div>
                                            <div class="text-xs text-gray-500" x-show="item.variant" x-text="item.variant"></div>
                                        </td>
                                        <td class="py-3 px-4 text-sm text-gray-600" x-text="item.sku"></td>
                                        <td class="py-3 px-4 text-sm text-gray-600 text-right" x-text="'€' + formatNumber(item.price)">
                                        </td>
                                        <td class="py-3 px-4 text-sm text-gray-600 text-right" x-text="item.quantity"></td>
                                        <td class="py-3 px-4 text-sm text-gray-800 text-right font-medium" x-text="'€' + formatNumber(item.price * item.quantity)"></td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>

                        <!-- Invoice totals -->
                        <div class="flex justify-end mb-8">
                            <div class="w-80">
                                <div class="flex justify-between py-2">
                                    <span class="text-sm text-gray-600">Subtotal:</span>
                                    <span class="text-sm text-gray-800" x-text="'€' + formatNumber(invoiceOrder?.subtotal || 0)"></span>
                                </div>
                                <div class="flex justify-between py-2">
                                    <span class="text-sm text-gray-600">Shipping:</span>
                                    <span class="text-sm text-gray-800" x-text="'€' + formatNumber(invoiceOrder?.shipping_cost || 0)"></span>
                                </div>
                                <div class="flex justify-between py-2" x-show="invoiceOrder?.discount > 0">
                                    <span class="text-sm text-gray-600">Discount:</span>
                                    <span class="text-sm text-green-600" x-text="'-€' + formatNumber(invoiceOrder?.discount || 0)"></span>
                                </div>
                                <div class="flex justify-between py-2">
                                    <span class="text-sm text-gray-600">Tax:</span>
                                    <span class="text-sm text-gray-800" x-text="'€' + formatNumber(invoiceOrder?.tax || 0)"></span>
                                </div>
                                <div class="flex justify-between py-2 border-t border-gray-200 mt-2">
                                    <span class="text-base font-semibold text-gray-800">Total:</span>
                                    <span class="text-base font-semibold text-gray-800" x-text="'€' + formatNumber(invoiceOrder?.total || 0)"></span>
                                </div>
                            </div>
                        </div>

                        <!-- Payment info -->
                        <div class="border-t border-gray-200 pt-6">
                            <h3 class="text-gray-800 font-semibold mb-2">Payment Information</h3>
                            <p class="text-sm text-gray-600">Payment Method: <span x-text="invoiceOrder?.payment_method || 'Credit Card'"></span></p>
                            <p class="text-sm text-gray-600">Payment Status: <span x-text="capitalizeFirstLetter(invoiceOrder?.payment_status || '')"></span></p>
                            <p class="text-sm text-gray-600">Transaction ID: <span x-text="invoiceOrder?.transaction_id || '-'"></span></p>
                        </div>

                        <!-- Notes -->
                        <div class="mt-8">
                            <p class="text-sm text-gray-600 mb-6">Thank you for your business!</p>
                            <p class="text-xs text-gray-500">For any questions regarding this invoice, please contact our customer service at support@company.com</p>
                        </div>
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
                totalOrders: 0,
                pendingOrders: 0,
                totalRevenue: 0,
                avgOrderValue: 0,
                orderGrowth: 0,
                revenueGrowth: 0
            },
            filters: {
                status: '',
                payment_status: '',
                date_from: '',
                date_to: '',
                search: '',
                min_price: '',
                max_price: ''
            },
            pagination: {
                current_page: 1,
                per_page: 10,
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
                    min_price: urlParams.get('min_price') || '',
                    max_price: urlParams.get('max_price') || ''
                };

                this.showFilters = Object.values(this.filters).some(val => val !== '');
                
                // Listen for status update events
                window.addEventListener('order-status-updated', this.handleOrderStatusUpdated.bind(this));
            },

            handleOrderStatusUpdated(event) {
                // Refresh orders when status is updated
                this.fetchOrders();
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

                fetch(`/admin/orders/data?${queryParams.toString()}`, {
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error(`HTTP error! Status: ${response.status}`);
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (!data.error) {
                            // Process orders
                            this.orders = data.orders.data.map(order => {
                                // Add a property to control item visibility
                                order.showItems = false;
                                
                                // Mark orders from last 24 hours as new
                                const orderDate = new Date(order.created_at);
                                const now = new Date();
                                const hoursDiff = Math.abs(now - orderDate) / 36e5; // hours
                                order.isNew = hoursDiff < 24;
                                
                                return order;
                            });
                            
                            // Update pagination
                            this.pagination = {
                                current_page: data.orders.current_page,
                                per_page: data.orders.per_page,
                                total: data.orders.total,
                                last_page: data.orders.last_page
                            };
                            
                            // Update stats
                            this.stats = {
                                totalOrders: data.stats.totalOrders,
                                pendingOrders: data.stats.pendingOrders,
                                totalRevenue: data.stats.totalRevenue,
                                avgOrderValue: data.stats.avgOrderValue,
                                orderGrowth: data.stats.orderGrowth,
                                revenueGrowth: data.stats.revenueGrowth
                            };
                        } else {
                            console.error('Error fetching orders:', data.message);
                        }
                        this.isLoading = false;
                    })
                    .catch(error => {
                        console.error('Error fetching orders:', error);
                        this.isLoading = false;
                    });
            },

            toggleOrderItems(order) {
                // If items aren't loaded yet, fetch them
                if (!order.items && !order.itemsLoading) {
                    order.itemsLoading = true;
                    
                    fetch(`/admin/orders/${order.id}/items`, {
                            headers: {
                                'Accept': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest'
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            order.items = data.items;
                            order.subtotal = data.subtotal;
                            order.shipping_cost = data.shipping_cost;
                            order.tax = data.tax;
                            order.discount = data.discount;
                            order.itemsLoading = false;
                            order.showItems = true;
                        })
                        .catch(error => {
                            console.error('Error fetching order items:', error);
                            order.itemsLoading = false;
                        });
                } else {
                    // Toggle visibility
                    order.showItems = !order.showItems;
                }
            },
            
            showInvoice(order) {
                // Set the invoice order and show modal
                const modal = document.querySelector('[x-data="{ showInvoiceModal: false, invoiceOrder: null }"]').__x.$data;
                modal.invoiceOrder = order;
                modal.showInvoiceModal = true;
            },
            
            printInvoice() {
                const printContent = document.getElementById('invoice-content').innerHTML;
                const originalContent = document.body.innerHTML;
                
                document.body.innerHTML = `
                    <html>
                        <head>
                            <title>Invoice</title>
                            <style>
                                @media print {
                                    body { font-family: Arial, sans-serif; margin: 20px; }
                                    table { width: 100%; border-collapse: collapse; }
                                    th, td { padding: 8px; text-align: left; }
                                    th { background-color: #f3f4f6; }
                                }
                            </style>
                        </head>
                        <body>
                            ${printContent}
                        </body>
                    </html>
                `;
                
                window.print();
                document.body.innerHTML = originalContent;
                
                // Re-initialize Alpine.js after printing
                window.Alpine.initTree(document.body);
            },
            
            downloadInvoicePDF() {
                const orderId = this.invoiceOrder?.id;
                if (orderId) {
                    window.location.href = `/admin/orders/${orderId}/invoice/pdf`;
                }
            },
            
            updateOrderStatus(order, newStatus) {
                // Set the order and status in modal
                const modal = document.querySelector('[x-data="{ showModal: false, order: null, newStatus: \'\', statusNote: \'\' }"]').__x.$data;
                modal.order = order;
                modal.newStatus = newStatus;
                modal.statusNote = '';
                modal.showModal = true;
            },
            
            submitStatusUpdate() {
                const modal = document.querySelector('[x-data="{ showModal: false, order: null, newStatus: \'\', statusNote: \'\' }"]').__x.$data;
                const order = modal.order;
                const newStatus = modal.newStatus;
                const statusNote = modal.statusNote;
                
                if (!order || !newStatus) return;
                
                // Get CSRF token
                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                
                fetch(`/admin/orders/${order.id}/status`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        status: newStatus,
                        note: statusNote
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Update order in the list
                        const orderIndex = this.orders.findIndex(o => o.id === order.id);
                        if (orderIndex !== -1) {
                            this.orders[orderIndex].status = newStatus;
                        }
                        
                        // Dispatch event to refresh other parts of UI if needed
                        window.dispatchEvent(new CustomEvent('order-status-updated', {
                            detail: { orderId: order.id, status: newStatus }
                        }));
                        
                        // Show toast notification
                        this.showToast(`Order #${order.order_number} status updated to ${this.capitalizeFirstLetter(newStatus)}`);
                    } else {
                        console.error('Error updating order status:', data.message);
                    }
                    
                    // Close modal
                    modal.showModal = false;
                })
                .catch(error => {
                    console.error('Error updating order status:', error);
                    modal.showModal = false;
                });
            },
            
            confirmCancelOrder(order) {
                if (confirm(`Are you sure you want to cancel order #${order.order_number}? This action cannot be undone.`)) {
                    this.updateOrderStatus(order, 'cancelled');
                }
            },
            
            printOrderDetails(order) {
                window.open(`/admin/orders/${order.id}/print`, '_blank');
            },
            
            showToast(message, type = 'success') {
                // You can use any toast notification library here
                // or implement a custom one
                if (window.Toastify) {
                    window.Toastify({
                        text: message,
                        duration: 3000,
                        close: true,
                        gravity: "top",
                        position: "right",
                        backgroundColor: type === 'success' ? "#10B981" : "#EF4444",
                        stopOnFocus: true
                    }).showToast();
                } else {
                    alert(message);
                }
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
                    search: '',
                    min_price: '',
                    max_price: ''
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
                if (!dateString) return '';
                const date = new Date(dateString);
                const options = {
                    month: 'short',
                    day: 'numeric',
                    year: 'numeric'
                };
                return date.toLocaleDateString('en-US', options);
            },
            
            formatTime(dateString) {
                if (!dateString) return '';
                const date = new Date(dateString);
                return date.toLocaleTimeString('en-US', {
                    hour: '2-digit',
                    minute: '2-digit'
                });
            },

            formatNumber(number) {
                if (number === undefined || number === null) return '0.00';
                return parseFloat(number).toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ",");
            },
            
            capitalizeFirstLetter(string) {
                if (!string) return '';
                return string.charAt(0).toUpperCase() + string.slice(1);
            },
            
            getStatusClasses(status) {
                switch (status) {
                    case 'pending':
                        return 'bg-yellow-50 text-yellow-700 border border-yellow-200';
                    case 'processing':
                        return 'bg-blue-50 text-blue-700 border border-blue-200';
                    case 'shipped':
                        return 'bg-indigo-50 text-indigo-700 border border-indigo-200';
                    case 'delivered':
                        return 'bg-green-50 text-green-700 border border-green-200';
                    case 'cancelled':
                        return 'bg-red-50 text-red-700 border border-red-200';
                    default:
                        return 'bg-gray-50 text-gray-700 border border-gray-200';
                }
            },
            
            getStatusIcon(status) {
                switch (status) {
                    case 'pending':
                        return 'ri-time-line';
                    case 'processing':
                        return 'ri-loader-2-line';
                    case 'shipped':
                        return 'ri-truck-line';
                    case 'delivered':
                        return 'ri-check-double-line';
                    case 'cancelled':
                        return 'ri-close-circle-line';
                    default:
                        return 'ri-question-line';
                }
            },
            
            getPaymentStatusClasses(status) {
                switch (status) {
                    case 'paid':
                        return 'bg-green-50 text-green-700 border border-green-200';
                    case 'pending':
                        return 'bg-yellow-50 text-yellow-700 border border-yellow-200';
                    case 'failed':
                        return 'bg-red-50 text-red-700 border border-red-200';
                    case 'refunded':
                        return 'bg-purple-50 text-purple-700 border border-purple-200';
                    default:
                        return 'bg-gray-50 text-gray-700 border border-gray-200';
                }
            },
            
            getPaymentStatusIcon(status) {
                switch (status) {
                    case 'paid':
                        return 'ri-bank-card-line';
                    case 'pending':
                        return 'ri-time-line';
                    case 'failed':
                        return 'ri-error-warning-line';
                    case 'refunded':
                        return 'ri-refund-2-line';
                    default:
                        return 'ri-question-line';
                }
            }
        }
    }
</script>@extends('layouts.admin')

@section('title', 'Orders')

@section('content')
    <div class="container mx-auto" x-data="ordersData()">
        <!-- Page Header -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6">
            <div>
                <h1 class="text-2xl font-semibold text-gray-900">Orders</h1>
                <p class="mt-1 text-sm text-gray-500">Manage and track all customer orders</p>
            </div>
            <div class="mt-4 md:mt-0 flex space-x-3">
                <a href="#"
                    class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition-colors">
                    <i class="ri-download-line mr-2"></i>
                    Export
                </a>
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
                        <h3 class="text-2xl font-bold text-gray-900 mt-1" x-text="stats.totalOrders"></h3>
                    </div>
                    <div class="w-12 h-12 bg-primary/10 rounded-full flex items-center justify-center">
                        <i class="ri-shopping-cart-line text-xl text-primary"></i>
                    </div>
                </div>
                <div class="mt-3 flex items-center text-sm">
                    <span class="text-green-500 font-medium flex items-center">
                        <i class="ri-arrow-up-line mr-1"></i> <span x-text="stats.orderGrowth + '%'"></span>
                    </span>
                    <span class="text-gray-500 ml-2">from last month</span>
                </div>
            </div>

            <!-- Pending Orders -->
            <div class="bg-white rounded-lg border border-gray-200 shadow-sm p-5">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Pending Orders</p>
                        <h3 class="text-2xl font-bold text-gray-900 mt-1" x-text="stats.pendingOrders"></h3>
                    </div>
                    <div class="w-12 h-12 bg-yellow-50 rounded-full flex items-center justify-center">
                        <i class="ri-time-line text-xl text-yellow-600"></i>
                    </div>
                </div>
                <div class="mt-3 flex items-center text-sm">
                    <span class="text-yellow-500 font-medium flex items-center">
                        <i class="ri-information-line mr-1"></i> Need attention
                    </span>
                </div>
            </div>

            <!-- Total Revenue -->
            <div class="bg-white rounded-lg border border-gray-200 shadow-sm p-5">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Total Revenue</p>
                        <h3 class="text-2xl font-bold text-gray-900 mt-1">
                            <span x-text="'€' + formatNumber(stats.totalRevenue)"></span>
                        </h3>
                    </div>
                    <div class="w-12 h-12 bg-green-50 rounded-full flex items-center justify-center">
                        <i class="ri-money-euro-circle-line text-xl text-green-600"></i>
                    </div>
                </div>
                <div class="mt-3 flex items-center text-sm">
                    <span class="text-green-500 font-medium flex items-center">
                        <i class="ri-arrow-up-line mr-1"></i> <span x-text="stats.revenueGrowth + '%'"></span>
                    </span>
                    <span class="text-gray-500 ml-2">from last month</span>
                </div>
            </div>

            <!-- Average Order Value -->
            <div class="bg-white rounded-lg border border-gray-200 shadow-sm p-5">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Avg. Order Value</p>
                        <h3 class="text-2xl font-bold text-gray-900 mt-1">
                            <span x-text="'€' + formatNumber(stats.avgOrderValue)"></span>
                        </h3>
                    </div>
                    <div class="w-12 h-12 bg-blue-50 rounded-full flex items-center justify-center">
                        <i class="ri-bar-chart-line text-xl text-blue-600"></i>
                    </div>
                </div>
                <div class="mt-3 flex items-center text-sm">
                    <span class="text-blue-500 font-medium flex items-center">
                        <i class="ri-line-chart-line mr-1"></i> Customer value
                    </span>
                </div>
            </div>
        </div>

        <!-- Filter Section -->
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm mb-6 transition-all duration-300 hover:shadow-md"
            x-show="showFilters" x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 transform -translate-y-4"
            x-transition:enter-end="opacity-100 transform translate-y-0"
            x-transition:leave="transition ease-in duration-300"
            x-transition:leave-start="opacity-100 transform translate-y-0"
            x-transition:leave-end="opacity-0 transform -translate-y-4">
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
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
                        <!-- Status Filter -->
                        <div class="relative">
                            <label for="status" class="block text-sm font-medium text-gray-700 mb-1.5">Status</label>
                            <div class="relative rounded-lg border border-gray-200">
                                <select id="status" name="status" x-model="filters.status"
                                    class="block w-full pl-4 pr-10 py-2.5 rounded-lg border-gray-200 bg-gray-50 focus:border-primary focus:ring focus:ring-primary/20 focus:ring-opacity-50 text-sm transition-all shadow-sm">
                                    <option value="">All Statuses</option>
                                    <option value="pending">Pending</option>
                                    <option value="processing">Processing</option>
                                    <option value="shipped">Shipped</option>
                                    <option value="delivered">Delivered</option>
                                    <option value="cancelled">Cancelled</option>
                                </select>
                            </div>
                        </div>

                        <!-- Payment Status Filter -->
                        <div class="relative">
                            <label for="payment_status" class="block text-sm font-medium text-gray-700 mb-1.5">Payment
                                Status</label>
                            <div class="relative rounded-lg border border-gray-200">
                                <select id="payment_status" name="payment_status" x-model="filters.payment_status"
                                    class="block w-full pl-4 pr-10 py-2.5 rounded-lg border-gray-200 bg-gray-50 focus:border-primary focus:ring focus:ring-primary/20 focus:ring-opacity-50 text-sm transition-all shadow-sm">
                                    <option value="">All Payment Statuses</option>
                                    <option value="paid">Paid</option>
                                    <option value="pending">Pending</option>
                                    <option value="failed">Failed</option>
                                    <option value="refunded">Refunded</option>
                                </select>
                            </div>
                        </div>

                        <!-- Date Range Filter -->
                        <div class="relative col-span-1 md:col-span-2 lg:col-span-1">
                            <label for="date_range" class="block text-sm font-medium text-gray-700 mb-1.5">Date Range</label>
                            <div class="grid grid-cols-2 gap-3">
                                <div class="relative rounded-lg border border-gray-200">
                                    <input type="date" id="date_from" name="date_from" x-model="filters.date_from"
                                        class="block w-full px-4 py-2.5 rounded-lg border-gray-200 bg-gray-50 focus:border-primary focus:ring focus:ring-primary/20 focus:ring-opacity-50 text-sm transition-all shadow-sm">
                                </div>
                                <div class="relative rounded-lg border border-gray-200">
                                    <input type="date" id="date_to" name="date_to" x-model="filters.date_to"
                                        class="block w-full px-4 py-2.5 rounded-lg border-gray-200 bg-gray-50 focus:border-primary focus:ring focus:ring-primary/20 focus:ring-opacity-50 text-sm transition-all shadow-sm">
                                </div>
                            </div>
                        </div>

                        <!-- Search Filter -->
                        <div class="relative md:col-span-2 lg:col-span-2">
                            <label for="search" class="block text-sm font-medium text-gray-700 mb-1.5">Search
                                Orders</label>
                            <div class="relative rounded-lg border border-gray-200">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="ri-search-line text-gray-400"></i>
                                </div>
                                <input type="text" id="search" name="search" x-model="filters.search"
                                    class="block w-full pl-10 pr-4 py-2.5 rounded-lg border-gray-200 bg-gray-50 focus:border-primary focus:ring focus:ring-primary/20 focus:ring-opacity-50 text-sm transition-all shadow-sm"
                                    placeholder="Order #, customer name, email...">
                            </div>
                        </div>

                        <!-- Price Range Filter -->
                        <div class="relative col-span-1">
                            <label for="price_range" class="block text-sm font-medium text-gray-700 mb-1.5">Price Range
                                (€)</label>
                            <div class="grid grid-cols-2 gap-3">
                                <div class="relative rounded-lg border border-gray-200">
                                    <input type="number" id="min_price" name="min_price" x-model="filters.min_price"
                                        class="block w-full px-4 py-2.5 rounded-lg border-gray-200 bg-gray-50 focus:border-primary focus:ring focus:ring-primary/20 focus:ring-opacity-50 text-sm transition-all shadow-sm"
                                        placeholder="Min">
                                </div>
                                <div class="relative rounded-lg border border-gray-200">
                                    <input type="number" id="max_price" name="max_price" x-model="filters.max_price"
                                        class="block w-full px-4 py-2.5 rounded-lg border-gray-200 bg-gray-50 focus:border-primary focus:ring focus:ring-primary/20 focus:ring-opacity-50 text-sm transition-all shadow-sm"
                                        placeholder="Max">
                                </div>
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
                <h3 class="text-lg font-medium text-gray-900">Order Management</h3>
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
                                Order</th>
                            <th scope="col"
                                class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-b border-gray-200">
                                Customer</th>
                            <th scope="col"
                                class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-b border-gray-200">
                                Date</th>
                            <th scope="col"
                                class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-b border-gray-200">
                                Status</th>
                            <th scope="col"
                                class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-b border-gray-200">
                                Payment</th>
                            <th scope="col"
                                class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-b border-gray-200">
                                Items</th>
                            <th scope="col"
                                class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-b border-gray-200">
                                Total</th>
                            <th scope="col"
                                class="px-6 py-4 text-right text-xs font-medium text-gray-500 uppercase tracking-wider border-b border-gray-200">
                                Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <template x-for="order in orders" :key="order.id">
                            <tr class="hover:bg-gray-50 transition-colors" :class="{'bg-blue-50/30': order.isNew}">
                                <td class="px-6 py-5 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 mr-3">
                                            <span class="w-10 h-10 rounded-md bg-primary/10 flex items-center justify-center">
                                                <i class="ri-shopping-bag-line text-primary text-lg"></i>
                                            </span>
                                        </div>
                                        <div>
                                            <div class="text-sm font-medium text-gray-900">#<span x-text="order.order_number"></span></div>
                                            <div class="text-xs text-gray-500" x-show="order.isNew">
                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800">
                                                    <i class="ri-notification-badge-line mr-1"></i> New
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-5">
                                    <div class="text-sm font-medium text-gray-900" x-text="order.customer.name"></div>
                                    <div class="text-xs text-gray-500" x-text="order.customer.email"></div>
                                </td>
                                <td class="px-6 py-5 whitespace-nowrap">
                                    <div class="text-sm text-gray-900" x-text="formatDate(order.created_at)"></div>
                                    <div class="text-xs text-gray-500" x-text="formatTime(order.created_at)"></div>
                                </td>
                                <td class="px-6 py-5 whitespace-nowrap">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium"
                                        :class="getStatusClasses(order.status)">
                                        <i class="mr-1" :class="getStatusIcon(order.status)"></i>
                                        <span x-text="capitalizeFirstLetter(order.status)"></span>
                                    </span>
                                </td>
                                <td class="px-6 py-5 whitespace-nowrap">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium"
                                        :class="getPaymentStatusClasses(order.payment_status)">
                                        <i class="mr-1" :class="getPaymentStatusIcon(order.payment_status)"></i>
                                        <span x-text="capitalizeFirstLetter(order.payment_status)"></span>
                                    </span>
                                </td>
                                <td class="px-6 py-5 whitespace-nowrap">
                                    <span class="flex items-center">
                                        <span class="text-sm font-medium text-gray-900" x-text="order.items_count"></span>
                                        <span class="w-6 h-6 ml-2 bg-gray-100 rounded-full flex items-center justify-center cursor-pointer"
                                            @click="toggleOrderItems(order)" x-show="order.items_count > 0">
                                            <i class="ri-eye-line text-gray-500 text-xs"></i>
                                        </span>
                                    </span>
                                </td>
                                <td class="px-6 py-5 whitespace-nowrap">
                                    <span class="text-sm font-medium text-gray-900" x-text="'€' + formatNumber(order.total)"></span>
                                </td>
                                <td class="px-6 py-5 whitespace-nowrap text-right">
                                    <div class="flex justify-end gap-2">
                                        <div class="relative" x-data="{ open: false }">
                                            <button @click="open = !open" type="button"
                                                class="inline-flex items-center px-3 py-1.5 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition-colors">
                                                <i class="ri-more-2-fill mr-1"></i>
                                                Actions
                                            </button>
                                            <div x-show="open" @click.away="open = false"
                                                class="origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 focus:outline-none z-10"
                                                x-transition:enter="transition ease-out duration-100"
                                                x-transition:enter-start="transform opacity-0 scale-95"
                                                x-transition:enter-end="transform opacity-100 scale-100"
                                                x-transition:leave="transition ease-in duration-75"
                                                x-transition:leave-start="transform opacity-100 scale-100"
                                                x-transition:leave-end="transform opacity-0 scale-95">
                                                <div class="py-1" role="menu" aria-orientation="vertical">
                                                    <a href="#" @click.prevent="updateOrderStatus(order, 'processing')"
                                                        class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                                                        role="menuitem" x-show="order.status === 'pending'">
                                                        <i class="ri-loader-2-line mr-3 text-gray-500"></i>
                                                        Mark as Processing
                                                    </a>
                                                    <a href="#" @click.prevent="updateOrderStatus(order, 'shipped')"
                                                        class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                                                        role="menuitem" x-show="order.status === 'processing'">
                                                        <i class="ri-truck-line mr-3 text-gray-500"></i>
                                                        Mark as Shipped
                                                    </a>
                                                    <a href="#" @click.prevent="updateOrderStatus(order, 'delivered')"
                                                        class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                                                        role="menuitem" x-show="order.status === 'shipped'">
                                                        <i class="ri-check-double-line mr-3 text-gray-500"></i>
                                                        Mark as Delivered
                                                    </a>
                                                    <a href="#" @click.prevent="showInvoice(order)"
                                                        class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                                                        role="menuitem">
                                                        <i class="ri-file-list-3-line mr-3 text-gray-500"></i>
                                                        View Invoice
                                                    </a>
                                                    <a href="#" @click.prevent="printOrderDetails(order)"
                                                        class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                                                        role="menuitem">
                                                        <i class="ri-printer-line mr-3 text-gray-500"></i>
                                                        Print Order
                                                    </a>
                                                    <a href="#" @click.prevent="confirmCancelOrder(order)"
                                                        class="flex items-center px-4 py-2 text-sm text-red-600 hover:bg-red-50"
                                                        role="menuitem" x-show="order.status !== 'cancelled' && order.status !== 'delivered'">
                                                        <i class="ri-close-circle-line mr-3 text-red-500"></i>
                                                        Cancel Order
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                        <a :href="'/admin/orders/' + order.id"
                                            class="inline-flex items-center px-3 py-1.5 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary hover:bg-primary/90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition-colors">
                                            <i class="ri-eye-line mr-1"></i>
                                            View
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            <!-- Order Items Expansion -->
                            <tr x-show="order.showItems" class="bg-gray-50 border-b border-gray-100">
                                <td colspan="8" class="px-6 py-4">
                                    <div class="overflow-x-auto">
                                        <table class="min-w-full divide-y divide-gray-200">
                                            <thead class="bg-gray-100">
                                                <tr>
                                                    <th scope="col"
                                                        class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                        Product</th>
                                                    <th scope="col"
                                                        class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                        SKU</th>
                                                    <th scope="col"
                                                        class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                        Price</th>
                                                    <th scope="col"
                                                        class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                        Quantity</th>
                                                    <th scope="col"
                                                        class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                        Subtotal</th>
                                                </tr>
                                            </thead>
                                            <tbody class="bg-white divide-y divide-gray-100">
                                                <template x-for="item in order.items" :key="item.id">
                                                    <tr>
                                                        <td class="px-4 py-3 whitespace-nowrap">
                                                            <div class="flex items-center">
                                                                <div
                                                                    class="h-10 w-10 rounded-md bg-gray-100 border border-gray-200 flex-shrink-0 overflow-hidden">
                                                                    <img :src="item.product_image || '/images/placeholder.jpg'"
                                                                        alt="" class="h-full w-full object-cover object-center">
                                                                </div>
                                                                <div class="ml-3">
                                                                    <div class="text-sm font-medium text-gray-900" x-text="item.product_name">
                                                                    </div>
                                                                    <div class="text-xs text-gray-500" x-show="item.variant">
                                                                        <span x-text="item.variant"></span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500" x-text="item.sku">
                                                        </td>
                                                        <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">
                                                            <span x-text="'€' + formatNumber(item.price)"></span>
                                                        </td>
                                                        <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500" x-text="item.quantity">
                                                        </td>
                                                        <td class="px-4 py-3 whitespace-nowrap text-sm font-medium text-gray-900">
                                                            <span x-text="'€' + formatNumber(item.price * item.quantity)"></span>
                                                        </td>
                                                    </tr>
                                                </template>
                                            </tbody>
                                            <tfoot class="bg-gray-50">
                                                <tr>
                                                    <td colspan="3" class="px-4 py-3 text-sm text-gray-500 text-right">
                                                    </td>
                                                    <td class="px-4 py-3 text-sm font-medium text-gray-900 text-right">
                                                        Total: