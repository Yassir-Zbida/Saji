@extends('layouts.admin')

@section('title', 'Product Tags')

@section('content')
    <div class="container mx-auto" x-data="tagsData()">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6">
            <div>
                <h1 class="text-2xl font-semibold text-gray-900">Tags</h1>
                <p class="mt-1 text-sm text-gray-500">Manage product tags and labeling</p>
            </div>
            <div class="mt-4 md:mt-0 flex space-x-3">
                <a href="{{ route('tags.export') }}" @click.prevent="exportTags()"
                    class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition-colors">
                    <i class="ri-download-line mr-2"></i>
                    Export
                </a>
                <a href="{{ route('tags.create') }}"
                    class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary hover:bg-primary/90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition-colors">
                    <i class="ri-add-line mr-2"></i>
                    Add Tag
                </a>
                <button type="button" @click="showFilters = !showFilters"
                    class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary hover:bg-primary/90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition-colors">
                    <i class="ri-filter-3-line mr-2"></i>
                    Filters
                </button>
            </div>
        </div>

        <!-- Stats Cards - Now with 4 boxes -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5 mb-6">
            <!-- Total Tags -->
            <div class="bg-white rounded-lg border border-gray-200 shadow-sm p-5">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Total Tags</p>
                        <h3 class="text-2xl font-bold text-gray-900 mt-1" x-text="stats.totalTags">0</h3>
                    </div>
                    <div class="w-12 h-12 bg-primary/10 rounded-full flex items-center justify-center">
                        <i class="ri-price-tag-3-line text-xl text-primary"></i>
                    </div>
                </div>
                <div class="mt-3 flex items-center text-sm">
                    <span class="text-green-500 font-medium flex items-center">
                        <i class="ri-arrow-up-line mr-1"></i> <span x-text="stats.tagGrowth + '%'"></span>
                    </span>
                    <span class="text-gray-500 ml-2">from last month</span>
                </div>
            </div>

            <!-- Popular Tag -->
            <div class="bg-white rounded-lg border border-gray-200 shadow-sm p-5">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Most Used Tag</p>
                        <h3 class="text-2xl font-bold text-gray-900 mt-1" x-text="stats.mostUsedTag || 'None'"></h3>
                    </div>
                    <div class="w-12 h-12 bg-blue-50 rounded-full flex items-center justify-center">
                        <i class="ri-hashtag text-xl text-blue-600"></i>
                    </div>
                </div>
                <div class="mt-3 flex items-center text-sm">
                    <span class="text-blue-500 font-medium flex items-center">
                        <i class="ri-information-line mr-1"></i> <span x-text="stats.mostUsedTagCount"></span> products
                    </span>
                </div>
            </div>

            <!-- Tag Types -->
            <div class="bg-white rounded-lg border border-gray-200 shadow-sm p-5">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Tag Types</p>
                        <h3 class="text-2xl font-bold text-gray-900 mt-1" x-text="stats.tagTypeCount">0</h3>
                    </div>
                    <div class="w-12 h-12 bg-purple-50 rounded-full flex items-center justify-center">
                        <i class="ri-price-tag-3-fill text-xl text-purple-600"></i>
                    </div>
                </div>
                <div class="mt-3 flex items-center text-sm">
                    <span class="text-purple-500 font-medium flex items-center">
                        <i class="ri-information-line mr-1"></i> Different tag types
                    </span>
                </div>
            </div>

            <!-- Active Tags -->
            <div class="bg-white rounded-lg border border-gray-200 shadow-sm p-5">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Active Tags</p>
                        <h3 class="text-2xl font-bold text-gray-900 mt-1" x-text="stats.activeTags">0</h3>
                    </div>
                    <div class="w-12 h-12 bg-green-50 rounded-full flex items-center justify-center">
                        <i class="ri-check-line text-xl text-green-600"></i>
                    </div>
                </div>
                <div class="mt-3 flex items-center text-sm">
                    <span class="text-green-500 font-medium flex items-center">
                        <i class="ri-information-line mr-1"></i> <span x-text="stats.activeTagsPercent"></span>% of total
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
                    <h3 class="text-lg font-medium text-gray-900">Filter Tags</h3>
                </div>
                <button @click="showFilters = false" class="text-gray-400 hover:text-gray-600 transition-colors">
                    <i class="ri-close-line text-lg"></i>
                </button>
            </div>

            <div class="p-5">
                <form id="tagFilterForm" @submit.prevent="applyFilters()">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5">
                        <!-- Tag Type Filter -->
                        <div class="relative">
                            <label for="type" class="block text-sm font-medium text-gray-700 mb-1.5">Tag Type</label>
                            <div class="relative rounded-lg border border-gray-200">
                                <select id="type" name="type" x-model="filters.type"
                                    class="block w-full pl-4 pr-10 py-2.5 rounded-lg border-gray-200 bg-gray-50 focus:border-primary focus:ring focus:ring-primary/20 focus:ring-opacity-50 text-sm transition-all shadow-sm">
                                    <option value="">All Types</option>
                                    <option value="product">Product</option>
                                    <option value="blog">Blog</option>
                                    <option value="content">Content</option>
                                    <option value="custom">Custom</option>
                                </select>
                            </div>
                        </div>

                        <!-- Color Filter -->
                        <div class="relative">
                            <label for="color" class="block text-sm font-medium text-gray-700 mb-1.5">Color</label>
                            <div class="relative rounded-lg border border-gray-200">
                                <select id="color" name="color" x-model="filters.color"
                                    class="block w-full pl-4 pr-10 py-2.5 rounded-lg border-gray-200 bg-gray-50 focus:border-primary focus:ring focus:ring-primary/20 focus:ring-opacity-50 text-sm transition-all shadow-sm">
                                    <option value="">All Colors</option>
                                    <option value="red">Red</option>
                                    <option value="blue">Blue</option>
                                    <option value="green">Green</option>
                                    <option value="yellow">Yellow</option>
                                    <option value="purple">Purple</option>
                                    <option value="pink">Pink</option>
                                    <option value="gray">Gray</option>
                                    <option value="black">Black</option>
                                </select>
                            </div>
                        </div>

                        <!-- Sort By Filter -->
                        <div class="relative">
                            <label for="sort" class="block text-sm font-medium text-gray-700 mb-1.5">Sort By</label>
                            <div class="relative rounded-lg border border-gray-200">
                                <select id="sort" name="sort" x-model="filters.sort"
                                    class="block w-full pl-4 pr-10 py-2.5 rounded-lg border-gray-200 bg-gray-50 focus:border-primary focus:ring focus:ring-primary/20 focus:ring-opacity-50 text-sm transition-all shadow-sm">
                                    <option value="name">Name</option>
                                    <option value="created_at">Date Created</option>
                                    <option value="products_count">Products Count</option>
                                </select>
                            </div>
                        </div>

                        <!-- Search Filter -->
                        <div>
                            <label for="search" class="block text-sm font-medium text-gray-700 mb-1.5">Search
                                Tags</label>
                            <div class="relative rounded-lg border border-gray-200">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="ri-search-line text-gray-400"></i>
                                </div>
                                <input type="text" id="search" name="search" x-model="filters.search"
                                    class="block w-full pl-10 pr-4 py-2.5 rounded-lg border-gray-200 bg-gray-50 focus:border-primary focus:ring focus:ring-primary/20 focus:ring-opacity-50 text-sm transition-all shadow-sm"
                                    placeholder="Tag name...">
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

        <!-- Tags Grid -->
        <div class="bg-white rounded-lg border border-gray-200 shadow-sm overflow-hidden">
            <div class="flex justify-between items-center p-5 border-b border-gray-100">
                <h3 class="text-lg font-medium text-gray-900">All Tags</h3>
                <div class="flex items-center space-x-2">
                    <span class="text-sm text-gray-500">Showing <span x-text="tagsCount"></span> of <span
                            x-text="stats.totalTags"></span> tags</span>
                </div>
            </div>

            <div class="p-6" x-show="!isLoading">
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                    <template x-for="tag in tags" :key="tag.id">
                        <div
                            class="border border-gray-200 rounded-lg overflow-hidden shadow-sm hover:shadow-md transition-shadow">
                            <div class="p-4" :class="'border-t-4 border-' + (tag.color || 'gray') + '-500'">
                                <div class="flex justify-between items-start mb-3">
                                    <h4 class="text-md font-medium text-gray-900 truncate" x-text="tag.name"></h4>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
                                        :class="tag.type === 'product' ? 'bg-blue-100 text-blue-800' :
                                            tag.type === 'blog' ? 'bg-green-100 text-green-800' :
                                            tag.type === 'content' ? 'bg-purple-100 text-purple-800' :
                                            'bg-gray-100 text-gray-800'">
                                        <span x-text="tag.type"></span>
                                    </span>
                                </div>
                                <p class="text-sm text-gray-500 mb-3 line-clamp-2"
                                    x-text="tag.description || 'No description'"></p>
                                <div class="flex justify-between items-center">
                                    <span class="text-xs text-gray-500">
                                        <i class="ri-shopping-bag-line"></i>
                                        <span x-text="tag.products_count || 0"></span> products
                                    </span>
                                    <div class="flex space-x-1">
                                        <a :href="`{{ url('/admin/tags') }}/${tag.id}/edit`" class="text-gray-500 hover:text-primary">
                                            <i class="ri-pencil-line"></i>
                                        </a>
                                        <button @click="confirmDelete(tag)" class="text-gray-500 hover:text-red-600">
                                            <i class="ri-delete-bin-line"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>
            </div>

            <!-- Loading Indicator -->
            <div class="p-10 flex justify-center items-center" x-show="isLoading">
                <div class="animate-spin rounded-full h-10 w-10 border-b-2 border-primary"></div>
            </div>

            <!-- Empty State -->
            <div class="text-center py-16" x-show="!isLoading && tags.length === 0">
                <div class="inline-flex items-center justify-center w-20 h-20 bg-gray-100 rounded-full mb-6">
                    <i class="ri-price-tag-3-line text-3xl text-gray-400"></i>
                </div>
                <h3 class="text-xl font-medium text-primary mb-3">No tags found</h3>
                <p class="text-gray-500 mb-8 max-w-md mx-auto">There are no tags matching your criteria.</p>
                <div class="flex justify-center gap-4">
                    <button @click="resetFilters()"
                        class="inline-flex items-center px-6 py-3 border border-gray-300 bg-white text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                        <i class="ri-refresh-line mr-2"></i> Reset Filters
                    </button>
                    <a href="{{ route('tags.create') }}"
                        class="inline-flex items-center px-6 py-3 border border-transparent bg-primary text-white rounded-lg hover:bg-primary/90 transition-colors">
                        <i class="ri-add-line mr-2"></i> Add Tag
                    </a>
                </div>
            </div>

            <!-- Pagination -->
            <div class="px-6 py-4 border-t border-gray-200 flex items-center justify-between"
                x-show="!isLoading && tags.length > 0">
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
                            <span class="font-medium" x-text="Math.min(currentPage * perPage, totalTags)"></span>
                            of
                            <span class="font-medium" x-text="totalTags"></span>
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

        <!-- Delete Confirmation Modal -->
        <div x-show="showDeleteModal" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
            <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                    <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
                </div>
                <!-- Modal -->
                <div
                    class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div
                                class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                                <i class="ri-error-warning-line text-red-600"></i>
                            </div>
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                                <h3 class="text-lg leading-6 font-medium text-gray-900">Delete Tag</h3>
                                <div class="mt-2">
                                    <p class="text-sm text-gray-500">
                                        Are you sure you want to delete the tag <span class="font-medium"
                                            x-text="tagToDelete?.name"></span>? This action cannot be undone and may affect
                                        products that have this tag.
                                    </p>
                                    <div class="mt-3 bg-yellow-50 p-3 rounded-md" x-show="tagHasProducts">
                                        <p class="text-sm text-yellow-700">
                                            <i class="ri-alert-line mr-1"></i> This tag has <span class="font-medium"
                                                x-text="tagToDelete?.products_count"></span> product(s) assigned to it.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <form :action="'{{ route('tags.destroy', ':id') }}'.replace(':id', tagToDelete?.id)" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm"
                                :disabled="isDeleting">
                                <span x-show="isDeleting" class="inline-block animate-spin mr-2">
                                    <i class="ri-loader-4-line"></i>
                                </span>
                                <span>Delete</span>
                            </button>
                        </form>
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
    function tagsData() {
        return {
            tags: [],
            stats: {
                totalTags: 0,
                mostUsedTag: null,
                mostUsedTagCount: 0,
                tagTypeCount: 0,
                tagGrowth: 0,
                activeTags: 0,
                activeTagsPercent: 0
            },
            filters: {
                type: '',
                color: '',
                search: '',
                sort: 'name',
                direction: 'asc'
            },
            pagination: {
                current_page: 1,
                per_page: 12,
                total: 0,
                last_page: 1
            },
            tagToDelete: null,
            isLoading: true,
            isDeleting: false,
            showFilters: false,
            showDeleteModal: false,
            tagHasProducts: false,

            init() {
                this.fetchTags();

                // Check URL params for filters
                const urlParams = new URLSearchParams(window.location.search);
                this.filters = {
                    type: urlParams.get('type') || '',
                    color: urlParams.get('color') || '',
                    search: urlParams.get('search') || '',
                    sort: urlParams.get('sort') || 'name',
                    direction: urlParams.get('direction') || 'asc'
                };

                this.showFilters = Object.values(this.filters).some(val => val !== '' && val !== 'name' && val !==
                    'asc');
            },

            fetchTags() {
                this.isLoading = true;

                // Build the query string
                const queryParams = new URLSearchParams();
                queryParams.append('page', this.currentPage);

                for (const [key, value] of Object.entries(this.filters)) {
                    if (value) {
                        queryParams.append(key, value);
                    }
                }

                fetch(`/admin/tags/data?${queryParams.toString()}`, {
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
                            this.tags = data.tags.data;
                            this.pagination = {
                                current_page: data.tags.current_page,
                                per_page: data.tags.per_page,
                                total: data.tags.total,
                                last_page: data.tags.last_page
                            };
                            this.stats = {
                                totalTags: data.stats.totalTags,
                                mostUsedTag: data.stats.mostUsedTag,
                                mostUsedTagCount: data.stats.mostUsedTagCount,
                                tagTypeCount: data.stats.tagTypeCount,
                                tagGrowth: data.stats.tagGrowth,
                                activeTags: data.stats.activeTags,
                                activeTagsPercent: data.stats.activeTagsPercent
                            };
                        } else {
                            console.error('Error fetching tags:', data.message);
                        }
                        this.isLoading = false;
                    })
                    .catch(error => {
                        console.error('Error fetching tags:', error);
                        this.isLoading = false;
                    });
            },

            get tagsCount() {
                return this.tags.length;
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

            get totalTags() {
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
                this.fetchTags();
                this.updateURLParams();
            },

            resetFilters() {
                this.filters = {
                    type: '',
                    color: '',
                    search: '',
                    sort: 'name',
                    direction: 'asc'
                };
                this.currentPage = 1;
                this.fetchTags();
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
                    this.fetchTags();
                }
            },

            prevPage() {
                if (this.currentPage > 1) {
                    this.currentPage--;
                    this.fetchTags();
                }
            },

            goToPage(page) {
                if (page !== '...' && page !== this.currentPage) {
                    this.currentPage = page;
                    this.fetchTags();
                }
            },

            confirmDelete(tag) {
                this.tagToDelete = tag;
                this.tagHasProducts = tag.products_count > 0;
                this.showDeleteModal = true;
            },

            exportTags() {
                // Build the query string with current filters
                const queryParams = new URLSearchParams();

                for (const [key, value] of Object.entries(this.filters)) {
                    if (value) {
                        queryParams.append(key, value);
                    }
                }

                window.location.href = "/admin/tags/export?" + queryParams.toString();
            }
        };
    }
</script>