@extends('layouts.admin')

@section('title', 'Products')

@section('content')
    <div class="container mx-auto" x-data="productsData()">
        <!-- Page Header -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6">
            <div>
                <h1 class="text-2xl font-semibold text-gray-900">Products</h1>
                <p class="mt-1 text-sm text-gray-500">Manage and view all product inventory</p>
            </div>
            <div class="mt-4 md:mt-0 flex space-x-3">
                <a href="" @click.prevent="exportProducts()"
                    class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition-colors">
                    <i class="ri-download-line mr-2"></i>
                    Export
                </a>
                <a href="{{ route('admin.products.create') }}"
                    class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary hover:bg-primary/90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition-colors">
                    <i class="ri-add-line mr-2"></i>
                    Add Product
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
            <!-- Total Products -->
            <div class="bg-white rounded-lg border border-gray-200 shadow-sm p-5">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Total Products</p>
                        <h3 class="text-2xl font-bold text-gray-900 mt-1" x-text="stats.totalProducts">
                            {{ $totalProducts ?? 0 }}</h3>
                    </div>
                    <div class="w-12 h-12 bg-primary/10 rounded-full flex items-center justify-center">
                        <i class="ri-shopping-bag-line text-xl text-primary"></i>
                    </div>
                </div>
                <div class="mt-3 flex items-center text-sm">
                    <span class="text-green-500 font-medium flex items-center">
                        <i class="ri-arrow-up-line mr-1"></i> <span x-text="stats.productGrowth + '%'"></span>
                    </span>
                    <span class="text-gray-500 ml-2">from last month</span>
                </div>
            </div>

            <!-- Low Stock Products -->
            <div class="bg-white rounded-lg border border-gray-200 shadow-sm p-5">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Low Stock Products</p>
                        <h3 class="text-2xl font-bold text-gray-900 mt-1" x-text="stats.lowStockProducts">
                            {{ $lowStockProducts ?? 0 }}</h3>
                    </div>
                    <div class="w-12 h-12 bg-yellow-50 rounded-full flex items-center justify-center">
                        <i class="ri-alert-line text-xl text-yellow-600"></i>
                    </div>
                </div>
                <div class="mt-3 flex items-center text-sm">
                    <span class="text-yellow-500 font-medium flex items-center">
                        <i class="ri-information-line mr-1"></i> Need attention
                    </span>
                </div>
            </div>

            <!-- Active Products -->
            <div class="bg-white rounded-lg border border-gray-200 shadow-sm p-5">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Active Products</p>
                        <h3 class="text-2xl font-bold text-gray-900 mt-1" x-text="stats.activeProducts">
                            {{ $activeProducts ?? 0 }}</h3>
                    </div>
                    <div class="w-12 h-12 bg-green-50 rounded-full flex items-center justify-center">
                        <i class="ri-check-line text-xl text-green-600"></i>
                    </div>
                </div>
                <div class="mt-3 flex items-center text-sm">
                    <span class="text-green-500 font-medium flex items-center">
                        <i class="ri-arrow-up-line mr-1"></i> Active items
                    </span>
                </div>
            </div>

            <!-- Top Category -->
            <div class="bg-white rounded-lg border border-gray-200 shadow-sm p-5">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Categories</p>
                        <h3 class="text-2xl font-bold text-gray-900 mt-1" x-text="stats.categoryCount"></h3>
                    </div>
                    <div class="w-12 h-12 bg-blue-50 rounded-full flex items-center justify-center">
                        <i class="ri-folder-line text-xl text-blue-600"></i>
                    </div>
                </div>
                <div class="mt-3 flex items-center text-sm">
                    <span class="text-blue-500 font-medium" x-text="stats.topCategory"></span>
                    <span class="text-gray-500 ml-2">top category</span>
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
                    <h3 class="text-lg font-medium text-gray-900">Filter Products</h3>
                </div>
                <button @click="showFilters = false" class="text-gray-400 hover:text-gray-600 transition-colors">
                    <i class="ri-close-line text-lg"></i>
                </button>
            </div>

            <div class="p-5">
                <form id="productFilterForm" @submit.prevent="applyFilters()">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-5">
                        <!-- Category Filter -->
                        <div class="relative">
                            <label for="category" class="block text-sm font-medium text-gray-700 mb-1.5">Category</label>
                            <div class="relative rounded-lg border border-gray-200">
                                <select id="category" name="category" x-model="filters.category"
                                    class="block w-full pl-4 pr-10 py-2.5 rounded-lg border-gray-200 bg-gray-50 focus:border-primary focus:ring focus:ring-primary/20 focus:ring-opacity-50 text-sm transition-all shadow-sm">
                                    <option value="">All Categories</option>
                                    <template x-for="category in categories" :key="category.id">
                                        <option :value="category.id" x-text="category.name"></option>
                                    </template>
                                </select>
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
                                </select>
                            </div>
                        </div>

                        <!-- Stock Status Filter -->
                        <div class="relative">
                            <label for="stock_status" class="block text-sm font-medium text-gray-700 mb-1.5">Stock
                                Status</label>
                            <div class="relative rounded-lg border border-gray-200">
                                <select id="stock_status" name="stock_status" x-model="filters.stock_status"
                                    class="block w-full pl-4 pr-10 py-2.5 rounded-lg border-gray-200 bg-gray-50 focus:border-primary focus:ring focus:ring-primary/20 focus:ring-opacity-50 text-sm transition-all shadow-sm">
                                    <option value="">All Stock Status</option>
                                    <option value="in_stock">In Stock</option>
                                    <option value="out_of_stock">Out of Stock</option>
                                    <option value="low_stock">Low Stock</option>
                                </select>
                            </div>
                        </div>

                        <!-- Sort By Filter -->
                        <div class="relative">
                            <label for="sort" class="block text-sm font-medium text-gray-700 mb-1.5">Sort By</label>
                            <div class="relative rounded-lg border border-gray-200">
                                <select id="sort" name="sort" x-model="filters.sort"
                                    class="block w-full pl-4 pr-10 py-2.5 rounded-lg border-gray-200 bg-gray-50 focus:border-primary focus:ring focus:ring-primary/20 focus:ring-opacity-50 text-sm transition-all shadow-sm">
                                    <option value="created_at">Date Created</option>
                                    <option value="name">Name</option>
                                    <option value="price">Price</option>
                                    <option value="quantity">Stock Quantity</option>
                                </select>
                            </div>
                        </div>

                        <!-- Search Filter -->
                        <div>
                            <label for="search" class="block text-sm font-medium text-gray-700 mb-1.5">Search
                                Products</label>
                            <div class="relative rounded-lg border border-gray-200">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="ri-search-line text-gray-400"></i>
                                </div>
                                <input type="text" id="search" name="search" x-model="filters.search"
                                    class="block w-full pl-10 pr-4 py-2.5 rounded-lg border-gray-200 bg-gray-50 focus:border-primary focus:ring focus:ring-primary/20 focus:ring-opacity-50 text-sm transition-all shadow-sm"
                                    placeholder="Product name, SKU...">
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

        <!-- Products Table -->
        <div class="bg-white rounded-lg border border-gray-200 shadow-sm overflow-hidden">
            <div class="flex justify-between items-center p-5 border-b border-gray-100">
                <h3 class="text-lg font-medium text-gray-900">Product Inventory</h3>
                <div class="flex items-center space-x-2">
                    <span class="text-sm text-gray-500">Showing <span x-text="productsCount"></span> of <span
                            x-text="stats.totalProducts"></span> products</span>
                </div>
            </div>

            <div class="overflow-x-auto" x-show="!isLoading">
                <table class="w-full">
                    <thead>
                        <tr class="bg-gray-50">
                            <th scope="col"
                                class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-b border-gray-200">
                                Product</th>
                            <th scope="col"
                                class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-b border-gray-200">
                                SKU</th>
                            <th scope="col"
                                class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-b border-gray-200">
                                Category</th>
                            <th scope="col"
                                class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-b border-gray-200">
                                Price</th>
                            <th scope="col"
                                class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-b border-gray-200">
                                Stock</th>
                            <th scope="col"
                                class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-b border-gray-200">
                                Status</th>
                            <th scope="col"
                                class="px-6 hidden py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-b border-gray-200">
                                Added On</th>
                            <th scope="col"
                                class="px-6 py-4 text-right text-xs font-medium text-gray-500 uppercase tracking-wider border-b border-gray-200">
                                Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <template x-for="product in products" :key="product.id">
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-5">
                                    <div class="flex items-center">
                                        <div
                                            class="h-14 w-14 rounded-md bg-gray-100 border border-gray-200 flex-shrink-0 overflow-hidden">
                                            <img :src="product.image_url || '/images/placeholder.jpg'" alt=""
                                                class="h-full w-full object-cover object-center">
                                        </div>
                                        <div class="ml-4">
                                            <span class="text-sm font-medium text-gray-900" x-text="product.name"></span>
                                            <p class="text-xs hidden text-gray-500 mt-1 truncate max-w-xs"
                                                x-text="product.description ? (product.description.length > 60 ? product.description.substring(0, 60) + '...' : product.description) : '-'">
                                            </p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-5 whitespace-nowrap">
                                    <span class="text-sm text-gray-900" x-text="product.sku || '-'"></span>
                                </td>
                                <td class="px-6 py-5 whitespace-nowrap">
                                    <span class="text-sm text-gray-900"
                                        x-text="product.category ? product.category.name : '-'"></span>
                                </td>
                                <td class="px-6 py-5 whitespace-nowrap">
                                    <span class="text-sm font-medium text-gray-900"
                                        x-text="'€' + formatNumber(product.price)"></span>
                                </td>
                                <td class="px-6 py-5 whitespace-nowrap">
                                    <div>
                                        <span class="text-sm font-medium text-gray-900" x-text="product.quantity"></span>
                                        <div class="w-16 bg-gray-200 rounded-full h-1.5 mt-1.5">
                                            <div class="h-1.5 rounded-full" :class="getStockLevelClass(product)"
                                                :style="'width: ' + getStockPercentage(product) + '%'">
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-5 whitespace-nowrap">
                                    <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-medium"
                                        :class="product.is_active ? 'bg-green-50 text-green-700 border border-green-200' :
                                            'bg-gray-50 text-gray-600 border border-gray-200'">
                                        <i
                                            :class="product.is_active ? 'ri-checkbox-circle-line mr-1' :
                                                'ri-pause-circle-line mr-1'"></i>
                                        <span x-text="product.is_active ? 'Active' : 'Inactive'"></span>
                                    </span>
                                </td>
                                <td class="px-6 py-5 hidden whitespace-nowrap">
                                    <span class="text-sm text-gray-500" x-text="formatDate(product.created_at)"></span>
                                </td>
                                <td class="px-6 py-5 whitespace-nowrap text-right">
                                    <div class="flex justify-end gap-2">
                                        <a :href="'/admin/products/' + product.id + '/edit'"
                                            class="inline-flex items-center px-3 py-1.5 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition-colors">
                                            <i class="ri-pencil-line mr-1"></i>
                                            Edit
                                        </a>


                                        <button @click="confirmDelete(product)"
                                            class="inline-flex items-center px-3 py-1.5 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors">
                                            <i class="ri-delete-bin-line mr-1"></i>
                                            Delete
                                        </button>
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
            <div class="text-center py-16" x-show="!isLoading && products.length === 0">
                <div class="inline-flex items-center justify-center w-20 h-20 bg-gray-100 rounded-full mb-6">
                    <i class="ri-shopping-bag-line text-3xl text-gray-400"></i>
                </div>
                <h3 class="text-xl font-medium text-primary mb-3">No products found</h3>
                <p class="text-gray-500 mb-8 max-w-md mx-auto">There are no products matching your criteria.</p>
                <div class="flex justify-center gap-4">
                    <button @click="resetFilters()"
                        class="inline-flex items-center px-6 py-3 border border-gray-300 bg-white text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                        <i class="ri-refresh-line mr-2"></i> Reset Filters
                    </button>
                    <a href="{{ route('admin.products.create') }}"
                        class="inline-flex items-center px-6 py-3 border border-transparent bg-primary text-white rounded-lg hover:bg-primary/90 transition-colors">
                        <i class="ri-add-line mr-2"></i> Add Product
                    </a>
                </div>
            </div>

            <!-- Pagination -->
            <div class="px-6 py-4 border-t border-gray-200 flex items-center justify-between"
                x-show="!isLoading && products.length > 0">
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
                            <span class="font-medium" x-text="Math.min(currentPage * perPage, totalProducts)"></span>
                            of
                            <span class="font-medium" x-text="totalProducts"></span>
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
                                        'z-10 bg-primary text-white border-primary hover:text-black ' :
                                        'text-gray-500'">
                                    <span x-text="page" class="text-black"></span>
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
    function productsData() {
        return {
            products: [],
            categories: [],
            stats: {
                totalProducts: 0,
                lowStockProducts: 0,
                activeProducts: 0,
                categoryCount: 0,
                topCategory: 'None',
                productGrowth: 0
            },
            filters: {
                category: '',
                status: '',
                stock_status: '',
                search: '',
                sort: 'created_at',
                direction: 'desc'
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
                this.fetchProducts();
                this.fetchCategories();

                // Check URL params for filters
                const urlParams = new URLSearchParams(window.location.search);
                this.filters = {
                    category: urlParams.get('category') || '',
                    status: urlParams.get('status') || '',
                    stock_status: urlParams.get('stock_status') || '',
                    search: urlParams.get('search') || '',
                    sort: urlParams.get('sort') || 'created_at',
                    direction: urlParams.get('direction') || 'desc'
                };

                this.showFilters = Object.values(this.filters).some(val => val !== '' && val !== 'created_at' && val !==
                    'desc');
            },

            fetchCategories() {
                fetch('/admin/categories/data', {
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
                            this.categories = data;
                        } else {
                            console.error('Error fetching categories:', data.message);
                        }
                    })
                    .catch(error => {
                        console.error('Error fetching categories:', error);
                    });
            },

            fetchProducts() {
                this.isLoading = true;

                // Build the query string
                const queryParams = new URLSearchParams();
                queryParams.append('page', this.currentPage);

                for (const [key, value] of Object.entries(this.filters)) {
                    if (value) {
                        queryParams.append(key, value);
                    }
                }

                fetch(`/admin/products/data?${queryParams.toString()}`, {
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
                            this.products = data.products.data;
                            this.pagination = {
                                current_page: data.products.current_page,
                                per_page: data.products.per_page,
                                total: data.products.total,
                                last_page: data.products.last_page
                            };
                            this.stats = {
                                totalProducts: data.stats.totalProducts,
                                lowStockProducts: data.stats.lowStockProducts,
                                activeProducts: data.stats.activeProducts,
                                categoryCount: data.stats.categoryCount,
                                topCategory: data.stats.topCategory,
                                productGrowth: data.stats.productGrowth
                            };
                        } else {
                            console.error('Error fetching products:', data.message);
                        }
                        this.isLoading = false;
                    })
                    .catch(error => {
                        console.error('Error fetching products:', error);
                        this.isLoading = false;
                    });
            },

            get productsCount() {
                return this.products.length;
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

            get totalProducts() {
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

            fetchProducts() {
                this.isLoading = true;

                // Build the query string
                const queryParams = new URLSearchParams();
                queryParams.append('page', this.currentPage);

                for (const [key, value] of Object.entries(this.filters)) {
                    if (value) {
                        queryParams.append(key, value);
                    }
                }

                fetch(`/admin/products/data?${queryParams.toString()}`, {
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
                            this.products = data.products.data;
                            this.pagination = {
                                current_page: data.products.current_page,
                                per_page: data.products.per_page,
                                total: data.products.total,
                                last_page: data.products.last_page
                            };
                            this.stats = {
                                totalProducts: data.stats.totalProducts,
                                lowStockProducts: data.stats.lowStockProducts,
                                activeProducts: data.stats.activeProducts,
                                categoryCount: data.stats.categoryCount,
                                topCategory: data.stats.topCategory,
                                productGrowth: data.stats.productGrowth
                            };
                        } else {
                            console.error('Error fetching products:', data.message);
                        }
                        this.isLoading = false;
                    })
                    .catch(error => {
                        console.error('Error fetching products:', error);
                        this.isLoading = false;
                    });
            },

            // Add this function to your productsData() Alpine.js component
            confirmDelete(product) {
                if (confirm(
                        `Are you sure you want to delete the product "${product.name}"? This action cannot be undone.`
                    )) {
                    // Create a form dynamically to submit a DELETE request
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = `/admin/products/${product.id}`;

                    // Add CSRF token
                    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                    const csrfInput = document.createElement('input');
                    csrfInput.type = 'hidden';
                    csrfInput.name = '_token';
                    csrfInput.value = csrfToken;
                    form.appendChild(csrfInput);

                    // Add method spoofing for DELETE
                    const methodInput = document.createElement('input');
                    methodInput.type = 'hidden';
                    methodInput.name = '_method';
                    methodInput.value = 'DELETE';
                    form.appendChild(methodInput);

                    // Append form to document body and submit
                    document.body.appendChild(form);
                    form.submit();
                }
            },

            applyFilters() {
                this.currentPage = 1;
                this.fetchProducts();
                this.updateURLParams();
            },

            resetFilters() {
                this.filters = {
                    category: '',
                    status: '',
                    stock_status: '',
                    search: '',
                    sort: 'created_at',
                    direction: 'desc'
                };
                this.currentPage = 1;
                this.fetchProducts();
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
                    this.fetchProducts();
                }
            },

            prevPage() {
                if (this.currentPage > 1) {
                    this.currentPage--;
                    this.fetchProducts();
                }
            },

            goToPage(page) {
                if (page !== '...' && page !== this.currentPage) {
                    this.currentPage = page;
                    this.fetchProducts();
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

            formatNumber(number) {
                return parseFloat(number).toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ",");
            },

            getStockPercentage(product) {
                if (!product.stock_alert_threshold) return 100;
                const percentage = (product.quantity / (product.stock_alert_threshold * 2)) * 100;
                return Math.min(percentage, 100);
            },

            exportProducts() {
                // Show loading indicator or message
                const loadingToast = this.$dispatch('toast', {
                    title: 'Exporting products...',
                    message: 'Please wait while we prepare your export.',
                    type: 'info',
                    autoClose: false
                });

                // Build the query string with current filters
                const queryParams = new URLSearchParams();

                for (const [key, value] of Object.entries(this.filters)) {
                    if (value) {
                        queryParams.append(key, value);
                    }
                }

                // Redirect to export URL with current filters
                window.location.href = `/admin/products/export?${queryParams.toString()}`;
            },

            getStockLevelClass(product) {
                if (product.quantity <= 0) {
                    return 'bg-red-500';
                } else if (product.quantity <= product.stock_alert_threshold) {
                    return 'bg-yellow-500';
                } else {
                    return 'bg-green-500';
                }
            }
        }
    }
</script>
