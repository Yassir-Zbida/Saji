@extends('layouts.admin')

@section('title', 'Coupons')

@section('content')
    <div class="container mx-auto" x-data="couponsData()">
        <!-- Page Header -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6">
            <div>
                <h1 class="text-2xl font-semibold text-gray-900">Coupons</h1>
                <p class="mt-1 text-sm text-gray-500">Manage and view all discount coupons</p>
            </div>
            <div class="mt-4 md:mt-0 flex space-x-3">
                
                <button type="button" @click="exportCoupons()"
                    class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition-colors">
                    <i class="ri-download-line mr-2"></i>
                    Export Coupons
                </button>

                <a href="{{ route('admin.coupons.create') }}"
                    class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary hover:bg-primary/90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition-colors">
                    <i class="ri-add-line mr-2"></i>
                    Add Coupon
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
            <!-- Total Coupons -->
            <div class="bg-white rounded-lg border border-gray-200 shadow-sm p-5">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Total Coupons</p>
                        <h3 class="text-2xl font-bold text-gray-900 mt-1" x-text="stats.total || 0">0</h3>
                    </div>
                    <div class="w-12 h-12 bg-primary/10 rounded-full flex items-center justify-center">
                        <i class="ri-coupon-line text-xl text-primary"></i>
                    </div>
                </div>
                <div class="mt-3 flex items-center text-sm">
                    <span class="text-green-500 font-medium flex items-center">
                        <i class="ri-arrow-up-line mr-1"></i> <span x-text="stats.growth || 0 + '%'">0%</span>
                    </span>
                    <span class="text-gray-500 ml-2">from last month</span>
                </div>
            </div>

            <!-- Active Coupons -->
            <div class="bg-white rounded-lg border border-gray-200 shadow-sm p-5">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Active Coupons</p>
                        <h3 class="text-2xl font-bold text-gray-900 mt-1" x-text="stats.active || 0">0</h3>
                    </div>
                    <div class="w-12 h-12 bg-green-50 rounded-full flex items-center justify-center">
                        <i class="ri-check-line text-xl text-green-600"></i>
                    </div>
                </div>
                <div class="mt-3 flex items-center text-sm">
                    <span class="text-green-500 font-medium flex items-center">
                        <i class="ri-arrow-up-line mr-1"></i> <span x-text="getActiveRate() + '%'">0%</span>
                    </span>
                    <span class="text-gray-500 ml-2">active rate</span>
                </div>
            </div>

            <!-- Expired Coupons -->
            <div class="bg-white rounded-lg border border-gray-200 shadow-sm p-5">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Expired Coupons</p>
                        <h3 class="text-2xl font-bold text-gray-900 mt-1" x-text="stats.expired || 0">0</h3>
                    </div>
                    <div class="w-12 h-12 bg-yellow-50 rounded-full flex items-center justify-center">
                        <i class="ri-time-line text-xl text-yellow-600"></i>
                    </div>
                </div>
                <div class="mt-3 flex items-center text-sm">
                    <span class="text-red-500 font-medium flex items-center">
                        <i class="ri-arrow-down-line mr-1"></i> <span x-text="getExpiredRate() + '%'">0%</span>
                    </span>
                    <span class="text-gray-500 ml-2">need renewal</span>
                </div>
            </div>

            <!-- Usage Rate -->
            <div class="bg-white rounded-lg border border-gray-200 shadow-sm p-5">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Usage Rate</p>
                        <h3 class="text-2xl font-bold text-gray-900 mt-1" x-text="stats.usageRate + '%' || '0%'">0%</h3>
                    </div>
                    <div class="w-12 h-12 bg-blue-50 rounded-full flex items-center justify-center">
                        <i class="ri-percent-line text-xl text-blue-600"></i>
                    </div>
                </div>
                <div class="mt-3 flex items-center text-sm">
                    <span class="text-green-500 font-medium flex items-center">
                        <i class="ri-arrow-up-line mr-1"></i> <span x-text="stats.usageGrowth || 0 + '%'">0%</span>
                    </span>
                    <span class="text-gray-500 ml-2">from previous month</span>
                </div>
            </div>
        </div>

        <!-- Filter Section -->
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm mb-6 transition-all duration-300 hover:shadow-md"
            x-show="showFilters" data-aos="fade-up">
            <div class="p-5 border-b border-gray-100 flex justify-between items-center">
                <div class="flex items-center">
                    <i class="ri-filter-3-line text-lg text-primary mr-3"></i>
                    <h3 class="text-lg font-medium text-gray-900">Filter Coupons</h3>
                </div>
                <button @click="showFilters = false" class="text-gray-400 hover:text-gray-600 transition-colors">
                    <i class="ri-close-line text-lg"></i>
                </button>
            </div>

            <div class="p-5">
                <form id="couponFilterForm" @submit.prevent="applyFilters()">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5">
                        <!-- Type Filter -->
                        <div class="relative">
                            <label for="type" class="block text-sm font-medium text-gray-700 mb-1.5">Coupon Type</label>
                            <div class="relative rounded-lg border border-gray-200">
                                <select id="type" name="type" x-model="filters.type"
                                    class="block w-full pl-4 pr-10 py-2.5 rounded-lg border-gray-200 bg-gray-50 focus:border-primary focus:ring focus:ring-primary/20 focus:ring-opacity-50 text-sm transition-all shadow-sm">
                                    <option value="">All Types</option>
                                    <option value="percentage">Percentage</option>
                                    <option value="fixed_amount">Fixed Amount</option>
                                </select>
                                <div
                                    class="absolute hidden inset-y-0 right-0 flex items-center pr-3 pointer-events-none text-gray-400">
                                    <i class="ri-arrow-down-s-line"></i>
                                </div>
                            </div>
                        </div>

                        <!-- Status Filter -->
                        <div class="relative">
                            <label for="status" class="block text-sm font-medium text-gray-700 mb-1.5">Status</label>
                            <div class="relative rounded-lg border border-gray-200">
                                <select id="status" name="status" x-model="filters.status"
                                    class="block w-full pl-4 pr-10 py-2.5 rounded-lg border-gray-200 bg-gray-50 focus:border-primary focus:ring focus:ring-primary/20 focus:ring-opacity-50 text-sm transition-all shadow-sm">
                                    <option value="">All Statuses</option>
                                    <option value="active">Active</option>
                                    <option value="inactive">Inactive</option>
                                    <option value="expired">Expired</option>
                                </select>
                                <div
                                    class="hidden absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none text-gray-400">
                                    <i class="ri-arrow-down-s-line"></i>
                                </div>
                            </div>
                        </div>

                        <!-- From Date Filter -->
                        <div>
                            <label for="date_from" class="block text-sm font-medium text-gray-700 mb-1.5">From
                                Date</label>
                            <div class="relative rounded-lg border border-gray-200">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="ri-calendar-line text-gray-400"></i>
                                </div>
                                <input type="date" id="date_from" name="date_from" x-model="filters.date_from"
                                    class="block w-full pl-10 pr-4 py-2.5 rounded-lg border-gray-200 bg-gray-50 focus:border-primary focus:ring focus:ring-primary/20 focus:ring-opacity-50 text-sm transition-all shadow-sm">
                            </div>
                        </div>

                        <!-- Search Filter -->
                        <div>
                            <label for="search" class="block text-sm font-medium text-gray-700 mb-1.5">Search
                                Coupons</label>
                            <div class="relative rounded-lg border border-gray-200">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="ri-search-line text-gray-400"></i>
                                </div>
                                <input type="text" id="search" name="search" x-model="filters.search"
                                    class="block w-full pl-10 pr-4 py-2.5 rounded-lg border-gray-200 bg-gray-50 focus:border-primary focus:ring focus:ring-primary/20 focus:ring-opacity-50 text-sm transition-all shadow-sm"
                                    placeholder="Code, Description...">
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

        <!-- Coupons Table -->
        <div class="bg-white rounded-lg border border-gray-200 shadow-sm overflow-hidden">
            <div class="flex justify-between items-center p-5 border-b border-gray-100">
                <h3 class="text-lg font-medium text-gray-900">Coupon List</h3>
                <div class="flex items-center space-x-2">
                    <span class="text-sm text-gray-500">Showing <span x-text="couponsCount"></span> of <span
                            x-text="stats.total"></span> coupons</span>
                </div>
            </div>

            <div class="overflow-x-auto" x-show="!isLoading">
                <table class="w-full">
                    <thead>
                        <tr class="bg-gray-50">
                            <th scope="col"
                                class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-b border-gray-200 cursor-pointer"
                                @click="sortBy('code')">
                                <div class="flex items-center">
                                    <span>Code</span>
                                    <i class="ri-arrow-up-s-line ml-1" x-show="sortField === 'code' && sortDirection === 'asc'"></i>
                                    <i class="ri-arrow-down-s-line ml-1" x-show="sortField === 'code' && sortDirection === 'desc'"></i>
                                </div>
                            </th>
                            <th scope="col"
                                class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-b border-gray-200 cursor-pointer"
                                @click="sortBy('type')">
                                <div class="flex items-center">
                                    <span>Type</span>
                                    <i class="ri-arrow-up-s-line ml-1" x-show="sortField === 'type' && sortDirection === 'asc'"></i>
                                    <i class="ri-arrow-down-s-line ml-1" x-show="sortField === 'type' && sortDirection === 'desc'"></i>
                                </div>
                            </th>
                            <th scope="col"
                                class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-b border-gray-200 cursor-pointer"
                                @click="sortBy('value')">
                                <div class="flex items-center">
                                    <span>Value</span>
                                    <i class="ri-arrow-up-s-line ml-1" x-show="sortField === 'value' && sortDirection === 'asc'"></i>
                                    <i class="ri-arrow-down-s-line ml-1" x-show="sortField === 'value' && sortDirection === 'desc'"></i>
                                </div>
                            </th>
                            <th scope="col"
                                class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-b border-gray-200">
                                Status</th>
                            <th scope="col"
                                class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-b border-gray-200 cursor-pointer"
                                @click="sortBy('usage_count')">
                                <div class="flex items-center">
                                    <span>Usage</span>
                                    <i class="ri-arrow-up-s-line ml-1" x-show="sortField === 'usage_count' && sortDirection === 'asc'"></i>
                                    <i class="ri-arrow-down-s-line ml-1" x-show="sortField === 'usage_count' && sortDirection === 'desc'"></i>
                                </div>
                            </th>
                            <th scope="col"
                                class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-b border-gray-200 cursor-pointer"
                                @click="sortBy('end_date')">
                                <div class="flex items-center">
                                    <span>Expiry</span>
                                    <i class="ri-arrow-up-s-line ml-1" x-show="sortField === 'end_date' && sortDirection === 'asc'"></i>
                                    <i class="ri-arrow-down-s-line ml-1" x-show="sortField === 'end_date' && sortDirection === 'desc'"></i>
                                </div>
                            </th>
                            <th scope="col"
                                class="px-6 py-4 text-right text-xs font-medium text-gray-500 uppercase tracking-wider border-b border-gray-200">
                                Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <template x-for="coupon in coupons" :key="coupon.id">
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10 rounded-full bg-primary/10 flex items-center justify-center text-primary">
                                            <i class="ri-coupon-line"></i>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900" x-text="coupon.code"></div>
                                            <div class="text-xs text-gray-500 max-w-xs truncate" x-text="coupon.description"></div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
                                        :class="getCouponTypeBadgeClasses(coupon.type)">
                                        <i :class="getCouponTypeIcon(coupon.type) + ' mr-1'"></i>
                                        <span x-text="capitalizeFirst(coupon.type === 'percentage' ? 'Percentage' : 'Fixed Amount')"></span>
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900" x-text="formatCouponValue(coupon)"></div>
                                    <template x-if="coupon.minimum_spend">
                                        <div class="text-xs text-gray-500">Min: $<span x-text="coupon.minimum_spend"></span></div>
                                    </template>
                                    <template x-if="coupon.maximum_discount && coupon.type === 'percentage'">
                                        <div class="text-xs text-gray-500">Max: $<span x-text="coupon.maximum_discount"></span></div>
                                    </template>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span x-show="isActive(coupon)" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        <i class="ri-checkbox-circle-line mr-1"></i>
                                        Active
                                    </span>
                                    <span x-show="isExpired(coupon)" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                        <i class="ri-time-line mr-1"></i>
                                        Expired
                                    </span>
                                    <span x-show="isUpcoming(coupon)" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        <i class="ri-time-line mr-1"></i>
                                        Upcoming
                                    </span>
                                    <span x-show="!coupon.is_active" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                        <i class="ri-archive-line mr-1"></i>
                                        Inactive
                                    </span>
                                    <span x-show="isExhausted(coupon)" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-orange-100 text-orange-800">
                                        <i class="ri-archive-line mr-1"></i>
                                        Exhausted
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">
                                        <span x-text="coupon.usage_count"></span>
                                        <template x-if="coupon.usage_limit">
                                            <span> / <span x-text="coupon.usage_limit"></span></span>
                                        </template>
                                    </div>
                                    <template x-if="coupon.usage_limit">
                                        <div class="w-full bg-gray-200 rounded-full h-1.5 mt-1">
                                            <div class="h-1.5 rounded-full bg-primary"
                                                :style="`width: ${Math.min(100, (coupon.usage_count / coupon.usage_limit) * 100)}%`"></div>
                                        </div>
                                    </template>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500" x-text="formatDate(coupon.end_date)">
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex flex-wrap justify-end gap-2">
                                        <button @click="viewCouponDetails(coupon.id)"
                                            class="inline-flex items-center px-3 py-1.5 border border-black rounded-md shadow-sm text-sm font-medium text-white bg-black hover:bg-gray-50 hover:text-black focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition-colors">
                                            <i class="ri-eye-line mr-1"></i>
                                            View
                                        </button>
                                        <a :href="`/admin/coupons/${coupon.id}/edit`"
                                            class="inline-flex items-center px-3 py-1.5 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition-colors">
                                            <i class="ri-edit-line mr-1"></i>
                                            Edit
                                        </a>
                                        <div class="relative" x-data="{ open: false }">
                                            <button @click="open = !open"
                                                class="inline-flex items-center px-3 py-2 border border-gray-300 rounded-md text-xs font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition-colors">
                                                <i class="ri-more-2-fill"></i>
                                            </button>
                                            <div x-show="open" @click.away="open = false"
                                                class="origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 focus:outline-none z-10"
                                                x-cloak>
                                                <div class="py-1" role="menu" aria-orientation="vertical">
                                                    <button @click="toggleCouponStatus(coupon.id, coupon.is_active); open = false"
                                                        class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                                        <template x-if="coupon.is_active">
                                                            <span><i class="ri-archive-line mr-2"></i> Deactivate</span>
                                                        </template>
                                                        <template x-if="!coupon.is_active">
                                                            <span><i class="ri-checkbox-circle-line mr-2"></i> Activate</span>
                                                        </template>
                                                    </button>
                                                    <button @click="deleteCoupon(coupon.id); open = false"
                                                        class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50">
                                                        <i class="ri-delete-bin-line mr-2"></i> Delete
                                                    </button>
                                                </div>
                                            </div>
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
            <div class="text-center py-16" x-show="!isLoading && coupons.length === 0">
                <div class="inline-flex items-center justify-center w-20 h-20 bg-gray-100 rounded-full mb-6">
                    <i class="ri-coupon-line text-3xl text-gray-400"></i>
                </div>
                <h3 class="text-xl font-medium text-primary mb-3">No coupons found</h3>
                <p class="text-gray-500 mb-8 max-w-md mx-auto">There are no coupons matching your criteria.</p>
                <button @click="resetFilters()"
                    class="inline-flex items-center px-6 py-3 border border-gray-300 bg-white text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                    <i class="ri-refresh-line mr-2"></i> Reset Filters
                </button>
            </div>

            <!-- Pagination -->
            <div class="px-6 py-4 border-t border-gray-200 flex items-center justify-between"
                x-show="!isLoading && coupons.length > 0">
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
                            <span class="font-medium" x-text="Math.min(currentPage * perPage, totalCoupons)"></span>
                            of
                            <span class="font-medium" x-text="totalCoupons"></span>
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
                                        'z-10 bg-primary text-black hover:text-white border-primary hover:bg-primary/90' :
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

        <!-- Coupon Details Modal -->
        <div class="fixed inset-0 z-50 overflow-y-auto" 
             x-show="showModal" 
             x-cloak>
            <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 transition-opacity" aria-hidden="true" x-show="showModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
                    <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
                </div>

                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full"
                     x-show="showModal"
                     x-transition:enter="ease-out duration-300"
                     x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                     x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                     x-transition:leave="ease-in duration-200"
                     x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                     x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
                    
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                                <h3 class="text-lg leading-6 font-medium text-gray-900" x-text="modalCoupon ? 'Coupon Details: ' + modalCoupon.code : 'Coupon Details'"></h3>
                                <div class="mt-4" x-show="modalCoupon">
                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                        <!-- Coupon Info -->
                                        <div class="col-span-1 bg-gray-50 p-4 rounded-lg">
                                            <div class="flex flex-col items-center">
                                                <div class="w-24 h-24 rounded-full bg-primary/10 flex items-center justify-center text-primary text-3xl mb-3">
                                                    <i class="ri-coupon-line"></i>
                                                </div>
                                                <h4 class="text-lg font-medium" x-text="modalCoupon ? modalCoupon.code : ''"></h4>
                                                <div class="mt-2 flex space-x-2">
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
                                                        :class="modalCoupon ? getCouponTypeBadgeClasses(modalCoupon.type) : ''">
                                                        <i :class="modalCoupon ? getCouponTypeIcon(modalCoupon.type) + ' mr-1' : ''"></i>
                                                        <span x-text="modalCoupon ? (modalCoupon.type === 'percentage' ? 'Percentage' : 'Fixed Amount') : ''"></span>
                                                    </span>
                                                    <span x-show="modalCoupon && isActive(modalCoupon)" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                        <i class="ri-checkbox-circle-line mr-1"></i>
                                                        Active
                                                    </span>
                                                    <span x-show="modalCoupon && isExpired(modalCoupon)" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                        <i class="ri-time-line mr-1"></i>
                                                        Expired
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="mt-4">
                                                <h5 class="text-sm font-medium text-gray-700 mb-2">Coupon Value</h5>
                                                <div class="space-y-2 text-sm">
                                                    <div class="flex items-center">
                                                        <i class="ri-coupon-line text-gray-400 mr-2"></i>
                                                        <span x-text="modalCoupon ? formatCouponValue(modalCoupon) : ''"></span>
                                                    </div>
                                                    <div class="flex items-center" x-show="modalCoupon && modalCoupon.minimum_spend">
                                                        <i class="ri-money-dollar-circle-line text-gray-400 mr-2"></i>
                                                        <span>Minimum spend: $<span x-text="modalCoupon ? modalCoupon.minimum_spend : ''"></span></span>
                                                    </div>
                                                    <div class="flex items-center" x-show="modalCoupon && modalCoupon.maximum_discount && modalCoupon.type === 'percentage'">
                                                        <i class="ri-money-dollar-circle-line text-gray-400 mr-2"></i>
                                                        <span>Maximum discount: $<span x-text="modalCoupon ? modalCoupon.maximum_discount : ''"></span></span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="mt-4">
                                                <h5 class="text-sm font-medium text-gray-700 mb-2">Usage Information</h5>
                                                <div class="space-y-2 text-sm">
                                                    <div class="flex items-center">
                                                        <i class="ri-bar-chart-line text-gray-400 mr-2"></i>
                                                        <span>Used: <span x-text="modalCoupon ? modalCoupon.usage_count : 0"></span> times</span>
                                                    </div>
                                                    <div class="flex items-center" x-show="modalCoupon && modalCoupon.usage_limit">
                                                        <i class="ri-bar-chart-line text-gray-400 mr-2"></i>
                                                        <span>Limit: <span x-text="modalCoupon ? modalCoupon.usage_limit : ''"></span> uses</span>
                                                    </div>
                                                    <div class="flex items-center">
                                                        <i class="ri-user-line text-gray-400 mr-2"></i>
                                                        <span>Individual use only: <span x-text="modalCoupon && modalCoupon.individual_use ? 'Yes' : 'No'"></span></span>
                                                    </div>
                                                    <div class="flex items-center">
                                                        <i class="ri-price-tag-3-line text-gray-400 mr-2"></i>
                                                        <span>Exclude sale items: <span x-text="modalCoupon && modalCoupon.exclude_sale_items ? 'Yes' : 'No'"></span></span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="mt-4">
                                                <h5 class="text-sm font-medium text-gray-700 mb-2">Validity Period</h5>
                                                <div class="space-y-2 text-sm">
                                                    <div class="flex items-center">
                                                        <i class="ri-calendar-line text-gray-400 mr-2"></i>
                                                        <span>Start Date: <span x-text="modalCoupon ? formatDate(modalCoupon.start_date) : ''"></span></span>
                                                    </div>
                                                    <div class="flex items-center">
                                                        <i class="ri-calendar-line text-gray-400 mr-2"></i>
                                                        <span>End Date: <span x-text="modalCoupon ? formatDate(modalCoupon.end_date) : ''"></span></span>
                                                    </div>
                                                    <div class="flex items-center">
                                                        <i class="ri-time-line text-gray-400 mr-2"></i>
                                                        <span>Created: <span x-text="modalCoupon ? formatDate(modalCoupon.created_at) : ''"></span></span>
                                                    </div>
                                                    <div class="flex items-center">
                                                        <i class="ri-time-line text-gray-400 mr-2"></i>
                                                        <span>Last Updated: <span x-text="modalCoupon ? formatDate(modalCoupon.updated_at) : ''"></span></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <!-- Usage & Orders -->
                                        <div class="col-span-2">
                                            <div class="mb-4">
                                                <h5 class="text-sm font-medium text-gray-700 mb-2">Usage Statistics</h5>
                                                <div class="bg-white border border-gray-200 rounded-lg overflow-hidden p-4">
                                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                                        <div class="bg-gray-50 p-4 rounded-lg">
                                                            <div class="text-sm text-gray-500">Total Uses</div>
                                                            <div class="text-2xl font-bold" x-text="modalCoupon ? modalCoupon.usage_count : 0"></div>
                                                        </div>
                                                        <div class="bg-gray-50 p-4 rounded-lg">
                                                            <div class="text-sm text-gray-500">Remaining Uses</div>
                                                            <div class="text-2xl font-bold" x-text="modalCoupon && modalCoupon.usage_limit ? Math.max(0, modalCoupon.usage_limit - modalCoupon.usage_count) : '∞'"></div>
                                                        </div>
                                                        <div class="bg-gray-50 p-4 rounded-lg">
                                                            <div class="text-sm text-gray-500">Usage Rate</div>
                                                            <div class="text-2xl font-bold" x-text="modalCoupon && modalCoupon.usage_limit ? Math.round((modalCoupon.usage_count / modalCoupon.usage_limit) * 100) + '%' : 'N/A'"></div>
                                                        </div>
                                                    </div>

                                                    <template x-if="modalCoupon && modalCoupon.usage_limit">
                                                        <div class="mt-4">
                                                            <div class="text-sm text-gray-500 mb-1">Usage Progress</div>
                                                            <div class="w-full bg-gray-200 rounded-full h-2.5">
                                                                <div class="h-2.5 rounded-full bg-primary"
                                                                    :style="`width: ${Math.min(100, (modalCoupon.usage_count / modalCoupon.usage_limit) * 100)}%`"></div>
                                                            </div>
                                                            <div class="flex justify-between text-xs text-gray-500 mt-1">
                                                                <span>0</span>
                                                                <span x-text="modalCoupon.usage_limit"></span>
                                                            </div>
                                                        </div>
                                                    </template>
                                                </div>
                                            </div>
                                            
                                            <div>
                                                <h5 class="text-sm font-medium text-gray-700 mb-2">Recent Orders Using This Coupon</h5>
                                                <div class="bg-white border border-gray-200 rounded-lg overflow-hidden">
                                                    <div x-show="modalCoupon && modalCoupon.orders && modalCoupon.orders.length > 0">
                                                        <table class="min-w-full divide-y divide-gray-200">
                                                            <thead class="bg-gray-50">
                                                                <tr>
                                                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Order ID</th>
                                                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</th>
                                                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Discount</th>
                                                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody class="bg-white divide-y divide-gray-200">
                                                                <template x-for="order in modalCoupon.orders" :key="order.id">
                                                                    <tr>
                                                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900" x-text="order.id"></td>
                                                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500" x-text="order.customer_name"></td>
                                                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500" x-text="formatDate(order.created_at)"></td>
                                                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500" x-text="'$' + order.discount_amount"></td>
                                                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500" x-text="'$' + order.total_amount"></td>
                                                                    </tr>
                                                                </template>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                    <div x-show="!modalCoupon || !modalCoupon.orders || modalCoupon.orders.length === 0" class="p-4 text-center text-sm text-gray-500">
                                                        No orders found using this coupon.
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="mt-4" x-show="modalCoupon && modalCoupon.description">
                                                <h5 class="text-sm font-medium text-gray-700 mb-2">Description</h5>
                                                <div class="bg-white border border-gray-200 rounded-lg p-4">
                                                    <p class="text-sm text-gray-700" x-text="modalCoupon.description"></p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="mt-4 flex justify-center" x-show="!modalCoupon">
                                    <div class="animate-spin rounded-full h-10 w-10 border-b-2 border-primary"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button type="button" @click="showModal = false"
                            class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                            Close
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

<script>
function couponsData() {
    return {
        // State variables
        coupons: [],
        stats: {
            total: 0,
            active: 0,
            expired: 0,
            upcoming: 0,
            usageRate: 0,
            usageGrowth: 0,
            growth: 0
        },
        isLoading: true,
        showFilters: false,
        
        showModal: false,
        modalCoupon: null,
        
        currentPage: 1,
        lastPage: 1,
        totalCoupons: 0,
        perPage: 10,
        
        sortField: 'created_at',
        sortDirection: 'desc',
        
        filters: {
            type: '',
            status: '',
            date_from: '',
            search: ''
        },
        
        get couponsCount() {
            return this.coupons.length;
        },
        
        get paginationPages() {
            const pages = [];
            const maxPages = 5;
            
            if (this.lastPage <= maxPages) {
                for (let i = 1; i <= this.lastPage; i++) {
                    pages.push(i);
                }
            } else {
                let startPage = Math.max(1, this.currentPage - 2);
                let endPage = Math.min(this.lastPage, startPage + maxPages - 1);
                
                if (endPage - startPage < maxPages - 1) {
                    startPage = Math.max(1, endPage - maxPages + 1);
                }
                
                for (let i = startPage; i <= endPage; i++) {
                    pages.push(i);
                }
            }
            
            return pages;
        },
        
        getActiveRate() {
            if (!this.stats.total || this.stats.total === 0) return 0;
            return Math.round((this.stats.active / this.stats.total) * 100);
        },
        
        getExpiredRate() {
            if (!this.stats.total || this.stats.total === 0) return 0;
            return Math.round((this.stats.expired / this.stats.total) * 100);
        },
        
        init() {
            this.loadCoupons();
            this.loadStats();
            
            this.currentPage = 1;
            this.lastPage = 1;
        },
        
        async loadCoupons() {
            this.isLoading = true;
            
            try {
                const params = new URLSearchParams({
                    page: this.currentPage,
                    per_page: this.perPage,
                    sort_field: this.sortField,
                    sort_direction: this.sortDirection,
                    type: this.filters.type,
                    status: this.filters.status,
                    date_from: this.filters.date_from,
                    search: this.filters.search
                });
                
                const response = await fetch(`/admin/coupons-data?${params.toString()}`);
                const data = await response.json();
                
                if (data.success) {
                    this.coupons = data.data;
                    this.currentPage = data.pagination.current_page;
                    this.lastPage = data.pagination.last_page;
                    this.totalCoupons = data.pagination.total;
                } else {
                    console.error('Error loading coupons:', data.message);
                    this.showToast('Error loading coupons', 'error');
                }
            } catch (error) {
                console.error('Error loading coupons:', error);
                this.showToast('Error loading coupons', 'error');
            } finally {
                this.isLoading = false;
            }
        },
        
        async loadStats() {
            try {
                const response = await fetch('/admin/coupons/statistics');
                const data = await response.json();
                
                if (data.success) {
                    this.stats = {
                        total: data.stats.total || 0,
                        active: data.stats.active || 0,
                        expired: data.stats.expired || 0,
                        upcoming: data.stats.upcoming || 0,
                        usageRate: data.stats.usage_rate || 0,
                        usageGrowth: data.stats.usage_growth || 0,
                        growth: data.stats.growth || 0
                    };
                } else {
                    console.error('Error loading statistics:', data.message);
                }
            } catch (error) {
                console.error('Error loading statistics:', error);
            }
        },
        
        prevPage() {
            if (this.currentPage > 1) {
                this.currentPage--;
                this.loadCoupons();
            }
        },
        
        nextPage() {
            if (this.currentPage < this.lastPage) {
                this.currentPage++;
                this.loadCoupons();
            }
        },
        
        goToPage(page) {
            if (page >= 1 && page <= this.lastPage) {
                this.currentPage = page;
                this.loadCoupons();
            }
        },
        
        sortBy(field) {
            if (this.sortField === field) {
                this.sortDirection = this.sortDirection === 'asc' ? 'desc' : 'asc';
            } else {
                this.sortField = field;
                this.sortDirection = 'desc';
            }
            
            this.loadCoupons();
        },
        
        applyFilters() {
            this.currentPage = 1; 
            this.loadCoupons();
        },
        
        resetFilters() {
            this.filters = {
                type: '',
                status: '',
                date_from: '',
                search: ''
            };
            
            this.currentPage = 1;
            this.loadCoupons();
        },
        
        async viewCouponDetails(couponId) {
            this.showModal = true;
            this.modalCoupon = null;
            
            try {
                const response = await fetch(`/admin/coupons/${couponId}/details`);
                const data = await response.json();
                
                if (data.success) {
                    const coupon = data.coupon;
                    coupon.orders = Array.isArray(coupon.orders) ? coupon.orders : [];
                    
                    // Set the coupon data in the modal
                    this.modalCoupon = coupon;
                } else {
                    this.showToast('Error loading coupon details', 'error');
                    this.showModal = false;
                }
            } catch (error) {
                console.error('Error loading coupon details:', error);
                this.showToast('Error loading coupon details', 'error');
                this.showModal = false;
            }
        },
        
        async toggleCouponStatus(couponId, currentStatus) {
            try {
                const response = await fetch(`/admin/coupons/${couponId}/toggle-status`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                });
                
                const data = await response.json();
                
                if (data.success) {
                    this.showToast(`Coupon ${currentStatus ? 'deactivated' : 'activated'} successfully`, 'success');
                    
                    // Update the coupon in the list
                    const couponIndex = this.coupons.findIndex(c => c.id === couponId);
                    if (couponIndex !== -1) {
                        this.coupons[couponIndex].is_active = !currentStatus;
                    }
                    
                    // Update stats
                    this.loadStats();
                } else {
                    this.showToast(data.message || 'Error updating coupon status', 'error');
                }
            } catch (error) {
                console.error('Error toggling coupon status:', error);
                this.showToast('Error updating coupon status', 'error');
            }
        },
        
        deleteCoupon(couponId) {
            if (confirm('Are you sure you want to delete this coupon? This action cannot be undone.')) {
                this.performDeleteCoupon(couponId);
            }
        },
        
        async performDeleteCoupon(couponId) {
            try {
                const response = await fetch(`/admin/coupons/${couponId}`, {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                });
                
                const data = await response.json();
                
                if (data.success) {
                    this.showToast('Coupon deleted successfully', 'success');
                    
                    this.loadCoupons();
                    this.loadStats();
                } else {
                    this.showToast(data.message || 'Error deleting coupon', 'error');
                }
            } catch (error) {
                console.error('Error deleting coupon:', error);
                this.showToast('Error deleting coupon', 'error');
            }
        },
        
        formatDate(dateString) {
            if (!dateString) return 'N/A';
            const date = new Date(dateString);
            return date.toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric' });
        },
        
        formatCouponValue(coupon) {
            if (!coupon) return '';
            return coupon.type === 'percentage' ? `${coupon.value}% off` : `$${coupon.value} off`;
        },
        
        capitalizeFirst(string) {
            if (!string) return '';
            return string.charAt(0).toUpperCase() + string.slice(1).replace('_', ' ');
        },
        
        getCouponTypeBadgeClasses(type) {
            const classes = {
                'percentage': 'bg-purple-100 text-purple-800',
                'fixed_amount': 'bg-emerald-100 text-emerald-800'
            };
            
            return classes[type] || 'bg-gray-100 text-gray-800';
        },
        
        getCouponTypeIcon(type) {
            const icons = {
                'percentage': 'ri-percent-line',
                'fixed_amount': 'ri-money-dollar-circle-line'
            };
            
            return icons[type] || 'ri-coupon-line';
        },
        
        isActive(coupon) {
            if (!coupon.is_active) return false;
            
            const now = new Date();
            
            if (coupon.start_date && new Date(coupon.start_date) > now) return false;
            if (coupon.end_date && new Date(coupon.end_date) < now) return false;
            if (coupon.usage_limit && coupon.usage_count >= coupon.usage_limit) return false;
            
            return true;
        },
        
        isExpired(coupon) {
            return coupon.is_active && coupon.end_date && new Date(coupon.end_date) < new Date();
        },
        
        isUpcoming(coupon) {
            return coupon.is_active && coupon.start_date && new Date(coupon.start_date) > new Date();
        },
        
        isExhausted(coupon) {
            return coupon.is_active && coupon.usage_limit && coupon.usage_count >= coupon.usage_limit;
        },
        
        showToast(message, type = 'success') {
            if (typeof Toast === 'function') {
                Toast(message, type);
            } else {
                alert(message);
            }
        },
        
        exportCoupons() {
            const params = new URLSearchParams({
                type: this.filters.type,
                status: this.filters.status,
                date_from: this.filters.date_from,
                search: this.filters.search
            });
            
            window.location.href = `/admin/coupons/export?${params.toString()}`;
        }
    };
}
</script>