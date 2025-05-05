@extends('layouts.admin')

@section('title', 'Customers')

@section('content')
    <div class="container mx-auto" x-data="customersData()">
        <!-- Page Header -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6">
            <div>
                <h1 class="text-2xl font-semibold text-gray-900">Customers</h1>
                <p class="mt-1 text-sm text-gray-500">Manage and view all customer accounts</p>
            </div>
            <div class="mt-4 md:mt-0 flex space-x-3">
                
                <button type="button" @click="exportUsers()"
                    class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition-colors">
                    <i class="ri-download-line mr-2"></i>
                    Export Customers
                </button>

                <a href="{{ route('admin.customers.create') }}"
                    class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary hover:bg-primary/90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition-colors">
                    <i class="ri-add-line mr-2"></i>
                    Add Customer
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
            <!-- Total Customers -->
            <div class="bg-white rounded-lg border border-gray-200 shadow-sm p-5">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Total Customers</p>
                        <h3 class="text-2xl font-bold text-gray-900 mt-1" x-text="stats.total || 0">0</h3>
                    </div>
                    <div class="w-12 h-12 bg-primary/10 rounded-full flex items-center justify-center">
                        <i class="ri-user-line text-xl text-primary"></i>
                    </div>
                </div>
                <div class="mt-3 flex items-center text-sm">
                    <span class="text-green-500 font-medium flex items-center">
                        <i class="ri-arrow-up-line mr-1"></i> <span x-text="stats.growth || 0 + '%'">0%</span>
                    </span>
                    <span class="text-gray-500 ml-2">from last month</span>
                </div>
            </div>

            <!-- Verified Customers -->
            <div class="bg-white rounded-lg border border-gray-200 shadow-sm p-5">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Verified Customers</p>
                        <h3 class="text-2xl font-bold text-gray-900 mt-1" x-text="stats.verified || 0">0</h3>
                    </div>
                    <div class="w-12 h-12 bg-green-50 rounded-full flex items-center justify-center">
                        <i class="ri-check-line text-xl text-green-600"></i>
                    </div>
                </div>
                <div class="mt-3 flex items-center text-sm">
                    <span class="text-green-500 font-medium flex items-center">
                        <i class="ri-arrow-up-line mr-1"></i> <span x-text="getVerificationRate() + '%'">0%</span>
                    </span>
                    <span class="text-gray-500 ml-2">verification rate</span>
                </div>
            </div>

            <!-- Unverified Customers -->
            <div class="bg-white rounded-lg border border-gray-200 shadow-sm p-5">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Unverified Customers</p>
                        <h3 class="text-2xl font-bold text-gray-900 mt-1" x-text="stats.unverified || 0">0</h3>
                    </div>
                    <div class="w-12 h-12 bg-yellow-50 rounded-full flex items-center justify-center">
                        <i class="ri-time-line text-xl text-yellow-600"></i>
                    </div>
                </div>
                <div class="mt-3 flex items-center text-sm">
                    <span class="text-red-500 font-medium flex items-center">
                        <i class="ri-arrow-down-line mr-1"></i> <span x-text="getUnverifiedRate() + '%'">0%</span>
                    </span>
                    <span class="text-gray-500 ml-2">awaiting verification</span>
                </div>
            </div>

            <!-- New Customers -->
            <div class="bg-white rounded-lg border border-gray-200 shadow-sm p-5">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500">New This Month</p>
                        <h3 class="text-2xl font-bold text-gray-900 mt-1" x-text="stats.newCustomers || 0">0</h3>
                    </div>
                    <div class="w-12 h-12 bg-blue-50 rounded-full flex items-center justify-center">
                        <i class="ri-user-add-line text-xl text-blue-600"></i>
                    </div>
                </div>
                <div class="mt-3 flex items-center text-sm">
                    <span class="text-green-500 font-medium flex items-center">
                        <i class="ri-arrow-up-line mr-1"></i> <span x-text="stats.growth || 0 + '%'">0%</span>
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
                    <h3 class="text-lg font-medium text-gray-900">Filter Customers</h3>
                </div>
                <button @click="showFilters = false" class="text-gray-400 hover:text-gray-600 transition-colors">
                    <i class="ri-close-line text-lg"></i>
                </button>
            </div>

            <div class="p-5">
                <form id="customerFilterForm" @submit.prevent="applyFilters()">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5">
                        <!-- Role Filter -->
                        <div class="relative">
                            <label for="role" class="block text-sm font-medium text-gray-700 mb-1.5">Role</label>
                            <div class="relative rounded-lg border border-gray-200">
                                <select id="role" name="role" x-model="filters.role"
                                    class="block w-full pl-4 pr-10 py-2.5 rounded-lg border-gray-200 bg-gray-50 focus:border-primary focus:ring focus:ring-primary/20 focus:ring-opacity-50 text-sm transition-all shadow-sm">
                                    <option value="">All Roles</option>
                                    <option value="admin">Admin</option>
                                    <option value="manager">Manager</option>
                                    <option value="customer">Customer</option>
                                    <option value="support_agent">Support Agent</option>
                                </select>
                                <div
                                    class="absolute hidden inset-y-0 right-0 flex items-center pr-3 pointer-events-none text-gray-400">
                                    <i class="ri-arrow-down-s-line"></i>
                                </div>
                            </div>
                        </div>

                        <!-- Verification Status -->
                        <div class="relative">
                            <label for="verified" class="block text-sm font-medium text-gray-700 mb-1.5">Verification Status</label>
                            <div class="relative rounded-lg border border-gray-200">
                                <select id="verified" name="verified" x-model="filters.verified"
                                    class="block w-full pl-4 pr-10 py-2.5 rounded-lg border-gray-200 bg-gray-50 focus:border-primary focus:ring focus:ring-primary/20 focus:ring-opacity-50 text-sm transition-all shadow-sm">
                                    <option value="">All Statuses</option>
                                    <option value="verified">Verified</option>
                                    <option value="unverified">Unverified</option>
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
                                Customers</label>
                            <div class="relative rounded-lg border border-gray-200">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="ri-search-line text-gray-400"></i>
                                </div>
                                <input type="text" id="search" name="search" x-model="filters.search"
                                    class="block w-full pl-10 pr-4 py-2.5 rounded-lg border-gray-200 bg-gray-50 focus:border-primary focus:ring focus:ring-primary/20 focus:ring-opacity-50 text-sm transition-all shadow-sm"
                                    placeholder="Name, Email...">
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

        <!-- Customers Table -->
        <div class="bg-white rounded-lg border border-gray-200 shadow-sm overflow-hidden">
            <div class="flex justify-between items-center p-5 border-b border-gray-100">
                <h3 class="text-lg font-medium text-gray-900">Customer List</h3>
                <div class="flex items-center space-x-2">
                    <span class="text-sm text-gray-500">Showing <span x-text="customersCount"></span> of <span
                            x-text="stats.total"></span> customers</span>
                </div>
            </div>

            <div class="overflow-x-auto" x-show="!isLoading">
                <table class="w-full">
                    <thead>
                        <tr class="bg-gray-50">
                            <th scope="col"
                                class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-b border-gray-200 cursor-pointer"
                                @click="sortBy('name')">
                                <div class="flex items-center">
                                    <span>Name</span>
                                    <i class="ri-arrow-up-s-line ml-1" x-show="sortField === 'name' && sortDirection === 'asc'"></i>
                                    <i class="ri-arrow-down-s-line ml-1" x-show="sortField === 'name' && sortDirection === 'desc'"></i>
                                </div>
                            </th>
                            <th scope="col"
                                class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-b border-gray-200 cursor-pointer"
                                @click="sortBy('email')">
                                <div class="flex items-center">
                                    <span>Email</span>
                                    <i class="ri-arrow-up-s-line ml-1" x-show="sortField === 'email' && sortDirection === 'asc'"></i>
                                    <i class="ri-arrow-down-s-line ml-1" x-show="sortField === 'email' && sortDirection === 'desc'"></i>
                                </div>
                            </th>
                            <th scope="col"
                                class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-b border-gray-200 cursor-pointer"
                                @click="sortBy('role')">
                                <div class="flex items-center">
                                    <span>Role</span>
                                    <i class="ri-arrow-up-s-line ml-1" x-show="sortField === 'role' && sortDirection === 'asc'"></i>
                                    <i class="ri-arrow-down-s-line ml-1" x-show="sortField === 'role' && sortDirection === 'desc'"></i>
                                </div>
                            </th>
                            <th scope="col"
                                class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-b border-gray-200">
                                Verification</th>
                            <th scope="col"
                                class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-b border-gray-200 cursor-pointer"
                                @click="sortBy('created_at')">
                                <div class="flex items-center">
                                    <span>Registered</span>
                                    <i class="ri-arrow-up-s-line ml-1" x-show="sortField === 'created_at' && sortDirection === 'asc'"></i>
                                    <i class="ri-arrow-down-s-line ml-1" x-show="sortField === 'created_at' && sortDirection === 'desc'"></i>
                                </div>
                            </th>
                            <th scope="col"
                                class="px-6 py-4 text-right text-xs font-medium text-gray-500 uppercase tracking-wider border-b border-gray-200">
                                Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <template x-for="customer in customers" :key="customer.id">
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10 rounded-full bg-primary/10 flex items-center justify-center text-primary">
                                            <i class="ri-user-line"></i>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900" x-text="customer.name"></div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900" x-text="customer.email"></div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
                                        :class="getRoleBadgeClasses(customer.role)">
                                        <i :class="getRoleIcon(customer.role) + ' mr-1'"></i>
                                        <span x-text="capitalizeFirst(customer.role)"></span>
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span x-show="customer.email_verified_at" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        <i class="ri-checkbox-circle-line mr-1"></i>
                                        Verified
                                    </span>
                                    <span x-show="!customer.email_verified_at" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                        <i class="ri-time-line mr-1"></i>
                                        Unverified
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500" x-text="formatDate(customer.created_at)">
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex flex-wrap justify-end gap-2">
                                        <button @click="viewCustomerDetails(customer.id)"
                                            class="inline-flex items-center px-3 py-1.5 border border-black rounded-md shadow-sm text-sm font-medium text-white bg-black hover:bg-gray-50 hover:text-black focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition-colors">
                                            <i class="ri-eye-line mr-1"></i>
                                            View
                                        </button>
                                        <a :href="`/admin/customers/${customer.id}/edit`"
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
                                                    <button @click="verifyEmail(customer.id); open = false"
                                                        class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                                                        x-show="!customer.email_verified_at">
                                                        <i class="ri-mail-check-line mr-2"></i> Verify Email
                                                    </button>
                                                
                                                    <a :href="`/admin/impersonate/${customer.id}`"
                                                        class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                                        <i class="ri-user-follow-line mr-2"></i> Impersonate
                                                    </a>
                                                    <button @click="deleteCustomer(customer.id); open = false"
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
            <div class="text-center py-16" x-show="!isLoading && customers.length === 0">
                <div class="inline-flex items-center justify-center w-20 h-20 bg-gray-100 rounded-full mb-6">
                    <i class="ri-user-search-line text-3xl text-gray-400"></i>
                </div>
                <h3 class="text-xl font-medium text-primary mb-3">No customers found</h3>
                <p class="text-gray-500 mb-8 max-w-md mx-auto">There are no customers matching your criteria.</p>
                <button @click="resetFilters()"
                    class="inline-flex items-center px-6 py-3 border border-gray-300 bg-white text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                    <i class="ri-refresh-line mr-2"></i> Reset Filters
                </button>
            </div>

            <!-- Pagination -->
            <div class="px-6 py-4 border-t border-gray-200 flex items-center justify-between"
                x-show="!isLoading && customers.length > 0">
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
                            <span class="font-medium" x-text="Math.min(currentPage * perPage, totalCustomers)"></span>
                            of
                            <span class="font-medium" x-text="totalCustomers"></span>
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

        <!-- Customer Details Modal -->
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
                                <h3 class="text-lg leading-6 font-medium text-gray-900" x-text="modalCustomer ? 'Customer Details: ' + modalCustomer.name : 'Customer Details'"></h3>
                                <div class="mt-4" x-show="modalCustomer">
                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                        <!-- Customer Info -->
                                        <div class="col-span-1 bg-gray-50 p-4 rounded-lg">
                                            <div class="flex flex-col items-center">
                                                <div class="w-24 h-24 rounded-full bg-primary/10 flex items-center justify-center text-primary text-3xl mb-3">
                                                    <i class="ri-user-line"></i>
                                                </div>
                                                <h4 class="text-lg font-medium" x-text="modalCustomer ? modalCustomer.name : ''"></h4>
                                                <p class="text-sm text-gray-500" x-text="modalCustomer ? modalCustomer.email : ''"></p>
                                                <div class="mt-2 flex space-x-2">
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
                                                        :class="modalCustomer ? getRoleBadgeClasses(modalCustomer.role) : ''">
                                                        <i :class="modalCustomer ? getRoleIcon(modalCustomer.role) + ' mr-1' : ''"></i>
                                                        <span x-text="modalCustomer ? capitalizeFirst(modalCustomer.role) : ''"></span>
                                                    </span>
                                                    <span x-show="modalCustomer && modalCustomer.email_verified_at" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                        <i class="ri-checkbox-circle-line mr-1"></i>
                                                        Verified
                                                    </span>
                                                    <span x-show="modalCustomer && !modalCustomer.email_verified_at" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                        <i class="ri-time-line mr-1"></i>
                                                        Unverified
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="mt-4">
                                                <h5 class="text-sm font-medium text-gray-700 mb-2">Contact Information</h5>
                                                <div class="space-y-2 text-sm">
                                                    <div class="flex items-center">
                                                        <i class="ri-mail-line text-gray-400 mr-2"></i>
                                                        <span x-text="modalCustomer ? modalCustomer.email : ''"></span>
                                                    </div>
                                                    <div class="flex items-start">
                                                        <i class="ri-map-pin-line text-gray-400 mr-2 mt-1"></i>
                                                        <span x-text="modalCustomer ? (modalCustomer.address ? `${modalCustomer.address}, ${modalCustomer.city || ''} ${modalCustomer.state || ''} ${modalCustomer.zip_code || ''}, ${modalCustomer.country || ''}` : 'No address provided') : ''"></span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="mt-4">
                                                <h5 class="text-sm font-medium text-gray-700 mb-2">Account Information</h5>
                                                <div class="space-y-2 text-sm">
                                                    <div class="flex items-center">
                                                        <i class="ri-calendar-line text-gray-400 mr-2"></i>
                                                        <span>Registered: </span>
                                                        <span class="ml-1" x-text="modalCustomer ? formatDate(modalCustomer.created_at) : ''"></span>
                                                    </div>
                                                    <div class="flex items-center">
                                                        <i class="ri-time-line text-gray-400 mr-2"></i>
                                                        <span>Last Updated: </span>
                                                        <span class="ml-1" x-text="modalCustomer ? formatDate(modalCustomer.updated_at) : ''"></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <!-- Orders & Activity -->
                                        <div class="col-span-2">
                                            <div class="mb-4">
                                                <h5 class="text-sm font-medium text-gray-700 mb-2">Recent Orders</h5>
                                                <div class="bg-white border border-gray-200 rounded-lg overflow-hidden">
                                                    <div x-show="modalCustomer && modalCustomer.orders && modalCustomer.orders.length > 0">
                                                        <table class="min-w-full divide-y divide-gray-200">
                                                            <thead class="bg-gray-50">
                                                                <tr>
                                                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Order ID</th>
                                                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody class="bg-white divide-y divide-gray-200">
                                                                <template x-for="order in modalCustomer ? (modalCustomer.orders || []) : []" :key="order.id">
                                                                    <tr>
                                                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900" x-text="order.id"></td>
                                                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500" x-text="formatDate(order.created_at)"></td>
                                                                        <td class="px-6 py-4 whitespace-nowrap">
                                                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full"
                                                                                :class="{
                                                                                    'bg-green-100 text-green-800': order.status === 'completed',
                                                                                    'bg-yellow-100 text-yellow-800': order.status === 'pending',
                                                                                    'bg-blue-100 text-blue-800': order.status === 'processing',
                                                                                    'bg-red-100 text-red-800': order.status === 'cancelled'
                                                                                }"
                                                                                x-text="capitalizeFirst(order.status)">
                                                                            </span>
                                                                        </td>
                                                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500" x-text="'$' + order.total_amount"></td>
                                                                    </tr>
                                                                </template>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                    <div x-show="modalCustomer && (!modalCustomer.orders || modalCustomer.orders.length === 0)" class="p-4 text-center text-sm text-gray-500">
                                                        No orders found for this customer.
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <div>
                                                <h5 class="text-sm font-medium text-gray-700 mb-2">Support Tickets</h5>
                                                <div class="bg-white border border-gray-200 rounded-lg overflow-hidden">
                                                    <div x-show="modalCustomer && modalCustomer.tickets && modalCustomer.tickets.length > 0">
                                                        <table class="min-w-full divide-y divide-gray-200">
                                                            <thead class="bg-gray-50">
                                                                <tr>
                                                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ticket ID</th>
                                                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Subject</th>
                                                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody class="bg-white divide-y divide-gray-200">
                                                                <template x-for="ticket in modalCustomer ? (modalCustomer.tickets || []) : []" :key="ticket.id">
                                                                    <tr>
                                                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900" x-text="ticket.id"></td>
                                                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500" x-text="ticket.subject"></td>
                                                                        <td class="px-6 py-4 whitespace-nowrap">
                                                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full"
                                                                                :class="{
                                                                                    'bg-green-100 text-green-800': ticket.status === 'resolved',
                                                                                    'bg-yellow-100 text-yellow-800': ticket.status === 'pending',
                                                                                    'bg-blue-100 text-blue-800': ticket.status === 'open',
                                                                                    'bg-gray-100 text-gray-800': ticket.status === 'closed'
                                                                                }"
                                                                                x-text="capitalizeFirst(ticket.status)">
                                                                            </span>
                                                                        </td>
                                                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500" x-text="formatDate(ticket.created_at)"></td>
                                                                    </tr>
                                                                </template>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                    <div x-show="modalCustomer && (!modalCustomer.tickets || modalCustomer.tickets.length === 0)" class="p-4 text-center text-sm text-gray-500">
                                                        No support tickets found for this customer.
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="mt-4 flex justify-center" x-show="!modalCustomer">
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
function customersData() {
    return {
        // State variables
        customers: [],
        stats: {
            total: 0,
            verified: 0,
            unverified: 0,
            newCustomers: 0,
            growth: 0
        },
        isLoading: true,
        showFilters: false,
        
        showModal: false,
        modalCustomer: null,
        
        currentPage: 1,
        lastPage: 1,
        totalCustomers: 0,
        perPage: 10,
        
        sortField: 'created_at',
        sortDirection: 'desc',
        
        filters: {
            role: '',
            verified: '',
            date_from: '',
            search: ''
        },
        
        get customersCount() {
            return this.customers.length;
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
        
        getVerificationRate() {
            if (!this.stats.total || this.stats.total === 0) return 0;
            return Math.round((this.stats.verified / this.stats.total) * 100);
        },
        
        getUnverifiedRate() {
            if (!this.stats.total || this.stats.total === 0) return 0;
            return Math.round((this.stats.unverified / this.stats.total) * 100);
        },
        
        getGrowthRate() {
            return this.stats.growth || 0;
        },
        
        init() {
            this.loadCustomers();
            this.loadStats();
            
            this.currentPage = 1;
            this.lastPage = 1;
        },
        
        async loadCustomers() {
            this.isLoading = true;
            
            try {
                const params = new URLSearchParams({
                    page: this.currentPage,
                    per_page: this.perPage,
                    sort_field: this.sortField,
                    sort_direction: this.sortDirection,
                    role: this.filters.role,
                    verified: this.filters.verified,
                    date_from: this.filters.date_from,
                    search: this.filters.search
                });
                
                const response = await fetch(`/admin/customers-data?${params.toString()}`);
                const data = await response.json();
                
                if (data.success) {
                    this.customers = data.data;
                    this.currentPage = data.pagination.current_page;
                    this.lastPage = data.pagination.last_page;
                    this.totalCustomers = data.pagination.total;
                } else {
                    console.error('Error loading customers:', data.message);
                    this.showToast('Error loading customers', 'error');
                }
            } catch (error) {
                console.error('Error loading customers:', error);
                this.showToast('Error loading customers', 'error');
            } finally {
                this.isLoading = false;
            }
        },
        
        async loadStats() {
            try {
                const response = await fetch('/admin/customers/statistics');
                const data = await response.json();
                
                if (data.success) {
                    this.stats = {
                        total: data.stats.total || 0,
                        verified: data.stats.verified || 0,
                        unverified: data.stats.unverified || 0,
                        newCustomers: data.stats.newCustomers || 0,
                        growth: data.stats.growth || 0
                    };
                    
                    console.log('Loaded stats:', this.stats);
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
                this.loadCustomers();
            }
        },
        
        nextPage() {
            if (this.currentPage < this.lastPage) {
                this.currentPage++;
                this.loadCustomers();
            }
        },
        
        goToPage(page) {
            if (page >= 1 && page <= this.lastPage) {
                this.currentPage = page;
                this.loadCustomers();
            }
        },
        
        sortBy(field) {
            if (this.sortField === field) {
                this.sortDirection = this.sortDirection === 'asc' ? 'desc' : 'asc';
            } else {
                this.sortField = field;
                this.sortDirection = 'desc';
            }
            
            this.loadCustomers();
        },
        
        applyFilters() {
            this.currentPage = 1; 
            this.loadCustomers();
        },
        
        resetFilters() {
            this.filters = {
                role: '',
                verified: '',
                date_from: '',
                search: ''
            };
            
            this.currentPage = 1;
            this.loadCustomers();
        },
        
        async viewCustomerDetails(customerId) {
            this.showModal = true;
            this.modalCustomer = null;
            
            try {
                const response = await fetch(`/admin/customers/${customerId}/details`);
                const data = await response.json();
                
                if (data.success) {
    const user = data.user;
    user.orders = Array.isArray(user.orders) ? user.orders : [];
            user.tickets = Array.isArray(user.tickets) ? user.tickets : [];
    
    // Set the customer data in the modal
    this.modalCustomer = user;
} else {
                    this.showToast('Error loading customer details', 'error');
                    this.showModal = false;
                }
            } catch (error) {
                console.error('Error loading customer details:', error);
                this.showToast('Error loading customer details', 'error');
                this.showModal = false;
            }
        },
        
        async verifyEmail(customerId) {
            try {
                const response = await fetch(`/admin/customers/${customerId}/verify-email`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                });
                
                const data = await response.json();
                
                if (data.success) {
                    this.showToast('Email verified successfully', 'success');
                    
                    // Update the customer in the list
                    const customerIndex = this.customers.findIndex(c => c.id === customerId);
                    if (customerIndex !== -1) {
                        this.customers[customerIndex].email_verified_at = data.verified_at;
                    }
                    
                    // Update stats
                    this.loadStats();
                } else {
                    this.showToast(data.message || 'Error verifying email', 'error');
                }
            } catch (error) {
                console.error('Error verifying email:', error);
                this.showToast('Error verifying email', 'error');
            }
        },
        
            deleteCustomer(customerId) {
                if (confirm('Are you sure you want to delete this customer? This action cannot be undone.')) {
                    this.performDeleteCustomer(customerId);
                }
            },
            
            async performDeleteCustomer(customerId) {
                try {
                    const response = await fetch(`/admin/customers/${customerId}`, {
                        method: 'DELETE',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        }
                    });
                    
                    const data = await response.json();
                    
                    if (data.success) {
                        this.showToast('Customer deleted successfully', 'success');
                        
                        this.loadCustomers();
                        this.loadStats();
                    } else {
                        if (data.related_records) {
                            let message = 'Cannot delete customer with related records: ';
                            if (data.related_records.orders) {
                                message += `${data.related_records.orders} orders`;
                            }
                            if (data.related_records.tickets) {
                                message += data.related_records.orders ? ` and ${data.related_records.tickets} tickets` : `${data.related_records.tickets} tickets`;
                            }
                            this.showToast(message, 'warning');
                        } else {
                            this.showToast(data.message || 'Error deleting customer', 'error');
                        }
                    }
                } catch (error) {
                    console.error('Error deleting customer:', error);
                    this.showToast('Error deleting customer', 'error');
                }
            },
        
        formatDate(dateString) {
            if (!dateString) return 'N/A';
            const date = new Date(dateString);
            return date.toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric' });
        },
        
        capitalizeFirst(string) {
            if (!string) return '';
            return string.charAt(0).toUpperCase() + string.slice(1).replace('_', ' ');
        },
        
        getRoleBadgeClasses(role) {
            const classes = {
                'admin': 'bg-red-100 text-red-800',
                'manager': 'bg-blue-100 text-blue-800',
                'customer': 'bg-green-100 text-green-800',
                'support_agent': 'bg-purple-100 text-purple-800'
            };
            
            return classes[role] || 'bg-gray-100 text-gray-800';
        },
        
        getRoleIcon(role) {
            const icons = {
                'admin': 'ri-shield-user-line',
                'manager': 'ri-user-settings-line',
                'customer': 'ri-user-line',
                'support_agent': 'ri-customer-service-line'
            };
            
            return icons[role] || 'ri-user-line';
        },
        
        showToast(message, type = 'success') {
            if (typeof Toast === 'function') {
                Toast(message, type);
            } else {
                alert(message);
            }
        },
        
        exportUsers() {
            const params = new URLSearchParams({
                role: this.filters.role,
                verified: this.filters.verified,
                date_from: this.filters.date_from,
                search: this.filters.search
            });
            
            window.location.href = `/admin/customers/export?${params.toString()}`;
        }
    };
}
</script>
