<!-- This is a template for a single product card with AJAX cart functionality -->
<div class="product-card group" data-product-id="{{ $product->id }}">
    <div class="relative overflow-hidden rounded-lg aspect-[3/4] mb-4">
        @if ($product->sale_price)
            <div class="absolute top-4 left-4 z-10 bg-black text-white text-xs font-medium px-3 py-1 rounded-full">
                SALE
            </div>
        @endif
        @if ($product->is_new)
            <div class="absolute top-4 left-4 z-10 bg-black text-white text-xs font-medium px-3 py-1 rounded-full">
                NEW
            </div>
        @endif
        <button
            class="product-wishlist-btn absolute top-4 right-4 z-10 bg-white w-10 h-10 rounded-full flex items-center justify-center shadow-md transition-all duration-300 hover:bg-gray-100">
            <i class="ri-heart-line wishlist-icon text-lg transition-all"></i>
        </button>
        <a href="/product/{{ $product->id }}">
            @if (isset($product->images) && $product->images->count() > 0)
                <img src="{{ asset('storage/' . $product->images->first()->path) }}"
                    alt="{{ $product->name }}"
                    class="w-full h-full object-cover transform transition-transform duration-700 group-hover:scale-105">
            @else
                <img src="{{ asset('images/placeholder.jpg') }}" alt="{{ $product->name }}"
                    class="w-full h-full object-cover transform transition-transform duration-700 group-hover:scale-105">
            @endif
        </a>
    </div>
    <div class="product-info">
        <div class="flex justify-between items-center mb-1">
            <h3 class="text-base font-medium text-black/80">
                <a href="/product/{{ $product->id }}"
                    class="hover:text-black transition-colors">{{ $product->name }}</a>
            </h3>
            @if (isset($product->category) && $product->category)
                <span
                    class="text-xs uppercase tracking-wider text-black/50">{{ $product->category->name }}</span>
            @endif
        </div>
        <div class="flex justify-between items-center mb-3">
            <div>
                @if ($product->sale_price)
                    <span class="font-medium">${{ number_format($product->sale_price, 2) }}</span>
                    <span
                        class="ml-2 text-sm text-black/40 line-through">${{ number_format($product->price, 2) }}</span>
                @else
                    <span class="font-medium">${{ number_format($product->price, 2) }}</span>
                @endif
            </div>
            <div class="flex items-center">
                <i class="ri-star-fill text-black text-xs"></i>
                <span class="text-xs ml-1">4.8</span>
            </div>
        </div>
        <button
            class="add-to-cart-btn w-full bg-black text-white hover:bg-black/80 transition-colors duration-300 py-2 rounded-lg font-medium text-sm">
            Add to Cart
        </button>
    </div>
</div>