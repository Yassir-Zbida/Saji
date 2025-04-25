@extends('layouts.app')

@section('title', $product->name ?? 'Product Details')

@section('content')
    <div class="container px-8 py-6">
        <!-- Breadcrumb -->
        <div class="flex items-center text-sm mb-6">
            <a href="/" class="text-gray-500 hover:text-primary">Home</a>
            <span class="mx-2 text-gray-400">/</span>
            <a href="{{ route('shop.index') }}" class="text-gray-500 hover:text-primary">Shop</a>
            <span class="mx-2 text-gray-400">/</span>
            <span class="font-medium">{{ $product->name }}</span>
        </div>

        <!-- Product Detail Layout -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-16">
            <!-- Product Images Gallery -->
            <div class="product-gallery">
                <!-- Main Image -->
                <div class="main-image-container bg-gray-100 rounded-lg overflow-hidden mb-4">
                    <div id="mainImage" class="aspect-square relative">
                        @if ($product->images && $product->images->count() > 0)
                            <img src="{{ asset('storage/' . $product->images->first()->path) }}" 
                                alt="{{ $product->name }}" 
                                class="w-full h-full object-cover">
                            
                            <!-- Sale Badge -->
                            @if ($product->sale_price)
                                <div class="absolute top-4 left-4 bg-red-500 text-white text-xs py-1 px-2 rounded-lg">
                                    SALE
                                </div>
                            @endif
                        @else
                            <img src="{{ asset('images/placeholder.jpg') }}" 
                                alt="{{ $product->name }}" 
                                class="w-full h-full object-cover">
                        @endif
                    </div>
                </div>

                <!-- Thumbnail Gallery -->
                @if ($product->images && $product->images->count() > 1)
                    <div class="thumbnail-gallery grid grid-cols-5 gap-2">
                        @foreach ($product->images as $index => $image)
                            <div class="thumbnail-item aspect-square bg-gray-100 rounded-md overflow-hidden cursor-pointer {{ $index === 0 ? 'ring-2 ring-primary' : '' }}" 
                                data-image="{{ asset('storage/' . $image->path) }}">
                                <img src="{{ asset('storage/' . $image->path) }}" 
                                    alt="{{ $product->name }}" 
                                    class="w-full h-full object-cover">
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Product Information -->
            <div class="product-info">
                <!-- Category -->
                @if ($product->category)
                    <div class="product-category text-sm text-gray-500 mb-2">{{ $product->category->name }}</div>
                @endif

                <!-- Product Title -->
                <h1 class="text-2xl md:text-3xl font-medium text-gray-900 mb-4">{{ $product->name }}</h1>

                <!-- Product Rating -->
                <div class="product-rating flex items-center mb-4">
                    <div class="stars flex mr-2">
                        @php
                            $rating = $product->reviews && $product->reviews->count() > 0 
                                ? round($product->reviews->avg('rating'), 1) : 0;
                            $fullStars = floor($rating);
                            $halfStar = $rating - $fullStars >= 0.5;
                        @endphp

                        @for ($i = 1; $i <= 5; $i++)
                            @if ($i <= $fullStars)
                                <i class="ri-star-fill text-yellow-400"></i>
                            @elseif ($i == $fullStars + 1 && $halfStar)
                                <i class="ri-star-half-fill text-yellow-400"></i>
                            @else
                                <i class="ri-star-line text-gray-300"></i>
                            @endif
                        @endfor
                    </div>
                    
                    @if ($product->reviews && $product->reviews->count() > 0)
                        <span class="text-sm text-gray-500">
                            {{ $rating }} ({{ $product->reviews->count() }} {{ Str::plural('review', $product->reviews->count()) }})
                        </span>
                    @else
                        <span class="text-sm text-gray-500">No reviews yet</span>
                    @endif
                </div>

                <!-- Product Price -->
                <div class="product-price text-xl font-medium mb-4">
                    @if ($product->sale_price)
                        <span class="text-primary">{{ number_format($product->sale_price, 2) }} €</span>
                        <span class="text-gray-500 line-through ml-2">{{ number_format($product->price, 2) }} €</span>
                        @php
                            $discount = round((($product->price - $product->sale_price) / $product->price) * 100);
                        @endphp
                        <span class="ml-2 bg-red-100 text-red-700 text-xs px-2 py-1 rounded">
                            Save {{ $discount }}%
                        </span>
                    @else
                        <span class="text-primary">{{ number_format($product->price, 2) }} €</span>
                    @endif
                </div>

                <!-- Short Description -->
                @if ($product->short_description)
                    <div class="product-short-description text-gray-600 mb-6 text-sm">
                        {!! nl2br(e($product->short_description)) !!}
                    </div>
                @endif

                <!-- Product Attributes -->
                @if ($product->attributeValues && $product->attributeValues->count() > 0)
                    <div class="product-attributes space-y-4 mb-6">
                        @php
                            $groupedAttributes = $product->attributeValues->groupBy(function($attributeValue) {
                                return $attributeValue->attribute ? $attributeValue->attribute->name : 'Other';
                            });
                        @endphp

                        @foreach ($groupedAttributes as $attributeName => $values)
                            <div class="attribute-group">
                                <h3 class="text-sm font-medium text-gray-900 mb-2">{{ $attributeName }}</h3>
                                
                                @if (strtolower($attributeName) == 'color' || strtolower($attributeName) == 'couleur')
                                    <div class="flex flex-wrap gap-2">
                                        @foreach ($values as $index => $attributeValue)
                                            @php
                                                // Try to convert the color name to a CSS color value
                                                $colorValue = strtolower($attributeValue->value);
                                                $colorMap = [
                                                    'noir' => 'black',
                                                    'blanc' => 'white',
                                                    'rouge' => 'red',
                                                    'bleu' => 'blue',
                                                    'vert' => 'green',
                                                    'jaune' => 'yellow',
                                                    'orange' => 'orange',
                                                    'violet' => 'purple',
                                                    'gris' => 'gray',
                                                    'marron' => 'brown',
                                                    'rose' => 'pink',
                                                ];
                                                $colorValue = array_key_exists($colorValue, $colorMap) ? $colorMap[$colorValue] : $colorValue;
                                            @endphp
                                            
                                            <label class="color-option cursor-pointer">
                                                <input type="radio" name="color" value="{{ $attributeValue->value }}" 
                                                    class="sr-only" {{ $index === 0 ? 'checked' : '' }}>
                                                <span class="w-8 h-8 rounded-full inline-block border border-gray-200"
                                                    style="background-color: {{ $colorValue }}"></span>
                                            </label>
                                        @endforeach
                                    </div>
                                @elseif (strtolower($attributeName) == 'size' || strtolower($attributeName) == 'taille')
                                    <div class="flex flex-wrap gap-2">
                                        @foreach ($values as $index => $attributeValue)
                                            <label class="size-option cursor-pointer">
                                                <input type="radio" name="size" value="{{ $attributeValue->value }}" 
                                                    class="sr-only" {{ $index === 0 ? 'checked' : '' }}>
                                                <span class="px-3 py-2 rounded-md border border-gray-300 inline-flex items-center justify-center text-sm
                                                    hover:border-primary peer-checked:border-primary peer-checked:bg-primary-50">
                                                    {{ $attributeValue->value }}
                                                </span>
                                            </label>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="flex flex-wrap gap-2">
                                        @foreach ($values as $index => $attributeValue)
                                            <span class="inline-block px-3 py-1 bg-gray-100 text-gray-800 rounded-md text-xs">
                                                {{ $attributeValue->value }}
                                            </span>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @endif

                <!-- Quantity Selector -->
                <div class="product-quantity flex items-center mb-6">
                    <label for="quantity" class="text-sm font-medium text-gray-900 mr-4">Quantity</label>
                    <div class="quantity-selector flex items-center border border-gray-300 rounded-lg">
                        <button type="button" class="quantity-btn minus w-10 h-10 flex items-center justify-center text-gray-600 hover:text-primary">
                            <i class="ri-subtract-line"></i>
                        </button>
                        <input type="number" id="quantity" name="quantity" min="1" value="1" 
                            class="w-14 h-10 text-center border-none focus:ring-0 focus:outline-none">
                        <button type="button" class="quantity-btn plus w-10 h-10 flex items-center justify-center text-gray-600 hover:text-primary">
                            <i class="ri-add-line"></i>
                        </button>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="product-actions flex flex-wrap gap-4 mb-8">
                    <button id="add-to-cart" 
                        class="flex-1 bg-primary text-white py-3 px-6 rounded-lg hover:bg-primary-dark transition flex items-center justify-center"
                        data-product-id="{{ $product->id }}">
                        <i class="ri-shopping-bag-2-line mr-2"></i>
                        Add to Cart
                    </button>
                    <button id="add-to-wishlist" 
                        class="w-12 h-12 border border-gray-300 rounded-lg flex items-center justify-center hover:bg-gray-50"
                        data-product-id="{{ $product->id }}">
                        <i class="ri-heart-line text-xl wishlist-icon"></i>
                    </button>
                </div>

                <!-- Product Meta -->
                <div class="product-meta border-t border-gray-200 pt-6 space-y-2 text-sm text-gray-500">
                    @if ($product->sku)
                        <div class="flex">
                            <span class="font-medium text-gray-900 w-24">SKU:</span>
                            <span>{{ $product->sku }}</span>
                        </div>
                    @endif
                    
                    @if ($product->category)
                        <div class="flex">
                            <span class="font-medium text-gray-900 w-24">Category:</span>
                            <a href="{{ route('shop.index', ['category' => $product->category->id]) }}" class="hover:text-primary">
                                {{ $product->category->name }}
                            </a>
                        </div>
                    @endif
                    
                    @if ($product->tags && $product->tags->count() > 0)
                        <div class="flex">
                            <span class="font-medium text-gray-900 w-24">Tags:</span>
                            <div class="flex flex-wrap gap-1">
                                @foreach ($product->tags as $tag)
                                    <a href="{{ route('shop.index', ['tag' => $tag->id]) }}" class="hover:text-primary">
                                        {{ $tag->name }}{{ !$loop->last ? ',' : '' }}
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Product Tabs -->
        <div class="product-tabs mb-16">
            <div class="border-b border-gray-200">
                <div class="flex overflow-x-auto">
                    <button class="tab-btn active px-6 py-3 text-sm font-medium whitespace-nowrap border-b-2 border-primary" data-tab="description">
                        Description
                    </button>
                    <button class="tab-btn px-6 py-3 text-sm font-medium whitespace-nowrap text-gray-500 border-b-2 border-transparent" data-tab="attributes">
                        Specifications
                    </button>
                    <button class="tab-btn px-6 py-3 text-sm font-medium whitespace-nowrap text-gray-500 border-b-2 border-transparent" data-tab="reviews">
                        Reviews ({{ $product->reviews ? $product->reviews->count() : 0 }})
                    </button>
                </div>
            </div>

            <div class="tab-content pt-6">
                <!-- Description Tab -->
                <div id="description-tab" class="tab-pane active">
                    <div class="prose max-w-none text-gray-600">
                        @if ($product->description)
                            {!! nl2br(e($product->description)) !!}
                        @else
                            <p>No detailed description available for this product.</p>
                        @endif
                    </div>
                </div>

                <!-- Attributes Tab -->
                <div id="attributes-tab" class="tab-pane hidden">
                    @if ($product->attributeValues && $product->attributeValues->count() > 0)
                        <table class="w-full border-collapse">
                            <tbody>
                                @php
                                    $groupedSpecs = $product->attributeValues->sortBy(function($attributeValue) {
                                        return $attributeValue->attribute ? $attributeValue->attribute->name : 'ZZ';
                                    });
                                @endphp
                                
                                @foreach ($groupedSpecs as $attributeValue)
                                    @if ($attributeValue->attribute)
                                        <tr class="{{ $loop->even ? 'bg-gray-50' : 'bg-white' }}">
                                            <td class="py-3 px-4 border-b border-gray-200 font-medium text-gray-900 w-1/3">
                                                {{ $attributeValue->attribute->name }}
                                            </td>
                                            <td class="py-3 px-4 border-b border-gray-200 text-gray-600">
                                                {{ $attributeValue->value }}
                                            </td>
                                        </tr>
                                    @endif
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <p class="text-gray-600">No specifications available for this product.</p>
                    @endif
                </div>

                <!-- Reviews Tab -->
                <div id="reviews-tab" class="tab-pane hidden">
                    @if ($product->reviews && $product->reviews->count() > 0)
                        <div class="mb-8">
                            <!-- Review Summary -->
                            <div class="flex flex-col md:flex-row gap-8 mb-8">
                                <div class="md:w-1/3">
                                    <div class="text-center p-6 bg-gray-50 rounded-lg">
                                        <div class="text-5xl font-bold text-primary mb-2">
                                            {{ number_format($product->reviews->avg('rating'), 1) }}
                                        </div>
                                        <div class="stars flex justify-center mb-1">
                                            @for ($i = 1; $i <= 5; $i++)
                                                @if ($i <= $fullStars)
                                                    <i class="ri-star-fill text-yellow-400"></i>
                                                @elseif ($i == $fullStars + 1 && $halfStar)
                                                    <i class="ri-star-half-fill text-yellow-400"></i>
                                                @else
                                                    <i class="ri-star-line text-gray-300"></i>
                                                @endif
                                            @endfor
                                        </div>
                                        <div class="text-sm text-gray-500">
                                            Based on {{ $product->reviews->count() }} {{ Str::plural('review', $product->reviews->count()) }}
                                        </div>
                                    </div>
                                </div>

                                <div class="md:w-2/3">
                                    <div class="space-y-2">
                                        @foreach ([5, 4, 3, 2, 1] as $star)
                                            @php
                                                $count = $product->reviews->where('rating', $star)->count();
                                                $percentage = $product->reviews->count() > 0 
                                                    ? ($count / $product->reviews->count()) * 100 : 0;
                                            @endphp
                                            <div class="flex items-center">
                                                <div class="w-12 text-sm text-gray-600">{{ $star }} star</div>
                                                <div class="flex-1 mx-4 h-2 bg-gray-200 rounded-full overflow-hidden">
                                                    <div class="h-full bg-yellow-400" style="width: {{ $percentage }}%"></div>
                                                </div>
                                                <div class="w-12 text-sm text-gray-600 text-right">{{ $count }}</div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>

                            <!-- Review List -->
                            <div class="space-y-6">
                                @foreach ($product->reviews->sortByDesc('created_at') as $review)
                                    <div class="review border-b border-gray-200 pb-6" id="review-{{ $review->id }}">
                                        <div class="flex justify-between mb-2">
                                            <div class="flex items-center">
                                                <div class="w-10 h-10 rounded-full bg-primary-100 text-primary flex items-center justify-center font-medium mr-3">
                                                    {{ strtoupper(substr($review->user ? $review->user->name : 'A', 0, 1)) }}
                                                </div>
                                                <div>
                                                    <div class="font-medium">{{ $review->user ? $review->user->name : 'Anonymous' }}</div>
                                                    <div class="text-xs text-gray-500">
                                                        {{ $review->created_at ? $review->created_at->format('M d, Y') : 'N/A' }}
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="stars flex">
                                                @for ($i = 1; $i <= 5; $i++)
                                                    @if ($i <= $review->rating)
                                                        <i class="ri-star-fill text-yellow-400"></i>
                                                    @else
                                                        <i class="ri-star-line text-gray-300"></i>
                                                    @endif
                                                @endfor
                                            </div>
                                        </div>
                                        <div class="text-gray-600 text-sm">
                                            {{ $review->comment }}
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <div class="text-center">
                            <a href="#write-review" class="inline-block bg-primary text-white py-2 px-6 rounded-lg hover:bg-primary-dark transition">
                                Write a Review
                            </a>
                        </div>
                    @else
                        <div class="text-center py-8">
                            <div class="text-gray-400 mb-4">
                                <i class="ri-chat-1-line text-6xl"></i>
                            </div>
                            <h3 class="text-xl font-medium text-gray-900 mb-2">No reviews yet</h3>
                            <p class="text-gray-500 mb-6">Be the first to review this product</p>
                            <a href="#write-review" class="inline-block bg-primary text-white py-2 px-6 rounded-lg hover:bg-primary-dark transition">
                                Write a Review
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Related Products -->
        @if(isset($relatedProducts) && $relatedProducts->count() > 0)
            <div class="related-products mb-16">
                <h2 class="text-xl font-medium text-gray-900 mb-6">Related Products</h2>
                
                <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-4 md:gap-6">
                    @foreach ($relatedProducts as $relatedProduct)
                        <div class="product-card bg-white rounded-lg border border-gray-200 overflow-hidden transition-all hover:shadow-md flex flex-col"
                            data-product-id="{{ $relatedProduct->id }}">
                            <div class="product-image-container aspect-square bg-gray-100 overflow-hidden relative">
                                <a href="{{ route('products.show', $relatedProduct->slug) }}" class="block">
                                    @if (isset($relatedProduct->images) && $relatedProduct->images->count() > 0)
                                        <img src="{{ asset('storage/' . $relatedProduct->images->first()->path) }}"
                                            alt="{{ $relatedProduct->name }}"
                                            class="w-full h-full object-cover transition-transform duration-500 hover:scale-105">
                                    @else
                                        <img src="{{ asset('images/placeholder.jpg') }}" alt="{{ $relatedProduct->name }}"
                                            class="w-full h-full object-cover transition-transform duration-500 hover:scale-105">
                                    @endif
                                </a>

                                @if ($relatedProduct->sale_price)
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
                                        @if (isset($relatedProduct->category) && $relatedProduct->category)
                                            <div class="product-category text-xs text-gray-500">{{ $relatedProduct->category->name }}
                                            </div>
                                        @endif
                                    </div>

                                    <h3 class="product-title font-medium mb-2 text-gray-900 text-sm">
                                        <a href="{{ route('products.show', $relatedProduct->slug) }}">{{ $relatedProduct->name }}</a>
                                    </h3>
                                </div>

                                <div class="product-actions mt-auto">
                                    <div class="product-price">
                                        @if ($relatedProduct->sale_price)
                                            <span class="font-medium">{{ number_format($relatedProduct->sale_price, 2) }} €</span>
                                            <span
                                                class="text-gray-500 line-through text-xs ml-1">{{ number_format($relatedProduct->price, 2) }}
                                                €</span>
                                        @else
                                            <span class="font-medium">{{ number_format($relatedProduct->price, 2) }} €</span>
                                        @endif
                                    </div>

                                    <div class="product-buttons">
                                        <button
                                            class="add-to-cart-btn w-full mt-2 bg-primary text-white py-2 rounded-lg hover:bg-primary-dark transition flex items-center justify-center text-sm"
                                            data-product-id="{{ $relatedProduct->id }}">
                                            <i class="ri-shopping-bag-2-line mr-2"></i> Add to Cart
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
@endsection

    <style>
        /* Color selection */
        .color-option input:checked+span {
            outline: 2px solid var(--color-primary);
            outline-offset: 2px;
        }
        
        /* Size selection */
        .size-option input:checked+span {
            border-color: var(--color-primary);
            background-color: rgba(var(--color-primary-rgb), 0.1);
        }
        
        /* Quantity input arrows removal */
        input[type=number]::-webkit-inner-spin-button, 
        input[type=number]::-webkit-outer-spin-button { 
            -webkit-appearance: none; 
            margin: 0; 
        }
        
        /* Tab buttons */
        .tab-btn.active {
            color: var(--color-primary);
            border-color: var(--color-primary);
        }
        
        /* Main image zoom effect */
        #mainImage img {
            transition: transform 0.3s ease;
        }
        
        #mainImage:hover img {
            transform: scale(1.05);
        }
        
        /* Thumbnail active state */
        .thumbnail-item.active {
            ring: 2px;
            ring-color: var(--color-primary);
        }
    </style>

    <script src="{{ asset('js/product-detail.js') }}"></script>
                
