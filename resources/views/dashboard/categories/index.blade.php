@extends('layouts.admin')

@section('title', 'Categories')

@section('content')
    <div class="container mx-auto" x-data="categoriesData()">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6">
            <div>
                <h1 class="text-2xl font-semibold text-gray-900">Categories</h1>
                <p class="mt-1 text-sm text-gray-500">Manage product categories and organization</p>
            </div>
            <div class="mt-4 md:mt-0 flex space-x-3">
                <a href="" @click.prevent="exportCategories()"
                    class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition-colors">
                    <i class="ri-download-line mr-2"></i>
                    Export
                </a>
                <a href="{{ route('admin.categories.create') }}"
                    class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary hover:bg-primary/90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition-colors">
                    <i class="ri-add-line mr-2"></i>
                    Add Category
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
            <!-- Total Categories -->
            <div class="bg-white rounded-lg border border-gray-200 shadow-sm p-5">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Total Categories</p>
                        <h3 class="text-2xl font-bold text-gray-900 mt-1" x-text="stats.totalCategories">0</h3>
                    </div>
                    <div class="w-12 h-12 bg-primary/10 rounded-full flex items-center justify-center">
                        <i class="ri-folder-line text-xl text-primary"></i>
                    </div>
                </div>
                <div class="mt-3 flex items-center text-sm">
                    <span class="text-green-500 font-medium flex items-center">
                        <i class="ri-arrow-up-line mr-1"></i> <span x-text="stats.categoryGrowth + '%'"></span>
                    </span>
                    <span class="text-gray-500 ml-2">from last month</span>
                </div>
            </div>

            <!-- Active Categories -->
            <div class="bg-white rounded-lg border border-gray-200 shadow-sm p-5">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Active Categories</p>
                        <h3 class="text-2xl font-bold text-gray-900 mt-1" x-text="stats.activeCategories">0</h3>
                    </div>
                    <div class="w-12 h-12 bg-green-50 rounded-full flex items-center justify-center">
                        <i class="ri-check-line text-xl text-green-600"></i>
                    </div>
                </div>
                <div class="mt-3 flex items-center text-sm">
                    <span class="text-gray-500 font-medium flex items-center">
                        <i class="ri-information-line mr-1"></i> Currently active
                    </span>
                </div>
            </div>

            <!-- Parent Categories -->
            <div class="bg-white rounded-lg border border-gray-200 shadow-sm p-5">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Parent Categories</p>
                        <h3 class="text-2xl font-bold text-gray-900 mt-1" x-text="stats.parentCategories">0</h3>
                    </div>
                    <div class="w-12 h-12 bg-blue-50 rounded-full flex items-center justify-center">
                        <i class="ri-folders-line text-xl text-blue-600"></i>
                    </div>
                </div>
                <div class="mt-3 flex items-center text-sm">
                    <span class="text-blue-500 font-medium flex items-center">
                        <i class="ri-information-line mr-1"></i> Root categories
                    </span>
                </div>
            </div>

            <!-- Top Category -->
            <div class="bg-white rounded-lg border border-gray-200 shadow-sm p-5">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Most Products</p>
                        <h3 class="text-2xl font-bold text-gray-900 mt-1" x-text="stats.topCategoryProductCount">0</h3>
                    </div>
                    <div class="w-12 h-12 bg-yellow-50 rounded-full flex items-center justify-center">
                        <i class="ri-shopping-bag-line text-xl text-yellow-600"></i>
                    </div>
                </div>
                <div class="mt-3 flex items-center text-sm">
                    <span class="text-yellow-600 font-medium" x-text="stats.topCategory"></span>
                    <span class="text-gray-500 ml-2">has most products</span>
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
                    <h3 class="text-lg font-medium text-gray-900">Filter Categories</h3>
                </div>
                <button @click="showFilters = false" class="text-gray-400 hover:text-gray-600 transition-colors">
                    <i class="ri-close-line text-lg"></i>
                </button>
            </div>

            <div class="p-5">
                <form id="categoryFilterForm" @submit.prevent="applyFilters()">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5">
                        <!-- Parent Category Filter -->
                        <div class="relative">
                            <label for="parent" class="block text-sm font-medium text-gray-700 mb-1.5">Parent
                                Category</label>
                            <div class="relative rounded-lg border border-gray-200">
                                <select id="parent" name="parent" x-model="filters.parent"
                                    class="block w-full pl-4 pr-10 py-2.5 rounded-lg border-gray-200 bg-gray-50 focus:border-primary focus:ring focus:ring-primary/20 focus:ring-opacity-50 text-sm transition-all shadow-sm">
                                    <option value="">All Categories</option>
                                    <option value="root">Root Categories Only</option>
                                    <template x-for="category in parentCategories" :key="category.id">
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

                        <!-- Sort By Filter -->
                        <div class="relative">
                            <label for="sort" class="block text-sm font-medium text-gray-700 mb-1.5">Sort By</label>
                            <div class="relative rounded-lg border border-gray-200">
                                <select id="sort" name="sort" x-model="filters.sort"
                                    class="block w-full pl-4 pr-10 py-2.5 rounded-lg border-gray-200 bg-gray-50 focus:border-primary focus:ring focus:ring-primary/20 focus:ring-opacity-50 text-sm transition-all shadow-sm">
                                    <option value="position">Position</option>
                                    <option value="name">Name</option>
                                    <option value="created_at">Date Created</option>
                                    <option value="products_count">Products Count</option>
                                </select>
                            </div>
                        </div>

                        <!-- Search Filter -->
                        <div>
                            <label for="search" class="block text-sm font-medium text-gray-700 mb-1.5">Search
                                Categories</label>
                            <div class="relative rounded-lg border border-gray-200">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="ri-search-line text-gray-400"></i>
                                </div>
                                <input type="text" id="search" name="search" x-model="filters.search"
                                    class="block w-full pl-10 pr-4 py-2.5 rounded-lg border-gray-200 bg-gray-50 focus:border-primary focus:ring focus:ring-primary/20 focus:ring-opacity-50 text-sm transition-all shadow-sm"
                                    placeholder="Category name...">
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

        <!-- Categories Table -->
        <div class="bg-white rounded-lg border border-gray-200 shadow-sm overflow-hidden">
            <div class="flex justify-between items-center p-5 border-b border-gray-100">
                <h3 class="text-lg font-medium text-gray-900">All Categories</h3>
                <div class="flex items-center space-x-2">
                    <span class="text-sm text-gray-500">Showing <span x-text="categoriesCount"></span> of <span
                            x-text="stats.totalCategories"></span> categories</span>
                </div>
            </div>

            <div class="overflow-x-auto" x-show="!isLoading">
                <table class="w-full">
                    <thead>
                        <tr class="bg-gray-50">
                            <th scope="col"
                                class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-b border-gray-200">
                                Category</th>
                            <th scope="col"
                                class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-b border-gray-200">
                                Slug</th>
                            <th scope="col"
                                class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-b border-gray-200">
                                Parent</th>
                            <th scope="col"
                                class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-b border-gray-200">
                                Products</th>
                            <th scope="col"
                                class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-b border-gray-200">
                                Position</th>
                            <th scope="col"
                                class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-b border-gray-200">
                                Status</th>
                            <th scope="col"
                                class="px-6 py-4 text-right text-xs font-medium text-gray-500 uppercase tracking-wider border-b border-gray-200">
                                Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <template x-for="category in categories" :key="category.id">
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-5">
                                    <div class="flex items-center">
                                        <div>
                                            <span class="text-sm font-medium text-gray-900" x-text="category.name"></span>
                                            <p class="text-xs text-gray-500 mt-1 truncate max-w-xs"
                                                x-text="category.description ? (category.description.length > 60 ? category.description.substring(0, 60) + '...' : category.description) : '-'">
                                            </p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-5 whitespace-nowrap">
                                    <span class="text-sm text-gray-500" x-text="category.slug"></span>
                                </td>
                                <td class="px-6 py-5 whitespace-nowrap">
                                    <span class="text-sm text-gray-900"
                                        x-text="category.parent ? category.parent.name : '-'"></span>
                                </td>
                                <td class="px-6 py-5 whitespace-nowrap">
                                    <span class="text-sm font-medium text-gray-900"
                                        x-text="category.products_count || 0"></span>
                                </td>
                                <td class="px-6 py-5 whitespace-nowrap">
                                    <div class="flex items-center space-x-2">

                                        <span class="text-sm text-gray-900" x-text="category.position"></span>

                                    </div>
                                </td>
                                <td class="px-6 py-5 whitespace-nowrap">
                                    <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-medium"
                                        :class="category.is_active ? 'bg-green-50 text-green-700 border border-green-200' :
                                            'bg-gray-50 text-gray-600 border border-gray-200'">
                                        <i
                                            :class="category.is_active ? 'ri-checkbox-circle-line mr-1' :
                                                'ri-pause-circle-line mr-1'"></i>
                                        <span x-text="category.is_active ? 'Active' : 'Inactive'"></span>
                                    </span>
                                </td>
                                <td class="px-6 py-5 whitespace-nowrap text-right">
                                    <div class="flex justify-end gap-2">
                                        <a :href="'/admin/categories/' + category.id + '/edit'"
                                            class="inline-flex items-center px-3 py-1.5 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition-colors">
                                            <i class="ri-pencil-line mr-1"></i>
                                            Edit
                                        </a>
                                        <button @click="confirmDelete(category)"
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
            <div class="text-center py-16" x-show="!isLoading && categories.length === 0">
                <div class="inline-flex items-center justify-center w-20 h-20 bg-gray-100 rounded-full mb-6">
                    <i class="ri-folder-line text-3xl text-gray-400"></i>
                </div>
                <h3 class="text-xl font-medium text-primary mb-3">No categories found</h3>
                <p class="text-gray-500 mb-8 max-w-md mx-auto">There are no categories matching your criteria.</p>
                <div class="flex justify-center gap-4">
                    <button @click="resetFilters()"
                        class="inline-flex items-center px-6 py-3 border border-gray-300 bg-white text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                        <i class="ri-refresh-line mr-2"></i> Reset Filters
                    </button>
                    <a href="{{ route('admin.categories.create') }}"
                        class="inline-flex items-center px-6 py-3 border border-transparent bg-primary text-white rounded-lg hover:bg-primary/90 transition-colors">
                        <i class="ri-add-line mr-2"></i> Add Category
                    </a>
                </div>
            </div>

            <!-- Pagination -->
            <div class="px-6 py-4 border-t border-gray-200 flex items-center justify-between"
                x-show="!isLoading && categories.length > 0">
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
                            <span class="font-medium" x-text="Math.min(currentPage * perPage, totalCategories)"></span>
                            of
                            <span class="font-medium" x-text="totalCategories"></span>
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
                                        'z-10 bg-primary text-black border-primary hover:text-black' :
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

        <!-- Delete Confirmation Modal -->
        <div x-show="showDeleteModal" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
            <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center">
                <!-- Backdrop -->
                <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                    <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
                </div>

                <!-- Modal -->
                <div
                    class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <!-- Modal Header -->
                    <div class="bg-white px-6 pt-6 pb-4">
                        <div class="flex items-center justify-center mb-4">
                            <div class="flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100">
                                <i class="ri-error-warning-line text-red-600 text-xl"></i>
                            </div>
                        </div>

                        <div class="text-center">
                            <h3 class="text-xl leading-6 font-medium text-gray-900 mb-3">Delete Category</h3>
                            <p class="text-sm text-gray-500 mx-auto max-w-md">
                                Are you sure you want to delete the category <span class="font-medium"
                                    x-text="categoryToDelete?.name"></span>? This action cannot be undone and may affect
                                products that belong to this category.
                            </p>
                        </div>
                    </div>

                    <!-- Warning Messages -->
                    <div class="px-6 pb-4 space-y-3">
                        <div class="bg-yellow-50 p-4 rounded-md" x-show="categoryHasProducts">
                            <div class="flex items-center justify-center">
                                <i class="ri-alert-line text-yellow-700 mr-2 text-lg"></i>
                                <p class="text-sm text-yellow-700">
                                    This category has <span class="font-medium"
                                        x-text="categoryToDelete?.products_count"></span> product(s) assigned to it.
                                </p>
                            </div>
                        </div>

                        <div class="bg-orange-50 p-4 rounded-md" x-show="categoryHasChildren">
                            <div class="flex items-center justify-center">
                                <i class="ri-alert-line text-orange-700 mr-2 text-lg"></i>
                                <p class="text-sm text-orange-700">
                                    This category has <span class="font-medium"
                                        x-text="categoryToDelete?.children_count"></span> child categories that will also
                                    be affected.
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Modal Footer -->
                    <div class="bg-gray-50 px-6 py-4 flex justify-center gap-4">
                        <button type="button" @click="showDeleteModal = false"
                            class="w-1/2 inline-flex justify-center items-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary sm:text-sm">
                            Cancel
                        </button>
                        <button type="button" @click="deleteCategory()"
                            class="w-1/2 inline-flex justify-center items-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:text-sm"
                            :disabled="isDeleting">
                            <span x-show="isDeleting" class="inline-block animate-spin mr-2">
                                <i class="ri-loader-4-line"></i>
                            </span>
                            <span>Delete</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

<script>
    function categoriesData() {
        return {
            categories: [],
            parentCategories: [],
            stats: {
                totalCategories: 0,
                activeCategories: 0,
                parentCategories: 0,
                topCategoryProductCount: 0,
                topCategory: 'None',
                categoryGrowth: 0
            },
            filters: {
                parent: '',
                status: '',
                search: '',
                sort: 'position',
                direction: 'asc'
            },
            pagination: {
                current_page: 1,
                per_page: 10,
                total: 0,
                last_page: 1
            },
            categoryToDelete: null,
            isLoading: true,
            isDeleting: false,
            isUpdatingPosition: false,
            showFilters: false,
            showDeleteModal: false,
            categoryHasProducts: false,
            categoryHasChildren: false,

            init() {
                this.fetchCategories();

                // Check URL params for filters
                const urlParams = new URLSearchParams(window.location.search);
                this.filters = {
                    parent: urlParams.get('parent') || '',
                    status: urlParams.get('status') || '',
                    search: urlParams.get('search') || '',
                    sort: urlParams.get('sort') || 'position',
                    direction: urlParams.get('direction') || 'asc'
                };

                this.showFilters = Object.values(this.filters).some(val => val !== '' && val !== 'position' && val !==
                    'asc');
            },

            fetchCategories() {
                this.isLoading = true;


                const queryParams = new URLSearchParams();
                queryParams.append('page', this.currentPage);

                for (const [key, value] of Object.entries(this.filters)) {
                    if (value) {
                        queryParams.append(key, value);
                    }
                }

                fetch(`/admin/categories/data?${queryParams.toString()}`, {
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
                            this.categories = data.categories.data;
                            this.parentCategories = data.parentCategories || [];
                            this.pagination = {
                                current_page: data.categories.current_page,
                                per_page: data.categories.per_page,
                                total: data.categories.total,
                                last_page: data.categories.last_page
                            };
                            this.stats = {
                                totalCategories: data.stats.totalCategories,
                                activeCategories: data.stats.activeCategories,
                                parentCategories: data.stats.parentCategories,
                                topCategoryProductCount: data.stats.topCategoryProductCount,
                                topCategory: data.stats.topCategory,
                                categoryGrowth: data.stats.categoryGrowth
                            };
                        } else {
                            console.error('Error fetching categories:', data.message);
                        }
                        this.isLoading = false;
                    })
                    .catch(error => {
                        console.error('Error fetching categories:', error);
                        this.isLoading = false;
                    });
            },

            get categoriesCount() {
                return this.categories.length;
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

            get totalCategories() {
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
                this.fetchCategories();
                this.updateURLParams();
            },

            resetFilters() {
                this.filters = {
                    parent: '',
                    status: '',
                    search: '',
                    sort: 'position',
                    direction: 'asc'
                };
                this.currentPage = 1;
                this.fetchCategories();
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
                    this.fetchCategories();
                }
            },

            prevPage() {
                if (this.currentPage > 1) {
                    this.currentPage--;
                    this.fetchCategories();
                }
            },

            goToPage(page) {
                if (page !== '...' && page !== this.currentPage) {
                    this.currentPage = page;
                    this.fetchCategories();
                }
            },

            confirmDelete(category) {
                this.categoryToDelete = category;
                this.categoryHasProducts = category.products_count > 0;
                this.categoryHasChildren = category.children_count > 0;
                this.showDeleteModal = true;
            },

            deleteCategory() {
                if (!this.categoryToDelete) return;

                this.isDeleting = true;

                // Create a form dynamically to submit a DELETE request
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/admin/categories/${this.categoryToDelete.id}`;

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
            },

            updatePosition(category, direction) {
                if (this.isUpdatingPosition) return;

                this.isUpdatingPosition = true;

                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/admin/categories/${category.id}/position`;

                // Add CSRF token
                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                const csrfInput = document.createElement('input');
                csrfInput.type = 'hidden';
                csrfInput.name = '_token';
                csrfInput.value = csrfToken;
                form.appendChild(csrfInput);

                // Add method spoofing for PATCH
                const methodInput = document.createElement('input');
                methodInput.type = 'hidden';
                methodInput.name = '_method';
                methodInput.value = 'PATCH';
                form.appendChild(methodInput);

                // Add direction
                const directionInput = document.createElement('input');
                directionInput.type = 'hidden';
                directionInput.name = 'direction';
                directionInput.value = direction;
                form.appendChild(directionInput);

                // Append form to document body and submit
                document.body.appendChild(form);
                form.submit();
            },

            exportCategories() {
                const queryParams = new URLSearchParams();

                for (const [key, value] of Object.entries(this.filters)) {
                    if (value) {
                        queryParams.append(key, value);
                    }
                }

                window.location.href = "/admin/categories/export?" + queryParams.toString();
            }
        }
    }
</script>
