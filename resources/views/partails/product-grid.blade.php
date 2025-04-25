<!-- Replace your current foreach loop with this updated version -->
<div id="products-container" class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-4 md:gap-6 md:px-4">
    @foreach ($products as $product)
        <div class="product-card bg-white rounded-lg border border-gray-200 overflow-hidden transition-all hover:shadow-md flex flex-col"
            data-product-id="{{ $product->id }}">
            <!-- This wrapper div helps with the list view layout -->
            <div class="product-image-container aspect-square bg-gray-100 overflow-hidden relative">
                <a href="{{ route('shop.product', $product->slug) }}" class="block">
                    @if (isset($product->images) && $product->images->count() > 0)
                        <img src="{{ asset('storage/' . $product->images->first()->path) }}"
                            alt="{{ $product->name }}"
                            class="w-full h-full object-cover transition-transform duration-500 hover:scale-105">
                    @else
                        <img src="{{ asset('images/placeholder.jpg') }}" alt="{{ $product->name }}"
                            class="w-full h-full object-cover transition-transform duration-500 hover:scale-105">
                    @endif
                </a>

                @if ($product->sale_price)
                    <div class="absolute top-2 left-2 bg-red-500 text-white text-xs py-1 px-2 rounded-lg">
                        SALE
                    </div>
                @endif

                <button
                    class="product-wishlist-btn absolute top-2 right-2 z-10 w-10 h-10 rounded-full flex items-center justify-center transition-all duration-300 hover:bg-transparent">
                    <i class="ri-heart-line wishlist-icon text-lg transition-all"></i>
                </button>
            </div>

            <div class="product-content p-3 flex-1 flex flex-col">
                <div class="product-details flex-1">
                    <div class="product-meta">
                        @if (isset($product->category) && $product->category)
                            <div class="product-category text-xs text-gray-500">{{ $product->category->name }}</div>
                        @endif
                    </div>

                    <h3 class="product-title font-medium mb-2 text-gray-900 text-sm">
                        <a href="{{ route('shop.product', $product->slug) }}">{{ $product->name }}</a>
                    </h3>

                    <!-- Product description - hidden in grid view, visible in list view -->
                    <div class="product-description hidden text-sm text-gray-600 mb-3">
                        {{ $product->short_description ?? 'A high-quality product for your collection.' }}
                    </div>
                </div>

                <div class="product-actions mt-auto">
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

                    <div class="product-buttons">
                        <button
                            class="add-to-cart-btn w-full mt-2 bg-primary text-white py-2 rounded-lg hover:bg-primary-dark transition flex items-center justify-center text-sm"
                            data-product-id="{{ $product->id }}">
                            <i class="ri-shopping-bag-2-line mr-2"></i> Add to Cart
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</div>