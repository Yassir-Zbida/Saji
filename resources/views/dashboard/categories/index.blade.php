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
                <button @click="showCreateModal = true"
                    class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary hover:bg-primary/90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition-colors">
                    <i class="ri-add-line mr-2"></i>
                    Add Category
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
                            <label for="parent" class="block text-sm font-medium text-gray-700 mb-1.5">Parent Category</label>
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
                                        <div
                                            class="h-12 w-12 rounded-md bg-gray-100 border border-gray-200 flex-shrink-0 overflow-hidden">
                                            <img :src="category.image || '/images/placeholder-category.jpg'" alt="" class="h-full w-full object-cover object-center">
                                        </div>
                                        <div class="ml-4">
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
                                    <span class="text-sm font-medium text-gray-900" x-text="category.products_count || 0"></span>
                                </td>
                                <td class="px-6 py-5 whitespace-nowrap">
                                    <div class="flex items-center space-x-2">
                                        <button @click="updatePosition(category, 'up')" 
                                            class="text-gray-500 hover:text-primary focus:outline-none" 
                                            :disabled="isUpdatingPosition">
                                            <i class="ri-arrow-up-s-line"></i>
                                        </button>
                                        <span class="text-sm text-gray-900" x-text="category.position"></span>
                                        <button @click="updatePosition(category, 'down')" 
                                            class="text-gray-500 hover:text-primary focus:outline-none"
                                            :disabled="isUpdatingPosition">
                                            <i class="ri-arrow-down-s-line"></i>
                                        </button>
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
                                        <button @click="editCategory(category)"
                                            class="inline-flex items-center px-3 py-1.5 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition-colors">
                                            <i class="ri-pencil-line mr-1"></i>
                                            Edit
                                        </button>
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
                    <button @click="showCreateModal = true"
                        class="inline-flex items-center px-6 py-3 border border-transparent bg-primary text-white rounded-lg hover:bg-primary/90 transition-colors">
                        <i class="ri-add-line mr-2"></i> Add Category
                    </button>
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
                                        'z-10 bg-primary text-white border-primary hover:text-black' :
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

        <!-- Create/Edit Category Modal -->
        <div x-show="showCreateModal || showEditModal" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
            <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                    <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
                </div>
                <!-- Modal -->
                <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-primary/10 sm:mx-0 sm:h-10 sm:w-10">
                                <i class="ri-folder-add-line text-primary"></i>
                            </div>
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                                <h3 class="text-lg leading-6 font-medium text-gray-900" x-text="showEditModal ? 'Edit Category' : 'Add New Category'"></h3>
                                <div class="mt-2">
                                    <p class="text-sm text-gray-500" x-text="showEditModal ? 'Update category information below.' : 'Create a new category for your products.'"></p>
                                </div>
                            </div>
                        </div>
                        <div class="mt-6 space-y-4">
                            <form id="categoryForm" @submit.prevent="submitCategoryForm">
                                <div class="space-y-4">
                                    <!-- Name -->
                                    <div>
                                        <label for="category_name" class="block text-sm font-medium text-gray-700">Name</label>
                                        <input type="text" name="name" id="category_name" x-model="categoryForm.name" required
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-20 sm:text-sm">
                                    </div>

                                    <!-- Slug -->
                                    <div>
                                        <label for="category_slug" class="block text-sm font-medium text-gray-700">Slug</label>
                                        <div class="mt-1 flex rounded-md shadow-sm">
                                            <input type="text" name="slug" id="category_slug" x-model="categoryForm.slug"
                                                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-20 sm:text-sm">
                                        </div>
                                        <p class="mt-1 text-xs text-gray-500">Leave empty to auto-generate from name</p>
                                    </div>

                                    <!-- Description -->
                                    <div>
                                        <label for="category_description" class="block text-sm font-medium text-gray-700">Description</label>
                                        <textarea id="category_description" name="description" rows="3" x-model="categoryForm.description"
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-20 sm:text-sm"></textarea>
                                    </div>

                                    <!-- Parent Category -->
                                    <div>
                                        <label for="parent_id" class="block text-sm font-medium text-gray-700">Parent Category</label>
                                        <select id="parent_id" name="parent_id" x-model="categoryForm.parent_id"
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-20 sm:text-sm">
                                            <option value="">None (Root Category)</option>
                                            <template x-for="category in parentCategoryOptions" :key="category.id">
                                                <option :value="category.id" x-text="category.name"></option>
                                            </template>
                                        </select>
                                    </div>

                                    <!-- Position -->
                                    <div>
                                        <label for="position" class="block text-sm font-medium text-gray-700">Position</label>
                                        <input type="number" name="position" id="position" x-model="categoryForm.position" min="0"
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-20 sm:text-sm">
                                    </div>

                                    <!-- Image Upload -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Category Image</label>
                                        <div class="mt-1 flex items-center">
                                            <div x-show="categoryForm.imagePreview || categoryForm.image" class="flex-shrink-0 h-16 w-16 mr-3 rounded-md overflow-hidden bg-gray-100">
                                                <img :src="categoryForm.imagePreview || categoryForm.image" alt="Category image preview" class="h-full w-full object-cover">
                                            </div>
                                            <div x-show="!categoryForm.imagePreview && !categoryForm.image" class="flex-shrink-0 h-16 w-16 mr-3 rounded-md overflow-hidden bg-gray-100 flex items-center justify-center">
                                                <i class="ri-image-line text-gray-400 text-xl"></i>
                                            </div>
                                            <label for="category_image" class="relative cursor-pointer bg-white py-2 px-3 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-primary">
                                                <span>Upload image</span>
                                                <input id="category_image" name="image" type="file" class="sr-only" @change="previewImage($event)">
                                            </label>
                                            <button type="button" @click="clearImage()" x-show="categoryForm.imagePreview || categoryForm.image"
                                                class="ml-2 bg-white py-2 px-3 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary">
                                                Clear
                                            </button>
                                        </div>
                                    </div>

                                    <!-- Status -->
                                    <div class="flex items-center">
                                        <div class="flex items-center h-5">
                                            <input id="is_active" name="is_active" type="checkbox" x-model="categoryForm.is_active"
                                                class="focus:ring-primary h-4 w-4 text-primary border-gray-300 rounded">
                                        </div>
                                        <div class="ml-3 text-sm">
                                            <label for="is_active" class="font-medium text-gray-700">Active</label>
                                            <p class="text-gray-500">Make this category visible on the shop</p>
                                        </div>
                                    </div>

                                    <!-- SEO Section -->
                                    <div class="border-t border-gray-200 pt-4 mt-4">
                                        <h4 class="text-sm font-medium text-gray-900">SEO Information</h4>
                                        <p class="mt-1 text-xs text-gray-500">Optimize this category for search engines</p>

                                        <div class="mt-3 space-y-4">
                                            <!-- Meta Title -->
                                            <div>
                                                <label for="meta_title" class="block text-sm font-medium text-gray-700">Meta Title</label>
                                                <input type="text" name="meta_title" id="meta_title" x-model="categoryForm.meta_title"
                                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-20 sm:text-sm">
                                            </div>

                                            <!-- Meta Description -->
                                            <div>
                                                <label for="meta_description" class="block text-sm font-medium text-gray-700">Meta Description</label>
                                                <textarea id="meta_description" name="meta_description" rows="2" x-model="categoryForm.meta_description"
                                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-20 sm:text-sm"></textarea>
                                            </div>

                                            <!-- Meta Keywords -->
                                            <div>
                                                <label for="meta_keywords" class="block text-sm font-medium text-gray-700">Meta Keywords</label>
                                                <input type="text" name="meta_keywords" id="meta_keywords" x-model="categoryForm.meta_keywords"
                                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-20 sm:text-sm">
                                                <p class="mt-1 text-xs text-gray-500">Separate keywords with commas</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button type="button" @click="submitCategoryForm()"
                            class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-primary text-base font-medium text-white hover:bg-primary/90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary sm:ml-3 sm:w-auto sm:text-sm"
                            :disabled="isSubmitting">
                            <span x-show="isSubmitting" class="inline-block animate-spin mr-2">
                                <i class="ri-loader-4-line"></i>
                            </span>
                            <span x-text="showEditModal ? 'Update Category' : 'Create Category'"></span>
                        </button>
                        <button type="button" @click="closeModal()"
                            class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                            Cancel
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Delete Confirmation Modal -->
        <div x-show="showDeleteModal" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
            <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                    <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
                </div>
                <!-- Modal -->
                <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                                <i class="ri-error-warning-line text-red-600"></i>
                            </div>
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                                <h3 class="text-lg leading-6 font-medium text-gray-900">Delete Category</h3>
                                <div class="mt-2">
                                    <p class="text-sm text-gray-500">
                                        Are you sure you want to delete the category <span class="font-medium" x-text="categoryToDelete?.name"></span>? This action cannot be undone and may affect products that belong to this category.
                                    </p>
                                    <div class="mt-3 bg-yellow-50 p-3 rounded-md" x-show="categoryHasProducts">
                                        <p class="text-sm text-yellow-700">
                                            <i class="ri-alert-line mr-1"></i> This category has <span class="font-medium" x-text="categoryToDelete?.products_count"></span> product(s) assigned to it.
                                        </p>
                                    </div>
                                    <div class="mt-3 bg-orange-50 p-3 rounded-md" x-show="categoryHasChildren">
                                        <p class="text-sm text-orange-700">
                                            <i class="ri-alert-line mr-1"></i> This category has <span class="font-medium" x-text="categoryToDelete?.children_count"></span> child categories that will also be affected.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button type="button" @click="deleteCategory()"
                            class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm"
                            :disabled="isDeleting">
                            <span x-show="isDeleting" class="inline-block animate-spin mr-2">
                                <i class="ri-loader-4-line"></i>
                            </span>
                            <span>Delete</span>
                        </button>
                        <button type="button" @click="showDeleteModal = false"
                            class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                            Cancel
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
            categoryForm: {
                id: null,
                name: '',
                slug: '',
                description: '',
                parent_id: '',
                image: null,
                imagePreview: null,
                is_active: true,
                meta_title: '',
                meta_description: '',
                meta_keywords: '',
                position: 0
            },
            categoryToDelete: null,
            isLoading: true,
            isSubmitting: false,
            isDeleting: false,
            isUpdatingPosition: false,
            showFilters: false,
            showCreateModal: false,
            showEditModal: false,
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

                this.showFilters = Object.values(this.filters).some(val => val !== '' && val !== 'position' && val !== 'asc');
            },

            fetchCategories() {
                this.isLoading = true;

                // Build the query string
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

            get parentCategoryOptions() {
                if (this.showEditModal) {
                    // When editing, filter out the current category and its descendants
                    return this.parentCategories.filter(cat => 
                        cat.id !== this.categoryForm.id && 
                        !this.isDescendant(cat, this.categoryForm.id)
                    );
                }
                return this.parentCategories;
            },

            // Check if a category is a descendant of another
            isDescendant(category, potentialAncestorId) {
                if (!category.parent_id) return false;
                if (category.parent_id == potentialAncestorId) return true;
                const parent = this.parentCategories.find(p => p.id == category.parent_id);
                if (parent) {
                    return this.isDescendant(parent, potentialAncestorId);
                }
                return false;
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

            previewImage(event) {
                const file = event.target.files[0];
                if (file) {
                    this.categoryForm.image = file;
                    const reader = new FileReader();
                    reader.onload = e => {
                        this.categoryForm.imagePreview = e.target.result;
                    };
                    reader.readAsDataURL(file);
                }
            },

            clearImage() {
                this.categoryForm.image = null;
                this.categoryForm.imagePreview = null;
                const input = document.getElementById('category_image');
                if (input) input.value = '';
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

            editCategory(category) {
                this.categoryForm = {
                    id: category.id,
                    name: category.name,
                    slug: category.slug,
                    description: category.description || '',
                    parent_id: category.parent_id || '',
                    image: category.image,
                    imagePreview: null,
                    is_active: category.is_active,
                    meta_title: category.meta_title || '',
                    meta_description: category.meta_description || '',
                    meta_keywords: category.meta_keywords || '',
                    position: category.position || 0
                };
                this.showEditModal = true;
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

            submitCategoryForm() {
                if (this.isSubmitting) return;
                
                this.isSubmitting = true;
                
                const formData = new FormData();
                
                // Add all form fields
                for (const [key, value] of Object.entries(this.categoryForm)) {
                    if (key !== 'imagePreview') {
                        if (value !== null) {
                            formData.append(key, value);
                        }
                    }
                }

                const method = this.showEditModal ? 'PUT' : 'POST';
                const url = this.showEditModal 
                    ? `/admin/categories/${this.categoryForm.id}` 
                    : '/admin/categories';

                fetch(url, {
                    method: method,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: formData
                })
                .then(response => {
                    if (!response.ok) {
                        return response.json().then(data => {
                            throw new Error(data.message || 'Something went wrong');
                        });
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        // Show success message
                        this.$dispatch('toast', {
                            title: 'Success!',
                            message: data.message || (this.showEditModal ? 'Category updated successfully' : 'Category created successfully'),
                            type: 'success'
                        });
                        
                        // Close modal and refresh data
                        this.closeModal();
                        this.fetchCategories();
                    } else {
                        throw new Error(data.message || 'Something went wrong');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    // Show error message
                    this.$dispatch('toast', {
                        title: 'Error!',
                        message: error.message || 'An error occurred while saving the category',
                        type: 'error'
                    });
                })
                .finally(() => {
                    this.isSubmitting = false;
                });
            },

            closeModal() {
                this.showCreateModal = false;
                this.showEditModal = false;
                this.resetCategoryForm();
            },

            resetCategoryForm() {
                this.categoryForm = {
                    id: null,
                    name: '',
                    slug: '',
                    description: '',
                    parent_id: '',
                    image: null,
                    imagePreview: null,
                    is_active: true,
                    meta_title: '',
                    meta_description: '',
                    meta_keywords: '',
                    position: 0
                };
            },

            exportCategories() {
                // Build the query string with current filters
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
