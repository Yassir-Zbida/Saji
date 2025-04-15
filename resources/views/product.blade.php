<!-- resources/views/product.blade.php -->
@extends('layouts.app')

@section('title', $product->name)

@section('styles')
<style>
    .product-detail {
        padding: 3rem 0;
    }
    
    .product-container {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 3rem;
    }
    
    @media (max-width: 768px) {
        .product-container {
            grid-template-columns: 1fr;
        }
    }
    
    .product-gallery {
        position: sticky;
        top: 2rem;
    }
    
    .product-main-image {
        width: 100%;
        height: auto;
        margin-bottom: 1rem;
    }
    
    .product-thumbnails {
        display: flex;
        gap: 0.5rem;
    }
    
    .product-thumbnail {
        width: 80px;
        height: 80px;
        object-fit: cover;
        cursor: pointer;
        border: 1px solid var(--gray-300);
        transition: border-color 0.3s ease;
    }
    
    .product-thumbnail.active {
        border-color: var(--black);
    }
    
    .product-info {
        padding-top: 1rem;
    }
    
    .product-title {
        font-size: 2rem;
        font-weight: 700;
        margin-bottom: 1rem;
    }
    
    .product-price {
        font-size: 1.5rem;
        font-weight: 600;
        margin-bottom: 1.5rem;
    }
    
    .product-description {
        margin-bottom: 2rem;
        color: var(--gray-600);
    }
    
    .product-meta {
        margin-bottom: 2rem;
    }
    
    .product-meta-item {
        display: flex;
        margin-bottom: 0.5rem;
    }
    
    .product-meta-label {
        width: 100px;
        font-weight: 500;
    }
    
    .product-meta-value {
        color: var(--gray-600);
    }
    
    .product-colors {
        margin-bottom: 2rem;
    }
    
    .product-colors-title {
        font-size: 1rem;
        font-weight: 500;
        margin-bottom: 0.5rem;
    }
    
    .color-options {
        display: flex;
        gap: 0.5rem;
    }
    
    .color-option {
        width: 2rem;
        height: 2rem;
        border-radius: 50%;
        cursor: pointer;
        border: 1px solid var(--gray-300);
    }
    
    .color-option.active {
        outline: 2px solid var(--black);
        outline-offset: 2px;
    }
    
    .product-quantity {
        margin-bottom: 2rem;
    }
    
    .quantity-title {
        font-size: 1rem;
        font-weight: 500;
        margin-bottom: 0.5rem;
    }
    
    .quantity-input {
        display: flex;
        align-items: center;
        width: fit-content;
        border: 1px solid var(--gray-300);
    }
    
    .quantity-btn {
        width: 2.5rem;
        height: 2.5rem;
        display: flex;
        align-items: center;
        justify-content: center;
        background: transparent;
        border: none;
        cursor: pointer;
    }
    
    .quantity-value {
        width: 3rem;
        height: 2.5rem;
        text-align: center;
        border: none;
        border-left: 1px solid var(--gray-300);
        border-right: 1px solid var(--gray-300);
    }
    
    .product-actions {
        display: flex;
        gap: 1rem;
        margin-bottom: 2rem;
    }
    
    .product-tabs {
        margin-top: 4rem;
        border-top: 1px solid var(--gray-200);
        padding-top: 2rem;
    }
    
    .tabs-nav {
        display: flex;
        border-bottom: 1px solid var(--gray-200);
        margin-bottom: 2rem;
    }
    
    .tab-btn {
        padding: 1rem 1.5rem;
        font-size: 1rem;
        font-weight: 500;
        background: transparent;
        border: none;
        border-bottom: 2px solid transparent;
        cursor: pointer;
        transition: all 0.3s ease;
    }
    
    .tab-btn.active {
        border-bottom-color: var(--black);
    }
    
    .tab-content {
        display: none;
    }
    
    .tab-content.active {
        display: block;
    }
    
    .related-products {
        padding: 4rem 0;
        background-color: var(--gray-100);
    }
    
    .section-title {
        font-size: 2rem;
        font-weight: 700;
        margin-bottom: 2rem;
        text-align: center;
    }
    
    .products-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 2rem;
    }
    
    @media (max-width: 992px) {
        .products-grid {
            grid-template-columns: repeat(3, 1fr);
        }
    }
    
    @media (max-width: 768px) {
        .products-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }
    
    @media (max-width: 480px) {
        .products-grid {
            grid-template-columns: 1fr;
        }
    }
</style>
@endsection

@section('content')
<section class="product-detail">
    <div class="container">
        <div class="product-container">
            <div class="product-gallery">
                <img src="{{ asset($product->image) }}" alt="{{ $product->name }}" class="product-main-image" id="main-image">
                
                <div class="product-thumbnails">
                    @foreach($product->images as $index => $image)
                    <img src="{{ asset($image) }}" alt="{{ $product->name }}" class="product-thumbnail {{ $index === 0 ? 'active' : '' }}" onclick="changeImage('{{ asset($image) }}', this)">
                    @endforeach
                </div>
            </div>
            
            <div class="product-info">
                <h1 class="product-title">{{ $product->name }}</h1>
                <p class="product-price">${{ number_format($product->price, 2) }}</p>
                
                <div class="product-description">
                    <p>{{ $product->description }}</p>
                </div>
                
                <div class="product-meta">
                    <div class="product-meta-item">
                        <span class="product-meta-label">SKU:</span>
                        <span class="product-meta-value">{{ $product->sku }}</span>
                    </div>
                    <div class="product-meta-item">
                        <span class="product-meta-label">Category:</span>
                        <span class="product-meta-value">{{ $product->category->name }}</span>
                    </div>
                    <div class="product-meta-item">
                        <span class="product-meta-label">Material:</span>
                        <span class="product-meta-value">{{ $product->material }}</span>
                    </div>
                    <div class="product-meta-item">
                        <span class="product-meta-label">Dimensions:</span>
                        <span class="product-meta-value">{{ $product->dimensions }}</span>
                    </div>
                </div>
                
                <div class="product-colors">
                    <h3 class="product-colors-title">Color</h3>
                    <div class="color-options">
                        @foreach($product->colors as $color)
                        <div class="color-option {{ $loop->first ? 'active' : '' }}" style="background-color: {{ $color->hex_code }};" title="{{ $color->name }}"></div>
                        @endforeach
                    </div>
                </div>
                
                <div class="product-quantity">
                    <h3 class="quantity-title">Quantity</h3>
                    <div class="quantity-input">
                        <button class="quantity-btn" id="decrease-quantity">
                            <i class="ri-subtract-line"></i>
                        </button>
                        <input type="number" class="quantity-value" id="quantity" name="quantity" value="1" min="1" max="{{ $product->stock }}">
                        <button class="quantity-btn" id="increase-quantity">
                            <i class="ri-add-line"></i>
                        </button>
                    </div>
                </div>
                
                <div class="product-actions">
                    <button class="btn btn-primary" style="flex: 1;">Add to Cart</button>
                    <button class="btn" aria-label="Add to wishlist">
                        <i class="ri-heart-line"></i>
                    </button>
                </div>
                
                <div class="product-tabs">
                    <div class="tabs-nav">
                        <button class="tab-btn active" data-tab="description">Description</button>
                        <button class="tab-btn" data-tab="details">Additional Information</button>
                        <button class="tab-btn" data-tab="reviews">Reviews ({{ count($product->reviews) }})</button>
                    </div>
                    
                    <div class="tab-content active" id="description">
                        <p>{{ $product->description }}</p>
                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam auctor, nisl eget ultricies tincidunt, nisl nisl aliquam nisl, eget ultricies nisl nisl eget nisl. Nullam auctor, nisl eget ultricies tincidunt, nisl nisl aliquam nisl, eget ultricies nisl nisl eget nisl.</p>
                    </div>
                    
                    <div class="tab-content" id="details">
                        <table style="width: 100%; border-collapse: collapse;">
                            <tr>
                                <th style="text-align: left; padding: 0.75rem 0; border-bottom: 1px solid var(--gray-200);">Dimensions</th>
                                <td style="text-align: left; padding: 0.75rem 0; border-bottom: 1px solid var(--gray-200);">{{ $product->dimensions }}</td>
                            </tr>
                            <tr>
                                <th style="text-align: left; padding: 0.75rem 0; border-bottom: 1px solid var(--gray-200);">Material</th>
                                <td style="text-align: left; padding: 0.75rem 0; border-bottom: 1px solid var(--gray-200);">{{ $product->material }}</td>
                            </tr>
                            <tr>
                                <th style="text-align: left; padding: 0.75rem 0; border-bottom: 1px solid var(--gray-200);">Weight</th>
                                <td style="text-align: left; padding: 0.75rem 0; border-bottom: 1px solid var(--gray-200);">{{ $product->weight }} kg</td>
                            </tr>
                            <tr>
                                <th style="text-align: left; padding: 0.75rem 0; border-bottom: 1px solid var(--gray-200);">Assembly</th>
                                <td style="text-align: left; padding: 0.75rem 0; border-bottom: 1px solid var(--gray-200);">Required</td>
                            </tr>
                            <tr>
                                <th style="text-align: left; padding: 0.75rem 0; border-bottom: 1px solid var(--gray-200);">Care Instructions</th>
                                <td style="text-align: left; padding: 0.75rem 0; border-bottom: 1px solid var(--gray-200);">Wipe clean with a damp cloth</td>
                            </tr>
                        </table>
                    </div>
                    
                    <div class="tab-content" id="reviews">
                        @if(count($product->reviews) > 0)
                            @foreach($product->reviews as $review)
                            <div style="margin-bottom: 2rem; padding-bottom: 2rem; border-bottom: 1px solid var(--gray-200);">
                                <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
                                    <h4 style="font-weight: 600;">{{ $review->user->name }}</h4>
                                    <span style="color: var(--gray-500);">{{ $review->created_at->format('M d, Y') }}</span>
                                </div>
                                <div style="display: flex; margin-bottom: 0.5rem;">
                                    @for($i = 1; $i <= 5; $i++)
                                        @if($i <= $review->rating)
                                            <i class="ri-star-fill" style="color: #FFD700;"></i>
                                        @else
                                            <i class="ri-star-line" style="color: #FFD700;"></i>
                                        @endif
                                    @endfor
                                </div>
                                <p>{{ $review->comment }}</p>
                            </div>
                            @endforeach
                        @else
                            <p>There are no reviews yet.</p>
                        @endif
                        
                        @auth
                            <h3 style="font-size: 1.25rem; font-weight: 600; margin: 2rem 0 1rem;">Write a Review</h3>
                            <form action="{{ route('reviews.store') }}" method="POST">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                                
                                <div style="margin-bottom: 1rem;">
                                    <label style="display: block; margin-bottom: 0.5rem;">Rating</label>
                                    <div style="display: flex;">
                                        @for($i = 1; $i <= 5; $i++)
                                            <input type="radio" id="rating-{{ $i }}" name="rating" value="{{ $i }}" style="display: none;">
                                            <label for="rating-{{ $i }}" class="rating-star" data-rating="{{ $i }}">
                                                <i class="ri-star-line" style="font-size: 1.5rem; color: #FFD700; cursor: pointer;"></i>
                                            </label>
                                        @endfor
                                    </div>
                                </div>
                                
                                <div style="margin-bottom: 1rem;">
                                    <label for="review-comment" style="display: block; margin-bottom: 0.5rem;">Your Review</label>
                                    <textarea id="review-comment" name="comment" rows="5" style="width: 100%; padding: 0.75rem; border: 1px solid var(--gray-300);"></textarea>
                                </div>
                                
                                <button type="submit" class="btn btn-primary">Submit Review</button>
                            </form>
                        @else
                            <p style="margin-top: 2rem;">Please <a href="{{ route('login') }}" style="text-decoration: underline;">log in</a> to write a review.</p>
                        @endauth
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="related-products">
    <div class="container">
        <h2 class="section-title">You May Also Like</h2>
        
        <div class="products-grid">
            @foreach($relatedProducts as $relatedProduct)
            <div class="product-card">
                <div class="product-image-container">
                    <img src="{{ asset($relatedProduct->image) }}" alt="{{ $relatedProduct->name }}" class="product-image">
                    <div class="product-actions">
                        <button class="product-action" aria-label="Add to wishlist">
                            <i class="ri-heart-line"></i>
                        </button>
                        <button class="product-action" aria-label="Quick view">
                            <i class="ri-eye-line"></i>
                        </button>
                    </div>
                </div>
                <div class="product-info">
                    <h3 class="product-title">{{ $relatedProduct->name }}</h3>
                    <p class="product-price">${{ number_format($relatedProduct->price, 2) }}</p>
                    <button class="btn btn-primary" style="width: 100%; margin-top: 1rem;">Add to Cart</button>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endsection

@section('scripts')
<script>
    // Image gallery