@extends('layouts.app')

@section('title', 'Shop - Browse our Collection')
<script src="{{ asset('js/shop.js') }}"></script>

@section('content')
    <div class="container  px-8 py-6">
        <div class="flex items-center text-sm mb-6">
            <a href="/" class="text-gray-500 hover:text-primary">Home</a>
            <span class="mx-2 text-gray-400">/</span>
            <span class="font-medium">Shop</span>
        </div>

        <div class="flex flex-wrap gap-4 items-center justify-between mb-8">
            <button id="openFilters"
                class="saji-filter control-btn flex items-center justify-center gap-2 py-1 px-4 border text-gray-700 hover:bg-gray-50 transition">
                <i class="ri-filter-3-line"></i>
                <span>Filter</span>
            </button>


            <div class="flex items-center gap-4">
                <div class="control-btn flex overflow-hidden gap-2">
                    <button class="view-toggle-btn active" data-view="grid" aria-label="Grid View">
                        <i class="ri-layout-grid-line text-2xl"></i>
                    </button>
                    <button class="view-toggle-btn" data-view="list" aria-label="List View">
                        <i class="ri-list-check text-2xl"></i>
                    </button>
                </div>

                <!-- Sorting Dropdown -->
                <div class="dropdown-saji relative border flex justify-between border-gray-200 pl-5 py-2 rounded-md">
                    <select id="sort-select" class="control-btn appearance-none pr-10 rounded-md bg-transparent text-sm focus:outline-none focus:ring-2 focus:ring-transparent focus:border-transparent">>
                        <option value="default"
                            {{ request()->get('sort') == 'default' || !request()->has('sort') ? 'selected' : '' }}>Default
                            sorting</option>
                        <option value="price-low" {{ request()->get('sort') == 'price-low' ? 'selected' : '' }}>Price: low
                            to high</option>
                        <option value="price-high" {{ request()->get('sort') == 'price-high' ? 'selected' : '' }}>Price:
                            high to low</option>
                        <option value="newest" {{ request()->get('sort') == 'newest' ? 'selected' : '' }}>Newest first
                        </option>
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4">
                        <i class="ri-arrow-down-s-line text-gray-500"></i>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- Main Shop Content -->
    <div class="container mx-auto px-8 pb-16">
        <!-- Products Grid -->
        <div id="products-container" class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-4 md:gap-6">
            @foreach ($products as $product)
                <div class="product-card bg-white rounded-lg border border-gray-200 overflow-hidden transition-all hover:shadow-md"
                    data-product-id="{{ $product->id }}">
                    <a href="{{ route('shop.product', $product->slug) }}" class="block relative">
                        <div class="aspect-square bg-gray-100 overflow-hidden">
                            @if (isset($product->images) && $product->images->count() > 0)
                                <img src="{{ asset('storage/' . $product->images->first()->path) }}"
                                    alt="{{ $product->name }}"
                                    class="w-full h-full object-cover transition-transform duration-500 hover:scale-105">
                            @else
                                <img src="{{ asset('images/placeholder.jpg') }}" alt="{{ $product->name }}"
                                    class="w-full h-full object-cover transition-transform duration-500 hover:scale-105">
                            @endif
                        </div>

                        @if ($product->sale_price)
                            <div class="absolute top-2 left-2 bg-red-500 text-white text-xs py-1 px-2 rounded-lg">
                                SALE
                            </div>
                        @endif

                        <button
                            class="add-to-wishlist absolute top-2 right-2 bg-white rounded-full w-8 h-8 flex items-center justify-center shadow-md hover:bg-gray-50 transition">
                            <i class="ri-heart-line text-gray-500 hover:text-primary"></i>
                        </button>
                    </a>

                    <div class="p-3">
                        @if (isset($product->category) && $product->category)
                            <div class="text-xs text-gray-500 mb-1">{{ $product->category->name }}</div>
                        @endif

                        <h3 class="font-medium mb-2 text-gray-900 text-sm">
                            <a href="{{ route('shop.product', $product->slug) }}">{{ $product->name }}</a>
                        </h3>

                        <div class="flex items-center justify-between">
                            <div class="product-price">
                                @if ($product->sale_price)
                                    <span class="font-medium">{{ number_format($product->sale_price, 2) }} €</span>
                                    <span
                                        class="text-gray-500 line-through text-xs ml-1">{{ number_format($product->price, 2) }}
                                        €</span>
                                @else
                                    <span class="font-medium">{{ number_format($product->price, 2) }} €</span>
                                @endif
                            </div>
                        </div>

                        <button
                            class="add-to-cart-btn w-full mt-2 bg-primary text-white py-2 rounded-lg hover:bg-primary-dark transition flex items-center justify-center text-sm"
                            data-product-id="{{ $product->id }}">
                            <i class="ri-shopping-bag-2-line mr-2"></i> Add to Cart
                        </button>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Load More Container -->
        <div id="load-more-container" class="text-center mt-10 {{ $products->hasMorePages() ? '' : 'hidden' }}">
            <button id="load-more-btn" class="control-btn px-8">
                Load More Products
                <i class="ri-loader-4-line ml-2 hidden"></i>
            </button>
        </div>

        <!-- No Products Message -->
        @if ($products->count() === 0)
            <div class="flex flex-col items-center justify-center py-16 bg-white rounded-lg border border-gray-200">
                <div class="text-gray-400 mb-4">
                    <i class="ri-shopping-bag-3-line text-6xl"></i>
                </div>
                <h2 class="text-xl font-medium text-gray-900 mb-2">No products found</h2>
                <p class="text-gray-500 mb-6 text-center max-w-md">Sorry, we couldn't find any products matching your
                    criteria. Try adjusting your filters or browse our other categories.</p>
                <a href="{{ route('shop.index') }}"
                    class="bg-primary text-white px-6 py-2 rounded-lg hover:bg-primary-dark transition">
                    Reset Filters
                </a>
            </div>
        @endif
    </div>

    <!-- Filter Modal -->
    <div id="filterModal" class="fixed inset-0 z-50 hidden">
        <!-- Overlay -->
        <div id="overlay" class="absolute inset-0 bg-black opacity-50"></div>

        <!-- Filter Sidebar -->
        <div id="filterSidebar"
            class="filter-sidebar fixed top-0 left-0 h-full w-80 md:w-96 bg-white z-40 shadow-xl overflow-hidden flex flex-col transform -translate-x-full transition-transform duration-300">
            <div class="p-4 border-b border-gray-100 flex justify-between items-center">
                <h2 class="text-xl font-medium">Filter Products</h2>
                <button id="closeFilters" class="text-gray-500 hover:text-gray-900 transition rounded-md">
                    <i class="ri-close-line text-2xl"></i>
                </button>
            </div>

            <div class="flex-1 overflow-y-auto p-4">
                <!-- Applied Filters -->
                <div id="applied-filters" class="mb-6 {{ count(request()->except(['page', 'sort'])) ? '' : 'hidden' }}">
                    <h3 class="font-medium mb-3 text-sm uppercase">Applied Filters</h3>
                    <div class="filter-tags flex flex-wrap gap-2">
                        @if (request()->has('min_price') || request()->has('max_price'))
                            <div class="filter-tag bg-gray-100 text-xs py-1.5 px-2.5 rounded-lg flex items-center">
                                Prix: {{ request()->get('min_price', 0) }} € — {{ request()->get('max_price', 100) }} €
                                <button type="button" class="ml-1 text-gray-500" data-remove="price">
                                    <i class="ri-close-line"></i>
                                </button>
                            </div>
                        @endif

                        @if (request()->has('category'))
                            @foreach ((array) request()->get('category') as $catId)
                                @php
                                    $catName = $categories->firstWhere('id', $catId)->name ?? 'Category';
                                @endphp
                                <div class="filter-tag bg-gray-100 text-xs py-1.5 px-2.5 rounded-lg flex items-center">
                                    {{ $catName }}
                                    <button type="button" class="ml-1 text-gray-500" data-remove="category"
                                        data-value="{{ $catId }}">
                                        <i class="ri-close-line"></i>
                                    </button>
                                </div>
                            @endforeach
                        @endif

                        @if (request()->has('color'))
                            @foreach ((array) request()->get('color') as $color)
                                <div class="filter-tag bg-gray-100 text-xs py-1.5 px-2.5 rounded-lg flex items-center">
                                    {{ ucfirst($color) }}
                                    <button type="button" class="ml-1 text-gray-500" data-remove="color"
                                        data-value="{{ $color }}">
                                        <i class="ri-close-line"></i>
                                    </button>
                                </div>
                            @endforeach
                        @endif
                    </div>

                    <button id="clear-all-filters" class="mt-3 text-sm text-primary hover:underline">Clear all
                        filters</button>
                </div>

                <!-- Filter by Price -->
                <div class="mb-6">
                    <h3 class="font-medium mb-4 uppercase text-sm">Filter by Price</h3>

                    <div class="price-slider mb-4">
                        <div class="relative h-1 bg-gray-200 rounded-full">
                            <div id="price-range-progress" class="absolute h-1 bg-primary rounded-full"></div>
                            <input type="range" id="price-min" min="0" max="100"
                                value="{{ request()->get('min_price', 0) }}"
                                class="absolute w-full h-1 opacity-0 cursor-pointer">
                        </div>
                    </div>

                    <div class="flex items-center justify-between">
                        <div class="text-sm">
                            Prix: <span id="price-min-value">{{ request()->get('min_price', 0) }}</span> € —
                            <span id="price-max-value">{{ request()->get('max_price', 100) }}</span> €
                        </div>
                    </div>

                    <button id="apply-price-filter"
                        class="mt-4 w-full bg-black text-white py-2 rounded-lg text-sm hover:bg-gray-800 transition">
                        Apply Price Filter
                    </button>
                </div>

                <!-- Categories -->
                <div class="mb-6">
                    <h3 class="font-medium mb-4 uppercase text-sm">Categories</h3>

                    <div class="space-y-2">
                        @foreach ($categories ?? [] as $category)
                            <div class="flex items-center">
                                <input type="checkbox" id="cat-{{ $category->id }}" name="category[]"
                                    value="{{ $category->id }}"
                                    class="category-filter h-4 w-4 text-primary rounded border-gray-300 focus:ring-primary"
                                    {{ in_array($category->id, (array) request()->get('category', [])) ? 'checked' : '' }}>
                                <label for="cat-{{ $category->id }}" class="ml-2 text-sm text-gray-700">
                                    {{ $category->name }} <span
                                        class="text-gray-500">({{ $category->products_count ?? 0 }})</span>
                                </label>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Colors -->
                <div class="mb-6">
                    <h3 class="font-medium mb-4 uppercase text-sm">Colors</h3>

                    <div class="flex flex-wrap gap-2">
                        <label class="color-option cursor-pointer">
                            <input type="checkbox" name="color[]" value="black" class="sr-only color-filter"
                                {{ in_array('black', (array) request()->get('color', [])) ? 'checked' : '' }}>
                            <span
                                class="w-8 h-8 rounded-full bg-black inline-block border border-transparent hover:border-gray-300"></span>
                        </label>
                        <label class="color-option cursor-pointer">
                            <input type="checkbox" name="color[]" value="white" class="sr-only color-filter"
                                {{ in_array('white', (array) request()->get('color', [])) ? 'checked' : '' }}>
                            <span
                                class="w-8 h-8 rounded-full bg-white inline-block border border-gray-300 hover:border-gray-400"></span>
                        </label>
                        <label class="color-option cursor-pointer">
                            <input type="checkbox" name="color[]" value="red" class="sr-only color-filter"
                                {{ in_array('red', (array) request()->get('color', [])) ? 'checked' : '' }}>
                            <span
                                class="w-8 h-8 rounded-full bg-red-600 inline-block border border-transparent hover:border-gray-300"></span>
                        </label>
                        <label class="color-option cursor-pointer">
                            <input type="checkbox" name="color[]" value="blue" class="sr-only color-filter"
                                {{ in_array('blue', (array) request()->get('color', [])) ? 'checked' : '' }}>
                            <span
                                class="w-8 h-8 rounded-full bg-blue-600 inline-block border border-transparent hover:border-gray-300"></span>
                        </label>
                        <label class="color-option cursor-pointer">
                            <input type="checkbox" name="color[]" value="green" class="sr-only color-filter"
                                {{ in_array('green', (array) request()->get('color', [])) ? 'checked' : '' }}>
                            <span
                                class="w-8 h-8 rounded-full bg-green-600 inline-block border border-transparent hover:border-gray-300"></span>
                        </label>
                        <label class="color-option cursor-pointer">
                            <input type="checkbox" name="color[]" value="yellow" class="sr-only color-filter"
                                {{ in_array('yellow', (array) request()->get('color', [])) ? 'checked' : '' }}>
                            <span
                                class="w-8 h-8 rounded-full bg-yellow-500 inline-block border border-transparent hover:border-gray-300"></span>
                        </label>
                    </div>
                </div>
            </div>

            <div class="sticky bottom-0 bg-white p-4 border-t border-gray-100">
                <button id="apply-all-filters"
                    class="w-full bg-primary text-white py-3 rounded-lg hover:bg-primary-dark transition">
                    Apply Filters
                </button>
            </div>
        </div>
    </div>
@endsection

@section('styles')
    <style>
        /* Common Control Buttons Styling */
        .control-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            height: 46px;
            padding: 0 20px;
            background-color: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 500;
            color: #374151;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .control-btn:hover {
            background-color: #f9fafb;
        }

        .saji-filter {
            border-radius: 12px !important;
        }

        /* View Toggle Buttons */
        .view-toggle-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 46px;
            height: 46px;
            background-color: #fff;
            color: #6b7280;
            border: none;
            transition: all 0.2s ease;
        }

        .view-toggle-btn:hover {
            background-color: #f9fafb;
        }

        .view-toggle-btn.active {
            color: #374151;
            background-color: #f3f4f6;
        }

        /* Color selection */
        .color-option input:checked+span {
            outline: 2px solid var(--color-primary);
            outline-offset: 2px;
        }

        /* Price range slider */
        #price-min {
            -webkit-appearance: none;
            height: 1rem;
            background: transparent;
        }

        #price-min::-webkit-slider-thumb {
            -webkit-appearance: none;
            width: 16px;
            height: 16px;
            border-radius: 50%;
            background: var(--color-primary);
            cursor: pointer;
            border: 2px solid white;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.2);
        }

        #price-min::-moz-range-thumb {
            width: 16px;
            height: 16px;
            border-radius: 50%;
            background: var(--color-primary);
            cursor: pointer;
            border: 2px solid white;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.2);
        }

        /* Product cards animation */
        .product-card {
            opacity: 0;
            transform: translateY(20px);
            animation: fadeInUp 0.5s ease forwards;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Staggered animation for product cards */
        .product-card:nth-child(1) {
            animation-delay: 0.05s;
        }

        .product-card:nth-child(2) {
            animation-delay: 0.1s;
        }

        .product-card:nth-child(3) {
            animation-delay: 0.15s;
        }

        .product-card:nth-child(4) {
            animation-delay: 0.2s;
        }

        .product-card:nth-child(5) {
            animation-delay: 0.25s;
        }
    </style>
@endsection

@section('scripts')
    <script src="{{ asset('js/shop.js') }}"></script>
@endsection
